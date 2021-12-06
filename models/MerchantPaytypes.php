<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_paytypes".
 *
 * @property int $ID
 * @property string $merchant_id
 * @property string $paymenttype
 * @property string $merchantid
 * @property string $merchantkey
 * @property string $reg_date
 * @property string $mod_date
 */
class MerchantPaytypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_paytypes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'paymenttype', 'reg_date'], 'required'],
            [['merchantid', 'merchantkey'], 'string'],
            [['mod_date'], 'safe'],
            [['status'], 'integer'],
            [['merchant_id', 'paymenttype'], 'string', 'max' => 30],
            [['reg_date'], 'string', 'max' => 20],
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
            'paymenttype' => 'Paymenttype',
            'merchantid' => 'Merchantid',
            'merchantkey' => 'Merchantkey',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
        ];
    }
}
