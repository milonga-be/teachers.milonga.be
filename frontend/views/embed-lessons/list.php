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
	<div class="col-lg-4 col-md-6 eq-blocks">
    <div class="post-block">
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
	<p class="school-info">
		<?= ( $school->phone )? '<a target="_blank" href="tel:' . $school->phone . '"><i class="fa fa-phone-square" aria-hidden="true"></i> ' . $school->phone . '</a> ':'' ?>
		<?= ( $school->email )? '<a href="mailto:' . $school->email . '"><i class="fa fa-envelope" aria-hidden="true"></i> ' . $school->email . '</a><br>':'' ?>
		<?= ( $school->facebook )? '<a target="_blank" href="' . $school->facebook . '"><i class="fa fa-facebook-square" aria-hidden="true"></i> On Facebook</a> ':'' ?>
		<?= ( $school->website )? '<a target="_blank" href="' . (( strpos($school->website,'http') === FALSE )? 'http://' . $school->website : $school->website) . '"><i class="fa fa-globe" aria-hidden="true"></i> Website</a><br>':'' ?>
		<?= ( $school->flyer )? '<a class="swipebox" href="' . $school->flyerUrl . '"><i class="fa fa-paperclip" aria-hidden="true"></i> Flyer</a>':'' ?>
	</p>
	<?php if($school->description){ ?>
		<p class="school-description"><?= nl2br($school->description) ?></p>
	<?php } ?>
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
	</div></div>
	<?php
}
?>
</div>