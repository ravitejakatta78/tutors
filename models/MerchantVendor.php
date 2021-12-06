<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_vendor".
 *
 * @property int $ID
 * @property string $store_name
 * @property string $vendor_type
 * @property string|null $owner_name
 * @property string|null $owner_mobile
 * @property string|null $manager_name
 * @property string|null $manager_mobile
 * @property string $vendor_location
 * @property string $vendor_city
 * @property string $vendor_range
 * @property string $merchant_id
 * @property int $status
 * @property string $created_by
 * @property string $reg_date
 */
class MerchantVendor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_vendor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_name', 'vendor_type', 'vendor_location', 'vendor_city', 'vendor_range', 'merchant_id', 'status', 'created_by', 'reg_date'], 'required'],
            [['status'], 'integer'],
            [['reg_date'], 'safe'],
            [['store_name', 'vendor_type', 'owner_name', 'manager_name', 'vendor_location', 'vendor_city', 'vendor_range', 'created_by'], 'string', 'max' => 100],
            [['owner_mobile', 'manager_mobile', 'merchant_id'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'store_name' => 'Store Name',
            'vendor_type' => 'Vendor Type',
            'owner_name' => 'Owner Name',
            'owner_mobile' => 'Owner Mobile',
            'manager_name' => 'Manager Name',
            'manager_mobile' => 'Manager Mobile',
            'vendor_location' => 'Vendor Location',
            'vendor_city' => 'Vendor City',
            'vendor_range' => 'Vendor Range',
            'merchant_id' => 'Merchant ID',
            'status' => 'Status',
            'created_by' => 'Created By',
            'reg_date' => 'Reg Date',
        ];
    }
}
