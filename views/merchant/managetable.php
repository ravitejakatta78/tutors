<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
$actionId = Yii::$app->controller->action->id;
?>
<header class="page-header">
            <?php 
foreach (Yii::$app->session->getAllFlashes() as $message) {
    echo SweetAlert::widget([
        'options' => [
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            'text' => (!empty($message['text'])) ? Html::encode($message['text']) : 'Text Not Set!',
            'type' => (!empty($message['type'])) ? $message['type'] : SweetAlert::TYPE_INFO,
            //'timer' => (!empty($message['timer'])) ? $message['timer'] : 4000,
            'showConfirmButton' =>  (!empty($message['showConfirmButton'])) ? $message['showConfirmButton'] : true
        ]
    ]);
}
?>
          </header>
          <section>
              <?= \Yii::$app->view->renderFile('@app/views/merchant/_managetable.php',['actionId'=>$actionId]); ?>
          <div class="col-lg-12">
            <div class="card">
              
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Manage Spots</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal1"><i class="fa fa-plus mr-1"></i> Add Excel</button>
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Spot</button>
				</div>
              </div>
              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
						<th>S No</th>
						<th>Spot Name</th> 
						<th>Capacity</th> 
						<th>Section Name</th>
						<th>Download QR Code</th>
						<th>Multi QR Code</th>						
						<th>Status</th>             
						<th>Action</th>
                      </tr>
                    </thead>
		    <tbody>
								<?php $x=1; 
									foreach($tableDet as $productlist){
								?>
                                  <tr>
                                 	<td><?php echo $x;?></td>                     
									<td><?php echo $productlist['name'];?></td>  
									<td><?php echo $productlist['capacity'];?></td>
									<td><?php echo Utility::sectiontype_value($productlist['section_id']);?></td> 
									<td class="icons"><a class="label label-xs label-warning " <?php if(!empty($merchantdetails['qrlogo'])){?>  
									href="../../../test.php?table=<?= $productlist['ID'];?>&tablename=<?= $productlist['name'];?>&userid=<?= $merchantdetails['ID']; ?>&qrlogo=<?= $merchantdetails['qrlogo']; ?>&qrtype=old"	<?php }?> target="_blank" ><span class="fa fa-download"></span></a>
									
									<?php if(empty($productlist['qr_path'])) { ?>
									
									<a class="label label-xs label-warning " <?php if(!empty($merchantdetails['qrlogo'])){?>  
									onclick="qrdownload('<?= $productlist['ID'];?>','<?= $productlist['name'];?>','<?= $merchantdetails['ID']; ?>','<?= $merchantdetails['qrlogo']; ?>')" 
									
									<?php } ?>  ><span class="fa fa-download"></span></a>
									<?php }else{ ?>
										<a class="label label-xs label-warning " <?php if(!empty($merchantdetails['qrlogo'])){?>  
									onclick="qrpreview('<?= $productlist['ID'];?>','<?= $productlist['name'];?>','<?= $merchantdetails['ID']; ?>','<?= $merchantdetails['qrlogo']; ?>','<?= $productlist['qr_path'];?>')"	<?php }?> target="_blank" ><span class="fa fa-download"></span></a>
									<?php } ?>
									</td>		
<td class="icons"><a class="label label-xs label-warning " <?php if(!empty($merchantdetails['qrlogo'])){?>  
									href="../../../test.php?table=<?= $productlist['ID'];?>&tablename=<?= $productlist['name'];?>&userid=<?= $merchantdetails['ID']; ?>&qrlogo=<?= $merchantdetails['qrlogo']; ?>&qrtype=new"	<?php }?> target="_blank" ><span class="fa fa-download"></span></a>
</td>									
									<td><label class="switch">	
									  <input type="checkbox" <?php if($productlist['status']=='1'){ echo 'checked';}?> onChange="changestatus('tablename',<?php echo $productlist['ID'];?>);">	
									  <span class="slider round"></span>
										</label>					
									</td>
                                    <td  class="icons">
									<a onclick="edittablepopup('<?php echo $productlist['ID']; ?>')"  ><span class="fa fa-pencil"></span></a>
									<a  onClick="deletetable('<?=$productlist['ID'] ?>')"   ><span class="fa fa-trash"></span></a>
									</td>
                                                                  </tr>
									<?php $x++; }?>
                       </tbody>
                  </table>
		  
                </div>
              </div>
            </div>
          </div>

<div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add Spot</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
				<?php	$form = ActiveForm::begin([
    		'id' => 'add-table-form',
		'options' => ['class' => 'form-horizontal','wrapper' => 'col-xs-12','onsubmit' => 'validateSpot()'],
        	'layout' => 'horizontal',
			 'fieldConfig' => [
        'horizontalCssClasses' => [
            
            'offset' => 'col-sm-offset-0',
            'wrapper' => 'col-sm-12 pl-0 pr-0',
        ],
		]
		]) ?>

		<div class="form-group row">
	   <label class="control-label col-md-4">Section Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'section_id')
				  ->dropdownlist(\yii\helpers\ArrayHelper::map(\app\models\Sections::find()
				  ->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->all(), 'ID', 'section_name')
				  ,['prompt'=>'Select'])->label(false); ?>
	   </div></div>
			   	<table id="tblAddRow" class="table table-bordered table-striped">
    <thead>
        <tr>
			<th>Spot Name</th>
			<th>Capacity</th>
			<th>Action</th>
        </tr>
    </thead>
    <tbody>
                <tr>
            <td>
                <input type="text"  name="tablename[]" class="form-control" required/>
            </td>
            
			<td>
                <input type="text" name="capacity[]" class="form-control" required>
            </td>
            
        </tr>
    </tbody>
