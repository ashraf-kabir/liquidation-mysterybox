<style type="text/css">
     .required-must{
          color: red;
     }
</style>

<div class="container mt-5 mb-5">
     <div class="row d-flex justify-content-center">
          <div class="col-md-12">
               <div class="card"> 
                         <div class="container mt-3 mb-3">
                              <div class="d-flex flex-column">
                                    <?php foreach ($orders as $key => $order) : ?>
                                        <div class="d-flex justify-content-between mb-5 mt-1 border-bottom">
                                             <div class=" flex-fill d-flex flex-column ">
                                                 <div class="text-muted">Order  #<?php echo $order->id ?> </div>
                                                 <div class="text-primary">  <?php echo $order_model->status_mapping()[$order->status] ?> </div>
                                                
                                                 <div>Total $<?php echo number_format($order->total, 2) ?></div>
                                                 <div>Date <?php echo date('F d, Y H:i', strtotime( $order->order_date_time)) ?></div>

                                                 <div class="action flex-fill">
                                                  <?php if ($order->is_picked == 1): ?>
                                                       <div class="text-info "> Order Picked </div>
                                                  <?php elseif ($order->is_shipped == 1): ?>
                                                       <div class="text-success">Order Shipped </div>
                                                       <?php else: ?>
                                                            <span class="text-primary">Order Processing </span>
                                                       <?php endif; ?>
                                                  </div>
                                             </div>
                                             
                                              
                                              <div class="action ml-auto">
                                                  <button onclick="toggleOrderDetails(<?php echo $order->id ?>)" class="btn text-success shadow-sm" >Details</a>
                                             </div>
                                        </div>
                                        <div class="details mb-2" id="order_details_<?php echo $order->id ?>" style="display:none">
                                             <div class="d-flex flex-row-reverse mb-1">
                                                  <span class="border rounded-circle font-weight-bold px-1 btn" onclick="toggleOrderDetails(<?php echo $order->id ?>)" > &times;</span>
                                             </div>
                                             <div class="lead">Order Details</div>
                                             <div class='row'>
                                             <div class="col-md-12">
                                             <div class="table-responsive" >
                                                  <table class="table table-bordered ">
                                                       <thead class="thead-primary">
                                                            <tr>
                                                                 <th style="width:20%">Item Name</th> 
                                                                 <th style="width:10%">Quantity</th>
                                                                 <th style="width:10%">Rate</th> 
                                                                 <th style="width:10%">Amount</th>
                                                                 <th style="width:10%">Delivery Type</th> 
                                                                 <th style="width:10%">Shipping Service/Store</th> 
                                                            </tr> 
                                                       </thead>
                                                       <tbody>
                                                       <?php foreach($order->details as $key => $detail){  ?> 
                                                            <tr>
                                                                 <td><?php echo  $detail->product_name; ?>  </td> 
                                                                 <td><?php echo  $detail->quantity; ?></td>
                                                                 <td>$<?php echo  number_format($detail->product_unit_price,2); ?></td> 
                                                                 <td>$<?php echo  number_format($detail->amount,2); ?></td>
                                                                 <td><?php echo $detail->is_pickup == "0"? 'Delivery' : 'Pick up'; ?></td>
                                                                           <?php if (empty($detail->store_id)) : ?>
                                                                 <td><?php echo $detail->shipping_cost_name ; ?></td>
                                                                           <?php else : ?>
                                                                           <?php foreach($stores as $key => $store){
                                                                                if($store->id == $detail->store_id){
                                                                                     echo "<td> {$store->name} </td>";
                                                                                }
                                                                           } ?>

                                                                           <?php endif ; ?>
                                                            </tr>
                                                       <?php } ?>
                                                       </tbody>
                                                       <tfoot>
                                                                 <tr>
                                                                      <td colspan="2"></td> 
                                                                      <td>Subtotal</td>
                                                                      <td>$<?php echo number_format($order->subtotal, 2);?></td>
                                                                 </tr> 
                                                                 <tr>
                                                                      <td colspan="2"></td> 
                                                                      <td>Shipping Cost</td>
                                                                      <td>$<?php echo number_format($order->shipping_cost, 2);?>
                                                                           <span><?php echo $order->shipping_cost_service_name ; ?></span>
                                                                      </td>
                                                                 </tr>
                                                                 

                                                                 <tr>
                                                                      <td colspan="2"></td> 
                                                                      <td>Tax</td>
                                                                      <td>$<?php echo number_format($order->tax, 2);?></td>
                                                                 </tr>


                                                                 <tr>
                                                                      <td colspan="2"></td> 
                                                                      <td>Discount</td>
                                                                      <td>$<?php echo number_format($order->discount, 2);?></td>
                                                                 </tr>

                                                                 <tr>
                                                                      <td colspan="2"></td> 
                                                                      <td>Total</td>
                                                                      <td>
                                                                 $<?php  
                                                                      echo number_format($order->total, 2);
                                                                 ?>
                                                                 
                                                            </td>
                                                                 </tr>
                                                            </tfoot>
                                                  </table>
                                             </div>
                                             </div> 
                                             </div> 
                                        </div>
                                    <?php endforeach; ?>
                              </div>
                         </div>
               </div>
          </div>
     </div>
</div>

<script>
     function toggleOrderDetails(id) {
          let detailsComponent = document.querySelector(`#order_details_${id}`);
          detailsComponent.style.display = detailsComponent.style.display == 'block' ? 'none' : 'block'; 
     }
</script>