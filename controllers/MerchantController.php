<?php
namespace app\controllers;


use yii;
use yii\helpers\Url;
use app\models\Product;
use app\models\Orders;
use app\models\FoodCategeries;
use app\models\Tablename;
use app\models\Sections;
use app\models\RoomReservations;
use app\models\RoomTitleImages;
use app\models\RoomGuestIdentitiy;
use app\models\Userinformation;
use app\models\Serviceboy;
use app\models\Merchant;
use app\models\MerchantCoupon;
use app\models\CounterSettlement;
use app\models\Banners;
use app\helpers\Utility;
use app\helpers\MyConst;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use app\models\MerchantGallery;
use app\models\Ingredients;
use app\models\MerchantRecipe;
use app\models\IngredientPurchase;
use app\models\IngredientPurchaseDetail;
use app\models\MerchantVendor;
use app\models\MerchantEmployee;
use app\models\MerchantPermissions;
use app\models\MerchantTax;
use app\models\MerchantLoyaltyDetails;
use app\models\EmployeeRole;
use app\models\EmployeeAttendance;
use app\models\MerchantNotifications;
use app\models\InventaryUpdationRequest;
use app\models\MerchantLoyalty;
use app\models\RoomProfileTitles;
use app\models\FoodSections;
use app\models\SectionItemPriceList;
use app\models\FoodCategoryTypes;
use app\models\MerchantFoodCategoryTax;
use app\models\OrderProducts;
use app\models\IngredientStockRegister;
use app\models\TableReservations;
use app\models\AllocatedRooms;
use app\models\ServiceboyNotifications;
use yii\db\Query;
use app\models\Users;
use app\models\PilotTable;
use yii\helpers\ArrayHelper;


