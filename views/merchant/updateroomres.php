<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$hrRange = ['00'=>'12 AM','01'=>'1 AM','02'=>'2 AM','03'=>'3 AM','04'=>'4 AM','05'=>'5 AM','06'=>'6 AM','07'=>'7 AM','08'=>'8 AM','09'=>'9 AM','10'=>'10 AM','11'=>'11 AM',
'12'=>'12 PM','13'=>'1 PM','14'=>'2 PM','15'=>'3 PM','16'=>'4 PM','17'=>'5 PM','18'=>'6 PM','19'=>'7 PM','20'=>'8 PM','21'=>'9 PM','22'=>'10 PM','23'=>'11 PM'
];
$paidStatus = ['1' => 'Pending', '2' => 'Partial Paid', '3' => 'Paid'];

?>
   		<?php	$form = ActiveForm::begin([
    		'id' => 'update-room-res-form',
			'action'=>'updateroomres',
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
<label class="control-label col-md-4">User Name</label>
<div class="col-md-8">
			<?= $form->field($model, 'user_name')->textinput(['class' => 'form-control title','autocomplete'=>'off','placeholder'=>'User Name'])->label(false); ?>
			<?= $form->field($model, 'ID')->hiddeninput(['class' => 'form-control title','autocomplete'=>'off','placeholder'=>'User Name'])->label(false); ?>

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
									$( ".updateprice").val( res["price"] );
									$( ".update_room_allocated").html( res["allocatedRooms"] );						}
					);
				'])->label(false); ?>

</div>
</div>
<div class="form-group row">
<label class="control-label col-md-4">Price</label>
<div class="col-md-8">
<?= $form->field($model, 'price')->textinput(['class' => 'form-control updateprice','placeholder'=>'Price','readonly'=>true])->label(false); ?>
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
<?= $form->field($model, 'paid_amount')->textinput(['class' => 'form-control','placeholder'=>'Paid Amount'])->label(false); ?>
</div></div>
	  <div class="form-group row">
<label class="control-label col-md-4">Pending Amount</label>
<div class="col-md-8">
<?= $form->field($model, 'pending_amount')->textinput(['class' => 'form-control','placeholder'=>'Pending Amount'])->label(false); ?>
</div></div>
<div class="form-group row">
<label class="control-label col-md-4">Booking time</label></label>
<div class="col-md-8">
		  <?php  $model->booking_time= date('H' , strtotime($model->booking_time)); 
		  echo $form->field($model, 'booking_time')
		  ->dropdownlist($hrRange,['prompt'=>'Select'])->label(false); ?> 
</div>
</div>

<div class="form-group row">
<label class="control-label col-md-4">Rooms Alocated</label></label>
<div class="col-md-8">
<?= $form->field($model, 'room_alocated')
				  ->dropdownlist([\yii\helpers\ArrayHelper::map(\app\models\AllocatedRooms::find()
				  ->where(['merchant_id'=>Yii::$app->user->identity->merchant_id,'category_id'=>$model->room_category])
				  
				  ->all(), 'ID', 'room_name')]
				  ,['prompt'=>'Select','class'=>'form-control update_room_allocated'])->label(false); ?>
	<?= $form->field($model, 'reservation_status')->hiddeninput(['class' => 'form-control title'
,'autocomplete'=>'off','placeholder'=>'User Name','value'=>'1'])->label(false); ?>

</div></div>


<div class="form-group row">
<label class="control-label col-md-4">Status</label>
<div class="col-md-8">
<?= $form->field($model, 'reservation_status')->dropdownlist(['1' => 'New', '2' => 'Checked Out'])->label(false); ?>

</div></div>


</div>
<?php if(count($roomguests)>0 &&  !empty($roomguests)   ) {?>	
	<table id="tblAddRowsupdate" class="table table-bordered table-striped">
    <thead>
        <tr>
		<th>Guest Name</th>
			<th>Identity</th>
			<th>Guest Identity</th>
        </tr>
    </thead>
    <tbody>
	<?php for($i=0;$i<count($roomguests);$i++){ ?>
        <tr>
			<td>
                <input name="guestname_<?=$roomguests[$i]['ID']; ?>" value="<?= $roomguests[$i]['guest_name']?>" class="form-control">
            </td>
			<td>
			<select id="guestid" name="guestidname_<?=$roomguests[$i]['ID']; ?>" >
			<option value="">Select</option>
					<option value="1" <?php if($roomguests[$i]['guest_id_name'] == 1) { ?> selected <?php } ?> >Adhar Card</option>
					<option value="2"  <?php if($roomguests[$i]['guest_id_name'] == 2) { ?> selected <?php } ?>>Pan Card</option>
					<option value="3"  <?php if($roomguests[$i]['guest_id_name'] == 3) { ?> selected <?php } ?>>Driving License</option>
			</select>
                
            </td>
			<td>
                <input type="file" >
            </td>
        </tr>
    <?php } ?>   
    </tbody>
</table>
	<?php } ?>

	   </div>
	   </div>
	   <div class="modal-footer">
		<?= Html::submitButton('Update Reservation', ['class'=> 'btn btn-add']); ?>

      </div> 

<?php ActiveForm::end() ?>
        
<?php
$script = <<< JS
    $('#tblAddRow1').DataTable();
JS;
$this->registerJs($script);
?>
   
<script>

</script>    
        
        