<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_reservations".
 *
 * @property int $ID
 * @property string $user_id
 * @property string $merchant_id
 * @property string $table_id
 * @property string $serviceboy_id
 * @property string $bookdate
 * @property string $booktime
 * @property string $status
 * @property string $reg_date
 * @property string $mod_date
 */
class TableReservations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_reservations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'merchant_id', 'table_id', 'bookdate', 'booktime', 'status', 'reg_date'], 'required'],
            [['bookdate', 'booktime', 'status','person_name'], 'string'],
            [['mod_date'], 'safe'],
            [['user_id', 'merchant_id', 'table_id', 'serviceboy_id'], 'string', 'max' => 30],
            [['reg_date'], 'string', 'max' => 20],
            [['number_of_person'], 'integer'],
            [['mobile_number'], 'string', 'max' => 50],


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'user_id' => 'User ID',
            'merchant_id' => 'Merchant ID',
            'table_id' => 'Table ID',
            'serviceboy_id' => 'Serviceboy ID',
            'bookdate' => 'Bookdate',
            'booktime' => 'Booktime',
            'status' => 'Status',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
        ];
    }
}
