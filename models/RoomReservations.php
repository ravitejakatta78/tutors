<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "room_reservations".
 *
 * @property int $ID
 * @property string $merchant_id
 * @property string $category_name
 * @property string $category_pic
 * @property string $price
 * @property string $availability
 * @property int $availability_alert
 */
class RoomReservations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'room_reservations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'category_name', 'price', 'availability', 'availability_alert'], 'required'],
            [['category_name', 'category_pic', 'price', 'availability'], 'string'],
            [['category_pic'], 'required', 'on' => 'insertreservationdet'],
            [['availability_alert'], 'integer'],
            [['merchant_id'], 'string', 'max' => 50],
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
            'category_name' => 'Category Name',
            'category_pic' => 'Category Pic',
            'price' => 'Price',
            'availability' => 'Availability',
            'availability_alert' => 'Availability Alert',
        ];
    }
}
