<?php
use common\models\School;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
		'options' => ['enctype' => 'multipart/form-data']
	]);
$this->title = 'New school';
echo '<h1>New school</h1>';

echo $this->render('_form', ['form' => $form, 'school' => $school]);
echo '<p class="text-right"><button type="submit" class="btn btn-success">Create</button></p>';

ActiveForm::end();
?>