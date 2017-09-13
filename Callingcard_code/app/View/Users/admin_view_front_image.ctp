<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $pageTitle; ?></div>
	<div class="sub_title"><i class="icon-user home_icon"></i> <span class="sub_litle_m"><?php echo __('Manage Front Images')?></span> <i class="icon-angle-right home_icon"></i> <span class="sub_litle_m"><?php echo __('View Front Image');?></span></div>
  </div>
</div>

<div class="main_subdiv">
		    <div class="gird_button">
		        <div class="main_sub_title rat_w"><?php echo $pageTitle; ?></div>
        		<button class="new_button back" type="button" style="cursor:pointer;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
    		</div>    
    
		  
		<div class="clear10"></div>
		<div align="left" class="grid_table_box">
			<?php echo $this->Form->create('FrontImage');  ?>
			  <div class="clear10"></div>
			  
  				<div class="row">
				  <div class="col-md-3 sb_left_pad"><?php echo __('Content English')?></div>
				  <div class="col-md-6 sb_left_mar">
			    	<?php echo $this->request->data['FrontImage']['content_english'];?>
				  </div>
			    <div class="col-md-3">&nbsp;</div>
				</div>
			  
			  <div class="clear10"></div>
				<div class="row">
				  <div class="col-md-3 sb_left_pad"><?php echo __('Content German')?></div>
				  <div class="col-md-6 sb_left_mar">
			    	<?php echo $this->request->data['FrontImage']['content_german'];?>
				  </div>
			    <div class="col-md-3">&nbsp;</div>
				</div>
			  
			  <div class="clear10"></div>
				<div class="row">
				  <div class="col-md-3 sb_left_pad"><?php echo __('Updated')?></div>
				  <div class="col-md-6 sb_left_mar">
			    	<?php echo date('d.m.Y h:i:s',strtotime($this->request->data['FrontImage']['updated']));?>
				  </div>
			    <div class="col-md-3">&nbsp;</div>
				</div>
			  
			  <?php
						if(isset($this->request->data['FrontImage']['image']) && !empty($this->request->data['FrontImage']['image']))
						{
							$image = $this->request->data['FrontImage']['image'];
						}
						else
						{
							$image ="";
						}
						?>	
			                    <div class="clear10"></div>
			                    <div class="row">
			                    <div class="col-md-3 sb_left_pad">&nbsp;</div>
			                    <div class="col-md-7 sb_left_mar">
			              	<?php 
						  	if(file_exists(WWW_ROOT.'img/front_images/'.$image))
							{
							echo $this->Html->image(IMAGE_PATH.'image.php?image=img/front_images/'.$image.'&amp;width=400&amp;height=150'
																				, array('alt' => '','border'=>'0','div'=>true));
							}
							else
							{
							 echo $this->Html->image(IMAGE_PATH.'image.php?image=img/no_bg_available.jpg&amp;width=400&amp;height=150'
																				, array('alt' => '','border'=>'0','div'=>true));
								
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
  var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'front_image','admin'=>'true'));?>";
  //window.location.href = url;
  history.go(-1);
});

$(document).ready(function(){
   $('#manage-front-image').addClass('sb_active_single_opt');
}) ;

</script>