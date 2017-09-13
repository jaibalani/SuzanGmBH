<style>
.checkbox_bootstrap {
    float: left;
    width: 250px;
}
.btn{
background-color: unset !important;
background-image:none !important;
}
.btn-info{
background-color: #5bc0de !important;
border-color: #46b8da !important;
color: #fff;
}
.btn-primary{
background-color: #337ab7 !important;
border-color: #2e6da4 !important;
color: #fff !important;
margin-left: -50px;
}
.error_image{
  float: left;;
  width: 100%;
  font-size: 12px;
  color: #F00;
  margin-top: -15px;
}
</style>
<?php
    echo $this->Html->script('upload/jquery.ui.widget.js'); 
    echo $this->Html->script('upload/jquery.fileupload.js'); 
    echo $this->Html->css('upload/jquery.fileupload-ui.css'); 
    echo $this->Html->css('upload/bootstrap.min.css'); 
    echo $this->Html->script('upload/jquery.fileupload-process.js'); 
    echo $this->Html->script('upload/jquery.fileupload-validate.js');
?>
<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
    <div class="sub_title"><i class="icon-list-alt home_icon"></i> <span class="sub_litle_m">Product Master</span> <i class="icon-angle-right home_icon"></i> <span class="sub_litle_m">Card Management</span> <i class="icon-angle-right home_icon"></i> <span>Import Pins</span></div>
  </div>
</div>

<div class="main_subdiv">
	<div class="gird_button">
	        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
	</div>    
	  
	<div class="clear10"></div>
	
	<div align="left" class="grid_table_box">
			<?php echo $this->Form->create('Pin',array('type' => 'file')); ?>
			<div class="clear10"></div>
			<div class="row">
			  <div class="col-md-3 sb_left_pad"><?php echo __('Main Category')?><sup class="MandatoryFields">*</sup></div>
			  <div class="col-md-6 sb_left_mar">
			  	<?php echo $this->Form->input('cat_id',array(
												  'label' 	 => false, 
												  'required' => true, 
												  'id' 	 => 'cat_id',
												  'default'	 => '',
												  'empty' 	 => '--- Select Category---',
												  'options'  => $catList,
		  										  'value' =>@$main_cat,
												  'disabled' => TRUE,
												  //'selected' =>(isset($parent_details['Parent']['cat_id']) ? $parent_details['Parent']['cat_id'] : 0),
		  										  'class'=>'form-control select_change'
								  ))?>
			   
			  </div>
			  <div class="col-md-2">&nbsp;</div>
			</div>
			
			<div class="clear10"></div>
			<div class="row">
			  <div class="col-md-3 sb_left_pad"><?php echo __('Sub Category')?><sup class="MandatoryFields">*</sup></div>
			  <div class="col-md-6 sb_left_mar">
			  	<?php echo $this->Form->input('Card.sub_cat_id',array(
												  'label' 	 => false, 
												  'required' => true, 
												  'id' 	 => 'sub_cat_id',
												  'default'	 => '',
												  'options'  => $subCatList,
 		  										  'value' =>@$sub_cat,
 												  'disabled' => TRUE,
												  'empty' 	 => '--- Select Sub Category---',
												  'class' => ' form-control select_change',
								  ))?>
                   <?php 
				   			echo $this->Form->input('PinsCard.pc_c_id_hidden',array(
												  'value' =>@$card_id,
												  'type'=>'hidden'
 								  ));
				   ?>
			  </div>
		    <div class="col-md-2">&nbsp;</div>
			</div>
			
			<div class="clear10"></div>
				<div class="row">
			        <div class="col-md-3 sb_left_pad"><?php echo __('Select Card')?><sup class="MandatoryFields">*</sup></div>
			        <div class="col-md-6 sb_left_mar"> <?php  
						echo $this->Form->input("PinsCard.pc_c_id", array('empty' 	 => '--- Select card ---',
							"div"=>false,
							 "label"=>false, 
							"class"=>"form-control select_change",
							"options"=>$CardType,
							"value"=>@$card_id,
							"disabled"=>TRUE,
							"required"=>true,
							 /*'multiple' => 'multiple',*/
							'hiddenField'=>false))	
						?>
			        </div>
			       </div>
			       <div class="clear10"></div>

			<div class="row">
			    <div class="col-md-3 sb_left_pad"><?php echo __('Upload Excel')?><sup class="MandatoryFields">*</sup></div>
			    <div class="col-md-6 sb_left_mar">
			        <?php //echo $this->Form->input('excel',array('label' => false,'type' => 'file', 'required' => 'required', 'class' => '')); ?>
              <?php echo $this->Form->input('excel',array(
																'id'=>'upload_content',
																'placeholder'=>__('Upload Excel'),
																'required' =>'required','readonly'=>'readonly',
																'onclick'=>'document.getElementById("fileupload").click()',
																'label'=>false)); ?>
              <div style="display: none; ">
				<?php echo $this->Form->input('',array('type'=>'file',
										'id'=>'fileupload',
										'name'=>'files[]',
										'onclick'=>'ExcelContent("fileupload","bar1","progress","upload_content")',
										'label' => false)); ?>
                          	</div>
							 <?php echo __('Supported Files : xls-xlsx')?>
                             <div id="progress" class="progress progress-success progress-striped  userprocess" style="width:489px;"><div class="bar" id="bar1"></div></div>
                             <div class="error_image"></div>
			    </div>
			    <div class="col-md-3">
			    	&nbsp;
			    </div>
			 </div>
			<div class="clear10"></div>
			
			<?php if(isset($download_file)) { ?>
			 <div class="row">
			        <div class="col-md-3 sb_left_pad"><?php echo __('Duplicate Pins')?><sup class="MandatoryFields"></sup></div>
			        <div class="col-md-6 sb_left_mar">  
						<a style="color:#0000FF;text-decoration:none;" href="<?=$this->html->url(array('controller'=>'Pins', 'action'=>'admin_download_duplicate_pins'))?>">
                        <?php echo __('Download Excel');?>
                        </a>
			        </div>
			</div>
			<div class="clear10"></div>       
			<?php } ?>

			<div class="row submitclass">
				  <div class="col-md-3 sb_left_pad">&nbsp;</div>
				  <div class="col-md-3 sb_left_mar">
				  <?php echo $this->Form->submit('Update', array('type'=>'button', 'class'=>'btn btn-primary','label'=>false,'id'=>'submit_btn','div'=>false)); ?>
				    <?php 
				     	echo $this->Form->button(__('Cancel'), array('type' => 'button',
				     		'class'=>'btn btn-warning cancel',
							'label'=>false,
							'style'=>'cursor:pointer;'));
					?>
			    </div> 
				</div>	
					
				<div class="clear10"></div>
			
			<?php echo $this->Form->end(); ?>
			
		</div>
		
