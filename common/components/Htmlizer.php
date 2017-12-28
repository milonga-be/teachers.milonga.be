<?php
namespace common\components;
class Htmlizer
{
    public static function execute($event) {
        $html=$event['description'];
		$html=str_replace(" euro","&euro;",$html);
		$html=preg_replace("|http://([a-zA-Z0-9/_\-\.]+\.jp[e]?g)|", "<a href=\"httpx://$1\" class=\"swipebox img_mask\"><img src='httpx://$1' /></a>", $html);
		$html=preg_replace("|https://([a-zA-Z0-9/_\-\.]+\.jp[e]?g)|", "<a href=\"httpx://$1\" class=\"swipebox img_mask\"><img src='httpx://$1' /></a>", $html);
		$html=preg_replace("|http://([a-zA-Z0-9/_\-\.~\?=\&;]+)|", "<a target='_blank' href='http://$1'>$1</a>", $html);
		$html=preg_replace("|https://([a-zA-Z0-9/_\-\.~\?=\&;]+)|", "<a target='_blank' href='https://$1'>$1</a>", $html);
		$html=preg_replace("|([A-Za-z0-9._\-]+@[A-Za-z0-9\.]+\.[a-z]+)|", "<a href='mailto:$1'>$1</a>", $html);
		$html=str_replace("httpx://", "http://", $html);

		if(!strpos($html, 'mailto:') && isset($event['email']) && $event['email'] != 'milonga@milonga.be' && $event['email'] != 'bverdeye@gmail.com'){
			$html.="\nMore info : <a href=\"mailto:".$event['email']."\">".$event['email']."</a>";
		}

		return nl2br($html);
    }
}