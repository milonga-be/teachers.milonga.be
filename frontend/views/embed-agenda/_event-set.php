<?php
use common\components\Sponsorship;
?>
<div <?= $set_index>0?'style="display:none;"':'' ?>class="set" id="set-<?= $eventDate->format('Ymd') ?>-<?= $set_name?>">
	<h3><?= $set_name ?></h3>
	<?php
	if(sizeof($events) > 2){
		echo '<div class="quicklinks">';
		foreach ($events as $event) {
			$canceled = false;
			$festival = ($event['category']=='FESTIVAL' || $event['category']=='MARATHON');
			if(isset($event['extendedProperties']['shared']['cancelled']) && !empty($event['extendedProperties']['shared']['cancelled']))
				$canceled = true;
			if(isset($event['school']) && isset($event['school']['picture']) && !empty($event['school']['picture'])){
				$start = (new Datetime($event['start']['dateTime']))->format('H:i');
				$end = (new Datetime($event['end']['dateTime']))->format('H:i');
				echo '<a data-toggle="popover" data-container="body" data-trigger="hover" data-placement="bottom" data-html="true" data-content="'.htmlentities((isset($event['school']['name'])?'<small>'.mb_strtoupper($event['school']['name']).'</small><br>':'').'<b '.($canceled?'class="title_canceled"':'').'>'.mb_strtoupper($event['summary']).'</b>'.($canceled?'<i class="text-danger"> CANCELED !</i>':'').'<br>'.(!$festival?$start.' - '.$end:'')).'" class="quicklink" href="#'.$event['id'].'">';
				echo '<img src="'.$event['school']['picture'].'"><br>';
				if(!$festival)
					echo $start;
				else
					echo '&nbsp;';
				echo '</a>';
			}
		}
		echo '</div><div class="clear"></div>';
	}
	?>
	<?php
	// First displaying the sponsored events
	foreach ($events as $event) {
		if(Sponsorship::isEventSponsored($event))
			echo $this->render('_event', ['event' => $event]);
	}
	// Then the normal list
	foreach ($events as $event) {
		if(!Sponsorship::isEventSponsored($event))
			echo $this->render('_event', ['event' => $event]);
	}
	?>
</div>