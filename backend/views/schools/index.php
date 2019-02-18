<?php
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Lesson;

$this->title = 'Schools';
echo '<h1>' . $this->title . '</h1>';
?>
<p>
	<div class="row">
		<div class="col-md-6 text-left">
			
		</div>
		<div class="col-md-6 text-right">
			<a href="<?= Url::to(['schools/create']) ?>" class="btn btn-primary">New school</a>
		</div>
	</div>
</p>
<div class="row">
	<div class="col-md-12">
	<?=
		 GridView::widget([
			 'dataProvider' => $dataProvider,
			 'filterModel' => $searchModel,
			 'columns' => [
				 [
				 	'attribute' => 'name',
				 	'format' => 'raw',
				 	'value' => function($data){
				 		return Html::a($data->name,['schools/update','id' => $data->id]);
				 	}
				 ],
				 // 'city',
				 // [
    	// 			'class' => 'yii\grid\ActionColumn',
    	// 			'template' => '{update} {delete}',
    	// 			'buttons' => [
    	// 				'update' => function ($url, $model, $key) {
					// 	    return Html::a('Edit',$url,['class' => 'btn btn-success']);
					// 	},
					// 	'delete' => function ($url, $model, $key) {
					// 	    return Html::a('Delete',$url,['class' => 'btn btn-danger','data-confirm' => 'Are you sure to delete this item?','data-method' => 'post']);
					// 	},
    	// 			]
    	// 		],
			 ],
		 ]);
	?>
	</div>
</div>