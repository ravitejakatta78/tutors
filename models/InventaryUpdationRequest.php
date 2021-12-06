<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventary_updation_request".
 *
 * @property int $ID
 * @property int|null $merchant_id
 * @property string|null $reg_date
 * @property string|null $created_on
 * @property string|null $created_by
 * @property string|null $unique_id
 * @property string|null $type
 * @property string|null $person_name
 */
class InventaryUpdationRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventary_updation_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id'], 'integer'],
            [['reg_date', 'created_on','unique_id','type','person_name'], 'safe'],
            [['created_by'], 'string', 'max' => 50],
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
            'reg_date' => 'Reg Date',
            'created_on' => 'Created On',
            'created_by' => 'Created By',
            'unique_id' => 'Unique Id',
            'type' => 'Type',
            'person_name' => 'Person Name'
        ];
    }
}
