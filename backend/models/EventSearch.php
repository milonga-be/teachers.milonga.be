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

    	return new ArrayDataProvider([
    		'allModels' => $events,
    		'pagination' => [
		        'pageSize' => 20,
		    ],
    	]);
    }

    /**
	 * Returns a list of events, gathering them from Google
	 * @param  integer $days  the number of days to list
	 * @param  string $filter a comma separated list of values that must be in the event title
	 * @return array         the events
	 */
	private function getEvents(){
		$google_calendar_id = Yii::$app->params['google-calendar-id'];
		putenv('GOOGLE_APPLICATION_CREDENTIALS='.Yii::$app->params['google_json']);
		$client = new \Google_Client();
		$client->useApplicationDefaultCredentials();
		$client->addScope(\Google_Service_Calendar::CALENDAR);
		$service = new \Google_Service_Calendar($client);
		$optParams = array(
		  'maxResults' => 1000,
		  'orderBy' => 'startTime',
		  'singleEvents' => TRUE,
		  'timeMin' => date('c'/*, gmmktime(9,0,0,3,14,2020)*/),
		  // 'sharedExtendedProperty' => 'organizer_id=6'
		  // 'timeMax' => date('c', gmmktime(9,0,0,6,14,2020)),
		);
		$user = Yii::$app->user->identity;
		if(!$user->isAdmin() && $user->school){
			$optParams['sharedExtendedProperty'] = 'organizer_id='.$user->school->id;
		}
		$results = $service->events->listEvents($google_calendar_id, $optParams);
		return /*$this->filterEvents(*/$results->items/*)*/;
	}

	/**
	 * Filter the events to keep only the one of the user himself
	 * @param  array  $items The events
	 * @return array
	 */
	// private function filterEvents(array $events){
	// 	// var_dump($events);
	// 	$filtered_events = array();
	// 	$authorized_emails = array();
	// 	$user = Yii::$app->user->identity;
	// 	if($user->school && $user->school->email){
	// 		$authorized_emails[] = $user->school->email;
	// 	}
	// 	foreach ($user->school->users as $authorized_user) {
	// 		$authorized_emails[] = $authorized_user->email;
	// 	}

	// 	foreach ($events as $event) {
	// 		if(in_array($event->creator->email, $authorized_emails) || (isset($event->getExtendedProperties()->shared['organizer']) && in_array($event->getExtendedProperties()->shared['organizer'], $authorized_emails)) || $user->isAdmin()){
	// 			// if(substr($event->summary, 0, 7) == 'ONLINE:')
	// 				$filtered_events[] = $event;
	// 		}
	// 	}
	// 	return $filtered_events;
	// }
}