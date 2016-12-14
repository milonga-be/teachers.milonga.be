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
	 * Get a readable day name
	 * @return string
	 */
	public function getDayname(){
		return self::getDaysList()[ $this->day ];
	}

	public static function getLevelsList(){
		return array(
			"0" => "Absolute Beginners (no experience)",
			"1" => "Beginners (< 1 year)",
			"2" => "Intermediates (1-2 years)",
			"3" => "Intermediates advanced (2-3 years)",
			"4" => "Advanced (3-5 years)",
			"5" => "Experts (> 5 years)",
			"P" => "Practica (for students)",
			"A" => "All levels",
			"T" => "Technique",
			"TW" => "Technique for women",
			"TM" => "Technique for men",
		);
	}

	/**
	 * Get a readable level name
	 * @return string
	 */
	public function getLevelname(){
		return self::getLevelsList()[ $this->level ];
	}
}