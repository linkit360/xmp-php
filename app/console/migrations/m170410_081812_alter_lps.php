<?php

use yii\db\Migration;

class m170410_081812_alter_lps extends Migration
{
    public function safeUp()
    {
        $this->dropTable('xmp_lps');
        $this->execute('
            CREATE TABLE xmp_lps
                (
                    id          UUID DEFAULT public.uuid_generate_v4() PRIMARY KEY NOT NULL,
                    id_user     UUID                                               NOT NULL,
                    title       VARCHAR(64)                                        NOT NULL,
                    description VARCHAR(225),
                    status      INTEGER DEFAULT 1                                  NOT NULL,
                    created_at  TIMESTAMP DEFAULT now()                            NOT NULL,
                    updated_at  TIMESTAMP DEFAULT now()                            NOT NULL
                );
        ');
    }

    public function safeDown()
    {

        $this->dropTable('xmp_lps');
        $this->execute('
            CREATE TABLE xmp_lps
                (
                  id         UUID DEFAULT public.uuid_generate_v4() PRIMARY KEY NOT NULL,
                  id_user    UUID                                               NOT NULL,
                  status     INTEGER DEFAULT 1                                  NOT NULL,
                  created_at TIMESTAMP DEFAULT now()                            NOT NULL,
                  updated_at TIMESTAMP DEFAULT now()                            NOT NULL
                );
        ');
    }
}
