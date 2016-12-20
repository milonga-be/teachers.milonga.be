<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

class Venue extends ActiveRecord{

	public static function tableName(){
		return 'venue';
	}

	public function rules(){
		return [
			[['name','address','postalcode'],'safe']
		];
	}

	/**
     * Returns the school that uses the venue
     * @return School
     */
    public function getSchool(){
        return $this->hasOne(School::className(), ['id' => 'school_id']);
    }

    /**
     * Returns the lessons that uses the venue
     * @return array
     */
    public function getLessons(){
        return $this->hasMany(Lesson::className(), ['venue_id' => 'id'])->orderBy(new Expression(' MOD( `day` + 7 , 8 ) ASC,`start_hour` ASC') );
    }

    /* Returns the lessons for a certain level
     * @param  array $level the level
     * @return array
     */
    public function getLevelLessons( $level ){
        return $this->getLessons()->where(['=','level',$level ])->all();
    }

    /* Returns the lessons for a certain day
     * @param  array $day the day
     * @return array
     */
    public function getDayLessons( $day ){
        return $this->getLessons()->where(['=','day',$day ])->all();
    }
    

    /**
     * Get a list of the school venues
     * @return array
     */
    public static function getSchoolVenues(){
    	$user = Yii::$app->user->identity;
        $school = $user->school;
    	$venues = Venue::find()->where(['school_id' => $school->id])->asArray()->all();

    	return $venuesArray = ArrayHelper::map( $venues , 'id', 'name');
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
}