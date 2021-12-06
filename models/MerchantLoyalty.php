<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_loyalty".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property int $days
 * @property int $time_period
 * @property string $created_on
 * @property string $created_by
 * @property string $repetition_no
 * @property string $title
 * @property string $message
 */
class MerchantLoyalty extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_loyalty';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'days', 'time_period', 'created_by'], 'required'],
            [['merchant_id', 'days', 'time_period'], 'integer'],
            [['created_on', 'repetition_no', 'title', 'message'], 'safe'],
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
            'days' => 'Days',
            'time_period' => 'Time Period',
            'created_on' => 'Created On',
            'created_by' => 'Created By',
            'repetition_no' => 'Repetition No',
            'title' => 'Title',
            'message' => 'Message'
        ];
    }
}
