<?php
    echo $this->Html->script('upload/jquery.ui.widget.js'); 
    echo $this->Html->script('upload/jquery.fileupload.js'); 
    echo $this->Html->css('upload/jquery.fileupload-ui.css'); 
    echo $this->Html->css('upload/bootstrap.min.css'); 
    echo $this->Html->script('upload/jquery.fileupload-process.js'); 
    echo $this->Html->script('upload/jquery.fileupload-validate.js');
  
?>
<style type="text/css">
.hidden_credit{
display: none;
}
.error_image{
    float: left;;
    width: 100%;
    font-size: 12px;
    color: #F00;
    margin-top: -15px;
  }  
.btn{
background-color: #E0E0E0 !important;
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
margin-left: 16px !important;
}


.form-control{ border-radius:0px; /*color: #C2C8BC; */}

.grid_table_box .row .col-md-1 div.checkbox{ margin-left: -50px; }

</style>
<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
    <div class="sub_title"><i class="icon-user home_icon"></i> <span class="sub_litle_m"><?php echo __('Manage Front Images');?></span> <i class="icon-angle-right home_icon"></i> <span class="sub_litle_m"><?php echo __('Edit Front Image');?></span></div>
  </div>
</div>

<div class="main_subdiv">
	<div class="gird_button">
	        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
	</div>    
	  
	<div class="clear10"></div>
	
	<div align="left" class="grid_table_box">

		<?php echo $this->Form->create('FrontImage'); ?>
		<div class="clear10"></div>
		
		<div class="row">
		    <div class="col-md-3 sb_left_pad"><?php echo __('Content English')?><sup class="MandatoryFields">*</sup></div>
		    <div class="col-md-6 sb_left_mar">
		        <?php echo $this->Form->input('id',array('type'=>'hidden',
																	'required' =>true,
																	 'label'=>false)); ?>
		        <?php echo $this->Form->input('content_english',array('class'=>'form-control',
																	'placeholder'=>__('Content English'),
																	'required' =>true,
																	'maxLength'=>80,
																	 'label'=>false)); ?>
		    </div>
		    <div class="col-md-3">&nbsp;</div>
		 </div>
		<div class="clear10"></div>
        
        <div class="row">
		    <div class="col-md-3 sb_left_pad"><?php echo __('Content German')?><sup class="MandatoryFields">*</sup></div>
		    <div class="col-md-6 sb_left_mar">
		        <?php echo $this->Form->input('content_german',array('class'=>'form-control',
																	'placeholder'=>__('Content German'),
																	'required' =>true,
																	'maxLength'=>80,
																	'label'=>false)); ?>
		    </div>
		    <div class="col-md-3">&nbsp;</div>
		 </div>
    	  <div class="clear10"></div>
		
		<div class="row">
		    <div class="col-md-3 sb_left_pad"><?php echo __('Image')?></div>
		    <div class="col-md-6 sb_left_mar">
		        <?php echo $this->Form->input('image',array('class'=>'',
															'id'=>'upload_content',
															'placeholder'=>__('Front Image'),
															'required' =>false,'readonly'=>'readonly',
															'div'=>false,
															'label'=>false)); ?>
		      <div onclick='document.getElementById("fileupload").click()'  class='btn btn-info'><?php echo __('Browse');?></div>
   		      <div onclick='reset_image()'  class='btn btn-info'><?php echo __('Reset Default');?></div>
		      <div style="display: none;">
				  <?php echo $this->Form->input('',array('type'=>'file',
                                                        'id'=>'fileupload',
                                                        'name'=>'files[]',
                                                        'onclick'=>'PdfContent("fileupload","bar1","progress","upload_content")',
                                                        'label' => false)); ?>
              </div>
		      <div class="supported_file"><?php echo __('Supported Files : jpg-jpeg-gif-png')?></div>
		     
		      <div id="progress" class="progress progress-success progress-striped userprocess">
		          	<div class="bar" id="bar1">
		            </div>
		      </div>
	          <div class="error_image"></div>
		    </div>
		    <div class="col-md-3">
		    	&nbsp;
		    </div>
		 </div>
		<div class="clear10"></div>
			       <div class="row">
		             <div class="col-md-3 sb_left_pad">&nbsp;</div>
		             <div class="col-md-6 sb_left_mar img_upload">
								 		<?php  
										if(isset($this->request->data['FrontImage']['image']))
										$image = $this->request->data['FrontImage']['image'];

										if(file_exists(WWW_ROOT.'img/front_images/'.$image) && !empty($image))
										{
										    echo $this->Html->image('front_images/'.$image,
											array('class'=>'','border'=>'0','div'=>true,'width'=>200,'height'=>'100'));
										}
										else
										{
											echo $this->Html->image('users/noimage.jpg',
										    array('class'=>'','border'=>'0','div'=>true,'width'=>200,'height'=>'100','label'=>'Front Image'));
										}
										?>		
		             </div>
		             <div class="col-md-3">&nbsp;</div>
		          </div>
		          <div class="clear10"></div>
		 
		 <div class="row">
			  <div class="col-md-2">&nbsp;</div>
			  <div class="col-md-3 sb_left_pad"><?php echo $this->Form->submit('Update', array('type'=>'submit', 
			  																	'class'=>'btn btn-primary',
																				'label'=>false,
																				'id'=>'submit_btn',
																				'div'=>false)); ?>
		     <?php echo $this->Form->button(__('Cancel'), array('type' => 'button',
																'class'=>'btn btn-warning cancel',
																'label'=>false,
																'onclick'=>'redirect();',
																'style'=>'cursor:pointer;'));?>
		    </div> 
			</div>	
				
			<div class="clear10"></div>
		
		<?php echo $this->Form->end(); ?>
		
	</div>
		
