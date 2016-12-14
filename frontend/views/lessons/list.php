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
		<?= $school->name ?>
		<?= (isset($postalcodes))? ' (' . implode(', ', array_unique(ArrayHelper::getColumn( $schools_venues[ $school->id ] , 'postalcode') )) . ')' : '' ?>
	</h3>
	<p class="school-info">
		<?= ( $school->phone )? 'Tel: ' . $school->phone . '<br>':'' ?>
		<?= ( $school->email )? 'Email : <a href="mailto:' . $school->email . '">' . $school->email . '</a><br>':'' ?>
		<?= ( $school->facebook )? 'Facebook : <a href="' . $school->facebook . '">' . $school->facebook . '</a><br>':'' ?>
		<?= ( $school->website )? '<a href="' . $school->website . '">' . $school->website . '</a><br>':'' ?>
	</p>
	<?php
	foreach ($schools_venues[ $school->id ] as $venue) {
		?>
		<h4><?= $venue->name ?>, <?= $venue->address ?></h4>
		<table class="table striped condensed table-sm">
		<?php
		foreach ($venues_lessons[ $venue->id ] as $lesson) {
			?>
			<tr>
				<td class="dayname"><?= $lesson->dayname ?></td>
				<td class="starthour"><?= $lesson->start_hour ?></td>
				<td class="levelname"><?= $lesson->levelname ?></td>
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

$this->registerJs(
	'
	if(window.parent && window.parent.resizeIframe){
		window.parent.resizeIframe();
	}',
	View::POS_LOAD
);
?>
