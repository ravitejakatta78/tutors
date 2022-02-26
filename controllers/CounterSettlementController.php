<?php

namespace app\controllers;
use app\models\CounterSettlement;
use yii;
use yii\web\Response;

class CounterSettlementController extends GoController
{
    /**
     * @var string $merchantId
     */
    public $merchantId;

    /**
     * CounterSettlementController constructor.
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->merchantId = Yii::$app->user->identity->merchant_id;
    }

    /**
     * @return string
     * @throws yii\db\Exception
     */
    public function actionIndex()
    {
        $sdate = isset($_POST['sdate']) ? $_POST['sdate'] : date('Y-m-d');
        $edate = isset($_POST['edate']) ? $_POST['edate'] : date('Y-m-d');

        $sql = "select * from (select sum(o.totalamount) totalamount,o.closed_by,date(o.reg_date) orders_date,s.name from orders o
	    inner join serviceboy s on s.ID = o.closed_by
	    where date(o.reg_date) between '".$sdate."' and '".$edate."' and o.paymenttype = 1
	    and  o.closed_by is not null  and o.orderprocess = '4' and o.merchant_id = '".Yii::$app->user->identity->merchant_id."'
	    group by o.closed_by,date(o.reg_date),s.name ) A left join counter_settlement cs on cs.pilot_id = A.closed_by";
        $res = Yii::$app->db->createCommand($sql)->queryAll();

        return $this->render('counter-settlement',['res'=>$res,'sdate'=>$sdate,'edate'=>$edate]);
    }

    /**
     * @throws yii\db\Exception
     */
    public function actionAddsettlement()
    {
        $order_date = $_POST['order_date'];
        $closed_by = $_POST['closed_by'];
        $total_amount = $_POST['total_amount'];
        $paid_amount = $_POST['paid_amount'];
        $sql =  "SELECT * FROM counter_settlement WHERE (date(reg_date='".$order_date."') AND (pilot_id='".$closed_by."')";
        $countersettlementdet = Yii::$app->db->createCommand($sql)->queryOne();
        if(!empty($countersettlementdet)){

            $paid_amount = $countersettlementdet['paid_amount']+$paid_amount;
            $pending_amount  = $countersettlementdet['pending_amount'] - $countersettlementdet['paid_amount'];
            $sqlupdate = "update counter_settlement set paid_amount = '".$paid_amount."',pending_amount = '".$pending_amount."' where ID = '".$countersettlementdet['ID']."' ";
            Yii::$app->db->createCommand($sqlupdate)->execute();

        }
        else{
            $model =  new CounterSettlement;
            $model->pilot_id =	$closed_by;
            $model->pending_amount =  $total_amount- $paid_amount;
            $model->order_amount =  $total_amount;
            $model->paid_amount =  $paid_amount;
            $model->reg_date = date('Y-m-d H:i:s');
            $model->created_by = Yii::$app->user->identity->ID;
            if(!$model->save()){
                print_r($model->getErrors());
            }
            echo "1";
        }
    }
}
