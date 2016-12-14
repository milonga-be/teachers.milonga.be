<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class School extends ActiveRecord{

	public static function tableName(){
		return 'school';
	}

	public function rules(){
		return [
			[['name','address','email','facebook','phone','website','picture'],'safe']
		];
	}

	/**
     * Returns the venues that the school uses
     * @return array
     */
    public function getVenues(){
        return $this->hasMany(Venue::className(), ['school_id' => 'id']);
    }

    /**
     * Returns the lessons that the school gives
     * @return array
     */
    public function getLessons(){
        return $this->hasMany(Lesson::className(), ['school_id' => 'id']);
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
     
        return parent::beforeSave( $insert );
    }


    /**
     * Returns the venues for a list of postalcodes
     * @param  array $postalcodes the list of postal codes
     * @return array
     */
    public function getPostalCodeVenues( $postalcodes ){
    	return $this->getVenues()->where(['IN','venue.postalcode',$postalcodes ])->all();
    }

    /**
     * Returns the venues for a level
     * @param  array $level the level
     * @return array
     */
    public function getLevelVenues( $level ){
        return $this->getVenues()->joinWith('lessons')->where(['IN','lesson.level',$level ])->all();
    }

    /**
     * Returns the venues for a certain day
     * @param  array $day the day
     * @return array
     */
    public function getDayVenues( $day ){
        return $this->getVenues()->joinWith('lessons')->where(['IN','lesson.day',$day ])->all();
    }
}