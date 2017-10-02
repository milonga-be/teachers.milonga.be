<?php

use yii\db\Migration;

class m170817_125246_user_google_emails extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'google_emails', $this->string(500));
    }

    public function down()
    {
        $this->dropColumn('user', 'google_emails');
    }
}
