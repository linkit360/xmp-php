<?php

use yii\db\Migration;
use yii\db\Query;

class m170316_153814_alter_reports extends Migration
{
    public function safeUp()
    {
        # Providers
        $this->execute('ALTER TABLE xmp_providers ADD name_alias VARCHAR(255) NULL;');

        $this->execute("INSERT INTO xmp_providers(name, id_country, name_alias) VALUES('cheese', 6, 'cheese');");
        $this->execute("INSERT INTO xmp_providers(name, id_country, name_alias) VALUES('qrtech', 6, 'qrtech');");
        $this->execute("INSERT INTO xmp_providers(name, id_country, name_alias) VALUES('beeline', 2, 'beeline');");
        $this->execute("INSERT INTO xmp_providers(name, id_country, name_alias) VALUES('yondu', 9, 'yondu');");
        $this->execute("INSERT INTO xmp_providers(name, id_country, name_alias) VALUES('mobilink', 4, 'mobilink');");

        $query = (new Query())
            ->select([
                'id',
                'name_alias',
            ])
            ->from('xmp_providers')
            ->indexBy('name_alias')
            ->all();

        # Operators
        $this->execute("INSERT INTO xmp_operators (name, id_provider, isp, msisdn_prefix, mcc, mnc, code) VALUES ('Mobilink', " . $query['mobilink']['id'] . ", NULL, '92', '410', '01',  41001);");
        $this->execute("INSERT INTO xmp_operators (name, id_provider, isp, msisdn_prefix, mcc, mnc, code) VALUES ('QR Tech', " . $query['qrtech']['id'] . ", NULL, NULL, NULL, NULL, 52991);");
        $this->execute("INSERT INTO xmp_operators (name, id_provider, isp, msisdn_prefix, mcc, mnc, code) VALUES ('Cheese Mobile', " . $query['cheese']['id'] . ", NULL, NULL, NULL, NULL, 52992);");
        $this->execute("INSERT INTO xmp_operators (name, id_provider, isp, msisdn_prefix, mcc, mnc, code) VALUES ('Global Telecom', " . $query['yondu']['id'] . ", NULL, NULL, '515', '01', 51501);");

        $this->execute("INSERT INTO xmp_operators (name, id_provider, isp, msisdn_prefix, mcc, mnc, code) VALUES ('AIS', " . $query['cheese']['id'] . ", 'AIS/Advanced Info Service ', '061, 062, 063, 0800, 0801, 0802, 0806, 0810, 0812, 0817, 0818, 0819, 082, 084, 087, 089, 090, 0901, 092', '520', '01', 52001);");
        $this->execute("INSERT INTO xmp_operators (name, id_provider, isp, msisdn_prefix, mcc, mnc, code) VALUES ('DTAC', " . $query['cheese']['id'] . ", 'Total Access (DTAC) ', NULL, '520', '05', 52005);");
        $this->execute("INSERT INTO xmp_operators (name, id_provider, isp, msisdn_prefix, mcc, mnc, code) VALUES ('TRUEH', " . $query['cheese']['id'] . ", NULL, NULL, '520', '00', 52000);");

        # Reports
        $this->execute('ALTER TABLE xmp_reports RENAME COLUMN report_date TO report_at;');
        $this->execute('ALTER TABLE xmp_reports RENAME COLUMN id_provider TO provider_name;');
        $this->execute('ALTER TABLE xmp_reports RENAME COLUMN id_operator TO operator_code;');
    }

    public function safeDown()
    {
        $this->truncateTable('xmp_providers');
        $this->truncateTable('xmp_operators');
        $this->execute('ALTER TABLE xmp_providers DROP name_alias;');
    }
}
