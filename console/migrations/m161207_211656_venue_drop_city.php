<?php

use yii\db\Migration;

class m161207_211656_venue_drop_city extends Migration
{
    public function up()
    {
        $this->dropColumn('venue','city');
    }

    public function down()
    {
        $this->addColumn('venue','city',$this->string());
    }

}
