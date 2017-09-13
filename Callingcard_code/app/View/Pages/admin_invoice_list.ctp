<style type="text/css">
	.selcet_sub {
		width: 16% !important;
	}	
</style>
<?php $class = 'pin_class2';
    if(isset($back_cat) && !empty($back_cat)){
    $class='pin_class';
}?> 

<?php 
      echo $this->Form->create('Invoices',array('id'=>'invoice_form_download','action'=>'get_invoice_pdf'));
	  echo $this->Form->input('invoice_data',array('type'=>'hidden','id'=>'invoice_data'));
	  echo $this->Form->input('invoice_number',array('type'=>'hidden','id'=>'invoice_number'));
	  echo $this->Form->end();
		   
?>

<?php echo $this->Form->create('Invoice',array('id'=>'invoice_form')); ?>
<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title; ?></div>

  <div class="sub_title"><i class="icon-list-alt home_icon"></i> <span class="sub_litle_m">Invoices</span> </div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title <?php echo $class; ?>" style="width:30%;"><?php echo $title; ?></div>
             <button class="new_button clear_filer_class" style="float:right;" type="button" style="cursor:pointer;">
              	<span class="icon-remove icon-white"></span>&nbsp;&nbsp;<?php echo __("Clear Filter");?>
             </button>
             <button class="new_button new_button_right " id="add_button" type="button" style="float:right;margin-right:10px;"><span class="icon-plus icon-white"></span>&nbsp&nbsp;<?php echo __("Add");?></button>
   	<button class="new_button back" type="button" style="cursor:pointer;float:right;margin-right:10px;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;<?php echo __("Back");?></button>
            
    </div>

    <div class="clear10"></div>
    <div class="selectbox_div">
    			<?php if($this->Session->read('Auth.User.role_id')==1){ //if distributor?>
               <div class="col-md-6" align="left">
					<?php
						echo $this->Form->input ('mediator_id', array (
								'class' => 'form-control',
								'required' => true,
								'type' => 'select',
								'options' => @$mediator_list,
								'value' => @$mediator_id,
								'empty' => 'All Mediator',
								'label' => false 
						) );
						?>
                </div>  
            <?php }?>
                <div class="col-md-6">
				<?php
				echo $this->Form->input ( 'retailer_id', array (
						'class' => 'form-control',
						'required' => true,
						'type' => 'select',
						'options' => @$retailer_list,
						'value' => @$retailer_id,
						'empty' => 'All Retailer',
						'label' => false 
				) );
				?>
                </div>
           	</div>  
  <div class="clear10"></div>
  
  <div class="clear10"></div>
  <div align="left" class="grid_table_box">
    <table  width="100%" id="list"></table> 
		<div id="gridpager"></div>
  </div>
  <div class="clear10"></div>
  </div>
