<?php

use yii\db\Migration;

class m170807_145253_school_thumb extends Migration
{
    public function up()
    {
        $this->addColumn('school', 'thumb', $this->string(100));
    }

    public function down()
    {
        $this->dropColumn('school', 'thumb');
    }
}
