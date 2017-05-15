<?php

use yii\db\Migration;

class m170515_114920_alter_reports extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE xmp_reports ADD outflow BIGINT DEFAULT 0 NOT NULL;");
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE xmp_reports DROP outflow;");
    }
}
