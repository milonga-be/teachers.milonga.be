<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Lesson;
use common\models\School;
use common\models\Venue;
use common\models\LessonSearch;
use yii\helpers\ArrayHelper;


class LessonsController extends Controller{

	/**
	 * List the classes by postalcodes
	 * @return string
	 */
	public function actionPostalcodes( array $postalcodes = array(), $from = null , $to = null ){

		if($from && $to){
			$venues = Venue::find()->where(['>=','postalcode',$from])->andWhere(['<=','postalcode',$to])->all();
			$postalcodes = ArrayHelper::merge( ArrayHelper::getColumn($venues, 'postalcode'), $postalcodes );
		}else if(!sizeof($postalcodes)){
			$venues = Venue::find()->asArray()->all();
			$postalcodes = ArrayHelper::getColumn($venues, 'postalcode');
		}

		$schools = School::find()->joinWith('venues')->where(['IN','venue.postalcode',$postalcodes ])->orderBy('venue.postalcode ASC')->all();

		foreach ($schools as $school) {
			$schools_venues[ $school->id ] = $school->getPostalCodeVenues( $postalcodes );
		}

		foreach ($schools_venues as $venues) {
			foreach($venues as $venue)
				$venues_lessons[ $venue->id ] = $venue->lessons;
		}

        return $this->render('list', [
            'schools' => $schools,
            'postalcodes' => $postalcodes,
            'schools_venues' => $schools_venues,
            'venues_lessons' => $venues_lessons,
            
        ]);
	}

	/**
	 * List the classes for a certain level
	 * @return string
	 */
	public function actionLevel( $level ){

		$schools = School::find()->joinWith('lessons')->where(['=','lesson.level',$level ])->orderBy('name ASC')->all();

		foreach ($schools as $school) {
			$schools_venues[ $school->id ] = $school->getLevelVenues( $level );
		}

		foreach ($schools_venues as $venues) {
			foreach($venues as $venue)
				$venues_lessons[ $venue->id ] = $venue->getLevelLessons( $level );
		}

        return $this->render('list', [
            'schools' => $schools,
            'level' => $level,
            'schools_venues' => $schools_venues,
            'venues_lessons' => $venues_lessons,
        ]);
	}

	/**
	 * List the classes for a certain day
	 * @return string
	 */
	public function actionDay( $day ){

		$schools = School::find()->joinWith('lessons')->where(['=','lesson.day',$day ])->orderBy('name ASC')->all();

		foreach ($schools as $school) {
			$schools_venues[ $school->id ] = $school->getDayVenues( $day );
		}

		foreach ($schools_venues as $venues) {
			foreach($venues as $venue)
				$venues_lessons[ $venue->id ] = $venue->getDayLessons( $day );
		}

        return $this->render('list', [
            'schools' => $schools,
            'day' => $day,
            'schools_venues' => $schools_venues,
            'venues_lessons' => $venues_lessons,
        ]);
	}

}