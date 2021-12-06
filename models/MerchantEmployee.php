<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_employee".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property string $emp_name
 * @property string $emp_password
 * @property int $emp_role
 * @property string $emp_phone
 * @property string $emp_email
 * @property float $emp_exp
 * @property string $date_of_join
 * @property float $emp_salary
 * @property string $emp_designation
 * @property string $emp_specialities
 * @property int $emp_status
 * @property string $emp_id
 * @property string $loginstatus
 * @property string $push_id
 * @property string $profilepic
 * @property string $otp
 * @property string $loginaccess
 * @property string $reg_date
 * @property string $mod_date
 * @property string $created_by
 */
class MerchantEmployee extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'emp_name', 'emp_password', 'emp_role', 'emp_phone', 'emp_email', 'emp_exp', 'date_of_join', 'emp_salary', 'emp_designation', 'emp_specialities', 'emp_status', 'emp_id', 'reg_date', 'mod_date', 'created_by'], 'required'],
            [['ID','merchant_id', 'emp_role', 'emp_status','loginstatus','otp','loginaccess'], 'integer'],
            [['emp_password', 'push_id', 'profilepic'], 'string'],
            [['emp_exp', 'emp_salary'], 'number'],
            [['date_of_join', 'reg_date', 'mod_date'], 'safe'],
            [['emp_name', 'emp_email', 'emp_designation', 'emp_id', 'created_by'], 'string', 'max' => 100],
            [['emp_phone'], 'string', 'max' => 20],
            [['emp_specialities'], 'string', 'max' => 255],
			['emp_email','unique', 'on'=>'insertemail', 'message' => 'This email has already been taken.'],
			['emp_email','updatemerchantemail', 'on'=>'updateemail'],
			['emp_phone','unique', 'on'=>'insertemail', 'message' => 'This mobile number has already been taken.'],
			['emp_phone','updatemerchantemail', 'on'=>'updateemail'],	
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
            'emp_name' => 'Emp Name',
            'emp_password' => 'Emp Password',
            'emp_role' => 'Emp Role',
            'emp_phone' => 'Emp Phone',
            'emp_email' => 'Emp Email',
            'emp_exp' => 'Emp Exp',
            'date_of_join' => 'Date Of Join',
            'emp_salary' => 'Emp Salary',
            'emp_designation' => 'Emp Designation',
            'emp_specialities' => 'Emp Specialities',
            'emp_status' => 'Emp Status',
            'emp_id' => 'Emp ID',
            'loginstatus' => 'Loginstatus',
            'push_id' => 'Push Id',
            'profilepic' => 'Profilepic',
            'otp' => 'OTP',
            'loginaccess' => 'Loginaccess',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
            'created_by' => 'Created By',
        ];
    }
	/**
     * {@inheritdoc}
     */
	 public function pwdequalcheck($attribute, $params, $validator){

        $user_id = Yii::$app->user->identity->ID;
        $user_det =  \app\models\Merchant::findOne($user_id);
        if (password_verify($this->$attribute, $user_det->password)) {
   
        } else {

             $this->addError($attribute, 'Incorrect password');
        } 
    }
	
	public function updatemerchantemail($attribute, $params)
    {
		$oldDet = MerchantEmployee::findOne($this->ID);
		if($oldDet[$attribute] != $this->$attribute){
			$merchantEmailCheck = MerchantEmployee::find()->where([$attribute=>$this->$attribute])->asArray()->all();
			if(count($merchantEmailCheck) > 0 ){
				
				if($attribute == 'emp_email'){
					$word = 'email'; 
				}
				else{
					$word = 'mobile';
				}
					$this->addError($attribute, 'This '.$word.' has already been taken.');
			}
	    }
    }
	
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    public static function findByUsername($username)
    {       
                return static::findOne(['emp_email' => $username]);

    }


    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->ID;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
    //    return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
      //  return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
	 if (password_verify($password, $this->emp_password)) {
             return true;   
        } else {
             return false;
        } 
       // $md5Password = md5($password);
       // return $this->password === $password;
    }
}
