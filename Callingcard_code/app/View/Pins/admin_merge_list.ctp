
<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <div class="sub_title"><i class="icon-list-alt home_icon"></i> <span class="sub_litle_m">Product Master</span> <i class="icon-angle-right home_icon"></i> <span>Card Management</span> <i class="icon-angle-right home_icon"></i> <span>Merge Pins</span></div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title merge_w"><?php echo $title; ?></div>
        <button class="new_button mar_right" id="add_button" type="button"><span class="icon-plus icon-white"></span>&nbsp;&nbsp;Merge</button>
        <?php if(isset($back_cat) && !empty($back_cat)){?>
				<button class=" new_button back" type="button"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
        <?php }?>
        
    </div>    
  
  <div class="clear10"></div>
  <div align="left" class="grid_table_box">
    <table id="list"></table> 
		<div id="gridpager"></div>
  </div>
  <div class="clear10"></div>
  </div>
</div>
<script>
$(document).ready(function (){
    jQuery("#list").jqGrid({
        url: "<?php echo Router::url(array('controller'=>'Pins','action'=>'admin_merge_json',$card_id));?>",
        datatype: "json",
        colNames:['Serial Number','Pin Number',/*'Card Name',*/'Merged from Card','Status'],
        colModel:[
					{name:'p_serial',index:'p_serial', width:200,search:true, sortable:true, align:"left"},
					{name:'p_pin',index:'p_pin', width:160,search:true, sortable:true,align:"center"},
					{name:'c_title',index:'c_title', width:200,search:true, sortable:true, align:"left"},
					/*{name:'c_merged',index:'c_merged', width:200,search:false, sortable:false,align:"center"},*/
					{name:'p_status',index:'p_status', width:50,search:false, sortable:true, align:"center"}
        ],
        rowNum:50,
   			rowList:[10,20,30,50],
        pager: '#gridpager',
        sortname: 'pc_p_id',
        viewrecords: true,
        rownumbers: false,
        sortorder: "asc",
        caption:false,
			  multiselect: false,
				height:"100%",
				autowidth:true,
				shrinkToFit:true
    });
    jQuery("#list").jqGrid('navGrid','#gridpager',{edit:false,search:false,add:false,del:false});
		jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true});

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
		window.location = "<?php echo Router::url(array('controller'=>'Pins','action'=>'admin_merge',$id,$card_id));?>";
	});

	$('.back').click(function(){
			history.go(-1);
    });
});

function reloadGrid(){
	jQuery("#list").trigger("reloadGrid");
}

function getSelectedRows(){
	var s = jQuery("#list").jqGrid('getGridParam','selarrrow');
	return s;
}
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
   $(document).ready(function(){
        $('#product').addClass('sb_active_opt');
        $('#product').removeClass('has_submenu');
         $('#addcard_active').addClass('sb_active_subopt_active');
    }) ;
</script>