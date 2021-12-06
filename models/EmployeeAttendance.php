<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee_attendance".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property int $employee_id
 * @property int $attendent_status
 * @property string $created_on
 * @property string $reg_date
 * @property string $mod_date
 * @property string $created_by
 */
class EmployeeAttendance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_attendance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'employee_id', 'attendent_status', 'created_on', 'reg_date', 'mod_date', 'created_by'], 'required'],
            [['merchant_id', 'employee_id', 'attendent_status'], 'integer'],
            [['created_on', 'reg_date', 'mod_date'], 'safe'],
            [['created_by'], 'string', 'max' => 100],
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
            'employee_id' => 'Employee ID',
            'attendent_status' => 'Attendent Status',
            'created_on' => 'Created On',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
            'created_by' => 'Created By',
        ];
    }
}
