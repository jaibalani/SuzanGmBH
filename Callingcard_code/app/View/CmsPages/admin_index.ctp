<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <div class="sub_title"><i class="icon-gear home_icon"></i> <span class="sub_litle_m">Settings</span> <i class="icon-angle-right home_icon"></i> <span>CMS Manage</span></div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title card_w" style="width:30%;"><?php echo $title; ?></div>
            <button class="new_button clear_filer_class" style="float:right;" type="button" style="cursor:pointer;">
                <span class="icon-remove icon-white"></span>&nbsp;&nbsp;Clear Filter
            </button>
       <button class="new_button back " type="button" style="cursor:pointer; float:right;margin-right:10px;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
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
    url:'<?php echo $this->Html->url(array("controller" => "CmsPages","action" => "generategrid"));?>',
    datatype: "json",
    mtype: 'GET',
    colNames:['Id','Title','Created','Updated','Action'],
    colModel :[ 
    {name:'id',index:'id',width:20,	align:'center',stype:'text',sorttype:'int',sortable:true}, 
    {name:'title',index:'title',width:90,	align:'left',stype:'text',sortable:true},
    {name:'created',index:'created',width:40,align:'center',stype:'',	sortable:true},
    {name:'updated',index:'updated',width:40,	align:'center',stype:'',	sortable:true},
    <!--{name:'edit',index:'edit',width:20,align:'center',stype:'',	sortable:false},->
    {name:'manage_content',index:'manage_content',width:40,align:'center',stype:'',sortable:false},
    ],
    pager: jQuery('#pager'),
    rowNum:50,
    rowList:[10,20,30,50],
    sortname: 'id',
    sortorder: 'desc',
    viewrecords: true,
    gridview: true,
	//loadonce: true,
  	hidegrid:false,
  	height:"100%",
  	autowidth: true,
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
   // window.location.href = url;
  history.go(-1);
});

$(document).ready(function(){
   $('#setting').addClass('sb_active_opt');
   $('#setting').removeClass('has_submenu');
   $('#cmspage_active').addClass('sb_active_subopt_active');
   
}) ;

$('.clear_filer_class').click(function(){
 		var new_url = "<?php echo $this->Html->url(array('controller'=>'CmsPages','action'=>'generategrid','admin'=>'true'));?>";
		$('#list').setGridParam({url:new_url});
	    $("#list")[0].clearToolbar();
		reloadGrid();
});

</script>

