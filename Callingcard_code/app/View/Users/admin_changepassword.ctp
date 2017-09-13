<style type="text/css">

.btn-primary{ margin-left: 43px; }

.grid_table_box .row .col-md-1 div.checkbox { margin-left: -50px; }

#submit_btn{ margin-left: 0px; }

</style>
<div>
<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $pageTitle; ?></div>
    <?php if($this->Session->read('Auth.User.role_id') == 1) {?>
    <div class="sub_title"><i class="icon-user home_icon"></i> <span class="sub_litle_m">Distributor's Profile</span> <i class="icon-angle-right home_icon"></i> <span>Change Password</span></div>
    <?php } else { ?>
    <div class="sub_title"><i class="icon-user home_icon"></i> <span class="sub_litle_m">Mediator's Profile</span> <i class="icon-angle-right home_icon"></i> <span>Change Password</span></div>
    <?php }  ?>
  </div>
</div>

<div class="main_subdiv">

		<div class="gird_button">
		        <div class="main_sub_title"><?php echo $pageTitle; ?></div>
		</div>    
		  
		<div class="clear10"></div>
		<div align="left" class="grid_table_box">
		
		  <?php echo $this->Form->create('User');?>
		 
		  <div class="row">
		  		<div class="col-md-3 sb_left_pad"><?php echo __('Password')?><sup class="MandatoryFields">*</sup></div>
			 	  <div class="col-md-6 sb_left_mar"><?php echo $this->Form->input('password',array('type'=>'password','class' => 'form-control','div'=>false,'maxlength'=>'15', 'autofocus'=>true,'label'=>false,'required'=>'required')); ?>
		    </div>
		  </div>
		  <div class="clear10"></div>
		  
		  <div class="row">
		  		<div class="col-md-3 sb_left_pad"><?php echo __('Confirm Password')?><sup class="MandatoryFields">*</sup></div>
			 	  <div class="col-md-6 sb_left_mar"><?php echo $this->Form->input('confirm_password',array('type'=>'password','class' => 'form-control','maxlength'=>'15','div'=>false,'label'=>false,'required'=>'required')); ?>
		    </div>
		  </div>
		  <div class="clear10"></div>
		  
		  <div class="row">
		  		<div class="col-md-2">&nbsp;</div>
			 	  <div class="col-md-8"><?php echo $this->Form->submit('Change',array('class'=>'btn btn-primary','div'=>false)).' '.$this->Form->button('Cancel',array('class'=>'btn btn-warning cancel','div'=>false,'type'=>'button')); ?></div>
		  </div>
		  <div class="clear10"></div>
		  
		  
		  <?php echo $this->Form->end();?>
		  
	</div>
	
</div>
  
</div>
<script type="text/javascript">

 $('.cancel').click(function(){
 			var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'profile_edit','admin'=>'true'));?>";
      window.location.href = url;
 });

</script>