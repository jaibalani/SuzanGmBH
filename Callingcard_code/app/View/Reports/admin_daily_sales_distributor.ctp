<style type="text/css">
/
*
.ui-helper-clearfix:before, .ui-helper-clearfix:after {
	display: inline-table;
}

.ui-widget-content {
	background: url("images/ui-bg_flat_75_ffffff_40x100.png") repeat-x
		scroll 50% 50% #ffffff;
}

.form-control {
	padding: 6px 7px;
}

.label-style {
	font-weight: 100 !important;
}
</style>

<div align="left" style="padding-right: 10px;">
	<div class="page_title"><?php echo $title_for_layout; ?></div>
	<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
	<div class="sub_title">
		<i class="icon-user home_icon"></i> <span class="sub_litle_m">Reports</span>
		<i class="icon-angle-right home_icon"></i> <span>Daily Sales Report</span>
	</div>
	<div class="main_subdiv">
		<div class="gird_button">
			<div class="main_sub_title mediator_w"><?php echo $title_for_layout; ?></div>
			<button class="new_button clear_filer_class" style="float: right;"
				type="button" style="cursor:pointer;">
				<span class="icon-remove icon-white"></span>&nbsp;&nbsp;Clear Filter
			</button>
			<button class="new_button back " type="button"
				style="cursor: pointer; float: right; margin-right: 10px;">
				<span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back
			</button>

		</div>

		<div class="clear10"></div>
		<div align="left" class="grid_table_box">
			<div class="selectbox_div">

				<div class="col-md-4 " style="padding-left: 0px;">
					<div class="col-md-5">
					<label class="label_date label-style"><?php echo __('From Date')?></label>
					</div>
					<div class="col-md-7">
						<div class="input-group">
							<div class="input-group-addon"
								style="padding: 1px 12px; font-size: 13px;">
					      		<?php  echo $this->Html->image(IMAGE_PATH.'/images/calender.png',array('alt'=>'Calender','class'=>'','border'=>'0','div'=>false));?>
	                            <div
									style="color: #4570B8; float: left; cursor: pointer;"
									class="reset_from"><?php echo __('Reset')?></div>
							</div>
					      <?php
											
	echo $this->Form->input ( 'datepicker1', array (
													'label' => false,
													'class' => 'form-control',
													'id' => 'datepicker1',
													'value' => @$date_set_start,
													'style' => 'background-color:#FFF;',
													'readonly' => 'readonly',
													'type' => 'text',
													'placeholder' => __ ( 'From Date' ) 
											) );
											?>
					    </div>
					</div>
				</div>

				<div class="col-md-4" align="right" style="margin-right: 10px;">
					<div class="col-md-5">
						<label class="label_date label-style"><?php echo __('To Date')?></label>
					</div>
					<div class="col-md-7">
						<div class="input-group">
							<div class="input-group-addon"
								style="padding: 1px 12px; font-size: 13px;">
					      		<?php  echo $this->Html->image(IMAGE_PATH.'/images/calender.png',array('alt'=>'Calender','class'=>'','border'=>'0','div'=>false));?>
	                            <div
									style="color: #4570B8; float: left; cursor: pointer;"
									class="reset_to"><?php echo __('Reset')?></div>
							</div>
					      <?php
											
                            echo $this->Form->input ( 'datepicker2', array (
													'label' => false,
													'class' => 'form-control',
													'id' => 'datepicker2',
													'value' => @$date_set_end,
													'style' => 'background-color:#FFF;',
													'readonly' => 'readonly',
													'type' => 'text',
													'placeholder' => __ ( 'To Date' ) 
											) );
											?>
					    </div>
					 </div>
				</div>

				<div class="col-md-2 " style="float: right; padding-right: 0px;">
					<button class="new_button" id="filter_card_button" type="button"
						style="cursor: pointer; float: right;">
						<span class=" icon-filter icon-white"></span>&nbsp;&nbsp;Filter
						Data
					</button>
				</div>

			</div>

			<div class="clear10"></div>
			<div class="row">
				<div class="col-md-4">
					<div class="col-md-5" style="margin-top: 6px;">Select Mediator</div>
					<div class="col-md-7">	 
						<?php
						echo $this->Form->input ( 'mediator_id', array (
								'class' => 'form-control',
								'required' => true,
								'type' => 'select',
								'options' => @$mediator_list,
								'value' => @$mediator_id,
								'empty' => 'All Mediator',
								'label' => false 
						) );
						?>
			       </div>
		       </div>
		       <div class="col-md-4">
				  <div class="col-md-5" style="margin-top: 6px;">Select Retailer</div>
				  <div class="col-md-7">	 
					<?php
					echo $this->Form->input ( 'retailer_id', array (
							'class' => 'form-control',
							'required' => true,
							'type' => 'select',
							'options' => @$retailer_list,
							'value' => @$retailer_id,
							'empty' => 'All Retailer',
							'label' => false 
					) );
					?>
		           </div>
       			</div>

				<div class="col-md-2 selectbox_title">Download Excel Report</div>
				<div class="col-md-2" style="margin-top: 6px;">	 
		<?php  echo $this->Html->image(IMAGE_PATH.'/images/excel.png',array('alt'=>'Excel','border'=>'0','div'=>false,'id'=>'excel_sales','class'=>'cursor_class'));?>
     </div>
			</div>
			<div class="clear10"></div>

    <?php
				if ($retailer_id || ! $retailer_id) {
					?>
    <table class="table table-striped" id="t_filter">
        <?php 
/*
					       * if(!$retailer_id) { ?> <!-- <caption>Sales Report For All Retailer</caption>--> <?php } else {?> <caption>Sales Report For <b><?php echo @$retailer_name;?></b></caption> <?php }
					       */
					?>
        <thead>
					<tr>
						<th>S.No.</th>
						<th>Retailer</th>
						<th>Date</th>
						<th>Quantity</th>
						<th>Total Purchase</th>
						<th>Total Sales</th>

                        <th filter="false"></th>
                    </tr>
				</thead>

				<tbody>
           <?php
					
$sales_counter = 1;
					if (! empty ( $get_sales_data )) {
						
						foreach ( $get_sales_data as $data ) {
							?>
                    <tr class="sum">
						<td><?php echo $sales_counter; $sales_counter++;?></td>
						<td><?php echo $data[0]['retailer_name']; ?></td>
						<td><?php echo date('d.m.Y',strtotime($data['Sale']['sale_date']));?></td>
						<td data-quantity="<?php echo  $data[0]['total_card']; ?>"><?php echo  $data[0]['total_card']; ?></td>
						<td data-purchase="<?php echo $data[0]['total_purchase']?>">&euro;<?php echo $data[0]['total_purchase']?></td>
						<td data-sales="<?php echo $data[0]['total_sales']?>">&euro;<?php echo $data[0]['total_sales']?></td>
						<td>
               <?php  echo $this->Html->image(IMAGE_PATH.'/images/excel.png',array('alt'=>'Calender','class'=>'','border'=>'0','div'=>false,'class'=>'cursor_class','onclick'=>'single_excel_report("'.$data["Sale"]["s_u_id"].'","'.$data["Sale"]["sale_date"].'")'));?>
               </td>
					</tr>
           <?php } ?>
                    <tfoot>
           <tr style="font-weight: bold;">
						<td colspan="2"></td>
						<td>Net Quantity</td>
                        <td class="total-quantity"><?php echo $card_count;?></td>
                        <td class="total-purchase">&euro;<?php echo $total_purchase_amount;?></td>
						<td class="total-sales" colspan="2">&euro;<?php echo $total_sales_amount;?></td>
					</tr> 
                    </tfoot>
           <?php } else { ?>
           <tr>
						<td colspan="6" align="center"><?php echo __('No records found.');?></td>

					</tr>
           <?php } ?>
       
        </tbody>

			</table>
   <?php } ?>
		
  </div>
		<div class="clear10"></div>
	</div>
