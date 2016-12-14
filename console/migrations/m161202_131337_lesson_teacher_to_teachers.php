<?php

use yii\db\Migration;

class m161202_131337_lesson_teacher_to_teachers extends Migration
{
    public function up()
    {
        $this->renameColumn('lesson','teacher','teachers');
    }

    public function down()
    {
        $this->renameColumn('lesson','teachers','teacher');
    }
}
