<?php

use yii\db\Migration;

class m170803_200634_school_flyer_start extends Migration
{
    public function up()
    {
        $this->addColumn('school', 'flyer', $this->string(100));
        $this->addColumn('school', 'start', $this->string(100));
        $this->addColumn('school', 'end', $this->string(100));
    }

    public function down()
    {
        $this->dropColumn('school', 'flyer');
        $this->dropColumn('school', 'start');
        $this->dropColumn('school', 'end');
    }
}
