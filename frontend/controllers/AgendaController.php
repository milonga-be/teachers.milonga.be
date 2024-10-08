<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use common\models\School;
use backend\models\Event;
use yii\helpers\ArrayHelper;
/**
 * Site controller
 */
class AgendaController extends Controller{

	// public $layout = 'embed';
	var $embedded = false;
	const DEFAULT_FILTER = 'milonga:,practica:,millonga:,concert:,show:,film:,practilonga:';
	// const DEFAULT_FILTER = 'online:';
	const ALL_FILTER = 'milonga:,practica:,millonga:,workshop:,concert:,show:,film:,practilonga:';
	const MILONGAS_FILTER = 'milonga:,practica:,millonga:,concert:,show:,film:,practilonga:';
	const WORKSHOPS_FILTER = 'workshop:';

	/**
	 * List the events in the agenda
	 * @param  integer $weeks  the number of weeks to display
	 * @param  string  $filter a filter e.g. MILONGA: , PRACTICA: etc
	 * @return string
	 */
	public function actionList( $weeks = 4 , $filter = null){

		$events = $this->getEvents( $weeks * 7 , $filter );
		$events = $this->filterEvents(self::ALL_FILTER, $events);
		$start = new \Datetime();
		if($start->format('w') != 1){
			$start->modify('previous monday');
		}
		$end = clone $start;
		$end->modify('4 weeks');

		return $this->render('list', [ 'events' => $events , 'start' => $start, 'weeks' => $weeks ]);
	}

	private function weeks_in_month($month, $year) {
		// Start of month
		$start = mktime(0, 0, 0, $month, 1, $year);
		// End of month
		$end = mktime(0, 0, 0, $month, date('t', $start), $year);
		// Start week
		$start_week = date('W', $start);
		// End week
		$end_week = date('W', $end);
		
		if ($end_week < $start_week) { // Month wraps
		  return ((52 + $end_week) - $start_week) + 1;
		}
		
		return ($end_week - $start_week) + 1;
	}

	/**
	 * Search events in the agenda
	 * @param  integer $weeks  the number of weeks to display
	 * @param  string  $q the string to search
	 * @return string
	 */
	public function actionSearch( $weeks = 8 , $q = null){
		// $search = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
		// $replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
		// $q = str_replace($search, $replace, $q);
		$events = $this->getEvents( $weeks * 7 , self::ALL_FILTER, null, $q);
		// $q_without_accents = str_replace($search, $replace, $q);
		// if($q != $q_without_accents){
		// 	$events_without_accents = $this->getEvents( $weeks * 7 , self::ALL_FILTER, null, $q_without_accents);
		// 	$events = array_replace($events, $events_without_accents);
		// 	// var_dump($events);
		// 	// die();
		// }
		$start = new \Datetime();
		if($start->format('w') != 1){
			$start->modify('previous monday');
		}
		$end = clone $start;
		$end->modify('4 weeks');

		return $this->render('search', [ 'events' => $events , 'start' => $start, 'weeks' => $weeks ]);
	}

