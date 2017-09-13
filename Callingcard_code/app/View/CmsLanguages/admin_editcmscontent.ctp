<style>
#frm_cmspage{ position: absolute; }

#CmsPagesLanguageAlias{ width: 250px; margin-left: 350px; }

</style>
<div class="row">
  	<div class="col-md-12">
		<div class="page_title"><?php echo $title_for_layout; ?></div>
    	<div class="sub_title"><i class="icon-gear home_icon"></i> <span class="sub_litle_m">Settings</span><i class="icon-angle-right home_icon"></i> <span class="sub_litle_m">CMS Manage</span> <i class="icon-angle-right home_icon"></i> <span>Edit Cms Page Content</span></div>
  	</div>
  </div>
  <?php 
		echo $this->element('admin/ChangeLanguageCms');
	?>

<div class="main_subdiv">
	<div class="gird_button">
	        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
	</div>    
	  
	<div class="clear10"></div>
	
	<div align="left" class="grid_table_box">
	
		 <?php echo $this->Form->create('CmsLanguage', array('id' => 'frm_cmslanguage', 'name'=>'frm_cmslanguage')); ?>
			<div class="clear10"></div>
			<div class="row">
			  <div class="col-md-3 sb_left_pad"><?php echo __('Title')?><sup class="MandatoryFields">*</sup></div>
			  <div class="col-md-6 sb_left_mar">
		    		<?php echo $this->Form->input('title',array('type'=>'text','class' => 'form-control','label' => false,'div'=>false)); ?>
			  </div>
		    <div class="col-md-3">&nbsp;</div>
			</div>
			
			<div class="clear10"></div>
			<div class="row">
			  <div class="col-md-3 sb_left_pad"><?php echo __('Content')?><sup class="MandatoryFields">*</sup></div>
			  <div class="col-md-6 sb_left_mar">
		    		<?php echo $this->Form->input('id',array('type'=>'hidden','class' => 'input_textbox','label' => false)); ?>
		        <?php echo $this->Form->input('content',array('class' => 'form-control','label' => false,'required' => false,'div'=>false));?>
		    		
			  </div>
		    <div class="col-md-3">&nbsp;</div>
			</div>
		 
		 	<div class="clear10"></div>
		  
			<div class="row">
			  <div class="col-md-3 sb_left_pad">&nbsp;</div>
			  <div class="col-md-3 sb_left_mar"><?php echo $this->Form->submit('Update', array('type'=>'submit', 'class'=>'btn btn-primary','label'=>false,'id'=>'submit_btn','div'=>false)); ?>
		      <button class="cancel btn btn-warning" type="button" >Cancel</button>
		    </div> 
			</div>	
				
			<div class="clear10"></div>
			<?php echo $this->Form->end(); ?>
	
	</div>

</div>

<script type="text/javascript">
	var editor =CKEDITOR.replace('CmsLanguageContent', {height:200,width:'auto',toolbar:'MyToolbar'});
	$(document).ready(function(){
		   $('#setting').addClass('sb_active_opt');
		   $('#setting').removeClass('has_submenu');
		   $('#cmspage_active').addClass('sb_active_subopt_active');
		   
		}) ;
		
    $('.cancel').click(function(){
	
		 var url = "<?php echo $this->Html->url(array('controller'=>'CmsPages','action'=>'index','admin'=>'true'));?>";
         window.location.href = url;  
		// history.go(-1);
    
    });
</script>      