<?php

use yii\db\Migration;

class m170620_085937_alter_instances extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE xmp_instances ADD title VARCHAR(255) DEFAULT '' NOT NULL;");
        $this->execute("ALTER TABLE xmp_instances ADD hostname VARCHAR(255) DEFAULT 'linkit360.ru' NOT NULL;");

        $this->execute("ALTER TABLE xmp_instances ALTER COLUMN title DROP DEFAULT;");
        $this->execute("ALTER TABLE xmp_instances ALTER COLUMN hostname DROP DEFAULT;");
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE xmp_instances DROP title;");
        $this->execute("ALTER TABLE xmp_instances DROP hostname;");
    }
}
