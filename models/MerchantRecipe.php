<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_recipe".
 *
 * @property int $ID
 * @property int $product_id
 * @property int $ingredient_id
 * @property float $ingred_quantity
 * @property float $ingred_price
 * @property int $status
 * @property int $merchant_id
 * @property string $reg_date
 * @property string $modify_date
 */
class MerchantRecipe extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_recipe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'ingredient_id', 'ingred_quantity', 'status', 'merchant_id', 'reg_date', 'modify_date'], 'required'],
            [['product_id', 'ingredient_id', 'status', 'merchant_id'], 'integer'],
            [['ingred_quantity', 'ingred_price'], 'number'],
            [['reg_date', 'modify_date'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'product_id' => 'Product ID',
            'ingredient_id' => 'Ingredient ID',
            'ingred_quantity' => 'Ingred Quantity',
            'ingred_price' => 'Ingred Price',
            'status' => 'Status',
            'merchant_id' => 'Merchant ID',
            'reg_date' => 'Reg Date',
            'modify_date' => 'Modify Date',
        ];
    }
}