	/**
	 * List the events in the agenda with a calendar
	 * @param  integer $weeks  the number of weeks to display
	 * @param  string  $filter a filter e.g. MILONGA: , PRACTICA: etc
	 * @param  string  $location a city or region to filter
	 * @return string
	 */
	public function actionCalendar( $month = null, $year = null, $selected = null, $filter = null, $city = null){
		if(!$month){
			$month = date('m');
			$year = date('Y');
		}
		$start = new \Datetime($year.'-'.$month.'-01');
		$month_first_day = clone $start;
		if($start->format('w') != 1){
			$start->modify('previous monday');
		}
		if(!empty($selected)){
			$selected_day = new \Datetime($selected);
		}else if($month == date('m') && $year == date('Y')){
			$selected_day = new \Datetime();
		}else{
			$selected_day = clone $month_first_day;
		}
		$end = clone $start;
		$end->modify('4 weeks');
		$weeks = $this->weeks_in_month($month, $year);
		$events_sets = array();
		$events = $this->getEvents( $weeks * 7, $filter, $start, null, $city);
		// if(isset($_GET['debug'])){
		// 	var_dump($events);
		// 	die();
		// }
		$events_by_date = array();
		foreach ($events as $event) {
			if(isset($event['start']['date'])){
				$date = substr($event['start']['date'], 0, 4).substr($event['start']['date'], 5, 2).substr($event['start']['date'], 8, 2);
			}else{
				$date = (new \Datetime($event['start']['dateTime']))->format('Ymd');
			}
			
			$events_by_date[$date][] = $event;
		}
		foreach ($events_by_date as $date => $date_events) {
			$events_by_date[$date] = array();
			$milongas = $this->filterEvents(self::DEFAULT_FILTER, $date_events);
			if(sizeof($milongas)){
				$events_by_date[$date]['Milongas'] = $milongas;
			}
			$workshops = $this->filterEvents('workshop:,workhop:', $date_events);
			if(sizeof($workshops))
				$events_by_date[$date]['Workshops'] = $workshops;
			if(sizeof($milongas)){
				// Put the milongas outside of Belgium apart
				list($events_by_date[$date]['Milongas'], $events_by_date[$date]['Outside Belgium']) = $this->filterOutsideEvents($events_by_date[$date]['Milongas']);
				if(sizeof($events_by_date[$date]['Outside Belgium']) == 0){
					unset($events_by_date[$date]['Outside Belgium']);
				}
			}
		}

		// Adding festivals in their own special categories
		// There must be an instance on all days of the festival
		$festivals = $this->filterEvents('FESTIVAL:,MARATHON:', $events);
		foreach($festivals as $event){
			if(isset($event['start']['dateTime'])){
				$start_datetime = new \Datetime($event['start']['dateTime']);
				$end_datetime = new \Datetime($event['end']['dateTime']);
				while($start_datetime <= $end_datetime){
					$date = $start_datetime->format('Ymd');
					if(isset($events_by_date[$date]['Festivals']))
						array_unshift($events_by_date[$date]['Festivals'], $event);
					else
						$events_by_date[$date]['Festivals/Marathons'][] = $event;
					$start_datetime->modify('+1 day');
				}
			}
		}

		// Adding the holidays in the workshops
		// There must be an instance on all days of the holidays
		$holidays = $this->filterEvents('HOLIDAYS:', $events);
		foreach($holidays as $event){
			if(isset($event['start']['dateTime'])){
				$start_datetime = new \Datetime($event['start']['dateTime']);
				$end_datetime = new \Datetime($event['end']['dateTime']);
				while($start_datetime <= $end_datetime){
					$date = $start_datetime->format('Ymd');
					if(isset($events_by_date[$date]['Workshops']))
						array_unshift($events_by_date[$date]['Workshops'], $event);
					else
						$events_by_date[$date]['Workshops'][] = $event;
					$start_datetime->modify('+1 day');
				}
			}
		}


		return $this->render('calendar', [ 'events_by_date' => $events_by_date , 'start' => $start, 'month_first_day' => $month_first_day , 'weeks' => $weeks, 'selected_day' => $selected_day, 'city' => $city]);
	}

	/**
	 * XML RSS for the newsletter sent by Mailchimp
	 * @return string
	 */
	public function actionNewsletterRss($category = '', $debug = false){
		$response = Yii::$app->getResponse();
    	$headers = $response->getHeaders();

    	if(!$debug)
    		$headers->set('Content-Type', 'application/rss+xml; charset=utf-8');

    	$startDate = new \Datetime();
    	if( $startDate->format('w') != 5 ){
    		$startDate->modify('next friday');
    	}
    	if($category == 'milongas')
    		$events = $this->getEvents( 9 , self::MILONGAS_FILTER, $startDate );
    	else if($category == 'workshops')
    		$events = $this->getEvents( 9 , self::WORKSHOPS_FILTER, $startDate );
    	else
    		$events = $this->getEvents( 9 , self::ALL_FILTER, $startDate );

    	return $this->renderPartial(
    		'newsletter-rss',
    		[ 
    			'events' => $events,
    			'debug' => $debug,
    		]);
	}

