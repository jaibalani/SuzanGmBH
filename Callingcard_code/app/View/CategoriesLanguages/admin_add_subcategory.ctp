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

#submit_btn{ margin-left: 0px; }

.grid_table_box .row .col-md-1 div.checkbox{ margin-left: -50px; }

</style>
<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $title; ?></div>
    <div class="sub_title"><i class="icon-book home_icon"></i> <span class="sub_litle_m">Product Master</span> <i class="icon-angle-right home_icon"></i> <span>Sub Category</span></div>
  </div>
</div>

<div class="main_subdiv">
	<div class="gird_button">
	        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
	</div>    
	  
	<div class="clear10"></div>
	
	<div align="left" class="grid_table_box">
	
		<?php echo $this->Form->create('Category');?>
		
			<div class="clear20"></div>
			
		  <div class="row">
		      <div class="col-md-3 sb_left_pad">Main Category<sup class="MandatoryFields">*</sup></div>
		      <div class="col-md-6 sb_left_mar">
		           <?php echo $this->Form->input('cat_parent_id', array('type' => 'button',
		                        'class'=>'form-control',
		                        'type'=>'select',
		                        'options'=>$catList,
		                        'value' => @$parent_id,
		                        'required' =>true,
		                        'label'=>false,
		                        'style'=>'cursor:pointer;',
		                        'empty' =>'Select Category'
		                      ));
		                      ?>
		     </div>
		     <div class="col-md-3">&nbsp;</div>
		  </div>  
		  
		  <div class="clear10"></div>
		  
		  <div class="row">
		  		<div class="col-md-3 sb_left_pad">Title<sup class="MandatoryFields">*</sup></div>
		      <div class="col-md-6 sb_left_mar">
		      		 <?php echo $this->Form->input('cat_title',array('label' => false, 'required' => 'required', 'class' => 'form-control','type'=>'text')); ?>
		     </div>
		     <div class="col-md-3">&nbsp;</div>
		  </div>  
		  
		  <div class="clear10"></div>
			<div class="row">
		  		<div class="col-md-3 sb_left_pad">Description</div>
		      <div class="col-md-6 sb_left_mar">
		      <?php echo $this->Form->input('cat_desc',array('class' => 'input_textbox','label' => false,'required' => false));?>
		      		  
		     </div>
		     <div class="col-md-3">&nbsp;</div>
		  </div>  
				
		  <div class="clear10"></div>
			<div class="row">
		  		<div class="col-md-3 sb_left_pad">&nbsp;</div>
		      <div class="col-md-6 sb_left_mar">
		      		<?php echo $this->Form->button('Save',array('class'=>'btn btn-primary','id'=>'submit_btn','div'=>false)); ?>
           	       <button class="cancel btn btn-warning" type="button" >Cancel</button>
       		      	<?php //echo $this->Form->button('Cancel',array('class'=>'cancel btn btn-warning','div'=>false)); ?>
		     </div>
		     <div class="col-md-3">&nbsp;</div>
		  </div>    
		    
		<?php echo $this->Form->end();?>

	</div>
		
</div>	

<script type="text/javascript">
	var editor =CKEDITOR.replace('CategoryCatDesc', {height:200,toolbar:'MyToolbar'});
	 $(document).ready(function(){
		    $('#product').addClass('sb_active_opt');
		   $('#product').removeClass('has_submenu');
		   $('#subcat_active').addClass('sb_active_subopt_active');
		}) ;

	$('.cancel').click(function(){
			var url = "<?php echo $this->Html->url(array('controller'=>'Categories','action'=>'subcategory','admin'=>'true'));?>";
            window.location.href = url;  
			//history.go(-1);
	});

</script>

