<style type="text/css">
	.selcet_sub {
		width: 16% !important;
	}	
</style>
<?php $class = 'pin_class2';
    if(isset($back_cat) && !empty($back_cat)){
    $class='pin_class';
}?> 

<?php echo $this->Form->create('Pin',array('id'=>'pin_from')); ?>
<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <div class="sub_title"><i class="icon-list-alt home_icon"></i> <span class="sub_litle_m">PIN Management</span> <i class="icon-angle-right home_icon"></i> <span>Manage card PINs</span></div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title <?php echo $class; ?>" style="width:45%;"><?php echo $title; ?></div>
             <button class="new_button clear_filer_class" style="float:right;" type="button" style="cursor:pointer;">
              	<span class="icon-remove icon-white"></span>&nbsp;&nbsp;Clear Filter
             </button>
             <?php if(isset($card_id) && !empty($card_id)){?>
             <button class="new_button new_button_right " id="add_button" type="button" style="float:right;margin-right:10px;"><span class="icon-plus icon-white"></span>&nbsp&nbsp;Import</button>
             <?php }?>
            <?php //if(isset($back_cat) && !empty($back_cat)){?>
			<button class="new_button back back" type="button" style="cursor:pointer;float:right;margin-right:10px;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
            <?php //}?> 
            
    </div>

    <div class="clear10"></div>
    <div class="selectbox_div">
       <div class="col-md-3" align="right" style="margin-right: 10px; padding-left:0px;">
                <?php
                 echo $this->Form->input('cat_id', 
                       array('type' => 'button',
                            'class'=>'select_boxfrom form_submit',
                            'type'=>'select',
                            'options'=> @$catList,
                            'value'=>@$cat_id,
                            'label'=>false,
                            'style'=>'cursor:pointer;',
                            'empty'=>'--- All Category ---'
                           )
                       );
                ?>
        </div>  
        <div class="col-md-3 " align="right" style="margin-right: 10px;">
                     <?php echo $this->Form->input('sub_cat_id', 
                            array(
                            	'type' => 'button',
                                'class'=>'select_boxfrom form_submit',
                                'type'=>'select',
                                'options'=> @$subCatList,
                                'value'=>@$sub_cat_id,
                                'label'=>false,
                                'style'=>'cursor:pointer;',
                                'empty'=>'--- All Sub Category ---'
                                )
                            );
                     ?>
        </div>
        <div class="col-md-3">
        	<?php
                 echo $this->Form->input('card_id', 
                       array('type' => 'button',
                            'class'=>'select_boxfrom form_submit',
                            'type'=>'select',
                            'options'=> @$cardList,
                            'value'=>@$card_id,
                            'label'=>false,
                            'style'=>'cursor:pointer;',
                            'empty'=>'--- All Card ---'
                           )
                       );
                ?>
							<?php /*?><?php echo $this->Form->input('card_id',array(
                'label' 	 => false, 
                'type' 		 => 'select',
    						'required' => false, 
    						'value' 	 => @$card_id,
                'class' 	 => 'select_boxfrom form_submit',
                'empty' 	 => '--- All Card ---',
                'options'  => @$cardList
               ));?><?php */?>
       	</div>
       	<div class="col-md-2">
				<?php 
				    $arrStatus=array(0=>'--- All Status ---', 1=> 'Unused',2=> 'Sold', 3=> 'Parked',4=> 'Rejected',5=> 'Returned');
				    echo $this->Form->input('pc_status', array('class'=>'select_boxfrom form_submit','options'=>$arrStatus, 'label'=>false,
				                                  'selected'=>'0')); 
				?>
        </div>
    </div> 

    <div class="selectbox_div" style="margin-top:15px;">
       <div class="col-md-2" align="right" style="margin-right: 10px; padding-left:0px;">
                <?php
                 echo $this->Form->input('file_name', 
                       array('type' => 'button',
                            'class'=>'select_boxfrom form_submit',
                            'type'=>'select',
                            'options'=> @$fileList,
                            'value'=>@$selected_file_name,
                            'label'=>false,
                            'style'=>'cursor:pointer;',
                            'empty'=>'--- All Files ---'
                           )
                       );
                ?>
        </div> 
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
       	<div class="col-md-2 selectbox_title">Download CSV Report</div>
		<div class="col-md-2" style="margin-top: 6px;">	 
			<?php  echo $this->Html->image(IMAGE_PATH.'/images/excel.png',array('alt'=>'Excel','border'=>'0','div'=>false,'id'=>'excel_sales','class'=>'cursor_class'));?>
			</div>
		</div>	


           	 
  <div class="clear10"></div>
  
  <div class="clear10"></div>
  <div align="left" class="grid_table_box">
    <table  width="100%" id="list"></table> 
		<div id="gridpager"></div>
  </div>
  <div class="clear10"></div>
  </div>
