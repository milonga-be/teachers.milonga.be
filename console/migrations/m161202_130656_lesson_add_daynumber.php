<?php

use yii\db\Migration;

class m161202_130656_lesson_add_daynumber extends Migration
{
    public function up()
    {
        $this->addColumn('lesson','day','INT(11) after venue_id');

        return TRUE;
    }

    public function down()
    {
       $this->dropColumn('lesson','day');
    }
}
