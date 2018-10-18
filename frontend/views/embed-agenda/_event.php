<?php
use yii\web\View;
use common\components\Htmlizer;
?>
<div class="V13">
	<?php 
	if(isset($event['school']) && isset($event['school']['picture']) && !empty($event['school']['picture'])){
		echo '<a class="swipebox" title="'.$event['school']['name'].'" href="'.$event['school']['picture'].'"><img class="event_icon" src="'.$event['school']['picture'].'"></a>';
	}else{
		echo '<div class="event_icon">&nbsp;</div>';
	}
	if(isset($event['summary'])){ ?>
	<a name="<?= $event['id']?>"></a>
	<?php if(isset($event['school']['name'])){ ?><h5><?= $event['school']['name'] ?></h5><?php } ?>
	<h4 data-creator="<?= $event['creator']['email']?>" data-organizer="<?= $event['email']?>" data-id="<?= $event['id']?>">
		
		<?= $event['summary'] ?>
	</h4>
	<?php } ?>
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
	<div class="milonga-description">
		<?php
		if(isset($event['extendedProperties']['shared']['picture']) && !empty($event['extendedProperties']['shared']['picture'])){

			$pictureUrl = 'http://'.\Yii::$app->getRequest()->serverName.\Yii::$app->request->BaseUrl.'/../../uploads/events/'.$event['extendedProperties']['shared']['picture'];
			echo '<a href="'.$pictureUrl.'" class="swipebox img_mask" style="background-image:url('.$pictureUrl.');"></a><br/>';
		}
		$html = Htmlizer::execute($event);
		$html_lines = explode('<br />', $html);
		if(sizeof($html_lines) > 5){
			echo implode('<br />', array_slice($html_lines, 0, 5));
			echo '<a class="more-link" href="#">... READ MORE ...</a>';
			echo '<p class="more-content">'.implode('<br />', array_slice($html_lines, 5)).'</p>';
			echo '<a class="less-link" href="#"> -- LESS -- </a>';
		}else{
			echo $html;
		}
		?>
	</div>
</div>