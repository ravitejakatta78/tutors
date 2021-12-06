<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "food_category_types".
 *
 * @property int $ID
 * @property int $food_cat_id
 * @property string $merchant_id
 * @property string $food_type_name
 * @property string $reg_date
 */
class FoodCategoryTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'food_category_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['food_cat_id', 'merchant_id', 'food_type_name', 'reg_date'], 'required'],
            [['food_cat_id'], 'integer'],
            [['merchant_id'], 'string', 'max' => 100],
            [['food_type_name'], 'string', 'max' => 255],
            [['reg_date'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'food_cat_id' => 'Food Cat ID',
            'merchant_id' => 'Merchant ID',
            'food_type_name' => 'Food Type Name',
            'reg_date' => 'Reg Date',
        ];
    }
}
