<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Lesson;
use common\models\School;
use common\models\User;
use backend\models\SchoolSearch;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SchoolsController extends Controller{

	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['message-before-expiration'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'create', 'update' ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays schools list for admin.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SchoolSearch();
        $provider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',['dataProvider' => $provider, 'searchModel' => $searchModel]);
    }

    /**
     * Displays one school
     *
     * @return string
     */
    public function actionUpdate($id = null)
    {
        $user = Yii::$app->user->identity;
        if($user->isAdmin()){
            $school = School::findOne($id);
        }else{
            $school = $user->school;
        }
        

        if ($school->load(Yii::$app->request->post())) {
            $school->pictureFile = UploadedFile::getInstance($school, 'pictureFile');
            $school->flyerFile = UploadedFile::getInstance($school, 'flyerFile');
            $school->uploadFiles();
            if($school->save()){
                Yii::$app->getSession()->setFlash('success', ['title' => 'School updated']);
            }
        }

        return $this->render('update',[ 'school' => $school ]);
    }

    /**
     * Create a new school
     *
     * @return string
     */
    public function actionCreate()
    {
        $school = new School();

        if ($school->load(Yii::$app->request->post())) {
            if($school->save()){
                $school->pictureFile = UploadedFile::getInstance($school, 'pictureFile');
                $school->flyerFile = UploadedFile::getInstance($school, 'flyerFile');
                $school->uploadFiles();
                $school->save();
                Yii::$app->getSession()->setFlash('success', ['title' => 'School created']);

                return $this->redirect(['schools/update', 'id' => $school->id ]);
            }
        }

        return $this->render('create',[ 'school' => $school ]);
    }

    /**
     * Send a message to an account admin before expiration
     */
    function actionMessageBeforeExpiration(){
        $datetime = new \Datetime();
        $datetime->modify('+30 days');
        $schools = School::find()->where(['=', 'expiration', $datetime->format('Y-m-d')])->all();
        foreach($schools as $school){
            $users_emails = \yii\helpers\ArrayHelper::getColumn($school->getUsers()->where(['status' => User::STATUS_ACTIVE])->all(), 'email');
            $message = Yii::$app->mailer->compose('@backend/mail/expiring', ['school' => $school])
                    ->setFrom('milonga@milonga.be')
                    ->setCc('milonga@milonga.be')
                    ->setTo($users_emails)
                    ->setSubject('Your account on milonga.be is about to expire')
                    ->send();
        }
    }
}