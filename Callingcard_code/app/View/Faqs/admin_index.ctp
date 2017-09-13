<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <div class="sub_title"><i class="icon-gear home_icon"></i> <span class="sub_litle_m">Settings</span> <i class="icon-angle-right home_icon"></i> <span>Manage FAQ</span></div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title cat_w" style="width:30%;"><?php echo $title; ?></div>
            <button class="new_button clear_filer_class" style="float:right;" type="button" style="cursor:pointer;">
                <span class="icon-remove icon-white"></span>&nbsp;&nbsp;Clear Filter
            </button>
            <button class="new_button new_button_right mar_rightr" id="add_button" type="button" style="float:right;margin-right:10px;"><span class="icon-plus icon-white"></span>&nbsp;&nbsp;Add New</button>
            <button class="new_button back " type="button" style="cursor:pointer;float:right; margin-right:10px;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
            <!-- <button class="new_button " id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;&nbsp;Delete</button> -->
            
    </div>    
  
  <div class="clear10"></div>
  <div align="left" class="grid_table_box">
    <table  width="100%" id="list"></table> 
		<div id="gridpager"></div>
  </div>
  <div class="clear10"></div>
  </div>
</div>
<script>
$(document).ready(function (){
    jQuery("#list").jqGrid({
        url: "<?php echo Router::url(array('controller'=>'Faqs','action'=>'admin_json'));?>",
        datatype: "json",
        colNames:['Title','Modified Date','Action','Status'],
        colModel:[
					{name:'f_title',index:'f_title', width:240,search:true, sortable:true, align:"left"},
					{name:'f_modified_date',index:'f_modified_date', width:100,search:false, sortable:true, align:"center"},
					{name:'edit',index:'edit', width:30,search:false, sortable:false,align:"center"},
					{name:'f_status',index:'f_status', width:50,search:true, sortable:true, stype:'select',sortable:true,stype:'select',searchoptions: {value: {'':'All', 0: 'Disable',1: 'Enable'},defaultValue: ''}, align:"center"}
        ],
        rowNum:50,
   		rowList:[10,20,50],
        pager: '#gridpager',
        sortname: 'f_id',
        viewrecords: true,
        rownumbers: true,
        sortorder: "asc",
        caption:false,
        multiselect: true,
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
					url: "<?php echo Router::url(array('controller'=>'Faqs','action'=>'admin_delete'));?>", 
					type: "POST", 
					data: ({ids : ids}), 
					success: function(data){
						if(data == '1'){
							reloadGrid();
                            alert('Selected record(s) deleted successfully.');
						}else{
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
	
	$("#add_button").click(function(){
		window.location = "<?php echo Router::url(array('controller'=>'FaqsLanguages','action'=>'admin_addcontent'));?>";
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
function changeStatus(id,status)
{	
	var msg = "";
	st = status.value;
	if(st == 1){
		msg = "Are you sure? You want to active the status.";
	} else {
		msg = "Are you sure? You want to inactive the status.";
	}
	if(confirm(msg)){
		$.ajax({
			url: "<?php echo Router::url(array('controller'=>'Faqs','action'=>'admin_changestatus'));?>", 
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
}
$(document).ready(function(){
   $('#setting').addClass('sb_active_opt');
   $('#setting').removeClass('has_submenu');
   $('#faq_active').addClass('sb_active_subopt_active');
}) ;

$('.clear_filer_class').click(function(){
 		var new_url = "<?php echo $this->Html->url(array('controller'=>'Faqs','action'=>'admin_json','admin'=>'true'));?>";
		$('#list').setGridParam({url:new_url});
	    $("#list")[0].clearToolbar();
		reloadGrid();
});

</script>