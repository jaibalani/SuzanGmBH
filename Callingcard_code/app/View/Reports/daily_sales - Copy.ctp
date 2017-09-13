
<div id="right_panel">
    
     <div id="title">
          <?php echo $title_for_layout; ?>
     </div>
     
     <div id="ds_date_range">
        <form action="#" method="post">
        <div id="ds_date_panel">
            <label><?php echo __('From Date:')?></label>
            <div class="input-group">
                <div class="input-group-addon" style="padding:1px 12px; font-size:13px;">
                    <?php  echo $this->Html->image(IMAGE_PATH.'/images/calender.png',array('alt'=>'Calender','class'=>'','border'=>'0','div'=>false));?>
                    <div style="color:#4570B8; float:left;cursor:pointer;" class="reset_from"><?php echo __('Reset')?></div>
                </div>
                 <?php echo $this->Form->input('datepicker1',array(
                                            'label'    => false, 
                                            'class'=>'form-control',
											'id'=>'datepicker1',
                                            'value'=>@$date_set_start,
											'style'=>'background-color:#FFF',
                                            'readonly' => 'readonly',
											'type'=>'text', 
											'placeholder'=>__('From Date')
											));
						  ?>
            </div>
            <label><?php echo __('To Date:')?></label>
            <div class="input-group">
                <div class="input-group-addon" style="padding:1px 12px; font-size:13px;">
                    <?php  echo $this->Html->image(IMAGE_PATH.'/images/calender.png',array('alt'=>'Calender','class'=>'','border'=>'0','div'=>false));?>
                    <div style="color:#4570B8; float:left;cursor:pointer;" class="reset_to"><?php echo __('Reset')?></div>
                </div>
                  <?php echo $this->Form->input('datepicker2',array(
                                            'label'    => false, 
                                            'class'=>'form-control',
											'id'=>'datepicker2',
                                            'value'=>@$date_set_end,
											'style'=>'background-color:#FFF',
                                            'readonly' => 'readonly',
											'type'=>'text', 
											'placeholder'=>__('To Date')
											));
						  ?>
            </div>
        </div>
        <div id="action" style="margin-left:0px; margin-top:15px;">
              <input type="button" name="filter" value="APPLY FILTER" class="button_gradient" id="filter_card_button"/>
        </div>
        <div id="extra_opt">
	        <?php  echo $this->Html->image(IMAGE_PATH.'/images/print.png',array('alt'=>'Print','class'=>'','border'=>'0','div'=>false));?>
			<?php  echo $this->Html->image(IMAGE_PATH.'/images/excel.png',array('alt'=>'Excel','class'=>'cursor_class','border'=>'0','div'=>false,'id'=>'excel_sales'));?>
        </div>
        </form>
   </div>
                    
    <div id="daily_reports">
        <table class="table table-bordered">
            <thead>
              <tr>
                <th><?php echo __('S. No.')?></th>  
                <th><?php echo __('Date')?></th>
                <th><?php echo __('Quantity')?></th>
                <th><?php echo __('Total Purchase')?></th>
                <th><?php echo __('Total Sales')?></th>
                <th><?php echo __('Action')?></th>
              </tr>
            </thead>
            <tbody>
             <?php $counter = 1;foreach ($get_sales_data as $data) {?>    
              <tr>
                <td><?php echo $counter;  $counter++; ?></td>
                <td><?php echo date('m/d/Y',strtotime($data['Sale']['sale_date']));?></td>
                <td><?php echo $data[0]['total_card']?></td>
                <td>&euro;<?php echo $data[0]['total_purchase']?></td>
                <td>&euro;<?php echo $data[0]['total_sales']?></td>
                <td>
                <?php  echo $this->Html->image(IMAGE_PATH.'/images/excel.png',array('alt'=>'Print','class'=>'','border'=>'0','div'=>false,'class'=>'cursor_class','onclick'=>'single_excel_report("'.$data["Sale"]["sale_date"].'")'));?>
                </td>
              </tr>
             <?php } ?> 
              
              
              <tr>
                <td colspan="2">Net Total</td>
                <td><?php echo $card_count;?></td>
				<td colspan="1" >&euro;<?php echo $total_purchase_amount;?></td>
                <td colspan="2" >&euro;<?php echo $total_sales_amount;?></td>
              </tr> 
              
            </tbody>
          </table>
        
    </div>
</div>

<script type="text/javascript">