</div>
<?php echo $this->Form->end();  ?>
<script>

global_cat_id = '';
global_sub_cat_id = '';
global_card_id = '';
global_status = '';
global_file_name = '';
global_file_id = '';
global_start_date = '';
global_end_date = '';



$(document).ready(function (){
    jQuery("#list").jqGrid({
        url: "<?php echo Router::url(array('controller'=>'Pins','action'=>'admin_json','cat_id'=>@$cat_id,'sub_cat_id'=>@$sub_cat_id,'card_id'=> @$card_id,'pc_status'=>@$pc_status));?>",
        datatype: "json",
        colNames:['Serial Number','Pin Number','File Name','Date','Card Name' ,'Category' ,'Sub Category','Action','Status'],
        colModel:[
					{name:'Pin.p_serial',index:'Pin.p_serial',width:140,search:true, sortable:true, align:"left"},
					{name:'Pin.p_pin',index:'Pin.p_pin', width:140,search:true, sortable:true,align:"center"},
					{name:'pin_file',index:'pin_file', width:160,search:true, sortable:true,align:"center"},
					
					{
						name:'pin_created',
						index:'pin_created', 
						width:160,
						search:true,
						sortable:true,
						align:"center",
						formatter: 'date',
            			sorttype: 'date',
            			datefmt: 'Y-m-d H:i:s',
            			formatoptions: { srcformat: 'Y-m-d H:i:s', newformat: 'd.m.Y H:i:s' },
            			//searchoptions:{sopt:['eq']},
            	    },

					{name:'c_title',index:'c_title', width:145,search:true, sortable:false, stype:'text',align:"left"},
					{name:'cat_title',index:'cat_title', width:145,search:false, sortable:false, align:"left"},
					{name:'sub_cat_title',index:'sub_cat_title', width:145,search:false, sortable:false, align:"left"},
					{name:'edit',index:'edit', width:50,search:false, sortable:false,align:"center"},
					{name:'pc_status',index:'pc_status', width:90,search:false,stype:'select',sortable:true,stype:'select',searchoptions: {value: {0:'All', 1: 'Unused',2: 'Sold', 3: 'Parked',4: 'Rejected',5: 'Returned'},defaultValue: 0}, align:"center"}
        ],
        rowNum:50,
   		rowList:[10,20,30,50],
        pager: '#gridpager',
        sortname: 'Pin.p_serial',
        viewrecords: true,
        rownumbers: false,
        sortorder: "asc",
        caption:false,
        multiselect: false,
        height:"100%",
        autowidth:true,
        shrinkToFit:true,
        beforeRequest: function () {
      	$("#loading-image").fadeIn();
        }, 
        gridComplete: function() {
            $('#list tr:nth-child(odd)').addClass("evenTableRow");
            $('#list tr:nth-child(even)').addClass("oddTableRow");
            $("#loading-image").fadeOut();
        }
    });
    jQuery("#list").jqGrid('navGrid','#gridpager',{edit:false,search:false,add:false,del:false});
	jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

	//delete records
	$("#delete_button").click(function(){
		var ids = getSelectedRows();
		if(ids!=''){
			if(confirm("Are you sure? You want to remove this record.")){
				$.ajax({
					url: "<?php echo Router::url(array('controller'=>'Pins','action'=>'admin_delete'));?>", 
					type: "POST", 
					data: ({ids : ids}), 
					success: function(data){
						if(data == '1'){
							reloadGrid();
             				 alert('Selected record(s) deleted successfully.');
						}else{
             			 alert('Error in deleting.Please try again later.');
            }
					}
				});
			}
		}else{
			alert('Please select rows to perform this specific action.');
		}	
	});
	
	$("#add_button").click(function(){
		window.location = "<?php echo Router::url(array('controller'=>'Pins','action'=>'admin_add',$card_id));?>";
	});
<?php if(isset($back_cat) && !empty($back_cat)){?>
				$('.back').click(function(){
					//window.location.href = '<?php //echo $url; ?>';
					history.go(-1);
					/*window.location = "<?php //echo Router::url(array('controller'=>'Cards','action'=>'admin_index',$back_cat));?>";*/
				});

<?php }?>
});

