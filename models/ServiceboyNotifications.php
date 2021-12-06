<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "serviceboy_notifications".
 *
 * @property int $ID
 * @property string $merchant_id
 * @property string $serviceboy_id
 * @property string $order_id
 * @property string $title
 * @property string $message
 * @property string $ordertype
 * @property string $seen
 * @property string $reg_date
 * @property string $mod_date
 */
class ServiceboyNotifications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'serviceboy_notifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'serviceboy_id', 'order_id', 'title', 'message', 'ordertype', 'seen', 'reg_date'], 'required'],
            [['title', 'message', 'seen'], 'string'],
            [['mod_date'], 'safe'],
            [['merchant_id', 'serviceboy_id', 'order_id'], 'string', 'max' => 30],
            [['ordertype'], 'string', 'max' => 50],
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
            'serviceboy_id' => 'Serviceboy ID',
            'order_id' => 'Order ID',
            'title' => 'Title',
            'message' => 'Message',
            'ordertype' => 'Ordertype',
            'seen' => 'Seen',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
        ];
    }
}
