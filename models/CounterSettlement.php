<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "counter_settlement".
 *
 * @property int $ID
 * @property int $pilot_id
 * @property float $pending_amount
 * @property float $order_amount
 * @property float $paid_amount
 * @property string $reg_date
 * @property int|null $created_by
 */
class CounterSettlement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'counter_settlement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pilot_id', 'pending_amount', 'order_amount', 'paid_amount'], 'required'],
            [['pilot_id', 'created_by'], 'integer'],
            [['pending_amount', 'order_amount', 'paid_amount'], 'number'],
            [['reg_date','order_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'pilot_id' => 'Pilot ID',
            'pending_amount' => 'Pending Amount',
            'order_amount' => 'Order Amount',
            'paid_amount' => 'Paid Amount',
            'reg_date' => 'Reg Date',
            'created_by' => 'Created By',
        ];
    }
}
