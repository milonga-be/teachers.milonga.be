<?php

use yii\db\Migration;

class m220317_090828_user_delete_clear_password extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('user', 'clear_password');
    }

    public function safeDown()
    {
        echo "m220317_090828_user_delete_clear_password cannot be reverted.\n";

        return false;
    }
}
