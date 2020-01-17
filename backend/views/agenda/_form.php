<?php
use backend\models\Event;
use common\models\User;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\datetime\DateTimePicker;
use kartik\widgets\DatePicker;
 use kartik\time\TimePicker;
use rmrevin\yii\fontawesome\FA;
// use marqu3s\summernote\Summernote;
use dosamigos\tinymce\TinyMce;
use yii\helpers\ArrayHelper;
?>
<p>
	<div class="row">
		<div class="col-md-6 text-left">
			<a href="<?= Url::to(['agenda/index', 'page' => isset(Yii::$app->request->queryParams['page'])?Yii::$app->request->queryParams['page']:1]) ?>" class=""><?= FA::icon('angle-left')?> Back to the events</a>
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
<?php if(\Yii::$app->user->identity->isAdmin()){ ?>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($event, 'organizer')->dropDownList(ArrayHelper::map(User::find()->orderBy('email')->all(), 'email', 'email'), ['prompt' => 'Select the organizer']) ?>
	</div>
	<div class="col-md-12">
		<?= $form->field($event, 'disabled')->checkbox() ?>
	</div>
	<div class="col-md-12">
		<?= $form->field($event, 'disabled_reason')->textInput() ?>
	</div>
</div>
<?php } ?>
<?php if(!\Yii::$app->user->identity->isAdmin() && !empty($event->disabled_reason)){ ?>
<p class="bg-danger" style="padding:15px">
	<?= $event->disabled_reason ?>
</p>
<?php } ?>
<?php if(!$event->isRecurrent()){ ?>
<div class="row">
	<div class="col-md-6">
		<?= $form->field($event, 'start')->widget(DateTimePicker::classname(), 
			array_merge($datepicker_options, [
				'pluginEvents' => [
					'changeDate' => 'function(e) {  
						if(!$("#event-end").val()){ $("#event-end").val($("#event-start").val()); } 
					}'
				]
			])) ?>
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
echo $form->field($event, 'city')->dropDownList(Event::getCities()); 
echo $form->field($event, 'location'); 
echo $form->field($event, 'pictureFile')->fileInput();
echo '<input id="picture-remove" type="hidden" name="Event[pictureRemove]" value="0">';
if($event->picture){
	echo '<p id="picture-preview"><a class="swipebox img_mask" target="_blank" href="'.$event->pictureUrl.'" style="background-image:url('.$event->pictureUrl.');"></a>'.FA::icon('close').'</p>';
}
// echo $form->field($event, 'description')->textarea(['rows' => 10]);
// echo $form->field($event, 'description')->widget(Summernote::className(), 
// 	[
// 		'clientOptions' => [
// 			'toolbar' => [
// 				['style', ['bold', 'italic', 'underline', 'link', 'clear']]
// 			],
// 		]
// 	]);
echo $form->field($event, 'description')->widget(TinyMce::className(), 
	[
      'options' => [
         'rows' => 15,
      ],
      'clientOptions' => [
      	'plugins' => [
            "autolink link autoresize paste"
        ],
        'toolbar' => 'bold italic underline link | removeformat ',
        'menubar' => false,
        'branding' => false,
        'statusbar' => false,
    	// 'forced_root_blocks' => "",
    // 	'paste_preprocess' => new JsExpression('function(plugin, args) {
		  //   console.log(args.content);
		  //   args.content += " preprocess";
		  // }'),
		'paste_data_images' => false,
		'paste_as_text' => true,
    	'content_css' => '/backend/web/css/tinymce.css'
      ],
    ]);
echo '<p class="text-right">'.(($event->id)?'<a onclick="return confirm(\'Do you really want to delete this event ?\');" href="'.Url::to(['delete', 'id' => $event->id]).'" class="btn btn-danger">Delete</a> <a href="'.Url::to(['agenda/duplicate', 'id' => $event->id]).'" class="btn btn-primary">Copy</a>':'').'  <button type="submit" class="btn btn-success">'.($event->id?'Save':'Create').'</button></p>';

if(isset($event->id)){
?>
<p>
	<div class="row">
		<div class="col-md-12 text-right">
			<?php
			$start = new \DateTime($event->start);
			?>
			<a href="http://www.milonga.be/dancing/?u-year=<?= $start->format('Y') ?>&u-month=<?= $start->format('m') ?>&u-selected=<?= $start->format('Y-m-d') ?>#<?= $event->id ?>" target="_blank" class=""><?= FA::icon('eye')?> Click here to see on milonga.be</a>
		</div>
	</div>
</p>
<?php
}
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