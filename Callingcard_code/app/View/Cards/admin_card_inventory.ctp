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
    padding: 5px 16px;
    width: 100%;
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
    padding: 5px 0;
    width: 100%;
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
    padding: 5px 16px;
    width: 100%;
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
    padding: 5px 16px;
    width: 100%;
    text-align: left;
    margin-bottom: 10px;
}
</style>

<div align="left" style="padding-right: 10px;">
	<div class="page_title"><?php echo $title_for_layout; ?></div>
	
    <div class="sub_title">
		<i class="icon-user home_icon"></i> <span class="sub_litle_m">Reports</span>
		<i class="icon-angle-right home_icon"></i> <span>Card Inventory Report</span>
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
				<div class="col-md-4 " align="left"
					style=" padding-left: 0px !important;">
					<div class="col-md-5" style="">Select Card</div>
                        <?php
								echo $this->Form->input ( 'card_id', array (
										'type' => 'button',
										'class' => ' select_boxfrom form_submit',
										'div' => 'col-md-7',
										'type' => 'select',
										'options' => @$all_cards,
										'value' => @$card_id,
										'label' => false,
										'style' => 'cursor:pointer;',
										//'empty' => '--- All ---' 
								) );
								?>
                </div>
                
                <div class="col-md-3 selectbox_title">Download Excel Report</div>
				  <div class="col-md-2" style="margin-top: 6px;">	 
		          <?php  echo $this->Html->image(IMAGE_PATH.'/images/excel.png',array('alt'=>'Excel','border'=>'0','div'=>false,'id'=>'excel_cards','class'=>'cursor_class'));?>
                </div>
             
				<div class="col-md-2" style="float: right; padding-right: 0px;">
					<button class="new_button" id="filter_card_button" type="button"
						style="cursor: pointer; float: right;">
						<span class="icon-filter icon-white"></span>&nbsp;&nbsp;Filter
						Data
					</button>
				</div>
			</div>
    <div class="clear10"></div>        
            
	<div class="clear10"></div>
    
    <?php 
    		//prd($inventory_data_category_wise);
            $total_cards_remain = 0;
            $total_cards_sold = 0;
            $final_cards_remain = 0;
            $final_cards_sold = 0;

            $table_counter = 0;
    		foreach($inventory_data_category_wise as $key => $value)
    		{
    		   if($value['chnage_cat'] == 1 )
               { 
    		     
    		     if($key !=0)
    		     {


    ?>  
           <div class="total_sub_cat">
           <div class="row">
            <div class="col-md-12">
                 <?php 
                 
                 echo __('Total Cards Sold')." : ". $total_cards_sold."&nbsp;&nbsp;&nbsp;";
                 echo __('Total Cards Remaining')." : ". $total_cards_remain ;  
                 $total_cards_sold = 0;
                 $total_cards_remain = 0;
                 ?>
            </div>
          </div>
        </div>  
           <?php } ?>
    			<div class="selectbox_div new_cat">
    				<div class="col-md-12">
    				<?php echo __('Category : ').$value['main_cat_title'];?>
    				</div>
    			</div>
           <?php } ?>
           
          <div class="new_cat_sub_cat">
			<div class="row">
				<div class="col-md-12">
				   <?php 
				   echo __('Sub Category').": ".$value['sub_cat_title'];    
				   ?>
				</div>
			</div>
		  </div>
          <?php 
	          if(isset($value['card_data']) && !empty($value['card_data']))
	          {
	          	$table_counter++;
	                 
          ?>   
      <table class="table table-striped" id="t_filter<?=$table_counter?>">
        <thead>
					<tr>
						<th>S.No.</th>
						<th>Category</th>
						<th>Sub Category</th>
						<th>Card Name</th>
						<th>Total Sold</th>
						<th>Remaining Balance</th>
						<th filter=false>Action</th>
					</tr>
				</thead>

				<tbody>
                    <?php
						$card_count = 1;
						$sub_cat_sold = 0;
                    	$sub_cat_remain = 0; 
						foreach ($value['card_data'] as $data_card) 
	                    { 

	                    	$total_cards_sold = $total_cards_sold + $data_card['card_sold'];
	                    	$total_cards_remain = $total_cards_remain + $data_card['card_remaining'];

	                    	$final_cards_sold = $final_cards_sold + $data_card['card_sold'];
	                    	$final_cards_remain = $final_cards_remain + $data_card['card_remaining'];

	                    	$sub_cat_sold = $sub_cat_sold + $data_card['card_sold'];
	                    	$sub_cat_remain = $sub_cat_remain + $data_card['card_remaining'];

	                ?>
           				<tr class="sum<?=$table_counter?>" >
						<td><?php echo $card_count; $card_count++;?></td>
						<td><?php echo $value['main_cat_title']; ?></td>
						<td><?php echo $value['sub_cat_title']; ?></td>
                        <td><?php echo $data_card['title']; ?></td>
                        <td data-quantity="<?php echo $data_card['card_sold']; ?>"><?php echo ucwords($data_card['card_sold']); ?></td>
                        <td data-total="<?php echo $data_card['card_remaining']; ?>"><?php echo ucwords($data_card['card_remaining']); ?></td>
                       <td>
               <?php  echo $this->Html->image(IMAGE_PATH.'/images/excel.png',array('alt'=>'Calender','class'=>'','border'=>'0','div'=>false,'class'=>'cursor_class','onclick'=>'single_excel_report('.$data_card["id"].')'));?>
               </td>
					</tr>
           <?php } ?>
                    <tfoot>
                    <tr style="font-weight: bold;">
						<td colspan="3"></td>
						<td>Net Quantity</td>
                        <td class="total-quantity<?=$table_counter?>"><?php echo $sub_cat_sold;?></td>
                        <td class="total-total<?=$table_counter?>" colspan="3"><?php echo $sub_cat_remain;?></td>
					</tr> 
                    </tfoot>
         </tbody>
	 </table>
   <?php }  else { ?>
      <div class="row">
            <div class="col-md-12">
                 <?php 
                   echo __('<span style="margin-left:16px;"><b>No records are found.</b></span>');
                 ?>
            </div>
       </div>
  <?php } ?>
  <!-- Else No Record Found --> 
      <?php if(!empty($inventory_data_category_wise) && count($inventory_data_category_wise) == $key+1) { ?>
     <div class="total_sub_cat">
           <div class="row">
            <div class="col-md-12">
                 <?php 
                 echo __('Total Cards Sold')." : ". $total_cards_sold."&nbsp;&nbsp;&nbsp;";
                 echo __('Total Cards Remaining')." : &euro;". $total_cards_remain ;  
                 ?>
            </div>
       </div>
    </div>
    <div class="final_detais">
             <div class="row">
              <div class="col-md-12">
                   <?php 
                   
                   echo __('Final Details').' -- '.__('Total Cards Sold')." : ". $final_cards_sold."&nbsp;&nbsp;&nbsp;";
                   echo __('Total Cards Remaining')." : ". $final_cards_remain ;  
                   ?>
              </div>
         </div>
    </div>
    <?php } ?>
    <?php }  ?>
     
  </div>
		<div class="clear10"></div>
	</div>
