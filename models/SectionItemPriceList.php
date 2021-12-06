<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "section_item_price_list".
 *
 * @property int $ID primary key
 * @property int $merchant_id
 * @property int $section_id
 * @property int $item_id
 * @property float $section_item_price
 * @property string $created_by
 * @property string $created_on
 * @property string $updated_by
 * @property string $updated_on
 */
class SectionItemPriceList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'section_item_price_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'section_id', 'item_id', 'section_item_price', 'created_by', 'created_on', 'updated_by', 'updated_on','section_item_sale_price'], 'required'],
            [['merchant_id', 'section_id', 'item_id'], 'integer'],
            [['section_item_price'], 'number'],
            [['created_on', 'updated_on','section_item_sale_price'], 'safe'],
            [['created_by', 'updated_by'], 'string', 'max' => 100],
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
            'item_id' => 'Item ID',
            'section_item_price' => 'Section Item Price',
            'created_by' => 'Created By',
            'created_on' => 'Created On',
            'updated_by' => 'Updated By',
            'updated_on' => 'Updated On',
        ];
    }
}
