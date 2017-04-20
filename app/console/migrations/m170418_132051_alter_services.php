<?php

use yii\db\Migration;

class m170418_132051_alter_services extends Migration
{
    public function safeUp()
    {
        $this->dropTable('xmp_services');
        $this->execute('CREATE TABLE xmp_services
            (
              id           UUID DEFAULT uuid_generate_v4() NOT NULL  CONSTRAINT xmp_services_pkey PRIMARY KEY,
              title        VARCHAR(64)                     NOT NULL,
              description  VARCHAR(255),
              id_user      UUID                            NOT NULL,
              id_provider  INTEGER                         NOT NULL,
              id_service   VARCHAR(64)                     NOT NULL,
              id_content   JSONB                           NOT NULL,
              service_opts JSONB                           NOT NULL,
              status       INTEGER DEFAULT 1               NOT NULL,
              time_create  TIMESTAMP DEFAULT now()         NOT NULL
            );
        ');

        $this->execute('CREATE UNIQUE INDEX xmp_services_id_provider_id_service_uindex ON xmp_services (id_provider, id_service);');
    }

    public function safeDown()
    {
        $this->dropTable('xmp_services');
        $this->execute('CREATE TABLE xmp_services
            (
              id           UUID DEFAULT uuid_generate_v4() NOT NULL  CONSTRAINT xmp_services_pkey PRIMARY KEY,
              title        VARCHAR(64)                     NOT NULL,
              description  VARCHAR(255),
              id_user      UUID                            NOT NULL,
              id_provider  INTEGER                         NOT NULL,
              id_service   VARCHAR(64)                     NOT NULL,
              service_opts JSONB                           NOT NULL,
              status       INTEGER DEFAULT 1               NOT NULL,
              updated_at   TIMESTAMP DEFAULT now()         NOT NULL,
              created_at   TIMESTAMP DEFAULT now()         NOT NULL
            );
        ');

        $this->execute('CREATE UNIQUE INDEX xmp_services_id_provider_id_service_uindex ON xmp_services (id_provider, id_service);');
    }
}
