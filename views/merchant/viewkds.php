<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FOODQ Merchant</title>
	<link rel="shortcut icon" href="<?php echo Url::base(); ?>/favicon.png" type="image/x-icon" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="all,follow">
<meta name="csrf-param" content="_csrf">
 <meta http-equiv="refresh" content="15"/> 
    <link href="<?= Yii::$app->request->baseUrl.'/vendor/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet">
<link href="<?= Yii::$app->request->baseUrl.'/vendor/font-awesome/css/font-awesome.min.css'?>" rel="stylesheet">
<link href="<?= Yii::$app->request->baseUrl.'/css/css/style10.css'?>" rel="stylesheet">
<link href="<?= Yii::$app->request->baseUrl.'/css/css/style_common.css'?>" rel="stylesheet">
<link href="<?= Yii::$app->request->baseUrl.'/css/css/style.default.css'?>" rel="stylesheet">
<link href="<?= Yii::$app->request->baseUrl.'/css/css/dataTables.bootstrap4.min.css'?>" rel="stylesheet">
<link href="<?= Yii::$app->request->baseUrl.'/css/css/gijgo.min.css'?>" rel="stylesheet">
<link href="<?= Yii::$app->request->baseUrl.'/css/css/select2.css'?>" rel="stylesheet">
<link href="<?= Yii::$app->request->baseUrl.'/css/css/custom.css'?>" rel="stylesheet">
<link href="img/favicon.ico" rel="stylesheet">	
<script src="<?= Yii::$app->request->baseUrl.'/js/code.jquery-3.3.1.js'?>"></script>
  </head>
  <body>
	    <div class="page">
<?php
echo \Yii::$app->view->renderFile('@app/views/layouts/_header.php');
?><div class="page-content d-flex align-items-stretch"> 
        <!-- Side Navbar -->
        <nav class="side-navbar kitchen-sidebar">
          <div class="kitchen-menu-list">
          	<?php 		$countSummary = \yii\helpers\ArrayHelper::index($res, null, 'food_category');?>
			<div class="col-md-12">
          		<div class="float-left">
          		<strong>Products</strong>
          	</div>
          	<div class="float-right">
          		<strong>Qty</strong>
          	</div>
          	</div>
          	<div class="clearfix"></div>
          	<?php foreach($countSummary as $foodtypename => $foodsummary) {
			for($c=0;$c<count($countSummary[$foodtypename]);$c++) {
				$foodtypeSummary[$countSummary[$foodtypename][$c]['title_quantity']][$c] =  $countSummary[$foodtypename][$c]['orderCount'];

			}
				?>
			<div class="kitchen-menu-head">
          		<h4 class="text-center"><?= $foodtypename;?></h4>
          	</div>
			<div class="kitchen-menu-body">
          		<ul>
			<?php foreach($foodtypeSummary as $productname => $orderCountArr) { ?>
          	
          			<li class="row">
          				<div class="float-left col-md-10 pl-0 pr-0"><?= $productname; ?></div>
          				<div class="float-right kitchen-item-count"><?= array_sum($foodtypeSummary[$productname])?></div>
          			</li>
          			          		
			<?php } 
			unset($foodtypeSummary); ?>
			
</ul>
          	</div>
			<?php } ?>
          </div>
         
        </nav>
<div class="content-inner ktchn-mainpage">

<header class="page-header" style="padding: 5px 0px !important;">
	<div class="text-right">
		<a href="<?= Url::to(['site/index'])?>"><button class="btn btn-add btn-sm"><i class="fa fa-mail-reply"></i> Back to Home</button></a>
	</div>
