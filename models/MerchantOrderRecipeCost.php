<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_order_recipe_cost".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property int $order_id
 * @property int $product_id
 * @property int $ingredi_id
 * @property int $ingredi_qty
 * @property float $ingredi_price
 * @property string $ingredi_name
 * @property int $ingredi_detail_id
 * @property string $reg_date
 */
class MerchantOrderRecipeCost extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_order_recipe_cost';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'order_id', 'product_id', 'ingredi_id', 'ingredi_qty', 'ingredi_price', 'ingredi_name', 'ingredi_detail_id', 'reg_date'], 'required'],
            [['merchant_id', 'order_id', 'product_id', 'ingredi_id', 'ingredi_qty', 'ingredi_detail_id'], 'integer'],
            [['ingredi_price'], 'number'],
            [['reg_date'], 'safe'],
            [['ingredi_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'merchant_id' => 'Merchant ID',
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'ingredi_id' => 'Ingredi ID',
            'ingredi_qty' => 'Ingredi Qty',
            'ingredi_price' => 'Ingredi Price',
            'ingredi_name' => 'Ingredi Name',
            'ingredi_detail_id' => 'Ingredi Detail ID',
            'reg_date' => 'Reg Date',
        ];
    }
}
