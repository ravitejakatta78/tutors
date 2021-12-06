<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cancelled_reasons".
 *
 * @property int $ID
 * @property string $cancel_reason
 */
class CancelledReasons extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cancelled_reasons';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cancel_reason'], 'required'],
            [['cancel_reason'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'cancel_reason' => 'Cancel Reason',
        ];
    }
}
