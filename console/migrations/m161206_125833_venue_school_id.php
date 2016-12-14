<?php

use yii\db\Migration;

class m161206_125833_venue_school_id extends Migration
{
    public function up()
    {
        $this->addColumn('venue','school_id',$this->integer());
    }

    public function down()
    {
        $this->dropColumn('venue','school_id');
    }
}
