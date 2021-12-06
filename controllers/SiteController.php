<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\helpers\Utility;
class SiteController extends Controller
{
public $idty;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
public function beforeAction($action)
{
	$this->idty = Yii::$app->user->identity; 
    $this->enableCsrfValidation = false;
 
  //return true;
  return parent::beforeAction($action);
}
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
			  if(is_null(Yii::$app->user->identity)):
		   $url = Yii::$app->request->baseUrl."/site/login";
             $this->redirect($url);
            Yii::$app->end();
        endif;
        return $this->render('index');
    }
    public function actionTransactiondash()
    {
        return $this->render('transactiondash');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $roleId = Yii::$app->user->identity->emp_role;
             $merchant_id = Yii::$app->user->identity->merchant_id;

$sqlPermissionsArr = 'select * from merchant_permission_role_map mprp inner join merchant_permissions mp 
		on mp.ID = mprp.permission_id where merchant_id = \''.$merchant_id.'\' and mprp.employee_id=\''.$roleId.'\' and permission_status = \'1\'';
$permissionsArr = Yii::$app->db->createCommand($sqlPermissionsArr)->queryAll();

/*	$merchantDet =  \app\models\Merchant::findOne(Yii::$app->user->identity->merchant_id);
	 if(strtotime($merchantDet['subscription_date']) < strtotime(date('Y-m-d'))){
    die('Subscription date is out');
} else */
if($roleId == '0') {
    			$launchUrl = Yii::$app->request->baseUrl.'/merchant/tableplaceorder';
} else if(count($permissionsArr) > 0){
    			$launchUrl = Yii::$app->request->baseUrl.$permissionsArr[0]['process_action'];
}
else{
    $launchUrl = Yii::$app->request->baseUrl.'/merchant/accessforbidden';
}

	
				$oldStock = Utility::ingredientcbtoob();
				if(count($oldStock)>0)
				{
					Yii::$app->db
					->createCommand()
					->batchInsert('ingredient_stock_register', ['merchant_id','ingredient_id','ingredient_name'
					,'opening_stock','stock_in','stock_out','wastage','closing_stock', 'reg_date','created_on'],$oldStock)
					->execute();
				}
	

			
			return $this->redirect($launchUrl);
        }

        $model->password = '';
        return $this->renderpartial('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

	public function actionSignout()
    {

			$url = 'login';	
        Yii::$app->user->logout();
		return $this->redirect($url);
		
	}
    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    	public function actionForgetpassword()
	{
		
$MAILID = 'hello@foodqonline.com';

$MERCHANT_URL = 'https://foodqonline.com/development/foodq-merchant/';
		$model = new \app\models\MerchantEmployee;

        if ($model->load(Yii::$app->request->post()) ) {

        $arr = $_POST['MerchantEmployee'];
        $arr['email'] = $arr['emp_email'];

			$userdetils = \app\models\Merchant::find()->where(['email'=>$arr['emp_email']])->asArray()->One();
            


				if(!empty($userdetils['ID'])){ 

					$headers = 'MIME-Version: 1.0' . "\r\n";

								$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

								$headers .= 'From:  info <'.$MAILID.'>' . " \r\n" .

											'Reply-To:  '.$MAILID.' '."\r\n" .

											'X-Mailer: PHP/' . phpversion();

				 $emailmessage = "Hello ".$userdetils['name'].",

			  <br /><br />

			 Forgot password<br/>

			  Just click following link to change your password<br/>

			  <br /><br />

			  <a href='".$MERCHANT_URL."forgot-password.php?type=".Utility::encrypt('forgotpass')."&id=".Utility::encrypt($userdetils['ID'])."'>Click HERE to change password :)</a>

			  <br /><br />

			  Thanks";

			  $to = $userdetils['email'];

			/*  var_dump(mail($userdetils['email'],'Forgot password mail',$emailmessage,$headers));exit(); */

			if(mail($userdetils['email'],'Forgot password mail',$emailmessage,$headers)){

				if (!filter_var($to, FILTER_VALIDATE_EMAIL) === false) {

				$loginmessage .= " Hi! ".$userdetils['name'].",  we have sent you a link to change your password on this - ".$userdetils['email']." ";

				

				} else {

				$userdetils['email'] = preg_replace('/(?<=.).(?=.*@)/', '*', $userdetils['email']);

				$loginmessage .= " Hi! ".$userdetils['name'].",  we have sent you a link to change your password on this -".$userdetils['email']." ";

				}

			}else{

				$loginmessage .= "Mail failed try again.";

			}

					 

				}else{



				$loginmessage .="Invalid email please use another email.";



			}

			

			
			return $this->refresh();
        }else{
			

		}
		return $this->renderPartial('forgetpassword',['model'=>$model]);
	}
	public function actionWebqrcode(){
	    return $this->renderPartial('qrcode');
	}
	public function actionClosetableorder(){
	    extract($_POST);
	    echo "sad".$_POST['STATUS'];exit;
	    if(isset($_POST['STATUS'])){
	        if($_POST['STATUS'] == 'TXN_SUCCESS'){
	            $orderDet = \app\models\Orders::findOne($_POST['ORDERID']);
	            $tableUpdate = \app\models\Tablename::findOne($orderDet['tablename']);
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
}
