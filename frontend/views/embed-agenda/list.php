<?php
use yii\web\View;
use common\components\Htmlizer;
$previous_event = null;
?>
<div class="events" data-nb="<?= sizeof($events) ?>">
	<?php
	foreach ($events as $event) {
	?>
	<?php if( !isset($previous_event) || (new Datetime($previous_event['start']['dateTime']))->format('Ymd') != (new Datetime($event['start']['dateTime']))->format('Ymd') ){ ?>
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
			<?= (new Datetime($event['start']['dateTime']))->format('H:i') ?> - <?= (new Datetime($event['end']['dateTime']))->format('H:i')?><br/>
			
			<?php if( isset($event['location']) ){ ?>
			<?= $event['location']?>
			<?php } ?>
		</div>
		<div class="milonga-description">
			<?php
			if(isset($event['extendedProperties']['shared']['picture']) && !empty($event['extendedProperties']['shared']['picture'])){
				$pictureUrl = 'http://'.\Yii::$app->getRequest()->serverName.\Yii::$app->request->BaseUrl.'/../../uploads/events/'.$event['extendedProperties']['shared']['picture'];
				echo '<a href="'.$pictureUrl.'" class="swipebox img_mask" style="background-image:url('.$pictureUrl.');"></a><br/>';
			}
			?>
			<?= Htmlizer::execute($event)?>
		</div>
	</div>
	<?php
	$previous_event = $event;
	}
	?>
</div>