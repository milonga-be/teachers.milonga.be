<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class AgendaController extends Controller{

	/**
	 * List the events in the agenda
	 * @param  integer $weeks  the number of weeks to display
	 * @param  string  $filter a filter e.g. MILONGA: , PRACTICA: etc
	 * @return string
	 */
	public function actionList( $weeks = 4 , $filter = null ){

		$google_calendar_id = Yii::$app->params['google-calendar-id'];
		$google_api_key = Yii::$app->params['google-api-key'];

		$today = new \Datetime();
		$endPeriod = clone $today;
		$endPeriod->modify( ($weeks * 7) . ' days');

		$json_array = $this->getFromGoogleApi( 'https://www.googleapis.com/calendar/v3/calendars/' . $google_calendar_id . '/events?key=' . $google_api_key . '&orderBy=startTime&singleEvents=true&timeMin=' . urlencode( $today->format('c') ) . '&timeMax=' . urlencode( $endPeriod->format('c') )  );

		$events = $json_array['items'];
		if( !is_null($filter) ){
			$events = $this->filterEvents( $filter , $events );
		}

		return $this->render('list',['events' => $events ]);
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