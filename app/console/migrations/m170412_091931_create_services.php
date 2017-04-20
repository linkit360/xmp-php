<?php

use yii\db\Migration;

class m170412_091931_create_services extends Migration
{
    public function safeUp()
    {
        $this->execute('CREATE TABLE xmp_services
                            (
                              id          UUID PRIMARY KEY                                   NOT NULL,
                              title       VARCHAR(64)                                        NOT NULL,
                              description VARCHAR(255),
                              id_provider INT                                                NOT NULL,
                              id_user     UUID                                               NOT NULL,
                              status      INTEGER DEFAULT 1                                  NOT NULL,
                              created_at  TIMESTAMP DEFAULT now()                            NOT NULL,
                              updated_at  TIMESTAMP DEFAULT now()                            NOT NULL
                            )
        ');
    }

    public function safeDown()
    {
        $this->dropTable('xmp_services');
    }
}
