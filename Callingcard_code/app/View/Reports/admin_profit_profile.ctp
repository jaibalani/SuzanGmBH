<style type="text/css">
.ui-helper-clearfix:before, .ui-helper-clearfix:after{
/*display:inline-table;*/
}
.ui-widget-content{
	background:url("images/ui-bg_flat_75_ffffff_40x100.png") repeat-x scroll 50% 50% #ffffff;
}
.form-control{
padding:6px 7px;
}
.label-style {
	font-weight: 100 !important;
	margin-right: 37px !important;
}
</style>

<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <div class="sub_title"><i class="icon-user home_icon"></i> <span class="sub_litle_m">Reports</span> <i class="icon-angle-right home_icon"></i> <span>Profitability Report</span></div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title mediator_w" style="width:30%;"><?php echo $title_for_layout; ?></div>
        <button class="new_button clear_filer_class" style="float:right;" type="button" style="cursor:pointer;">
            <span class="icon-remove icon-white"></span>&nbsp;&nbsp;Clear Filter
        </button>
        <button class="new_button back " type="button" style="cursor:pointer; float:right;margin-right:10px;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>

    </div>    
  
  <div class="clear10"></div>
  <div align="left" class="grid_table_box">
    <div class="clear10"></div>
        <div class="clear10"></div>
            <div class="selectbox_div">
                <div class="col-md-5" style="padding-left: 0px !important;">
                        <div class="col-md-4">
                            <?php echo __('Category:') ?>
                        </div>
                        <div class="col-md-8">
                            <?php
                            echo $this->Form->input('Cat.id', array(
                                'label' => false,
                                'class' => 'form-control selectbox_graditent',
                                'type' => 'select',
                                'required' => false,
                                'value' => @$selected_cat,
                                'options' => @$cateList,
                                'empty' => '--- All ---'
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="col-md-4">
                            <?php echo __('Sub Category:') ?>
                        </div>
                        <div class="col-md-8" style="padding-right: 0px !important;">
                            <?php
                            echo $this->Form->input('SubCat.id', array(
                                'label' => false,
                                'class' => 'form-control selectbox_graditent',
                                'type' => 'select',
                                'required' => false,
                                'value' => @$selected_sub_cat,
                                'options' => @$subCateList,
                                'empty' => '--- All ---'
                            ));
                            ?>
                        </div>
                    </div>
            </div>
            <div class="clear10"></div>
            <div class="selectbox_div">
                <div class="col-md-4 " align="left" style="margin-right: 10px; padding-left:0px !important;">
                        <div class="col-md-5" style="">Select Card</div>
                        <div class="col-md-7">
	                        <?php
	                         echo $this->Form->input('card_id', 
	                               array('type' => 'button',
	                                    'class'=>'select_boxfrom form_submit form-control',
	                                    'type'=>'select',
	                                    'options'=> @$all_cards,
	                                    'value'=>@$card_id,
	                                    'label'=>false,
	                                    'style'=>'cursor:pointer;',
	                                    'empty'=>'--- All Card ---'
	                                   )
	                               );
	                        ?>
                        </div>
                </div>  
                <div class="col-md-3 " align="right" style="">
                    <label class="label_date label-style" style="margin-right:21px !important;"><?php echo __('From Date')?></label>
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
											'style'=>'background-color:#FFF;',
                                            'readonly' => 'readonly',
											'type'=>'text', 
											'placeholder'=>__('From Date')
											));
						  ?>
                   </div>
              </div>
              
              <div class="col-md-3 " align="right" style="">
                    <label class="label_date label-style"><?php echo __('To Date')?></label>
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
											'style'=>'background-color:#FFF;',
                                            'readonly' => 'readonly',
											'type'=>'text', 
											'placeholder'=>__('From Date')
											));
						  ?>
                   </div>
              </div>

              <div class="col-md-2 " style="float:right;padding-right:0px;  margin-left:-10px;">
              	<button class="new_button" id="filter_card_button" type="button" style="cursor:pointer; float:right;"><span class="icon-filter icon-white"></span>&nbsp;&nbsp;Filter Data</button>
			  </div>	
                
           	</div>

