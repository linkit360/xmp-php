<?php

use yii\db\Migration;

class m170502_081718_alter_lps extends Migration
{
    public function safeUp()
    {
        $this->execute('ALTER TABLE xmp_lps ALTER COLUMN title SET NOT NULL;');
    }

    public function safeDown()
    {
        $this->execute('ALTER TABLE xmp_lps ALTER COLUMN title DROP NOT NULL;');
    }
}