</div>

<script type="text/javascript">

$(document).ready(function(){
   $('#reports').addClass('sb_active_opt');
   $('#reports').removeClass('has_submenu');
   $('#card_inventory_report').addClass('sb_active_subopt_active');
   
    /* Calculate total on table filter */
        var count_class = $('.table').length;
      /*  for(i = 1; i<= count_class; i++)
        {
        	 var filterTbl = $('#t_filter'+i);
             var options2 = { 
		      clearFiltersControls: [$('.clear_filer_class')],   
			  enableCookies :false,
			  filteringRows: function(filterStates) {
                
              },
              filteredRows: function(filterStates) {
                var sumQuantity = 0;
                var sumTotal = 0;
                $('.sum'+i).each(function() {
                    if(!$(this).attr("filtermatch")){
                        var p = parseInt($(this).find('td').eq(4).data('quantity'));
                        var t = parseInt($(this).find('td').eq(5).data('total'));
                        sumQuantity += p;
                        sumTotal += t;
                    }
                });
                $('.total-quantity'+i).html(sumQuantity);
                $('.total-total'+i).html(parseFloat(sumTotal).toFixed(2));
              }
            };   
    	    filterTbl.tableFilter(options2);
       	    $('.filters > td >input').removeAttr('title');
    }*/
        
}) ;

$('#filter_card_button').click(function(){

 var card_id = $('#card_id').val();
 var CatId = $('#CatId').val();
 var SubCatId = $('#SubCatId').val();
 if(card_id == '' || card_id.length == 0)
 card_id = 0;
 
 if(CatId == '' || CatId.length == 0)
 CatId = 0;
 
 if(SubCatId == '' || SubCatId.length == 0)
 SubCatId = 0;
 
 var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'admin_card_inventory'));?>/"+CatId+"/"+SubCatId+"/"+card_id;
 window.location.href = url;

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
			$('#card_id').html('<option value="0">--- All ---</option>');

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

        var cat_id = $('#CatId').val();
        var sub_cat_id = $(this).val();
        if (!sub_cat_id) 
		{
            sub_cat_id = 0;
        }
		
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
			$('#card_id').html('<option value="0">--- All ---</option>');
				
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

$('#excel_cards').click(function(){
	
	var cat_id = <?php echo @$selected_cat; ?>;
	var sub_cat_id = 0;
	<?php if (@$selected_sub_cat > 0) { ?>
	var sub_cat_id = <?php echo $selected_sub_cat; ?>;
	<?php } ?>
	var card_id = "<?php echo @$card_id;?>";
	
	if(card_id == '' || card_id.length == 0)
	card_id = 0;
	
	if(cat_id == '' || cat_id.length == 0)
	cat_id = 0;
	
	if(sub_cat_id == '' || sub_cat_id.length == 0)
	sub_cat_id = 0;
    
 	var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'excel_card_inventory'));?>/"+cat_id+ "/" + sub_cat_id + "/" +card_id;
    window.location.href = url;
});

function single_excel_report(card_id)
{
	var cat_id = 0;
	var sub_cat_id = 0;
 	var card_id = card_id;
	var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'excel_card_inventory'));?>/"+cat_id+ "/" + sub_cat_id + "/" +card_id;
    window.location.href = url;
}

$('.back').click(function(){
  history.go(-1);
});

$('.clear_filer_class').click(function(){
	var new_url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'card_inventory','admin'=>'true'));?>";
    window.location = new_url;
});

</script>
