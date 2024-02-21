<?php

use yii\db\Migration;

/**
 * Class m240220_114315_add_user_sessions_table
 */
class m240220_114315_add_user_sessions_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_sessions}}', [
            'user_id' => $this->primaryKey()->comment('Идентификатор пользователя'),
            'access_token' => $this->string(255)->notNull()->comment('Токен'),
        ], $tableOptions);

        $this->createIndex('access_token', '{{%user_sessions}}', 'access_token');

        $this->addForeignKey('user-session', '{{%user_sessions}}', 'user_id', '{{%user}}', 'id','CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('user-session', '{{%user_sessions}}');
        $this->dropTable('{{%user_sessions}}');
    }
}
