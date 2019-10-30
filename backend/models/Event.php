<?php
namespace backend\models;

use Yii;
use yii\base\Model;

class Event extends Model{

	var $id;
	var $type;
	var $city;
	var $organizer;
	var $summary;
	var $location;
	var $description;
	var $weekday;
	var $start;
	var $end;
	var $picture;
	var $pictureFile;
	var $pictureRemove;
	var $raw_recurrence;
	var $recurrence_every;
	var $from;
	var $until;
	var $start_hour;
	var $end_hour;
	var $masterId;

	const GOOGLE_CAL_API = 'https://www.googleapis.com/calendar/v3/calendars/';

	const TYPE_MILONGA = 'MILONGA';
	const TYPE_PRACTICA = 'PRACTICA';
	const TYPE_WORKSHOP = 'WORKSHOP';
	const TYPE_CONCERT = 'CONCERT';
	const TYPE_SHOW = 'SHOW';
	const TYPE_FESTIVAL = 'FESTIVAL';
	const TYPE_HOLIDAYS = 'HOLIDAYS';
	const TYPE_MARATHON = 'MARATHON';

	const CITY_BRUSSELS = 'Brussels';
	const CITY_ANTWERPEN = 'Antwerpen';
	const CITY_BRUGGE = 'Brugge';
	const CITY_GENT = 'Gent';
	const CITY_LIEGE = 'Liège';
	const CITY_NAMUR = 'Namur';
	const CITY_BW = 'Brabant Wallon';
	const CITY_HASSELT = 'Hasselt';
	const CITY_MOUSCRON = 'Mouscron';

	const EVERY = 'WEEKLY';
	const EVERY_MONDAY = 'MO';
	const EVERY_TUESDAY = 'TU';
	const EVERY_WEDNESDAY = 'WE';
	const EVERY_THURSDAY = 'TH';
	const EVERY_FRIDAY = 'FR';
	const EVERY_SATURDAY = 'SA';
	const EVERY_SUNDAY = 'SU';
	const EVERY_FIRST_MONDAY = '1MO';
	const EVERY_FIRST_TUESDAY = '1TU';
	const EVERY_FIRST_WEDNESDAY = '1WE';
	const EVERY_FIRST_THURSDAY = '1TH';
	const EVERY_FIRST_FRIDAY = '1FR';
	const EVERY_FIRST_SATURDAY = '1SA';
	const EVERY_FIRST_SUNDAY = '1SU';
	const EVERY_SECOND_MONDAY = '2MO';
	const EVERY_SECOND_TUESDAY = '2TU';
	const EVERY_SECOND_WEDNESDAY = '2WE';
	const EVERY_SECOND_THURSDAY = '2TH';
	const EVERY_SECOND_FRIDAY = '2FR';
	const EVERY_SECOND_SATURDAY = '2SA';
	const EVERY_SECOND_SUNDAY = '2SU';
	const EVERY_THIRD_MONDAY = '3MO';
	const EVERY_THIRD_TUESDAY = '3TU';
	const EVERY_THIRD_WEDNESDAY = '3WE';
	const EVERY_THIRD_THURSDAY = '3TH';
	const EVERY_THIRD_FRIDAY = '3FR';
	const EVERY_THIRD_SATURDAY = '3SA';
	const EVERY_THIRD_SUNDAY = '3SU';
	const EVERY_FOURTH_MONDAY = '4MO';
	const EVERY_FOURTH_TUESDAY = '4TU';
	const EVERY_FOURTH_WEDNESDAY = '4WE';
	const EVERY_FOURTH_THURSDAY = '4TH';
	const EVERY_FOURTH_FRIDAY = '4FR';
	const EVERY_FOURTH_SATURDAY = '4SA';
	const EVERY_FOURTH_SUNDAY = '4SU';

	const MONDAY = 'MO';
	const TUESDAY = 'TU';
	const WEDNESDAY = 'WE';
	const THURSDAY = 'TH';
	const FRIDAY = 'FR';
	const SATURDAY = 'SA';
	const SUNDAY = 'SU';

	const FREQ_WEEKLY = 'FREQ=WEEKLY';
	const FREQ_MONTHLY = 'FREQ=MONTHLY';

	const SCENARIO_RECURRING = 'recurring';

