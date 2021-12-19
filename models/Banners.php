<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "banners".
 *
 * @property int $ID
 * @property string $merchant_id
 * @property string $user_id
 * @property string $image
 * @property string $status
 * @property string $reg_date
 * @property string $mod_date
 */
class Banners extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banners';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'image', 'status', 'reg_date'], 'required'],
            [['mod_date'], 'safe'],
            [['merchant_id', 'user_id', 'image', 'status'], 'string', 'max' => 50],
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
            'user_id' => 'User ID',
            'image' => 'Image',
            'status' => 'Status',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
        ];
    }
}
