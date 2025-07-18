<?php

namespace app\operations;

use app\entity\Products;
use app\repository\OrdersRepository;
use app\repository\ProductsRepository;

class OrdersOperations
{
    public static function createOrder($user_id, $products, $comment = null)
    {
        $order_id = false;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $order_id = OrdersRepository::createOrder($user_id, $comment);
            foreach ($products as $product) {
                OrdersRepository::addProductToOrder($order_id, $product['product_id'], $product['count']);
            }
            OrdersRepository::updatePrice($order_id, ProductsRepository::getOrderSumPrice($order_id));
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
        }
        return $order_id;
    }
}