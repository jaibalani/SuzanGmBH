<style>
.checkbox_bootstrap {
    float: left;
    width: 250px;
}
</style>
<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
    <div class="sub_title"><i class="icon-list-alt home_icon"></i> <span class="sub_litle_m">PIN Management</span> <i class="icon-angle-right home_icon"></i> <span class="sub_litle_m"> Manage card PINs</span> <i class="icon-angle-right home_icon"></i> <span>Edit Pins</span></div>
  </div>
</div>

<div class="main_subdiv">
	<div class="gird_button">
	        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
	</div>    
	  
	<div class="clear10"></div>
	
	<div align="left" class="grid_table_box">
		<?php echo $this->Form->create('Pin',array()); ?>
		<div class="clear20"></div>
		<?php if(isset($CardType) && !empty($CardType)){//same cards subcategory?> 
					<div class="row">
		        <div class="col-md-3 sb_left_pad"><?php echo __('Select Card')?></div>
		        <div class="col-md-6 sb_left_mar"> <?php 
						echo $this->Form->input('PinsCard.pc_c_id', array("div"=>false, "label"=>false, "class"=>"form-control",'multiple' => true, 'options' => $CardType,'hiddenField'=>false));	?>
		        </div>
		       </div>
		       <div class="clear10"></div>
		<?php }?>
		<?php $status = array(
					'1' => 'Unused',
					'2'	=> 'Sold',
					'3' => 'Parked',
					'4' => 'Rejected',
					'5' => 'Returned'
		);?>
			<div class="row">
		  <div class="col-md-3 sb_left_pad"><?php echo __('Status')?></div>
		  <div class="col-md-6 sb_left_mar"> <?php 
		  echo $this->Form->input('Pin.p_status', array("div"=>false, "label"=>false, "class"=>"form-control", 'options' => $status,'type'=>'select','hiddenField'=>false));	?>
		  </div>
		 </div>
		 <div class="clear10"></div>
		<div class="row">
			  <div class="col-md-3 sb_left_pad">&nbsp;</div>
			  <div class="col-md-3 sb_left_mar"><?php echo $this->Form->submit('Update', array('type'=>'submit', 'class'=>'btn btn-primary','label'=>false,'id'=>'submit_btn','div'=>false)); ?>
              <?php echo $this->Form->button(__('Cancel'), array('type' => 'button',
						'class'=>'btn btn-warning cancel',
						'label'=>false,
						'style'=>'cursor:pointer;'));?>
		    </div> 
			</div>	
				
			<div class="clear10"></div>
		
		<?php echo $this->Form->end(); ?>
		
	</div>

</div>

<script type="text/javascript">

$(document).ready(function(){
	$('.cancel').click(function(){
		//var url = "<?php //echo $this->Html->url(array('controller'=>'Pins','action'=>'index','admin'=>'true'));?>";
		
		var url = "<?php echo $this->Html->url(array('controller'=>'Pins','action'=>'index','admin'=>'true',
			$this->Session->read('card_id_pin'),$this->Session->read('from_card_id_pin')));?>";
		window.location.href = url;
		
		//history.go(-1);
	});
});

$(document).ready(function(){
	   $('#pin').addClass('sb_active_opt');
	   $('#pin').removeClass('has_submenu');
	   $('#managecard_active').addClass('sb_active_subopt_active');
	}) ;

</script>