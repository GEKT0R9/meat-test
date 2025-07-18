<?php

use yii\db\Migration;

/**
 * Class m250716_131602_orders
 */
class m250716_131602_orders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            'order_statuses',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(100)->notNull(),
                'description' => $this->text()->null(),
            ]
        );
        $this->insert(
            'order_statuses',
            [
                'title' => 'Новый заказ',
            ]
        );

        $this->createTable(
            'orders',
            [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer()->notNull(),
                'comment' => $this->text()->null(),
                'status_id' => $this->integer()->defaultValue(1)->notNull(),
                'price' => $this->float()->defaultValue(0)->notNull(),
                'create_time' => $this->timestamp()
                    ->defaultExpression('current_timestamp')
                    ->notNull(),
            ]
        );
        $this->addForeignKey(
            'orders_to_status_fk',
            'orders',
            'status_id',
            'order_statuses',
            'id',
            'NO ACTION',
            'CASCADE'
        );

        $this->createTable(
            'order_products',
            [
                'product_id' => $this->integer()->notNull(),
                'order_id' => $this->integer()->notNull(),
                'count' => $this->integer()->notNull(),
            ]
        );
        $this->addPrimaryKey('order_products_pk',
            'order_products',
            ['product_id', 'order_id']
        );
        $this->addForeignKey(
            'orders_to_products_fk',
            'order_products',
            'order_id',
            'orders',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'products_to_orders_fk',
            'order_products',
            'product_id',
            'products',
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
        $this->dropTable('order_products');
        $this->dropTable('orders');
        $this->dropTable('order_statuses');
    }

}
