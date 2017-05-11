<?php

use yii\db\Migration;

class m170511_110859_alter_campaigns extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE xmp_campaigns ADD autoclick_enabled BOOLEAN DEFAULT FALSE  NOT NULL;");
        $this->execute("ALTER TABLE xmp_campaigns ADD autoclick_ratio SMALLINT DEFAULT 10 NOT NULL;");
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE xmp_campaigns DROP autoclick_enabled;");
        $this->execute("ALTER TABLE xmp_campaigns DROP autoclick_ratio;");
    }
}
