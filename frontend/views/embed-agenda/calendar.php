<?php
use yii\web\View;
use yii\helpers\Url;
use common\components\Htmlizer;

$prev_month = clone $month_first_day;
$prev_month->modify('-1 month');
$prev_month->modify('first day');
$next_month = clone $month_first_day;
$next_month->modify('+1 month');
$next_month->modify('first day');

if($this->context->embedded == true){
	$url_prev = '/dancing/?u-year='.$prev_month->format('Y').'&u-month='.$prev_month->format('m').'&u-city='.$city;
}else{
	$url_prev = Url::to(["agenda/calendar","year" => $prev_month->format('Y'), "month" => $prev_month->format('m'), 'city' => $city]);
}

if($this->context->embedded == true){
	$url_next = '/dancing/?u-year='.$next_month->format('Y').'&u-month='.$next_month->format('m').'&u-city='.$city;
}else{
	$url_next = Url::to(["agenda/calendar","year" => $next_month->format('Y'), "month" => $next_month->format('m'), 'city' => $city]);
}

$current_url = '/dancing/?u-year='.$month_first_day->format('Y').'&u-month='.$month_first_day->format('m');

?>
<p class="hidden-xs cities">
	<a class="<?= (($city == '')?'selected':'') ?>" href="<?= $current_url.'&u-city=' ?>"><img class="alignnone wp-image-4622" src="<?= Url::to('@web/img/milonga.be-all.png', true)?>" alt="" width="64" height="64"></a>&nbsp;
	<a class="<?= (($city == 'Brussels')?'selected':'') ?>" href="<?= $current_url.'&u-city=Brussels' ?>"><img class="alignnone wp-image-4622" src="<?= Url::to('@web/img/milonga.be-brussels-300x300.png', true)?>" alt="" width="64" height="64"></a>&nbsp;
	<a class="<?= (($city == 'Antwerpen')?'selected':'') ?>" href="<?= $current_url.'&u-city=Antwerpen' ?>"><img class="alignnone wp-image-4619" src="<?= Url::to('@web/img/milonga.be-antwerpen-300x300.png', true)?>" alt="" width="64" height="64"></a> 
	<a class="<?= (($city == 'Gent')?'selected':'') ?>" href="<?= $current_url.'&u-city=Gent' ?>"><img class="alignnone wp-image-4623" src="<?= Url::to('@web/img/milonga.be-gent-300x300.png', true)?>" alt="" width="64" height="64"></a>&nbsp;
	<a class="<?= (($city == 'Liège')?'selected':'') ?>" href="<?= $current_url.'&u-city=Liège' ?>"><img class="alignnone size-medium wp-image-4624" src="<?= Url::to('@web/img/milonga.be-liege-300x300.png', true)?>" alt="" width="64" height="64"></a>&nbsp;
	<a class="<?= (($city == 'Brugge')?'selected':'') ?>" href="<?= $current_url.'&u-city=Brugge' ?>"><img class="alignnone size-medium wp-image-4625" src="<?= Url::to('@web/img/milonga.be-brugge-300x300.png', true)?>" alt="" width="64" height="64"></a>
</p>
<div class="agenda-set">
	<h2>
		<a tabindex="0" id="search-icon" data-container="body" data-toggle="popover" data-placement="bottom" data-content="<form action=&quot;/dancing/agenda/search/&quot;><input class=&quot;form-control&quot; name=&quot;u-q&quot;></form>" data-html="true" class="glyphicon glyphicon-search pull-right"></a>

		<a class="prev-month glyphicon glyphicon-chevron-left" href="<?= $url_prev ?>"></a>
		<span><?= $month_first_day->format('F') ?></span>
		<a class="next-month glyphicon glyphicon-chevron-right" href="<?= $url_next ?>"></a>
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
						echo '<td class="agenda-day-' . $start->format('D') . (($selected)?' selected':'').(($other_month)?' other_month':'').' '.(isset($events_by_date[$start->format('Ymd')])?'has-event':'').'">';
							// if($yesterday > $start){
							// 	echo '<span class="text-muted">' . $start->format('d') . '</span>';
							// }else{
								echo '<a class="'.(($yesterday > $start)?"text-muted":"").'" href="#" data-day="' .$start->format('Ymd'). '">' . $start->format('d') . '</a>';
							// }
							
						echo '</td>';
						$start->modify('1 day');
					}
				echo '</tr>';
			}
			
			?>
		</table>
		<div class="events">
			<div id="empty-set" class="agenda-day <?= (!isset($events_by_date[$selected_day->format('Ymd')]))?'':'hidden' ?>">
				No event on this day
			</div>
		<?php
		
		foreach ($events_by_date as $date => $events_sets) {
			$eventDate = new \Datetime($date);
			?>
			<div class="agenda-day <?= ($eventDate->format('Ymd') == $selected_day->format('Ymd'))?"":"hidden" ?>" data-day="<?= $eventDate->format('Ymd') ?>">
				<h2 class="date"><?= $eventDate->format('l, F j')?></h2>
				<ul class="nav navbar-nav" id="navbar-agenda">
				<?php
				$set_index = 0;
				foreach ($events_sets as $set_name => $events) {
					echo '<li class="menu-item '.(($set_index==0)?'active':'').'"><a href="#" data-set="set-'.$eventDate->format('Ymd').'-'.$set_name.'">'.$set_name.'</a><em>'.sizeof($events).'</em></li>';
					$set_index++;
				}
				?>
				</ul>
				<?php
				$set_index = 0;
				foreach ($events_sets as $set_name => $events) {
					?>
					<div <?= $set_index>0?'style="display:none;"':'' ?>class="set" id="set-<?= $eventDate->format('Ymd') ?>-<?= $set_name?>">
					<h3><?= $set_name ?></h3>
					<?php
					if(sizeof($events) > 2){
						echo '<div class="quicklinks">';
						foreach ($events as $event) {
							$canceled = false;
							$festival = $event['category']=='FESTIVAL';
							if(isset($event['extendedProperties']['shared']['cancelled']) && !empty($event['extendedProperties']['shared']['cancelled']))
								$canceled = true;
							if(isset($event['school']) && isset($event['school']['picture']) && !empty($event['school']['picture'])){
								$start = (new Datetime($event['start']['dateTime']))->format('H:i');
								$end = (new Datetime($event['end']['dateTime']))->format('H:i');
								echo '<a data-toggle="popover" data-container="body" data-trigger="hover" data-placement="bottom" data-html="true" data-content="'.htmlentities((isset($event['school']['name'])?'<small>'.mb_strtoupper($event['school']['name']).'</small><br>':'').'<b '.($canceled?'class="title_canceled"':'').'>'.mb_strtoupper($event['summary']).'</b>'.($canceled?'<i class="text-danger"> CANCELED !</i>':'').'<br>'.(!$festival?$start.' - '.$end:'')).'" class="quicklink" href="#'.$event['id'].'">';
								echo '<img src="'.$event['school']['picture'].'"><br>';
								if(!$festival)
									echo $start;
								else
									echo '&nbsp;';
								echo '</a>';
							}
						}
						echo '</div><div class="clear"></div>';
					}
					?>
					<?php
					foreach ($events as $event) {
						echo $this->render('_event', ['event' => $event]);
					}
					?>
					</div>
					<?php
					$set_index++;
				}
				?>
		<!-- </div> -->
	</div>
	<?php } ?>
</div>