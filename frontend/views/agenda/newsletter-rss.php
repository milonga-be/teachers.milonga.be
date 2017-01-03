<?php
echo '<?xml version="1.0" encoding="utf8"?>';

function generateEventsCData( $events ){
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

function generatePicturesCData( $pictures ){
	$i = 0;
	$cdata = '<![CDATA[';
	foreach($pictures as $picture){
		$cdata.='<a href="' . $picture['url_o'] . '"><img height="120" title="' . $picture['title'] . '" src="' . $picture['url_q'] . '" /></a> ';
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
			<guid><?= microtime() ?></guid>
			<pubDate><?= (new \Datetime())->format(\Datetime::RSS) ?></pubDate>
		</item>
		<item>
			<title>Milongas this week in Belgium</title>
			<description></description>
			<link>http://www.milonga.be/dancing/</link>
			<description><?= generateEventsCData( $milongas ) ?></description>
			<guid><?= microtime() ?></guid>
			<pubDate><?= (new \Datetime())->format(\Datetime::RSS) ?></pubDate>
		</item>
		<item>
			<title>Workshops this week in Belgium</title>
			<description></description>
			<link>http://www.milonga.be/dancing/</link>
			<description><?= generateEventsCData( $workshops ) ?></description>
			<guid><?= microtime() ?></guid>
			<pubDate><?= (new \Datetime())->format(\Datetime::RSS) ?></pubDate>
		</item>
	</channel>
</rss>