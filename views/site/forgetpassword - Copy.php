<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;
use yii\bootstrap\ActiveForm;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
  <head>
<title>Forget Password</title>
    <?php $this->head() ?>
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body>
	<?php $this->beginBody() ?>
    <div class="page login-page">
      <div class="container d-flex align-items-center">
        <div class="form-holder has-shadow">
          <div class="row">
            <!-- Logo & Information Panel-->
            <div class="col-lg-6">
              <div class="info d-flex align-items-center">
                <div class="content">
                  <div class="logo">
                    <h1>Dashboard</h1>
                  </div>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                </div>
              </div>
            </div>
            <!-- Form Panel    -->
            <div class="col-lg-6 bg-white">
              <div class="form d-flex align-items-center">
                <div class="content">
               <!--   <form method="post" class="form-validate">-->
	<?php $form = ActiveForm::begin([
        'id' => 'forget-form',
        'layout' => 'horizontal',
	'options' => [
                'class' => 'form-validate',
         ],
	'fieldConfig' => [
		'template' => "{label}\n{input}\n{hint}\n{error}"
    	],

    ]); ?>
                    <div class="form-group">

				      <?= $form->field($model, 'email')->textInput(['class' => 'input-material','placeholder'=>'Email'])->label(false); ?>

                    </div>
<?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    <!-- This should be submit button but I replaced it with <a> for demo purposes-->
<!--                  </form>-->
				    <?php ActiveForm::end(); ?>
<a href="<?= Url::to(['site/login']); ?>" class="forgot-pass">Login Here</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="copyrights text-center">
        <p>Design by <a href="https://bootstrapious.com/p/admin-template" class="external">Bootstrapious</a>
          <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
        </p>
      </div>
    </div>

	<?php   $this->endBody() ?>
  </body>
</html>
<?php $this->endPage() ?>
