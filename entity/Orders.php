<?php

namespace app\entity;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $comment
 * @property float $price
 * @property int $status_id
 * @property string $create_time
 *
 * @property OrderProducts[] $orderProducts
 * @property Products[] $products
 * @property OrderStatuses $status
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'price'], 'required'],
            [['user_id', 'status_id'], 'integer'],
            [['price'], 'number'],
            [['user_id'], 'default', 'value' => null],
            [['status_id'], 'default', 'value' => 1],
            [['price'], 'default', 'value' => 0],
            [['comment'], 'string'],
            [['create_time'], 'safe'],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderStatuses::class, 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'comment' => 'Comment',
            'status_id' => 'Status ID',
            'create_time' => 'Create Time',
            'price' => 'Price',
        ];
    }

    /**
     * Gets query for [[OrderProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProducts::class, ['order_id' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::class, ['id' => 'product_id'])->viaTable('order_products', ['order_id' => 'id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus(): \yii\db\ActiveQuery
    {
        return $this->hasOne(OrderStatuses::class, ['id' => 'status_id']);
    }
}
