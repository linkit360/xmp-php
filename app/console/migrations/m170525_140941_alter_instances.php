<?php

use yii\db\Migration;

class m170525_140941_alter_instances extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE xmp_instances RENAME COLUMN id_operator TO id_provider;");
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE xmp_instances RENAME COLUMN id_provider TO id_operator;");
    }
}
