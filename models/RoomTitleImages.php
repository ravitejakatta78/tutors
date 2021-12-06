<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "room_title_images".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property int $title_id
 * @property string $title_pic
 * @property int $pic_status
 * @property string $created_on
 * @property string $created_by
 * @property string|null $updated_on
 * @property string|null $updated_by
 */
class RoomTitleImages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'room_title_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'title_id', 'created_on', 'created_by'], 'required'],
            [['title_pic'], 'required', 'on' => 'inserttitleimagedet'],
            [['merchant_id', 'title_id', 'pic_status'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['title_pic'], 'string', 'max' => 225],
            [['created_by', 'updated_by'], 'string', 'max' => 100],
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
            'title_id' => 'Title ID',
            'title_pic' => 'Title Pic',
            'pic_status' => 'Pic Status',
            'created_on' => 'Created On',
            'created_by' => 'Created By',
            'updated_on' => 'Updated On',
            'updated_by' => 'Updated By',
        ];
    }
}
