<?php
use yii\web\View;
use common\components\Htmlizer;
$previous_event = null;
?>
<p>
	<a href="/dancing/"><span class="glyphicon glyphicon-chevron-left"></span> Back to the agenda</a>
</p>
<form action="">
	<input class="form-control" name="u-q" value="<?= Yii::$app->request->get('q') ?>">
</form>
<div class="events" data-nb="<?= sizeof($events) ?>">
	<?php
	if(sizeof($events) == 0){
		echo '<h3>No events found</h3><hr>';
	}
	foreach ($events as $event) {
		echo $this->render('_date', ['event' => $event, 'previous_event' => $previous_event]);
		echo $this->render('_event', ['event' => $event]);
		$previous_event = $event;
	}
	?>
</div>