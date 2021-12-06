<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sections".
 *
 * @property int $ID
 * @property string $merchant_id
 * @property string $section_id
 * @property string $section_name
 */
class Sections extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sections';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'section_name'], 'required'],
            [['merchant_id'], 'string', 'max' => 50],
            [['section_id', 'section_name'], 'string', 'max' => 100],
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
            'section_name' => 'Section Name',
        ];
    }
}
