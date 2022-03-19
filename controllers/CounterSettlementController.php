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

        $sql = "select cs.ID, cs.reg_date, s.name, cs.pending_amount, cs.order_amount, cs.status 
        from counter_settlement cs 
        inner join serviceboy s on s.ID = cs.pilot_id 
        where date(cs.reg_date) between '".$sdate."' and '".$edate."' and s.merchant_id = '".$this->merchantId."'  
        order by cs.ID desc";
        $res = Yii::$app->db->createCommand($sql)->queryAll();

        return $this->render('counter-settlement',['res'=>$res,'sdate'=>$sdate,'edate'=>$edate]);
    }

    public function actionConfirmSettlement()
    {
        extract($_POST);
        $model = CounterSettlement::findOne($sessionId);
        $model->status = $settlementStatus;
        $model->save();
    }
}
