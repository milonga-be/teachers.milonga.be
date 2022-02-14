<?php
use common\components\Htmlizer;
?>
<div class="row post-list">
<?php
foreach ($events as $event) { ?>
	<div class="col-lg-4 col-md-6">
		<div class="post-block eq-blocks">
			<?php if(isset($event['extendedProperties']['shared']['picture']) && !empty($event['extendedProperties']['shared']['picture'])){

					$pictureUrl = 'https://'.\Yii::$app->getRequest()->serverName.\Yii::$app->request->BaseUrl.'/../../uploads/events/'.$event['extendedProperties']['shared']['picture'];
					echo '<a href="/special-event?u-id='.$event['id'].'" style="display:block;width:100%;height:275px;background-size:cover;background-position: center;background-image:url('.$pictureUrl.');"></a><br/>';
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
				<h4 style="margin-top:0px;margin-bottom:5px;"><?= $event['category']?><br/> 
					<?php if(isset($event['start']['dateTime'])){ ?>
						<?= (new Datetime($event['start']['dateTime']))->format('F j') ?>
						<?php if(isset($event['end']['dateTime'])){ ?>
						  - 
						 <?= (new Datetime($event['end']['dateTime']))->format('F j')?>
						 <?php } ?>
						 <br/>
					<?php }else if(isset($event['start']['date'])){ ?>
					<?= (new Datetime($event['start']['date']))->format('F j') ?> <span class="glyphicon glyphicon-arrow-right"></span> <?= (new Datetime($event['end']['date']))->format('F j'); 
				} ?>
				</h4><br/>
				<div class="description">
					<?php
						if(strlen($event['description']) > 250){
							echo '<p>'.substr(strip_tags($event['description']), 0, 500).'...</p>';
							echo '<p><br><a href="/special-event?u-id='.$event['id'].'">More info ...</a></p>';
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