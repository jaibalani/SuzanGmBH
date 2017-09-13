<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <div class="sub_title"><i class="icon-user home_icon"></i> <span class="sub_litle_m">Mediator</span> <i class="icon-angle-right home_icon"></i> <span>View Retailers</span></div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title rat_w" style="width:30%;"><?php echo $title_for_layout; ?></div>
        <button class="new_button clear_filer_class" style="float:right;" type="button" style="cursor:pointer;">
           <span class="icon-remove icon-white"></span>&nbsp;&nbsp;Clear Filter
        </button>
        <button class="new_button back" type="button" style="cursor:pointer;float:right;margin-right:10px;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
        
    </div>    
  
  <div class="clear10"></div>
  
  <div class="row">
       <div class="col-md-2 selectbox_title">Select Mediator</div>
       <div class="col-md-5 ">	 
			<?php 
			echo $this->Form->input('mediator_id',array('class'=>'form-control amount_validation',
														'required' =>true,
														'type'=>'select',
														'options'=>$mediator_list,
														'value'=>$mediator_id,
														'empty'=>'All',
														'label'=>false)); ?>
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
    var lastSel;
  $("#list").jqGrid({
    url:'<?php echo $this->Html->url(array("controller" => "Users","action" => "generategrid_retailer_list",'admin'=>true,$mediator_id));?>',
    datatype: "json",
    mtype: 'GET',
	   colNames:['Id','First Name','Last Name','Mediator','Email','Account Number','Image','Status','Created','Action'],
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
                width:90,
                align:'left',	
                stype:'text',	
                sortable:true, 
                editable : true
            },
			{
                name:'lname', 
                index:'lname', 
                width:90,	
                align:'left',	
                stype:'text',	
                sortable:true, 
                editable : true
            },
			{
                name:'added_by', 
                index:'added_by', 
                width:100,	
                align:'left',	
                stype:'select',
                searchoptions: {
                    value: {<?php echo @$mediator_search_list?>}
                },	
                sortable:false
            },
			{
                name:'email', 
                index:'email', 
                width:150,	
                align:'left',	
                stype:'text',	
                sortable:true, 
                editable : true
            },
            {
                name:'username', 
                index:'username', 
                width:100,  
                align:'left',   
                stype:'text',   
                sortable:true, 
                editable : false
            },
			{
                name:'image', 
                index:'image', 
                width:65,	
                align:'center',	
                stype:'',	
                sortable:false
            },
			{
                name:'status', 
                index:'status', 
                width:80,	
                align:'left',
                stype:'select',
                searchoptions: {
                    value: {0:'All', 1: 'Enable',2: 'Disable'}
                },
                sortable:true
            },
			{
                name:'created', 
                index:'created', 
                width:70,	
                align:'center',	
                stype:'',	
                sortable:true
            },
            {
                name:'action', 
                index:'action', 
                width:100,	
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
        editurl: "<?php echo $this->Html->url(array("controller" => "users","action" => "inline_mediator",'admin'=>true));?>",          
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
	multiselect: false,
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
});

$('.back').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'dashboard','admin'=>'true'));?>";
    //window.location.href = url;
	history.go(-1);

});
$(document).ready(function(){
   $('#Mediator').addClass('sb_active_opt');
    var new_url ="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'generategrid_retailer_list','admin'=>'true'));?>/";
    var new_val = $('#mediator_id :nth-child(0)').val(); // To select via index
    $('#mediator_id').val(new_val);
    $('#list').setGridParam({url:new_url});
    $("#list")[0].clearToolbar();
    reloadGrid();
}) ;

function changeStatus(id,status)
{	
	st = status.value;
	var msg = "";
	if(st == 1){
		msg = "Are you sure? You want to active the status.";
	} else {
		msg = "Are you sure? You want to inactive the status.";
	}
	if(confirm(msg)){
		$.ajax({
			url: "<?php echo Router::url(array('controller'=>'Users','action'=>'admin_changestatus_new'));?>", 
			type: "POST",
			data: ({id : id, st : st}),
			success: function(data){
				 if(data == '1'){
					//alert('Status has been changed successfully');
					reloadGrid();
				 }
			}
		});
			
	}
	else {
		var st = st == 1 ? 0 : 1 ;
		$(status).val(st);
	}
}

function reloadGrid(){
	jQuery("#list").trigger("reloadGrid");
}

$('#mediator_id').change(function(){
    var mediator =$(this).val();
	//var url = "<?php //echo $this->Html->url(array('controller'=>'Users','action'=>'view_retailer','admin'=>'true'));?>/"+mediator;
    var new_url ="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'generategrid_retailer_list','admin'=>'true'));?>/"+mediator;
   	$('#list').setGridParam({url:new_url});
	reloadGrid();
	//window.location.href = url;
});

$(document).ready(function(){
	   $('#Mediator').addClass('sb_active_opt');
	   $('#Mediator').removeClass('has_submenu');
	   $('#retailer_active').addClass('sb_active_subopt_active');
	}) ;


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
        }else{
						if(result.responseText){
								 $.each($.parseJSON(result.responseText), function(index, element) {
										alert(element);
								});
						}else{
            	alert("Record could not be updated. Please try again!");
						}
            inplaceCancel(rowid)
        }
    }

    function changeActionState(action, id) {
        if (action == 'edit') {
            jQuery('#action_edit_' + id).css('display', 'none');
              jQuery('#action_view_' + id).css('display', 'none');
            jQuery('#action_switch_' + id).css('display', 'none');
			jQuery('#action_save_' + id).css('display', 'inline-block');
            jQuery('#action_cancel_' + id).css('display', 'inline-block');

            $("#action_status_" + id).attr("disabled", false);
        }
        else {
            jQuery('#action_edit_' + id).css('display', 'inline-block');
            jQuery('#action_view_' + id).css('display', 'inline-block');
			jQuery('#action_switch_' + id).css('display', 'inline-block');
            jQuery('#action_save_' + id).css('display', 'none');
            jQuery('#action_cancel_' + id).css('display', 'none');
            $("#action_status_" + id).attr("disabled", true);
        }
    }
	
$('.clear_filer_class').click(function(){
    var new_url ="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'generategrid_retailer_list','admin'=>'true'));?>/";
	var new_val = $('#mediator_id :nth-child(0)').val(); // To select via index
	$('#mediator_id').val(new_val);
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
