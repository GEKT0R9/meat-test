<?php

namespace app\models;

class OrderProductForm extends \yii\base\Model
{
    public $product_id;
    public $count;

    public function rules()
    {
        return [
            [['product_id', 'count'], 'required'],
            [['product_id', 'count'], 'integer'],
        ];
    }
}