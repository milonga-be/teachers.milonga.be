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
			<a href="<?= Url::to(['agenda/create', 'recurring' => 1]) ?>" class="btn btn-primary">New recurring event</a> 
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
			 			return $dateTime->format('D d M');
			 		}
			 	],
			 	[
			 		'attribute' => 'summary',
			 		'format' => 'raw',
			 		'value' => function($data){
			 			$summary = $data['summary'];
			 			if(strlen($summary) > 60){
			 				$summary = substr($summary, 0, 60).'...';
			 			}
			 			return Html::a($summary, ['agenda/update', 'id' => $data['id'], 'page' => isset(Yii::$app->request->queryParams['page'])?Yii::$app->request->queryParams['page']:1]);
			 		}
			 	],
			 	[
			 		'attribute' =>	'organizer',
			 		'format' => 'raw',
			 		'value' => function($data){
			 			if(isset($data->getExtendedProperties()->shared['organizer'])){
			 				return $data->getExtendedProperties()->shared['organizer'];
			 			}
			 			if(isset($data->creator->email)){
			 				return $data->creator->email;
			 			}
			 			return null;
			 		},
			 		'visible' => Yii::$app->user->identity->isAdmin()
			 	],
			 	[
			 		'format' => 'raw',
			 		'value' => function($data){
			 			return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['agenda/delete', 'id' => $data['id']], ['onclick' => 'return confirm(\'Do you really want to delete this event ?\');']);
			 		}
			 	]
			 ]
	]) ?>