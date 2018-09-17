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
	5380 => 'Namur Fernelmont',
	8870 => 'Izegem',
	3470 => 'Kortenaken',
	1357 => 'Hélécine',
	7321 => 'Blaton',
);
$page_url = 'http://milonga.be/classes/';
if(isset($_GET['url'])){
	$page_url = $_GET['url'];
}
echo '<table><tr>';
$i = 0;
$j = 0;
foreach($schools as $school){
	// $school_url = $page_url.'#'.$school->id;
	if($school->website){
		$school_url = $school->website;
	}else if($school->facebook){
		$school_url = $school->facebook;
	}else if($school->email){
		$school_url = 'mailto:'.$school->email;
	}else{
		$school_url = '#';
	}
	?>
	<td style="width:33%;vertical-align: top;">
		<a class="swipebox" title="<?= $school->name ?>"  href="<?= $school_url ?>" style="display:block;width:100%;height:250px;background-size:cover;background-position: center;background-image:url(<?= $school->pictureUrl ?>);">
    	</a>
		<div style="font-size:11px;padding:10px;">
			<a style="font-weight:normal;font-family: Roboto;text-transform:uppercase;font-size: 19.5px;color: rgb(102, 102, 102);" href="<?= $school_url?>"><?= $school->name ?></a>
			<p style="color: rgb(246, 96, 98);margin-bottom: 5px;">
			<?php
			$school_postalcodes = array();
			foreach ($schools_venues[ $school->id ] as $venue) {
				$school_postalcodes[$venue->postalcode] = $venue->postalcode;
			}
			$first = true;
			foreach ($school_postalcodes as $postalcode) {
				if(!$first){
					echo ' / ';
				}
				if(isset($postalcodes[$postalcode]))
					echo $postalcode.' - '.$postalcodes[$postalcode];
				else
					echo $postalcode;
				$first = false;
			}
			?>
			</p>
			<p style="line-height:1.2;">
				<?= $school->description ?>
			</p>
			<p style="margin-bottom:35px;">
				<a href="<?= $school_url?>"><?= $school_url ?></a>
			</p>
		</div>
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
