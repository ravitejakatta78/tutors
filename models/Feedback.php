<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property int $ID
 * @property string $merchant_id
 * @property string $order_id
 * @property string $user_id
 * @property string $rating
 * @property string $message
 * @property string $reg_date
 * @property string $mod_date
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'order_id', 'user_id', 'reg_date'], 'required'],
            [['message','pilot_message'], 'string'],
            [['mod_date'], 'safe'],
            [['merchant_id', 'order_id', 'user_id', 'rating','pilot_rating'], 'string', 'max' => 30],
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
            'order_id' => 'Order ID',
            'user_id' => 'User ID',
            'rating' => 'Rating',
            'message' => 'Message',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
        ];
    }
}