function reloadGrid(){
	jQuery("#list").trigger("reloadGrid");
}

function getSelectedRows(){
	var s = jQuery("#list").jqGrid('getGridParam','selarrrow');
	return s;
}

$('.back').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'dashboard','admin'=>'true'));?>";
    // window.location.href = url;
    history.go(-1);
});


function changeStatus(id,st)
{	
	var msg = "";
	if(st == 1){
		msg = "Are you sure? You want to active the status.";
	} else {
		msg = "Are you sure? You want to inactive the status.";
	}
	if(confirm(msg)){
		$.ajax({
			url: "<?php echo Router::url(array('controller'=>'Cards','action'=>'admin_changestatus'));?>", 
			type: "POST",
			data: ({id : id, st : st}),
			success: function(data){
				if(data == '1'){
					alert('Status has been changed successfully');
					reloadGrid();
				 }
			}
		});
			
	}
}
$('#PinCardId').change(function(){
		var cat_id 			=  $('#PinCatId').val();
		var sub_cat_id 	=  $('#PinSubCatId').val();
		var pc_status 	=  $('#PinPcStatus').val();
	 	var card_id 		=  $(this).val();
		var	file_name = 0;
		var new_url = '';
		
		$.ajax({
		  beforeSend: function (XMLHttpRequest){
			 $("#loading-image").fadeIn();
		  },
		  complete: function (XMLHttpRequest, textStatus) {
			$("#loading-image").fadeOut();
		  },
		  url: "<?php echo Router::url(array('controller'=>'pins','action'=>'get_file_name'));?>", 
		  type: "POST",
		  data: ({card_id : card_id,sub_cat_id:sub_cat_id,cat_id:cat_id}),
		  dataType: 'json',
		  success: function(json){
			
			$('#PinFileName').html('');
			$('#PinFileName').html('<option value="">--- All Files ---</option>');

			var keys = [];
			var datas = {}
			
			$.each(json, function(key, value){
			  keys.push(value)
			  datas[value] = key;
			})
			
			var aa = keys.sort()
			
			$.each(aa, function(index, value){
				$('#PinFileName').append($('<option>').text(value).attr('value', datas[value]));
			})			    
		  }
		});

		/*new_url = '/cat_id:'+cat_id;
		new_url = new_url+'/sub_cat_id:'+sub_cat_id;
		new_url = new_url+'/card_id:'+card_id;
		new_url = new_url+'/pc_status:'+pc_status;
		

		var updated_url = "<?php echo Router::url(array('controller'=>'Pins','action'=>'admin_json'));?>"+new_url;
     	$('#list').setGridParam({url:updated_url});
	    reloadGrid();*/
});

$('#PinFileName').change(function(){

		var cat_id 			=  $('#PinCatId').val();
		var sub_cat_id 	=  $('#PinSubCatId').val();
		var pc_status 	=  $('#PinPcStatus').val();
	 	var card_id 		=  $('#PinCardId').val();
	 	
	 	var file_name_id =  $(this).val();
        if(file_name_id.length != 0 )
        {
        	var file_name = $("#PinFileName option[value="+file_name_id+"]").text()
	 	}
	 	else
	    {
 			file_name = '';
	 	}
	 	
	 	/*var new_url = '';
     	new_url = '/cat_id:'+cat_id;
		new_url = new_url+'/sub_cat_id:'+sub_cat_id;
		new_url = new_url+'/card_id:'+card_id;
		new_url = new_url+'/pc_status:'+pc_status;
		new_url = new_url+'/file_name:'+file_name;

		var updated_url = "<?php echo Router::url(array('controller'=>'Pins','action'=>'admin_json'));?>"+new_url;
     	$('#list').setGridParam({url:updated_url});
	    reloadGrid();*/
});


