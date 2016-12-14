<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Lesson;
use common\models\School;

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
        return $this->render('index');
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
            if($school->validate()){

                $school->save();

                Yii::$app->getSession()->setFlash('success', ['title' => 'School updated']);
            }
        }

        return $this->render('update',[ 'school' => $school ]);
    }
}