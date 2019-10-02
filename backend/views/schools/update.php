<?php
use common\models\School;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

$form = ActiveForm::begin([
		'options' => ['enctype' => 'multipart/form-data']
	]);
$this->title = 'Your organization';
echo '<h1>Your organization</h1>';

echo $this->render('_form', ['form' => $form, 'school' => $school]);
echo '<p class="text-right"><button type="submit" class="btn btn-success">Save</button></p>';

ActiveForm::end();
?>