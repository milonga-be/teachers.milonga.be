<?php
use yii\web\View;
use common\components\Htmlizer;
$previous_event = null;
?>
<div class="events" data-nb="<?= sizeof($events) ?>">
	<?php
	foreach ($events as $event) {
		echo $this->render('_date', ['event' => $event, 'previous_event' => $previous_event]);
		echo $this->render('_event', ['event' => $event]);
		$previous_event = $event;
	}
	?>
</div>