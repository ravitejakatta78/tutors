<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "room_guest_identitiy".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property int $reservation_id
 * @property int $category_id
 * @property int $room_id
 * @property string|null $guest_name
 * @property string|null $guest_identity
 * @property int|null $guest_id_name
 * @property string $created_on
 * @property string $created_by
 * @property string|null $updated_on
 * @property string|null $updated_by
 */
class RoomGuestIdentitiy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'room_guest_identitiy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'merchant_id', 'reservation_id', 'category_id', 'room_id', 'created_on', 'created_by'], 'required'],
            [['ID', 'merchant_id', 'reservation_id', 'category_id', 'room_id', 'guest_id_name'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['guest_name', 'created_by', 'updated_by'], 'string', 'max' => 100],
            [['guest_identity'], 'string', 'max' => 255],
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
            'reservation_id' => 'Reservation ID',
            'category_id' => 'Category ID',
            'room_id' => 'Room ID',
            'guest_name' => 'Guest Name',
            'guest_identity' => 'Guest Identity',
            'guest_id_name' => 'Guest Id Name',
            'created_on' => 'Created On',
            'created_by' => 'Created By',
            'updated_on' => 'Updated On',
            'updated_by' => 'Updated By',
        ];
    }
}