$(document).ready(function(){
	//Highlight active menu
	$('#sb-opt-report').addClass('opt-selected');
	$('#sb-opt-report').next().toggle('sliderup');
	$('#sb-subopt-daily-report').addClass('opt-selected');
});

$('#filter_card_button').click(function(){

 var range_start_date = $('#datepicker1').val();
 var range_end_date = $('#datepicker2').val();
 
 
 var length_start = range_start_date.length;
 var length_end = range_end_date.length;
 
 if((length_start == 0 && length_end !=0) || (length_start != 0 && length_end ==0))
 {
	 alert("<?php echo __('Either select both the dates or none.');?>");
	 return;
 }
 else
 {
	/* Date Format MM/DD /YYYY*/
	var start = range_start_date.split("/");
	var end = range_end_date.split("/");
	
	var url_start_date = start[2] + "-" + start[0] + "-" + start[1]; 
	var url_end_date = end[2] + "-" + end[0] + "-" + end[1]; 

	/* Converting Y/M/D Format*/
	var new_start = start[2] + "/" + start[0] + "/" + start[1] + " 00:00:00"
   	var new_start = new Date(new_start);

	var new_end = end[2] + "/" + end[0] + "/" + end[1] + " 00:00:00"
   	var new_end = new Date(new_end);
	
	var start_timestamp = new_start.getTime();
	var end_timestamp = new_end.getTime();

	if(start_timestamp > end_timestamp)
	{
		 alert("<?php echo __('Invalid date range.');?>");
		 $("#datepicker1").css('border','1px solid #F00');
		 $("#datepicker2").css('border','1px solid #F00');
		 return;
	}
 }
 if(length_start != 0 && length_end !=0)
 {
 	var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'daily_sales'));?>/"+url_start_date+"/"+url_end_date;
 }
 else
 {
 	var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'daily_sales'));?>";
 }
 window.location.href = url;

});

$('.reset_from').click(function(){
	$("#datepicker1").val('');
});

$('.reset_to').click(function(){
	$("#datepicker2").val('');
});

$(function() {
	    
		var date = new Date();
		var currentMonth = date.getMonth();
		var currentDate = date.getDate();
		var currentYear = date.getFullYear();

		$( "#datepicker1" ).datepicker({
			changeMonth: true,
			changeYear: true,
			//yearRange: "-10:+0", // last 10  years
			maxDate: new Date(currentYear, currentMonth, currentDate),
		  	});
		
        $( "#datepicker2" ).datepicker({
			changeMonth: true,
			changeYear: true,
			//yearRange: "-10:+0", // last 10  years
			maxDate: new Date(currentYear, currentMonth, currentDate),
		});
});

$('#excel_sales').click(function(){

 var range_start_date = "<?php echo @$date_set_start;?>";
 var range_end_date = "<?php echo @$date_set_end;?>";
 
 var length_start = range_start_date.length;
 var length_end = range_end_date.length;
 
 if((length_start == 0 && length_end !=0) || (length_start != 0 && length_end ==0))
 {
	 alert("<?php echo __('Either select both the dates or none.');?>");
	 return;
 }
 else
 {
	/* Date Format MM/DD /YYYY*/
	var start = range_start_date.split("/");
	var end = range_end_date.split("/");
	
	var url_start_date = start[2] + "-" + start[0] + "-" + start[1]; 
	var url_end_date = end[2] + "-" + end[0] + "-" + end[1]; 

	/* Converting Y/M/D Format*/
	var new_start = start[2] + "/" + start[0] + "/" + start[1] + " 00:00:00"
   	var new_start = new Date(new_start);

	var new_end = end[2] + "/" + end[0] + "/" + end[1] + " 00:00:00"
   	var new_end = new Date(new_end);
	
	var start_timestamp = new_start.getTime();
	var end_timestamp = new_end.getTime();

	if(start_timestamp > end_timestamp)
	{
		 alert("<?php echo __('Invalid date range.');?>");
		 $("#datepicker1").css('border','1px solid #F00');
		 $("#datepicker2").css('border','1px solid #F00');
		 return;
	}
 }
 
 if(length_start != 0 && length_end !=0)
 {
 	var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'daily_sales_excel'));?>/"+url_start_date+"/"+url_end_date;
 }
 else
 {
 	var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'daily_sales_excel'));?>";
 }
 window.location.href = url;
	
});

function single_excel_report(sales_date)
{
	var url_start_date = 0;
	var url_end_date = 0;
	var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'daily_sales_excel'));?>/"+url_start_date+"/"+url_end_date+"/"+sales_date;
    window.location.href = url;
}

</script>