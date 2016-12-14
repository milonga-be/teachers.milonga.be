<?php

use yii\db\Migration;

class m161204_100720_lesson_drop_address extends Migration
{
    public function up()
    {
        $this->dropColumn('lesson','postal_code');
        $this->dropColumn('lesson','address');
    }

    public function down()
    {
        $this->addColumn('lesson','address',$this->string(500));
        $this->addColumn('lesson','postal_code',$this->string(50).' after address');
    }
}
