<style type="text/css">
.hidden_field{
	display:none;
}
</style>
<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
 <?php if($user_role_id == 2 ) {?>
  <div class="sub_title">
  		<i class="icon-user home_icon"></i> 
  		<span class="sub_litle_m">Mediator</span> 
  		<i class="icon-angle-right home_icon"></i>
    	<span class ="sub_litle_m">Manage Mediator Fund</span>
    	<i class="icon-angle-right home_icon"></i>
    	<span class ="sub_litle_m">Fund Allocation</span>
  		<i class="icon-angle-right home_icon"></i>
        <span>Edit</span> 
  </div>
 <?php } else { ?>
   <div class="sub_title">
        <i class="icon-user home_icon"></i> 
        <span class="sub_litle_m">Retailer</span> 
        <i class="icon-angle-right home_icon"></i>
        <span class ="sub_litle_m">Manage Fund</span>
        <i class="icon-angle-right home_icon"></i>
        <span class ="sub_litle_m">Fund Allocation</span>
        <i class="icon-angle-right home_icon"></i>
          <span>Edit</span> 
    </div>

 <?php } ?>
  
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
    </div>    
  
  <div class="clear10"></div>
  <div align="left" class="grid_table_box">
    <?php echo $this->Form->create('FundAllocate'); ?>
<div class="clear10"></div>
<div class="row">
   <div class="col-md-3 sb_left_pad"><?php echo $user_type ;?><sup class="MandatoryFields">*</sup></div>
  <div class="col-md-6 sb_left_mar">
      <?php 
						echo $this->Form->input('user_id',array('class'=>'form-control',
	                        'options'=>$user_list,
	                        'required' =>true,
	                        'value'=>@$mediator_id,
	                        'disabled'=>'disabled',
	                        'empty'=>'Select Mediator',
	                        'label'=>false)); 
	  ?>
	  <?php 
			echo $this->Form->input('id',array(
                'type'=>'hidden',
                'value'=>$this->request->data['FundAllocate']['id'])); 
	  ?>
  </div>
  <div class="col-md-3">&nbsp;</div>
</div>

<div class="clear10"></div>
<div class="row">
  <div class="col-md-3 sb_left_pad"><?php echo __('Payment Mode')?><sup class="MandatoryFields">*</sup></div>
  <div class="col-md-6 sb_left_mar">
      <?php 
			$pay_options =array('1'=>__('Cash'),'2'=>__('Cheque'),'3'=>__('Other'));
			echo $this->Form->input('payment_mode',array('class'=>'form-control',
            'options'=>$pay_options,
            'empty'=>'Select Mode',
            'disabled'=>'disabled',
            'value'=>@$selected_mode,
            'label'=>false)); 
	?>
  </div>
  <div class="col-md-3">&nbsp;</div>
</div>

<div class="clear10 hidden_field"></div>
<div class="row hidden_field">
  <div class="col-md-3 sb_left_pad"><?php echo __('Bank Name')?><sup class="MandatoryFields">*</sup></div>
  	<div class="col-md-6 sb_left_mar">
      <?php echo $this->Form->input('bank_name',array('class'=>'form-control not_empty_first',
				'placeholder'=>__('Bank Name'),
				 'disabled'=>'disabled',
				'required'=>false,
				'type'=>'text',
				'label'=>false)); ?>
  </div>
  <div class="col-md-3">&nbsp;</div>
</div>

<div class="clear10 hidden_field"></div>
<div class="row hidden_field">
  <div class="col-md-3 sb_left_pad"><?php echo __('Cheque Number')?><sup class="MandatoryFields">*</sup></div>
  <div class="col-md-6 sb_left_mar">
     <?php echo $this->Form->input('check_number',array('class'=>'form-control not_empty_first',
                'placeholder'=>__('Cheque Number'),
                'disabled'=>'disabled',
                'required'=>false,
                //'onkeypress'=>'return isNumber(event);',
                'maxlength'=>10,
                'type'=>'text',
                'label'=>false)); ?>
  </div>
  <div class="col-md-3">&nbsp;</div>
</div>

<div class="clear10"></div>
<div class="row">
  <div class="col-md-3 sb_left_pad"><?php echo __('Fund Amount')?><sup class="MandatoryFields">*</sup></div>
  <div class="col-md-6 sb_left_mar">
     <?php echo $this->Form->input('total_amount',array('class'=>'form-control amount_validation',
            'placeholder'=>__('Fund Amount'),
             'disabled'=>'disabled',
             'type'=>'text',
            'label'=>false)); ?>
  </div>
  <div class="col-md-3">&nbsp;</div>
</div>

