<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_food_category_tax".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property int $food_category_id
 * @property int $merchant_tax_id
 * @property string $tax_type
 * @property int $tax_value
 * @property string $status
 * @property string $created_on
 * @property string $created_by
 * @property string|null $updated_on
 * @property string $updated_by
 */
class MerchantFoodCategoryTax extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_food_category_tax';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'merchant_id', 'food_category_id', 'merchant_tax_id', 'tax_type', 'tax_value', 'status', 'created_by', 'updated_by'], 'required'],
            [['ID', 'merchant_id', 'food_category_id', 'merchant_tax_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['tax_type', 'status'], 'string', 'max' => 50],
            [['created_by', 'updated_by'], 'string', 'max' => 100],
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
            'food_category_id' => 'Food Category ID',
            'merchant_tax_id' => 'Merchant Tax ID',
            'tax_type' => 'Tax Type',
            'tax_value' => 'Tax Value',
            'status' => 'Status',
            'created_on' => 'Created On',
            'created_by' => 'Created By',
            'updated_on' => 'Updated On',
            'updated_by' => 'Updated By',
        ];
    }
}