</div>

<script type="text/javascript">

$(document).ready(function(){
   $('#reports').addClass('sb_active_opt');
   $('#reports').removeClass('has_submenu');
   $('#daily_sales_report').addClass('sb_active_subopt_active');
   
   /* Calculate total on table filter */
        var filterTbl = $('#t_filter');
        var options2 = {         
              clearFiltersControls: [$('.clear_filer_class')],   
			  enableCookies :false,
              filteringRows: function(filterStates) {
                
              },
              filteredRows: function(filterStates) {
                var sumQuantity = 0;
                var sumPurchase = 0;
                var sumSalse = 0;
                $('.sum').each(function() {
                    if(!$(this).attr("filtermatch")){
                        var sQ = parseInt($(this).find('td').eq(3).data('quantity'));
                        var sP = parseFloat($(this).find('td').eq(4).data('purchase'));
                        var sS = parseFloat($(this).find('td').eq(5).data('sales'));
                        sumQuantity += sQ;
                        sumPurchase += sP;
                        sumSalse += sS;
                       
                    }
                });
                
                $('.total-quantity').html(sumQuantity);
                $('.total-purchase').html(parseFloat(sumPurchase).toFixed(2));
                $('.total-sales').html(parseFloat(sumSalse).toFixed(2));
              }
            };
   filterTbl.tableFilter(options2);
   $('.filters > td >input').removeAttr('title');
}) ;

