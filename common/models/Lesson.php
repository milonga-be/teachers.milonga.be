<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Lesson extends ActiveRecord{

	public static function tableName(){
		return 'lesson';
	}

	/**
     * Returns the school of the lesson
     * @return School
     */
    public function getSchool(){
        return $this->hasOne(School::className(), ['id' => 'school_id']);
    }

    /**
     * Returns the venue of the lesson
     * @return Venue
     */
    public function getVenue(){
        return $this->hasOne(Venue::className(), ['id' => 'venue_id']);
    }

	/** Which attributes can be modified and how **/
	public function rules(){
		return [
			[['venue_id','day','start_hour','end_hour','level','teachers'],'safe'],
			[['venue_id','day','level','teachers','start_hour'], 'required']
		];
	}

	/**
     * Saves the timestamp of creation and update
     * @param boolean $insert
     * @return mixed
     */
    public function beforeSave( $insert ) {
	    if ($insert)
	        $this->created_at = date('Y-m-d H:i:s');
	 
	    $this->updated_at = date('Y-m-d H:i:s');
	    $user = Yii::$app->user->identity;
        $school = $user->school;
        if(!$this->school_id)
	    	$this->school_id = $school->id;
	 
	    return parent::beforeSave( $insert );
	}

	/**
	 * Return a list of days for the dropdown in the edition form
	 * @return array
	 */
	public static function getDaysList(){
		return array(
			'1' => 'Monday',
			'2' => 'Tuesday',
			'3' => 'Wednesday',
			'4' => 'Thursday',
			'5' => 'Friday',
			'6' => 'Saturday',
			'0' => 'Sunday',
		);
	}

	/**
	 * Return a list of days for the dropdown in the edition form
	 * @return array
	 */
	public static function getAbbrevDaysList(){
		return array(
			'1' => 'Mon.',
			'2' => 'Tues.',
			'3' => 'Wed.',
			'4' => 'Thur.',
			'5' => 'Fri.',
			'6' => 'Sat.',
			'0' => 'Sun.',
		);
	}

	/**
	 * Get a readable day name
	 * @return string
	 */
	public function getDayname(){
		return self::getDaysList()[ $this->day ];
	}

	/**
	 * Get an abbreviated day name
	 * @return string
	 */
	public function getAbbrevDayname(){
		return self::getAbbrevDaysList()[ $this->day ];
	}

	/**
	 * The list of levels
	 * @return array
	 */
	public static function getLevelsList(){
		return array(
			"0" => "Absolute Beginners",
			"1" => "Beginners",
			"2" => "Intermediates",
			"3" => "Advanced Intermediates",
			"4" => "Advanced",
			"5" => "Experts",
			"P" => "Practica",
			"A" => "All levels",
			"T" => "Technique",
			"TW" => "Technique for women",
			"TM" => "Technique for men",
		);
	}

	/**
	 * The list of levels abbreviated
	 * @return array
	 */
	public static function getAbbrevLevelsList(){
		return array(
			"0" => "Abs. Beg.",
			"1" => "Beginners",
			"2" => "Inter.",
			"3" => "Adv. inter.",
			"4" => "Adv.",
			"5" => "Experts",
			"P" => "Pract.",
			"A" => "All levels",
			"T" => "Tech.",
			"TW" => "Tech. 4 women",
			"TM" => "Tech. 4 men",
		);
	}

	/**
	 * Get a readable level name
	 * @return string
	 */
	public function getLevelname(){
		return self::getLevelsList()[ $this->level ];
	}

	/**
	 * Get an abbreviated level name
	 * @return string
	 */
	public function getAbbrevLevelname(){
		return self::getAbbrevLevelsList()[ $this->level ];
	}
}