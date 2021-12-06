<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_permission_role_map".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property int $employee_id
 * @property int $permission_id
 * @property int $permission_status
 */
class MerchantPermissionRoleMap extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_permission_role_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'employee_id', 'permission_id', 'permission_status'], 'required'],
            [['merchant_id', 'employee_id', 'permission_id', 'permission_status'], 'integer'],
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
            'permission_id' => 'Permission ID',
            'permission_status' => 'Permission Status',
        ];
    }
}
