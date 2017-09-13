<style type="text/css">
.hidden_field{
	display:none;
}
</style>

<div class="row">
  <div class="col-md-12">
     <div class="page_title"><?php echo $title_for_layout; ?></div>
     <div class="sub_title"><i class="icon-user home_icon"></i> <span class="sub_litle_m">Retailer</span> <i class="icon-angle-right home_icon"></i> <span class="sub_litle_m">Manage Fund</span><span><i class="icon-angle-right home_icon"></i> <span>Add</span></div>
  </div>
</div>

<div class="main_subdiv">
	<div class="gird_button">
	        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
	</div>    
	  
	<div class="clear10"></div>
	
	<div align="left" class="grid_table_box">
		<?php echo $this->Form->create('Transaction'); ?>
		<div class="clear10"></div>
		
		<div class="row">
		  <div class="col-md-4 sb_left_pad"><?php echo __('Select Retailer')?><sup class="MandatoryFields">*</sup></div>
		  <div class="col-md-6 sb_left_mar">
		      <?php 
								echo $this->Form->input('user_id',array('class'=>'form-control',
		                                                'options'=>$retailer_list,
		                                                'required' =>true,
		                                                 'empty'=>'Select Retailer',
		                                                'label'=>false)); 
					?>
		  </div>
		  <div class="col-md-2">&nbsp;</div>
		</div>
		
		<div class="clear10"></div>
		<div class="row">
		  <div class="col-md-4 sb_left_pad"><?php echo __('Payment Mode')?><sup class="MandatoryFields">*</sup></div>
		  <div class="col-md-6 sb_left_mar">
		      <?php 
								
								$pay_options =array('1'=>__('Cash'),'2'=>__('Cheque'),'3'=>__('Other'));
								echo $this->Form->input('payment_mode',array('class'=>'form-control',
		                                                'options'=>$pay_options,
		                                                'required' =>true,
		                                                 'empty'=>'Select Mode',
		                                                'label'=>false)); 
					?>
		  </div>
		  <div class="col-md-2">&nbsp;</div>
		</div>
		
		<div class="clear10 hidden_field"></div>
		<div class="row hidden_field">
		  <div class="col-md-4 sb_left_pad"><?php echo __('Bank Name')?><sup class="MandatoryFields">*</sup></div>
		  	<div class="col-md-6 sb_left_mar">
			<?php echo $this->Form->input('bank_name',array('class'=>'form-control not_empty_first',
			'placeholder'=>__('Bank Name'),
							'required'=>false,
							'type'=>'text',
			'label'=>false)); ?>
		  </div>
		  <div class="col-md-2">&nbsp;</div>
		</div>
		
		<div class="clear10 hidden_field"></div>
		<div class="row hidden_field">
		  <div class="col-md-4 sb_left_pad"><?php echo __('Cheque Number')?><sup class="MandatoryFields">*</sup></div>
		  <div class="col-md-6 sb_left_mar">
				<?php echo $this->Form->input('check_number',array('class'=>'form-control not_empty_first',
						'placeholder'=>__('Cheque Number'),
						'required'=>false,
						'onkeypress'=>'return isNumber(event);',
						'maxlength'=>10,
						'type'=>'text',
						'label'=>false)); ?>
		  </div>
		  <div class="col-md-2">&nbsp;</div>
		</div>
		
		<div class="clear10"></div>
		<div class="row">
		  <div class="col-md-4 sb_left_pad"><?php echo __('Fund Amount')?><?php echo " ( Limit : "."&euro;".$balance." )";?><sup class="MandatoryFields">*</sup></div>
		  <div class="col-md-6 sb_left_mar">
				<?php echo $this->Form->input('total_amount',array('class'=>'form-control amount_validation',
						'placeholder'=>__('Fund Amount'),
						'required' =>true,
						'type'=>'text',
						'label'=>false)); ?>
		  </div>
		  <div class="col-md-2">&nbsp;</div>
		</div>
		
		<div class="clear10"></div>
		<div class="row">
		  <div class="col-md-4 sb_left_pad"><?php echo __('Remarks')?><sup class="MandatoryFields"></sup></div>
		  <div class="col-md-6 sb_left_mar">
				<?php echo $this->Form->input('remarks',array('class'=>'form-control not_empty_first',
						'placeholder'=>__('Remarks'),
						'required' =>false,
						'label'=>false)); ?>
		  </div>
		  <div class="col-md-2">&nbsp;</div>
		</div>
		
		<div class="clear10"></div>
		<div class="row">
		  <div class="col-md-4 sb_left_pad">&nbsp;</div>
		  <div class="col-md-3 sb_left_mar">
		  <?php echo $this->Form->submit('Save', array('type'=>'submit', 
					'class'=>'btn btn-primary',
					'label'=>false,
					'id'=>'submit_btn',
					'onclick'=>'return validate_form()',
					'div'=>false));
		         ?>
				<?php echo $this->Form->button(__('Cancel'), array('type' => 'button',
							'class'=>'btn btn-warning cancel',
							'label'=>false,
							'div'=>false));?>
	  </div> 
		</div>	
		  
		<div class="clear10"></div>
		
		<?php echo $this->Form->end();?>
		
	</div>

