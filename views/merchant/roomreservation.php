
<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
$actionId = Yii::$app->controller->action->id;


?>
<script src="<?= Yii::$app->request->baseUrl.'/js/typeahead.js'?>"></script>
<style>
#tblAddRow {
    margin: 2rem;
}
</style>
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
    <div class="loading" style="display:none">
  		<img src="<?php echo Url::base(); ?>/img/load.gif" >
  	</div>
          <section>
          <?= \Yii::$app->view->renderFile('@app/views/merchant/_roomreservations.php',['actionId'=>$actionId]); ?>

          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Rooms Availability</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"
				><i class="fa fa-plus mr-1"></i> Add Room Category</button>

				</div>
              </div>


              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example1" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Category Name</th>
                        <th>Category Pic</th>
                        <th>Price</th>
                        <th>Availability</th>
                        <th>Availability Alert</th>
						<th>Action</th>
                      </tr>
                    </thead>
		    <tbody>
		        <?php $x=1;
		            foreach($roomModel as $roomreservation){ ?>
							
                                  <tr>
                                 	<td><?php echo $x;?></td>
                                 	<td><?php echo $roomreservation['category_name']; ?></td>
                                	<td><img src="<?php echo  Yii::$app->request->baseUrl.'/uploads/roomcategoryimages/'. $roomreservation['category_pic'];?>" alt="" class="img-table dash-icon" style="height:50px"/></td> 
                                 	<td><?php echo $roomreservation['price']; ?></td>
                                 	<td><?php echo $roomreservation['availability']; ?></td>
                                 	<td><?php echo $roomreservation['availability_alert']; ?></td>
                    <td class="icons"><a title="Room - Edit" onclick="editroompopup('<?= $roomreservation['ID'];?>')"><span class="fa fa-pencil"></span></a> 
                    					<a title="Room - Delete" onClick="deleteroom('<?= $roomreservation['ID'] ?>')"><span class="fa fa-trash"></span></a>
                    </td>
                            </tr>
                            <?php $x++; } ?>
                       </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>

<div class="modal" id="myModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add Room Category</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
		<?php	$form = ActiveForm::begin([
    		'id' => 'food-categery-form',
		'options' => ['class' => 'form-horizontal','wrapper' => 'col-xs-12',],
        	'layout' => 'horizontal',
			 'fieldConfig' => [
        'horizontalCssClasses' => [
            
            'offset' => 'col-sm-offset-0',
            'wrapper' => 'col-sm-12 pl-0 pr-0',
        ],
		]
		]) ?>
<div class="row">	
<div class="col-md-6">	

	   <div class="form-group row">
	   <label class="control-label col-md-4">Category Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'category_name')->textinput(['class' => 'form-control title','autocomplete'=>'off','placeholder'=>'Category Name'])->label(false); ?>
	   </div>
	   </div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Price</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'price')->textinput(['class' => 'form-control','placeholder'=>'Price'])->label(false); ?>
	   </div></div>
</div>
<div class="col-md-6">
	   	   <div class="form-group row">
	   <label class="control-label col-md-4">Availability</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'availability')->textinput(['class' => 'form-control','placeholder'=>'availability'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Availability Alert</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'availability_alert')->textinput(['class' => 'form-control','placeholder'=>'availability_alert'])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Upload</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'category_pic')->fileinput(['class' => 'form-control'])->label(false); ?>
	   </div></div>
	   </div>
	   <table id="tblAddRow" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Room Name</th>
			<th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>

            
			<td>
                <input name="roomnames[]" class="form-control">
            </td>
            
        </tr>
       
    </tbody>
</table>

	   </div>
	   </div>
	  
	  
	   <div class="modal-footer">
	
	   <button id="btnAddRow" class="btn btn-add btn-xs" type="button">
    Add Row
</button>
		<?= Html::submitButton('Add Room Category', ['class'=> 'btn btn-add btn-hide']); ?>

      </div> 

	  


<?php ActiveForm::end() ?>
        
        
        
        
      </div>
    </div>
  </div>
  
    <div id="editroom" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Room Category</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="editroombody">
		
		</div>	
		
		  
		
	</div>
	</div>
</div>
        </section>
		<?php
$script = <<< JS
   $('#example').DataTable({
  "scrollX": true
});
JS;
$this->registerJs($script);
?>
<script>

function deleteroom(id){
//	var res = confirm('Are you sure want to delete??')
		    swal({
				title: "Are you sure want to delete??", 
				type: "warning",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
					    var request = $.ajax({
						  url: "deleteroom",
						  type: "POST",
						  data: {id : id},
						}).done(function(msg) {
							
							location.reload();
						});
					}
				});

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
