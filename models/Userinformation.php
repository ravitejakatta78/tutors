<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "userinformation".
 *
 * @property int $ID
 * @property string $user_name
 * @property int $user_mobile_no
 * @property string $room_category
 * @property int $price
 * @property string $guests
 * @property string $payment_status
 * @property int $paid_amount
 * @property int $pending_amount
 * @property string $booking_time
 * @property string $arrived_time
 * @property string $room_alocated
 * @property string $merchant_id
 * @property int $reservation_status
 */
class Userinformation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userinformation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_name', 'user_mobile_no', 'room_category', 'price', 'guests', 'payment_status', 'paid_amount', 'pending_amount', 'booking_time', 'arrived_time', 'room_alocated', 'merchant_id', 'reservation_status'], 'required'],
            [[ 'price', 'paid_amount', 'pending_amount', 'reservation_status'], 'integer'],
            [['booking_time', 'arrived_time'], 'safe'],
            [['user_name', 'guests', 'payment_status', 'room_alocated', 'merchant_id'], 'string', 'max' => 20],
            [['room_category','user_mobile_no'], 'string', 'max' => 100],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'user_name' => 'User Name',
            'user_mobile_no' => 'User Mobile No',
            'room_category' => 'Room Category',
            'price' => 'Price',
            'guests' => 'Guests',
            'payment_status' => 'Payment Status',
            'paid_amount' => 'Paid Amount',
            'pending_amount' => 'Pending Amount',
            'booking_time' => 'Booking Time',
            'arrived_time' => 'Arrived Time',
            'room_alocated' => 'Room Alocated',
            'merchant_id' => 'Merchant ID',
            'reservation_status' => 'Reservation Status',
        ];
    }
}
