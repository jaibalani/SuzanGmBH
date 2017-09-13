
<div class="row">
  <div class="col-md-12">
    <h1><?php echo $title_for_layout; ?></h1>
  </div>
</div>

<div class="clear10"></div>
<div class="row">
  <?php
  	foreach($card_categories as $category_id => $category_value)
	{
		if($selected_card_category == $category_id)
		$class = "btn btn-danger";
		else
		$class = "btn btn-info";
		
  ?>
 	<button class="<?php echo $class;?>" type="button"  style="margin-left:20px; margin-top:10px;" onclick='change_type("<?php echo $category_id?>");'>
 		<span class="icon-white"></span>&nbsp;
			<?php echo $category_value; ?>
    </button>
  <?php } ?> 
</div>
<div class="clear10"></div>

<nav role="navigation" class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div class="navbar-collapse collapse" id="navbar">
            <ul class="nav navbar-nav">
            	<?php  foreach (range('A', 'Z') as $char){
											$class = '';
											if($selchar==$char){
												$class = 'class="active"';
											}?>
											<li <?php echo $class?>><a href="javascript:void(0)" onclick="submitChar('<?php echo $char?>','<?php echo $selected_card_category?>')"><?php echo $char?></a></li>
							<?php }?>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
</nav>
    <div class="clear10"></div>
    <table class="table table-striped">
        <caption>Card Price Management</caption>
        <thead>
            <tr>
                <th>Card Name</th>
                <th>Denomination Rate</th>
                <th>Purchase Rate</th>
                <th>Selling Rate</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
           <?php echo $this->Form->create('Card',array('id'=>'cardForm')); ?>
           <?php foreach ($get_cards as $data) { ?>
            <tr>
                <td><?php echo ucwords($data['Card']['c_title']);?></td>
                <td><?php echo ucwords($data['Card']['c_selling_price']);?></td>
                <td>
					<?php 
					    if(isset($data['CardsPrice']))
						{ 
						    $flag = 0;
							
							if( !empty($data['CardsPrice']))
							{
								// Viewing Own Set Details
								foreach($data['CardsPrice'] as $price)
								{
									if($price['cp_u_role'] == 3 && $price['cp_updated_by'] == $this->Session->read('Auth.User.id'))
									{
										$data['Card']['c_buying_price'] =$price['cp_buying_price'];
										$data['Card']['c_selling_price'] =$price['cp_selling_price'];
									    $flag= 1; 
									}
								}
								
								// If Not Set then viewing set by its mediator
								if($flag == 0)
								{
									foreach($data['CardsPrice'] as $price)
									{
										if($price['cp_u_role'] == 2 && $price['cp_updated_by'] == $this->Session->read('Auth.User.added_by'))
										{
											$data['Card']['c_buying_price'] =$price['cp_selling_price'];
											$data['Card']['c_selling_price'] =$price['cp_selling_price'];
											$flag= 1; 
										}
									}
								}
							}
							
							if($flag == 0)
							{
								$data['Card']['c_buying_price'] =  $data['Card']['c_selling_price'];
							}
							echo $data['Card']['c_buying_price'];
						}
						else
						{
							$data['Card']['c_buying_price'] =  $data['Card']['c_selling_price'];
							echo $data['Card']['c_selling_price'];
						}
					?>
                </td>
                <td>
				  <?php 
				   
				   echo $this->Form->input('totalamount_'.$data['Card']['c_id'],array('class'=>'form-control amount_validation',
																									'required' =>true,
																									'type'=>'text',
																									'value'=>$data['Card']['c_selling_price'],
																									'style'=>'width:100px',
																									'label'=>false)); ?>
                   <?php echo $this->Form->input('purchase_'.$data['Card']['c_id'],array('class'=>'form-control',
																									'required' =>true,
																									'type'=>'hidden',
																									'value'=>$data['Card']['c_buying_price'],
																									'label'=>false)); ?>                                                                                           
                                                                                                    
				  </td>
            </tr>
           <?php } ?>
           <tr>
                <td>
  	            <button class="btn btn-primary"  style="margin-top:10px;" type="submit"  onclick="check_submit();" ><span class="icon-white"></span>&nbsp;Update</button>
                <button class="btn btn-warning"  style="margin-top:10px;" type="button"  onclick="go_dashboard();"><span class="icon-white"></span>&nbsp;Cancel</button>
                </td>
            </tr>
        <?php echo $this->form->create('Card'); ?>
        </tbody>

    </table>
 

<script type="text/javascript">

function check_submit()
{
	var ans = confirm("<?php echo __('Are you sure you want to update the selling prices ?');?>");	
	if(ans)
	{
		$('#cardForm').submit();
	}
	else
	{
		
	}
}

function change_type(card_category)
{
    var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price_retailer'));?>/"+card_category;
	window.location.href = url;
}

function go_dashboard()
{
	var url = "<?php echo $this->Html->url(array('controller'=>'Pages','action'=>'dashboard'));?>";
	window.location.href = url;
}
function submitChar(char,card_category){
	
	var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price_retailer'));?>/"+card_category+"/"+char;
	window.location.href = url;
}
</script>