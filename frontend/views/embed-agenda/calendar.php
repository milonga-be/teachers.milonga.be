<?php
use yii\web\View;
use yii\helpers\Url;
use common\components\Htmlizer;
use backend\models\Event;

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
<form id="city-selector" class="visible-block cities" action="/dancing/" method="GET">
	<input type="hidden" name="u-year" value="<?= $month_first_day->format('Y') ?>">
	<input type="hidden" name="u-month" value="<?= $month_first_day->format('m') ?>">
	<select  class="form-control" name="u-city" onchange="jQuery('#city-selector').submit();">
		<?php foreach(Event::getCities() as $city_item => $city_label){ 
			if($city_item == '') $city_label = 'All Belgium';
			?>
			<option value="<?= $city_item ?>" <?= ($city == $city_item)?'selected':''?> ><?= $city_label ?></option>
		<?php } ?>
	</select>
</form>
<div class="agenda-set">
	<h2>
		<a tabindex="0" id="search-icon" data-container="body" data-toggle="popover" data-placement="bottom" data-content="&lt;form action=&quot;/dancing/agenda/search/&quot;&gt;&lt;input class=&quot;form-control&quot; name=&quot;u-q&quot;/&gt;&lt;/form&gt;" data-html="true" class="glyphicon glyphicon-search pull-right"></a>

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
				$menu_item_size = (sizeof($events_sets)>2)?floor(100 / sizeof($events_sets)) - sizeof($events_sets) + 1 :48;
				foreach ($events_sets as $set_name => $events) {
					echo '<li style="width:'.$menu_item_size.'%;" class="menu-item '.(($set_index==0)?'active':'').'"><a href="#" data-set="set-'.$eventDate->format('Ymd').'-'.$set_name.'">'.((sizeof($events_sets)>2 && strlen($set_name) > 10)?substr($set_name, 0, 8).'...':$set_name).'</a><em>'.sizeof($events).'</em></li>';
					$set_index++;
				}
				?>
				</ul>
				<?php
				$set_index = 0;
				foreach ($events_sets as $set_name => $events) {
					echo $this->render('_event-set', ['events' => $events, 'set_index' => $set_index, 'eventDate' => $eventDate, 'set_name' => $set_name]);
					$set_index++;
				}
				?>
		<!-- </div> -->
	</div>
	<?php } ?>
</div>