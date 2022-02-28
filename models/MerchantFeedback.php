<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_feedback".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property string|null $feedback
 * @property int $user_id
 * @property string $reg_date
 */
class MerchantFeedback extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'user_id'], 'required'],
            [['merchant_id', 'user_id'], 'integer'],
            [['feedback'], 'string'],
            [['reg_date'], 'safe'],
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
            'feedback' => 'Feedback',
            'user_id' => 'User ID',
            'reg_date' => 'Reg Date',
        ];
    }
}
