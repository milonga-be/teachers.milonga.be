<?php
use common\models\Venue;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin();
echo $form->field($venue, 'name');
echo $form->field($venue, 'postalcode');
echo $form->field($venue, 'address');

echo '<p class="text-right"><button type="submit" class="btn btn-success">Save</button></p>';

ActiveForm::end();
?>