<?php
use common\components\Htmlizer;
use backend\models\Event;

$date_format_start = 'F j, H:i';
$date_format_end = 'H:i';
if($event->type == Event::TYPE_FESTIVAL || $event->type == Event::TYPE_HOLIDAYS || $event->type == Event::TYPE_MARATHON){
	$date_format_start = 'F j';
	$date_format_end = 'F j';
}

?>
<?php if(!$event->sponsored): ?>
<p>
	<a href="/events/"><span class="glyphicon glyphicon-chevron-left"></span> Back to the special events</a>
</p>
<?php endif ?>
<?php if(isset($event->picture) && !empty($event->picture)){

		$pictureUrl = 'https://'.\Yii::$app->getRequest()->serverName.\Yii::$app->request->BaseUrl.'/../../uploads/events/'.$event->picture;
		echo '<a href="'.((!empty($event->url))?$event->url:$pictureUrl).'" class="'.((!empty($event->url))?'':'swipebox').'" style="display:block;width:100%;"><img src="'.$pictureUrl.'"/></a><br/>';
	}
	?>
	<div class="summary special-event">
	<h3><?= $event->summary?></h3>
	<h4 style="margin-top:0px;margin-bottom:5px;"><?= $event->type?><br/> 
		<?php if(isset($event->start) && $event->type != Event::TYPE_CLASS){ ?>
			<?= (new Datetime($event->start))->format($date_format_start) ?>
			<?php if(isset($event->end) && $event->end != $event->start){ ?>
			  - 
			 <?= (new Datetime($event->end))->format($date_format_end)?>
			 <?php } ?>
			 <br/>
		<?php }else if(isset($event->start) && $event->type != Event::TYPE_CLASS){ ?>
		<?= (new Datetime($event->start))->format($date_format_start) ?> <span class="glyphicon glyphicon-arrow-right"></span> <?= (new Datetime($event->end))->format($date_format_end); 
	} ?>
	</h4><br/>
	<div class="description">
		<?= Htmlizer::execute($event) ?>
	</div>
</div>