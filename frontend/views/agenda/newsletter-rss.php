<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';

function generateEventsCData( $events ){
	$i = 0;
	$events_cdata = '<![CDATA[';
	foreach($events as $event){
		$new_day = false;
		if( !isset($events[$i-1]) || (new Datetime($events[$i-1]['start']['dateTime']))->format('Ymd') != (new Datetime($events[$i]['start']['dateTime']))->format('Ymd') ){ 
			if( isset($events[$i-1]) ){
				$events_cdata.='</ul>';
			}
			$events_cdata.='<h5>' . (new Datetime($event['start']['dateTime']))->format('l, F j') . '</h5><ul>';
		}
		$events_cdata.='<li>' . htmlspecialchars($event['summary']) . '<br /><small><i>' . (new Datetime($event['start']['dateTime']))->format('H:i') . (( isset($event['location']) )?' @ ' . htmlspecialchars($event['location']) : '') . '</i></small></li>';
		$i++;

	}
	$events_cdata.='</ul>]]>';
	return $events_cdata;
}

function generatePicturesCData( $pictures ){
	$i = 0;
	$cdata = '<![CDATA[';
	foreach($pictures as $picture){
		$cdata.='<a href="http://www.milonga.be/tango-photos/"><img title="' . htmlspecialchars($picture['title']) . '" src="' . $picture['url_sq'] . '" /></a> ';
	}
	$cdata.=']]>';
	return $cdata;
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
			<title>Recent Tango Pictures</title>
			<description><?= generatePicturesCData( $pictures ) ?></description>
			<link>http://www.milonga.be/tango-photos/</link>
			<pubDate><?= (new \Datetime())->format(\Datetime::RSS) ?></pubDate>
		</item>
		<item>
			<title>Milongas this week in Belgium</title>
			<link>http://www.milonga.be/dancing/</link>
			<description><?= generateEventsCData( $milongas ) ?></description>
			<pubDate><?= (new \Datetime())->format(\Datetime::RSS) ?></pubDate>
		</item>
		<item>
			<title>Workshops this week in Belgium</title>
			<link>http://www.milonga.be/dancing/</link>
			<description><?= generateEventsCData( $workshops ) ?></description>
			<pubDate><?= (new \Datetime())->format(\Datetime::RSS) ?></pubDate>
		</item>
	</channel>
</rss>