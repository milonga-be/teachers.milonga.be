<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class AgendaController extends Controller{

	// public $layout = 'embed';

	/**
	 * List the events in the agenda
	 * @param  integer $weeks  the number of weeks to display
	 * @param  string  $filter a filter e.g. MILONGA: , PRACTICA: etc
	 * @return string
	 */
	public function actionList( $weeks = 4 , $filter = null ){

		$events = $this->getEvents( $weeks , $filter );

		return $this->render('list',[ 'events' => $events ]);
	}

	/**
	 * Returns a list of events, gathering them from Google
	 * @param  integer $weeks  the number of weeks to list
	 * @param  string $filter a comma separated list of values that must be in the event title
	 * @return array         the events
	 */
	private function getEvents( $weeks , $filter ){
		$events = array();
		$google_calendar_id = Yii::$app->params['google-calendar-id'];
		$google_api_key = Yii::$app->params['google-api-key'];

		$startDate = new \Datetime();
		$endPeriod = clone $startDate;
		if( $weeks < 12 )
				$moveweeks = $weeks;
			else{
				$moveweeks = 12;
			}
		$endPeriod->modify( ($moveweeks * 7) . ' days');
		$format = 'Y-m-d';
		do{

			$google_url = 'https://www.googleapis.com/calendar/v3/calendars/' . $google_calendar_id . '/events?key=' . $google_api_key . '&orderBy=startTime&singleEvents=true&timeMin=' . urlencode( $startDate->format($format)  . 'T07:00:00+00:00') . '&timeMax=' . urlencode( $endPeriod->format($format) . 'T23:59:59+00:00' );
			$json_array = $this->getFromGoogleApi( $google_url );

			$collected_events = $json_array['items'];
			if( !is_null($filter) ){
				$collected_events = $this->filterEvents( $filter , $collected_events );
			}
			$events = array_merge( $events , $collected_events );

			$startDate = $endPeriod;
			$endPeriod = clone $startDate;
			if( $weeks < 12 )
				$moveweeks = $weeks;
			else{
				$moveweeks = 12;
			}
			$endPeriod->modify( ($moveweeks * 7) . ' days');
			$weeks-= 12;
		}while( $weeks > 0 );

		return $events;
	}

	/**
	 * [filterEvents description]
	 * @param  string $filter a string which must be in the title of the event
	 * @param  array $events the list of events to filter
	 * @return array         the events filtered
	 */
	private function filterEvents( $filter_string , $events ){
		$filters = explode(',' , $filter_string );

		$events_filtered = array();
		foreach ($events as $event) {
			$event_text = $event['summary'];

			foreach( $filters as $filter ){
				$filter_without_semicolons = str_replace([' :',':'], '', $filter );

				if(stristr($event_text,$filter) || stristr(str_replace(' :',':',$event_text), $filter) || strtolower(substr($event_text, 0, strlen($filter_without_semicolons))) === strtolower($filter_without_semicolons) ){
					// stripping the category from the title
					$prefixes = [ $filter , str_replace(':',' :',$filter) ];
					foreach($prefixes as $prefix){
						if (substr(strtolower($event_text), 0, strlen($prefix)) == strtolower($prefix)) {
    						$event_text = substr($event_text, strlen($prefix));
    						break;
						}
					}
					$event['summary'] = $event_text;
					$events_filtered[] = $event;
				}
			}
		}
		return $events_filtered;
	}

	/**
	 * Make a call to the Google API
	 * @param  string $url the url to call
	 * @return array
	 */
	private function getFromGoogleApi( $url ){

		$content = file_get_contents( $url );
		if( $content ){
			return json_decode($content, true);
		}else{
			return false;
		}

	}
}