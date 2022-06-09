<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Hello,</p>

    <p>You have requested a reset of your password.<br>
    Follow the link below to set a new one yourself :</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>

    <p>Have a nice day,</p>

    <p>
--<br>
Boris Verdeyen Pazmiño<br>
milonga@milonga.be<br>
https://www.facebook.com/Milongabe/
    </p>
</div>
