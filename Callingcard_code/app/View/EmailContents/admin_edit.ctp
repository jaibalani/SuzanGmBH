<style type="text/css">
.hidden_credit{
display: none;
}
.btn{
background-color: unset !important;
background-image:none !important;
}
.btn-info{
background-color: #5bc0de !important;
border-color: #46b8da !important;
color: #fff;
}
.btn-primary{
background-color: #337ab7 !important;
border-color: #2e6da4 !important;
color: #fff !important;
}

.grid_table_box .row .col-md-1 div.checkbox{ margin-left: -50px; }

#submit_btn{ margin-left: 0px; }

</style>
<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
    <div class="sub_title"><i class="icon-gear home_icon"></i> <span class="sub_litle_m">Settings</span> <i class="icon-angle-right home_icon"></i> <span class="sub_litle_m">Email Content</span><i class="icon-angle-right home_icon"></i> <span>Edit</span></div>
  </div>
</div>
<div class="clear10"></div>
<div class="main_subdiv">
	<div class="gird_button">
	        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
	</div>    
	  
	<div class="clear10"></div>
	
	<div align="left" class="grid_table_box">
	
		<?php echo $this->Form->create('EmailContent', array('id' => 'frm_emailcontent', 'name'=>'frm_emailcontent')); ?>
		<div class="row">
		  <div class="col-md-3 sb_left_pad"><?php echo __('Title')?><sup class="MandatoryFields">*</sup></div>
		  <div class="col-md-6 sb_left_mar">
		      <?php echo $this->Form->input('title',array('type'=>'text','class' => 'form-control','label' => false,'div'=>false)); ?>
		      <?php echo $this->Form->input('id',array('type'=>'hidden','class' => 'input_textbox','label' => false)); ?>
		  </div>
		  <div class="col-md-3">&nbsp;</div>
		</div>
		<div class="clear10"></div>
		
		<div class="row">
		  <div class="col-md-3 sb_left_pad"><?php echo __('Subject')?><sup class="MandatoryFields">*</sup></div>
		  <div class="col-md-6 sb_left_mar">
		      <?php echo $this->Form->input('subject',array('type'=>'text','class' => 'form-control','label' => false,'div'=>false)); ?>
		  </div>
		  <div class="col-md-3">&nbsp;</div>
		</div>
		<div class="clear10"></div>
		
		<div class="row">
		  <div class="col-md-3 sb_left_pad"><?php echo __('Message')?><sup class="MandatoryFields">*</sup></div>
		  <div class="col-md-6 sb_left_mar">
		      <?php echo $this->Form->input('message',array('type'=>'textarea','class' => 'form-control','label' => false,'div'=>false)); ?>
		  </div>
		  <div class="col-md-3">&nbsp;</div>
		</div>
		<div class="clear10"></div>
		
		<div class="row">
		  <div class="col-md-3 sb_left_pad"><?php echo __('Keywords')?></div>
		  <div class="col-md-6 sb_left_mar">
		      <?php echo $content;?>
		  </div>
		  <div class="col-md-3">&nbsp;</div>
		</div>
		<div class="clear10"></div>
		
		<div class="row">
		  <div class="col-md-3 sb_left_pad">&nbsp;</div>
		  <div class="col-md-6 sb_left_mar"><?php echo $this->Form->submit('Update', array('type'=>'submit', 'class'=>'btn btn-primary','label'=>false,'id'=>'submit_btn','div'=>false)); ?>
         <button class="cancel btn btn-warning" type="button" >Cancel</button>
	  </div> 
	</div>	
	<div class="clear10"></div>
  <?php echo $this->Form->end();?>  
</div>
		
</div>	
		
<script type="text/javascript">
	var editor =CKEDITOR.replace('EmailContentMessage', {height:200,width:700,toolbar:'MyToolbar'});
	$(document).ready(function(){
        $('#setting').addClass('sb_active_opt');
        $('#setting').removeClass('has_submenu');
        $('#emailcontent_active').addClass('sb_active_subopt_active');
    }) ;
	
	$('.cancel').click(function(){
			
            var url = "<?php echo $this->Html->url(array('controller'=>'EmailContents','action'=>'index','admin'=>'true'));?>";
		    window.location.href = url;  

			//history.go(-1);
	});
</script> 