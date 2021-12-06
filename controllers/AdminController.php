<?php

namespace app\controllers;
use yii;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use app\helpers\Utility;
use \app\models\FoodCategeries;
use \app\models\Users;
use \app\models\Feedback;
use \app\models\Appnotifications;
use \app\models\Banners;
use \app\models\Rewards;
use \app\models\MerchantCoupon;
use \app\models\Merchant;
use \app\models\MerchantPaytypes;
use yii\db\Query;


class AdminController extends GoController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
	public function actionCategeries(){
		$categoryModel = FoodCategeries::find()
		->orderBy([
            'ID'=>SORT_DESC
        ])
	->asArray()->all();
	return $this->render('categorylist',['categoryModel'=>$categoryModel]);
	}
	public function actionUserdata(){
		$usersModel = Users::find()
		->orderBy([
            'ID'=>SORT_DESC
        ])
	->asArray()->all();
	return $this->render('usersData',['usersModel'=>$usersModel]);

	}
	public function actionRating(){
		extract($_POST);
		$sdate = $sdate ?? date('Y-m-d');
		$edate = $edate ?? date('Y-m-d');
		
		$merchants = \app\models\Merchant::find()->asArray()->all();
		
		$sqlrating = 'select m.name merchant_name,m.storename,u.name as user_name,fd.*,ord.serviceboy_id,ord.order_id, ord.totalamount,sb.name service_boy_name
		from feedback fd
		inner join orders ord on fd.order_id=ord.id
		left join serviceboy sb on ord.serviceboy_id = sb.ID
		left join users u on fd.user_id = u.ID
		left join merchant m on fd.merchant_id = m.ID 
		where fd.reg_date between \''.$sdate.'\' and \''.$edate.'\'';
		if(!empty($merchantId)){
		$sqlrating .= ' and  fd.merchant_id=\''.$merchantId.'\'';
		}
		else{
			$merchantId = '';
		}
		$ratingModel=Yii::$app->db->createCommand($sqlrating)->queryAll();		
		return $this->render('rating',['ratingModel'=>$ratingModel
		,'sdate'=>$sdate,'edate'=>$edate,'merchants'=>$merchants,'merchantId'=>$merchantId]);
	}
	public function actionAppnotifications(){
		
		$appNotificationModel = Appnotifications::find()
		->orderBy([
            'ID'=>SORT_DESC
        ])
		->asArray()->all();
		$model = new Appnotifications;
if ($model->load(Yii::$app->request->post()) ) {

		$model->admin_id = (string)Yii::$app->user->identity->ID;
		$model->reg_date = date('Y-m-d h:i:s');
		$model->mod_date = date('Y-m-d h:i:s');
		$image = UploadedFile::getInstance($model, 'image');
		if($image){
			$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$image->extension;
			$image->saveAs('uploads/notificationimage/' . $imagename);
			$model->image = $imagename;
		}
		if($model->validate()){
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'APP Notification',
		'text' => 'APP Notification Added Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
			$model->save();

		return	$this->redirect('appnotifications');
		}
		else
		{
		//	echo "<pre>";print_r($model->getErrors());exit;
		}
	}

		return $this->render('appNotifications',['appNotificationModel'=>$appNotificationModel,'model'=>$model]);

	}
	public function actionEditappnotificationpopup()
    {
		extract($_POST);
		$rewardsModel = Appnotifications::findOne($id);
		return $this->renderAjax('editapnotificationpopup', ['model' => $rewardsModel,'id'=>$id]);	
    }
	public function actionUpdateappnotification()
	{
		extract($_POST);
$model = new Appnotifications;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$appnotifyArr = Yii::$app->request->post('Appnotifications');
		$appnotifyUpdate = Appnotifications::findOne($_POST['Appnotifications']['ID']);
		$appnotifyImage = $appnotifyUpdate['image'];

		$appnotifyUpdate->attributes = \Yii::$app->request->post('Appnotifications');
		$image = UploadedFile::getInstance($model, 'image');
			if($image){
				if(!empty($appnotifyUpdate['image'])){
					$imagePath =  '../../'.Url::to(['/uploads/notificationimage/'. $appnotifyUpdate['logo']]);
					if(file_exists($imagePath)){
						unlink($imagePath);	
					}
				}
				
				$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$image->extension;
				$image->saveAs('uploads/notificationimage/' . $imagename);
				$appnotifyUpdate->image = $imagename;
			}else{
				$appnotifyUpdate->image = $appnotifyImage;
			}
			
			
			
		if($appnotifyUpdate->validate()){
			$appnotifyUpdate->save();
		}
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'APP Notification',
		'text' => 'APP Notification Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		return $this->redirect('appnotifications');

	}
	public function actionBannerdetails(){
		$status = '1';
		$bannerdet = Banners::find()
		
		->orderBy([
            'ID'=>SORT_DESC
        ])
		->asArray()->all();
		$model = new Banners;
				if ($model->load(Yii::$app->request->post()) ) {
			$MerchantGalleryArr = Yii::$app->request->post('Banners');
			$model->user_id = (string)Yii::$app->user->identity->ID;
			$model->reg_date = date('Y-m-d h:i:s');
			$model->status = '1';
			$image = UploadedFile::getInstance($model, 'image');
			if($image){
				$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$image->extension;
				$image->saveAs('uploads/bannerimage/' . $imagename);
				$model->image = $imagename;
			}
			if($model->validate()){
				$model->save();
	Yii::$app->getSession()->setFlash('success', [
        'title' => 'Banner',
		'text' => 'Banner Uploaded Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
				return $this->redirect('bannerdetails');
			}
			else
			{
				echo "<pre>";print_r($model->getErrors());exit;
			}
		}
		return $this->render('bannerDetails',['bannerdet'=>$bannerdet,'model'=>$model]);
	}
	public function actionDeletebanner()
	{
		extract($_POST);
				$bannerDet = Banners::findOne($id);
		if($bannerDet['image']){
				$imagePath =  '../../'.Url::to(['/uploads/bannerimage/'. $bannerDet['image']]);
					if(file_exists($imagePath)){
					unlink($imagePath);	
					}
			\Yii::$app->db->createCommand()->delete('banners', ['id' => $id])->execute();
		}
	}
	public function actionEditbannerpopup()
    {
		extract($_POST);
		$rewardsModel = Banners::findOne($id);
		return $this->renderAjax('editbannerpopup', ['model' => $rewardsModel,'id'=>$id]);	
    }
	public function actionUpdatebanner()
	{
		extract($_POST);
		$model = new Banners;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$bannerArr = Yii::$app->request->post('Banners');
		$bannerUpdate = Banners::findOne($_POST['Banners']['ID']);
		$bannerOldImage = $bannerUpdate['image'];

		$bannerUpdate->attributes = \Yii::$app->request->post('Banners');
		$image = UploadedFile::getInstance($model, 'image');
			if($image){
				if(!empty($bannerUpdate['image'])){
					$imagePath =  '../../'.Url::to(['/uploads/bannerimage/'. $bannerUpdate['logo']]);
					if(file_exists($imagePath)){
						unlink($imagePath);	
					}
				}
				
				$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$image->extension;
				$image->saveAs('uploads/bannerimage/' . $imagename);
				$bannerUpdate->image = $imagename;
			}else{
				$bannerUpdate->image = $bannerOldImage;
			}
			
			
			
		if($bannerUpdate->validate()){
			$bannerUpdate->save();
		}
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'Banner',
		'text' => 'Banner Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		return $this->redirect('bannerdetails');

	}
	public function actionDeleteappnotification()
	{
		extract($_POST);
				$appnotificationsDet = Appnotifications::findOne($id);
		if($appnotificationsDet['image']){
				$imagePath =  '../../'.Url::to(['/uploads/notificationimage/'. $appnotificationsDet['image']]);
					if(file_exists($imagePath)){
					unlink($imagePath);	
					}
			\Yii::$app->db->createCommand()->delete('appnotifications', ['id' => $id])->execute();
		}
	}
	public function actionDeleteuser()
	{
		extract($_POST);
				$usersDet = Users::findOne($id);
		if($usersDet['ID']){
			\Yii::$app->db->createCommand()->delete('users', ['id' => $id])->execute();
		}
	}

	public function actionRewards(){
		$rewardsdet = Rewards::find()
		->orderBy([
            'ID'=>SORT_DESC
        ])
		->asArray()->all();
		$model = new Rewards;
		$model->scenario = 'insertphoto';
		if ($model->load(Yii::$app->request->post()) ) {
		$model->user_id  = (string)Yii::$app->user->identity->ID;
		$model->soldout = '0';
		$model->status = '1';
		$model->validity = '1';
		$model->reg_date = date('Y-m-d h:i:s');
		$model->mod_date = date('Y-m-d h:i:s');
		$image = UploadedFile::getInstance($model, 'logo');
		if($image){
			$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$image->extension;
			$image->saveAs('uploads/rewardsimage/' . $imagename);
			$model->logo = $imagename;
		}
		$coverimage = UploadedFile::getInstance($model, 'cover');
		if($coverimage){
			$coverimagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$coverimage->extension;
			$coverimage->saveAs('uploads/rewardsimage/' . $coverimagename);
			$model->cover = $coverimagename;
		}
		
		if($model->validate()){
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'Reward',
		'text' => 'Reward Created Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
			$model->save();

		return	$this->redirect('rewards');
		}
		else
		{
		//	echo "<pre>";print_r($model->getErrors());exit;
		}
	}
		return $this->render('rewards',['rewardsdet'=>$rewardsdet,'model'=>$model]);
	}
	public function actionEditrewardpopup()
	{
		extract($_POST);
		$rewardsModel = Rewards::findOne($id);
		return $this->renderAjax('editrewardpopup', ['model' => $rewardsModel,'id'=>$id]);		
	}
	public function actionUpdatereward()
	{
		extract($_POST);
		$model = new Rewards;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$rewardArr = Yii::$app->request->post('Rewards');
		$rewardUpdate = Rewards::findOne($_POST['Rewards']['ID']);
		$rewardLogo = $rewardUpdate['logo'];
		$rewardCover = $rewardUpdate['cover'];
		$rewardUpdate->attributes = \Yii::$app->request->post('Rewards');
		$image = UploadedFile::getInstance($model, 'logo');
			if($image){
				if(!empty($rewardUpdate['logo'])){
					$imagePath =  '../../'.Url::to(['/uploads/rewardsimage/'. $rewardUpdate['logo']]);
					if(file_exists($imagePath)){
						unlink($imagePath);	
					}
				}
				
				$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$image->extension;
				$image->saveAs('uploads/rewardsimage/' . $imagename);
				$rewardUpdate->logo = $imagename;
			}else{
				$rewardUpdate->logo = $rewardLogo;
			}
			$coverimage = UploadedFile::getInstance($model, 'cover');
			if($coverimage){
				if(!empty($rewardUpdate['cover'])){
					$imagePath =  '../../'.Url::to(['/uploads/rewardsimage/'. $rewardUpdate['cover']]);
					if(file_exists($imagePath)){
						unlink($imagePath);	
					}
				}
				
				$coverimagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$coverimage->extension;
				$coverimage->saveAs('uploads/rewardsimage/' . $coverimagename);
				$rewardUpdate->cover = $coverimagename;
			}else{
				$rewardUpdate->cover = $rewardCover;
			}
			
			
		if($rewardUpdate->validate()){
			$rewardUpdate->save();
		}
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'Reward',
		'text' => 'Reward Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		return $this->redirect('rewards');
	}
	public function actionCoupons(){
		$sqlcoupons = "select * from merchant_coupon where merchant_id <= '0' order by ID desc";
		$coupondet = Yii::$app->db->createCommand($sqlcoupons)->queryAll();
		$model = new \app\models\MerchantCoupon;
		if ($model->load(Yii::$app->request->post()) ) {
		$MerchantCouponArr = Yii::$app->request->post('MerchantCoupon');
			$model->merchant_id = '0';
			$model->status = 'Active';
			$model->minorderamt = 0;
			$model->reg_date = date('Y-m-d h:i:s');
			$model->validity = '0';
			if($model->validate()){
				$model->save();
					Yii::$app->getSession()->setFlash('success', [
        'title' => 'Coupon',
		'text' => 'Coupon Added Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
				return $this->redirect('coupons');
			}
			else
			{
				echo "<pre>";print_r($model->getErrors());exit;
			}
		}
		return $this->render('coupons',['coupondet'=>$coupondet,'model'=>$model]);
	}
	public function actionEditcouponpopup()
	{
		extract($_POST);
		$couponModel = MerchantCoupon::findOne($id);
		return $this->renderAjax('editcouponpopup', ['model' => $couponModel,'id'=>$id]);
	}
public function actionUpdatecoupon()
	{
		extract($_POST);
		$model = new MerchantCoupon;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$couponArr = Yii::$app->request->post('MerchantCoupon');
		$couponUpdate = MerchantCoupon::findOne($_POST['MerchantCoupon']['ID']);

		$couponUpdate->attributes = \Yii::$app->request->post('MerchantCoupon');
			
			
		if($couponUpdate->validate()){
			$couponUpdate->save();
		}
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'Coupon',
		'text' => 'Coupon Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		return $this->redirect('coupons');
	}
	public function actionMerchants(){
		$model = new Merchant;
		$model->scenario = 'insertemail';
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		if(!empty($_POST['storetype'])){
			$merchants = Merchant::find()
			->where(['storetype'=>$_POST['storetype']])
			->orderBy([
				'ID'=>SORT_DESC
			])
			->asArray()->all();
		}else{
			$merchants = Merchant::find()
			->orderBy([
				'ID'=>SORT_DESC
			])
			->asArray()->all();
			
		}
		$model = new Merchant;
if ($model->load(Yii::$app->request->post()) ) {
	$MerchantArr = Yii::$app->request->post('Merchant');
		
			$model->user_id = '1';
			$model->unique_id = Utility::get_uniqueid('merchant','FDME');
			$model->password = password_hash($MerchantArr['password'], PASSWORD_DEFAULT);
			$model->otp = 0;
			$model->status = '1';
			$model->reg_date = date('Y-m-d h:i:s');
			$logoimage = UploadedFile::getInstance($model, 'logo');
			$qrlogoimage = UploadedFile::getInstance($model, 'qrlogo');
			$coverpicimage = UploadedFile::getInstance($model, 'coverpic');	
			if($logoimage){
				$logoimagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$logoimage->extension;
				$logoimage->saveAs('../../merchantimages/' . $logoimagename);
				$model->logo = $logoimagename;

			}
	
			if($qrlogoimage){
				$qrlogoimagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$qrlogoimage->extension;
				$qrlogoimage->saveAs('../../merchantimages/' . $qrlogoimagename);
				$model->qrlogo = $qrlogoimagename;
			}
			if($coverpicimage){
				$coverpicimageimagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$coverpicimage->extension;
				$coverpicimage->saveAs('../../merchantimages/' . $coverpicimageimagename);
				$model->coverpic = $coverpicimageimagename;
			}
			if($model->validate()){
				$model->save();
				
			$modelEmp = new \app\models\MerchantEmployee;
			$modelEmp->merchant_id = (string)$model->ID;
			$modelEmp->emp_id =  Utility::get_uniqueid('merchant','FDME');
			$modelEmp->emp_name = $MerchantArr['name'];
			$modelEmp->emp_email = $MerchantArr['email'];
			$modelEmp->emp_phone = $MerchantArr['mobile'];
			$modelEmp->emp_password = password_hash($MerchantArr['password'], PASSWORD_DEFAULT);
			$modelEmp->emp_role = 0;
			$modelEmp->emp_exp = 0;
			$modelEmp->date_of_join = date('Y-m-d');
			$modelEmp->emp_designation = 'OWNER';
			$modelEmp->emp_specialities = 'OWNER';
			$modelEmp->emp_status = '1';
			$modelEmp->emp_salary = 0;
			$modelEmp->reg_date = date('Y-m-d h:i:s');
			$modelEmp->mod_date = date('Y-m-d h:i:s');
			$modelEmp->created_by = Yii::$app->user->identity->name;
			if($modelEmp->validate()){
					$modelEmp->save();
			}
			else{
				echo "<pre>";print_r($modelEmp->getErrors());exit;
			}
			
			$modelRole =new \app\models\EmployeeRole;
			$modelRole->merchant_id = (string)$model->ID;
			$modelRole->role_name = 'OWNER';
			$modelRole->role_status = 1;
			$modelRole->created_by = Yii::$app->user->identity->name;
			$modelRole->reg_date = date('Y-m-d h:i:s');
			$modelRole->save();
			
			$modelRole =new \app\models\EmployeeRole;
			$modelRole->merchant_id = (string)$model->ID;
			$modelRole->role_name = 'PILOT';
			$modelRole->role_status = 1;
			$modelRole->created_by = Yii::$app->user->identity->name;
			$modelRole->reg_date = date('Y-m-d h:i:s');
			$modelRole->save();
			
					Yii::$app->getSession()->setFlash('success', [
        'title' => 'Merchant',
		'text' => 'Merchant Added Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
				return $this->redirect('merchants');
			}
			else
			{
				echo "<pre>";print_r($model->getErrors());exit;
			}
		}
		return $this->render('merchants',['merchantslist'=>$merchants,'model'=>$model]);
	}
	public function actionEditmerchantpopup()
	{
		extract($_POST);
		$merchantModel = Merchant::findOne($id);
		return $this->renderAjax('editmerchantpopup', ['model' => $merchantModel,'id'=>$id]);
	}
	public function actionUpdatemerchant()
	{
		extract($_POST);
		$model = new Merchant;
		$model->scenario = 'updateemail';
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		

		$merchantArr = Yii::$app->request->post('Merchant');
		$model->password = password_hash($merchantArr['password'], PASSWORD_DEFAULT);
			
		$merchantUpdate = Merchant::findOne($_POST['Merchant']['ID']);
		if($merchantUpdate['password'] != '')
		{
			$newpassword = password_hash($merchantArr['password'], PASSWORD_DEFAULT);
			$sqlupdateMerchantUser = 'update merchant_employee	 set emp_password = \''.$newpassword.'\' 
			where merchant_id = \''.$_POST['Merchant']['ID'].'\' and emp_role = \'0\'';
		$updateMerchantUser = Yii::$app->db->createCommand($sqlupdateMerchantUser)->execute();	
		}
		else{
			$newpassword = $merchantArr['password'];
		}
		if($merchantUpdate['mobile'] != $merchantArr['mobile'])
		{
			$sqlupdateMerchantEmail = 'update merchant_employee	 set emp_phone = \''.$merchantArr['mobile'].'\' 
					where merchant_id = \''.$_POST['Merchant']['ID'].'\' and emp_role = \'0\'';
		$updateMerchantEmail = Yii::$app->db->createCommand($sqlupdateMerchantEmail)->execute();	
   
		}
		if($merchantUpdate['email'] != $merchantArr['email'])
		{
			$sqlupdateMerchantEmail = 'update merchant_employee	 set emp_email = \''.$merchantArr['email'].'\' 
					where merchant_id = \''.$_POST['Merchant']['ID'].'\' and emp_role = \'0\'';
		$updateMerchantEmail = Yii::$app->db->createCommand($sqlupdateMerchantEmail)->execute();	
   
		}
	    $oldLogo = $merchantUpdate['logo'];
		$oldQrLogo = $merchantUpdate['qrlogo'];
		$oldCoverpic = $merchantUpdate['coverpic'];
		
		$merchantUpdate->attributes = \Yii::$app->request->post('Merchant');
		$merchantUpdate->password = $newpassword;
		
	$logoimage = UploadedFile::getInstance($merchantUpdate, 'logo');
	$qrlogoimage = UploadedFile::getInstance($merchantUpdate, 'qrlogo');
	$coverpicimage = UploadedFile::getInstance($merchantUpdate, 'coverpic');	
	if($logoimage){
					if(!empty($oldLogo)){
					$imagePath =  '../../'.Url::to(['../../merchantimages/'. $oldLogo]);
					if(file_exists($imagePath)){
						unlink($imagePath);	
					}
					
				}
				
				$logoimagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$logoimage->extension;
				$logoimage->saveAs('../../merchantimages/' . $logoimagename);
				$merchantUpdate->logo = $logoimagename;

			}else{
				$merchantUpdate->logo = $oldLogo;
			}
	
			if($qrlogoimage){
				if(!empty($oldQrLogo)){
					$imagePath =  '../../'.Url::to(['../../merchantimages/'. $oldQrLogo]);
					if(file_exists($imagePath)){
						unlink($imagePath);	
					}
				}
				$qrlogoimagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$qrlogoimage->extension;
				$qrlogoimage->saveAs('../../merchantimages/' . $qrlogoimagename);
				$merchantUpdate->qrlogo = $qrlogoimagename;
			}else{
				$merchantUpdate->qrlogo = $oldQrLogo;
			}
			if($coverpicimage){
				if(!empty($oldCoverpic)){
					$imagePath =  '../../'.Url::to(['../../merchantimages/'. $oldCoverpic]);
					if(file_exists($imagePath)){
						unlink($imagePath);	
					}
				}
				$coverpicimageimagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$coverpicimage->extension;
				$coverpicimage->saveAs('../../merchantimages/' . $coverpicimageimagename);
				$merchantUpdate->coverpic = $coverpicimageimagename;
			}else{
				$merchantUpdate->coverpic = $oldCoverpic;
			}
			
		if($merchantUpdate->validate()){

			$merchantUpdate->save();
		}
		else{
			print_r($merchantUpdate->getErrors());exit;
		}
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'Merchant',
		'text' => 'Merchant Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		return $this->redirect('merchants');
	}
	public function actionDeletemerchant()
	{
		extract($_POST);
$merchantDet = Merchant::findOne($id);
		if($merchantDet['ID']){
				
			\Yii::$app->db->createCommand()->delete('merchant', ['id' => $id])->execute();
			\Yii::$app->db->createCommand()->delete('merchant_employee', ['merchant_id' => $id])->execute();
		}
	}
	public function actionChangeproductavailabilty(){
	extract($_POST);
	$productDetails = Product::findOne($tableid);
	if($productDetails['availabilty']=='1'){
				$availabilty ='0';
		}else{
				$availabilty = '1';
		}
	$productDetails->availabilty = $availabilty;
	$productDetails->save();

    }
    public function actionChangeproductstatus(){
	extract($_POST);
	if($tablename == 'banners')
	{
		$details = Banners::findOne($tableid);	
	if($details['status']=='1'){
				$status ='0';
		}else{
				$status = '1';
		}
	}
	else if($tablename == 'rewards')
	{
		$details = Rewards::findOne($tableid);	
	if($details['status']=='1'){
				$status ='0';
		}else{
				$status = '1';
		}
	}
	else if($tablename == 'merchant')
	{
		$details = Merchant::findOne($tableid);	
	if($details['status']=='1'){
				$status ='0';
		}else{
				$status = '1';
		}
	}
else if($tablename == 'coupon')
	{
		$details = MerchantCoupon::findOne($tableid);	
	if($details['status']=='Active'){
				$status ='Deactive';
		}else{
				$status = 'Active';
		}
	}

	$details->status = $status;
	if($details->save()){
		print_r($details->getErrors());exit;
	}
	

    }
	public function actionPaydetails()
	{
		extract($_REQUEST);
		if(!empty($merchantId)){
			$merchantId = $merchantId;
			$mer_id = $merchantId;
		}else if(isset($_POST['MerchantPaytypes']['merchant_id'])){
			$merchantId = $_POST['MerchantPaytypes']['merchant_id'];
		//$mer_id = '';
		}else{
			return $this->redirect('merchants');
		}
		$sqlmerchantPay = 'SELECT mp.ID,storename,paymenttype,paymentgateway,merchantid,merchantkey  FROM merchant_paytypes mp inner join merchant m on mp.merchant_id = m.ID
		where merchant_id = \''.$merchantId.'\' order by mp.ID desc';
		$merchantPay = Yii::$app->db->createCommand($sqlmerchantPay)->queryAll();
		$model = new MerchantPaytypes;

		
		if ($model->load(Yii::$app->request->post()) ) {

		$model->reg_date = date('Y-m-d h:i:s');
		
		if($model->validate()){
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'Payment Method',
		'text' => 'Payment Method Created Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
			$model->save();

//		return	$this->redirect('paydetails');
		return $this->redirect(['admin/paydetails','merchantId'=>$model->merchant_id]);
		}
		else
		{
		//	echo "<pre>";print_r($model->getErrors());exit;
		}
	}
		return $this->render('merchantpaytypes',['merchantPay'=>$merchantPay,'model'=>$model,'merchantId'=>$merchantId]);
		
	}
	public function actionEditpaytypespopup()
	{
		extract($_POST);
		$paytypesModel = MerchantPaytypes::findOne($id);
		return $this->renderAjax('editpaytypespopup', ['model' => $paytypesModel,'id'=>$id]);
	}
	public function actionUpdatepaymentmethod()
	{
		extract($_POST);
		$model = new MerchantPaytypes;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$merchantPaymentArr = Yii::$app->request->post('MerchantPaytypes');
		$merchantPaymentUpdate = MerchantPaytypes::findOne($_POST['MerchantPaytypes']['ID']);

		$merchantPaymentUpdate->attributes = \Yii::$app->request->post('MerchantPaytypes');
			
			 
		if($merchantPaymentUpdate->validate()){
			$merchantPaymentUpdate->save();
		}
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'Payment Method',
		'text' => 'Payment Method Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		return $this->redirect(['admin/paydetails','merchantId'=>$merchantPaymentUpdate['merchant_id']]);
	}
	public function actionDeletepaytype(){
		extract($_POST);
		$paydetails = \app\models\MerchantPaytypes::findOne($id);
		$paydetails->delete();
		return $this->redirect(['admin/paydetails','merchantId'=>$paydetails['merchant_id']]);
		
	}
	public function actionDeletecoupon(){
		extract($_POST);
		$coupondetails = \app\models\MerchantCoupon::findOne($id);
		$coupondetails->delete();
	}
	public function actionDeletereward(){
		extract($_POST);
		$rewarddetails = \app\models\Rewards::findOne($id);
		$rewarddetails->delete();
	}	
    public function actionDashboard()
	{
		$date = date('Y-m-d');
		$sqlUsersCount = 'select count(ID) usercount from users ';
		$resUsersCount = Yii::$app->db->createCommand($sqlUsersCount)->queryOne();
		
		$userCount = $resUsersCount['usercount'] ?? 0; 
		
		$sqlRegResturant = 'select count(*) regResturanrt  from merchant where storetype = \'Restaurant\' ';
		$resRegResturant = Yii::$app->db->createCommand($sqlRegResturant)->queryOne();
		
		$regResturant = $resRegResturant['regResturanrt'] ?? 0;
		
		$sqlRegTheatre = 'select count(*) regTheatre  from merchant where storetype = \'Theatre\' ';
		$resRegTheatre = Yii::$app->db->createCommand($sqlRegTheatre)->queryOne();
		
		$regTheatre = $resRegTheatre['regTheatre'] ?? 0;
		
		$sqlLocation = 'select DISTINCT(location) as location from merchant';
		$resLocation = Yii::$app->db->createCommand($sqlLocation)->queryAll();
		
		$sqlOrderStatusCount =' select count(orderprocess) cnt,orderprocess from orders where date(reg_date) = \''.$date.'\' 
		 group by orderprocess';
		$orderStatusCount = Yii::$app->db->createCOmmand($sqlOrderStatusCount)->queryAll();
		$ordStatusCount = array_column($orderStatusCount,'cnt','orderprocess');
		$year = date('Y').'-01-01';
		$sqlSales = 'SELECT MONTHNAME(reg_date) label,round(sum(totalamount),2)  value FROM orders 
		where date(reg_date) >= \''.$year.'\' group by MONTH(reg_date)';
		$resSales = Yii::$app->db->createCommand($sqlSales)->queryAll();
		$yearStartDate = date('Y').'-01-01';
		//echo json_encode($resSales);exit;
		$sqlChart = 'select mon_name,coalesce(sale_amount,0) sale_amount from (
select 1 mon_num,\'Jan\' mon_name
UNION
select 2,\'Feb\'
    UNION
select 3,\'Mar\'
    UNION
select 4,\'Apr\'
    UNION
select 5,\'May\'
    UNION
select 6,\'Jun\'
    UNION
select 7,\'Jul\'
    UNION
select 8,\'Aug\'
    UNION
select 9,\'Sep\'
    UNION
select 10,\'Oct\'
    UNION
select 11,\'Nov\'
    UNION
select 12,\'Dec\'
    ) as A left join (

SELECT sum(totalamount) sale_amount,month(reg_date) num_mon FROM orders where date(reg_date) between \''.$yearStartDate.'\' and \''.date('Y-m-d').'\'
group by month(reg_date)
        ) as B on A.mon_num = B.num_mon';
		$resChart = Yii::$app->db->createCommand($sqlChart)->queryAll();
		
	$str =	'<chart caption="Monthly Sale Value" subcaption="Current year" xaxisname="Month" yaxisname="Earnings" theme="fusion">';
    for($i=0;$i<count($resChart);$i++){
	$str.='<set label="'.$resChart[$i]['mon_name'].'" value="'.$resChart[$i]['sale_amount'].'"  tooltext="'.$resChart[$i]['mon_name'].' Month  Earnings is ₹ '.round($resChart[$i]['sale_amount'],2).' "/>';	
	}
$str.='</chart>';
		
		return $this->render('dashboard',['userCount'=>$userCount,'regResturant'=>$regResturant
		,'regTheatre'=>$regTheatre,'resLocation'=>$resLocation,'ordStatusCount'=>$ordStatusCount
		,'resSales'=>json_encode($resSales),'str'=>$str]);
	}
    public function actionMerchantview()
	{
		return $this->render('merchantview');
	}
	public function actionOrders()
	{
		extract($_POST);
		$sdate = $_POST['sdate'] ?? date('Y-m-d'); 
		$edate = $_POST['edate'] ?? date('Y-m-d');

		$orderprocess = $_REQUEST['orderProcess'] ?? null;
		$sqlOrders = 'select * from orders where date(reg_date) between \''.$sdate.'\' and \''.$edate.'\' ';
		if(!empty($orderprocess)) { 
		$sqlOrders .=' and orderprocess = \''.$orderprocess.'\' ';
		}		
		$sqlOrders .=' order by ID desc';
		$orderModel = Yii::$app->db->createCOmmand($sqlOrders)->queryAll();
		

		return $this->render('orders',['orderModel'=>$orderModel,'sdate'=>$sdate,'edate'=>$edate]);
	}	
	public function actionTablebill()
	{
		extract($_POST);
		$orderDet = \app\models\Orders::findOne($id);
		$tableDet = \app\models\Tablename::findOne($orderDet['tablename']);
		$orderProdDet = \app\models\OrderProducts::find()->where(['order_id'=>$orderDet['ID']])->asArray()->all();
		return $this->render('tablebill',['tableDet'=>$tableDet,'orderDet'=>$orderDet,'orderProdDet'=>$orderProdDet]);
	}
	 public function actionPilot()
	{
		$pilotModel = \app\models\Serviceboy::find()
		
		->orderBy([
				'ID'=>SORT_DESC
			])
		->asArray()->all();
		
        return $this->render('pilot',['pilotModel'=>$pilotModel]);
    }
	public function actionMerchantgrouping(){
		$sql = 'select * from merchant where owner_type =\'1\'';
		$res = Yii::$app->db->createCommand($sql)->queryAll();
		
		return $this->render('merchantgrouping',['res'=>$res]);
	}
	public function actionEditgroupingpopup()
    {
	extract($_POST);
	$sqlcoowner = 'select * from merchant m where owner_type = \'2\' and id not in (select child_id from merchant_group where status = \'1\')';
	$rescoowner = Yii::$app->db->createCommand($sqlcoowner)->queryAll();
	$sqlgroup = 'select mg.ID,storename,child_id from merchant m inner join merchant_group mg on mg.child_id = m.id and parent_id = \''.$id.'\'';
	$group = Yii::$app->db->createCommand($sqlgroup)->queryAll();
	
	return $this->renderAjax('updategroup', ['rescoowner' => $rescoowner,'group' => $group,'id'=>$id]);		
    }
	public function actionUpdategroup(){
		extract($_POST);
		
		
		if(isset($group) )
		{
			
			$deleteSql = 'delete  from merchant_group where parent_id = \''.$update_merchant_id.'\'';
		$resDelete = Yii::$app->db->createCommand($deleteSql)->execute();
				for($i=0;$i<count($group);$i++){
					$merchantGroups[$i]['parent_id'] = $update_merchant_id ;
					$merchantGroups[$i]['child_id'] = $group[$i] ;
					$merchantGroups[$i]['status'] = '1' ;
					$merchantGroups[$i]['reg_date'] = date('Y-m-d h:i:s') ;
				}
					Yii::$app->db
			->createCommand()
			->batchInsert('merchant_group', ['parent_id','child_id','status','reg_date'],$merchantGroups)
			->execute();
		}
		return $this->redirect('merchantgrouping');
	}
	public function actionDeletegroupmerchant()
	{
		extract($_POST);
		$deleteSql = 'delete  from merchant_group where ID = \''.$id.'\'';
		$resDelete = Yii::$app->db->createCommand($deleteSql)->execute();
		
	}
	public function actionDeletedempoyee()
	{
		extract($_POST);
		$deleteSql = 'select * from merchant_employee me inner join merchant m on m.ID = me.merchant_id where emp_status = \'17\'';
		$resDelete = Yii::$app->db->createCommand($deleteSql)->queryAll();
		return $this->render('deletedemployee',['usersModel'=>$resDelete]);
	}
	public function actionDeletedvendor()
	{
		extract($_POST);
		$deleteSql = 'select * from merchant_vendor me inner join merchant m on m.ID = me.merchant_id where me.status = \'17\'';
		$resDelete = Yii::$app->db->createCommand($deleteSql)->queryAll();
		return $this->render('deletedvendor',['usersModel'=>$resDelete]);
	}
	public function actionContest()
	{
		extract($_POST);
		$model = new \app\models\Contest;
		$sql = 'SELECT * FROM contest ';
		$res = Yii::$app->db->createCommand($sql)->queryAll();
			

		if ($model->load(Yii::$app->request->post()) ) {
		$model->contest_id = Utility::get_uniqueid('contest','CNTST');
		$model->created_on = date('Y-m-d');
		$model->created_by = (string)Yii::$app->user->identity->ID;
		if(!empty($_POST['Contest']['contest_participants'])){
		$model->contest_participants = implode(',',$_POST['Contest']['contest_participants']);	
		}
		
		$contestimage = UploadedFile::getInstance($model, 'contest_image');
			
			if($contestimage){
				$contestimagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$contestimage->extension;
				$contestimage->saveAs('../../contestimages/' . $contestimagename);
				$model->contest_image = $contestimagename;

			}
		
		if($model->validate()){
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'Contest',
		'text' => 'Contest Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
			$model->save();

		return	$this->redirect('contest');
		}
		else
		{
			echo "<pre>";print_r($model->getErrors());exit;
		}
	}
		$sqlLocation = 'select distinct location as location from merchant ';
		$resLocation = Yii::$app->db->createCommand($sqlLocation)->queryAll();
		
		return $this->render('contest',['model'=>$model,'contest'=>$res,'resLocation'=>$resLocation]);
	}
	public function actionEditcontestpopup()
	{
		extract($_POST);
		$contestModel = \app\models\Contest::findOne($id);
		return $this->renderAjax('editcontestpopup', ['model' => $contestModel,'id'=>$id]);
	}
		public function actionEditcontestparticpants()
	{
		extract($_POST);
		$contestModel = \app\models\Contest::findOne($id);
		$particpantsArr =	'select storename from merchant ';
		if(!empty($contestModel['contest_participants'])){
			$merchantIdIn = str_replace(",","','",$contestModel['contest_participants']);
		$particpantsArr .=	' where ID in (\''.$merchantIdIn.'\')';
		}

		$res = Yii::$app->db->createCommand($particpantsArr)->queryAll();
		
		return json_encode(($res));
	}
	public function actionUpdatecontest()
	{
		extract($_POST);
		$model = new \app\models\Contest;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$merchantPaymentArr = Yii::$app->request->post('Contest');
		$merchantPaymentUpdate = \app\models\Contest::findOne($_POST['Contest']['ID']);

		$merchantPaymentUpdate->attributes = \Yii::$app->request->post('Contest');
			
			 
		if($merchantPaymentUpdate->validate()){
			$merchantPaymentUpdate->save();
		}
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'Contest',
		'text' => 'Contest Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		return $this->redirect('contest');
	}
	
	
}
