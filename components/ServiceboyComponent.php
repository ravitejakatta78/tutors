<?php
namespace app\components;
use yii;
use yii\base\Component;
use \app\helpers\Utility;
use app\models\Product;

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
					  
					$jwt = base64_encode($row['ID']);	 

						
							$userwherearray['ID'] = $row['ID']; 
						$userarray['loginaccess'] = '1';

						$sqlUpdate = 'update serviceboy set loginaccess = \''.$userarray['loginaccess'].'\',loginstatus=\'1\' where ID = \''.$userwherearray['ID'].'\'';
						$result = Yii::$app->db->createCommand($sqlUpdate)->execute();
						 $payload = array("status" => '1',"text" =>"","usersid" => $row['ID'],"merchant_id" => $row['merchant_id']);
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
				$sqluserdetails = "select * from serviceboy where ID = '".$usersid."'";
				$userdetails = Yii::$app->db->createCommand($sqluserdetails)->queryOne();
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
			$sqlrow = "SELECT * FROM users WHERE email = '".$userarray['email']."'";
			$row = Yii::$app->db->createCommand($sqlrow)->queryOne();
			if(empty($row['ID'])){
				$sqlrow = "SELECT * FROM users WHERE mobile = '".$userarray['mobile']."'";
				$row = Yii::$app->db->createCommand($sqlrow)->queryOne(); 
				if(empty($row['ID'])){
					$result = new \app\models\Users;
					$result->attributes = $userarray;
					

					//$result = insertQuery($userarray,'users');
					if($result->save()){
						$message = "Hi ".$userarray['name']." ".$userarray['otp']." is your otp for verification.";
						 \app\helpers\Utility::otp_sms($userarray['mobile'],$message);
							$sqluserdetails = "select ID from users where unique_id = '".$userarray['unique_id']."'";
							$userdetails = Yii::$app->db->createCommand($sqluserdetails)->queryOne();
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
				$sqluserdetails = "select * from serviceboy where ID = '".$usersid."'";
				$userdetails = Yii::$app->db->createCommand($sqluserdetails)->queryOne();
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
				$sqluserdetails = "select ID,otp from serviceboy where ID = '".$usersid."'";
				$userdetails = Yii::$app->db->createCommand($sqluserdetails)->queryOne();
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
		  $sqlrow = "SELECT * FROM serviceboy WHERE ID = '".$customer_id."'";
		  $row = Yii::$app->db->createCommand($sqlrow)->queryOne();
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
		  $sqlrow = "SELECT * FROM serviceboy WHERE ID = '".$customer_id."'";
		  $row = Yii::$app->db->createCommand($sqlrow)->queryOne();
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
				$sqlloginstatus = "select ID,loginstatus,push_id from serviceboy where ID = '".$usersid."'";
				$loginstatus = Yii::$app->db->createCommand($sqlloginstatus)->queryOne();
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
			  $sqlrow = "SELECT * FROM serviceboy WHERE ID = '".$val['usersid']."' and status = '1'";
			  $row = Yii::$app->db->createCommand($sqlrow)->queryOne();
			  $sqlmerchant = "SELECT * FROM merchant WHERE ID = '".$row['merchant_id']."' and status = '1'";
			  $merchant = Yii::$app->db->createCommand($sqlmerchant)->queryOne();
			  $sqltotalorders = "SELECT count(*) as count FROM orders WHERE merchant_id = '".$row['merchant_id']."' 
			  and serviceboy_id = '".$row['ID']."'";
			  $restotalorders = Yii::$app->db->createCommand($sqltotalorders)->queryOne();
			  $totalorders = $restotalorders['count'];
			  $sqltodayorders = "SELECT sum(case when (orderprocess != '4' and orderprocess != '3') then 1 else 0 end) rununing_orders
			  ,sum(case when orderprocess = '4' then 1 else 0 end) completed_orders,
			  count(*) as count,sum(case when  (paymenttype = 'cash' or paymenttype = '1') then totalamount else 0 end) OfflinePay
			  ,sum( case when (paymenttype != 'cash' and paymenttype != '1') then totalamount else 0 end) OnlinePay,sum(tips) tips
			  FROM orders WHERE merchant_id = '".$row['merchant_id']."' and serviceboy_id = '".$row['ID']."' 
			  and reg_date>='".$date." 00:00:00' and reg_date<='".$date." 23:59:59'";
			  $restodayorders = Yii::$app->db->createCommand($sqltodayorders)->queryOne();
			  $todayorders = $restodayorders['count'];
			  $sqltotalamount = "SELECT sum(totalamount) as amount FROM orders WHERE merchant_id = '".$row['merchant_id']."' 
			  and serviceboy_id = '".$row['ID']."'";
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
				  
					$payload = array("status"=>'1',"users"=>$customerdetails);
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
	    	    	    	    		 Yii::debug('===notificationlist parameters==='.json_encode($val));
		$sqlserviceboydetails = 'select * from serviceboy where ID = \''.$val['header_user_id'].'\'';
		$serviceboydetails = Yii::$app->db->createCommand($sqlserviceboydetails)->queryOne();
		$date = date('Y-m-d');  
					$sqlorderlistarray = "select * from serviceboy_notifications where merchant_id = '".$serviceboydetails['merchant_id']."' and serviceboy_id = '".$serviceboydetails['ID']."' and reg_date >= '".$date." 00:00:00' and reg_date <= '".$date." 23:59:59' 
					union select * from serviceboy_notifications where merchant_id = '".$serviceboydetails['merchant_id']."' and reg_date >= '".$date." 00:00:00' and reg_date <= '".$date." 23:59:59' 
					and ordertype = 'new' and serviceboy_id = '0' order by ID desc";
					$orderlistarray = Yii::$app->db->createCommand($sqlorderlistarray)->queryAll();
					$sqlunseennotifications = "select ID from serviceboy_notifications where merchant_id = '".$serviceboydetails['merchant_id']."' and serviceboy_id = '".$serviceboydetails['ID']."' and seen = '0' and reg_date >= '".$date." 00:00:00' and reg_date <= '".$date." 23:59:59' 
					union  select ID from serviceboy_notifications where merchant_id = '".$serviceboydetails['merchant_id']."' and seen = '0' 
					and reg_date >= '".$date." 00:00:00' and reg_date <= '".$date." 23:59:59' and ordertype = 'new' and serviceboy_id = '0'";
					$unseennotifications = Yii::$app->db->createCommand($sqlunseennotifications)->queryAll();
					
					$unseennotifications = !empty($unseennotifications) ? count($unseennotifications) : 0;
					if(!empty($orderlistarray)){
					$orderarray = $totalordersarray = array();
					foreach($orderlistarray as $orderlist){
						$totalproductaarray = array();
						$orderarray['id'] =  $orderlist['ID'];
						$orderarray['order_id'] =  $orderlist['order_id'];
						$orderarray['title'] =  $orderlist['title']; 
						$orderarray['message'] =  $orderlist['message']; 
						$orderarray['seen'] =  $orderlist['seen'];  
						$orderarray['regdate'] =  date('d M Y H:i:s',strtotime($orderlist['reg_date'])); 
					$totalordersarray[] = $orderarray;
					}
				 	$payload = array('status'=>'1','count'=>$unseennotifications,'orders'=>$totalordersarray);  
					}else{
					$payload = array('status'=>'0','message'=>'Notification not found!!');
					}
		return $payload;
	}
	public function seenstatus($val){
	    
	    	    	    	    		 Yii::debug('===seenstatus parameters==='.json_encode($val));
		$sqlserviceboydetails = 'select * from serviceboy where ID = \''.$val['header_user_id'].'\'';
		$serviceboydetails = Yii::$app->db->createCommand($sqlserviceboydetails)->queryOne();	
		
		if(!empty($serviceboydetails['ID'])&&!empty($serviceboydetails['merchant_id'])){
					$sqlresult =	"update serviceboy_notifications set seen = '1' where merchant_id = '".$serviceboydetails['merchant_id']."' and ordertype = 'new'";
					$resultupdate = Yii::$app->db->createCommand($sqlresult)->execute();
					$sqlresult1 =	"update serviceboy_notifications set seen = '1' where serviceboy_id = '".$serviceboydetails['ID']."'"; 
					$result = Yii::$app->db->createCommand($sqlresult1)->execute();
					
					
					  
						$payload = array('status'=>'1','message'=>'Data Updated');
						
					 }else{
					$payload = array('status'=>'0','message'=>'Service boy not found.');
					 }
					 
					 return $payload;
	}
	public function neworders($val){
		$sqlserviceboydetails = 'select * from serviceboy where ID = \''.$val['header_user_id'].'\'';
		$serviceboydetails = Yii::$app->db->createCommand($sqlserviceboydetails)->queryOne();			
	$date = date('Y-m-d');
					$sqlorderlistarray = 'select * from orders where merchant_id = \''.$serviceboydetails['merchant_id'].'\' and date(reg_date) = \''.$date.'\'   and orderprocess = \'0\'' ;
					$orderlistarray = Yii::$app->db->createCommand($sqlorderlistarray)->queryAll();
					if(!empty($orderlistarray)){
					$orderarray = $totalordersarray = array();
					foreach($orderlistarray as $orderlist){
						$totalproductaarray = array();
						$orderarray['order_id'] =  $orderlist['ID'];
						$orderarray['unique_id'] =  $orderlist['order_id']; 
						$orderarray['username'] = Utility::user_details($orderlist['user_id'],"name");
						$orderarray['storename'] = Utility::merchant_details($orderlist['merchant_id'],"storename");
						$orderarray['tablename'] = Utility::table_details($orderlist['tablename'],"name"); 
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
						$orderarray['userprofilepic'] = !empty($orderlist['user_id']) ? \app\helpers\Utility::user_image($orderlist['user_id']) : '';

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
	    	    	    		 Yii::debug('===orderswithstatus==='.json_encode($val));

	    
		$sqlserviceboydetails = 'select * from serviceboy where ID = \''.$val['header_user_id'].'\'';
		$serviceboydetails = Yii::$app->db->createCommand($sqlserviceboydetails)->queryOne();	
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
					
					/*$sqlpaidorderlistarray = 'select * from orders where merchant_id = \''.$serviceboydetails['merchant_id'].'\' and date(reg_date) = \''.$date.'\' and orderprocess IN (\'1\',\'2\',\'4\') and paidstatus = \'1\' 
					and serviceboy_id = \''.$serviceboydetails['ID'].'\' order by ID desc';
					$paidorderlistarray = Yii::$app->db->createCommand($sqlpaidorderlistarray)->queryAll();
					*/
					$orderlistarray  = $notpaidorderlistarray ;//array_merge($notpaidorderlistarray,$paidorderlistarray);
					if(!empty($orderlistarray)){
					$orderarray = $totalordersarray = array();
					foreach($orderlistarray as $orderlist){
						$totalproductaarray = array();
						$feedbackDet = \app\models\Feedback::find()->where(['order_id'=>$orderlist['order_id']])->asArray()->one();
						$orderarray['order_id'] =  $orderlist['ID'];
						$orderarray['unique_id'] =  $orderlist['order_id']; 
						$orderarray['username'] = Utility::user_details($orderlist['user_id'],"name");
						$orderarray['storename'] = Utility::merchant_details($orderlist['merchant_id'],"storename");
						$orderarray['tablename'] = Utility::table_details($orderlist['tablename'],"name"); 
						$orderarray['amount'] =  $orderlist['amount']; 
						$orderarray['tax'] = (string)(!empty($orderlist['tax']) ?  $orderlist['tax'] : 0);
						$orderarray['tips'] = (string)(!empty($orderlist['tips']) ?  $orderlist['tips'] : 0);
						$orderarray['subscription'] = (string)(!empty($orderlist['subscription']) ?   $orderlist['subscription'] : 0);
						$orderarray['couponamount'] = (string)(!empty($orderlist['couponamount']) ?  $orderlist['couponamount'] : 0);
						$orderarray['totalamount'] =  (string)(!empty($orderlist['totalamount']) ?   $orderlist['totalamount'] : 0);
						$orderarray['paymenttype'] =  $orderlist['paymenttype']=='cash' ? 'Cash' : 'Online';
						$orderarray['orderprocess'] =  $orderlist['orderprocess']; 
						$orderarray['paidstatus'] =  $orderlist['paidstatus']; 
						$orderarray['orderline'] =  $orderlist['orderline']; 
						$orderarray['reorder'] =  $orderlist['reorderprocess']; 
						$orderarray['preparetime'] = $orderlist['preparetime']; 
						$orderarray['updatedtime'] = $orderlist['mod_date'];
						$orderarray['preparedate'] = $orderlist['preparedate'];
						$orderarray['merchant_id'] = $orderlist['merchant_id'];
						
						$orderarray['instructions'] = $orderlist['instructions'];
						$orderarray['tax'] = $orderlist['tax']; 
						$orderarray['discount_number'] = $orderlist['discount_number'];
						$orderarray['userprofilepic'] = !empty($orderlist['user_id']) ? \app\helpers\Utility::user_image($orderlist['user_id']) : '';
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
						
						
						$productaarray['name'] = Utility::product_details($orderproduct['product_id'],'title');
						 

						if(!empty(@$productDet['food_category_quantity'])){
						    $productaarray['foodqtyname'] = Utility::foodcategory_type($productDet['food_category_quantity']);
						} else{
						    $productaarray['foodqtyname'] = '';
						}
						$productaarray['count'] = $orderproduct['count'];
						$productaarray['price'] = $orderproduct['price'];
						$productaarray['reorder'] = $orderproduct['reorder']; 
						
						
						
						$productaarray['productimage'] = !empty($productDet['image']) ? MERCHANT_PRODUCT_URL.$productDet['image'] : '';

						
						$totalproductaarray[] = $productaarray;
							}
						}
						$orderarray['products'] =  array_filter($totalproductaarray);
						$orderarray['reg_date'] =  date('Y-m-d h:i:s A',strtotime($orderlist['reg_date']) ); 
						$orderarray['regdate'] =  date('h:i A',strtotime($orderlist['reg_date']) ); 
						$orderarray['acceptedtime'] =  date('h:i A',strtotime($orderlist['preparedate']) ); 
						$orderarray['pilot_rating'] = !empty($feedbackDet['pilot_rating']) ? $feedbackDet['pilot_rating'] : '';
						$orderarray['pilot_message'] = !empty($feedbackDet['pilot_message']) ? $feedbackDet['pilot_message'] : '';
						
						$totalordersarray[] = $orderarray;
					}
				 	$payload = array('status'=>'1','orders'=>$totalordersarray); 
					}else{
					$payload = array('status'=>'1','message'=>'Order not found!!','orders' => []);
					}
		return $payload;
	}
	public function order($val)
	{
		$date = date('Y-m-d');
					$orderid = $val['orderid'];
					$sqlorderlist = 'select * from orders where ID = \''.$orderid.'\' order by ID desc';
					$orderlist = Yii::$app->db->createCommand($sqlorderlist)->queryOne();
					$sqlmerchantdetails = 'select * from merchant where ID = \''.$orderlist['merchant_id'].'\'
					and status = \'1\'';
					$merchantdetails = Yii::$app->db->createCommand($sqlmerchantdetails)->queryOne();
					if(!empty($orderlist)){
					$orderarray = $totalordersarray = array(); 
						$totalproductaarray = array();
						$orderarray['order_id'] =  $orderlist['ID'];
						$orderarray['merchant_id'] =  $orderlist['merchant_id'];
						$orderarray['unique_id'] =  $orderlist['order_id']; 
						$orderarray['username'] = Utility::user_details($orderlist['user_id'],"name");
						$orderarray['storename'] =  !empty($merchantdetails['storename']) ? $merchantdetails['storename'] : '';
						$orderarray['tablename'] = Utility::table_details($orderlist['tablename'],"name"); 
						$orderarray['logo'] = !empty($merchantdetails['logo']) ? MERCHANT_LOGO.$merchantdetails['logo'] : '';
						$orderarray['coverpic'] = !empty($merchantdetails['coverpic']) ? MERCHANT_LOGO.$merchantdetails['coverpic'] : '';
						$orderarray['amount'] =  sprintf("%.2f", (!empty($orderlist['tax']) ? $orderlist['amount'] : 0));; 
						$orderarray['tax'] = sprintf("%.2f", (!empty($orderlist['tax']) ?  $orderlist['tax'] : 0));
						$orderarray['tips'] = sprintf("%.2f", (!empty($orderlist['tips']) ?  $orderlist['tips'] : 0));
						$orderarray['subscription'] = sprintf("%.2f", (!empty($orderlist['subscription']) ?   $orderlist['subscription'] : 0));
						$orderarray['couponamount'] = sprintf("%.2f", (!empty($orderlist['couponamount']) ?  $orderlist['couponamount'] : 0));
						$orderarray['totalamount'] =  sprintf("%.2f", (!empty($orderlist['totalamount']) ?   $orderlist['totalamount'] : 0));;
						$orderarray['paymenttype'] =  $orderlist['paymenttype']=='cash' ? 'Cash' : 'Online';
						$orderarray['orderprocess'] =  $orderlist['orderprocess']; 
						$orderarray['orderprocesstext'] =  Utility::orderstatus_details($orderlist['orderprocess']); 
						$orderarray['paidstatus'] =  Utility::status_details($orderlist['paidstatus']); 
						$orderarray['reorder'] =  $orderlist['reorderprocess'];
						$orderarray['reg_date'] =  date('Y-m-d h:i:s A',strtotime($orderlist['reg_date']) ); 
						$orderarray['preparedate'] =  $orderlist['preparedate'];
						$orderarray['preparetime'] = $orderlist['preparetime']; 
						$orderarray['orderline'] =  $orderlist['orderline'];
						$orderarray['instructions'] =  $orderlist['instructions'];
						$orderarray['discount_number'] =  sprintf("%.2f", (!empty($orderlist['discount_number']) ?   $orderlist['discount_number'] : 0));;;
						$sqlpendingamount = "select sum(totalamount) as pendingamount from order_transactions 
						where order_id = '".$orderlist['ID']."' and merchant_id = '".$orderlist['merchant_id']."' 
						and user_id = '".$orderlist['user_id']."' and paymenttype = 'cash' and paidstatus = '0'";
						$pendingamount = Yii::$app->db->createCommand($sqlpendingamount)->queryOne();
							$orderarray['pendingamount'] =  sprintf("%.2f", $pendingamount['pendingamount'] ?: 0.00); 
						if($orderarray['pendingamount']>0){
						$orderarray['paidstatus'] =  '0'; 
						}
						$sqlorderproducts = "select * from order_products where order_id = '".$orderlist['ID']."' 
						and merchant_id = '".$orderlist['merchant_id']."' and user_id = '".$orderlist['user_id']."' order by inc desc";
						$orderproducts = Yii::$app->db->createCommand($sqlorderproducts)->queryAll();
						
						if(count($orderproducts) > 0){
						    
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
				 	$payload = array('status'=>'1','orders'=>$orderarray); 
					}else{
					$payload = array('status'=>'0','message'=>'Order not found!!');
					}
		return $payload;
	}
	public function acceptorder($val)
	{
		$sqlserviceboydetails = 'select * from serviceboy where ID = \''.$val['header_user_id'].'\'';
		$serviceboydetails = Yii::$app->db->createCommand($sqlserviceboydetails)->queryOne();
		$date = date('Y-m-d');
					$orderid = $val['orderid'];
					$preparetime = $val['preparetime'];
					$sqlorderlist = 'select * from orders where ID = \''.$orderid.'\'';
					$orderlist = Yii::$app->db->createCommand($sqlorderlist)->queryOne();
					if(!empty($orderlist)){
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
						$sqluserdetails = "select * from users where ID = '".$orderlist['user_id']."'";
						$userdetails = Yii::$app->db->createCommand($sqluserdetails)->queryOne();
						if(!empty($userdetails['push_id'])){	
						$title = 'Your order has been accepted';
						$image = '';
						$message = "Hey ".ucwords($userdetails['name']).", ".$serviceboydetails['name']." has been Accepted your order.";			
						//Utility::sendNewFCM($userdetails['push_id'],$title,$message,$image,null,null,$orderid);	
    					$notificationdet = ['type' => 'ORDER_ACCEPTED','username' => $userdetails['name']];
						\app\helpers\Utility::sendNewFCM($userdetails['push_id'],$title,$message,$image,'6',null,$orderdetails['ID'],$notificationdet); 

						}
						
						$sqlserviceboyarray = 'select * from serviceboy where merchant_id = \''.$orderlist['merchant_id'].'\' 
						and loginstatus = \'1\' and push_id is not null and ID != \''.$val['header_user_id'].'\' order by ID desc';
						$serviceboyarray = Yii::$app->db->createCommand($sqlserviceboyarray)->queryAll();
						
											if(!empty($serviceboyarray)){
												$stitle = 'Order Conformation';
												$smessage = $orderlist['order_id'].' is accepted by '.Utility::serviceboy_details($roderarray['serviceboy_id'],'name');
												$simage = '';
												foreach($serviceboyarray as $serviceboy){ 
													\app\helpers\Utility::sendNewFCM($serviceboy['push_id'],$stitle,$smessage,$simage,null,null,$orderid); 
												}
											}
						
								$payload = array('status'=>'1','message'=>'Order has been accepted');
						}else{
						$payload = array('status'=>'1','message'=>'Request already accepted');
						}
					}else{
					$payload = array('status'=>'0','message'=>'Order not found!!');
					}
		return $payload;
	}
	public function serveorder($val){
	    			$orderid = $val['orderid'];
	    			$order_details = \app\models\Orders::findOne($orderid);
	    			if(!empty($order_details)){
	    			    $order_details->orderprocess = '2';
	    			    $order_details->save();
								$payload = array('status'=>'1','message'=>'Order Served Successfully');
	    			    
	    			}else{
	    			    $payload = array('status'=>'0','message'=>'Order not found!!');
	    			}
	    			return $payload;
	}
	public function prepareorder($val)
	{
	    		$sqlserviceboydetails = 'select * from serviceboy where ID = \''.$val['header_user_id'].'\'';
		$serviceboydetails = Yii::$app->db->createCommand($sqlserviceboydetails)->queryOne();
		$date = date('Y-m-d');
					$orderid = $val['orderid'];
					$preparetime = $val['preparetime'];
					$sqlorderlist = 'select * from orders where ID = \''.$orderid.'\'';
					$orderlist = Yii::$app->db->createCommand($sqlorderlist)->queryOne();
					if(!empty($orderlist)){
					    if(!empty($preparetime)){

					     $sqlUpdate = 'update orders set preparetime = \''.$preparetime.'\',mod_date=\''.date('Y-m-d H:i:s').'\'	where ID = \''.$orderid.'\'';
						 $result = Yii::$app->db->createCommand($sqlUpdate)->execute();
						 if(!empty(@$orderlist['user_id'])){
						     $userDet = \app\models\Users::findOne($orderlist['user_id']);
						     $title = 'Order';
						     $message = 'Order will be served in '.$preparetime.' minutes';
						     \app\helpers\Utility::sendNewFCM($userdetails['push_id'],$title,$message,null,null,null,$orderlist['ID']);

						 }
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
	    		$sqlserviceboydetails = 'select * from serviceboy where ID = \''.$val['header_user_id'].'\'';
		$serviceboydetails = Yii::$app->db->createCommand($sqlserviceboydetails)->queryOne();
		$date = date('Y-m-d');
					$orderid = $val['orderid'];
					$preparedate = $val['preparedate'];
					$sqlorderlist = 'select * from orders where ID = \''.$orderid.'\'';
					$orderlist = Yii::$app->db->createCommand($sqlorderlist)->queryOne();
					if(!empty($orderlist)){
					    if(!empty($preparedate)){

					     $sqlUpdate = 'update orders set preparedate = \''.$preparedate.'\'	where ID = \''.$orderid.'\'';
						 $result = Yii::$app->db->createCommand($sqlUpdate)->execute();
						 
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
	        $orderdet = \app\models\Orders::findOne($orderid);

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
	    Yii::debug('===reject order parameters==='.json_encode($val));
		$sqlserviceboydetails = 'select * from serviceboy where ID = \''.$val['header_user_id'].'\'';
		$serviceboydetails = Yii::$app->db->createCommand($sqlserviceboydetails)->queryOne();
		$date = date('Y-m-d');
					$orderid = $val['orderid'];
					$sqlorderlist = 'select * from orders where ID = \''.$orderid.'\'';
					$orderlist = Yii::$app->db->createCommand($sqlorderlist)->queryOne(); 
					if(!empty($orderlist)){ 
                            $sqlorderpush = "select sum(case when status = 3 then 1 else 0 end) reject_count,count(ID)  order_sent_count from order_push_pilot where merchant_id = '".$orderlist['merchant_id']."' and order_id = '".$orderlist['ID']."'";
                            $resorderpush = Yii::$app->db->createCommand($sqlorderpush)->queryOne();
	                        if((!empty($sqlorderpush) &&  ($resorderpush['reject_count']+1) == $resorderpush['order_sent_count'] && $val['cancelconfirm'] == 1) || $val['cancelconfirm'] == 3){
	                        
							$roderarray = $roderwharray=  array();
							$roderwharray['ID'] = $orderid;  
							$roderarray['orderprocess'] = 3;
							$roderarray['serviceboy_id'] = $serviceboydetails['ID']; 
							//updateQuery($roderarray,'orders',$roderwharray);
							$sqlUpdate = 'update orders set orderprocess = \''.$roderarray['orderprocess'].'\',serviceboy_id = \''.$roderarray['serviceboy_id'].'\'
							,cancel_reason = \''.$val['cancel_reason'].'\' where ID = \''.$roderwharray['ID'].'\'';
							$result = Yii::$app->db->createCommand($sqlUpdate)->execute();
							$sqluserdetails = "select * from users where ID = '".$orderlist['user_id']."'";
							$userdetails = Yii::$app->db->createCommand($sqluserdetails)->queryOne();
							if(!empty($userdetails['push_id'])){	
							$message = "Hey ".ucwords($userdetails['name']).", Your Order has been Rejected. Sorry for inconvenience.";	
							$title = 'Order has been cancelled';
							$image = '';
							
							Utility::sendNewFCM($userdetails['push_id'],$title,$message,$image,null,null,$orderid);			
							}
							$table_status = null;
			                $current_order_id = 0;
	                        $tableUpdate = \app\models\Tablename::findOne($orderlist['tablename']);
							$tableUpdate->table_status = $table_status;
		                    $tableUpdate->current_order_id = $current_order_id;
		                	$tableUpdate->save();
		                	    
		                	
							$payload = array('status'=>'1','message'=>'Order has been Cancelled');
	                        }else if(!empty($sqlorderpush) &&  ($resorderpush['reject_count']+1) == $resorderpush['order_sent_count'] && $val['cancelconfirm'] == 2){
					            
					            $payload = array('status'=>'1','message'=>'If we reject the order it will auto cancel !!');
					    }
					    else{
    					     $OrderPushPilotDet =   \app\models\OrderPushPilot::find()
                                    ->where(['merchant_id' => $orderlist['merchant_id'],'order_id' => $orderlist['ID']])
    	                            ->One();
    	                            if(!empty($OrderPushPilotDet)){
    	                                $OrderPushPilotDet->status = 3;
    	                                $OrderPushPilotDet->save();    	                                
    	                            }

                                    $orderrejmodel = new \app\models\OrderRejections;
                                    $orderrejmodel->order_id = $orderid;
                                    $orderrejmodel->rejected_by = $val['header_user_id'];
                                    $orderrejmodel->rejection_reason = $val['cancel_reason'];
                                    $orderrejmodel->created_on = date('Y-m-d H:i:s');
                                    $orderrejmodel->reg_date = date('Y-m-d');
                                    $orderrejmodel->save();
        							$payload = array('status'=>'1','message'=>'Order has been Rejected');

					    }
					}else{
					$payload = array('status'=>'0','message'=>'Order not found!!');
					}
		return $payload;
	}
	public function deliverorder($val)
	{
	    Yii::trace("========delivery order=======".json_encode($val));
		$sqlserviceboydetails = 'select * from serviceboy where ID = \''.$val['header_user_id'].'\'';
		$serviceboydetails = Yii::$app->db->createCommand($sqlserviceboydetails)->queryOne();
		$date = date('Y-m-d');
					$orderid = $val['orderid'];
					$sqlorderlist = 'select * from orders where ID = \''.$orderid.'\'';
					$orderlist = Yii::$app->db->createCommand($sqlorderlist)->queryOne(); 
					if(!empty($orderlist)){ 
						$roderarray = $roderwharray=  array();
						$roderwharray['ID'] = $orderid;  
						$roderarray['reorderprocess'] = 0;
						$roderarray['orderprocess'] = 4;
						$roderarray['closed_by'] = $serviceboydetails['ID'];
						$roderarray['serviceboy_id'] = $serviceboydetails['ID'];
						$roderarray['paymenttype'] = $val['paymenttype'];
						//updateQuery($roderarray,'orders',$roderwharray);
						$sqlUpdate = 'update orders set reorderprocess = \''.$roderarray['reorderprocess'].'\'
						,orderprocess=\''.$roderarray['orderprocess'].'\',serviceboy_id=\''.$roderarray['serviceboy_id'].'\'
						,paymenttype=\''.$roderarray['paymenttype'].'\',closed_by = \''.$roderarray['closed_by'].'\'  where ID =\''.$roderwharray['ID'].'\'';
						$resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute();
					
					$sqlresult = "update order_products set reorder = '0' where order_id = '".$orderlist['ID']."'";
					$result =  Yii::$app->db->createCommand($sqlresult)->execute();
						$sqluserdetails = "select * from users where ID = '".$orderlist['user_id']."'";	
						$userdetails = Yii::$app->db->createCommand($sqluserdetails)->queryOne();
						if(!empty($userdetails['push_id'])){	
							$title = 'Pick your order';
							$image = '';
							$message = "Hey ".ucwords($userdetails['name']).", Your Order has been Served. Take it with smile";			
							Utility::sendNewFCM($userdetails['push_id'],$title,$message,$image,null,null,$orderid);
						}
						$coinstransactions = array();
						$coinsAdd = round(($orderlist['amount'] - $orderlist['coupanamt'] ) /20,0);
						$coinstransactions['user_id'] = $userdetails['ID'];
						$coinstransactions['txn_id'] = Utility::coinstxn_id();
						$coinstransactions['order_id'] = $orderlist['ID'];
						$coinstransactions['merchant_id'] = $serviceboydetails['merchant_id'];
						$coinstransactions['coins'] =  $coinsAdd;
						$coinstransactions['type'] = 'Credit';
						$coinstransactions['reward_id'] = 0;
						$coinstransactions['rewardcoupon_id'] = 0;
						$coinstransactions['reason'] = $coinsAdd." coins added to your wallet for the order.";
						
						$result = new \app\models\CoinsTransactions;
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
			                
						$tableUpdate = \app\models\Tablename::findOne($orderlist['tablename']);
							$tableUpdate->table_status = $table_status;
		                    $tableUpdate->current_order_id = $current_order_id;
		                	$tableUpdate->save();
							
						$payload = array('status'=>'1','message'=>'Order has been delivered.');
						 
					}else{
					$payload = array('status'=>'0','message'=>'Order not found!!');
					}
		return $payload;
	}
	public function paidstatus($val)
	{
		$sqlserviceboydetails = 'select * from serviceboy where ID = \''.$val['header_user_id'].'\'';
		$serviceboydetails = Yii::$app->db->createCommand($sqlserviceboydetails)->queryOne();
		$orderid = $val['orderid'];
					$sqlorderlist = 'select * from orders where ID = \''.$orderid.'\'';
					$orderlist = Yii::$app->db->createCommand($sqlorderlist)->queryOne();
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
						$sqluserdetails = "select * from users where ID = '".$orderlist['user_id']."'";
						$userdetails = Yii::$app->db->createCommand($sqluserdetails)->queryOne();
						if(!empty($userdetails['push_id'])){	
						$message = "Hey ".ucwords($userdetails['name']).", Thank you for making payment.";	
						$title = 'Amount recevied.';
						$image = '';
						Utility::sendNewFCM($userdetails['push_id'],$title,$message,$image,null,null,$orderid);			
						}
							$payload = array('status'=>'1','message'=>'Payment status updated.');
					}else{
					$payload = array('status'=>'0','message'=>'Order not found!!');
					}
		return $payload;
	}
	public function orderslist($val)
	{
		$sqlserviceboydetails = 'select * from serviceboy where ID = \''.$val['header_user_id'].'\'';
		$serviceboydetails = Yii::$app->db->createCommand($sqlserviceboydetails)->queryOne();
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
	public function qrcodepilot($val)
	{

	    		 Yii::debug('===qrcode parameters==='.json_encode($val));
		
						$userwherearray = $userarray = array();
						
						if ( strstr( $val['enckey'], 'foodqonline' ) ) {
                            $pos = explode('?',$val['enckey']);
                            parse_str($pos[1], $outputencarray);
                            $enckey = \app\helpers\Utility::decrypt($outputencarray['enckey']);
                        } else {
						    $enckey = \app\helpers\Utility::decrypt(trim($val['enckey']));
                        }
						

    					 $foodtype = trim($val['foodtype']) ?: 0;
						 $latfrom = trim($val['lat']) ?: 0;
						 $lngfrom = trim($val['lng']) ?: 0;
						/* $enckey = trim($_POST['enckey']);*/ 
								$merchantid = $val['merchant_id'];
								
								$sqlmerchantdetails = 'select * from merchant where ID =  \''.$merchantid.'\' and status = \'1\'';
								$merchantdetails = Yii::$app->db->createCommand($sqlmerchantdetails)->queryOne();
						
							if(!empty($merchantdetails)){
									$sqlSections = 'select s.ID section_id,s.section_name from sections s 
									inner join tablename tn on s.ID = tn.section_id
									where tn.merchant_id = \''.$merchantdetails['ID'].'\' and s.ID = \''.$val['section_id'].'\'';
									$resSections = Yii::$app->db->createCommand($sqlSections)->queryAll();

									if(!empty($resSections)){
		$sqlproductDetails = 'select P.ID,fs.ID food_section_id,fs.food_section_name,P.title,P.food_category_quantity,P.price,P.image
		,fc.food_category,fc.ID  food_category_ID
		,case when food_type_name is not null then concat(title ,\' (\' , food_type_name , \')\') else title end  title_quantity  
		,P.item_type
		from product P 
		left join food_categeries fc on fc.ID = P.foodtype  
		left join food_sections fs on fc.food_section_id =  fs.ID
		left join food_category_types fct on fct.ID =  P.food_category_quantity 
		and fct.merchant_id =  \''.$val['merchant_id'].'\'
		where P.merchant_id = \''.$val['merchant_id'].'\' ';
		if(!empty($val['term']) && isset($val['term'])){
		$sqlproductDetails .= ' and (title like \''.'%'.$val['term'].'%'.'\' or fs.food_section_name like \''.'%'.$val['term'].'%'.'\' )';
		}
		if(!empty($val['isVeg'])){
		    $sqlproductDetails .= ' and item_type = \''.$val['isVeg'].'\'';
		}
		$productDetails = Yii::$app->db->createCommand($sqlproductDetails)->queryAll();
		$foodSectionArr =  array_values(array_unique(array_filter(array_column($productDetails,'food_section_id'))));
		$fsNameArr = array_column($productDetails,'food_section_name','food_section_id');
		$titleNameArr = array_column($productDetails,'title_quantity','ID');
		
		$sqlfoodCategories = 'select * from food_categeries where merchant_id = \''.$val['merchant_id'].'\'';
		$foodCategories = Yii::$app->db->createCommand($sqlfoodCategories)->queryAll();
		
		$sqlProducts = 'select * from product where merchant_id = \''.$val['merchant_id'].'\' and status = \'1\'';
		$resproducts = Yii::$app->db->createCommand($sqlProducts)->queryAll();
		
		$productsIndexArr = \yii\helpers\ArrayHelper::index($resproducts, 'ID');
		

		
		$fcArr = array_column($foodCategories,'food_category','ID'); 
		

		for($i=0;$i<count($productDetails);$i++)
		{
				$fsCatArr[$productDetails[$i]['food_section_id']][$i] = $productDetails[$i]['food_category_ID'];
		        $fcProdArr[$productDetails[$i]['food_category_ID']][$i] = $productDetails[$i]['ID'];
		}
	
	//return print_r($fcProdArr);
$getproducts = array();
		if(!empty($foodSectionArr)){
		    for($fs = 0;$fs <count($foodSectionArr);$fs++){
		        
		        $fcId = array_values(array_unique($fsCatArr[$foodSectionArr[$fs]]));
		         
		        for($fc =0; $fc < count($fcId) ; $fc++){
		            $foodCatArr[$fc]['id'] = $fcId[$fc];
		            $foodCatArr[$fc]['name'] = $fcArr[$fcId[$fc]];
		            //echo $fcId[$fc]."<br>";
		            $prodIDArr = array_values($fcProdArr[$fcId[$fc]]);
		            //print_r($prodIDArr); 
		            $prodArr = array();
					for($fp=0;$fp<count($prodIDArr);$fp++){
					$sqlSectionPrice = 'select * from section_item_price_list 
					        where merchant_id= \''.$merchantdetails['ID'].'\' and item_id = \''.$prodIDArr[$fp].'\' 
					        and section_id = \''.$val['section_id'].'\'';
					        $resSectionPrice = Yii::$app->db->createCommand($sqlSectionPrice)->queryOne();					
						
						
		                $prodArr[$fp]['id'] = $prodIDArr[$fp]; 
		                $prodArr[$fp]['unique_id'] = $productsIndexArr[$prodIDArr[$fp]]['unique_id']; 
		                $prodArr[$fp]['title'] = $productsIndexArr[$prodIDArr[$fp]]['title'];
		                $prodArr[$fp]['item_type'] = $productsIndexArr[$prodIDArr[$fp]]['item_type'];
		                
						$prodArr[$fp]['labeltag'] = $productsIndexArr[$prodIDArr[$fp]]['labeltag'];
						$prodArr[$fp]['serveline'] = $productsIndexArr[$prodIDArr[$fp]]['serveline'];
						$prodArr[$fp]['price'] = !empty($resSectionPrice) ? $resSectionPrice['section_item_price'] : $productsIndexArr[$prodIDArr[$fp]]['price'];
						$prodArr[$fp]['food_category'] = \app\helpers\Utility::foodtype_value_another($productsIndexArr[$prodIDArr[$fp]]['foodtype'],$merchantdetails['ID']);
						$prodArr[$fp]['saleprice'] = !empty($resSectionPrice) ? $resSectionPrice['section_item_sale_price'] : $productsIndexArr[$prodIDArr[$fp]]['saleprice'];
						$prodArr[$fp]['availabilty'] = $productsIndexArr[$prodIDArr[$fp]]['availabilty']; 
						$prodArr[$fp]['image'] = !empty($productsIndexArr[$prodIDArr[$fp]]['image']) ? MERCHANT_PRODUCT_URL.$productsIndexArr[$prodIDArr[$fp]]['image'] : '';
						$prodArr[$fp]['title_quantity'] = $titleNameArr[$prodIDArr[$fp]];
						
		            
					    
										$sqltax = 'select tax_type,tax_value from merchant_food_category_tax where merchant_id = \''.$merchantdetails['ID'].'\' 
										and food_category_id = \''.$productsIndexArr[$prodIDArr[$fp]]['foodtype'].'\'';
										$restax = Yii::$app->db->createCommand($sqltax)->queryAll();
										
										$prodArr[$fp]['tax'] = $restax;
					    
					}
		            $foodCatArr[$fc]['products']  = $prodArr; 
					unset($prodArr);
				}
				
		        
		        $getproducts[$fs]['id'] = $foodSectionArr[$fs];
		        $getproducts[$fs]['name'] = $fsNameArr[$foodSectionArr[$fs]];
		        $getproducts[$fs]['subcategories'] = $foodCatArr;
		        
		        
		        
		    }
			
		   	
		    
		}	
									    
										$merchantlgo = !empty($merchantdetails['logo']) ? MERCHANT_LOGO.$merchantdetails['logo'] : '';
                                    
										$merchantcoverpic = !empty($merchantdetails['coverpic']) ? MERCHANT_LOGO.$merchantdetails['coverpic'] : '';
										
										    $sqlcategoryDetail = 'select 0 foodtype, \'Recommended\' food_category ,count(foodtype) itemcount  from product where merchant_id = \''.$merchantid.'\'
                                                        
                                                        union all
select foodtype,case when foodtype = \'0\' then \'All\'  else fc.food_category end as food_category
                                                        ,count(foodtype) itemcount  from product p
                                                        left join food_categeries fc on fc.id = p.foodtype
                                                        where p.merchant_id = \''.$merchantid.'\'
                                                        group by foodtype';
											$categoryDetail = Yii::$app->db->createCommand($sqlcategoryDetail)->queryAll();
					$sqlsections = 'select ID,s.section_name
					from sections s  
					where s.merchant_id = \''.$merchantid.'\'';		            
					$ressections = Yii::$app->db->createCommand($sqlsections)->queryAll();

					$sectionsArr = [];
					$tableDetArr = [];
					for($s=0;$s<count($ressections);$s++){
					   $sqlTable = 'select * from tablename where merchant_id = \''.$merchantid.'\' and section_id = \''.$ressections[$s]['ID'].'\'';
					   $resTable = Yii::$app->db->createCommand($sqlTable)->queryAll();
					    if(!empty($resTable)){
					    for($t=0;$t<count($resTable);$t++){
					        $tableDetArr[$t]['id'] = $resTable[$t]['ID'];
					        $tableDetArr[$t]['tablename'] = $resTable[$t]['name'];
					    }
					
					    }
					    
					    
					    $sectionsArr[$s]['id'] = $ressections[$s]['ID']  ;
					    $sectionsArr[$s]['section_name'] =  $ressections[$s]['section_name'];
					    $sectionsArr[$s]['table_details'] = $tableDetArr;
					unset($tableDetArr);
					    
					}

					
					
					
										$payload = array("status"=>'1',"merchantid"=>$merchantdetails['ID']
										,"store"=>$merchantdetails['storename'],"storetype"=>$merchantdetails['storetype']
										,"servingtype"=>$merchantdetails['servingtype'],"verify"=>$merchantdetails['verify']
										,"location"=>$merchantdetails['location'],"logo"=>$merchantlgo,"coverpic"=>$merchantcoverpic
										,"categories"=>$getproducts,'categoryDetail'=>$categoryDetail,'sections'=>$sectionsArr);
    									}else{
    									    $payload = array("status"=>'0',"text"=>"Requires atleast one section");
    									}
									    
									
								}else{
									
									$payload = array("status"=>'0',"text"=>"Invalid Restaurant or  theater can again");
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
		
		/* $tabel_Det = \app\models\Tablename::findOne($val['table']);
								
								if($tabel_Det['current_order_id'] != 0 || $tabel_Det['current_order_id'] != null )
								{
								    $currentOrder = \app\models\Orders::findOne($tabel_Det['current_order_id']);
								    if($currentOrder['user_id'] != $val['user_id'] ){
								        $payload = array("status"=>'0',"text"=>"Table is already occupied");
									    return $payload;
									    exit;
								    }    
								} */
						
						
						$valtax = !empty($val['tax']) ? number_format(trim($val['tax']), 2, '.', ',') : 0;
							$userarray['merchant_id'] = $merchantid;
							$userarray['serviceboy_id'] = isset($val['serviceboy_id']) ? $val['serviceboy_id'] : '';
							$userarray['tablename'] = 	$val['table']; 
							$userarray['order_id'] = \app\helpers\Utility::order_id($merchantid,'order'); 
							$userarray['txn_id'] = \app\helpers\Utility::order_id($merchantid,'transaction'); 
							$userarray['txn_date'] = date('Y-m-d H:i:s');
							$userarray['reg_date'] = date('Y-m-d H:i:s');
							$userarray['amount'] = $val['amount'];
							$userarray['tax'] = (string)$valtax;
							$userarray['tips'] = !empty($val['tips']) ? number_format(trim($val['tips']), 2, '.', ',') : '0';
							$userarray['subscription'] = !empty($val['subscription']) ?  number_format(trim($val['subscription']), 2, '.', ',') : '0';
							$userarray['totalamount'] =  !empty($val['totalamount']) ?  number_format(trim($val['totalamount']), 2, '.', ',') : 0;
							$userarray['couponamount'] = !empty($val['couponamount']) ? number_format(trim($val['couponamount']), 2, '.', ',') : 0;
							$userarray['paymenttype'] = 'cash';
							$userarray['orderprocess'] = '1';
							$userarray['status'] = '1';
							$userarray['paidstatus'] = '0';
							$userarray['paymentby'] = '1';
							$userarray['ordertype'] = 1;
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
								
									$sqlorderdetails = 'select * from orders where ID = \''.$result->ID.'\'';
									$orderdetails = Yii::$app->db->createCommand($sqlorderdetails)->queryOne();
									
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
										$productscount['order_id'] = $orderdetails['ID'];
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

											$notificaitonarary = array();
											$notificaitonarary['merchant_id'] = $merchantid;
											$notificaitonarary['serviceboy_id'] = $val['serviceboy_id'];
											$notificaitonarary['order_id'] = $orderdetails['ID'];
											$notificaitonarary['title'] = 'New Order';
											$notificaitonarary['message'] = 'New Order request from '.$userdetails['name']." with order id ".$orderdetails['order_id'];
											$notificaitonarary['ordertype'] = 'new';
											$notificaitonarary['seen'] = '0';
											$serviceBoyNotiModel = new  \app\models\ServiceboyNotifications;
											$serviceBoyNotiModel->attributes = $notificaitonarary;
											$serviceBoyNotiModel->reg_date = date('Y-m-d H:i:s');
											$serviceBoyNotiModel->mod_date = date('Y-m-d H:i:s');
											$serviceBoyNotiModel->save();
											$tableUpdate = \app\models\Tablename::findOne($val['table']);
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
						 //$orderdetails = selectQuery("orders","ID = '".$orderid."'");
							$sqlorderdetails = 'select * from orders where ID = \''.$orderid.'\'';
							$orderdetails = Yii::$app->db->createCommand($sqlorderdetails)->queryOne();

						    		$sqlusers = 'select * from users where ID = \''.$orderdetails['user_id'].'\'';
		                            $userdetails = Yii::$app->db->createCommand($sqlusers)->queryOne();

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
							$userarray['paidstatus'] = '0';
							$userarray['paymentby'] = '1';
							$userarray['totalamount'] = $totalamount; 
							$userarray['couponamount'] = !empty($val['couponamount']) ? number_format(trim($val['couponamount']), 2, '.', ',') : 0;
                            $userarray['coupon'] = $couponcode;
							$userarray['instructions'] = $val['instructions'];
							$userarray['discount_number'] = $val['discount_number'];
							$userarray['instructions'] = !empty($val['instructions']) ? $val['instructions'] : $orderdetails['instructions'];
							$userwwherearray['ID'] = $orderid; 
							//$result = updateQuery($userarray,"orders",$userwwherearray);
							$result = \app\models\Orders::findOne($orderid);
							//echo "<pre>";print_r($userarray);exit;
							$result->attributes = $userarray;
							if($result->save()){
									$sqlcoinsdata = "select * from coins_transactions where user_id = '".$orderdetails['user_id']."' and order_id = '".$orderdetails['order_id']."'";
									$coinsdata = Yii::$app->db->createCommand($sqlcoinsdata)->queryOne();
									if(!empty($coinsdata['user_id'])&&!empty($coinsdata['coins'])){
									$sqlDelete = "delete from coins_transactions where ID = '".$coinsdata['ID']."'";
									$resDelete = Yii::$app->db->createCommand($sqlDelete)->execute();
									\app\helpers\Utility::coins_deduct($coinsdata['user_id'],$coinsdata['coins']);
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
										$productscount['order_id'] = $orderdetails['ID'];
										$productscount['user_id'] = @$userdetails['ID'];
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
									$x++; }
	
											$title = 'Order Items has been added!!';
											$message = "Hi ".$userdetails['name'].", Your order has been placed. ".$orderdetails['order_id']." is your line Please Wait for your turn Thank you.";
											$image = '';
											//Yii::$app->merchant->send_sms($userdetails['mobile'],$message);
											if(!empty($userdetails['push_id'])){
												\app\helpers\Utility::sendNewFCM($userdetails['push_id'],$title,$message,$image,null,null,$orderdetails['ID']);
											} 
											$servicepushid = \app\helpers\Utility::serviceboy_details($orderdetails['serviceboy_id'],'push_id');
											if(!empty($servicepushid)){
												$stitle = 'Order items have been added.';
												$smessage = 'Order received please check the app for information.';
												$simage = ''; 
													\app\helpers\Utility::sendNewFCM($servicepushid,$stitle,$smessage,$simage,'6',null,$orderdetails['ID']); 
											}
											$notificaitonarary = array();
											$notificaitonarary['merchant_id'] = $merchantid;
											$notificaitonarary['serviceboy_id'] = $orderdetails['serviceboy_id'];
											$notificaitonarary['order_id'] = $orderdetails['ID'];
											$notificaitonarary['title'] = 'Reorder Request';
											$notificaitonarary['message'] = 'Reorder Order request from '.$userdetails['name']." with order id ".$orderdetails['order_id'];
											$notificaitonarary['seen'] = '0';
											$notificaitonarary['ordertype'] = 'reorder';
											//insertQuery($notificaitonarary,'serviceboy_notifications');
											$serviceBoyNotiModel = new  \app\models\ServiceboyNotifications;
											$serviceBoyNotiModel->attributes = $notificaitonarary;
											$serviceBoyNotiModel->reg_date = date('Y-m-d H:i:s');
											$serviceBoyNotiModel->save();
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
		    $sqlorderdet = \app\models\Orders::findOne($val['orderid']);
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


        $paytypearray[0]['ID'] = '1';
        $paytypearray[0]['name'] = 'Cash';
        $paytypearray[1]['ID'] = '2';
        $paytypearray[1]['name'] = 'Online';
        $paytypearray[1]['ID'] = '3';
        $paytypearray[1]['name'] = 'UPI';
        $paytypearray[1]['ID'] = '4';
        $paytypearray[1]['name'] = 'Card';
        //= array('1'=>'Cash','2'=>'Online','3'=>'UPI','4'=>'Card');

        
	return					$payload = array('status'=>'1','payment_names' => $paytypearray);

	}


}
?>