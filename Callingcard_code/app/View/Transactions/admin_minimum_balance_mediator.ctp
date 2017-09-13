<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <div class="sub_title"><i class="icon-briefcase home_icon"></i> <span class="sub_litle_m">Minimum Balance</span> </div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title mediator_w"><?php echo $title_for_layout; ?></div>
        <button class="new_button clear_filer_class" style="float:right;" type="button" style="cursor:pointer;">
	        <span class="icon-remove icon-white"></span>&nbsp;&nbsp;Clear Filter
        </button>
        <button class="new_button back" type="button" style="cursor:pointer; float:right;margin-right:10px;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
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
    url:'<?php echo $this->Html->url(array("controller" => "Transactions","action" => "generategrid_minimum_balance_mediator",'admin'=>true));?>',
    datatype: "json",
    mtype: 'GET',
	   colNames:['Id','First Name','Last Name','Email','Image','Minimum Balance','Available Balance'],
       colModel :[ 
			{name:'id',index:'id',width:50,align:'center',stype:'',sorttype:'int', stype:'text',sortable:true}, 
			{name:'fname',index:'fname', width:120,align:'left',	stype:'text',	sortable:true},
			{name:'lname', index:'lname', width:120,	align:'left',	stype:'text',	sortable:true},
			{name:'email', index:'email', width:180,	align:'left',	stype:'text',	sortable:true},
			{name:'image', index:'image', width:65,	align:'center',	stype:'',	sortable:false},
			{name:'minimum_balance', index:'minimum_balance', width:100,	align:'center',	stype:'text',	sortable:true},
			{name:'balance', index:'balance', width:90,	align:'center',	stype:'text',	sortable:true},
		],
    pager: jQuery('#pager'),
    rowNum:50,
    rowList:[10,20,30,50],
    sortname: 'Transaction.id',
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
      	$("#loading-image").fadeOut();
	}
  }); 
  
  jQuery("#list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false,search:false,refresh:true});	
  jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
  
		//delete records
	$("#delete_button").click(function(){
		var ids = getSelectedRows();
		if(ids!=''){
			if(confirm("Are you sure? You want to remove this record.")){
				$.ajax({
					url: "<?php echo Router::url(array('controller'=>'Users','action'=>'admin_deleteuser'));?>", 
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

$('.back').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'dashboard','admin'=>'true'));?>";
    //window.location.href = url;
	history.go(-1);
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

function add_user()
{
	var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'add','admin'=>'true'));?>";
  window.location.href = url;
}

function deleteuser(id)
{
	var ans = confirm("<?php echo __('Are you sure you want to delete this retailer ?');?>");
	if(ans)
	{
		var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'deleteuser','admin'=>'true'));?>/"+id;
  	window.location.href = url;
	}
}

function changeStatus(user_id,status)
{
	var ans = confirm("<?php echo __('Are you sure you want to change status of this retailer ?');?>");
	if(ans)
	{
		var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'changeStatus','admin'=>'true'));?>/"+user_id+"/"+status;
  	window.location.href = url;
	}
}

function send_mail()
{
	var ids = getSelectedRows();
	if(ids!='')
	{
		var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'send_mail','admin'=>'true'));?>/"+ids;
  	window.location.href = url;
	}
	else
	{
		alert('Please select rows to perform this specific action.');
	}
}
$(document).ready(function(){
   $('#minimus_bal').addClass('sb_active_single_opt');
   $('#minimus_bal').removeClass('has_submenu');
}) 

$('.clear_filer_class').click(function(){
 		var new_url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'generategrid_minimum_balance_mediator','admin'=>'true'));?>";
		$('#list').setGridParam({url:new_url});
	    $("#list")[0].clearToolbar();
		reloadGrid();
});

</script>
