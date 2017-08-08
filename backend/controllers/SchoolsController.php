<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Lesson;
use common\models\School;
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
                        'actions' => ['index', 'update' ],
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
        $user = Yii::$app->user->identity;
        $query = $user->getSchools();

        $provider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index',['dataProvider' => $provider]);
    }

    /**
     * Displays own school for user
     *
     * @return string
     */
    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        $school = $user->school;

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
}