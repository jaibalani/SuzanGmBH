<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <div class="sub_title"><i class="icon-user home_icon"></i> <span class="sub_litle_m">Mediator</span> <i class="icon-angle-right home_icon"></i> <span>Mediator Personal Data</span></div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title mediator_w" style="width:30%;"><?php echo $title_for_layout; ?></div>
        <button class="new_button clear_filer_class" style="float:right;" type="button" style="cursor:pointer;">
            <span class="icon-remove icon-white"></span>&nbsp;&nbsp;Clear Filter
        </button>
        <button class="new_button mar_right" id="add_button" type="button"  style="float:right; margin-left:10px"onclick="add_user();"><span class="icon-plus icon-white"></span>&nbsp;&nbsp;Add New</button>
        <button class="new_button" id="send_mail" type="button"  style="float:right; margin-left:10px" onclick="send_mail();"><span class="icon-envelope icon-white"></span>&nbsp;&nbsp;Send Mail</button>
        <button class="new_button back" type="button" style="cursor:pointer;float:right; margin-left:10px;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
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
  
  	var editOptions = {
	aftersavefunc: function (rowid, response, options) {
	console.log("Строка " + rowid + " сохранена.");
	//$("#grid").trigger("reloadGrid");
	}
	};
    
    var lastSel;
    $("#list").jqGrid({
    url:'<?php echo $this->Html->url(array("controller" => "Users","action" => "generategrid",'admin'=>true));?>',
    datatype: "json",
    mtype: 'GET',
	   colNames:['Id','First Name','Last Name','Email','Account Number','Status','Action'],
     colModel :[ 
		{
            name:'id',
            index:'id',
            width:50,
            align:'center',
            stype:'',
            sorttype:'int', 
            stype:'text',
            sortable:true
        }, 
		{
            name:'fname',
            index:'fname', 
            width:100,
            align:'left',	
            stype:'text',	
            sortable:true, 
            editable: true
        },
		{
            name:'lname', 
            index:'lname', 
            width:100,	
            align:'left',	
            stype:'text',	
            sortable:true, 
            editable: true
        },
		{
            name:'email', 
            index:'email', 
            width:140,	
            align:'left',	
            stype:'text',	
            sortable:true, 
            editable: true
        },
        {
            name:'username', 
            index:'username', 
            width:140,  
            align:'left',   
            stype:'text',   
            sortable:true, 
            editable: false
        },
        /*<!--	{name:'image', index:'image', width:65,	align:'center',	stype:'',	sortable:false},--> */
		{
            name:'status', 
            index:'status', 
            width:90,	
            align:'center',	
            stype:'select',
            searchoptions: {
                value: {0:'All', 1: 'Enable',2: 'Disable'}
            },
            sortable:true
        },
		{
            name:'action', 
            index:'action', 
            width:180,	
            align:'center',	
            stype:'',	
            sortable:false
        },
	],
            /*
            onSelectRow: function(id){
                //alert(id);
                if(id && id!==lastSel){ 
                   jQuery('#list').restoreRow(lastSel); 
                   lastSel=id; 
                }
                jQuery('#list').editRow(id, true,null,function(xhr){
                    var data = eval(xhr.responseText);
                    if(data == '0'){
                        alert("Selected record not saved please try again");
                    }else{
                        alert("Selected record upldated successfully.");
                        return true;
                    }
                }); 
              },
              */        
    editurl: "<?php echo $this->Html->url(array("controller" => "Users","action" => "inline_mediator",'admin'=>true));?>",          
    pager: jQuery('#pager'),
    rowNum:50,
    rowList:[10,20,30,50],
    sortname: 'lname,fname',
    sortorder: 'asc',
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
	$("#delete_button").click(function()
	{
		var ids = getSelectedRows();
		if(ids!='')
		{
			if(confirm("Are you sure? You want to remove this record."))
			{
				$.ajax({
					url: "<?php echo Router::url(array('controller'=>'Users','action'=>'admin_deleteuser'));?>", 
					type: "POST", 
					data: ({ids : ids}), 
					success: function(data){
						if(data == '1')
						{
							reloadGrid();
                            alert('Selected record(s) have been deleted successfully.');
						}
						else
						{
             				 // showErrorNotification();
            			}
					}
				});
			}
		}
		else
		{
			alert('Please select atleast one user to perform this specific action.');
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

function deleteuser(id)
{
	var ans = confirm("<?php echo __('Are you sure you want to delete this mediator ?');?>");
	if(ans)
	{
		var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'deleteuser','admin'=>'true'));?>/"+id;
  	window.location.href = url;
	}
}

function changeStatus_old(user_id,status)
{
	var ans = confirm("<?php echo __('Are you sure you want to change status of this mediator ?');?>");
	if(ans)
	{
		var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'changeStatus','admin'=>'true'));?>/"+user_id+"/"+status;
  	window.location.href = url;
	}
}
$(document).ready(function(){
   $('#Mediator').addClass('sb_active_opt');
   $('#Mediator').removeClass('has_submenu');
   $('#mediator_active').addClass('sb_active_subopt_active');
}) ;

function changeStatus(id,status)
{	
	st = status.value;
	var msg = "";
	if(st == 1)
    {
		msg = "Are you sure? You want to active the status. Related retailer will be also marked as active.";
	} 
    else 
    {
		msg = "Are you sure? You want to inactive the status.Related retailer will be also marked as inactive. ";
	}
	if(confirm(msg)){
		$.ajax({
			url: "<?php echo Router::url(array('controller'=>'Users','action'=>'admin_changestatus_new'));?>", 
			type: "POST",
			data: ({id : id, st : st}),
			success: function(data){
				// alert(data);
				 if(data == '1'){
					//alert('Status has been changed successfully');
					reloadGrid();
				 }
			}
		});
			
	}else{
		var st = st == 1 ? 2 : 1 ;
		$(status).val(st);
	}
}

var rowid;
    function inplaceEdit(id) {
        jQuery('#list').editRow(id);
        changeActionState('edit', id);
    }
    function inplaceCancel(id) {
        jQuery('#list').restoreRow(id);
        changeActionState('cancel', id);
    }
    function inplaceSave(id) {
        rowid = id;
        jQuery('#list').saveRow(id, checkSave);

        //jQuery('#list').saveRow(id);
        //changeActionState('save', id);
    }

    function checkSave(result) {

        if (result.responseText.toLowerCase() == '1') {
            changeActionState('save', rowid);
            reloadGrid();
        }else if(result.responseText){
						 $.each($.parseJSON(result.responseText), function(index, element) {
								alert(element);
						});
						inplaceCancel(rowid);
				}else{
            alert("Record not update. Please try again!");
            inplaceCancel(rowid);
        }
    }

  function changeActionState(action, id) 
  {
      		if (action == 'edit') {
            jQuery('#action_edit_' + id).css('display', 'none');
            jQuery('#action_save_' + id).css('display', 'inline-block');
            jQuery('#action_cancel_' + id).css('display', 'inline-block');

            $("#action_status_" + id).attr("disabled", false);
            jQuery('#action_view_' + id).css('display', 'none');
            jQuery('#action_viewdetail_' + id).css('display', 'none');
			jQuery('#action_switch_' + id).css('display', 'none');
        }
        else {
            jQuery('#action_edit_' + id).css('display', 'inline-block');
            jQuery('#action_save_' + id).css('display', 'none');
            jQuery('#action_cancel_' + id).css('display', 'none');

            $("#action_status_" + id).attr("disabled", true);
            jQuery('#action_view_' + id).css('display', 'inline-block');
            jQuery('#action_viewdetail_' + id).css('display', 'inline-block');
			jQuery('#action_switch_' + id).css('display', 'inline-block');
        }
    }

$('.clear_filer_class').click(function(){
 		var new_url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'generategrid','admin'=>'true'));?>";
 		$('#list').setGridParam({url:new_url});
	    $("#list")[0].clearToolbar();
		reloadGrid();
});


function switch_account(user_id)
{
	var ans = confirm ("Are you sure you want to switch to this account.");
	if(ans)
	{
		var new_url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'one_to_another_login','admin'=>'true'));?>/"+user_id;
  	    window.location.href = new_url;
	}
}
</script>
