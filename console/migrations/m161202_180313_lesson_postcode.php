<?php

use yii\db\Migration;

class m161202_180313_lesson_postcode extends Migration
{
    public function up()
    {
        $this->addColumn('lesson','postal_code',$this->string(50).' after address');
    }

    public function down()
    {
        $this->dropColumn('lesson','postal_code');
    }
}