</header>

		<section class="col-md-12">
		<div class="row">
		  <?php if(count($tableres) > 0) { 
		  $clr = 0;
		  $color1 = 'color1';
		  $color2 = 'color2';
		  //echo "<pre>";print_r($tabletimeres);exit;
		  foreach($tabletimeres as $tableid => $tabletime) { ?>
		  	<div class="col-md-3">
			<input type="hidden" value="<?= $tableorderidres[$tableid];?>" id="table_order_id_<?= $tableid; ?>">

		  		<div style="cursor: pointer;" class="kitchen-table-head <?php if($clr % 2 == 0) { echo $color1; }else { echo $color2; }  ?> col-md-12" id="<?=$tableid ?>">
		  			
		  			<div class="float-left">
		  				<div class="time"><?= $tabletime;?> <span class="badge badge-dark">Online</span></div>
		  				<div class="tableno">Table: <?= $tableres[$tableid] ?? $tableid ; ?></div>
		  			</div>
		  			<div class="float-right">
		  				<div class="time">Pilot</div>
		  				<div class="tableno">#<?=substr($tableorderidres[$tableid],-6) ?></div>
		  			</div>
		  		</div>
		  		<div class="kitchen-table-body <?php if($clr % 2 == 0) { echo $color1; }else { echo $color2; }  ?> col-md-12">
		  			<?php 
					//echo "<pre>";print_r($resindex[$tableid]);exit;
					for($i=0;$i<count($resindex[$tableid]);$i++) { ?>
					<div class="row" onclick="productDeliver('<?= $resindex[$tableid][$i]['order_product_id'];?>')">
		  				<div class="col-md-2">
		  					<div class="ktchn-qty">
			  					<?= $resindex[$tableid][$i]['orderCount'].'x' ?>
			  				</div>
		  				</div>
		  				<div class="col-md-8">
		  					<div class="ktchn-title">
		  						<?= $resindex[$tableid][$i]['title_quantity'] ;?>
		  					</div>

		  				</div>
						<?php if(!empty($resindex[$tableid][$i]['item_deliver_status'])) { ?>
						<div class="col-md-2" id="<?= $resindex[$tableid][$i]['order_product_id'];?>">
						<i class="fa fa-check color-green"></i>
						</div>							
						<?php } ?>

		  			</div>
					<?php if(count($resindex[$tableid]) - 1 != $i) { ?>
		  			<div class="divider"></div>
					<?php } } ?>
		  			
		  		</div>
		  	</div>
		  <?php 
		  $clr++;
		  } ?> 
		  <div class="clearfix"></div>
		  <?php } ?>
</div>
	
        </section>
<script>
$(window).scroll(function(){
      if ($(this).scrollTop() > 135) {
          $('#task_flyout').addClass('fixed');
      } else {
          $('#task_flyout').removeClass('fixed');
      }
  });

$(".kitchen-table-head").click(function(){
	
	swal({
				title: "Is food prepared ?", 
				//text: "You will be redirected to https://utopian.io", 
				type: "warning",
				confirmButtonText: "Yes!",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
	var id = $("#table_order_id_"+this.id).val();			
	
						var request = $.ajax({
  url: "tableorderstatuschange",
  type: "POST",
  data: {tableId : this.id,chageStatusId:'2',id:id},
}).done(function(msg) {
	location.reload();
});
					} else if (result.dismiss === 'cancel') {
					    
					}
				})
});

function productDeliver(orderProductId){
	swal({
				title: "Is Item prepared ?", 
				//text: "You will be redirected to https://utopian.io", 
				type: "warning",
				confirmButtonText: "Yes!",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
	
	var request = $.ajax({
  url: "tableorderproductdeliver",
  type: "POST",
  data: {orderProductId : orderProductId},
	}).done(function(msg) {
	location.reload();
	});
					} else if (result.dismiss === 'cancel') {
					    
					}
				})					


					
}  

