<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sequence_master".
 *
 * @property int $ID
 * @property string $seq_name
 * @property int $merchant_id
 * @property int $seq_number
 * @property string $reg_date
 */
class SequenceMaster extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sequence_master';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['seq_name', 'merchant_id', 'seq_number', 'reg_date'], 'required'],
            [['merchant_id', 'seq_number'], 'integer'],
            [['reg_date'], 'safe'],
            [['seq_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'seq_name' => 'Seq Name',
            'merchant_id' => 'Merchant ID',
            'seq_number' => 'Seq Number',
            'reg_date' => 'Reg Date',
        ];
    }
}
