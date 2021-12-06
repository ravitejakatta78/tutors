
<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
$actionId =  Yii::$app->controller->action->id;
?>
<style>
#notfound {
    position: relative;
    height: 100vh;
}
#notfound .notfound {
    position: absolute;
    left: 50%;
    top: 50%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
}
.notfound {
    max-width: 460px;
    width: 100%;
    text-align: center;
    line-height: 1.4;
}
.notfound .notfound-404 {
    position: relative;
    width: 180px;
    height: 180px;
    margin: 0px auto 50px;
}
.notfound {
    text-align: center;
    line-height: 1.4;
}
.notfound .notfound-404 > div:first-child {
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    background: #ffa200;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
    border: 5px dashed #000;
    border-radius: 5px;
}
.notfound .notfound-404 > div:first-child::before {
    content: '';
    position: absolute;
    left: -5px;
    right: -5px;
    bottom: -5px;
    top: -5px;
    -webkit-box-shadow: 0px 0px 0px 5px rgba(0, 0, 0, 0.1) inset;
    box-shadow: 0px 0px 0px 5px rgba(0, 0, 0, 0.1) inset;
    border-radius: 5px;
}
.notfound .notfound-404 h1 {
    color: #000;
    font-weight: 700;
    margin: 0;
    font-size: 90px;
    position: absolute;
    top: 17%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    left: 50%;
    text-align: center;
    height: 40px;
    line-height: 40px;
}
.notfound h2 {
    font-size: 33px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 7px;
}
.notfound p {
    font-size: 16px;
    color: #000;
    font-weight: 400;
}
</style>
<header class="page-header">
  
          </header>
          <section>
		      
			<div id="notfound" style="margin-top:-50px;">
<div class="notfound">
<div class="notfound-404">
<div></div>
<h1><img src="https://s3.amazonaws.com/lintel-blogs-static-files/wp-content/uploads/2019/10/12061853/lock-logo-512x510.png" style="width:150px;"></h1>
</div>
<h4>You do not have access to this page.</h4>
<h4>Please Contact administrator for furthur details.</h4>

</div>
</div>

        </section>
		<?php
$script = <<< JS
 
   


JS;
$this->registerJs($script);
?>
