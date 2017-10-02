<?php
use common\models\School;
use common\models\Venue;
use common\models\Lesson;
use yii\helpers\ArrayHelper;
use yii\web\View;

$postalcodes = array(
	1000 => 'Bruxelles',
	1050 => 'Ixelles',
	1150 => 'Woluwe-Saint-Pierre',
	1030 => 'Schaerbeek',
	1180 => 'Uccle',
	2140 => 'Borgerhout',
	1040 => 'Etterbeek',
	1348 => 'Ottignies Louvain-La-Neuve',
	1060 => 'Saint Gilles',
	1700 => 'Dilbeek',
	2170 => 'Merksem',
	1070 => 'Anderlecht',
	1140 => 'Evere',
	1200 => 'Woluwe-Saint-Lambert',
	1080 => 'Molenbeek',
	1160 => 'Auderghem',
	1170 => 'Watermael-Boitsfort',
	1325 => 'Corroy-le-Grand',
	2000 => 'Antwerpen',
	2018 => 'Antwerpen',
	2020 => 'Antwerpen',
	2800 => 'Mechelen',
	2640 => 'Mortsel',
	2060 => 'Antwerpen',
	2100 => 'Deurne',
	2600 => 'Berchem',
	2920 => 'Kalmthout',
	3020 => 'Herent',
	3470 => 'Kortenaken',
	3500 => 'Hasselt',
	4000 => 'Liège',
	4020 => 'Liège',
	4700 => 'Eupen',
	4430 => 'Ans',
	4800 => 'Verviers',
	7033 => 'Cuesmes',
	8000 => 'Bruges',
	8500 => 'Kortrijk',
	9000 => 'Gent',
	9040 => 'Gent',
	1380 => 'Lasne',
	8800 => 'Roeselare',
);
$page_url = 'http://milonga.be/classes/';
if(isset($_GET['url'])){
	$page_url = $_GET['url'];
}
echo '<table><tr>';
$i = 0;
$j = 0;
foreach($schools as $school){
	$school_url = $page_url.'#'.$school->id;
	?>
	<td style="width:33%;text-align: center;vertical-align: top;">
		<a href="<?= $school_url?>"><img style="height:75px;width:75px;border-radius:8px;margin-bottom:15px;" src="<?= $school->thumbUrl?>"/></a><br/>
		<a style="font-weight:bold;color: #F66062;text-decoration: none;font-size:0.95em;" href="<?= $school_url?>"><?= $school->name ?></a>
		<div style="font-size:0.8em">
		<?php
		$school_postalcodes = array();
		foreach ($schools_venues[ $school->id ] as $venue) {
			$school_postalcodes[$venue->postalcode] = $venue->postalcode;
		}
		foreach ($school_postalcodes as $postalcode) {
			echo $postalcode.' - '.$postalcodes[$postalcode].'<br>';
		}
		?>
		</div>
		<br>
	</td>
<?php
	$i++;
	$j++;
	if($i == 3){
		$i = 0;
		echo '</tr>';
		echo  '<tr>';
	}
}
echo '</tr></table>';
