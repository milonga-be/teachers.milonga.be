<?php
namespace backend\models;

use Yii;
use yii\base\Model;

class Event extends Model{

	var $id;
	var $type;
	var $summary;
	var $location;
	var $description;
	var $start;
	var $end;
	var $picture;
	var $pictureFile;
	var $pictureRemove;

	const GOOGLE_CAL_API = 'https://www.googleapis.com/calendar/v3/calendars/';

	const TYPE_MILONGA = 'MILONGA';
	const TYPE_PRACTICA = 'PRACTICA';
	const TYPE_WORKSHOP = 'WORKSHOP';
	const TYPE_CONCERT = 'CONCERT';
	const TYPE_SHOW = 'SHOW';
	const TYPE_FESTIVAL = 'FESTIVAL';

	/** Which attributes can be modified and how **/
	public function rules(){
		return [
			[['type', 'summary', 'description', 'location', 'start','end'], 'safe'],
			[['summary', 'description', 'start', 'end', 'location'], 'required'],
			[['start', 'end'], 'datetime', 'format' => 'php:d-m-Y H:i'],
			[['pictureFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
			[['pictureRemove'], 'boolean'],
		];
	}

	public function attributeLabels(){
		return [
			'summary' => "Title"
		];
	}

	/**
	 * Saving the datas to Google
	 * @return boolean
	 */
	public function save(){

		if($this->validate()){
			$user = Yii::$app->user->identity;
			$id = $this->id;
			$google_calendar_id = Yii::$app->params['google-calendar-id'];
			putenv('GOOGLE_APPLICATION_CREDENTIALS='.Yii::$app->params['google_json']);
			$client = new \Google_Client();
			$client->useApplicationDefaultCredentials();
			$client->addScope(\Google_Service_Calendar::CALENDAR);
			$service = new \Google_Service_Calendar($client);

			// Composing the datas
			$datas = array();
			$datas['summary'] = (($this->type)? $this->type.': ':'').$this->summary;
			$datas['location'] = $this->location;
			$datas['picture'] = $this->picture;
			$datas['description'] = $this->description;
			$startDateTime = new \DateTime($this->start);
			$datas['start']['dateTime'] = $startDateTime->format(\DateTime::RFC3339);
			$endDateTime = new \DateTime($this->end);
			$datas['end']['dateTime'] = $endDateTime->format(\DateTime::RFC3339);

			$event = new \Google_Service_Calendar_Event();
			$event->setSummary($datas['summary']);
			$event->setLocation($datas['location']);
			$event->setDescription($datas['description']);
			$gsdt = new \Google_Service_Calendar_EventDateTime();
			$gsdt->setDatetime($datas['start']['dateTime']);
			$gedt = new \Google_Service_Calendar_EventDateTime();
			$gedt->setDatetime($datas['end']['dateTime']);
			$event->setStart($gsdt);
			$event->setEnd($gedt);
			$extentedProperties = new \Google_Service_Calendar_EventExtendedProperties();
			$sharedProperties = array('organizer' => $user->email);
			if($datas['picture'] || $this->pictureRemove){
				$sharedProperties['picture'] = $datas['picture'];
			}

			$extentedProperties->setShared($sharedProperties);
			$event->setExtendedProperties($extentedProperties);

			if($id)
				$result = $service->events->patch($google_calendar_id, $id, $event);
			else
				$result = $service->events->insert($google_calendar_id, $event);

			$this->id = $result->id;

			return true;
		}
		return false;
	}

	/**
     * Upload and saves the files for the event
     * @return boolean
     */
    public function uploadFiles()
    {
    	if($this->pictureRemove){
    		$this->picture = "";
    	}
        if ($this->validate()) {
            $events_dir = \Yii::$app->basePath.'/../uploads/events/';
            $event_dir = $events_dir.$this->id;
            @mkdir($events_dir);
            @mkdir($event_dir);
            if($this->pictureFile){
                $path = '/picture-'.date('YmdHis').'.' . $this->pictureFile->extension;
                $complete_path = $event_dir.$path;
                $this->pictureFile->saveAs($complete_path);
                $this->picture = $this->id.$path;

                $this->pictureFile = null;
            }
            return true;
        } else {
            return false;
        }
    }

	/**
	 * Find one event
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public static function findOne($id){
		$google_calendar_id = Yii::$app->params['google-calendar-id'];
		putenv('GOOGLE_APPLICATION_CREDENTIALS='.Yii::$app->params['google_json']);
		$client = new \Google_Client();
		$client->useApplicationDefaultCredentials();
		$client->addScope(\Google_Service_Calendar::CALENDAR);
		$service = new \Google_Service_Calendar($client);

		$result = $service->events->get($google_calendar_id, $id);

		// var_dump($result);
		// die();

		$event = new Event();
		$event->id = $result->id;
		$analyse_summary = self::filterType($result->summary);
		$event->summary = $analyse_summary['summary'];
		$event->location = $result->location;
		$event->type = $analyse_summary['type'];
		$event->description = $result->description;
		if(!strpos($event->description, '<a ') && !strpos($event->description, '<b')){
			$event->description = nl2br($event->description);
		}
		// Dates
		$startDateTime = new \DateTime($result->start->dateTime);
		$event->start = $startDateTime->format('d-m-Y H:i');

		$endDateTime = new \DateTime($result->end->dateTime);
		$event->end = $endDateTime->format('d-m-Y H:i');

		if(isset($result->getExtendedProperties()->shared['picture'])){
			$event->picture = $result->getExtendedProperties()->shared['picture'];
		}

		return $event;
	}

	/**
	 * filter the type of the event
	 * @param  string $summary The summary to analyse
	 * @return array
	 */
	public static function filterType($summary){
		$type = '';
		$types = self::getTypes();
		foreach ($types as $key => $type) {
			if(substr($summary, 0, strlen($type) + 1) == $type.':'){
				$found_type = $type;
				$summary = trim(substr($summary, strlen($type) + 1));
				break;
			}else if(substr($summary, 0, strlen($type) + 2) == $type.' :'){
				$found_type = $type;
				$summary = trim(substr($summary, strlen($type) + 2));
				break;
			}
		}

		return array('summary' => $summary, 'type' => $found_type);
	}

	/**
	 * Call Google API
	 * @param  string $url    The complete url to call
	 * @param  string $method GET/POST/PUT/...
	 * @return array
	 */
	public static function getFromApi($url, $method = 'GET', $body = null){
		$ch = curl_init();
		$headers = [
			'Content-Type: application/json'
		];
		// Adding the authorization
		//if($method == 'GET'){
			if(strpos($url, '?')){
				$url.='&';
			}else{
				$url.='?';
			}
			$url.='key='.Yii::$app->params['google-api-key'];
		// }else{
		// 	$headers[] = 'Authorization: Bearer '.Yii::$app->params['google-api-key'];
		// }
		// var_dump($url);
		// die();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		if($method == 'POST'){
			curl_setopt($ch, CURLOPT_POST, 1);
		}else if($method != 'GET'){
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		}
		if($body){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION , true);
		$output = curl_exec ($ch);
		curl_close ($ch);

		return json_decode($output, true);
	}

	/**
	 * Get the list of event types
	 * @return array
	 */
	public static function getTypes(){
		return array(
			'' => '',
			self::TYPE_MILONGA => self::TYPE_MILONGA,
			self::TYPE_PRACTICA => self::TYPE_PRACTICA,
			self::TYPE_WORKSHOP => self::TYPE_WORKSHOP,
			self::TYPE_CONCERT => self::TYPE_CONCERT,
			self::TYPE_SHOW => self::TYPE_SHOW,
			self::TYPE_FESTIVAL => self::TYPE_FESTIVAL,
		);
	}

	/**
     * Get a absolute url to the original picture for the school
     * @return string
     */
    public function getPictureUrl(){
        return 'http://'.\Yii::$app->getRequest()->serverName.\Yii::$app->request->BaseUrl.'/../../uploads/events/'.$this->picture;
    }

    /**
     * Delete the event in Google calendar
     */
    public function delete(){
    	$google_calendar_id = Yii::$app->params['google-calendar-id'];
		putenv('GOOGLE_APPLICATION_CREDENTIALS='.Yii::$app->params['google_json']);
		$client = new \Google_Client();
		$client->useApplicationDefaultCredentials();
		$client->addScope(\Google_Service_Calendar::CALENDAR);
		$service = new \Google_Service_Calendar($client);

		$result = $service->events->delete($google_calendar_id, $this->id);
		return 1;
    }

}