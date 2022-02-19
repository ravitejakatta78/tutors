<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_info".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property string $merchant_description
 * @property string $reg_date
 */
class MerchantInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'merchant_description', 'reg_date'], 'required'],
            [['merchant_id'], 'integer'],
            [['merchant_description'], 'string'],
            [['reg_date'], 'safe'],
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
            'merchant_description' => 'Merchant Description',
            'reg_date' => 'Reg Date',
        ];
    }
}
