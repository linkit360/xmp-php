<?php

use yii\db\Migration;

class m170515_115118_alter_reports extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN outflow DROP DEFAULT;");
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN outflow SET DEFAULT 0;");
    }
}
