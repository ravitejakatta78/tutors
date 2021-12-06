<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "storetypes".
 *
 * @property int $ID
 * @property string $storetypename
 * @property int $type_status
 * @property string $reg_date
 */
class Storetypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'storetypes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'type_status', 'reg_date'], 'required'],
			        ['storetypename', 'required', 'message' => 'Merchant Type is required.'],
            [['type_status'], 'integer'],
            [['reg_date'], 'safe'],
            [['storetypename'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'storetypename' => 'Storetypename',
            'type_status' => 'Type Status',
            'reg_date' => 'Reg Date',
        ];
    }
}
