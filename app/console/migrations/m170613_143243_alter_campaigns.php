<?php

use yii\db\Migration;

class m170613_143243_alter_campaigns extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE xmp_campaigns ALTER COLUMN id_old DROP NOT NULL;");
    }

    public function safeDown()
    {

    }
}
