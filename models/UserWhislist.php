<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property int $user_id
 * @property int $status
 * @property string $reg_date
 * @property string $updated_on
 * @property int $created_by
 * @property int $updated_by
 */
class UserWhislist extends \yii\db\ActiveRecord 
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_whislist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'merchant_id', 'reg_date'], 'required'],
            [['user_id', 'merchant_id', 'status', 'updated_by', 'created_by'], 'integer'],
            [['reg_date', 'updated_on'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'user_id' => 'User Id',
            'merchant_id' => 'Merchant Id',
            'status' => 'Status',
            'reg_date' => 'Reg Date',
            'created_by' => 'Created By',
            'updated_on' => 'Updated On',
            'updated_by' => 'Updated By'
        ];
    }
    
}
