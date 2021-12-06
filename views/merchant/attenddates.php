
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
                <h3 class="h4 col-md-6 pl-0 tab-title">Employee Attendance</h3>
				<div class="col-md-6 text-right pr-0">
				<a href="attendentview" target="_blank"><button type="button" class="btn btn-add btn-xs" id="myBtn" ><i class="fa fa-plus mr-1"></i> Add Attendance</button></a>

				</div>
              </div>


              <div class="card-body">
                <div class="table-responsive">   
<form action="saveempattendance" method="POST" >                
				<table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                    	<th>S.No</th>
						<th>Attendance Date</th>
						<th>Action</th>
					  </tr>
                    </thead>
					<tbody>
						<?php $x=1; 
							foreach($attendedDate as $key=>$value){
						?>
                            <tr>
                                <td><?= $x;?></td>
                                <td><?php echo $value; ?></td>
                                <td class="icons"><a  onclick="editattendance('<?= $value?>');"><span class="fa fa-pencil"></span></a></td>
							</tr>			
                                                	<?php $x++; }?>
                    </tbody>
                  </table>

				</form>
                </div>
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
function editattendance(attend_date) 
{
  
    var form=document.createElement('form');
    form.setAttribute('method','post');
    form.setAttribute('action','editattendance');
    form.setAttribute('target','_blank');

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "attend_date");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", attend_date);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();    
	
}
</script>