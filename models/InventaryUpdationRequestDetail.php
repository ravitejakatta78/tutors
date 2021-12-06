<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventary_updation_request_detail".
 *
 * @property int $ID
 * @property int|null $merchant_id
 * @property string|null $reg_date
 * @property int|null $ingredient_id
 * @property string|null $ingredient_name
 * @property float|null $quantity
 * @property int|null $qty_type
 * @property float|null $total_quantity
 * @property int|null request_id
 */
class InventaryUpdationRequestDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventary_updation_request_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'ingredient_id', 'qty_type','request_id'], 'integer'],
            [['reg_date'], 'safe'],
            [['quantity', 'total_quantity'], 'number'],
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
            'merchant_id' => 'Merchant ID',
            'reg_date' => 'Reg Date',
            'ingredient_id' => 'Ingredient ID',
            'ingredient_name' => 'Ingredient Name',
            'quantity' => 'Quantity',
            'qty_type' => 'Qty Type',
            'total_quantity' => 'Total Quantity',
            'request_id'=>'Request Id'
        ];
    }
}
