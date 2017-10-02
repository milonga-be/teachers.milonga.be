<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Event;
use rmrevin\yii\fontawesome\FA;

$this->title = 'Your event ';
echo '<h1>' . $this->title . '</h1>';

echo $this->render('_form' , ['event' => $event ] );
?>