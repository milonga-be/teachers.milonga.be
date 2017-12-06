<?php

use yii\db\Migration;

class m170817_125246_user_google_emails extends Migration
{
    public function up()
    {
        $this->createTable('school_emails',[
        	'id' => $this->primaryKey(),
        	'school_id' => $this->integer(),
        	'email' => $this->string(50)
        ]);
    }

    public function down()
    {
        $this->dropTable('school_emails');
    }
}
