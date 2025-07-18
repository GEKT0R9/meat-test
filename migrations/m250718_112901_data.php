<?php

use yii\db\Migration;

/**
 * Class m250718_112901_data
 */
class m250718_112901_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('categories',[
            'id' => 1,
            'title' => 'Первая'
        ]);
        $this->insert('categories',[
            'id' => 2,
            'title' => 'Вторая'
        ]);

        $this->insert('products',[
            'title' => 'Заголовок1',
            'description' => 'Описание1',
            'price' => '123',
            'category_id' => '1',
            'count' => '20',
        ]);
        $this->insert('products',[
            'title' => 'Заголовок2',
            'description' => 'Описание2',
            'price' => '30',
            'category_id' => '1',
            'count' => '20',
        ]);
        $this->insert('products',[
            'title' => 'Заголовок3',
            'description' => 'Описание3',
            'price' => '12',
            'category_id' => '2',
            'count' => '20',
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250718_112901_data cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250718_112901_data cannot be reverted.\n";

        return false;
    }
    */
}
