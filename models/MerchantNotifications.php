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
class MerchantNotifications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_notifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'message', 'seen'], 'required'],
            [['message', 'seen'], 'string'],
            [['merchant_id','message','seen','created_on','created_by'], 'safe'],
           
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
            'message' => 'Message',
            'seen' => 'Seen',
            'created_on' => 'Created On',
            'created_by' => 'Created By',
        ];
    }
}
