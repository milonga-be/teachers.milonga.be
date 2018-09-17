<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\School;
use common\models\Venue;
use common\models\Lesson;
use common\models\User;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','import','set-password','send-accesses'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Import the classes ini file
     */
    public function actionImport(){
        $config = parse_ini_file( Yii::getAlias('@webroot') . '/../../data/courses2016sep.ini' , true );
        // var_dump($config);
        foreach ($config as $key => $values) {
            $ids = explode('.',$key);
            $short_name = $ids[1];

            $school = School::find()->where(['short_name' => $short_name])->one();
            if(!$school)
                $school = new School();
            $school->name = $values['name'];
            $school->email = isset($values['email'])?$values['email']:'';
            $school->phone = isset($values['phone'])?$values['phone']:'';
            $school->website = isset($values['external_url'])?$values['external_url']:'';
            $school->facebook = isset($values['facebook_url'])?$values['facebook_url']:'';
            $school->short_name = $short_name;

            $school->save();

            $user = User::find()->where(['username' => $school->email])->one();

            if(!$user){
                $user = new User();
            }

            $user->username = $user->email = $school->email;
            if(!$user->clear_password)
                $user->password = $user->clear_password = $this->generate_password();

            $user->save();
            $user->unlink('school',$school,true);
            $user->link('school',$school);


            $venue = Venue::find()->where(['school_id' => $school->id])->andWhere(['name' => $values['venue']])->one();
            if(!$venue){
                $venue = new Venue();
            }

            $venue->name = $values['venue'];
            $venue->address = $values['address'];
            $venue->postalcode = $values['postcode'];
            $venue->school_id = $school->id;

            $venue->save();

            foreach($values['course'] as $course){
                $course_values = explode(';',$course);

                $lesson = Lesson::find()->where(['venue_id' => $venue->id])->andWhere(['day' => $course_values[0]])->andWhere(['start_hour' => $course_values[1] ])->one();
                if(!$lesson){
                    $lesson = new Lesson();
                }
                $lesson->day = $course_values[0];
                if( $course_values[0] == 7 ){
                    $lesson->day = 0;
                }
                $lesson->start_hour = $course_values[1];
                $lesson->level = $course_values[2];
                $lesson->teachers = $course_values[3];
                $lesson->venue_id = $venue->id;
                $lesson->school_id = $school->id;

                $lesson->save();
            }
        }
    }

    /**
     * Generate a random password for a user
     * @param  integer $length length of the password
     * @return string         The password
     */
    private function generate_password($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }


    /**
     * Set a new password for a user
     * @param  integer $id The id of the user you wanna set a password to
     */
    public function actionSetPassword( $id ){
        $user = User::find()->where(['id' => $id])->one();
        if( $user ){
            $user->password = $user->clear_password = $this->generate_password();
            $user->save();
        }
    }

    /**
     * Send the accesses to the backend
     */
    public function actionSendAccesses(){
        $users = User::find()->where(['!=','clear_password',''])->andWhere(['status' => User::STATUS_ACTIVE])->all();
        foreach ($users as $user) {
            if($user->school && $user->school->venues){
                Yii::$app->mailer->compose('access', ['user' => $user])
                    ->setFrom('milonga@milonga.be')
                    ->setTo($user->email)
                    ->setCc('milonga@milonga.be')
                    ->setSubject('Update your regular classes for September')
                    ->send();
                echo 'Sent access to '. $user->email.'<br>';
            }
        }
    }
}
