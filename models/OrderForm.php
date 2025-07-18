<?php

namespace app\models;

use app\entity\Products;
use app\repository\ProductsRepository;
use app\repository\UserRepository;

class OrderForm extends \yii\base\Model
{
    public $user_id;
    public $products;
    public $comment;

    private $_products = [];

    public function rules()
    {
        return [
            [['user_id', 'products'], 'required'],
            ['comment', 'string'],
            ['comment', 'default', 'value' => ''],
            ['user_id', 'validateUser'],
            ['products', 'validateProductsForm'],
            ['products', 'validateProducts'],
        ];
    }

    public function validateUser($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (empty(UserRepository::getUser(['id' => $this->user_id]))) {
                $this->addError($attribute, 'Пользователя с данным id не существует');
            }
        }
    }


    public function validateProductsForm($attribute, $params)
    {
        if (!$this->hasErrors()) {
            foreach ($this->products as $id => $product) {
                $model = new OrderProductForm();
                $model->load($product, '');
                if (!$model->validate()) {
                    $this->addError($attribute, "Некорректный ввод, элемент с индексом '{$id}'");
                    break;
                }
                if (!empty($this->_products[$model->product_id])) {
                    $this->addError($attribute, "Дубликат, элемент с индексом '{$id}'");
                    break;
                }
                $this->_products[$model->product_id] = $model->count;
            }
        }
    }

    public function validateProducts($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $productIDs = array_keys($this->_products);
            $productsInDB = ProductsRepository::getProducts(['in', 'id', $productIDs]);
            if (count($productIDs) != count($productsInDB)){
                $this->addError($attribute, "Добавлены отсутствующие товары: "
                    . implode(', ', array_diff(
                        $productIDs,
                        array_map(fn(Products $product) => $product->id, $productsInDB)
                    ))
                );
                return;
            }
            /** @var Products $productInDB */
            foreach ($productsInDB as $productInDB){
                if ($productInDB->count < $this->_products[$productInDB->id]){
                    $this->addError($attribute, "Продукта под индексом '{$productInDB->id}' недостаточно");
                }
            }
        }
    }
}