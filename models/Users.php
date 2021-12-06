<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $ID
 * @property string $unique_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $mobile
 * @property string $otp
 * @property string $status 0=pending,1=active,2-failed
 * @property string $push_id
 * @property int $coins
 * @property string $profilepic
 * @property string $reg_date
 * @property string $mod_date
 * @property string|null $date_of_birth
 * @property string|null $referral_code
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $refered_code
 */
class Users extends \yii\db\ActiveRecord 
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unique_id', 'name', 'mobile', 'status', 'reg_date'], 'required'],
            [['password', 'mobile', 'status', 'push_id', 'profilepic', 'latitude', 'longitude'], 'string'],
            [['coins'], 'integer'],
            [['mod_date', 'date_of_birth'], 'safe'],
            [['unique_id', 'name', 'email'], 'string', 'max' => 50],
            [['otp'], 'string', 'max' => 10],
            [['reg_date'], 'string', 'max' => 20],
            [['referral_code', 'refered_code'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'unique_id' => 'Unique ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'mobile' => 'Mobile',
            'otp' => 'Otp',
            'status' => 'Status',
            'push_id' => 'Push ID',
            'coins' => 'Coins',
            'profilepic' => 'Profilepic',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
            'date_of_birth' => 'Date Of Birth',
            'referral_code' => 'Referral Code',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'refered_code' => 'Refered Code',
        ];
    }
    
}
