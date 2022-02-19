<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_amenities".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property int $amenity_id
 * @property int $status 1.Active, 2.Inactive
 * @property string $reg_date
 */
class MerchantAmenities extends \yii\db\ActiveRecord
{
    const AMENITIES = ['1' => 'Lift', '2' => 'Wifi', '3' => 'Washrooms', '4' => 'Smoking'
        , '5' => 'Alcohol', '6' => 'Pets', '7' => 'Wheel Chair', '8' => 'Parking'];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_amenities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'amenity_id', 'reg_date'], 'required'],
            [['merchant_id', 'amenity_id', 'status'], 'integer'],
            [['reg_date'], 'safe'],
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
            'amenity_id' => 'Amenity ID',
            'status' => 'Status',
            'reg_date' => 'Reg Date',
        ];
    }
}
