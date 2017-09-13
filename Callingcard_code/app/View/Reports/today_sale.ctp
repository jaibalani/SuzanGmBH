<style type="text/css">
    
    #sales_records table tr th:nth-child(2){
        width: auto;
    }
</style>
<div class="right-part right-panel">
    
    <!--Today Sales Start-->
    <div class="sb-page-title">
        <strong><?php echo $title_for_layout;?></strong>
    </div>
    
    <div id="sales_records">
        <table class="table table-bordered">
            <thead>
                  <tr>
                    <th><?php echo __('Time');?></th>
                    <th><?php echo __('Card Name');?></th>
                    <th style="width:100px;"><?php echo __('Quantity');?></th>
                    <th style="width:150px;"><?php echo __('Purchase Price'); ?></th>
                    <th><?php echo __('Selling Price');?></th>
                    <th><?php echo __('Total Sales');?></th>
                  </tr>
            </thead>
            <tbody>
            <?php 
						if(isset($get_sales_data) && !empty($get_sales_data)){
						foreach ($get_sales_data as $data) {?> 
            <tr>
                    <td><?php echo ucwords($data['Sale']['s_time'])?></td>
                    <td><a id="print_bill_popup" href="<?php echo Router::url(array('controller'=>'Carts', 'action'=>'printcard',$data['Sale']['s_id'])); ?>" ><?php echo ucwords($data['Card']['c_title'])?></a></td>
                    <td><?php echo $data['Sale']['card_sale_count']?></td>
                    <td>&euro;<?php echo $data['Sale']['s_purchase_price']?></td>
                    <td>&euro;<?php echo $data['Sale']['s_selling_price']?></td>
                    <td>&euro;<?php echo $data['Sale']['s_total_sales']?></td>
            </tr>
            <?php }
						}else{?> 
								<td colspan="6" align="center"><?php echo __('No record found')?></td>
			<?php }?> 
            
            <tr>
                <td colspan="2"><?php echo __('Net Quantity');?></td>
                <td colspan="3"><?php echo $card_count;?></td>
                <td >&euro;<?php echo $total_sales_amount;?></td>
            </tr> 
         </tbody>
     </table>
    </div>
    <!--Today Sales End-->
</div>
<script type="text/javascript">

$(document).ready(function(){
	//Highlight active menu
	$('#sb-opt-report').addClass('opt-selected');
	$('#sb-opt-report').next().toggle('sliderup');
	$('#sb-subopt-today-sale').addClass('opt-selected');
});
</script>
