
<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
$actionId =  Yii::$app->controller->action->id;
?>
<header class="page-header">
                    <?php 
foreach (Yii::$app->session->getAllFlashes() as $message) {
    echo SweetAlert::widget([
        'options' => [
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            'text' => (!empty($message['text'])) ? Html::encode($message['text']) : 'Text Not Set!',
            'type' => (!empty($message['type'])) ? $message['type'] : SweetAlert::TYPE_INFO,
            'timer' => (!empty($message['timer'])) ? $message['timer'] : 4000,
            'showConfirmButton' =>  (!empty($message['showConfirmButton'])) ? $message['showConfirmButton'] : true
        ]
    ]);
}
?>
          </header>
          <section>

          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Role List</h3>
				<div class="col-md-6 text-right pr-0">

				</div>
              </div>


              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                        <th>S No</th>
						<th>Role</th>
						<th>Permission Edit</th>
				   
                      </tr>
                    </thead>
					<tbody>
						<?php $x=1; 
							foreach($res as $res){
						?>
                            <tr>
                                <td><?php echo $x; ?></td>
                                <td><?php echo $res['role_name']; ?></td>
								
								<td class="icons">
									<a onclick="updatepermission('<?= $res['ID'];?>')"><span class="fa fa-pencil"></span></a>
								</td>
							</tr>			
                                                	<?php $x++; }?>
                    </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>

  

        </section>
		<?php
$script = <<< JS
 
   $('#example').DataTable();


JS;
$this->registerJs($script);
?>
<script>
function updatepermission(id)
{
	var form=document.createElement('form');
      
		form.setAttribute('method','post');
        form.setAttribute('action','assignrolepermission');
       // form.setAttribute('target','_blank');

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "id");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", id);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();    

}
</script>
