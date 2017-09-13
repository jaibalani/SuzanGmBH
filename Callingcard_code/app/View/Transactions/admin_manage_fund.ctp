<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
    
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <div class="sub_title"><i class="icon-user home_icon"></i> <span class="sub_litle_m">Mediator</span> <i class="icon-angle-right home_icon"></i> <span>Manage Mediator Fund</span></div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title mangefund" style="width:30%;"><?php echo $title_for_layout; ?></div>
         <button class="new_button clear_filer_class" style="float:right;" type="button" style="cursor:pointer;">
                <span class="icon-remove icon-white"></span>&nbsp;&nbsp;Clear Filter
         </button>
        <button class="new_button back" type="button" style="cursor:pointer;float:right; margin-right:10px;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
        <button class="new_button new_button_right mar_right" id="add_button" type="button" style="float:right;margin-right:10px;"><span class="icon-plus icon-white"></span>&nbsp;&nbsp;Add New</button>

        <!-- <button class="new_button"  id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;&nbsp;Delete</button> -->
    </div>
        <div class="selectbox_div">
<!--      <div class="col-md-7"></div> -->
            <div class="col-md-2 selectbox_title" style="padding-left:0px;"><?php echo __('Select Mediator:'); ?></div>
            
            <div class="col-md-5" align="right">
                        <?php echo $this->Form->input('mediator', 
                               array('type' => 'button',
                                    'class'=>'select_boxfrom',
                                    'type'=>'select',
                                    'options'=>$mediator_list,
                                    'value'=>$mediator_id,
                                    'label'=>false,
                                    'style'=>'cursor:pointer;'
                                   )
                               );
                        ?>
                </div> 
        </div>
  
  <div class="clear10"></div>
  <div align="left" class="grid_table_box">
    <table id="list"></table> 
		<div id="pager"></div>
  </div>
  <div class="clear10"></div>
  </div>
</div>
<script type="text/javascript">

