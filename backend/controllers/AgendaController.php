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
                        'actions' => ['index', 'create' , 'delete' , 'update' ],
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
     * Create an event
     * @return mixed
     */
    public function actionCreate(){
        $event = new Event();
        $event->type = 'MILONGA';

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
}