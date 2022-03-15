<?php

namespace app\models;

use app\helpers\MyConst;
use app\helpers\Utility;
use Yii;

/**
 * This is the model class for table "counter_settlement".
 *
 * @property int $ID
 * @property int $pilot_id
 * @property float $pending_amount
 * @property float $order_amount
 * @property float $paid_amount
 * @property int $status
 * @property int $orderid
 * @property string $reg_date
 * @property int|null $created_by
 */
class CounterSettlement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'counter_settlement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pilot_id', 'pending_amount', 'order_amount', 'paid_amount'], 'required'],
            [['pilot_id', 'created_by', 'status', 'cut_order_id'], 'integer'],
            [['pending_amount', 'order_amount', 'paid_amount'], 'number'],
            [['reg_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'pilot_id' => 'Pilot ID',
            'pending_amount' => 'Pending Amount',
            'order_amount' => 'Order Amount',
            'paid_amount' => 'Paid Amount',
            'status' => 'Status',
            'cut_order_id' => 'Cut Off Order',
            'reg_date' => 'Reg Date',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @param int $pilotId
     * @return array|\yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public function lastPilotSession(int $pilotId)
    {
        $lastMonthDate = Utility::lastDesiredDate(30);

        $sqlLastSession = "select * from counter_settlement where date(reg_date) between '".$lastMonthDate."' and '".date('Y-m-d')."'
        AND pilot_id =  '".$pilotId."' AND status = '".MyConst::_COMPLETED."' ORDER BY reg_date DESC LIMIT 1";
        $resLastSession = Yii::$app->db->createCommand($sqlLastSession)->queryOne();

        return $resLastSession;
    }

    /**
     * @param int $pilotId
     * @param int $orderId
     * @return array
     * @throws \yii\db\Exception
     */
    public function runningPilotSession(int $pilotId,int $orderId)
    {
        $lastMonthDate = Utility::lastDesiredDate(30);

        $sqlRunningSession = "select ID,totalamount from orders where ID > '".$orderId."'  and
        date(reg_date) between '".$lastMonthDate."' and '".date('Y-m-d')."' 
        AND serviceboy_id = '".$pilotId."' and orderprocess = '4'  and closed_by = '".$pilotId."' 
        ORDER BY ID desc";
        $resRunningSession = Yii::$app->db->createCommand($sqlRunningSession)->queryAll();

        $totalSessionArray = array_column($resRunningSession,'totalamount','ID');

        $runningTotalSessionAmount = !empty($totalSessionArray) ? array_sum(array_values($totalSessionArray)) : 0;
        $runningCutOrderId = !empty($totalSessionArray) ? current(array_keys($totalSessionArray)) : 0;

        $runningSession = ['runningTotalSessionAmount' => round($runningTotalSessionAmount,2), 'runningCutOrderId' => $runningCutOrderId];

        return $runningSession;
    }

    public function saveSettlement(array $arr)
    {
        $model = new CounterSettlement;
        $model->attributes = $arr;
        if ($model->save()) {
            return true;
        }
        else {
            return false;
        }
    }
}