$(function(){ 
  $("#list").jqGrid({
    url:'<?php echo $this->Html->url(array("controller" => "Transactions","action" => "generategrid",'admin'=>true,$mediator_id));?>',
    datatype: "json",
    mtype: 'GET',
	   colNames:['Id','First Name','Last Name','Total Balance','Available Balance','Minimum Balance','Updated','Action'],
     colModel :[ 
			{name:'id',index:'id',width:40,align:'center',stype:'',sorttype:'int', stype:'text',sortable:true}, 
			{name:'fname',index:'fname', width:75,align:'left',	stype:'text',	sortable:true,jsonmap: "User.fname"},
			{name:'lname', index:'lname', width:75,	align:'left',	stype:'text',	sortable:true,jsonmap: "User.lname"},
			<!--{name:'email', index:'email', width:130,	align:'left',	stype:'text',	sortable:true},-->
<!--			{name:'image', index:'image', width:50,	align:'center',	stype:'',	sortable:false},-->
			{name:'Transaction.total_amount', index:'Transaction.total_amount', width:80,	align:'left',jsonmap: "Transaction.total_amount",sortable:true},
			{name:'balance', index:'balance', width:70,	align:'center',	jsonmap: "Transaction.balance",	sortable:true},
			{name:'minimum_balance',index:'minimum_balance',width:90, align:'left',stype:'text',jsonmap: "User.minimum_balance",	sortable:true},
			{name:'updated', index:'updated', width:60,	align:'center',	stype:'',	sortable:false,jsonmap: "User.updated",	sortable:true},
			{name:'manage', index:'manage', width:70,	align:'center',	stype:'',	sortable:false},


		],
    pager: jQuery('#pager'),
    rowNum:50,
    rowList:[10,20,30,50],
    sortname: 'User.lname,Transaction.total_amount',
    sortorder: 'asc',
    viewrecords: true,
    gridview: true,
	loadonce: false,
	hidegrid:false,
	height:"100%",
	autowidth:true,
	multiselect: false,
	ignoreCase:true,
    caption: false,
    beforeRequest: function () {
      	$("#loading-image").fadeIn();
        }, 
    gridComplete: function() {
      	$('#list tr:nth-child(odd)').addClass("evenTableRow");
      	$('#list tr:nth-child(even)').addClass("oddTableRow");
		var rowIDs = jQuery("#list").jqGrid('getDataIDs');
                                    var rowID;
									var row;
									var balance;
									var minimum_balance;
									for (var i = 0; i < rowIDs.length ; i++) 
									{
										 rowID = rowIDs[i];
										 row = jQuery('#list').jqGrid ('getRowData', rowID);
										 balance = parseFloat(row.balance);
										 minimum_balance = parseFloat(row.minimum_balance);
										 if(minimum_balance >= balance)
										 {
    										 $('#'+rowID).removeClass('oddTableRow');
											 $('#'+rowID).removeClass('evenTableRow');
											 $('#'+rowID).css('background','#00BFFF');
											 $('#'+rowID).css('color','#FFF');
											 $('#'+rowID+' a').css('color','#FFF');
										 }
									}
	    $("#loading-image").fadeOut();
	}
  }); 
  
  jQuery("#list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false,search:false,refresh:true});	
  jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

  	//delete records
	$("#delete_button").click(function(){
		var ids = getSelectedRows();
		alert(ids);
		if(ids!=''){
			if(confirm("Are you sure? You want to delete selected records.")){
				$.ajax({
					url: "<?php echo Router::url(array('controller'=>'Transactions','action'=>'admin_delete_fund'));?>", 
					type: "POST", 
					data: ({ids : ids}), 
					success: function(data){
						if(data == '1' || data == '2' || data == '3')
						{
							reloadGrid();
                            if(data == '1')
							alert('Selected record(s) deleted successfully.');
							else if(data == '2')
							alert('Some Selected record(s) deleted successfully.');
							else
							alert('Record(s) could not be deleted');
						}
						else
						{
							alert('Record(s) could not be deleted');
              // showErrorNotification();
            }
					}
				});
			}
		}else{
			alert('Please select rows to perform this specific action.');
			//showErrorNotification('Please select rows to perform this specific action.');
		}	
	});
	
});

function reloadGrid(){
	jQuery("#list").trigger("reloadGrid");
}

function getSelectedRows(){
	var s = jQuery("#list").jqGrid('getGridParam','selarrrow');
	return s;
}

function change_password()
{
	var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'changepassword','admin'=>'true'));?>";
  window.location.href = url;
}

$('#mediator').change(function(){
   
   var mediator_id = $(this).val();
	 if(mediator_id)
	 {
 		var new_url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'generategrid','admin'=>'true'));?>/"+mediator_id;
     	$('#list').setGridParam({url:new_url});
		reloadGrid();
		//window.location.href = url;
	 }
});

$('#add_button').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'add_fund_parent','admin'=>'true'));?>";
	window.location.href = url;
});
$(document).ready(function(){
   $('#Mediator').addClass('sb_active_opt');
   $('#Mediator').removeClass('has_submenu');
   $('#fund_m').addClass('sb_active_subopt_active');
   
   var new_url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'generategrid','admin'=>'true'));?>";
   var new_val = $('#mediator :nth-child(1)').val(); // To select via index
    $('#mediator').val(new_val);
    $('#list').setGridParam({url:new_url});
    $("#list")[0].clearToolbar();
    reloadGrid();
}) ;

$('.back').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'dashboard','admin'=>'true'));?>";
    //window.location.href = url;
	history.go(-1);
});

$('.clear_filer_class').click(function(){
 	var new_url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'generategrid','admin'=>'true'));?>";
     	var new_val = $('#mediator :nth-child(1)').val(); // To select via index
		$('#mediator').val(new_val);
		$('#list').setGridParam({url:new_url});
	    $("#list")[0].clearToolbar();
		reloadGrid();
});
</script>
