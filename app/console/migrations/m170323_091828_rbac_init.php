<?php

use yii\db\Migration;

class m170323_091828_rbac_init extends Migration
{
    public function safeUp()
    {
        $this->execute('DROP TABLE IF EXISTS "xmp_rbac_assignments";');
        $this->execute('DROP TABLE IF EXISTS "xmp_rbac_items_childs";');
        $this->execute('DROP TABLE IF EXISTS "xmp_rbac_items";');
        $this->execute('DROP TABLE IF EXISTS "xmp_rbac_rules";');

        $this->execute('CREATE TABLE "xmp_rbac_rules"
                            (
                              "name"       VARCHAR(64) NOT NULL,
                              "data"       BYTEA,
                              "created_at" INTEGER,
                              "updated_at" INTEGER,
                              PRIMARY KEY ("name")
                            );
                        ');

        $this->execute('CREATE TABLE "xmp_rbac_items"
                            (
                              "name"        VARCHAR(64) NOT NULL,
                              "type"        SMALLINT    NOT NULL,
                              "description" TEXT,
                              "rule_name"   VARCHAR(64),
                              "data"        BYTEA,
                              "created_at"  INTEGER,
                              "updated_at"  INTEGER,
                              PRIMARY KEY ("name"),
                              FOREIGN KEY ("rule_name") REFERENCES "xmp_rbac_rules" ("name") ON DELETE SET NULL ON UPDATE CASCADE
                            );
                      ');

        $this->execute('CREATE INDEX xmp_rbac_item_type_idx ON "xmp_rbac_items" ("type");');
        $this->execute('CREATE TABLE "xmp_rbac_items_childs"
                            (
                              "parent" VARCHAR(64) NOT NULL,
                              "child"  VARCHAR(64) NOT NULL,
                              PRIMARY KEY ("parent", "child"),
                              FOREIGN KEY ("parent") REFERENCES "xmp_rbac_items" ("name") ON DELETE CASCADE ON UPDATE CASCADE,
                              FOREIGN KEY ("child") REFERENCES "xmp_rbac_items" ("name") ON DELETE CASCADE ON UPDATE CASCADE
                            );
                        ');

        $this->execute('CREATE TABLE "xmp_rbac_assignments"
                            (
                              "item_name"  VARCHAR(64) NOT NULL,
                              "user_id"    UUID NOT NULL,
                              "created_at" INTEGER,
                              PRIMARY KEY ("item_name", "user_id"),
                              FOREIGN KEY ("item_name") REFERENCES "xmp_rbac_items" ("name") ON DELETE CASCADE ON UPDATE CASCADE
                            );
                        ');

        $auth = Yii::$app->authManager;

        // add "logs" permission
        $perm = $auth->createPermission('logsView');
        $perm->description = 'Logs View';
        $auth->add($perm);


        // DO NOT USE
        // permission
        // INSERT INTO public.xmp_rbac_items (name, type, description, rule_name, data, created_at, updated_at) VALUES ('createPost', 2, 'Create a post', null, null, 1490262887, 1490262887);

        // role
        // INSERT INTO public.xmp_rbac_items (name, type, description, rule_name, data, created_at, updated_at) VALUES ('admin', 1, null, null, null, 1490263083, 1490263083);

        // assign
        // INSERT INTO public.xmp_rbac_items_childs (parent, child) VALUES ('admin', 'createPost');
    }

    public function safeDown()
    {
        $this->dropTable('xmp_rbac_assignments');
        $this->dropTable('xmp_rbac_items_childs');
        $this->dropTable('xmp_rbac_items');
        $this->dropTable('xmp_rbac_rules');
    }
}
