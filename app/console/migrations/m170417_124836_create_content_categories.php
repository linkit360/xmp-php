<?php

use yii\db\Migration;

class m170417_124836_create_content_categories extends Migration
{
    public function safeUp()
    {
        $this->execute('CREATE TABLE xmp_content_categories
            (
              id      UUID PRIMARY KEY DEFAULT public.uuid_generate_v4() NOT NULL,
              id_user UUID                                               NOT NULL,
              icon    VARCHAR(32)                                        NOT NULL,
              title   VARCHAR(32)                                        NOT NULL,
              status  INTEGER DEFAULT 1                                  NOT NULL
            );
        ');
    }

    public function safeDown()
    {
        $this->dropTable('xmp_content_categories');
    }
}
