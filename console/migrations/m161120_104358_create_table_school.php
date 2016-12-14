<?php

use yii\db\Migration;

class m161120_104358_create_table_school extends Migration
{
    public function up()
    {
        $this->createTable('school',[
            'id' => $this->primaryKey(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
            'modifier_id' => $this->integer(),
            'name' => $this->string(),
            'address' => $this->string(),
            'email' => $this->string(),
            'facebook' => $this->string(),
            'phone' => $this->string(),
            'website' => $this->string(),
            'picture' => $this->string(),
        ]);

        return TRUE;
    }

    public function down()
    {
        $this->dropTable('school');

        return TRUE;
    }
}