</table>

   <div class="modal-footer">
       <button id="btnAddRow" class="btn btn-add btn-xs" type="button">
    Add Row
</button>
		<?= Html::submitButton('Add Spot', ['class'=> 'btn btn-add btn-hide']); ?>
      </div> 
		<?php ActiveForm::end() ?>
		
	    </div>
        
        
        
      </div>
    </div>
  </div>
  
   <div class="modal fade" id="myModal1">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add Spot</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
				<?php	$form = ActiveForm::begin([
    		'id' => 'add-table-form',
			'action'=>'uploadtableexcel',
		'options' => ['class' => 'form-horizontal','wrapper' => 'col-xs-12',],
        	'layout' => 'horizontal',
			 'fieldConfig' => [
        'horizontalCssClasses' => [
            
            'offset' => 'col-sm-offset-0',
            'wrapper' => 'col-sm-12 pl-0 pr-0',
        ],
		]
		]) ?>

		
		<div class="form-group row">
		<label class="control-label col-md-4">Capacity</label>
		<div class="col-md-8">
		 <?= $form->field($model, 'name')->fileinput(['class' => 'form-control'])->label(false); ?>
		</div>
		</div>
		<div class="form-group row">
	   <label class="control-label col-md-4">Section Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'section_id')
				  ->dropdownlist(\yii\helpers\ArrayHelper::map(\app\models\Sections::find()
				  ->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->all(), 'ID', 'section_name')
				  ,['prompt'=>'Select'])->label(false); ?>
	   </div></div>
		
   <div class="modal-footer">
		<?= Html::submitButton('Add Spot', ['class'=> 'btn btn-add btn-hide']); ?>
      </div> 
		<?php ActiveForm::end() ?>
		
	    </div>
        
        
        
      </div>
    </div>
  </div>

  
    <div id="edittable" class="modal fade" role="dialog">
<div class="modal-dialog " >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Spot</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="edittablebody">
		
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
    function validateSpot()
    {
        var user_input_value;
        var err_value = 0
        
        $('#add-table-form').find('input').each(function(){
            if($(this).prop('required')){
                user_input_value  = $(this).val(); // jQuery
                if(user_input_value == ''){
                    if(err_value == 0){
                    }
                    err_value = err_value + 1;
                }else{

                }
            }
        });

        if(err_value > 0){
            return false;
        }
    }
function deletetable(id){
//	var res = confirm('Are you sure want to delete??')
		    swal({
				title: "Are you sure want to delete??", 
				type: "warning",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
					    var request = $.ajax({
						  url: "deletetable",
						  type: "POST",
						  data: {id : id},
						}).done(function(msg) {
							
							location.reload();
						});
					}
				});

}
function qrdownload(tableid,tablename,userid,qrlogo){


 var request = $.ajax({
						  url: "qrdownload",
						  type: "POST",
						  data: {tableid : tableid,userid:userid},
						}).done(function(msg) {
							alert(msg);
							location.reload();
						});


}
function qrpreview()
{
	
}

function qrpreview(tableid,tablename,userid,qrlogo,qrpath){


        var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','testqr');
        form.setAttribute('target','_blank');

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "tableid");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", tableid);
    form.appendChild(hiddenField);
	
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "tablename");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", tablename);
    form.appendChild(hiddenField);	

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "userid");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", userid);
    form.appendChild(hiddenField);	
	
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "qrlogo");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", qrlogo);
    form.appendChild(hiddenField);		

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "qrpath");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", qrpath);
    form.appendChild(hiddenField);		
	
    document.body.appendChild(form);
    form.submit();    


}
function addrow() {
	     var lastRow = $('#tblAddRows tbody tr:last').html();
    //alert(lastRow);
    $('#tblAddRows tbody').append('<tr>' + lastRow + '</tr>');
    $('#tblAddRows tbody tr:last input').val('');

}

$(document).on("click", "i[name=deleterow]", function(e) {
	 var lenRow = $('#tblAddRows tbody tr').length;
    e.preventDefault();
    if (lenRow == 1 || lenRow <= 1) {
        alert("Can't remove all row!");
    } else {
        $(this).parents('tr').remove();
    }
});
$(document).on("click", "input[id=checkedAll]", function(e) {
	e.preventDefault();
    $(this).closest('#tblAddRow').find('td input:checkbox').prop('checked', this.checked);
});

// Add button Delete in row

$('#tblAddRow tbody tr')
    .find('td')
    //.append('<input type="button" value="Delete" class="del"/>')
    .parent() //traversing to 'tr' Element
    .append('<td><a href="#" class="delrow"><i class="fa fa-trash border-red text-red"></i></a></td>');

// For select all checkbox in table
$('#checkedAll').click(function (e) {
	//e.preventDefault();
    $(this).closest('#tblAddRow').find('td input:checkbox').prop('checked', this.checked);
});

// Add row the table
$('#btnAddRow').on('click', function() {
    var lastRow = $('#tblAddRow tbody tr:last').html();
    //alert(lastRow);
    $('#tblAddRow tbody').append('<tr>' + lastRow + '</tr>');
    $('#tblAddRow tbody tr:last input').val('');
});


// Delete row on click in the table
$('#tblAddRow').on('click', 'tr a', function(e) {
    var lenRow = $('#tblAddRow tbody tr').length;
    e.preventDefault();
    if (lenRow == 1 || lenRow <= 1) {
        alert("Can't remove all row!");
    } else {
        $(this).parents('tr').remove();
    }
});
</script>