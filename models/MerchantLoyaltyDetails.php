<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_loyalty_details".
 *
 * @property int $ID
 * @property string $merchant_id
 * @property int $loyalty_id
 * @property string $reg_date
 */
class MerchantLoyaltyDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_loyalty_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'loyalty_id', 'reg_date','user_id'], 'required'],
            [['loyalty_id','user_id'], 'integer'],
            [['merchant_id', 'reg_date'], 'string', 'max' => 255],
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
            'loyalty_id' => 'Loyalty ID',
            'reg_date' => 'Reg Date',
            'user_id' => 'User Id',
        ];
    }
}
