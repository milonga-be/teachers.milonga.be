<?php
use yii\web\View;
use yii\helpers\Url;
use common\components\Htmlizer;

$this->registerJs(
	'$(".milonga-description .more-link").on("click",function(e){
		e.preventDefault();
		var desc_elt = $(this).parent();
		$(this).hide();
		desc_elt.find(".more-content").css("opacity", 0).slideDown("normal",function(){
			desc_elt.find(".less-link").show();
			window.parent.resizeIframe();
		}).animate(
    		{ opacity: 1 },
    		{ queue: false, duration: "normal" }
  		);
	});

	$(".milonga-description .less-link").on("click",function(e){
		e.preventDefault();
		var desc_elt = $(this).parent();
		$(this).fadeOut();
		desc_elt.find(".more-content").slideUp("normal",function(){ 
			desc_elt.find(".more-link").show();
			
			window.parent.resizeIframe();
		}).animate(
    		{ opacity: 0 },
    		{ queue: false, duration: "normal" }
  		);
	});'
);
$prev_month = clone $month_first_day;
$prev_month->modify('-1 month');
$prev_month->modify('first day');
$next_month = clone $month_first_day;
$next_month->modify('+1 month');
$next_month->modify('first day');
?>
<div class="agenda-set">
	<h2>
		<a class="prev-month glyphicon glyphicon-chevron-left" href="<?= Url::to(["agenda/calendar","year" => $prev_month->format('Y'), "month" => $prev_month->format('m')])?>"></a>
		<span><?= $month_first_day->format('F') ?></span>
		<a class="next-month glyphicon glyphicon-chevron-right" href="<?= Url::to(["agenda/calendar","year" => $next_month->format('Y'), "month" => $next_month->format('m')])?>"></a>
	</h2>
	<table id="agenda-calendar" class="calendar table table-striped condensed">
		<tr class="agenda-daynames">
				<!--td/-->
				<th>Mon.</th>
				<th>Tue.</th>
				<th>Wed.</th>
				<th>Thu.</th>
				<th>Fri.</th>
				<th class="agenda-day-Sat">Sat.</th>
				<th>Sun.</th>
			</tr>
			<?php
			$yesterday = new \Datetime();
			$today = new \Datetime();
			$yesterday->modify('-1 day');
			for ($i=0; $i < $weeks; $i++) {
				$endOfWeek = clone $start;
				$endOfWeek->modify('+6 day');
				echo '<tr>';
					// if($endOfWeek->format('M') != $start->format('M') || $i==0){
					// 	echo '<td>'.$endOfWeek->format('M').'</td>';
					// }else{
					// 	echo '<td/>';
					// }
					for ($j=0; $j < 7; $j++) {
						$selected = ($selected_day->format('Ymd') == $start->format('Ymd'));
						$other_month = ($start->format('m') != $month_first_day->format('m'));
						echo '<td class="agenda-day-' . $start->format('D') . (($selected)?' selected':'').(($other_month)?' other_month':'').'">';
							if($yesterday > $start){
								echo '<span class="text-muted">' . $start->format('d') . '</span>';
							}else{
								echo '<a href="#" data-day="' .$start->format('Ymd'). '">' . $start->format('d') . '</a>';
							}
							
						echo '</td>';
						$start->modify('1 day');
					}
				echo '</tr>';
			}
			
			?>
		</table>
		<div class="events">
		<?php
		$set_index = 0;
		foreach ($events_by_date as $date => $events_sets) {
			$eventDate = new \Datetime($date);
			?>
			<div class="agenda-day <?= ($eventDate->format('Ymd') == $selected_day->format('Ymd'))?"":"hidden" ?>" data-day="<?= $eventDate->format('Ymd') ?>">
				<h2 class="date"><?= $eventDate->format('l, F j')?></h2>
				<?php
				foreach ($events_sets as $set_name => $events) {
					?>
					<div class="set">
					<h3><?= $set_name ?></h3>
					<?php
					foreach ($events as $event) {
					?>
					<div class="V13">
						<?php if(isset($event['summary'])){ ?>
						<h4><?= $event['summary'] ?></h4>
						<?php } ?>
						<div class="milonga-data">
							<?php if(isset($event['category'])){ ?>
							<?= strtoupper($event['category'])?><br>
							<?php } ?>
							<?php if(isset($event['start']['dateTime'])){ ?>
								<?= (new Datetime($event['start']['dateTime']))->format('H:i') ?> - <?= (new Datetime($event['end']['dateTime']))->format('H:i')?><br/>
							<?php }else if(isset($event['start']['date'])){ ?>
								<?= (new Datetime($event['start']['date']))->format('D, F j') ?> <span class="glyphicon glyphicon-arrow-right"></span> <?= (new Datetime($event['end']['date']))->format('D, F j')?><br/>
							<?php } ?>
							<?php if( isset($event['location']) ){ ?>
							<?= $event['location']?>
							<?php } ?>
						</div>
						<?php
						// https://www.googleapis.com/drive/v2/files/fileId&alt=media
						// if(isset($event['attachments'][0])){
						// 	$file = $event['attachments'][0];
						// 	echo '<img src="'.Url::to(['agenda/event-picture','fileId' => $file['fileId']]).'"/>';
						// }
						?>
						<?php if( isset($event['description']) ){ ?>
						<div class="milonga-description">
							<?php
							$html = Htmlizer::execute($event['description']);
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
						<?php } ?>
					</div>
					<?php
					}
					?>
					</div>
					<?php
				}
				?>
		<!-- </div> -->
	</div>
	<?php } ?>
</div>