<?php

use yii\db\Migration;

class m170525_152536_alter_services extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE xmp_services ADD price INT DEFAULT 0 NOT NULL;");
        $this->execute("ALTER TABLE xmp_services ALTER COLUMN price DROP DEFAULT;");
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE xmp_services DROP price;");
    }
}
