<?php
namespace app\components;
use yii;
use yii\base\Component;
use \app\helpers\Utility;
use \app\models\MerchantNotifications;

 date_default_timezone_set("asia/kolkata");

class MerchantComponent extends Component{

    public function init(){
        date_default_timezone_set("asia/kolkata");
        parent::init();
    }
    public function deductstockfrominventory($arr)
    {
        
        $sqlOrderDetail = 'select ingredient_id,ingred_quantity,ingred_units,count as item_order_count,mr.product_id  
        from order_products op 
        left join 
			merchant_recipe mr on op.product_id = mr.product_id
			where order_id = \''.$arr['order_id'].'\'';
			$orderDetail = Yii::$app->db->createCommand($sqlOrderDetail)->queryAll();

			if(count($orderDetail) > 0)
			{
				$sqlStock = 'select * from ingredient_stock_register isr where merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'
				and  created_on = \''.date('Y-m-d').'\'';
				$resStock = Yii::$app->db->createCommand($sqlStock)->queryAll();
				$stockVsingredient = array_column($resStock,'closing_stock','ingredient_id');
				$oldStockOutVsingredient = array_column($resStock,'stock_out','ingredient_id');
				$stockIngredients = array_column($resStock,'ingredient_id');
				
				if(count($resStock) > 0)
				{
					for($i=0;$i<count($orderDetail);$i++)
					{
						if(in_array($orderDetail[$i]['ingredient_id'],$stockIngredients))
						{
							$sqlCuurentIngredientStock = 'select * from ingredient_stock_register where merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'
							and created_on =\''.date('Y-m-d').'\' and ingredient_id = \''.$orderDetail[$i]['ingredient_id'].'\'';
							$resCurrentIngredientStock = Yii::$app->db->createCommand($sqlCuurentIngredientStock)->queryOne();
							$ingredientClosingStock = $resCurrentIngredientStock['closing_stock'];
							if($orderDetail[$i]['ingred_units'] == '1' || $orderDetail[$i]['ingred_units'] == '2'){
								$outIngredQty = $orderDetail[$i]['ingred_quantity'] * 1000;
							}
							else{
								$outIngredQty = $orderDetail[$i]['ingred_quantity'];
							}
							$newStockOut = ($orderDetail[$i]['item_order_count'] * ($outIngredQty)); //stock out in grams
							$newIngredClosingStock = $ingredientClosingStock - $newStockOut;
							if($newIngredClosingStock >= 0){
								$newIngredClosingStock = $newIngredClosingStock;
								$newStockOut = $newStockOut;
							}else{
								$newIngredClosingStock = 0;
								$newStockOut = $ingredientClosingStock;
							}
						$sqlStockUpdate = 'update ingredient_stock_register set stock_out = \''.($resCurrentIngredientStock['stock_out']+$newStockOut).'\'
						,closing_stock = \''.$newIngredClosingStock.'\' where merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'
						and	ingredient_id =\''.$orderDetail[$i]['ingredient_id'].'\' and created_on = \''.date('Y-m-d').'\'';
						$resStockUpdate = Yii::$app->db->createCommand($sqlStockUpdate)->execute();
						
						//Stock Alert Notifications 
						
						$sqlIngredientDet = 'select * from ingredients where ID=\''.$orderDetail[$i]['ingredient_id'].'\'';
						$ingredientDet = Yii::$app->db->createCommand($sqlIngredientDet)->queryOne();
						if($ingredientDet['stock_alert'] > ($newIngredClosingStock/1000)){
						    $message = $ingredientDet['item_name'].' stock is less available stock is '.$newIngredClosingStock. ' on '.date('d-M-Y h:i:s A');
						    $stockNotiArr = ['merchant_id'=>Yii::$app->user->identity->merchant_id, 'message'=>$message,'seen'=>'0'];
						    $stockNotiStatus = $this->addMerchantNotifications($stockNotiArr);
						}
						
						//--Stock Alert Notifications-- 
						
						$sqlPurchase = 'select ipd.ID,purchase_qty_units,purchase_price,used_qty,remaining_qty,ingredient_id 
						from ingredient_purchase ip 
						inner join ingredient_purchase_detail ipd on ip.ID = ipd.purchase_id 
						where merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
						and ipd.ingredient_id = \''.$orderDetail[$i]['ingredient_id'].'\' and ipd.remaining_qty > 0
						order by reg_date';
						$resPurchase = Yii::$app->db->createCommand($sqlPurchase)->queryAll();
						
						$knockOffNewStockOut = $newStockOut;
						for($p=0;$p<count($resPurchase);$p++)
						{
							
							$remaining_qty_start = $resPurchase[$p]['remaining_qty'];	
							
							if($remaining_qty_start >= $knockOffNewStockOut)
							{
								$sqlKnockOff_one = 'update ingredient_purchase_detail set
								used_qty = \''.(($resPurchase[$p]['used_qty']??0)+$knockOffNewStockOut).'\'
								,remaining_qty = \''.($remaining_qty_start - $knockOffNewStockOut).'\' 
								where ID = \''.$resPurchase[$p]['ID'].'\'';
								$resKnockOff_one = Yii::$app->db->createCommand($sqlKnockOff_one)->execute();
								$merchantOrderRecArr = [
								'merchant_id' => Yii::$app->user->identity->merchant_id
								,'order_id'=>$arr['order_id']
								,'product_id'=>$orderDetail[$i]['product_id']
								,'ingredi_id'=>$orderDetail[$i]['ingredient_id']
								,'ingredi_name'=>$resCurrentIngredientStock['ingredient_name']
								,'ingredi_qty'=>$knockOffNewStockOut
								,'ingredi_price'=>($resPurchase[$p]['purchase_price']/$resPurchase[$p]['purchase_qty_units']) * $knockOffNewStockOut
								,'ingredi_detail_id'=>$resPurchase[$p]['ID']
								,'reg_date'=>date('Y-m-d h:i:s')
								];
								$merchantOrderRec = new \app\models\MerchantOrderRecipeCost;
							$merchantOrderRec->attributes = $merchantOrderRecArr;
							$merchantOrderRec->save();
							
							break;								
							}
							else{

								$sqlKnockOff_one = 'update ingredient_purchase_detail set used_qty = \''.(($resPurchase[$p]['used_qty']??0)+$remaining_qty_start).'\'
								,remaining_qty = \''.'0'.'\' where ID = \''.$resPurchase[$p]['ID'].'\'';
								$resKnockOff_one = Yii::$app->db->createCommand($sqlKnockOff_one)->execute();
								$knockOffNewStockOut = $knockOffNewStockOut - $remaining_qty_start;
								$merchantOrderRecArr = [
								'merchant_id' => Yii::$app->user->identity->merchant_id
								,'order_id'=>$arr['order_id'],'product_id'=>$orderDetail[$i]['product_id']
								,'ingredi_id'=>$orderDetail[$i]['ingredient_id']
								,'ingredi_name'=>$resCurrentIngredientStock['ingredient_name']
								,'ingredi_qty'=>$remaining_qty_start
								,'ingredi_price'=>($resPurchase[$p]['purchase_price']/$resPurchase[$p]['purchase_qty_units']) * $remaining_qty_start
								,'ingredi_detail_id'=>$resPurchase[$p]['ID']
								,'reg_date'=>date('Y-m-d h:i:s')
								];
								print_r($merchantOrderRecArr);exit;
								$merchantOrderRec = new \app\models\MerchantOrderRecipeCost;
							$merchantOrderRec->attributes = $merchantOrderRecArr;
							$merchantOrderRec->save();
							}
							
						}
						
						
						}
					}	
				}
			}

    }
    public function addMerchantNotifications($inputArr){
        $model = new MerchantNotifications;
        $model->attributes = $inputArr;
        $model->created_on = date('Y-m-d H:i:s');
        $model->created_by = Yii::$app->user->identity->merchant_id;
        if($model->validate()){
            if($model->save()){
                //echo 'saved';
                Yii::trace('merchant notification saved');
            } else {
                //echo 'not saved';
                Yii::trace('merchant notification not saved');
            }
        }
    }
    public function userCreation($customer_mobile,$customer_name ='',$email='',$dob='',$occupation = ''){
		$userCheckDet = \app\models\Users::find()->where(['mobile' => $customer_mobile])->asArray()->One();
		if(empty($userCheckDet)){
			$modelUser = new \app\models\Users;
			$sqlprevuser = 'select MAX(ID) as id from users';
			$prevuser = Yii::$app->db->createCommand($sqlprevuser)->queryOne();
			$newid = $prevuser['id']+1;
				$modelUser->unique_id = 'FDQ'.sprintf('%06d',$newid);
				$modelUser->name = ucwords($customer_name);
				$modelUser->mobile = trim($customer_mobile);
				if(!empty($email)){
				$modelUser->email =   $email;  
				}
				if(!empty($dob)){
				$modelUser->date_of_birth =   $dob;  
				}
				
				$modelUser->password = password_hash('112233',PASSWORD_DEFAULT); 	
				$modelUser->status = '1';
				$modelUser->referral_code = 'REFFDQ'.$newid;
				$modelUser->reg_date = date('Y-m-d h:i:s');
				if($modelUser->validate()){
				$modelUser->save();	
				}
				else{
				print_r($modelUser->getErrors());exit;	
				}
				
				return $modelUser['ID'];
		}
		else{
			return $userCheckDet['ID'];
		}
	}
	public function send_sms($mobile,$message){
	    $merchantId = Yii::$app->user->identity->merchant_id;
	    $merchant = \app\models\Merchant::findOne($merchantId);
	    echo '<pre>';
	    echo $merchant['allocated_msgs'].'>'.$merchant['used_msgs'];
	    if($merchant['allocated_msgs'] > $merchant['used_msgs']){
	        \app\helpers\Utility::send_sms($mobile,$message);    
	        $sqlUpdate = 'update merchant set used_msgs = \''.($merchant['used_msgs']+1).'\' where ID = \''.$merchantId.'\'';
		    $resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute();   
		    echo $mobile.'-'.$message;
		    
	    } else {
	       // echo 'Message not sent';
	    }
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
			$model->amount = $arr['amount'];
			
					$model->tax = (string)$arr['taxamt'];
					$model->tips = number_format($arr['tipamt'], 2, '.', '');
					$model->subscription = (string)$arr['subscriptionamt'];
					$model->couponamount = (string)$arr['couponamount'];
					$model->totalamount = (string)$arr['totalamt'];
					$model->paid_amount = 0.00;
					$model->pending_amount = $arr['totalamt'];
					$model->coupon = $arr['merchant_coupon'];
					//$model['paymenttype'] = $arr['payment_mode'];
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
						    //$model->preparedate = date('Y-m-d H:i:s');
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
						//$orderTransaction->paymenttype = $arr['payment_mode'];
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

                        if ($arr['table_name'] != 'PARCEL') {
                            $tableUpdate = \app\models\Tablename::findOne($arr['tableid']);
                            $tableUpdate->table_status = '1';
                            $tableUpdate->current_order_id = $model->ID;
                            $tableUpdate->save();
                        }

							$cur_order_id = $model->ID;	
							return $cur_order_id;
					}
					else{
					    echo "<pre>";print_r($model->getErrors());exit;
					}
	}
	public function roomavailblityupdate($room_cat_id,$rooms_allocated,$type,$merchant_id = null )
	{
		$roomRedDet = \app\models\RoomReservations::findOne($room_cat_id);
		if($type == 1){
			$availablity = $roomRedDet['availability'] - $rooms_allocated;
		}
		else{
			$availablity = $roomRedDet['availability'] + $rooms_allocated;
		}
			if($availablity <= 0){
			$availablity = 0;
		}
		$roomRedDet->availability = $availablity;
		
	if($availablity < $roomRedDet['availability_alert']){
		$merchantId = !empty($merchant_id) ? $merchant_id : Yii::$app->user->identity->merchant_id;
		$inputArr = ['merchant_id'=>$merchantId, 'message'=>$roomRedDet['category_name']." is crossed the limit",'seen'=>'0'];
		Yii::$app->merchant->addMerchantNotifications($inputArr);
	}

		$sqlUpdate = 'update room_reservations set availability = \''.$availablity.'\' 
		where ID = \''.$roomRedDet['ID'].'\'';
		$resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute(); 
		
	}
	public function tableslist($val){
	    $sql = 'select tb.*,s.section_name,tb.section_id,m.table_occupy_status from tablename tb 
	    inner join  sections s on s.ID = tb.section_id 
		inner join merchant m on m.ID =  tb.merchant_id
		where tb.status = \'1\' 
		and tb.merchant_id = \''.$val['merchant_id'].'\' ';
	    if(!empty($val['section_id'])){
	        $sql .= ' and s.ID = \''.$val['section_id'].'\' ' ;    
	    }
    
	    $sql .= ' order by tb.ID desc';
	    $res = Yii::$app->db->createCommand($sql)->queryAll();
	    $tablelist = $tablelistarray = [];
	    foreach($res as $res)
	    {
	        $tablelist['ID'] = $res['ID'];
	        $tablelist['name'] = $res['name'];
	        $tablelist['capacity'] = $res['capacity'];
            $tablelist['enckey'] = Utility::encrypt($val['merchant_id'].','.$res['ID']);
            $tablelist['section_name'] = $res['section_name'];
            $tablelist['section_id'] = $res['section_id'];
            $tablelist['table_status'] = $res['table_status'] ?? 0;
            $tablelist['table_status_text'] = ($res['table_status'] == 1) ? 'Occupied' : 'Free';
			$tablelist['table_occupy_status'] = $res['table_occupy_status'] ?? 1  ;
            $tablelistarray[] = $tablelist;
	        
	    }
	    
	    return $tablelistarray;
	}
	public function tablesections($val){
	    $sql = 'select *  from sections s where  s.merchant_id = \''.$val['merchant_id'].'\' order by s.ID desc';
	    $res = Yii::$app->db->createCommand($sql)->queryAll();
	    $tablesection = $tablesectionlistarray = [];
	    foreach($res as $res)
	    {
	        $sqltables = 'select count(*) total_table,sum(case when table_status = 1 then 1 else 0 end) occupied_count
	        ,sum(case when (table_status = 0 or table_status is null)  then 1 else 0 end) available_count
	        from tablename where section_id = \''.$res['ID'].'\'';
	        $restables = Yii::$app->db->createCommand($sqltables)->queryOne();
			$resPilotTable = [];
			if(!empty($val['header_user_id'])) {
				$sqlPilotTable = 'select * from pilot_table where serviceboy_id = \''.$val['header_user_id'].'\'
				and section_id = \''.$res['ID'].'\'';
				$resPilotTable = Yii::$app->db->createCommand($sqlPilotTable)->queryOne();
			}
	        
	        $tablesection['section_id'] = $res['ID'];
            $tablesection['section_name'] = $res['section_name'];
            $tablesection['total_tables'] = $restables['total_table'];
            $tablesection['tables_occupied'] = @$restables['occupied_count'] ?? "0";
            $tablesection['tables_available'] = @$restables['available_count'] ?? "0";
			$tablesection['section_available'] = !empty($resPilotTable) ? true : false;
            $tablesectionlistarray[] = $tablesection;
	        
	    }
	    
	    return $tablesectionlistarray;
	}
	public function getExcelDataAndUpload($param){
		$dataArr = $optionsarray =  array(); 
	            if (in_array($param['file']->extension,array('xls','xlsx'))) {
          $fileType = \PHPExcel_Iofactory::identify ($param['filename']); // the file name automatically determines the type
          $excelReader = \PHPExcel_IOFactory::createReader($fileType);

          $phpexcel = $excelReader->load ($param['filename'])->getsheet ($param['sheet']); // load the file and get the first sheet
          $total_line = $phpexcel->gethighestrow(); // total number of rows
          $total_column = $phpexcel->gethighestcolumn(); // total number of columns
        



			$worksheetTitle     = $phpexcel->getTitle();
			$highestRow         = $phpexcel->getHighestRow();
			$highestColumn      = $phpexcel->getHighestColumn();
			$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
			for ($row = 2; $row <= $highestRow; ++ $row) {
				for ($col = 0; $col < $highestColumnIndex; ++ $col) {
					$cell = $phpexcel->getCellByColumnAndRow($col, $row);
					
						 $val = $cell->getValue() ;
					
					
					if(!empty($val)){
					$dataArr[$row][$col] = $val;
					} 
					$optionname = $phpexcel->getCellByColumnAndRow($col, 1);
					$optionname = $optionname->getValue();
					if(!empty($optionname)){ 
							$optionsarray[$col] = $optionname; 
					}
				}

			}
	  }
	  return array_values($dataArr);
	  
	}
	public function addFoodSections($data)
	{
	    
	    for($i=0;$i<count($data['food_sections']);$i++){
    	    $fs_food_name = Yii::$app->db->createCommand('SELECT * FROM food_sections WHERE food_section_name=:food_section_name AND merchant_id=:merchant_id')
               ->bindValue(':food_section_name', $data['food_sections'][$i])
               ->bindValue(':merchant_id', $data['merchant_id'])
               ->queryOne();
    	    if(empty($fs_food_name))
    	    {
            	$insertdata[] = [$data['merchant_id'],$data['food_sections'][$i],1,date('Y-m-d H:i:s')];
    	    }    
	    }
	    if(!empty($insertdata))
	    {
	    
    	    Yii::$app->db
				->createCommand()
				->batchInsert('food_sections', ['merchant_id','food_section_name','fs_status','reg_date'],$insertdata)
				->execute();    
	    }
	}
	public function addFoodCategeries($data)
	{
	     $addedCategory = [];
	     for($i=0;$i<count($data['foodCategeries']);$i++){
    	    $food_cat_name = Yii::$app->db->createCommand('SELECT * FROM food_categeries WHERE food_category=:food_category	 AND merchant_id=:merchant_id')
               ->bindValue(':food_category', $data['foodCategeries'][$i])
               ->bindValue(':merchant_id', $data['merchant_id'])
               ->queryOne();
    	    if(empty($food_cat_name))
    	    {
    	        $foodSectionName = $data['sectionCat'][$data['foodCategeries'][$i]];
    	        if(!empty($foodSectionName))
    	        {
    	            //echo $data['sectionCat'][$data['foodCategeries'][$i]];
    	            $fs_food_name = Yii::$app->db->createCommand('SELECT * FROM food_sections WHERE food_section_name=:food_section_name AND merchant_id=:merchant_id')
                    ->bindValue(':food_section_name', $data['sectionCat'][$data['foodCategeries'][$i]])
                    ->bindValue(':merchant_id', $data['merchant_id'])
                    ->queryOne();
                    $food_section_id = $fs_food_name['ID'];
    	        }
    	        else{
    	            $food_section_id = 0;
    	        }
    	        //echo "<pre>";print_r($data['catUpselling']);exit;
    	        if(!empty($data['catUpselling'][$data['foodCategeries'][$i]])){
    	            $catupsellstatus = ($data['catUpselling'][$data['foodCategeries'][$i]] == 'Yes') ? '1' : '2'; 
    	        }
    	        else{
    	            $catupsellstatus = '2';
    	        }
    	        if(!in_array($data['foodCategeries'][$i],$addedCategory))
    	        {
    	            $insertdata[] = [$data['merchant_id'],$data['foodCategeries'][$i],$food_section_id,$catupsellstatus,date('Y-m-d H:i:s')];
    	        }
               	$addedCategory[] = $data['foodCategeries'][$i];                 

    	    }
    	   
	    }
	    if(!empty($insertdata))
	    {
	        Yii::$app->db
				->createCommand()
				->batchInsert('food_categeries', ['merchant_id','food_category','food_section_id','upselling','reg_date'],array_values($insertdata))
				->execute();    
	    }
	    return 1;
	}
	public function addFoodUnits($data)
	{
	    $addedUnit = [];
	    $fcIdArray = [];
	    for($i=0;$i<count($data['dataArr']);$i++){
	        $food_cat_name = Yii::$app->db->createCommand('SELECT * FROM food_categeries WHERE food_category=:food_category	 AND merchant_id=:merchant_id')
               ->bindValue(':food_category', $data['dataArr'][$i]['6'])
               ->bindValue(':merchant_id', $data['merchant_id'])
               ->queryOne();
            $fcIdArray[] =    $food_cat_name['ID'];
               if(!empty($food_cat_name)){
                   $food_unit_check =  Yii::$app->db->createCommand('SELECT * FROM food_category_types WHERE food_type_name=:food_type_name	 and food_cat_id=:food_cat_id AND merchant_id=:merchant_id')
                        ->bindValue(':food_type_name', $data['dataArr'][$i]['4'])
                        ->bindValue(':food_cat_id',$food_cat_name['ID'])
                        ->bindValue(':merchant_id', $data['merchant_id'])
                        ->queryOne();
                    if(empty($food_unit_check)){
                        if(!in_array($data['dataArr'][$i]['4']."-".$food_cat_name['ID'],$addedUnit))
    	                {
          	                $insertdata[] = [$food_cat_name['ID'],$data['merchant_id'],$data['dataArr'][$i]['4'],date('Y-m-d H:i:s')];
    	                }
                        $addedUnit[] = $data['dataArr'][$i]['4']."-".$food_cat_name['ID'];                 

                        
                    }    
               }
	    }
	    
	    if(!empty($insertdata))
	    {
	        Yii::$app->db
				->createCommand()
			    ->batchInsert('food_category_types', ['food_cat_id','merchant_id','food_type_name','reg_date'],array_values($insertdata))
		        ->execute();    
	    }
	    return $fcIdArray;
	    
	}
	public function addItems($data){
	    for($i=0;$i<count($data['dataArr']);$i++){
	        $food_unit_check =  Yii::$app->db->createCommand('SELECT * FROM food_category_types WHERE food_type_name=:food_type_name AND food_cat_id=:food_cat_id AND merchant_id=:merchant_id')
                        ->bindValue(':food_type_name', $data['dataArr'][$i]['4'])
                        ->bindValue(':food_cat_id',$data['fcIdArray'][$i])
                        ->bindValue(':merchant_id', $data['merchant_id'])
                        ->queryOne();
	        if(!empty($food_unit_check)){
	            //echo strtoupper($data['dataArr'][$i]['1']).' '.$data['fcIdArray'][$i].' '.$food_unit_check['ID']." ".$data['merchant_id']."<br>";
                $item_check = Yii::$app->db->createCommand('SELECT * FROM product WHERE merchant_id=:merchant_id AND UPPER(title)=:title AND foodtype=:foodtype  
                and food_category_quantity=:food_category_quantity')
                        ->bindValue(':title', trim($data['dataArr'][$i]['1']))
                        ->bindValue(':foodtype',$data['fcIdArray'][$i])
                        ->bindValue(':merchant_id', $data['merchant_id'])
                        ->bindValue(':food_category_quantity',$food_unit_check['ID'])
                        ->queryOne();
                        if(empty($item_check)){
                          $insertdata[] = [$data['merchant_id'],$data['dataArr'][$i]['1'],$data['dataArr'][$i]['2']
                          ,$data['dataArr'][$i]['3'],$food_unit_check['ID'],$data['fcIdArray'][$i],$data['dataArr'][$i]['5'],'1','1',date('Y-m-d H:i:s')];  
                        }
	        }
	    }
	    
	    if(!empty($insertdata))
	    {
	    //echo "<pre>";    print_r($insertdata);exit;
	        Yii::$app->db
				->createCommand()
			    ->batchInsert('product', ['merchant_id','title','unique_id','labeltag','food_category_quantity','foodtype','serveline','status','availabilty','reg_date'],array_values($insertdata))
		        ->execute();   
	    }
	    return 1;    
	}
	public function addTableSection($data){
	    if(!empty($data['dataArr'])){
	      $sections = array_values(array_unique(array_column($data['dataArr'],3)));
	      if(!empty($sections))
	      {
	          for($i=0;$i<count($sections);$i++) {
	         $section_det = Yii::$app->db->createCommand('SELECT * FROM sections WHERE section_name=:section_name	 AND merchant_id=:merchant_id')
               ->bindValue(':section_name', $sections[$i])
               ->bindValue(':merchant_id', $data['merchant_id'])
               ->queryOne();    
	            if(empty($section_det)){
	                       $insertdata[] = [$data['merchant_id'],$sections[$i]];
	            }
	              
	          }
        	    if(!empty($insertdata))
        	    {
        	    //echo "<pre>";    print_r($insertdata);exit;
        	        Yii::$app->db
        				->createCommand()
        			    ->batchInsert('sections', ['merchant_id','section_name'],array_values($insertdata))
        		        ->execute();   
        	    }
	      }
	    }
	
	    return 1;
	}
	public function addSectionPrices($data){
	    if(!empty($data['dataArr'])){
	           for($i=0;$i<count($data['dataArr']);$i++){
	               $itemcode = $data['dataArr'][$i][2];                        
	               $sections = $data['dataArr'][$i][3];
	               $product_det = Yii::$app->db->createCommand('select * from product where unique_id=:unique_id AND merchant_id=:merchant_id')
	               ->bindValue(':unique_id', $itemcode)
	               ->bindValue(':merchant_id', $data['merchant_id'])
	               ->queryOne();
	               if(!empty($product_det)){
	                   $sections_det = Yii::$app->db->createCommand('select * from sections where section_name=:section_name  AND merchant_id=:merchant_id')
	               ->bindValue(':section_name', $sections)
	               ->bindValue(':merchant_id', $data['merchant_id'])
	               ->queryOne();
	                    if(!empty($sections_det)){
	                        $price_sections_det = Yii::$app->db->createCommand('select * from section_item_price_list where section_id=:section_id and item_id=:item_id AND merchant_id=:merchant_id')
	                        ->bindValue(':section_id', $sections_det['ID'])
	                        ->bindValue(':item_id', $product_det['ID'])
	                        ->bindValue(':merchant_id', $data['merchant_id'])
	                        ->queryOne();
	                        if(empty($price_sections_det)){
        	                       $insertdata[] = [$data['merchant_id'],$sections_det['ID'],$product_det['ID'],$data['dataArr'][$i][4],$data['dataArr'][$i][5]
        	                       ,Yii::$app->user->identity->emp_name,date('Y-m-d H:i:s')];
	                        }
	                    }
	               }
	           }
	           
	                   	    if(!empty($insertdata))
        	    {
        	    //echo "<pre>";    print_r($insertdata);exit;
        	        Yii::$app->db
        				->createCommand()
        			    ->batchInsert('section_item_price_list', ['merchant_id','section_id','item_id','section_item_price','section_item_sale_price'
        			    ,'created_by','created_on'],array_values($insertdata))
        		        ->execute();  
        	    }
	    }
	}

	public function applyCoupon($val){
		$minOrderAmt = $val['couponDetails']['minorderamt'];
		$maxOrderAmt = $val['couponDetails']['maxamt'];
		$subTotalAmount = $val['sub_total_amount'];
		$appliedCouponAmount = $val['applied_coupon_amount'];
		$userid = $this->userCreation($val['mobile_number'],$val['username']);
		$sqlOrders = 'select * from orders where date(reg_date) between \''.date('Y-m-d',strtotime($val['couponDetails']['fromdate'])).'\' 
		and \''.date('Y-m-d',strtotime($val['couponDetails']['todate'])).'\' 
		and merchant_id = \''.$val['merchant_id'].'\' and coupon = \''.$val['couponDetails']['code'].'\'
		 and user_id = \''.$userid.'\' ';
		$resOrders = Yii::$app->db->createCommand($sqlOrders)->queryAll(); 

		
		if(!empty($resOrders) && $val['couponDetails']['purpose'] == 'Single'){
			$payload = ['status' => '0', 'message' => 'Coupon Code had alreay used by this user'];
		}
		else if($subTotalAmount < $minOrderAmt) {
			$payload = ['status' => '0', 'message' => 'Order Amount Should More Than '.$minOrderAmt];	
		}
		else if($maxOrderAmt < $appliedCouponAmount) {
			$payload = ['status' => '2', 'message' => 'Coupon Applied Successfully', 'cpnAmt' => $maxOrderAmt];	
		}
		else {
			$payload = ['status' => '1', 'message' => 'Coupon Applied Successfully'];
		}
		return $payload;
	}
}
?>