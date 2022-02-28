<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant".
 *
 * @property int $ID
 * @property string $user_id
 * @property string $unique_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $mobile
 * @property string $storetype
 * @property string $storename
 * @property string $address
 * @property string $state
 * @property string $city
 * @property string $location
 * @property string $logo
 * @property string $latitude
 * @property string $longitude
 * @property string $qrlogo
 * @property string $coverpic
 * @property string $status 0=pending,1=active,2-failed
 * @property int $otp
 * @property string $recommend
 * @property string $verify 0=pending,1=verify
 * @property string $description
 * @property string $servingtype
 * @property string $plan
 * @property string $useraccess
 * @property string $reg_date
 * @property string $mod_date
 * @property string $subscription_date
 * @property int $allocated_msgs
 * @property int $used_msgs
 * @property int $popularity
 * @property int $cancel_decision
 */
class Merchant extends \yii\db\ActiveRecord 
{
    /**
     * {@inheritdoc}
     */
	public $newpassword;
	public $confirmpassword;
    public static function tableName()
    {
        return 'merchant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'unique_id', 'name', 'email', 'password', 'mobile', 'storetype', 'storename'
			, 'address', 'state', 'city', 'location', 'latitude', 'longitude', 'scan_range'], 'required'],
            [['password', 'mobile', 'storetype', 'storename', 'address', 'state', 'city', 'location'
			, 'latitude', 'longitude',   'status', 'recommend', 'verify', 'description'
			, 'servingtype', 'plan', 'useraccess'], 'string'],
			[['logo','qrlogo','coverpic'], 'file', 'extensions' => ['png', 'jpg', 'gif']],
            [['otp','owner_type','open_time','close_time','table_res_avail', 'table_occupy_status', 'popularity', 'cancel_decision'], 'integer'],
		    [['mod_date', 'food_serve_type', 'subscription_date','allocated_msgs','used_msgs'], 'safe'],
		    [['scan_range','tax','tip', 'approx_cost'], 'number'],
            [['user_id', 'unique_id', 'name', 'email'], 'string', 'max' => 50],
            [['reg_date'], 'string', 'max' => 20],
			[['newpassword', 'confirmpassword'], 'required', 'on' => 'chagepassword'],
		  ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'user_id' => 'User ID',
            'unique_id' => 'Unique ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'mobile' => 'Mobile',
            'storetype' => 'Storetype',
            'storename' => 'Storename',
            'address' => 'Address',
            'state' => 'State',
            'city' => 'City',
            'location' => 'Location',
            'logo' => 'Logo',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'qrlogo' => 'Qrlogo',
            'coverpic' => 'Coverpic',
            'status' => 'Status',
            'otp' => 'Otp',
            'recommend' => 'Recommend',
            'verify' => 'Verify',
            'description' => 'Description',
            'servingtype' => 'Servingtype',
            'plan' => 'Plan',
            'useraccess' => 'Useraccess',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
            'owner_type' => 'Owner Type',
            'open_time' => 'Open Time',
            'close_time' => 'Close Time',
            'table_res_avail' => 'Table Reservation Availablity',
            'food_serve_type' => 'Food Serve Type',
			'scan_range' => 'Scan Range',
			'tax' => 'Tax',
			'tip' => 'Tip',
			'table_occupy_status' => 'Table Occupy Status',
			'popularity' => 'Popularity',
            'cancel_decision' => 'Cancel Decision'
        ];
    }

}
