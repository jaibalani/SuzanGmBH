<style type="text/css">
.con-search-label:nth-child(2){
width:270px; 
}
#con-date-panel .row{ margin-right:0px !important; margin-left:0px !important; }
.sb-top-margin{ margin-top: 30px; }
</style>
<div class="right-part right-panel">
   <!--Contract Start-->
    <div class="sb-page-title">
        <strong><?php echo __('Contract');?></strong>
    </div>
    <div id="sales-details" class="spacer12">
        <table class="table table-bordered">
            <thead>
              <tr>
                  <th colspan="2"><?php echo __('Cards Sales Details');?></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo __('Amount Paid');?>:</td>
                <td align="right">&euro;<?php echo $total_paid_amount ;?></td>
              </tr>
              <tr>
                <td><?php echo __('Total Purchase Amount');?>:</td>
                <td align="right">&euro;<?php echo $total_purchase ;?></td>
              </tr>
              <tr>
                <td><?php echo __('Balance Amount');?>:</td>
                <td align="right">&euro;<?php echo $available_balance ;?></td>
              </tr>
              <tr>
                <td><?php echo __('Purchase Amount (Today)');?>:</td>
                <td align="right">&euro;<?php echo $todays_sales['sales_amount'] ;?></td>
              </tr>
              <tr>
                <td><?php echo __('No. of Cards Purchased (Today)');?>:</td>
                <td align="right"><?php echo $card_sale_count ;?></td>
              </tr>
            </tbody>
          </table>

    </div>

    <div id="con-date-range">

        <div id="con-date-panel">
          <?php echo $this->Form->create('User'); ?>
              <div class="row">
                  <div class="col-xs-4 sb-top-margin">
                      <div class="input-group">
                          <div class="input-group-addon" style="padding:1px 12px; font-size:13px;">
                              <?php  echo $this->Html->image(IMAGE_PATH.'/images/calender.png',array('alt'=>'Calender','class'=>'','border'=>'0','div'=>false));?>
                               <div style="color:#4570B8; float:left;cursor:pointer;" class="reset_from"><?php echo __('Reset') ?></div>
                          </div>
                          <?php echo $this->Form->input('datepicker1',array('id'=>'datepicker1','label'=>false,'placeholder'=>__('From Date:'),'class'=>'form-control','required'=>false,'value'=>@$start_date)); ?> 
                       </div>
                  </div>
                  <div class="col-xs-4 sb-top-margin">
                       <div class="input-group">
                          <div class="input-group-addon" style="padding:1px 12px; font-size:13px;">
                            <?php  echo $this->Html->image(IMAGE_PATH.'/images/calender.png',array('alt'=>'Calender','class'=>'','border'=>'0','div'=>false));?>
                             <div style="color:#4570B8; float:left;cursor:pointer;" class="reset_to"><?php echo __('Reset') ?></div>
                          </div>
                        <?php echo $this->Form->input('datepicker2',array('id'=>'datepicker2','label'=>false,'placeholder'=>__('To Date:'),'class'=>'form-control','required'=>false,'value'=>@$end_date)); ?>
                        
                       </div>
                  </div>
                  <div class="col-xs-2">
                        <label><?php echo __('Records');?></label>
                        <div class="input-group sel-input">
                            <?php 
                            $options = array(0=>__('All'),10=>10,20=>20,30=>30,40=>40,50=>50);
                            echo $this->Form->input('total_records',array('type'=>'select',
                                'label'=>false,
                                'options'=>$options,
                                'class'=>'form-control selectbox_graditent',
                                'value'=>@$records)); 
                            ?>
                        </div>
                  </div>
                  <div class="col-xs-2">
                      <?php echo $this->Form->input(__('Go'),array('id'=>'filter_card_button','label'=>false,'type'=>'submit','style' => 'margin-top:32px;','class'=>'button_gradient' ,'onclick'=>'return validation();')); ?>
                  </div>
              </div>
               <?php echo $this->Form->end(); ?>
        </div>
    </div>
    <div id="contract">
        <table class="table table-bordered">
            <thead>
              <tr>
                <th align="center"><?php echo __('Transaction Date');?><div class="down_arrow_filter"></div></th>
                <th><?php echo __('Sales Amount')."(&euro;)";?></th>
                <th><?php echo __('Amount Paid')."(&euro;)";?></th>
                <th><?php echo __('Previous Balance')."(&euro;)";?></th>
                <th><?php echo __('Payment Type');?></th>
                <th><?php echo __('Remarks');?></th>
              </tr>
                            
            </thead>
            
            <tbody>
                <?php if(!empty($set_all_transactions)) {
                    foreach($set_all_transactions as $transaction)
                    { //prd($transaction);
                ?>
                <tr>
                <td><?php echo !empty($transaction['date']) ? $transaction['date'] : '-'; ?></td>
                <td><?php echo !empty($transaction['date_sale']) ? $transaction['date_sale'] : '-'; ?></td>
                <td><?php echo !empty($transaction['total_amount']) ? $transaction['total_amount'] : '-'; ?></td>
                <td><?php echo !empty($transaction['previous_balance']) ? $transaction['previous_balance'] : '-'; ?></td>
                <td><?php echo !empty($transaction['payment_mode']) ? $transaction['payment_mode'] : '-'; ?></td>
                <td><?php echo !empty($transaction['remarks']) ? $transaction['remarks'] : '-'; ?></td>
                </tr>
                <?php } } else { ?>
                <tr>
                <td colspan="6" align="center"><?php echo __('No records found.');?></td>
                </tr>
                <?php } ?>   
        </tbody>
    </table>
    </div>
 <!--Contract End-->