	/** Which attributes can be modified and how **/
	public function rules(){
		return [
			[['type', 'summary', 'city', 'description', 'location', 'start','end', 'from', 'until', 'recurrence_every', 'weekday', 'organizer'], 'safe'],
			[['summary', 'description', 'start', 'end', 'location'], 'required'],
			[['start', 'end'], 'datetime', 'format' => 'php:d-m-Y H:i'],
			[['start_hour', 'end_hour'], 'datetime', 'format' => 'php:H:i'],
			[['pictureFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
			[['pictureRemove'], 'boolean'],
		];
	}

	public function scenarios(){
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_RECURRING] = ['start_hour', 'end_hour', 'summary', 'location', 'description', 'from', 'until', 'pictureFile', 'pictureRemove','recurrence_every', 'weekday', 'type', 'city', 'organizer'];
		return $scenarios;
	}

	public function attributeLabels(){
		return [
			'summary' => "Title",
			'recurrence_every' => "Recurrence",
			'weekday' => "Weekday",
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
			$datas['summary'] = (($this->type)? $this->type.': ':'').$this->summary.(($this->city)? ' @ '.$this->city:'');
			$datas['location'] = $this->location;
			$datas['picture'] = $this->picture;
			$datas['description'] = $this->description;
			$startDateTime = new \DateTime($this->start);
			if($this->from){
				$startDateTime = new \DateTime($this->from);
			}
			if($this->start_hour){
				$time = explode(':', $this->start_hour);
				$startDateTime->setTime($time[0], $time[1]);
			}
			$datas['start']['dateTime'] = $startDateTime->format(\DateTime::RFC3339);
			$endDateTime = new \DateTime($this->end);
			if($this->from){
				$endDateTime = new \DateTime($this->from);
			}
			if($this->end_hour){
				$time = explode(':', $this->end_hour);
				$endDateTime->setTime($time[0], $time[1]);
				if($this->end_hour < $this->start_hour){
					$endDateTime->modify('+1 day');
				}
			}
			$datas['end']['dateTime'] = $endDateTime->format(\DateTime::RFC3339);

			// var_dump($this);
			// var_dump($datas);
			// die();

			$event = new \Google_Service_Calendar_Event();
			$event->setSummary($datas['summary']);
			$event->setLocation($datas['location']);
			$event->setDescription($datas['description']);
			$gsdt = new \Google_Service_Calendar_EventDateTime();
			$gsdt->setTimezone("Europe/Paris");
			$gsdt->setDatetime($datas['start']['dateTime']);
			$gedt = new \Google_Service_Calendar_EventDateTime();
			$gedt->setDatetime($datas['end']['dateTime']);
			$gedt->setTimezone("Europe/Paris");
			$event->setStart($gsdt);
			$event->setEnd($gedt);
			$extentedProperties = new \Google_Service_Calendar_EventExtendedProperties();
			$sharedProperties = array('organizer' => $user->email);
			if(isset($this->organizer) && $this->organizer != $user->email){
				$sharedProperties['organizer'] = $this->organizer;
			}
			if($datas['picture'] || $this->pictureRemove){
				$sharedProperties['picture'] = $datas['picture'];
			}

			$extentedProperties->setShared($sharedProperties);
			$event->setExtendedProperties($extentedProperties);

			$raw_recurrence = $this->formatNewRecurrence();
			if($raw_recurrence){
				// var_dump($raw_recurrence);
				// die();
				$event->setRecurrence(array($raw_recurrence));
			}

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
		$event->city = $analyse_summary['city'];
		$event->description = $result->description;
		// var_dump($event->description);
		// exit();
		if(strpos($event->description, '<a ') === false && strpos($event->description, '<b') === false && strpos($event->description, '<div') === false && strpos($event->description, '<p') === false){
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
		if(isset($result->getExtendedProperties()->shared['organizer'])){
			$event->organizer = $result->getExtendedProperties()->shared['organizer'];
		}
		if(!isset($event->organizer) && isset($result->creator->email)){
			$event->organizer = $result->creator->email;
		}
		if($result->getRecurringEventId()){
			$event->masterId = $result->getRecurringEventId();
		}
		if($result->getRecurrence()){
			// for recurring event
			$event->start_hour = $startDateTime->format('H:i');
			$event->from = $startDateTime->format('d-m-Y');
			$event->weekday = $startDateTime->format('D');
			$event->end_hour = $endDateTime->format('H:i');

			$event->raw_recurrence = $result->getRecurrence();
			$event->parseRawRecurrence();
		}

		return $event;
	}


	private function formatNewRecurrence(){
		$rule = '';
		$freq = '';
		$byday = '';
		switch($this->recurrence_every){
			case self::EVERY_MONDAY:
				$byday = self::MONDAY;
				$freq = self::FREQ_WEEKLY;
				break;
			case self::EVERY_TUESDAY:
				$byday = self::TUESDAY;
				$freq = self::FREQ_WEEKLY;
				break;
			case self::EVERY_WEDNESDAY:
				$byday = self::WEDNESDAY;
				$freq = self::FREQ_WEEKLY;
				break;
			case self::EVERY_THURSDAY:
				$byday = self::THURSDAY;
				$freq = self::FREQ_WEEKLY;
				break;
			case self::EVERY_FRIDAY:
				$byday = self::FRIDAY;
				$freq = self::FREQ_WEEKLY;
				break;
			case self::EVERY_SATURDAY:
				$byday = self::SATURDAY;
				$freq = self::FREQ_WEEKLY;
				break;
			case self::EVERY_SUNDAY:
				$byday = self::SUNDAY;
				$freq = self::FREQ_WEEKLY;
				break;
			default:
				if($this->recurrence_every){
					$freq = self::FREQ_MONTHLY;
					$byday = $this->recurrence_every;
				}
		}
		if($freq){
			$rule = 'RRULE:';
			$rule.=$freq.';';
			if($this->until){
				$untilDatetime = new \DateTime($this->until);
				$untilDatetime->setTime(23, 59);
				$rule.='UNTIL='.$untilDatetime->format('Ymd\THis\Z').';';
			}
			$rule.='BYDAY='.$byday;
			// var_dump($rule);
			// die();
			return $rule;
		}
		return false;
	}


	/**
	 * Parses the raw recurrence and fill the recurrence modifiable fields
	 * @return boolean
	 */
	private function parseRawRecurrence(){
		if(is_array($this->raw_recurrence)){
			foreach ($this->raw_recurrence as $rule) {
				if(strpos($rule, 'RRULE:')!==false){
					if(strpos($rule, 'FREQ=MONTHLY')){
						if(strpos($rule, 'BYDAY=1MO')){
							$this->recurrence_every = self::EVERY_FIRST_MONDAY;
						}else if(strpos($rule, 'BYDAY=1TU')){
							$this->recurrence_every = self::EVERY_FIRST_TUESDAY;
						}else if(strpos($rule, 'BYDAY=1WE')){
							$this->recurrence_every = self::EVERY_FIRST_WEDNESDAY;
						}else if(strpos($rule, 'BYDAY=1TH')){
							$this->recurrence_every = self::EVERY_FIRST_THURSDAY;
						}else if(strpos($rule, 'BYDAY=1FR')){
							$this->recurrence_every = self::EVERY_FIRST_FRIDAY;
						}else if(strpos($rule, 'BYDAY=1SA')){
							$this->recurrence_every = self::EVERY_FIRST_SATURDAY;
						}else if(strpos($rule, 'BYDAY=1SU')){
							$this->recurrence_every = self::EVERY_FIRST_SUNDAY;
						}elseif(strpos($rule, 'BYDAY=2MO')){
							$this->recurrence_every = self::EVERY_SECOND_MONDAY;
						}else if(strpos($rule, 'BYDAY=2TU')){
							$this->recurrence_every = self::EVERY_SECOND_TUESDAY;
						}else if(strpos($rule, 'BYDAY=2WE')){
							$this->recurrence_every = self::EVERY_SECOND_WEDNESDAY;
						}else if(strpos($rule, 'BYDAY=2TH')){
							$this->recurrence_every = self::EVERY_SECOND_THURSDAY;
						}else if(strpos($rule, 'BYDAY=2FR')){
							$this->recurrence_every = self::EVERY_SECOND_FRIDAY;
						}else if(strpos($rule, 'BYDAY=2SA')){
							$this->recurrence_every = self::EVERY_SECOND_SATURDAY;
						}else if(strpos($rule, 'BYDAY=2SU')){
							$this->recurrence_every = self::EVERY_SECOND_SUNDAY;
						}else if(strpos($rule, 'BYDAY=3MO')){
							$this->recurrence_every = self::EVERY_THIRD_MONDAY;
						}else if(strpos($rule, 'BYDAY=3TU')){
							$this->recurrence_every = self::EVERY_THIRD_TUESDAY;
						}else if(strpos($rule, 'BYDAY=3WE')){
							$this->recurrence_every = self::EVERY_THIRD_WEDNESDAY;
						}else if(strpos($rule, 'BYDAY=3TH')){
							$this->recurrence_every = self::EVERY_THIRD_THURSDAY;
						}else if(strpos($rule, 'BYDAY=3FR')){
							$this->recurrence_every = self::EVERY_THIRD_FRIDAY;
						}else if(strpos($rule, 'BYDAY=3SA')){
							$this->recurrence_every = self::EVERY_THIRD_SATURDAY;
						}else if(strpos($rule, 'BYDAY=3SU')){
							$this->recurrence_every = self::EVERY_THIRD_SUNDAY;
						}else if(strpos($rule, 'BYDAY=4MO')){
							$this->recurrence_every = self::EVERY_FOURTH_MONDAY;
						}else if(strpos($rule, 'BYDAY=4TU')){
							$this->recurrence_every = self::EVERY_FOURTH_TUESDAY;
						}else if(strpos($rule, 'BYDAY=4WE')){
							$this->recurrence_every = self::EVERY_FOURTH_WEDNESDAY;
						}else if(strpos($rule, 'BYDAY=4TH')){
							$this->recurrence_every = self::EVERY_FOURTH_THURSDAY;
						}else if(strpos($rule, 'BYDAY=4FR')){
							$this->recurrence_every = self::EVERY_FOURTH_FRIDAY;
						}else if(strpos($rule, 'BYDAY=4SA')){
							$this->recurrence_every = self::EVERY_FOURTH_SATURDAY;
						}else if(strpos($rule, 'BYDAY=4SU')){
							$this->recurrence_every = self::EVERY_FOURTH_SUNDAY;
						}
					}else if(strpos($rule, self::FREQ_WEEKLY)){
						switch($this->weekday){
							case 'Mon':
								$this->recurrence_every = self::EVERY_MONDAY;
								break;
							case 'Tue':
								$this->recurrence_every = self::EVERY_TUESDAY;
								break;
							case 'Wed':
								$this->recurrence_every = self::EVERY_WEDNESDAY;
								break;
							case 'Thu':
								$this->recurrence_every = self::EVERY_THURSDAY;
								break;
							case 'Fri':
								$this->recurrence_every = self::EVERY_FRIDAY;
								break;
							case 'Sat':
								$this->recurrence_every = self::EVERY_SATURDAY;
								break;
							case 'Sun':
								$this->recurrence_every = self::EVERY_SUNDAY;
								break;
						}
						
					}
					if(strpos($rule, 'UNTIL=')!==false){
						$untilStr = substr($rule, strpos($rule, 'UNTIL=') + 6, 16);
						$untilDatetime = new \DateTime($untilStr);
						$this->until = $untilDatetime->format('d-m-Y');
					}
				}
			}
		}
	}

	/**
	 * Is the event a repeating event
	 * @return boolean
	 */
	public function isRecurrent(){
		return !empty($this->recurrence_every);
	}

	/**
	 * filter the type of the event
	 * @param  string $summary The summary to analyse
	 * @return array
	 */
	public static function filterType($summary){
		$summary = trim($summary);
		$type = '';
		$found_type = null;
		$found_city = null;
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
		$cities = self::getCities();
		foreach ($cities as $city => $city_label) {
			if(strtolower(substr($summary,  - strlen($city) - 1 )) == '@'.strtolower($city)){
				$found_city = $city;
				$summary = trim(substr($summary, 0, - strlen($city) - 1));
				break;
			}else if(strtolower(substr($summary,  - strlen($city) - 2)) == '@ '.strtolower($city)){
				$found_city = $city;
				$summary = trim(substr($summary, 0, - strlen($city) - 2));
				break;
			}
		}

		return array('summary' => $summary, 'type' => $found_type, 'city' => $found_city);
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
			self::TYPE_MARATHON => self::TYPE_MARATHON,
			self::TYPE_HOLIDAYS => self::TYPE_HOLIDAYS,
		);
	}

	/**
	 * Get the list of cities
	 * @return array
	 */
	public static function getCities(){
		return array(
			'' => '',
			self::CITY_BRUSSELS => self::CITY_BRUSSELS,
			self::CITY_ANTWERPEN => self::CITY_ANTWERPEN,
			self::CITY_BRUGGE => self::CITY_BRUGGE,
			self::CITY_GENT => self::CITY_GENT,
			self::CITY_LIEGE => self::CITY_LIEGE,
			self::CITY_NAMUR => self::CITY_NAMUR,
			self::CITY_BW => self::CITY_BW,
			self::CITY_HASSELT => self::CITY_HASSELT,
			self::CITY_MOUSCRON => self::CITY_MOUSCRON,
		);
	}

	/**
	 * Get the list of possible recurrence
	 * @return array
	 */
	public static function getRecurrenceEveryList(){
		return array(
			'Every week' => [
				self::EVERY_MONDAY => 'Every Monday',
				self::EVERY_TUESDAY => 'Every Tuesday',
				self::EVERY_WEDNESDAY => 'Every Wednesday',
				self::EVERY_THURSDAY => 'Every Thursday',
				self::EVERY_FRIDAY => 'Every Friday',
				self::EVERY_SATURDAY => 'Every Saturday',
				self::EVERY_SUNDAY => 'Every Sunday'
			],
			'Every first' => [
				self::EVERY_FIRST_MONDAY => 'Every 1st Monday',
				self::EVERY_FIRST_TUESDAY => 'Every 1st Tuesday',
				self::EVERY_FIRST_WEDNESDAY => 'Every 1st Wednesday',
				self::EVERY_FIRST_THURSDAY => 'Every 1st Thursday',
				self::EVERY_FIRST_FRIDAY => 'Every 1st Friday',
				self::EVERY_FIRST_SATURDAY => 'Every 1st Saturday',
				self::EVERY_FIRST_SUNDAY => 'Every 1st Sunday'
			],
			'Every second' => [
				self::EVERY_SECOND_MONDAY => 'Every 2nd Monday',
				self::EVERY_SECOND_TUESDAY => 'Every 2nd Tuesday',
				self::EVERY_SECOND_WEDNESDAY => 'Every 2nd Wednesday',
				self::EVERY_SECOND_THURSDAY => 'Every 2nd Thursday',
				self::EVERY_SECOND_FRIDAY => 'Every 2nd Friday',
				self::EVERY_SECOND_SATURDAY => 'Every 2nd Saturday',
				self::EVERY_SECOND_SUNDAY => 'Every 2nd Sunday'
			],
			'Every third' => [
				self::EVERY_THIRD_MONDAY => 'Every 3d Monday',
				self::EVERY_THIRD_TUESDAY => 'Every 3d Tuesday',
				self::EVERY_THIRD_WEDNESDAY => 'Every 3d Wednesday',
				self::EVERY_THIRD_THURSDAY => 'Every 3d Thursday',
				self::EVERY_THIRD_FRIDAY => 'Every 3d Friday',
				self::EVERY_THIRD_SATURDAY => 'Every 3d Saturday',
				self::EVERY_THIRD_SUNDAY => 'Every 3d Sunday'
			],
			'Every fourth' => [
				self::EVERY_FOURTH_MONDAY => 'Every 4th Monday',
				self::EVERY_FOURTH_TUESDAY => 'Every 4th Tuesday',
				self::EVERY_FOURTH_WEDNESDAY => 'Every 4th Wednesday',
				self::EVERY_FOURTH_THURSDAY => 'Every 4th Thursday',
				self::EVERY_FOURTH_FRIDAY => 'Every 4th Friday',
				self::EVERY_FOURTH_SATURDAY => 'Every 4th Saturday',
				self::EVERY_FOURTH_SUNDAY => 'Every 4th Sunday'
			]
		);
	}

	/**
	 * Get the list of possible recurrence
	 * @return array
	 */
	public static function getRecurrenceWeekdaysList(){
		return array(
			'' => '',
			self::MONDAY => 'Monday',
			self::TUESDAY => 'Tuesday',
			self::WEDNESDAY => 'Wednesday',
			self::THURSDAY => 'Thursday',
			self::FRIDAY => 'Friday',
			self::SATURDAY => 'Saturday',
			self::SUNDAY => 'Sunday',
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