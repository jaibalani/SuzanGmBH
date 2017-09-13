<div align="left" style="padding-right:10px;">
  <h1><?php echo $title_for_layout; ?></h1>
   <button class="btn btn-warning back" type="button" style="cursor:pointer;"><span class="icon-backward icon-white"></span>&nbsp;Back</button>
<!--   <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
<!--  <button class="btn btn-primary" id="add_button" type="button" ><span class="icon-plus icon-white"></span>&nbsp;Add New</button>
-->
  <div class="clear10"></div>
  <div align="left" class="grid_table_box">
    <table id="list"></table> 
		<div id="pager"></div>
  </div>
</div>
<script type="text/javascript">

$(function(){ 
  $("#list").jqGrid({
    url:'<?php echo $this->Html->url(array("controller" => "Transactions","action" => "transaction_details_generategrid",'admin'=>true,$transaction_parent_id));?>',
    datatype: "json",
    mtype: 'GET',
	   colNames:['Id','Payment Mode','Main Transaction Id','Bank Name','Check Number','Previous Balance','Funded Amount','Date'],
     colModel :[ 
			{name:'id',index:'id',width:50,align:'center',stype:'',sorttype:'int', stype:'text',sortable:true}, 
			{name:'payment_mode',index:'fname', width:100,align:'left',	stype:'',	sortable:true},
			{name:'parent_id', index:'parent_id', width:100,	align:'left',	stype:'',	sortable:false},
			{name:'bank_name', index:'bank_name', width:100,	align:'left',	stype:'text',	sortable:true},
			{name:'check_number', index:'check_number', width:100,	align:'center',	stype:'text',	sortable:true},
			{name:'previous_balance', index:'previous_balance', width:100,	align:'left',	stype:'text',	sortable:true},
			{name:'total_amount', index:'total_amount', width:100,	align:'center',	stype:'text',	sortable:true},
			{name:'created', index:'created', width:100,	align:'center',	stype:'',	sortable:true},

		],
    pager: jQuery('#pager'),
    rowNum:10,
    rowList:[10,20,30],
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
    caption: '<?=$title_for_layout?>'
  }); 
  
  jQuery("#list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false,search:false,refresh:true});	
  jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true});

  	//delete records
	$("#delete_button").click(function(){
		var ids = getSelectedRows();
		if(ids!=''){
			if(confirm("Are you sure? You want to remove this record.")){
				$.ajax({
					url: "<?php echo Router::url(array('controller'=>'Users','action'=>'admin_delete_mediator_fund'));?>", 
					type: "POST", 
					data: ({ids : ids}), 
					success: function(data){
						if(data == '1')
						{
							reloadGrid();
              alert('Selected record(s) deleted successfully.');
						}
						else
						{
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

$('.back').click(function(){
	var role_id = "<?php echo $login_user_roleid ;?>";
	if(role_id == 1)
	{
		var url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'manage_fund','admin'=>'true'));?>";
	}
	else
	{
		var url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'manage_fund_retailer','admin'=>'true'));?>";
	}
	window.location.href = url;
});

$('#add_button').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'add_fund_data','admin'=>'true',$transaction_parent_id));?>";
	window.location.href = url;
});
</script>
