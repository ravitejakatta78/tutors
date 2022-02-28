<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pilot_factor_rating".
 *
 * @property int $ID
 * @property int $feedback_id
 * @property int $factor_id
 * @property int $rating
 * @property string $reg_date
 */
class PilotFactorRating extends \yii\db\ActiveRecord
{
    const FACTORS = ['1' => 'Time Management', '2' => 'Communication', '3' => 'Knowledge/Food Recommendations'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pilot_factor_rating';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['feedback_id', 'factor_id', 'rating'], 'required'],
            [['feedback_id', 'factor_id', 'rating'], 'integer'],
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
            'feedback_id' => 'Feedback ID',
            'factor_id' => 'Factor ID',
            'rating' => 'Rating',
            'reg_date' => 'Reg Date',
        ];
    }
}
