<?php 
 $language=Configure::read('Config.language');
  if(empty($language))
  $language="en";
?>


<style type="text/css">

.sales-reports
{ 
  float: left;
  width: 100%; 
}

.sales-reports table{ width: 760px; float: left; }

.sales-reports table tr th{ padding-left: 8px; background-color: #fff; color: #382F2F; border-bottom: 0px; font-size: 12px;}

.sales-reports table tr th:first-child{ width: 328px; }

.sales-reports table tr th:nth-child(2){ width: 135px; }

.sales-reports table tr th span{ margin-left: 20px; }

.sales-reports table tr:nth-child(odd){ background-color: #f9f9f9;  }

.sales-reports table tr:nth-child(even){ background-color: #fff;  }

.sales-reports table tr td{ border-left:0px; border-right: 0px; padding-left: 15px; padding-right: 15px; color: #333333; font-size: 12px;  }

.sales-reports table tr th:nth-child(3){ width: 107px; }

.sales-reports table tr th:nth-child(4){ width: 85px; }

.sales-reports table tr th:last-child{ width: 90px; }

.sales-reports table tfoot td{ border-right: 1px solid #ccc; }

.sales-reports table tfoot td:first-child{ background-color: #F2F2F2; text-align: right; font-weight: bold; }

#print{
  cursor:pointer;
}
.final_detais{
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #00ffff;
    border-color: #ddd #ddd -moz-use-text-color;
    border-image: none;
    border-width: 1px 1px 0;
    float: left;
    margin-top: 12px;
    padding: 0px 0;
    width: 760px;
    text-align: left;
    font-weight: 600;
}
.new_cat{
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #e8e8e8;
    border-color: #ddd #ddd -moz-use-text-color;
    border-image: none;
    border-width: 1px 1px 0;
    float: left;
    margin-top: 12px;
    padding: 0px 0;
    width: 760px;
    text-align: left;
    font-weight: 600;
}
.total_sub_cat{
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #03549f;
    color: #FFFFFF;
    border-color: #ddd #ddd -moz-use-text-color;
    border-image: none;
    border-width: 1px 1px 0;
    float: left;
    margin-top: 12px;
    padding: 0px 0;
    width: 760px;
    text-align: left;
    font-weight: 600;
}
.new_cat_sub_cat{
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #bebebe;
    border-color: #ddd #ddd -moz-use-text-color;
    border-image: none;
    border-width: 1px 1px 0;
    float: left;
    margin-top: 12px;
    padding: 0px 0;
    width: 760px;
    text-align: left;
    margin-bottom: 10px;
}
.row{
    margin-top: 5px !important;
    margin-bottom: 5px !important;
}
#sr-date-panel .row{
    margin-top: 0px !important;
    margin-bottom: 0px !important;
}
#sr-date-panel .row .col-xs-12{ padding: 0px; } 
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
                            <label><?php echo __('Category:') ?></label><br/>
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
                            <label><?php echo __('Sub Category:') ?></label><br/>
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
                            <label><?php echo __('Card Name') ?></label><br/>
                        </div>
                        <div class="col-xs-12">
                            <div class="input-group sel-input" style="width: 132px;">
                                <?php
                                echo $this->Form->input('Card.id', array(
                                    'label' => false,
                                    'class' => 'form-control selectbox_graditent',
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
                    <div class="col-xs-4">
                        <div class="col-xs-12">
                            <label><?php echo __('From Date:') ?></label>
                        </div>
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
                    <div class="col-xs-4">
                          <div class="col-xs-12">
                                <label><?php echo __('To Date:') ?></label>
                          </div>
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
                                <input type="button" name="filter" value="<?php echo __('APPLY FILTER');?>" class="button-gradient" id="filter_card_button" style="margin-left:1px; margin-top:32px;"/>
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

    <?php 
        
        $counter = 0; 
        $total_sales_for_main_cat =0;
        $total_cards_for_main_cat =0;
        $final_sales_for_main_cat =0;
        $final_cards_for_main_cat =0;
        $previou_cat = ''; 

        foreach ($sales_data_category_wise as $data_sales) 
        {
            if($data_sales['chnage_cat'] == 1 )
            {    

                if($counter !=0) 
                {

        ?>
               <div class="total_sub_cat">
                         <div class="row">
                          <div class="col-md-12">
                               <?php 
                               
                               echo __('Total Cards')." : ". $total_cards_for_main_cat."&nbsp;&nbsp;&nbsp;";
                               echo __('Total Sales')." : &euro;". $total_sales_for_main_cat ;  
                               $total_sales_for_main_cat = 0;
                               $total_cards_for_main_cat = 0;


                               ?>
                          </div>
                     </div>
               </div>
       <?php  } ?>
                <div class="new_cat">
                 <div class="row">
                  <div class="col-md-12">
                       <?php 
                       
                       echo __('Category').": ".$data_sales['main_cat_title'] ;  

                       ?>
                  </div>
                 </div>
                </div>
        
        <?php   }   
                 $previou_cat =$data_sales['main_cat_title'];
                 
                 $counter++;
                 $total_sales_for_main_cat =$total_sales_for_main_cat+$data_sales['total_sales_amount'];
                 $total_cards_for_main_cat =$total_cards_for_main_cat+$data_sales['card_count'];

                 $final_sales_for_main_cat =$final_sales_for_main_cat+$data_sales['total_sales_amount'];
                 $final_cards_for_main_cat =$final_cards_for_main_cat+$data_sales['card_count'];

         ?>

                <div class="new_cat_sub_cat">
                 <div class="row">
                  <div class="col-md-12">
                       <?php 
                       
                       echo __('Sub Category').": ".$data_sales['sub_cat_title'];    

                       ?>
                  </div>
                 </div>
              </div>
              
<div class="main_print_div sales-reports">
  <table class="table table-bordered">
    <thead>
        <tr>
            <th style="text-align:left;width:180px;"><?php echo __('Card Name'); ?></th>
            <th> <?php echo __('Selling Price'); ?></th>
            <th><?php echo __('Quantity'); ?></th>
            <th><?php echo __('Net Total'); ?></th>
            <th style="width:140px;"><?php echo __('Date-Time'); ?></th>
            <th class="filters" filter='false'><?php echo __('Action'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php 
          if(isset($data_sales['sales_data']) && !empty($data_sales['sales_data']))
          {
             foreach ($data_sales['sales_data'] as $data) { ?>  
             <tr class="sum">
                <td>
                 <?php echo ucwords($data['Card']['c_title']) ?>
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
            }
            else
            {
        ?> 
            <td colspan="6" align="center"><?php echo __('No record found')?></td>
        <?php } ?>                                
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2"><?php echo __('Net Quantity')?></td>
            <td class="total-quantity"><?php echo $data_sales['card_count']; ?></td>
            <td class="total-total" colspan="3">&euro;<?php echo $data_sales['total_sales_amount']; ?>
            </td>
        </tr>
    </tfoot>
  </table>

    
    <?php if(!empty($sales_data_category_wise) && count($sales_data_category_wise) == $counter) { ?>
    <div class="total_sub_cat">
           <div class="row">
            <div class="col-md-12">
                 <?php 
                 echo __('Total Cards')." : ". $total_cards_for_main_cat."&nbsp;&nbsp;&nbsp;";
                 echo __('Total Sales')." : &euro;". $total_sales_for_main_cat ;  
                 $total_sales_for_main_cat = 0;
                 $total_cards_for_main_cat = 0;
                 ?>
            </div>
       </div>
    </div>

    <div class="final_detais">
             <div class="row">
              <div class="col-md-12">
                   <?php 
                   
                   echo __('Final Details').' -- '.__('Total Card')." : ". $final_cards_for_main_cat."&nbsp;&nbsp;&nbsp;";
                   echo __('Total Sales')." : &euro;". $final_sales_for_main_cat ;  
                   ?>
              </div>
         </div>
    </div>
    <?php } }?>
  </div>
</div>

<script type="text/javascript">
    $(document).ready(function (){
        //Highlight active menu
		$("#loading-image").fadeOut();
        $('#sb-opt-report').addClass('opt-selected');
        $('#sb-opt-report').next().toggle('sliderup');
        $('#sb-subopt-sales-report_detailed').addClass('opt-selected');

        //set panel on language change
        <?php if($language == 'deu'){?>
            $('#datepicker1').css('width','129px');
            $('#datepicker2').css('width','129px');
        <?php }else{ ?>
            $('#datepicker1').css('width','158px');
            $('#datepicker2').css('width','158px');
        <?php    
        }?>
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
            var url = "<?php echo $this->Html->url(array('controller' => 'Reports', 'action' => 'detailed_sales_report')); ?>/" + card_id + "/" + url_start_date + "/" + url_end_date + "/" + cat_id + "/" + sub_cat_id;
        }
        else
        {
            url_start_date = null;
            url_end_date = null;
            var url = "<?php echo $this->Html->url(array('controller' => 'Reports', 'action' => 'detailed_sales_report')); ?>/" + card_id + "/" + url_start_date + "/" + url_end_date + "/" + cat_id + "/" + sub_cat_id;
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
            var url = "<?php echo $this->Html->url(array('controller' => 'Reports', 'action' => 'detailed_sales_report_excel')); ?>/"+cat_id+ "/" + sub_cat_id+ "/" + card_id + "/" + url_start_date + "/" + url_end_date;
        }
        else
        {
            var url = "<?php echo $this->Html->url(array('controller' => 'Reports', 'action' => 'detailed_sales_report_excel')); ?>/" + cat_id + "/" + sub_cat_id+ "/" +  card_id;
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
        var url = "<?php echo $this->Html->url(array('controller' => 'Reports', 'action' => 'detailed_sales_report_excel')); ?>/" +cat_id+ "/" + sub_cat_id+ "/" +  card_id + "/" + url_start_date + "/" + url_end_date + "/" + sales_id;
        window.location.href = url;
    }
		$('#print').click(function(){
			var thePopup = window.open( '', "Sales Report", "menubar=0,location=0,height=700,width=700" );
            thePopup.document.write($('.main_print_div').html()+'<style>.filters{display : none; } </style>');
            	$('#popup-content').clone().appendTo( thePopup.document.body );
				thePopup.print();
		});

</script>