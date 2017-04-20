<?php

use yii\db\Migration;

class m170417_141036_create_content_publishers extends Migration
{
    public function safeUp()
    {
        $this->execute('CREATE TABLE xmp_content_publishers
            (
              id          UUID PRIMARY KEY DEFAULT public.uuid_generate_v4() NOT NULL,
              id_user     UUID                                               NOT NULL,
              title       VARCHAR(32)                                        NOT NULL,
              description VARCHAR(255),
              status      INTEGER DEFAULT 1                                  NOT NULL
            );
        ');
    }

    public function safeDown()
    {
        $this->dropTable('xmp_content_publishers');
    }
}
