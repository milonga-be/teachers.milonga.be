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
		];
	}

	/**
	 * Saving the datas to Google
	 * @return boolean
	 */
	public function save(){
		if($this->validate()){
			$id = $this->id;
			$google_calendar_id = Yii::$app->params['google-calendar-id'];

			$url = self::GOOGLE_CAL_API;
			$url.=urlencode($google_calendar_id) . '/events/';
			if($id)
				$url.=$id;
			// Composing the datas
			$datas = array();
			$datas['summary'] = (($this->type)? $this->type.': ':'').$this->summary;
			$datas['location'] = $this->location;
			$datas['description'] = $this->description;
			$startDateTime = new \DateTime($this->start);
			$datas['start']['dateTime'] = $startDateTime->format(\DateTime::RFC3339);
			$endDateTime = new \DateTime($this->end);
			$datas['end']['dateTime'] = $endDateTime->format(\DateTime::RFC3339);

			$body = json_encode($datas);
			if($id)
				$datas = self::getFromApi($url, 'PATCH', $body);
			else
				$datas = self::getFromApi($url, 'POST', $body);

			var_dump($datas);
			die();

			return true;
		}
		return false;
	}

	/**
	 * Find one event
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public static function findOne($id){
		$google_calendar_id = Yii::$app->params['google-calendar-id'];

		$url = self::GOOGLE_CAL_API;
		$url.=urlencode($google_calendar_id) . '/events/';
		$url.=$id;
		
		$datas = self::getFromApi($url, 'GET');

		// var_dump($datas);
		// die();

		$event = new Event();
		$event->id = $datas['id'];
		$analyse_summary = self::filterType($datas['summary']);
		$event->summary = $analyse_summary['summary'];
		$event->location = $datas['location'];
		$event->type = $analyse_summary['type'];
		$event->description = $datas['description'];
		// Dates
		$startDateTime = new \DateTime($datas['start']['dateTime']);
		$event->start = $startDateTime->format('d-m-Y H:i');

		$endDateTime = new \DateTime($datas['end']['dateTime']);
		$event->end = $endDateTime->format('d-m-Y H:i');

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

}