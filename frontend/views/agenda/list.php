<?php

use yii\web\View;
use common\components\Htmlizer;

$this->registerJs(
	"$('.milonga-description').expander({
	  slicePoint: 300,
	  widow: 2,
	  expandEffect: 'slideDown',
	  collapseEffect: 'slideUp',
	  expandText: '...READ MORE',
	  userCollapseText: 'LESS',
	  afterExpand : function(){ $(this).find('.details').css('display', 'inline'); window.parent.resizeIframe();  },
	  afterCollapse : function(){ window.parent.resizeIframe(); },
	});"
);

$this->registerJs(
	'
	if(window.parent && window.parent.resizeIframe){
		window.parent.resizeIframe();
	}',
	View::POS_LOAD
);

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
		<?php if(isset($event['category'])){ ?>
			<h6><?= strtoupper($event['category'])?></h6>
		<?php } ?>
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
	<?php
	$i++;
}
?>
</div>