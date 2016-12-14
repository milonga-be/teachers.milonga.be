<?php

use yii\db\Migration;

class m161120_104350_create_table_lesson extends Migration
{
    public function up()
    {
        $this->createTable('lesson',[
            'id' => $this->primaryKey(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
            'modifier_id' => $this->integer(),
            'school_id' => $this->integer(),
            'venue_id' => $this->integer(),
            'start_hour' => $this->string(),
            'end_hour' => $this->string(),
            'level' => $this->string(),
            'teacher' => $this->string(),
        ]);

        return TRUE;
    }

    public function down()
    {
        $this->dropTable('lesson');

        return TRUE;
    }
}
