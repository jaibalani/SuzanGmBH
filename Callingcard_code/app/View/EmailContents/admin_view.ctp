<style>
.checkbox_bootstrap {
    float: left;
    width: 250px;
}
</style>
<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
    <div class="sub_title"><i class="icon-gear home_icon"></i> <span class="sub_litle_m">Settings</span> <i class="icon-angle-right home_icon"></i> <span class="sub_litle_m">Email Content</span><i class="icon-angle-right home_icon"></i> <span>View</span></div>
  </div>
</div>
<div class="clear10"></div>

<div class="main_subdiv">
	
    <div class="gird_button">
        <div class="main_sub_title rat_w"><?php echo $title_for_layout; ?></div>
        <button class="new_button back" type="button" style="cursor:pointer;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
    </div>    
	  
	<div class="clear10"></div>
	
	<div align="left" class="grid_table_box">

		<div class="row">
		  <div class="col-md-3 sb_left_pad"><b><?php echo __('Id')?>:</b></div>
		  <div class="col-md-6 sb_left_mar">
		      <?php echo $email_content['EmailContent']['id']; ?>
		  </div>
		  <div class="col-md-3">&nbsp;</div>
		</div>
		<div class="clear10"></div>
		<div class="row">
		  <div class="col-md-3 sb_left_pad"><b><?php echo __('Title')?>:</b></div>
		  <div class="col-md-6 sb_left_mar">
		      <?php echo $email_content['EmailContent']['title']; ?>
		  </div>
		  <div class="col-md-3">&nbsp;</div>
		</div>
		<div class="clear10"></div>
		<div class="row">
		  <div class="col-md-3 sb_left_pad"><b><?php echo __('Subject')?>:</b></div>
		  <div class="col-md-6 sb_left_mar">
		      <?php echo $email_content['EmailContent']['subject']; ?>
		  </div>
		  <div class="col-md-3">&nbsp;</div>
		</div>
		<div class="clear10"></div>
		<div class="row">
		  <div class="col-md-3 sb_left_pad"><b><?php echo __('Message')?>:</b></div>
		  <div class="col-md-6 sb_left_mar">
		      <?php echo $email_content['EmailContent']['message']; ?>
		  </div>
		  <div class="col-md-3">&nbsp;</div>
		</div>
		<div class="clear10"></div>
		<div class="row">
		  <div class="col-md-3 sb_left_pad"><b><?php echo __('Keywords')?>:</b></div>
		  <div class="col-md-6 sb_left_mar">
		      <?php echo $email_content['EmailContent']['keywords']; ?>
		  </div>
		  <div class="col-md-3">&nbsp;</div>
		</div>
		
	</div>
	
</div>

<script type="text/javascript">
$('.back').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'EmailContents','action'=>'index','admin'=>'true'));?>";
	//window.location.href = url;
	history.go(-1);

});
$(document).ready(function(){
    $('#setting').addClass('sb_active_opt');
    $('#setting').removeClass('has_submenu');
    $('#emailcontent_active').addClass('sb_active_subopt_active');
}) ;
</script>