<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ingredients".
 *
 * @property int $ID
 * @property string $item_name
 * @property string|null $item_type
 * @property float|null $item_price
 * @property string|null $photo
 * @property float|null $stock_alert
 * @property int $status
 * @property string $reg_date
 * @property string|null $modify_date
 * @property string $merchant_id
 */
class Ingredients extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingredients';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_name', 'status', 'reg_date', 'merchant_id'], 'required'],
            [['item_price', 'stock_alert'], 'number'],
            [['photo'], 'string'],
            [['status'], 'integer'],
            [['item_name', 'item_type'], 'string', 'max' => 255],
            [['reg_date', 'modify_date', 'merchant_id'], 'string', 'max' => 50],
			['item_name', 'uniqueitemname', 'on' => 'addingredient'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'item_name' => 'Item Name',
            'item_type' => 'Item Type',
            'item_price' => 'Item Price',
            'photo' => 'Photo',
            'stock_alert' => 'Stock Alert',
            'status' => 'Status',
            'reg_date' => 'Reg Date',
            'modify_date' => 'Modify Date',
            'merchant_id' => 'Merchant ID',
        ];
    }
	 public function uniqueitemname($attribute, $params, $validator){

        $user_id = Yii::$app->user->identity->ID;
        $ingredientDet  =  \app\models\Ingredients::find()->where(['merchant_id'=>$user_id,'item_name'=>$this->$attribute])->One();
        if (!empty($ingredientDet['item_name'])) {
   $this->addError($attribute, 'Item Name Already Added');
        } else {

             
        } 
    }
}
