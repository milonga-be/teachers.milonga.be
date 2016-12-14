<?php

use yii\db\Migration;

class m161207_205536_school_shortname extends Migration
{
    public function up()
    {
        $this->addColumn('school','short_name',$this->string(50));
    }

    public function down()
    {
        $this->dropColumn('school','short_name');
    }
}
