
<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
?>
<header class="page-header">
          </header>
          <section>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Pilot List</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Pilot</button>

				</div>
              </div>


              <div class="card-body">
                <div class="table-responsive">
                    <form method="POST" action="../api/serviceboyprofilepic" enctype="multipart/form-data">   
                <input type="file" name="profilepic" >
                <input type="text" name="userid">
                <input type="submit" value="save">
                </form>
                </div>
              </div>
            </div>
          </div>

        </section>
