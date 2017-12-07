<?php
use common\models\School;
use common\models\Venue;
use common\models\Lesson;
use yii\helpers\ArrayHelper;
use  yii\web\View;

foreach($schools as $school){
	?>
	<div class="school">
	<h3>
		<a name="<?= $school->id?>"></a>
		<?php
		if($school->picture){
			echo '<a class="swipebox" href="'.$school->pictureUrl.'"><img style="height:100px;float:left;margin-right:10px;border-radius:8px;margin-bottom:10px;" src="'.$school->thumbUrl.'"/></a>';
		}
		?>
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
		<h4><?= $venue->name ?>, <?= $venue->address ?></h4>
		<table class="lessons table striped condensed table-sm">
		<?php
		foreach ($venues_lessons[ $venue->id ] as $lesson) {
			?>
			<tr>
				<td class="dayname">
					<span class="sm"><?= $lesson->abbrevdayname ?></span>
					<span class="lg"><?= $lesson->dayname ?></span>
				</td>
				<td class="starthour"><?= $lesson->start_hour ?></td>
				<td class="levelname">
					<span class="sm"><?= $lesson->abbrevlevelname ?></span>
					<span class="lg"><?= $lesson->levelname ?></span>
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
	<?php
}