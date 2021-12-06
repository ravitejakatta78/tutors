<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_tax".
 *
 * @property int $ID
 * @property string $tax_name
 * @property int $merchant_id
 * @property string $created_on
 * @property string $created_by
 * @property string|null $updated_on
 * @property string|null $updated_by
 */
class MerchantTax extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_tax';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tax_name', 'merchant_id', 'created_by'], 'required'],
            [['merchant_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['tax_name', 'created_by', 'updated_by'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'tax_name' => 'Tax Name',
            'merchant_id' => 'Merchant ID',
            'created_on' => 'Created On',
            'created_by' => 'Created By',
            'updated_on' => 'Updated On',
            'updated_by' => 'Updated By',
        ];
    }
}
