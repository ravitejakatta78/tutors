<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class GoController extends Controller
{
    /**
     * {@inheritdoc}
     */
	public $idty;
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
	    date_default_timezone_set("asia/kolkata");

	    $this->idty = Yii::$app->user->identity;
		$this->enableCsrfValidation = false;

		if(is_null(Yii::$app->user->identity)):
		   $url = Yii::$app->request->baseUrl."/site/login";
             $this->redirect($url);
            Yii::$app->end();
        endif;
    

        parent::beforeAction($action);        
        return $action;
	
    
	}
}
