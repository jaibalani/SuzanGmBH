<style type="text/css">
#frm_Category{ position: absolute; }

#CategoryClAlias{ width: 250px;
margin-left: 398px; }

</style>
<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
    <div class="sub_title"><i class="icon-book home_icon"></i> <span class="sub_litle_m">Product Master</span> <i class="icon-angle-right home_icon"></i> <span>Edit - Category</span></div>
  </div>
</div>
<?php 
	echo $this->element('admin/ChangeLanguage');
?>	

<div class="main_subdiv">

		<div class="gird_button">
		        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
		</div>    
		  
		<div class="clear10"></div>
		<div align="left" class="grid_table_box">

			<?php echo $this->Form->create('CategoriesLanguage'); ?>
			<div class="clear20"></div>
			
			
				<div class="row">
			  		<div class="col-md-3 sb_left_pad">Title<sup class="MandatoryFields">*</sup></div>
			      <div class="col-md-6 sb_left_mar">
			      		 <?php echo $this->Form->input('cl_title',array('label' => false, 'required' => 'required', 'class' => 'form-control','type'=>'text')); ?>
			     </div>
			     <div class="col-md-3">&nbsp;</div>
			  </div>  
			  
			  <div class="clear10"></div>
				<div class="row">
			  		<div class="col-md-3 sb_left_pad">Description</div>
			      <div class="col-md-6 sb_left_mar">
			      <?php echo $this->Form->input('cl_id',array('type'=>'hidden','class' => 'input_textbox','label' => false)); ?>
			      <?php echo $this->Form->input('cl_desc',array('class' => 'input_textbox','label' => false,'required' => false));?>
			      		  
			     </div>
			     <div class="col-md-3">&nbsp;</div>
			  </div>  
					
			  <div class="clear10"></div>
				<div class="row">
			  		<div class="col-md-3 sb_left_pad">&nbsp;</div>
			      <div class="col-md-6 sb_left_mar">
			      	<?php echo $this->Form->button('Update',array('class'=>'btn btn-primary','id'=>'submit_btn','div'=>false)); ?>
	           	    <button class="cancel btn btn-warning" type="button" >Cancel</button>
			      	<?php //echo $this->Form->button('Cancel',array('class'=>'cancel btn btn-warning','div'=>false)); ?>
			     </div>
			  </div>    
			<?php echo $this->Form->end();?>  
			
		</div>
		
</div>

<script type="text/javascript">
	var editor =CKEDITOR.replace('CategoriesLanguageClDesc', {height:200,width:'auto',toolbar:'MyToolbar'});
     
$(document).ready(function(){
    	   $('#product').addClass('sb_active_opt');
    	   $('#product').removeClass('has_submenu');
    	   $('#cat_active').addClass('sb_active_subopt_active');
    	}) ;

		$('.cancel').click(function(){
			var url = "<?php echo $this->Html->url(array('controller'=>'Categories','action'=>'index','admin'=>'true'));?>";
             window.location.href = url;  
			//history.go(-1);
		});

</script>      