define('SITE_URL','http://superpilot.in/dev/');
class MerchantController extends GoController
{

    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionProductList(){
	$merchantId = Yii::$app->user->identity->merchant_id;
	$productModel = Product::find()
        ->where(['merchant_id' => $merchantId])
	->orderBy([
            'ID'=>SORT_DESC
        ])
	->asArray()->all();

	$model = new Product;
	if ($model->load(Yii::$app->request->post()) ) {
		$productArr = Yii::$app->request->post('Product');
		$model->merchant_id = (string)$merchantId;
		$model->unique_id = !empty($productArr['unique_id']) ? $productArr['unique_id'] : Utility::get_uniqueid('product','PR');
		$model->slug = $productArr['title'];
		$model->price = '0';
		$model->saleprice = '0';
		$model->availabilty = '1';
		$model->status = '1';
		$model->reg_date = date('Y-m-d H:i:s');
		$model->mod_date = date('Y-m-d H:i:s');
		$image = UploadedFile::getInstance($model, 'image');
		if($image){
			$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$image->extension;
			$image->saveAs('uploads/productimages/' . $imagename);
			$model->image = $imagename;
		}
		if($model->validate()){
			$model->save();
			$prices = $_POST['prices'];
			$sale_prices=  $_POST['sale_prices'];
			$sectionid = $_POST['sectionid'];
			for($i=0;$i<count($prices);$i++)
			{
							$itemsectionprice = is_numeric($prices[$i]) ? $prices[$i] : 0; 
							$itemsectionsaleprice = is_numeric($sale_prices[$i]) ? $sale_prices[$i] : 0; 
				$modelsectionitem = new SectionItemPriceList;
				$modelsectionitem->merchant_id = $merchantId;
				$modelsectionitem->item_id = $model->ID;
				$modelsectionitem->section_id = $sectionid[$i];
				$modelsectionitem->section_item_price = $itemsectionprice;
				$modelsectionitem->section_item_sale_price = $itemsectionsaleprice;
				$modelsectionitem->created_by = Yii::$app->user->identity->emp_name;
				$modelsectionitem->created_on = date('Y-m-d H:i:s');
				$modelsectionitem->updated_by = Yii::$app->user->identity->emp_name;
				$modelsectionitem->updated_on = date('Y-m-d H:i:s');
				if($modelsectionitem->save()){
					
				}else{
				echo "<pre>";	print_r($modelsectionitem->getErrors());exit;
				}
			}
			
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'Item',
		'text' => 'Item Created Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);


		return	$this->redirect('product-list');
		}
		else
		{
		//	echo "<pre>";print_r($model->getErrors());exit;
		}
	}
	  $sections = Sections::find()->where(['merchant_id' => $merchantId])->asArray()->all();
      return $this->render('productlist',['productModel'=>$productModel,'model'=>$model,'sections'=>$sections]);
    }
	public function actionEditproductpopup()
	{
		extract($_POST);
		$merchantId = Yii::$app->user->identity->merchant_id;
		$productModel = Product::findOne($id);
		$sections = Sections::find()->where(['merchant_id' => $merchantId])->asArray()->all();
		
		
		$sqlSectionItemsPrice = 'select concat(section_id , \'-\', item_id) section_item_id,section_item_price,section_item_sale_price from section_item_price_list 
		where merchant_id = \''.$merchantId.'\'';
		$resSectionItemsPrice = Yii::$app->db->createCommand($sqlSectionItemsPrice)->queryAll();
		
		$itemSectionPricesArr = (array_column($resSectionItemsPrice,'section_item_price','section_item_id'));
		$itemSectionSalePricesArr = (array_column($resSectionItemsPrice,'section_item_sale_price','section_item_id'));
		
		return $this->renderAjax('editproductpopup', ['model' => $productModel,'id'=>$id,'sections'=>$sections,'itemSectionPricesArr'=>$itemSectionPricesArr,'itemSectionSalePricesArr'=>$itemSectionSalePricesArr]);		
    
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
        public function actionChangeupsellingstatus(){
	extract($_POST);
	$productDetails = Product::findOne($tableid);
	if($productDetails['upselling']=='1'){
				$availabilty ='2';
		}else{
				$availabilty = '1';
		}
	$productDetails->upselling = $availabilty;
	$productDetails->save();

    }
    public function actionChangeproductstatus(){
	extract($_POST);
	if($tablename == 'product')
	{
		$details = Product::findOne($tableid);	
	if($details['status']=='1'){
				$status ='0';
		}else{
				$status = '1';
		}
	}
	else if($tablename == 'vendor')
	{
		$details = MerchantVendor::findOne($tableid);
	if($details['status']=='1'){
				$status ='0';
		}else{
				$status = '1';
		}
	}
	else if($tablename == 'serviceboy')
	{
		$details = Serviceboy::findOne($tableid);
	if($details['status']=='1'){
				$status ='0';
		}else{
				$status = '1';
		}
	}
	else if($tablename == 'employee')
	{
		$details = MerchantEmployee::findOne($tableid);
	if($details['emp_status']=='1'){
				$status ='0';
		}else{
				$status = '1';
		}
	}
	else if($tablename == 'role')
	{
		$details = EmployeeRole::findOne($tableid);
	if($details['role_status']=='1'){
				$status ='0';
		}else{
				$status = '1';
		}
	}
	else if($tablename == 'ingredients')
	{
		$details = \app\models\Ingredients::findOne($tableid);
	if($details['status']=='1'){
				$status ='0';
		}else{
				$status = '1';
		}
	}
	else if($tablename == 'tablename')
	{
		$details = Tablename::findOne($tableid);
	if($details['status']=='1'){
				$status ='0';
		}else{
				$status = '1';
		}
	}
	else if($tablename == 'gallery')
	{
		$details = \app\models\MerchantGallery::findOne($tableid);
	if($details['status']=='1'){
				$status =0;
		}else{
				$status = 1;
		}
	}
	
	else if($tablename == 'merchant_coupon')
	{
		$details = MerchantCoupon::findOne($tableid);

		if($details['status']=='Active'){
				$status ='Deactive';
		}else{
				$status = 'Active';
		}
	}
if($tablename == 'employee')
{
	$details->emp_status = $status;
	if(!$details->save()){
		print_r($details->getErrors());exit;
	}
}
else if($tablename == 'role')
{
	$details->role_status = $status;
	if(!$details->save()){
		print_r($details->getErrors());exit;
	}
	if($status == '0'){
	$sqlUpdate = 'update merchant_employee set emp_status = \''.$status.'\' where emp_role = \''.$tableid.'\'';
	$resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute(); 
		
	}
}
else{
	$details->status = $status;
	if(!$details->save()){
		print_r($details->getErrors());exit;
	}
}
	
	

    }
    public function actionFoodCategeries(){
		extract($_POST);
	$allcategeries = FoodCategeries::allcategeries();
	$merchantId = Yii::$app->user->identity->merchant_id;
            $model = new MerchantTax;

	$sqlcategorytypes = 'select * from food_categeries fc left join food_category_types fct on fc.id = fct.food_cat_id 
	and fc.merchant_id = \''.$merchantId.'\'';
	$rescategorytypes = Yii::$app->db->createCommand($sqlcategorytypes)->queryAll();
	$foodSections = FoodSections::find()->where(['merchant_id' => $merchantId])->asArray()->all();
	
	
	$foodcatgerymodel = new FoodCategeries;
	if (!empty($food_category)){
		$foodcatgerymodel['food_category'] = $food_category; 
		$foodcatgerymodel['food_section_id'] = $food_section; 
		$foodcatgerymodel['upselling'] = $upselling; 

		$foodcatgerymodel['merchant_id'] = (string)$merchantId;
		$foodcatgerymodel['reg_date'] = date('Y-m-d H:i:s A');
    	
		if(!empty($_FILES['category_label']['name'])){
			$newName =	$this->uploadItemCategory($_FILES['category_label'],$merchantId,'insert');
			$foodcatgerymodel['category_img'] = $newName;
		}
		$foodcatgerymodel->save();
		$food_cat_id =  $foodcatgerymodel->getPrimaryKey();	


		$catTypeArray = array_filter($categorytypes);
	    if(!empty($catTypeArray))
	    {
			for($i=0;$i<count($catTypeArray);$i++)
			{
				$data[] = [$food_cat_id,$catTypeArray[$i],(string)$merchantId,date('Y-m-d H:i:s A')];
			}
			Yii::$app->db
			->createCommand()
			->batchInsert('food_category_types', ['food_cat_id','food_type_name','merchant_id', 'reg_date'],$data)
			->execute();	
			
	    }
	  Yii::$app->getSession()->setFlash('success', [
        'title' => 'Food Category',
		'text' => 'Food Category Created Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);

	return $this->redirect('food-categeries');
	
	}
	
	return $this->render('foodcategeries',['allcategeries'=>$allcategeries,'foodcatgerymodel'=>$foodcatgerymodel
	,'categorytypes'=>$rescategorytypes,'model' => $model,'foodSections'=>$foodSections,'site_url' => SITE_URL]);
    }
    public function actionEditcategorypopup()
    {
	$merchantid = Yii::$app->user->identity->merchant_id; 	
	extract($_POST);
	$categeryModel = FoodCategeries::findOne($id);
	$sqlcategorytypes = 'select fc.ID,fc.food_category,fct.food_type_name,fct.ID fcid,food_section_id,fc.upselling  from food_categeries fc left join food_category_types fct on fc.id = fct.food_cat_id 
	where fc.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and fc.ID = \''.$id.'\'';
	$categorytypes = Yii::$app->db->createCommand($sqlcategorytypes)->queryAll();

	$foodSections = FoodSections::find()->where(['merchant_id' => $merchantid])->asArray()->all();
		
	
	return $this->renderAjax('updatecategery', ['categorytypes' => $categorytypes,'id'=>$id,'foodSections'=>$foodSections]);		
    }
    public function actionUpdatefoodcategery()
    {
	extract($_POST);
	$merchantId = Yii::$app->user->identity->merchant_id;
	$foodCategoryUpdate = FoodCategeries::findOne($_POST['update_food_id']);
	$oldUpselling = $foodCategoryUpdate['upselling'];  
	$foodCateQtys =  FoodCategoryTypes::find()->where(['food_cat_id'=>$foodCategoryUpdate['ID']])->asArray()->all();

	if( (trim($foodCategoryUpdate['food_category']) != trim($_POST['update_food_category'])) 
	||  (trim($foodCategoryUpdate['food_section_id']) != trim($_POST['update_food_section'])) 
	|| (trim($foodCategoryUpdate['upselling']) != trim($_POST['update_upselling']))
	)
	{
		$foodCategoryUpdate->food_category = trim($_POST['update_food_category']);
		$foodCategoryUpdate->food_section_id = trim($_POST['update_food_section']);	
		$foodCategoryUpdate->upselling = trim($_POST['update_upselling']);	
		$foodCategoryUpdate->save();	

        if(!empty($_POST['update_upselling']) && ($oldUpselling != trim($_POST['update_upselling'])) ){

        	$productModel = Product::find()
            ->where(['merchant_id' => $merchantId])
            ->andWhere(['foodtype' => $foodCategoryUpdate['ID']])
    	    ->asArray()->all();
    	    
    	    if(!empty($productModel)){
    	        $sqlProductUpdate = 'update product set upselling = \''.$_POST['update_upselling'].'\' where foodtype = \''.$foodCategoryUpdate['ID'].'\' and merchant_id = \''.$merchantId.'\'';
    	        $resProductUpdate = Yii::$app->db->createCommand($sqlProductUpdate)->execute();
    	    }
    	}

		
	}
	
	if(!empty($_FILES['update_category_label']['name'])){
		$newName =	$this->uploadItemCategory($_FILES['update_category_label'],$merchantId,'update');
		if(!empty($foodCategoryUpdate['category_img'])){
			unlink('../../merchant_docs/'.$merchantId.'/item_category/'.$foodCategoryUpdate['category_img']);
		}
		$foodCategoryUpdate['category_img'] = $newName;
		$foodCategoryUpdate->save();
	}

	if(count($foodCateQtys) > 0)
	{
			for($i=0;$i<count($foodCateQtys);$i++)
			{
				if($_POST['categorytypes_'.$foodCateQtys[$i]['ID']] != $foodCateQtys[$i]['food_type_name'] )
				{
						$foodcategorytypes = FoodCategoryTypes::findOne($foodCateQtys[$i]['ID']);
						$foodcategorytypes->merchant_id = (string)Yii::$app->user->identity->merchant_id;
						$foodcategorytypes->food_type_name = $_POST['categorytypes_'.$foodCateQtys[$i]['ID']];
						$foodcategorytypes->save();
				}
			}
	}
	$catTypeArray = array_filter($categorytypes);
	if(!empty($catTypeArray))
	{
			for($i=0;$i<count($catTypeArray);$i++)
			{
				$data[] = [$_POST['update_food_id'],$catTypeArray[$i],(string)Yii::$app->user->identity->merchant_id,date('Y-m-d H:i:s A')];
			}
			Yii::$app->db
			->createCommand()
			->batchInsert('food_category_types', ['food_cat_id','food_type_name','merchant_id', 'reg_date'],$data)
			->execute();
	}

  Yii::$app->getSession()->setFlash('success', [
        'title' => 'Food Category',
		'text' => 'Food Category Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
	return $this->redirect('food-categeries');		
    }
	public function uploadItemCategory($filearray,$merchantId,$reqType)
	{
		$file_tmpname = $filearray['tmp_name'];
		$file_name = $filearray['name'];
		$file_size = $filearray['size'];
		$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);		
		$newname = date('YmdHis',time()).mt_rand().'.'.$file_ext;
		$path = '../../merchant_docs/'.$merchantId.'/item_category';
		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		}
		move_uploaded_file($file_tmpname,$path.'/'.$newname);
		return $newname;
	}
	public function actionManagetable()
	{
	    	extract($_POST);
		$model = new Tablename;
		$model->scenario = 'inserttable';
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$tableDet = Tablename::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
		$merchantdetails = Merchant::findOne(Yii::$app->user->identity->merchant_id);
		
		$tableArr = Yii::$app->request->post('Tablename');
		$merchant_id = (string)Yii::$app->user->identity->merchant_id;
		

		
		
		if(!empty($tablename))
	    {
			for($i=0;$i<count($tablename);$i++)
			{
				$data[] = [$merchant_id,$tablename[$i],$capacity[$i],'1',$tableArr['section_id'],date('Y-m-d H:i:s')];
			}
			Yii::$app->db
			->createCommand()
			->batchInsert('tablename', ['merchant_id','name','capacity', 'status','section_id','reg_date'],$data)
			->execute();	
			
				Yii::$app->getSession()->setFlash('success', [
        'title' => 'Spot',
		'text' => 'Spot Created Successfully',
        'type' => 'success',
        //'timer' => 3000,
        'showConfirmButton' => false
    ]);
		
			return $this->redirect('managetable');
	    }

		return $this->render('managetable',['model'=>$model,'tableDet'=>$tableDet,'merchantdetails'=>$merchantdetails]);
	}
	public function actionEdittablepopup()
	{
		extract($_POST);
		$tableModel = Tablename::findOne($id);
		return $this->renderAjax('edittablepopup', ['model' => $tableModel,'id'=>$id]);		
	}
	public function actionEdittable()
	{
		$model = new Tablename;
		$model->scenario = 'updatetable';
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$tableNameArr = Yii::$app->request->post('Tablename');
		$tableNameUpdate = Tablename::findOne($_POST['Tablename']['ID']);
		
		$tableNameUpdate->attributes = \Yii::$app->request->post('Tablename');
		
		if($tableNameUpdate->validate()){
			$tableNameUpdate->save();
			Yii::$app->getSession()->setFlash('success', [
        'title' => 'Spot',
		'text' => 'Spot Edited Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		}
			return $this->redirect('managetable');
	
	}
	public function actionQuantitylist($id = '')
    {				
		extract($_REQUEST);
        $foodCatTypes = FoodCategoryTypes::find()
				->where(['food_cat_id' => $id])
				->andWhere(['merchant_id'=>Yii::$app->user->identity->merchant_id])
				->all();

		if (!empty($foodCatTypes)) {
						echo "<option value=''>Select</option>"; 

			foreach($foodCatTypes as $foodCatTypes) {
			echo "<option value='".$foodCatTypes->ID."'>".$foodCatTypes->food_type_name."</option>";
			}
		} else {
			echo "<option>-</option>";
		}
		
    }
	public function actionUpdateproduct()
	{
 		extract($_POST);
		$merchantid = Yii::$app->user->identity->merchant_id;		
		$model = new Product;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$productArr = Yii::$app->request->post('Product');
		
		$productUpdate = Product::findOne($_POST['Product']['ID']);
		$oldProductImage = $productUpdate['image'];
		
		$productUpdate->attributes = \Yii::$app->request->post('Product');
		$image = UploadedFile::getInstance($model, 'image');
			if($image){
				if(!empty($productUpdate['image'])){
					$imagePath =  '../../'.Url::to(['/uploads/productimages/'. $productUpdate['image']]);
					if(file_exists($imagePath)){
						unlink($imagePath);	
					}
				}
				
				$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$image->extension;
				$image->saveAs('uploads/productimages/' . $imagename);
				$productUpdate->image = $imagename;
			}else{
				$productUpdate->image = $oldProductImage;
			}
		if($productUpdate->validate()){
		    for($i=0;$i<count($updateprices);$i++)
			{
				$itemsectionprice = is_numeric($updateprices[$i]) ? $updateprices[$i] : 0; 
				$itemsectionsaleprice = is_numeric($updatesaleprices[$i]) ? $updatesaleprices[$i] : 0; 
				
				
				$ressectioitem = SectionItemPriceList::find()->where(['merchant_id'=>$merchantid
				,'section_id' => $updatesectionid[$i],'item_id' => $_POST['Product']['ID']])->asArray()->one();   

				
				if(!empty($ressectioitem)){
				$sqlUpdate = 'update section_item_price_list set section_item_price = \''.$itemsectionprice.'\',
				section_item_sale_price = \''.$itemsectionsaleprice.'\',updated_by = \''.Yii::$app->user->identity->emp_name.'\'
				,updated_on = \''.date('Y-m-d H:i:s').'\' where ID = \''.$ressectioitem['ID'].'\'';
				$resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute();
				}
				else{
				$modelsectionitem = new SectionItemPriceList;
				$modelsectionitem->merchant_id = Yii::$app->user->identity->merchant_id;
				$modelsectionitem->item_id = $_POST['Product']['ID'];
				$modelsectionitem->section_id = $updatesectionid[$i];
				$modelsectionitem->section_item_price = $itemsectionprice;
				$modelsectionitem->section_item_sale_price = $itemsectionsaleprice;
				$modelsectionitem->created_by = Yii::$app->user->identity->emp_name;
				$modelsectionitem->created_on = date('Y-m-d H:i:s');
				$modelsectionitem->updated_by = Yii::$app->user->identity->emp_name;
				$modelsectionitem->updated_on = date('Y-m-d H:i:s');
				if($modelsectionitem->save()){
				}else{
				//echo "<pre>";	print_r($modelsectionitem->getErrors());exit;
				}
				}

			}
			$productUpdate->save();
		}
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'Item',
		'text' => 'Item Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		return $this->redirect('product-list');
	}
	public function actionPlaceorder()
	{
		extract($_REQUEST);
            
		if(empty($tableid)){
		    if(!empty($sectionId)){
				$selectOneTable = Tablename::find()
								->where(['section_id'=>$sectionId,'status'=>'1'])
								->orderBy([
									'ID'=>SORT_ASC
								])
								->asArray()->one() ;
		        
		        $tableid = $selectOneTable['ID'];
		        $tableName = $selectOneTable['name'];
		        
		        $current_order_id = $selectOneTable['current_order_id'];
		        
		    }else{

		    return $this->redirect('tableplaceorder');    
		    }
			
		}
		else{
		   
		   $tabDet =  Tablename::findOne($tableid);
		   $sectionId = $tabDet['section_id']; 
		}
		$allcategeries = FoodCategeries::allcategeries();
	    
	    
	    $sqlTableSections = 'select s.section_name,tn.section_id,tn.ID table_id  from sections s inner join tablename tn on s.ID = tn.section_id 
		where s.merchant_id =  \''.Yii::$app->user->identity->merchant_id.'\' and tn.status = \'1\'';
		$tableSections = Yii::$app->db->createCommand($sqlTableSections)->queryAll();
	    
	    
		$sqlcategorytypes = 'select * from food_categeries fc left join food_category_types fct on fc.id = fct.food_cat_id 
	and fc.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'';
	$rescategorytypes = Yii::$app->db->createCommand($sqlcategorytypes)->queryAll();

	$sqlproductDetails = 'select P.ID,P.title,P.food_category_quantity,P.price,P.image,fc.food_category 
		,case when food_type_name is not null then concat(title ,\' (\' , food_type_name , \')\') else title end  title_quantity
		,fc.ID food_category_id
		from product P 
		left join food_categeries fc on fc.ID = P.foodtype  
		left join food_category_types fct on fct.ID =  P.food_category_quantity 
		and fct.merchant_id =  \''.Yii::$app->user->identity->merchant_id.'\'
		where P.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and status=\'1\'';
			$productDetails = Yii::$app->db->createCommand($sqlproductDetails)->queryAll();
		$mainProducts = array_column($productDetails,'ID','title');
		$mainProductsName = array_column($productDetails,'title_quantity','ID');
		$foodCategoryNameArr = array_column($productDetails,'food_category','title_quantity');
		//echo "<pre>";print_ 	r($foodCategoryNameArr);exit;
		$mainArr = [];
		$priceArr = [];
		$imgArr = [];$idArr=[];
		for($i=0;$i<count($productDetails);$i++)
		{
				$mainArr[$productDetails[$i]['title']][$i] = $productDetails[$i]; 
				$priceArr[$productDetails[$i]['title'].'_'.$productDetails[$i]['food_category_quantity']] = $productDetails[$i]['price'];
				$imgArr[$mainProducts[$productDetails[$i]['title']]] = $productDetails[$i]['image'];
				$idArr[$productDetails[$i]['title'].'_'.$productDetails[$i]['food_category_quantity']] = $productDetails[$i]['ID'];
		}
		
		

		$food_cat_qty_arr = FoodCategoryTypes::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->All();
		$food_cat_qty_det = array_column($food_cat_qty_arr,'food_type_name','ID');
		$prevOrderDetails = [];
		$prevFullSingleOrderDet = [];
		if(!empty($current_order_id))
		{
			$prevFullSingleOrderDet = Orders::findOne($current_order_id);
			//$prevOrderDetails = \app\models\OrderProducts::find()->where(['order_id'=>$current_order_id])
			//->andWhere(['not in','count',['0']])
			//->asArray()->all();
			$sqlPrevOrderDetails = 'select op.user_id,op.order_id,op.merchant_id,op.product_id
			,sum(op.count) count,sum(op.price) price
			,sum(coalesce(op.count,0)*coalesce(op.price,0)) 
			as totalprice,u.name,u.mobile from order_products op left join users u on op.user_id = u.ID 
			where order_id = \''.$current_order_id.'\' and count != \'0\' group by op.user_id,op.order_id,op.merchant_id,op.product_id
			,u.name,u.mobile';
			$prevOrderDetails = Yii::$app->db->createCommand($sqlPrevOrderDetails)->queryAll();
		}
		else{
		  $prevFullSingleOrderDet['paymenttype'] = 'cash';
			$prevFullSingleOrderDet['serviceboy_id'] = '';		  
		  
		}
		//echo "<pre>";print_r($prevOrderDetails);exit;
		$sqlmerchantcoupon = 'SELECT  code from merchant_coupon where  merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'
		and status = \'Active\' AND \''.date('Y-m-d').'\' between  date(fromdate) and date(todate)';
		$resmerchantcoupon = YIi::$app->db->createCommand($sqlmerchantcoupon)->queryAll();
		$merchantcoupons = array_column($resmerchantcoupon,'code');

	//	$tableDetails = Tablename::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
		$sqlTableDetails = 'select tn.*,o.orderprocess
		 from tablename tn left join orders o on tn.current_order_id = o.id 
		where tn.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and tn.status = \'1\'';
		if(!empty($sectionId)){
		$sqlTableDetails .= ' and section_id = \''.$sectionId.'\'';
		}
		$tableDetails = Yii::$app->db->createCommand($sqlTableDetails)->queryAll();
		$resServiceBoy = Serviceboy::find()->
		where(['merchant_id'=>Yii::$app->user->identity->merchant_id,'loginstatus'=>'1'])->asArray()->All();
		

		$discountTypes = Utility::discountTypes();
		$resMerchantfoodTax = MerchantFoodCategoryTax::find()->
		where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->All();
		
	    	$MerchantfoodTaxArr = \yii\helpers\ArrayHelper::index($resMerchantfoodTax, null, 'food_category_id');

		
		return $this->render('placeorder',['mainArr'=>$mainArr,'productDetails'=>$productDetails
		,'mainProducts'=>$mainProducts,'food_cat_qty_det'=>$food_cat_qty_det
		,'priceArr'=>$priceArr,'imgArr'=>$imgArr,'idArr'=>$idArr,'tableid'=>$tableid
		,'tableName'=>$tableName,'prevOrderDetails'=>$prevOrderDetails,'mainProductsName'=>$mainProductsName
		,'merchantcoupons'=>$merchantcoupons,'prevFullSingleOrderDet'=>$prevFullSingleOrderDet
		,'tableDetails'=>$tableDetails,'resServiceBoy'=>$resServiceBoy
		,'categorytypes'=>$rescategorytypes,'allcategeries'=>$allcategeries
		,'foodCategoryNameArr'=>$foodCategoryNameArr,'tableSections'=>$tableSections
		,'discountTypes'=>$discountTypes,'MerchantfoodTaxArr'=>$MerchantfoodTaxArr]);
	}
	public function actionTablebill()
	{
		extract($_POST);
		$orderDet = Orders::findOne($id);
		$tableDet = Tablename::findOne($orderDet['tablename']);

	   $orderDet = Orders::findOne($orderDet['ID']);
		$sqlOrderProdDet = 'select order_id, merchant_id, product_id, sum(count) as count, (price) as price
		                 from order_products where order_id=\''.$orderDet['ID'].'\' group by product_id, order_id,price';
	    $orderProdDet =  Yii::$app->db->createCommand($sqlOrderProdDet)->queryAll();
	    
		return $this->renderpartial('testprint',['tableDet'=>$tableDet,'orderDet'=>$orderDet,'orderProdDet'=>$orderProdDet,'orderDet'=>$orderDet]);
	}
	public function actionTablekot()
	{
		extract($_POST);
		$orderDet = Orders::findOne($id);
		$tableDet = Tablename::findOne($orderDet['tablename']);

		$sqlLatestKot = 'select max(CAST(reorder AS UNSIGNED)) as latestreorder from order_products where order_id = \''.$orderDet['ID'].'\'';
		$resLatestKot = Yii::$app->db->createCommand($sqlLatestKot)->queryOne();

		$latestreorder = $resLatestKot['latestreorder'];
    
		$orderProdDet = OrderProducts::find()->where(['order_id'=>$orderDet['ID'],'reorder'=>$latestreorder])->asArray()->all();

		return $this->renderpartial('testprintkot',['tableDet'=>$tableDet,'orderDet'=>$orderDet,'orderProdDet'=>$orderProdDet]);
	}
	
	public function actionRating()
	{
		extract($_POST);
		$sdate = $_POST['sdate'] ?? date('Y-m-d'); 
		$edate = $_POST['edate'] ?? date('Y-m-d');
		//$sdate = '2020-02-03';		
		$sqlRating = 'SELECT u.name as user_name,sb.name service_boy_name,f.order_id,totalamount,rating,message,f.reg_date
		FROM feedback f 
		left join orders o on f.order_id = o.ID
		left join serviceboy sb on o.serviceboy_id = sb.ID
		left join users u on f.user_id = u.ID
		where f.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
		and DATE(f.reg_date) between \''.$sdate.'\' and \''.$edate.'\'';
		$rating = Yii::$app->db->createCommand($sqlRating)->queryAll();
		return $this->render('ratings',['rating'=>$rating,'sdate'=>$sdate,'edate'=>$edate]);
	}
    public function actionPilot()
	{
		$merchantId = Yii::$app->user->identity->merchant_id;
		$pilotModel = Serviceboy::find()
			->where(['merchant_id' => $merchantId])
		->orderBy([
				'ID'=>SORT_DESC
			])
		->asArray()->all();
		$model = new MerchantEmployee;
		
		$empRole = EmployeeRole::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id,'ID'=>'2'])->asArray()->all();
		$empRoleIdNameArr = array_column($empRole,'role_name','ID'); 
		$categorytypes = Sections::find()->where(['merchant_id' => $merchantId])
		->asArray()->All();
	
        return $this->render('pilot',['pilotModel'=>$pilotModel,'model'=>$model,'empRoleIdNameArr'=>$empRoleIdNameArr,'categorytypes'=>$categorytypes]);
    }
	public function actionChangeloginaccess()
	{
		extract($_POST);
		if($tablename == 'serviceboy')
		{
			$details = Serviceboy::findone($tableid);
				
			if($details['loginaccess']=='1'){
				$availabilty ='0';
		}else{
				$availabilty = '1';
		}
		$details->loginaccess = $availabilty;
		$details->save(); 
		}	
	}
	public function actionChangeloginstatus()
	{
		extract($_POST);
		if($tablename == 'serviceboy')
		{
			$details = Serviceboy::findone($tableid);
				
			if($details['loginstatus']=='1'){
				$availabilty ='0';
		}else{
				$availabilty = '1';
		}
		$details->loginstatus = $availabilty;
		$details->save(); 
		}	
	}
	public function actionUpdatepilotpopup()
	{
		extract($_POST);
		$serviceBoyModel = Serviceboy::findOne($id);
		$pilotTable = PilotTable::findOne($id);
		$pilotTableId = ArrayHelper::index($pilotTable, null, 'ID');
		$categorytypes = Sections::find()->where(['merchant_id' => $merchantId])
		->asArray()->All();
		return $this->renderAjax('editpilot', ['model' => $serviceBoyModel,'id'=>$id
		, 'pilotTable' => $pilotTable, 'categorytypes' => $categorytypes]);		
	}
	public function actionUpdatepilot()
	{
		$model = new Serviceboy;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$serviceBoyArr = Yii::$app->request->post('Serviceboy');
		$pilotUpdate = Serviceboy::findOne($_POST['Serviceboy']['ID']);
		$currentPassword = $pilotUpdate->password;
		$pilotUpdate->attributes = \Yii::$app->request->post('Serviceboy');
		if(!empty($serviceBoyArr['password']))
		{
			$pilotUpdate->password = password_hash($serviceBoyArr['password'], PASSWORD_DEFAULT);
		}
		else{
			$pilotUpdate->password = $currentPassword;
		}
		if($pilotUpdate->validate()){
			$pilotUpdate->save();
			Yii::$app->getSession()->setFlash('success', [
        'title' => 'Pilot',
		'text' => 'Pilot Details Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		}
			return $this->redirect('pilot');
	
	}
	public function actionCoupon()
	{
		$productString = '';
		$merchantId = Yii::$app->user->identity->merchant_id;
		$couponModel = MerchantCoupon::find()
			->where(['merchant_id' => $merchantId])
		->orderBy([
				'ID'=>SORT_DESC
			])
		->asArray()->all();
		$sqlproductDetails = 'select P.ID,P.title,P.food_category_quantity
		,P.image,fc.food_category 
		,fc.ID food_category_id,REPLACE(REPLACE(REPLACE(title, " ", "_"),"\'","_"),"/",",") modified_title
		,food_type_name
		,case when concat(title , " ",food_type_name) is null then title else concat(title , " ",food_type_name) end   titlewithqty
		from product P 
		left join food_categeries fc on fc.ID = P.foodtype  
		left join food_category_types fct on fct.ID =  P.food_category_quantity and fct.merchant_id =  \''.$merchantId.'\'
		where P.merchant_id = \''.$merchantId.'\' and status=\'1\' ';
		$product = Yii::$app->db->createCommand($sqlproductDetails)->queryAll();
		$model = new MerchantCoupon;
		$model->scenario = 'uniquescenario';
		if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
		if ($model->load(Yii::$app->request->post()) ) {

	$productString = '';		
if(!empty($_POST['ckcDel'])){
				$productString = implode(",",$_POST['ckcDel']);
}
	
				
		$MerchantCouponArr = Yii::$app->request->post('MerchantCoupon');
			
			
			$model->merchant_id = (string)$merchantId;
			$model->status = 'Active';
			if(!empty($productString)){
			$model->product = $productString;	
			}
			$model->reg_date = date('Y-m-d H:i:s');
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
				return $this->redirect('coupon');
			}
			else
			{
				//echo "<pre>";print_r($model->getErrors());exit;
			}
		}


        return $this->render('coupon',['couponModel'=>$couponModel,'model'=>$model,'merchantId'=>$merchantId,'product'=>$product]);
    }
	public function actionEditcouponpopup()
	{
		extract($_POST);
		$merchantCouponModel = MerchantCoupon::findOne($id);
				$sqlproductDetails = 'select P.ID,P.title,P.food_category_quantity
		,P.image,fc.food_category 
		,fc.ID food_category_id,REPLACE(REPLACE(REPLACE(title, " ", "_"),"\'","_"),"/",",") modified_title
		,food_type_name
		,case when concat(title , " ",food_type_name) is null then title else concat(title , " ",food_type_name) end   titlewithqty
		from product P 
		left join food_categeries fc on fc.ID = P.foodtype  
		left join food_category_types fct on fct.ID =  P.food_category_quantity and fct.merchant_id =  \''.Yii::$app->user->identity->merchant_id.'\'
		where P.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and status=\'1\' ';
		$product = Yii::$app->db->createCommand($sqlproductDetails)->queryAll();
	$prodArr = [];
	$prodString = $merchantCouponModel['product'];
	if(!empty($prodString)){
		$prodArr = 	explode(",",$prodString);
	}

		return $this->renderAjax('editcouponpopup', ['model' => $merchantCouponModel,'id'=>$id,'product'=>$product,'prodArr'=>$prodArr]);		
	}
	
	public function actionEditcoupon()
	{
		$model = new MerchantCoupon;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$prodArr = Yii::$app->request->post('ckcDeledit');
		

		$MerchantCouponArr = Yii::$app->request->post('MerchantCoupon');
		$MerchantCouponUpdate = MerchantCoupon::findOne($_POST['MerchantCoupon']['ID']);
	
		$MerchantCouponUpdate->attributes = \Yii::$app->request->post('MerchantCoupon');
		if(!empty($prodArr)){
			$productString = implode(",",$prodArr);
		}else{
			$productString = '';
		}
		$MerchantCouponUpdate->product = $productString;
		if($MerchantCouponUpdate->validate()){
			$MerchantCouponUpdate->save();
						Yii::$app->getSession()->setFlash('success', [
        'title' => 'Coupon',
		'text' => 'Coupon Details Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		}
			return $this->redirect('coupon');
	
	}
	public function actionGallery()
	{
		$merchantId = Yii::$app->user->identity->merchant_id;
		$galleryModel = MerchantGallery::find()
			->where(['merchant_id' => $merchantId])
		->orderBy([
				'ID'=>SORT_DESC
			])
		->asArray()->all();
		$model = new MerchantGallery;
		
		if ($model->load(Yii::$app->request->post()) ) {
			$MerchantGalleryArr = Yii::$app->request->post('MerchantGallery');
			$model->merchant_id = (string)$merchantId;
			$model->reg_date = date('Y-m-d H:i:s');
			$image = UploadedFile::getInstance($model, 'image');
			if($image){
				$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$image->extension;
				$image->saveAs('uploads/merchantgallery/' . $imagename);
				$model->image = $imagename;
			}
			if($model->validate()){
				$model->save();
	Yii::$app->getSession()->setFlash('success', [
        'title' => 'Gallery',
		'text' => 'Gallery Created Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
				return $this->redirect('gallery');
			}
			else
			{
		//		echo "<pre>";print_r($model->getErrors());exit;
			}
		}
        return $this->render('gallery',['galleryModel'=>$galleryModel,'model'=>$model]);
    }
	public function actionDeletegallery()
	{
		extract($_POST);
		$galleryDet = MerchantGallery::findOne($id);
		if($galleryDet['image']){
				$imagePath =  '../../'.Url::to(['/uploads/merchantgallery/'. $galleryDet['image']]);
					if(file_exists($imagePath)){
					unlink($imagePath);	
					}
			\Yii::$app->db->createCommand()->delete('merchant_gallery', ['id' => $id])->execute();
		}
		return $this->redirect('gallery');
	}
	public function actionOrders()
	{
		extract($_POST);
		$sdate = $_POST['sdate'] ?? date('Y-m-d'); 
		$edate = $_POST['edate'] ?? date('Y-m-d');
		$merchantId = Yii::$app->user->identity->merchant_id;
		$orderprocess = $_REQUEST['orderProcess'] ?? null;
		/*$orderModel = Orders::find()
			->where(['between', 'date(reg_date)', $sdate, $edate ])
			->andWhere(['merchant_id' => $merchantId ])
			
		->orderBy([
				'ID'=>SORT_DESC
			])
		->asArray()->all();*/
		$sqlOrders = 'select * from orders where date(reg_date) between \''.$sdate.'\' and \''.$edate.'\' 
		and merchant_id = \''.$merchantId.'\' ';
		if(!empty($orderprocess)) { 
		$sqlOrders .=' and orderprocess = \''.$orderprocess.'\' ';
		}		
		$sqlOrders .=' order by ID desc';
		$orderModel = Yii::$app->db->createCOmmand($sqlOrders)->queryAll();
		

		$model = new Orders;
		return $this->render('orders',['orderModel'=>$orderModel,'model'=>$model,'sdate'=>$sdate,'edate'=>$edate]);
	}
	public function userCreation($customer_mobile,$customer_name){
		$modelUser = new Users;
		$sqlprevuser = 'select MAX(ID) as id from users';
		$prevuser = Yii::$app->db->createCommand($sqlprevuser)->queryOne();
		$newid = $prevuser['id']+1;
			$modelUser->unique_id = 'FDQ'.sprintf('%06d',$newid);
			$modelUser->name = ucwords($customer_name);
			$modelUser->mobile = trim($customer_mobile); 	
			$modelUser->password = password_hash('112233',PASSWORD_DEFAULT); 	
			$modelUser->status = '1';
			$modelUser->referral_code = 'REFFDQ'.$newid;
			$modelUser->reg_date = date('Y-m-d H:i:s');
			if($modelUser->validate()){
			$modelUser->save();	
			}
			else{
			//print_r($modelUser->getErrors());exit;	
			}
			
			return $modelUser['ID']; 
	}
	public function actionSavetableorder()
	{
    $connection = \Yii::$app->db;	
	$transaction = $connection->beginTransaction();
    try {
		extract($_POST);

		$tablecheck = $tableid;
		if($tableid == 'PARCEL'){
			$tableid = 'PARCEL'.Utility::get_parcel_uniqueid();
		}
		
		$table_det = Tablename::findOne($tableid);
        $cur_order_id = $table_det['current_order_id'];	
		$priceArr =  array_filter($_POST['order_price_popup']) ;
		$qtyArr =  array_filter($_POST['order_quantity_popup']) ;	
		$totalPrice = [];
		foreach ($priceArr as $key=>$price) {
			$totalPrice[] = $price * $qtyArr[$key];
		}
	
		$productprice = array_sum($totalPrice) ??  0;
		$orderArray = array_combine($_POST['product_popup'],$_POST['order_quantity_popup']);
		$orderPriceArray = array_combine($_POST['product_popup'],$_POST['order_price_popup']);

		foreach($orderArray as $key => $value)
		{
			if($value == 0){
				unset($orderArray[$key]);
				unset($orderPriceArray[$key]);
			}

		}

		if(!empty($customer_mobile)){
			$userDet = Users::find()->where(['mobile'=>$customer_mobile])->asArray()->One();
			if(empty($userDet)){
				$userId = $this->userCreation($customer_mobile,$customer_name);	
			}
			else{
				$userId = $userDet['ID'];
			}
			
		}else{
			$userId = '';
		}
		
		if($table_det['table_status'] == '1'){
  
		    $_POST['order_id'] =  $table_det['current_order_id'];
			$_POST['productprice'] = $productprice;
			$_POST['orderArray'] = $orderArray;
			$_POST['orderPriceArray'] = $orderPriceArray;
			$_POST['user_id'] = $userId;
			$_POST['selectedpilot'] = $selectedpilot;
			$this->re_order($_POST);

            
		}
		else{
		

		if(count($orderArray) > 0){
			$merchantid = (string)Yii::$app->user->identity->merchant_id;
			$model = new Orders;
			$model->merchant_id = $merchantid;
			$model->tablename = $tableid;
			$model->user_id = $userId;
			$model->serviceboy_id  = $selectedpilot ;
			$model->order_id = Utility::order_id($merchantid,'order'); 
			$model->txn_id = Utility::order_id($merchantid,'transaction');
			$model->txn_date = date('Y-m-d H:i:s');
			$model->amount = $popupamount ? number_format($popupamount, 2, '.', ',') : 0;
			
					$model->tax = $popuptaxamt;
					$model->tips = number_format($popuptipamt, 2, '.', '');
					$model->subscription = $popupsubscriptionamt;
					$model->couponamount = (string)$couponamountpopup;
					$model->totalamount = (string)$popuptotalamt;
					$model->coupon = $merchant_coupon;
					$model['paymenttype'] = $payment_mode;
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
						    $model->discount_type = $discount_mode;
						    $model->discount_number = $merchant_discount ??  0;
						  //  echo "<pre>";
						  //  print_r($model);exit;
					if($model->save())
						{

							$orderTransaction = new \app\models\OrderTransactions;
							$orderTransaction->order_id = (string)$model->ID;
							$orderTransaction->user_id = $userId;			
							$orderTransaction->merchant_id = $merchantid;
							$orderTransaction->amount = !empty($popupamount) ? number_format(trim($popupamount),2, '.', ',') : 0; 
							$orderTransaction->couponamount =   (string)$couponamountpopup; 
							$orderTransaction->tax =  !empty($popuptaxamt) ? number_format(trim($popuptaxamt),2, '.', ',') : 0; 
							$orderTransaction->tips =  !empty($popuptipamt) ? number_format(trim($popuptipamt),2, '.', ',') : '0'; 
							$orderTransaction->subscription =  !empty($popupsubscriptionamt) ? number_format(trim($popupsubscriptionamt),2, '.', ',') : '0'; 
							$orderTransaction->totalamount =   !empty($popuptotalamt) ? number_format(trim($popuptotalamt),2, '.', ',') : 0; 
							$orderTransaction->paymenttype = $payment_mode;
							$orderTransaction->reorder= '0';
							$orderTransaction->paidstatus = '0';
							$orderTransaction->reg_date = date('Y-m-d H:i:s');
							$orderTransaction->save();
					//echo "<pre>";						print_r($orderTransaction->getErrors());exit;
						$productscount = []; $p=0;$r=1;
						foreach($orderArray as $k => $v)
						{
							$productscount[$p]['order_id'] = $model->ID;
										$productscount[$p]['user_id'] = $userId;
										$productscount[$p]['merchant_id'] = $merchantid;
										$productscount[$p]['product_id'] = trim($k);
										$productscount[$p]['count'] = trim($v);
										$productscount[$p]['price'] = trim($orderPriceArray[$k]);
										$productscount[$p]['inc'] = $r;
										$productscount[$p]['reorder'] = '0';
						$p++;$r++;
						}
					Yii::$app->db
			->createCommand()
			->batchInsert('order_products', ['order_id','user_id','merchant_id','product_id', 'count', 'price','inc','reorder'],$productscount)
			->execute();
			if(!empty($table_det)){
						$tableUpdate = Tablename::findOne($tableid);
						$tableUpdate->table_status = '1';
						$tableUpdate->current_order_id = $model->ID;
						$tableUpdate->save();
			}
								$cur_order_id = $model->ID;

						}
							$title = 'Order has been placed!!';
							if($userId != ''){
								$userDet = Users::find()->where(['mobile'=>$customer_mobile])->asArray()->One();
								$message = "Hi ".$userDet['name'].", Your order has been placed. ".$newid." is your line Please Wait for your turn Thank you.";
										$image = '';
										Yii::$app->merchant->send_sms($userDet['mobile'],$message); 
										if(!empty($userDet['push_id'])){
										\app\helpers\Utility::sendFCM($userDet['push_id'],$title,$message,$image);
										}
							}
							
							if(!empty($selectedpilot)){
								$sqlserviceboyarray = "select * from serviceboy where ID = '".$selectedpilot."'";
								$serviceboyarray = Yii::$app->db->createCommand($sqlserviceboyarray)->queryOne();
							$stitle = 'New order.';
							$smessage = 'New order received please check the app for information.';
							$simage = '';
							
							\app\helpers\Utility::sendFCM($serviceboyarray['push_id'],$stitle,$smessage,$simage);	
							}
							 
												
										
		}
	}
	 $transaction->commit();
	 Yii::$app->getSession()->setFlash('success', [
        'title' => 'Order',
		'text' => 'Order created successfully.',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
	 
				  		if($tablecheck == 'PARCEL'){
	 //return $this->redirect('parcels');
	 	 return $this->redirect(['merchant/placeorder','tableid'=>$tablecheck,'tableName'=>'']);


						}else{
	 //return $this->redirect('currentorders');	
	 	 return $this->redirect(['merchant/placeorder','tableid'=>$tablecheck,'tableName'=>$table_det['name'],'current_order_id'=>$cur_order_id  ]);

						}
    } catch(Exception $e) {
        Yii::trace('======error====='.json_encode($e->getErrors()));
        $transaction->rollback();
    }
	}
	public function re_order($orderData){
		$order_det = Orders::findOne($orderData['order_id']);
		if($order_det['orderprocess'] == '0' || !empty($orderData['selectedpilot']))
		{
		    
		    if(isset($orderData['selectedpilot'])){
		        $sqlTableStatusUpdate = ' update orders set serviceboy_id = \''.$orderData['selectedpilot'].'\' where ID = \''.$orderData['order_id'].'\'';
		        $TableStatusUpdate = Yii::$app->db->createCommand($sqlTableStatusUpdate)->execute();
				
		    }
			



		
			$selectedpilot = $orderData['selectedpilot'];
		    	if(!empty($selectedpilot)){
						$serviceboyarray = Serviceboy::findOne($selectedpilot);
						$stitle = 'New order.';
						$smessage = 'New order received please check the app for information.';
						$simage = '';
						if(!empty($order_det['user_id'])){
							$userdetails = Users::findOne($order_det['user_id']);
						}
						$notificationdet = ['type' => 'NEW_ORDER','orderamount' => $order_det['totalamount'],'username' => !empty(@$userdetails['name']) ? $userdetails['name'] : null];
						Utility::sendNewFCM($serviceboyarray['push_id'],$stitle,$smessage,$simage,'6',null,$order_det['ID'],$notificationdet);
				}
		}
		
	$product_popup = $_POST['product_popup'];
		//$prevOrderProductDetails = \app\models\OrderProducts::find()->where(['order_id'=>$order_det->ID])->asArray()->all();
		$sqlprevOrderProductDetails = 'select sum(CAST(count AS UNSIGNED)) cnt,product_id from order_products where order_id = \''.$order_det->ID.'\'   
		group by product_id';
		$prevOrderProductDetails = Yii::$app->db->createCommand($sqlprevOrderProductDetails)->queryAll();
		
		//$prevOrderIdArr = array_column($prevOrderProductDetails,'ID');
					$prevOrderProductsArr = (array_column($prevOrderProductDetails,'product_id'));
					//echo "<pre>";print_r($prevOrderProductsArr);exit;
										$prevOrderProductsCntArr = (array_column($prevOrderProductDetails,'cnt','product_id'));
				//	echo "<pre>";print_r($prevOrderProductsCntArr);exit;
					$newProductIdArr = array_diff($orderData['product_popup'],$prevOrderProductsArr);
					
			$sqlRecorderCount  = 'select max(CAST(reorder AS UNSIGNED)) as max_reorder from order_products where order_id=\''.$order_det->ID.'\'';
			$reorderCount = Yii::$app->db->createCommand($sqlRecorderCount)->queryOne();
			
		$orderUpdateArray = array_combine($_POST['product_popup'],$_POST['order_quantity_popup']);
		$orderUpdatePriceArray = array_combine($_POST['product_popup'],$_POST['order_price_popup']);
        
//	echo "<pre>";		print_r($orderUpdateArray);exit;        
        
		$productprice = $orderData['popupamount'];
		$order_det->amount =(string)$orderData['popupamount'];
		            
					$tipsamount = $orderData['popuptipamt'];
					$taxsamount = number_format((float)$orderData['popuptaxamt'], 2, '.', '');
					$subscriptionamount = number_format($orderData['popupsubscriptionamt'], 2, '.', '');
					$savingamt = 0;
					$order_det->user_id = (string)$orderData['user_id'];
					$order_det->tax = $taxsamount;
					$order_det->tips = number_format($tipsamount, 2, '.', '');
					$order_det->subscription = $subscriptionamount;
					$order_det->couponamount = $orderData['couponamountpopup'];
					$order_det->coupon = $orderData['merchant_coupon'];
					$order_det->discount_number = !empty(@$orderData['merchant_discount']) ? $orderData['merchant_discount'] :  0;
					$compleamt = $orderData['popuptotalamt'];
					$order_det->totalamount = (string)$compleamt;
					$order_det->reorderprocess = '1';
					$order_det->pending_amount = $orderData['pending_amount'];
		
		

		if($order_det->save())
					{
							$orderTransaction = new \app\models\OrderTransactions;
							$orderTransaction->order_id = (string)$order_det->ID;
							$orderTransaction->user_id = $orderData['user_id'];
							$orderTransaction->merchant_id = (string)$order_det->merchant_id;
							$orderTransaction->amount = !empty($productprice) ? number_format(trim($productprice),2, '.', ',') : 0; 
							$orderTransaction->couponamount =   $orderData['couponamountpopup']; 
							$orderTransaction->tax =  !empty($taxsamount) ? number_format(trim($taxsamount),2, '.', ',') : 0; 
							$orderTransaction->tips =  !empty($tipsamount) ? number_format(trim($tipsamount),2, '.', ',') : 0; 
							$orderTransaction->subscription =  !empty($subscriptionamount) ? number_format(trim($subscriptionamount),2, '.', ',') : 0; 
							$orderTransaction->totalamount =   !empty($compleamt) ? number_format(trim($compleamt),2, '.', ',') : 0; 
							$orderTransaction->paymenttype = $order_det->paymenttype;
							$orderTransaction->reorder= '1';
							$orderTransaction->paidstatus = '0';
							$orderTransaction->reg_date = date('Y-m-d H:i:s');
							$orderTransaction->save();
				
		$diff_del_arr = array_values(array_diff($prevOrderProductsArr,$product_popup));
		
		for($d=0;$d<count($diff_del_arr);$d++){
			$sqlUpdateOrderProductd = 'delete from order_products
			where merchant_id = \''.$order_det->merchant_id.'\' and order_id =\''.$order_det->ID.'\' 
			and product_id = \''.$diff_del_arr[$d].'\'';
			$updateOrderProductd = Yii::$app->db->createCommand($sqlUpdateOrderProductd)->execute();

		}
		for($prev = 0; $prev<count($product_popup);$prev++){
		    $insertCondition = 0;
		    if(in_array($product_popup[$prev],$prevOrderProductsArr)){
		        if($orderUpdateArray[$product_popup[$prev]]  != $prevOrderProductsCntArr[$product_popup[$prev]]){
     		    $orderCount = $orderUpdateArray[$product_popup[$prev]] - $prevOrderProductsCntArr[$product_popup[$prev]];
		               if($orderCount > 0){
		                   
		               }else{
		                $orderCount  =  $orderUpdateArray[$product_popup[$prev]];         
		                $sqlUpdateOrderProduct = 'delete from order_products
		                where merchant_id = \''.$order_det->merchant_id.'\' and order_id =\''.$order_det->ID.'\' 
		                and product_id = \''.$product_popup[$prev].'\'';
		                $updateOrderProduct = Yii::$app->db->createCommand($sqlUpdateOrderProduct)->execute();
		                   
		               }
		               		                 $insertCondition++;  


		        }
		    }      
		        else{
		            $insertCondition++;
		            $orderCount = $orderUpdateArray[$product_popup[$prev]];
		        }
if($insertCondition > 0){
$re_Order =     (int)$reorderCount['max_reorder'] + 1;
			    $prevOrderUpdate = new \app\models\OrderProducts;
			    $prevOrderUpdate->merchant_id = $order_det->merchant_id;
				$prevOrderUpdate->product_id = $product_popup[$prev];
				$prevOrderUpdate->user_id = $orderData['user_id'];
				$prevOrderUpdate->order_id = (string)$order_det->ID;
				$prevOrderUpdate->price = $orderUpdatePriceArray[$product_popup[$prev]];
				$prevOrderUpdate->count = (string)$orderCount;
				$prevOrderUpdate->reorder = (string)$re_Order;
				$prevOrderUpdate->inc = '1';
				$prevOrderUpdate->reg_date = date('Y-m-d H:i:s');
				if($prevOrderUpdate->save()){
				    
				}else{
				    echo "<pre>";print_r($prevOrderUpdate->getErrors());
				}
    
}
			}
			
			}
			else{
			        echo "<pre>";print_r($order_det->getErrors());exit;exit;
			}
		$newArray = [];
		

//					Yii::$app->db
//			->createCommand()
//			->batchInsert('order_products', ['user_id','order_id','merchant_id','product_id', 'count', 'price','inc'],$newArray)
//			->execute();
		
		
	}
	
	public function actionTableplaceorder()
	{
	    extract($_REQUEST);
	    if(!empty($sectionId)){
	        $tableDetails = Tablename::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id ,'status'=> '1', 'section_id'=>$sectionId])->asArray()->all();
	    }
	    else{
		$tableDetails = Tablename::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id,'status'=> '1'])->asArray()->all();
	    }
	    
		$sqlTableSections = 'select s.section_name,tn.section_id,tn.ID table_id  from sections s 
		inner join tablename tn on s.ID = tn.section_id 
		where s.merchant_id =  \''.Yii::$app->user->identity->merchant_id.'\' and tn.status = \'1\'';
		$tableSections = Yii::$app->db->createCommand($sqlTableSections)->queryAll();
		
		return $this->render('tableorder',['tableDetails'=>$tableDetails,'tableSections'=>$tableSections]);
	}
	public function actionTableorder()
	{
		$sqlTableDetails = 'select o.ID,tn.ID tableId,tn.name,o.orderprocess
		,o.totalamount from tablename tn left join orders o on tn.current_order_id = o.id 
		where tn.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' ';
		$tableDetails = Yii::$app->db->createCommand($sqlTableDetails)->queryAll();
				$sqlServiceBoy = 'select * from serviceboy where merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and loginstatus = \'1\'';
		$resServiceBoy = Yii::$app->db->createCommand($sqlServiceBoy)->queryAll();
		//echo "<pre>";	print_r($tableDetails);exit;
		return $this->render('tableorderstatus',['tableDetails'=>$tableDetails,'resServiceBoy'=>$resServiceBoy]);
	}
	public function actionTableorderstatuschange()
	{
		extract($_POST);
		$pilotassign = $pilotassign ?? '';
		$orderUpdate = Orders::findOne($id);
		$tableUpdate = Tablename::findOne($tableId);

		//		$orderUpdate->serviceboy_id = $pilotassign ?? '';
        if(!empty($chageStatusId)){
            $orderUpdate->orderprocess = $chageStatusId;    
        }
		else{
			$chageStatusId = $orderUpdate->orderprocess; 
		}
		
		
		if(!empty($kdschange)){
		    $orderUpdate->preparedate = date('Y-m-d H:i:s');
			if(!empty($orderUpdate['serviceboy_id'])){
				$serviceboyarray = Serviceboy::findOne($orderUpdate['serviceboy_id']);

				$notificaitonarary = array();
				$notificaitonarary['merchant_id'] = $orderUpdate['merchant_id'];
				$notificaitonarary['serviceboy_id'] = $orderUpdate['serviceboy_id'];
				$notificaitonarary['order_id'] = (string)$id;
				$notificaitonarary['title'] = 'Prepared Order';
				$notificaitonarary['message'] = 'Order '.$orderUpdate['order_id'].' on '.$tableUpdate['name'].'-'.$tableUpdate->section['section_name'].' is Prepared';
				$notificaitonarary['ordertype'] = 'prepared';
				$notificaitonarary['seen'] = '0';
								
				$serviceBoyNotiModel = new  ServiceboyNotifications;
				$serviceBoyNotiModel->attributes = $notificaitonarary;
				$serviceBoyNotiModel->reg_date = date('Y-m-d H:i:s');
				$serviceBoyNotiModel->mod_date = date('Y-m-d H:i:s');
				if(!$serviceBoyNotiModel->save()){
					print_r($serviceBoyNotiModel->getErrors());exit;
				}

			}
		}
		if(!empty($cancelReason)){
		    $orderUpdate->cancel_reason = $cancelReason;
		}
		$orderUpdate->save();
		
		
		

		if(!empty($tableUpdate) || $tableId == 'PARCEL'){
			
		
		if($chageStatusId == '4' || $chageStatusId == '3')
		{
			$table_status = null;
			$current_order_id = 0;
			if($chageStatusId == '4')
			{
				$arr = ['order_id' => $id];
				$stockded = Yii::$app->merchant->deductstockfrominventory($arr);
			}
		}
		else{
			$table_status = $chageStatusId;
			$current_order_id = $id;
		}
		

		
		
		if(!empty($tableUpdate)){
			$tableUpdate->table_status = $table_status;
		$tableUpdate->current_order_id = $current_order_id;
		if($tableUpdate->save()){
			return "Status Updated Sucessfully";
		}
		else{
			return "Status Not Updated";
		}
		}
		}
	}
	public function actionProductautocomplete(){
		extract($_POST);
		$like = '%'.$query.'%';
		$sql = 'SELECT distinct title title from product where title like \''.$like.'\' and merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'';
		$res = YIi::$app->db->createCommand($sql)->queryAll();
			 return  stripslashes(json_encode(array_column($res,'title'), JSON_UNESCAPED_SLASHES));
	}
	public function actionApplycouponautocomplete()
	{
			extract($_REQUEST);

		$like = '%'.$query.'%';
		$sql = 'SELECT  code,concat(price,\'-\',type) price from merchant_coupon where code like \''.$like.'\' 
		and merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and status = \'Active\' AND \''.date('Y-m-d').'\' between  date(fromdate) and date(todate)  ';
		$res = Yii::$app->db->createCommand($sql)->queryAll();
if(!empty($res)){
	return  stripslashes(json_encode($res, JSON_UNESCAPED_SLASHES));
}
else{
	return json_encode([]);
}


	}
	public function actionCheckcouponcode(){
	    extract($_REQUEST);
		$sqlc = 'SELECT  * from merchant_coupon where code = \''.$id.'\' 
		and merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
		and status = \'Active\' AND \''.date('Y-m-d').'\' between  date(fromdate) and date(todate)  ';
		$sql = Yii::$app->db->createCommand($sqlc)->queryOne();
		if(!empty($sql)){
			$payload = Yii::$app->merchant->applyCoupon(['applied_coupon_amount' => $_REQUEST['discount_amount'],'couponDetails' => $sql,
			'sub_total_amount' => $_REQUEST['sub_total_amount']
			,'merchant_id' => Yii::$app->user->identity->merchant_id
			,'mobile_number' => $_REQUEST['mobile_number'], 'username' => $_REQUEST['username']
		]);
		}
		else{
		    $payload = ['status' => '0','message' => 'Invalid Coupon Code'];
		}
		return json_encode($payload);
	}
	
	public function actionAutocompleteingredient(){
		extract($_REQUEST);
		$like = '%'.$query.'%';
		$sql = 'SELECT  * from ingredients where item_name like \''.$like.'\' and merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'';
		
		$res = YIi::$app->db->createCommand($sql)->queryAll();
		 return  stripslashes(json_encode($res, JSON_UNESCAPED_SLASHES));

	}
	public function actionAutocompletevendor()
	{
		extract($_REQUEST);
		$like = '%'.$query.'%';
		$sql = 'select * from merchant_vendor where store_name like \''.$like.'\' and merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'';
		$res = Yii::$app->db->createCommand($sql)->queryAll();
		 return  stripslashes(json_encode($res, JSON_UNESCAPED_SLASHES));
	}
	public function actionUpdatemerchatprofile(){
		$model =  Merchant::findOne(Yii::$app->user->identity->merchant_id);
		$oldLogo = $model['logo'];
		$oldQrLogo = $model['qrlogo'];
		$oldCoverpic = $model['coverpic'];
if($model->load(Yii::$app->request->post())){
	$logoimage = UploadedFile::getInstance($model, 'logo');
	$qrlogoimage = UploadedFile::getInstance($model, 'qrlogo');
	$coverpicimage = UploadedFile::getInstance($model, 'coverpic');	
	if($logoimage){
					if(!empty($oldLogo)){
					$imagePath =  '../../'.Url::to(['/uploads/merchantimages/'. $oldLogo]);
					if(file_exists($imagePath)){
						unlink($imagePath);	
					}
					
				}
				
				$logoimagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$logoimage->extension;
				$logoimage->saveAs('../../merchantimages/' . $logoimagename);
				$model->logo = $logoimagename;

			}else{
				$model->logo = $oldLogo;
			}
	
			if($qrlogoimage){
				if(!empty($oldQrLogo)){
					$imagePath =  '../../'.Url::to(['/uploads/merchantimages/'. $oldQrLogo]);
					if(file_exists($imagePath)){
						unlink($imagePath);	
					}
				}
				$qrlogoimagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$qrlogoimage->extension;
				$qrlogoimage->saveAs('../../merchantimages/' . $qrlogoimagename);
				$model->qrlogo = $qrlogoimagename;
			}else{
				$model->qrlogo = $oldQrLogo;
			}
			if($coverpicimage){
				if(!empty($oldCoverpic)){
					$imagePath =  '../../'.Url::to(['/uploads/merchantimages/'. $oldCoverpic]);
					if(file_exists($imagePath)){
						unlink($imagePath);	
					}
				}
				$coverpicimageimagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$coverpicimage->extension;
				$coverpicimage->saveAs('../../merchantimages/' . $coverpicimageimagename);
				$model->coverpic = $coverpicimageimagename;
			}else{
				$model->coverpic = $oldCoverpic;
			}

if($model->validate()){
    
			$model->save();
			$this->refresh();
		}
		else
		{
			echo "<pre>";print_r($model->getErrors());exit;

		}	

}		

		return $this->render('merchantprofile',['model'=>$model]);
	}
	public function actionRemoveimage(){
		extract($_POST);
		$model =  Merchant::findOne($id);
		$removableImage = $model[$col];
		if(!empty($removableImage)){
					$imagePath =  '../../'.Url::to(['/uploads/merchantimages/'. $removableImage]);
					if(file_exists($imagePath)){
						unlink($imagePath);
						$model->$col = null;
						$model->save();
					}
				}
				 
	}
	public function actionChangepassword()
	{
		$model =  Merchant::findOne(Yii::$app->user->identity->merchant_id);
		$model->scenario = 'chagepassword';
		
			if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$password = password_hash($_POST['Merchant']['newpassword'],PASSWORD_DEFAULT);
		$sqlupdateMerchant = 'update merchant set password = \''.$password.'\' where ID = \''.Yii::$app->user->identity->merchant_id.'\'';
		$updateMerchant = Yii::$app->db->createCommand($sqlupdateMerchant)->execute();

		$sqlupdateMerchantUser = 'update merchant_employee	 set emp_password = \''.$password.'\' where merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and emp_role = \'0\'';
		$updateMerchantUser = Yii::$app->db->createCommand($sqlupdateMerchantUser)->execute();		
		return $this->redirect('updatemerchatprofile');

	}
	public function actionIngredients()
	{
		$merchantId = Yii::$app->user->identity->merchant_id;
		$ingredientsModel = \app\models\Ingredients::find()
			->where(['merchant_id' => $merchantId])
		->orderBy([
				'ID'=>SORT_DESC
			])
		->asArray()->all();
		
		$model = new Ingredients;
		$model->scenario = 'addingredient';
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}

		if ($model->load(Yii::$app->request->post()) ) {
			$ingredientsArr = Yii::$app->request->post('Ingredients');
			$model->merchant_id = (string)$merchantId;
			$model->status = '1';
			$model->reg_date = date('Y-m-d H:i:s');
			$image = UploadedFile::getInstance($model, 'photo');
			if($image){
				$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$image->extension;
				$image->saveAs('uploads/ingredients/' . $imagename);
				$model->photo = $imagename;
			}
			if($model->validate()){
				$model->save();
	Yii::$app->getSession()->setFlash('success', [
        'title' => 'Ingredient',
		'text' => 'Ingredient Created Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
				return $this->redirect('ingredients');
			}
			else
			{
				echo "<pre>";print_r($model->getErrors());exit;
			}
		}
    
    return $this->render('ingredients',['ingredientsModel'=>$ingredientsModel,'model'=>$model]);
    
	}
	public function actionUpdateingredientpopup()
	{
		extract($_POST);
		$ingredientsModel = Ingredients::findOne($id);
		return $this->renderAjax('editingredient', ['model' => $ingredientsModel,'id'=>$id]);		
	}
	public function actionUpdateingredients()
	{
		$model = new Ingredients;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$ingredientsArr = Yii::$app->request->post('Ingredients');
		$ingredientsUpdate = Ingredients::findOne($_POST['Ingredients']['ID']);
		$oldPhoto = $ingredientsUpdate['photo']; 
		$ingredientsUpdate->attributes = \Yii::$app->request->post('Ingredients');
		$ingredientsUpdate->modify_date = date('Y-m-d H:i:s');
		$image = UploadedFile::getInstance($model, 'photo');
			if($image){
				if(!empty($ingredientsUpdate['photo'])){
					$imagePath =  '../../'.Url::to(['/uploads/ingredients/'. $ingredientsUpdate['photo']]);
					if(file_exists($imagePath)){
						unlink($imagePath);	
					}
				}
				
				$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$image->extension;
				$image->saveAs('uploads/ingredients/' . $imagename);
				$ingredientsUpdate->photo = $imagename;
			}else{
				$ingredientsUpdate->photo = $oldPhoto;
			}
		if($ingredientsUpdate->validate()){
			$ingredientsUpdate->save();
			Yii::$app->getSession()->setFlash('success', [
        'title' => 'Ingredients',
		'text' => 'Ingredient Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		}else{
			//echo "<pre>";print_r($ingredientsUpdate->getErrors());exit;
		}
			return $this->redirect('ingredients');
	
	}
	public function actionRecipeproducts()
	{
		$merchantId = Yii::$app->user->identity->merchant_id;
	$sqlproductDetails = 'select P.ID,P.title,P.food_category_quantity,P.price,P.image 
		,case when food_type_name is not null then concat(title ,\' (\' , food_type_name , \')\') else title end  title_quantity  
		from product P left join food_category_types fct on fct.ID =  P.food_category_quantity and fct.merchant_id =  \''.Yii::$app->user->identity->merchant_id.'\'
		where P.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' ORDER BY ID DESC';
			$productDetails = Yii::$app->db->createCommand($sqlproductDetails)->queryAll();
			
	$sqlIngredients = 'select mr.ID,mr.ingredient_id,i.item_name,mr.product_id,mr.ingred_quantity,mr.ingred_units,mr.ingred_price  from merchant_recipe mr  
	inner join ingredients i on mr.ingredient_id = i.ID
	where mr.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' ';
	$resIngredients = Yii::$app->db->createCommand($sqlIngredients)->queryAll();
	
	$addedIngreds = array_column($resIngredients,'ingredient_id');
	$prevRecipeing = \yii\helpers\ArrayHelper::index($resIngredients, null, 'product_id');

		$ingredientsDet =  Ingredients::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
		$ingredIdNameArr =  array_column($ingredientsDet,'item_name');
			 
	return 	$this->render('recipeproducts',['productModel'=>$productDetails,'prevRecipeing'=>json_encode($prevRecipeing)
	,'addedIngreds'=>json_encode($addedIngreds),'ingredIdNameArr'=>json_encode($ingredIdNameArr)]);
	}
	public function actionSaverecipe()
	{
		extract($_POST);
		if(!empty(array_filter($ingredients))){
			$ingredientsDet =  Ingredients::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
			$ingredIdNameArr =  array_column($ingredientsDet,'ID','item_name');
			$merchantRecpie = MerchantRecipe::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id,'product_id'=>$productid])->asArray()->all();
			$prevAddIngred = array_column($merchantRecpie,'ingredient_id');
			//echo "<pre>";			print_r($prevAddIngred);print_r($_POST['ingredients']);print_r($commonIngredIdArr);exit;
	for($i=0;$i<count($ingredients);$i++)
			{
				

				if (in_array($ingredIdNameArr[$_POST['ingredients'][$i]], $prevAddIngred )) {
    				Yii::$app->getSession()->setFlash('error', [
					'title' => 'Recipe',
					'text' => $_POST['ingredients'][$i].' is already in your recipe',
					'type' => 'error',
					'timer' => 3000,
					'showConfirmButton' => false
					]);				
						return $this->redirect('recipeproducts');
					exit;
				}
				else{
					$data[] = [$productid,$ingredIdNameArr[$_POST['ingredients'][$i]],$_POST['quantity'][$i]
					,$qty_units[$i],'0'
				,'1',(string)Yii::$app->user->identity->merchant_id,date('Y-m-d H:i:s A')];
				}
				
			}
			
		
			Yii::$app->db
			->createCommand()
			->batchInsert('merchant_recipe', ['product_id','ingredient_id','ingred_quantity','ingred_units','ingred_price','status','merchant_id', 'reg_date'],$data)
			->execute();
			Yii::$app->getSession()->setFlash('success', [
        'title' => 'Recipe',
		'text' => 'Recipe Added Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);		

				
			
		}
		else{

		}
		return $this->redirect('recipeproducts');
	}
	public function actionDeleteingredientfromrecipe()
	{
		extract($_POST);
		$sql = 'delete from merchant_recipe where ID = \''.$id.'\'';
		$res = Yii::$app->db->createCommand($sql)->execute();
		return 'sucess';
	}
	public function actionViewrecpie()
	{
		extract($_POST);
		$sql = 'select case when food_type_name is not null then concat(title ,\' (\' , food_type_name , \')\') else title end  title_quantity  
		,p.price,i.item_name,mr.ingred_quantity,mr.ingred_price,mr.ingred_units from merchant_recipe mr 
		inner join ingredients i on mr.ingredient_id = i.ID 
		inner join product p on mr.product_id = p.ID
		left join food_category_types fct on fct.ID =  p.food_category_quantity and fct.merchant_id =  \''.Yii::$app->user->identity->merchant_id.'\'
		where product_id = \''.$id.'\'';
		$res = Yii::$app->db->createCommand($sql)->queryAll();
		
		return $this->render('recpieview',['res'=>$res]);
	}
    public function actionAddinventory()
	{
		extract($_POST);
		$sdate = $_POST['sdate'] ?? date('Y-m-d'); 
		$edate = $_POST['edate'] ?? date('Y-m-d');
		$vendorModel = MerchantVendor::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id,'status'=>'1'])->asArray()->all();
		$ingredientsDet =  Ingredients::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
		$ingredIdNameArr =  array_column($ingredientsDet,'item_name');
		$sqlPurchaseDetail = 'select * from ingredient_purchase ip 
		where merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and date(reg_date) between \''.$sdate.'\' and \''.$edate.'\' order by reg_date desc';
		$resPurchaseDetail = Yii::$app->db->createCommand($sqlPurchaseDetail)->queryAll();
		return $this->render('addinventory',['sdate'=>$sdate,'edate'=>$edate
		,'ingredIdNameArr'=>json_encode($ingredIdNameArr)
		,'resPurchaseDetail'=>$resPurchaseDetail,'vendorModel'=>$vendorModel]);
	}
    public function actionIngredientstock()
	{
		extract($_POST);
		$sdate = $_POST['sdate'] ?? date('Y-m-d'); 
		$edate = $_POST['edate'] ?? date('Y-m-d');
		$type = $_POST['type'] ?? 'detail';
		$fcid = $_POST['fc_id'] ?? '';

		//echo $type;exit;
		if($type == 'detail'){
		$sqlStock = 'select * from ingredient_stock_register where created_on between \''.$sdate.'\' and \''.$edate.'\' order by created_on , ingredient_name';
		
		}
		else{
		$sqlStock = 'with opening as (
		select opening_stock,ingredient_id from  ingredient_stock_register where created_on = \''.$sdate.'\'
		),
		inandout as (
		select ingredient_name,sum(stock_in) stock_in,sum(stock_out) stock_out,sum(wastage) wastage,ingredient_id 
		from ingredient_stock_register where created_on between \''.$sdate.'\' and \''.$edate.'\' group by  ingredient_id	
		)
		,cb as (
		select closing_stock,ingredient_id from  ingredient_stock_register where created_on = \''.$edate.'\'
		)
		select coalesce(opening_stock,0) opening_stock
		,coalesce(stock_in,0) stock_in
,coalesce(stock_out,0) stock_out
,coalesce(wastage,0) wastage
,coalesce(closing_stock,0) closing_stock,ingredient_name		
		from inandout left join  opening on inandout.ingredient_id = opening.ingredient_id
		left join  cb on inandout.ingredient_id = cb.ingredient_id
		';
	//	echo $sqlStock;exit;
				}
			$resStock = Yii::$app->db->createCommand($sqlStock)->queryAll();
	//	echo "<pre>";print_r($resStock);exit;
		return $this->render('ingredientstock',['sdate'=>$sdate,'edate'=>$edate
		,'resStock'=>$resStock,'type'=>$type,'fcid'=>$fcid]);
	}
    public function actionSaveinventory()
	{
		extract($_POST);
		    $connection = \Yii::$app->db;	
		$transaction = $connection->beginTransaction();
    try {
		if(!empty(array_filter($ingredients))){
			$ingredientsDet =  Ingredients::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
			$ingredIdNameArr =  array_column($ingredientsDet,'ID','item_name');
			$currentSeq = \app\models\SequenceMaster::find()->where(['seq_name'=>'purchase_number','merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->one();
			$purchaseNumber = 'PN'.date('dmy').($currentSeq['seq_number']+1);
			$updateSqlSeq = 'update sequence_master set seq_number = \''.($currentSeq['seq_number']+1).'\' where ID = \''.$currentSeq['ID'].'\''  ;
			$resupdateSqlSeq = Yii::$app->db->createCommand($updateSqlSeq)->execute();
			$merchantVendorDet = MerchantVendor::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
			$merchantNameIDArr = array_column($merchantVendorDet,'store_name','ID');
					$model = new IngredientPurchase;
					$model->purchase_number = $purchaseNumber;
					$model->vendor_name = $merchantNameIDArr[$vendorname];
					$model->vendor_id = $vendorname;
					$model->merchant_id = (string)Yii::$app->user->identity->merchant_id;
					$model->vendor_bill_no = $billno; 
					$model->purchase_amount = $amount;
					$model->amount_paid  = $paid;
					$model->balance_amount   = $balance;
					$model->purchase_gst   = $TAX;
					$model->purchase_payment_type = $payment_type;
					$model->purchase_transcation_id = $transaction_id;
					$model->status = 1;
					$model->reg_date = date('Y-m-d H:i:s');
					$model->modify_date = date('Y-m-d H:i:s');
					if($model->validate()){
						$model->save();

	
						for($i=0;$i<count($ingredients);$i++)
						{
							
							if($qtytype[$i] == '1' || $qtytype[$i] == '2')
							{
								$qtygml =  $volume[$i] * 1000;
							}
							else{
								$qtygml = $volume[$i];
							}
							$data[] = [$model->getPrimaryKey(),$ingredIdNameArr[$ingredients[$i]],$ingredients[$i],$volume[$i]
							,$qtytype[$i],$qtygml,0,$qtygml,$sum[$i],$bulk[$i],$quantity[$i],$price[$i],$effective_price[$i]];
							$this->stockInsertUpdate($ingredIdNameArr[$ingredients[$i]],$ingredients[$i],$volume[$i],$qtytype[$i]); 
						}
						Yii::$app->db
			->createCommand()
			->batchInsert('ingredient_purchase_detail', ['purchase_id','ingredient_id','ingredient_name','purchase_quantity','purchase_qty_type'
			,'purchase_qty_units','used_qty','remaining_qty' , 'purchase_price'
			,'bag_quantity','each_quantity','quantity_price','effective_price'],$data)
			->execute();
Yii::$app->getSession()->setFlash('success', [
        'title' => 'Inventory',
		'text' => 'Ingredient Inventory Added Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
	
		 $transaction->commit();										
					}
					else
					{
					//	echo "<pre>";print_r($model->getErrors());exit;
					}
					
					$this->redirect('addinventory');
			}
	}catch(Exception $e) {
        $transaction->rollback();
    }
	}	
	public function actionViewpurchasedetail()
	{
		extract($_POST);
		$sql = 'select * from ingredient_purchase_detail ipd 
		inner join ingredient_purchase ip on ip.ID = ipd.purchase_id	 where purchase_id = \''.$id.'\'';
		$res = Yii::$app->db->createCommand($sql)->queryAll();
		return json_encode($res);
	}
	public function stockInsertUpdate($ingredientId,$ingredientName,$quantity,$qty_units)
	{
		$resStock = IngredientStockRegister::find()->where(['created_on' => date('Y-m-d')
		,'merchant_id' => Yii::$app->user->identity->merchant_id,'ingredient_id' => $ingredientId])->asArray()->All(); 

		if($qty_units == '1' || $qty_units == '2')
		{
			$quantity = $quantity * 1000;
		}
		else{
			$quantity = $quantity ;
		}
		if(count($resStock) > 0){
			$sqlUpdateStock = 'UPDATE ingredient_stock_register set stock_in = \''.($resStock[0]['stock_in']+$quantity).'\'
			,closing_stock = \''.($resStock[0]['closing_stock']+$quantity).'\' where created_on = \''.date('Y-m-d').'\' 
		and merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and ingredient_id = \''.$ingredientId.'\'';
			$resUpdateStock = Yii::$app->db->createCommand($sqlUpdateStock)->execute();
		}else{
			$stockModel = new \app\models\IngredientStockRegister;
			$stockModel->merchant_id = (string)Yii::$app->user->identity->merchant_id;
			$stockModel->ingredient_id  = $ingredientId;
			$stockModel->ingredient_name  = $ingredientName;
			$stockModel->opening_stock = 0;
			$stockModel->stock_in = $quantity;
			$stockModel->stock_out = 0;
			$stockModel->wastage = 0;
			$stockModel->closing_stock = $quantity;			
			$stockModel->reg_date = date('Y-m-d H:i:s');
			$stockModel->created_on = date('Y-m-d');			
			$stockModel->save();
		}
	}
    public function actionVendorlist()
	{
		$vendorModel = MerchantVendor::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id,'status'=>'1'])->asArray()->all();
		$model = new MerchantVendor;

if ($model->load(Yii::$app->request->post()) ) {
			$model->merchant_id = (string)Yii::$app->user->identity->merchant_id;
			$model->created_by = (string)Yii::$app->user->identity->merchant_id;
			$model->reg_date = date('Y-m-d H:i:s');
			$model->status = 1;
			if($model->validate()){
				$model->save();
	Yii::$app->getSession()->setFlash('success', [
        'title' => 'Vendor Creation',
		'text' => 'Vendor Created Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
				return $this->redirect('vendorlist');
			}
			else
			{
				echo "<pre>";print_r($model->getErrors());exit;
			}
		}
		return $this->render('merchant_vendor',['vendorModel'=>$vendorModel,'model'=>$model]);
	}
    public function actionOrder_recipe_cost_card()
	{
		extract($_POST);
		$sql = 'select 
		o.amount,o.order_id,morc.product_id
		,case when food_type_name is not null then concat(title ,\' (\' , food_type_name , \')\') else title end  title_quantity 
		,morc.ingredi_name,ingredi_price,ingredi_qty,ingred_quantity recipe_quantity,ingred_units,price cost_price
		from merchant_order_recipe_cost morc
		inner join orders o on morc.order_id = o.ID
		inner join product p on morc.product_id = p.ID
		inner join merchant_recipe mr on mr.product_id = morc.product_id and mr.ingredient_id = morc.ingredi_id 
		left join food_category_types fct on fct.ID =  p.food_category_quantity
		where morc.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
		and morc.order_id = \''.$id.'\'';
		$res = Yii::$app->db->createCOmmand($sql)->queryAll();
		$productIdArr = array_column($res,'product_id');
		if(count($productIdArr)>0){
		$productCountValues =	array_count_values($productIdArr);
		}else{
			$productCountValues = [];
		}
		
		return $this->render('orderrecipecostcard',['res'=>$res,'productCountValues'=>$productCountValues]); 
	}
    public function actionEmployeelist()
	{
		$model = new MerchantEmployee;
		$model->scenario = 'insertemail';
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$merchantId = (string)Yii::$app->user->identity->merchant_id;
		$empModel = MerchantEmployee::find()
					->where(['merchant_id' => $merchantId,'emp_status'=>'1'])
					 ->andWhere(['<>','emp_role', 0])
					->orderBy(['ID'=>SORT_DESC])
					
					->asArray()->all();
	$connection = \Yii::$app->db;	
	$transaction = $connection->beginTransaction();
	try {		
		if ($model->load(Yii::$app->request->post()) ) {
			$MerchantEmployeeArr = Yii::$app->request->post('MerchantEmployee');
			$hashPassword = password_hash($MerchantEmployeeArr['emp_password'], PASSWORD_DEFAULT);
			$model->merchant_id = (string)$merchantId;
			$model->emp_id = Utility::emp_uniqueid('merchant_employee','EMP',$merchantId);
			$model->emp_password =  $hashPassword;
			$model->emp_status = '1';
			$model->loginstatus = '0' ;
			$model->loginaccess = '0' ;
			$model->reg_date = date('Y-m-d H:i:s');
			$model->mod_date = date('Y-m-d H:i:s');
			$model->created_by = Yii::$app->user->identity->emp_name;
			if ( $model->validate() ) {
				$model->save();
				$emproleDet = EmployeeRole::findOne($MerchantEmployeeArr['emp_role']);
				if($emproleDet['role_name'] == 'PILOT')
				{
							$serviceModel = new Serviceboy;
							$serviceModel->merchant_id = (string)$merchantId;
							$serviceModel->unique_id = Utility::get_uniqueid('serviceboy','SBOY');
							$serviceModel->employee_id = $model->ID;
							$serviceModel->name = $MerchantEmployeeArr['emp_name'] ;
							$serviceModel->mobile = $MerchantEmployeeArr['emp_phone'] ;
							$serviceModel->email = $MerchantEmployeeArr['emp_email'] ;
							$serviceModel->password = $hashPassword ;
							$serviceModel->status = '1' ;
							$serviceModel->loginstatus = '0' ;
							$serviceModel->loginaccess = '0' ;
							$serviceModel->joiningdate = $MerchantEmployeeArr['date_of_join'];
							$serviceModel->reg_date = date('Y-m-d H:i:s');
							$serviceModel->mod_date = date('Y-m-d H:i:s');
								if ( $serviceModel->validate() ) {
									$serviceModel->save();
									if(!empty($_POST['sectiongroup'])){
										for($i=0; $i < count($_POST['sectiongroup']); $i++ ) {
											$modelPilotTable = new PilotTable;
											$modelPilotTable->merchant_id = $merchantId;
											$modelPilotTable->section_id = $_POST['sectiongroup'][$i];
											$modelPilotTable->serviceboy_id  = $serviceModel->ID;
											$modelPilotTable->created_on = date('Y-m-d H:i:s');
											$modelPilotTable->created_by = Yii::$app->user->identity->emp_name;
											if ( $modelPilotTable->validate() ) {
												$modelPilotTable->save();
											}
											else{
												//echo "<pre>";print_r($modelPilotTable->getErrors());exit;
											}	
											
										}
									}
								}else{
								    //echo "<pre>";print_r($serviceModel->getErrors());exit;
								}
				}
				

				$transaction->commit();
				Yii::$app->getSession()->setFlash('success'
				,[
					'title' => 'Employee',
					'text' => 'Employee Created Successfully',
					'type' => 'success',
					'timer' => 3000,
					'showConfirmButton' => false
				 ]);
				 if(@$_POST['pilotrender']){
					return $this->redirect('pilot');
				 }else{
					return $this->redirect('employeelist');
				 }
				
			}
			else
			{
				//echo "<pre>";print_r($model->getErrors());exit;
			}
		}
	}
	catch(Exception $e) {
        Yii::trace('======error====='.json_encode($e->getErrors()));
        $transaction->rollback();
    }
			$empRole = EmployeeRole::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
			$empRoleIdNameArr = array_column($empRole,'role_name','ID'); 
		
			$categorytypes = Sections::find()->where(['merchant_id' => Yii::$app->user->identity->merchant_id])
			->asArray()->All();

// 			echo "<pre>";
// 			print_r($categorytypes);exit;
		
		return $this->render('employelist',['model'=>$model,'empModel'=>$empModel,'empRoleIdNameArr'=>$empRoleIdNameArr
		,'categorytypes'=>$categorytypes,'id' => $merchantId]);
	}
	public function actionEditemployeepopup()
	{
		extract($_POST);
		$empModel = MerchantEmployee::findOne($id);
		$serviceBoy =  Serviceboy::find()
		->where(['employee_id'=>$empModel['ID']])
		->asArray()->One();
		$pilotTableId = [];
		if(!empty($serviceBoy)){
			$pilotTable = PilotTable::find()->where(['serviceboy_id' => $serviceBoy['ID']])->asArray()->All();
			$pilotTableId = ArrayHelper::getColumn($pilotTable,  'section_id');
						
		}
		$categorytypes = Sections::find()->where(['merchant_id' => $empModel['merchant_id']])
			->asArray()->All();

		return $this->renderAjax('editemployeepopup', ['model' => $empModel, 'id'=>$id
		, 'categorytypes' => $categorytypes,'pilotTableId' => $pilotTableId]);		
    
	}
	public function actionUpdateemployee()
	{
		$model = new MerchantEmployee;
		$model->scenario = 'updateemail';
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$employeeArr = Yii::$app->request->post('MerchantEmployee');
		$employeeUpdate = MerchantEmployee::findOne($_POST['MerchantEmployee']['ID']);
		$currentPassword = $employeeUpdate->emp_password;
		$employeeUpdate->attributes = \Yii::$app->request->post('MerchantEmployee');
		$emproleDet = EmployeeRole::findOne($employeeArr['emp_role']);		
		
		if (password_verify($currentPassword, $employeeUpdate['emp_password'])) {
                $employeeUpdate->emp_password = $currentPassword;
        } else {
             $hashPassword = password_hash($employeeArr['emp_password'], PASSWORD_DEFAULT);

             $employeeUpdate->emp_password = $hashPassword;

			if($emproleDet['role_name'] == 'PILOT')
			{
			$sqlUPdate = 'update serviceboy set password = \''.$hashPassword.'\' where mobile = \''.$employeeUpdate['emp_phone'].'\' ';
			$resUpdate = Yii::$app->db->createCommand($sqlUPdate)->execute();	
			}
        }
		
	    if($emproleDet['role_name'] == 'PILOT')
			{
			$sqlUPdate = 'update serviceboy set name = \''.$employeeArr['emp_name'].'\'
			,email = \''.$employeeArr['emp_email'].'\',mobile =  \''.$employeeArr['emp_phone'].'\' 
			where mobile = \''.$employeeUpdate['merchant_id'].'\' and merchant_id = \''.$employeeUpdate['merchant_id'].'\'';
			$resUpdate = Yii::$app->db->createCommand($sqlUPdate)->execute();
			$serviceBoy =  Serviceboy::find()
			->where(['employee_id' => $_POST['MerchantEmployee']['ID']])
			->asArray()->One();
			if(!empty($serviceBoy)){
				$pilotTable = PilotTable::find()->where(['serviceboy_id' => $serviceBoy['ID']])->asArray()->All();
				$pilotTableId = ArrayHelper::getColumn($pilotTable,  'section_id');
				
				$sectionGroup = $_POST['sectiongroup'];

				if(!empty($sectionGroup))
				{
					$sqlPilotTable = 'delete from pilot_table where serviceboy_id = \''.$serviceBoy['ID'].'\'';
					$resDelPilotTable = Yii::$app->db->createCommand($sqlPilotTable)->execute();
					
					for($i=0; $i < count($_POST['sectiongroup']); $i++ ) {
						$modelPilotTable = new PilotTable;
						$modelPilotTable->merchant_id = $employeeUpdate['merchant_id'];
						$modelPilotTable->section_id = $_POST['sectiongroup'][$i];
						$modelPilotTable->serviceboy_id  = $serviceBoy['ID'];
						$modelPilotTable->created_on = date('Y-m-d H:i:s');
						$modelPilotTable->created_by = Yii::$app->user->identity->emp_name;
						if ( $modelPilotTable->validate() ) {
							$modelPilotTable->save();
						}
						else{
							//echo "<pre>";print_r($modelPilotTable->getErrors());exit;
						}
					}	
				}

			}


			
		}

	

		$employeeUpdate->mod_date = date('Y-m-d H:i:s');
		
		if($employeeUpdate->validate()){
			$employeeUpdate->save();
			Yii::$app->getSession()->setFlash('success', [
        'title' => 'Employee',
		'text' => 'Employee Details Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		}else{
			echo "<pre>";print_r($employeeUpdate->getErrors());exit;
		}
			return $this->redirect('employeelist');
	
	}
	public function actionUpdatevendorpopup()
	{
		extract($_POST);
		$empModel = MerchantVendor::findOne($id);
		return $this->renderAjax('editvendorpopup', ['model' => $empModel,'id'=>$id]);		
    
	}
	public function actionUpdatevendor()
	{
		$model = new MerchantVendor;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$employeeArr = Yii::$app->request->post('MerchantVendor');
		$employeeUpdate = MerchantVendor::findOne($_POST['MerchantVendor']['ID']);
		$employeeUpdate->attributes = \Yii::$app->request->post('MerchantVendor');
		if($employeeUpdate->validate()){
			$employeeUpdate->save();
			Yii::$app->getSession()->setFlash('success', [
        'title' => 'Vendor',
		'text' => 'Vendor Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		}else{
			echo "<pre>";print_r($employeeUpdate->getErrors());exit;
		}
			return $this->redirect('vendorlist');
	
	}
    public function actionAddrole()
	{
		$model = new EmployeeRole;
		$merchantId = (string)Yii::$app->user->identity->merchant_id;
		$roleModel = EmployeeRole::find()
					->where(['merchant_id' => $merchantId])
					->orderBy(['ID'=>SORT_DESC])
					->asArray()->all();
		if ($model->load(Yii::$app->request->post()) ) {
			$model->merchant_id = (string)Yii::$app->user->identity->merchant_id;
			$model->role_status = '1';
			$model->reg_date = date('Y-m-d H:i:s');
			$model->created_by = Yii::$app->user->identity->emp_name;
			if ( $model->validate() ) {
				$model->save();
				Yii::$app->getSession()->setFlash('success'
				,[
					'title' => 'Role',
					'text' => 'Role Created Successfully',
					'type' => 'success',
					'timer' => 3000,
					'showConfirmButton' => false
				 ]);
				return $this->redirect('addrole');
			}
			else
			{
				echo "<pre>";print_r($model->getErrors());exit;
			}
		}
		return $this->render('addrole',['model'=>$model,'roleModel'=>$roleModel]);
	}
    public function actionEditrolepopup()
	{
		extract($_POST);
		$roleModel = EmployeeRole::findOne($id);
		return $this->renderAjax('editrole', ['model' => $roleModel,'id'=>$id]);		
    
	}
	public function actionUpdaterole()
	{
		$model = new EmployeeRole;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$roleArr = Yii::$app->request->post('EmployeeRole');
		$roleUpdate = EmployeeRole::findOne($_POST['EmployeeRole']['ID']);
		
		$roleUpdate->attributes = \Yii::$app->request->post('EmployeeRole');
		
		if($roleUpdate->validate()){
			$roleUpdate->save();
			Yii::$app->getSession()->setFlash('success', [
        'title' => 'Role',
		'text' => 'Role Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		}else{
			echo "<pre>";print_r($roleUpdate->getErrors());exit;
		}
			return $this->redirect('addrole');
	
	}
    public function actionEmpattendancelist()
	{
		$sql = 'select distinct created_on  from employee_attendance order by created_on desc';
		$res = Yii::$app->db->createCommand($sql)->queryAll();
		$attendedDate = array_values(array_column($res,'created_on'));
		return $this->render('attenddates',['attendedDate'=>$attendedDate]);			
	}
	public function actionAttendentview()
	{
		$merchantId = (string)Yii::$app->user->identity->merchant_id;
		$empModel = MerchantEmployee::find()
		->where(['merchant_id' => $merchantId])
		->andWhere(['<>','emp_role',0])
		->orderBy(['ID'=>SORT_DESC])
		->asArray()->all();

		$empRole = EmployeeRole::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])
		->andWhere(['<>','role_name','OWNER'])
		->asArray()->all();

		$empRoleIdNameArr = array_column($empRole,'role_name','ID');
		
		$res = EmployeeAttendance::find()->where(['created_on'=>date('Y-m-d')])->asArray()->All(); 
		
		return $this->render('empattendance',['empModel'=>$empModel,'empRoleIdNameArr'=>$empRoleIdNameArr,'res'=>$res]);					
	}
    public function actionSaveempattendance()
	{
		extract($_POST);
		if(!empty($ckcEmp))
	    {
			for($i=0;$i<count($ckcEmp);$i++)
			{
				$data[] = [(string)Yii::$app->user->identity->merchant_id,$ckcEmp[$i],'1',date('Y-m-d'),date('Y-m-d H:i:s A'),date('Y-m-d H:i:s A'),(string)Yii::$app->user->identity->emp_name];
			}
			Yii::$app->db
			->createCommand()
			->batchInsert('employee_attendance', ['merchant_id','employee_id','attendent_status','created_on', 'reg_date','mod_date','created_by'],$data)
			->execute();	
			
	    }
	  Yii::$app->getSession()->setFlash('success', [
        'title' => 'Attendance',
		'text' => 'Attendance Taken Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
	return $this->redirect('empattendancelist');
	}
    public function actionEditattendance()
	{
		extract($_POST);
		$sql = 'select emp_name,emp_role,emp_designation,attendent_status
		,me.merchant_id,me.ID,emp_id from merchant_employee me left join 
		employee_attendance ea on me.ID = ea.employee_id and    created_on =\''.$attend_date.'\' 
		where me.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and me.emp_role != \'0\'';
		$res = Yii::$app->db->createCommand($sql)->queryAll();
		
		
		$empRole = EmployeeRole::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])
		->andWhere(['<>','role_name','OWNER'])
		->asArray()->all();
		$empRoleIdNameArr = array_column($empRole,'role_name','ID');
		return $this->render('editempattendance',['empModel'=>$res,'empRoleIdNameArr'=>$empRoleIdNameArr,'attend_date'=>$attend_date]);
	}
	public function actionSaveeditattendance()
	{
		extract($_POST);
//print_r($_POST);exit;
		if(!empty($ckcEmp))
	    {
		$sqlDelete = 'delete from employee_attendance where created_on = \''.$edit_attend_date.'\' and merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'';
		$resDelete = Yii::$app->db->createCommand($sqlDelete)->execute();
			for($i=0;$i<count($ckcEmp);$i++)
			{
				$data[] = [(string)Yii::$app->user->identity->merchant_id,$ckcEmp[$i],'1',$edit_attend_date
				,date('Y-m-d H:i:s A'),date('Y-m-d H:i:s A'),(string)Yii::$app->user->identity->emp_name];
			}
			Yii::$app->db
			->createCommand()
			->batchInsert('employee_attendance', ['merchant_id','employee_id','attendent_status','created_on', 'reg_date','mod_date','created_by'],$data)
			->execute();	
			
	    }
	  Yii::$app->getSession()->setFlash('success', [
        'title' => 'Attendance',
		'text' => 'Attendance Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
	return $this->redirect('empattendancelist');
		
	}
	public function actionAssignpermission()
	{
		$sql = 'select * from employee_role where role_name != \'OWNER\' 
		and merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'';
		$res = Yii::$app->db->createCommand($sql)->queryAll();
		return $this->render('rolesview',['res'=>$res]);
	}
	public function actionAssignrolepermission()
	{
		extract($_POST);
		/*$sql = 'select process_name,mp.ID  ,permission_status  from merchant_permissions mp left join merchant_permission_role_map mprm 
		on mp.ID = mprm.permission_id and mprm.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
		and mprm.employee_id = \''.$id.'\'';
		$res = Yii::$app->db->createCommand($sql)->queryAll();
		return $this->render('assignrolepermission',['res'=>$res,'role_id'=>$id]);*/
				$sqlpermission = 'select distinct display_id display_id from  merchant_permissions mp left join merchant_permission_role_map mprm 
		on mp.ID = mprm.permission_id
		where mprm.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
		and mprm.employee_id = \''.$id.'\' and permission_status = \'1\'';
		$respermission = Yii::$app->db->createCommand($sqlpermission)->queryAll();
		$rolePermissionArray = array();
		$rolePermissionArray = (array_column($respermission,'display_id'));
		$sql = 'select * from merchant_permission_display where display_status = \'1\'';
		$res = Yii::$app->db->createCommand($sql)->queryAll();
		$sqlRoleName = 'select * from employee_role where role_status = \'1\'
		and ID = \''.$id.'\'';
		$resRoleName = Yii::$app->db->createCommand($sqlRoleName)->queryAll();
		
	return $this->render('permissionview',['res'=>$res,'role_id'=>$id,'rolePermissionArray'=>$rolePermissionArray
	,'resRoleName'=>$resRoleName]);
	
	}
	public function actionSaverolepermissions()
	{
		extract($_POST);
		$sqlPermissionCheck = 'select permission_status,mprm.ID from merchant_permissions mp 
		INNER JOIN merchant_permission_role_map mprm 
		on mp.ID = mprm.permission_id
		where mprm.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
		and mprm.employee_id = \''.$roleid.'\' 
		and display_id = \''.$main_pernission_id.'\'';
		$resPermissionCheck = Yii::$app->db->createCommand($sqlPermissionCheck)->queryAll();
		

		
		if(count($resPermissionCheck) == 0)
		{

		$resPermissionIds = MerchantPermissions::find()->select('ID')
		->where(['display_id'=>$main_pernission_id])
		->asArray()
		->All();
		
			for($i=0;$i<count($resPermissionIds);$i++)
			{
				$data[] = [(string)Yii::$app->user->identity->merchant_id,$roleid,$resPermissionIds[$i]['ID'],1];
			}
			Yii::$app->db
			->createCommand()
			->batchInsert('merchant_permission_role_map', ['merchant_id','employee_id','permission_id', 'permission_status'],$data)
			->execute();
		}
		else{

			if($resPermissionCheck[0]['permission_status'] == '1'){
				$permissionStatus = '0';
			}
			else{
				$permissionStatus = '1';
			}
			$idString = implode("','",(array_column($resPermissionCheck,'ID')));
			$sqlUpdate = 'update merchant_permission_role_map set permission_status = \''.$permissionStatus.'\' 
			where  merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
		and employee_id = \''.$roleid.'\'  AND ID IN ( \''.$idString.'\')';
			$resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute();
		}
		
		echo 1;
	}
	public function actionViewkds()
	{
		$sql = 'select o.tablename as tableid,name as tablename,product_id ,o.ID table_order_id ,o.order_id  
		,concat(hour(o.reg_date),\':\',LPAD(minute(o.reg_date), 2, \'0\')) order_time,count as orderCount 
		,fc.food_category,o.reg_date
		,case when food_type_name is not null then concat(title ,\' (\' , food_type_name , \')\') else title end  title_quantity
		,od.ID order_product_id,od.item_deliver_status
		from  orders o left join  tablename tb  on o.ID = tb.current_order_id 
		inner join order_products od on o.ID = od.order_id 
		inner join product p on p.ID = od.product_id
		inner join food_categeries fc on fc.ID = p.foodtype 
	    left join food_category_types fct on fct.ID =  p.food_category_quantity
		where -- (tb.current_order_id != \'0\' and tb.current_order_id is not null) and 
		orderprocess = \'1\' and o.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' order by food_category,title_quantity';
		$res = Yii::$app->db->createCommand($sql)->queryAll();
		$tableres = array_column($res,'tablename','tableid');
		

		$tableorderidres = array_column($res,'order_id','tableid');
		$tabletimeres = array_column($res,'order_time','tableid');
		
		asort($tabletimeres);
		$tableorderidres = array_column($res,'table_order_id','tableid');
		$resindex =  \yii\helpers\ArrayHelper::index($res, null, 'tableid');
		//$tableprodcount = array_count_values(array_column($res,'tableid'));

		return $this->renderPartial('viewkds',['tableres'=>$tableres,'tableorderidres'=>$tableorderidres
		,'tabletimeres'=>$tabletimeres,'resindex'=>$resindex,'tableorderidres'=>$tableorderidres
		,'res'=>$res]);
	}
	public function actionViewkdsone()
	{
		$sql = 'select o.tablename as tableid,name as tablename,product_id ,o.ID table_order_id ,o.order_id  
		,concat(hour(o.reg_date),\':\',LPAD(minute(o.reg_date), 2, \'0\')) order_time,count as orderCount 
		,fc.food_category,o.reg_date
		,case when food_type_name is not null then concat(title ,\' (\' , food_type_name , \')\') else title end  title_quantity
		,od.ID order_product_id,od.item_deliver_status
		from  orders o left join  tablename tb  on o.tablename = tb.ID 
		inner join order_products od on o.ID = od.order_id 
		inner join product p on p.ID = od.product_id
		inner join food_categeries fc on fc.ID = p.foodtype 
	    left join food_category_types fct on fct.ID =  p.food_category_quantity
		where  
		orderprocess = \'1\' and (o.preparedate is null or o.preparedate = "")   and o.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' order by food_category,title_quantity';
		$res = Yii::$app->db->createCommand($sql)->queryAll();
		$tableres = array_column($res,'tablename','table_order_id');

		$tabletimeres = array_column($res,'reg_date','table_order_id');
		
		asort($tabletimeres);
		$tableorderidres = array_column($res,'tableid','table_order_id');
		$resindex =  \yii\helpers\ArrayHelper::index($res, null, 'table_order_id');

		return $this->render('viewkds1',['tableres'=>$tableres,'tableorderidres'=>$tableorderidres
		,'tabletimeres'=>$tabletimeres,'resindex'=>$resindex,'tableorderidres'=>$tableorderidres
		,'res'=>$res]);
	}
  public function actionTesting()
  {
	$notificationdet = ['type' => 'NEW_ORDER','orderamount' => 200
	,'username' => 'Ravi','tablename' => 'TABLE 0'];
    $id = 'c4YeKBaFQ3OI5-PVp1lFZp:APA91bGbeD_4CJgNBt3bpDIpfx18DEflsbra0Hv8htpFn1J0VrAkWdoNn7PWL6Bg9VV6uKPFU37utVqasN04MDXWJuzQ5TTVo24U6kXcoTwV0fR8fjC8vrnLEWErAvc-qC4HoX3wMMBo';
	  \app\helpers\Utility::sendNewFCM($id,'Hi',"First Notification","",'6',null,'1',$notificationdet);

  }
    public function actionTableorderproductdeliver()
	{
		extract($_POST);
	
		$sqlUpdate = 'update order_products set item_deliver_status = \'DELIVER\' where ID = \''.$orderProductId.'\'';
		$resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute();
		
		return 1;
	}
    public function actionMerchantdashboard()
	{
		$date = date('Y-m-d');
		extract($_POST);
            $merchant_id = Yii::$app->user->identity->merchant_id;
		
		$sqlTotalCustomers = 'select user_id from orders 
		where merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'
		and TRIM(user_id) <> ""';
		$resTotalCustomers = Yii::$app->db->createCommand($sqlTotalCustomers)->queryAll();

		$totalCustomers = !empty($resTotalCustomers) ? 
		count(array_values(array_unique(array_column($resTotalCustomers,'user_id')))) : 0;

		$repeatCustomerMainArray =  array_count_values(array_column($resTotalCustomers,'user_id')); 
		$repeatedCusArr = [];
		foreach($repeatCustomerMainArray as $k => $v){
			if($v > 1){
				$repeatedCusArr[] =  $k;
			}
		}

		$repeatCustomers = !empty($repeatedCusArr) ? count($repeatedCusArr) : 0;

		$sqltablereservation = 'select ID from table_reservations where merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
		and date(reg_date) = \''.$date.'\'
		union
		select ID from table_reservations where merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
		and date(reg_date) != \''.$date.'\' and status = \'0\'';
		$restablereservation = Yii::$app->db->createCommand($sqltablereservation)->queryAll();

		$productCount = Product::find()->where(['merchant_id' => $merchant_id])
		->andWhere(['status' => '1'])
		->asArray()->count();


		$sqlOrderStatusCount =' select count(orderprocess) cnt,orderprocess from orders where date(reg_date) = \''.$date.'\' 
		and merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' group by orderprocess';
		$orderStatusCount = Yii::$app->db->createCOmmand($sqlOrderStatusCount)->queryAll();
		$ordStatusCount = array_column($orderStatusCount,'cnt','orderprocess');
		
		$selectPaidCOunt = ' select sum(case when paymenttype = \'cash\' 
		then totalamount else 0 end) paidByCash
		,sum(case when paymenttype != \'cash\' then totalamount else 0 end) paidOnline
		,sum(case when paidstatus = \'0\' then totalamount else 0 end) notPaid
		,sum(case when paidstatus = \'1\' then totalamount else 0 end) completePaid
        ,sum(case when orderprocess != \'4\' then 1 else 0 end) runningOrders
        ,sum(totalamount) totalamount
		from orders where date(reg_date) = \''.$date.'\' and orderprocess != \'3\'
		and merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' ';
		$resPaidCOunt = Yii::$app->db->createCOmmand($selectPaidCOunt)->queryOne();
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

SELECT sum(totalamount) sale_amount,month(reg_date) num_mon FROM orders where merchant_id= \''.Yii::$app->user->identity->merchant_id.'\' and date(reg_date) between \''.$yearStartDate.'\' and \''.date('Y-m-d').'\'
group by month(reg_date)
        ) as B on A.mon_num = B.num_mon';

$resChart1 = Yii::$app->db->createCommand($sqlChart)->queryAll();
$saleselect = isset($saleselect) ? $saleselect : '4';
$saleChartArr = ['saleselect' => $saleselect,'date'=>$date,'date2'=>$date];
	$str = $this->saleChart($saleChartArr);

         $sqlpilot = 'SELECT sb.name,count(o.ID) total_served_orders,
                         sum(case when o.orderprocess = \'4\' then 1 else 0 end) completed_orders,
                         sum(case when o.orderprocess = \'3\' then 1 else 0 end) rejected_order,sum(o.totalamount) totalamount 
                         FROM orders o inner join serviceboy sb on o.serviceboy_id = sb.ID 
                         where date(o.reg_date)
                         and o.merchant_id = \''.$merchant_id.'\' group by sb.name LIMIT 5';
                $pilotdet = Yii::$app->db->createCommand($sqlpilot)->queryAll();

	 $sqlTableDetails = 'select s.section_name as label
	,sum(case when orderprocess = \'4\' then totalamount else 0 end) completedamount
	,sum(case when (orderprocess != \'4\') then totalamount else 0
	end) runningamount
	from tablename tn
	inner join sections s on s.ID = tn.section_id
	left join orders o on tn.ID = o.tablename
	where tn.merchant_id = \''.$merchant_id.'\'
	and date(o.reg_date) = \''.$date.'\' and o.orderprocess != \'3\'
	group by s.section_name';
			   $resTableDetails = Yii::$app->db->createCommand($sqlTableDetails)->queryAll();
			 
$completedPie = [];
$runningPie = [];
 for($p=0;$p<count($resTableDetails);$p++)
{
	if($resTableDetails[$p]['completedamount'] > 0){
		$completedPie[$p]['label'] = $resTableDetails[$p]['label'];
		$completedPie[$p]['value'] = $resTableDetails[$p]['completedamount']; 
	}
	 if($resTableDetails[$p]['runningamount'] > 0 ){
		$runningPie[$p]['label'] = $resTableDetails[$p]['label'];
		$runningPie[$p]['value'] = $resTableDetails[$p]['runningamount']; 
	}
}

//echo "<pre>";print_r($resTableDetails);print_r($runningPie);exit;
		return $this->render('dashboard',['ordStatusCount'=>$ordStatusCount
		,'str'=>$str,'resPaidCOunt'=>$resPaidCOunt,'pilotdet'=>$pilotdet
		,'totalCustomers' => $totalCustomers, 'repeatCustomers' => $repeatCustomers
		,'restablereservation' => $restablereservation,'productCount' => $productCount
		,'runningPie' => array_values($runningPie),'completedPie' => array_values($completedPie)
		]);
	}
	
	public function actionAjaxsalechart(){
		$yearStartDate = date('Y').'-01-01';

		if($_POST['saleselect'] == 3){
			$date1 = $_POST['date2'];
		}

		if ($_POST['saleselect'] == 2) {
			$date1 = $yearStartDate;
			$date2 = date('Y-m-d');
		}

		if($_POST['saleselect'] == 4){
			$date1 = $_POST['date1'];
			$date2 = $_POST['date2'];
		}


		$saleChartArr = ['saleselect' => $_POST['saleselect'],'date'=> $date1 ,'date2' => $date2 ?? date('Y-m-d')];
		return $this->saleChart($saleChartArr);
	}

	public function saleChart($arr = '')
	{
		$yearStartDate = date('Y').'-01-01';
		$arr['date2'] = isset($arr['date2']) ? $arr['date2'] : date('Y-m-d');
		$sqlHour = 'select sum(totalamount) sale_amount ';
		if($arr['saleselect'] == '1' || $arr['saleselect'] == '3' ){
			$sqlHour .= ' ,hour(reg_date) col_name '; 
		}
		else if($arr['saleselect'] == '2'){
			$sqlHour .= ' ,month(reg_date) col_name ';
		}
		else if($arr['saleselect'] == '4'){
			$sqlHour .= ' ,date(reg_date) col_name ';
		}
		$sqlHour .= '   from orders where 
		merchant_id= \''.Yii::$app->user->identity->merchant_id.'\' ';
		if($arr['saleselect'] == '1' || $arr['saleselect'] == '3'){
		$sqlHour .=' and date(reg_date) = \''.$arr['date'].'\'  ';
		}
		else if($arr['saleselect'] == '2'){
			$sqlHour .=' and date(reg_date) between \''.$yearStartDate.'\' and \''.$arr['date'].'\' ';
		}
		else if($arr['saleselect'] == '4'){
			$sqlHour .=' and date(reg_date) between \''.$arr['date'].'\' and \''.$arr['date2'].'\' ';
		}

		if($arr['saleselect'] == '1' || $arr['saleselect'] == '3'){
			$sqlHour .=' group by hour(reg_date) order by hour(reg_date) ';
		}	
		else if($arr['saleselect'] == '2'){
			$sqlHour .= ' group by month(reg_date)  order by month(reg_date)';
		}
		else if($arr['saleselect'] == '4'){
			$sqlHour .= ' group by date(reg_date)  order by date(reg_date)';
		}
		$resChart = Yii::$app->db->createCommand($sqlHour)->queryAll();
			if($arr['saleselect'] == '1' || $arr['saleselect'] == '3'){
				$xaxis = 'Hours';
		
			}
			else if($arr['saleselect'] == '2'){
				$xaxis = 'Months';
		
			}
			else if($arr['saleselect'] == '4'){
				$xaxis = 'Date';
		
			}
			$str =	'<chart  xaxisname="'.$xaxis.'" yaxisname="Earnings" theme="fusion">';
		
			for($i=0;$i<count($resChart);$i++){
				if($arr['saleselect'] == '1' || $arr['saleselect'] == '3'){
						$col_name = Utility::hourRange($resChart[$i]['col_name']);	
				}
				else if($arr['saleselect'] == '2'){
					$col_name = Utility::monthRange($resChart[$i]['col_name']);	
				}
				else {
					$col_name = ($resChart[$i]['col_name']);	

				}
			$str.='<set label="'.$col_name.'" value="'.$resChart[$i]['sale_amount'].'"  tooltext="'.$col_name.' Earnings is ₹ '.round($resChart[$i]['sale_amount'],2).' "/>';	
			}
		$str.='</chart>';
		return $str;
	}
	
	public function actionTranscationdashboard()
	{
		extract($_POST);
		$sdate = $sdate ?? date('Y-m-d');
		$edate = $edate ?? date('Y-m-d');
		
		$sqlProducts = 'SELECT count(ID) productCount from product where   merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'';
		$resProductCount = Yii::$app->db->createCommand($sqlProducts)->queryOne();
		$productCount = $resProductCount['productCount'] ?? 0;
		
		$sqlOrders = 'SELECT count(ID) orderCount from orders where   merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'
		and date(reg_date) between \''.$sdate.'\' and \''.$edate.'\'';
		$resOrderCount = Yii::$app->db->createCommand($sqlOrders)->queryOne();
		$orderCount = $resOrderCount['orderCount'] ?? 0;
		
		$sqlEmployee = 'SELECT count(ID) empCount from merchant_employee where   merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'';
		$resEmployeeCount = Yii::$app->db->createCommand($sqlEmployee)->queryOne();
		$empCount = $resEmployeeCount['empCount'] ?? 0;
		
		
		$sqlOrderStatusCount =' select count(orderprocess) cnt,orderprocess from orders where date(reg_date) between \''.$sdate.'\' and \''.$edate.'\' 
		and merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' group by orderprocess';
		$orderStatusCount = Yii::$app->db->createCOmmand($sqlOrderStatusCount)->queryAll();
		$ordStatusCount = array_column($orderStatusCount,'cnt','orderprocess');
		
		return $this->render('transactiondash',['productCount'=>$productCount,'orderCount'=>$orderCount
		,'empCount'=>$empCount,'ordStatusCount'=>$ordStatusCount,'sdate'=>$sdate,'edate'=>$edate]);
	}	
	public function actionTestlog()
	{
		echo "asda";
		$name = 'ravi';
		 Yii::debug('fsdfs==='.$name);
		 \Yii::info("hi there+$name", 'mycategory');
	exit;
	}
	public function actionPermissionsview(){
		return $this->render('permissionview');
	}
	public function actionEmployeenewattendance()
	{
		return $this->render('employeenewattendance');
	}
	public function actionAccessforbidden(){
		return $this->render('accessforbidden');
	}
	public function actionErrorpage(){
		return $this->render('errorpage');
	}
	public function actionCheckneworder()
	{
		$sqlTableDetails = 'select tn.*,o.orderprocess
		 from tablename tn left join orders o on tn.current_order_id = o.id 
		where tn.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and orderprocess = \'0\' ';
		$tableDetails = Yii::$app->db->createCommand($sqlTableDetails)->queryAll();
		
			 return  stripslashes(json_encode($tableDetails, JSON_UNESCAPED_SLASHES));
		
	}
	public function actionDeleteproduct(){
		extract($_POST);
		$Productdetails = Product::findOne($id);
		$sqlpricesdelete = 'delete from section_item_price_list where item_id = \''.$id.'\' and merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'';
		$respricesdelete = Yii::$app->db->createCommand($sqlpricesdelete)->execute();
		$Productdetails->delete();
	}
	public function actionDeletetable(){
		extract($_POST);
		$Tablenamedetails = Tablename::findOne($id);
		$Tablenamedetails->delete();
	}
	public function actionDeletecoupon(){
		extract($_POST);
		$Coupondetails = \app\models\Coupon::findOne($id);
		$Coupondetails->delete();
	}
	public function actionDeleteemployee(){
		extract($_POST);
		$MerchantEmployeedetails = MerchantEmployee::findOne($id);
		$MerchantEmployeedetails->emp_status = '17';
		$MerchantEmployeedetails->save();
	}
	public function actionDeletevendor(){
		extract($_POST);
		$MerchantEmployeedetails = MerchantVendor::findOne($id);
		$MerchantEmployeedetails->status = '17';
		$MerchantEmployeedetails->save();
	}
	public function actionTablereservation(){

		$restablereservation =TableReservations::find()
		->where(['merchant_id'=>Yii::$app->user->identity->merchant_id,'status'=>'0'])
		->asArray()->All();
		return $this->render('tablereservation',['restablereservation'=>$restablereservation]);
	}
	public function actionReservationhistory(){
		extract($_POST);
				$sdate = $_POST['sdate'] ?? date('Y-m-d'); 
		$edate = $_POST['edate'] ?? date('Y-m-d');
		$reservationstatus = $reservationstatus ?? '';
		$sqltablereservation = 'select * from table_reservations where merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
		and date(bookdate) between \''.$sdate.'\' and \''.$edate.'\' and status = coalesce(\''.$reservationstatus.'\',status)';	
		$restablereservation = Yii::$app->db->createCommand($sqltablereservation)->queryAll();
		return $this->render('reservationhistory',['restablereservation'=>$restablereservation,'sdate'=>$sdate,'edate'=>$edate,'reservationstatus'=>$reservationstatus]);
	}
	public function actionReservationstatus(){
	extract($_POST);

	$tabledatata = TableReservations::findOne($checked);
	if(!empty($tabledatata['user_id'])){
			$pushid = Utility::user_details($tabledatata['user_id'],'push_id');
			if($pushid){ 
				 switch($changestatus){
					 case '1':
					 $title = "Reservation Approved.";
					 $message = "Hi ".Utility::user_details($tabledatata['user_id'],'name').", Your table reservation has been approved.";
					 $image = "";
					$result = Utility::sendFCM($pushid,$title,$message,$image); 
					 break;
					 case '2':
					 $title = "Reservation Declined.";
					 $message = "Hi ".Utility::user_details($tabledatata['user_id'],'name').", Sorry your table reservation has been Declined.";
					 $image = "";
					$result = 	Utility::sendFCM($pushid,$title,$message,$image); 
					 break;
				 }
			}
		}
		$sqlUpdate = 'update table_reservations set status = \''.$changestatus.'\' where ID = \''.$checked.'\'';
		$resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute();
	}
    public function actionCurrentorders()
	{
				$sqlTableDetails = 'select o.ID,tn.ID tableId,tn.name,o.orderprocess,o.reg_date
		,o.totalamount,current_order_id,o.ordertype,o.serviceboy_id from tablename tn inner join orders o on tn.ID = o.tablename 
		where tn.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and  orderprocess in(\'1\',\'2\') ';
		$tableDetails = Yii::$app->db->createCommand($sqlTableDetails)->queryAll();
				
		$resServiceBoy = Serviceboy::find()
		->where(['merchant_id'=>Yii::$app->user->identity->merchant_id,'loginstatus'=>'1'])
		->asArray()->All();
		$sqlParcelDetails = 'select * from  orders o 
		where o.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
		and tablename like \'%PARCEL%\'and date(reg_date) = \''.date('Y-m-d').'\' and orderprocess not in (\'3\',\'4\')';
		$parcelDetails = Yii::$app->db->createCommand($sqlParcelDetails)->queryAll();
	//	print_r($tableDetails);exit;
                $sqlNewOrdersDetails = 'select o.ID,tn.ID tableId,tn.name,o.orderprocess,o.reg_date
		,o.totalamount,current_order_id,o.ordertype,o.serviceboy_id 
                from tablename tn left join orders o on tn.current_order_id = o.id 
		where tn.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and  orderprocess=\'0\'';
		$newOrdersDetails = Yii::$app->db->createCommand($sqlNewOrdersDetails)->queryAll();
                
		return $this->render('currentorder',['tableDetails'=>$tableDetails,'resServiceBoy'=>$resServiceBoy,'parcelDetails'=>$parcelDetails,'newOrdersDetails'=>$newOrdersDetails]);
	}
	public function actionParcels()
	{
		
		$sqlTableDetails = 'select o.ID,tn.ID tableId,tn.name,o.orderprocess,o.reg_date
		,o.totalamount,o.ordertype,o.serviceboy_id from tablename tn left join orders o on tn.current_order_id = o.id 
		where tn.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and  orderprocess is not null ';
		$tableDetails = Yii::$app->db->createCommand($sqlTableDetails)->queryAll();
		
				$sqlParcelDetails = 'select * from  orders o 
		where o.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
		and tablename like \'%PARCEL%\'and date(reg_date) = \''.date('Y-m-d').'\' and orderprocess not in (\'3\',\'4\')';
		$parcelDetails = Yii::$app->db->createCommand($sqlParcelDetails)->queryAll();
	//				echo "<pre>";	print_r($tableDetails);exit;
                $sqlDinein = 'select o.ID,tn.ID tableId,tn.name,o.orderprocess,o.reg_date
		,o.totalamount,current_order_id,o.ordertype,o.serviceboy_id from tablename tn left join orders o on tn.current_order_id = o.id 
		where tn.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and  orderprocess in(\'1\',\'2\') ';
		$dineIn = Yii::$app->db->createCommand($sqlDinein)->queryAll();
                
                $sqlNewOrders = 'select o.ID,tn.ID tableId,tn.name,o.orderprocess,o.reg_date
		,o.totalamount,current_order_id,o.ordertype,o.serviceboy_id 
                from tablename tn left join orders o on tn.current_order_id = o.id 
		where tn.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and  orderprocess=\'0\'';
		$newOrders = Yii::$app->db->createCommand($sqlNewOrders)->queryAll();
		return $this->render('currentparcels',['parcelDetails'=>$parcelDetails,'tableDetails'=>$tableDetails,'dineIn'=>$dineIn,'newOrders'=>$newOrders]);
	}
	public function actionChkpwd()
	{
	    extract($_POST);
	    		$details = MerchantEmployee::findOne(Yii::$app->user->identity->ID);
	    		$hashPwd = password_hash(trim($pwd),PASSWORD_DEFAULT);
	    		
	    	
	    		
	    		if (password_verify($pwd, $details['emp_password'])) {
             return true;   
        } else {
             	return false;
        }
	}
	
	public function actionUpdateorderstatus()
	{
	    extract($_POST);
		$orderDet = Orders::findOne($id);
		$tableUpdate = Tablename::findOne($orderDet['tablename']);
	    $connection = \Yii::$app->db;	
	    $transaction = $connection->beginTransaction();
    	try {
			if(!empty($tableUpdate)) {
				$table_status = null;
				$current_order_id = 0;
				$tableUpdate->table_status = $table_status;
				$tableUpdate->current_order_id = $current_order_id;
				$tableUpdate->save();
			}
			$orderDet->orderprocess = '4';
			$orderDet->paidstatus = '1';
			$orderDet->pending_amount = 0.00;
			$orderDet->paid_amount = (float)$orderDet->totalamount;
			$orderDet->paymenttype = $orderpaymethod;
			$orderDet->ordercompany = $orderorigin;
			$orderDet->closed_by = Yii::$app->user->identity->merchant_id;
			$orderDet->save();

			if(!empty($orderDet['serviceboy_id'])){
				
				$notificaitonarary = array();
				$notificaitonarary['merchant_id'] = $orderDet['merchant_id'];
				$notificaitonarary['serviceboy_id'] = $orderDet['serviceboy_id'];
				$notificaitonarary['order_id'] = (string)$id;
				$notificaitonarary['title'] = 'Complete Order';
				$notificaitonarary['message'] = 'Order '.$orderDet['order_id'].' on '.$tableUpdate['name'].'-'.$tableUpdate->section['section_name'].' is Completed';
				$notificaitonarary['ordertype'] = 'complete';
				$notificaitonarary['seen'] = '0';
								
				$serviceBoyNotiModel = new  ServiceboyNotifications;
				$serviceBoyNotiModel->attributes = $notificaitonarary;
				$serviceBoyNotiModel->reg_date = date('Y-m-d H:i:s');
				$serviceBoyNotiModel->mod_date = date('Y-m-d H:i:s');
				$serviceBoyNotiModel->save();


				$notificaitonarary = array();
				$notificaitonarary['merchant_id'] = $orderDet['merchant_id'];
				$notificaitonarary['serviceboy_id'] = $orderDet['serviceboy_id'];
				$notificaitonarary['order_id'] = (string)$id;
				$notificaitonarary['title'] = 'Paid Order';
				$notificaitonarary['message'] =  \app\helpers\MyConst::PAYMENT_METHODS[$orderpaymethod].' Payment of Rs. '.$orderDet['totalamount'].'  
				received on Order '.$orderDet['order_id'].' of '.$tableUpdate['name'].'-'.$tableUpdate->section['section_name'];
				$notificaitonarary['ordertype'] = 'Payment';
				$notificaitonarary['seen'] = '0';
								
				$serviceBoyNotiModel = new  ServiceboyNotifications;
				$serviceBoyNotiModel->attributes = $notificaitonarary;
				$serviceBoyNotiModel->reg_date = date('Y-m-d H:i:s');
				$serviceBoyNotiModel->mod_date = date('Y-m-d H:i:s');
				$serviceBoyNotiModel->save();



			}
			

			$transaction->commit();
			$arr = ['order_id' => $orderDet['ID']];
			//$stockded = Yii::$app->merchant->deductstockfrominventory($arr);
	    	return json_encode(['table_id'=>$tableUpdate['ID'] ?? 'PARCEL','table_name'=>$tableUpdate['name'] ?? 'PARCEL']);
		} catch(Exception $e) {
			Yii::trace('======error====='.json_encode($e->getErrors()));
			$transaction->rollback();
		}
	}
	public function actionClosetableorder(){
	    extract($_POST);
	    if(isset($_POST['STATUS'])){
	        if($_POST['STATUS'] == 'TXN_SUCCESS'){
	            $orderDet = Orders::findOne($_POST['ORDERID']);
	            $tableUpdate = Tablename::findOne($orderDet['tablename']);
	            $merchant_pay_types_det = \app\models\MerchantPaytypes::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id,'paymenttype' => 2])->One();
	    if(!empty($tableUpdate))
		{
			$table_status = null;
			$current_order_id = 0;
			$tableUpdate->table_status = $table_status;
			$tableUpdate->current_order_id = $current_order_id;
			$tableUpdate->save();
		}
		$orderDet->orderprocess = '4';
		$orderDet->paidstatus = '1';
		$orderDet->paymenttype = $merchant_pay_types_det['ID'];
		//$orderDet->ordercompany = $orderorigin;
		$orderDet->closed_by = Yii::$app->user->identity->merchant_id;
		$orderDet->save();
				return $this->redirect(['merchant/newpos','tableid'=>$tableUpdate['ID'],'tableName'=>$tableUpdate['name'],'current_order_id'=>0  ]);
	        }
	    }
	}
		public function encrypt($string){

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
public function actionQrdownload()
	{
		extract($_POST);
		
		$res = Tablename::findOne($tableid) ;
		if(empty($res['qr_path'])){
			$qrtext = $this->encrypt($tableid,$userid);
			$sqlQrUpdate = 'update tablename set qr_path = \''.$qrtext.'\' where merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'
			and ID = \''.$tableid.'\'';
			$resQrUpdate = Yii::$app->db->createCommand($sqlQrUpdate)->execute();
			 $centralurl1 = SITE_URL.'qrdownload.php';

$alloclineid_json = json_encode(['table'=>$tableid,'userid'=>$userid,'qrtext'=>$qrtext,'tablename'=>$tablename]);

/*$params = array('http' => array(

       'method' => 'POST',

       'header' => 'Content-type: json',

       'content' =>$alloclineid_json,

        ));

        $ctx = stream_context_create($params);

        $fp = fopen($centralurl1, 'rb', false, $ctx);

        $resp= trim(stream_get_contents($fp));
*/

$curl = curl_init();
	
	
	$authentication_key = '318274A8Ym3ky6q5e4661fbP1';
  
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $centralurl1,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $alloclineid_json,
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
			
			return "Qr Generated Successfully! Please Click Again To Download ! ";
		}
		
		
	}
	public function actionApitest(){
					 $centralurl1 = SITE_URL.'qrdownload.php';

		$alloclineid_json = json_encode(['table'=>'7','userid'=>'8','qrtext'=>'Yk1RL09CalFnK29LeXFnV0Y1QjFvdz09']);

$params = array('http' => array(

       'method' => 'POST',

       'header' => 'Content-type: json',

       'content' =>$alloclineid_json,

        ));

        $ctx = stream_context_create($params);

        $fp = fopen($centralurl1, 'rb', false, $ctx);

        $resp= trim(stream_get_contents($fp));
echo $resp;

	}
	public function actionTestqr(){
		extract($_POST);
		
		return $this->render('testqr',['qrlogo'=>$qrlogo,'qrpath'=>$qrpath,'tablename'=>$tablename]);
	}
	public function actionTestprint(){
	    $merchantid = '8';
	    											$sqlserviceboyarray = "select * from serviceboy where merchant_id = '".$merchantid."' and loginstatus = '1' 
	    											and push_id <> '' order by ID desc";
											$serviceboyarray = Yii::$app->db->createCommand($sqlserviceboyarray)->queryAll();
											if(!empty($serviceboyarray)){
												$stitle = 'New order.';
												$smessage = 'New order received please check the app for information.';
												$simage = '';
												foreach($serviceboyarray as $serviceboy){ 
													\app\helpers\Utility::sendFCM($serviceboy['push_id'],$stitle,$smessage,$simage,null,null,'576'); 
												}
											}
	    
	}
	public function actionEnc()
	{
	    $val['enckey'] = 'K2ZLUG96RDJEbTI2ZmdTU2drVTZvQT09';
	 						 $enckey = \app\helpers\Utility::decrypt(trim($val['enckey']));   
	    								$merchantexplode = explode(',',$enckey);
								$merchantid = $merchantexplode[0];
						echo		$tableid = $merchantexplode[1];
	}
	public function actionQrcode(){
	    echo $_GET['enckey'];
	}
	public function actionMenuqr()
	{
		$merchant_id = Yii::$app->user->identity->merchant_id;
        $merchantdetails = Merchant::findOne($merchant_id);
        $food_sections = Sections::find()->where(['merchant_id' => $merchant_id])->asArray()->all();
	    return $this->render('menuqr',['merchantdetails' => $merchantdetails,'food_sections' => $food_sections]);
	}
	public function actionSections()
	{
		$model = new Sections;
		if ($model->load(Yii::$app->request->post()) ) {
		    $model->merchant_id = (string)Yii::$app->user->identity->merchant_id;
		    $model->section_id= Utility::get_uniqueid('sections','SS');
		
		if($model->validate()){
			$model->save();
				Yii::$app->getSession()->setFlash('success', [
        'title' => 'Section',
		'text' => 'Section Created Successfully',
        'type' => 'success',
        //'timer' => 3000,
        'showConfirmButton' => false
	]);
	
			return $this->redirect('sections');
		}
		else
		{
			echo "<pre>";print_r($model->getErrors());exit;
		}
	}
		
	$categorytypes = Sections::find()
	->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])
	->asArray()->All();
// 		echo '<pre>';
// 	print_r($categorytypes);exit;
		return $this->render('sections',['model'=>$model,'categorytypes'=>$categorytypes]);
	}
	public function actionEditsectionpopup()
	{
		extract($_POST);
		$sectionModel = Sections::findOne($id);
		return $this->renderAjax('editsectionpopup', ['model' => $sectionModel,'id'=>$id]);		
	}
	public function actionEditsection()
	{
		$model = new Sections;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$sectionNameArr = Yii::$app->request->post('Sections');
		$sectionNameUpdate = Sections::findOne($_POST['Sections']['ID']);
		
		$sectionNameUpdate->attributes = \Yii::$app->request->post('Sections');
		
		if($sectionNameUpdate->validate()){
			$sectionNameUpdate->save();
			Yii::$app->getSession()->setFlash('success', [
        'title' => 'Section',
		'text' => 'Section Edited Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		}
			return $this->redirect('sections');
	
	}
	public function actionDeletesection(){
		extract($_POST);
		$Tablenamedetails = Sections::findOne($id);
		$Tablenamedetails->delete();
	}
	public function actionTesttime(){
	    $preparetime = 10 * 60; 
	  $diffTime =  strtotime(date('Y-m-d H:i:s')) - strtotime('2020-11-05 10:33:20');
	echo $preparetime - $diffTime;
	    
	    
	}
	public function actionRaiserequest()
	{
		extract($_POST);
		$sdate = $_POST['sdate'] ?? date('Y-m-d'); 
		$edate = $_POST['edate'] ?? date('Y-m-d');
		$ingredientsDet =  Ingredients::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
		$ingredIdNameArr =  array_column($ingredientsDet,'item_name');
		$sqlPurchaseDetail = 'select * from ingredient_purchase ip 
		where merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and date(reg_date) between \''.$sdate.'\' and \''.$edate.'\' order by reg_date desc';
		$resPurchaseDetail = Yii::$app->db->createCommand($sqlPurchaseDetail)->queryAll();
		$vendorModel = MerchantVendor::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id,'status'=>'1'])->asArray()->all();
		return $this->render('raiserequest',['sdate'=>$sdate,'edate'=>$edate,'ingredIdNameArr'=>json_encode($ingredIdNameArr),'resPurchaseDetail'=>$resPurchaseDetail,'vendorModel'=>$vendorModel]);
	}
	public function actionFoodsections(){
	    $model = new FoodSections;
		if ($model->load(Yii::$app->request->post()) ) {
		    $model->merchant_id = (string)Yii::$app->user->identity->merchant_id;
		    $model->fs_status  = 1;
		    $model->reg_date = date('Y-m-d H:i:s A');
		if($model->validate()){
			$model->save();
				Yii::$app->getSession()->setFlash('success', [
        'title' => 'Food Section',
		'text' => 'Food Section Created Successfully',
        'type' => 'success',
        //'timer' => 3000,
        'showConfirmButton' => false
	]);
	
			return $this->redirect('foodsections');
		}
		else
		{
			echo "<pre>";print_r($model->getErrors());exit;
		}
	}
		
		$foodsections = FoodSections::find()
		->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->All();
		return $this->render('foodsections',['model'=>$model,'foodsections'=>$foodsections]);
	}
		public function actionEditfcpopup()
	{
		extract($_POST);
		$sectionModel = FoodSections::findOne($id);
		return $this->renderAjax('editfoodsection', ['model' => $sectionModel,'id'=>$id]);		
	}
	public function actionEditfoodsection()
	{
		$model = new FoodSections;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$sectionNameArr = Yii::$app->request->post('FoodSections');
		$sectionNameUpdate = FoodSections::findOne($_POST['FoodSections']['ID']);
		
		$sectionNameUpdate->attributes = \Yii::$app->request->post('FoodSections');
		
		if($sectionNameUpdate->validate()){
			$sectionNameUpdate->save();
			Yii::$app->getSession()->setFlash('success', [
        'title' => 'Food Section',
		'text' => 'Food Section Edited Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		}
		else{
			echo "<pre>";print_r($sectionNameUpdate->getErrors());exit;		    
		}

			return $this->redirect('foodsections');
	
	}
    public function actionTestsms(){
     $message = "Hi 1234 is OTP for your Registration.";
     $mobilenumber = '9014306522';
	Utility::otp_sms($mobilenumber,$message);   
    }
    public function actionRoomreservation(){
		extract($_POST);
        $merchantId = Yii::$app->user->identity->merchant_id;
	$roomModel = RoomReservations::find()
        ->where(['merchant_id' => $merchantId])
	->orderBy([
            'ID'=>SORT_DESC
        ])
	->asArray()->all();
	$model = new RoomReservations;
	$model->scenario = 'insertreservationdet';
	if ($model->load(Yii::$app->request->post()) ) {
		$model->merchant_id = (string)Yii::$app->user->identity->merchant_id;
		$category_pic = UploadedFile::getInstance($model, 'category_pic');
		if($category_pic){
			$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$category_pic->extension;
			$category_pic->saveAs('uploads/roomcategoryimages/' . $imagename);
			$model->category_pic = $imagename;
		}
// 		echo "<pre>";print_r($model);exit;
		if($model->validate()){
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'Room Reservation',
		'text' => 'Room Reservation Created Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
			$model->save();
			$category_id =  $model->getPrimaryKey();
			
			$roomNameArray = array_filter($roomnames);
	    if(!empty($roomNameArray))
	    {
			for($i=0;$i<count($roomNameArray);$i++)
			{
				$data[] = [$category_id,$roomNameArray[$i]
				,(string)Yii::$app->user->identity->merchant_id
				,Yii::$app->user->identity->emp_name
				,date('Y-m-d H:i:s A')];
			}
			Yii::$app->db
			->createCommand()
			->batchInsert('allocated_rooms', ['category_id','room_name','merchant_id', 'created_by', 'created_on'],$data)
			->execute();	
			
	    }
		return	$this->redirect('roomreservation');
		}
		else
		{
		//	echo "<pre>";print_r($model->getErrors());exit;
		}
	}
        return $this->render('roomreservation',['roomModel'=>$roomModel,'model'=>$model]);
    }
    public function actionEditroompopup()
	{
		extract($_POST);
		$roomModel = RoomReservations::findOne($id);
		$roomnames = [];
		$roomnames = AllocatedRooms::find()->where(['category_id' => $id])->asArray()->all();
		return $this->renderAjax('editroompopup', ['model' => $roomModel,'id'=>$id,'roomnames' => $roomnames]);		
    
	}
	public function actionUpdateroom()
	{
		extract($_POST);
	
		
		$model = new RoomReservations;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$roomArr = Yii::$app->request->post('RoomReservations');
		
		$roomUpdate = RoomReservations::findOne($_POST['ID']);
		$oldRoomNames = AllocatedRooms::find()->where(['category_id'=>$_POST['ID']])->asArray()->all();

		$oldRoomImage = $roomUpdate['category_pic'];
		
		$roomUpdate->attributes = \Yii::$app->request->post('RoomReservations');
		$image = UploadedFile::getInstance($model, 'category_pic');
			if($image){
				if(!empty($roomUpdate['category_pic'])){
					$imagePath =  '../../'.Url::to(['/uploads/roomcategoryimages/'. $roomUpdate['category_pic']]);
					if(file_exists($imagePath)){
						unlink($imagePath);	
					}
				}
				
				$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$image->extension;
				$image->saveAs('uploads/roomcategoryimages/' . $imagename);
				$roomUpdate->category_pic = $imagename;
				
			}else{
				$roomUpdate->category_pic = $oldRoomImage;
			}
		if($roomUpdate->validate()){
			if(count($oldRoomNames) > 0)
			{
					for($i=0;$i<count($oldRoomNames);$i++)
					{
						if($_POST['roomnames_'.$oldRoomNames[$i]['ID']] != $oldRoomNames[$i]['room_name'] )
						{
								$foodcategorytypes = AllocatedRooms::findOne($oldRoomNames[$i]['ID']);
								$foodcategorytypes->merchant_id = (string)Yii::$app->user->identity->merchant_id;
								$foodcategorytypes->room_name = $_POST['roomnames_'.$oldRoomNames[$i]['ID']];
								$foodcategorytypes->updated_by = Yii::$app->user->identity->emp_name;
								$foodcategorytypes->updated_on = date('Y-m-d H:i:s A');
								$foodcategorytypes->save();
						}
					}
			}
			$roomnamesArray = array_filter($roomnamesupdate);
			if(!empty($roomnamesArray))
			{
					for($i=0;$i<count($roomnamesArray);$i++)
					{
						$data[] = [$_POST['ID'],$roomnamesArray[$i]
						,(string)Yii::$app->user->identity->merchant_id
						,Yii::$app->user->identity->emp_name
						,date('Y-m-d H:i:s A')
					];
					}
					Yii::$app->db
					->createCommand()
					->batchInsert('allocated_rooms', ['category_id','room_name','merchant_id'
					, 'created_by','created_on'],$data)
					->execute();
			}

			$roomUpdate->save();
		}
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'Room',
		'text' => 'Room Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		return $this->redirect('roomreservation');
	}
    public function actionDeleteroom(){
		extract($_POST);
		$Roomdetails = \app\models\RoomReservations::findOne($id);
		$Roomdetails->delete();
	}
	public function actionNeworders()
	{
		
		$sqlTableDetails = 'select o.ID,tn.ID tableId,tn.name,o.orderprocess,o.reg_date
		,o.totalamount,o.ordertype,o.serviceboy_id from tablename tn left join orders o on tn.current_order_id = o.id 
		where tn.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and  orderprocess is not null ';
		$tableDetails = Yii::$app->db->createCommand($sqlTableDetails)->queryAll();
		
				$sqlParcelDetails = 'select * from  orders o 
		where o.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
		and tablename like \'%PARCEL%\'and date(reg_date) = \''.date('Y-m-d').'\' and orderprocess not in (\'3\',\'4\')';
		$parcelDetails = Yii::$app->db->createCommand($sqlParcelDetails)->queryAll();
	//				echo "<pre>";	print_r($tableDetails);exit;

		return $this->render('neworders',['parcelDetails'=>$parcelDetails,'tableDetails'=>$tableDetails]);
	}
	 public function actionMerchantnoti(){
		$roomRedDet = RoomReservations::findOne(6);
		$inputArr = ['merchant_id'=>'8', 'message'=>$roomRedDet['category_name']." is crossed the limit",'seen'=>'0'];
		Yii::$app->merchant->addMerchantNotifications($inputArr);

    //        $inputArr = ['merchant_id'=>'8', 'message'=>'New Message notification 3','seen'=>'0'];
   //         Yii::$app->merchant->addMerchantNotifications($inputArr);
            
        }
    public function actionStocksummary()
	{
		extract($_POST);
		$sdate = $_POST['sdate'] ?? date('Y-m-d'); 
		$edate = $_POST['edate'] ?? date('Y-m-d');
		$ingredientsDet =  Ingredients::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
		$ingredIdNameArr =  array_column($ingredientsDet,'item_name');
		$sqlPurchaseDetail = 'select * from ingredient_purchase ip 
		where merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and date(reg_date) between \''.$sdate.'\' and \''.$edate.'\' order by reg_date desc';
		$resPurchaseDetail = Yii::$app->db->createCommand($sqlPurchaseDetail)->queryAll();
		return $this->render('stocksummary',['sdate'=>$sdate,'edate'=>$edate,'ingredIdNameArr'=>json_encode($ingredIdNameArr),'resPurchaseDetail'=>$resPurchaseDetail]);
	}
	public function actionConsumption()
	{
		return $this->render('consumption');
	}
	 public function actionNewlycreatedorders()
	{
                $sqlTableDetails = 'select o.ID,tn.ID tableId,tn.name,o.orderprocess,o.reg_date
		,o.totalamount,current_order_id,o.ordertype,o.serviceboy_id 
                from tablename tn left join orders o on tn.current_order_id = o.id 
		where tn.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and  orderprocess=\'0\'';
		$tableDetails = Yii::$app->db->createCommand($sqlTableDetails)->queryAll();
		$sqlParcelDetails = 'select * from  orders o 
		where o.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
		and o.orderprocess= \'0\'';
		$newOrdersDetail = Yii::$app->db->createCommand($sqlParcelDetails)->queryAll();
                
                $sqlDinein = 'select o.ID,tn.ID tableId,tn.name,o.orderprocess,o.reg_date
		,o.totalamount,current_order_id,o.ordertype,o.serviceboy_id from tablename tn left join orders o on tn.current_order_id = o.id 
		where tn.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and  orderprocess in(\'1\',\'2\') ';
		$dineIn = Yii::$app->db->createCommand($sqlDinein)->queryAll();
                
		return $this->render('neworders',['tableDetails'=>$tableDetails,'newOrdersDetail'=>$newOrdersDetail,'dineIn'=>$dineIn]);
	}
	public function actionPurchase()
	{
	    extract($_POST);
		$sdate = $_POST['sdate'] ?? date('Y-m-d'); 
		$edate = $_POST['edate'] ?? date('Y-m-d');
		$daterowSpanArr = [];
		$purchase_number_rowSpanArr = [];
		$merchant_id = Yii::$app->user->identity->merchant_id;
$merchantDet = Merchant::findOne($merchant_id);
$storename = $merchantDet['storename'];
		if($type == '1' || '2')
		{
			$sql = 'select ip.purchase_number,date(ip.reg_date) reg_date,i.item_name,ipd.purchase_quantity,purchase_price from ingredient_purchase ip 
			inner join ingredient_purchase_detail ipd on ip.ID = ipd.purchase_id 
			inner join ingredients i on i.ID = ipd.ingredient_id 
			where ip.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
			and date(ip.reg_date) between \''.$sdate.'\' and \''.$edate.'\' order by date(reg_date),purchase_number,item_name ';
			
			$res = Yii::$app->db->createCommand($sql)->queryAll();
			//print_r($res);exit;
			
		$sqlJson = 'select * from ( select * from ( select ip.purchase_number,date(ip.reg_date) reg_date,i.item_name,ipd.purchase_quantity
,purchase_price from ingredient_purchase ip inner join ingredient_purchase_detail ipd on ip.ID = ipd.purchase_id
inner join ingredients i on i.ID = ipd.ingredient_id where ip.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'
and date(ip.reg_date) between \''.$sdate.'\' and \''.$edate.'\' order by date(ip.reg_date),purchase_number,item_name ) A
union select concat(date(ip.reg_date),\' Total\'),concat(date(ip.reg_date),\' Total\') reg_date,null,sum(ipd.purchase_quantity) purchase_quantity,sum(purchase_price) purchase_price from ingredient_purchase ip inner join ingredient_purchase_detail ipd on ip.ID = ipd.purchase_id inner join ingredients i on i.ID = ipd.ingredient_id
where ip.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and date(ip.reg_date) between \''.$sdate.'\' and \''.$edate.'\' group by date(ip.reg_date) ) B
order by reg_date,purchase_number';
			$resjson = Yii::$app->db->createCommand($sqlJson)->queryAll();
			
			$dateArr = array_column($res,'reg_date');
		    $daterowSpanArr = array_count_values($dateArr);
			$purchase_number_Arr = array_column($res,'purchase_number');
		    $purchase_number_rowSpanArr = array_count_values($purchase_number_Arr);			
		}
// 		else if($type == '2'){
// 			$sql = 'select date(ip.reg_date) reg_date,sum(coalesce(purchase_amount,0)) purchase_amount from ingredient_purchase ip
// 			where ip.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
// 			and date(ip.reg_date) between \''.$sdate.'\' and \''.$edate.'\' group by date(reg_date) order by date(reg_date) ';
// 			$res = Yii::$app->db->createCommand($sql)->queryAll();
// 			$resjson = [];
// 		}
        return $this->render('purchase',['sdate'=>$sdate,'edate'=>$edate,'res'=>$res
		,'daterowSpanArr'=>$daterowSpanArr,'sdate'=>$sdate,'edate'=>$edate
,'purchase_number_rowSpanArr'=>$purchase_number_rowSpanArr,'type'=>$type
,'resjson'=>json_encode($resjson),'orgres'=>json_encode($res),'storename'=>$storename]);
		
	}
	
    	public function actionVendorreport()
	{
	    extract($_POST);
		$sdate = $_POST['sdate'] ?? date('Y-m-d'); 
		$edate = $_POST['edate'] ?? date('Y-m-d');
		
        return $this->render('vendorreport',['sdate'=>$sdate,'edate'=>$edate]);
		
	} 
	public function actionWastagereport()
	{
	    extract($_POST);
		$sdate = $_POST['sdate'] ?? date('Y-m-d'); 
		$edate = $_POST['edate'] ?? date('Y-m-d');
		
        return $this->render('wastagereport',['sdate'=>$sdate,'edate'=>$edate]);
		
	} 
	public function actionTax()
	{
	    extract($_POST);
		$sdate = $_POST['sdate'] ?? date('Y-m-d'); 
		$edate = $_POST['edate'] ?? date('Y-m-d');
		
        return $this->render('tax',['sdate'=>$sdate,'edate'=>$edate]);
		
	}
	public function actionRoomreservationhistory(){
		extract($_POST);
		
		$sdate = $_POST['sdate'] ?? date('Y-m-d'); 
		$edate = $_POST['edate'] ?? date('Y-m-d');
		$merchantId = Yii::$app->user->identity->merchant_id;
		$reservationstatus = $_POST['reservationstatus'] ?? '';

		$sqlroomreshis = 'select ui.*,rr.category_name from userinformation ui 
		inner join room_reservations rr on rr.ID = ui.room_category
		where date(ui.booking_time) between \''.$sdate.'\' 
		and \''.$edate.'\' and ui.merchant_id = \''.$merchantId.'\' ';
		if(!empty($reservationstatus)){
			$sqlroomreshis .= ' and reservation_status = coalesce(\''.$reservationstatus.'\',reservation_status) ';
		}
			$sqlroomreshis .= ' order by ui. ID desc';
		$roomModel = Yii::$app->db->createCommand($sqlroomreshis)->queryAll();
		//echo "<pre>";print_r($roomModel);exit;

	$model = new Userinformation;
	$userDet = (Yii::$app->request->post('Userinformation'));
	if ($model->load(Yii::$app->request->post()) ) {
		$model->merchant_id = (string)Yii::$app->user->identity->merchant_id;
		$model->booking_time = date('Y-m-d H:i:s A');
		$model->arrived_time = date('Y-m-d H:i:s A');
		if($model->validate()){
			$this->roomavailblityupdate($userDet['room_category'],1,1);
			$catModel = AllocatedRooms::findOne($userDet['room_alocated']);
			$catModel->status = 2;
			$catModel->save();
			Yii::$app->getSession()->setFlash('success', [
        		'title' => 'User Reservation',
				'text' => 'User Created Successfully',
        		'type' => 'success',
        		'timer' => 3000,
        		'showConfirmButton' => false
    		]);
			$model->save();

			$reservation_id =  $model->getPrimaryKey();
			
			$guestnameArray = array_filter($guestname);
	    if(!empty($guestnameArray))
	    {

			

			for($i=0;$i<count($guestnameArray);$i++)
			{
				$imagename = '';
				$filename = $_FILES['guestidentity']['name'][$i];
	
			if(!empty($filename))
			  {
				$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
				$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$ext;
				// Upload file
				move_uploaded_file($_FILES['guestidentity']['tmp_name'][$i],'uploads/guestIdentity/'.$imagename);	
			 }
				$data[] = [$reservation_id,$userDet['room_category']
				,(string)Yii::$app->user->identity->merchant_id
				,$userDet['room_alocated']
				,$guestnameArray[$i]
				,$identity[$i]
				,$imagename
				,Yii::$app->user->identity->emp_name
				,date('Y-m-d H:i:s A')];


			}

			Yii::$app->db
			->createCommand()
			->batchInsert('room_guest_identitiy', ['reservation_id'
			,'category_id','merchant_id','room_id','guest_name','guest_id_name'
			,'guest_identity'
			, 'created_by', 'created_on'],$data)
			->execute();	
			
	    }

		return	$this->redirect('roomreservationhistory');
		}
		else
		{
			echo "<pre>";print_r($model->getErrors());exit;
		}
	}
        return $this->render('roomreservationhistory',['roomModel'=>$roomModel
		,'model'=>$model,'sdate' => $sdate ,'edate' => $edate, 'reservationstatus' => $reservationstatus]);
    }
            public function actionTaxes(){
            $model = new MerchantTax;
		if ($model->load(Yii::$app->request->post()) ) {
		    $model->merchant_id = (string)Yii::$app->user->identity->merchant_id;
		    $model->created_on = date('Y-m-d H:i:s A');
                    $model->created_by = Yii::$app->user->identity->emp_name;
		if($model->validate()){
		    //print_r($_POST);exit;
			$model->save();
				Yii::$app->getSession()->setFlash('success', [
        'title' => 'Tax',
		'text' => 'Tax Created Successfully',
        'type' => 'success',
        //'timer' => 3000,
        'showConfirmButton' => false
	]);
	if(!empty($_POST['toredirect'])){
	    			return $this->redirect('food-categeries');
	}else{
			return $this->redirect('taxes');	    
	}

		}
		else
		{
			echo "<pre>";print_r($model->getErrors());exit;
		}
	}
		$sqlTaxes = 'select * from merchant_tax';
    	$taxes = Yii::$app->db->createCommand($sqlTaxes)->queryAll();

	
            return $this->render('taxes',['model'=>$model,'taxes'=>$taxes]);
        }
        public function actionEdittaxpopup(){
            extract($_POST);
            $taxModel = MerchantTax::findOne($id);
            return $this->renderAjax('edittax', ['model' => $taxModel,'id'=>$id]);		
        }
        public function actionEdittax()
	{
		$model = new MerchantTax;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$taxNameArr = Yii::$app->request->post('MerchantTax');
		$taxNameUpdate = MerchantTax::findOne($_POST['MerchantTax']['ID']);
		
		$taxNameUpdate->attributes = \Yii::$app->request->post('MerchantTax');
                //echo '<pre>';print_r($taxNameUpdate->attributes);exit;
		if($taxNameUpdate->validate()){
			$taxNameUpdate->save();
			Yii::$app->getSession()->setFlash('success', [
        'title' => 'Tax',
		'text' => 'Tax Edited Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		}
		else{
			echo "<pre>";print_r($taxNameUpdate->getErrors());exit;		    
		}

			return $this->redirect('taxes');
	
	}
       
        public function actionEditcategorytaxpopup()
    {
	extract($_POST);
        //echo '<pre>';print_r($_POST);exit;
        $merchantTax =  MerchantTax::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->all();
	$categeryModel = FoodCategeries::findOne($id);
        
        $sqlCategoryTax = 'select mfct.*,mt.tax_name from merchant_food_category_tax mfct
                        join merchant_tax mt on mt.ID=mfct.merchant_tax_id
                        where mfct.merchant_id=\''.Yii::$app->user->identity->merchant_id.'\'
                        and mfct.food_category_id=\''.$id.'\'';
        $categoryTax = Yii::$app->db->createCommand($sqlCategoryTax)->queryAll();
//        echo '<pre>';print_r($categoryTax);exit;
	return $this->renderAjax('updatecategerytax', ['merchantTax' => $merchantTax,'id'=>$id,'categeryModel'=>$categeryModel,'categoryTax'=>$categoryTax]);		
    }
    public function actionUpdatefoodcategerytax(){
        //echo '<pre>';print_r($_POST);
        extract($_POST);

	$foodCategoryUpdate = FoodCategeries::findOne($_POST['food_category_id']);
	$foodCatetax =  MerchantFoodCategoryTax::find()->where(['food_category_id'=>$foodCategoryUpdate['ID']])->asArray()->all();
        //$catTypeArray = array_filter($categorytypes);
	if(!empty($tax_id[0]))
	{
			for($i=0;$i<count($tax_id);$i++)
			{
				$data[] = [(string)Yii::$app->user->identity->merchant_id,$food_category_id,$tax_id[$i],$tax_type[$i],$tax_value[$i]];
			}
//                       echo '<pre>'; print_r($data);exit;
			Yii::$app->db
			->createCommand()
			->batchInsert('merchant_food_category_tax', ['merchant_id','food_category_id','merchant_tax_id', 'tax_type','tax_value'],$data)
			->execute();
	}
	
	if(isset($update_food_cat_tax_id)){
	    for($t=0;$t<count($update_food_cat_tax_id);$t++){
	    $sqlUpdate = 'update merchant_food_category_tax set tax_value = \''.$update_tax_value[$t].'\' where ID = \''.$update_food_cat_tax_id[$t].'\'';
	    $resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute();
	    }     
	    }

  Yii::$app->getSession()->setFlash('success', [
        'title' => 'Foor Category Tax',
		'text' => 'Food Category Tax Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
	return $this->redirect('food-categeries');		
    }
    public function actionNew(){
	    $message = "Hi ravi is your otp for verification.";
			echo			 \app\helpers\Utility::otp_sms('9014306522',$message).'<br>';
			echo date('Y-m-d H:i:s A');
	}
	public function actionGetvendornames(){
		$vendorDetails = MerchantVendor::find()
		->where(['merchant_id' => (string)Yii::$app->user->identity->merchant_id , 'vendor_type' => $_POST['vendortype'] ])
	->orderBy([
            'ID'=>SORT_DESC
        ])
	->asArray()->all();

	$vendorDet = array_column($vendorDetails,'store_name','ID');
	return json_encode($vendorDetails);
	}
	public function actionLoyalty(){

		$loyaltyDet = MerchantLoyalty::find()
		->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->All();

	    $model = new MerchantLoyalty;
	    	if ($model->load(Yii::$app->request->post()) ) {
	    	   $model->merchant_id = Yii::$app->user->identity->merchant_id;
	    	   $model->created_by = Yii::$app->user->identity->emp_name;
	    	   $model->attributes = \Yii::$app->request->post('MerchantLoyalty');
	    	if(!$model->save()){
	    	    print_r($model->getErrors());
	    	}
	    }
	    
	    return $this->render('loyalty',['model'=>$model,'loyaltyDet'=>$loyaltyDet]);
	}
	
	public function actionCalculateloyalty()
	{
	    extract($_POST);
	   // $id = 1;
	    $loyaltyMainDet = MerchantLoyalty::findOne($id);
	    $days = $loyaltyMainDet['days'] -1 ;
	    $time_period =  $loyaltyMainDet['time_period'];
	    for($i=$time_period,$j=0;$i>0;$i--,$j++){
	       $prevMonth = date('m',strtotime("-$i months"));
	       $prevYear = date('Y',strtotime("-$i months"));
	       $prevMonthStartDate = date('Y-m-01',strtotime("-$i months"));
	       $numOfDaysInMonth = cal_days_in_month(CAL_GREGORIAN,$prevMonth,$prevYear);
	       $divideSection = round($numOfDaysInMonth/$days);
	       for($k=0;$k<$divideSection;$k++){
	           $startDate =  $prevMonthStartDate;
	           if($divideSection == $k+1){
	               $endDate =      date("Y-m-$numOfDaysInMonth", strtotime($prevMonthStartDate. " + $days days"));         
	           }else{
	               $endDate =      date('Y-m-d', strtotime($prevMonthStartDate. " + $days days"));
	           }
	      $datesArr[$j][$k]['start_date'] = $prevMonthStartDate;
	      $datesArr[$j][$k]['end_date'] = $endDate;
	      $prevMonthStartDate = date('Y-m-d', strtotime($endDate. " + 1 days"));
	       
	       }
	    }
        $userIdArr = [];
        $mainUserIdArr = [];
        for($l=0;$l<count($datesArr);$l++){
            for($p=0;$p<count($datesArr[$l]);$p++){
                $sdate =  $datesArr[$l][$p]['start_date'];
                $edate =  $datesArr[$l][$p]['end_date'];
                $sqlOrders = 'select  user_id,count(user_id) orderCount from orders where merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' 
                and orderprocess = \'4\' and date(reg_date) between \''.$sdate.'\' and \''.$edate.'\' 
                and TRIM(user_id) <> "" and user_id is not null group by user_id having count(user_id) >= '.$loyaltyMainDet['repetition_no'];
                $resOrders = Yii::$app->db->createCommand($sqlOrders)->queryAll();
               // print_r($resOrders);
                if($l==0 && $p == 0){
                  
                    $mainUserIdArr = array_values(array_filter(array_unique(array_column($resOrders,'user_id'))));
                //$userIdArr = $resOrders;
                }else{
                     
                    $userIdArr = array_values(array_filter(array_unique(array_column($resOrders,'user_id'))));
                    $mainUserIdArr = array_intersect($mainUserIdArr, $userIdArr);
               }
            }            
        }
       
        $mainUserIdArr = array_values($mainUserIdArr);
        $sqlLoyaltyDetails = 'select * from merchant_loyalty ml inner join merchant_loyalty_details mld on ml.ID = mld.loyalty_id
        and ml.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and date(mld.reg_date) = \''.date('Y-m-d').'\'';
        $resLoyaltyDetails = Yii::$app->db->createCommand($sqlLoyaltyDetails)->queryAll();
        if(count($resLoyaltyDetails) == 0){
            for($m=0;$m<count($mainUserIdArr);$m++)
			{
				$data[] = [(string)Yii::$app->user->identity->merchant_id,$id,$mainUserIdArr[$m],date('Y-m-d H:i:s A')];
					$userdetails = Users::findOne($mainUserIdArr[$m]);
													 Yii::$app->merchant->send_sms($userdetails['mobile'],$loyaltyMainDet['message']); 

			}
	
		
		$insert	= Yii::$app->db
			->createCommand()
			->batchInsert('merchant_loyalty_details', ['merchant_id','loyalty_id','user_id', 'reg_date'],$data)
			->execute();
        }
	}
	public function actionWeeklystock(){
	    
		$inventoryUpdateReq = InventaryUpdationRequest::find()->
		where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->All();
	  //  echo 'heeloooo<pre>';
	  //  print_r($inventoryUpdateReq);exit;
	    $this->render('weeklystock',array('inventoryUpdateReq'=>$inventoryUpdateReq));
	    
	}
	public function actionMerchantloyaltydetails(){
	    //exit('asdfasdffafs');
	    extract($_POST);
	    
		$loyaltyDet = MerchantLoyaltyDetails::find()
		->where(['loyalty_id'=>$id])->asArray()->All();
		return $this->renderAjax('merchantloyaltydetails', ['loyaltyDet' => $loyaltyDet]);	
	}
    public function actionNewpos()
	{
		extract($_REQUEST);
		$merchant_id = Yii::$app->user->identity->merchant_id;
		if(empty($tableid)){
		        
				$selectOneTable = Tablename::find()
				->where(['status'=>'1', 'merchant_id'=>$merchant_id])->limit(1)->asArray()->One();
		        $tableid = $selectOneTable['ID'];
		        $tableName = $selectOneTable['name'];
		        $table_section_id = $selectOneTable['section_id'];
		        $current_order_id = $selectOneTable['current_order_id'];
		}
		else{
			
		   if(is_numeric($tableid) && !empty($tableid)){
			$tabDet =  Tablename::findOne($tableid);
			$tableName = $tabDet['name'];
			$table_section_id = $tabDet['section_id'];
		   }
		   else{
			$tableName = "PARCEL";
		   }

		   
		}


		$sql = 'select * from food_categeries fc inner join (select distinct title as title
		,REPLACE(REPLACE(REPLACE(title, " ", "_"),"\'","_"),"/",",") modified_title ,
		foodtype from product where merchant_id = \''.$merchant_id.'\' and status=\'1\') p on p.foodtype = fc.ID 
		where fc.merchant_id = \''.$merchant_id.'\'';
		$res = Yii::$app->db->createCommand($sql)->queryAll();
		$result = \yii\helpers\ArrayHelper::index($res, null, 'ID');
		$fsresult = \yii\helpers\ArrayHelper::index($res, null, 'food_section_id');
		unset($fsresult[0]);
		//echo "<pre>";print_r($fsresult);exit;
		$sqlproductDetails = 'select P.ID,P.title,P.food_category_quantity
		,P.image,coalesce(sipl.section_item_sale_price,0) as price,fc.food_category 
		,fc.ID food_category_id,REPLACE(REPLACE(REPLACE(title, " ", "_"),"\'","_"),"/",",") modified_title
		,food_type_name
		,case when concat(title , " ",food_type_name) is null then title else concat(title , " ",food_type_name) end   titlewithqty
		from product P 
		left join food_categeries fc on fc.ID = P.foodtype  
		left join food_category_types fct on fct.ID =  P.food_category_quantity and fct.merchant_id =  \''.Yii::$app->user->identity->merchant_id.'\'

        left join section_item_price_list sipl on sipl.item_id =  P.ID and sipl.section_id = \''.$table_section_id.'\'
		where P.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and status=\'1\' ';
		$productDetails = Yii::$app->db->createCommand($sqlproductDetails)->queryAll();
		$allProductDetails = \yii\helpers\ArrayHelper::index($productDetails, null, 'modified_title');
		$fcqarr = array_column($productDetails,'food_type_name','ID');
		$sqlTableDetails = 'select s.section_name as sectionname,tn.*,o.orderprocess
		 from tablename tn 
		 inner join sections s on s.ID = tn.section_id
		 left join orders o on tn.current_order_id = o.id 
		where tn.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' and tn.status = \'1\'';
		
		$tableDetails = Yii::$app->db->createCommand($sqlTableDetails)->queryAll();
		
		$food_cat_qty_arr = FoodCategoryTypes::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->All();
		$food_cat_qty_det = array_column($food_cat_qty_arr,'food_type_name','ID');
		$prevOrderDetails = [];
		$prevFullSingleOrderDet = [];
		$userDet = [];
			
		if(!empty($current_order_id) && $current_order_id > 0 )
		{
			$prevFullSingleOrderDet = Orders::findOne($current_order_id);
			if(!empty($prevFullSingleOrderDet['user_id'])){
				$userDet = Users::findOne($prevFullSingleOrderDet['user_id']);
			}
			if($prevFullSingleOrderDet['orderprocess'] != '3' && $prevFullSingleOrderDet['orderprocess'] != '4'){
				$sqlPrevOrderDetails = 'select op.user_id,op.order_id,op.merchant_id,op.product_id
				,sum(op.count) count,(op.price) price
				,sum(coalesce(op.count,0)*coalesce(op.price,0)) 
			    as totalprice,u.name,u.mobile from order_products op left join users u on op.user_id = u.ID 
			    where order_id = \''.$current_order_id.'\' and count != \'0\' group by op.user_id,op.order_id,op.merchant_id,op.product_id
			    ,u.name,u.mobile,op.price';
			    $prevOrderDetails = Yii::$app->db->createCommand($sqlPrevOrderDetails)->queryAll();
			}
			
		}
		else{
		  $prevFullSingleOrderDet['paymenttype'] = '1';
			$prevFullSingleOrderDet['serviceboy_id'] = '';		  
		  
		}

		$resMerchantfoodTax = MerchantFoodCategoryTax::find()
		->select('food_category_id,tax_type,tax_value,merchant_tax_id')
		->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])
		->asArray()->All();

		
	    $MerchantfoodTaxArr = \yii\helpers\ArrayHelper::index($resMerchantfoodTax, null, 'food_category_id');

		$prodvsfcidarr = array_column($productDetails,'food_category_id','ID');
		//echo "<pre>";print_r($prodvsfcidarr);exit;
		
		$resfc = FoodSections::find()
		->where(['fs_status'=>'1','merchant_id'=>Yii::$app->user->identity->merchant_id])
		->asArray()->All();

		$resServiceBoy = Serviceboy::find()
		->where(['merchant_id'=>Yii::$app->user->identity->merchant_id,'loginstatus'=>'1'])
		->asArray()->All();
		//echo "<pre>";print_r($resServiceBoy);exit;
		
		$sqlRunning = 'select o.order_id,s.name pilot_name,u.name username,o.tablename,o.ID
		,orderprocess,o.totalamount,tb.name table_name,preparetime,preparedate,sec.section_name from orders o 
		left join serviceboy s on o.serviceboy_id = s.ID
		left join users u on o.user_id = u.ID
		left join tablename tb on tb.ID = o.tablename
		left join sections sec on sec.ID = tb.section_id 
		where orderprocess not in (\'3\',\'4\') and o.merchant_id = \''.Yii::$app->user->identity->merchant_id.'\' order by ID desc';
		$runningOrders = Yii::$app->db->createCommand($sqlRunning)->queryAll();

		$sqlmerchantcoupon = 'SELECT  code from merchant_coupon where  merchant_id = \''.Yii::$app->user->identity->merchant_id.'\'
		and status = \'Active\' AND \''.date('Y-m-d').'\' between  date(fromdate) and date(todate)';
		$resmerchantcoupon = YIi::$app->db->createCommand($sqlmerchantcoupon)->queryAll();
		$merchantcoupons = array_column($resmerchantcoupon,'code');
		$merchant_pay_types_det = \app\models\MerchantPaytypes::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id,'status' => 1])->orderBy([
            'ID'=>SORT_DESC
        ])->asArray()->All();
        $merchant_pay_types = array_column($merchant_pay_types_det,'paymenttype');
		return $this->render('newpos',['result' => $result
		,'res' => $res,'allProductDetails' => $allProductDetails
		,'tableDetails' => $tableDetails 
		, 'prevOrderDetails' => $prevOrderDetails, 'productDetails' => $productDetails
		,'MerchantfoodTaxArr' => $MerchantfoodTaxArr, 'prodvsfcidarr' => $prodvsfcidarr
		,'tableid' => $tableid , 'tableName' => $tableName
		,'fsresult' => $fsresult, 'resfc' => $resfc ,'prevFullSingleOrderDet' => $prevFullSingleOrderDet 
		,'resServiceBoy' => $resServiceBoy
		,'userDet' => $userDet, 'runningOrders' => $runningOrders,'merchantcoupons' => $merchantcoupons
		,'fcqarr' => $fcqarr, 'merchant_pay_types' => $merchant_pay_types
		,'current_order_id' => !empty($current_order_id) ? $current_order_id : 0
		]);
	}	
	public function actionSaveneworder()
	{
		extract($_REQUEST);
		$arr =  $_REQUEST;
if(!empty($user_mobile)){
	
	$alreadyid = Users::find()->where(['mobile'=>$user_mobile])->asArray()->One();

	if(empty($alreadyid)){
		$user_id = Yii::$app->merchant->userCreation($user_mobile,$user_name);
	}
	else{
		if(!empty($user_name) &&  $user_name != $alreadyid['name'] ){
			$umodel = Users::findOne($alreadyid['ID']);
			$umodel->name = $user_name;
			$umodel->save();
		}
		$user_id = $alreadyid['ID'];
	}
}
		$tablecheck = $tableid;
		if(!is_numeric($tablecheck) || empty($tableid)){

		Yii::$app->getSession()->setFlash('success', [
			'title' => 'Order',
			'text' => "Place Services are temporarily disabled!!",
			'type' => 'warning',
			'timer' => 3000,
			'showConfirmButton' => false
		]);
		   
		   return $this->redirect('merchant/newpos');
		   
			$tableid = 'PARCEL'.Utility::get_parcel_uniqueid();
			$table_det['name'] =  $tableid;
			$sqlparcel = 'select * from orders where date(reg_date) = \''.date('Y-m-d').'\' 
			and tablename = \''.$tablecheck.'\' and orderprocess not in (\'3\',\'4\')' ;
			$resparcel = Yii::$app->db->createCommand($sqlparcel)->queryOne();
			
			if(!empty($resparcel)){
				$table_det['table_status'] = 1;	
				$table_det['current_order_id'] = $resparcel['ID'];
			}
			else
			{
				$table_det['table_status'] = 0;
			}

			
		}
		else{
			$table_det = Tablename::findOne($tableid);
	         
		}
		$merchant_details = Merchant::findOne(Yii::$app->user->identity->merchant_id);
		if ( ($table_det['table_status'] == '1' && $current_order_id > 0 && $merchant_details['table_occupy_status'] == 1) ||  
		($merchant_details['table_occupy_status'] == 2 && $current_order_id > 0) ) {
			if($table_det['current_order_id'] != $current_order_id && $merchant_details['table_occupy_status'] == 1) {
				Yii::$app->getSession()->setFlash('success', [
					'title' => 'Order',
					'text' => 'Table is already occupied',
					'type' => 'warning',
					'timer' => 3000,
					'showConfirmButton' => false
				]);
				
				return $this->redirect(['merchant/newpos','tableid'=>$tableid,'tableName'=>$table_det['name'],'current_order_id'=>0  ]);
			}

			$orderDetails = Orders::findOne($current_order_id);

			$orderData['order_id'] =  $current_order_id;
			$_POST['productprice'] = $pricetot;
			$orderData['user_id'] = $user_id ?? '';
			$orderData['selectedpilot'] = $pilotid ?? '';
			$_POST['product_popup'] = $_POST['itemid'];
			$orderData['product_popup'] = $_POST['itemid'];
			$orderData['popupamount'] = is_array($pricetot) ? array_sum($pricetot) : 0;
			$orderData['popuptipamt'] = '0';
			$orderData['popuptaxamt'] = $ttl_tax ?? 0;
			$orderData['popupsubscriptionamt'] = '0';
			$orderData['couponamount'] = $ttl_cpn_amt ?? 0;
			$orderData['couponamountpopup'] = $ttl_cpn_amt ?? 0;
			$orderData['merchant_coupon'] = $_POST['merchantcpn'];
			$orderData['merchant_discount'] = $ttl_discount ?? 0;
            $orderData['popuptipamt'] = $ttl_tip ?? 0;
			$orderData['popuptotalamt'] = ($ttl_amt) ? ($ttl_amt) : 0;;
			//$orderData['payment_mode'] = 'Cash';
			$orderData['pending_amount'] = $ttl_pending_amount ?? 0;
			$_POST['order_quantity_popup'] = $_POST['qtyitem'];
			$_POST['order_price_popup'] = $_POST['priceind'];
			$this->re_order($orderData);
			$cur_order_id = $current_order_id;
			$order_status = 'Order updated successfully';

			if($pilotid != '' && empty($orderDetails['serviceboy_id'])){
				$notificaitonarary = array();
				$notificaitonarary['merchant_id'] = $orderDetails['merchant_id'];
				$notificaitonarary['serviceboy_id'] = $pilotid;
				$notificaitonarary['order_id'] = (string)$orderDetails['ID'];
				$notificaitonarary['title'] = 'New Order';
				$notificaitonarary['message'] = 'Order '.$orderDetails['order_id'].' is assigned to you with Accepted Status';
				$notificaitonarary['ordertype'] = 'new';
				$notificaitonarary['seen'] = '0';
								
				$serviceBoyNotiModel = new  ServiceboyNotifications;
				$serviceBoyNotiModel->attributes = $notificaitonarary;
				$serviceBoyNotiModel->reg_date = date('Y-m-d H:i:s');
				$serviceBoyNotiModel->mod_date = date('Y-m-d H:i:s');
				$serviceBoyNotiModel->save();
	
			}
			
		}
		else{
			if($table_det['table_status'] == '1' && $merchant_details['table_occupy_status'] == 1) {
				Yii::$app->getSession()->setFlash('success', [
					'title' => 'Order',
					'text' => 'Table is already occupied',
					'type' => 'warning',
					'timer' => 3000,
					'showConfirmButton' => false
				]);
				
				return $this->redirect(['merchant/newpos','tableid'=>$tableid,'tableName'=>$table_det['name'],'current_order_id'=>0  ]);
			}
			$arr['amount'] = !empty($ttl_sub_amt) ? $ttl_sub_amt : 0;
			$arr['taxamt'] = $ttl_tax ?? 0;;
			$arr['tipamt'] = $ttl_tip ?? 0;
			$arr['subscriptionamt'] = 0;
			$arr['couponamount'] = $ttl_cpn_amt ?? 0;;
			$arr['totalamt'] = ($ttl_amt) ? ($ttl_amt) : 0;
			$arr['merchant_coupon'] = $_POST['merchantcpn'];
			//$arr['payment_mode'] = 'Cash';
			$arr['discount_mode'] = $ttl_discount_type ?? 0;
			$arr['merchant_discount'] = $ttl_discount ?? 0;
			$arr['tableid'] = $tableid;		
			$arr['pilotid'] = $pilotid ?? '';
			$arr['user_id'] = !empty($user_id) ? (string)$user_id : '';
		$cur_order_id = Yii::$app->merchant->saveorder($arr);
		$order_status = 'Order created successfully';
		if(!empty($pilotid)){
			$serviceboyarray = Serviceboy::findOne($pilotid);
			$orderdetails = Orders::findOne($cur_order_id);

			$stitle = 'New order.';
			$smessage = 'New order received please check the app for information.';
			$simage = '';
			$order_det = Orders::findOne($cur_order_id);
			if(!empty($order_det['user_id'])){
				$userdetails = Users::findOne($order_det['user_id']);
			}
			$notificationdet = ['type' => 'NEW_ORDER','orderamount' => $order_det['totalamount'],'username' => !empty(@$userdetails['name']) ? $userdetails['name'] : null];
			Utility::sendNewFCM($serviceboyarray['push_id'],$stitle,$smessage,$simage,'6',null,$order_det['ID'],$notificationdet);
			
			$notificaitonarary = array();
			$notificaitonarary['merchant_id'] = $orderdetails['merchant_id'];
			$notificaitonarary['serviceboy_id'] = $pilotid;
			$notificaitonarary['order_id'] = (string)$orderdetails['ID'];
			$notificaitonarary['title'] = 'New Order';
			$notificaitonarary['message'] = 'Order '.$orderdetails['order_id'].' is assigned to you with Accepted Status';
			$notificaitonarary['ordertype'] = 'new';
			$notificaitonarary['seen'] = '0';
							
			$serviceBoyNotiModel = new  ServiceboyNotifications;
			$serviceBoyNotiModel->attributes = $notificaitonarary;
			$serviceBoyNotiModel->reg_date = date('Y-m-d H:i:s');
			$serviceBoyNotiModel->mod_date = date('Y-m-d H:i:s');
			$serviceBoyNotiModel->save();
		}

		}
		Yii::$app->getSession()->setFlash('success', [
			'title' => 'Order',
			'text' => $order_status,
			'type' => 'success',
			'timer' => 3000,
			'showConfirmButton' => false
		]);
		
		return $this->redirect(['merchant/newpos','tableid'=>$tableid,'tableName'=>$table_det['name'],'current_order_id'=>$cur_order_id  ]);
	} 
	public function actionRoomprofiles()
	{
		$merchantId = Yii::$app->user->identity->merchant_id;
		$model = new RoomProfileTitles;
		$titleModel = RoomProfileTitles::find()
        ->where(['merchant_id' => $merchantId])
		->orderBy([
            'ID'=>SORT_DESC
        ])
		->asArray()->all();
		if ($model->load(Yii::$app->request->post()) ) {
			$model->merchant_id = Yii::$app->user->identity->merchant_id;
			$model->created_on = date('Y-m-d H:i:s A');
			$model->status = 1;
			$model->created_by = Yii::$app->user->identity->emp_name;
			$model->attributes = \Yii::$app->request->post('RoomProfileTitles');
		 if($model->save()){
			Yii::$app->getSession()->setFlash('success', [
				'title' => 'Title',
				'text' => 'Title Added Successfully',
				'type' => 'success',
				'timer' => 3000,
				'showConfirmButton' => false
			]);
			return	$this->redirect('roomprofiles');
		 }
	 }
		return $this->render('roomprofiles',['model' => $model,'titleModel' => $titleModel]);
	}
	public function actionEditprofiletitlepopup()
	{
		extract($_POST);
		$model = RoomProfileTitles::findOne($id);
		return $this->renderAjax('updateroomprofiles', ['model' => $model]);		
	}
	public function actionUpdateprofiletitle()
	{
		$model = new RoomProfileTitles;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$titleArr = Yii::$app->request->post('RoomProfileTitles');
		$titleUpdate = RoomProfileTitles::findOne($_POST['RoomProfileTitles']['ID']);
		
		$titleUpdate->attributes = \Yii::$app->request->post('RoomProfileTitles');
		$titleUpdate->updated_on = date('Y-m-d H:i:s A');
		$titleUpdate->updated_by = Yii::$app->user->identity->emp_name;
		if($titleUpdate->validate()){
			$titleUpdate->save();
			Yii::$app->getSession()->setFlash('success', [
        'title' => 'Title',
		'text' => 'Title Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		}else{
			echo "<pre>";print_r($titleUpdate->getErrors());exit;
		}
			return $this->redirect('roomprofiles');
	}
	public function actionTitleimages()
	{
		$merchantId = Yii::$app->user->identity->merchant_id;
		$titleImageModelSql = 'select rti.ID,rpt.title_name,title_pic from room_title_images rti 
		inner join room_profile_titles rpt on rti.title_id = rpt.ID
		where rti.merchant_id = \''.$merchantId.'\' ';
		$titleImageModel = Yii::$app->db->createCommand($titleImageModelSql)->queryAll();
		$model = new RoomTitleImages;
		$model->scenario = 'inserttitleimagedet';
		if ($model->load(Yii::$app->request->post()) ) {
			$model->merchant_id = (string)Yii::$app->user->identity->merchant_id;
			$model->created_on = date('Y-m-d H:i:s A');
			$model->created_by = Yii::$app->user->identity->emp_name;
			$title_pic = UploadedFile::getInstance($model, 'title_pic');

			if($title_pic){
				$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$title_pic->extension;
				$title_pic->saveAs('uploads/titleimages/' . $imagename);
				$model->title_pic = $imagename;
			}

			if($model->validate()){
			Yii::$app->getSession()->setFlash('success', [
			'title' => 'Title Image',
			'text' => 'Title Image Created Successfully',
			'type' => 'success',
			'timer' => 3000,
			'showConfirmButton' => false
		]);
				$model->save();
	
			return	$this->redirect('titleimages');
			}
			else
			{
				echo "<pre>";print_r($model->getErrors());exit;
			}
		}
			return $this->render('titleimages',['titleImageModel'=>$titleImageModel,'model'=>$model]);
	}
	public function actionEdittitleimagespopup()
	{
		extract($_POST);
		$model = RoomTitleImages::findOne($id);
		return $this->renderAjax('updatetitleimagespopup', ['model' => $model,'id'=>$id]);		
    
	}
	public function actionUpdatetitleimage()
	{
		extract($_POST);
	
		
		$model = new RoomTitleImages;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$roomArr = Yii::$app->request->post('RoomTitleImages');
		
		$roomUpdate = RoomTitleImages::findOne($roomArr['ID']);
		$oldRoomImage = $roomUpdate['title_pic'];
		
		$roomUpdate->attributes = \Yii::$app->request->post('RoomTitleImages');
		$image = UploadedFile::getInstance($model, 'title_pic');
			if($image){
				if(!empty($oldRoomImage)){
					$imagePath =  '../../'.Url::to(['/uploads/titleimages/'. $oldRoomImage]);
					if(file_exists($imagePath)){
						unlink($imagePath);	
					}
				}
				
				$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$image->extension;
				$image->saveAs('uploads/titleimages/' . $imagename);
				$roomUpdate->title_pic = $imagename;
				
			}else{
				$roomUpdate->title_pic = $oldRoomImage;
			}
		if($roomUpdate->validate()){
			$roomUpdate->save();
		}
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'Title Image',
		'text' => 'Title Image Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		return $this->redirect('titleimages');
	}
	public function actionRoomcategoryprice()
	{
		extract($_REQUEST);
        $roomreservationDet = \app\models\RoomReservations::findOne($id);
		$allocatedRooms = AllocatedRooms::find()->where(['category_id' => $id, 'status' => '1'])->asArray()->all();		
		if (!empty($roomreservationDet)) {
			if (!empty($allocatedRooms)) {
				$str =  "<option value=''>Select</option>"; 

	foreach($allocatedRooms as $allocatedRooms) {
		$str .="<option value='".$allocatedRooms['ID']."'>".$allocatedRooms['room_name']."</option>";
	}
} else {
	$str  = "<option>-</option>";
}

			$resp = ['price' => $roomreservationDet['price'] , 'allocatedRooms' => $str ];
			return json_encode($resp); 

		} else {
			return "0";
		}
	}
	public function actionEditroomrespopup()
	{
		extract($_POST);
		$model = Userinformation::findOne($id);
		$roomguests = RoomGuestIdentitiy::find()->where(['reservation_id' => $id])->asArray()->all();
		return $this->renderAjax('updateroomres', ['model' => $model,'id'=>$id, 'roomguests' => $roomguests]);		
		
	}
	public function actionUpdateroomres()
	{
		extract($_POST);
	
		
		$model = new Userinformation;
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
		$roomArr = Yii::$app->request->post('Userinformation');
		$resUpdate = Userinformation::findOne($roomArr['ID']);

		$resUpdate->attributes = \Yii::$app->request->post('Userinformation');
		$resUpdate->booking_time = date('Y-m-d '.$roomArr['booking_time'].':i:s A');
		if($resUpdate->validate()){
			
			$oldGuests =  RoomGuestIdentitiy::find()->where(['reservation_id'=>$roomArr['ID']])->asArray()->all();

			if(count($oldGuests) > 0)
			{
					for($i=0;$i<count($oldGuests);$i++)
					{
						if( ($_POST['guestname_'.$oldGuests[$i]['ID']] != $oldGuests[$i]['guest_name']) ||
						($_POST['guestidname_'.$oldGuests[$i]['ID']] != $oldGuests[$i]['guest_id_name'])
						)
						{
								$foodcategorytypes = RoomGuestIdentitiy::findOne($oldGuests[$i]['ID']);
								$foodcategorytypes->merchant_id = (string)Yii::$app->user->identity->merchant_id;
								$foodcategorytypes->guest_name = $_POST['guestname_'.$oldGuests[$i]['ID']];
								$foodcategorytypes->guest_id_name = $_POST['guestidname_'.$oldGuests[$i]['ID']];
								$foodcategorytypes->updated_by = Yii::$app->user->identity->emp_name;
								$foodcategorytypes->updated_on = date('Y-m-d H:i:s A');
								$foodcategorytypes->save();
						}
					}
			}
			
			$resUpdate->save();
			$this->roomavailblityupdate($resUpdate['room_category'],1,2);
$catModel = AllocatedRooms::findOne($resUpdate['room_alocated']);
			$catModel->status = 1;
			$catModel->save();
		}
		Yii::$app->getSession()->setFlash('success', [
        'title' => 'Room Reservation',
		'text' => 'Room Reservation Updated Successfully',
        'type' => 'success',
        'timer' => 3000,
        'showConfirmButton' => false
    ]);
		return $this->redirect('roomreservationhistory');
	}
	public function roomavailblityupdate($room_cat_id,$rooms_allocated,$type)
	{
		$roomRedDet = RoomReservations::findOne($room_cat_id);
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
		$merchantId = Yii::$app->user->identity->merchant_id;
		$inputArr = ['merchant_id'=>$merchantId, 'message'=>$roomRedDet['category_name']." is crossed the limit",'seen'=>'0'];
		Yii::$app->merchant->addMerchantNotifications($inputArr);
	}

		$sqlUpdate = 'update room_reservations set availability = \''.$availablity.'\' 
		where ID = \''.$roomRedDet['ID'].'\'';
		$resUpdate = Yii::$app->db->createCommand($sqlUpdate)->execute(); 
		
	}
	public function actionRoomsdisplay(){
		extract($_REQUEST);
		$categorylist = \app\models\RoomReservations::find()->where(['merchant_id' => Yii::$app->user->identity->merchant_id])
		->asArray()->all();
		if(!empty($categorylist))
		{
			$category_id = $_REQUEST['category_id'] ?? $categorylist[0]['ID'];
			$rooms = AllocatedRooms::find()->where(['category_id' => $category_id ])
			->asArray()->all();
		}

		return $this->render('roomsdisplay',['categorylist' => $categorylist
		,'rooms' => $rooms , 'category_id' => $category_id ]);
	}
		public function actionUploadtableexcel()
	{
		extract($_POST);
		include('../PHPExcel/Classes/PHPExcel/IOFactory.php');
		$model = new Tablename();
		if (Yii::$app->request->isPost) {
			$file = UploadedFile::getInstance($model,'name');

      if ($file) {
        $filename = 'uploads/tables/' . $file->name;
        $file->saveAs($filename);

        if (in_array($file->extension,array('xls','xlsx'))) {
          $fileType = \PHPExcel_Iofactory::identify ($filename); // the file name automatically determines the type
          $excelReader = \PHPExcel_IOFactory::createReader($fileType);

          $phpexcel = $excelReader->load ($filename)->getsheet (0); // load the file and get the first sheet
          $total_line = $phpexcel->gethighestrow(); // total number of rows
          $total_column = $phpexcel->gethighestcolumn(); // total number of columns
        


		$dataArr = $optionsarray =  array();  
			$worksheetTitle     = $phpexcel->getTitle();
			$highestRow         = $phpexcel->getHighestRow();
			$highestColumn      = $phpexcel->getHighestColumn();
			//echo $highestRow;exit;
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
			$tableArr = Yii::$app->request->post('Tablename');
			$merchant_id = (string)Yii::$app->user->identity->merchant_id;
			
	
			

			if(!empty($dataArr))
			{
				$dataArr = array_values($dataArr);
				for($i=0;$i<count($dataArr);$i++)
				{
					$data[] = [$merchant_id,$dataArr[$i][1],$dataArr[$i][2],'1',$tableArr['section_id'],date('Y-m-d H:i:s')];
				}
				Yii::$app->db
				->createCommand()
				->batchInsert('tablename', ['merchant_id','name','capacity', 'status','section_id','reg_date'],$data)
				->execute();
			}
			Yii::$app->getSession()->setFlash('success', [
				'title' => 'Table',
				'text' => 'Table(s) Created Successfully',
				'type' => 'success',
				//'timer' => 3000,
				'showConfirmButton' => false
			]);
			return $this->redirect('managetable');	
		
	  }
	}	
	}
	}
	public function actionUploadItems()
	{
		extract($_POST);
		include('../PHPExcel/Classes/PHPExcel/IOFactory.php');
		$merchant_id = (string)Yii::$app->user->identity->merchant_id;
		$sectionCat = $dataArr = [];
		$model = new Product;
		if (Yii::$app->request->isPost) {
		    $file = UploadedFile::getInstance($model,'title');
          if ($file) {
                $filename = 'uploads/items/' . $file->name;
                $file->saveAs($filename);
    		    $morefoodsections = 0;
                for($i=2;$i>=0;$i--){
                    $params = ['file' => $file,'sheet' => $i,'filename' =>$filename ];
                    $dataArr = Yii::$app->merchant->getExcelDataAndUpload($params);
                    if($i == 1 ){
                        $foodCategeries = array_column($dataArr,6);
                        $catUpselling = array_column($dataArr,7,6);
                        $fc_params = ['merchant_id'=>$merchant_id,'foodCategeries' => $foodCategeries,'sectionCat' => $sectionCat,'catUpselling' => $catUpselling]; 
                        $resp_fc = Yii::$app->merchant->addFoodCategeries($fc_params);
                        if($resp_fc == 1){
                            $fu_params = ['merchant_id'=>$merchant_id,'dataArr' => $dataArr]; 
                            $fcIdArray = Yii::$app->merchant->addFoodUnits($fu_params);
                            if(!empty($fcIdArray)){
                                $item_params = ['merchant_id'=>$merchant_id,'dataArr' => $dataArr,'fcIdArray' => $fcIdArray]; 
                                $itemReturn = Yii::$app->merchant->addItems($item_params);
                            }
                        }
                    }
                    else if($i == 0 ){
                        $ts_params = ['merchant_id'=>$merchant_id,'dataArr' => $dataArr];
                        $t_section = Yii::$app->merchant->addTableSection($ts_params);
                        if($t_section == 1)
                        {
                            $t_section = Yii::$app->merchant->addSectionPrices($ts_params);
                        }
                    }
                    else if($i == 2){
                        if(!empty($dataArr))
                        {
                            $sectionNames = array_column($dataArr,2);
                            $sectionCat = array_column($dataArr,2,1);
							
							$foodSections = FoodSections::find()
							->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->All();
							
							if(count($foodSections)+count(array_unique($sectionNames)) <= 3 ){
								$fs_params = ['merchant_id'=>$merchant_id,'food_sections' => array_values(array_unique($sectionNames))];    
								Yii::$app->merchant->addFoodSections($fs_params);
							}
							
							
                        }
                    }
                    unset($dataArr);
                }
            }	
	    }
	    		return	$this->redirect('product-list');
	}
	public function actionAcceptandserveorder()
	{
	    $orderid = $_POST['orderid'];
	    $model =Orders::findOne($orderid);
        $tableDetails = Tablename::findOne($model['tablename']);
		$model->orderprocess = $_POST['orderstatus'];

		if(!empty($model['serviceboy_id']) && $_POST['orderstatus'] == '2'){
			$notificaitonarary = array();
			$notificaitonarary['merchant_id'] = $model['merchant_id'];
			$notificaitonarary['serviceboy_id'] = $model['serviceboy_id'];
			$notificaitonarary['order_id'] = (string)$orderid;
			$notificaitonarary['title'] = 'Serve Order';
			$notificaitonarary['message'] = 'Order '.$model['order_id'].' on '.$tableDetails['name'].'-'.$tableDetails->section['section_name'].' is Served';
			$notificaitonarary['ordertype'] = 'serve';
			$notificaitonarary['seen'] = '0';
							
			$serviceBoyNotiModel = new  ServiceboyNotifications;
			$serviceBoyNotiModel->attributes = $notificaitonarary;
			$serviceBoyNotiModel->reg_date = date('Y-m-d H:i:s');
			$serviceBoyNotiModel->mod_date = date('Y-m-d H:i:s');
			$serviceBoyNotiModel->save();
		}
        $model->save();
	
	    return 1;
	}
	
	public function actionSetorderpreparetime()
	{
	    $orderid = $_POST['orderid'];
	    $preptime = $_POST['preptime'];
	    $model = Orders::findOne($orderid);
		$tableDetails = Tablename::findOne($model['tablename']);

        $model->preparetime = $preptime;
		$model->mod_date = date('Y-m-d H:i:s');
        if(!$model->save()){
            print_r($model->getErrors());
        }
		if(!empty($model['serviceboy_id'])){
			$notificaitonarary = array();
			$notificaitonarary['merchant_id'] = $model['merchant_id'];
			$notificaitonarary['serviceboy_id'] = $model['serviceboy_id'];
			$notificaitonarary['order_id'] = (string)$orderid;
			$notificaitonarary['title'] = 'Prepartion of Order';
			$notificaitonarary['message'] = 'Order '.$model['order_id'].' on '.$tableDetails['name'].'-'.$tableDetails->section['section_name'].' as preparing in '.$preptime.' mins';
			$notificaitonarary['ordertype'] = 'preparation';
			$notificaitonarary['seen'] = '0';
							
			$serviceBoyNotiModel = new  ServiceboyNotifications;
			$serviceBoyNotiModel->attributes = $notificaitonarary;
			$serviceBoyNotiModel->reg_date = date('Y-m-d H:i:s');
			$serviceBoyNotiModel->mod_date = date('Y-m-d H:i:s');
			$serviceBoyNotiModel->save();
		}
	
	
	    return 1;
	}
	public function actionCancelreasonpos()
	{
	    extract($_POST);

		$model =Orders::findOne($orderid);
        $model->orderprocess = '3';
        $model->cancel_reason = $cr_reason;
		$model->closed_by = Yii::$app->user->identity->merchant_id;
        $model->save();

		$tabledet = Tablename::findOne($tableid);
		$tabledet->table_status = null;
		$tabledet->current_order_id = 0;
	    $tabledet->save();
	     
	}
	public function actionCounterSettlement()
	{
	    $sdate = isset($_POST['sdate']) ? $_POST['sdate'] : date('Y-m-d');
	    $edate = isset($_POST['edate']) ? $_POST['edate'] : date('Y-m-d');
	    $sql = "select * from (select sum(o.totalamount) totalamount,o.closed_by,date(o.reg_date) orders_date,s.name from orders o
	    inner join serviceboy s on s.ID = o.closed_by
	    where date(o.reg_date) between '".$sdate."' and '".$edate."' and o.paymenttype = 1
	    and  o.closed_by is not null  and o.orderprocess = '4' and o.merchant_id = '".Yii::$app->user->identity->merchant_id."'
	    group by o.closed_by,date(o.reg_date),s.name ) A left join counter_settlement cs on cs.pilot_id = A.closed_by and A.orders_date = date(cs.order_date)";
	    $res = Yii::$app->db->createCommand($sql)->queryAll();
	    return $this->render('counter-settlement',['res'=>$res,'sdate'=>$sdate,'edate'=>$edate]);
	}
	public function actionAddsettlement()
	{
	    $order_date = $_POST['order_date'];
	    $closed_by = $_POST['closed_by'];
	    $total_amount = $_POST['total_amount'];
	    $paid_amount = $_POST['paid_amount'];
	    $sql =  "SELECT * FROM counter_settlement WHERE (date(order_date)='".$order_date."') AND (pilot_id='".$closed_by."')";
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
            $model->order_date = $order_date;
            $model->reg_date = date('Y-m-d H:i:s');
            $model->created_by = Yii::$app->user->identity->ID;
            if(!$model->save()){
                print_r($model->getErrors());
            }
	        echo "1";
	    }
	}
	public function actionBannerdetails(){
		$status = MyConst::TYPE_ACTIVE;
		$bannerdet = Banners::find()
		->where(['merchant_id' => Yii::$app->user->identity->merchant_id])
		->orderBy([
            'ID'=>SORT_DESC
        ])
		->asArray()->all();
		$model = new Banners;
				if ($model->load(Yii::$app->request->post()) ) {
			$MerchantGalleryArr = Yii::$app->request->post('Banners');
			$model->user_id = (string)Yii::$app->user->identity->ID;
			$model->merchant_id = (string)Yii::$app->user->identity->merchant_id;
			$model->reg_date = date('Y-m-d h:i:s');
			$model->status = MyConst::TYPE_ACTIVE;
			$image = UploadedFile::getInstance($model, 'image');
			if($image){
				$imagename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$image->extension;
		        if(!is_dir('../../bannerimage/')){
					mkdir('../../bannerimage/', 0777, true);
				}
				$image->saveAs('../../bannerimage/' . $imagename);
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
			$imagePath =  '../../'.Url::to(['../../bannerimage/'. $bannerDet['image']]);
			if(file_exists($imagePath)){
				unlink($imagePath);	
			}
			\Yii::$app->db->createCommand()->delete('banners', ['id' => $id])->execute();
		}
	}
}