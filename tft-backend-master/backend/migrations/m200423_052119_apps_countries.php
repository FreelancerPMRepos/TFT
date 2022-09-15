<?php

use yii\db\Schema;
use yii\db\Migration;

class m200423_052119_apps_countries extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%apps_countries}}',
            [
                'id'=> $this->primaryKey(11),
                'country_code'=> $this->string(2)->notNull()->defaultValue(''),
                'country_name'=> $this->string(100)->notNull()->defaultValue(''),
                'status'=> $this->integer(1)->notNull()->defaultValue(0),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%apps_countries}}');
    }
}
