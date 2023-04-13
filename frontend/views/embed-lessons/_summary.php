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