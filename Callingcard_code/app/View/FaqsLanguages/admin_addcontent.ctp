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

</style>
<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
    <div class="sub_title"><i class="icon-gear home_icon"></i> <span class="sub_litle_m">Settings</span> <i class="icon-angle-right home_icon"></i> <span>Add FAQ</span></div>
  </div>
</div>

<div class="main_subdiv">
	<div class="gird_button">
	        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
	</div>    
	  
	<div class="clear10"></div>
	
	<div align="left" class="grid_table_box">
			<?php echo $this->Form->create('Faq');?>
			
				<div class="clear20"></div>
				<div class="row">
			  		<div class="col-md-3 sb_left_pad">Title<sup class="MandatoryFields">*</sup></div>
			      <div class="col-md-6 sb_left_mar">
			      		 <?php echo $this->Form->input('f_title',array('label' => false, 'required' => 'required', 'class' => 'form-control','type'=>'text')); ?>
			     </div>
			     <div class="col-md-3">&nbsp;</div>
			  </div>  
			  
			  <div class="clear10"></div>
				<div class="row">
			  		<div class="col-md-3 sb_left_pad">Description<sup class="MandatoryFields">*</sup></div>
			      <div class="col-md-6 sb_left_mar">
			      <?php echo $this->Form->input('f_desc',array('class' => 'input_textbox','label' => false,'required' => false));?>
			      		  
			     </div>
			     <div class="col-md-3">&nbsp;</div>
			  </div>  
					
			  <div class="clear10"></div>
				<div class="row">
			  		<div class="col-md-3 sb_left_pad">&nbsp;</div>
			      <div class="col-md-6 sb_left_mar">
			      		<?php echo $this->Form->button('Save',array('class'=>'btn btn-primary','id'=>'submit_btn','div'=>false)); ?>
					    <button class="cancel btn btn-warning" type="button" >Cancel</button>
			     </div>
			     <div class="col-md-3">&nbsp;</div>
			  </div>    
			    
			<?php echo $this->Form->end();?>

		</div>
		
</div>	
<script type="text/javascript">
	var editor =CKEDITOR.replace('FaqFDesc', {height:200,toolbar:'MyToolbar'});
	$(document).ready(function(){
		   $('#setting').addClass('sb_active_opt');
		   $('#setting').removeClass('has_submenu');
		   $('#faq_active').addClass('sb_active_subopt_active');
		}) ;

  	$('.cancel').click(function(){
	  var url = "<?php echo $this->Html->url(array('controller'=>'Faqs','action'=>'index','admin'=>'true'));?>";
      window.location.href = url; 
	 // history.go(-1);
	});

</script>

