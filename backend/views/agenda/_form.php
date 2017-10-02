<?php
use backend\models\Event;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\datetime\DateTimePicker;
use rmrevin\yii\fontawesome\FA;
?>
<p>
	<div class="row">
		<div class="col-md-6 text-left">
			<a href="<?= Url::to(['agenda/index']) ?>" class=""><?= FA::icon('angle-left')?> Back to the events</a>
		</div>
	</div>
</p>
<?php

$form = ActiveForm::begin([
		'options' => ['enctype' => 'multipart/form-data']
	]);

$datepicker_options = [
	'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'dd-mm-yyyy hh:ii'
    ]
];
?>
<div class="row">
	<div class="col-md-6">
		<?= $form->field($event, 'start')->widget(DateTimePicker::classname(), $datepicker_options) ?>
	</div>
	<div class="col-md-6">
		<?= $form->field($event, 'end')->widget(DateTimePicker::classname(), $datepicker_options) ?>
	</div>
</div>
<?php
echo $form->field($event, 'type')->dropDownList(Event::getTypes()); 
echo $form->field($event, 'summary'); 
echo $form->field($event, 'location'); 
echo $form->field($event, 'description')->textarea(['rows' => 20]);

echo '<p class="text-right"><button type="submit" class="btn btn-success">Save</button></p>'; 

ActiveForm::end();
?>