<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $ID
 * @property string $merchant_id
 * @property string $unique_id
 * @property string $title
 * @property string $slug
 * @property string $labeltag
 * @property string $serveline
 * @property string $price
 * @property string $saleprice
 * @property string $image
 * @property string $availabilty 0=pending,1=active,2-failed
 * @property int|null $foodtype
 * @property int|null $food_category_quantity
 * @property string $status 0=pending,1=active,2-failed
 * @property int $item_type 1=Veg 2=Non Veg
 * @property int $today_special 1=Yes 2=No
 * @property string $taste_category
 * @property int $taste_range  
 * @property string $reg_date
 * @property string $mod_date
 */
class Product extends \yii\db\ActiveRecord
{
    const ACTIVE_PRODUCT = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'title', 'availabilty', 'status', 'reg_date'], 'required'],
            [['title', 'slug', 'labeltag', 'serveline', 'price', 'saleprice', 'image', 'availabilty', 'status'], 'string'],
            [['foodtype', 'food_category_quantity', 'upselling','item_type','today_special', 'taste_range'], 'integer'],
            [['mod_date'], 'safe'],
            [['merchant_id', 'unique_id', 'taste_category'], 'string', 'max' => 50],
            [['reg_date'], 'string', 'max' => 20],
            [['title','food_category_quantity'], 'productnameinsertunique','on'=>'insertproduct','message'=>'Name has already taken'],
            [['title','food_category_quantity'], 'productnameupdateunique','on'=>'updateproduct','message'=>'Name has already taken'],
            ['unique_id', 'productidinsertunique','on'=>'insertproduct'],
            ['unique_id', 'productidupdateunique','on'=>'updateproduct'],
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
            'unique_id' => 'Unique ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'labeltag' => 'Labeltag',
            'serveline' => 'Serveline',
            'price' => 'Price',
            'saleprice' => 'Saleprice',
            'image' => 'Image',
            'availabilty' => 'Availabilty',
            'foodtype' => 'Foodtype',
            'food_category_quantity' => 'Food Category Quantity',
            'status' => 'Status',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
            'upselling' => 'Upselling',
            'item_type' => 'Item Type',
            'today_special' => 'Today Special',
            'taste_category' => 'Taste Category',
            'taste_range' => 'Taste Range'

        ];
    }

    public function productnameinsertunique($attribute,$param)
    {
        $validateCompleteArray = [];
        $oldProductDet = Product::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
        foreach($oldProductDet as $product){
            $validateArray['newTitle'] = $product['title'] .'-'.$product['food_category_quantity'];
            $validateCompleteArray[] = $validateArray['newTitle'];
        }

        if(in_array($this->$attribute.'-'.$this->food_category_quantity , $validateCompleteArray)){
            $this->addError($attribute, 'Name Combination has already taken');
        }
    }
    public function productnameupdateunique($attribute,$param)
    {
       $validateCompleteArray = [];
       $oldDet = Product::find()
            ->where(['unique_id' => $this->unique_id,'merchant_id' => Yii::$app->user->identity->merchant_id])
            ->asArray()->One();
        if(isset($oldDet) && @$oldDet[$attribute].'-'.@$oldDet['food_category_quantity'] != $this->$attribute.'-'.$this->food_category_quantity) {
            $oldProductDet = Product::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
            foreach($oldProductDet as $product){
                $validateArray['newTitle'] = $product['title'] .'-'.$product['food_category_quantity'];
                $validateCompleteArray[] = $validateArray['newTitle'];
            }
        }

        if(in_array($this->$attribute.'-'.$this->food_category_quantity , $validateCompleteArray)){
            $this->addError($attribute, 'Name Combination has already taken');
        }

    }
    public function productidinsertunique($attribute,$param)
    {
        $validateCompleteArray = [];
        $oldProductDet = Product::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
        $oldProductId = array_column($oldProductDet,'unique_id');



        if(in_array($this->$attribute, $oldProductId)){
            $this->addError($attribute, 'Item Code has already taken');
        }
    }
    public function productidupdateunique($attribute,$param)
    {
        $validateCompleteArray = [];
        $oldDet = Product::find()
            ->where(['title' => $this->title,'merchant_id' => Yii::$app->user->identity->merchant_id,'food_category_quantity' => $this->food_category_quantity])
            ->asArray()->One();
        if(isset($oldDet) && @$oldDet[$attribute] != $this->$attribute) {
            $oldProductDet = Product::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
            $oldProductId = array_column($oldProductDet,'unique_id');
            if(in_array($this->$attribute, $oldProductId)){
                $this->addError($attribute, 'Item Code has already taken');
            }
        }
    }
}
