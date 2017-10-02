<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;

/**
 * LessonsSearch represents the model behind the search form about `common\models\Lesson`.
 */
class EventSearch extends Event
{

	public function search( $params )
    {
    	$user = Yii::$app->user->identity;
    	$events = $this->getEvents();

    	return new ArrayDataProvider(['allModels' => $events]);
    }

    /**
	 * Returns a list of events, gathering them from Google
	 * @param  integer $days  the number of days to list
	 * @param  string $filter a comma separated list of values that must be in the event title
	 * @return array         the events
	 */
	private function getEvents(){
		$events = array();
		$google_calendar_id = Yii::$app->params['google-calendar-id'];
		$emails = [\Yii::$app->user->identity->email];
		$google_emails = explode(',', \Yii::$app->user->identity->google_emails);
		$emails = array_merge($emails, $google_emails);

		$startDate = new \Datetime();
		$format = 'Y-m-d';

		$google_url = 'https://www.googleapis.com/calendar/v3/calendars/' . urlencode($google_calendar_id) . '/events?orderBy=startTime&singleEvents=true&timeMin=' . urlencode( $startDate->format($format)  . 'T07:00:00+00:00');
		$json_array = self::getFromApi( $google_url );

		foreach ($json_array['items'] as $event) {
			if( in_array($event['creator']['email'], $emails) ){
				$events[] = $event;
			}
		}

		return $events;
	}
}