<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pilot_demo_requests".
 *
 * @property int $ID Primary key with auto incrementt
 * @property string $business_name
 * @property string $owner_name
 * @property string $location
 * @property string $city
 * @property string $state
 * @property string $pincode
 * @property string $mobile_number
 * @property string $alt_mobile_number
 * @property string $lat
 * @property string $lng
 * @property string $reg_date
 */
class PilotDemoRequests extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pilot_demo_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_name', 'owner_name', 'location', 'city', 'state', 'pincode', 'mobile_number', 'alt_mobile_number', 'lat', 'lng', 'reg_date'], 'required'],
            [['reg_date'], 'safe'],
            [['business_name', 'owner_name'], 'string', 'max' => 100],
            [['location'], 'string', 'max' => 255],
            [['city', 'state'], 'string', 'max' => 30],
            [['pincode'], 'string', 'max' => 10],
            [['mobile_number', 'alt_mobile_number'], 'string', 'max' => 20],
            [['lat', 'lng'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'business_name' => 'Business Name',
            'owner_name' => 'Owner Name',
            'location' => 'Location',
            'city' => 'City',
            'state' => 'State',
            'pincode' => 'Pincode',
            'mobile_number' => 'Mobile Number',
            'alt_mobile_number' => 'Alt Mobile Number',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'reg_date' => 'Reg Date',
        ];
    }
}