$('#PinPcStatus').change(function(){
	
	 
	var cat_id 		=  $('#PinCatId').val();
	var sub_cat_id 	=  $('#PinSubCatId').val();
	var card_id = $('#PinCardId').val();
 	var pc_status =  $(this).val();
 	var file_name_id =  $('#PinFileName').val();
	if(file_name_id.length != 0 )
	{
	    var file_name = $("#PinFileName option[value="+file_name_id+"]").text()
	}
	else
	{
		file_name = '';
	}

	/*var new_url = '';
	new_url = '/cat_id:'+cat_id;
	new_url = new_url+'/sub_cat_id:'+sub_cat_id;
	new_url = new_url+'/card_id:'+card_id;
	new_url = new_url+'/pc_status:'+pc_status;
	new_url = new_url+'/file_name:'+file_name;
	var updated_url = "<?php echo Router::url(array('controller'=>'Pins','action'=>'admin_json'));?>"+new_url;
 	$('#list').setGridParam({url:updated_url});
    reloadGrid();*/
});

$('#PinSubCatId').change(function(){
		 
	     var sub_cat_id =  $('#PinSubCatId').val();
		 
		 if(sub_cat_id.length == 0)
		 var cat_id = $('#PinCatId').val();
		 else	
         var cat_id  = '';
		
		 var pc_status 	=  $('#PinPcStatus').val();
		 var card_id =  '';
		 $.ajax({
		  beforeSend: function (XMLHttpRequest){
			 $("#loading-image").fadeIn();
		  },
		  complete: function (XMLHttpRequest, textStatus) {
			$("#loading-image").fadeOut();
		  },
		  url: "<?php echo Router::url(array('controller'=>'cards','action'=>'get_cards'));?>", 
		  type: "POST",
		  data: ({id : sub_cat_id,main_cat_id : cat_id}),
		  dataType: 'json',
		  success: function(json){
			$('#PinCardId').html('');
			$('#PinCardId').html('<option value="">--- All Card---</option>');

			var keys = [];
			var datas = {}
			
			$.each(json, function(key, value){
			  keys.push(value)
			  datas[value] = key;
			})
			
			var aa = keys.sort()
			
			$.each(aa, function(index, value){
				$('#PinCardId').append($('<option>').text(value).attr('value', datas[value]));
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
		  url: "<?php echo Router::url(array('controller'=>'pins','action'=>'get_file_name'));?>", 
		  type: "POST",
		  data: ({card_id : card_id,sub_cat_id:sub_cat_id,cat_id:cat_id}),
		  dataType: 'json',
		  success: function(json){
			
			$('#PinFileName').html('');
			$('#PinFileName').html('<option value="">--- All Files ---</option>');

			var keys = [];
			var datas = {}
			
			$.each(json, function(key, value){
			  keys.push(value)
			  datas[value] = key;
			})
			
			var aa = keys.sort()
			
			$.each(aa, function(index, value){
				$('#PinFileName').append($('<option>').text(value).attr('value', datas[value]));
			})			    
		  }
		}); 

		/*var new_url = '';
		new_url = '/cat_id:'+cat_id;
		new_url = new_url+'/sub_cat_id:'+sub_cat_id;
		new_url = new_url+'/card_id:'+card_id;
		new_url = new_url+'/pc_status:'+pc_status;
		var updated_url = "<?php echo Router::url(array('controller'=>'Pins','action'=>'admin_json'));?>"+new_url;
     	$('#list').setGridParam({url:updated_url});
	    reloadGrid();*/
});

$('#PinCatId').change(function(){
 		
		 var cat_id =  $('#PinCatId').val();
		 var sub_cat_id =  '';
 		 var card_id =  '';
 		 var pc_status 	=  $('#PinPcStatus').val();
		 $.ajax({
		  beforeSend: function (XMLHttpRequest){
			 $("#loading-image").fadeIn();
		  },
		  complete: function (XMLHttpRequest, textStatus) {
			$("#loading-image").fadeOut();
		  },
		  url: "<?php echo Router::url(array('controller'=>'cards','action'=>'get_subcat'));?>", 
		  type: "POST",
		  data: ({id : cat_id}),
		  dataType: 'json',
		  success: function(json){
			$('#PinSubCatId').html('');
			$('#PinSubCatId').html('<option value="">--- All Sub Category---</option>');
			 
 			var keys = [];
			var datas = {}
			
			$.each(json, function(key, value){
			  keys.push(value)
			  datas[value] = key;
			})
			
			var aa = keys.sort()
			
			$.each(aa, function(index, value){
				$('#PinSubCatId').append($('<option>').text(value).attr('value', datas[value]));
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
		  url: "<?php echo Router::url(array('controller'=>'cards','action'=>'get_cards_parent_cat'));?>", 
		  type: "POST",
		  data: ({id : cat_id}),
		  dataType: 'json',
		  success: function(json){
			$('#PinCardId').html('');
			$('#PinCardId').html('<option value="">--- All Card---</option>');
			   
   			var keys = [];
			var datas = {}
			
			$.each(json, function(key, value){
			  keys.push(value)
			  datas[value] = key;
			})
			
			var aa = keys.sort()
			
			$.each(aa, function(index, value){
				$('#PinCardId').append($('<option>').text(value).attr('value', datas[value]));
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
		  url: "<?php echo Router::url(array('controller'=>'pins','action'=>'get_file_name'));?>", 
		  type: "POST",
		  data: ({card_id : card_id,sub_cat_id:sub_cat_id,cat_id:cat_id}),
		  dataType: 'json',
		  success: function(json){
			
			$('#PinFileName').html('');
			$('#PinFileName').html('<option value="">--- All Files ---</option>');

			var keys = [];
			var datas = {}
			
			$.each(json, function(key, value){
			  keys.push(value)
			  datas[value] = key;
			})
			
			var aa = keys.sort()
			
			$.each(aa, function(index, value){
				$('#PinFileName').append($('<option>').text(value).attr('value', datas[value]));
			})			    
		  }
		});

		
	    /*var new_url = '';
		new_url = '/cat_id:'+cat_id;
		new_url = new_url+'/sub_cat_id:'+sub_cat_id;
		new_url = new_url+'/card_id:'+card_id;
		new_url = new_url+'/pc_status:'+pc_status;
		var updated_url = "<?php echo Router::url(array('controller'=>'Pins','action'=>'admin_json'));?>"+new_url;
     	$('#list').setGridParam({url:updated_url});
	    reloadGrid();*/

});

$(document).ready(function(){
   $('#pin').addClass('sb_active_opt');
   $('#pin').removeClass('has_submenu');
   $('#managecard_active').addClass('sb_active_subopt_active');
}) ;

$('#filter_card_button').click(function () {

	var card_id = $('#PinCardId').val();
	var cat_id = $('#PinCatId').val();
	var sub_cat_id =  $('#PinSubCatId').val();
	var pc_status =  $('#PinPcStatus').val();
	var file_name_id =  $('#PinFileName').val();
	if(file_name_id.length != 0 )
	{
	    var file_name = $("#PinFileName option[value="+file_name_id+"]").text()
	}
	else
	{
		file_name = '';
	}
	

	if(card_id == '' || card_id.length == 0)
	card_id = 0;
	if(cat_id == '' || cat_id.length == 0)
	cat_id = 0;
	if(sub_cat_id == '' || sub_cat_id.length == 0)
	sub_cat_id = 0;
	if(pc_status == '' || pc_status.length == 0 || pc_status == 0)
	pc_status = 0;

	if(file_name == '' || file_name.length == 0 || file_name == 0)
	file_name = 0;

    


    var range_start_date = $('#datepicker1').val();
    var range_end_date = $('#datepicker2').val();
 
    var length_start = range_start_date.length;
    var length_end = range_end_date.length;
    
    var url_start_date = '';
    var url_end_date = '';

     if((length_start == 0 && length_end !=0) || (length_start != 0 && length_end ==0))
	 {
		 alert("<?php echo __('Either select both the dates or none.');?>");
		 return;
	 }
	 else if(length_start != 0 && length_end !=0)
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

		 if (start_timestamp > end_timestamp)
         {
            alert("<?php echo __('Invalid date range.'); ?>");
            $("#datepicker1").css('border', '1px solid #F00');
            $("#datepicker2").css('border', '1px solid #F00');
            return;
         }
	 }
     
    
	 $.ajax({
		  beforeSend: function (XMLHttpRequest){
			 $("#loading-image").fadeIn();
		  },
		  complete: function (XMLHttpRequest, textStatus) {
			$("#loading-image").fadeOut();
		  },
		  url: "<?php echo Router::url(array('controller'=>'pins','action'=>'get_file_name'));?>", 
		  type: "POST",
		  data: ({card_id : card_id,sub_cat_id:sub_cat_id,cat_id:cat_id,url_start_date:url_start_date,url_end_date:url_end_date}),
		  dataType: 'json',
		  success: function(json){
			
			$('#PinFileName').html('');
			$('#PinFileName').html('<option value="">--- All Files ---</option>');

			var keys = [];
			var datas = {}
			
			$.each(json, function(key, value){
			  keys.push(value)
			  datas[value] = key;
			})
			
			var aa = keys.sort()
			
			$.each(aa, function(index, value){
				if(value == file_name)
				$('#PinFileName').append($('<option Selected="Selected">').text(value).attr('value', datas[value]));
			    else
			    $('#PinFileName').append($('<option>').text(value).attr('value', datas[value]));
			    	
			})			    
		  }
		}); 
    
 
	    global_cat_id = cat_id;
		global_sub_cat_id = sub_cat_id;
		global_card_id = card_id;
		global_status = pc_status;
		global_file_name = file_name;
		global_file_name_id = file_name_id;
		
	
	    
	    new_url = '';
	    new_url = '/cat_id:'+cat_id;
		new_url = new_url+'/sub_cat_id:'+sub_cat_id;
		new_url = new_url+'/card_id:'+card_id;
		new_url = new_url+'/pc_status:'+pc_status;
		new_url = new_url+'/file_name:'+file_name;
        
        if(length_start != 0 && length_end !=0)
        {
        	new_url = new_url+'/url_start_date:'+url_start_date;
        	new_url = new_url+'/url_end_date:'+url_end_date;

        	global_start_date = $('#datepicker1').val();
     		global_end_date =   $('#datepicker2').val();
        }

		var updated_url = "<?php echo Router::url(array('controller'=>'Pins','action'=>'admin_json'));?>"+new_url;
     	$('#list').setGridParam({url:updated_url});
	    reloadGrid();
});	

$('.clear_filer_class').click(function(){
	     $('#PinCatId').val('');
	     var cat_id =  '';
		 var sub_cat_id = '';
 		 var card_id =  '';
 		 var pc_status =  '';
		 $.ajax({
		  beforeSend: function (XMLHttpRequest){
			 $("#loading-image").fadeIn();
		  },
		  complete: function (XMLHttpRequest, textStatus) {
			$("#loading-image").fadeOut();
		  },
		  url: "<?php echo Router::url(array('controller'=>'cards','action'=>'get_subcat'));?>", 
		  type: "POST",
		  data: ({id : cat_id}),
		  dataType: 'json',
		  success: function(json){
			$('#PinSubCatId').html('');
			$('#PinSubCatId').html('<option value="">--- All Sub Category---</option>');
			 
			var keys = [];
			var datas = {}
			
			$.each(json, function(key, value){
			  keys.push(value)
			  datas[value] = key;
			})
			
			var aa = keys.sort()
			
			$.each(aa, function(index, value){
				$('#PinSubCatId').append($('<option>').text(value).attr('value', datas[value]));
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
		  url: "<?php echo Router::url(array('controller'=>'cards','action'=>'get_cards_parent_cat'));?>", 
		  type: "POST",
		  data: ({id : cat_id}),
		  dataType: 'json',
		  success: function(json){
			$('#PinCardId').html('');
			$('#PinCardId').html('<option value="">--- All Card---</option>');
			  
			  
			var keys = [];
			var datas = {}
			
			$.each(json, function(key, value){
			  keys.push(value)
			  datas[value] = key;
			})
			
			var aa = keys.sort()
			
			$.each(aa, function(index, value){
				$('#PinCardId').append($('<option>').text(value).attr('value', datas[value]));
			})		
		  }
		});
		
		$('#PinPcStatus').html('');
		$('#PinPcStatus').html('<option value="0">All</option>');
		$('#PinPcStatus').append('<option value="1">Unused</option>');
		$('#PinPcStatus').append('<option value="2">Sold</option>');
		$('#PinPcStatus').append('<option value="3">Parked</option>');
		$('#PinPcStatus').append('<option value="4">Rejected</option>');
		$('#PinPcStatus').append('<option value="5">Returned</option>');

		$.ajax({
		  beforeSend: function (XMLHttpRequest){
			 $("#loading-image").fadeIn();
		  },
		  complete: function (XMLHttpRequest, textStatus) {
			$("#loading-image").fadeOut();
		  },
		  url: "<?php echo Router::url(array('controller'=>'pins','action'=>'get_file_name'));?>", 
		  type: "POST",
		  data: ({card_id : card_id,sub_cat_id:sub_cat_id,cat_id:cat_id}),
		  dataType: 'json',
		  success: function(json){
			
			$('#PinFileName').html('');
			$('#PinFileName').html('<option value="">--- All Files ---</option>');

			var keys = [];
			var datas = {}
			
			$.each(json, function(key, value){
			  keys.push(value)
			  datas[value] = key;
			})
			
			var aa = keys.sort()
			
			$.each(aa, function(index, value){
				$('#PinFileName').append($('<option>').text(value).attr('value', datas[value]));
			})			    
		  }
		});

    
		var new_url = '';
		new_url = '/cat_id:'+cat_id;
		new_url = new_url+'/sub_cat_id:'+sub_cat_id;
		new_url = new_url+'/card_id:'+card_id;
		new_url = new_url+'/pc_status:'+pc_status;

		var updated_url = "<?php echo Router::url(array('controller'=>'Pins','action'=>'admin_json'));?>"+new_url;
     	var new_val = $('#mediator :nth-child(1)').val(); // To select via index
		$('#list').setGridParam({url:updated_url});
	    $("#list")[0].clearToolbar();
		reloadGrid();
});

///////////////////////////////

$('#excel_sales').click(function(){
	
	var card_id = global_card_id;
	var cat_id = global_cat_id;
	var sub_cat_id =  global_sub_cat_id;
	var pc_status =  global_status;
	var file_name =  global_file_name;
	
    var range_start_date = global_start_date;
    var range_end_date = global_end_date;
    
    var length_start = range_start_date.length;
    var length_end = range_end_date.length;
    
    var url_start_date =0;
    var url_end_date =0;
    
     if((length_start == 0 && length_end !=0) || (length_start != 0 && length_end ==0))
	 {
		 alert("<?php echo __('Either select both the dates or none.');?>");
		 return;
	 }
	 else if(length_start != 0 && length_end !=0)
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

		 if (start_timestamp > end_timestamp)
         {
            alert("<?php echo __('Invalid date range.'); ?>");
            $("#datepicker1").css('border', '1px solid #F00');
            $("#datepicker2").css('border', '1px solid #F00');
            return;
         }
	    //alert(cat_id+" "+sub_cat_id);
    	 var url = "<?php echo $this->Html->url(array('controller'=>'Pins','action'=>'pins_detail_excel'));?>/"+card_id+"/"+cat_id+"/"+sub_cat_id+"/"+pc_status+"/"+file_name+ "/" + url_start_date + "/" + url_end_date;
	 }
    else
	{
		//alert(cat_id+" "+sub_cat_id);
	    var url = "<?php echo $this->Html->url(array('controller'=>'Pins','action'=>'pins_detail_excel'));?>/"+card_id+"/"+cat_id+"/"+sub_cat_id+"/"+pc_status+"/"+file_name;
	} 
	window.location.href = url;
});


$('.reset_from').click(function(){
	$("#datepicker1").val('');
});

$('.reset_to').click(function(){
	$("#datepicker2").val('');
});

$(function () {

var date = new Date();
var currentMonth = date.getMonth();
var currentDate = date.getDate();
var currentYear = date.getFullYear();

$("#datepicker1").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd.mm.yy",
            //yearRange: "-10:+0", // last 10  years
            maxDate: new Date(currentYear, currentMonth, currentDate),
        });

        $("#datepicker2").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd.mm.yy",
            //yearRange: "-10:+0", // last 10  years
            maxDate: new Date(currentYear, currentMonth, currentDate),
        });
});
</script>