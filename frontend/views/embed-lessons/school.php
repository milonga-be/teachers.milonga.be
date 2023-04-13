<?php
if($school->picture){
?>
<a class="swipebox" title="<?= $school->name ?>"  href="<?= $school->pictureUrl ?>" style="display:block;width:100%;height:300px;background-size:cover;background-position: center;background-image:url(<?= $school->pictureUrl ?>);">
</a>
<?php } ?>
<h1>
	<a name="<?= $school->id?>"></a>
	<?= $school->name ?>
	<?= (isset($postalcodes))? ' (' . implode(', ', array_unique(ArrayHelper::getColumn( $schools_venues[ $school->id ] , 'postalcode') )) . ')' : '' ?>
</h1>
<?= $this->render('_summary', ['school' => $school]) ?>
<div class="schedules">
	<?php
	foreach ($school->venues as $venue) {
		if(sizeof($venue->lessons)){
		?>
		<h4 style="margin-top:20px"><?= $venue->name ?>, <?= $venue->address ?></h4>
		<table class="lessons table striped condensed table-sm" style="font-size:0.9em">
		<?php
		foreach ($venue->lessons as $lesson) {
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
	}
	?>
	</div>
	</div>
	</div>
</div>