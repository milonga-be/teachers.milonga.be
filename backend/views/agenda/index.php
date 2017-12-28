<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Your events ';
echo '<h1>' . $this->title . '</h1>';
?>
<p>
	<div class="row">
		<div class="col-md-6 text-left">
			
		</div>
		<div class="col-md-6 text-right">
			<a href="<?= Url::to(['agenda/create']) ?>" class="btn btn-primary">New event</a>
		</div>
	</div>
</p>
<?= GridView::widget([
			 'dataProvider' => $dataProvider,
			 // 'filterModel' => $searchModel,
			 'columns' => [
			 	[
			 		'attribute' => 'start.dateTime',
			 		'format' => 'raw',
			 		'value' => function($data){
			 			$dateTime = new \Datetime($data['start']['dateTime']);
			 			return $dateTime->format('d M Y');
			 		}
			 	],
			 	[
			 		'attribute' => 'summary',
			 		'format' => 'raw',
			 		'value' => function($data){
			 			$summary = $data['summary'];
			 			if(strlen($summary) > 75){
			 				$summary = substr($summary, 0, 75).'...';
			 			}
			 			return Html::a($summary, ['agenda/update', 'id' => $data['id']]);
			 		}
			 	],
			 	[
			 		'attribute' => 'recurrence'
			 	]
			 ]
	]) ?>