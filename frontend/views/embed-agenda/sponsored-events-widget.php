<?php
use common\components\Htmlizer;
use common\components\Sponsorship;
use backend\models\Event;

$date_format_start = 'F j, H:i';
$date_format_end = 'H:i';
if($event['category'] == Event::TYPE_FESTIVAL || $event['category'] == Event::TYPE_HOLIDAYS || $event['category'] == Event::TYPE_MARATHON){
	$date_format_start = 'F j';
	$date_format_end = 'F j';
}

?>
<?php
foreach ($events as $event) { 
	$sponsored = Sponsorship::isEventSponsored($event);
	if($sponsored){
		$link = '/sponsored-event?u-id='.$event['id'];
	}else{
		$link = '/special-event?u-id='.$event['id'];
	}
	?>
	<div class="widget <?= $sponsored ? 'sponsored':'' ?>">
			<?php if($sponsored){ ?>
				<div class="label label-pink pull-right">In the spotlight</div>
			<?php } ?>
			<?php if(isset($event['extendedProperties']['shared']['picture']) && !empty($event['extendedProperties']['shared']['picture'])){

					$pictureUrl = 'https://'.\Yii::$app->getRequest()->serverName.\Yii::$app->request->BaseUrl.'/../../uploads/events/'.$event['extendedProperties']['shared']['picture'];
					echo '<a href="'.$link.'" style="display:block;width:100%;height:120px;background-size:cover;background-position: center;background-image:url('.$pictureUrl.');"></a><br/>';
				}
			?>
			<div class="summary special-event">

				<?php 
				if(isset($event['school']) && isset($event['school']['picture']) && !empty($event['school']['picture'])){
					echo '<a class="swipebox" title="'.$event['school']['name'].'" href="'.$event['school']['picture'].'"><img class="event_icon" src="'.$event['school']['picture'].'"></a>';
				}else{
					echo '<div class="event_icon">&nbsp;</div>';
				}?>
				<?php if(isset($event['school']['name'])){ ?>
					<h5>
						<?php if(isset($event['school']['url'])){ ?>
						<a href="<?= $event['school']['url'] ?>" target="_blank"><?= $event['school']['name'] ?></a>
						<?php }else{ ?>
							<?= $event['school']['name'] ?>
						<?php } ?>
					</h5>
				<?php } ?>
				<h3><?= $event['summary']?></h3>
				<h4 style="margin-top:0px;margin-bottom:5px;"><?= isset($event['category'])?$event['category']:''?><br/> 
					<?php if(isset($event['start']['dateTime'])){ ?>
						<?= (new Datetime($event['start']['dateTime']))->format($date_format_start) ?>
						<?php if(isset($event['end']['dateTime'])){ ?>
						  - 
						 <?= (new Datetime($event['end']['dateTime']))->format($date_format_end)?>
						 <?php } ?>
						 <br/>
					<?php }else if(isset($event['start']['date'])){ ?>
					<?= (new Datetime($event['start']['date']))->format($date_format_start) ?> <span class="glyphicon glyphicon-arrow-right"></span> <?= (new Datetime($event['end']['date']))->format($date_format_end); 
				} ?>
				</h4><br/>
				<div class="description">
					<?php
						if(strlen($event['description']) > 250){
							echo '<p>'.substr(strip_tags($event['description']), 0, 500).'...</p>';
							echo '<p><br><a href="'.$link.'">More info ...</a></p>';
						}else{
							echo Htmlizer::execute($event);
						}
					?>
					<br>&nbsp;
				</div>
			</div>
		</div>
<?php }