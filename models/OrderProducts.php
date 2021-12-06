<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_products".
 *
 * @property int $ID
 * @property string $user_id
 * @property string $order_id
 * @property string $merchant_id
 * @property string $product_id
 * @property string $count
 * @property string $price
 * @property string $inc
 * @property string $reorder
 * @property string $reg_date
 * @property string $mod_date
 */
class OrderProducts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'order_id', 'merchant_id', 'product_id', 'count', 'price', 'inc', 'reorder', 'reg_date'], 'required'],
            [['mod_date'], 'safe'],
            [['user_id', 'order_id', 'merchant_id', 'product_id', 'count', 'price', 'inc', 'reorder'], 'string', 'max' => 50],
            [['reg_date'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'user_id' => 'User ID',
            'order_id' => 'Order ID',
            'merchant_id' => 'Merchant ID',
            'product_id' => 'Product ID',
            'count' => 'Count',
            'price' => 'Price',
            'inc' => 'Inc',
            'reorder' => 'Reorder',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
        ];
    }
}
