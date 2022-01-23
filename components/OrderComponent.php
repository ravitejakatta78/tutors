<?php
namespace app\components;
use yii;
use yii\base\Component;
use app\models\MerchantFoodCategoryTax;
use app\models\Product;
use app\helpers\Utility;
use yii\helpers\ArrayHelper;

date_default_timezone_set("asia/kolkata");

class OrderComponent extends Component{

    public function init(){
        date_default_timezone_set("asia/kolkata");
        parent::init();
    }
	public function saveorder($arr)
	{
		//echo "<pre>";print_r($arr);exit;
		$userid = $arr['user_id'];
		$selectedpilot = $arr['pilotid'];
		$merchantid = (string)Yii::$app->user->identity->merchant_id;
			$model = new \app\models\Orders;
			$model->merchant_id = $merchantid;
			$model->tablename = $arr['tableid'];
			$model->user_id = (string)$userid;
			$model->serviceboy_id  = $selectedpilot ;
			$model->order_id = Utility::order_id($merchantid,'order'); 
			$model->txn_id = Utility::order_id($merchantid,'transaction');
			$model->txn_date = date('Y-m-d H:i:s');
			$model->amount = $arr['amount'] ? number_format($arr['amount'], 2, '.', ',') : 0;
			
					$model->tax = (string)$arr['taxamt'];
					$model->tips = number_format($arr['tipamt'], 2, '.', '');
					$model->subscription = (string)$arr['subscriptionamt'];
					$model->couponamount = (string)$arr['couponamount'];
					$model->totalamount = (string)$arr['totalamt'];
					$model->coupon = $arr['merchant_coupon'];
					$model['paymenttype'] = $arr['payment_mode'];
							$model->orderprocess = '1';
							$model->status = '1';
							$model->paidstatus = '0';
							$model->paymentby = '1';
							$model->ordertype = 2;
							$sqlprevmerchnat = 'select max(orderline) as id from orders where merchant_id = \''.$merchantid.'\' and date(reg_date) =  \''.date('Y-m-d').'\''; 
							$resprevmerchnat = Yii::$app->db->createCommand($sqlprevmerchnat)->queryOne();
							$prevmerchnat = $resprevmerchnat['id']; 
							$newid = $prevmerchnat>0 ? $prevmerchnat+1 : 100;  
							$model->orderline = (string)$newid;
						    $model->reg_date = date('Y-m-d H:i:s');
						    $model->discount_type = $arr['discount_mode'];
						    $model->discount_number = $arr['merchant_discount'] ??  0;
						    $model->preparedate = date('Y-m-d H:i:s');
						  //  echo "<pre>";
						  //  print_r($model);exit;
						  
					if($model->save()){
						
						$orderTransaction = new \app\models\OrderTransactions;
						$orderTransaction->order_id = (string)$model->ID;
						$orderTransaction->user_id = (string)$userid;			
						$orderTransaction->merchant_id = $merchantid;
						$orderTransaction->amount = !empty($arr['amount']) ? number_format(trim($arr['amount']),2, '.', ',') : 0; 
						$orderTransaction->couponamount =   (string)$arr['couponamount']; 
						$orderTransaction->tax =  !empty($arr['taxamt']) ? number_format(trim($arr['taxamt']),2, '.', ',') : 0; 
						$orderTransaction->tips =  !empty($arr['tipamt']) ? number_format(trim($arr['tipamt']),2, '.', ',') : '0'; 
						$orderTransaction->subscription =  !empty($arr['subscriptionamt']) ? number_format(trim($arr['subscriptionamt']),2, '.', ',') : '0'; 
						$orderTransaction->totalamount =   !empty($arr['totalamt']) ? number_format(trim($arr['totalamt']),2, '.', ',') : 0; 
						$orderTransaction->paymenttype = $arr['payment_mode'];
						$orderTransaction->reorder= '0';
						$orderTransaction->paidstatus = '0';
						$orderTransaction->reg_date = date('Y-m-d h:i:s');
						$orderTransaction->save();
						
						
							$productscount = []; $p=0;$r=1;
							foreach($arr['priceind'] as $priceind )
							{
								$productscount[$p]['order_id'] = $model->ID;
											$productscount[$p]['user_id'] = (string)$userid;
											$productscount[$p]['merchant_id'] = $merchantid;
											$productscount[$p]['product_id'] = trim($arr['itemid'][$p]);
											$productscount[$p]['count'] = trim($arr['qtyitem'][$p]);
											$productscount[$p]['price'] = trim($arr['priceind'][$p]);
											$productscount[$p]['inc'] = $r;
											$productscount[$p]['reorder'] = '0';
											$productscount[$p]['reg_date'] = date('Y-m-d h:i:s');
							$p++;$r++;
							}
							Yii::$app->db
							->createCommand()
							->batchInsert('order_products', ['order_id','user_id','merchant_id','product_id', 'count'
							, 'price','inc','reorder','reg_date'],$productscount)
							->execute();

							$tableUpdate = \app\models\Tablename::findOne($arr['tableid']);
										$tableUpdate->table_status = '1';
										$tableUpdate->current_order_id = $model->ID;
										$tableUpdate->save();
							
							$cur_order_id = $model->ID;	
							return $cur_order_id;
					}
					else{
					    echo "<pre>";print_r($model->getErrors());exit;
					}
	}

	public function taxcomputation($arr){
		$calFoodTax = 0;
		$taxamt = 0;
		$resMerchantfoodTax = MerchantFoodCategoryTax::find()
		                        ->select('tax_value,food_category_id')
		                        ->where(['merchant_id'=>$arr['merchant_id']])->asArray()->All();
		$merchantFoodTaxArr = ArrayHelper::index($resMerchantfoodTax, null, 'food_category_id');

		$productDetails = Product::find()
		                    ->select('ID,foodtype')
		                    ->where(['merchant_id'=>$arr['merchant_id'],'status' => (string)Product::ACTIVE_PRODUCT])
							->asArray()->all();
		$prodvsfcidarr = array_column($productDetails,'foodtype','ID');					

		foreach($arr['productIdArr'] as $i => $v){
		    $foodTaxArr = !empty($merchantFoodTaxArr[$prodvsfcidarr[$v]]) ? $merchantFoodTaxArr[$prodvsfcidarr[$v]] : [];
           if(count($foodTaxArr) > 0){
                for($f =0;$f< count($foodTaxArr) ; $f++){
                    $foodTaxValue = (float)($foodTaxArr[$f]['tax_value']);
                    $calFoodTax = $calFoodTax + ($arr['orderCountArr'][$i] * ($foodTaxValue/100));
                }
                $taxamt = round($calFoodTax,2); 
            }
		}
		return $taxamt;
	}
}
?>