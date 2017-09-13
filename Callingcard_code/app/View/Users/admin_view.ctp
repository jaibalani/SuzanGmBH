<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $pageTitle; ?></div>
    <?php
	if($this->Session->read('Auth.User.role_id') == 1)
	{
		$view_data = "Mediator Personal Data" ;
	    $manage = "Mediator";
	}
	else
	{
		$view_data = "Manage Retailer" ;
		$manage = "Retailer";
	}
	?>
	<div class="sub_title"><i class="icon-user home_icon"></i> <span class="sub_litle_m"><?php echo $manage;?></span> <i class="icon-angle-right home_icon"></i> <span class="sub_litle_m"><?php echo $view_data;?></span> <i class="icon-angle-right home_icon"></i> <span>View</span> </div>
  </div>
</div>

<div class="main_subdiv">
		    <div class="gird_button">
		        <div class="main_sub_title rat_w"><?php echo $pageTitle; ?></div>
        		<button class="new_button back" type="button" style="cursor:pointer;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
    		</div>    
    
		  
		<div class="clear10"></div>
		<div align="left" class="grid_table_box">
			<?php echo $this->Form->create('User');  ?>
			  <div class="clear10"></div>
				<div class="row">
				  <div class="col-md-3 sb_left_pad"><?php echo __('First Name')?></div>
				  <div class="col-md-6 sb_left_mar">
					<?php echo $this->Form->input('id',array('class'=>'login_textbox_admin add_label',
											'required' =>true,'type'=>'hidden',
											'label'=>false)); ?>

				   	<?php echo $this->request->data['User']['fname'];?>
				  </div>
			    <div class="col-md-3">&nbsp;</div>
				</div>
			  
			  <div class="clear10"></div>
				<div class="row">
				  <div class="col-md-3 sb_left_pad"><?php echo __('Last Name')?></div>
				  <div class="col-md-6 sb_left_mar">
			    	<?php echo $this->request->data['User']['lname'];?>
				  </div>
			    <div class="col-md-3">&nbsp;</div>
				</div>
			  
			  <div class="clear10"></div>
				<div class="row">
				  <div class="col-md-3 sb_left_pad"><?php echo __('Email')?></div>
				  <div class="col-md-6 sb_left_mar">
			    	<?php 
                    if(empty($this->request->data['User']['email']))
                   	$this->request->data['User']['email'] = "-";
			    	echo $this->request->data['User']['email'];?>
				  </div>
			    <div class="col-md-3">&nbsp;</div>
				</div>
			  
			  <div class="clear10"></div>
				<div class="row">
				  <div class="col-md-3 sb_left_pad">
				  <?php echo __('Contact Number')?></div>
				  <div class="col-md-6 sb_left_mar">
			    	<?php 
                    if(empty($this->request->data['User']['phone']))
                   	$this->request->data['User']['phone'] = "-";
			    	echo $this->request->data['User']['phone'];?>
				  </div>
			    <div class="col-md-3">&nbsp;</div>
				</div>
			  
			  <div class="clear10"></div>
				<div class="row">
				  <div class="col-md-3 sb_left_pad"><?php echo __('Country')?></div>
				  <div class="col-md-6 sb_left_mar">
			    	<?php 
			    	if(empty($this->request->data['User']['country_code']))
                   	$this->request->data['User']['country_code'] = "-";
			    	echo $this->request->data['User']['country_code'];?>
				  </div>
			    <div class="col-md-3">&nbsp;</div>
				</div>
			  
			  
			  <div class="clear10"></div>
				<div class="row">
				  <div class="col-md-3 sb_left_pad"><?php echo __('Address')?></div>
				  <div class="col-md-6 sb_left_mar">
			    	<?php 
                    if(empty($this->request->data['User']['address']))
                   	$this->request->data['User']['address'] = "-";
			    	echo $this->request->data['User']['address'];?>
				  </div>
			    <div class="col-md-3">&nbsp;</div>
				</div>
			  
			  <div class="clear10"></div>
				<div class="row">
				  <div class="col-md-3 sb_left_pad"><?php echo __('Created')?></div>
				  <div class="col-md-6 sb_left_mar">
			    	<?php echo date('d.m.Y H:i:s' ,strtotime($this->request->data['User']['created']));?>
				  </div>
			    <div class="col-md-3">&nbsp;</div>
				</div>
			  
			  <div class="clear10"></div>
				<div class="row">
				  <div class="col-md-3 sb_left_pad"><?php echo __('Updated')?></div>
				  <div class="col-md-6 sb_left_mar">
			    	<?php echo date('d.m.Y H:i:s' ,strtotime($this->request->data['User']['updated']));?>
				  </div>
			    <div class="col-md-3">&nbsp;</div>
				</div>
			  
			  <?php
						if(isset($this->request->data['User']['image']) && !empty($this->request->data['User']['image']))
						{
							$image = $this->request->data['User']['image'];
						}
						else
						{
							$image ="noimage.jpg";
						}
						?>	
			                    <div class="clear10"></div>
			                    <div class="row">
			                    <div class="col-md-3 sb_left_pad">&nbsp;</div>
			                    <div class="col-md-7 sb_left_mar">
			              	<?php 
						  	if(file_exists(WWW_ROOT.'img/users/'.$image))
							{
							  /*echo $this->Html->image(IMAGE_PATH.'image.php?image=img/users/'.$image.'&amp;width=100&amp;height=100'
							, array('alt' => '','border'=>'0','div'=>true));*/
                               echo $this->Html->image('users/'.$image,
											array('class'=>'','border'=>'0','div'=>true,'width'=>100,'height'=>'100'));
							}
							else
							{
							 /*echo $this->Html->image(IMAGE_PATH.'image.php?image=img/users/noimage.jpg&amp;width=100&amp;height=100'
																				, array('alt' => '','border'=>'0','div'=>true));*/
							 echo $this->Html->image('users/noimage.jpg',
									    array('class'=>'','border'=>'0','div'=>true,'width'=>100,'height'=>'100','label'=>'Profile Image'));
							}
						?>
			            </div>
			            <div class="col-md-2">&nbsp;</div>
			          </div>
			
			<div class="clear10"></div>
			<?php echo $this->Form->end();?>

	</div>
	
</div>

<script type="text/javascript">
$('.back').click(function(){
	var role_id = "<?php echo $role_id; ?>";
	if(role_id == 1)
	{
		var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'manage_mediator','admin'=>'true'));?>";
	}
	else
	{
		var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'manage_retailer','admin'=>'true'));?>";
	}
    //window.location.href = url;
	history.go(-1);
});

$(document).ready(function(){
	   $('#Mediator').addClass('sb_active_opt');
	   $('#Mediator').removeClass('has_submenu');
	   $('#mediator_active').addClass('sb_active_subopt_active');
	   $('#retailer').addClass('sb_active_opt');
	   $('#retailer').removeClass('has_submenu');
	   $('#manage_retailer').addClass('sb_active_subopt_active');
	}) ;

</script>