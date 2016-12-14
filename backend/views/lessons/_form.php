<?php
use common\models\Lesson;
use common\models\Venue;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\time\TimePicker;

$form = ActiveForm::begin([
		'options' => ['enctype' => 'multipart/form-data']
	]);


$timepicker_options = 
    [
        'addon' => '',
        'addonOptions' => [ 'asButton' => FALSE ],
        'pluginOptions' => [ 'showMeridian' => FALSE, 'template' => FALSE ]
    ];

echo $form->field($lesson, 'venue_id')->dropDownList(Venue::getSchoolVenues());
echo $form->field($lesson, 'day')->dropDownList(Lesson::getDaysList());
echo $form->field($lesson, 'start_hour')->widget(TimePicker::classname(), $timepicker_options);
// echo $form->field($lesson, 'end_hour')->widget(TimePicker::classname(), $timepicker_options);
echo $form->field($lesson, 'level')->dropDownList(Lesson::getLevelsList());
echo $form->field($lesson, 'teachers');

echo '<p class="text-right"><button type="submit" class="btn btn-success">Save</button></p>';

ActiveForm::end();
?>