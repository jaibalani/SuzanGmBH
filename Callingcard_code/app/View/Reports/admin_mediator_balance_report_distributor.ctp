<style type="text/css">
/*
.ui-helper-clearfix:before, .ui-helper-clearfix:after {
	display: inline-table;
}
*/
.ui-widget-content {
	background: url("images/ui-bg_flat_75_ffffff_40x100.png") repeat-x
		scroll 50% 50% #ffffff;
}

.form-control {
	padding: 6px 7px;
}

.label-style {
	font-weight: 100 !important;
	margin-right: 37px !important;
}
</style>

<div align="left" style="padding-right: 10px;">
	<div class="page_title"><?php echo $title_for_layout; ?></div>
	<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
	<div class="sub_title">
		<i class="icon-user home_icon"></i> <span class="sub_litle_m">Reports</span>
		<i class="icon-angle-right home_icon"></i> <span>Mediator Balance Report</span>
	</div>
	<div class="main_subdiv">
		<div class="gird_button">
			<div class="main_sub_title mediator_w" style="width: 30%;"><?php echo $title_for_layout; ?></div>
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
            <div class="col-md-2" style="float: right; padding-right: 0px;">
					<button class="new_button" id="filter_card_button" type="button"
						style="cursor: pointer; float: right;">
						<span class="icon-filter icon-white"></span>&nbsp;&nbsp;Filter
						Data
					</button>
				</div>
            <div class="clear10"></div>
			
			<div class="row">
			<div class="col-md-4">
				<div class="col-md-5" style="">Select Mediator</div>
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
      

				<div class="col-md-2 selectbox_title">Download Excel Report</div>
				<div class="col-md-2" style="margin-top: 6px;">	 
		<?php  echo $this->Html->image(IMAGE_PATH.'/images/excel.png',array('alt'=>'Excel','border'=>'0','div'=>false,'id'=>'excel_sales','class'=>'cursor_class'));?>
     </div>

			</div>
			<div class="clear10"></div>

    <?php
				if ($mediator_id || ! $mediator_id) {
					?>
    <table class="table table-striped" id="t_filter">
        
        <thead>
					<tr>
						<th>S.No.</th>
						<th>Mediator</th>
						<th>Total Amount</th>
						<th>Available Balance</th>
						<th>Modified Date</th>
						<th filter=false>Action</th>
					</tr>
				</thead>

				<tbody>
           <?php
					
$sales_counter = 1;
					if (! empty ( $get_account_balance_data )) {
						
						foreach ( $get_account_balance_data as $data ) {
							?>
           			<tr class="sum">
						<td><?php echo $sales_counter; $sales_counter++;?></td>
						<td><?php echo ucwords(strtolower($data[0]['mediator_name'])); ?></td>
						<td data-balance="<?php echo $data['Transaction']['total_amount'];?>"><?php echo "&euro;".$data['Transaction']['total_amount']; ?></td>
						<td data-amount="<?php echo $data['Transaction']['balance'];?>"><?php echo "&euro;".$data['Transaction']['balance']; ?></td>
						<td><?php echo date('d.m.Y',strtotime($data['Transaction']['updated']));?></td>
						<td>
               				<?php  echo $this->Html->image(IMAGE_PATH.'/images/excel.png',array('alt'=>'Calender','class'=>'','border'=>'0','div'=>false,'class'=>'cursor_class','onclick'=>'single_excel_report('.$data["Transaction"]["id"].')'));?>
               			</td>
					</tr>
           <?php } ?>
                    <tfoot>
                    <tr style="font-weight: bold;">
						<td></td>
						<td></td>
                        <td class="total-balance">&euro;<?php echo $total_retailer_amount;?></td>
                        <td class="total-total">&euro;<?php echo $total_retailer_balance;?></td>
                        <td></td>
					</tr> 
                    </tfoot>
           <?php } else { ?>
                    <tr>
						<td colspan="8" align="center"><?php echo __('No records found.');?></td>

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
   $('#mediator_balance_report').addClass('sb_active_subopt_active');
   
    /* Calculate total on table filter */
        var filterTbl = $('#t_filter');
        var options2 = {                
		      clearFiltersControls: [$('.clear_filer_class')],   
			  enableCookies :false,
              filteringRows: function(filterStates) {
                
              },
              filteredRows: function(filterStates) {
				  
                var sumBalance = 0;
                var sumTotal = 0;
                $('.sum').each(function() {
                    if(!$(this).attr("filtermatch")){
                        var p = parseFloat($(this).find('td').eq(2).data('balance'));
                        var t = parseFloat($(this).find('td').eq(3).data('amount'));
                        //alert(p);
                        sumBalance += p;
                        sumTotal += t;
                    }
                });
                $('.total-balance').html("€"+parseFloat(sumBalance).toFixed(2));
                $('.total-total').html("€"+parseFloat(sumTotal).toFixed(2));
              }
            };   
            
       filterTbl.tableFilter(options2);
       $('.filters > td >input').removeAttr('title');
}) ;

$('#filter_card_button').click(function(){
 
 var card_id = $('#card_id').val();
 var mediator_id = $('#mediator_id').val();


 
 if(mediator_id == '' || mediator_id.length == 0)
  mediator_id = 0;


 
 	var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'mediator_balance_report_distributor'));?>/"+mediator_id;

 window.location.href = url;
});


$('#excel_sales').click(function(){

 
 var mediator_id = "<?php echo @$mediator_id;?>";

 
 if(mediator_id == '' || mediator_id.length == 0)
 mediator_id = 0;




 var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'mediator_balance_report_excel_distributor'));?>/" +mediator_id;

 window.location.href = url;
	
});

function single_excel_report(transaction_id)
{
 	var retailer_id = 0;
 	var mediator_id = 0;
	var url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'mediator_balance_report_excel_distributor'));?>/" +mediator_id+"/"+transaction_id;
    window.location.href = url;
}

$('.back').click(function(){
  history.go(-1);
});

$('.clear_filer_class').click(function(){
	var new_url = "<?php echo $this->Html->url(array('controller'=>'Reports','action'=>'mediator_balance_report_distributor','admin'=>'true'));?>";
    window.location = new_url;
});

</script>
