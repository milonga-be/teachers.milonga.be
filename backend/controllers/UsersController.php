<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;
use backend\models\UserSearch;
use common\models\School;
use yii\data\ActiveDataProvider;

/**
 * Site controller
 */
class UsersController extends Controller{

	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
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
     * Displays users list for admin.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $provider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',['dataProvider' => $provider, 'searchModel' => $searchModel]);
    }

    /**
     * Displays one user
     *
     * @return string
     */
    public function actionUpdate($id = null)
    {
        $user = Yii::$app->user->identity;
        if($user->isAdmin()){
            $user = User::findOne($id);
        }else{
            return;
        }

        if ($user->load(Yii::$app->request->post())) {
            if(!empty($user->clear_password))
                $user->setPassword($user->clear_password);
            if($user->save()){
                if(isset($user->school))
                    $user->unlink('school', $user->school, true);
                if(isset(Yii::$app->request->post()['User']['school'])){
                    $user->link('school', School::findOne(Yii::$app->request->post()['User']['school']));
                }
                Yii::$app->getSession()->setFlash('success', ['title' => 'User updated']);
            }
        }

        return $this->render('update',[ 'user' => $user ]);
    }

    /**
     * Create a new user
     *
     * @return string
     */
    public function actionCreate()
    {
        $user = new User();

        if ($user->load(Yii::$app->request->post())) {
            if(!empty($user->clear_password))
                $user->setPassword($user->clear_password);
            if($user->save()){
                if(isset(Yii::$app->request->post()['User']['school'])){
                    $user->link('school', School::findOne(Yii::$app->request->post()['User']['school']));
                }
                Yii::$app->getSession()->setFlash('success', ['title' => 'User created']);

                return $this->redirect(['users/update', 'id' => $user->id ]);
            }
        }

        return $this->render('create',[ 'user' => $user ]);
    }
}