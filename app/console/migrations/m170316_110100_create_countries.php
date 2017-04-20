<?php

use yii\db\Migration;

class m170316_110100_create_countries extends Migration
{
    public function safeUp()
    {
        $this->execute('CREATE TABLE xmp_countries
                            (
                                id SERIAL PRIMARY KEY NOT NULL,
                                name VARCHAR(255) NOT NULL,
                                code INTEGER NOT NULL,
                                status INTEGER NOT NULL,
                                iso VARCHAR(32) NOT NULL,
                                priority INTEGER NOT NULL
                            );
                        ');

        $this->execute("COMMENT ON TABLE xmp_countries IS 'Countries List';");

        # Init Data
        $this->execute("INSERT INTO xmp_countries (id, name, code, status, iso, priority) VALUES (2, 'Russia', 7, 1, 'RU', 6);");
        $this->execute("INSERT INTO xmp_countries (id, name, code, status, iso, priority) VALUES (4, 'Pakistan', 92, 1, 'PK', 1);");
        $this->execute("INSERT INTO xmp_countries (id, name, code, status, iso, priority) VALUES (6, 'Thailand', 66, 1, 'TH', 2);");
        $this->execute("INSERT INTO xmp_countries (id, name, code, status, iso, priority) VALUES (1, 'Indonesia', 62, 1, 'ID', 8);");
        $this->execute("INSERT INTO xmp_countries (id, name, code, status, iso, priority) VALUES (9, 'Philippines', 63, 1, 'PH', 3);");
    }

    public function safeDown()
    {
        $this->dropTable('xmp_countries');
    }
}
