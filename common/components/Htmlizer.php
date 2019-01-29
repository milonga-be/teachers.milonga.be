<?php
namespace common\components;
class Htmlizer
{
    public static function execute($event) {
    	if(isset($event['description'])){
    		$html=$event['description'];
    	}else{
    		$html = '';
    	}
        // $html = str_replace('</p>', '<br />', $html);
        // Only for old non modified texts
        if(strpos($html, '<br') === false && strpos($html, '<p') === false && strpos($html, '<a') === false){
            $html = nl2br($html);
        }
        $html = str_replace("\n", "", $html);
        $html = preg_replace('/ style=("|\')(.*?)("|\')/','',$html);
        $html = preg_replace('/ class=("|\')(.*?)("|\')/','',$html);
        $html = strip_tags($html, '<br><a><b><i><p><strong><em>');
        $html = str_replace('<br>', '<br />', $html);

    	$encode_url = true;
    	if(strpos($html, '<a ')){
    		$encode_url = false;
    	}
        
        $html=preg_replace("|http://([a-zA-Z0-9/_\-\.]+\.jp[e]?g)|", "<a href=\"httpx://$1\" class=\"swipebox img_mask\" style=\"background-image:url(httpx://$1);\"></a>", $html);
		$html=preg_replace("|https://([a-zA-Z0-9/_\-\.]+\.jp[e]?g)|", "<a href=\"httpsx://$1\" class=\"swipebox img_mask\" style=\"background-image:url(httpsx://$1);\"></a>", $html);
        if($encode_url){
        	// $html=str_replace(" euro","&euro;",$html);
			
			$html=preg_replace("|http://([a-zA-Z0-9/_\-\.~\?=\&;]+)|", "<a target='_blank' href='http://$1'>$1</a>", $html);
			$html=preg_replace("|https://([a-zA-Z0-9/_\-\.~\?=\&;]+)|", "<a target='_blank' href='https://$1'>$1</a>", $html);
			$html=preg_replace("|([A-Za-z0-9._\-]+@[A-Za-z0-9\.]+\.[a-z]+)|", "<a href='mailto:$1'>$1</a>", $html);
        }
        $html=str_replace("httpx://", "http://", $html);
		$html=str_replace("httpsx://", "https://", $html);

		// $html = nl2br($html);
        $html = preg_replace('#<br />(\s*<br />)+#', '<br /><br />', $html);
        $html = preg_replace('#(( ){0,}<br( {0,})(/{0,1})>){1,}$#i', '', $html);
        $html = preg_replace('#^(( ){0,}<br( {0,})(/{0,1})>){1,}#i', '', $html);


        if(!strpos($html, 'mailto:') && isset($event['email']) && $event['email'] != 'milonga@milonga.be' && $event['email'] != 'bverdeye@gmail.com'){
            $html.="<br>More info : <a href=\"mailto:".$event['email']."\">".$event['email']."</a>";
        }
        return $html;
    }
}