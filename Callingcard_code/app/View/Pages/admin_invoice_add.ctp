<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $title; ?></div>
    <div class="sub_title"><i class="icon-list-alt home_icon"></i> <span class="sub_litle_m">Custom Invoice</span> <i class="icon-angle-right home_icon"></i> <span class="sub_litle_m">Add Invoice</span></div>
  </div>
</div>

<div class="main_subdiv">
	<div class="gird_button">
	        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
	</div>    
	  
	<div class="clear10"></div>
	
	<div align="left" class="grid_table_box">
	  <?php echo $this->Form->create('Invoice',array("enctype"=>"multipart/form-data",'onSubmit'=>'return ValidateForm()')); ?>
       <?php if($this->Session->read('Auth.User.role_id')==1){ //if distributor?>
        <div class="row">
          <div class="col-md-3 sb_left_pad"><?php echo __('Select Mediator')?></div>
          <div class="col-md-6 sb_left_mar">
            <?php
            echo $this->Form->input ( 'added_by_user', array (
                'class' => 'form-control',
                'required' => true,
                'type' => 'select',
                'options' => @$mediator_list,
                'value' => @$mediator_id,
               	'label' => false,
				'empty' => 'Select Mediator' 
            ) );
            ?>
             </div>
        </div>		
      	<div class="clear10"></div>
<?php }?>
    <div class="row">
		    <div class="col-md-3 sb_left_pad"><?php echo __('Select Retailer')?></div>
		    <div class="col-md-6 sb_left_mar">
		        <?php
						echo $this->Form->input ( 'user_id', array (
								'class' => 'form-control',
								'required' => true,
								'type' => 'select',
								'options' => @$retailer_list,
								'value' => @$retailer_id,
								'empty' => 'Select Retailer',
								'label' => false 
						) );
						?>
		    </div>
		    <div class="col-md-3">
		    	&nbsp;
		    </div>
		 </div>
		<div class="clear10"></div>
    
		<div class="row">
		    <div class="col-md-3 sb_left_pad"><?php echo __('Invoice Date')?></div>
		    <div class="col-md-6 sb_left_mar">
		        <div class="input-group">
              <div class="input-group-addon"
                style="padding: 1px 12px; font-size: 13px;">
                <?php  echo $this->Html->image(IMAGE_PATH.'/images/calender.png',array('alt'=>'Calender','class'=>'','border'=>'0','div'=>false));?>
                              <div
                  style="color: #4570B8; float: left; cursor: pointer;"
                  class="reset"><?php echo __('Reset')?></div>
              </div>
                        <?php
																								
							echo $this->Form->input ( 'invoice_date_month', array (
													'label' => false,
													'class' => 'form-control',
													'style' => 'background-color:#FFF;',
													'readonly' => 'readonly',
													'type' => 'text',
													'placeholder' => __ ( 'Invoice Date' ) 
											) );
											?>
                   </div>
		    </div>
		    <div class="col-md-3">
		    	&nbsp;
		    </div>
		 </div>
		<div class="clear10"></div>
		
		<div class="row">
		    <div class="col-md-3 sb_left_pad"><?php echo __('Upload Custom Invoice Pdf')?></div>
		    <div class="col-md-6 sb_left_mar">
		        <?php echo $this->Form->input('file_name',array('type'=>'file','label' => false)); ?>
		    </div>
		    <div class="col-md-3">
		    	&nbsp;
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
	$( "#InvoiceInvoiceDateMonth" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"dd.mm.yy",
			//yearRange: "-10:+0", // last 10  years
			maxDate: 0,//new Date(currentYear, currentMonth, currentDate),
		});
	$('.reset').click(function(){
		$("#InvoiceInvoiceDateMonth").val('');
	});
});

$('#InvoiceAddedByUser').change(function(){
  
  var mediator_id = $(this).val();
  $.ajax({
		  beforeSend: function (XMLHttpRequest){
			 $("#loading-image").fadeIn();
		  },
		  complete: function (XMLHttpRequest, textStatus) {
			$("#loading-image").fadeOut();
		  },
		  url: "<?php echo Router::url(array('controller'=>'Reports','action'=>'get_retailers'));?>", 
		  type: "POST",
		  data: ({id : mediator_id}),
		  dataType: 'json',
		  success: function(json){
			$('#InvoiceUserId').html('');
			$('#InvoiceUserId').html('<option value="">Select Retailer</option>');
			$.each(json, function(i, value) {
			        $('#InvoiceUserId').append($('<option>').text(value).attr('value', i));
		    });
		  }
		});
});

$('.cancel').click(function(){
	
    var url = "<?php echo $this->Html->url(array('controller'=>'Pages','action'=>'invoice_list','admin'=>'true'));?>";
    window.location.href = url;
	//history.go(-1);
});
function ValidateForm(){
	if($('#InvoiceInvoiceDateMonth').val()==''){
		alert('Please enter invoice date');
		$('#InvoiceInvoiceDateMonth').focus();
		return false;
	}
}
$(document).ready(function(){
   $('#custom_invoice_opt').addClass('sb_active_single_opt');
 }) ;
</script>
