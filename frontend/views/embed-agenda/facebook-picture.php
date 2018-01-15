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
		<?php 
		if(isset($event['school']) && isset($event['school']['picture']) && !empty($event['school']['picture'])){
			echo '<a class="swipebox" title="'.$event['school']['name'].'" href="'.$event['school']['picture'].'"><img class="event_icon" src="'.$event['school']['picture'].'"></a>';
		}else{
			echo '<div class="event_icon">&nbsp;</div>';
		}
		?>
		<h4><?= $event['summary'] ?></h4>
		<div class="milonga-data">
			<?php if(isset($event['category'])){ ?>
			<?= strtoupper($event['category'])?>
			<?php } ?>
			<div class="hours">
				<?php if(isset($event['start']['dateTime'])){ ?>
					<?= (new Datetime($event['start']['dateTime']))->format('H:i') ?>
					<?php if(isset($event['end']['dateTime'])){ ?>
					 - 
					 <?= (new Datetime($event['end']['dateTime']))->format('H:i')?>
					 <?php } ?>
					 <br/>
				<?php }else if(isset($event['start']['date'])){ ?>
				<?= (new Datetime($event['start']['date']))->format('D, F j') ?> <span class="glyphicon glyphicon-arrow-right"></span> <?= (new Datetime($event['end']['date']))->format('D, F j')?><br/>
				<?php } ?>
			</div>
			<?php if( isset($event['location']) ){ ?>
			<?= $event['location']?>
			<?php } ?>
		</div>
	</div>
	<?php
	$i++;
}
?>
</div>