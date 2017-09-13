<?php 
 $language=Configure::read('Config.language');
  if(empty($language))
  $language="en";
?>
<style>
#print{
	cursor:pointer;
}
a:hover {
    text-decoration: none;
    color: #0000FF; 
} 
.sb-field-label{ padding-left: 0px; }
.sb-select-box{ width: 134px !important; }
#sr-date-panel .row{ margin-top:0px !important; margin-bottom:0px !important; }
#sr-date-panel .row .col-xs-12{ padding: 0px; }
.sb-top-margin{ margin-top: 10px; }
</style>
<div class="right-part right-panel">
    <!--Sales Report Start-->
    <div class="sb-page-title">
        <strong><?php echo $title_for_layout; ?></strong>
    </div>
    <div id="sr-date-range">
        <form action="#" method="post">
            <div id="sr-date-panel">
                <div class="row">
                    <div class="col-xs-4">
                        <div class="col-xs-12">
                            <label><?php echo __('Category:') ?></label>
                        </div>
                        <div class="col-xs-12">
                            <?php
                                echo $this->Form->input('Cat.id', array(
                                    'label' => false,
                                    'class' => 'form-control selectbox_graditent',
                                    'type' => 'select',
                                    'required' => false,
                                    'value' => @$selected_cat,
                                    'options' => @$cateList,
                                    'empty' => ' --- '.__('All').' --- '
                                ));
                                ?>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="col-xs-12">
                            <label><?php echo __('Sub Category:') ?></label>
                        </div>
                        <div class="col-xs-12">
                            <?php
                                echo $this->Form->input('SubCat.id', array(
                                    'label' => false,
                                    'class' => 'form-control selectbox_graditent',
                                    'type' => 'select',
                                    'required' => false,
                                    'value' => @$selected_sub_cat,
                                    'options' => @$subCateList,
                                    'empty' =>' --- '.__('All').' --- '
                                ));
                                ?>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="col-xs-12">
                            <label><?php echo __('Card Name') ?></label>
                        </div>
                        <div class="col-xs-12">
                            <div class="input-group sel-input" style="width: 132px;">
                                <?php
                                echo $this->Form->input('Card.id', array(
                                    'label' => false,
                                    'class' => 'form-control selectbox_graditent sb-select-box',
                                    'type' => 'select',
                                    'required' => false,
                                    'value' => @$card_id,
                                    'options' => @$all_cards));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4 sb-top-margin">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <div class="input-group-addon" style="padding:1px 12px; font-size:13px;">
                                <?php echo $this->Html->image(IMAGE_PATH . '/images/calender.png', array('alt' => 'Calender', 'class' => '', 'border' => '0', 'div' => false)); ?>
                                    <div style="color:#4570B8; float:left;cursor:pointer;" class="reset_from"><?php echo __('Reset') ?></div>
                                </div>
                                <?php
                                echo $this->Form->input('datepicker1', array(
                                    'label' => false,
                                    'class' => 'form-control',
                                    'id' => 'datepicker1',
                                    'value' => @$date_set_start,
                                    'style' => 'background-color:#FFF',
                                    'readonly' => 'readonly',
                                    'type' => 'text',
                                    'placeholder' => __('From Date:')
                                ));
                                ?>
                            </div>
                        </div>                            
                    </div>
                    <div class="col-xs-4 sb-top-margin">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <div class="input-group-addon" style="padding:1px 12px; font-size:13px;">
                                <?php echo $this->Html->image(IMAGE_PATH . '/images/calender.png', array('alt' => 'Calender', 'class' => '', 'border' => '0', 'div' => false)); ?>
                                    <div style="color:#4570B8;float:left; cursor:pointer;" class="reset_to"><?php echo __('Reset') ?></div>
                                </div>
                                <?php
                                echo $this->Form->input('datepicker2', array(
                                    'label' => false,
                                    'class' => 'form-control',
                                    'id' => 'datepicker2',
                                    'value' => @$date_set_end,
                                    'style' => 'background-color:#FFF',
                                    'readonly' => 'readonly',
                                    'type' => 'text',
                                    'placeholder' => __('To Date:')
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="col-xs-12">
                            <div id="action">
                                <input type="button" name="filter" value="<?php echo __('APPLY FILTER');?>" class="button-gradient" id="filter_card_button" style="margin-left:1px; margin-top:10px; width:132px;"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="extra-opt" style="margin-right:47px;">
                <?php echo $this->Html->image(IMAGE_PATH . '/images/print.png', array('alt' => 'Print', 'class' => '', 'border' => '0', 'div' => false,'id'=>'print')); ?>
                <?php echo $this->Html->image(IMAGE_PATH . '/images/excel.png', array('alt' => 'Excel', 'border' => '0', 'div' => false, 'id' => 'excel_sales', 'class' => 'cursor_class')); ?>
            </div>
        </form>
    </div>


    <div id="sales-reports"  class="main_print_div">
        <table class="table table-bordered" id="sales_filter">
            <thead>
                <tr>
                    <th style="text-align:left;width:180px;"><?php echo __('Card Name'); ?></th>
                    <th><?php echo __('Selling Price'); ?></th>
                    <th><?php echo __('Quantity'); ?></th>
                    <th><?php echo __('Net Total'); ?></th>
                    <th style="width:140px;"><?php echo __('Date-Time'); ?></th>
                    <th class="filters" filter='false'><?php echo __('Action'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
								if(isset($get_sales_data) && !empty($get_sales_data)){
								foreach ($get_sales_data as $data) { ?>  
                    <tr class="sum">
                        <td>
                         <a href ="<?php echo Router::url(array('controller'=>'Carts','action'=>'printcard',$data['Sale']['s_id']));?>" target="_blank" style="text-decoration:none;cursor:pointer;color:#0000FF;">
                         <?php echo ucwords($data['Card']['c_title']) ?>
                         </a>
                         </td>
                         
                        <td>&euro;<?php echo $data['Sale']['s_selling_price'] ?></td>
                        <td data-quantity="<?php echo $data['Sale']['card_sale_count'] ?>"><?php echo $data['Sale']['card_sale_count'] ?></td>
                        <td data-total="<?php echo $data['Sale']['s_total_sales'] ?>">&euro;<?php echo $data['Sale']['s_total_sales'] ?></td>
                        <td><?php echo date('d.m.Y', strtotime($data['Sale']['s_date'])) . " " . $data['Sale']['s_time']; ?></td>
                        <td class="filters">
                            <?php echo $this->Html->image(IMAGE_PATH . '/images/excel.png', array('alt' => 'Calender', 'class' => '', 'border' => '0', 'div' => false, 'class' => 'cursor_class', 'onclick' => 'single_excel_report(' . $data["Sale"]["s_id"] . ')')); ?>
                        </td>
                    </tr>
                <?php } 
								}else{?> 
									<td colspan="6" align="center"><?php echo __('No record found')?></td>
					<?php }?>                                
            </tbody>
            <tfoot>
            	<tr>
                    <td colspan="2"><?php echo __('Net Quantity')?></td>
                    <td class="total-quantity"><?php echo $card_count; ?></td>
                    <td class="total-total" colspan="3">&euro;<?php echo $total_sales_amount; ?></td>
                </tr>
            </tfoot>
        </table>
<div id="pager" ></div>
    </div>
    <!--Sales Report End-->
</div>
<script type="text/javascript">
    


    $(document).ready(function () {
        //Highlight active menu
		$("#loading-image").fadeOut();
        $('#sb-opt-report').addClass('opt-selected');
        $('#sb-opt-report').next().toggle('sliderup');
        $('#sb-subopt-sales-report').addClass('opt-selected');

        //set panel on language change
        <?php if($language == 'deu'){?>
            //$('#filter_card_button').css('margin','10px 0px 0px 102px');
            $('#datepicker1').css('width','129px');
            $('#datepicker2').css('width','129px');
        <?php }else{ ?>
            $('#datepicker1').css('width','158px');
            $('#datepicker2').css('width','158px');
        <?php    
        }?>
        
        /* Calculate total on table filter */
        var filterTbl = $('#sales_filter');
        var options2 = {                
			  enableCookies :false,
			  filteringRows: function(filterStates) {
                
              },
              filteredRows: function(filterStates) {
                var sumQuantity = 0;
                var sumTotal = 0;
                $('.sum').each(function() {
                    if(!$(this).attr("filtermatch")){
                        var p = parseInt($(this).find('td').eq(2).data('quantity'));
                        var t = parseFloat($(this).find('td').eq(3).data('total'));
                        sumQuantity += p;
                        sumTotal += t;
                    }
                });
                
                $('.total-quantity').html(sumQuantity);
                $('.total-total').html("&euro;"+parseFloat(sumTotal).toFixed(2));
              }
            };   
        filterTbl.tableFilter(options2);
        $('.filters > td >input').removeAttr('title');
    });

    $('#filter_card_button').click(function () {
        var card_id = $('#CardId').val();
        var range_start_date = $('#datepicker1').val();
        var range_end_date = $('#datepicker2').val();

        var length_start = range_start_date.length;
        var length_end = range_end_date.length;

        if ((length_start == 0 && length_end != 0) || (length_start != 0 && length_end == 0))
        {
            alert("<?php echo __('Either select both the dates or none.'); ?>");
            return;
        }
        else
        {
            /* Date Format dd.mm.yy*/
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

            if (start_timestamp > end_timestamp)
            {
                alert("<?php echo __('Invalid date range.'); ?>");
                $("#datepicker1").css('border', '1px solid #F00');
                $("#datepicker2").css('border', '1px solid #F00');
                return;
            }
        }

        var cat_id = $('#CatId').val();<?php //echo @$selected_cat; ?>;
        var sub_cat_id = $('#SubCatId').val();
        <?php if (@$selected_sub_cat > 0) { ?>
                    //var sub_cat_id = <?php echo $selected_sub_cat; ?>;
        <?php } ?>

        if (length_start != 0 && length_end != 0)
        {
            var url = "<?php echo $this->Html->url(array('controller' => 'Reports', 'action' => 'sales_report')); ?>/" + card_id + "/" + url_start_date + "/" + url_end_date + "/" + cat_id + "/" + sub_cat_id;
        }
        else
        {
            url_start_date = null;
            url_end_date = null;
            var url = "<?php echo $this->Html->url(array('controller' => 'Reports', 'action' => 'sales_report')); ?>/" + card_id + "/" + url_start_date + "/" + url_end_date + "/" + cat_id + "/" + sub_cat_id;
        }
        window.location.href = url;
				$("#loading-image").fadeIn();
    });


    $('#CatId').change(function () {
        url_start_date = null;
        url_end_date = null;
        var cat_id = $(this).val();
        if (!cat_id) {
            cat_id = 0;
        }
        var sub_cat_id = 0;
			//	$("#loading-image").fadeIn();
           /*var url = "<?php echo $this->Html->url(array('controller' => 'Reports', 'action' =>  'sales_report')); ?>/" + 0 + "/" + url_start_date + "/" + url_end_date + "/" + cat_id + "/" + sub_cat_id;
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
				$('#SubCatId').html('<option value=""><?php echo ' --- '.__('All').' --- '; ?></option>');
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
				$('#CardId').html('');
				$('#CardId').html('<option value="0"><?php echo ' --- '.__('All').' --- '; ?></option>');
				    var keys = [];
					var datas = {}
					
					$.each(json, function(key, value){
					  keys.push(value)
					  datas[value] = key;
					})
					
					var aa = keys.sort()
					
					$.each(aa, function(index, value){
						$('#CardId').append($('<option>').text(value).attr('value', datas[value]));
					})
				}
			});
		});

    $('#SubCatId').change(function () {
        var url_start_date = null;
        var url_end_date = null;
        var cat_id = $('#CatId').val();
        var sub_cat_id = $(this).val();
        if (!sub_cat_id) {
            sub_cat_id = 0;
        }

        //var url = "<?php echo $this->Html->url(array('controller' => 'Reports', 'action' => 'sales_report')); ?>/" + 0 + "/" + url_start_date + "/" + url_end_date + "/" + cat_id + "/" + sub_cat_id;
        //window.location.href = url;
				
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
				data: ({id : sub_cat_id,main_cat_id : cat_id}),
				dataType: 'json',
				success: function(json){
				$('#CardId').html('');
				$('#CardId').html('<option value="0"><?php echo ' --- '.__('All').' --- '; ?></option>');
				    var keys = [];
					var datas = {}
					
					$.each(json, function(key, value){
					  keys.push(value)
					  datas[value] = key;
					})
					
					var aa = keys.sort()
					$.each(aa, function(index, value){
						$('#CardId').append($('<option>').text(value).attr('value', datas[value]));
					})
				}
			});
		});

    $('.reset_from').click(function () {
        $("#datepicker1").val('');
    });

    $('.reset_to').click(function () {
        $("#datepicker2").val('');
    });

    $(function () {

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


        $("#datepicker1").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd.mm.yy",
            //yearRange: "-10:+0", // last 10  years
            maxDate: new Date(currentYear, currentMonth, currentDate),
            minDate: new Date(minYear, minMonth, minDate),
        });

        $("#datepicker2").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd.mm.yy",
            //yearRange: "-10:+0", // last 10  years
            maxDate: new Date(currentYear, currentMonth, currentDate),
            minDate: new Date(minYear, minMonth, minDate),
        });
    });

    $('#excel_sales').click(function () {

        var cat_id       = "<?php echo @$selected_cat?>";
        if(cat_id == '' || cat_id.length == 0)
        cat_id = 0;
        
        var sub_cat_id = 0;
        <?php if (@$selected_sub_cat > 0) { ?>
            var sub_cat_id = <?php echo $selected_sub_cat; ?>;
        <?php } ?>

        //var card_id = $('#CardId').val();

        var card_id = 0;
        <?php if (@$card_id) { ?>
            var card_id = <?php echo $card_id; ?>;
        <?php } ?>


        
        var range_start_date = "<?php echo @$date_set_start; ?>";
        var range_end_date = "<?php echo @$date_set_end; ?>";

        var length_start = range_start_date.length;
        var length_end = range_end_date.length;
        //alert("leng="+length_start+" "+length_end);
        if ((length_start == 0 && length_end != 0) || (length_start != 0 && length_end == 0))
        {
            alert("<?php echo __('Either select both the dates or none.'); ?>");
            return;
        }
        else
        {
            /* Date Format dd.mm.yy*/
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

        }
        if (length_start != 0 && length_end != 0)
        {
            var url = "<?php echo $this->Html->url(array('controller' => 'Reports', 'action' => 'sales_report_excel')); ?>/"+cat_id+ "/" + sub_cat_id+ "/" + card_id + "/" + url_start_date + "/" + url_end_date;
        }
        else
        {
            var url = "<?php echo $this->Html->url(array('controller' => 'Reports', 'action' => 'sales_report_excel')); ?>/" + cat_id + "/" + sub_cat_id+ "/" +  card_id;
        }
        window.location.href = url;
    });

    function single_excel_report(sales_id)
    {
        var card_id = 0;
        var url_start_date = 0;
        var url_end_date = 0;
		var cat_id 		 = $('#CatId').val();
        var sub_cat_id = $('#SubCatId').val();
        if (!sub_cat_id) {
            sub_cat_id = 0;
        }
        if(cat_id == '' || cat_id.length == 0)
            cat_id = 0;
        var url = "<?php echo $this->Html->url(array('controller' => 'Reports', 'action' => 'sales_report_excel')); ?>/" +cat_id+ "/" + sub_cat_id+ "/" +  card_id + "/" + url_start_date + "/" + url_end_date + "/" + sales_id;
        window.location.href = url;
    }

    $('#print').click(function(){
		
        var report_name = "<div style='font-weight:bold;font-size:14px;'><?php echo __('Sales Report');?></div>";
        
        var report_name = report_name+"<div style='margin-top:10px;font-size:12px;'><?php echo __('Retailer').': <b>'.ucwords(strtolower($this->Session->read('Auth.User.fname').' '.$this->Session->read('Auth.User.lname')).'</b>') ;?></div><br/>";

        var report_name = report_name+"<div style='margin-top:-10px;font-size:12px;'><?php echo __('Account Number').': <b>'.$this->Session->read('Auth.User.username').'</b>' ;?></div><br/>";
        
        <?php

           $address = $this->Session->read('Auth.User.address');
           $address = str_replace(array("\r", "\n"), " ", $address);
        ?>  

        var retailer_address = '<?php echo $address;?>';
        if(retailer_address.length ==0)
        {
          var report_name = report_name+"<div style='margin-top:-10px;font-size:12px;'><?php echo __('Address').': <b>N/A</b>' ;?></div><br/>"; 
        }
        else
        {
          var report_name = report_name+"<div style='margin-top:-10px;font-size:12px;'><?php echo __('Address').': <b>'.$address.'</b>' ;?></div><br/>"; 
        } 
        
        var cat_id = "<?php echo @$selected_cat?>";
        if(cat_id != '' && cat_id.length != 0 && cat_id != 0)
        {
          var report_name = report_name+"<div style='margin-top:-10px;font-size:12px;'><?php echo __('Category').': <b>'.@$cateList[@$selected_cat].'</b>';?></div><br/>";
        }
      
        var subCateList = "<?php echo @$selected_sub_cat?>";
        if(subCateList != '' && subCateList.length != 0 && subCateList !=0)
        {
           var report_name =  report_name+"<div style='margin-top:-10px;font-size:12px;'><?php echo __('Sub Category').': <b>'.@$subCateList[@$selected_sub_cat].'</b>';?></div><br/>";
        }
        
        var card_id = "<?php echo @$card_id?>";
        if(card_id != '' && card_id.length != 0 && card_id !=0)
        {
             var report_name =  report_name+"<div style='margin-top:-10px;font-size:12px;'><?php echo __('Card').': <b>'.@$all_cards[@$card_id].'</b>';?></div>";
        }
        
        var start_date = "<?php echo $date_set_start;?>";
        var end_date = "<?php echo $date_set_end;?>"; 
        if(start_date.length != 0)
        {
           var report_name =  report_name+"<div style='margin-top:10px;font-size:12px;'><?php echo __('Date Selection').': <b>'.$date_set_start.' - '.$date_set_end.'</b>';?></div><br/>";
        }
       
        var thePopup = window.open( '', "Sales Report", "menubar=0,location=0,height=700,width=700" );
		var new_style = '<style>.filters{display : none; } a{color:#333333 !important;}thead>tr>th{border-bottom:2px solid #CCCCCC;border-top:2px solid #CCCCCC; padding-bottom:5px;font-size:12px;}tfoot>tr>td{border-bottom:2px solid #CCCCCC;border-top:2px solid #CCCCCC; padding-bottom:5px;padding-top:5px;font-size:12px;}tbody>table-bordered{border:none !important;}tbody>tr>td{font-size:12px;} </style>';

        thePopup.document.write(report_name+$('.main_print_div').html()+new_style);
        	$('#popup-content').clone().appendTo( thePopup.document.body );
			thePopup.print();
	});

</script>