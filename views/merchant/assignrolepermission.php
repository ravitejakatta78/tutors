
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
                <h3 class="h4 col-md-6 pl-0 tab-title">Role Permission</h3>
				<div class="col-md-6 text-right pr-0">

				</div>
              </div>


              <div class="card-body">
			  <form action="saverolepermissions" method="POST" > 
                <div class="table-responsive" style="max-height:500px;overflow-y:auto;display:inline-block;">  
				
<input type="hidden" value="<?= $role_id;?>" name="role_id">				
				<table id="example" class="table table-striped table-bordered " ">
                    <thead>
                      <tr>
                        <th><input type="checkbox" id="checkedAll"/></th>
						<th>Permission Name</th>
					  </tr>
                    </thead>
					<tbody>
						<?php $x=1; 
							foreach($res as $res){
						?>
                            <tr>
                                <td> <input name="ckcPerms[]" class="individualcheck" type="checkbox" value="<?= $res['ID']; ?>" <?php if($res['permission_status'] == '1') { echo "checked";  } ?> /></td>
                                <td><?php echo  $res['process_name']; ?></td>
                              
							</tr>			
                                                	<?php $x++; }?>
                    </tbody>
                  </table>
				
              	</div>
				<div class="text-right mt-4 col-md-12">
				<button type="submit" class="btn btn-add">Add Permissions</button>
				</div>
				</form>
              </div>
            </div>
          </div>

  
        </section>
		<?php
$script = <<< JS
 
   $('#example').DataTable({
        "paging":   false,
      } );

// For select all checkbox in table
$('#checkedAll').click(function (e) {
	//e.preventDefault();
    $(this).closest('#example').find('td input:checkbox').prop('checked', this.checked);
});
$('.individualcheck').click(function (e) {

	$("#checkedAll").prop("checked", false);
});
JS;
$this->registerJs($script);
?>

<script>

</script>