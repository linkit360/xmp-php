<?php

use yii\db\Migration;

class m170418_095418_alter_content extends Migration
{
    public function safeUp()
    {
        $this->execute('ALTER TABLE xmp_content ADD blacklist JSONB NULL;');
    }

    public function safeDown()
    {
        $this->execute('ALTER TABLE xmp_content DROP blacklist;');
    }
}
