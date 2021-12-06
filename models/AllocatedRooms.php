<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "allocated_rooms".
 *
 * @property int $ID
 * @property int $category_id
 * @property int $merchant_id
 * @property string $room_name
 * @property int $status
 * @property string $created_by
 * @property string $created_on
 * @property string|null $updated_by
 * @property string|null $updated_on
 */
class AllocatedRooms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'allocated_rooms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'merchant_id', 'room_name', 'created_by', 'created_on'], 'required'],
            [['category_id', 'merchant_id', 'status'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['room_name', 'created_by', 'updated_by'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'category_id' => 'Category ID',
            'merchant_id' => 'Merchant ID',
            'room_name' => 'Room Name',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_on' => 'Created On',
            'updated_by' => 'Updated By',
            'updated_on' => 'Updated On',
        ];
    }
}
