<?php
namespace app\controllers;
use app\models\PilotFactorRating;
use app\models\Serviceboy;
use yii;
use sizeg\jwt\Jwt;
use sizeg\jwt\JwtHttpBearerAuth;
use yii\rest\Controller;
use \app\helpers\Utility;
define('SITE_URL','https://superpilot.in/dev/');
define('UPLOADS_URL','http://superpilot.in/dev/tutors/web/uploads/');
define('INVOICE_URL',SITE_URL.'invoice/');
define('PATH_NAME',dirname(__DIR__).'/public_html/dev/'); 
define('MAILID','invoice@superpilot.in');
define('ADMIN_URL',SITE_URL.'foodq-master/');
define('SERVICEBOY_URL',SITE_URL.'foodq-service/');
define('MERCHANT_URL',SITE_URL.'foodq-merchant/');
define('MERCHANT_PRODUCT_URL',SITE_URL.'/tutors/web/uploads/productimages/');
define('MERCHANT_GALLERY_URL',SITE_URL.'merchantgallery/');
define('MERCHANT_LOGO',SITE_URL.'merchantimages/');
define('USER_LOGO',SITE_URL.'userprofilepic/');
define('BRAND_LOGO',SITE_URL.'brandlogo/');
define('BANNER_IMAGE',SITE_URL.'bannerimage/');
define('REWARDS_IMAGES',SITE_URL.'rewardsimage/');
define('RECOMMEND_IMAGES',SITE_URL.'recommendimages/');
define('SERVICEBOY_IMAGE',SITE_URL.'serviceboyprofilepic/'); 
define('CONTEST_IMAGE',SITE_URL.'contestimages/');
define('CATEGORY_IMAGE',UPLOADS_URL.'roomcategoryimages/');

