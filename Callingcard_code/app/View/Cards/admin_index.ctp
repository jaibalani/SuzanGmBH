<style type="text/css">
	.selcet_sub {
		/*width: 16% !important;*/
	}
</style>
<?php echo $this->Form->create('Card',array('id'=>'card_from')); ?>
<?php echo $this->Form->hidden('selchar',array('type'=>'hidden','value'=>@$selchar)); ?>
<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <div class="sub_title"><i class="icon-list-alt home_icon"></i> <span class="sub_litle_m">Product Master</span> <i class="icon-angle-right home_icon"></i> <span>Card Management</span></div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title card_w" style="width:30%;"><?php echo $title; ?></div>
         <button class="new_button clear_filer_class" style="float:right;" type="button" style="cursor:pointer;">
              	<span class="icon-remove icon-white"></span>&nbsp;&nbsp;Clear Filter
        </button>
        <button class="new_button new_button_right mar_right" id="add_button" type="button" style="float:right;margin-right:10px;"><span class="icon-plus icon-white"></span>&nbsp;&nbsp;Add New</button>
        <button class="new_button back" type="button" style="float:right;margin-right:10px;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
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
            <ul class="nav navbar-nav alpha">
    			 <?php if ($selchar =='All' ) {?>
				<li class="active"><a href="javascript:void(0)" onclick="searchChar('All')"><?php echo __('All');?></a></li>
			    <?php } else {?>
            	<li class=""><a href="javascript:void(0)" onclick="searchChar('All')"><?php echo __('All');?></a></li>
			    <?php } ?>

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
                <div class="col-md-3" align="right" style="margin-right: 0px;padding-left:0px !important;">
                        <?php
                         echo $this->Form->input('cat_id', 
                               array(
                                    'class'=>'select_boxfrom',
                                    'type'=>'select',
                                    'options'=> @$catList,
                                    'value'=>@$cat_id,
                                    'label'=>false,
                                    'style'=>'cursor:pointer;',
                                    'empty'=>'--- All Category ---'
                                   )
                               );
                        ?>
                </div>  
                <div class="col-md-3 " align="right" style="margin-right: 0px;">
                             <?php echo $this->Form->input('sub_cat_id', 
                                    array(
                                    	'class'=>'select_boxfrom form_submit',
                                        'type'=>'select',
                                        'empty'=>'--- All Sub Category ---',
										'options'=> @$subCatList,
	                                    'value'=>@$sub_cat_id,
		                                'label'=>false,
                                        'style'=>'cursor:pointer;',
                                        )
                                    );
                             ?>
                </div>
                
                <div class="col-md-3">
                	<?php
                         echo $this->Form->input('card_id', 
                               array('type' => 'button',
                                    'class'=>'select_boxfrom form_submit',
                                    'type'=>'select',
                                    'options'=> @$cardList,
                                    'value'=>@$card_id,
                                    'label'=>false,
                                    'style'=>'cursor:pointer;',
                                    'empty'=>'--- All Card ---'
                                   )
                               );
                        ?>
				</div>
                <div class="col-md-3"><?php echo $this->Form->input('card_rate',array(
                        'label' 	 => false, 
                        'type' 		 => 'select',
						'required' => false, 
						'value' => $rate,
                        'class' 	 => 'select_boxfrom form_submit',
                        'empty' 	 => '--- All Selling Price---',
                        'options'  => array(
                        				"1" => "<=1",
                        				"2" => ">1<=2.5",
                        				"3" => ">2.5<=5",
                        				"4" => ">5<=10",
                        				"5" => ">10",
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
	//\alert('asdasd');
	
    jQuery("#list").jqGrid({
    	
        url: "<?php echo Router::url(array('controller'=>'Cards','action'=>'admin_json','cat_id'=>@$cat_id,'sub_cat_id'=>@$sub_cat_id,'selchar'=>@$selchar,'rate'=>@$rate));?>",
        datatype: "json",

		colNames:['Title','Category','Sub Category','Uploaded Pins','Used','Balance Including Unused','Unused','Status','Action',''],

		colModel:[
			{name:'c_title',index:'c_title', width:145,search:true, sortable:true, align:"left"},
			{name:'Parent.cat_title',index:'Parent.cat_title', width:125,search:true, sortable:true, align:"left"},
			{name:'Category.cat_title',index:'Category.cat_title', width:125,search:true, sortable:true, align:"left"},
			{name:'pin_card_count',index:'pin_card_count', width:90,search:true, sortable:true,align:"left"},
			{name:'pin_card_sold_count',index:'pin_card_sold_count', width:80,search:true, sortable:true,align:"left"},
			{name:'all_pins_except_sold',index:'all_pins_except_sold', width:80,search:true, sortable:true,align:"left"},
			{name:'pin_card_remain_count',index:'pin_card_remain_count', width:65,search:true,sortable:true},
			{name:'c_status',index:'c_status', width:119,search:true,stype:'select',searchoptions: {value: {'-1':'All', 1: 'Enable',0: 'Disable'},defaultValue: '-1'}, sortable:true, align:"center"},
			{name:'action',index:'action', width:290,search:false, sortable:false, align:"center"},
			{name:'total_pins_required',index:'total_pins_required', width:1,search:false,hidden:true},
			
        ],
        rowNum:50,
   		rowList:[10,20,30,50],
        pager: '#gridpager',
        //sortname: 'Card.c_inventory_threshold * Card.c_pin_per_card < Card.pin_card_remain_count, Card.c_title',
		//sortname: 'Card.c_inventory_threshold * Card.c_pin_per_card < Card.pin_card_remain_count, Card.c_title',
		sortname:'',
        viewrecords: true,
        rownumbers: false,
        sortorder: '',
        caption:false,
		multiselect: false,
        height:"100%",
        autowidth:true,
        shrinkToFit:true,
       	beforeRequest: function () {
           $("#loading-image").fadeIn();
        },
        gridComplete: function() {
            $('#list tr:nth-child(odd)').addClass("evenTableRow");
            $('#list tr:nth-child(even)').addClass("oddTableRow");
			var rowIDs = jQuery("#list").jqGrid('getDataIDs');
                                    var rowID;
									var row;
									var pin_card_remain_count;
									var total_pins;
									for (var i = 0; i < rowIDs.length ; i++) 
									{
										 rowID = rowIDs[i];
										 row = jQuery('#list').jqGrid ('getRowData', rowID);
										 pin_card_remain_count = parseInt(row.pin_card_remain_count);
										 total_pins_required = parseInt(row.total_pins_required);
										 //alert(total_pins+' >= '+pin_card_remain_count);
										 if(total_pins_required > pin_card_remain_count)
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
    jQuery("#list").jqGrid('navGrid','#gridpager',{edit:false,search:false,add:false,del:false});
	jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
    
	$('#CardCatId').change(function(){
		$('.navbar-nav li').removeClass('active');
        var cat_id =  $(this).val();
		var sub_cat_id =  '';
		var selchar =  'All';
		var rate =  $('#CardCardRate').val();
		
		$.ajax({
		  beforeSend: function (XMLHttpRequest){
			 $("#loading-image").fadeIn();
		  },
		  complete: function (XMLHttpRequest, textStatus) {
			$("#loading-image").fadeOut();
		  },
		  url: "<?php echo Router::url(array('controller'=>'cards','action'=>'get_subcat'));?>", 
		  type: "POST",
		  data: ({id : cat_id}),
		  dataType: 'json',
		  success: function(json){
			$('#CardSubCatId').html('');
			$('#CardSubCatId').html('<option value="">--- All Sub Category---</option>');
			
			var keys = [];
			var datas = {}
			$.each(json, function(key, value){
			  keys.push(value)
			  datas[value] = key;
			})
			var aa = keys.sort()
			$.each(aa, function(index, value){
				$('#CardSubCatId').append($('<option>').text(value).attr('value', datas[value]));
			}) 
			
		  }
		});
		
		
		$.ajax({
		  beforeSend: function (XMLHttpRequest){
			 $("#loading-image").fadeIn();
		  },
		  complete: function (XMLHttpRequest, textStatus) {
			$("#loading-image").fadeOut();
		  },
		  url: "<?php echo Router::url(array('controller'=>'cards','action'=>'get_cards_parent_cat'));?>", 
		  type: "POST",
		  data: ({id : cat_id}),
		  dataType: 'json',
		  success: function(json){
			$('#CardCardId').html('');
			$('#CardCardId').html('<option value="">--- All Card---</option>');
   			var keys = [];
			var datas = {}
			$.each(json, function(key, value){
			  keys.push(value)
			  datas[value] = key;
			})
			var aa = keys.sort()
			$.each(aa, function(index, value){
				$('#CardCardId').append($('<option>').text(value).attr('value', datas[value]));
			})			    
			  
		  }
		});
		
		
		var new_url = '';
		new_url = '/cat_id:'+cat_id;
		new_url = new_url+'/sub_cat_id:'+sub_cat_id;
		new_url = new_url+'/selchar:'+selchar;
		new_url = new_url+'/rate:'+rate;
		var updated_url = "<?php echo Router::url(array('controller'=>'Cards','action'=>'admin_json'));?>"+new_url;
     	$('#list').setGridParam({url:updated_url});
	    reloadGrid();
	}); 
    
	$('#CardSubCatId').change(function(){
		$('.navbar-nav li').removeClass('active');
        
		var sub_cat_id = $(this).val();

		if(sub_cat_id.length == 0)
		var cat_id = $('#CardCatId').val();
	    else
        var cat_id =   '';

		var card_id = '';
		var selchar =  'All';
		var rate =  $('#CardCardRate').val();
		
		$.ajax({
		  beforeSend: function (XMLHttpRequest){
			 $("#loading-image").fadeIn();
		  },
		  complete: function (XMLHttpRequest, textStatus) {
			$("#loading-image").fadeOut();
		  },
		  url: "<?php echo Router::url(array('controller'=>'cards','action'=>'get_cards'));?>", 
		  type: "POST",
		  data: ({id : sub_cat_id,main_cat_id : cat_id}),
		  dataType: 'json',
		  success: function(json){
			$('#CardCardId').html('');
			$('#CardCardId').html('<option value="">--- All Cards ---</option>');
			
			var keys = [];
			var datas = {}
			$.each(json, function(key, value){
			  keys.push(value)
			  datas[value] = key;
			})
			var aa = keys.sort()
			$.each(aa, function(index, value){
				$('#CardCardId').append($('<option>').text(value).attr('value', datas[value]));
			}) 
			
		  }
		});
		
		var new_url = '';
		new_url = '/cat_id:'+cat_id;
		new_url = new_url+'/sub_cat_id:'+sub_cat_id;
		new_url = new_url+'/selchar:'+selchar;
		new_url = new_url+'/rate:'+rate;
		new_url = new_url+'/card_id:'+card_id;
		var updated_url = "<?php echo Router::url(array('controller'=>'Cards','action'=>'admin_json'));?>"+new_url;
     	$('#list').setGridParam({url:updated_url});
	    reloadGrid();
	}); 


     $('#CardCardId').change(function(){

		$('.navbar-nav li').removeClass('active');
        var cat_id =   $('#CardCatId').val();
		var sub_cat_id = $('#CardSubCatId').val();
		var card_id = $(this).val();
		var selchar =  'All';
		var rate =  $('#CardCardRate').val();
		
		var new_url = '';
		new_url = '/cat_id:'+cat_id;
		new_url = new_url+'/sub_cat_id:'+sub_cat_id;
		new_url = new_url+'/selchar:'+selchar;
		new_url = new_url+'/rate:'+rate;
		new_url = new_url+'/card_id:'+card_id;
		
		var updated_url = "<?php echo Router::url(array('controller'=>'Cards','action'=>'admin_json'));?>"+new_url;
     	$('#list').setGridParam({url:updated_url});
	    reloadGrid();
	}); 
     



	$('.form_submit').change(function(){
		
		$('.navbar-nav li').removeClass('active');
        var cat_id =  $('#CardCatId').val();
        var card_id =  $('#CardCardId').val();
		var sub_cat_id =  $('#CardSubCatId').val();
		var selchar =  'All';
		var rate =  $('#CardCardRate').val();
		
		var new_url = '';
		new_url = '/cat_id:'+cat_id;
		new_url = new_url+'/sub_cat_id:'+sub_cat_id;
		new_url = new_url+'/selchar:'+selchar;
		new_url = new_url+'/rate:'+rate;
		new_url = new_url+'/card_id:'+card_id;
		var updated_url = "<?php echo Router::url(array('controller'=>'Cards','action'=>'admin_json'));?>"+new_url;
     	$('#list').setGridParam({url:updated_url});
	    reloadGrid();

		//$('#card_from').submit();
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
		history.go(-1);
		//window.location = "<?php //echo Router::url(array('controller'=>'Users','action'=>'dashboard'));?>";
	});

});

function searchChar(char){
	
	$('#CardSelchar').val(char);
	$('#CardCatId').val('');
	$('#CardCardId').val('');
	$('#CardSubCatId').val('');
	$('#CardCardRate').val('');
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
	}else if(st == 0) {
		msg = "Are you sure? You want to inactive the status.";
	}

	if(confirm(msg)){
		$.ajax({
			url: "<?php echo Router::url(array('controller'=>'Cards','action'=>'admin_changestatus'));?>", 
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

$('.clear_filer_class').click(function(){
 		
		$('#CardCatId').val('');
		$('#CardCardRate').val('');
		
        $('.navbar-nav li').removeClass('active');
		var sub_cat_id =  '';
		var selchar =  'All';
		var rate =  '';
		var CardCatId = '';
		
		$.ajax({
		  beforeSend: function (XMLHttpRequest){
			 $("#loading-image").fadeIn();
		  },
		  complete: function (XMLHttpRequest, textStatus) {
			$("#loading-image").fadeOut();
		  },
		  url: "<?php echo Router::url(array('controller'=>'cards','action'=>'get_subcat'));?>", 
		  type: "POST",
		  data: ({id : CardCatId}),
		  dataType: 'json',
		  success: function(json){
			$('#CardSubCatId').html('');
			$('#CardSubCatId').html('<option value="">--- All Sub Category---</option>');
			$.each(json, function(i, value) {
			        $('#CardSubCatId').append($('<option>').text(value).attr('value', i));
			 });
		  }
		});
		var new_url = '';
		new_url = '/cat_id:'+CardCatId;
		new_url = new_url+'/sub_cat_id:'+sub_cat_id;
		new_url = new_url+'/selchar:'+selchar;
		new_url = new_url+'/rate:'+rate;
		var updated_url = "<?php echo Router::url(array('controller'=>'Cards','action'=>'admin_json'),true);?>"+new_url;
		$('#list').setGridParam({url:updated_url,page:1});
		//$('#list').jqGrid('setGridParam',{url:updated_url,page:1});
		$("#list")[0].clearToolbar();
		reloadGrid();
});

$(document).ready(function(){
   $('#product').addClass('sb_active_opt');
   $('#product').removeClass('has_submenu');
   $('#addcard_active').addClass('sb_active_subopt_active');
}) ;

function delete_card(id)
{
    var ans = confirm("Are you sure? You want to delete the selected card.");
    if(ans)
    {
    	window.location = "<?php echo Router::url(array('controller'=>'Cards','action'=>'delete_card'));?>/"+id;
    }
	
}

function view_alert()
{
	alert("You can not view the pins details of disabled cards");
}
</script>
