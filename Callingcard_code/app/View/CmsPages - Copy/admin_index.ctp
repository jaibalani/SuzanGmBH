<div class="main_admin_dashboard">
	<table  width="100%" id="list"></table> 
	<div id="pager"></div>
</div>

<script>
 $(function(){ 
  $("#list").jqGrid({
    url:'<?php echo $this->Html->url(array("controller" => "CmsPages","action" => "generategrid"));?>',
    datatype: "json",
    mtype: 'GET',
    colNames:['Id','Title','Created','Updated','Edit','Translate'],
    colModel :[ 
			{name:'id',index:'id',width:20,	align:'center',stype:'text',sorttype:'int',sortable:true}, 
			{name:'title',index:'title',width:90,	align:'left',stype:'text',sortable:true},
			{name:'created',index:'created',width:40,align:'center',stype:'text',	sortable:true},
			{name:'updated',index:'updated',width:40,	align:'center',stype:'text',	sortable:true},
			{name:'edit',index:'edit',width:20,align:'center',stype:'',	sortable:false},
			{name:'manage_content',index:'manage_content',width:20,align:'center',stype:'',sortable:false},
    ],
    pager: jQuery('#pager'),
    rowNum:10,
    rowList:[10,20,30],
    sortname: 'id',
    sortorder: 'desc',
    viewrecords: true,
    gridview: true,
		//loadonce: true,
		hidegrid:false,
		height:"100%",
		width:780,
	  //ignoreCase:true,
    caption: 'View Cmspages'
  }); 
  
  jQuery("#list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false,search:false,refresh:true});
	
  jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true});
	
});

</script>

