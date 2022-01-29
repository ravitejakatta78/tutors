<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FOODQ Merchant</title>
    <link rel="shortcut icon" href="<?php echo Url::base(); ?>/favicon.png" type="image/x-icon" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="all,follow">
<?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
	<script src="<?= Yii::$app->request->baseUrl.'/js/code.jquery-3.3.1.js'?>"></script> 
<!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>-->

  </head>
  <body>
	<?php $this->beginBody() ?>
    <div class="page">
<?php
echo \Yii::$app->view->renderFile('@app/views/layouts/_header.php');
?>

<div class="page-content d-flex align-items-stretch"> 
<?php
echo \Yii::$app->view->renderFile('@app/views/layouts/_sidebar.php');
?>
<div class="content-inner">
            <?= $content ?>
<?php
echo \Yii::$app->view->renderFile('@app/views/layouts/_footer.php');
?>
        </div>
      </div>
    </div>
	<?php   $this->endBody() ?>
	<script>
	$(document).ready(function(){
	
	$('select').select2();

	  	toastr.options = {
				'closeButton': true,
				'debug': false,
				'newestOnTop': false,
				'progressBar': false,
				'positionClass': 'toast-bottom-right',
				'preventDuplicates': false,
				'showDuration': '1000',
				'hideDuration': '1000',
				'timeOut': '5000',
				'extendedTimeOut': '1000',
				'showEasing': 'swing',
				'hideEasing': 'linear',
				'showMethod': 'fadeIn',
				'hideMethod': 'fadeOut',
			}
	setInterval(function(){get_fb();}, 10000);
		//toastr.success('New Order #0010');
  
	});
	function get_fb(){
	
    var request = $.ajax({
    url: "checkneworder",
    type: "POST",
    }).done(function(msg) {
		//alert(msg);
					 data = JSON.parse(msg);
					 for(var i=0;i<data.length;i++){
							//	toastr.success(data[i]['name'] +' has got new order');
								toastr.success('<p onclick="placeOrder(\''+data[i]['ID']+'\',\''+data[i]['name']+'\',\''+data[i]['current_order_id']+'\')">'+data[i]['name'] +' has got new order</p>');
								var audio = new Audio("http://superpilot.in/dev/tutors/web/sounds/notification.mp3");
                				audio.play();
					 }


        });

    }name
function taketoorder(){
  location.replace("tableorder")


}
	</script>
  </body>
</html>
<?php $this->endPage() ?>
