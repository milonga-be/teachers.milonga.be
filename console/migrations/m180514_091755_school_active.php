<?php

use yii\db\Migration;

class m180514_091755_school_active extends Migration
{
    public function up()
    {
        $this->addColumn('school', 'active', $this->boolean().' DEFAULT 1');
    }

    public function down()
    {
        echo "m180514_091755_school_active cannot be reverted.\n";

        return false;
    }
}
