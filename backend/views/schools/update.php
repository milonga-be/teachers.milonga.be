<?php
use common\models\School;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

$form = ActiveForm::begin([
		'options' => ['enctype' => 'multipart/form-data']
	]);
$this->title = 'Your school';
echo '<h1>Your school</h1>';

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
echo '<p class="text-right"><button type="submit" class="btn btn-success">Save</button></p>';

ActiveForm::end();
?>