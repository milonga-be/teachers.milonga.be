<?php

use yii\db\Migration;

class m161120_104628_create_table_venue extends Migration
{
    public function up()
    {
        $this->createTable('venue',[
            'id' => $this->primaryKey(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
            'modifier_id' => $this->integer(),
            'name' => $this->string(),
            'address' => $this->string(),
            'postalcode' => $this->string(),
            'city' => $this->string(),
        ]);

        return TRUE;
    }

    public function down()
    {
        $this->dropTable('venue');

        return TRUE;
    }
}
