<?php

namespace app\repository;

use app\entity\Products;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;

class ProductsRepository
{
    public static function getProductsToList($where = [])
    {
        return Products::find()
            ->select([
                'id' => 'products.id',
                'title' => 'products.title',
                'description' => 'products.description',
                'price' => 'products.price',
                'category' => 'c.title',
                'count' => 'products.count',
            ])
            ->joinWith('category as c', false)
            ->where($where)
            ->asArray()
            ->all();
    }

    public static function getProducts($where = []){
        return Products::find()->where($where)->all();
    }

    public static function getProduct($where){
        return Products::find()->where($where)->one();
    }

    public static function updateProductCount($product_id, $diffCount){
        /** @var Products $product */
        $product = self::getProduct(['id' => $product_id]);
        $product->count -= $diffCount;
        $product->save();
        return $product->count;
    }

    public static function getOrderSumPrice($order_id){
        $query = (new Query())
            ->select(['sum' => 'SUM(result)'])
            ->from((new Query())
                ->select(['result' => '"p"."price" * "order_products"."count"'])
                ->from(['order_products'])
                ->leftJoin('products as p', 'p.id = order_products.product_id')
                ->where(['order_id' => $order_id])
            )
            ->one();
        if (!empty($query)){
            return $query['sum'];
        }
        return 0;
    }
}