</div>
<script type="text/javascript">

$(document).ready(function(){

 <?php if( isset($this->request->data['Transaction']['payment_mode'])) { ?>
 var payment_mode = "<?php echo $this->request->data['Transaction']['payment_mode']; ?>";
 $('TransactionPaymentMode').val(payment_mode);
 
 if(payment_mode == 2)
 {
	 	$('.hidden_field').css('display','block');
		$("#TransactionCheckNumber").prop('required',true);
 		$("#TransactionBankName").prop('required',true);
 }
 else
 {
	 	$('.hidden_field').css('display','none');
		$("#TransactionCheckNumber").prop('required',false);
 		$("#TransactionBankName").prop('required',false);
 }
 <?php } ?>

});

$('.cancel').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'manage_fund_retailer','admin'=>'true'));?>";
	   window.location.href = url;
	 //history.go(-1);
});

$('#add_button').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'add_fund_parent_retailer','admin'=>'true'));?>";
	window.location.href = url;
});

$('#TransactionPaymentMode').change(function(){
    
		var mode = $(this).val();
    if(mode == 2)
		{
			$('.hidden_field').css('display','block');
			$("#TransactionCheckNumber").prop('required',true);
  		$("#TransactionBankName").prop('required',true);
		}
		else
		{
			$('.hidden_field').css('display','none');
			$("#TransactionCheckNumber").prop('required',false);
  		$("#TransactionBankName").prop('required',false);
		}
});

function validate_form()
{
	var amount = $('#TransactionTotalAmount').val();
	var id = $('#TransactionUserId').val();
	var mode = $('#TransactionPaymentMode').val();
	var bank = $('#TransactionBankName').val();
	var check = $('#TransactionCheckNumber').val();



	var balance =parseFloat("<?php echo $balance ; ?>");
	if(balance<amount)
	{
		alert("<?php echo 'You can not allocate more than '.'&euro;'.$balance;?>");
		return false;
	}
	else
	{
		
		if(amount.trim().length != 0 && id.length != 0 && mode.length != 0)
		{
			if(mode == 1 || mode == 3 )
			{
				  $("#loading-image").fadeIn();
			     return true;
			}
			else if(bank.trim().length != 0 && check.trim().length != 0  && mode == 2)
			{
	             $("#loading-image").fadeIn();
			     return true;
			}
			else
			{
				if(check.trim().length == 0 && mode == 2)
				$('#TransactionCheckNumber').css('border','1px solid #F00');
			    else
		        $('#TransactionCheckNumber').css('border','1px solid #CCC');

			    if(bank.trim().length == 0 && mode == 2)
				$('#TransactionBankName').css('border','1px solid #F00');
			    else
		        $('#TransactionBankName').css('border','1px solid #CCC');
	            
	            if(amount.trim().length == 0)
				$('#TransactionTotalAmount').css('border','1px solid #F00');
			    else
			    $('#TransactionTotalAmount').css('border','1px solid #CCC');

				return false;
			}
			
		}
		else
		{
	        if(id.trim().length == 0)
			$('#TransactionUserId').css('border','1px solid #F00');
		    else
		    $('#TransactionUserId').css('border','1px solid #CCC');

		    if(mode.trim().length == 0)
			$('#TransactionPaymentMode').css('border','1px solid #F00');
		    else
		    $('#TransactionPaymentMode').css('border','1px solid #CCC');


	        if(amount.trim().length == 0)
			$('#TransactionTotalAmount').css('border','1px solid #F00');
		    else
		    $('#TransactionTotalAmount').css('border','1px solid #CCC');
		    	

		    if(bank.trim().length == 0 && mode == 2)
			$('#TransactionBankName').css('border','1px solid #F00');
		    else
		    $('#TransactionBankName').css('border','1px solid #CCC');


		    if(check.trim().length == 0 && mode == 2)
			$('#TransactionCheckNumber').css('border','1px solid #F00');
		    else
		    $('#TransactionCheckNumber').css('border','1px solid #CCC');
			

			return false;
			
		}
		
	}
}

$(document).ready(function(){
	   $('#retailer').addClass('sb_active_opt');
	   $('#retailer').removeClass('has_submenu');
	   $('#manage_fund').addClass('sb_active_subopt_active');
	}) ;
</script>