<div class="clear10"></div>
<div class="row">
  <div class="col-md-3 sb_left_pad">
    <?php echo __('Reduce Fund Amount');?>
      
      <sup class="MandatoryFields">*</sup>
        <span style="font-size:12px;">        
         <?php 
            if($balance < $this->request->data['FundAllocate']['total_amount'])
            $deductable_balance =$balance;
            else  
            $deductable_balance = $this->request->data['FundAllocate']['total_amount']; 
            echo "<br>( Max Deduction: &euro;".$deductable_balance.")";
        ?>
        </span>
    </div>
  <div class="col-md-6 sb_left_mar">
     <?php echo $this->Form->input('reduce_amount',array('class'=>'form-control amount_validation',
            'placeholder'=>__('Reduce Fund Amount'),
            'required' =>true,
            'type'=>'text',
            'label'=>false)); ?>
  </div>
  <div class="col-md-3">&nbsp;</div>
</div>

<div class="clear10"></div>
<div class="row">
  <div class="col-md-3 sb_left_pad"><?php echo __('Remarks')?><sup class="MandatoryFields"></sup></div>
  <div class="col-md-6 sb_left_mar">
		<?php echo __('Fund updated : &euro;X to &euro;Y.')?>
  </div>
  <div class="col-md-3">&nbsp;</div>
</div>


<div class="clear10"></div>
<div class="row">
  <div class="col-md-3 sb_left_pad"><?php echo __('Reason')?><sup class="MandatoryFields"></sup></div>
  <div class="col-md-6 sb_left_mar">
		<?php echo $this->Form->input('remarks',array('class'=>'form-control not_empty_first',
					'placeholder'=>__('Remarks'),
					'required' =>false,
					'label'=>false)); ?>
  </div>
  <div class="col-md-3">&nbsp;</div>
</div>

<div class="clear10"></div>
<div class="row">
  <div class="col-md-3 sb_left_pad">&nbsp;</div>
  <div class="col-md-3 sb_left_mar"><?php echo $this->Form->submit('Update', array('type'=>'submit', 'class'=>'btn btn-primary','label'=>false,'id'=>'submit_btn','div'=>false,'onclick'=>'return check_valid();')); ?>
		<?php echo $this->Form->button(__('Cancel'), array('type' => 'button',
		'class'=>'btn btn-warning cancel',
		'label'=>false,
		'div'=>false));?>
  </div> 
</div>	
  
<div class="clear10"></div>

<?php echo $this->Form->end();?>
		
  </div>
  <div class="clear10"></div>
  </div>
</div>

<script type="text/javascript">

function check_valid()
{
	
  var amount = $('#FundAllocateReduceAmount').val();
  var previous_amount = "<?php echo $deductable_balance;?>";

  if(amount.trim().length != 0)
	{
		if(parseFloat(amount) > parseFloat(previous_amount))
		{
		    
		    alert("<?php echo __('Reduce amount can not be greater than €'.$deductable_balance)?>");
			  return false;
		}
    else if(amount < 0.01)
    {
      alert("<?php echo __('Reduce amount can not be less than 0.01')?>");
      return false;
    }
		else
		{
		   var ans = confirm("Are you sure ? You want to update the fund amount ?")
		   if(ans)
		   {
		   	 $("#loading-image").fadeIn();
		     return true;
		   }
		   else
		   {
		   	 return false;
		   }
		   
		}
		
	}
	else
	{
        if(amount.trim().length == 0)
        $('#FundAllocateReduceAmount').css('border','1px solid #F00');
        else
        $('#FundAllocateReduceAmount').css('border','1px solid #CCC');
        return false;
		
	}
}


$(document).ready(function(){

 <?php if( isset($this->request->data['FundAllocate']['payment_mode'])) { ?>
 var payment_mode = "<?php echo $this->request->data['FundAllocate']['payment_mode']; ?>";
 $('#FundAllocatePaymentMode').val(payment_mode);
 
 if(payment_mode == 2)
 {
 	$('.hidden_field').css('display','block');
 }
 else
 {
 	$('.hidden_field').css('display','none');
 }
 <?php } ?>

});

$('.cancel').click(function(){
	
    <?php
      if($this->Session->read('Auth.User.role_id') == 1)
      {
    ?>
      var url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'manage_fund','admin'=>'true'));?>";
	   <?php } else { ?>
      var url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'manage_fund_retailer','admin'=>'true'));?>";
     <?php } ?> 
     window.location.href = url; 
	//history.go(-1);
});


$(document).ready(function(){
  <?php
  if($this->Session->read('Auth.User.role_id') == 1)
  {
  ?>
   $('#Mediator').addClass('sb_active_opt');
   $('#Mediator').removeClass('has_submenu');
   $('#fund_m').addClass('sb_active_subopt_active');

   <?php } else { ?>
   $('#retailer').removeClass('has_submenu');
   $('#retailer').addClass('sb_active_opt');
   $('#manage_fund').addClass('sb_active_subopt_active');
   <?php } ?> 
	}) ;

</script>