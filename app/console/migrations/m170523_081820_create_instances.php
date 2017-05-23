<?php

use yii\db\Migration;

class m170523_081820_create_instances extends Migration
{
    public function safeUp()
    {
        $this->execute('CREATE TABLE xmp_instances
            (
              id          UUID PRIMARY KEY DEFAULT uuid_generate_v4() NOT NULL,
              id_operator INTEGER                                     NOT NULL,
              status      INTEGER DEFAULT 1                           NOT NULL
            );
        ');
    }

    public function safeDown()
    {
        $this->dropTable('xmp_instances');
    }
}
