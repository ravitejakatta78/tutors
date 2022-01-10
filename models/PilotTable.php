<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pilot_table".
 *
 * @property int $ID
 * @property string $merchant_id
 * @property int $table_id
 * @property int $serviceboy_id
 * @property string $created_on
 * @property string $created_by
 * @property string $updated_on
 */
class PilotTable extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pilot_table';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'section_id', 'serviceboy_id', 'created_on', 'created_by'], 'required'],
            [['merchant_id', 'section_id', 'serviceboy_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [[ 'created_by'], 'string', 'max' => 20],
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
            'section_id' => 'Section ID',
            'serviceboy_id' => 'Serviceboy ID',
            'created_on' => 'Created On',
            'created_by' => 'Created By',
            'updated_on' => 'Updated On',
        ];
    }
}
