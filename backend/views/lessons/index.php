<?php
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Lesson;
use rmrevin\yii\fontawesome\FA;

$this->title = 'Your classes @ ' . $venue->name;
echo '<h1>' . $this->title . '</h1>';
?>
<p>
	<div class="row">
		<div class="col-md-6 text-left">
			<a href="<?= Url::to(['venues/index']) ?>" class=""><?= FA::icon('angle-left')?> Back to the locations</a>
		</div>
		<div class="col-md-6 text-right">
			<a href="<?= Url::to(['lessons/create','venue_id' => $venue->id ]) ?>" class="btn btn-primary">New class</a>
		</div>
	</div>
</p>

<div class="row">
	<div class="col-md-12 text-right">
	<?=
		 GridView::widget([
			 'dataProvider' => $dataProvider,
			 // 'filterModel' => $searchModel,
			 'columns' => [
				 'venue.name',
				 array(
				 	'attribute' => 'day',
				 	'value' => 'dayname'
				 ),
				 'start_hour',
				 // 'end_hour',
				 array(
				 	'attribute' => 'level',
				 	'value' => 'levelname'
				 ),
				 'teachers',
				 [
    				'class' => 'yii\grid\ActionColumn',
    				'template' => '{update} {delete}',
    				'buttons' => [
    					'update' => function ($url, $model, $key) {
						    return Html::a('Edit',$url,['class' => 'btn btn-success']);
						},
						'delete' => function ($url, $model, $key) {
						    return Html::a('Delete',$url,['class' => 'btn btn-danger','data-confirm' => 'Are you sure to delete this item?','data-method' => 'post']);
						},
    				]
    			],
			 ],
		 ]);
	?>
	</div>
</div>
