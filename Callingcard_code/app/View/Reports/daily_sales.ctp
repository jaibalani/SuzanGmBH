<?php 
 $language=Configure::read('Config.language');
  if(empty($language))
  $language="en";
?>
<style type="text/css">
#print{
	cursor:pointer;
}
a:hover {
    text-decoration: none;
    color: #0000FF; 
} 
#sr-date-panel .row{ margin-top:0px !important; margin-bottom:0px !important; }
.sb-top-margin{ margin-top: 10px; }
</style>
<?php ?>
<div class="right-part right-panel">
     <div class="sb-page-title">
          <?php echo $title_for_layout; ?>
     </div>
     
      <div id="sr-date-range">
        <form action="#" method="post">
        <div id="sr-date-panel" style="padding-left:10px;">
            <div class="row">
                <div class="col-xs-4 sb-top-margin">                    
                    <div class="input-group sel-input">
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
                              'placeholder'=>__('From Date:')
                              ));
                      ?>
                    </div>
                </div>
                <div class="col-xs-4 sb-top-margin">
                    <div class="input-group sel-input">
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
                              'placeholder'=>__('To Date:')
                              ));
                      ?>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div id="action">
                          <input type="button" name="filter" value="<?php echo __('APPLY FILTER');?>" class="button-gradient" id="filter_card_button" style="margin-left:1px; margin-top:10px;"/>
                    </div>
                </div>
            </div>
        </div>
        <div id="extra_opt" style="float:right;margin-right:53px; margin-top:10px;">
	        <?php  echo $this->Html->image(IMAGE_PATH.'/images/print.png',array('alt'=>'Print','class'=>'','border'=>'0','div'=>false,'id'=>'print'));?>
			<?php  echo $this->Html->image(IMAGE_PATH.'/images/excel.png',array('alt'=>'Excel','class'=>'cursor_class','border'=>'0','div'=>false,'id'=>'excel_sales'));?>
        </div>
        </form>
   </div>
                    
    <div id="sales-reports" class="main_print_div">
        <table class="table table-bordered" id="daily_filter">
            <thead>
              <tr>
                <th style="width:100px; text-align:left;"><?php echo __('S. No.')?></th>  
                <th class="date_td"><?php echo __('Date')?></th>
                <th><?php echo __('Quantity')?></th>
                <th style="width:165px;"><?php echo __('Total Purchase')?></th>
                <th><?php echo __('Total Sales')?></th>
                <th class="filters" filter="false"><?php echo __('Action')?></th>
              </tr>
            </thead>
            <tbody>
             <?php $counter = 1;
						 if(isset($get_sales_data) && !empty($get_sales_data)){
						 foreach ($get_sales_data as $data) {?>    
                <tr class="sum">
                <td><?php echo $counter;  
                                $counter++; 
                    ?>
                </td>
                <td>
                  <a class="get_daily_sales_data" style="color:#0000FF;text-decoration:none;" href="<?=$this->html->url(array('controller'=>'Reports', 'action'=>'daily_sales_popup',$data['Sale']['sale_date']))?>">
                    <?php echo date('d.m.Y',strtotime($data['Sale']['sale_date']));?>
                  </a>
                </td>
                <td data-quantity="<?php echo $data[0]['total_card']?>" ><?php echo $data[0]['total_card']?></td>
                <td data-purchase="<?php echo $data[0]['total_purchase']?>">&euro;<?php echo $data[0]['total_purchase']?></td>
                <td data-sales="<?php echo $data[0]['total_sales']?>">&euro;<?php echo $data[0]['total_sales']?></td>
                <td class="filters">
                <?php  echo $this->Html->image(IMAGE_PATH.'/images/excel.png',array('alt'=>'Print','class'=>'','border'=>'0','div'=>false,'class'=>'cursor_class','onclick'=>'single_excel_report("'.$data["Sale"]["sale_date"].'")'));?>
                </td>
              </tr>
             <?php }
						 }else{?> 
								<td colspan="6" align="center"><?php echo __('No record found')?></td>
			 <?php }?> 
              
              <tfoot>
              <tr>
                <td colspan="2"><?php echo __('Net Quantity');?></td>
                <td class="total-quantity"><?php echo $card_count;?></td>
				<td class="total-purchase" colspan="1" ><?php echo "€".$total_purchase_amount;?></td>
                <td class="total-sales" colspan="2" ><?php echo "€".$total_sales_amount;?></td>
              </tr> 
              </tfoot>
            </tbody>
          </table>
        
    </div>
</div>
<script type="text/javascript">

$(".get_daily_sales_data").fancybox({
      'autoDimensions'  : false,
      'hideOnOverlayClick': false,
      'scrolling'     : 'no',
      'width'             : 830,
      'height'            : 650,
      'transitionIn'    : 'none',
      'transitionOut'   : 'none',
      'autoScale'       : false,
      'type'        : 'iframe',
  });