<div class="clear10"></div>
    <div class="row">
    	<div class="col-md-4">
	       <div class="col-md-5">Select Retailer</div>
	       <div class="col-md-7">	 
				<?php 
				echo $this->Form->input('retailer_id',array('class'=>'form-control',
																										'required' =>true,
																										'type'=>'select',
																										'options'=>@$retailer_list,
																										'value'=>@$retailer_id,
																										'empty'=>'All Retailer',
																										'label'=>false)); ?>
	       </div>
       </div>

      <div class="col-md-2 selectbox_title sb-excel-margin">Download Excel Report</div>
      <div class="col-md-2" style="margin-top:6px;">	 
		<?php  echo $this->Html->image(IMAGE_PATH.'/images/excel.png',array('alt'=>'Excel','border'=>'0','div'=>false,'id'=>'excel_sales','class'=>'cursor_class'));?>
     </div>	

    </div>
<div class="clear10"></div>

    <?php 
	if($retailer_id || !$retailer_id )
	{
	?>
    <table class="table table-striped" id="t_filter">
        <?php /* if(!$retailer_id) { ?>
		<!-- <caption>Sales Report For All Retailer</caption>-->
        <?php } else {?>
        <caption>Sales Report For <b><?php echo @$retailer_name;?></b></caption>
        <?php } */?>
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Retailer</th>
                <th>Card Name</th>
                <th>Purchase Price</th>
                <th>Selling Price</th>
                <th>Quantity</th>
                <th>Total Purchase</th>
                <th>Total Sales</th>
                <th>Profit</th>
                <th>Date-Time</th>
                <th filter="false">Action</th>
            </tr>
        </thead>

        <tbody>
           <?php $sales_counter = 1; if(!empty($get_sales_data)) { 
		   		
				foreach($get_sales_data as $data)
				{	
		   ?>
            <tr class="sum"> 
               <td><?php echo $sales_counter; $sales_counter++;?></td>
               <td><?php echo $data[0]['retailer_name']; ?></td>
               <td><?php echo ucwords($data['Card']['c_title']); ?></td>
               <td><?php echo "&euro;".$data['Sale']['s_purchase_price']; ?></td>
               <td><?php echo "&euro;".$data['Sale']['s_selling_price']; ?></td>
               <td data-quantity="<?php echo  $data['Sale']['card_sale_count']; ?>"><?php echo  $data['Sale']['card_sale_count']; ?></td>
               <td data-purchase="<?php echo $data['Sale']['s_total_purchase']; ?>"><?php echo "&euro;".$data['Sale']['s_total_purchase']; ?></td>
               <td data-sales="<?php echo $data['Sale']['s_total_sales']; ?>"><?php echo "&euro;".$data['Sale']['s_total_sales']; ?></td>
               <td data-profit="<?php echo ($data['Sale']['s_total_sales']-$data['Sale']['s_total_purchase']); ?>"><?php echo "&euro;".($data['Sale']['s_total_sales']-$data['Sale']['s_total_purchase']); ?></td>

               <td><?php echo date('d.m.Y',strtotime($data['Sale']['s_date']))." ".$data['Sale']['s_time'];?></td>
               <td>
               <?php  echo $this->Html->image(IMAGE_PATH.'/images/excel.png',array('alt'=>'Calender','class'=>'','border'=>'0','div'=>false,'class'=>'cursor_class','onclick'=>'single_excel_report('.$data["Sale"]["s_id"].')'));?>
               </td> 
           </tr>
           <?php } ?>
           <tfoot>
           <tr style="font-weight:bold;">
              <td colspan="4"></td>
              <td>Net Quantity</td>
              <td class="total-quantity"><?php echo $card_count;?></td>
              <td class="total-purchase">&euro;<?php echo $total_purchase_amount;?></td>
              <td class="total-sales">&euro;<?php echo $total_sales_amount;?></td>
              <td class="total-profit">&euro;<?php echo $total_sales_amount - $total_purchase_amount;?></td>

           </tr> 
           </tfoot>
           <?php } else { ?>
           <tr> 
           <td colspan="11" align="center"><?php echo __('No records found.');?></td>
           
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
   $('#profitability_report').addClass('sb_active_subopt_active');
   
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
                var sumProfit = 0;
                $('.sum').each(function() {
                    if(!$(this).attr("filtermatch")){
                        var sQ = parseInt($(this).find('td').eq(5).data('quantity'));
                        var sP = parseFloat($(this).find('td').eq(6).data('purchase'));
                        var sS = parseFloat($(this).find('td').eq(7).data('sales'));
                        var sPr = parseFloat($(this).find('td').eq(8).data('profit'));
                        sumQuantity += sQ;
                        sumPurchase += sP;
                        sumSalse += sS;
                        sumProfit += sPr;
                    }
                });
                
                $('.total-quantity').html(sumQuantity);
                $('.total-purchase').html(parseFloat(sumPurchase).toFixed(2));
                $('.total-sales').html(parseFloat(sumSalse).toFixed(2));
                $('.total-profit').html(parseFloat(sumProfit).toFixed(2));
              }
            };
            
        filterTbl.tableFilter(options2);
        $('.filters > td >input').removeAttr('title');
   
}) ;

