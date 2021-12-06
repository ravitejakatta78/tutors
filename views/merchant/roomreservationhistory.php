<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
$hrRange = ['0'=>'12 AM','1'=>'1 AM','2'=>'2 AM','3'=>'3 AM','4'=>'4 AM','5'=>'5 AM','6'=>'6 AM','7'=>'7 AM','8'=>'8 AM','9'=>'9 AM','10'=>'10 AM','11'=>'11 AM',
'12'=>'12 PM','13'=>'1 PM','14'=>'2 PM','15'=>'3 PM','16'=>'4 PM','17'=>'5 PM','18'=>'6 PM','19'=>'7 PM','20'=>'8 PM','21'=>'9 PM','22'=>'10 PM','23'=>'11 PM'
];
$paidStatus = ['1' => 'Pending', '2' => 'Partial Paid', '3' => 'Paid'];
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
            'timer' => (!empty($message['timer'])) ? $message['timer'] : 4000,
            'showConfirmButton' =>  (!empty($message['showConfirmButton'])) ? $message['showConfirmButton'] : true
        ]
    ]);
}
?>
          </header>
          <section>
		  <?= \Yii::$app->view->renderFile('@app/views/merchant/_roomreservations.php',['actionId'=>$actionId]); ?>

          <div class="col-lg-12">
            <div class="card">
               
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Room Reservation History</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"
				><i class="fa fa-plus mr-1"></i> Add Reservation</button>

				</div>
                
              </div>
              <div class="card-body">
			  <form class="form-horizontal" method="POST" action="roomreservationhistory">
                   <div class="row">
			  <div class="col-md-3">
                  <div class="form-group row">
                    <label class="control-label col-md-4 pt-2">Start Date:</label>
					<div class="col-md-8">
                  <div class="input-group mb-3 mr-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker1" name="sdate" placeholder="Start Date" value="<?= $sdate ; ?>">
                </div>
                  </div>
				  </div>
				  </div>
				  
				 <div class="col-md-3">
                  <div class="form-group row">
                    <label class="control-label col-md-4 pt-2">End Date:</label>
					<div class="col-md-8">
                  <div class="input-group mb-3 mr-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker2" name="edate" placeholder="End Date" value="<?= $edate ; ?>">
                </div>
				</div>
                  </div>
				  </div>
				  
				  
				  <div class="col-md-3">
                  <div class="form-group row">
                    <label class="control-label col-md-4 pt-1">Status:</label>
					<div class="col-md-8">
                  <div class="input-group mb-3 mr-3">
                  <select id="reservationstatus" name="reservationstatus">
				  <option value="" <?php if($reservationstatus == '') { echo "selected"; } ?>>All</option>
				  <option value="0" <?php if($reservationstatus == '0') { echo "selected"; } ?>>Pending</option>
				  <option value="1" <?php if($reservationstatus == '1') { echo "selected"; } ?>>Approved</option>
				  <option value="2" <?php if($reservationstatus == '2') { echo "selected"; } ?>>Rejected</option>
				  <option value="3" <?php if($reservationstatus == '3') { echo "selected"; } ?>>Checkout</option>
				  				  
				</select> 
				 </div>
                </div>
                  </div> 
				  </div>
				  
				  
				  
 
				  <div class="col-md-3 ">
                  <div class="form-group pt-3">
                    <input type="submit" value="Search" class="btn btn-add btn-sm btn-search"/>
                  </div>
				  </div>
                  </div>
                  
                </form>
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
									<th>S No</th>
                                    <th>User Name</th>
                                <!--    <th>Booking No</th> --> 
									<th>Room Category</th> 
									<th>Rooms Allocated</th>
									<th>Price</th> 
									<th>Payment Status</th>
									<th>Pending</th>
                                    <th>Paid Amount</th>
                                    <th>Booking Time</th> 
									<th>Room Allocated</th> 
									<th>Guest/s</th> 
									<th>Action</th> 
                      </tr>
                    </thead>
		    <tbody>
								<?php for($i=0;$i< count($roomModel);$i++) { ?>
                                  <tr>
                                 	<td><?= $i+1 ; ?></td>
                                 	<td><?= $roomModel[$i]['user_name']; ?></td> 
                                 	<td><?= $roomModel[$i]['category_name']; ?></td>
                                 	<td><?= $roomModel[$i]['room_alocated']; ?></td>

                                 	<td><?= $roomModel[$i]['price']; ?></td>  
									<td><?= $paidStatus[$roomModel[$i]['payment_status']]; ?></td>
									<td><?= $roomModel[$i]['pending_amount']; ?></td>  
                                 	<td><?= $roomModel[$i]['paid_amount']; ?></td> 
                                 	<td><?= date('Y-m-d',strtotime($roomModel[$i]['booking_time']))."<br>".date('H A',strtotime($roomModel[$i]['booking_time'])); ?></td> 
									 <td><?php 
									 $roomName = \app\models\AllocatedRooms::findOne($roomModel[$i]['room_alocated']);
									 echo @$roomName['room_name'] ?? '' ; ?></td> 
								 	<td><?= $roomModel[$i]['guests']; ?></td> 
                                     <td class="icons"><a  title="Rservation - Edit" onclick="editroomrespopup('<?= $roomModel[$i]['ID'];?>')"><span class="fa fa-pencil"></span></a></td>
									</tr>
								<?php } ?>	
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
          <h4 class="modal-title">Add Reservation</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
		<?php	$form = ActiveForm::begin([
    		'id' => 'room-reg-form',
		'options' => ['class' => 'form-horizontal',
		'enctype' => "multipart/form-data"
		,'wrapper' => 'col-xs-12',],
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
		<label class="control-label col-md-4">User Name</label>
		<div class="col-md-8">
					<?= $form->field($model, 'user_name')->textinput(['class' => 'form-control title','autocomplete'=>'off','placeholder'=>'User Name'])->label(false); ?>
		</div>
		</div>
		<div class="form-group row">
		<label class="control-label col-md-4">Mobile No</label>
		<div class="col-md-8">
		<?= $form->field($model, 'user_mobile_no')->textinput(['class' => 'form-control','placeholder'=>'Mobile No'])->label(false); ?>
		</div></div>
		<div class="form-group row">
		<label class="control-label col-md-4">Room Category</label>
		<div class="col-md-8">
		<?= $form->field($model, 'room_category')
					->dropdownlist(\yii\helpers\ArrayHelper::map(\app\models\RoomReservations::find()
					->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->all(), 'ID', 'category_name')
					,['prompt'=>'Select',
						'onchange'=>'
							$.get( "'.Url::toRoute('/merchant/roomcategoryprice').'", { id: $(this).val() } )
								.done(function( data ) {
									var res = JSON.parse(data);
									$( "#'.Html::getInputId($model, 'price').'" ).val( res["price"] );
									$( "#'.Html::getInputId($model, 'room_alocated').'" ).html( res["allocatedRooms"] );
								}
							);
						'])->label(false); ?>

		</div>
		</div>
		<div class="form-group row">
		<label class="control-label col-md-4">Price</label>
		<div class="col-md-8">
		<?= $form->field($model, 'price')->textinput(['class' => 'form-control','placeholder'=>'Price','readonly'=>true])->label(false); ?>
		</div></div>
		<div class="form-group row">
		<label class="control-label col-md-4">Guests</label>
		<div class="col-md-8">
		<?= $form->field($model, 'guests')->textinput(['class' => 'form-control','placeholder'=>'Guests'])->label(false); ?>
		</div></div>
		
	   
	</div>
	<div class="col-md-6">
            <div class="form-group row">
	   <label class="control-label col-md-4">Payment Status</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'payment_status')->dropDownList(
            $paidStatus , 
			['prompt'=>'Select'])->label(false); 
		?>
	   
	 
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Paid Amount</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'paid_amount')->textinput(['class' => 'form-control'
	   ,'placeholder'=>'Paid Amount','onchange' => 'var price = $( "#'.Html::getInputId($model, 'price').'" ).val();
	 		var pending_price = price - (this.value);
			 $( "#'.Html::getInputId($model, 'pending_amount').'" ).val(pending_price)  

	   '])->label(false); ?>
	   </div></div>
	   	   <div class="form-group row">
	   <label class="control-label col-md-4">Pending Amount</label>
	   <div class="col-md-8">
	   <?= $form->field($model, 'pending_amount')->textinput(['class' => 'form-control'
	   ,'placeholder'=>'Pending Amount','readonly'=>true])->label(false); ?>
	   </div></div>
	   <div class="form-group row">
	   <label class="control-label col-md-4">Booking time</label></label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'booking_time')
				  ->dropdownlist($hrRange,['prompt'=>'Select'])->label(false); ?> 
	   </div>
	   </div>

	   <div class="form-group row">
	   <label class="control-label col-md-4">Rooms Alocated</label></label>
	   <div class="col-md-8">

	   <?= $form->field($model, 'room_alocated')
				  ->dropdownlist([]
				  ,['prompt'=>'Select'])->label(false); ?>
	   <?= $form->field($model, 'reservation_status')->hiddeninput(['class' => 'form-control title'
	   ,'autocomplete'=>'off','placeholder'=>'User Name','value'=>'1'])->label(false); ?>

	   </div></div>
	   
	</div>

	<table id="tblAddRow" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Guest Name</th>
			<th>Identity</th>
			<th>Guest Identity</th>
			<th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>

            
			<td>
                <input type = "text" name="guestname[]" class="form-control">
            </td>
			<td>
				<select name=identity[]>
					<option value="">Select</option>
					<option value="1">Adhar Card</option>
					<option value="2">Pan Card</option>
					<option value="3">Driving License</option>
				</select>
			</td>
			<td>
                <input type = "file" name="guestidentity[]" class="form-control">
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
		<?= Html::submitButton('Add ', ['class'=> 'btn btn-add btn-hide']); ?>

      </div> 


<?php ActiveForm::end() ?>
        
        
        
        
      </div>
    </div>
  </div>
  
  <div id="editroomres" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Room Reservation</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="editroomresbody">
		
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
  function editroomrespopup(id){
  var request = $.ajax({
    url: "editroomrespopup",
    type: "POST",
    data: {id : id},
  }).done(function(msg) {
    $('#editroomresbody').html(msg);
    $('#editroomres').modal('show');
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