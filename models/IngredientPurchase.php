<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ingredient_purchase".
 *
 * @property int $ID
 * @property int $vendor_id
 * @property string $vendor_name
 * @property string $purchase_number
 * @property string $merchant_id
 * @property float $purchase_amount
 * @property float|null $amount_paid
 * @property string|null $vendor_bill_no
 * @property float|null $balance_amount
 * @property float|null $purchase_gst
 * @property string|null $purchase_payment_type
 * @property string|null $purchase_transcation_id
 * @property int $status
 * @property string $reg_date
 * @property string $modify_date
 */
class IngredientPurchase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingredient_purchase';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vendor_id', 'vendor_name', 'purchase_number', 'merchant_id', 'purchase_amount', 'status', 'reg_date', 'modify_date'], 'required'],
            [['vendor_id', 'status'], 'integer'],
            [['purchase_amount', 'amount_paid', 'balance_amount', 'purchase_gst'], 'number'],
            [['reg_date', 'modify_date'], 'safe'],
            [['vendor_name', 'vendor_bill_no', 'purchase_transcation_id'], 'string', 'max' => 255],
            [['purchase_number', 'merchant_id', 'purchase_payment_type'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'vendor_id' => 'Vendor ID',
            'vendor_name' => 'Vendor Name',
            'purchase_number' => 'Purchase Number',
            'merchant_id' => 'Merchant ID',
            'purchase_amount' => 'Purchase Amount',
            'amount_paid' => 'Amount Paid',
            'vendor_bill_no' => 'Vendor Bill No',
            'balance_amount' => 'Balance Amount',
            'purchase_gst' => 'Purchase Gst',
            'purchase_payment_type' => 'Purchase Payment Type',
            'purchase_transcation_id' => 'Purchase Transcation ID',
            'status' => 'Status',
            'reg_date' => 'Reg Date',
            'modify_date' => 'Modify Date',
        ];
    }
}
