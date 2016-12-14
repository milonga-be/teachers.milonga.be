<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Lesson;
use common\models\School;
use common\models\Venue;
use common\models\VenueSearch;

/**
 * Site controller
 */
class VenuesController extends Controller{

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
     * Create a new venue
     * @return string
     */
    public function actionCreate(){
        $venue = new Venue();

        if ($venue->load(Yii::$app->request->post())) {
            if($venue->validate()){

                $venue->save();

                Yii::$app->getSession()->setFlash('success', ['title' => 'Location saved']);

                return $this->actionIndex();
            }
        }

        return $this->render('create',['venue' => $venue]);
    }

    /**
     * Retrieving a venue filtering on own school
     * @param  integer $id the identifier of the venue
     * @return Venue     The object representing the venue
     */
    public function findModel( $id ){
        $user = Yii::$app->user->identity;
        $school = $user->school;
        return Venue::findOne([ 'id' => $id , 'school_id' => $school->id ] );
    }

    /**
     * Update a lesson
     * @return string
     */
    public function actionUpdate( $id ){
        $venue = $this->findModel( $id );

        if ($venue->load(Yii::$app->request->post())) {
            if($venue->validate()){

                $venue->save();

                Yii::$app->getSession()->setFlash('success', ['title' => 'Location saved']);

                return $this->actionIndex();
            }
        }

        return $this->render('update',['venue' => $venue]);
    }

    /**
     * Delete a venue
     * @param  integer $id the identifier of the venue
     * @return string
     */
    public function actionDelete( $id ){
        $venue = $this->findModel( $id );

        $venue->delete();

        Yii::$app->getSession()->setFlash('success', ['title' => 'Location deleted']);

        return $this->actionIndex();
    }

    /**
     * Displays venues list for school.
     *
     * @return string
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $school = $user->school;
        $venueSearchModel = new VenueSearch();
        $venueDataProvider = $venueSearchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $venueSearchModel,
            'dataProvider' => $venueDataProvider,
            
        ]);
        return $this->render('index');
    }
}