$('#CatId').change(function () {
        url_start_date = null;
        url_end_date = null;
        var cat_id = $(this).val();
        if (!cat_id) {
            cat_id = 0;
        }
        var sub_cat_id = 0;

        /*var url = "<?php echo $this->Html->url(array('controller' => 'Reports', 'action' => 'sales_report_distributor')); ?>/"+0+"/"+0+"/"+0+"/"+url_start_date+"/"+url_end_date + "/" + cat_id + "/" + sub_cat_id;
        window.location.href = url;*/
         var card_id =  '';
       $.ajax({
        beforeSend: function (XMLHttpRequest){
         $("#loading-image").fadeIn();
        },
        complete: function (XMLHttpRequest, textStatus) {
        $("#loading-image").fadeOut();
        },
        url: "<?php echo Router::url(array('controller'=>'Reports','action'=>'get_subcat'));?>", 
        type: "POST",
        data: ({id : cat_id}),
        dataType: 'json',
        success: function(json){
        $('#SubCatId').html('');
        $('#SubCatId').html('<option value="">--- All ---</option>');
          var keys = [];
          var datas = {}
          
          $.each(json, function(key, value){
            keys.push(value)
            datas[value] = key;
          })
          
          var aa = keys.sort()
          
          $.each(aa, function(index, value){
            $('#SubCatId').append($('<option>').text(value).attr('value', datas[value]));
          })
        }
      });
      
      $.ajax({
        beforeSend: function (XMLHttpRequest){
         $("#loading-image").fadeIn();
        },
        complete: function (XMLHttpRequest, textStatus) {
        $("#loading-image").fadeOut();
        },
        url: "<?php echo Router::url(array('controller'=>'Reports','action'=>'get_cards_parent_cat'));?>", 
        type: "POST",
        data: ({id : cat_id}),
        dataType: 'json',
        success: function(json){
        $('#card_id').html('');
        $('#card_id').html('<option value="0">--- All Card ---</option>');
            var keys = [];
          var datas = {}
          
          $.each(json, function(key, value){
            keys.push(value)
            datas[value] = key;
          })
          
          var aa = keys.sort()
          
          $.each(aa, function(index, value){
            $('#card_id').append($('<option>').text(value).attr('value', datas[value]));
          })
        }
      });
    });

    $('#SubCatId').change(function () {
        url_start_date = null;
        url_end_date = null;
        var cat_id = $('#CatId').val();
        var sub_cat_id = $(this).val();
        if (!sub_cat_id) {
            sub_cat_id = 0;
        }

       // var url = "<?php echo $this->Html->url(array('controller' => 'Reports', 'action' => 'sales_report_distributor')); ?>/" +0+"/"+0+"/"+0+"/"+url_start_date+"/"+url_end_date + "/" + cat_id + "/" + sub_cat_id;
       // window.location.href = url;
       //var cat_id = ''
       var sub_cat_id =  $('#SubCatId').val();
       var card_id =  '';
       $.ajax({
        beforeSend: function (XMLHttpRequest){
         $("#loading-image").fadeIn();
        },
        complete: function (XMLHttpRequest, textStatus) {
        $("#loading-image").fadeOut();
        },
        url: "<?php echo Router::url(array('controller'=>'Reports','action'=>'get_cards'));?>", 
        type: "POST",
        data: ({id : sub_cat_id,main_cat_id:cat_id}),
        dataType: 'json',
        success: function(json){
        $('#card_id').html('');
        $('#card_id').html('<option value="0">--- All Card ---</option>');
            var keys = [];
          var datas = {}
          
          $.each(json, function(key, value){
            keys.push(value)
            datas[value] = key;
          })
          
          var aa = keys.sort()
          
          $.each(aa, function(index, value){
            $('#card_id').append($('<option>').text(value).attr('value', datas[value]));
          })
        }
      });
    });