</script><?php
echo \Yii::$app->view->renderFile('@app/views/layouts/_footer.php');
?>
        </div>
      </div>
    </div>
	<div id="yii-debug-toolbar" data-url="/tutor/web/debug/default/toolbar?tag=5e8878934f7e2" style="display:none" class="yii-debug-toolbar-bottom"></div><style>#yii-debug-toolbar-logo{position:fixed;right:31px;bottom:4px}@media print{.yii-debug-toolbar{display:none !important}}.yii-debug-toolbar{font:11px Verdana,Arial,sans-serif;text-align:left;width:96px;transition:width .3s ease;z-index:1000000}.yii-debug-toolbar.yii-debug-toolbar_active:not(.yii-debug-toolbar_animating) .yii-debug-toolbar__bar{overflow:visible;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.yii-debug-toolbar.yii-debug-toolbar_iframe_active:not(.yii-debug-toolbar_animating) .yii-debug-toolbar__resize-handle{display:block;height:4px;cursor:ns-resize;margin-bottom:0;z-index:1000001;position:absolute;left:0;right:0}.yii-debug-toolbar:not(.yii-debug-toolbar_active) .yii-debug-toolbar__bar,.yii-debug-toolbar.yii-debug-toolbar_animating .yii-debug-toolbar__bar{height:40px}.yii-debug-toolbar_active{width:100%}.yii-debug-toolbar_active .yii-debug-toolbar__toggle-icon{-webkit-transform:rotate(0);transform:rotate(0)}.yii-debug-toolbar_position_top{margin:0 0 20px 0;width:100%}.yii-debug-toolbar_position_bottom{position:fixed;right:0;bottom:0;margin:0}.yii-debug-toolbar__bar{position:relative;padding:0;font:11px Verdana,Arial,sans-serif;text-align:left;overflow:hidden;box-sizing:content-box;display:flex;flex-wrap:wrap;background:#fff;background:-moz-linear-gradient(top, white 0%, #f7f7f7 100%);background:-webkit-linear-gradient(top, white 0%, #f7f7f7 100%);background:linear-gradient(to bottom, white 0%, #f7f7f7 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#ffffff", endColorstr="#f7f7f7", GradientType=0);border:1px solid rgba(0,0,0,.11);direction:ltr}.yii-debug-toolbar__bar::after{content:"";display:table;clear:both}.yii-debug-toolbar__view{height:0;overflow:hidden;background:#fff}.yii-debug-toolbar__view iframe{margin:0;padding:10px 0 0;height:100%;width:100%;border:0}.yii-debug-toolbar_iframe_active .yii-debug-toolbar__view{height:100%}.yii-debug-toolbar_iframe_active .yii-debug-toolbar__toggle-icon{-webkit-transform:rotate(90deg);transform:rotate(90deg)}.yii-debug-toolbar_iframe_active .yii-debug-toolbar__external{display:block}.yii-debug-toolbar_iframe_animating .yii-debug-toolbar__view{transition:height .3s ease}.yii-debug-toolbar__block{margin:0;border-right:1px solid rgba(0,0,0,.11);border-bottom:1px solid rgba(0,0,0,.11);padding:4px 8px;line-height:32px;white-space:nowrap}@media(max-width: 767.98px){.yii-debug-toolbar__block{flex-grow:1;text-align:center}}.yii-debug-toolbar__block a{display:inline-block;text-decoration:none;color:#000}.yii-debug-toolbar__block img{vertical-align:middle}.yii-debug-toolbar__block_active,.yii-debug-toolbar__ajax:hover{background:#f7f7f7;background:-moz-linear-gradient(top, #f7f7f7 0%, #e0e0e0 100%);background:-webkit-linear-gradient(top, #f7f7f7 0%, #e0e0e0 100%);background:linear-gradient(to bottom, #f7f7f7 0%, #e0e0e0 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#f7f7f7", endColorstr="#e0e0e0", GradientType=0)}.yii-debug-toolbar__label{display:inline-block;padding:2px 4px;font-size:12px;font-weight:normal;line-height:14px;white-space:nowrap;vertical-align:middle;max-width:100px;overflow-x:hidden;text-overflow:ellipsis;color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,.25);background-color:#737373;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px}.yii-debug-toolbar__label:empty{display:none}a.yii-debug-toolbar__label:hover,a.yii-debug-toolbar__label:focus{color:#fff;text-decoration:none;cursor:pointer}.yii-debug-toolbar__label_important,.yii-debug-toolbar__label_error{background-color:#b94a48}.yii-debug-toolbar__label_important[href]{background-color:#953b39}.yii-debug-toolbar__label_warning,.yii-debug-toolbar__badge_warning{background-color:#f89406}.yii-debug-toolbar__label_warning[href]{background-color:#c67605}.yii-debug-toolbar__label_success{background-color:#217822}.yii-debug-toolbar__label_success[href]{background-color:#356635}.yii-debug-toolbar__label_info{background-color:#0b72b8}.yii-debug-toolbar__label_info[href]{background-color:#2d6987}.yii-debug-toolbar__label_inverse,.yii-debug-toolbar__badge_inverse{background-color:#333}.yii-debug-toolbar__label_inverse[href],.yii-debug-toolbar__badge_inverse[href]{background-color:#1a1a1a}.yii-debug-toolbar__title{background:#f7f7f7;background:-moz-linear-gradient(top, #f7f7f7 0%, #e0e0e0 100%);background:-webkit-linear-gradient(top, #f7f7f7 0%, #e0e0e0 100%);background:linear-gradient(to bottom, #f7f7f7 0%, #e0e0e0 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#f7f7f7", endColorstr="#e0e0e0", GradientType=0)}.yii-debug-toolbar__block_last{width:80px;height:40px;float:left}.yii-debug-toolbar__toggle,.yii-debug-toolbar__external{cursor:pointer;position:absolute;width:30px;height:30px;font-size:25px;font-weight:100;line-height:28px;color:#fff;text-align:center;opacity:.5;filter:alpha(opacity=50);transition:opacity .3s ease}.yii-debug-toolbar__toggle:hover,.yii-debug-toolbar__toggle:focus,.yii-debug-toolbar__external:hover,.yii-debug-toolbar__external:focus{color:#fff;text-decoration:none;opacity:.9;filter:alpha(opacity=90)}.yii-debug-toolbar__toggle-icon,.yii-debug-toolbar__external-icon{display:inline-block;background-position:50% 50%;background-repeat:no-repeat}.yii-debug-toolbar__toggle{right:10px;bottom:4px}.yii-debug-toolbar__toggle-icon{padding:7px 0;width:10px;height:16px;background-image:url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNSIgaGVpZ2h0PSIxNSIgdmlld0JveD0iMCAwIDUwIDUwIj48cGF0aCBmaWxsPSIjNDQ0IiBkPSJNMTUuNTYzIDQwLjgzNmEuOTk3Ljk5NyAwIDAgMCAxLjQxNCAwbDE1LTE1YTEgMSAwIDAgMCAwLTEuNDE0bC0xNS0xNWExIDEgMCAwIDAtMS40MTQgMS40MTRMMjkuODU2IDI1LjEzIDE1LjU2MyAzOS40MmExIDEgMCAwIDAgMCAxLjQxNHoiLz48L3N2Zz4=");transition:-webkit-transform .3s ease-out;transition:transform .3s ease-out;-webkit-transform:rotate(180deg);transform:rotate(180deg)}.yii-debug-toolbar__external{display:none;right:50px;bottom:4px}.yii-debug-toolbar__external-icon{padding:8px 0;width:14px;height:14px;background-image:url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNSIgaGVpZ2h0PSIxNSIgdmlld0JveD0iMCAwIDUwIDUwIj48cGF0aCBmaWxsPSIjNDQ0IiBkPSJNMzkuNjQyIDkuNzIyYTEuMDEgMS4wMSAwIDAgMC0uMzgyLS4wNzdIMjguMTAzYTEgMSAwIDAgMCAwIDJoOC43NDNMMjEuNyAyNi43OWExIDEgMCAwIDAgMS40MTQgMS40MTVMMzguMjYgMTMuMDZ2OC43NDNhMSAxIDAgMCAwIDIgMFYxMC42NDZhMS4wMDUgMS4wMDUgMCAwIDAtLjYxOC0uOTI0eiIvPjxwYXRoIGQ9Ik0zOS4yNiAyNy45ODVhMSAxIDAgMCAwLTEgMXYxMC42NmgtMjh2LTI4aDEwLjY4M2ExIDEgMCAwIDAgMC0ySDkuMjZhMSAxIDAgMCAwLTEgMXYzMGExIDEgMCAwIDAgMSAxaDMwYTEgMSAwIDAgMCAxLTF2LTExLjY2YTEgMSAwIDAgMC0xLTF6Ii8+PC9zdmc+")}.yii-debug-toolbar__switch-icon{margin-left:10px;padding:5px 10px;width:18px;height:18px;background-image:url("data:image/svg+xml;base64,PHN2ZyB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgdmlld0JveD0iMCAwIDUwIDUwIiB2ZXJzaW9uPSIxLjEiPjxwYXRoIGQ9Im00MS4xIDIzYy0wLjYgMC0xIDAuNC0xIDF2MTAuN2wtMjUuNi0wLjFjMCAwIDAtMiAwLTIuOCAwLTAuOC0wLjctMS0xLTAuNmwtMy41IDMuNWMtMC42IDAuNi0wLjYgMS4zIDAgMmwzLjQgMy40YzAuNCAwLjQgMS4xIDAuMiAxLTAuNmwwLTIuOWMwIDAgMjAuOCAwLjEgMjYuNiAwIDAuNiAwIDEtMC40IDEtMXYtMTEuN2MwLTAuNi0wLjQtMS0xLTF6TTkgMjYuOSA5IDI2LjkgOSAyNi45IDkgMjYuOSIvPjxwYXRoIGQ9Im05IDI2LjljMC42IDAgMS0wLjQgMS0xdi0xMC43bDI1LjYgMC4xYzAgMCAwIDIgMCAyLjggMCAwLjggMC43IDEgMSAwLjZsMy41LTMuNWMwLjYtMC42IDAuNi0xLjMgMC0ybC0zLjQtMy40Yy0wLjQtMC40LTEuMS0wLjItMSAwLjZsMCAyLjljMCAwLTIwLjgtMC4xLTI2LjYgMC0wLjYgMC0xIDAuNC0xIDF2MTEuN2MwIDAuNiAwLjQgMSAxIDF6Ii8+PC9zdmc+")}.yii-debug-toolbar__ajax{position:relative}.yii-debug-toolbar__ajax:hover .yii-debug-toolbar__ajax_info,.yii-debug-toolbar__ajax:focus .yii-debug-toolbar__ajax_info{visibility:visible}.yii-debug-toolbar__ajax a{color:#337ab7}.yii-debug-toolbar__ajax table{width:100%;table-layout:auto;border-spacing:0;border-collapse:collapse}.yii-debug-toolbar__ajax table td{padding:4px;font-size:12px;line-height:normal;vertical-align:top;border-top:1px solid #ddd}.yii-debug-toolbar__ajax table th{padding:4px;font-size:11px;line-height:normal;vertical-align:bottom;border-bottom:2px solid #ddd}.yii-debug-toolbar__ajax_info{visibility:hidden;transition:visibility .2s linear;background-color:#fff;box-shadow:inset 0 -10px 10px -10px #e1e1e1;position:absolute;bottom:40px;left:-1px;padding:10px;max-width:480px;max-height:480px;word-wrap:break-word;overflow:hidden;overflow-y:auto;box-sizing:border-box;border:1px solid rgba(0,0,0,.11);z-index:1000001}.yii-debug-toolbar__ajax_request_status{color:#fff;padding:2px 5px}.yii-debug-toolbar__ajax_request_url{max-width:170px;overflow:hidden;text-overflow:ellipsis}
