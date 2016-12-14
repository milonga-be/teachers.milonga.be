<?php
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Lesson;

$this->title = 'Your classes locations';
echo '<h1>' . $this->title . '</h1>';
?>
<div class="row">
	<div class="col-md-12 text-right">
			<a href="<?= Url::to(['venues/create']) ?>" class="btn btn-primary">New location</a>
		</div>
	</div>
</p>
<p>
<div class="row">
	<div class="col-md-12 text-right">
	<?=
		 GridView::widget([
			 'dataProvider' => $dataProvider,
			 // 'filterModel' => $searchModel,
			 'columns' => [
				 [
				 	'attribute' => 'name',
				 	// 'format' => 'raw',
				 	// 'value' => function($data){
				 	// 	return Html::a($data->name,['lessons/index','LessonSearch[venue_id]' => $data->id]);
				 	// }
				 ],
				 'address',
				 'postalcode',
				 // 'city',
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
    			[
				 	'label' => 'Classes',
				 	'format' => 'raw',
				 	'value' => function($data){
				 		return Html::a( sizeof($data->lessons) . ' classes ',['lessons/index','venue_id' => $data->id]);
				 	},
				 ],
			 ],
		 ]);
	?>
	</div>
</div>