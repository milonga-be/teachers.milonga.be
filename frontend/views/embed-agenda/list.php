<?php
use yii\web\View;
use common\components\Htmlizer;
$i = 0;
?>
<div class="events" data-nb="<?= sizeof($events) ?>">
	<?php
	foreach ($events as $event) {
	?>
	<?php if( !isset($events[$i-1]) || (new Datetime($events[$i-1]['start']['dateTime']))->format('Ymd') != (new Datetime($events[$i]['start']['dateTime']))->format('Ymd') ){ ?>
	<h3 class="V12"><?= (new Datetime($event['start']['dateTime']))->format('l, F j')?></h3>
	<?php } ?>
	<div class="V13">
		<?php if(isset($event['category'])){ ?>
		<h6><?= strtoupper($event['category'])?></h6>
		<?php } ?>
		<h4><?= $event['summary'] ?></h4>
		<div class="milonga-data">
			<?= (new Datetime($event['start']['dateTime']))->format('H:i') ?> - <?= (new Datetime($event['end']['dateTime']))->format('H:i')?><br/>
			
			<?php if( isset($event['location']) ){ ?>
			<?= $event['location']?>
			<?php } ?>
		</div>
		<?php if( isset($event['description']) ){ ?>
		<div class="milonga-description">
			<?= Htmlizer::execute($event)?>
		</div>
		<?php } ?>
	</div>
	<?php
	$i++;
	}
	?>
</div>