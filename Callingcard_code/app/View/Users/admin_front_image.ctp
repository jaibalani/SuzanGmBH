<style type="text/css">
.ui-jqgrid tr.jqgrow td{
height:50px !important;
}
</style>
<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <div class="sub_title"><i class="icon-user home_icon"></i> <span class="sub_litle_m">Manage Front Images </span> </div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title mediator_w" style="width:30%;"><?php echo $title_for_layout; ?></div>
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
  
  	var editOptions = {
	aftersavefunc: function (rowid, response, options) {
	console.log("Строка " + rowid + " сохранена.");
	//$("#grid").trigger("reloadGrid");
	}
	};
    var lastSel;
    $("#list").jqGrid({
    url:'<?php echo $this->Html->url(array("controller" => "Users","action" => "admin_generategrid_front_image",'admin'=>true));?>',
    datatype: "json",
    mtype: 'GET',
	 colNames:['Id','Content','Image','Action'],
     colModel :[ 
		{name:'id',index:'id',width:50,align:'center',stype:'',sorttype:'int', stype:'text'}, 
		{name:'content_english', index:'content_english', width:250,align:'left',	stype:'text',	sortable:true},
		{name:'image', index:'image', width:150,	align:'left',	stype:'text', search:false,	sortable:false},
		<!--{name:'status', index:'status', width:100,	align:'center',	stype:'select',searchoptions: {value: {0:'All', 1: 'Enable',2: 'Disable'}},sortable:true},-->
		{name:'action', index:'action', width:100,	align:'center',	stype:'',	sortable:false},

	],
   
    pager: jQuery('#pager'),
    rowNum:50,
    rowList:[10,20,30,50],
    sortname: 'id',
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

function deleteuser(id)
{
	var ans = confirm("<?php echo __('Are you sure you want to delete this mediator ?');?>");
	if(ans)
	{
		var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'deleteuser','admin'=>'true'));?>/"+id;
  	window.location.href = url;
	}
}

$(document).ready(function(){
   $('#manage-front-image').addClass('sb_active_single_opt');
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
			url: "<?php echo Router::url(array('controller'=>'Users','action'=>'admin_front_image_status'));?>", 
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
			
	}
	else 
	{
		var st = st == 1 ? 0 : 1 ;
		$(status).val(st);
	}
}



$('.clear_filer_class').click(function(){
 		var new_url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'admin_generategrid_front_image','admin'=>'true'));?>";
		$('#list').setGridParam({url:new_url});
	    $("#list")[0].clearToolbar();
		reloadGrid();
});

</script>
