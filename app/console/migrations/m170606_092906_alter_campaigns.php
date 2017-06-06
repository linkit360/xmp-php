<?php

use yii\db\Migration;

class m170606_092906_alter_campaigns extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE xmp_campaigns DROP id_operator;");
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE xmp_campaigns ADD id_operator INT DEFAULT 0 NOT NULL;");
    }
}
