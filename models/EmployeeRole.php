<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee_role".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property string $role_name
 * @property int $role_status
 * @property string $created_by
 * @property string $reg_date
 */
class EmployeeRole extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'role_name', 'role_status', 'created_by', 'reg_date'], 'required'],
            [['merchant_id', 'role_status'], 'integer'],
            [['reg_date'], 'safe'],
            [['role_name', 'created_by'], 'string', 'max' => 100],
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
            'role_name' => 'Role Name',
            'role_status' => 'Role Status',
            'created_by' => 'Created By',
            'reg_date' => 'Reg Date',
        ];
    }
}
