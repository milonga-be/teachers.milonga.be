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
            <div class="col-lg-6">
                <h2>School</h2>

                <p>Here you can manage the informations about your school</p>

                <p><a class="" href="<?= Url::to(['schools/update']) ?>">Edit the information about my school</a></p>
            </div>
            <div class="col-lg-6">
                <h2>Classes</h2>

                <p>Here you can manage your classes displayed on Milonga.be</p>

                <p><a class="" href="<?= Url::to(['venues/index']) ?>">Manage my classes</a></p>
            </div>
            <!-- <div class="col-lg-4">
                <h2>Agenda</h2>

                <p>Get of a view on the complete Milonga.be agenda</p>

                <p><a class="" target="_blank" href="http://organizer.milonga.be">Check the agenda</a></p>
            </div> -->
            </div>
        </div>

    </div>
</div>