</div>
			
<script type="text/javascript">
function ExcelContent(file_upload,bar_id,progress_id,upload_content)
{
      var content= $('#'+upload_content).val();
      var url = "<?=$this->Html->url(array('controller' => 'Pins','action' => 'contentUpload'))?>";
			$('#'+file_upload).fileupload({
				url: url,
				dataType: 'json',
				maxFileSize: 20971520, // 20 MB
				loadImageMaxFileSize: 15728640, // 15MB
				//acceptFileTypes: /(\.|\/)(xls|xlsx)$/i,
				error: "Image must be in JPG format",
				done: function (e, data) {
				 		$.each(data.result.files, function (index, file) {				  
                         if(typeof file.error != 'undefined')
			              {
			                  //alert(file.error);
			                  //alert(file.type);
			                  $("#"+upload_content).val(content);
			                  var progress = 0;
			                  $('#progress .bar').css(
			                                  'width',
			                                   progress + '%'
			                              );
			                  $('.error_image').html(file.error);
			                      
			              }
			              else
			              {
			              	$("#"+upload_content).val(file.name);
							var file_size = getReadableFileSizeString(file.size); 
							$('#'+bar_id).text(file.name+'( '+file_size+' )');
							$('.error_image').html('File upload successfully..');
							if(content){
	              		    $.ajax({
									beforeSend: function (XMLHttpRequest) {
										 $("#loading-image").fadeIn();
									},
									complete: function (XMLHttpRequest, textStatus) {
										$("#loading-image").fadeOut();
									},
									dataType: "html",
									type: "POST",
									evalScripts: true,
									url: "<?php echo $this->Html->Url(array('controller'=>'Users','action'=>'unlink_file_pins'));?>",
									data: ({file_name:content}),
									success: function (data)
									{
										
									}
								});
							 }
			              }  
					});
				},
				progressall: function (e, data) {
					var progress = parseInt(data.loaded / data.total * 100, 10);
					var p=progress_id;
					if(p=='progress'){
					$('#progress .bar').css(
				  	'width',
						 progress + '%'
					);}
				}
			});
}

