<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "food_categeries".
 *
 * @property int $ID
 * @property string $merchant_id
 * @property string $food_category
 * @property string $reg_date
 */
class FoodCategeries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'food_categeries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'food_category', 'reg_date'], 'required'],
            [['merchant_id'], 'string', 'max' => 50],
            [['food_category'], 'string', 'max' => 255],
            [['reg_date'], 'string', 'max' => 50],
            [['food_section_id','upselling'],'integer']
            
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
            'food_category' => 'Food Category',
            'reg_date' => 'Reg Date',
            'food_section_id' => 'Food Section Id',
            'upselling' => 'Upselling'
        ];
    }
    public static function allcategeries()
    {
	return 	FoodCategeries::find()
	->where(['merchant_id' => Yii::$app->user->identity->merchant_id])
	->orderBy([
            'ID'=>SORT_DESC
        ])
	->asArray()->all();
    }
}
