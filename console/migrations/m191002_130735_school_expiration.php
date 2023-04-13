<?php

use yii\db\Migration;

class m191002_130735_school_expiration extends Migration
{
    public function up()
    {
        $this->addColumn('school', 'expiration', $this->date().' DEFAULT NULL');
    }

    public function down()
    {
        echo "m191002_130735_school_expiration cannot be reverted.\n";

        return false;
    }
}