</div>

<script type="text/javascript">
$(document).ready(function(){
	//Highlight active menu
	$('#sb-opt-report').addClass('opt-selected');
	$('#sb-opt-report').next().toggle('sliderup');
	$('#sb-subopt-account-info').addClass('opt-selected');
});

	$(function() {

		var date = new Date();
		var currentMonth = date.getMonth();
		var currentDate = date.getDate();
		var currentYear = date.getFullYear();

		
		$( "#datepicker1" ).datepicker({
			changeMonth: true,
			changeYear: true,
			maxDate: new Date(currentYear, currentMonth, currentDate),
		    dateFormat: "dd.mm.yy",
		});
        $( "#datepicker2" ).datepicker({
			changeMonth: true,
			changeYear: true,
			maxDate: new Date(currentYear, currentMonth, currentDate),
			dateFormat: "dd.mm.yy",
		});
	});

function validation()
{
 var range_start_date = $('#datepicker1').val();
 var range_end_date = $('#datepicker2').val();
 
 var length_start = range_start_date.length;
 var length_end = range_end_date.length;
 
 if(length_start == 0 && length_end ==0)
 {
	 //alert("<?php echo __('Select both the dates.');?>");
	 return true;
 }
 else if((length_start == 0 && length_end !=0) || (length_start != 0 && length_end ==0))
 {
	 alert("<?php echo __('Either select both the dates or none.');?>");
	 return false;
 }
 else
 {
	/* Date Format  dd.mm.yy   Old Format  MM/DD /YYYY*/
	var start = range_start_date.split(".");
	var end = range_end_date.split(".");
	
	//var url_start_date = start[2] + "-" + start[0] + "-" + start[1]; 
	//var url_end_date = end[2] + "-" + end[0] + "-" + end[1]; 

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
 $('#datepicker1').val(range_start_date);
 $('#datepicker2').val(range_end_date);
 $('#UserAccountInformationForm').submit();

}

$('.reset_from').click(function () {
        $("#datepicker1").val('');
    });

    $('.reset_to').click(function () {
        $("#datepicker2").val('');
    });
</script>