function getReadableFileSizeString(fileSizeInBytes)
{
    var i = -1;
    var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
    do {
        fileSizeInBytes = fileSizeInBytes / 1024;
        i++;
    } while (fileSizeInBytes > 1024);

    return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
}

$('#submit_btn').click(function(){

     var control = $("#upload_content").val();
	 if(control==''){
			alert('Please upload pin excel file');
	 }
	 else
	 {			
    	 	$("#loading-image").fadeIn();
			$('#PinAdminAddForm').submit();
     } 
}); 

$(document).ready(function(){
	$("#loading-image").fadeOut();
    $('#cat_id').unbind('change'); 
	$('#sub_cat_id').unbind('change'); 
	$('#PinsCardPcCId').unbind('change'); 
	
	$('#cat_id').change(function(){
		$.ajax({
			beforeSend: function (XMLHttpRequest) {
				 $("#loading-image").fadeIn();
			},
			complete: function (XMLHttpRequest, textStatus) {
				$("#loading-image").fadeOut();
			},
			url: "<?php echo Router::url(array('controller'=>'cards','action'=>'get_subcat'));?>", 
			type: "POST",
			data: ({id : $(this).val()}),
			dataType: 'json',
			success: function(json){
				$('#sub_cat_id').html('');
				$('#sub_cat_id').html('<option>--- Select Sub Category---</option>');
				$.each(json, function(i, value) {
					$('#sub_cat_id').append($('<option>').text(value).attr('value', i));
		        });
			}
		});
	});

	$('#sub_cat_id').change(function(){
		$.ajax({
			beforeSend: function (XMLHttpRequest) {
				 $("#loading-image").fadeIn();
			},
			complete: function (XMLHttpRequest, textStatus) {
				$("#loading-image").fadeOut();
			},
			url: "<?php echo Router::url(array('controller'=>'pins','action'=>'get_card'));?>", 
			type: "POST",
			data: ({id : $(this).val()}),
			dataType: 'json',
			success: function(json){
				$('#PinsCardPcCId').html('');
				$('#PinsCardPcCId').html('<option>--- Select Card ---</option>');
				$.each(json, function(i, value) {
					$('#PinsCardPcCId').append($('<option>').text(value).attr('value', i));
		        });
			}
		});
	});

	$('.cancel').click(function(){
		
		var url = "<?php //echo $this->Html->url(array('controller'=>'Pins','action'=>'index',$card_id,'admin'=>'true'));?>";
		var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'index','admin'=>'true'));?>";
		
		window.location.href = url;
		
		//history.go(-1);
	});
	
	$('[class^="lbl_"]').change(function(){
		var parent_id = $(this).attr('class');
		if($(this).is(":checked")){
			$('#'+parent_id).prop('checked', true);
		}
	});
	$('[id^="lbl_"]').change(function(){
		//changle in parent category
		var child_class = $(this).attr('id');
		if($(this).is(":checked")){
			//if parent is checked, all its child will also be checked
			$('.'+child_class).each(function(){
				$(this).prop('checked', true);
			});
		}else{
			//if parent is unchecked
			parent_shud_be_checked = 0;
			$('.'+child_class).each(function(){
				if($(this).is(":checked")){
					parent_shud_be_checked = 1;	
				}
			});
			if(parent_shud_be_checked){
				$(this).prop('checked', true);
			}
		}
	});
	$('.submitclass').show();
});
$(document).ready(function(){
	   $('#product').addClass('sb_active_opt');
	   $('#product').removeClass('has_submenu');
	   $('#addcard_active').addClass('sb_active_subopt_active');
	}) ;
</script>