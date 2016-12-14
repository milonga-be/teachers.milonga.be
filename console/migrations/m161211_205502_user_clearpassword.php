<?php

use yii\db\Migration;

class m161211_205502_user_clearpassword extends Migration
{
    public function up()
    {
        $this->addColumn('user','clear_password',$this->string());
    }

    public function down()
    {
        $this->dropColumn('user','clear_password');
    }
}
