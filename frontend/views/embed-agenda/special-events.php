<?php
use common\components\Htmlizer;
use common\components\Sponsorship;
use backend\models\Event;

?>
<?php
foreach ($events as $event) {
	$date_format_start = 'F j, H:i';
	$date_format_end = 'H:i';
	if($event['category'] == Event::TYPE_FESTIVAL || $event['category'] == Event::TYPE_HOLIDAYS || $event['category'] == Event::TYPE_MARATHON){
		$date_format_start = 'F j';
		$date_format_end = 'F j';
	}
	$sponsored = Sponsorship::isEventSponsored($event);
	if($sponsored){
		$more_link = '/sponsored-event?u-id='.$event['id'];
		$link = $more_link;
		if(isset($event['extendedProperties']['shared']['url']) && !empty($event['extendedProperties']['shared']['url']))
			$link = $event['extendedProperties']['shared']['url'];
	}else{
		$more_link = '/special-event?u-id='.$event['id'];
		$link = $more_link;
	}
	?>
	<div class="col-lg-4 col-md-6 eq-blocks">
		<div class="post-block <?= $sponsored ? 'sponsored':'' ?>">
			<?php if($sponsored){ ?>
				<div class="label label-pink pull-right">In the spotlight</div>
			<?php } ?>
			<?php if(isset($event['extendedProperties']['shared']['picture']) && !empty($event['extendedProperties']['shared']['picture'])){

					$pictureUrl = 'https://'.\Yii::$app->getRequest()->serverName.\Yii::$app->request->BaseUrl.'/../../uploads/events/'.$event['extendedProperties']['shared']['picture'];
					echo '<a href="'.$link.'" style="display:block;width:100%;height:275px;background-size:cover;background-position: center;background-image:url('.$pictureUrl.');"></a><br/>';
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
					<?php if(isset($event['start']['dateTime']) && $event['category'] != Event::TYPE_CLASS){ ?>
						<?= (new Datetime($event['start']['dateTime']))->format($date_format_start) ?>
						<?php if(isset($event['end']['dateTime']) && $event['end']['dateTime'] != $event['start']['dateTime']){ ?>
						  - 
						 <?= (new Datetime($event['end']['dateTime']))->format($date_format_end)?>
						 <?php } ?>
						 <br/>
					<?php }else if(isset($event['start']['date']) && $event['category'] != Event::TYPE_CLASS){ ?>
					<?= (new Datetime($event['start']['date']))->format($date_format_start) ?> <span class="glyphicon glyphicon-arrow-right"></span> <?= (new Datetime($event['end']['date']))->format($date_format_end); 
				} ?>
				</h4><br/>
				<div class="description">
					<?php
						if(strlen($event['description']) > 250){
							echo '<p>'.substr(strip_tags($event['description']), 0, 500).'...</p>';
							echo '<p><br><a href="'.$more_link.'">More info ...</a></p>';
						}else{
							echo Htmlizer::execute($event);
						}
					?>
					<br>&nbsp;
				</div>
			</div>
		</div>
	</div>
<?php }