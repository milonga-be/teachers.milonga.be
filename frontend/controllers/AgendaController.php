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
	public function actionList( $weeks = 4 , $filter = null){

		$events = $this->getEvents( $weeks * 7 , $filter );
		$start = new \Datetime();
		if($start->format('w') != 1){
			$start->modify('previous monday');
		}
		$end = clone $start;
		$end->modify('4 weeks');

		return $this->render('list', [ 'events' => $events , 'start' => $start, 'weeks' => $weeks ]);
	}

	/**
	 * List the events in the agenda with a calendar
	 * @param  integer $weeks  the number of weeks to display
	 * @param  string  $filter a filter e.g. MILONGA: , PRACTICA: etc
	 * @return string
	 */
	public function actionCalendar( $month = null, $year = null/*, $filter = null*/){
		if(!$month){
			$month = date('m');
			$year = date('Y');
		}
		$start = new \Datetime($year.'-'.$month.'-01');
		$month_first_day = clone $start;
		if($start->format('w') != 1){
			$start->modify('previous monday');
		}
		if($month == date('m') && $year == date('Y')){
			$selected_day = new \Datetime();
		}else{
			$selected_day = clone $month_first_day;
		}
		$end = clone $start;
		$end->modify('4 weeks');
		$weeks = 5;
		$events_sets = array();
		$events = $this->getEvents( $weeks * 7, null, $start);
		$events_by_date = array();
		foreach ($events as $event) {
			if(isset($event['start']['date'])){
				$date = substr($event['start']['date'], 0, 4).substr($event['start']['date'], 5, 2).substr($event['start']['date'], 8, 2);
			}else{
				$date = (new \Datetime($event['start']['dateTime']))->format('Ymd');
			}
			$events_by_date[$date][] = $event;
		}
		foreach ($events_by_date as $date => $events) {
			$events_by_date[$date] = array();
			$milongas = $this->filterEvents('milonga:,practica:,millonga:,concert:,show:,festival:', $events);
			if(sizeof($milongas))
				$events_by_date[$date]['Milongas'] = $milongas;
			$workshops = $this->filterEvents('workshop:,workhop:', $events);
			if(sizeof($workshops))
				$events_by_date[$date]['Workshops'] = $workshops;
		}

		return $this->render('calendar', [ 'events_by_date' => $events_by_date , 'start' => $start, 'month_first_day' => $month_first_day , 'weeks' => $weeks, 'selected_day' => $selected_day ]);
	}

	/**
	 * XML RSS for the newsletter sent by Mailchimp
	 * @return string
	 */
	public function actionNewsletterRss(){
		$response = Yii::$app->getResponse();
    	$headers = $response->getHeaders();

    	$headers->set('Content-Type', 'application/rss+xml; charset=utf-8');

    	$startDate = new \Datetime();
    	if( $startDate->format('w') != 5 ){
    		$startDate->modify('next friday');
    	}

    	$milongas = $this->getEvents( 9 , 'milonga:,practica:,millonga:,workshop:,concert:,show:' , $startDate );
    	$pictures = $this->getPictures( 9 );
    	// $posts = $this->getLatestPosts();
    	$posts = array();

    	return $this->renderPartial(
    		'newsletter-rss',
    		[ 
    			'milongas' => $milongas , 
    			'pictures' => $pictures,
    			'posts' => $posts,
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
	private function getEvents( $days , $filter = null , $startDate = null ){
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

			$google_url = 'https://www.googleapis.com/calendar/v3/calendars/' . urlencode($google_calendar_id) . '/events?key=' . urlencode($google_api_key) . '&orderBy=startTime&singleEvents=true&timeMin=' . urlencode( $startDate->format($format)  . 'T07:00:00+00:00') . '&timeMax=' . urlencode( $endPeriod->format($format) . 'T23:59:59+00:00' );
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
			$event_text = $event['summary'];
			$fits = FALSE;
			foreach( $filters as $filter ){
				$filter_without_semicolons = str_replace([' :',':'], '', $filter );

				if(stristr($event_text,$filter) || stristr(str_replace(' :',':',$event_text), $filter) || strtolower(substr($event_text, 0, strlen($filter_without_semicolons))) === strtolower($filter_without_semicolons) ){
					// stripping the category from the title
					$prefixes = [ $filter , str_replace(':',' :',$filter) ];
					foreach($prefixes as $prefix){
						if (substr(strtolower($event_text), 0, strlen($prefix)) == strtolower($prefix)) {
    						$event_text = substr($event_text, strlen($prefix));
    						$event['category'] = str_replace(':','',strtolower($prefix));
    						break;
						}
					}
					$event['summary'] = $event_text;
					$fits = TRUE;
				}
			}
			if( $fits == TRUE ){
				$events_filtered[] = $event;
			}
		}
		return $events_filtered;
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

		
		$events = $this->getEvents( 9 , 'milonga:,practica:,millonga:,workshop:,concert:,show:' , $startDate );

    	foreach ($events as $event) {
    		// var_dump($event);
    		// Official creator of the event
    		if( isset($event['creator']['email']) ){
    			$email = $event['creator']['email'];
    			$emails[ $email ] = $email;
    		}
    		
    		// Mailto in the event
    		if( isset($event['description']) ){
    			$event_html = $event['description'];
    			// preg_match_all("#mailto:([a-z0-9\.\-\_]*@[a-z0-9\.\_\-]*)#",$event_html,$matches1,PREG_SET_ORDER);
				preg_match_all('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i',$event_html,$matches,PREG_SET_ORDER);

				if($matches){
					foreach($matches as $match){
						$email = $match[0];
						if( strlen($email)>5 ){
							$emails[$email] = $email;
						}
					}
				}
    		}
    		
    	}


    	$emails = array_filter( $emails , function($email){
    		$excludes = array( 'bverdeye@gmail.com' , 'peter.forret@gmail.com' );
    		if( !in_array( $email, $excludes ) ){
    			return true;
    		}
    		return false;
    	} );

    	var_dump(array_values($emails));
    	// die();

        Yii::$app->mailer->compose('alert',[ 'events' => $events])
            ->setFrom('milonga@milonga.be')
            ->setBcc(array_values($emails))
        	->setTo('milonga@milonga.be')
            ->setSubject('Milonga.be : please check your events')
            ->send();
	}
}