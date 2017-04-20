<?php

use yii\db\Migration;

class m170418_075229_create_content extends Migration
{
    public function safeUp()
    {
        $this->execute('CREATE TABLE xmp_content
            (
              id           UUID DEFAULT public.uuid_generate_v4() PRIMARY KEY NOT NULL,
              id_user      UUID                                               NOT NULL,
              id_category  UUID                                               NOT NULL,
              id_publisher UUID,
              title        VARCHAR(32)                                        NOT NULL,
              status       INTEGER DEFAULT 1                                  NOT NULL,
              time_create  TIMESTAMP DEFAULT NOW()                            NOT NULL
            );
        ');
    }

    public function safeDown()
    {
        $this->dropTable('xmp_content');
    }
}
