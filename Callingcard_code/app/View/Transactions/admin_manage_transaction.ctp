<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->

  <?php
  
  if($this->Session->read('Auth.User.role_id') == 1)
  {
  	$user_type = "Mediator";
  	$fund_allocate_for = "Manage Mediator Fund";
  }
  else
  {
  	$user_type = "Retailer";
    $fund_allocate_for = "Manage Fund";
  }

  ?>
  <div class="sub_title"><i class="icon-user home_icon"></i> <span class="sub_litle_m"><?php echo $user_type; ?></span> <i class="icon-angle-right home_icon "></i> <span class="sub_litle_m"><?php echo $fund_allocate_for; ?></span> <i class="icon-angle-right home_icon"></i> <span>Fund Allocation</span></div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title mediatorTa"><?php echo $title_for_layout; ?></div>
        
        <button class="new_button new_button_right mar_right back" type="button" style="cursor:pointer;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
         <!-- <button class="new_button" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;&nbsp;Delete</button> -->
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
    url:'<?php echo $this->Html->url(array("controller" => "Transactions","action" => "transaction_details_generategrid",'admin'=>true,$transaction_parent_id));?>',
    datatype: "json",
    mtype: 'GET',
	   colNames:['Id','Payment<br/>Mode','Payment<br/>Id','Bank<br/>Name','Cheque<br/>Number','Previous<br/>Balance','Funded<br/>Amount','Payment<br/>Date','Remarks','Action',''],
     colModel :[ 
			{name:'id',index:'id',width:50,align:'center',stype:'text',sorttype:'int',sortable:true}, 
			{name:'payment_mode',index:'payment_mode', width:60,align:'left',	stype:'',	sortable:true},
			{name:'parent_id', index:'parent_id', width:60,	align:'left',	stype:'',	sortable:false},
			{name:'bank_name', index:'bank_name', width:80,	align:'left',	stype:'text',	sortable:true},
			{name:'check_number', index:'check_number', width:70,	align:'center',	stype:'text',	sortable:true},
			{name:'previous_balance', index:'previous_balance', width:80,	align:'left',	stype:'text',	sortable:true},
			{name:'total_amount', index:'total_amount', width:80,	align:'center',	stype:'text',	sortable:true},
			{name:'created', index:'created', width:90,	align:'center',	stype:'',	sortable:true},
			{name:'remarks', index:'remarks', width:170,	align:'left',	stype:'',	sortable:true},
			{name:'action', index:'action', width:40,	align:'center',	stype:'',	sortable:false},
			{name:'edited_amount', index:'edited_amount', width:1,	align:'center',hidden:true},

		],
    pager: jQuery('#pager'),
    rowNum:50,
    rowList:[10,20,30,50],
    sortname: 'id',
    sortorder: 'desc',
    viewrecords: true,
    gridview: true,
	loadonce: false,
	hidegrid:false,
	height:"100%",
	autowidth:true,
	multiselect: true,
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
									var edited_fund;
									for (var i = 0; i < rowIDs.length ; i++) 
									{
										 rowID = rowIDs[i];
										 row = jQuery('#list').jqGrid ('getRowData', rowID);
										 edited_amount = parseFloat(row.edited_amount);
										 if(edited_amount == 1)
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
		if(ids!=''){
			if(confirm("Are you sure? You want to delete selected records.")){
				$.ajax({
					url: "<?php echo Router::url(array('controller'=>'Transactions','action'=>'admin_delete_child_fund'));?>", 
					type: "POST", 
					data: ({ids : ids}), 
					success: function(data){
						location.reload();
					}
				});
			}
		}
		else
		{
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

$('.back').click(function(){

	var role_id = "<?php echo $login_user_roleid ;?>";
	var redirect_link = "<?php echo $redirect_link ;?>";
	
	if(role_id == 1) 
	{
		var url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'manage_fund','admin'=>'true'));?>";
	}
	else
	{
		var url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'manage_fund_retailer','admin'=>'true'));?>";
	}
	if(redirect_link == 1)
	{
		window.location.href = url;
	}
	else
	{
		history.go(-1);
	}
});

$('#add_button').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'add_fund_data','admin'=>'true',$transaction_parent_id));?>";
	window.location.href = url;
});
$(document).ready(function(){

   <?php
     if($this->Session->read('Auth.User.role_id') == 1)
     {
   ?>
   $('#Mediator').addClass('sb_active_opt');
   $('#Mediator').removeClass('has_submenu');
   $('#fund_m').addClass('sb_active_subopt_active');

   <?php } else { ?>
   $('#retailer').removeClass('has_submenu');
   $('#retailer').addClass('sb_active_opt');
   $('#manage_fund').addClass('sb_active_subopt_active');
   <?php } ?>	
}) ;

function edit_fun(fund_id){

	var url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'admin_edit_fund','admin'=>'true'));?>/"+fund_id;
	window.location.href = url;
}
</script>
