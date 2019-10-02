<?php
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Welcome';

rmrevin\yii\fontawesome\AssetBundle::register($this);

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome</h1>
        <!-- <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <p class="lead">You are in the Milonga.be section dedicated to the professionals</p>
            </div>
        </div> -->
    </div>

    <div class="body-content">

        <div class="row">
            <?php if(Yii::$app->user->identity->school){ ?>
            <div class="col-lg-4">
                <h2>Organization</h2>

                <p>Here you can manage the informations about your organization</p>

                <p><a class="" href="<?= Url::to(['schools/update']) ?>">Edit the information about my organization</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Classes</h2>

                <p>Here you can manage your classes displayed on Milonga.be</p>

                <p><a class="" href="<?= Url::to(['venues/index']) ?>">Manage my classes</a></p>
            </div>
            <?php } ?>
            <div class="col-lg-4">
                <h2>Agenda</h2>

                <p>Here you can manage your events in the agenda</p>

                <p><a class="" href="<?= Url::to(['agenda/index']) ?>">Manage my events</a></p>
            </div>
            </div>
        </div>

    </div>
</div>
