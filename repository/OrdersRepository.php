<?php

namespace app\repository;

use app\entity\OrderProducts;
use app\entity\Orders;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\db\Transaction;

class OrdersRepository
{
    public static function getOrder($where)
    {
        return Orders::find()->where($where)->one();
    }

    public static function getOrders($where = [])
    {
        return Orders::find()->where($where)->all();
    }

    public static function getOrdersToList($where = [])
    {
        return Orders::find()
            ->where($where)
            ->joinWith('status as s', true)
            ->joinWith('orderProducts')
            ->joinWith('orderProducts.product.category')
            ->orderBy(['create_time' => SORT_DESC])
            ->asArray()
            ->all();
    }

    public static function createOrder($user_id, $comment = null, $status_id = 1)
    {
        $order = new Orders();
        $order->user_id = $user_id;
        $order->comment = $comment;
        $order->status_id = $status_id;
        $order->price = 0;
        $order->save();
        return $order->id;
    }

    public static function addProductToOrder($order_id, $product_id, $count)
    {
        $orderProduct = new OrderProducts();
        $orderProduct->order_id = $order_id;
        $orderProduct->product_id = $product_id;
        $orderProduct->count = $count;
        $orderProduct->save();
        ProductsRepository::updateProductCount($product_id, $count);
    }

    public static function updatePrice($order_id, $price)
    {
        $order = self::getOrder(['id' => $order_id]);
        $order->price = $price;
        return $order->save();
    }


}
