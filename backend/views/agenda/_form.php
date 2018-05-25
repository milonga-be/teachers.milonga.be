<?php
use backend\models\Event;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\datetime\DateTimePicker;
use kartik\widgets\DatePicker;
 use kartik\time\TimePicker;
use rmrevin\yii\fontawesome\FA;
use marqu3s\summernote\Summernote;
?>
<p>
	<div class="row">
		<div class="col-md-6 text-left">
			<a href="<?= Url::to(['agenda/index']) ?>" class=""><?= FA::icon('angle-left')?> Back to the events</a>
		</div>
		<?php if($event->masterId){ ?>
		<div class="col-md-6 text-right">
			<a href="<?= Url::to(['agenda/update', 'id' => $event->masterId]) ?>" class="btn btn-primary">Edit the recurring event</a>
		</div>
		<?php } ?>
	</div>
</p>
<?php
echo '<!-- ';
var_dump($event);
echo '-->';

$form = ActiveForm::begin([
		'options' => ['enctype' => 'multipart/form-data']
	]);

$datepicker_options = [
	'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'dd-mm-yyyy hh:ii',
        'weekStart' => 1
    ]
];

$timepicker_options = 
    [
        'addon' => '',
        'addonOptions' => [ 'asButton' => FALSE ],
        'pluginOptions' => [ 'showMeridian' => FALSE, 'template' => FALSE ]
    ];
?>
<?php if(!$event->isRecurrent()){ ?>
<div class="row">
	<div class="col-md-6">
		<?= $form->field($event, 'start')->widget(DateTimePicker::classname(), $datepicker_options) ?>
	</div>
	<div class="col-md-6">
		<?= $form->field($event, 'end')->widget(DateTimePicker::classname(), $datepicker_options) ?>
	</div>
</div>
<?php }else{ ?>
<!-- 
<?php
var_dump($event->raw_recurrence);
?>
-->
<?php if($event->id){ ?>
<p class="bg-warning" style="padding:15px">
	Modifying the model modifies all future events (but not the infos that have been modified separately)
</p>
<?php } ?>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($event, 'recurrence_every')->dropDownList(Event::getRecurrenceEveryList()) ?>
	</div>
	<div class="col-md-6">
		<?= $form->field($event, 'start_hour')->widget(TimePicker::classname(), $timepicker_options); ?>
	</div>
	<div class="col-md-6">
		<?= $form->field($event, 'end_hour')->widget(TimePicker::classname(), $timepicker_options); ?>
	</div>
	<div class="col-md-12">
		<?= $form->field($event, 'from')->widget(DatePicker::classname(), [
		'pluginOptions' => [
	        'autoclose'=>true,
	        'format' => 'dd-mm-yyyy',
	        'weekStart' => 1
	    ]]); ?>
		<?= $form->field($event, 'until')->widget(DatePicker::classname(), [
		'options' => [
			'placeholder' => 'Optional'
		],
		'pluginOptions' => [
	        'autoclose'=>true,
	        'format' => 'dd-mm-yyyy',
	        'weekStart' => 1
	    ]]); ?>
	</div>
</div>
<?php } ?>
<?php
echo $form->field($event, 'type')->dropDownList(Event::getTypes()); 
echo $form->field($event, 'summary'); 
echo $form->field($event, 'location'); 
echo $form->field($event, 'pictureFile')->fileInput();
echo '<input id="picture-remove" type="hidden" name="Event[pictureRemove]" value="0">';
if($event->picture){
	echo '<p id="picture-preview"><a class="swipebox img_mask" target="_blank" href="'.$event->pictureUrl.'" style="background-image:url('.$event->pictureUrl.');"></a>'.FA::icon('close').'</p>';
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

echo '<p class="text-right">'.(($event->id)?'<a onclick="return confirm(\'Do you really want to delete this event ?\');" href="'.Url::to(['delete', 'id' => $event->id]).'" class="btn btn-danger">Delete</a> <a href="'.Url::to(['agenda/duplicate', 'id' => $event->id]).'" class="btn btn-primary">Copy</a>':'').'  <button type="submit" class="btn btn-success">'.($event->id?'Save':'Create').'</button></p>';

ActiveForm::end();

$this->registerJs(
	'$(".swipebox").swipebox({useSVG : false});
	$("#picture-preview .fa-close").on("click", function(e){
		e.preventDefault();
		$("#picture-preview").hide();
		$("#picture-remove").val(1);
	});
	$("#event-recurrence_every").on("change", function(e){
		
	});
	'
);
?>