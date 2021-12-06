<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_push_pilot".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property int $order_id
 * @property int $pilot_id
 * @property int $status
 * @property string $reg_date
 * @property string $mod_date
 */
class OrderPushPilot extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_push_pilot';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'order_id', 'pilot_id'], 'required'],
            [['merchant_id', 'order_id', 'pilot_id', 'status'], 'integer'],
            [['reg_date', 'mod_date'], 'safe'],
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
            'order_id' => 'Order ID',
            'pilot_id' => 'Pilot ID',
            'status' => 'Status',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
        ];
    }
}
