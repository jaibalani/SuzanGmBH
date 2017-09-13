<style>
.checkbox_bootstrap {
    float: left;
    width: 250px;
}

.cancel{  
background-color: #E0E0E0;
color: #000;
border-color: #E0E0E0;
margin-top: 20px;
}

.gird_button{ border-bottom: 1px solid #D6D6D6; }

.btn-primary{ background-color: #418BCA !important; border-color:#418BCA !important; margin-top: 20px; }

.btn-warning{
background-color: #E0E0E0;
color: #000;
border-color: #E0E0E0;
}

.btn-warning:hover, .btn-warning:focus, .btn-warning:active, .btn-warning.active, .open .dropdown-toggle.btn-warning {
background-color: #E0E0E0;
color: #000;
border-color: #E0E0E0;
}

.form-control{ border-radius:0px; color: #C2C8BC; }

.grid_table_box .row .col-md-3{ padding-left: 40px; }

.grid_table_box .row .col-md-6{ margin-left: -50px; }

#submit_btn{ margin-left: -74px; }
</style>
<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <div class="sub_title"><i class="icon-list-alt home_icon"></i> <span class="sub_litle_m">Product Master</span> <i class="icon-angle-right home_icon"></i> <span class="sub_litle_m">Card Management</span><i class="icon-angle-right home_icon"></i> <span class="sub_litle_m">Merge Pins</span><i class="icon-angle-right home_icon"></i> <span>Merge</span></div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title card_w"><?php echo $title_for_layout; ?></div>
    </div>    
  
  <div class="clear10"></div>
  <div align="left" class="grid_table_box">
<?php echo $this->Form->create('PinsCard',array()); ?>

<?php if(isset($all_cards) && !empty($all_cards)){
				echo $this->Form->input('merge_from_c_id.', array( 'type' => 'radio',
                                     'separator'=> '</div><div>',
                                     'before' => '<div>',
                                     'after' => '</div>',
                                     'options' =>  $all_cards,
                                     'label' => true,
																		 'hiddenField' => false,
																		 "legend" => false
                                   )
                                );
		 }else{?> 
				<div class="row">
        	<div class="col-md-3">&nbsp;</div>
          <div class="col-md-9">No Cards Found</div>
        </div>
<?php }?>

<div class="clear10"></div>
	<div class="row">
	  <div class="col-md-3">&nbsp;</div>
	  <div class="col-md-3"><?php echo $this->Form->submit('Update', array('type'=>'submit', 'class'=>'btn btn-primary','label'=>false,'id'=>'submit_btn','div'=>false)); ?>
     <?php echo $this->Form->button(__('Cancel'), array('type' => 'button',
																											'class'=>'btn btn-warning cancel',
																											'label'=>false,
																											'style'=>'cursor:pointer;'));?>
    </div> 
	</div>	
		
	<div class="clear10"></div>

<?php echo $this->Form->end(); ?>
      </div>
  <div class="clear10"></div>
  </div>
</div>

<script type="text/javascript">

$(document).ready(function(){
	$('.cancel').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'index','admin'=>'true',$id));?>";
	//window.location.href = url;
	history.go(-1);
});
    $('#product').addClass('sb_active_opt');
    $('#product').removeClass('has_submenu');
    $('#addcard_active').addClass('sb_active_subopt_active');
});

</script>