<style>
.checkbox_bootstrap {
    float: left;
    width: 250px;
}

</style>
<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
    <div class="sub_title"><i class="icon-gear home_icon"></i> <span class="sub_litle_m">Settings</span><i class="icon-angle-right home_icon"></i> <span class="sub_litle_m">Language Manage</span> <i class="icon-angle-right home_icon"></i> <span>Edit Locale</span></div>
  </div>
</div>

<div class="main_subdiv">
	<div class="gird_button">
	        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
	</div>    
	  
	<div class="clear10"></div>
	
	<div align="left" class="grid_table_box">

		<?php echo $this->Form->create('Language', array('id' => 'frm_language', 'name'=>'frm_language')); ?>
		<div class="clear10"></div>
			<div class="row">
			  <div class="col-md-3"><?php echo __('Locale')?>:</div>
			  <div class="col-md-6">
		    		 <?php
								echo $this->Form->input('Locale.content', array(
									'label' => false,
									'value' => $content,
									'type' => 'textarea',
									'class' => 'form-control',
							));
							?>
					<?php echo $this->Form->input('id',array('type'=>'hidden','class' => 'input_textbox','label' => false)); ?>
			  </div>
		    <div class="col-md-3">&nbsp;</div>
			</div>
		  
		  <div class="clear10"></div>
		 	<div class="row">
			  <div class="col-md-3">&nbsp;</div>
			  <div class="col-md-3"><?php echo $this->Form->submit('Update', array('type'=>'submit', 'class'=>'btn btn-primary','label'=>false,'id'=>'submit_btn','div'=>false)); ?>
		      <button class="cancel btn btn-warning" type="button" >Cancel</button>
		    </div> 
			</div>	
				
			<div class="clear10"></div>
		<?php echo $this->Form->end(); ?>
		
	</div>
	
</div>

<script type="text/javascript">
$(document).ready(function(){
   $('#setting').addClass('sb_active_opt');
   $('#setting').removeClass('has_submenu');
   $('#lag_active').addClass('sb_active_subopt_active');
}) ;

$('.cancel').click(function(){
     var url = "<?php echo $this->Html->url(array('controller'=>'Languages','action'=>'index','admin'=>'true'));?>";
    window.location.href = url;  
    // history.go(-1);
});

</script>