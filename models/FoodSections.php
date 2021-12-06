<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "food_sections".
 *
 * @property int $ID
 * @property string $food_section_name
 * @property int $fs_status
 * @property string $reg_date
 */
class FoodSections extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'food_sections';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'food_section_name', 'fs_status', 'reg_date'], 'required'],
            [['ID', 'fs_status', 'merchant_id'], 'integer'],
            [['reg_date'], 'safe'],
            [['food_section_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'merchant_id' => 'Merchant Id',
            'food_section_name' => 'Food Section Name',
            'fs_status' => 'Fs Status',
            'reg_date' => 'Reg Date',
        ];
    }
}
