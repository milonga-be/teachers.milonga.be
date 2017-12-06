<?php
use backend\models\Event;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\datetime\DateTimePicker;
use rmrevin\yii\fontawesome\FA;
use marqu3s\summernote\Summernote;
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
echo $form->field($event, 'pictureFile')->fileInput();
if($event->picture){
	echo '<p><a class="swipebox img_mask" target="_blank" href="'.$event->pictureUrl.'" style="background-image:url('.$event->pictureUrl.');"></a></p>';
}
// echo $form->field($event, 'description')->textarea(['rows' => 10]);
echo $form->field($event, 'description')->widget(Summernote::className(), 
	[
		'clientOptions' => [
			'toolbar' => [
				['style', ['bold', 'italic', 'underline', 'link', 'clear']]
			],
		]
	]);

echo '<p class="text-right">'.(($event->id)?'<a onclick="return confirm(\'Do you really want to delete this event ?\');" href="'.Url::to(['delete', 'id' => $event->id]).'" class="btn btn-danger">Delete</a>':'').' <button type="submit" class="btn btn-success">Save</button></p>';

ActiveForm::end();

$this->registerJs(
	'$(".swipebox").swipebox({useSVG : false});'
);
?>