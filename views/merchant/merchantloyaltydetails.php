<table id="example" class="table table-striped table-bordered " width="100%">
                    <thead>
                      <tr>
                        <th>S No</th>
						<th>Date</th>
					<!--	<th>Merchant Id</th> -->
						<th>User Id</th>
                      </tr>
                    </thead>
					<tbody>
						<?php $x=1; 
							foreach($loyaltyDet as $loyalty){
						?>
                            <tr>
                                <td><?php echo $x; ?></td>
                                <td><?php echo $loyalty['reg_date']; ?></td>
							<!--	<td><?php echo $loyalty['merchant_id'].' (Months)'; ?></td> -->
                                <td><?php echo app\helpers\Utility::user_details($loyalty['user_id'],'name'); ?></td>
							</tr>			
                                                	<?php $x++; }?>
                    </tbody>
                  </table>