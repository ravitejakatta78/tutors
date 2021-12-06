<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ingredient_stock_register".
 *
 * @property int $ID
 * @property string $merchant_id
 * @property int $ingredient_id
 * @property string $ingredient_name
 * @property float $opening_stock
 * @property float $stock_in
 * @property float $stock_out
 * @property float $wastage
 * @property float $closing_stock
 * @property string $reg_date
 * @property string $created_on
 * @property float $marinated;
 */
class IngredientStockRegister extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingredient_stock_register';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'ingredient_id', 'ingredient_name', 'reg_date', 'created_on'], 'required'],
            [['ingredient_id'], 'integer'],
            [['opening_stock', 'stock_in', 'stock_out', 'wastage', 'closing_stock','marinated'], 'number'],
            [['reg_date', 'created_on'], 'safe'],
            [['merchant_id'], 'string', 'max' => 100],
            [['ingredient_name'], 'string', 'max' => 255],
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
            'ingredient_id' => 'Ingredient ID',
            'ingredient_name' => 'Ingredient Name',
            'opening_stock' => 'Opening Stock',
            'stock_in' => 'Stock In',
            'stock_out' => 'Stock Out',
            'wastage' => 'Wastage',
            'closing_stock' => 'Closing Stock',
            'reg_date' => 'Reg Date',
            'created_on' => 'Created On',
            'marinated' => 'Marinated'
        ];
    }
}
