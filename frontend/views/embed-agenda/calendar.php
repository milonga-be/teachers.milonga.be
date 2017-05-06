<?php
use yii\web\View;
use common\components\Htmlizer;
?>
<div class="agenda-set">
	<table id="agenda-calendar" class="calendar table table-striped condensed">
		<tr class="agenda-daynames">
			<td/>
				<td>Mon.</td>
				<td>Tue.</td>
				<td>Wed.</td>
				<td>Thu.</td>
				<td>Fri.</td>
				<td class="agenda-day-Sat">Sat.</td>
				<td>Sun.</td>
			</tr>
			<?php
			$yesterday = new \Datetime();
			$today = new \Datetime();
			$yesterday->modify('-1 day');
			for ($i=0; $i < $weeks; $i++) {
				echo '<tr>';
					echo '<td>'.$start->format('M').'</td>';
					for ($j=0; $j < 7; $j++) {
						echo '<td class="agenda-day-' . $start->format('D') . '">';
							if($yesterday > $start){
								echo '<span class="text-muted">' . $start->format('d') . '</span>';
							}else{
								echo '<a class="' . (($today->format('Ymd') == $start->format('Ymd'))? 'selected':'') . '" href="#" data-day="' .$start->format('Ymd'). '">' . $start->format('d') . '</a>';
							}
							
						echo '</td>';
						$start->modify('1 day');
					}
				echo '</tr>';
			}
			$i = 0;
			?>
		</table>
		<div class="events" data-nb="<?= sizeof($events) ?>">
			<?php
			foreach ($events as $event) {
				$eventDate = new Datetime($event['start']['dateTime']);
			?>
			<?php if( !isset($events[$i-1]) || (new Datetime($events[$i-1]['start']['dateTime']))->format('Ymd') != (new Datetime($events[$i]['start']['dateTime']))->format('Ymd') ){ ?>
		<?= ($i!=0)?'</div>':'' ?>
		<div class="agenda-day <?= ($eventDate->format('Ymd') == $today->format('Ymd'))?"":"hidden" ?>" id="<?= $eventDate->format('Ymd') ?>"><h3 class="V12"><?= (new Datetime($event['start']['dateTime']))->format('l, F j')?></h3>
			<?php } ?>
			<div class="V13">
				<?php if(isset($event['category'])){ ?>
				<h6><?= strtoupper($event['category'])?></h6>
				<?php } ?>
				<h4><?= $event['summary'] ?></h4>
				<div class="milonga-data">
					<?= (new Datetime($event['start']['dateTime']))->format('H:i') ?> - <?= (new Datetime($event['end']['dateTime']))->format('H:i')?><br/>
					
					<?php if( isset($event['location']) ){ ?>
					<?= $event['location']?>
					<?php } ?>
				</div>
				<?php if( isset($event['description']) ){ ?>
				<div class="milonga-description">
					<?= Htmlizer::execute($event['description'])?>
				</div>
				<?php } ?>
			</div>
			<?php
			$i++;
			}
			?>
		</div>
	</div>
</div>