date_default_timezone_set("asia/kolkata");
class ApiController extends Controller
{
public function beforeAction($action)
{
    date_default_timezone_set("asia/kolkata");
    $this->enableCsrfValidation = false;
 
  //return true;
  return parent::beforeAction($action);
}
  public function actionTesting()
  {
	  $to = 'cyC2VPN743s:APA91bEOn_bs0m9Kx8C-ONcVB2S_q1SgATcCZb7iHjiTUrkYL3H6Mrv5PLM7GZmyOm20BUxbA8w_uwej0pdDrN6CKBvDVs1GgbQTz-nq-IB5TYyobIT_NwMfOkKtiDUijDfg54jbHovT';
	  $title = 'Hi';
	  $message = 'Hello FCM';
	  
	  \app\helpers\Utility::sendFCM($to,$title,$message);
  }   
	public function actionApiservice(){
	    Yii::debug("=====action ===".trim($_REQUEST['action']));
		$action = trim($_REQUEST['action']);
		if(!empty($action)){
			switch($action){
				case 'login':
				$this->login($_REQUEST);
				break;
				case 'signin':
				$this->signin($_REQUEST);
				break;
				case 'registration':
				$this->registration($_REQUEST);
				break;
				case 'register-otp':
				$this->registerotp($_REQUEST);
				break;
				case 'resend-otp':
				$this->resendotp($_REQUEST);
				break;
				case 'password':
				$this->password($_REQUEST);
				break;				
				case 'forgotpassword':
				$this->forgotpassword($_REQUEST);
				break;
				case 'verifymobile':
				$this->verifymobile($_REQUEST);
				break;
				case 'forgotpassword-otp':
				$this->forgotpasswordotp($_REQUEST);
				break;
				case 'updatepassword':
				$this->updatepassword($_REQUEST);
				break;
				case 'changepassword':
				$this->changepassword($_REQUEST);
				break;
				case 'pushid':
				$this->pushid($_REQUEST);
				break;
				case 'updation':
				$this->updation($_REQUEST);
				break;
				case 'users':
				$this->users($_REQUEST);
				break;
				case 'add-feedback':
				$this->addfeedback($_REQUEST);
				break;
				case 'check-merchant-feedback':
				$this->checkMerchantFeedback($_REQUEST);
				break;
                case 'check-order-feedback':
                    $this->checkOrderFeedback($_REQUEST);
                    break;
				case 'set-alert':
				$this->setalert($_REQUEST);
				break;
				case 'close-feedback':
				$this->closefeedback($_REQUEST);
				break;
				case 'notificationslist':
				$this->notificationslist($_REQUEST);
				break;
				case 'seenstatus':
				$this->seenstatus($_REQUEST);
				break;
				case 'merchants':
				$this->merchants($_REQUEST);
				break;
				case 'recommendlist':
				$this->recommendlist($_REQUEST);
				break;
				case 'restaurants':
				$this->restaurants($_REQUEST);
				break;
				case 'bannerlist':
				$this->bannerlist($_REQUEST);
				break;
				case 'searchlocations':
				$this->searchlocations($_REQUEST);
				break;
				case 'merchantbyid':
				$this->merchantbyid($_REQUEST);
				break;
				case 'redeem-coins':
				$this->redeemcoins($_REQUEST);
				break;
				case 'coins-transactionslist':
				$this->coinstransactionslist($_REQUEST);
				break;				
				case 'coins-transactions': 
				$this->coinstransactions($_REQUEST);
				break;	
				case 'rewards-count': 
				$this->rewardscount($_REQUEST);
				break;
				case 'rewards-list':
				$this->rewardslist($_REQUEST);
				break;
				case 'rewards-individual-details':
				$this->rewardsdetails($_REQUEST);
				break;
				case 'redeem-rewards-details':
				$this->redeemrewardsdetails($_REQUEST);
				break;
				case 'apply-computation':
				$this->applycomputation($_REQUEST);
				break;
				case 'cancel-coupon':
				$this->cancelcoupon($_REQUEST);
				break;
				case 'qrcode':
				$this->qrcode($_REQUEST);
				break;
				case 'qrcodenew':
				$this->qrcodenew($_REQUEST);
				break;				
				case 'qrcodetest':
				$this->qrcodetest($_REQUEST);
				break;				
				case 'qr-users':
				$this->qrusers($_REQUEST);
				break;
				case 'cash':
				$this->cash($_REQUEST);
				break;
				case 'reordercash':
				$this->reordercash($_REQUEST);
				break;
				case 'prepaid':
				$this->prepaid($_REQUEST);
				break;	
				case 'reorderprepaid':				
				$this->reorderprepaid($_REQUEST);
				break;
				case 'orderlist':
				$this->orderlist($_REQUEST);
				break;
				case 'order':
				$this->order($_REQUEST);
				break;	
				case 'arrangeorder':
				$this->arrangeorder($_REQUEST);
				break;	
				case 'reservationlist':
				$this->reservationlist($_REQUEST);
				break;
				case 'orderinvoice':
				$this->orderinvoice($_REQUEST);
				break;		
				case 'tablenames':
				$this->tablenames($_REQUEST);
				break;
				case 'booktable':
				$this->booktable($_REQUEST);
				break;
				case 'gallerylist':
				$this->gallerylist($_REQUEST);
				break;
				case 'generateCheckSum':
				$this->generateCheckSum($_REQUEST);
				break;
				case 'contestlist':
				$this->contestlist($_REQUEST);
				break;
				case 'contestdetaillist':
				$this->contestdetaillist($_REQUEST);
				break;
				case 'verifyregisterotp':
				$this->verifyregisterotp($_REQUEST);
				break;
				case 'roomreservations':
				$this->roomreservations($_REQUEST);
				break;
				case 'storetypedetails':
				$this->storetypedetails($_REQUEST);
				break;	
				case 'roomcategeries':
				$this->roomcategeries($_REQUEST);
				break;
				case 'upselling':
				$this->upselling($_REQUEST);
				break;	
				case 'selectroom':
				$this->selectroom($_REQUEST);
				break;	
				case 'bookroom':
				$this->bookroom($_REQUEST);
				break;
				case 'meetme':
				$this->meetme($_REQUEST);
				break;
				case 'coupondetails':
				$this->coupondetails($_REQUEST);
				break;
				case 'addwhislist':
					$this->addwhislist($_REQUEST);
				break;
				case 'getUserWhilist':
					$this->getUserWhilist($_REQUEST);
				break;
				case 'getStores':
					$this->getStores($_REQUEST);
				break;
				case 'usermerchantpaymenttypes':
					$this->usermerchantpaymenttypes($_REQUEST);
				break;
                case 'add-merchant-rating':
                    $this->addMerchantRating($_REQUEST);
                    break;
                case 'get-pilot-factors':
                    $this->getPilotFactors($_REQUEST);
                    break;
			}
		}
	}
	public function actionServiceboy()
    {
        $payload = []; // Declaring responce
        $action = trim($_REQUEST['action']); // API name
        $usersid = $_REQUEST['usersid']; // API Accessing User Id (Pilot)

        $loginFreeActions = ['login','registration'];
        if(in_array( $action, $loginFreeActions)) {
            switch ($action) {
                case 'login':
                    $payload = Yii::$app->serviceboy->login($_REQUEST);
                    break;
                case 'registration':
                    $payload =  Yii::$app->serviceboy->registration($_REQUEST);
                    break;
                case 'pilotDemoRequests':
                    $this->pilotDemoRequests($_REQUEST);
                    break;
            }
        }
        else if(!empty($usersid)){
            $pilotDetails = Serviceboy::findOne($usersid);
            $val = $_REQUEST;
            $val['header_user_id'] = $usersid;
            $val['merchantId'] = $pilotDetails['merchant_id'];
            if (!empty($action)) {
                switch ($action) {
                    case 'logout':
                        $payload = Yii::$app->serviceboy->logout($val);
                        break;
                    case 'updation':
                        $payload = Yii::$app->serviceboy->updation($val);
                        break;
                    case 'login-status':
                        $payload = Yii::$app->serviceboy->loginstatus($val);
                        break;
                    case 'forgotpassword':
                        $payload = Yii::$app->serviceboy->forgotpassword($val);
                        break;
                    case 'forgotpassword-otp':
                        $payload = Yii::$app->serviceboy->forgotpasswordotp($val);
                        break;
                    case 'changepassword':
                        $payload = Yii::$app->serviceboy->changepassword($val);
                        break;
                    case 'updatepassword':
                        $payload = Yii::$app->serviceboy->updatepassword($val);
                        break;
                    case 'checkloginstatus':
                        $payload = Yii::$app->serviceboy->checkloginstatus($val);
                        break;
                    case 'serviceboy':
                        $payload = Yii::$app->serviceboy->serviceboys($val);
                        break;
                    case 'notificationslist':
                        $payload = Yii::$app->serviceboy->servicenotificationslist($val);
                        break;
                    case 'seenstatus':
                        $payload = Yii::$app->serviceboy->seenstatus($val);
                        break;
                    case 'neworders':
                        $payload = Yii::$app->serviceboy->neworders($val);
                        break;
                    case 'acceptedorders':
                        $payload = Yii::$app->serviceboy->orderswithstatus($val);
                        break;
                    case 'cashpilot':
                        $payload = Yii::$app->serviceboy->cash($val);
                        break;
                    case 'reordercashpilot':
                        $payload = Yii::$app->serviceboy->reordercash($val);
                        break;
                    case 'order':
                        $payload = Yii::$app->serviceboy->order($val);
                        break;
                    case 'acceptorder':
                        $payload = Yii::$app->serviceboy->acceptorder($val);
                        break;
                    case 'prepareorder':
                        $payload = Yii::$app->serviceboy->prepareorder($val);
                        break;
                    case 'getpreparetiontime':
                        $payload = Yii::$app->serviceboy->getpreparetiontime($val);
                        break;
                    case 'serveorder':
                        $payload = Yii::$app->serviceboy->serveorder($val);
                        break;
                    case 'rejectorder':
                        $payload = Yii::$app->serviceboy->rejectorder($val);
                        break;
                    case 'deliverorder':
                        $payload = Yii::$app->serviceboy->deliverorder($val);
                        break;
                    case 'paidstatus':
                        $payload = Yii::$app->serviceboy->paidstatus($val);
                        break;
                    case 'orderslist':
                        $payload = Yii::$app->serviceboy->orderslist($val);
                        break;
                    case 'cancelreasons':
                        $payload = Yii::$app->serviceboy->cancelreasons($val);
                        break;
                    case 'foodcomplaintreasons':
                        $payload = Yii::$app->serviceboy->foodcomplaintreasons($val);
                        break;
                    case 'qrcodepilot':
                        $payload = Yii::$app->serviceboy->qrcode($val);
                        break;
                    case 'tablelist':
                        $tablelist = Yii::$app->merchant->tableslist($val);
                        $payload = array('status'=>'1','message'=>'List Of Tables','tablelist' => $tablelist);
                        break;
                    case 'tablesections':
                        $tablesections = Yii::$app->merchant->tablesections($val);
                        $serviceboydet = \app\models\Serviceboy::findOne($val['header_user_id']);
                        $payload = ['status'=>'1','message'=>'List Of Sections','loginstatus' => $serviceboydet['loginstatus'],'tablesections' => $tablesections];
                        break;
                    case 'addpilotfeedback':
                        $payload = Yii::$app->serviceboy->addpilotfeedback($val);
                        break;
                    case 'preparecompleteorder':
                        $payload = Yii::$app->serviceboy->preparecompleteorder($val);
                        break;
                    case 'merchantpaymenttypes':
                        $payload = Yii::$app->serviceboy->merchantpaymenttypes($val);
                        break;
                    case 'confirmpayment':
                        $payload = Yii::$app->serviceboy->confirmpayment($val);
                        break;
                    /* counter settlement */
                    case 'getCurrentSettlementSession':
                        $payload = Yii::$app->counter->getCurrentSettlementSession($val);
                        break;
                    case 'settlementHistory':
                        $payload = Yii::$app->counter->settlementHistory($val);
                        break;
                    case 'saveSettlement':
                        $payload = Yii::$app->counter->saveSettlement($val);
                        break;
                }
            }
            else {
                $payload = array('status'=>'0','message'=>'Invalid API!!');
            }
        }
        else{
            $payload = array('status'=>'0','message'=>'Invalid users details');
        }

        return $this->asJson($payload);
	}

