<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li><?php echo $this->Html->link('Home','/');?>
			</li>
			<li class="active">Cart</li>
		</ol>
	</div>
</div>

<?php echo $this->Form->create('Cart',array('url'=>array('action'=>'update')));?>
<?php echo $this->Form->input('delete_c_id',array('type'=>'hidden','value'=>'','class'=>'delete_c_id'));?>
<div class="row">
	<div class="col-lg-12">
		<table class="table">
			<thead>
				<tr>
					<th>Product Name</th>
					<th>Price</th>
					<th>Quantity</th>
					<th>Total</th>
          <th>REMOVE</th>
				</tr>
			</thead>
			<tbody>
				<?php $total=0;?>
				<?php foreach ($products as $product):?>
				<tr>
        	    <?php $count = $product['Card']['count'];?>
					<td><?php echo $product['Card']['c_title'];?></td>
					<td>$<?php echo $product[0]['price'];?>
					</td>
					<td><div class="col-xs-4">
							<?php echo $this->Form->hidden('c_id.',array('value'=>$product['Card']['c_id']));?>
							<?php  
							        $counter_array = array();
									for($i=1; $i<$product['Card']['max_available']; $i++)
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
									//prd($counter_array);
							         echo $this->Form->input('count.',array('type'=>'select', 'label'=>false,
									'class'=>'form-control input-sm','options'=>$counter_array,'value'=>$value));?>
						</div></td>
					<td>$<?php echo $count*$product[0]['price']; ?>
					</td>
              <td><span class="remove" id="<?php echo $product['Card']['c_id']; ?>"></span></td>
				</tr>
				<?php $total = $total + ($count*$product[0]['price']);?>
				<?php endforeach;?>

				<tr class="success">
					<td colspan=3></td>
					<td>$<?php echo $total;?>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="text-right">
			<?php echo $this->Form->button(__('Select More'),array('type'=>'button','class'=>'btn btn-info','div'=>false,'onclick'=>'select_more();'));?>
			<?php echo $this->Form->submit(__('Update'),array('class'=>'btn btn-warning','div'=>false));?>
          	<a class="btn btn-success"
				onclick="checkout();">CheckOut</a>
		</p>

	</div>
</div>
<?php echo $this->Form->end();?>
<script>
$(".remove").each(function() {
		$(this).replaceWith('<a class="remove" id="' + $(this).attr('id') + '" href="javascript:void(0)" title="Remove item"><img src="<?php echo APPLICATION_URL?>img/icon-remove.gif" alt="Remove" /></a>');
	});

	$(".remove").click(function() {
		$('.delete_c_id').val($(this).attr("id"));
		$('form').submit();
		
	});

function select_more()
{
	var url = "<?php echo $this->Html->url(array('controller'=>'Searches','action'=>'index'));?>";
    window.location.href = url;
}

function checkout()
{
	var url = "<?php echo $this->Html->url(array('controller'=>'Carts','action'=>'checkout'));?>";
    window.location.href = url;
}
</script>