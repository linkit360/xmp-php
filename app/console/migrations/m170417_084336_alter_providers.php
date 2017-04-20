<?php

use yii\db\Migration;

class m170417_084336_alter_providers extends Migration
{
    public function safeUp()
    {
        $this->execute('ALTER TABLE xmp_providers ADD status INT DEFAULT 1 NOT NULL;');
    }

    public function safeDown()
    {
        $this->execute('ALTER TABLE xmp_providers DROP status;');
    }
}
