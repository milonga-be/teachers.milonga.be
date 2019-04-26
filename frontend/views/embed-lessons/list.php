<?php
use common\models\School;
use common\models\Venue;
use common\models\Lesson;
use yii\helpers\ArrayHelper;
use  yii\web\View;

?>
<div class="row post-list">
<?php
foreach($schools as $school){
	?>
	<div class="col-lg-4 col-md-6">
    <div class="post-block eq-blocks">
    	<?php
		if($school->picture){
		?>
    	<a class="swipebox" title="<?= $school->name ?>"  href="<?= $school->pictureUrl ?>" style="display:block;width:100%;height:250px;background-size:cover;background-position: center;background-image:url(<?= $school->pictureUrl ?>);">

    	</a>
    	<?php } ?>
	<div class="summary">
	<h3>
		<a name="<?= $school->id?>"></a>
		<?= $school->name ?>
		<?= (isset($postalcodes))? ' (' . implode(', ', array_unique(ArrayHelper::getColumn( $schools_venues[ $school->id ] , 'postalcode') )) . ')' : '' ?>
	</h3>
	<?= $this->render('_summary', ['school' => $school]) ?>
	<p id="schedule-button">
		<a href="#" title="" onclick="jQuery(this).parent().parent().find('.schedules').toggle();jQuery(this).hide();jQuery(this).next().show();equalheight('.eq-blocks'); return false;" class="readmore">More </a>
		<a href="#" title="" style="display:none;" onclick="jQuery(this).parent().parent().find('.schedules').toggle();jQuery(this).hide();jQuery(this).prev().show();equalheight('.eq-blocks'); return false;" class="readmore">Less </a>
	</p>
	<div class="schedules">
	<?php
	foreach ($schools_venues[ $school->id ] as $venue) {
		?>
		<h4 style="margin-top:20px"><?= $venue->name ?>, <?= $venue->address ?></h4>
		<table class="lessons table striped condensed table-sm" style="font-size:0.9em">
		<?php
		foreach ($venues_lessons[ $venue->id ] as $lesson) {
			?>
			<tr>
				<td class="dayname" title="<?= $lesson->dayname ?>">
					<?= $lesson->abbrevdayname ?>
				</td>
				<td class="starthour"><?= $lesson->start_hour ?></td>
				<td class="levelname">
					<span class=""><?= $lesson->abbrevlevelname ?></span>
					<!--span class="lg"><?= $lesson->levelname ?></span-->
				</td>
				<td class="teachers"><?= $lesson->teachers ?></td>
			</tr>
			<?php
		}
		?>
		</table>
		<?php
	}
	?>
	</div>
	</div>
	</div></div>
	<?php
}
?>
</div>