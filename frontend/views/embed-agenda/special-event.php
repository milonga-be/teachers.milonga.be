<?php
use common\components\Htmlizer;
?>
<p>
	<a href="/events/"><span class="glyphicon glyphicon-chevron-left"></span> Back to the special events</a>
</p>
<?php if(isset($event->picture) && !empty($event->picture)){

		$pictureUrl = 'https://'.\Yii::$app->getRequest()->serverName.\Yii::$app->request->BaseUrl.'/../../uploads/events/'.$event->picture;
		echo '<a href="'.$pictureUrl.'" class="swipebox" style="display:block;width:100%;height:275px;background-size:cover;background-position: center;background-image:url('.$pictureUrl.');"></a><br/>';
	}
	?>
	<div class="summary special-event">
	<h3><?= $event->summary?></h3>
	<h4 style="margin-top:0px;margin-bottom:5px;"><?= $event->type?><br/> 
		<?php if(isset($event->start)){ ?>
			<?= (new Datetime($event->start))->format('F j') ?>
			<?php if(isset($event->end)){ ?>
			  - 
			 <?= (new Datetime($event->end))->format('F j')?>
			 <?php } ?>
			 <br/>
		<?php }else if(isset($event->start)){ ?>
		<?= (new Datetime($event->start))->format('F j') ?> <span class="glyphicon glyphicon-arrow-right"></span> <?= (new Datetime($event->end))->format('F j'); 
	} ?>
	</h4><br/>
	<div class="description">
		<?= Htmlizer::execute($event) ?>
	</div>
</div>