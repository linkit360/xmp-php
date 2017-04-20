<?php

use yii\db\Migration;

class m170316_115650_create_operators extends Migration
{
    public function safeUp()
    {
        $this->execute('CREATE TABLE xmp_operators
                            (
                              id            SERIAL PRIMARY KEY NOT NULL,
                              name          VARCHAR(255)       NOT NULL,
                              id_provider   INTEGER,
                              isp           VARCHAR(255),
                              msisdn_prefix VARCHAR(255),
                              mcc           VARCHAR(255),
                              mnc           VARCHAR(255),
                              code          INTEGER            NOT NULL,
                              status        INTEGER DEFAULT 1  NOT NULL,
                              created_at    TIMESTAMP DEFAULT now()
                            );
                        ');
    }

    public function safeDown()
    {
        $this->dropTable('xmp_operators');
    }
}
