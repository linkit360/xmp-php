<?php

use yii\db\Migration;

class m170405_130142_create_campaigns extends Migration
{
    public function safeUp()
    {
        $this->execute('CREATE TABLE xmp_campaigns
                                (
                                  id          UUID DEFAULT public.uuid_generate_v4() PRIMARY KEY NOT NULL,
                                  id_user     UUID                                               NOT NULL,
                                  id_service  UUID                                               NOT NULL,
                                  id_operator INT                                                NOT NULL,
                                  title       VARCHAR(128)                                       NOT NULL,
                                  description VARCHAR(255),
                                  link        VARCHAR(64)                                        NOT NULL,
                                  status      INTEGER DEFAULT 1                                  NOT NULL,
                                  created_at  TIMESTAMP DEFAULT now()                            NOT NULL,
                                  updated_at  TIMESTAMP DEFAULT now()                            NOT NULL
                                )');
        $this->execute('CREATE UNIQUE INDEX xmp_campaigns_link_uindex ON xmp_campaigns (link)');
    }

    public function safeDown()
    {
        $this->dropTable('xmp_campaigns');
    }
}
