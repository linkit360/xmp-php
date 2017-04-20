<?php

use yii\db\Migration;

class m170414_092636_create_logs extends Migration
{
    public function safeUp()
    {
        $this->execute("CREATE TABLE xmp_logs
            (
              id         UUID DEFAULT public.uuid_generate_v4() PRIMARY KEY NOT NULL,
              id_user    UUID                                               NOT NULL,
              time       TIMESTAMP DEFAULT now()                            NOT NULL,
              controller VARCHAR(32)                                        NOT NULL,
              action     VARCHAR(32)                                        NOT NULL,
              event      JSONB DEFAULT '{}'                                 NOT NULL
            );
        ");
    }

    public function safeDown()
    {
        $this->dropTable('xmp_logs');
    }
}
