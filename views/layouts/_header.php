<?php 
use yii\helpers\Html;
use yii\helpers\Url;
$merchant_id = Yii::$app->user->identity->merchant_id;
$sqlMerchantNotifications = 'select * from merchant_notifications where merchant_id=\''.$merchant_id.'\'
 and seen=\'0\' order by created_on desc limit 5 ';
$merchantNotifications = Yii::$app->db->createCommand($sqlMerchantNotifications)->queryAll();
$merchantDet = \app\models\Merchant::findOne($merchant_id);
?>
<header class="header">
        <nav class="navbar">
          <!-- Search Box-->
          <div class="search-box">
            <button class="dismiss"><i class="icon-close"></i></button>
            <form id="searchForm" action="#" role="search">
              <input type="search" placeholder="What are you looking for..." class="form-control">
            </form>
          </div>
          <div class="container-fluid">
            <div class="navbar-holder d-flex align-items-center justify-content-between">
              <!-- Navbar Header-->
              <div class="navbar-header">
                <!-- Navbar Brand --><a href="<?= \yii\helpers\Url::to('../merchant/merchantdashboard'); ?>" class="navbar-brand d-none d-sm-inline-block">
                  <div class="brand-text d-none d-lg-inline-block">
				  <img src="<?= Yii::$app->request->baseUrl.'/img/superpilot-1.png';?>" style="max-height:3.2rem;" > </div>
                  <div class="brand-text d-none d-sm-inline-block d-lg-none"><strong>HM</strong></div></a>
                <!-- Toggle Button--><a id="toggle-btn" href="#" class="menu-btn active"><span></span><span></span><span></span></a>
              </div>
              <!-- Navbar Menu -->
              <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                <!-- Search-->
                <li class="nav-item d-flex align-items-center"><a id="search" href="#"><i class="icon-search"></i></a></li>
                <li class="nav-item d-flex">
                <h1 class="h4 mt-1"><a href="<?= \yii\helpers\Url::to('../merchant/updatemerchatprofile'); ?>" style="text-decoration: none;"><i class="fa fa-user-circle-o" style="font-size:24"></i>
 <?= $merchantDet['name']; ?></a></h1>

                </li>
                <!-- Notifications-->
                <li class="nav-item dropdown"> 
                    <a id="notifications" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link">
                        <i class="fa fa-bell-o"></i>
                        <span class="badge bg-red badge-corner"><?= count($merchantNotifications); ?></span>
                    </a>
                  <ul aria-labelledby="notifications" class="dropdown-menu" style="max-height: 300px; width: 500px; overflow-y: auto;  overflow-x: hidden; min-width: 210px;">
                      <?php foreach($merchantNotifications as $merchantNoti){ ?>
                          <li>
                            <a rel="nofollow" href="#" class="dropdown-item"> 
                                <div class="notification" style="width:300px;">
                                    <div class="notification-content">
                                        <i class="fa fa-envelope bg-green"></i>
                                        <?= $merchantNoti['message']?>
                                    </div>
                                    <div class="notification-time">
                                        <small><?php // time_elapsed_string(strtotime($merchantNoti['created_on']))?></small>
                                    </div>
                                </div>
                            </a>
                        </li>
                      <?php } ?>

                       <li>
                           <a rel="nofollow" href="#" class="dropdown-item all-notifications text-center"> 
                               <strong>view all notifications</strong>
                           </a>
                       </li>
                  </ul>
                </li>
                <!-- Messages                        -->
                
                <!-- Languages dropdown    -->
                <!--      -->
                <li class="nav-item"><a href="<?php echo Url::to(['site/signout']); ?>" data-method="post" class="nav-link logout"> <span class="d-none d-sm-inline">Logout</span><i class="fa fa-sign-out"></i></a></li>
              </ul>
            </div>
          </div>
        </nav>
      </header>
