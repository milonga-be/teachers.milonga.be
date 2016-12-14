<?php

use yii\db\Migration;

class m161202_142447_lesson_address extends Migration
{
    public function up()
    {
        $this->addColumn('lesson','address',$this->string(500).' after venue');

        return TRUE;
    }

    public function down()
    {
       $this->dropColumn('lesson','address');
    }
}