</div>		
		
<script type="text/javascript">
function redirect()
{
   var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'front_image','admin'=>'true'));?>";
   window.location.href = url;
	//history.go(-1);
}


function PdfContent(file_upload,bar_id,progress_id,upload_content)
{
      var content= $('#'+upload_content).val();
      var id = "<?php echo $this->request->data['FrontImage']['id'];?>";
      
	  var url = "<?=$this->Html->url(array('controller' => 'Users','action' => 'contentUpload_front_image'))?>";
			$('#'+file_upload).fileupload({
				url: url,
				dataType: 'json',
				maxFileSize: 20971520, // 20 MB
				loadImageMaxFileSize: 15728640, // 15MB
				//acceptFileTypes: /(\.|\/)(jpg|jpeg||gif|png)$/i,
				done: function (e, data) {
					   $.each(data.result.files, function (index, file) {				  
						


 						if(typeof file.error != 'undefined')
                        {
                            //alert(file.error);
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
                        	//alert(file.url);
							$("#"+upload_content).val(file.name);
							$('.error_image').html('');
							//$('#upload_path').val(file.url);
							var file_size = getReadableFileSizeString(file.size); 
							$('#'+bar_id).text(file.name+'( '+file_size+' )');
							if(content && content != "bg1.png" && content != "bg2.png" && content != "bg3.png" && content != "bg4.png")
							{
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
										url: "<?php echo $this->Html->Url(array('controller'=>'Users','action'=>'unlink_file'));?>",
										data: ({file_name:content,id:id}),
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
//var BASE_URL = "<?php echo WWW_ROOT?>";
$(document).ready(function(){
   $('#manage-front-image').addClass('sb_active_single_opt');
}) ;

function reset_image()
{
  var id = "<?php echo $this->request->data['FrontImage']['id'];?>";
  var current = $('#upload_content').val();
  
  if(current && current != "bg1.png" && current != "bg2.png" && current != "bg3.png" && current != "bg4.png")
  {
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
				url: "<?php echo $this->Html->Url(array('controller'=>'Users','action'=>'unlink_file'));?>",
				data: ({file_name:current,id:id}),
				success: function (data)
				{
				   
				   $('.error_image').html('');	
                   $('#upload_content').val("");
					$('#bar1').text("");
					var progress = 0;
						$('#progress .bar').css(
										'width',
										 progress + '%'
									);
					var default_image = '';
					if(id == 1)
					default_image = "bg1.png";
					else   if(id == 2)
					default_image = "bg2.png";
					else   if(id == 3)
					default_image = "bg3.png";
					else   if(id == 4)
					default_image = "bg4.png";
					$('#upload_content').val(default_image);
					$('.img_upload').html('');
		$('.img_upload').html('<img name="delete_task" src="<?php echo IMAGE_PATH?>img/front_images/'+default_image+'" alt="Default Image" width="200" height="100" class="pointer"/>');
				}
		});
		
	 }
  var default_image = '';
  if(id == 1)
  default_image = "bg1.png";
  else   if(id == 2)
  default_image = "bg2.png";
  else   if(id == 3)
  default_image = "bg3.png";
  else   if(id == 4)
  default_image = "bg4.png";
  $('#upload_content').val(default_image);
  }

</script>