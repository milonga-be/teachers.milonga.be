<?php

use yii\db\Migration;

class m161120_104406_create_table_user_school extends Migration
{
    public function up()
    {
        $this->createTable('user_school',[
            'user_id' => $this->integer(),
            'school_id' => $this->integer(),
            'PRIMARY KEY (user_id,school_id)'
        ]);

        return TRUE;
    }

    public function down()
    {
        $this->dropTable('user_school');

        return TRUE;
    }
}
