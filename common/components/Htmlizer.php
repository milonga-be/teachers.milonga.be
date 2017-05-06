<?php
namespace common\components;
class Htmlizer
{
    public static function execute($text) {
        $html=$text;
		$html=str_replace(" euro","&euro;",$html);
		$html=preg_replace("|http://([a-zA-Z0-9/_\-\.]+\.jp[e]?g)|", "<img src='httpx://$1' />", $html);
		$html=preg_replace("|https://([a-zA-Z0-9/_\-\.]+\.jp[e]?g)|", "<img src='httpx://$1' />", $html);
		$html=preg_replace("|http://([a-zA-Z0-9/_\-\.~\?=\&;]+)|", "<a target='_blank' href='http://$1'>$1</a>", $html);
		$html=preg_replace("|https://([a-zA-Z0-9/_\-\.~\?=\&;]+)|", "<a target='_blank' href='https://$1'>$1</a>", $html);
		$html=preg_replace("|([A-Za-z0-9._\-]+@[A-Za-z0-9\.]+\.[a-z]+)|", "<a href='mailto:$1'>$1</a>", $html);
		$html=str_replace("httpx://", "http://", $html);

		return nl2br($html);
    }
}