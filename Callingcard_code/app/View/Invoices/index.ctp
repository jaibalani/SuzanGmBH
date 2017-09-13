<style type="text/css">
.dwnload{
	cursor : pointer;
}
#date-panel .row{ margin-left: 0px !important; margin-right: 0px !important; }
#date-panel .row .col-xs-12{ padding: 0px; }
#date-panel .row .col-xs-12 label{ padding-left: 0px; }
.sb-go-align{ text-align: left; }
</style>
<?php ?>
<div class="right-part right-panel">
    <!--Invoice Start-->
    <div class="sb-page-title">
        <strong><?php echo __('Invoice')?></strong>
    </div>

    <div id="date-range">
        <div id="date-panel">
           <?php 
		      echo $this->Form->create('Invoice',array('id'=>'invoice_form','action'=>'get_invoice_pdf'));
			  echo $this->Form->input('invoice_data',array('type'=>'hidden','id'=>'invoice_data'));
			  echo $this->Form->input('invoice_number',array('type'=>'hidden','id'=>'invoice_number'));
			  echo $this->Form->end();
		   
		   ?>
            <form action="#" method="post">
            <div class="row">
            	<div class="col-xs-4">
            		<div class="col-xs-12">
            			<div class="input-group">
			                <div class="input-group-addon" style="padding:1px 12px; font-size:13px;">
						 		<?php  echo $this->Html->image(IMAGE_PATH.'/images/calender.png',array('alt'=>'Calender','class'=>'','border'=>'0','div'=>false));?>
						 		<div style="color:#4570B8; float:left;cursor:pointer;" class="reset_from"><?php echo __('Reset') ?></div>
			                 </div>
			                 <?php echo $this->Form->input('datepicker1',array(
			                                            'label'    => false, 
			                                            'class'=>'form-control',
														'id'=>'datepicker1',
			                                            'value'=>@$date_set_start,
														'style'=>'background-color:#FFF',
			                                            'readonly' => 'readonly',
														'type'=>'text', 
														'placeholder'=>__('From Date:')
														));
							?>
			            </div>
            		</div>
            	</div>
            	<div class="col-xs-4">
            		<div class="col-xs-12">
            			<div class="input-group">
			                <div class="input-group-addon" style="padding:1px 12px; font-size:13px;">
			                    <?php  echo $this->Html->image(IMAGE_PATH.'/images/calender.png',array('alt'=>'Calender','class'=>'','border'=>'0','div'=>false));?>
			                     <div style="color:#4570B8; float:left;cursor:pointer;" class="reset_to"><?php echo __('Reset') ?></div>
			                </div>
						   <?php echo $this->Form->input('datepicker2',array(
			                                    'label'    => false, 
			                                    'class'=>'form-control',
			                                    'id'=>'datepicker2',
			                                    'value'=>@$date_set_end,
			                                    'style'=>'background-color:#FFF',
			                                    'readonly' => 'readonly', 
			                                    'type'=>'text',
			                                    'placeholder'=>__('To Date:')
			                                    ));
			                  ?>
			            </div>
            		</div>
            	</div>
            	<div class="col-xs-4 sb-go-align">
            		<div class="col-xs-12">
            			<?php echo $this->Form->input(__('Go'), array('type'=>'submit', 'class'=>'button-gradient','label'=>false,'id'=>'submit_btn','div'=>false ,'style' => 'margin-left:1px;','onclick'=>'return check_fields();')); ?>
            		</div>
            	</div>
            </div>
            </form>
        </div>

    </div>
                        <div id="invoice">

                            <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th align="center" style="width:150px;"><?php echo __('Invoice No.');?></th>
                                    <th style="width:150px;"><?php echo __('Invoice Description');?></th>
                                    <th style="width:150px;"><?php echo __('Invoice Type');?></th>
                                    <th><?php echo __('Invoice Start');?></th>
                                    <th><?php echo __('Created');?></th>
                                    <th><?php echo __('Action');?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php if( !empty($get_invoice_data)) {
											foreach ($get_invoice_data as $data) 
											{
								  ?>
                                  <tr>
                                    <td><?php echo $data['Invoice']['invoice_number'] ;?>
                                    </td>
                                    <td><?php echo $data['Invoice']['invoice_description'] ;?></td>
                                    <td>
                                    	<?php 
                                    	if(!empty($data['Invoice']['file_name']))
                                    	echo __('Custom'); 
                                    	else
                                    	echo __('Auto Generated');	
                                        ;?>
                                    	</td>
                                    <td><?php echo date('d.m.Y',strtotime($data['Invoice']['invoice_date_month']));?></td>
                                    <td><?php echo date('d.m.Y',strtotime($data['Invoice']['invoice_created']));?></td>
                                    <td>
                                    <?php  echo $this->Html->image(IMAGE_PATH.'/images/download.png',array('alt'=>'Download','class'=>'dwnload','border'=>'0','div'=>false,'onclick'=>'invoice_generate("'.$data['Invoice']['invoice_number'].'","'.$data['Invoice']['file_name'].'")'));?>
                                    </td>
                                  </tr>
								<?php } } else { ?>
                                 <tr>
                                    <td colspan="4" align="center"><?php echo __("No records found.");?></td>
                                  </tr>
								<?php } ?>                                  
                                </tbody>
                              </table>

                        </div>
                        <!--Invoice End-->
                    </div>


