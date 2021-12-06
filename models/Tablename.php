<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tablename".
 *
 * @property int $ID
 * @property string $merchant_id
 * @property string $name
 * @property int $capacity
 * @property string $status 0=pending,1=active,2-failed
 * @property int|null $table_status
 * @property int|null $current_order_id
 * @property string $reg_date
 * @property string $mod_date
 */
class Tablename extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tablename';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'name', 'capacity', 'status', 'reg_date','section_id'], 'required'],
            [['capacity', 'table_status', 'current_order_id','ID','section_id'], 'integer'],
            [['status'], 'string'],
            [['mod_date'], 'safe'],
            [['merchant_id', 'name'], 'string', 'max' => 50],
            [['reg_date'], 'string', 'max' => 20],
            [['section_name'], 'string', 'max' => 100],
            ['name', 'tablenameinsertunique','on'=>'inserttable','message'=>'Name has already taken'],
            ['name', 'tablenameupdateunique','on'=>'updatetable','message'=>'Name has already taken'],
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
            'name' => 'Name',
            'capacity' => 'Capacity',
            'status' => 'Status',
            'table_status' => 'Table Status',
            'current_order_id' => 'Current Order ID',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
             'section_id' => 'Section ID',
            'section_name' => 'Section Name',
        ];
    }
    public function tablenameinsertunique($attribute,$param)
    {
        $oldtableDet = Tablename::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
        $oldTableNames = array_column($oldtableDet,'name');
        if(in_array($this->$attribute , $oldTableNames)){
            $this->addError($attribute, 'Name has already taken');  
        }
    }
    public function tablenameupdateunique($attribute,$param)
    {
        $oldDet = Tablename::findOne($this->ID);
        if($oldDet[$attribute] != $this->$attribute){
        $oldtableDet = Tablename::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
        $oldTableNames = array_column($oldtableDet,'name');
        if(in_array($this->$attribute , $oldTableNames)){
            $this->addError($attribute, 'Name has already taken');  
        }
        }
    }
}
