<?php
namespace app\helpers;

use yii;
use app\models\FoodCategeries;
use app\models\Product;
use app\models\Tablename;
use app\models\Users;

class Utility{
	

    public static function foodtype_value($status)
    { 
	
	$foodCatFullDet = FoodCategeries::allcategeries();
	$foodCatDet = array_column($foodCatFullDet,'food_category','ID');
	
	return @$foodCatDet[$status];
    }
    public static function sectiontype_value($section_id)
    { 
	$sectionCatFullDet = 'select section_name from sections where ID = \''.$section_id.'\'';
	$sectionCatFullDet = Yii::$app->db->createCommand($sectionCatFullDet)->queryOne();
	return $sectionCatFullDet['section_name'];
    }
	public static function foodtype_value_another($status,$merchatid)
    { 
	
 	$sqlfoodCatFullDet = 'select * from food_categeries where merchant_id = \''.$merchatid.'\' order by ID desc';
	$foodCatFullDet = Yii::$app->db->createCommand($sqlfoodCatFullDet)->queryAll();
	$foodCatDet = array_column($foodCatFullDet,'food_category','ID');
	
	return $foodCatDet[$status];
    }
    public static function foodcategory_type($status)
    { 
	
 	$sqlfoodCatFullDet = 'select * from food_category_types where ID = \''.$status.'\'';
	$foodCatFullDet = Yii::$app->db->createCommand($sqlfoodCatFullDet)->queryOne();
	
	
	return @$foodCatFullDet['food_type_name'] ?? '';
    }
	public static function status_details($status){ 

	if($status==1){
		return 'Paid';
	}else if($status==2){
		return 'Failed';
	}else if($status==0){
		return 'Pending';
	}
	else if($status==3){
		return 'Partial Paid';
	}

	}

