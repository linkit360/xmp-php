<?php

use yii\db\Migration;

class m170605_100748_alter_content extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE xmp_content ADD filename VARCHAR(32) DEFAULT 'file' NOT NULL;");
        $this->execute("ALTER TABLE xmp_content ALTER COLUMN filename DROP DEFAULT;");
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE xmp_reports DROP filename;");
    }
}
