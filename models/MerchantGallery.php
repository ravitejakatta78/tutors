<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_gallery".
 *
 * @property int $ID
 * @property string $merchant_id
 * @property string $image
 * @property string $reg_date
 * @property string $mod_date
 */
class MerchantGallery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_gallery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'image', 'reg_date'], 'required'],
            [['image'], 'string'],
            [['mod_date'], 'safe'],
            [['merchant_id'], 'string', 'max' => 30],
            [['reg_date'], 'string', 'max' => 20],
			[['status'], 'integer'],
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
            'image' => 'Image',
			'status' => 'Status',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
        ];
    }
}
