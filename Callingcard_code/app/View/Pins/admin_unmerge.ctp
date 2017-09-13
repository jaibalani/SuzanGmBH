
<div class="sub_title"><i class="icon-list-alt home_icon"></i> <span class="sub_litle_m">Product Master</span> <i class="icon-angle-right home_icon"></i> <span class="sub_litle_m">Card Management</span><i class="icon-angle-right home_icon"></i> <span class="sub_litle_m">Unmerge Pins</span><i class="icon-angle-right home_icon"></i> <span>Unmerge</span></div>


<div class="row">
  <div class="col-md-12">
    <h1><?php echo $title_for_layout; ?></h1>
  </div>
</div>
<div class="clear10"></div>
<?php echo $this->Form->create('PinsCard',array()); ?>

<?php if(isset($all_cards) && !empty($all_cards)){
				echo $this->Form->input('unmerge_from_c_id.', array( 'type' => 'radio',
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
<script type="text/javascript">

$(document).ready(function(){

	$('#product').addClass('sb_active_opt');
    $('#product').removeClass('has_submenu');
    $('#addcard_active').addClass('sb_active_subopt_active');

	$('.cancel').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'index','admin'=>'true',$id));?>";
	//window.location.href = url;
	history.go(-1);
});
});



</script>