$('#filter_card_button').click(function(){
 
 var cat_id = $('#CatId').val();
 var sub_cat_id = $('#SubCatId').val();

 var card_id = $('#card_id').val();
 var retailer_id = $('#retailer_id').val();

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
	/* Date Format d.m.Y*/
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
 
 if(cat_id == '' || cat_id.length == 0)
 cat_id = 0;

if(sub_cat_id == '' || sub_cat_id.length == 0)
sub_cat_id = 0;


 if(card_id == '' || card_id.length == 0)
 card_id = 0;
 
 if(retailer_id == '' || retailer_id.length == 0)
 retailer_id = 0;
 
 if(length_start != 0 && length_end !=0)
 {
 	var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'profit_profile'));?>/"+retailer_id+"/"+card_id+"/"+url_start_date+"/"+url_end_date+"/"+cat_id+"/"+sub_cat_id;
 }
 else
 {
  url_end_date = null;
  url_start_date = null;
 	 var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'profit_profile'));?>/"+retailer_id+"/"+card_id+"/"+url_start_date+"/"+url_end_date+"/"+cat_id+"/"+sub_cat_id;
 }
 //alert(url);
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

 var cat_id = "<?php echo @$selected_cat;?>";
 var sub_cat_id = "<?php echo @$selected_sub_cat;?>";

 var card_id = "<?php echo @$card_id;?>";
 var retailer_id = "<?php echo @$retailer_id;?>";
 
 var range_start_date = "<?php echo @$date_set_start;?>";
 var range_end_date = "<?php echo @$date_set_end;?>";
 
 var length_start = range_start_date.length;
 var length_end = range_end_date.length;
 
 if(retailer_id == '' || retailer_id.length == 0)
 retailer_id = 0;

 if(card_id == '' || card_id.length == 0)
 card_id = 0;

if(cat_id == '' || cat_id.length == 0)
 cat_id = 0;

if(sub_cat_id == '' || sub_cat_id.length == 0)
 sub_cat_id = 0;

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
 	var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'profit_profile_excel'));?>/"+cat_id+ "/" + sub_cat_id+ "/" +retailer_id+"/"+card_id+"/"+url_start_date+"/"+url_end_date;
 }
 else
 {
 	var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'profit_profile_excel'));?>/"+cat_id+ "/" + sub_cat_id+ "/" +retailer_id+"/"+card_id;
 }
 //alert(url);
 window.location.href = url;
	
});

function single_excel_report(sales_id)
{
  var cat_id = 0;
  var sub_cat_id = 0;
 	var retailer_id = 0;
	var card_id = 0;
	var url_start_date = 0;
	var url_end_date = 0;
	var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'profit_profile_excel'));?>/"+cat_id+"/"+sub_cat_id+"/"+retailer_id+"/"+card_id+"/"+url_start_date+"/"+url_end_date+"/"+sales_id;
  window.location.href = url;
}

$('.back').click(function(){
  history.go(-1);
});

$('.clear_filer_class').click(function(){
	var new_url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'profit_profile','admin'=>'true'));?>";
	 window.location.href = new_url;
});

</script> 
