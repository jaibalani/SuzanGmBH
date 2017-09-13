<div class="right-part right-panel">
 <div class="sb-page-title">
      <?php echo __('Shopping Cart');?>
 </div>
                    
 <div id="sales-reports">
    <?php echo $this->Form->create('Cart',array('url'=>array('action'=>'update')));?>
    <?php echo $this->Form->input('delete_c_id',array('type'=>'hidden','value'=>'','class'=>'delete_c_id'));?>
    <table class="table table-bordered">
        <thead>
          <tr>
            <th align="center" style="width:10%;"><?php echo __('S. No.'); ?></th>
            <th style="width:25%;"><?php echo __('Card Name'); ?></th>
            <th style="width:15%;"><?php echo __('Rate'); ?></th>
            <th style="width:15%;"><?php echo __('Quantity'); ?></th>
            <th style="width:15%;"><?php echo __('Price'); ?></th>
            <th style="width:20%;"><?php echo __('Action'); ?></th>
          </tr>
        </thead>
        <tbody>
				<?php $total=0; $l = 1;?>
				<?php foreach ($products as $product):?>
				<tr>
        	    <?php $count = $product['Card']['count'];?>
					<td><?php echo $l; $l++; ?></td>
                    <td><?php echo $product['Card']['c_title'];?></td>
					<td>&euro;&nbsp;<?php echo $product[0]['price'];?>
					</td>
					<td>
                    	<div class="col-xs-4">
							<?php echo $this->Form->hidden('c_id.',array('value'=>$product['Card']['c_id']));?>
							<?php  
							        $counter_array = array();
									for($i=1; $i<=$product['Card']['max_available']; $i++)
									{
										$counter_array[$i] = $i;
									}
									if($product['Card']['count'] <= $product['Card']['max_available'] )
									{
									  $value = $product['Card']['count'];
									}
									else
									{
										if($product['Card']['max_available']  > 0)
										{
											$count = $product['Card']['max_available'];
											$value = $product['Card']['max_available'];
										}
										else
										{
											$count = 0;
											$counter_array[0] = 0;
											$value = 0;
										}
										
									}
									echo $this->Form->input('count.',array('type'=>'select', 'label'=>false,
																		'options'=>$counter_array,
																		'class'	=> 'CartCount',
																		'style'=>'width:60px;',
																		'value'=>$value));?>
						</div>
                    </td>
					<td>&euro;&nbsp;<?php echo $count*$product[0]['price']; ?>
					</td>
                    <td><span class="remove" id="<?php echo $product['Card']['c_id']; ?>"></span></td>
				</tr>
				<?php $total = $total + ($count*$product[0]['price']);?>
				<?php endforeach;?>

                <tr>
              		<td colspan="4" align="right"><strong><?php echo __('Net Total'); ?></strong></td>
              		<td colspan="2" align="left"><?php echo '&euro;'.$total;?></td>
          		</tr>
			   <?php /*?> <tr>
                  <td colspan="6" align="right">
                       <?php echo $this->Form->button(__('UPDATE CART'),array('class'=>'button-gradient button-gradient_front_cart','div'=>false,'style'=>'display:none'));?>
                  </td>
          		</tr><?php */?>

            </tbody>
      </table>
    <?php echo $this->Form->end();?>
    </div>
    <div id="cart_options">
       <?php echo $this->Form->button(__('CONTINUE SHOPPING'),array('type'=>'button','class'=>'button-gradient shopping_button button-gradient_front','div'=>false,'onclick'=>'select_more();'));?>
   	   <?php echo $this->Form->button(__('PRINT / BUY'),array('class'=>'button-gradient buy_button button-gradient_front_cart_right','div'=>false,'onclick'=>'checkout();','style'=>'margin-right:20px;'));?>
    </div>
</div>				
                
<script>
$(".remove").each(function() {
		$(this).replaceWith('<a class="remove" id="' + $(this).attr('id') + '" href="javascript:void(0)" title="Remove item"><img src="<?php echo APPLICATION_URL?>img/icon-remove.gif" alt="Remove" /></a>');
	});

	$(".remove").click(function() {
		var string_disp = "<?php echo __('Are you sure you want to remove this from cart?');?>";
		if(confirm(string_disp)){
			$("#loading-image").fadeIn();
			$('.delete_c_id').val($(this).attr("id"));
			$('form').submit();
		}
	});

function select_more()
{
	var url = "<?php echo $this->Session->read('loaded_url_session')?>";
	if(url.length == 0)
	var url = "<?php echo $this->Html->url(array('controller'=>'Searches','action'=>'online_card'));?>";
    window.location.href = url;
}

function checkout()
{
	$("#loading-image").fadeIn();
	var url = "<?php echo $this->Html->url(array('controller'=>'Carts','action'=>'checkout'));?>";
   window.location.href = url;
}

$(document).ready(function(){
	//Highlight active menu
	$("#loading-image").fadeOut();
	$('#sb-opt-online-card').addClass('opt-selected');
	$('#sb-opt-online-card').next().toggle('sliderup');
	$('#sb-subopt-shopping').addClass('opt-selected');
	
});
$('.CartCount').change(function(){
	$("#loading-image").fadeIn();
	$('#CartViewForm').submit();
});

</script>        