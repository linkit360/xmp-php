<?php

use yii\db\Migration;

class m170608_081856_alter_campaings extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE xmp_campaigns ADD id_old BIGINT DEFAULT 0 NOT NULL;");
        $this->execute("ALTER TABLE xmp_campaigns ALTER COLUMN id_old DROP DEFAULT;");
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE xmp_campaigns DROP id_old;");
    }
}
