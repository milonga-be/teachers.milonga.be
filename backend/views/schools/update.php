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

echo '<p class="text-right"><button type="submit" class="btn btn-success">Save</button></p>';

ActiveForm::end();
?>