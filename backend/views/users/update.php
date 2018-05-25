<?php
use common\models\User;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
		'options' => ['enctype' => 'multipart/form-data']
	]);
$this->title = 'Update user';
echo '<h1>Update user</h1>';

echo $this->render('_form', ['form' => $form, 'user' => $user]);
echo '<p class="text-right"><button type="submit" class="btn btn-success">Save</button></p>';

ActiveForm::end();
?>