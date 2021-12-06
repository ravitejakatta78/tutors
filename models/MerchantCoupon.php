<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_coupon".
 *
 * @property int $ID
 * @property string $merchant_id
 * @property string $category
 * @property string $product
 * @property string $purpose
 * @property string $code
 * @property int $validity
 * @property string $description
 * @property string $type
 * @property string $price
 * @property string $fromdate
 * @property string $todate
 * @property string $status
 * @property string $maxamt
 * @property int $minorderamt
 * @property string $reg_date
 * @property string $mod_date
 */
class MerchantCoupon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_coupon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id',   'purpose', 'code', 'validity', 'description', 'type', 'price', 'fromdate', 'todate', 'status', 'maxamt', 'minorderamt', 'reg_date'], 'required'],
            [['validity', 'minorderamt'], 'integer'],
			['code', 'unique', 'on' => 'uniquescenario'],
            [['description', 'type', 'price', 'fromdate', 'todate', 'status', 'maxamt'], 'string'],
			['todate', 'compare', 'compareAttribute' => 'fromdate', 'operator' => '>='],
            [['mod_date'], 'safe'],
            [['merchant_id', 'category', 'purpose', 'code'], 'string', 'max' => 30],
            [['reg_date'], 'string', 'max' => 20],
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
            'category' => 'Category',
            'product' => 'Product',
            'purpose' => 'Purpose',
            'code' => 'Code',
            'validity' => 'Validity',
            'description' => 'Description',
            'type' => 'Type',
            'price' => 'Price',
            'fromdate' => 'Fromdate',
            'todate' => 'Todate',
            'status' => 'Status',
            'maxamt' => 'Maxamt',
            'minorderamt' => 'Minorderamt',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
        ];
    }
	public function validateDates(){
    if(strtotime($this->todate) <= strtotime($this->fromdate)){
        $this->addError('start_date','Please give correct Start and End dates');
        $this->addError('end_date','Please give correct Start and End dates');
    }
}
}
