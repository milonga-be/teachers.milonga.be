<?php
use common\components\Htmlizer;
?>
<div class="row post-list">
<?php
foreach ($events as $event) { ?>
	<div class="col-lg-4 col-md-6">
		<div class="post-block eq-blocks">
			<?php if(isset($event['extendedProperties']['shared']['picture']) && !empty($event['extendedProperties']['shared']['picture'])){

					$pictureUrl = 'http://'.\Yii::$app->getRequest()->serverName.\Yii::$app->request->BaseUrl.'/../../uploads/events/'.$event['extendedProperties']['shared']['picture'];
					echo '<a href="'.$pictureUrl.'" class="swipebox" style="display:block;width:100%;height:250px;background-size:cover;background-position: center;background-image:url('.$pictureUrl.');"></a><br/>';
				}
			?>
			<div class="summary">
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
				<?= Htmlizer::execute($event) ?>
			</div>
		</div>
	</div>
<?php }