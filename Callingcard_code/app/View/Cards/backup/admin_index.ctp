<style type="text/css">
	.selcet_sub {
		width: 16% !important;
	}
</style>
<?php echo $this->Form->create('Card',array('id'=>'card_from')); ?>
<?php echo $this->Form->hidden('selchar',array('type'=>'hidden','value'=>@$selchar)); ?>
<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <div class="sub_title"><i class="icon-list-alt home_icon"></i> <span class="sub_litle_m">PIN Management</span> <i class="icon-angle-right home_icon"></i> <span>Card List</span></div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title card_w"><?php echo $title; ?></div>
        <button class="new_button mar_right" id="add_button" type="button"><span class="icon-plus icon-white"></span>&nbsp;&nbsp;Add New</button>
        <button class="new_button" type="button"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
    </div>
    <div class="clear20"></div>
  <nav role="navigation" class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div class="navbar-collapse collapse" id="navbar">
            <ul class="nav navbar-nav">
            	<?php foreach (range('A', 'Z') as $char){
											$class = '';
											if($selchar==$char){
												$class = 'class="active"';
											}?>
											<li <?php echo $class?>><a href="javascript:void(0)" onclick="searchChar('<?php echo $char; ?>')"><?php echo $char?></a>

											</li>
							<?php }
							?>

            </ul>

          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
    <div class="selectbox_div">
           <div class="col-md-6"></div>
                <div class="col-md-3 selcet_sub" align="right" style="margin-right: 10px;">
                        <?php
                         echo $this->Form->input('cat_id', 
                               array('type' => 'button',
                                    'class'=>'select_boxfrom form_submit',
                                    'type'=>'select',
                                    'options'=> @$catList,
                                    'value'=>@$cat_id,
                                    'label'=>false,
                                    'style'=>'cursor:pointer;',
                                    'empty'=>'--- Select Category ---'
                                   )
                               );
                        ?>
                </div>  
                <div class="col-md-3 selcet_sub" align="right" style="margin-right: 10px;">
                             <?php echo $this->Form->input('sub_cat_id', 
                                    array(
                                    	'type' => 'button',
                                        'class'=>'select_boxfrom form_submit',
                                        'type'=>'select',
                                        'options'=> @$subCatList,
	                                    'value'=>@$sub_cat_id,
		                                'label'=>false,
                                        'style'=>'cursor:pointer;',
                                        'empty'=>'--- Select Sub Category ---'
                                        )
                                    );
                             ?>
                </div>
                <div class="col-md-3 selcet_sub"><?php echo $this->Form->input('card_rate',array(
                        'label' 	 => false, 
                        'type' 		 => 'select',
						'required' => false, 
						'value' => $rate,
                        'class' 	 => 'select_boxfrom form_submit',
                        'empty' 	 => '--- Select Price---',
                        'options'  => array(
                        				"1" => "Under 100",
                        				"2" => "101 - 200",
                        				"3" => "201 - 300",
                        				"4" => "301 - 400",
                        				"5" => "401 - 500",
                        				"6" => "501 - 600",
                        				"7" => "More Than 600"
                        			)));?>
               	</div>
           	</div>  
  <div class="clear10"></div>
  <div align="left" class="grid_table_box">
    <table id="list"></table> 
		<div id="gridpager"></div>
  </div>
  <div class="clear10"></div>
  </div>
</div>

<?php echo $this->Form->end();  ?>

<script>
$(document).ready(function (){
	//alert('asdasd');
	
    jQuery("#list").jqGrid({
    	
        url: "<?php echo Router::url(array('controller'=>'Cards','action'=>'admin_json','cat_id'=>@$cat_id,'sub_cat_id'=>@$sub_cat_id,'selchar'=>@$selchar,'rate'=>@$rate));?>",
        datatype: "json",
		colNames:['Title','Icon','Category','Sub Category','Edit','Uploaded Pins','Used','Balance','Import', 'Export','Merge Pins','Unmerge Pins','Status'],
		colModel:[
			{name:'c_title',index:'c_title', width:150,search:true, sortable:true, align:"left"},
			{name:'c_image',index:'c_image', width:60,search:false, sortable:false,align:"center"},
			{name:'Category.cat_title',index:'Category.cat_title', width:125,search:false, sortable:true, align:"left"},
			{name:'sub_cat_title',index:'sub_cat_title', width:125,search:false, sortable:false, align:"left"},
			
			{name:'edit',index:'edit', width:30,search:false, sortable:false,align:"center"},
			{name:'pin_card_count',index:'pin_card_count', width:100,search:true, sortable:true,align:"left"},
			{name:'pin_card_sold_count',index:'pin_card_sold_count', width:100,search:true, sortable:true,align:"left"},
			{name:'pin_card_remain_count',index:'pin_card_remain_count', width:100,search:true, sortable:true,align:"left"},
			{name:'import',index:'import', width:60,search:false, sortable:false,align:"center"},
			{name:'download',index:'download', width:60,search:false, sortable:false,align:"center"},
			{name:'merge',index:'merge', width:70,search:false, sortable:false,align:"center"},
			{name:'ummerge',index:'unmerge', width:70,search:false, sortable:false,align:"center"},
			{name:'cat_status',index:'cat_status', width:120,search:false, sortable:true, align:"center"}
        ],
        rowNum:10,
   		  rowList:[10,20,30],
        pager: '#gridpager',
        sortname: 'Card.c_title',
        viewrecords: true,
        rownumbers: true,
        sortorder: "asc",
        caption:false,
		    multiselect: true,
        height:"100%",
        autowidth:true,
        shrinkToFit:true,
        gridComplete: function() {
            $('#list tr:nth-child(odd)').addClass("evenTableRow");
            $('#list tr:nth-child(even)').addClass("oddTableRow");
        }
    });
    jQuery("#list").jqGrid('navGrid','#gridpager',{edit:false,search:false,add:false,del:false});
	jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true});


	$('.form_submit').change(function(){
   		$('#card_from').submit();
	});



	//delete records
	$("#delete_button").click(function(){
		var ids = getSelectedRows();
		if(ids!=''){
			if(confirm("Are you sure? You want to remove this record.")){
				$.ajax({
					url: "<?php echo Router::url(array('controller'=>'Cards','action'=>'admin_delete'));?>", 
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
		window.location = "<?php echo Router::url(array('controller'=>'Cards','action'=>'admin_add'));?>";
	});
	$('.back').click(function(){
		window.location = "<?php echo Router::url(array('controller'=>'Users','action'=>'dashboard'));?>";
	});

});

function searchChar(char){
	
	$('#CardSelchar').val(char);
	$('#card_from').submit();
		
}

function reloadGrid(){
	jQuery("#list").trigger("reloadGrid");
}

function getSelectedRows(){
	var s = jQuery("#list").jqGrid('getGridParam','selarrrow');
	return s;
}
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
			url: "<?php echo Router::url(array('controller'=>'Cards','action'=>'admin_changestatus'));?>", 
			type: "POST",
			data: ({id : id, st : st}),
			success: function(data){
				if(data == '1'){
					alert('Status has been changed successfully');
					//reloadGrid();
				 }
			}
		});
			
	}
	else {
		var st = st == 1 ? 0 : 1 ;
		$(status).val(st);
	}
}

$(document).ready(function(){
   $('#pin').addClass('sb_active_opt');
   $('#pin').removeClass('has_submenu');
   $('#addlist_active').addClass('sb_active_subopt_active');
}) ;
</script>