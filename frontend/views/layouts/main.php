<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" id="ultrabootstrap-style-css" href="http://milonga.be/wp-content/themes/ultrabootstrap/style.css?ver=4.6.1" type="text/css" media="all">
    <link rel="stylesheet" id="ultrabootstrap-googlefonts-css" href="//fonts.googleapis.com/css?family=Roboto%3A400%2C300%2C700&amp;ver=4.6.1" type="text/css" media="all">
    <script type="text/javascript">
        document.domain = 'milonga.be';
    </script>
</head>
<body style="background-color: white;margin-top:20px;">
<?php $this->beginBody() ?>

<?= Alert::widget() ?>
<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
