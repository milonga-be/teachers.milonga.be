<?php
use common\models\School;
use common\models\Venue;
use common\models\Lesson;
use yii\helpers\ArrayHelper;
use yii\web\View;

foreach($schools as $school){
	if($school->venues){
	?>
		<?php
		if($school->picture){
			echo '<img src="'.$school->thumbUrl.'">';
		}
		?>
	<?php
	}
}
?>
