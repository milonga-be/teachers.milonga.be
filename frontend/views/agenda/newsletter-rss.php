<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';

function generateEventsCData( $events ){
	// $i = 0;
	$events_cdata = '<![CDATA[';
	foreach($events as $event){
		$new_day = false;
		if(!isset($event['start']['dateTime'])){
			$event['start']['dateTime'] = $event['start']['date'];
		}
		if( !isset($previous_event) || (new Datetime($previous_event['start']['dateTime']))->format('Ymd') != (new Datetime($event['start']['dateTime']))->format('Ymd') ){
			if( isset($previous_event) ){
				$events_cdata.='</table>';
			}
			$events_cdata.='<h5 style="font-family:sans-serif;color: #F66062;text-transform: uppercase;margin-bottom:15px;font-size:1.1em;font-weight:normal;margin-top:5px;">' . (new Datetime($event['start']['dateTime']))->format('l, F j') . '</h5><table>';
		}
		$events_cdata.='<tr>';
		if(isset($event['school']) && isset($event['school']['thumb']) && !empty($event['school']['thumb'])){
			$events_cdata.='<td valign="top"><img style="border-radius:50%;" width="40" height="40" src="'.$event['school']['thumb'].'"/>&nbsp;</td>';
		}else{
			$events_cdata.= '<td>&nbsp;</td>';
		}
		$events_cdata.='<td style="line-height:1.2;padding-bottom:20px;font-family:sans-serif;">';
		$events_cdata.='<a style="text-transform:uppercase;text-decoration:none;color:black;" target="_blank" href="http://www.milonga.be/dancing/?u-selected='.(new Datetime($event['start']['dateTime']))->format('Y-m-d').'#'.$event['id'].'">';
		
		$events_cdata.=htmlspecialchars($event['summary']) . '</a><br />';
		if( isset($event['category'])){
			$events_cdata.='<span style="font-size:0.8em;color:#777;">' . strtoupper($event['category']) . '</span><br/>';
		}
		if(!isset($event['start']['date']))
			$events_cdata.='<span style="font-size:0.8em;color:#777;">' . (new Datetime($event['start']['dateTime']))->format('H:i').' - '.(new Datetime($event['end']['dateTime']))->format('H:i').'</span><br/>';
		$events_cdata.='<span style="font-size:0.8em;color:#777;">' . (( isset($event['location']) )?'' . htmlspecialchars($event['location']) : '') . '</span></td></tr>';
		$previous_event = $event;

	}
	$events_cdata.='</table>]]>';
	return $events_cdata;
}

function generatePicturesCData( $pictures ){
	$i = 0;
	$cdata = '<![CDATA[';
	foreach($pictures as $picture){
		$cdata.='<a target="_blank" href="http://www.milonga.be/tango-photos/"><img title="' . htmlspecialchars($picture['title']) . '" src="' . $picture['url_q'] . '" /></a> ';
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
		<?php foreach ($posts as $post) { ?>
		<item>
			<title><?= $post->get_title() ?></title>
			<description><![CDATA[<?= $post->get_description() ?><a href="<?= $post->get_link() ?>">Read more...</a>]]></description>
			<link><?= $post->get_link() ?></link>
			<pubDate><?= $post->get_date(\Datetime::RSS) ?></pubDate>
		</item>
		<? } ?>
		<!--item>
			<title>Recent Tango Pictures</title>
			<description><?= generatePicturesCData( $pictures ) ?></description>
			<link>http://www.milonga.be/tango-photos/</link>
			<pubDate><?= (new \Datetime())->format(\Datetime::RSS) ?></pubDate>
		</item-->
		<item>
			<title></title>
			<link>http://www.milonga.be/dancing/</link>
			<description><?= generateEventsCData( $milongas ) ?></description>
			<pubDate><?= (new \Datetime())->format(\Datetime::RSS) ?></pubDate>
		</item>
	</channel>
</rss>