<script type="text/javascript">

$(document).ready(function(){
	//Highlight active menu
	$('#sb-opt-invoice').addClass('opt-selected');
});

$(function() {
	var date = new Date();
	var currentMonth = date.getMonth();
	var currentDate = date.getDate();
	var currentYear = date.getFullYear();

	
	$( "#datepicker1" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat:"dd.mm.yy",
		maxDate: new Date(currentYear, currentMonth, currentDate),
	});
	$( "#datepicker2" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat:"dd.mm.yy",
		maxDate: new Date(currentYear, currentMonth, currentDate),
	});
});

function check_fields()
{
	var range_start_date = $('#datepicker1').val();
	var range_end_date = $('#datepicker2').val();
	
	var length_start = range_start_date.length;
	var length_end = range_end_date.length;
 
	if((length_start == 0 && length_end !=0) || (length_start != 0 && length_end ==0))
	{
	 alert("<?php echo __('Either select both the dates or none.');?>");
	 return false;
	}
	else
	{
	/* Date Format d.m.Y*/
	var start = range_start_date.split(".");
	var end = range_end_date.split(".");
	
	/* Converting Y/M/D Format*/
	var new_start = start[2] + "/" + start[1] + "/" + start[0] + " 00:00:00"
	var new_start = new Date(new_start);
	
	var new_end = end[2] + "/" + end[1] + "/" + end[0] + " 00:00:00"
	var new_end = new Date(new_end);
	
	var start_timestamp = new_start.getTime();
	var end_timestamp = new_end.getTime();
	
	if(start_timestamp > end_timestamp)
	{
		 alert("<?php echo __('Invalid date range.');?>");
		 $("#datepicker1").css('border','1px solid #F00');
		 $("#datepicker2").css('border','1px solid #F00');
		 return false;
	}
  }
  return true; 
}

function invoice_generate(invoice_number ,file_name)
{
	if(file_name.length >0 )
	{
		var download = "<?php echo $this->Html->url(array('controller'=>'Invoices','action'=>'download'));?>/"+file_name;
   		window.location =download;
	}
	else
	{
	  $.ajax({
		  beforeSend: function (XMLHttpRequest){
			 $("#loading-image").fadeIn();
		  },
		  complete: function (XMLHttpRequest, textStatus) {
			$("#loading-image").fadeOut();
		  },
		  url: "<?php echo Router::url(array('controller'=>'Invoices','action'=>'generate_invoice'));?>", 
		  type: "POST",
		  data: ({invoice_number : invoice_number}),
		  dataType: 'html',
		  success: function(data){
			//console.log(data);
			$('#invoice_data').val(data);
			$('#invoice_number').val(invoice_number);
			$('#invoice_form').submit();
		  }
	  });		
	}
}

$('.reset_from').click(function () {
    $("#datepicker1").val('');
});

$('.reset_to').click(function () {
    $("#datepicker2").val('');
});
</script>