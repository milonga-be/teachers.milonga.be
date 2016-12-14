<?php

use yii\db\Migration;

class m161204_100525_lesson_venue_to_venue_id extends Migration
{
    public function up()
    {
        $this->renameColumn('lesson','venue','venue_id');
        $this->alterColumn('lesson','venue_id',$this->integer());

    }

    public function down()
    {
        $this->renameColumn('lesson','venue_id','venue');
        $this->alterColumn('lesson','venue',$this->string(500));
    }
}
