<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <div class="sub_title"><i class="icon-gear home_icon"></i> <span class="sub_litle_m">Settings</span> <i class="icon-angle-right home_icon"></i> <span>Language Manage</span></div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title" style="width:30%;"><?php echo $title_for_layout; ?></div>
        <button class="new_button clear_filer_class" style="float:right;" type="button" style="cursor:pointer;">
            <span class="icon-remove icon-white"></span>&nbsp;&nbsp;Clear Filter
        </button>
        <button class="new_button new_button_right mar_rightr" id="manage_order" style="cursor:pointer;margin-right:10px;float:right;" type="button" onclick="manage_order();">&nbsp;Manage Order</button>
        <button class="new_button back " type="button" style="cursor:pointer;margin-right:10px;float:right;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
    </div>    
  
  <div class="clear10"></div>
  <div align="left" class="grid_table_box">
    <table id="list"></table> 
		<div id="pager"></div>
  </div>
  <div class="clear10"></div>
  </div>
</div>
<script>
 $(function(){ 
  $("#list").jqGrid({
    url:'<?php echo $this->Html->url(array("controller" => "Languages","action" => "generategrid"));?>',
    datatype: "json",
    mtype: 'GET',
    colNames:['S.No.','Title','Native','Action'],
    colModel :[ 
			{name:'id',index:'id',width:100,align:'center',stype:'text',	sorttype:'int',	sortable:true}, 
			{name:'title',index:'title',width:200,align:'left',stype:'text',sortable:true},
    	    {name:'native',index:'native',width:200,align:'left',stype:'',sortable:true},
			{name:'action',index:'action',width:200,align:'center',stype:'',sortable:false},
			<!--{name:'edit',index:'edit',width:100,align:'center', stype:'',sortable:false},-->
			<!--{name:'edit_tr',index:'edit_tr',width:100,align:'center',stype:'',sortable:false}-->
    ],
    pager: jQuery('#pager'),
    rowNum:50,
    rowList:[10,20,30,50],
    sortname: 'lan_order',
    sortorder: 'asc',
    viewrecords: true,
    gridview: true,
    hidegrid:false,
    height:"100%",
    autowidth:true,
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

function manage_order()
{
	var url = "<?php echo $this->Html->url(array('controller'=>'languages','action'=>'manageorder','admin'=>'true'));?>";
	window.location.href = url;
}

$('.back').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'dashboard','admin'=>'true'));?>";
    //window.location.href = url;
    history.go(-1);
});


function add_new()
{
	var url = "<?php echo $this->Html->url(array('controller'=>'languages','action'=>'add','admin'=>'true'));?>";
	window.location.href = url;
}
    $(document).ready(function(){
   $('#setting').addClass('sb_active_opt');
   $('#setting').removeClass('has_submenu');
   $('#lag_active').addClass('sb_active_subopt_active');
}) ;

$('.clear_filer_class').click(function(){
 		var new_url = "<?php echo $this->Html->url(array('controller'=>'Languages','action'=>'generategrid','admin'=>'true'));?>";
		$('#list').setGridParam({url:new_url});
	    $("#list")[0].clearToolbar();
		reloadGrid();
});

</script>