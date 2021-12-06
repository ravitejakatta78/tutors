<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_transactions".
 *
 * @property int $ID
 * @property string $user_id
 * @property string $order_id
 * @property string $merchant_id
 * @property string $paidstatus
 * @property string $paymenttype
 * @property string $amount
 * @property string $couponamount
 * @property string $tax
 * @property string $tips
 * @property string $subscription
 * @property string $totalamount
 * @property string $reorder
 * @property string $reg_date
 * @property string $mod_date
 */
class OrderTransactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'order_id', 'merchant_id', 'paidstatus', 'paymenttype', 'amount', 'couponamount', 'tax', 'tips', 'subscription', 'totalamount', 'reorder', 'reg_date'], 'required'],
            [['mod_date'], 'safe'],
            [['user_id', 'order_id'], 'string', 'max' => 50],
            [['merchant_id', 'paidstatus', 'paymenttype', 'amount', 'couponamount', 'tax', 'tips', 'subscription', 'totalamount', 'reorder', 'reg_date'], 'string', 'max' => 20],
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
            'order_id' => 'Order ID',
            'merchant_id' => 'Merchant ID',
            'paidstatus' => 'Paidstatus',
            'paymenttype' => 'Paymenttype',
            'amount' => 'Amount',
            'couponamount' => 'Couponamount',
            'tax' => 'Tax',
            'tips' => 'Tips',
            'subscription' => 'Subscription',
            'totalamount' => 'Totalamount',
            'reorder' => 'Reorder',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
        ];
    }
}
