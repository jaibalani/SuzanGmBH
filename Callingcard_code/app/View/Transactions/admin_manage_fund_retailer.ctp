<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
    
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <div class="sub_title"><i class="icon-user home_icon"></i> <span class="sub_litle_m">Retailer</span> <i class="icon-angle-right home_icon"></i> <span>Manage Fund</span></div>
  <div class="main_subdiv">
    <div class="row" style="margin-bottom:15px;">
    <div class="col-md-3"><?php echo __('Total Amount Funded :'); ?></div>
    <div class="col-md-3" style="color:#F00"><?php echo "&euro;".$total_amount_funded; ?></div>
    <div class="col-md-3"><?php echo __('Fund In Balance :'); ?></div>
    <div class="col-md-3" style="color:#F00"><?php echo "&euro;".$balance; ?></div>
  </div> 
    <div class="gird_button">
        <div class="main_sub_title ret_fund" style="width:30%;"><?php echo $title_for_layout; ?></div>
<!--            <button class="new_button mar_right"  style="margin-left:15px;" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;&nbsp;Delete</button>
-->     
        <button class="new_button clear_filer_class" style="float:right;" type="button" style="cursor:pointer;">
                	<span class="icon-remove icon-white"></span>&nbsp;&nbsp;Clear Filter
                </button>
		<button class="new_button mar_rightr" id="add_button" type="button"  style="float:right;margin-right:10px;"><span class="icon-plus icon-white"></span>&nbsp;&nbsp;Add New</button>
        <button class="new_button back " type="button" style="cursor:pointer; float:right;margin-right:10px;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
    </div>
        <div class="selectbox_div">
<!--           <div class="col-md-7 cat_space"></div>
-->            <div class="col-md-2 selectbox_title" style="padding-left:0px;"><?php echo __('Select Retailer:'); ?></div>
                <div class="col-md-5" >
                    <?php echo $this->Form->input('retailer', array('type' => 'button',
                            'class'=>'select_boxfrom',
                            'type'=>'select',
                            'options'=>$retailer_list,
                            'value'=>$retailer_id,
                            'label'=>false,
							'onchange'=>'change_retailer(this);',
                            'style'=>'cursor:pointer;'));
                    ?>
                        
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
  $("#list").jqGrid({
    url:'<?php echo $this->Html->url(array("controller" => "Transactions","action" => "generategrid_retailer",'admin'=>true,$retailer_id));?>',
    datatype: "json",
    mtype: 'GET',
	 colNames:['Id','First Name','Last Name','Total Amount','Balance','Minimum Balance','Updated','Action'],
     colModel :[ 
			{name:'id',index:'id',width:40,align:'center',stype:'',sorttype:'int', stype:'text',sortable:true}, 
			{name:'fname',index:'fname', width:75,align:'left',	stype:'text',	sortable:true},
			{name:'lname', index:'lname', width:75,	align:'left',	stype:'text',	sortable:true},
<!--			{name:'email', index:'email', width:130,	align:'left',	stype:'text',	sortable:true},-->
		<!--	{name:'image', index:'image', width:50,	align:'center',	stype:'',	sortable:false},-->
			{name:'total_amount', index:'total_amount', width:80,	align:'left',	stype:'text',	sortable:true},
			{name:'balance', index:'balance', width:70,	align:'center',	stype:'text',	sortable:false},
			{name:'minimum_balance',index:'minimum_balance',width:90, align:'left',stype:'text'},
			{name:'updated', index:'updated', width:60,	align:'center',	stype:'',	sortable:false},
			{name:'manage', index:'manage', width:70,	align:'center',	stype:'',	sortable:false},

		],
    pager: jQuery('#pager'),
    rowNum:50,
    rowList:[10,20,30,50],
    sortname: 'User.lname,Transaction.total_amount',
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
		var rowIDs = jQuery("#list").jqGrid('getDataIDs');
                                    var rowID;
									var row;
									var balance;
									var minimum_balance;
									for (var i = 0; i < rowIDs.length ; i++) 
									{
										 rowID = rowIDs[i];
										 row = jQuery('#list').jqGrid ('getRowData', rowID);
										 balance = parseFloat(row.balance);
										 minimum_balance = parseFloat(row.minimum_balance);
										 if(minimum_balance >= balance)
										 {
											 $('#'+rowID).removeClass('oddTableRow');
											 $('#'+rowID).removeClass('evenTableRow');
											 $('#'+rowID).css('background','#00BFFF');
											 $('#'+rowID).css('color','#FFF');
											 $('#'+rowID+' a').css('color','#FFF');
										 }
									}
	    $("#loading-image").fadeOut();
	}
  }); 
  
  jQuery("#list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false,search:false,refresh:true});	
  jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

  	//delete records
	$("#delete_button").click(function(){
		var ids = getSelectedRows();
		if(ids!=''){
			if(confirm("Are you sure? You want to delete selected records.")){
				$.ajax({
					url: "<?php echo Router::url(array('controller'=>'Transactions','action'=>'admin_delete_retailer_fund'));?>", 
					type: "POST", 
					data: ({ids : ids}), 
					success: function(data){
							location.reload(true);
				  }
				});
			}
		}
		else
		{
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

function change_retailer(obj)
{
     var retailer_id = $(obj).val();
	 if(retailer_id)
	 {
 		var url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'generategrid_retailer','admin'=>'true'));?>/"+retailer_id;
 		$('#list').setGridParam({url:url});
	    $("#list")[0].clearToolbar();
		reloadGrid();
	 }
}

$('#add_button').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'add_fund_parent_retailer','admin'=>'true'));?>";
	window.location.href = url;
});
    
	$(document).ready(function(){
	   $('#retailer').addClass('sb_active_opt');
	   $('#retailer').removeClass('has_submenu');
	   $('#manage_fund').addClass('sb_active_subopt_active');
           var new_url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'generategrid_retailer','admin'=>'true'));?>";
		$('#list').setGridParam({url:new_url});
	    $("#list")[0].clearToolbar();
		reloadGrid();
	}) ;
	
	$('.clear_filer_class').click(function(){
 		var new_url = "<?php echo $this->Html->url(array('controller'=>'Transactions','action'=>'generategrid_retailer','admin'=>'true'));?>";
		$('#list').setGridParam({url:new_url});
	    $("#list")[0].clearToolbar();
		reloadGrid();
   });

	
	
</script>
