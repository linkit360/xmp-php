<?php

use yii\db\Migration;

class m170322_105558_create_users extends Migration
{
    public function safeUp()
    {
        $this->execute('CREATE TABLE xmp_users
                            (
                              id                   UUID PRIMARY KEY DEFAULT public.uuid_generate_v4() NOT NULL,
                              username             VARCHAR(255)                                       NOT NULL,
                              auth_key             VARCHAR(32)                                        NOT NULL,
                              password_hash        VARCHAR(255)                                       NOT NULL,
                              password_reset_token VARCHAR(255),
                              email                VARCHAR(255)                                       NOT NULL,
                              status               SMALLINT DEFAULT 1                                 NOT NULL,
                              created_at           INTEGER                                            NOT NULL,
                              updated_at           INTEGER                                            NOT NULL
                            );
                      ');

        $this->execute("INSERT INTO xmp_users (id, username, auth_key, password_hash, password_reset_token, email, status, created_at, updated_at) VALUES ('96c571ea-2a95-46d3-b2ad-f8b3d1c9ee6a', 'test', 'GsyEP6i-eM0RRGNxSyTxLloalBydwv5k', '\$2y\$13\$w3GpEBWoboiVVBLnogmESOmz7bR/bwwqsP5MYIw.gOg/SdLoxEfAK', NULL, '1@1.1', 1, 1490185853, 1490185853);");
    }

    public function safeDown()
    {
        $this->dropTable('xmp_users');
    }
}
