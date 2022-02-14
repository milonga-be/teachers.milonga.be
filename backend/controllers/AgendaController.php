<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\EventSearch;
use backend\models\Event;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class AgendaController extends Controller{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create' , 'delete' , 'update', 'duplicate', 'cancel', 'delete-confinement' ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays events for the user
     *
     * @return string
     */
    public function actionIndex()
    {
        
        $eventSearchModel = new EventSearch();
        $eventDataProvider = $eventSearchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', ['dataProvider' => $eventDataProvider]);
    }

    /**
     * Update an event
     * @param  string $id Id of the event on the Google Agenda
     * @return mixed
     */
    public function actionUpdate($id){
    	$event = Event::findOne($id);

    	if ($event->load(Yii::$app->request->post())) {
            $event->pictureFile = UploadedFile::getInstance($event, 'pictureFile');
            $event->uploadFiles();
            if($event->save()){
                Yii::$app->getSession()->setFlash('success', ['title' => 'Event updated']);
            }
        }

    	return $this->render('update', ['event' => $event]);
    }

    /**
     * Update an event
     * @param  string $id Id of the event on the Google Agenda
     * @return mixed
     */
    public function actionDuplicate($id){
        $tocopy = Event::findOne($id);
        $event = new Event();
        $event->setAttributes($tocopy->attributes);
        $event->pictureFile = $tocopy->pictureFile;

        if ($event->load(Yii::$app->request->post())) {
            $event->pictureFile = UploadedFile::getInstance($event, 'pictureFile');
            $event->uploadFiles();
            if($event->save()){
                Yii::$app->getSession()->setFlash('success', ['title' => 'Event created']);
                $this->redirect(['update', 'id' => $event->id]);
                return;
            }
        }

        return $this->render('update', ['event' => $event]);
    }

    /**
     * Create an event
     * @return mixed
     */
    public function actionCreate($recurring = 0){
        $event = new Event();
        $event->type = 'MILONGA';
        // $event->type = 'ONLINE';
        if($recurring){
            $event->recurrence_every = Event::EVERY_MONDAY;
            $event->start_hour = '20:00';
            $event->end_hour = '23:00';
            $event->scenario = Event::SCENARIO_RECURRING;
        }

        if ($event->load(Yii::$app->request->post())) {
            $event->pictureFile = UploadedFile::getInstance($event, 'pictureFile');
            $event->uploadFiles();
            if($event->save()){
                $this->redirect(['update', 'id' => $event->id]);
                Yii::$app->getSession()->setFlash('success', ['title' => 'Event created']);
                return;
            }
        }

        return $this->render('update', ['event' => $event]);
    }

    /**
     * Delete an event
     * @return  mixed
     */
    public function actionDelete($id){
        $event = Event::findOne($id);

        $event->delete();
        $this->redirect(['index']);
        return;
    }

    public function actionDeleteConfinement(){
        $eventSearchModel = new EventSearch();
        $eventDataProvider = $eventSearchModel->search(Yii::$app->request->queryParams);

        foreach ($eventDataProvider->getModels() as $model) {
            echo $model->id.' '.$model->summary.' '.$model->start['dateTime'].'<br>';
            $event = Event::findOne($model->id);
            $event->delete();
            // break;
        }
    }

    /**
     * Cancel an event
     * @return  mixed
     */
    public function actionCancel($id){
        $event = Event::findOne($id);

        $event->cancel();
        $this->redirect(['index']);
        return;
    }
}