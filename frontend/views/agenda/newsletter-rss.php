<?php

function generateEventsCData( $events ){
	// $i = 0;
	$events_cdata = '';
	foreach($events as $event){
		$new_day = false;
		$cancelled = isset($event['extendedProperties']['shared']['cancelled']) && !empty($event['extendedProperties']['shared']['cancelled']);
		if(!isset($event['start']['dateTime'])){
			$event['start']['dateTime'] = $event['start']['date'];
		}
		if( !isset($previous_event) || (new Datetime($previous_event['start']['dateTime']))->format('Ymd') != (new Datetime($event['start']['dateTime']))->format('Ymd') ){
			if( isset($previous_event) ){
				$events_cdata.='</table><br>';
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
		if(isset($event['school']['name'])){
			$events_cdata.='<h6 style="text-transform:uppercase;margin-bottom:0;color:#555;font-weight:normal;">';
				if(isset($event['school']['url'])){
					$events_cdata.='<a style="color:#555;text-decoration:none;" href="'.$event['school']['url'].'" target="_blank">'.$event['school']['name'].'</a>';
				}else{
					$events_cdata.=$event['school']['name'];
				}
			$events_cdata.='</h6>';
		}
		$events_cdata.='<a style="text-transform:uppercase;text-decoration:none;'.($cancelled?'text-decoration: line-through;':'').'color:black;" target="_blank" href="http://www.milonga.be/dancing/?u-selected='.(new Datetime($event['start']['dateTime']))->format('Y-m-d').'#'.$event['id'].'">';
		
		$events_cdata.=htmlspecialchars($event['summary']) . '</a>'.($cancelled?'<i> Canceled !</i>':'').'<br />';
		$events_cdata.='<div style="padding-bottom:3px;">';
		if(!isset($event['start']['date']))
			$events_cdata.='<span style="font-size:0.8em;color:#777;">' . (new Datetime($event['start']['dateTime']))->format('H:i').' - '.(new Datetime($event['end']['dateTime']))->format('H:i').'</span> ';
		$events_cdata.='<span style="font-size:0.8em;color:#aaa;">' . (( isset($event['location']) )?'' . htmlspecialchars($event['location']).'<br>' : '') . '</span>';
		$events_cdata.='</div>';
		if( isset($event['category']) && !empty($event['category'])){
			$events_cdata.='<span style="font-size:0.7em;color:#777;padding:3px;background-color:#e7e7e7;border-radius:5px;">' . strtoupper($event['category']) . '</span> ';
		}
		if( isset($event['city']) && !empty($event['city'])){
			$events_cdata.='<span style="font-size:0.7em;color:#777;padding:3px;background-color:#e7e7e7;border-radius:5px;">' . strtoupper($event['city']) . '</span>';
		}
		$events_cdata.='</td></tr>';
		$previous_event = $event;

	}
	$events_cdata.='</table>';
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

if($debug){
	echo generateEventsCData( $events );
}else{ 
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0">
	<channel>
		<title>Milonga.be Newsletter</title>
		<link><?= htmlentities(\Yii::$app->request->getAbsoluteUrl()) ?></link>
		<description>News about Tango in Belgium</description>
		<language>en-us</language>
		<copyright>Copyright (C) 2017 www.milonga.be</copyright>
		<item>
			<title></title>
			<link>http://www.milonga.be/dancing/</link>
			<description><![CDATA[<?= generateEventsCData( $events ) ?>]]></description>
			<pubDate><?= (new \Datetime())->format(\Datetime::RSS) ?></pubDate>
		</item>
	</channel>
</rss>
<? } ?>