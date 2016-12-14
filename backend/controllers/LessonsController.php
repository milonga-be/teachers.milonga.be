<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Lesson;
use common\models\School;
use common\models\Venue;
use common\models\LessonSearch;

/**
 * Site controller
 */
class LessonsController extends Controller{

	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create' , 'delete' , 'update' ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays lessons list for school.
     *
     * @return string
     */
    public function actionIndex( $venue_id )
    {
        $user = Yii::$app->user->identity;
        $school = $user->school;
        $venue = Venue::findOne(['id' => $venue_id ]);
        $lessonSearchModel = new LessonSearch();
        $lessonSearchModel->venue_id = $venue_id;
        $lessonDataProvider = $lessonSearchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'venue' => $venue,
            'searchModel' => $lessonSearchModel,
            'dataProvider' => $lessonDataProvider,
            
        ]);
        return $this->render('index');
    }

    /**
     * Create a new lesson
     * @return string
     */
    public function actionCreate( $venue_id = null ){
        $lesson = new Lesson();
        if( $venue_id ){
            $lesson->venue_id = $venue_id;
        }

        if ($lesson->load(Yii::$app->request->post())) {
            if($lesson->validate()){

                $lesson->save();

                Yii::$app->getSession()->setFlash('success', ['title' => 'Class saved']);

                return $this->actionIndex( $lesson->venue_id );
            }
        }

        return $this->render('create',['lesson' => $lesson]);
    }

    /**
     * Retrieving a lesson filtering on own school
     * @param  integer $id the identifier of the lesson
     * @return Lesson     The object representing the lesson
     */
    public function findModel( $id ){
        $user = Yii::$app->user->identity;
        $school = $user->school;
        return Lesson::findOne([ 'id' => $id , 'school_id' => $school->id ] );
    }

    /**
     * Update a lesson
     * @return string
     */
    public function actionUpdate( $id ){
        $lesson = $this->findModel( $id );

        if ($lesson->load(Yii::$app->request->post())) {
            if($lesson->validate()){

                $lesson->save();

                Yii::$app->getSession()->setFlash('success', ['title' => 'Class saved']);

                return $this->actionIndex( $lesson->venue_id );
            }
        }

        return $this->render('update',['lesson' => $lesson]);
    }

    /**
     * Delete a lesson
     * @param  integer $id the identifier of the lesson
     * @return string
     */
    public function actionDelete( $id ){
        $lesson = $this->findModel( $id );
        $venue_id = $lesson->venue_id;

        $lesson->delete();

        Yii::$app->getSession()->setFlash('success', ['title' => 'Class deleted']);

        return $this->actionIndex( $venue_id );
    }
}