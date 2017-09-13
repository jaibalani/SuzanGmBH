

<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title_for_layout; ?></div>

  <div class="sub_title"><i class="icon-user home_icon"></i> <span class="sub_litle_m">Manage Mediator</span> <i class="icon-angle-right home_icon"></i> <span>Selling Price Details</span></div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title mediator_w"><?php echo $title_for_layout; ?></div>
        
    </div>    
  
  <div class="clear10"></div>
  <div align="left" class="grid_table_box">
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
 	<button class="<?php echo $class;?>" type="button"  style="margin-left:20px; margin-top:10px;" onclick='change_type("<?php echo $category_id?>","<?php echo $selchar?>");'>
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
    <div class="row">
       <div class="col-md-2">Select Mediator</div>
       <div class="col-md-5">	 
			<?php 
			echo $this->Form->input('mediator_id',array('class'=>'form-control amount_validation',
																									'required' =>true,
																									'type'=>'select',
																									'options'=>$mediator_list,
																									'value'=>$mediator_id,
																									'empty'=>'Select Mediator',
																									'label'=>false)); ?>
       </div>	
    </div>
<div class="clear10"></div>
    <?php 
			if($mediator_id)
			{
	?>
    <table class="table table-striped">
        <caption>Distributor's Card Price Management For <b><?php echo $mediator_name;?></b></caption>
        <thead>
            <tr>
                <th>Card Name</th>
                <th>Purchase Rate</th>
                <th>Selling Rate</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
           <?php  if(!empty($get_cards)) { ?>
           <?php echo $this->form->create('Card'); ?>
           <?php foreach ($get_cards as $data) {?>
            <tr>
                <td><?php echo ucwords($data['Card']['c_title']);?></td>
                <td><?php echo $data['Card']['c_buying_price'];?></td>
                <?php
				        if(isset($data['CardsPrice']))
						{ 
						    $flag = 0;
							if( !empty($data['CardsPrice']))
							{
								// Viewing Own Set Details
								foreach($data['CardsPrice'] as $price)
								{
									if($price['cp_u_role'] == 2 && $price['cp_u_id'] == $mediator_id )
									{
										$data['Card']['c_selling_price'] =$price['cp_selling_price'];
									    $flag= 1; 
									}
								}
							}
						}
				?>
                   <td><?php echo $this->Form->input('totalamount_'.$data['Card']['c_id'],array('class'=>'form-control amount_validation',
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
                <button class="btn btn-primary"  style="margin-top:10px;" type="submit" onclick="return check_submit();"><span class="icon-white"></span>&nbsp;Update</button>
  	            <button class="btn btn-warning cancel"  style="margin-top:10px;" type="button" ><span class="icon-white"></span>&nbsp;Cancel</button>
                </td>
            </tr>
        <?php echo $this->form->create('Card'); ?>
        <?php } else { ?>
        <tr>
           <td><?php echo __('No record found');?></td>
        </tr>
        <?php } ?>
        </tbody>

    </table>
    <?php } ?>
  </div>
  <div class="clear10"></div>
  </div>
</div>




<script type="text/javascript">

function check_submit()
{
	var ans = confirm("<?php echo __('Are you sure you want to update the selling prices ?');?>");	
	if(ans)
	{
		flag = 0;
		$('.amount_validation').each(function(){
			
			price = $(this).val();
			if(price == '' || price <= 0)
			{
				flag = 1; 
				$(this).css('border','1px solid #F00');
			}
		});
		if(flag == 1)
		{
		  alert('<?php echo __('Invalid Price.')?>')	
		  return false;	
		}
		
		$('#CardAdminManagePriceForm').submit();
	}
	else
	{
		
	}
}

$('.cancel').click(function(){
   var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'dashboard','admin'=>'true'));?>";
   window.location.href = url;
});

function change_type(card_category,char)
{
    var mediator_id = $('#mediator_id').val();
	if(card_category == 0)
	{
		char = 0;
		var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price','admin'=>'true'));?>/"+card_category+"/"+char+"/"+mediator_id;
	}
	else
	{
		if(char == '' )
		char = 0;
		var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price','admin'=>'true'));?>/"+card_category+"/"+char+"/"+mediator_id;
	}
	window.location.href = url;
}

function submitChar(char,card_category){
	
    var mediator_id = $('#mediator_id').val();
	var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price'));?>/"+card_category+"/"+char+"/"+mediator_id;
	window.location.href = url;
}

$('#mediator_id').change(function(){
	var mediator_id = $(this).val();
	var card_category = '<?php echo $selected_card_category?>';
	var char = '<?php echo $selchar?>';
	var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price'));?>/"+card_category+"/"+char+"/"+mediator_id;
	window.location.href = url;
});
$(document).ready(function(){
   $('#Mediator').addClass('sb_active_opt');
   $('#Mediator').removeClass('has_submenu');
   $('#manage_active').addClass('sb_active_subopt_active');
   
}) ;
</script>