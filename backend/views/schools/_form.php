<?php
use kartik\file\FileInput;

if(\Yii::$app->user->identity->isAdmin())
	echo $form->field($school, 'active')->checkbox(); 
echo $form->field($school, 'name'); 
echo $form->field($school, 'address');
echo $form->field($school, 'email');
echo $form->field($school, 'facebook');
echo $form->field($school, 'website');
echo $form->field($school, 'phone');
echo $form->field($school, 'pictureFile')->fileInput();
if($school->picture){
	echo '<p><a target="_blank" href="'.$school->pictureUrl.'">See current picture</a></p>';
}
echo $form->field($school, 'flyerFile')->fileInput();
if($school->flyer){
	echo '<p><a target="_blank" href="'.$school->flyerUrl.'">See current flyer</a></p>';
}
echo $form->field($school, 'description')->textarea(['rows' => 5]);