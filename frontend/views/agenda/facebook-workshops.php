<?php

// var_dump($events);
// die();
$weekdays = array(1 => 'MONDAY', 2 => 'TUESDAY', 3 => 'WEDNESDAY', 4 => 'THURSDAY', 5 => 'FRIDAY', 6 => 'SATURDAY', 7 => 'SUNDAY');

function cmp($a, $b) {
    if ($a['start']['dateTime'] == $b['start']['dateTime']) {
        return 0;
    }
    return ($a['start']['dateTime'] < $b['start']['dateTime']) ? -1 : 1;
}

?>

A workshop, pleaaase ! <br/>
This week tango workshops : <?= $startDate->format('M j') ?> &rarr; <?= $endDate->format('M j') ?><br>
http://www.milonga.be/dancing/<br><br>

<?php
foreach ( $events as $weekday => $events ) {
	echo $weekdays[$weekday].'<br>';
	uasort($events, 'cmp');
	foreach ($events as $event) {
		$datetime = new Datetime($event['start']['dateTime']);
		echo $datetime->format('H:i').' - _____: ';
		echo ucwords(strtolower(str_replace('@', ' @ ', $event['summary'])));

		echo '<br>';
	}
	echo '<br>';
}
echo 'Know more :<br>';
