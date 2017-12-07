<?php

use yii\db\Migration;

class m171207_145622_school_description extends Migration
{
    public function up()
    {
        $this->addColumn('school', 'description', $this->string(250));
    }

    public function down()
    {
        $this->dropColumn('school', 'description');
    }
}
