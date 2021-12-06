<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "room_profile_titles".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property string $title_name
 * @property int $status
 * @property string $created_on
 * @property string $created_by
 * @property string|null $updated_on
 * @property string|null $updated_by
 */
class RoomProfileTitles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'room_profile_titles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'title_name', 'created_on', 'created_by'], 'required'],
            [['merchant_id', 'status'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['title_name', 'created_by', 'updated_by'], 'string', 'max' => 100],
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
            'title_name' => 'Title Name',
            'status' => 'Status',
            'created_on' => 'Created On',
            'created_by' => 'Created By',
            'updated_on' => 'Updated On',
            'updated_by' => 'Updated By',
        ];
    }
}
