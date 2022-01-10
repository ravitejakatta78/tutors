<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "serviceboy".
 *
 * @property int $ID
 * @property string $merchant_id
 * @property string $unique_id
 * @property int $employee_id
 * @property string $name
 * @property string $mobile
 * @property string $email
 * @property string $password
 * @property string $status 0=pending,1=active,2-failed
 * @property string $loginstatus
 * @property string $push_id
 * @property string $profilepic
 * @property int $otp
 * @property int $loginaccess
 * @property string $joiningdate
 * @property string $reg_date
 * @property string $mod_date
 */
class Serviceboy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'serviceboy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'unique_id', 'name', 'mobile', 'email', 'status', 'loginstatus', 'loginaccess', 'joiningdate', 'reg_date'], 'required'],
            [['name', 'mobile', 'email', 'password', 'status', 'loginstatus', 'push_id', 'profilepic'], 'string'],
            [['otp', 'loginaccess', 'employee_id'], 'integer'],
			['password','required','on'=>'passwordscenario'],
            [['mod_date'], 'safe'],
            [['merchant_id', 'unique_id'], 'string', 'max' => 50],
            [['joiningdate', 'reg_date'], 'string', 'max' => 20],
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
            'unique_id' => 'Unique ID',
            'employee_id' => 'Employee ID',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'password' => 'Password',
            'status' => 'Status',
            'loginstatus' => 'Loginstatus',
            'push_id' => 'Push ID',
            'profilepic' => 'Profilepic',
            'otp' => 'Otp',
            'loginaccess' => 'Loginaccess',
            'joiningdate' => 'Joiningdate',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
        ];
    }
}
