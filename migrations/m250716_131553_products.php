<?php

use yii\db\Migration;

/**
 * Class m250716_131553_products
 */
class m250716_131553_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            'categories',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(100)->notNull(),
                'description' => $this->text()->null(),
            ]
        );
        $this->createTable(
            'products',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(100)->notNull(),
                'description' => $this->text()->null(),
                'price' => $this->float()->notNull(),
                'category_id' => $this->integer()->notNull(),
                'count' => $this->integer()->defaultValue(0)->notNull(),
            ]
        );
        $this->addForeignKey(
            'product_to_category_fk',
            'products',
            'category_id',
            'categories',
            'id',
            'NO ACTION',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('products');
        $this->dropTable('categories');
    }
}
