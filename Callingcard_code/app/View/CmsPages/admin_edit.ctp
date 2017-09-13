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

</style>
<div class="row">
  	<div class="col-md-12">
		<div class="page_title"><?php echo $title_for_layout; ?></div>
    	<div class="sub_title"><i class="icon-gear home_icon"></i> <span class="sub_litle_m">Settings</span> <i class="icon-angle-right home_icon"></i> <span class="sub_litle_m">CMS Manage</span><i class="icon-angle-right home_icon"></i> <span>Edit Cms Page</span></div>
  	</div>
  </div>
  
  
<div class="main_subdiv">
	<div class="gird_button">
	        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
	</div>    
	  
	<div class="clear10"></div>
	
	<div align="left" class="grid_table_box">
	  
  <?php echo $this->Form->create('Cmspage', array('id' => 'frm_cmspage', 'name'=>'frm_cmspage', 'enctype' => 'multipart/form-data')); ?>
<?php if($id==0){ ?>
        <div class="clear10"></div>
        <div class="row">
          <div class="col-md-3 sb_left_pad"><?php echo $this->Html->link('Upload Images', array('controller'=>'CmsImages','action' => 'add',$id), array('class' => 'btn btn-primary'));?></div>
         <div class="col-md-9 sb_left_mar">&nbsp;</div>
        </div>
	<?php }?>
	<div class="clear10"></div>
	<div class="row">
	  <div class="col-md-3 sb_left_pad"><?php echo __('Title')?><sup class="MandatoryFields">*</sup></div>
	  <div class="col-md-6 sb_left_mar">
    		<?php echo $this->Form->input('id',array('type'=>'hidden','class' => 'input_textbox','label' => false)); ?>
        <?php echo $this->Form->input('title',array('class' => 'form-control','label' => false,'required' => 'required','div'=>false)); ?>
       
    		
	  </div>
    <div class="col-md-3">&nbsp;</div>
	</div>
 
 	<div class="clear10"></div>
  
	<div class="row">
	  <div class="col-md-3 sb_left_pad">&nbsp;</div>
	  <div class="col-md-3 sb_left_mar"><?php echo $this->Form->submit('Update', array('type'=>'submit', 'class'=>'btn btn-primary','label'=>false,'id'=>'submit_btn','div'=>false));     ?>
	<button class="cancel btn btn-warning" type="button" >Cancel</button>
    </div> 
	</div>	
<?php if($id==1){?>
	 <?php foreach($cms_image as $cmsimages){ ?>
					    <div class="clear10"></div>
  						<div class="row">
                <div class="col-md-3 sb_left_pad"><div style="float:left;"><?php echo $this->Html->image("admin_uploads/cms_uploads/".$cmsimages['CmsImage']['image'], array('border' =>'0','width'=>310,'height'=>130))?></div></div>
                <div class="col-md-6 sb_left_mar"> <?php echo $this->Html->link(
									$this->Html->image("delete.png", array('border' =>'0','style'=>'padding-left:3%')),
									array('controller'=>'CmsImages','action'=>'delete',$id,$cmsimages['CmsImage']['id'],),
									array('escape' => false),
									"Are you sure you wish to delete this image?"
							 );	
							 ?></div> 
              </div>	  
   <?php }?>
<?php }?>
	<div class="clear10"></div>
	<?php echo $this->Form->end(); ?>

	</div>
		
</div>	
<script>
$(document).ready(function(){
   $('#setting').addClass('sb_active_opt');
   $('#setting').removeClass('has_submenu');
   $('#cmspage_active').addClass('sb_active_subopt_active');
   
}) ;

	$('.cancel').click(function(){
		//window.location = "<?php //echo Router::url(array('controller'=>'Categories','action'=>'admin_index'));?>";
		history.go(-1);
	});
</script>
	
