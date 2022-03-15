<?php
namespace app\components;

use app\helpers\MyConst;
use app\models\CounterSettlement;
use yii;
use yii\base\Component;

date_default_timezone_set("asia/kolkata");

class CounterSettlementComponent extends Component{

    public function init()
    {
        date_default_timezone_set("asia/kolkata");
        parent::init();
    }

    /**
     * @param array $val
     */
    public function getCurrentSettlementSession(array $val)
    {
        $payload = [];

        $lastPilotSession = CounterSettlement::lastPilotSession($val['usersid']);
        $payload['last_session'] = !empty($lastPilotSession['reg_date']) ? $lastPilotSession['reg_date'] : date('Y-m-d H:i:s A');
        $payload['last_session_amount'] = !empty($lastPilotSession['order_amount']) ? $lastPilotSession['order_amount'] : 0;
        $payload['pending_amount'] = !empty($lastPilotSession['pending_amount']) ? $lastPilotSession['pending_amount'] : 0;

        $last_cut_order_id = !empty($lastPilotSession['cut_order_id']) ? $lastPilotSession['cut_order_id'] : 0;
        $runningSession = CounterSettlement::runningPilotSession($val['usersid'], $last_cut_order_id);
        $payload['cut_order_id'] = $runningSession['runningCutOrderId'];
        $payload['running_total_amount'] = round($runningSession['runningTotalSessionAmount'],2);
        $latestPayload = ['status' => 1
            , 'last_session' =>
                    [
                        'last_session_date' => $payload['last_session'],
                        'last_session_amount' => (string)round($payload['last_session_amount'],2)
                    ],
            'current_session' => [
                'current_session_date' =>date('Y-m-d H:i:s A'),
                'current_session_amount' => (string)round($payload['running_total_amount'],2),

            ],
            'cut_order_id' => $payload['cut_order_id'],
            'pending_amount' => (string)round($payload['pending_amount'],2)
        ];
        return $latestPayload;
    }

    /**
     * @param array $val
     * @return array
     * @throws yii\db\Exception
     */
    public function settlementHistory(array $val)
    {
        $sql = "select *, case 
			when status = '".MyConst::_NEW."' THEN 'New' 
			when status = '".MyConst::_COMPLETED."' THEN 'Completed'
			else 'Rejected' end status_text 
			from counter_settlement where date(reg_date) between '".$val['sdate']."' and '".$val['edate']."'
        and pilot_id = '".$val['header_user_id']."' ";
		if(!empty($val['status'])){
			$sql .= " and status in ('".$val['status']."') ";	
		}
		$sql .= " order by ID desc";
        $res = Yii::$app->db->createCommand($sql)->queryAll();

        return ['status' => '1','settlementHistory' => $res];
    }

    /**
     * @param array $val
     * @return string[]
     */
    public function saveSettlement(array $val)
    {
        $settlementArray = [];
        $settlementArray['merchant_id'] = $val['merchantId'];
        $settlementArray['pilot_id'] = $val['header_user_id'];
        $settlementArray['order_amount'] = $val['total_amount'];
        $settlementArray['pending_amount'] = $val['pending_amount'];
        $settlementArray['paid_amount'] = $val['paid_amount'];
        $settlementArray['status'] = MyConst::_NEW;
        $settlementArray['cut_order_id'] = $val['cut_order_id'];
        $settlementArray['reg_date'] = date('Y-m-d H:i:s');
        $settlementArray['created_by'] = $val['header_user_id'];

        $result = CounterSettlement::saveSettlement($settlementArray);
        if($result) {
            $payload = ['status' => '1', 'message' => 'Added Successfully'];
        }
        else{
            $payload = ['status' => '0', 'message' => 'Error While Adding Details!!'];
        }
        return $payload;
    }
}
?>