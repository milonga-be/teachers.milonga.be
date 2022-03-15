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
                <?php
                    if(isset(Yii::$app->user->identity->school->expiration)){
                        $oneMonth = new \Datetime();
                        $today = new \Datetime();
                        $expirationDate = new \Datetime(Yii::$app->user->identity->school->expiration);
                        $oneMonth->modify('+1 month');
                        if($expirationDate < $oneMonth){
                            ?>
                            <div class="col-lg-12">
                                <p class="alert alert-danger" style="font-size:1.1em"><strong><?= $expirationDate < $today?'Your subscription is expired':'Your subscription is almost expired'?> (<?= $expirationDate->format('d/m/Y') ?>)</strong><br> 
                                    Your account will close very soon !<br>
                                    Please take contact with <a href="mailto:milonga@milonga.be">milonga@milonga.be</a> to renew your subscription</p>
                            </div>
                            <?php
                        }else{
                            ?>
                            <div class="col-lg-12">
                                <p class="alert alert-success" style="font-size:1.1em">
                                    Your subscription is in order. <strong>Thank you</strong> for contributing to milonga.be ! 
                                </p>
                            </div>
                            <?php
                        }
                    }
                ?>
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
