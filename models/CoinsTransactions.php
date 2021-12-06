<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coins_transactions".
 *
 * @property int $ID
 * @property string $user_id
 * @property string $txn_id
 * @property int $reward_id
 * @property int $rewardcoupon_id
 * @property int $order_id
 * @property int $merchant_id
 * @property int $coins
 * @property string $type
 * @property string $reason
 * @property string $reg_date
 * @property string $mod_date
 */
class CoinsTransactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coins_transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'txn_id', 'reward_id', 'rewardcoupon_id', 'order_id', 'merchant_id', 'coins', 'type', 'reason', 'reg_date'], 'required'],
            [['reward_id', 'rewardcoupon_id', 'order_id', 'merchant_id', 'coins'], 'integer'],
            [['reason'], 'string'],
            [['mod_date'], 'safe'],
            [['user_id', 'txn_id', 'type'], 'string', 'max' => 30],
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
            'user_id' => 'User ID',
            'txn_id' => 'Txn ID',
            'reward_id' => 'Reward ID',
            'rewardcoupon_id' => 'Rewardcoupon ID',
            'order_id' => 'Order ID',
            'merchant_id' => 'Merchant ID',
            'coins' => 'Coins',
            'type' => 'Type',
            'reason' => 'Reason',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
        ];
    }
}
