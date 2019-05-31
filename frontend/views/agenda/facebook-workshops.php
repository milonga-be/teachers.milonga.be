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

$schools = array();

?>

A workshop, pleaaase ! <br/>
This week tango workshops : <?= $startDate->format('M j') ?> &rarr; <?= $endDate->format('M j') ?><br><br>

<?php
foreach ( $events as $weekday => $events ) {
	echo $weekdays[$weekday].'<br>';
	uasort($events, 'cmp');
	foreach ($events as $event) {
		$datetime = new Datetime($event['start']['dateTime']);
		if(!in_array(ucfirst($event['school']['name']), $schools))
			$schools[] = ucfirst($event['school']['name']);
		echo $datetime->format('H:i').' - '/*.strtoupper($event['school']['name']).': '*/;
		echo ucwords(strtolower(str_replace('@', ' @ ', $event['summary'])));
		echo ' ('.ucfirst($event['school']['name']).')';
		echo '<br>';
	}
	echo '<br>';
}
/*echo 'KNOW MORE<br>';
foreach ($schools as $school) {
	echo $school.' : <br/>';
}*/
?>
Know more : http://www.milonga.be/dancing/ <br>
Subscribe to the newsletter :
http://www.milonga.be/about/newsletter/
