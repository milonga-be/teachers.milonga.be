<?php
echo '<?xml version="1.0" encoding="utf8"?>';

function generateCData( $events ){
	$i = 0;
	$events_cdata = '<![CDATA[';
	foreach($events as $event){
		$new_day = false;
		if( !isset($events[$i-1]) || (new Datetime($events[$i-1]['start']['dateTime']))->format('Ymd') != (new Datetime($events[$i]['start']['dateTime']))->format('Ymd') ){ 
			if( isset($events[$i-1]) ){
				$events_cdata.='</ul>';
			}
			$events_cdata.='<h3>' . (new Datetime($event['start']['dateTime']))->format('l, F j') . '</h3><ul>';
		}
		$events_cdata.='<li>' . $event['summary'] . '<br /><small><i>' . (new Datetime($event['start']['dateTime']))->format('H:i') . (( isset($event['location']) )?' @ ' . $event['location']:'') . '</i></small></li>';
		$i++;

	}
	$events_cdata.='</ul>]]>';
	return $events_cdata;
}


?>
<rss version="2.0">
	<channel>
		<title>Milonga.be newsletter</title>
		<link><?= \Yii::$app->request->getAbsoluteUrl() ?></link>
		<description>News about Tango in Belgium</description>
		<language>en-us</language>
		<copyright>Copyright (C) 2017 www.milonga.be</copyright>
		<item>
			<title>Milongas this week in Belgium</title>
			<description></description>
			<link>http://www.milonga.be/dancing/</link>
			<description><?= generateCData( $milongas ) ?></description>
			<guid><?= microtime() ?></guid>
			<pubDate><?= (new \Datetime())->format(\Datetime::RSS) ?></pubDate>
		</item>
		<item>
			<title>Workshops this week in Belgium</title>
			<description></description>
			<link>http://www.milonga.be/dancing/</link>
			<description><?= generateCData( $workshops ) ?></description>
			<guid><?= microtime() ?></guid>
			<pubDate><?= (new \Datetime())->format(\Datetime::RSS) ?></pubDate>
		</item>
	</channel>
</rss>