</style><script>(function () {
    'use strict';

    var findToolbar = function () {
            return document.querySelector('#yii-debug-toolbar');
        },
        ajax = function (url, settings) {
            var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            settings = settings || {};
            xhr.open(settings.method || 'GET', url, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'text/html');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200 && settings.success) {
                        settings.success(xhr);
                    } else if (xhr.status !== 200 && settings.error) {
                        settings.error(xhr);
                    }
                }
            };
            xhr.send(settings.data || '');
        },
        url,
        div,
        toolbarEl = findToolbar(),
        toolbarAnimatingClass = 'yii-debug-toolbar_animating',
        barSelector = '.yii-debug-toolbar__bar',
        viewSelector = '.yii-debug-toolbar__view',
        blockSelector = '.yii-debug-toolbar__block',
        toggleSelector = '.yii-debug-toolbar__toggle',
        externalSelector = '.yii-debug-toolbar__external',

        CACHE_KEY = 'yii-debug-toolbar',
        ACTIVE_STATE = 'active',

        animationTime = 300,

        activeClass = 'yii-debug-toolbar_active',
        iframeActiveClass = 'yii-debug-toolbar_iframe_active',
        iframeAnimatingClass = 'yii-debug-toolbar_iframe_animating',
        titleClass = 'yii-debug-toolbar__title',
        blockClass = 'yii-debug-toolbar__block',
        ignoreClickClass = 'yii-debug-toolbar__ignore_click',
        blockActiveClass = 'yii-debug-toolbar__block_active',
        requestStack = [];

    if (toolbarEl) {
        url = toolbarEl.getAttribute('data-url');

        ajax(url, {
            success: function (xhr) {
                div = document.createElement('div');
                div.innerHTML = xhr.responseText;

                toolbarEl.parentNode && toolbarEl.parentNode.replaceChild(div, toolbarEl);

                showToolbar(findToolbar());

                var event;
                if (typeof(Event) === 'function') {
                    event = new Event('yii.debug.toolbar_attached', {'bubbles': true});
                } else {
                    event = document.createEvent('Event');
                    event.initEvent('yii.debug.toolbar_attached', true, true);
                }

                div.dispatchEvent(event);
            },
            error: function (xhr) {
                toolbarEl.innerText = xhr.responseText;
            }
        });
    }

    function showToolbar(toolbarEl) {
        var barEl = toolbarEl.querySelector(barSelector),
            viewEl = toolbarEl.querySelector(viewSelector),
            toggleEl = toolbarEl.querySelector(toggleSelector),
            externalEl = toolbarEl.querySelector(externalSelector),
            blockEls = barEl.querySelectorAll(blockSelector),
            blockLinksEls = document.querySelectorAll(blockSelector + ':not(.' + titleClass + ') a'),
            iframeEl = viewEl.querySelector('iframe'),
            iframeHeight = function () {
                return (window.innerHeight * (toolbarEl.dataset.height / 100) - barEl.clientHeight) + 'px';
            },
            isIframeActive = function () {
                return toolbarEl.classList.contains(iframeActiveClass);
            },
            resizeIframe = function(mouse) {
                var availableHeight = window.innerHeight - barEl.clientHeight;
                viewEl.style.height = Math.min(availableHeight, availableHeight - mouse.y) + "px";
            },
            showIframe = function (href) {
                toolbarEl.classList.add(iframeAnimatingClass);
                toolbarEl.classList.add(iframeActiveClass);

                iframeEl.src = externalEl.href = href;
                iframeEl.removeAttribute('tabindex');

                viewEl.style.height = iframeHeight();
                setTimeout(function () {
                    toolbarEl.classList.remove(iframeAnimatingClass);
                }, animationTime);
            },
            hideIframe = function () {
                toolbarEl.classList.add(iframeAnimatingClass);
                toolbarEl.classList.remove(iframeActiveClass);
                iframeEl.setAttribute("tabindex", "-1");
                removeActiveBlocksCls();

                externalEl.href = '#';
                viewEl.style.height = '';
                setTimeout(function () {
                    toolbarEl.classList.remove(iframeAnimatingClass);
                }, animationTime);
            },
            removeActiveBlocksCls = function () {
                [].forEach.call(blockEls, function (el) {
                    el.classList.remove(blockActiveClass);
                });
            },
            toggleToolbarClass = function (className) {
                toolbarEl.classList.add(toolbarAnimatingClass);
                if (toolbarEl.classList.contains(className)) {
                    toolbarEl.classList.remove(className);
                    [].forEach.call(blockLinksEls, function (el) {
                        el.setAttribute('tabindex', "-1");
                    });
                } else {
                    [].forEach.call(blockLinksEls, function (el) {
                        el.removeAttribute('tabindex');
                    });
                    toolbarEl.classList.add(className);
                }
                setTimeout(function () {
                    toolbarEl.classList.remove(toolbarAnimatingClass);
                }, animationTime);
            },
            toggleStorageState = function (key, value) {
                if (window.localStorage) {
                    var item = localStorage.getItem(key);

                    if (item) {
                        localStorage.removeItem(key);
                    } else {
                        localStorage.setItem(key, value);
                    }
                }
            },
            restoreStorageState = function (key) {
                if (window.localStorage) {
                    return localStorage.getItem(key);
                }
            },
            togglePosition = function () {
                if (isIframeActive()) {
                    hideIframe();
                } else {
                    toggleToolbarClass(activeClass);
                    toggleStorageState(CACHE_KEY, ACTIVE_STATE);
                }
            };

        if (restoreStorageState(CACHE_KEY) === ACTIVE_STATE) {
            var transition = toolbarEl.style.transition;
            toolbarEl.style.transition = 'none';
            toolbarEl.classList.add(activeClass);
            setTimeout(function () {
                toolbarEl.style.transition = transition;
            }, animationTime);
        } else {
            [].forEach.call(blockLinksEls, function (el) {
                el.setAttribute('tabindex', "-1");
            });
        }

        toolbarEl.style.display = 'block';

        window.onresize = function () {
            if (toolbarEl.classList.contains(iframeActiveClass)) {
                viewEl.style.height = iframeHeight();
            }
        };

        toolbarEl.addEventListener("mousedown", function(e) {
            if (isIframeActive() && (e.y - toolbarEl.offsetTop < 4 /* 4px click zone */)) {
                document.addEventListener("mousemove", resizeIframe, false);
            }
        }, false);

        document.addEventListener("mouseup", function(){
            if (isIframeActive()) {
                document.removeEventListener("mousemove", resizeIframe, false);
            }
        }, false);

        barEl.onclick = function (e) {
            var target = e.target,
                block = findAncestor(target, blockClass);

            if (block
                && !block.classList.contains(titleClass)
                && !block.classList.contains(ignoreClickClass)
                && e.which !== 2 && !e.ctrlKey // not mouse wheel and not ctrl+click
            ) {
                while (target !== this) {
                    if (target.href) {
                        removeActiveBlocksCls();
                        block.classList.add(blockActiveClass);
                        showIframe(target.href);
                    }
                    target = target.parentNode;
                }

                e.preventDefault();
            }
        };

        toggleEl.onclick = togglePosition;
    }

    function findAncestor(el, cls) {
        while ((el = el.parentElement) && !el.classList.contains(cls)) ;
        return el;
    }

    function renderAjaxRequests() {
        var requestCounter = document.getElementsByClassName('yii-debug-toolbar__ajax_counter');
        if (!requestCounter.length) {
            return;
        }
        var ajaxToolbarPanel = document.querySelector('.yii-debug-toolbar__ajax');
        var tbodies = document.getElementsByClassName('yii-debug-toolbar__ajax_requests');
        var state = 'ok';
        if (tbodies.length) {
            var tbody = tbodies[0];
            var rows = document.createDocumentFragment();
            if (requestStack.length) {
                var firstItem = requestStack.length > 20 ? requestStack.length - 20 : 0;
                for (var i = firstItem; i < requestStack.length; i++) {
                    var request = requestStack[i];
                    var row = document.createElement('tr');
                    rows.appendChild(row);

                    var methodCell = document.createElement('td');
                    methodCell.innerHTML = request.method;
                    row.appendChild(methodCell);

                    var statusCodeCell = document.createElement('td');
                    var statusCode = document.createElement('span');
                    if (request.statusCode < 300) {
                        statusCode.setAttribute('class', 'yii-debug-toolbar__ajax_request_status yii-debug-toolbar__label_success');
                    } else if (request.statusCode < 400) {
                        statusCode.setAttribute('class', 'yii-debug-toolbar__ajax_request_status yii-debug-toolbar__label_warning');
                    } else {
                        statusCode.setAttribute('class', 'yii-debug-toolbar__ajax_request_status yii-debug-toolbar__label_error');
                    }
                    statusCode.textContent = request.statusCode || '-';
                    statusCodeCell.appendChild(statusCode);
                    row.appendChild(statusCodeCell);

                    var pathCell = document.createElement('td');
                    pathCell.className = 'yii-debug-toolbar__ajax_request_url';
                    pathCell.innerHTML = request.url;
                    pathCell.setAttribute('title', request.url);
                    row.appendChild(pathCell);

                    var durationCell = document.createElement('td');
                    durationCell.className = 'yii-debug-toolbar__ajax_request_duration';
                    if (request.duration) {
                        durationCell.innerText = request.duration + " ms";
                    } else {
                        durationCell.innerText = '-';
                    }
                    row.appendChild(durationCell);
                    row.appendChild(document.createTextNode(' '));

                    var profilerCell = document.createElement('td');
                    if (request.profilerUrl) {
                        var profilerLink = document.createElement('a');
                        profilerLink.setAttribute('href', request.profilerUrl);
                        profilerLink.innerText = request.profile;
                        profilerCell.appendChild(profilerLink);
                    } else {
                        profilerCell.innerText = 'n/a';
                    }
                    row.appendChild(profilerCell);

                    if (request.error) {
                        if (state !== "loading" && i > requestStack.length - 4) {
                            state = 'error';
                        }
                    } else if (request.loading) {
                        state = 'loading'
                    }
                    row.className = 'yii-debug-toolbar__ajax_request';
                }
                while (tbody.firstChild) {
                    tbody.removeChild(tbody.firstChild);
                }
                tbody.appendChild(rows);
            }
            ajaxToolbarPanel.style.display = 'block';
        }
        requestCounter[0].innerText = requestStack.length;
        var className = 'yii-debug-toolbar__label yii-debug-toolbar__ajax_counter';
        if (state === 'ok') {
            className += ' yii-debug-toolbar__label_success';
        } else if (state === 'error') {
            className += ' yii-debug-toolbar__label_error';
        }
        requestCounter[0].className = className;
    }

    /**
     * Should AJAX request to be logged in debug panel
     *
     * @param requestUrl
     * @returns {boolean}
     */
    function shouldTrackRequest(requestUrl) {
        var a = document.createElement('a');
        a.href = requestUrl;

        return a.host === location.host;
    }

    var proxied = XMLHttpRequest.prototype.open;

    XMLHttpRequest.prototype.open = function (method, url, async, user, pass) {
        var self = this;

        if (shouldTrackRequest(url)) {
            var stackElement = {
                loading: true,
                error: false,
                url: url,
                method: method,
                start: new Date()
            };
            requestStack.push(stackElement);
            this.addEventListener('readystatechange', function () {
                if (self.readyState === 4) {
                    stackElement.duration = self.getResponseHeader('X-Debug-Duration') || new Date() - stackElement.start;
                    stackElement.loading = false;
                    stackElement.statusCode = self.status;
                    stackElement.error = self.status < 200 || self.status >= 400;
                    stackElement.profile = self.getResponseHeader('X-Debug-Tag');
                    stackElement.profilerUrl = self.getResponseHeader('X-Debug-Link');
                    renderAjaxRequests();
                }
            }, false);
            renderAjaxRequests();
        }
        proxied.apply(this, Array.prototype.slice.call(arguments));
    };

    // catch fetch AJAX requests
    if (window.fetch) {
        var originalFetch = window.fetch;

        window.fetch = function (input, init) {
            var method;
            var url;
            if (typeof input === 'string') {
                method = (init && init.method) || 'GET';
                url = input;
            } else if (window.URL && input instanceof URL) { // fix https://github.com/yiisoft/yii2-debug/issues/296
                method = (init && init.method) || 'GET';
                url = input.href;
            } else if (window.Request && input instanceof Request) {
                method = input.method;
                url = input.url;
            }
            var promise = originalFetch(input, init);

            if (shouldTrackRequest(url)) {
                var stackElement = {
                    loading: true,
                    error: false,
                    url: url,
                    method: method,
                    start: new Date()
                };
                requestStack.push(stackElement);
                promise.then(function (response) {
                    stackElement.duration = response.headers.get('X-Debug-Duration') || new Date() - stackElement.start;
                    stackElement.loading = false;
                    stackElement.statusCode = response.status;
                    stackElement.error = response.status < 200 || response.status >= 400;
                    stackElement.profile = response.headers.get('X-Debug-Tag');
                    stackElement.profilerUrl = response.headers.get('X-Debug-Link');
                    renderAjaxRequests();

                    return response;
                }).catch(function (error) {
                    stackElement.loading = false;
                    stackElement.error = true;
                    renderAjaxRequests();

                    throw error;
                });
                renderAjaxRequests();
            }

            return promise;
        };
    }

})();
</script>
<script src="<?= Yii::$app->request->baseUrl.'/vendor/popper.js/umd/popper.min.js'?>"></script>
<script src="<?= Yii::$app->request->baseUrl.'/vendor/bootstrap/js/bootstrap.min.js'?>"></script>
<script src="<?= Yii::$app->request->baseUrl.'/vendor/jquery.cookie/jquery.cookie.js'?>"></script>
<script src="<?= Yii::$app->request->baseUrl.'/vendor/jquery-validation/jquery.validate.min.js'?>"></script>
<script src="<?= Yii::$app->request->baseUrl.'/js/dataTables.min.js'?>"></script>
<script src="<?= Yii::$app->request->baseUrl.'/js/jquery.dataTables.min.js'?>"></script>
<script src="<?= Yii::$app->request->baseUrl.'/js/dataTables.bootstrap4.min.js'?>"></script>
<script src="<?= Yii::$app->request->baseUrl.'/js/bootstrap-multiselect.min.js'?>"></script>
<script src="<?= Yii::$app->request->baseUrl.'/js/gijgo.min.js'?>"></script>
<script src="<?= Yii::$app->request->baseUrl.'/js/select2.min.js'?>"></script>
<script src="<?= Yii::$app->request->baseUrl.'/js/front.js'?>"></script>
<script src="<?= Yii::$app->request->baseUrl.'/js/custom.js'?>"></script>	
<script src="<?= Yii::$app->request->baseUrl.'/js/sweetalert2.all.min.js'?>"></script>	
	
  </body>
</html>