</div>
<?php echo $this->Form->end();  ?>
<script type="text/javascript">
$(document).ready(function (){
    jQuery("#list").jqGrid({
        url: "<?php echo Router::url(array('controller'=>'Pages','action'=>'admin_generategrid','user_id'=>@$retailer_id,'added_by_user'=>@$mediator_id));?>",
        datatype: "json",
        colNames:['Serial Number','Mediator','Retailer','Invoice Number','Created','Type','Action','Status'],
        colModel:[
					{name:'id',index:'id', width:70,search:true, sortable:true, align:"left"},
					{name:'Parent.fname',index:'Parent.fname', width:140,search:true, sortable:true,align:"left"},

					{name:'User.fname',index:'User.fname', width:140,search:true, sortable:true,align:"left"},


					{name:'invoice_number',index:'invoice_number', width:140,search:true, sortable:true,align:"left"},
					{name:'invoice_created',index:'invoice_created', width:100,search:true, sortable:false, stype:'text',align:"left"},
					{name:'file_name',index:'file_name', width:95,search:false, sortable:false, align:"action"},
					{name:'action',index:'action', width:90,search:false, sortable:false, align:"center"},
					{name:'status',index:'status', width:90,search:false, sortable:false, align:"center"},
			  ],
        rowNum:50,
   			rowList:[10,20,30,50],
        pager: '#gridpager',
        sortname: 'invoice_date_month',
        viewrecords: true,
        rownumbers: false,
        sortorder: "asc",
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
			$("#loading-image").fadeOut();
        
        }
    });
    jQuery("#list").jqGrid('navGrid','#gridpager',{edit:false,search:false,add:false,del:false});
	jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

	$("#add_button").click(function(){
		window.location = "<?php echo Router::url(array('controller'=>'Pages','action'=>'admin_invoice_add'));?>";
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


$('#InvoiceMediatorId').change(function(){
		var mediator_id = $(this).val();
		$.ajax({
				beforeSend: function (XMLHttpRequest){
				 $("#loading-image").fadeIn();
				},
				complete: function (XMLHttpRequest, textStatus) {
				$("#loading-image").fadeOut();
				},
				url: "<?php echo Router::url(array('controller'=>'Reports','action'=>'get_retailers'));?>", 
				type: "POST",
				data: ({id : mediator_id}),
				dataType: 'json',
				success: function(json){
				$('#InvoiceRetailerId').html('');
				$('#InvoiceRetailerId').html('<option value="">Select Retailer</option>');
				$.each(json, function(i, value) {
								$('#InvoiceRetailerId').append($('<option>').text(value).attr('value', i));
					});
				}
			});
		
		
		 var med_id =	 $('#InvoiceMediatorId').val();
		 var ret_id =  $('#InvoiceRetailerId').val();
		 
		 if(ret_id==''){
				ret_id = 0;
		 }
		 if(med_id==''){
				med_id = 0;
		 }

		var new_url = '';
		new_url = '/user_id:'+ret_id;
		new_url = new_url+'/added_by_user:'+med_id;
		
		var updated_url = "<?php echo Router::url(array('controller'=>'Pages','action'=>'admin_generategrid'));?>"+new_url;
    $('#list').setGridParam({url:updated_url});
	  reloadGrid();
});

$('#InvoiceRetailerId').change(function(){
 		 var med_id =	 $('#InvoiceMediatorId').val();
		 var ret_id =  $('#InvoiceRetailerId').val();
		 <?php if($this->Session->read('Auth.User.role_id')==2){ //if mediator?>
		 					med_id = '<?php echo $mediator_id;?>';
		 <?php }?>
		 if(ret_id==''){
				ret_id = 0;
		 }
		 if(med_id==''){
				med_id = 0;
		 }
		var new_url = '';
		new_url = '/user_id:'+ret_id;
		new_url = new_url+'/added_by_user:'+med_id;
		
		var updated_url = "<?php echo Router::url(array('controller'=>'Pages','action'=>'admin_generategrid'));?>"+new_url;
    $('#list').setGridParam({url:updated_url});
	  reloadGrid();

});


$('.clear_filer_class').click(function(){
		$('#InvoiceMediatorId').val('');
	  $('#InvoiceRetailerId').val('');
	  var new_url = '';
		new_url = '/user_id:'+0;
		new_url = new_url+'/added_by_user:<?php echo $mediator_id?>';
		var updated_url = "<?php echo Router::url(array('controller'=>'Pages','action'=>'admin_generategrid'));?>"+new_url;
    $('#list').setGridParam({url:updated_url});
	  $("#list")[0].clearToolbar();
		reloadGrid();
});

function invoice_generate(invoice_no ,file_name)
{
	if(file_name.length >0 && file_name !=0 )
	{
		var download = "<?php echo $this->Html->url(array('controller'=>'Invoices','action'=>'download'));?>/"+file_name;
   		window.location =download;
	}
	else
	{	
	   $.ajax({
		  /*beforeSend: function (XMLHttpRequest){
			 $("#loading-image").fadeIn();
		  },
		  complete: function (XMLHttpRequest, textStatus) {
			$("#loading-image").fadeOut();
		  },*/
		  url: "<?php echo Router::url(array('controller'=>'Invoices','action'=>'admin_generate_invoice'));?>", 
		  type: "POST",
		  data: ({invoice_number : invoice_no}),
		  dataType: 'html',
		  success: function(data){
			//console.log(data);
            /*var thePopup = window.open( '', "Invoice", "menubar=0,location=0,height=700,width=700" );
        	thePopup.document.write(data);
            $('#popup-content').clone().appendTo( thePopup.document.body );
			thePopup.print();
			*/
			$('#invoice_data').val(data);
			$('#invoice_number').val(invoice_no);
			$('#invoice_form_download').submit();
		  }
	  });		
	}
}

function delete_invoice(invoice_number)
{
   var ans = confirm("<?php echo __('Are you sure you want to delete this invoice ?');?>");
   if(ans)
	{
		$.ajax({
			url: "<?php echo Router::url(array('controller'=>'Invoices','action'=>'admin_delete_invoice'));?>", 
			type: "POST",
			data: ({invoice_number : invoice_number}),
			success: function(data){
				 if(data == '1')
				 {
					alert('Invoice has been deleted successfully');
				 }
				 else
				 {
					alert('Invoice could not be deleted now.');
				 }
				 reloadGrid();
			}
		});
	}
}

function changeStatus(invoice_id,st)
{
	var ans = confirm("<?php echo __('Are you sure you want to change status of this invoice ?');?>");
	var st = $('#action_status_'+invoice_id).val();
	if(ans)
	{
		$.ajax({
			url: "<?php echo Router::url(array('controller'=>'Invoices','action'=>'admin_changestatus'));?>", 
			type: "POST",
			data: ({id : invoice_id, st : st}),
			success: function(data){
				 if(data == '1')
				 {
					alert('Status has been changed successfully');
				 }
				 else
				 {
					alert('Status could not be updated now.');
				 }
				 reloadGrid();
			}
		});
	}
}
$(document).ready(function(){
   $('#custom_invoice_opt').addClass('sb_active_single_opt');
 }) ;
</script>
