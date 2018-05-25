<?php
use common\models\User;
use common\models\School;
use yii\helpers\ArrayHelper;

echo $form->field($user, 'school')->dropDownList(
            ArrayHelper::map(School::find()->all(),'id', 'name'),
            ['prompt'=>'']    // options
        );
echo $form->field($user, 'username');
echo $form->field($user, 'email');
echo $form->field($user, 'clear_password');
echo $form->field($user, 'status')->dropDownList([User::STATUS_ACTIVE => 'Active' , User::STATUS_DELETED => 'Deleted']);