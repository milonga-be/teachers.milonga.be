<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\Point;

class School extends ActiveRecord{

    var $pictureFile;
    var $flyerFile;

	public static function tableName(){
		return 'school';
	}

	public function rules(){
		return [
			[['name','address','email','facebook','phone','website'],'safe'],
            [['pictureFile', 'flyerFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
		];
	}

    /**
     * Upload and saves the files for the schoold
     * @return boolean
     */
    public function uploadFiles()
    {
        if ($this->validate()) {
            $school_dir = \Yii::$app->basePath.'/../uploads/'.$this->id;
            @mkdir($school_dir);
            if($this->pictureFile){
                $path = '/picture.' . $this->pictureFile->extension;
                $complete_path = $school_dir.$path;
                $this->pictureFile->saveAs($complete_path);
                $this->picture = $this->id.$path;

                // Generating thumb
                $img_size = 100;
                $thumbnail = Image::thumbnail($complete_path, $img_size, $img_size);
                $size = $thumbnail->getSize();
                if ($size->getWidth() < $img_size or $size->getHeight() < $img_size) {
                    $white = Image::getImagine()->create(new Box($img_size, $img_size));
                    $thumbnail = $white->paste($thumbnail, new Point($img_size / 2 - $size->getWidth() / 2, $img_size / 2 - $size->getHeight() / 2));
                }
                $thumb_path = '/picture-thumb100.'.$this->pictureFile->extension;
                $thumbnail->save($school_dir.$thumb_path);
                $this->thumb = $this->id.$thumb_path;

                $this->pictureFile = null;
            }
            if($this->flyerFile){
                $path ='/flyer.' . $this->flyerFile->extension;
                $this->flyerFile->saveAs($school_dir.$path);
                $this->flyerFile = null;
                $this->flyer = $this->id.$path;
            }
            return true;
        } else {
            return false;
        }
    }

    public function getThumbUrl(){
        return 'http://'.\Yii::$app->getRequest()->serverName.\Yii::$app->request->BaseUrl.'/../../uploads/'.$this->thumb;
    }

    public function getPictureUrl(){
        return 'http://'.\Yii::$app->getRequest()->serverName.\Yii::$app->request->BaseUrl.'/../../uploads/'.$this->picture;
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

    /**
     * Get a list of the school venues
     * @return array
     */
    public static function getSchools(){
        $user = Yii::$app->user->identity;
        $schools = $user->getSchools()->asArray()->all();

        return $schoolsArray = ArrayHelper::map( $venues , 'id', 'name');
    }
}