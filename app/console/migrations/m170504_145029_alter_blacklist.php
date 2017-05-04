<?php

use yii\db\Migration;

class m170504_145029_alter_blacklist extends Migration
{
    public function safeUp()
    {
        $this->dropTable("xmp_msisdn_blacklist");
        $this->execute("CREATE TABLE xmp_msisdn_blacklist
            (
              id          UUID DEFAULT uuid_generate_v4() PRIMARY KEY NOT NULL,
              id_user     UUID                                        NOT NULL,
              id_provider INTEGER                                     NOT NULL,
              id_operator INTEGER                                     NOT NULL,
              msisdn      VARCHAR(32)                                 NOT NULL,
              created_at  TIMESTAMP DEFAULT now()                     NOT NULL
            );
        ");
    }

    public function safeDown()
    {
        $this->dropTable("xmp_msisdn_blacklist");
        $this->execute("CREATE TABLE xmp_msisdn_blacklist
            (
              id          SERIAL                  NOT NULL  PRIMARY KEY,
              msisdn      VARCHAR(32)             NOT NULL,
              id_provider INTEGER                 NOT NULL,
              id_operator INTEGER                 NOT NULL,
              created_at  TIMESTAMP DEFAULT now() NOT NULL
            );
        ");
    }
}
