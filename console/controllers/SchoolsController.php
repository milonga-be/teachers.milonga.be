<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\School;
use common\models\User;

/**
 * Site controller
 */
class SchoolsController extends Controller{

    /**
     * Send a message to an account admin before expiration
     */
    function actionMessageBeforeExpiration(){
        $datetime = new \Datetime();
        $datetime->modify('+1 month');
        $schools = School::find()->where(['=', 'expiration', $datetime->format('Y-m-d')])->all();
        foreach($schools as $school){
            $users_emails = \yii\helpers\ArrayHelper::getColumn($school->getUsers()->where(['status' => User::STATUS_ACTIVE])->all(), 'email');
            $message = Yii::$app->mailer->compose('@backend/mail/expiring', ['school' => $school])
                    ->setFrom('milonga@milonga.be')
                    ->setCc('milonga@milonga.be')
                    ->setTo($users_emails)
                    ->setSubject('Your account on milonga.be is about to expire')
                    ->send();
            echo 'Sent expiration message to '. implode(',', $users_emails).PHP_EOL;
        }
    }
}