<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_rejections".
 *
 * @property int $ID
 * @property int $order_id
 * @property int $rejected_by
 * @property string $rejection_reason
 * @property string $created_on
 * @property string $reg_date
 */
class OrderRejections extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_rejections';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'rejected_by', 'rejection_reason', 'reg_date'], 'required'],
            [['order_id', 'rejected_by'], 'integer'],
            [['rejection_reason'], 'string'],
            [['created_on', 'reg_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'order_id' => 'Order ID',
            'rejected_by' => 'Rejected By',
            'rejection_reason' => 'Rejection Reason',
            'created_on' => 'Created On',
            'reg_date' => 'Reg Date',
        ];
    }
}
