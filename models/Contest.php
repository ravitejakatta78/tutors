<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contest".
 *
 * @property int $ID
 * @property string $contest_id
 * @property string $contest_name
 * @property string $contest_start_date
 * @property string $contest_end_date
 * @property int $contest_persons
 * @property string $created_by
 * @property string $created_on
 */
class Contest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contest';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contest_name', 'contest_start_date', 'contest_end_date', 'contest_persons'], 'required'],
            [['contest_start_date', 'contest_end_date', 'created_on'], 'safe'],
            [['contest_persons'], 'integer'],
            [['contest_id'], 'string', 'max' => 30],
            [['contest_name'], 'string', 'max' => 255],
            [['created_by'], 'string', 'max' => 50],
						['contest_end_date', 'compare', 'compareAttribute' => 'contest_start_date', 'operator' => '>='],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'contest_id' => 'Contest ID',
            'contest_name' => 'Contest Name',
            'contest_start_date' => 'Contest Start Date',
            'contest_end_date' => 'Contest End Date',
            'contest_persons' => 'Contest Persons',
            'created_by' => 'Created By',
            'created_on' => 'Created On',
        ];
    }
	public function validateDates(){
    if(strtotime($this->contest_end_date) <= strtotime($this->contest_start_date)){
        $this->addError('contest_start_date','Please give correct Start and End dates');
        $this->addError('contest_end_date','Please give correct Start and End dates');
    }
}
}
