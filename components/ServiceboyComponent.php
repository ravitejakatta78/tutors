<?php
namespace app\components;
use app\models\PilotFactorRating;
use yii;
use yii\base\Component;
use app\helpers\Utility;
use app\models\Product;
use app\models\Merchant;
use app\models\Serviceboy;
use app\models\Users;
use app\models\Orders;
use app\models\FoodCategeries;
use app\models\SectionItemPriceList;
use app\models\MerchantFoodCategoryTax;
use app\models\Tablename;
use app\models\CoinsTransactions;
use app\models\OrderPushPilot;
use app\models\OrderRejections;
use app\models\MerchantNotifications;
use app\models\ServiceboyNotifications;
use yii\helpers\ArrayHelper;

 date_default_timezone_set("asia/kolkata");

class ServiceboyComponent extends Component{

    public function init(){
        date_default_timezone_set("asia/kolkata");
        parent::init();
    }
	public function login($val)
	{
	    	    Yii::debug("====login parameters====".json_encode($val));
			  if(!empty($val['username'])){
			if(!empty($val['password'])){
			
		  $mymailid = $val['username'];
		  $mypassword = $val['password']; 
		  if(filter_var($mymailid, FILTER_VALIDATE_EMAIL)) {
		    $sqlrow = "SELECT * FROM serviceboy WHERE email = '$mymailid'";
		  }else{
			$sqlrow = "SELECT * FROM serviceboy WHERE mobile = '$mymailid'";
		  }
		    $row = Yii::$app->db->createCommand($sqlrow)->queryOne();
			if(!empty($row['ID'])){
			  if(password_verify($mypassword,$row['password'])){
				if($row['status']=='1'){
				if($row['loginaccess']=='0'){

					$merchant_details = Merchant::findOne($row['merchant_id']);
					if(date('Y-m-d') < $merchant_details['subscription_date']){
						$jwt = base64_encode($row['ID']);	 
						$userwherearray['ID'] = $row['ID']; 
						$userarray['loginaccess'] = '1';

						$sqlUpdate = 'update serviceboy set loginaccess = \''.$userarray['loginaccess'].'\',loginstatus=\'1\' where ID = \''.$userwherearray['ID'].'\'';
						$result = Yii::$app->db->createCommand($sqlUpdate)->execute();
						$payload = array("status" => '1',"text" =>"","usersid" => $row['ID'],"merchant_id" => $row['merchant_id']);

					}
					else{
						$payload = array("status" => '0',"text" =>"Merchant Subscription Over");
					}


				} else {
						
					$payload = array("status"=>'0',"text"=>"Account already logged in other device.");
				}
				} else {
						
					$payload = array("status"=>'0',"text"=>"Account not verified.");
				}
			  }  else {
						
					$payload = array("status"=>'0',"text"=>"Invalid Password.");
			  }
			  }  else {
						
					$payload = array("status"=>'0',"text"=>"Invalid Email / Mobile number.");
			  }
		  }else{
						
			$payload = array("status"=>'0',"text"=>"Please enter the password.");
		  }
		  }else{
						
			 	$payload = array("status"=>'0',"text"=>"Please enter the email.");
		  }
		  return $payload;
	}
	public function logout($val)
	{
		if(!empty($val['usersid'])){
		  $usersid =  trim($val['usersid']);  
			$userdetails = Serviceboy::findOne($usersid);
			if(!empty($userdetails['ID'])){  
					$loginarray = 	$loginwherearray = array();
			$loginwherearray['ID'] = $userdetails['ID'];
				$loginarray['loginaccess'] = '0';
				$loginarray['loginstatus'] = '0';
				$loginarray['push_id'] = ''; 
				//$result = updateQuery($loginarray,'serviceboy',$loginwherearray);
				$sqlupdate = 'update serviceboy set loginaccess = \'0\',loginstatus = \'0\',push_id = null where ID = \''.$loginwherearray['ID'].'\' ';
				$result = Yii::$app->db->createCommand($sqlupdate)->execute();
				$payload = array("status"=>'1',"usersid"=>$userdetails['ID'],"text"=>"Status updated");
					  
			}else{
					
				$payload = array("status"=>'0',"text"=>"Invalid user");
			}
		}else{
					
			$payload = array('status'=>'0','message'=>'Invalid Parameters');
		}
		return $payload;
	}
	public function registration($val)
	{
		if(!empty($val['name'])&&!empty($val['email'])){ 
			$userarray = array();	
			$sqlprevmerchnat = "select max(ID) as id from users";
			$resprevmerchnat = Yii::$app->db->createCommand($sqlprevmerchnat)->queryOne();
			$prevmerchnat = $resprevmerchnat['id'];
			$newid = $prevmerchnat+1;
			$userarray['unique_id'] = 'FDGE'.sprintf('%05d',$newid);
			$userarray['otp'] = (string)rand(0000,9999);
			$userarray['name'] = ucwords($val['name']);
			$userarray['email'] = trim($val['email']);
			$userarray['mobile'] = trim($val['mobile']); 	
			$userarray['password'] = password_hash(trim($val['password']),PASSWORD_DEFAULT); 	
			$userarray['status'] = '0';
			$userarray['reg_date'] = date('Y-m-d H:i:s');

			$row = Users::find()->where(['email'=>$userarray['email']])->asArray()->One();  
			if(empty($row['ID'])){
				
				$row = Users::find()
				->where(['mobile'=>$userarray['mobile']])->asArray()->One();
				if(empty($row['ID'])){
					$result = new Users;
					$result->attributes = $userarray;
					

					//$result = insertQuery($userarray,'users');
					if($result->save()){
						$message = "Hi ".$userarray['name']." ".$userarray['otp']." is your otp for verification.";
						Utility::otp_sms($userarray['mobile'],$message);
						
						$userdetails = Users::find()->select('ID')
						->where(['unique_id'=>$userarray['unique_id']])->asArray()->one();
						$payload = array("status"=>'1',"usersid"=>$userdetails['ID'],"text"=>"Account created");	 
			
					}else{
						$payload = array("status"=>'0',"text"=>$result);
					}
				}else{
					$payload = array("status"=>'0',"text"=>"Mobile already exists please try another");
				}
			}else{
				$payload = array("status"=>'0',"text"=>"Email already exists please try another");
			}
		}else{
			$payload = array('status'=>'0','message'=>'Invalid Parameters');
		}
		return $payload;
	}
	public function updation($val)
	{
		if(!empty($val['name'])&&!empty($val['email'])&&!empty($val['mobile'])){ 
			$userarray = $userwherearray = array();
			$serviceboyid = $val['usersid'];
			$userwherearray['ID'] = $serviceboyid;
			$userarray['name'] = trim($val['name']);
			$userarray['email'] =  trim($val['email']);
			$userarray['mobile'] =  trim($val['mobile']);
			
			$sqlrow = "SELECT * FROM serviceboy WHERE email = '".$userarray['email']."' and ID <> '".$serviceboyid."'";
			$row = Yii::$app->db->createCommand($sqlrow)->queryOne();
		if(empty($row['ID'])){
			$sqlrow = "SELECT * FROM serviceboy WHERE mobile = '".$userarray['mobile']."' and ID <> '".$serviceboyid."'";
			$row = Yii::$app->db->createCommand($sqlrow)->queryOne();
				if(empty($row['ID'])){
				$sqlUpdate = 'update serviceboy set name = \''.$userarray['name'].'\',email=\''.$userarray['email'].'\',mobile = \''.$userarray['mobile'].'\' where ID = \''.$userwherearray['ID'].'\'';
				$result = Yii::$app->db->createCommand($sqlUpdate)->execute();
				
				$sqlMerchantEmployeeUpdate = 'update merchant_employee set emp_name = \''.$userarray['name'].'\',emp_email = \''.$userarray['email'].'\'
				emp_phone =  \''.$userarray['mobile'].'\' where merchant_id = \''.$row['merchant_id'].'\' and emp_email = \''.$row['email'].'\' and  emp_phone = \''.$row['mobile'].'\'';
				$resMerchantEmployeeUpdate = Yii::$app->db->createCommand($$sqlMerchantEmployeeUpdate)->execute();
				//$result = updateQuery($userarray,'serviceboy',$userwherearray); 
				
					if($result){  
						$payload = array("status"=>'1',"text"=>"Account has been updated");
					}else{ 
						$payload = array("status"=>'1',"text"=>$result);
					}  
			}else{ 
		$payload = array("status"=>'0',"text"=>"Mobile already exists please try another");
			}
		}else{ 		
			$payload = array("status"=>'0',"text"=>"Email already exists please try another");
		} 
		}else{ 
			$payload = array('status'=>'0','message'=>'Invalid Parameters');
		}
		return $payload;
	}
	public function loginstatus($val)
	{
	    Yii::trace("===service boy login status ======".json_encode($val));
	 if(!empty($val['usersid'])){
		  $usersid =  trim($val['usersid']);
		  $pushid =  trim($val['pushid']);
		  $loginstatus =  trim($val['loginstatus']);
				$userdetails = Serviceboy::findOne($usersid);
			if(!empty($userdetails['ID'])){
			    
					$loginarray = 	$loginwherearray = array();
			$loginwherearray['ID'] = $userdetails['ID'];
				$loginarray['loginstatus'] = $loginstatus;
				$loginarray['push_id'] = '';
				if($loginstatus==1){
						$loginarray['push_id'] = $pushid;
				}
				//$result = updateQuery($loginarray,'serviceboy',$loginwherearray);
				 $sqlUpdate = 'update serviceboy set loginstatus = \''.$loginarray['loginstatus'].'\',push_id = \''.$loginarray['push_id'].'\' where ID = \''.$loginwherearray['ID'].'\'';
				$result = Yii::$app->db->createCommand($sqlUpdate)->execute();
				
				$payload = array("status"=>'1',"usersid"=>$userdetails['ID'],"text"=>"Status updated");
					  
			}else{
					
				$payload = array("status"=>'0',"text"=>"Invalid user");
			}
		}else{
					
			$payload = array('status'=>'0','message'=>'Invalid Parameters');
		}
		return $payload;
	}
	public function forgotpassword($val)
	{
		$username = $val['username']; 
		if(!empty($username)){
			if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
					$sqlrow = "SELECT * FROM serviceboy WHERE email = '".$username."'";
			} else {
					$sqlrow = "SELECT * FROM serviceboy WHERE mobile = '".$username."'";
			}
					$row = Yii::$app->db->createCommand($sqlrow)->queryOne();
			if(!empty($row)){
					$userarray = $userwherearray = array();
					$otp = rand(1111,9999);
					$userarray['otp'] = $otp;
					$userwherearray['ID'] = $row['ID'];
					//$result = updateQuery($userarray,"serviceboy",$userwherearray);
					$sqlUpdate = 'update serviceboy set otp = \''.$userarray['otp'].'\' where ID = \''.$userwherearray['ID'].'\'';
					$result = Yii::$app->db->createCommand($sqlUpdate)->execute();
					if($result){ 
						$emailmessage = '';
						$emailmessage .= $otp.' is your otp for forgot password.';
						$subject = 'Forgot password for FoodQ';
						$email = $row['email'];
						$result = mail($email,$subject,$emailmessage);
						if($result){
							$message = "Hi ".$row['name']." ".$userarray['otp']." is your otp for Forgot password.";
						 Utility::otp_sms($row['mobile'],$message);
						 
						
						$payload = array("status"=>'1',"usersid"=>$row['ID'],"text"=>"OTP Sent successfully");
						}else{
						
							$payload = array("status"=>'0',"text"=>"Please try again");
						} 
					}else{
					
						$payload = array("status"=>'0',"text"=>$result);
					} 
			}else{
					
				$payload = array("status"=>'0',"text"=>"Invalid User details");
			} 
		}else{
				
			$payload = array("status"=>'0',"text"=>"Please enter the password");
		}
		return $payload;
	}
	public function forgotpasswordotp($val)
	{
		if(!empty($val['usersid'])){
		  $usersid =  trim($val['usersid']);
			$userdetails = Serviceboy::findOne($usersid);
			if(!empty($userdetails['ID'])){ 
						$otp = $val['otp'];
						if($userdetails['otp']==$otp){ 
					
							$payload = array("status"=>'1',"usersid"=>$userdetails['ID'],"text"=>"OTP Verified");
						}else{
					
							$payload = array("status"=>'0',"usersid"=>"null","text"=>"Invalid OTP");
						}  
			}else{
					
				$payload = array("status"=>'0',"text"=>"Invalid user");
			}
		}else{
					
			$payload = array('status'=>'0','message'=>'Invalid Parameters');
		}
		return $payload;
	}
	public function changepassword($val)
	{
		 if(!empty($val['usersid'])&&!empty($val['password'])){
		  $customer_id =  trim($val['usersid']);
		  $row = Serviceboy::findOne($customer_id);
		  if(!empty($row['ID'])){ 
		$userarray = $userwherearray = array();
				$userwherearray['ID'] = $row['ID']; 
				$userarray['password'] = password_hash(trim($val['password']), PASSWORD_DEFAULT);
				//$result = updateQuery($userarray,'serviceboy',$userwherearray);
				$sqlUpdate  = 'update serviceboy set password = \''.$userarray['password'].'\' where ID = \''.$userwherearray['ID'].'\'';
				$result = Yii::$app->db->createCommand($sqlUpdate)->execute();
                $sqlUpdate1  = 'update merchant_employee set emp_password = \''.$userarray['password'].'\' where merchant_id =  \''.$row['merchant_id'].'\' 
                and emp_email  = \''.$row['email'].'\'';
				$result = Yii::$app->db->createCommand($sqlUpdate1)->execute();
				if($result){
					
					$payload = array("status"=>'1',"text"=>"Password updated");
					}else{
					
					$payload = array("status"=>'0',"text"=>"Technical issue araised");
				} 
	  }else{
					
			$payload = array("status"=>'0',"text"=>"Invalid users");
	  }
	  }else{
					
			$payload = array("status"=>'0',"text"=>"Invalid parameters");
	  }
		return $payload;
	}
	public function updatepassword($val)
	{
		if(!empty($val['usersid'])&&!empty($val['password'])&&!empty($val['oldpassword'])){
		  $oldpassword =  trim($val['oldpassword']);
		  $customer_id =  trim($val['usersid']);
		  $row = Serviceboy::findOne($customer_id);
		  if(!empty($row['ID'])){
		  if(!empty($row['password'])&&!empty($oldpassword)&&password_verify($oldpassword,$row['password'])){
		$userarray = $userwherearray = array();
				$userwherearray['ID'] = $row['ID']; 
				$userarray['password'] = password_hash(trim($_REQUEST['password']), PASSWORD_DEFAULT);
				//$result = updateQuery($userarray,'serviceboy',$userwherearray);
				$sqlUpdate  = 'update serviceboy set password = \''.$userarray['password'].'\' where ID = \''.$userwherearray['ID'].'\'';
				$result = Yii::$app->db->createCommand($sqlUpdate)->execute();
				
				$sqlUpdate1  = 'update merchant_employee set emp_password = \''.$userarray['password'].'\' where merchant_id =  \''.$row['merchant_id'].'\' 
                and emp_email  = \''.$row['email'].'\'';
				$result = Yii::$app->db->createCommand($sqlUpdate1)->execute();

				if($result){
					
					$payload = array("status"=>'1',"text"=>"Password updated");
					}else{
					
					$payload = array("status"=>'0',"text"=>"Technical issue araised");
				}
	  }else{
					
			$payload = array("status"=>'0',"text"=>"Invalid Old password");
	  }
	  }else{
					
			$payload = array("status"=>'0',"text"=>"Invalid users");
	  }
	  }else{
					
			$payload = array("status"=>'0',"text"=>"Invalid parameters");
	  }
		return $payload;
	}
	public function checkloginstatus($val)
	{
		if(!empty($val['usersid'])){ 
				$userwherearray = $userarray = array();
				$usersid = $val['usersid'];
				$loginstatus = Serviceboy::findOne($usersid);
				if(!empty($loginstatus['ID'])){ 
					$payload = array("status"=>'1',"loginstatus"=>$loginstatus['loginstatus'],'push_id' => $loginstatus['push_id']);
				}else{ 
					$payload = array("status"=>'0',"text"=>"Invalid user");
				} 
			}else{ 
				$payload = array("status"=>'0',"text"=>"Invalid user");
			}
		return $payload;
	}
	public function serviceboys($val)
	{
			if(!empty($val['usersid'])){ 
				$date = date("Y-m-d");

			  $row = Serviceboy::find()->where(['ID'=>$val['usersid'],'status' => '1'])->asArray()->One();

			  $merchant = Merchant::find()->where(['ID'=>$row['merchant_id'],'status' => '1'])->asArray()->One();
			 

			  $sqltotalorders = "SELECT count(*) as count FROM orders 
                WHERE merchant_id = '".$row['merchant_id']."' 
			  and serviceboy_id = '".$row['ID']."' and closed_by = '".$row['ID']."'";
			  $restotalorders = Yii::$app->db->createCommand($sqltotalorders)->queryOne();
			  $totalorders = $restotalorders['count'];
			  $sqltodayorders = "SELECT sum(case when (orderprocess != '4' and orderprocess != '3') then 1 else 0 end) rununing_orders
			  ,sum(case when orderprocess = '4' then 1 else 0 end) completed_orders,
			  count(*) as count,sum(case when  (paymenttype = '1' 
			  and paidstatus = '1' and orderprocess != '3'
			  and closed_by = '".$row['ID']."'
			  ) then totalamount else 0 end) OfflinePay
			  ,sum( case when (paymenttype = '2' and paidstatus = '1' ) then totalamount else 0 end) OnlinePay
			  ,sum( case when (paymenttype = '3' or paymenttype = '4' and paidstatus = '1') then totalamount else 0 end) CounterPay
			  ,sum(case when (orderprocess != '4' and orderprocess != '3' and paidstatus != '1' and  paidstatus != '2') then totalamount else 0 end) rununing_amount
			  ,\"0\" tips
			  ,sum(case when order_performance = 1 then 1 else 0 end) on_time
			  ,sum(case when order_performance = 2 then 1 else 0 end) near_end_time
			  ,sum(case when order_performance = 3 then 1 else 0 end) extra_time
			  ,sum(case when order_performance = 4 then 1 else 0 end) late  
			  FROM orders WHERE merchant_id = '".$row['merchant_id']."' and serviceboy_id = '".$row['ID']."' 
			  and reg_date>='".$date." 00:00:00' and reg_date<='".$date." 23:59:59'";
			  $restodayorders = Yii::$app->db->createCommand($sqltodayorders)->queryOne();



			  $todayorders = $restodayorders['count'];
			  $sqltotalamount = "SELECT sum(totalamount) as amount FROM orders WHERE merchant_id = '".$row['merchant_id']."' 
			  and serviceboy_id = '".$row['ID']."' and closed_by = '".$row['ID']."'";
			  $restotalamount = Yii::$app->db->createCommand($sqltotalamount)->queryOne();
			  $totalamount = $restotalamount['amount'];
			  $totalpoints = (string)ceil($totalamount/100);
			  if(!empty($row['ID'])){
				  $customerdetails = array();
				  $customerdetails['id'] =  $row['ID'];
				  $customerdetails['unique_id'] =  $row['unique_id'];
				  $customerdetails['name'] =  $row['name'];
				  $customerdetails['email'] =  $row['email'];
				  $customerdetails['mobile'] =  $row['mobile'];
				  $customerdetails['profilepic'] =  Utility::serviceboy_image($row['ID']);
				  $customerdetails['storename'] =  $merchant['storename'] ?: '';
				  $customerdetails['storestate'] =  $merchant['state'] ?: '';
				  $customerdetails['city'] =  $merchant['city'] ?: '';
				  $customerdetails['address'] =  $merchant['address'] ?: '';
				  $customerdetails['location'] =  $merchant['location'] ?: '';
				  $customerdetails['rununing_orders'] =  $restodayorders['rununing_orders'] ?: '0';
				  $customerdetails['completed_orders'] =  $restodayorders['completed_orders'] ?: '0';
				  $customerdetails['OnlinePay'] =  $restodayorders['OnlinePay'] ?: '0';
				  $customerdetails['OfflinePay'] =  $restodayorders['OfflinePay'] ?: '0';
				  $customerdetails['tips'] =  $restodayorders['tips'] ?: '0';

				  $customerdetails['todayorders'] =  $todayorders ?: '0';
				  $customerdetails['totalpoints'] =  $totalpoints ?: '0';
				  $customerdetails['loginstatus'] =  $row['loginstatus'];
				  $customerdetails['billed_amount'] =  '0';
				  $customerdetails['running_amount'] =  $restodayorders['rununing_amount'] ?: '0';
				  $customerdetails['payable_amount'] =  $restodayorders['OfflinePay'] ?: '0';
				  $customerdetails['inpocket_amount'] =  $restodayorders['OfflinePay'] ?: '0';
				  $customerdetails['CounterPay'] = $restodayorders['CounterPay'] ?: '0';
				  $customerdetails['store_open_time'] =  $merchant['open_time'];
				  $customerdetails['store_close_time'] =  $merchant['close_time'];

				  $singlePerformance = $overAllPerformance = [];
                  $performanceArray = ['1' => $restodayorders['on_time'],'2' => $restodayorders['near_end_time'],'3' => $restodayorders['extra_time'], '4' => $restodayorders['late']];
                  foreach (Orders::ORDER_PERFORMACE as $key => $value) {
                      $singlePerformance['name'] =     $value;
                      $singlePerformance['order_count'] = (int) $performanceArray[$key] ?? 0;
                      $overAllPerformance[] = $singlePerformance;
				  }
				
					$payload = array("status"=>'1',"users"=>$customerdetails
                    ,'ratingDetails' => $this->pilotFeedback(['header_user_id' => $row['ID']])
                    ,'performance' => $overAllPerformance
                    );
				  }  else {
						
						$payload = array("status"=>'0',"text"=>"Invalid users");
				  }
			  }else{
					$payload = array("status"=>'0',"text"=>"Invalid users id");
			  }
		return $payload;
	}
	public function servicenotificationslist($val)
	{
		$serviceboydetails = Serviceboy::findOne($val['header_user_id']);
		$date = date('Y-m-d');  
		$sqlnotifications = "select * from serviceboy_notifications 
					where merchant_id = '".$serviceboydetails['merchant_id']."' 
					and serviceboy_id = '".$serviceboydetails['ID']."' 
					and seen = '0' and reg_date >= '".$date." 00:00:00' 
					and reg_date <= '".$date." 23:59:59'";
		$notifications = Yii::$app->db->createCommand($sqlnotifications)->queryAll();
					
		$countnotifications = !empty($notifications) ? count($notifications) : 0;
		if(!empty($notifications)){
			$orderarray = $totalordersarray = array();
			foreach($notifications as $orderlist){
				$totalproductaarray = array();
				$orderarray['id'] =  $orderlist['ID'];
				$orderarray['order_id'] =  $orderlist['order_id'];
				$orderarray['title'] =  $orderlist['title']; 
				$orderarray['message'] =  $orderlist['message']; 
				$orderarray['seen'] =  $orderlist['seen'];  
				$orderarray['regdate'] =  date('d M Y H:i:s',strtotime($orderlist['reg_date'])); 
				$totalordersarray[] = $orderarray;
			}
			$payload = array('status'=>'1','count'=>$countnotifications,'notifications'=>$totalordersarray);  
		}else{
			$payload = array('status'=>'0','message'=>'Notification not found!!');
		}
		return $payload;
	}
	public function seenstatus($val){
		$serviceboydetails = Serviceboy::findOne($val['header_user_id']);
		if(!empty($serviceboydetails['ID'])&&!empty($serviceboydetails['merchant_id'])){
					$sqlresult1 =	"update serviceboy_notifications set seen = '1' where serviceboy_id = '".$serviceboydetails['ID']."'"; 
					$result = Yii::$app->db->createCommand($sqlresult1)->execute();
					$payload = array('status'=>'1','message'=>'Data Updated');
		 }else{
				$payload = array('status'=>'0','message'=>'Service boy not found.');
		 }
		 return $payload;
	}
	public function neworders($val){
		$serviceboydetails = Serviceboy::findOne($val['header_user_id']);
	$date = date('Y-m-d');
			$sqlTableId = 'select tb.ID from pilot_table pt 
			inner join sections s on pt.section_id = s.ID
			inner join tablename tb on tb.section_id = s.ID
			where pt.merchant_id = \''.$serviceboydetails['merchant_id'].'\' and pt.serviceboy_id = \''.$val['header_user_id'].'\' and tb.status = \'1\'';
			$resTableId = Yii::$app->db->createCommand($sqlTableId)->queryAll();

					$tableId = ArrayHelper::getColumn($resTableId,  'ID');
					$tableIdString = implode("','",$tableId);
					$sqlorderlistarray = 'select * from orders o where merchant_id = \''.$serviceboydetails['merchant_id'].'\' 
					and date(reg_date) = \''.$date.'\'   and orderprocess = \'0\' 
					and o.tablename in (\''.$tableIdString.'\')
					AND NOT EXISTS (SELECT * FROM order_rejections ore
                    WHERE ore.order_id = o.ID and ore.rejected_by = \''.$val['header_user_id'].'\')' ;
					$orderlistarray = Yii::$app->db->createCommand($sqlorderlistarray)->queryAll();
					if(!empty($orderlistarray)){
					$orderarray = $totalordersarray = array();
					foreach($orderlistarray as $orderlist){
						$totalproductaarray = array();
						$tableDetails = Tablename::findOne($orderlist['tablename']);
						$orderarray['order_id'] =  $orderlist['ID'];
						$orderarray['unique_id'] =  $orderlist['order_id']; 
						$orderarray['username'] = Utility::user_details($orderlist['user_id'],"name");
						$orderarray['storename'] = Utility::merchant_details($orderlist['merchant_id'],"storename");
						$orderarray['tablename'] = $tableDetails['name']; 
						$orderarray['section_name'] = $tableDetails->section['section_name']; 
						$orderarray['amount'] =  $orderlist['amount'];
						$orderarray['totalamount'] =  $orderlist['totalamount'];
						$orderarray['paymenttype'] =  $orderlist['paymenttype']=='cash' ? 'Cash' : 'Online';
						$orderarray['orderprocess'] =  $orderlist['orderprocess']; 
						$orderarray['paidstatus'] =  $orderlist['paidstatus']; 
						$orderarray['orderline'] =  $orderlist['orderline'];
						$orderarray['reorder'] =  $orderlist['reorderprocess'];
						$orderarray['instructions'] = $orderlist['instructions'];
						$orderarray['tax'] = $orderlist['tax'];
						$orderarray['tips'] = $orderlist['tips'];
						$orderarray['discount_number'] = $orderlist['discount_number'];
						$orderarray['couponamount'] = $orderlist['couponamount'];
						$orderarray['coupon'] = $orderlist['coupon'];
                        $orderarray['ordertypetext'] =  Utility::orderTypeText($orderlist['ordertype']);
						$orderarray['userprofilepic'] = !empty($orderlist['user_id']) ? Utility::user_image($orderlist['user_id']) : '';

						$sqlpendingamount = "select sum(totalamount) as pendingamount from order_transactions where order_id = '".$orderlist['ID']."' 
						and merchant_id = '".$orderlist['merchant_id']."' and user_id = '".$orderlist['user_id']."' 
						and paymenttype = 'cash' and paidstatus = '0'";
						$pendingamount = Yii::$app->db->createCommand($sqlpendingamount)->queryOne();
						$orderarray['pendingamount'] =  $pendingamount['pendingamount']; 
						$sqlorderproducts = "select * from order_products where order_id = '".$orderlist['ID']."' and merchant_id = '".$orderlist['merchant_id']."'
						 order by inc asc";
						$orderproducts = Yii::$app->db->createCommand($sqlorderproducts)->queryAll();
						if(count($orderproducts) >0){
						foreach($orderproducts as $orderproduct){
							$productaarray = array();
							$foodCategoryQuantity = Utility::product_details($orderproduct['product_id'],'food_category_quantity');
							if(!empty($foodCategoryQuantity)){
							   $foodCategoryQuantityName =  Utility::foodcategory_type($foodCategoryQuantity);
							    
							}
						$productaarray['order'] = $orderproduct['inc'];
						$productaarray['name'] = Utility::product_details($orderproduct['product_id'],'title');
						$productaarray['count'] = $orderproduct['count'];
						$productaarray['price'] = $orderproduct['price'];
						$productaarray['reorder'] = $orderproduct['reorder']; 
						$productaarray['foodqtyname'] = $foodCategoryQuantityName ?? '';
						$totalproductaarray[] = $productaarray;
							}
						}
						$orderarray['products'] =  array_filter($totalproductaarray);
						$orderarray['regdate'] =  date('h:i A',strtotime($orderlist['reg_date']) ); 
						$orderarray['reg_date'] =  date('Y-m-d h:i:s A',strtotime($orderlist['reg_date']) ); 

					$totalordersarray[] = $orderarray;
					}
				 	$payload = array('status'=>'1','orders'=>$totalordersarray);  
					}else{
					$payload = array('status'=>'1','message'=>'Order not found!!','orders' => []);
					}	
		return $payload;
	}
	public function orderswithstatus($val)
	{
		$serviceboydetails = Serviceboy::findOne($val['header_user_id']);
		$date = date('Y-m-d');
					$notpaidorderlistarray = $paidorderlistarray = $orderlistarray = array();
					$sqlnotpaidorderlistarray = 'select * from orders where merchant_id = \''.$serviceboydetails['merchant_id'].'\'  ';
					if(!empty($val['orderprocess'])){
		                $orderproces_string =  str_replace(",","','",$val['orderprocess']);
					    $sqlnotpaidorderlistarray .= ' and orderprocess   IN (\''.$orderproces_string.'\') ';
	                }
					$sqlnotpaidorderlistarray .= ' and serviceboy_id = \''.$serviceboydetails['ID'].'\'  ';
					if(!empty($val['sdate'])){
				    	$sqlnotpaidorderlistarray .= '    and reg_date >= \''.$val['sdate'].'\' and reg_date <= \''.$val['edate'].'\'  '; 
					}
					$sqlnotpaidorderlistarray .= ' order by ID desc';
					$notpaidorderlistarray = Yii::$app->db->createCommand($sqlnotpaidorderlistarray)->queryAll();
					
					$sql_history_array = 'select 
					sum(case when (orderprocess = \'4\') then 1 else 0 end) completed_orders
					,sum(case when (orderprocess in (\'1\',\'2\')) then 1 else 0 end) running_orders
					,sum(case when (orderprocess = \'4\') then totalamount else 0 end) completed_amount
					,sum(case when (orderprocess in (\'1\',\'2\')) then totalamount else 0 end) running_amount
					from orders where merchant_id = \''.$serviceboydetails['merchant_id'].'\' '; 
					if(!empty($val['sdate'])){
				    	$sql_history_array .= '    and date(reg_date) between \''.$val['sdate'].'\' and \''.$val['edate'].'\'  '; 
					}
					$sql_history_array .= ' and orderprocess IN (\'1\',\'2\',\'4\')  
					and serviceboy_id = \''.$serviceboydetails['ID'].'\' order by ID desc';
					$order_history_array = Yii::$app->db->createCommand($sql_history_array)->queryOne();
					
					$orderlistarray  = $notpaidorderlistarray ;//array_merge($notpaidorderlistarray,$paidorderlistarray);
					$totalordersarray = array();

					if(!empty($orderlistarray)){
						$orderarray = [];
					foreach($orderlistarray as $orderlist){
						$totalproductaarray = array();
						$tableDetails = Tablename::findOne($orderlist['tablename']);
						$feedbackDet = \app\models\Feedback::find()->where(['order_id'=>$orderlist['order_id']])->asArray()->one();
						$orderarray['order_id'] =  $orderlist['ID'];
						$orderarray['unique_id'] =  $orderlist['order_id']; 
						$orderarray['username'] = Utility::user_details($orderlist['user_id'],"name");
						$orderarray['storename'] = Utility::merchant_details($orderlist['merchant_id'],"storename");
						$orderarray['tablename'] = $tableDetails['name'];
						$orderarray['section_name'] = $tableDetails->section['section_name']; 
						$orderarray['amount'] =  $orderlist['amount']; 
						$orderarray['tax'] = (string)(!empty($orderlist['tax']) ?  $orderlist['tax'] : 0);
						$orderarray['tips'] = (string)(!empty($orderlist['tips']) ?  $orderlist['tips'] : 0);
						$orderarray['subscription'] = (string)(!empty($orderlist['subscription']) ?   $orderlist['subscription'] : 0);
						$orderarray['couponamount'] = (string)(!empty($orderlist['couponamount']) ?  $orderlist['couponamount'] : 0);
						$orderarray['totalamount'] =  (string)(!empty($orderlist['totalamount']) ?   $orderlist['totalamount'] : 0);
						$orderarray['paymenttype'] =  $orderlist['paymenttype'];
						$orderarray['orderprocess'] =  $orderlist['orderprocess']; 
						$orderarray['paidstatus'] =  $orderlist['paidstatus']; 
						$orderarray['orderline'] =  $orderlist['orderline']; 
						$orderarray['reorder'] =  $orderlist['reorderprocess']; 
						$orderarray['preparetime'] = $orderlist['preparetime']; 
						$orderarray['updatedtime'] = $orderlist['mod_date'];
						$orderarray['preparedate'] = $orderlist['preparedate'];
						$orderarray['merchant_id'] = $orderlist['merchant_id'];
						
						$orderarray['instructions'] = $orderlist['instructions'];
                        $orderarray['ordertypetext'] =  Utility::orderTypeText($orderlist['ordertype']);

                        $orderarray['tax'] = $orderlist['tax'];
						$orderarray['discount_number'] = $orderlist['discount_number'];
						$orderarray['userprofilepic'] = !empty($orderlist['user_id']) ? Utility::user_image($orderlist['user_id']) : '';
						$sqlpendingamount = "select sum(totalamount) as pendingamount from order_transactions where order_id = '".$orderlist['ID']."' 
						and merchant_id = '".$orderlist['merchant_id']."' and user_id = '".$orderlist['user_id']."' and paymenttype = 'cash'
						and paidstatus = '0'";
						$pendingamount = Yii::$app->db->createCommand($sqlpendingamount)->queryOne();
						$orderarray['pendingamount'] =  (string)($pendingamount['pendingamount'] ?: 0); 
						if($orderarray['pendingamount']>0){
						$orderarray['paidstatus'] =  '0'; 
						}
						$sqlorderproducts = "select * from order_products where order_id = '".$orderlist['ID']."'
						and merchant_id = '".$orderlist['merchant_id']."'  order by inc desc";
						$orderproducts = Yii::$app->db->createCommand($sqlorderproducts)->queryAll();
						if(count($orderproducts) > 0){
						foreach($orderproducts as $orderproduct){
							$productaarray = array();
						$productDet = Product::findOne($orderproduct['product_id']);
						
						$productaarray['order'] = $orderproduct['inc'];
						
						
						$productaarray['name'] = $productDet['title'];;
						 

						if(!empty(@$productDet['food_category_quantity'])){
						    $productaarray['foodqtyname'] = (string)Utility::foodcategory_type($productDet['food_category_quantity']);
						} else{
						    $productaarray['foodqtyname'] = '';
						}
						$productaarray['count'] = $orderproduct['count'];
						$productaarray['price'] = $orderproduct['price'];
						$productaarray['reorder'] = $orderproduct['reorder']; 
						$productaarray['item_type'] = $productDet['item_type'];
						
						
						$productaarray['productimage'] = !empty($productDet['image']) ? MERCHANT_PRODUCT_URL.$productDet['image'] : '';

						
						$totalproductaarray[] = $productaarray;
							}
						}
						$orderarray['products'] =  array_filter($totalproductaarray);
						$orderarray['reg_date'] =  date('d-M-Y h:i A',strtotime($orderlist['reg_date']) ); 
						$orderarray['regdate'] =  date('h:i A',strtotime($orderlist['reg_date']) ); 
						$orderarray['acceptedtime'] =  date('h:i A',strtotime($orderlist['preparedate']) ); 
						$orderarray['pilot_rating'] = !empty($feedbackDet['pilot_rating']) ? $feedbackDet['pilot_rating'] : '';
						$orderarray['pilot_message'] = !empty($feedbackDet['pilot_message']) ? $feedbackDet['pilot_message'] : '';
						
						$totalordersarray[] = $orderarray;
					}
					}
					$payload = array('status'=>'1','orders'=>$totalordersarray
						,'completed_orders' => ($order_history_array['completed_orders'] ?? '0')
						,'running_orders' => ($order_history_array['running_orders'] ?? '0')
						,'completed_amount' => ($order_history_array['completed_amount'] ?? '0')
						,'running_amount' => ($order_history_array['running_amount'] ?? '0')
					); 

		return $payload;
	}
	
	public function order($val)
	{
		$date = date('Y-m-d');
		$orderid = $val['orderid'];
		
		$sqlorderlist = 'select * from orders where ID = \''.$orderid.'\' order by ID desc';
		$orderlist = Yii::$app->db->createCommand($sqlorderlist)->queryOne();
				
		$merchantdetails = Merchant::findOne($orderlist['merchant_id']);
		
		if(!empty($orderlist)){
			$tableDetails = Tablename::findOne($orderlist['tablename']);	
			$orderarray = $totalordersarray = array(); 
			$totalproductaarray = array();
			$orderarray['order_id'] =  $orderlist['ID'];
			$orderarray['merchant_id'] =  $orderlist['merchant_id'];
			$orderarray['unique_id'] =  $orderlist['order_id']; 
			$orderarray['username'] = Utility::user_details($orderlist['user_id'],"name") ?? '';
			$orderarray['storename'] =  !empty($merchantdetails['storename']) ? $merchantdetails['storename'] : '';
			$orderarray['tablename'] = $tableDetails['name'];
			$orderarray['section_name'] = $tableDetails->section['section_name']; 
			$orderarray['enckey'] = Utility::encrypt($orderlist['merchant_id'].','.$orderlist['tablename']);
			$orderarray['logo'] = !empty($merchantdetails['logo']) ? MERCHANT_LOGO.$merchantdetails['logo'] : '';
			$orderarray['coverpic'] = !empty($merchantdetails['coverpic']) ? MERCHANT_LOGO.$merchantdetails['coverpic'] : '';
			$orderarray['amount'] =  sprintf("%.2f", (!empty($orderlist['amount']) ? $orderlist['amount'] : 0));; 
			$orderarray['tax'] = sprintf("%.2f", (!empty($orderlist['tax']) ?  $orderlist['tax'] : 0));
			$orderarray['tips'] = sprintf("%.2f", (!empty($orderlist['tips']) ?  $orderlist['tips'] : 0));
			$orderarray['subscription'] = sprintf("%.2f", (!empty($orderlist['subscription']) ?   $orderlist['subscription'] : 0));
			$orderarray['couponamount'] = sprintf("%.2f", (!empty($orderlist['couponamount']) ?  $orderlist['couponamount'] : 0));
			$orderarray['totalamount'] =  sprintf("%.2f", (!empty($orderlist['totalamount']) ?   $orderlist['totalamount'] : 0));;
			$orderarray['paymenttype'] =  $orderlist['paymenttype'];
			$orderarray['orderprocess'] =  $orderlist['orderprocess']; 
			$orderarray['orderprocesstext'] =  Utility::orderstatus_details($orderlist['orderprocess']); 
			$orderarray['paidstatus'] =  $orderlist['paidstatus'];
			$orderarray['paidstatustext'] =  Utility::status_details($orderlist['paidstatus']); 
			$orderarray['reorder'] =  $orderlist['reorderprocess'];
			$orderarray['reg_date'] =  date('d-M-Y h:i A',strtotime($orderlist['reg_date']) ); 
			$orderarray['preparedate'] =  $orderlist['preparedate'];
			$orderarray['preparetime'] = $orderlist['preparetime']; 
			$orderarray['orderline'] =  $orderlist['orderline'];
			$orderarray['instructions'] =  $orderlist['instructions'] ?? '';
			$orderarray['discount_number'] =  sprintf("%.2f", (!empty($orderlist['discount_number']) ?   $orderlist['discount_number'] : 0));
            $orderarray['ordertype'] =  $orderlist['ordertype'];
            $orderarray['ordertypetext'] =  Utility::orderTypeText($orderlist['ordertype']);

			$sqlpendingamount = "select sum(totalamount) as pendingamount from order_transactions 
						where order_id = '".$orderlist['ID']."' and merchant_id = '".$orderlist['merchant_id']."' 
						and user_id = '".$orderlist['user_id']."' and paymenttype = 'cash' and paidstatus = '0'";
			$pendingamount = Yii::$app->db->createCommand($sqlpendingamount)->queryOne();
			
			$orderarray['pendingamount'] =  sprintf("%.2f", $pendingamount['pendingamount'] ?: 0.00); 
			if($orderarray['pendingamount']>0){
				$orderarray['paidstatus'] =  '0'; 
			}
			
			$sqlorderproducts = "select * from order_products where order_id = '".$orderlist['ID']."' 
						and merchant_id = '".$orderlist['merchant_id']."'  order by inc desc";
			$orderproducts = Yii::$app->db->createCommand($sqlorderproducts)->queryAll();
						
			if(count($orderproducts) > 0){
				foreach($orderproducts as $orderproduct){
				    $productDetails = Product::findOne($orderproduct['product_id']);
					$productaarray = array();
					$foodCategoryQuantity = $productDetails['food_category_quantity'];
					if(!empty($foodCategoryQuantity)){
						$foodCategoryQuantityName =  Utility::foodcategory_type($foodCategoryQuantity);
					}
					$productaarray['order'] = $orderproduct['inc'];
					$productaarray['name'] = $productDetails['title'];
					$productaarray['count'] = $orderproduct['count'];
					$productaarray['price'] = $orderproduct['price'];
					$productaarray['reorder'] = $orderproduct['reorder']; 
					$productaarray['foodqtyname'] = $foodCategoryQuantityName ?? '';
					$productaarray['item_type'] = $productDetails['item_type'];
					$totalproductaarray[] = $productaarray;
				}
			}
			
			$orderarray['products'] =  array_filter($totalproductaarray);
			 	$payload = array('status'=>'1','orders'=>$orderarray); 
			}else{
				$payload = array('status'=>'0','message'=>'Order not found!!');
			}

		return $payload;
	}
	
	public function acceptorder($val)
	{
		$serviceboydetails = Serviceboy::findOne($val['header_user_id']);
		$date = date('Y-m-d');
					$orderid = $val['orderid'];
					$preparetime = !empty($val['preparetime']) ? $val['preparetime'] : null;

					$orderlist = Orders::findOne($orderid);
					$tableDetails = Tablename::findOne($orderlist['tablename']);
					if(!empty($orderlist)){
						$orderlist['serviceboy_id'] = str_replace('""', NULL, $orderlist['serviceboy_id']);
						if(empty($orderlist['serviceboy_id'])){
						$roderarray = $roderwharray=  array();
						$roderwharray['ID'] = $orderid; 
						
						$roderarray['orderprocess'] = 1;
						
						$roderarray['preparetime'] = $preparetime;
						$roderarray['serviceboy_id'] = $serviceboydetails['ID']; 
						//updateQuery($roderarray,'orders',$roderwharray); 
						$sqlUpdate = 'update orders set orderprocess = \''.$roderarray['orderprocess'].'\'
						,preparetime = \''.$roderarray['preparetime'].'\',serviceboy_id = \''.$roderarray['serviceboy_id'].'\'
						where ID = \''.$roderwharray['ID'].'\'';
						$result = Yii::$app->db->createCommand($sqlUpdate)->execute();
							if(!empty($roderarray['preparetime']) && $roderarray['preparetime'] > 0 && empty($orderlist['preparetime'])) {
								$merchantNotidication = new MerchantNotifications;
								$merchantNotidication->merchant_id = $orderlist['merchant_id'];
								$merchantNotidication->message = 'Order '.$orderlist['order_id'].' on  '.$tableDetails['name'].'-'.$tableDetails->section['section_name'].' is <br> 
								Preparing in '.$roderarray['preparetime']. ' mins';
								$merchantNotidication->seen = '0';
								$merchantNotidication->created_on = date('Y-m-d H:i:s');
								$merchantNotidication->created_by = $val['header_user_id'];
								$merchantNotidication->save();
							}
						$userdetails = Users::findOne($orderlist['user_id']);
						if(!empty($userdetails['push_id'])){	
						$title = 'Your order has been accepted';
						$image = '';
						$message = "Hey ".ucwords($userdetails['name']).", ".$serviceboydetails['name']." has been Accepted your order.";			
						//Utility::sendNewFCM($userdetails['push_id'],$title,$message,$image,null,null,$orderid);	
    					$notificationdet = ['type' => 'ORDER_ACCEPTED','username' => $userdetails['name']];
						Utility::sendNewFCM($userdetails['push_id'],$title,$message,$image,'6',null,$orderdetails['ID'],$notificationdet); 

						}
						
						$sqlserviceboyarray = 'select * from serviceboy where merchant_id = \''.$orderlist['merchant_id'].'\' 
						and loginstatus = \'1\' and push_id is not null and ID != \''.$val['header_user_id'].'\' order by ID desc';
						$serviceboyarray = Yii::$app->db->createCommand($sqlserviceboyarray)->queryAll();
						
								if(!empty($serviceboyarray)){
									$stitle = 'Order Conformation';
									$smessage = $orderlist['order_id'].' is accepted by '.Utility::serviceboy_details($roderarray['serviceboy_id'],'name');
									$simage = '';
									foreach($serviceboyarray as $serviceboy){ 
										Utility::sendNewFCM($serviceboy['push_id'],$stitle,$smessage,$simage,null,null,$orderid); 
									}
								}
								
								$merchantNotidication = new MerchantNotifications;
								$merchantNotidication->merchant_id = $orderlist['merchant_id'];
								$merchantNotidication->message = 'Order '.$orderlist['order_id'].' on  '.$tableDetails['name'].'-'.$tableDetails->section['section_name'].' <br> is Accepted';
								$merchantNotidication->seen = '0';
								$merchantNotidication->created_on = date('Y-m-d H:i:s');
								$merchantNotidication->created_by = $val['header_user_id'];
								$merchantNotidication->save();
						
								$payload = array('status'=>'1','message'=>'Order has been accepted');
						}else{
						$smessage = $orderlist['order_id'].' is accepted by '.Utility::serviceboy_details($orderlist['serviceboy_id'],'name');
						

						
						
						$payload = array('status'=>'1','message'=>$smessage);
						}
					}else{
					$payload = array('status'=>'0','message'=>'Order not found!!');
					}
		return $payload;
	}
	public function serveorder($val){
	    			$orderid = $val['orderid'];
	    			$order_details = Orders::findOne($orderid);
					$tableDetails = Tablename::findOne($order_details['tablename']);
	    			if(!empty($order_details)){
                        //20/100*30
                        $conditionTime = ((20/100) * $order_details['preparation_time']);
                        $conditionStringTime = (strtotime($order_details['preparetime'])) - ($conditionTime*60);
                        $conditionDateTime = date("Y-m-d H:i:s", $conditionStringTime);
                        $currentStringTime = strtotime(date('Y-m-d H:i:s'));

                        //on time order
                        if($currentStringTime < strtotime($order_details['preparetime']) && $currentStringTime <= $conditionStringTime && $order_details['extra_preptime_flag'] == 0){
                            $order_details->order_performance = 1;
                        }
                        else if($currentStringTime < strtotime($order_details['preparetime']) && $order_details['extra_preptime_flag'] == 0 && $currentStringTime > $conditionStringTime){ // close to on time
                            $order_details->order_performance = 2;
                        }
                        else if($currentStringTime <= strtotime($order_details['preparetime']) && $order_details['extra_preptime_flag'] == 1){ // on time but requested extra time
                            $order_details->order_performance = 3;
                        }
                        else if($currentStringTime > strtotime($order_details['preparetime'])){ // on time but requested extra time
                            $order_details->order_performance = 4;
                        }


                        $order_details->orderprocess = '2';
	    			    $order_details->save();
						
						$merchantNotidication = new MerchantNotifications;
						$merchantNotidication->merchant_id = $order_details['merchant_id'];
						$merchantNotidication->message = 'Order '.$order_details['order_id'].' on  '.$tableDetails['name'].'-'.$tableDetails->section['section_name'].' <br> is Served';
						$merchantNotidication->seen = '0';
						$merchantNotidication->created_on = date('Y-m-d H:i:s');
						$merchantNotidication->created_by = $val['header_user_id'];
						$merchantNotidication->save();

								$payload = array('status'=>'1','message'=>'Order Served Successfully');
	    			    
	    			}else{
	    			    $payload = array('status'=>'0','message'=>'Order not found!!');
	    			}
	    			return $payload;
	}
	public function prepareorder($val)
	{
		$orderid = $val['orderid'];
		$preparetime = $val['preparetime'];
        $flag = !empty($val['flag']) ? $val['flag'] : 0 ;
	
		$orderlist = Orders::findOne($orderid);
		$tableDetails = Tablename::findOne($orderlist['tablename']);
		if(!empty($orderlist)){
		    if(!empty($preparetime)){
                $preparetimestamp  =date('Y-m-d H:i',strtotime('+'.$preparetime.' minutes',strtotime(date('Y-m-d H:i:s'))));
		        $sqlUpdate = 'update orders set preparetime = \''.$preparetimestamp.'\'
		        ,preparation_time = \''.$preparetime.'\',extra_preptime_flag = \''.$flag.'\' ,mod_date=\''.date('Y-m-d H:i:s').'\'	where ID = \''.$orderid.'\'';
				$result = Yii::$app->db->createCommand($sqlUpdate)->execute();

				if(!empty(@$orderlist['user_id'])){
				     $userDet = \app\models\Users::findOne($orderlist['user_id']);
				     $title = 'Order';
				     $message = 'Order will be served in '.$preparetime.' minutes';
				     Utility::sendNewFCM($userDet['push_id'],$title,$message,null,null,null,$orderlist['ID']);
				}

						 		$merchantNotidication = new MerchantNotifications;
								$merchantNotidication->merchant_id = $orderlist['merchant_id'];
								$merchantNotidication->message = 'Order '.$orderlist['order_id'].' on  '.$tableDetails['name'].'-'.$tableDetails->section['section_name'].' is <br> 
								Preparing in '.$preparetime. ' mins';
								$merchantNotidication->seen = '0';
								$merchantNotidication->created_on = date('Y-m-d H:i:s');
								$merchantNotidication->created_by = $val['header_user_id'];
								$merchantNotidication->save();
								$payload = array('status'=>'1','message'=>'Preparation time updated successfully');
					    
					        
					    }
					    else{
					        $payload = array('status'=>'0','message'=>'Please provide order preparation time');
					    }
	                }else{
					$payload = array('status'=>'0','message'=>'Order not found!!');
					}
	    return $payload;
	}
    public function preparecompleteorder($val)
	{
		$serviceboydetails = Serviceboy::findOne($val['header_user_id']);
		$date = date('Y-m-d');
					$orderid = $val['orderid'];
					$preparedate = $val['preparedate'];
					$orderlist = Orders::findOne($orderid);
					$tableDetails = Tablename::findOne($orderlist['tablename']);
					if(!empty($orderlist)){
					    if(!empty($preparedate)){

					     $sqlUpdate = 'update orders set preparedate = \''.$preparedate.'\'	where ID = \''.$orderid.'\'';
						 $result = Yii::$app->db->createCommand($sqlUpdate)->execute();
						 
						 $merchantNotidication = new MerchantNotifications;
						 $merchantNotidication->merchant_id = $orderlist['merchant_id'];
						 $merchantNotidication->message = 'Order '.$orderlist['order_id'].' on  '.$tableDetails['name'].'-'.$tableDetails->section['section_name'].' is <br> 
						 Prepared';
						 $merchantNotidication->seen = '0';
						 $merchantNotidication->created_on = date('Y-m-d H:i:s');
						 $merchantNotidication->created_by = $val['header_user_id'];
						 $merchantNotidication->save();

						$payload = array('status'=>'1','message'=>'Preparation date updated successfully');
					    
					        
					    }
					    else{
					        $payload = array('status'=>'0','message'=>'Please provide order preparation date');
					    }
	                }else{
					$payload = array('status'=>'0','message'=>'Order not found!!');
					}
	    return $payload;
	}


	public function getpreparetiontime($val){
	    $orderid = $val['orderid'];
	        $orderdet = Orders::findOne($orderid);

	    if(!empty($orderdet)){
	        if(!empty($orderdet['preparetime']) && @$orderdet['preparetime'] > 0)
	        {
	            $payload = array('status'=>'1','prepartiontime' => $orderdet['preparetime'],'preparedate' => $orderdet['preparedate']);
	        }
	        else{
	            $payload = array('status'=>'0','message'=>'Order prepartion time not found!!');
	        }
	    }
	    else{
			$payload = array('status'=>'0','message'=>'Order not found!!');
		}
		return $payload;
	}
	public function rejectorder($val)
	{
        $decisionCancelStatus = ['2','3'];
		$serviceboydetails = Serviceboy::findOne($val['header_user_id']);
		$merchantDetails = Merchant::findOne($serviceboydetails['merchant_id']);
		$date = date('Y-m-d');
					$orderid = $val['orderid'];
					$orderlist = Orders::findOne($orderid);
					$tableUpdate = Tablename::findOne($orderlist['tablename']);
					if(!empty($orderlist)){
						if($orderlist['orderprocess'] != '3'){

						 
                            $sqlorderpush = "select sum(case when status = 3 then 1 else 0 end) reject_count,count(ID)  order_sent_count from order_push_pilot where merchant_id = '".$orderlist['merchant_id']."' and order_id = '".$orderlist['ID']."'";
                            $resorderpush = Yii::$app->db->createCommand($sqlorderpush)->queryOne();

                            if((!empty($sqlorderpush) && in_array($merchantDetails['cancel_decision'],$decisionCancelStatus) &&  ($resorderpush['reject_count']+1) == $resorderpush['order_sent_count'] && $val['cancelconfirm'] == 1) || $val['cancelconfirm'] == 3){
	                        
                                $roderarray = $roderwharray=  array();
                                $roderwharray['ID'] = $orderid;
                                $roderarray['orderprocess'] = 3;
                                $roderarray['serviceboy_id'] = $serviceboydetails['ID'];
                                //updateQuery($roderarray,'orders',$roderwharray);
                                $sqlUpdate = 'update orders set orderprocess = \''.$roderarray['orderprocess'].'\',serviceboy_id = \''.$roderarray['serviceboy_id'].'\'
                                ,cancel_reason = \''.$val['cancel_reason'].'\' where ID = \''.$roderwharray['ID'].'\'';
                                $result = Yii::$app->db->createCommand($sqlUpdate)->execute();
                                $userdetails = Users::findOne($orderlist['user_id']);
                                if(!empty($userdetails['push_id'])){
                                $message = "Hey ".ucwords($userdetails['name']).", Your Order has been Rejected. Sorry for inconvenience.";
                                $title = 'Order has been cancelled';
                                $image = '';

                                Utility::sendNewFCM($userdetails['push_id'],$title,$message,$image,null,null,$orderid);
                                }
                                $table_status = null;
                                $current_order_id = 0;

                                $tableUpdate->table_status = $table_status;
                                $tableUpdate->current_order_id = $current_order_id;
                                $tableUpdate->save();

                                $merchantNotidication = new MerchantNotifications;
                                $merchantNotidication->merchant_id = $orderlist['merchant_id'];
                                $merchantNotidication->message = 'Order '.$orderlist['order_id'].' on  '.$tableUpdate['name'].'-'.$tableUpdate->section['section_name'].' is <br> Cancelled' ;
                                $merchantNotidication->seen = '0';
                                $merchantNotidication->created_on = date('Y-m-d H:i:s');
                                $merchantNotidication->created_by = $val['header_user_id'];
                                $merchantNotidication->save();

                                $payload = array('status'=>'1','message'=>'Order has been Cancelled');
	                        }
							else if(!empty($sqlorderpush) && in_array($merchantDetails['cancel_decision'],$decisionCancelStatus) &&  ($resorderpush['reject_count']+1) == $resorderpush['order_sent_count'] && $val['cancelconfirm'] == 2) {
					            $payload = array('status'=>'3','message'=>'If we reject the order it will auto cancel !!');
					        }
					        else{
    					     $OrderPushPilotDet =   OrderPushPilot::find()
                                    ->where(['merchant_id' => $orderlist['merchant_id'],'order_id' => $orderlist['ID']])
    	                            ->One();
    	                            if(!empty($OrderPushPilotDet)){
    	                                $OrderPushPilotDet->status = 3;
    	                                $OrderPushPilotDet->save();    	                                
    	                            }

                                    $orderrejmodel = new OrderRejections;
                                    $orderrejmodel->order_id = $orderid;
                                    $orderrejmodel->rejected_by = $val['header_user_id'];
                                    $orderrejmodel->rejection_reason = $val['cancel_reason'];
                                    $orderrejmodel->created_on = date('Y-m-d H:i:s');
                                    $orderrejmodel->reg_date = date('Y-m-d');
                                    $orderrejmodel->save();

									$merchantNotidication = new MerchantNotifications;
									$merchantNotidication->merchant_id = $orderlist['merchant_id'];
									$merchantNotidication->message = 'New Order '.$orderlist['order_id'].' received on  '.$tableUpdate['name'].'-'.$tableUpdate->section['section_name'].' is <br> rejected by '.$serviceboydetails['name'];
									$merchantNotidication->seen = '0';
									$merchantNotidication->created_on = date('Y-m-d H:i:s');
									$merchantNotidication->created_by = $val['header_user_id'];
									$merchantNotidication->save();

									$notificaitonarary = array();
									$notificaitonarary['merchant_id'] = $orderlist['merchant_id'];
									$notificaitonarary['serviceboy_id'] = $val['header_user_id'];
									$notificaitonarary['order_id'] = (string)$orderid;
									$notificaitonarary['title'] = 'Reject Order';
									$notificaitonarary['message'] = 'You Rejected the Order '.$orderlist['order_id'].' as '.$val['cancel_reason'];
									$notificaitonarary['ordertype'] = 'reject';
									$notificaitonarary['seen'] = '0';
													
									$serviceBoyNotiModel = new  ServiceboyNotifications;
									$serviceBoyNotiModel->attributes = $notificaitonarary;
									$serviceBoyNotiModel->reg_date = date('Y-m-d H:i:s');
									$serviceBoyNotiModel->mod_date = date('Y-m-d H:i:s');
									$serviceBoyNotiModel->save();

									$payload = array('status'=>'1','message'=>'Order has been Rejected');
					    }
					}else{
						$payload = array('status'=>'0','message'=>'Order has already cancelled!!');
					}
					}else{
					$payload = array('status'=>'0','message'=>'Order not found!!');
					}
		return $payload;
	}
	public function deliverorder($val)
	{
		$serviceboydetails = Serviceboy::findOne($val['header_user_id']);
		$date = date('Y-m-d');
					$orderid = $val['orderid'];
					$orderlist = Orders::findOne($orderid);
					$tableDetails = Tablename::findOne($orderlist['tablename']);
					if(!empty($orderlist)){ 
						$roderarray = $roderwharray=  array();
						$roderwharray['ID'] = $orderid;  
						$roderarray['reorderprocess'] = 0;
						$roderarray['orderprocess'] = 4;
						$roderarray['closed_by'] = $serviceboydetails['ID'];
						$roderarray['serviceboy_id'] = $serviceboydetails['ID'];
						$roderarray['paymenttype'] = $val['paymenttype'];
						$roderarray['paidstatus'] = '1';
						//updateQuery($roderarray,'orders',$roderwharray);
						$sqlUpdate = 'update orders set reorderprocess = \''.$roderarray['reorderprocess'].'\'
						,orderprocess=\''.$roderarray['orderprocess'].'\',serviceboy_id=\''.$roderarray['serviceboy_id'].'\'
						,paymenttype=\''.$roderarray['paymenttype'].'\'
						,closed_by = \''.$roderarray['closed_by'].'\',paidstatus = \''.$roderarray['paidstatus'].'\'  where ID =\''.$roderwharray['ID'].'\'';
						$resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute();
					
					$sqlresult = "update order_products set reorder = '0' where order_id = '".$orderlist['ID']."'";
					$result =  Yii::$app->db->createCommand($sqlresult)->execute();
						$userdetails = Users::findOne($orderlist['user_id']);
						if(!empty($userdetails['push_id'])){	
							$title = 'Pick your order';
							$image = '';
							$message = "Hey ".ucwords($userdetails['name']).", Your Order has been Served. Take it with smile";			
							Utility::sendNewFCM($userdetails['push_id'],$title,$message,$image,null,null,$orderid);
						}
						$coinstransactions = array();
						$coinsAdd = round(($orderlist['amount'] - $orderlist['couponamount'] ) /20,0);
						$coinstransactions['user_id'] = $userdetails['ID'];
						$coinstransactions['txn_id'] = Utility::coinstxn_id();
						$coinstransactions['order_id'] = $orderlist['ID'];
						$coinstransactions['merchant_id'] = $serviceboydetails['merchant_id'];
						$coinstransactions['coins'] =  $coinsAdd;
						$coinstransactions['type'] = 'Credit';
						$coinstransactions['reward_id'] = 0;
						$coinstransactions['rewardcoupon_id'] = 0;
						$coinstransactions['reason'] = $coinsAdd." coins added to your wallet for the order.";
						
						$result = new CoinsTransactions;
						$result->attributes = $coinstransactions;
						$result->reg_date = date('Y-m-d H:i:s');
						if($result->save()){
						Utility::coins_update($userdetails['ID'],$coinsAdd);
						}else{
						    Yii::trace("=====coins not insert=====".json_encode($result->getErrors()));
						}
						$datetime = date("Y-m-d H:i:s");
						$sqlUpdate = "update orders set orderprocessstatus = '1',deliverdate = '".$datetime."' 
						where ID = '".$orderlist['ID']."'";
						$resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute();
							$table_status = null;
			                $current_order_id = 0;
			                
						$tableUpdate = Tablename::findOne($orderlist['tablename']);
							$tableUpdate->table_status = $table_status;
		                    $tableUpdate->current_order_id = $current_order_id;
		                	$tableUpdate->save();
							

							$merchantNotidication = new MerchantNotifications;
							$merchantNotidication->merchant_id = $orderlist['merchant_id'];
							$merchantNotidication->message = 'Order '.$orderlist['order_id'].' on  '.$tableDetails['name'].'-'.$tableDetails->section['section_name'].' <br> is Completed';
							$merchantNotidication->seen = '0';
							$merchantNotidication->created_on = date('Y-m-d H:i:s');
							$merchantNotidication->created_by = $val['header_user_id'];
							$merchantNotidication->save();

						$payload = array('status'=>'1','message'=>'Order has been delivered.');
						 
					}else{
					$payload = array('status'=>'0','message'=>'Order not found!!');
					}
		return $payload;
	}
	public function paidstatus($val)
	{
		$serviceboydetails = Serviceboy::findOne($val['header_user_id']);
		$orderid = $val['orderid'];
					$orderlist = Orders::findOne($orderid);
					$tableDetails = Tablename::findOne($orderlist['tablename']);
					if(!empty($orderlist)){ 
						$roderarray = $roderwharray=  array();
						$roderwharray['ID'] = $orderid;  
						$roderarray['paidstatus'] = '1';
						$roderarray['serviceboy_id'] = $serviceboydetails['ID']; 
						//updateQuery($roderarray,'orders',$roderwharray);
						$sqlUpdate = 'update orders set paidstatus = \''.$roderarray['paidstatus'].'\'
						,serviceboy_id = \''.$roderarray['serviceboy_id'].'\' where ID = \''.$roderwharray['ID'].'\'';
						$resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute();
						$rodertrawharray['order_id'] = $orderid;  
						$rodertrawharray['user_id'] = $orderlist['user_id'];  
						$rodertraarray['paidstatus'] = '1'; 
						//updateQuery($rodertraarray,'order_transactions',$rodertrawharray);
						$sqlUpdate1 = 'update order_transactions set paidstatus = \''.$rodertraarray['paidstatus'].'\'
						where order_id = \''.$rodertrawharray['order_id'].'\' and user_id = \''.$rodertrawharray['user_id'].'\'';
						$resUpdate1 = Yii::$app->db->createCommand($sqlUpdate1)->execute();
						$userdetails = Users::findOne($orderlist['user_id']);
						if(!empty($userdetails['push_id'])){	
						$message = "Hey ".ucwords($userdetails['name']).", Thank you for making payment.";	
						$title = 'Amount recevied.';
						$image = '';
						Utility::sendNewFCM($userdetails['push_id'],$title,$message,$image,null,null,$orderid);			
						}
						$merchantNotidication = new MerchantNotifications;
						$merchantNotidication->merchant_id = $orderlist['merchant_id'];
						$merchantNotidication->message = \app\helpers\MyConst::PAYMENT_METHODS[$orderlist['paymenttype']].' Payment of Rs. '.$orderlist['totalamount'].'<br> 
						received on Order '.$orderlist['order_id'].' of '.$tableDetails['name'].'-'.$tableDetails->section['section_name'];
						$merchantNotidication->seen = '0';
						$merchantNotidication->created_on = date('Y-m-d H:i:s');
						$merchantNotidication->created_by = $val['header_user_id'];
						$merchantNotidication->save();


							$payload = array('status'=>'1','message'=>'Payment status updated.');
					}else{
					$payload = array('status'=>'0','message'=>'Order not found!!');
					}
		return $payload;
	}
	public function orderslist($val)
	{
		$serviceboydetails = Serviceboy::findOne($val['header_user_id']);
			if(!empty($val['orderfromdate'])){
					$getdate = $val['orderfromdate'];
						$fromdate = $getdate;
					}else{
					$fromdate = date('Y-m-d');
					}
					if(!empty($val['ordertodate'])){
					$gettodate = $val['ordertodate'];
						$todate = $gettodate;
					}else{
					$todate = date('Y-m-d');
					}
					$sqlorderlistarray = "select * from orders where  merchant_id = '".$serviceboydetails['merchant_id']."' 
					and serviceboy_id = '".$serviceboydetails['ID']."'  and date(reg_date) between '".$fromdate."' and  '".$todate."'";
					$orderlistarray = Yii::$app->db->createCommand($sqlorderlistarray)->queryAll();
					if(!empty($orderlistarray)){
	
					$orderarray = $totalordersarray = array();
					$totalOrders = 0;
					$totalAmount = 0;
					$onlinePayment = 0;
					$offlinePayment = 0;
					$pendingOrders = 0;
					$pendingAmount =0;
					foreach($orderlistarray as $orderlist){
						$totalproductaarray = array();
						$orderarray['order_id'] =  $orderlist['ID'];
						$orderarray['unique_id'] =  $orderlist['order_id']; 
						$orderarray['username'] = Utility::user_details($orderlist['user_id'],"name");
						$orderarray['storename'] = Utility::merchant_details($orderlist['merchant_id'],"storename");
						$orderarray['tablename'] = Utility::table_details($orderlist['tablename'],"name"); 
						$orderarray['amount'] =  !empty($orderlist['tax']) ? $orderlist['amount'] : 0; 
						$orderarray['tax'] = !empty($orderlist['tax']) ?  $orderlist['tax'] : 0;
						$orderarray['tips'] = !empty($orderlist['tips']) ?  $orderlist['tips'] : 0;
						$orderarray['subscription'] = !empty($orderlist['subscription']) ?   $orderlist['subscription'] : 0;
						$orderarray['couponamount'] = !empty($orderlist['couponamount']) ?  $orderlist['couponamount'] : 0;
						$orderarray['totalamount'] =  !empty($orderlist['totalamount']) ?   $orderlist['totalamount'] : 0;
						$orderarray['paymenttype'] =  $orderlist['paymenttype']=='cash' ? 'Cash' : 'Online';
						$orderarray['orderprocess'] =  $orderlist['orderprocess']; 
						$orderarray['orderprocesstext'] =  Utility::orderstatus_details($orderlist['orderprocess']); 
						$orderarray['paidstatus'] =  $orderlist['paidstatus']; 
						$orderarray['orderline'] =  $orderlist['orderline'];
                        $orderarray['ordertype'] =  $orderlist['ordertype'];
                        $orderarray['ordertypetext'] =  Utility::orderTypeText($orderlist['ordertype']);

						$orderarray['regdate'] =  date('d M Y',strtotime($orderlist['reg_date'])); 
						$totalOrders = $totalOrders + 1;
						$totalAmount = $totalAmount + $orderlist['totalamount'];
						if($orderlist['paymenttype']=='cash'){
						    $offlinePayment = $offlinePayment + $orderlist['totalamount'];
						}
						else{
						    $onlinePayment = $onlinePayment + $orderlist['totalamount'];
						}
						if($orderlist['paidstatus'] == '0'){
						    $pendingAmount = $pendingAmount + $orderlist['totalamount'];
					        $pendingOrders = $pendingOrders + 1;	    
						    
						}
						
					$totalordersarray[] = $orderarray;
					}
					$consolidatedOrderStatus = array('totalOrders'=>strval($totalOrders),'onlinePayment'=>strval($onlinePayment),'offlinePayment'=>strval($offlinePayment)
					,'pendingAmount'=>strval($pendingAmount),'pendingOrders'=>strval($pendingOrders),'totalAmount'=>strval($totalAmount));
				 	$payload = array('status'=>'1','orders'=>$totalordersarray,'orderstatus'=>$consolidatedOrderStatus); 
					}else{
					$payload = array('status'=>'1','message'=>'Order not found!!','orders' => []);
					}
		return $payload;
	}
	public function cancelreasons($val)
	{
	    $sql = 'select 0 as ID,\'others\' as cancel_reason union all  select ID,cancel_reason from cancelled_reasons';
	    //$sql = 'select ID,cancel_reason from cancelled_reasons';
	    $res = Yii::$app->db->createCommand($sql)->queryAll();
	    return array('status'=>'1','reasons'=>$res);
	}
	public function foodcomplaintreasons($val){
	    $sql = 'select 1 as id,\'Events\' as complaint_reason
	    union select 2 ,\'Friends and Family\' union select 3 ,\'Food is not good\'';
	    $res = Yii::$app->db->createCommand($sql)->queryAll();

	    return array('status'=>'1','reasons'=>$res);
	    
	}
	public function qrcode($val)
	{
		if(!empty($val['enckey'])){ 
			$userwherearray = $userarray = array();
			if ( strstr( $val['enckey'], 'superpilot' ) ) {
                $pos = explode('?',$val['enckey']);
                parse_str($pos[1], $outputencarray);
                $enckey = Utility::decrypt($outputencarray['enckey']);
            } else {
			    $enckey = Utility::decrypt(trim($val['enckey']));
            }
			$foodtype = trim($val['foodtype']) ?: 0;
			$latfrom = trim($val['lat']) ?: 0;
			$lngfrom = trim($val['lng']) ?: 0;
						/* $enckey = trim($_POST['enckey']);*/ 
			if(!empty($enckey)){
			    if($latfrom == 0 || $lngfrom == 0){
			        $payload = array("status"=>'0',"text"=>"Unable to get your location");
			        exit;
				}
				$merchantexplode = explode(',',$enckey);
				$merchantid = $merchantexplode[0];
				$tableid = $merchantexplode[1];
								
				$tabel_Det = Tablename::findOne($tableid);
				$merchantdetails = Merchant::find()
					->where(['ID'=>$merchantid, 'status'=>'1'])->asArray()->One();
				if( ($tabel_Det['table_status'] == '1' && $tabel_Det['current_order_id'] > 0 && $merchantdetails['table_occupy_status'] == 1) || 
				($merchantdetails['table_occupy_status'] == 2) )	{
				    $currentOrder = Orders::findOne($tabel_Det['current_order_id']);
				    if($currentOrder['serviceboy_id'] != $val['header_user_id'] && $merchantdetails['table_occupy_status'] == 1){
				        $payload = array("status"=>'0',"text"=>"Table is already occupied");
					    return $payload;
					    exit;
				    }
				}
								

						
							if(!empty($merchantdetails)){
									$latlngdistance = Utility::haversineGreatCircleDistance($latfrom,$lngfrom,$merchantdetails['latitude'],$merchantdetails['longitude']);
						
									if($latlngdistance > $merchantdetails['scan_range']){
									    $payload = array("status"=>'0',"text"=>"Restaurent or  theater distance is too long");
									//return $payload;
									 //   exit;
									}

									$tabledetails = Tablename::find()
									->where(['merchant_id'=>$merchantdetails['ID'], 'ID'=>$tableid, 'status'=>'1'])
									->asArray()->One();
									if(!empty($tabledetails)){
										$sqlmerchantproductsarray = 'select p.*,section_item_price,section_item_sale_price from product p 
										left join section_item_price_list sipl on sipl.item_id =  p.ID and sipl.section_id = \''.$tabledetails['section_id'].'\' 
										where p.merchant_id = \''.$merchantdetails['ID'].'\' 
										and  p.status = \'1\' ';

										if($foodtype>0){
                                            $sqlmerchantproductsarray .= ' and p.foodtype = \''.$foodtype.'\' ';
										}
										
										if(!empty($val["isVeg"])){
										       $sqlmerchantproductsarray .= ' and p.item_type = \''.$val['isVeg'].'\' ';
										}
										
										if(!empty($val["today_special"])){
										       $sqlmerchantproductsarray .= ' and p.today_special = \''.$val['today_special'].'\' ';
										}
										
										
										$merchantproductsarray = Yii::$app->db->createCommand($sqlmerchantproductsarray)->queryAll();
										$getproducts = array();
										foreach($merchantproductsarray as $merchantproduct){
										    if($merchantproduct['section_item_sale_price'] > 0){
										$singleproducts = array();
										$singleproducts['id'] = (int)$merchantproduct['ID'];
										$singleproducts['unique_id'] = $merchantproduct['unique_id'];
										$singleproducts['title'] = $merchantproduct['title'];
										$singleproducts['item_type'] = $merchantproduct['item_type'];
										$singleproducts['labeltag'] = $merchantproduct['labeltag'];
										$singleproducts['serveline'] = $merchantproduct['serveline'];
										$singleproducts['price'] = $merchantproduct['section_item_price'];
										$singleproducts['food_category'] = Utility::foodtype_value_another($merchantproduct['foodtype'],$merchantdetails['ID']);
										$singleproducts['food_unit'] = Utility::foodcategory_type($merchantproduct['food_category_quantity']);
										$singleproducts['saleprice'] = $merchantproduct['section_item_sale_price'];
										$singleproducts['availabilty'] = $merchantproduct['availabilty']; 
										$singleproducts['image'] = !empty($merchantproduct['image']) ? MERCHANT_PRODUCT_URL.$merchantproduct['image'] : '';

										$restax = MerchantFoodCategoryTax::find()
										->select('tax_type,tax_value')
										->where(['merchant_id'=>$merchantid, 'food_category_id'=>$merchantproduct['foodtype']])
										->asArray()->All();
										
										$singleproducts['tax'] = $restax;
										
										
										$getproducts[] = $singleproducts;
										    }
										} 
										$merchantlgo = !empty($merchantdetails['logo']) ? MERCHANT_LOGO.$merchantdetails['logo'] : '';
                                    
										$merchantcoverpic = !empty($merchantdetails['coverpic']) ? MERCHANT_LOGO.$merchantdetails['coverpic'] : '';
										$itemCategoryImagePath = SITE_URL.'merchant_docs/'.$merchantid.'/'.'item_category/';
										    $sqlcategoryDetail = 'select 0 foodtype, \'Recommended\' food_category,null category_img,count(foodtype) itemcount  from product where merchant_id = \''.$merchantid.'\'
                                                        
                                                        union all
													select foodtype,case when foodtype = \'0\' then \'All\'  else fc.food_category end as food_category 
													,concat(\''.$itemCategoryImagePath.'\',category_img) category_img
                                                        ,count(foodtype) itemcount  from product p
                                                        left join food_categeries fc on fc.id = p.foodtype
                                                     where p.merchant_id = \''.$merchantid.'\'
                                                        group by foodtype,category_img';
											$categoryDetail = Yii::$app->db->createCommand($sqlcategoryDetail)->queryAll();
							            	$getproductsreindex = ArrayHelper::index($getproducts, null, 'food_category');
                                            $newProduclistArr = [];
											$pr = 0;
											foreach($getproductsreindex as $catName => $catItems){
												$newProduclistArr[$pr]['categoryName'] =$catName;
												$newProduclistArr[$pr]['items'] =$catItems;
												$pr++;
											}

										$payload = array("status"=>'1',"merchantid"=>$merchantdetails['ID'],"table"=>$tabledetails['ID']
										,"tablename"=>$tabledetails['name'],'section_name' => $tabledetails->section['section_name']
										,"store"=>$merchantdetails['storename'],"storetype"=>$merchantdetails['storetype']
										,"servingtype"=>$merchantdetails['servingtype'],"verify"=>$merchantdetails['verify']
										,"location"=>$merchantdetails['location'],"logo"=>$merchantlgo,"coverpic"=>$merchantcoverpic
										,"productlist"=>$newProduclistArr,'categoryDetail'=>$categoryDetail,'configured_tip' => $merchantdetails['tip']);
									}else{
										
										$payload = array("status"=>'0',"text"=>"Invalid Table or seat details scan again");
									}
								}else{
									
									$payload = array("status"=>'0',"text"=>"Invalid Restaurent or  theater can again");
								}
							}else{
									
								$payload = array("status"=>'0',"text"=>"Please scan again");
							}
						}else{
								
							$payload = array("status"=>'0',"text"=>"Please scan again");
						}
		return $payload;	
	    
	}

		public function cash($val){
	    	    		 Yii::debug('===cash parameters==='.json_encode($val));

		if(!empty($val['merchantid']) && !empty($val['productid']) && !empty($val['count']) && !empty($val['price']) ){
						$userwherearray = $userarray = array();
						$couponcode = !empty($val['coupon']) ? trim($val['coupon']) : '';
						$merchantid = trim($val['merchantid']);
						$customer_mobile = $val['mobilenumber']; 
						
			$userId = '';
			if(!empty($customer_mobile)){
			$userDet = \app\models\Users::find()->where(['mobile'=>$customer_mobile])->asArray()->One();
			if(empty($userDet)){
				$userId = Yii::$app->merchant->userCreation($customer_mobile,$val['username'],$val['email'],$val['dob'],$val['occupation']);	
			}
			else{
				$userId = $userDet['ID'];
			}
			
		}
		$merchant_details = Merchant::findOne($merchantid);
		$tabel_Det = Tablename::findOne($val['table']);
								
								if(($tabel_Det['current_order_id'] > 0 &&  $merchant_details['table_occupy_status'] == 1) )
								{
								    $currentOrder = Orders::findOne($tabel_Det['current_order_id']);
								    if(($currentOrder['serviceboy_id'] != $val['user_id']) ){
								        $payload = array("status"=>'0',"text"=>"Table is already occupied");
									    return $payload;
									    exit;
								    }    
								}

                        $val['serviceboy_id'] = $val['usersid'];
						$valtax = !empty($val['tax']) ? number_format(trim($val['tax']), 2, '.', ',') : 0;
							$userarray['merchant_id'] = $merchantid;
							$userarray['serviceboy_id'] = isset($val['serviceboy_id']) ? $val['serviceboy_id'] : '';
							$userarray['tablename'] = 	$val['table'];
							$userarray['order_id'] = Utility::order_id($merchantid,'order'); 
							$userarray['txn_id'] = Utility::order_id($merchantid,'transaction'); 
							$userarray['txn_date'] = date('Y-m-d H:i:s');
							$userarray['reg_date'] = date('Y-m-d H:i:s');
							$userarray['amount'] = $val['amount'];
							$userarray['tax'] = (string)$valtax;
							$userarray['tips'] = !empty($val['tips']) ? number_format(trim($val['tips']), 2, '.', ',') : '0';
							$userarray['subscription'] = !empty($val['subscription']) ?  number_format(trim($val['subscription']), 2, '.', ',') : '0';
							$userarray['totalamount'] =  $val['totalamount'];
							$userarray['couponamount'] = !empty($val['couponamount']) ? number_format(trim($val['couponamount']), 2, '.', ',') : 0;
							$userarray['paymenttype'] = 'cash';
							$userarray['orderprocess'] = '1';
							$userarray['status'] = '1';
							$userarray['paidstatus'] = '0';
							$userarray['paid_amount'] = !empty($val['totalamount']) ?  $val['totalamount'] : 0.00;
							$userarray['pending_amount'] = 0.00;
							$userarray['paymentby'] = '1';
							$userarray['ordertype'] = 4;
							$userarray['coupon'] = $couponcode;
							$userarray['user_id'] = $userId;
							$userarray['instructions'] = $val['instructions'];
							$userarray['discount_number'] = $val['discount_number'];
							$userarray['tax'] = (string)$valtax;
							$sqlprevmerchnat = "select max(orderline) as id from orders where merchant_id = '".$merchantid."' and reg_date >='".date('Y-m-d')." 00:00:01' and reg_date <='".date('Y-m-d')." 23:59:59'";
							$resprevmerchnat = Yii::$app->db->createCommand($sqlprevmerchnat)->queryOne();
							$prevmerchnat = $resprevmerchnat['id']; 
							 
							$newid = $prevmerchnat>0 ? $prevmerchnat+1 : 100;  
							$userarray['orderline'] = (string)$newid;
							$result = new \app\models\Orders;
							$result->attributes = $userarray;

							$result->couponamount = (string)$userarray['couponamount'];
		
							if($result->save()){
								
							$orderdetails = Orders::findOne($result->ID);
									
							$transactionscount = array(); 
							$transactionscount['order_id'] = $orderdetails['ID'];
							$transactionscount['merchant_id'] = $merchantid;
							$transactionscount['amount'] = !empty($userarray['amount']) ? number_format(trim($userarray['amount']),2, '.', ',') : 0; 
							$transactionscount['couponamount'] =  !empty($userarray['couponamount']) ? number_format(trim($userarray['couponamount']),2, '.', ',') : 0; 
							$transactionscount['tax'] =  !empty($userarray['tax']) ? number_format(trim($userarray['tax']),2, '.', ',') : 0; 
							$transactionscount['tips'] =  !empty($userarray['tips']) ? number_format(trim($userarray['tips']),2, '.', ',') : '0'; 
							$transactionscount['subscription'] =  !empty($userarray['subscription']) ? number_format(trim($userarray['subscription']),2, '.', ',') : '0'; 
							$transactionscount['totalamount'] =   !empty($userarray['totalamount']) ? number_format(trim($userarray['totalamount']),2, '.', ',') : 0; 
							$transactionscount['paymenttype'] = 'cash';
							$transactionscount['reorder'] = '0';
							$transactionscount['paidstatus'] = '0';
							$transactionscount['paidstatus'] = $userId;
							
							//$result = insertQuery($transactionscount,"order_transactions");
							$ordertransmodel = new \app\models\OrderTransactions;
							$ordertransmodel->attributes = $transactionscount;
							$ordertransmodel->couponamount = (string)$transactionscount['couponamount'];
							$ordertransmodel->reg_date = date('Y-m-d H:i:s');
							$ordertransmodel->save();

								if(!empty($val['productid'])&&!empty($val['count'])&&!empty($val['price'])){
									$productidsarray = json_decode($val['productid']);
									$productcountarray = json_decode($val['count']);
									$productpricearray = json_decode($val['price']);
									$x=1;
									for($i=0;$i<count($productidsarray);$i++){
										$productscount = array();
										$productscount['order_id'] = (string)$orderdetails['ID'];
										$productscount['merchant_id'] = $merchantid;
										$productscount['product_id'] = trim($productidsarray[$i]);
										$productscount['count'] = trim($productcountarray[$i]);
										$productscount['price'] = trim($productpricearray[$i]);
										$productscount['inc'] = (string)$x;
										$productscount['reorder'] = '0';
										$productscount['user_id'] = $userId;
										//$result = insertQuery($productscount,"order_products");
										$orderProdModel = new \app\models\OrderProducts;
										$orderProdModel->attributes = $productscount;
										$orderProdModel->reg_date = date('Y-m-d H:i:s');
										$orderProdModel->save();
										

									$x++; }

									
										if(!empty($couponcode)){
										$sqlcoupandetails = "select * from merchant_coupon where code LIKE '".$couponcode."'";
										$coupandetails = Yii::$app->db->createCommand($sqlserviceboyarray)->queryOne();
											if(!empty($coupandetails)&&$coupandetails['purpose']=='Single'){
											$sqlUpdate = "update merchant_coupon set status = 'Deactive' where ID = '".$coupandetails['ID']."'";
											$resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute();
											}
											}

											$merchantNotidication = new MerchantNotifications;
											$merchantNotidication->merchant_id = $orderdetails['merchant_id'];
											$merchantNotidication->message = 'New Order '.$orderdetails['order_id'].' received on  '.$tabel_Det['name'].'-'.$tabel_Det->section['section_name'];
											$merchantNotidication->seen = '0';
											$merchantNotidication->created_on = date('Y-m-d H:i:s');
											$merchantNotidication->created_by = $val['header_user_id'];
											$merchantNotidication->save();
											
											$tableUpdate = Tablename::findOne($val['table']);
						                    $tableUpdate->table_status = '1';
						                    $tableUpdate->current_order_id = $orderdetails['ID'];
						                    if($tableUpdate->validate()){
						                     $tableUpdate->save();   
						                    }
						                    else{
						                       Yii::debug('===cash errors==='.json_encode($tableUpdate->getErrors()));
						                    }
										$payload = array("status"=>'1',"id"=>$orderdetails['ID'],"text"=>"Order Created successfully");
									
								}
							}else{
							    echo "<pre>";print_r($result->getErrors());exit;
								$payload = array("status"=>'0',"text"=>"Order Failed Please order again");
							}
							
						}else{
								
							$payload = array("status"=>'0',"text"=>"Invalid parameters");
						}
		return $payload;
	}
		public function reordercash($val){
	    	Yii::debug('===reordercash parameters==='.json_encode($val));

			if(!empty($val['merchantid'])){ 
						$userwherearray = $userarray = array();
						if(!empty($val['orderid'])){
						    $userdetails = [];

						$orderid = !empty($val['orderid']) ? trim($val['orderid']) : ''; 
						$couponcode = !empty($val['coupon']) ? trim($val['coupon']) : '';

							$orderdetails = Orders::findOne($orderid);
							$userdetails = Users::findOne($orderdetails['user_id']);
							$tabledetails = Tablename::findOne($orderdetails['tablename']);
							$serviceboyDetails = Serviceboy::findOne($orderdetails['serviceboy_id']);
							
							$orderamount = trim($val['totalamount']);
							$totalamount = number_format($orderamount, 2, '.', ',');
							
							$userarray['amount'] =  trim($val['amount']); 
							$couponamount = !empty($val['couponamount']) ? (string)trim($val['couponamount']) : '0';
							//$couponamount = number_format($orderdetails['couponamount']+$couponamount, 2, '.', ',');
							
							$userarray['couponamount'] = $couponamount;
							$taxamount = !empty($val['tax']) ? trim($val['tax']) : 0;
							$userarray['tax'] = number_format($taxamount, 2, '.', ',');
							$tipsamount = !empty($val['tips']) ? trim($val['tips']) : 0;
							$userarray['tips'] = number_format($tipsamount, 2, '.', ',');
							$subscriptionamount = !empty($val['subscription']) ? trim($val['subscription']) : '0';
							$userarray['subscription'] = number_format($subscriptionamount, 2, '.', ','); 
							$merchantid = trim($val['merchantid']); 
							$userarray['reorderprocess'] = '1'; 
							//$userarray['orderprocess'] = '1'; 
							$userarray['paidstatus'] = ($orderdetails['paidstatus'] == '1') ? '3' : $orderdetails['paidstatus'];
							$userarray['pending_amount'] = $orderamount -  ($orderdetails['paid_amount'] ?? 0);
							//$userarray['paymentby'] = '1';
							$userarray['totalamount'] = $orderamount; 
							$userarray['couponamount'] = !empty($val['couponamount']) ? number_format(trim($val['couponamount']), 2, '.', ',') : 0;
                            $userarray['coupon'] = $couponcode;
							$userarray['instructions'] = $val['instructions'];
							$userarray['discount_number'] = $val['discount_number'];
							$userarray['instructions'] = !empty($val['instructions']) ? $val['instructions'] : $orderdetails['instructions'];
							$userwwherearray['ID'] = $orderid; 
							//$result = updateQuery($userarray,"orders",$userwwherearray);
							$result = Orders::findOne($orderid);

							//echo "<pre>";print_r($userarray);exit;
							$result->attributes = $userarray;
							if($result->save()){
									/*$sqlcoinsdata = "select * from coins_transactions where user_id = '".$orderdetails['user_id']."' and
									 order_id = '".$orderdetails['order_id']."'";
									$coinsdata = Yii::$app->db->createCommand($sqlcoinsdata)->queryOne();*/
									$coinsdata = CoinsTransactions::find()
									->where(['user_id'=>$orderdetails['user_id'], 'order_id'=>$orderdetails['order_id']])
									->asArray()->One();
									if(!empty($coinsdata['user_id'])&&!empty($coinsdata['coins'])){
									$sqlDelete = "delete from coins_transactions where ID = '".$coinsdata['ID']."'";
									$resDelete = Yii::$app->db->createCommand($sqlDelete)->execute();
									Utility::coins_deduct($coinsdata['user_id'],$coinsdata['coins']);
									}
									
								 $transactionscount = array(); 
								$transactionscount['order_id'] = $orderdetails['ID'];
								$transactionscount['user_id'] = $orderdetails['user_id'];
								$transactionscount['merchant_id'] = $merchantid;
								$transactionscount['amount'] = !empty($amountant) ? number_format(trim($amountant),2, '.', ',') : 0; 
								$transactionscount['couponamount'] =  !empty($couponamount) ? number_format(trim($couponamount),2, '.', ',') : 0; 
								$transactionscount['tax'] =  !empty($taxamount) ? number_format(trim($taxamount),2, '.', ',') : 0;
								$transactionscount['tips'] =  !empty($tipsamount) ? number_format(trim($tipsamount),2, '.', ',') : 0; 
								$transactionscount['subscription'] =  !empty($subscriptionamount) ? number_format(trim($subscriptionamount),2, '.', ',') : 0;
								$transactionscount['totalamount'] =  !empty($orderamount) ? number_format(trim($orderamount),2, '.', ',') : 0;
								$transactionscount['paymenttype'] = 'cash';
								$transactionscount['reorder'] = '1';
								$transactionscount['paidstatus'] = '0';
								//$result = insertQuery($transactionscount,"order_transactions");
								$orderTransModel = new \app\models\OrderTransactions;
								$orderTransModel->attributes = $transactionscount;
								$orderTransModel->couponamount = (string)$transactionscount['couponamount'];
								$orderTransModel->reg_date = date('Y-m-d H:i:s');
								$orderTransModel->save();
								if(!empty($val['productid'])&&!empty($val['count'])&&!empty($val['price'])){
									$productidsarray = json_decode($val['productid']);
									$productcountarray = json_decode($val['count']);
									$productpricearray = json_decode($val['price']);
									$sqltopincdata = "select max(inc) as inc from order_products where order_id = '".$orderdetails['ID']."'";
									$topincdata = Yii::$app->db->createCommand($sqltopincdata)->queryOne();
									$newid = (int)$topincdata['inc'];
									$x=$newid+1;
									for($i=0;$i<count($productidsarray);$i++){
										$productscount = array();
										$productscount['order_id'] = (string)$orderdetails['ID'];
										$productscount['user_id'] = @(string)$userdetails['ID'];
										$productscount['merchant_id'] = $merchantid;
										$productscount['product_id'] = trim($productidsarray[$i]);
										$productscount['count'] = trim($productcountarray[$i]);
										$productscount['price'] = trim($productpricearray[$i]);
										$productscount['reorder'] = '1';
										$productscount['inc'] = (string)$x;
										
										//$result = insertQuery($productscount,"order_products");
										$orderProdModel = new \app\models\OrderProducts;
										$orderProdModel->attributes = $productscount;
										$orderProdModel->reg_date = date('Y-m-d H:i:s');
										$orderProdModel->save();
										if($i == 0){
											$merchantNotidication = new \app\models\MerchantNotifications;
											$merchantNotidication->merchant_id = $orderdetails['merchant_id'];
											$merchantNotidication->message = 'Items added to '.$orderdetails['order_id'].' on  '.$tabledetails['name'].'-'.$tabledetails->section['section_name'].' <br> by '.$serviceboyDetails['name'];
											$merchantNotidication->seen = '0';
											$merchantNotidication->created_on = date('Y-m-d H:i:s');
											$merchantNotidication->created_by = $val['header_user_id'];
											$merchantNotidication->save();
											
											$title = 'Order Items has been added!!';
											$message = "Hi ".$userdetails['name'].", Your order has been placed. ".$orderdetails['order_id']." is your line Please Wait for your turn Thank you.";
											$image = '';
											//Yii::$app->merchant->send_sms($userdetails['mobile'],$message);
											if(!empty($userdetails['push_id'])){
												Utility::sendNewFCM($userdetails['push_id'],$title,$message,$image,null,null,$orderdetails['ID']);
											}
											
											
											if(!empty($serviceboyDetails['push_id'])){
												$stitle = 'Order items have been added.';
												$smessage = 'Order received please check the app for information.';
												$simage = ''; 
													Utility::sendNewFCM($serviceboyDetails['push_id'],$stitle,$smessage,$simage,'6',null,$orderdetails['ID']); 
											}
										
										}
										
									$x++; }
	
											 
											

										$payload = array("status"=>'1',"id"=>$orderdetails['ID'],"message"=>"Order Updated successfully");
									
								}
								else{
								    $payload = array("status"=>'1',"id"=>$orderdetails['ID'],"message"=>"Order Updated successfully");
								}
							}else{
								echo "<pre>";print_r($result->getErrors());exit;
								$payload = array("status"=>'0',"message"=>"Order Failed Please order again");
							}
							
						}else{
								
							$payload = array("status"=>'0',"message"=>"Invalid order");
						}
						}else{
								
							$payload = array("status"=>'0',"message"=>"Invalid parameters");
						}
		
		return $payload;
	}
	public function addpilotfeedback($val){
	    //Yii::debug('===qrcode parameters==='.json_encode($val));
		if(!empty($val['rating'])){
		    $sqlorderdet = Orders::findOne($val['orderid']);
		    if(!empty($sqlorderdet)){
						$userarray = $userwherearray = array();
						$userarray['user_id'] = $sqlorderdet['user_id'];
						$userarray['order_id'] = trim($val['orderid']);
						$userarray['merchant_id'] = trim($val['merchantid']);
						$userarray['pilot_rating'] =  trim($val['rating']);
						$userarray['pilot_message'] =  !empty($val['message']) ? trim($val['message']) : ''; 
						$userarray['reg_date'] = date('Y-m-d H:i:s');
						//$result = insertQuery($userarray,'feedback');  
						$result = new \app\models\Feedback;
						$result->attributes = $userarray;
						if(!$result->save()){
						    print_r($result->getErrors());exit;
						}		        
		    }
		    else{
		        		$sqlUpdate = "update feedback set pilot_rating = '".$val['rating']."',pilot_message = '".$val['message']."' where order_id = '".$val['orderid']."'";
						$resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute();
		    }

			//			if($result->save()){  
				//		$sqlUpdate = "update orders set orderprocess = '4',orderprocessstatus = '0' where ID = '".$userarray['order_id']."'";
				//		$resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute(); 
							$payload = array("status"=>'1',"text"=>"Feedback updated");
					//	}else{ 	
				//			$payload = array("status"=>'1',"text"=>$result);
			//			}    
					}else{
									
						$payload = array('status'=>'0','message'=>'Invalid Parameters');
					}
					return $payload;
	}
	public function merchantpaymenttypes($val){

		$paytypes = array('1'=>'Cash On Dine','2'=>'Online Payment','3'=>'UPI Scanner','4'=>'Card Swipe');
		$merchant_pay_types_det = \app\models\MerchantPaytypes::find()->where(['merchant_id'=>$val['merchant_id'],'status' => 1])->orderBy([
            'ID'=>SORT_DESC
        ])->asArray()->All();
        $merchant_pay_types = array_column($merchant_pay_types_det,'paymenttype');

		foreach($merchant_pay_types as $merchant_pay_type) {
			$paytypesarray['ID'] = $merchant_pay_type; 
			$paytypesarray['name'] = $paytypes[$merchant_pay_type]; 
			$paytypearray[] = $paytypesarray;
	    }
		return	$payload = array('status'=>'1','payment_names' => $paytypearray);

	}
	public function confirmpayment($val)
	{
		if(!empty($val['payment_method']) && !empty($val['paidstatus']) && !empty($val['order_id']))
		{
			$orderDetails = Orders::findOne($val['order_id']);
			if(!empty($orderDetails)){
				$orderDetails->paymenttype = $val['payment_method'];
				$orderDetails->paidstatus = $val['paidstatus'];
				$orderDetails->closed_by = $val['header_user_id'];
				$orderDetails->save();
				$payload = ['status' => '1', 'message' => 'Payment Status Updated Successfully'];
			}
			else{
				$payload = ['status' => '0', 'message' => 'Please Provide Valid Order Details'];	
			}
		}
		else{
			$payload = ['status' => '0', 'message' => 'Please Provide Required Parameters'];
		}
		return $payload;
	}

	public function pilotFeedback($val){

       $sqlfeedbackrating = "select mfr.factor_id ID,avg(rating) as rating from feedback f
                                inner join pilot_factor_rating mfr on f.ID = mfr.feedback_id
                                where f.pilot_id =  '".$val['header_user_id']."' group by mfr.factor_id";
        $feedbackFactorRating = Yii::$app->db->createCommand($sqlfeedbackrating)->queryAll();
        $factorRatingArray =  array_column($feedbackFactorRating,'rating');
        if(!empty($factorRatingArray)){
            $overAllRating = array_sum($factorRatingArray)/count($factorRatingArray);
        }
        else{
            $overAllRating = 0.00;
        }
        $pilotFactors = PilotFactorRating::FACTORS;
        $singleFactor = $factorRating = [];
        $polishedFactorArray = array_column($feedbackFactorRating,'rating','ID');
        foreach($pilotFactors as $key => $value){
            $singleFactor['name'] =    $value;
            $singleFactor['rating'] = !empty(@$polishedFactorArray[$key]) ? $polishedFactorArray[$key]  : 0;
            $factorRating[] = $singleFactor;
        }

        return $payload = ['feedbackFactorRating' => $factorRating
            ,'overAllRating' => $overAllRating];
    }
}
?>