    public static function get_uniqueid($tablename,$text)
    {
	//$uniquedetails = $tablename::find()->select('MAX(ID) as id')->asArray()->one();
	$sqlunique = 'select MAX(ID) as id from '.$tablename.'';
	$uniquedetails = Yii::$app->db->createCommand($sqlunique)->queryOne();
	$uniqueId = $uniquedetails['id'];
	if(!empty($uniqueId)){
	    $newid = $uniqueId+1;
        }else{
	    $newid = 1;
        }
        return $text.sprintf('%04d',$newid);
    }
    public static function emp_uniqueid($tablename,$text,$merchantId=null)
    {
	//$uniquedetails = $tablename::find()->select('MAX(ID) as id')->asArray()->one();
	$sqlunique = 'select count(ID) as id from '.$tablename.' where merchant_id = coalesce(\''.$merchantId.'\',merchant_id)';
	$uniquedetails = Yii::$app->db->createCommand($sqlunique)->queryOne();
	$uniqueId = $uniquedetails['id'];
	if(!empty($uniqueId)){
	    $newid = $uniqueId+1;
        }else{
	    $newid = 1;
        }
        return $text.sprintf('%04d',$newid);
    }	
    public static function table_details($id,$type)
	{
		$tableDet = Tablename::findOne($id);	
			if(!empty($tableDet)){
				return  $tableDet[$type] ?: '';
			}
	}
	public static function user_details($id,$type)
	{
		$userDet = Users::findOne($id);
			if(!empty($userDet)){
		return  $userDet[$type] ?: '';
			}
			
	}
	public static function product_details($id,$type)
	{
		$prodDet = \app\models\Product::findOne($id);
			if(!empty($prodDet)){
		return  $prodDet[$type] ?: '';
			}
			
	}
	public static function food_cat_qty_det($id,$type)
	{
		$food_cat_qty_det = \app\models\FoodCategoryTypes::findOne($id);
		if(!empty($food_cat_qty_det)){
			return  $food_cat_qty_det[$type] ?: '';
		}
	}
	public static function merchant_details($id,$type){
	if($id){ 
	$sqlmodule_name = 'select * from merchant where ID = \''.$id.'\'';
	$module_name = Yii::$app->db->createCommand($sqlmodule_name)->queryOne();
		if(!empty($module_name)){
			if($type=="logo"){
				if(file_exists(PATH_NAME.'/merchantimages/'.$module_name['logo'])){
						return MERCHANT_LOGO.$module_name['logo'];
					}else{
						return SITE_URL.'images/dummy-logo.png';
					}
			}else{
			return  $module_name[$type] ?: '';
			}
		}
	}

}
public static function order_details($id,$type){
	if($id){

	$sqlmodule_name = 'select * from orders where ID = \''.$id.'\'';
	$module_name = Yii::$app->db->createCommand($sqlmodule_name)->queryOne();
		if(!empty($module_name)){
	return  $module_name[$type] ?: '';
		}
	}
}
public static function rewards_details($id,$type){  
	if($id){ 
	$sqlmodule_name = 'select * from rewards where ID = \''.$id.'\'';
	$module_name = Yii::$app->db->createCommand($sqlmodule_name)->queryAll();
		if(!empty($module_name)){
			if($type=="logo"){
				if(!empty($module_name['logo'])){
				if(file_exists(PATH_NAME.'/rewardsimage/'.$module_name['logo'])){
						return REWARDS_IMAGES.$module_name['logo'];
					}else{
						return SITE_URL.'images/boy.png';
					}
					}else{
						return SITE_URL.'images/boy.png';
				}
			}elseif($type=="cover"){
				if(!empty($module_name['cover'])){
				if(file_exists(PATH_NAME.'/rewardsimage/'.$module_name['cover'])){
						return REWARDS_IMAGES.$module_name['cover'];
					}else{
						return SITE_URL.'images/boy.png';
					}
					}else{
						return SITE_URL.'images/boy.png';
				}
			}else{
			return  $module_name[$type] ?: '';
			}
		}
	}
}
 public static function tablereservations_status($status){ 

	if($status==0){
		return 'Pending';
	}else if($status==1){
		return 'Approved';
	}else if($status==2){
		return 'Reject';
	}else if($status==3){
		return 'Visited';
	}

}  
	public static function orderstatus_details($status, $prearationTime = null, $prearationDate = null)
	{
		if($status == '1' && $prearationTime > 0 && $prearationTime != '0000-00-00 00:00:00' && empty($prearationDate)){
			return 'Preparing';
		}
		else if($status == '1' && $prearationTime > 0 && !empty($prearationDate)){
			return 'Prepared';
		}
		else if($status==1) {
			return 'Accepted';
		}
		else if($status==2) {
			return 'Served';
		}else if($status==0) {
			return 'New';
		}else if($status==3) {
			return 'Canceled';
		}else if($status==4) {
			return 'Delivered';
		}
		else if($status==5) {
			return 'Available';
		}
	}
	public static function order_id($merchant,$type)
	{
		$merchantdetails = \app\models\Merchant::findOne($merchant);
		$sqlprevmerchnat = 'select MAX(ID) as id from orders';
		$prevmerchnat = Yii::$app->db->createCommand($sqlprevmerchnat)->queryOne();
		$newid = $prevmerchnat['id']+1; 
		if($type=='order'){
			return strtoupper('SP'.$merchant.sprintf('%07d',$newid));
		}
		if($type=='transaction'){
			return strtoupper('SPTX'.$merchant.sprintf('%07d',$newid));
		}
	}
	public static function encrypt($string){

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
    public static function orderflowdropdown($status)
    {
		if($status == '0'){
			return ['0'=>'New','1' => 'Accept','3' => 'Cancel'];
		}
		else if($status == '1' )
		{
			return ['1' => 'Accepted','2' => 'Served','3' => 'Canceled'];
		}
		else if($status == '2')
		{
			return ['2' => 'Served','4' => 'Completed'];
		}
	} 
	public static function ingredientcbtoob()
	{
		$stockArr = [];
		$stockDet = \app\models\IngredientStockRegister::find()->where(['merchant_id'=>Yii::$app->user->identity->ID
		,'created_on'=>date('Y-m-d')])->asArray()->all();
		if(count($stockDet) == 0)
		{
				$sqlMaxCreatedOn = 'select max(created_on) created_on from ingredient_stock_register';
				$resPrev = Yii::$app->db->createCommand($sqlMaxCreatedOn)->queryAll();
				if(count($resPrev) > 0)
				{
					$stockPrevDet = \app\models\IngredientStockRegister::find()->where(['merchant_id'=>Yii::$app->user->identity->ID
					,'created_on'=>$resPrev[0]['created_on']])->asArray()->all();
					for($i=0;$i<count($stockPrevDet);$i++)
					{
						if($stockPrevDet[$i]['closing_stock'] > 0){
						$stockArr[] = [Yii::$app->user->identity->ID,$stockPrevDet[$i]['ingredient_id']
											,$stockPrevDet[$i]['ingredient_name']
											,$stockPrevDet[$i]['closing_stock']
											,0,0,0,$stockPrevDet[$i]['closing_stock']
											,date('Y-m-d h:i:s'),date('Y-m-d')];
	
						}
					}
				}
		}
		return $stockArr;
	}
	public static function purchase_quantity_type($type=null)
	{
		$quantityArr = ["1"=>"Kg","2"=>"Litre","3"=>"Pieces","4"=>"Packets","5"=>"Grams","6"=>"Milli Litre"];
		if(!empty($type))
		{
			$arr = $quantityArr[$type];
		}
		else
		{
			$arr = $quantityArr;
		}
		return $arr;
	} 
	public static function otp_sms1($mobilenumbers,$message){
       $message = urlencode($message);
	$curl = curl_init();
	$smsid = 'FOODQR';
	
	$smsarray = array();
	$smsarray['sender'] = $smsid;
	$smsarray['route'] = '106';
	$smsarray['country'] = '91';
	$smsarray['sms'] = array(array("to"=>array($mobilenumbers),'message'=>$message));
	$authentication_key = '318274A8Ym3ky6q5e4661fbP1';
  
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.msg91.com/api/v2/sendsms",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($smsarray),
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_HTTPHEADER => array(
    "authkey: $authentication_key",
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  return "cURL Error #:" . $err;
} else {
  return $response;
}
 
     }
     public static function otp_sms($mobilenumbers,$message,$otp = 1234){
       $message = urlencode($message);
	$curl = curl_init();
	$smsid = 'FOODQR';
	
	$smsarray = array();
	$smsarray['sender'] = $smsid;
	$smsarray['route'] = '106';
	$smsarray['country'] = '91';
	$smsarray['sms'] = array(array("to"=>array($mobilenumbers),'message'=>$message));
	$authentication_key = '318274A8Ym3ky6q5e4661fbP1';
  
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.msg91.com/api/v5/otp?template_id=617a9f5cd80e9118375d6ed1&mobile=91$mobilenumbers&authkey=318274A8Ym3ky6q5e4661fbP1",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "{\"OTP\":\"$otp\"}",
  CURLOPT_HTTPHEADER => array(
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
 // echo $response;
}

 
     }
	 public static function sendFCM($id,$title,$message,$imageurl=false,$type=null,$encykey=null,$orderid=null) {
if(!empty($id)){
	$imageurl = $imageurl ?: '';
    $api_key = "AAAAbL_6cuU:APA91bFMr3gEaAHBgPsZlB0Qnp9DICD9xBSP0hRl0kDehZEvFm82CrNr_xsthGTuK_8dAM0gXO5lDnUeJ33OUQkmEKvOVNYbIqQM9op4U5CY7OSXqc0FlEs4opTwXzviQhRIojgLW0-S";

    $url = 'https://fcm.googleapis.com/fcm/send';
 
    $fields = array (
        'to' =>  $id,   
		"priority" => "high",
        'data' => array (
			   "content" => $message,
			   "type" => $type ?? '1',
			   "encykey" => $enckey ?? '',
			   "title" => $title, 
			   "image" => $imageurl, 
			   "orderid" => $orderid,
			   "icon" => SITE_URL."foodq-icon72.png"
        ),
		"text" => $title,
    );
    $headers = array(
        'Content-Type:application/json',
        'Authorization: key='.$api_key
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
}
}

	 public static function sendPilotFCM($id,$title,$message,$imageurl=false,$type=null,$encykey=null,$orderid=null) {
if(!empty($id)){
	$imageurl = $imageurl ?: '';
    $api_key = "AAAAbL_6cuU:APA91bFMr3gEaAHBgPsZlB0Qnp9DICD9xBSP0hRl0kDehZEvFm82CrNr_xsthGTuK_8dAM0gXO5lDnUeJ33OUQkmEKvOVNYbIqQM9op4U5CY7OSXqc0FlEs4opTwXzviQhRIojgLW0-S";

    $url = 'https://fcm.googleapis.com/fcm/send';
 
    $fields = array (
        'to' =>  $id,   
		"priority" => "high",
        'data' => array (
			   "content" => $message,
			   "body" => $message,
			   
			   "type" => $type ?? '1',
			   "encykey" => $enckey ?? '',
			   "title" => $title, 
			   "image" => $imageurl, 
			   "orderid" => $orderid,
        ),
		"text" => $title,
    );
    $headers = array(
        'Content-Type:application/json',
        'Authorization: key='.$api_key
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
}
}


	 public static function sendNewFCM($id,$title,$message,$imageurl=false,$type=null,$encykey=null,$orderid=null,$notificationdet = []) {
if(!empty($notificationdet)){
    
}
	$imageurl = $imageurl ?: '';
    $api_key = "AAAAtGpeZ64:APA91bEr9V1P9DWoGnvFqXrvoY1DN_gEutreMpxWiPFrzHd1_Zzn7GjtrNxhWjnjxpxSclMeT8QTKymldAGHOnLNwLurHFl9Bz65OmaLUZkx8GCb4MWnDU-OLYYhxQjCJZfHJ6X90uyq";

    $url = 'https://fcm.googleapis.com/fcm/send';
 
    $fields = [
	"registration_ids" => [
	$id
	],
	"data" => [
        "click_action" => "FLUTTER_NOTIFICATION_CLICK",
        "type" => !empty($notificationdet['type']) ? $notificationdet['type'] : "" ,
        "title" => $title,
        "body" => $message,
        "order_id" => !empty($notificationdet['order_id']) ? $notificationdet['order_id'] : "",
        "table" => !empty($notificationdet['tablename']) ? $notificationdet['tablename'] : "",
        "payment_mode" => "Online",
        "amount" => !empty($notificationdet['orderamount']) ? $notificationdet['orderamount'] : 0,
       	"user_name" => !empty($notificationdet['username'])  ? $notificationdet['username'] : '',
        "sound" => "audio.mp3",
        "page_name" => "screen 1",
        "image_url" => "",
		"user_id" => !empty($notificationdet['user_id'])  ? $notificationdet['user_id'] : "",
		"section_name" => !empty($notificationdet['section_name'])  ? $notificationdet['section_name'] : "",
		"merchant_id" => !empty($notificationdet['merchant_id'])  ? $notificationdet['merchant_id'] : ""
    ]
];
//if(!empty($notificationdet))
//{
//    echo json_encode($fields);exit;
//}
    $headers = array(
        'Content-Type:application/json',
        'Authorization: key='.$api_key
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;

}

    public static function user_image($userid)
    {
    
        $sqluserde = "select profilepic from users where ID = ".$userid."";
        $userde = Yii::$app->db->createCommand($sqluserde)->queryOne();
        if(!empty($userde['profilepic'])){ 
          $imagePath =  '../../userprofilepic/'.$userde['profilepic'];
        	if(file_exists($imagePath)){
        	 		$iamge =  SITE_URL.'userprofilepic/'.$userde['profilepic'];
        	}else{
        			$iamge =  SITE_URL.'dummy-product.png';
        	}
        }else{ 
        			$iamge =  SITE_URL.'dummy-product.png'; 
        }
        return $iamge; 
    }

public static function coins_deduct($id,$amt){

	$userid = $id ; 

	$sqlcoins_amt = "select * from users where ID = ".$userid."";
	$coins_amt = Yii::$app->db->createCommand($sqlcoins_amt)->queryOne();

	if(!empty($coins_amt['ID'])){

		if((int)$coins_amt['coins']>=$amt){

		$coins = $coins_amt['coins'] - $amt;

		$sqlUpdate = "update users set coins = ".$coins." where ID = ".$coins_amt['ID']."";
		$resUpdate = Yii::$app->db->createCommand($sqlcoins_amt)->execute();
		}else{

		$message = 'Insufficient funds';

		return $message;

		}

	}else{

		$message = 'coins amt does not exits';

		return $message;

	}

}

public static function coins($uid){

	$userid = $uid; 

	$sqlcoinsamt = "select coins from users where ID = ".$userid."";
	$coinsamt = Yii::$app->db->createCommand($sqlcoinsamt)->queryOne();

	return (int)$coinsamt['coins'] ?: 0;

}

public static function coinstxn_id(){

		$sql_lecturer_name = "select MAX(ID) as id from coins_transactions";
		$lecturer_name = Yii::$app->db->createCommand($sql_lecturer_name)->queryOne();
		
		$id = $lecturer_name['id']+1;
		$name = "FDQCT".$id;
		return $name;

}	
public static  function haversineGreatCircleDistance(
  $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
{
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  return $angle * $earthRadius;
}
	

    public static function serviceboy_details($id,$type){ 
	
	if($id){ 
	$sqlmodule_name = 'select * from serviceboy where ID = \''.$id.'\'';
	$module_name = Yii::$app->db->createCommand($sqlmodule_name)->queryOne();
		if(!empty($module_name)){
			if($type=="profilepic"){
				if(!empty($module_name['profilepic'])){
				if(file_exists(PATH_NAME.'/serviceboyprofilepic/'.$module_name['profilepic'])){
						return SERVICEBOY_IMAGE.$module_name['profilepic'];
					}else{
						return SITE_URL.'images/boy.png';
					}
					}else{
						return SITE_URL.'images/boy.png';
				}
			}else{
			return  $module_name[$type] ?: '';
			}
		}
	}else{
	    //echo "fd";exit;
	}
	}
	public static function coins_update($id,$amt){

	

	$userid = $id ; 

	$amt = (int)$amt;

	$sqlcoins_amt = "select * from users where ID = ".$userid."";
	$coins_amt = Yii::$app->db->createCommand($sqlcoins_amt)->queryOne();

	if(!empty($coins_amt['ID'])){

		$total_amt = (int)$coins_amt['coins'] + $amt;

		$sqlresult = "update users set coins = ".$total_amt." where ID = ".$coins_amt['ID']."";
		$result = Yii::$app->db->createCommand($sqlresult)->execute();

	} 
	

}
	public static function serviceboy_image($userid){
	  
$sqluserde = "select profilepic from serviceboy where ID = ".$userid."";
$userde = Yii::$app->db->createCommand($sqluserde)->queryOne();
if(!empty($userde['profilepic'])){
    
    $imagePath =  '../../serviceboyprofilepic/'.$userde['profilepic'];
	if(file_exists($imagePath)){
			$iamge =  SITE_URL.'serviceboyprofilepic/'.$userde['profilepic'];
	}else{
			$iamge =  SITE_URL.'dummy-product.png';
	}
}else{ 
			$iamge =  SITE_URL.'dummy-product.png'; 
}
return $iamge; 
}
	public static function decrypt($string){

	 $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'foodgenee_key';
    $secret_iv = 'foodgenee_iv';
    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
  
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
   
    return trim($output);


} 
public static function send_sms($mobilenumbers,$message){
       $message = urlencode($message); 
 
	$url="http://roundsms.com/api/sendhttp.php?authkey=OGQxNTZkYTc3M2I&mobiles=".$mobilenumbers."&message=".$message."&sender=FOODQR&type=1&route=2"; 
  
$contents = file_get_contents($url); 
return $contents;
}
 public static function get_parcel_uniqueid()
    {
	//$uniquedetails = $tablename::find()->select('MAX(ID) as id')->asArray()->one();
	$sqlunique = 'select count(ID) as id from orders where date(reg_date) = \''.date('Y-m-d').'\'' ;
	$uniquedetails = Yii::$app->db->createCommand($sqlunique)->queryOne();
	$uniqueId = $uniquedetails['id'];
	if(!empty($uniqueId)){
	    $newid = $uniqueId+1;
        }else{
	    $newid = 1;
        }
        return $newid;
    }
       public static function employee_details($id,$type)
	{
		$empDet = \app\models\MerchantEmployee::findOne($id);	
			if(!empty($empDet)){
				return  $empDet[$type] ?: '';
			}
	}
	public static function hourRange($val){
	    $hrRange = ['0'=>'12 AM','1'=>'1 AM','2'=>'2 AM','3'=>'3 AM','4'=>'4 AM','5'=>'5 AM','6'=>'6 AM','7'=>'7 AM','8'=>'8 AM','9'=>'9 AM','10'=>'10 AM','11'=>'11 AM',
'12'=>'12 PM','13'=>'1 PM','14'=>'2 PM','15'=>'3 PM','16'=>'4 PM','17'=>'5 PM','18'=>'6 PM','19'=>'7 PM','20'=>'8 PM','21'=>'9 PM','22'=>'10 PM','23'=>'11 PM'
];
	if(!empty($val)){
	    return $hrRange[$val];	    
	}else{
	    return $hrRange; 
	}
	    
	}
	public static function monthRange($val)
	{
	    $monRange = ['1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'Jun','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov',
						'12'=>'Dec'
					];

		if (!empty($val)) {
			return $monRange[$val];
		}
		else {
			return $monRange;
		}
	    
	}

	public static function discountTypes()
	{
	    $discountArr = ['1'=>'Overall', '2'=>'Percentage', '3'=>'Itemwise'];

	    return $discountArr;
	}

	/**
	 * @param int $days
	 * @return false|string
	 */
	public function lastDesiredDate(int $days)
	{
		return date('Y-m-d', strtotime('-'.$days.' days'));
	}

	/**
	 * @param int $val
	 * @return string
	 */
	public static function orderTypeText($val)
	{
		if($val == '1' || $val == '3') {
			$orderType = 'Online Order';
		}else{
			$orderType = 'Offline Order';
		}

		return $orderType;
	}
}
?>