	public function actionProfilepic()
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$sqluserdetails = "select ID from users where ID = '".$usersid."'";
			$userdetails = Yii::$app->db->createCommand($sqluserdetails)->queryOne();
	if(!empty($userdetails['ID'])){  
		if(!empty($_FILES['profilepic']['name'])){
			  $productarray =  $productwherearray = array(); 
				if (!file_exists('../../userprofilepic')) {	
						mkdir('../../userprofilepic', 0777, true);	
						}																			
					$target_dir = '../../userprofilepic/';	 
						$file = $_FILES['profilepic']['name'];
						if(!empty($file)){
							$extn = pathinfo($file, PATHINFO_EXTENSION);
								$filename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$extn;	
						$target_file = $target_dir.$filename;
							 
						if (move_uploaded_file($_FILES['profilepic']["tmp_name"], $target_file)){			 
							$productarray['profilepic'] = $filename;
							 
						}  
						 $productwherearray['ID'] =  $userdetails['ID'];
							$sqlUpdate = 'update users set profilepic =\''.$productarray['profilepic'].'\' where ID =\''.$productwherearray['ID'].'\'';
							$resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute();
						$payload = array("status"=>'1',"text"=>"Profilepic updated","profilepic"=>Utility::user_image($userdetails['ID']));
						}  else { 
							$payload = array("status"=>'0',"text"=>"Image not found.");
						}  
			  }  else { 
					$payload = array("status"=>'0',"text"=>"Image not found.");
			  } 
			  }  else {
					
					$payload = array("status"=>'0',"text"=>"Invalid users");
			  } 
}else{
	 
	$payload = array('status'=>'0','message'=>'Invalid users details');
}
return $this->asJson($payload);

	}
	public function actionServiceboyprofilepic()
	{
	    
		if(!empty($_REQUEST['userid'])){
$userid = $_REQUEST['userid'];
$sqluserdetails = "select ID from serviceboy where ID = '".$userid."'";
$userdetails = Yii::$app->db->createCommand($sqluserdetails)->queryOne();
	if(!empty($userdetails['ID'])){  
		if(!empty($_FILES['profilepic']['name'])){
			  $productarray =  $productwherearray = array(); 
				if (!file_exists('../../serviceboyprofilepic')) {	
						mkdir('../../serviceboyprofilepic', 0777, true);	
						}																			
					$target_dir = '../../serviceboyprofilepic/';	 
						$file = $_FILES['profilepic']['name'];
						if($file){
							$extn = pathinfo($file, PATHINFO_EXTENSION);
								$filename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$extn;	
						$target_file = $target_dir.$filename;
							 
						if (move_uploaded_file($_FILES['profilepic']["tmp_name"], $target_file)){			 
							$productarray['profilepic'] =$filename;
							 
						}  
						 $productwherearray['ID'] =  $userdetails['ID'];
				//	$result = updateQuery($productarray,'serviceboy',$productwherearray); 
					$sqlUpdate = 'update serviceboy set profilepic = \''.$productarray['profilepic'].'\' where ID = \''.$productwherearray['ID'].'\'';
					$resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute();
				$payload = array("status"=>'1',"text"=>"Profilepic updated","profilepic"=>SERVICEBOY_IMAGE.$filename);
						}  else { 
					$payload = array("status"=>'0',"text"=>"Image not found.");
						}  
			  }  else { 
					$payload = array("status"=>'0',"text"=>"Image not found.");
			  } 
			  }  else {
					
					$payload = array("status"=>'0',"text"=>"Invalid service boy");
			  } 
}else{
	 
	$payload = array('status'=>'0','message'=>'Invalid Service boy details');
}
return  $this->asJson($payload);
	}
	
		public function signin($val)
		{
				  if(!empty($val['firstname'])){
			if(!empty($val['password'])){
			
		  $mymailid = $val['username'];
		  $mypassword = ($val['password']); 
		  if(filter_var($mymailid, FILTER_VALIDATE_EMAIL)) {
		   $sqlrow = 'SELECT * FROM users WHERE email = \''.$mymailid.'\'';
		  
		  }else{
			$sqlrow = 'SELECT * FROM users WHERE mobile = \''.$mymailid.'\'';
		  }
		 
		  $row = Yii::$app->db->createCommand($sqlrow)->queryOne();
		  if(!empty($row['ID'])){
			  if(password_verify($mypassword,$row['password'])){
				if($row['status']==1){
					 
						 $jwt = base64_encode($row['ID']);
	
						 $payload = array("status" => '1',"text" => "Successful login.","usersid" => $jwt);
				} else {
					$userwherearray = $userarray = array();
					$userwherearray['ID'] = $row['ID'];
					$userarray['otp'] = rand(1111,9999);
					//$result = updateQuery($userarray,"users",$userwherearray);
					$sqlupdate = 'update users set otp = \''.$userarray['otp'].'\' where ID = \''.$userwherearray['ID'].'\'';
					$result = Yii::$app->db->createCommand($sqlupdate)->execute();
					$message = "Hi ".$row['name']." ".$userarray['otp']." is your otp for verification.";
					\app\helpers\Utility::otp_sms($row['mobile'],$message); 
					$payload = array("status"=>'2',"usersid"=>$row['ID'],"text"=>"Please verify your account");	 
				}
			  }  else {
						
					$payload = array("status"=>'0',"text"=>"Invalid Password");
			  }
			  }  else {
						
					$payload = array("status"=>'0',"text"=>"Invalid Email / Mobile number");
			  }
		  }else{
						
			$payload = array("status"=>'0',"text"=>"Please enter the password");
		  }
		  }else{
						
			 	$payload = array("status"=>'0',"text"=>"Please enter your first name");
		  }
		return $this->asJson($payload);
	}

    /**
     * @param $val
     * @return yii\web\Response
     * @throws yii\db\Exception
     */
	public function login($val){
	    
	  if(!empty($val['mobilenumber'])){
	       $sqlalreadyid = "SELECT * FROM users WHERE mobile = '".$val['mobilenumber']."'";
					$alreadyid = Yii::$app->db->createCommand($sqlalreadyid)->queryOne();
					if(!empty($alreadyid)){
	    $mobilenumber = $val['mobilenumber']; 
	      	$otp = rand(1111,9999);
						$message = "Hi ".$otp." is OTP for your Registration.";
						\app\helpers\Utility::otp_sms($mobilenumber,$message);
						$otpModel = new \app\models\SequenceMaster;
						$otpModel->seq_name = $mobilenumber;
						$otpModel->seq_number = $otp;
						$otpModel->merchant_id = 0;
						$otpModel->reg_date = date('Y-m-d H:i:s');
						$otpModel->save();
						$payload = array("status"=>'1',"text"=>"OTP Sent successfully");
	        }
	        else{
	 	        $payload = array("status"=>'0',"text"=>"Please Sign Up");
	        }
		}else{
	 	$payload = array("status"=>'0',"text"=>"Please enter the mobile number");
	  }
	  return $this->asJson($payload);
	}

    /**
     * @param $val
     * @return yii\web\Response
     * @throws yii\db\Exception
     */
    public function merchants($val)
	{
        $headerslist = apache_request_headers();
        $usersid = base64_decode($headerslist['Authorization']);

        if(!empty($usersid)){
            $sqluserdetails = "select ID from users where ID = '".$usersid."'";
            $userdetails = Yii::$app->db->createCommand($sqluserdetails)->queryOne();

                if(!empty($val['latitude'])&&!empty($val['longitude'])){
                    $latitude = $val['latitude'];
                    $longitude = $val['longitude'];
                    $sqlmerchantsarray = "SELECT * FROM (SELECT *,(((acos(sin((".$latitude."*pi()/180)) * sin((latitude*pi()/180))+cos((".$latitude."*pi()/180)) * 
                        cos((latitude*pi()/180)) * cos(((".$longitude."- longitude)* pi()/180))))*180/pi())*60*1.1515 ) as distance FROM merchant 
                        WHERE 1 GROUP BY ID ) as X where distance <= 5 and status = '1' ORDER BY ID DESC";
                    $sqlmerchantsarray = "SELECT *  FROM merchant where status = '1'";
					$merchantsarray = Yii::$app->db->createCommand($sqlmerchantsarray)->queryAll();
					 if(!empty($merchantsarray)){
							$merchantlist = $merchants = $newMerchants = $popularMerchants = [];

							foreach($merchantsarray as $merchantsdata){
                                $merchants['id'] = $merchantsdata['ID'];
                                $merchants['unique_id'] = $merchantsdata['unique_id'];
                                $merchants['name'] = $merchantsdata['name'];
                                $merchants['email'] = $merchantsdata['email'];
                                $merchants['storename'] = $merchantsdata['storename'];
                                $merchants['storetype'] = $merchantsdata['storetype'];
                                $merchants['address'] = $merchantsdata['address'];
                                $merchants['state'] = $merchantsdata['state'];
                                $merchants['city'] = $merchantsdata['city'];
                                $merchants['location'] = $merchantsdata['location'];
                                $merchants['latitude'] =  $merchantsdata['latitude'];
                                $merchants['longitude'] =  $merchantsdata['longitude'];
                                $merchants['servingtype'] =  $merchantsdata['servingtype'];
                                $merchants['verify'] =  $merchantsdata['verify'];
                                $merchants['showpage'] =  $merchantsdata['storetype'] == 'Restaurant' ? '1' : '0';
                                $sqlfeedbackrating = "select sum(mar.rating)/count(ID) rating from (select mf.ID,avg(rating) as rating from merchant_feedback mf
                                inner join merchant_ambiance_rating mar on mf.ID = mar.merchant_feedback_id
                                where mf.merchant_id =  '".$merchantsdata['ID']."' group by mf.ID) A ";
                                $feedbackrating = Yii::$app->db->createCommand($sqlfeedbackrating)->queryOne();


                                $merchants['rating'] = !empty($feedbackrating) ? ceil($feedbackrating['rating']) : 0;
                                $merchants['logo'] = !empty($merchantsdata['logo']) ?  MERCHANT_LOGO.$merchantsdata['logo'] : '';
                                $merchants['coverpic'] = !empty($merchantsdata['coverpic']) ? MERCHANT_LOGO.$merchantsdata['coverpic'] : '';
                                $merchants['qrlogo'] = !empty($merchantsdata['qrlogo']) ? MERCHANT_LOGO.$merchantsdata['qrlogo'] : "";
                                $merchants['food_serve_type']= !empty($merchantsdata['food_serve_type']) ? $merchantsdata['food_serve_type'] : '';
                                $merchants['open_time'] =  $merchantsdata['open_time'];
                                $merchants['close_time'] =  $merchantsdata['close_time'];
                                $merchants['popularity'] =  $merchantsdata['popularity'];
                                $merchants['approx_cost'] =  $merchantsdata['approx_cost'];

                            if($merchants['popularity'] == '1' || $merchants['popularity'] == '3'){
                                $newMerchants[] = $merchants;
                            }

                            if($merchants['popularity'] == '2' || $merchants['popularity'] == '3'){
                                $popularMerchants[] = $merchants;
                            }
                            	$merchantlist[] = $merchants;
							} 
							$takeawayarr = [];
							for($i=0;$i<count($merchantlist);$i++){
							    if($merchantlist[$i]['food_serve_type'] == 2){
							        $takeawayarr[$i] = $merchantlist[$i];
							        $sqltablename = 'select * from tablename where merchant_id=\''.$merchantlist[$i]["id"].'\' order by ID';
							        $tabledet = Yii::$app->db->createCommand($sqltablename)->queryOne();
							        $takeawayarr[$i]['enckey'] =$this->encrypt($merchantlist[$i]["id"].','.$tabledet['ID']);
							 
							        
					
							        
							    }
							    
							}
						    
							$payload = ["status"=>'1',"merchantlist"=>$merchantlist
                                            ,'takeaway'=>array_values($takeawayarr),'newMerchants' => $newMerchants
                                            ,'popularMerchants' => $popularMerchants,'distance' => '10000'
                                       ];
						}else{
						
						$payload = ["status"=>'0',"text"=>"No Restaurant Found."];
					 }
					}else{
						
						$payload = ["status"=>'0',"text"=>"Invalid parameters"];
					 }
					}else{
						$payload = ['status'=>'0','message'=>'Invalid users details'];
					}
        return $this->asJson($payload);
	}
	public function recommendlist($val)
	{
				if(!empty($val['latitude'])&&!empty($val['longitude'])){
					$latitude = $val['latitude']; 
					$longitude = $val['longitude']; 
					$sqlmerchantsarray = "SELECT * FROM (SELECT *,(((acos(sin((".$latitude."*pi()/180)) * sin((latitude*pi()/180))+cos((".$latitude."*pi()/180)) * 
cos((latitude*pi()/180)) * cos(((".$longitude."- longitude)* pi()/180))))*180/pi())*60*1.1515 ) as distance FROM merchant WHERE 1
 GROUP BY ID ) as X where distance <= 5 and status = '1' and recommend <> '' ORDER BY ID DESC"; 
 $merchantsarray = Yii::$app->db->createCommand($sqlmerchantsarray)->queryAll();
				
					 if(!empty($merchantsarray)){
							$merchantlist = $merchants = array();
							foreach($merchantsarray as $merchantsdata){
							$merchants['id'] = $merchantsdata['ID'];
							$merchants['unique_id'] = $merchantsdata['unique_id'];
							$merchants['name'] = $merchantsdata['name'];
							$merchants['email'] = $merchantsdata['email'];
							$merchants['storename'] = $merchantsdata['storename'];
							$merchants['storetype'] = $merchantsdata['storetype'];
							$merchants['address'] = $merchantsdata['address'];
							$merchants['state'] = $merchantsdata['state'];
							$merchants['city'] = $merchantsdata['city'];
							$merchants['location'] = $merchantsdata['location'];
							$merchants['latitude'] =  $merchantsdata['latitude'];
							$merchants['longitude'] =  $merchantsdata['longitude'];
							$merchants['servingtype'] =  $merchantsdata['servingtype'];
							$merchants['verify'] =  $merchantsdata['verify'];
							$merchants['logo'] = !empty($merchantsdata['recommend']) ? RECOMMEND_IMAGES.$merchantsdata['recommend'] : '';
							$merchants['coverpic'] = !empty($merchantsdata['coverpic']) ? MERCHANT_LOGO.$merchantsdata['coverpic'] : '';
								$merchantlist[] = $merchants;
							}
						
							$payload = array("status"=>'1',"merchantlist"=>$merchantlist);
				}else{
						
						$payload = array("status"=>'0',"text"=>"Invalid user");
					 }
					}else{
						
						$payload = array("status"=>'0',"text"=>"Invalid parameters");
					 }
					 		return $this->asJson($payload);
	}
	public function bannerlist($val){
		$payload = Yii::$app->enduser->bannerlist($val);
		return $this->asJson($payload);
	}
	public function registration($val){
		$payload = Yii::$app->enduser->registration($val);
		return $this->asJson($payload);
	}
	public function registerotp($val) {
		$payload = Yii::$app->enduser->registerotp($val);
		return $this->asJson($payload);
	}
	public function resendotp($val) {
		$payload = Yii::$app->enduser->resendotp($val);
		return $this->asJson($payload);		
	}
	public function forgotpassword($val) {
		$payload = Yii::$app->enduser->forgotpassword($val);
		return $this->asJson($payload);		
	}
	public function verifymobile($val) {
		$payload = Yii::$app->enduser->verifymobile($val);
		return $this->asJson($payload);		
	}
	public function verifyregisterotp($val) {
		$payload = Yii::$app->enduser->verifyregisterotp($val);
		return $this->asJson($payload);		
	}	
	public function forgotpasswordotp($val) 
	{
	    Yii::trace("========entering forget password otp======");
		$payload = Yii::$app->enduser->forgotpasswordotp($val);
		return $this->asJson($payload);		
	}
	public function updatepassword($val)
	{
		$payload = Yii::$app->enduser->updatepassword($val);
		return $this->asJson($payload);
	}
	public function changepassword($val)
	{
		$payload = Yii::$app->enduser->changepassword($val);
		return $this->asJson($payload);
	}
    public function addfeedback($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
		$val['header_user_id'] = $usersid;
		$payload = Yii::$app->enduser->addfeedback($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
    public function checkMerchantFeedback($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
		$val['header_user_id'] = $usersid;
		$payload = Yii::$app->enduser->checkMerchantFeedback($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
    public function checkOrderFeedback($val)
    {
        $headerslist = apache_request_headers();
        $usersid = base64_decode($headerslist['Authorization']);
        if(!empty($usersid)){
            $val['header_user_id'] = $usersid;
            $payload = Yii::$app->enduser->checkOrderFeedback($val);
        }else{
            $payload = array('status'=>'0','message'=>'Invalid users details');
        }
        return $this->asJson($payload);
    }
    public function setalert($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
		$val['header_user_id'] = $usersid;
		$payload = Yii::$app->enduser->setalert($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}

		return $this->asJson($payload);		
	}
	public function closefeedback($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
		$val['header_user_id'] = $usersid;
		$payload = Yii::$app->enduser->closefeedback($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function password($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
		$val['header_user_id'] = $usersid;
		$payload = Yii::$app->enduser->password($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);				
	}
	public function pushid($val)
	{
		$headerslist = apache_request_headers();
		
		$usersid = base64_decode($headerslist['Authorization']);
		 
		if(!empty($usersid)){
		$val['header_user_id'] = $usersid;	
		
		$payload = Yii::$app->enduser->pushid($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
    public function updation($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
		$val['header_user_id'] = $usersid;
		$payload = Yii::$app->enduser->updation($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function users($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			
		$payload = Yii::$app->enduser->users($usersid);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function notificationslist($val){
		$payload = Yii::$app->enduser->notificationslist($val);
		return $this->asJson($payload);			
	}
	public function seenstatus($val){  // need to see
		$payload = Yii::$app->enduser->seenstatus($val);
		return $this->asJson($payload);		
	}
	public function restaurants($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){ 
			$payload = Yii::$app->enduser->restaurants($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);				
	}
	public function searchlocations($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
		$payload = Yii::$app->enduser->searchlocations($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);						
	}
	public function merchantbyid($val)
	{
		$payload = Yii::$app->enduser->merchantbyid($val);
		return $this->asJson($payload);							
	}
	public function redeemcoins($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->redeemcoins($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);				
	}
	public function coinstransactionslist($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->coinstransactionslist($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);				
	}
	public function coinstransactions($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->coinstransactions($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);			
	}
	public function rewardscount($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->rewardscount($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);			
	}
	public function rewardslist($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->rewardslist($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);			
	}
	public function rewardsdetails($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->rewardsdetails($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details new');
		}
		return $this->asJson($payload);				
	}
	public function redeemrewardsdetails($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->redeemrewardsdetails($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function applycomputation($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->applycomputation($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function cancelcoupon($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->cancelcoupon($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function qrcode($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->qrcode($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	
	
		public function qrcodenew($val){
	$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->qrcodenew($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function qrusers($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->qrusers($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function cash($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->cash($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function reordercash($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->reordercash($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function prepaid($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->prepaid($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function reorderprepaid($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->reorderprepaid($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function orderlist($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->orderlist($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function order($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->order($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function arrangeorder($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->arrangeorder($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function reservationlist($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->reservationlist($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function roomreservations($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->roomreservations($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);				
	}
	public function storetypedetails($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->storetypedetails($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);				
	}
	public function roomcategeries($val)
	{

		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->roomcategeries($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);				
	}
	public function upselling($val)
	{

		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->upselling($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);				
	}
	public function selectroom($val)
	{

		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->selectroom($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);				
	}
	public function bookroom($val)
	{

		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->bookroom($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);				
	}	
	public function orderinvoice($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->orderinvoice($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function tablenames($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->tablenames($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function booktable($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
	
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->booktable($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function gallerylist($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->gallerylist($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function contestlist($val){
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->contestlist($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}
	public function contestdetaillist($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
		    $val['header_user_id'] = $usersid;
		$payload = Yii::$app->enduser->contestdetaillist($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);		
	}

    public function encrypt($string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'foodgenee_key';
        $secret_iv = 'foodgenee_iv';
        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);

        return trim($output);
    }


	public function meetme($val)
	{

		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->meetme($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);				
	}
	public function coupondetails($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->coupondetails($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);				
	}
	public function addwhislist($val)
	{

		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->addwhislist($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);				
	}

	public function getUserWhilist($val)
	{

		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->getUserWhilist($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);				
	}
	public function getStores($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->getStores($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);				
	}
	public function usermerchantpaymenttypes($val)
	{
		$headerslist = apache_request_headers();
		$usersid = base64_decode($headerslist['Authorization']);
		if(!empty($usersid)){
			$val['header_user_id'] = $usersid;
			$payload = Yii::$app->enduser->usermerchantpaymenttypes($val);
		}else{
			$payload = array('status'=>'0','message'=>'Invalid users details');
		}
		return $this->asJson($payload);				
	}


	public function pilotDemoRequests($val)
	{
		$model = new \app\models\PilotDemoRequests;
		$model->business_name = $_REQUEST['business_name'];
		$model->owner_name = $_REQUEST['owner_name'];
		$model->location = $_REQUEST['location'];
		$model->city = $_REQUEST['city'];
		$model->state = $_REQUEST['state'];
		$model->pincode = $_REQUEST['pincode'];
		$model->mobile_number = $_REQUEST['mobile_number'];
		$model->alt_mobile_number = $_REQUEST['alt_mobile_number'];
		$model->lat = $_REQUEST['lat'];
		$model->lng = $_REQUEST['lng'];
		$model->reg_date = date('Y-m-d H:i:s A');
		if(!$model->save()){
			$payload = ['status' => '0', 'message' => 'Error while requesting for a demo'];
		}else{
			$payload = ['status' => '1', 'message' => 'Requested for demo successfully!!'];
		}

		return $this->asJson($payload);				
	}


    public function addMerchantRating($val)
    {
        $headerslist = apache_request_headers();
        $usersid = base64_decode($headerslist['Authorization']);
        if(!empty($usersid)){
            $val['header_user_id'] = $usersid;
            $payload = Yii::$app->enduser->addMerchantRating($val);
        }else{
            $payload = array('status'=>'0','message'=>'Invalid users details');
        }
        return $this->asJson($payload);
    }

    public function getPilotFactors($val)
    {
        $headerslist = apache_request_headers();
        $usersid = base64_decode($headerslist['Authorization']);
        if(!empty($usersid)){
            $val['header_user_id'] = $usersid;
            $payload = ['status' => '1', 'factors' => PilotFactorRating::FACTORS];

        }else{
            $payload = array('status'=>'0','message'=>'Invalid users details');
        }
        return $this->asJson($payload);
    }

}
