<?php

use yii\db\Migration;

/**
 * Class m240220_112524_init
 */
class m240220_112524_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => 'int(11) NOT NULL PRIMARY KEY',

            //тут хотя бы first_name бы сделать required, но в ТЗ нет такого условия. Иначе можно создать пустого пользователя без каких либо данных, кроме id
            'first_name' => $this->string(255)->notNull()->comment('Имя'),
            'last_name' => $this->string(255)->notNull()->comment('Фамилия'),
            'country' => $this->string(255)->notNull()->comment('Страна'),
            'city' => $this->string(255)->notNull()->comment('Город'),
        ], $tableOptions);

        $this->createIndex('primary_key', '{{%user}}', 'id', true);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