$('#filter_card_button').click(function(){
 var retailer_id = $('#retailer_id').val();
 var mediator_id = $('#mediator_id').val();
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
	/* Date Format d.m.y*/
	var start = range_start_date.split(".");
	var end = range_end_date.split(".");
	
	var url_start_date = start[2] + "-" + start[1] + "-" + start[0]; 
	var url_end_date = end[2] + "-" + end[1] + "-" + end[0]; 

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
		 return;
	}
 }
 
 if(retailer_id == '' || retailer_id.length == 0)
 retailer_id = 0;
 
 if(mediator_id == '' || mediator_id.length == 0)
 mediator_id = 0;

 if(length_start != 0 && length_end !=0)
 {
 	var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'daily_sales_distributor'));?>/"+mediator_id+"/"+retailer_id+"/"+url_start_date+"/"+url_end_date;
 }
 else
 {
 	var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'daily_sales_distributor'));?>/"+mediator_id+"/"+retailer_id;
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
			dateFormat:"dd.mm.yy",
			//yearRange: "-10:+0", // last 10  years
			maxDate: new Date(currentYear, currentMonth, currentDate),
		  	});
		
        $( "#datepicker2" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"dd.mm.yy",
			//yearRange: "-10:+0", // last 10  years
			maxDate: new Date(currentYear, currentMonth, currentDate),
		});
});

$('#excel_sales').click(function(){

 var retailer_id = "<?php echo @$retailer_id;?>";
 var mediator_id = "<?php echo @$mediator_id;?>";
 
 var range_start_date = "<?php echo @$date_set_start;?>";
 var range_end_date = "<?php echo @$date_set_end;?>";
 
 var length_start = range_start_date.length;
 var length_end = range_end_date.length;
 
 if(mediator_id == '' || mediator_id.length == 0)
 mediator_id = 0;

 if(retailer_id == '' || retailer_id.length == 0)
 retailer_id = 0;
 
 if((length_start == 0 && length_end !=0) || (length_start != 0 && length_end ==0))
 {
	 alert("<?php echo __('Either select both the dates or none.');?>");
	 return;
 }
 else
 {
	/* Date Format D.M.Y*/
	var start = range_start_date.split(".");
	var end = range_end_date.split(".");
	
	var url_start_date = start[2] + "-" + start[1] + "-" + start[0]; 
	var url_end_date = end[2] + "-" + end[1] + "-" + end[0]; 

	/* Converting Y/M/D Format*/
	var new_start = start[2] + "/" + start[1] + "/" + start[0] + " 00:00:00"
   	var new_start = new Date(new_start);

	var new_end = end[2] + "/" + end[1] + "/" + end[0] + " 00:00:00"
   	var new_end = new Date(new_end);
	
	var start_timestamp = new_start.getTime();
	var end_timestamp = new_end.getTime();

	/*if(start_timestamp > end_timestamp)
	{
		 alert("<?php echo __('Invalid date range.');?>");
		 $("#datepicker1").css('border','1px solid #F00');
		 $("#datepicker2").css('border','1px solid #F00');
		 return;
	}*/
 }
 
 if(length_start != 0 && length_end !=0)
 {
 	var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'daily_sales_excel_distributor'));?>/"+mediator_id+"/"+retailer_id+"/"+url_start_date+"/"+url_end_date;
 }
 else
 {
 	var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'daily_sales_excel_distributor'));?>/"+mediator_id+"/"+retailer_id;
 }
 window.location.href = url;
	
});

function single_excel_report(sales_user_id,sales_date)
{
 	var mediator_id = 0;
 	var retailer_id = 0;
	var url_start_date = 0;
	var url_end_date = 0;
	var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'daily_sales_excel_distributor'));?>/"+mediator_id+"/"+retailer_id+"/"+url_start_date+"/"+url_end_date+"/"+sales_user_id+"/"+sales_date;
    window.location.href = url;
}

$('#mediator_id').change(function(){
  
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
			$('#retailer_id').html('');
			$('#retailer_id').html('<option value="">All Retailer</option>');
			  var keys = [];
				var datas = {}
				
				$.each(json, function(key, value){
				  keys.push(value)
				  datas[value] = key;
				})
				
				var aa = keys.sort()
				
				$.each(aa, function(index, value){
					$('#retailer_id').append($('<option>').text(value).attr('value', datas[value]));
				})
		  }
		});
});

$('.back').click(function(){
  history.go(-1);
});
$('.clear_filer_class').click(function(){
	var new_url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'daily_sales_distributor','admin'=>'true'));?>";
    window.location = new_url;
});


</script>
