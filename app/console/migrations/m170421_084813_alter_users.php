<?php

use yii\db\Migration;

class m170421_084813_alter_users extends Migration
{
    public function safeUp()
    {
        $this->execute('ALTER TABLE xmp_users ADD new_pass SMALLINT DEFAULT 0;');
    }

    public function safeDown()
    {
        $this->execute('ALTER TABLE xmp_users DROP new_pass;');
    }
}
