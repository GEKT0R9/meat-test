<?php

use yii\db\Migration;

/**
 * Class m250716_120739_users
 * Миграция таблицы пользователей
 */
class m250716_120739_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            'users',
            [
                'id' => $this->primaryKey(),
                'phone' => $this->string(20)->notNull()->unique(),
                'password' => $this->string(250)->notNull(),
                'name' => $this->string(100)->notNull(),
                'address' => $this->string(200)->notNull(),
            ]
        );
        $this->insert(
            'users',
            [
                'phone' => '+79999999999',
                'name' => 'admin',
                'password' => password_hash('admin', PASSWORD_BCRYPT),
                'address' => 'ул. Администратора 1',
            ]
        );

        $this->createTable(
            'users_token',
            [
                'id' => $this->primaryKey(),
                'user_agent' => $this->string(200)->null(),
                'user_ip' => $this->string(100)->notNull(),
                'user_host' => $this->string(200)->null(),
                'access_token' => $this->string(250)->notNull(),
                'refresh_token' => $this->string(250)->notNull(),
                'user_id' => $this->integer()->notNull(),
                'lifetime_access' => $this->integer()->notNull(),
                'lifetime_refresh' => $this->integer()->notNull(),
                'create_time' => $this->timestamp()
                    ->defaultExpression('current_timestamp')
                    ->notNull(),
            ]
        );
        $this->addForeignKey(
            'user_to_tokens_fk',
            'users_token',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users_token');
        $this->dropTable('users');
    }
}