$(document).ready(function(){
	//Highlight active menu
	$('#sb-opt-report').addClass('opt-selected');
	$('#sb-opt-report').next().toggle('sliderup');
	$('#sb-subopt-daily-report').addClass('opt-selected');
  
  //set panel on language change
  <?php if($language == 'deu'){?>
       $('#extra_opt').css('margin-right','25px');
  <?php }?> 

    /* Calculate total on table filter */
        var filterTbl = $('#daily_filter');
        var options2 = {                
   			  enableCookies :false,
			  filteringRows: function(filterStates) {
                
              },
              filteredRows: function(filterStates) {
                var sumQuantity = 0;
                var sumPurchase = 0;
                var sumSalse = 0;
                $('.sum').each(function() {
                    if(!$(this).attr("filtermatch")){
                        var sQ = parseInt($(this).find('td').eq(2).data('quantity'));
                        var sP = parseFloat($(this).find('td').eq(3).data('purchase'));
                        var sS = parseFloat($(this).find('td').eq(4).data('sales'));
                        sumQuantity += sQ;
                        sumPurchase += sP;
                        sumSalse += sS;
                       
                    }
                });
                
                $('.total-quantity').html(sumQuantity);
                $('.total-purchase').html("&euro;"+parseFloat(sumPurchase).toFixed(2));
                $('.total-sales').html("&euro;"+parseFloat(sumSalse).toFixed(2));
              }
            };
    
    filterTbl.tableFilter(options2);
    $('.filters > td >input').removeAttr('title');
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
	/* Date Format  D.M.YY*/
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
    
    //create the date
    var min_date = new Date();

    //subtract 180 days to the date
    min_date.setDate(min_date.getDate() - 180);

    var minMonth = min_date.getMonth();
    var minDate = min_date.getDate();
    var minYear = min_date.getFullYear();

		$( "#datepicker1" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"dd.mm.yy",
			//yearRange: "-10:+0", // last 10  years
			maxDate: new Date(currentYear, currentMonth, currentDate),
      minDate: new Date(minYear, minMonth, minDate),
		  	});
		
        $( "#datepicker2" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"dd.mm.yy",
			//yearRange: "-10:+0", // last 10  years
			maxDate: new Date(currentYear, currentMonth, currentDate),
      minDate: new Date(minYear, minMonth, minDate),
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
	/* Date Format DD.MM.YY */
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
$('#print').click(function(){
		
    var report_name = "<div style='font-weight:bold;font-size:14px;'><?php echo __('Daily Sales Report');?></div>";
    
    var report_name = report_name+"<div style='margin-top:10px;font-size:12px;'><?php echo __('Retailer:').ucwords(strtolower($this->Session->read('Auth.User.fname').' '.$this->Session->read('Auth.User.lname'))) ;?></div><br/>"; 
    
    var report_name = report_name+"<div style='margin-top:-10px;font-size:12px;'><?php echo __('Account Number').': <b>'.$this->Session->read('Auth.User.username').'</b>' ;?></div><br/>"; 
    
     <?php

           $address = $this->Session->read('Auth.User.address');
           $address = str_replace(array("\r", "\n"), " ", $address);
     ?> 

    var retailer_address = "<?php echo $address;?>";

    if(retailer_address.length ==0)
    {
      var report_name = report_name+"<div style='margin-top:-10px;font-size:12px;'><?php echo __('Address').': <b>N/A</b>' ;?></div><br/>"; 
    }
    else
    {
      var report_name = report_name+"<div style='margin-top:-10px;font-size:12px;'><?php echo __('Address').': <b>'.$address.'</b>' ;?></div><br/>"; 
    }

    var start_date = "<?php echo $date_set_start;?>";
    var end_date = "<?php echo $date_set_end;?>"; 
    if(start_date.length != 0)
    {
      var report_name =  report_name+"<div style='margin-top:-10px;font-size:12px;'><?php echo __('Date Selection: ').$date_set_start.' - '.$date_set_end;?></div>";
    }
    
    var thePopup = window.open( '', "Sales Report", "menubar=0,location=0,height=700,width=700" );
		var new_style = '<style>.filters{display : none; } .date_td{width:130px; text-align:left;} a{color:#333333 !important; text-decoration:none !important: }thead>tr>th{border-bottom:2px solid #CCCCCC;border-top:2px solid #CCCCCC; padding-bottom:5px;font-size:12px;}tfoot>tr>td{border-bottom:2px solid #CCCCCC;border-top:2px solid #CCCCCC; padding-bottom:5px;padding-top:5px;font-size:12px;}tbody>table-bordered{border:none !important;}tbody>tr>td{font-size:12px;} </style>';

    thePopup.document.write(report_name+$('.main_print_div').html()+new_style);
			$('#popup-content').clone().appendTo( thePopup.document.body );
			thePopup.print();
});
</script>