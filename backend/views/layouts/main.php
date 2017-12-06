<?php
/* @var $this \yii\web\View */
/* @var $content string */
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
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
        <link rel="stylesheet" id="ultrabootstrap-style-css" href="http://milonga.local/wp-content/themes/ultrabootstrap/style.css?ver=4.6.1" type="text/css" media="all">
        <link rel="stylesheet" id="ultrabootstrap-googlefonts-css" href="//fonts.googleapis.com/css?family=Roboto%3A400%2C300%2C700&amp;ver=4.6.1" type="text/css" media="all">
        <style>
            .note-toolbar .btn{
                /* restoring lost summernote styles for the control buttons */
                color:black !important;
                border: 1px solid #ccc;
                border-radius: 3px;
            }
        </style>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <header>
            <section class="logo-menu">
                <nav class="navbar navbar-default navbar-fixed-top">
                    <div class="container">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            </button>
                            <div class="logo-tag">
                                <a href="<?= Url::to(['site/index']) ?>">
                                    <h1 class="site-title" style="color:#000000">Milonga.be – Profesional section</h1>
                                    <h2 class="site-description" style="color:#000000">Tango in Belgie / Tango en Belgique</h2>
                                </a>
                                
                            </div>
                        </div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            
                            <div class="menu-main-menu-container"><ul id="menu-main-menu" class="nav navbar-nav navbar-right">
                                <?php if(\Yii::$app->user->identity){ ?>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item current_page_item <?= Yii::$app->controller->id=='site'?'active':''?>"><a title="Classes" href="<?= Url::to(['site/index']) ?>">Welcome</a></li>
                                <?php if(\Yii::$app->user->identity->school){ ?>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page page_item current_page_item <?= Yii::$app->controller->id=='schools'?'active':''?>"><a title="Classes" href="<?= Url::to(['schools/update']) ?>">Your school</a></li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page page_item current_page_item <?= (Yii::$app->controller->id=='lessons' || Yii::$app->controller->id=='venues') ?'active':''?>"><a title="Classes" href="<?= Url::to(['venues/index']) ?>">Classes</a></li>
                                <?php } ?>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page page_item current_page_item <?= (Yii::$app->controller->id=='agenda') ?'active':''?>"><a title="Agenda" href="<?= Url::to(['agenda/index']) ?>">Agenda</a></li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page page_item"><a title="Classes" href="<?= Url::to(['site/logout']) ?>">Logout</a></li>
                                <?php } ?>
                                </ul></div>                 </div> <!-- /.end of collaspe navbar-collaspe -->
                                </div> <!-- /.end of container -->
                            </nav>
                            </section> <!-- /.end of section -->
                        </header>
                        <div>
                            <!--?php
                            NavBar::begin([
                            'brandLabel' => 'My Company',
                            'brandUrl' => Yii::$app->homeUrl,
                            'options' => [
                            'class' => 'navbar-inverse navbar-fixed-top',
                            ],
                            ]);
                            $menuItems = [
                            ['label' => 'Home', 'url' => ['/site/index']],
                            ];
                            if (Yii::$app->user->isGuest) {
                            $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
                            } else {
                            $menuItems[] = '<li>'
                                . Html::beginForm(['/site/logout'], 'post')
                                . Html::submitButton(
                                'Logout (' . Yii::$app->user->identity->username . ')',
                                ['class' => 'btn btn-link logout']
                                )
                                . Html::endForm()
                            . '</li>';
                            }
                            echo Nav::widget([
                            'options' => ['class' => 'navbar-nav navbar-right'],
                            'items' => $menuItems,
                            ]);
                            NavBar::end();
                            ?-->
                            <div class="container spacer">
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-8">
                                        <?= Alert::widget() ?>
                                        <?= $content ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <footer class="footer">
                            <div class="container">
                                <p class="pull-left">&copy; Milonga.be <?= date('Y') ?></p>
                                <p class="pull-right"><?= Yii::powered() ?></p>
                            </div>
                        </footer>
                        <?php $this->endBody() ?>
                    </body>
                </html>
                <?php $this->endPage() ?>