	/**
	 * Get the last pictures in the Flickr Group
	 * @param  integer $count the number of photos to retrieve
	 * @return array
	 */
	private function getPictures( $count ){
		$flickr_group_id = Yii::$app->params['flickr-group-id'];
		$flickr_api_key = Yii::$app->params['flickr-api-key'];

		$flickr_url = 'https://api.flickr.com/services/rest/?method=flickr.groups.pools.getPhotos&api_key=' . urlencode( $flickr_api_key ) . '&group_id=' . urlencode($flickr_group_id) . '&per_page=' . urlencode($count) . '&format=json&nojsoncallback=1&extras=url_q';
		$json_array = $this->getFromApi( $flickr_url );

		if( isset($json_array['photos']) && isset($json_array['photos']['photo']) ){
			if( isset($_GET['debug']) ){
				var_dump($json_array['photos']['photo']);
				die();
			}
			return $json_array['photos']['photo'];
		}

		return array();
	}

	/**
	 * Get the picture attached to the event via Google Drive 
	 * @param  string $fileId [description]
	 * @return mixed
	 */
	public function actionEventPicture($fileId){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://www.googleapis.com/drive/v2/files/".$fileId."?alt=media");
		$headers = [
			'Authorization: Bearer '.Yii::$app->params['google-api-key']
		];
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION , true);
		$output = curl_exec ($ch);
		curl_close ($ch);

