<?php

use yii\db\Migration;

class m161202_134124_lesson_venue_id_to_venue extends Migration
{
    public function up()
    {
        $this->renameColumn('lesson','venue_id','venue');
        $this->alterColumn('lesson','venue',$this->string(500));
    }

    public function down()
    {
        $this->renameColumn('lesson','venue','venue_id');
        $this->alterColumn('lesson','venue_id',$this->integer());
    }
}
