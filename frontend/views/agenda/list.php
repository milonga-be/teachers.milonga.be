<?php

function htmlize($text){
	$html=$text;
	$html=str_replace(" euro","&euro;",$html);
	$html=preg_replace("|http://([a-zA-Z0-9/_\-\.]+\.jp[e]?g)|", "<img src='httpx://$1' />", $html);
	$html=preg_replace("|https://([a-zA-Z0-9/_\-\.]+\.jp[e]?g)|", "<img src='httpx://$1' />", $html);
	$html=preg_replace("|http://([a-zA-Z0-9/_\-\.~\?=\&;]+)|", "<a target='_blank' href='http://$1'>$1</a>", $html);
	$html=preg_replace("|https://([a-zA-Z0-9/_\-\.~\?=\&;]+)|", "<a target='_blank' href='https://$1'>$1</a>", $html);
	$html=preg_replace("|([A-Za-z0-9._\-]+@[A-Za-z0-9\.]+\.[a-z]+)|", "<a href='mailto:$1'>$1</a>", $html);
	$html=str_replace("httpx://", "http://", $html);

	return nl2br($html);
}

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
		<h4><?= $event['summary'] ?></h4>
		<div class="milonga-data">
		<?= (new Datetime($event['start']['dateTime']))->format('H:i') ?> - <?= (new Datetime($event['end']['dateTime']))->format('H:i')?><br>
		<?php if( isset($event['location']) ){ ?>
		<?= $event['location']?>
		<?php } ?>
		</div>
		<?php if( isset($event['description']) ){ ?>
		<div class="milonga-description">
			<?= htmlize($event['description'])?>
		</div>
		<?php } ?>
	</div>
	<?php
	$i++;
}
?>
</div>