<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_permissions".
 *
 * @property int $ID
 * @property string $process_name
 * @property string|null $process_action
 * @property int $process_status
 * @property string $reg_date
 */
class MerchantPermissions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_permissions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['process_name', 'process_status', 'reg_date'], 'required'],
            [['process_status'], 'integer'],
            [['reg_date'], 'safe'],
            [['process_name', 'process_action'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'process_name' => 'Process Name',
            'process_action' => 'Process Action',
            'process_status' => 'Process Status',
            'reg_date' => 'Reg Date',
        ];
    }
}