		echo $output;
		die();
	}

	/**
	 * Returns a list of events, gathering them from Google
	 * @param  integer $days  the number of days to list
	 * @param  string $filter a comma separated list of values that must be in the event title
	 * @return array         the events
	 */
	private function getEvents( $days , $filter = null , $startDate = null, $q = null, $city = null, $sponsored = false ){
		$events = array();
		$google_calendar_id = Yii::$app->params['google-calendar-id'];
		$google_api_key = Yii::$app->params['google-api-key'];

		if( is_null($startDate) )
			$startDate = new \Datetime();
		$endPeriod = clone $startDate;
		if( $days < 84 )
			$movedays = $days;
		else{
			$movedays = 84;
		}
		$endPeriod->modify( ($movedays) . ' days');
		$format = 'Y-m-d';
		do{

			$google_url = 'https://www.googleapis.com/calendar/v3/calendars/' . urlencode($google_calendar_id) . '/events?key=' . urlencode($google_api_key) . '&maxResults=2500&orderBy=startTime&singleEvents=true&timeMin=' . urlencode( $startDate->format($format)  . 'T07:00:00+00:00') . '&timeMax=' . urlencode( $endPeriod->format($format) . 'T23:59:59+00:00' );
			if(isset($q)){
				$google_url.='&q='.urlencode($q);
			}
			if(isset($city) && !empty($city)){
				$google_url.='&sharedExtendedProperty=city%3D'.urlencode($city);
			}
			if(isset($sponsored) && $sponsored == true){
				$google_url.='&sharedExtendedProperty=sponsored%3D1';
			}
			$json_array = $this->getFromApi( $google_url );

			$collected_events = $json_array['items'];
			if( !is_null($filter) ){
				$collected_events = $this->filterEvents( $filter , $collected_events );
			}
			$events = array_merge( $events , $collected_events );

			$startDate = $endPeriod;
			$endPeriod = clone $startDate;
			if( $days < 84 )
				$movedays = $days;
			else{
				$movedays = 84;
			}
			$endPeriod->modify( ($movedays) . ' days');
			$days-= 84;
		}while( $days > 0 );

		return $events;
	}

	/**
	 * Filter events with a catetory, summary must contain a certain string
	 * @param  string $filter a string which must be in the title of the event
	 * @param  array $events the list of events to filter
	 * @return array         the events filtered
	 */
	private function filterEvents( $filter_string , $events ){
		$filters = explode(',' , $filter_string );

		$events_filtered = array();
		foreach ($events as $event) {
			if(isset($event['original']))
				$event_text = $event['original'];
			else
				$event_text = $event['summary'];
			$fits = FALSE;
			foreach( $filters as $filter ){
				$filter_without_semicolons = str_replace([' :',':'], '', $filter );

				if(stristr($event_text,$filter) || stristr(str_replace(' :',':',$event_text), $filter) || strtolower(substr(trim($event_text), 0, strlen($filter_without_semicolons))) === strtolower($filter_without_semicolons) ){
					// stripping the category from the title
					$prefixes = [ $filter , str_replace(':',' :',$filter) ];
					foreach($prefixes as $prefix){
						if (substr(strtolower($event_text), 0, strlen($prefix)) == strtolower($prefix)) {
    						// $event_text = substr($event_text, strlen($prefix));
    						// $event['category'] = str_replace(':','',strtolower($prefix));
    						break;
						}
					}
					// echo 'fits filter '.$filter.'<br>';
					// $event['summary'] = $event_text;
					$fits = TRUE;
					$analyse_summary = Event::filterType($event_text);
					$event['original'] = $event_text;
					$event['summary'] = $analyse_summary['summary'];
					$event['city'] = isset($analyse_summary['city'])?$analyse_summary['city']:'';
					$event['category'] = isset($analyse_summary['type'])?$analyse_summary['type']:'';
				}
			}

			// Adding some info to help
			if(!isset($event['start']['dateTime']) && isset($event['start']['date'])){
				$event['start']['dateTime'] = $event['start']['date'];
			}
			$datetime = new \Datetime($event['start']['dateTime']);
			$event['start']['weekday'] = $datetime->format('N');

			// Adding the picture
			// $creator_email = isset($event['creator']['email'])?$event['creator']['email']:null;
			$organizer_id = isset($event['extendedProperties']['shared']['organizer_id'])?$event['extendedProperties']['shared']['organizer_id']:null;
			
			if(!is_null($organizer_id) && !isset($event['school'])){
				
				$school = School::findOne($organizer_id);

				if($school){
					$event['email'] = $school->email;
					$event['school'] = array();
					$event['school']['picture'] = $school->getPictureUrl();
					$event['school']['thumb'] = $school->getThumbUrl();
					$event['school']['name'] = $school->name;
					if(isset($school->website))
						$event['school']['url'] = $school->website;
					else if(isset($school->facebook))
						$event['school']['url'] = $school->facebook;
					if($school->email){
						$event['email'] = $school->email;
					}
					if($school->active == false){
						$fits = FALSE;
					}
				}
			}
			if(isset($event['extendedProperties']['shared']['disabled']) && $event['extendedProperties']['shared']['disabled'] == 1){
				$fits = FALSE;
			}
			if( $fits == TRUE ){
				$events_filtered[$event['id']] = $event;
			}

		}
		return $events_filtered;
	}

	/**
	 * Filter the events that are outside of Belgium
	 * @param  array $events The array of events to filter
	 * @return array 	The array of events that are outside
	 */
	function filterOutsideEvents(&$events){
		$outsideEvents = array();
		$insideEvents = array();
		$outsideLocations = [\backend\models\Event::COUNTRY_FRANCE, \backend\models\Event::COUNTRY_GERMANY, \backend\models\Event::COUNTRY_LUXEMBURG, \backend\models\Event::COUNTRY_NETHERLANDS];
		foreach ($events as $key => $event) {
			$fits = true;
			if(isset($event['extendedProperties']['shared']['city']) && in_array($event['extendedProperties']['shared']['city'], $outsideLocations)){
				$fits = FALSE;
			}
			if( $fits == TRUE ){
				$insideEvents[$event['id']] = $event;
			}else{
				$outsideEvents[$event['id']] = $event;
			}
		}
		return [$insideEvents, $outsideEvents];
	}

	/**
	 * Make a call to an API
	 * @param  string $url the url to call
	 * @return array
	 */
	private function getFromApi( $url ){

		$content = file_get_contents( $url );
		if( $content ){
			return json_decode($content, true);
		}else{
			return false;
		}

	}

	public function getLatestPosts(){
		$url = 'http://www.milonga.be/feed/';
		$feed = new \SimplePie();
		$feed->enable_cache( false );
		$feed->set_feed_url($url);
		$feed->init();

		return $feed->get_items(0,3);
	}

	/**
	 * Send an alert to the organizers to alert them of the events in Milonga.be
	 */
	public function actionOrganizersAlert(){
		$startDate = new \Datetime();
		$startDate->modify('next friday');

		
		$events = $this->getEvents( 9 , self::ALL_FILTER, $startDate );
		$events_per_email = array();

    	foreach ($events as $event) {
    		// var_dump($event);
    		// Official creator of the event
    		if( isset($event['email']) ){
    			$email = $event['email'];
    			$emails[ $email ] = $email;
    			$events_per_email[$email][$event['id']] = $event;
    		}
    		
    		// Mailto in the event
    		if( isset($event['description']) ){
    			$event_html = $event['description'];
    			// preg_match_all("#mailto:([a-z0-9\.\-\_]*@[a-z0-9\.\_\-]*)#",$event_html,$matches1,PREG_SET_ORDER);
				preg_match_all('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i',$event_html,$matches,PREG_SET_ORDER);

				if($matches){
					foreach($matches as $match){
						$email = $match[0];
						if( strlen($email) > 5 ){
							$emails[$email] = $email;
							$events_per_email[$email][$event['id']] = $event;
						}
					}
				}
    		}
    		
    	}


    	$emails = array_filter( $emails , function($email){
    		$excludes = array( 'bverdeye@gmail.com' , 'peter.forret@gmail.com', 'backend@milongabe-177114.iam.gserviceaccount.com', 'milonga@milonga.be');
    		if( !in_array( $email, $excludes ) ){
    			return true;
    		}
    		return false;
    	} );

    	var_dump(array_values($emails));
    	// die();
    	foreach ($emails as $email) {
    		Yii::$app->mailer->compose('alert',[ 'events' => $events_per_email[$email]])
	            ->setFrom('milonga@milonga.be')
	            ->setBcc('milonga@milonga.be')
	        	->setTo($email)
	            ->setSubject('Milonga.be : please check your events')
	            ->send();
    	}
	}

	/**
	 * Compose a summary of the milongas for Facebook
	 * @return mixed
	 */
	public function actionFacebookMilongas(){
		$startDate = new \Datetime();
		$endDate = clone $startDate;
		$endDate->modify('next sunday');
		$events = $this->getEvents( 6 , 'milonga:,practica:,millonga:,concert:,show:,practilonga:' , $startDate );

		$events_by_days = array();

		foreach ($events as $event) {
			if( !(isset($event['extendedProperties']['shared']['cancelled']) && !empty($event['extendedProperties']['shared']['cancelled'])) ){
				$events_by_weekdays[substr($event['start']['dateTime'],0, 10)][] = $event;
			}
		}

		return $this->render('facebook-milongas', ['events' => $events_by_weekdays, 'startDate' => $startDate, 'endDate' => $endDate]);
	}

	/**
	 * List the events in the agenda for a Facebook picture
	 * @param  integer $weeks  the number of weeks to display
	 * @param  string  $filter a filter e.g. MILONGA: , PRACTICA: etc
	 * @return string
	 */
	public function actionFacebookPicture( $weeks = 1 , $filter = null){

		$events = $this->getEvents( $weeks * 7 , $filter );
		$start = new \Datetime();
		if($start->format('w') != 1){
			$start->modify('previous monday');
		}
		$end = clone $start;
		$end->modify('4 weeks');

		return $this->render('facebook-picture', [ 'events' => $events , 'start' => $start, 'weeks' => $weeks ]);
	}

	/**
	 * Compose a summary of the workshops for Facebook
	 * @return mixed
	 */
	public function actionFacebookWorkshops(){
		$startDate = new \Datetime();
		$endDate = clone $startDate;
		$endDate->modify('next sunday');
		$events = $this->getEvents( 6 , 'workshops:,workshop:' , $startDate );

		$events_by_days = array();

		foreach ($events as $event) {
			if( !(isset($event['extendedProperties']['shared']['cancelled']) && !empty($event['extendedProperties']['shared']['cancelled'])) )
				$events_by_weekdays[$event['start']['weekday']][] = $event;
		}

		return $this->render('facebook-workshops', ['events' => $events_by_weekdays, 'startDate' => $startDate, 'endDate' => $endDate]);
	}

	public function actionSpecialEvents(){
		$startDate = new \Datetime();
		$events = $this->getEvents( 4*7*7 , 'festival:,holidays:,marathon:', $startDate );
		// var_dump($events);
		// die();

		return $this->render('special-events', [ 'events' => $events ]);
	}

	public function actionSponsoredEvents(){
		$startDate = new \Datetime();
		$events = $this->getEvents( 48*7 , self::ALL_FILTER.',festival:,holidays:,marathon:', $startDate, null, null, true );
		// var_dump($events);
		// die();

		return $this->render('special-events', [ 'events' => $events ]);
	}

	public function actionSponsoredEventsWidget(){
		$startDate = new \Datetime();
		$events = $this->getEvents( 48*7 , self::ALL_FILTER.',festival:,holidays:,marathon:', $startDate, null, null, true );

		return $this->render('sponsored-events-widget', [ 'events' => $events ]);
	}

	public function actionSpecialEvent($id){
		$event = \backend\models\Event::findOne($id);
		return $this->render('special-event', ['event' => $event]);
	}

	/**
	 * Milongas for the app
	 * @param  [type]  $month [description]
	 * @param  [type]  $year  [description]
	 * @param  boolean $all   [description]
	 * @return [type]         [description]
	 */
	public function actionApiMilongas($month = null, $year = null, $all = false){
		return $this->_apiEvents(self::DEFAULT_FILTER, $month, $year, $all);
	}

	/**
	 * Workshops for the app
	 * @param  [type]  $month [description]
	 * @param  [type]  $year  [description]
	 * @param  boolean $all   [description]
	 * @return [type]         [description]
	 */
	public function actionApiWorkshops($month = null, $year = null, $all = false){
		return $this->_apiEvents(self::WORKSHOPS_FILTER, $month, $year, $all);
	}

	/**
	 * All events for the app
	 * @param  string  $month [description]
	 * @param  string  $year  [description]
	 * @param  boolean $all   [description]
	 * @return [type]         [description]
	 */
	public function actionApi($day = null, $month = null, $year = null, $all = false){
		return $this->_apiEvents(self::ALL_FILTER, $day, $month, $year, $all);
	}

	public function _apiEvents($filter, $day = null, $month = null, $year = null, $all = false){
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		// Checking that there is a api-key in the headers
		if(Yii::$app->request->headers->get('Api-Key') != Yii::$app->params['app-api-key']){
			return [];
		}
		
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST');

		if(!$month){
			$month = date('m');
			$year = date('Y');
			$day = date('d');
		}
		$start = new \Datetime($year.'-'.$month.'-'.$day);
		$events = $this->getEvents( 0, null, $start, null, null);
		$events = array_values($this->filterEvents($filter, $events));
		if($all)
			return $events;
		return [
			'items' => array_map(
				function(&$item){ 
					$item['description'] = \common\components\Htmlizer::execute($item); 
					$item['picture'] = isset($item['extendedProperties']['shared']['picture']) && !empty($item['extendedProperties']['shared']['picture'])?'https://'.\Yii::$app->getRequest()->serverName.'/uploads/events/'.$item['extendedProperties']['shared']['picture']:''; 
					return ArrayHelper::filter($item, ['id', 'summary', 'category', 'city', 'school', 'description', 'start', 'end', 'location', 'picture']);
				}, 
			$events)
		];
	}

	/**
	 * Returns a list of cities
	 * @return mixed
	 */
	public function actionApiCities(){
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST');

		return array_keys(\backend\models\Event::getCities());
	}
}