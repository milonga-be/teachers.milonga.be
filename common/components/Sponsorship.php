<?php
namespace common\components;

class Sponsorship
{
	public static function isDaySponsored($events_for_date){
		if(isset($events_for_date)){
			foreach($events_for_date as $set_name => $events){
				foreach($events as $id => $event){
					if(self::isEventSponsored($event))
						return true;
				}
			}
		}
		return false;
	}

	public static function isEventSponsored($event){
		return isset($event['extendedProperties']['shared']['sponsored']) && $event['extendedProperties']['shared']['sponsored'] == 1;
	}

}