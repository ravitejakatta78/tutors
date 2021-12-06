<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ingredient_purchase_detail".
 *
 * @property int $ID
 * @property int $purchase_id
 * @property int $ingredient_id
 * @property string $ingredient_name
 * @property float $bag_quantity
 * @property float $each_quantity
 * @property float $quantity_price
 * @property float $effective_price
 * @property float $purchase_quantity
 * @property int $purchase_qty_type
 * @property float|null $purchase_qty_units
 * @property float $used_qty
 * @property float $remaining_qty
 * @property float $purchase_price
 */
class IngredientPurchaseDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingredient_purchase_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['purchase_id', 'ingredient_id', 'ingredient_name', 'bag_quantity', 'each_quantity', 'quantity_price', 'effective_price', 'purchase_quantity', 'purchase_qty_type', 'purchase_price'], 'required'],
            [['purchase_id', 'ingredient_id', 'purchase_qty_type'], 'integer'],
            [['bag_quantity', 'each_quantity', 'quantity_price', 'effective_price', 'purchase_quantity', 'purchase_qty_units', 'used_qty', 'remaining_qty', 'purchase_price'], 'number'],
            [['ingredient_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'purchase_id' => 'Purchase ID',
            'ingredient_id' => 'Ingredient ID',
            'ingredient_name' => 'Ingredient Name',
            'bag_quantity' => 'Bag Quantity',
            'each_quantity' => 'Each Quantity',
            'quantity_price' => 'Quantity Price',
            'effective_price' => 'Effective Price',
            'purchase_quantity' => 'Purchase Quantity',
            'purchase_qty_type' => 'Purchase Qty Type',
            'purchase_qty_units' => 'Purchase Qty Units',
            'used_qty' => 'Used Qty',
            'remaining_qty' => 'Remaining Qty',
            'purchase_price' => 'Purchase Price',
        ];
    }
}
