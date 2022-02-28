<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_ambiance_feedback".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property int $merchant_feedback_id
 * @property int $ambiance_id
 * @property int $rating
 * @property string $reg_date
 */
class MerchantAmbianceRating extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_ambiance_feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'merchant_feedback_id', 'rating'], 'required'],
            [['merchant_id', 'merchant_feedback_id', 'rating', 'ambiance_id'], 'integer'],
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
            'merchant_feedback_id' => 'Merchant Feedback ID',
            'ambiance_id' => 'Ambiance Id',
            'rating' => 'Rating',
            'reg_date' => 'Reg Date',
        ];
    }
}
