<?php
    echo $this->Html->script('upload/jquery.ui.widget.js'); 
    echo $this->Html->script('upload/jquery.fileupload.js'); 
    echo $this->Html->css('upload/jquery.fileupload-ui.css'); 
    echo $this->Html->css('upload/bootstrap.min.css'); 
    echo $this->Html->script('upload/jquery.fileupload-process.js'); 
    echo $this->Html->script('upload/jquery.fileupload-validate.js');
  
?>
<style type="text/css">
.btn{
background-color: #E0E0E0 !important;
background-image:none !important;
}
.error_image{
    float: left;;
    width: 100%;
    font-size: 12px;
    color: #F00;
    margin-top: -15px;
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
</style>

<?php

   if($this->Session->read('Auth.User.role_id') == 1)
   {
   	  $manage = "Mediator";
   	  $view_data ="Mediator Personal Data";
   }
   else
   {
   	  $manage = "Retailer";
   	  $view_data ="Manage Retailer";
   }
   
?>
<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
     <div class="sub_title"><i class="icon-user home_icon"></i> <span class="sub_litle_m"><?php echo $manage;?></span> <i class="icon-angle-right home_icon"></i> <span class="sub_litle_m"><?php echo $view_data;?></span><span><i class="icon-angle-right home_icon"></i> <span>Add</span></div>
  </div>
</div>

<div class="main_subdiv">

		<div class="gird_button">
		        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
		</div>    
		  
		<div class="clear10"></div>
		<div align="left" class="grid_table_box">
		
			<?php echo $this->Form->create('User'); ?>
			<div class="clear10"></div>
			<div class="row">
			  <div class="col-md-3 sb_left_pad"><?php echo __('First Name')?><sup class="MandatoryFields">*</sup></div>
			  <div class="col-md-6 sb_left_mar">
			      <?php echo $this->Form->input('fname',array('class'=>'form-control',
			                                                'placeholder'=>__('First Name'),
			                                                'required' =>true,
			                                                'label'=>false)); ?>
			  </div>
			  <div class="col-md-3">&nbsp;</div>
			</div>
			
			<div class="clear10"></div>
			<div class="row">
			  <div class="col-md-3 sb_left_pad"><?php echo __('Last Name')?><sup class="MandatoryFields">*</sup></div>
			  <div class="col-md-6 sb_left_mar">
			      <?php echo $this->Form->input('lname',array('class'=>'form-control',
			                                                'placeholder'=>__('Last Name'),
			                                                'required' =>true,
			                                                'label'=>false)); ?>
			  </div>
			  <div class="col-md-3">&nbsp;</div>
			</div>
			
            <div class="clear10"></div>
			<div class="row">
			  <div class="col-md-3 sb_left_pad"><?php echo __('Account Number')?><sup class="MandatoryFields">*</sup></div>
			  <div class="col-md-6 sb_left_mar">
			      <?php echo $this->Form->input('username',array('class'=>'form-control',
			                                                'placeholder'=>__('Account Number'),
			                                                'required' =>true,
			                                                'label'=>false,
			                                                'value'=>$user_name,
			                                                'disabled'=>true
			                                                )); ?>
			  </div>
			  <div class="col-md-3">&nbsp;</div>
			</div>
            
            
			<div class="clear10"></div>
			<div class="row">
			  <div class="col-md-3 sb_left_pad"><?php echo __('Email')?><sup class="MandatoryFields"></sup></div>
			  <div class="col-md-6 sb_left_mar">
			     <?php echo $this->Form->input('email',array('class'=>'form-control',
															'placeholder'=>__('Email'),
															'required' =>false,
															'type'	=> 'email',
															'label'=>false,'value'=>@$email)); ?>
			  </div>
			  <div class="col-md-3">&nbsp;</div>
			</div>
			
			<div class="clear10"></div>
			<div class="row">
			  <div class="col-md-3 sb_left_pad"><?php echo __('Password')?><sup class="MandatoryFields">*</sup></div>
			  <div class="col-md-6 sb_left_mar">
			     <?php echo $this->Form->input('password',array('class'=>'form-control',
																'placeholder'=>__('Password'),
																'required' =>true,
																'type' => 'password',
																'label'=>false)); ?>
</div>
			  <div class="col-md-3">&nbsp;</div>
			</div>
			
			<div class="clear10"></div>
			<div class="row">
			  <div class="col-md-3 sb_left_pad"><?php echo __('Confirm Password')?><sup class="MandatoryFields">*</sup></div>
			  <div class="col-md-6 sb_left_mar">
<?php echo $this->Form->input('confirm_password',array('class'=>'form-control',
														'placeholder'=>__('Confirm Password'),
														'required' =>true,'type'=>'password',
														'label'=>false)); ?>
			  </div>
			  <div class="col-md-3">&nbsp;</div>
			</div>
			<div class="clear10"></div>
			
			<div class="row">
			    <div class="col-md-3 sb_left_pad"><?php echo __('Contact Number')?></div>
			    <div class="col-md-6 sb_left_mar">
			        <?php echo $this->Form->input('phone',array('class'=>'form-control',
											'placeholder'=>__('Contact Number'),
											'required' =>false,
											'onkeypress'=>'return isNumber(event);',
											'minLength'=>10,
											'maxLength'=>15,
											'label'=>false)); ?>
			    </div>
			    <div class="col-md-3">&nbsp;</div>
			 </div>
			<div class="clear10"></div>
			
			<?php if($this->Session->read('Auth.User.role_id') == 2 || $this->Session->read('Auth.User.role_id') == 1) {?>
			<div class="clear10"></div>
			<div class="row">
			    <div class="col-md-3 sb_left_pad"><?php echo __('Minimum Balance') . "(&euro;)";?><sup class="MandatoryFields"></sup></div>
			    <div class="col-md-6 sb_left_mar">
			        <?php echo $this->Form->input('minimum_balance',array('class'=>'form-control amount_validation',
																		'placeholder'=>__('Minimum Balance'),
																		'required' =>false,
																		'type'=>'text',
																		'maxLength'=>10,
																		'label'=>false)); ?>
			    </div>
			    <div class="col-md-3">&nbsp;</div>
			 </div>
            
			<?php if($this->Session->read('Auth.User.role_id') == 2){ ?>
			<div class="clear10"></div>

             <div class="row">
			    <div class="col-md-3 sb_left_pad"><?php echo __('Alert Purchase Limit') . "(&euro;)";?><sup class="MandatoryFields"></sup></div>
			    <div class="col-md-6 sb_left_mar">
			        <?php echo $this->Form->input('purchase_limit',array('class'=>'form-control amount_validation',
																		'placeholder'=>__('Alert Purchase Limit'),
																		'required' =>false,
																		'type'=>'text',
																		'maxLength'=>10,
																		'label'=>false)); ?>
			    </div>
			    <div class="col-md-3">&nbsp;</div>
			 </div>

            <div class="row">
			  <div class="col-md-3 sb_left_pad"><?php echo __('Allow Credit')?></div>
			   <div class="col-md-1 sb_checkbox">
			       <?php echo $this->Form->input('allow_credit_check',array('class'=>'',
																		'hiddenField' => false,
																		'title'=>__('Check it for maximum allowed credit below 0'),
																		'type' =>'checkbox',
																		'label'=>false)); ?>
			    </div>
			    <div class="col-md-3">&nbsp;</div>
			 </div>
			<div class="clear10"></div>
			<div class="row" >
			    <div class="col-md-3 sb_left_pad"></div>
			    <div class="col-md-6 sb_left_mar">
			        <?php echo $this->Form->input('allow_credit',array('class'=>'form-control amount_validation',
																'placeholder'=>__('Maximum Allowed Credit Below 0'),
																'required' =>true,
																'maxLength'=>10,
																'label'=>false)); ?>
			    </div>
			    <div class="col-md-3">&nbsp;</div>
			 </div>
			<?php } } ?> 
			<div class="clear10"></div>
			
			
			<div class="row">
			  <div class="col-md-3 sb_left_pad"><?php echo __('Country')?></div>
			  <div class="col-md-6 sb_left_mar">
			    <?php echo $this->Form->input('country_code', array('type' => 'button',
			                                              'class'=>'form-control',
															'type'=>'select',
															'options'=>$set_countries,
															'empty'=>__('Select Country'),
															'label'=>false,
          													'style'=>'cursor:pointer;'));?>
			  </div>
			  <div class="col-md-3">&nbsp;</div>
			</div>
			<div class="clear10"></div>
			
			<div class="row">
			  <div class="col-md-3 sb_left_pad"><?php echo __('Address')?></div>
			  <div class="col-md-6 sb_left_mar">
			      <?php echo $this->Form->input('address',array('class'=>'form-control',
																'placeholder'=>__('Address'),
																'type'=>'textarea',
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
													'placeholder'=>__('Profile Image'),
													'required' =>false,'readonly'=>'readonly',
													'div'=>false,
													'label'=>false)); ?>
		      <div onclick='document.getElementById("fileupload").click()'  class='btn btn-info'><?php echo __('Browse');?></div>
		      <div style="display: none; "><?php echo $this->Form->input('',array('type'=>'file','id'=>'fileupload','name'=>'files[]','onclick'=>'PdfContent("fileupload","bar1","progress","upload_content")','label' => false)); ?></div>
		      <div class="supported_file"><?php echo __('Supported Files : jpg-jpeg-bmp-gif-png')?></div>
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
		 		  <div class='row'>
		 		  <div class="col-md-3 sb_left_pad"><?php echo __('Security Question') ?></div>
		            <div class="col-md-6 sb_left_mar">
		              <?php
		              echo $this->Form->input('security_question_id', array(
		              'label' => false,
		              'class' => 'form-control selectbox_graditent',
		              'type' => 'select',
		              'required' => false,
		              'empty'=>__('Select Question'),
		              'value' => @$questions_selected,
		              'options' => @$questions,
		              ));
		              ?>
		            </div>
          		</div>
          		<div class='row'>
	              <div class="col-md-3 sb_left_pad">
	                    <label></label>
	              </div>
              		<div class="col-lg-6 error_ques" style="text-align:left;color:#F00;margin-left:-51px;"></div>  
           		</div>
			<div class="clear10"></div>
          	<div class='row'>      
          	  <div class="col-md-3 sb_left_pad"><?php echo __('Answer') ?></div>  
              <div class="col-lg-6 sb_left_mar">
               <?php 
                    echo $this->Form->input('security_answer',array('label'=>false,
                    											'class'=>'form-control',
                                                              'required'=>'false',
                                                              'placeholder'=>__('Answer'),
                                            )); 
               ?>
              </div>  
           </div>
            <div class='row'>
              <div class="col-md-3 sb_left_pad">
                    <label></label>
              </div>
              <div class="col-lg-6 error_answer" style="text-align:left;color:#F00;margin-left:-51px;"></div>  
           </div>
         <div class="clear10"></div>
         
		 <div class="row">
			  <div class="col-md-3 sb_left_pad">&nbsp;</div>
			  <div class="col-md-3 sb_left_mar"><?php echo $this->Form->submit('Save', array('type'=>'submit', 'class'=>'btn btn-primary','label'=>false,'id'=>'submit_btn','div'=>false,'onclick'=>'return check_details();')); ?>
			 <?php echo $this->Form->button(__('Cancel'), array('type' => 'button',
																'class'=>'btn btn-warning cancel',
																'label'=>false,
																'div'=>false));?>
			  </div> 
			</div>	
			  
			<div class="clear10"></div>
			
			<?php echo $this->Form->end();?>
		</div>

</div>

<script type="text/javascript">

$(document).ready(function(){
	if($('#UserAllowCreditCheck').is(':checked'))
	{
		$('#UserAllowCredit').attr("disabled",false) ;
	}
	else
	{
		$('#UserAllowCredit').attr("disabled","disabled") ;
	}
	var upload_file = "<?php echo @$this->request->data['User']['image']?>";
	$('#upload_content').val(upload_file);
});

$('#UserAllowCreditCheck').click(function(){
            
	if($(this).prop('checked'))
	 {
			$('#UserAllowCredit').attr("disabled",false) 
	 }
	 else
	 {
		$('#UserAllowCredit').attr("disabled","disabled") ;
	 }

});

$('.cancel').click(function(){
	var role_id = "<?php echo $role_id; ?>";
	if(role_id == 1)
	{
		var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'manage_mediator','admin'=>'true'));?>";
	}
	else
	{
		var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'manage_retailer','admin'=>'true'));?>";
	}

  window.location.href = url;
});

function PdfContent(file_upload,bar_id,progress_id,upload_content)
{
      var content= $('#'+upload_content).val();
      var url = "<?=$this->Html->url(array('controller' => 'Users','action' => 'contentUpload'))?>";
			$('#'+file_upload).fileupload({
				url: url,
				dataType: 'json',
				maxFileSize: 20971520, // 20 MB
				loadImageMaxFileSize: 15728640, // 15MB
				//acceptFileTypes: /(\.|\/)(jpg|jpeg|bmp|gif|png)$/i,
				done: function (e, data) {
				 		$.each(data.result.files, function (index, file) {				  
						//alert(file.url);
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
							//$('#upload_path').val(file.url);
							var file_size = getReadableFileSizeString(file.size); 
							$('#'+bar_id).text(file.name+'( '+file_size+' )');
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
										url: "<?php echo $this->Html->Url(array('controller'=>'Users','action'=>'unlink_add_file'));?>",
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

$(document).ready(function(){
   $('#Mediator').addClass('sb_active_opt');
   $('#Mediator').removeClass('has_submenu');
   $('#mediator_active').addClass('sb_active_subopt_active');
   $('#retailer').addClass('sb_active_opt');
   $('#retailer').removeClass('has_submenu');
   $('#manage_retailer').addClass('sb_active_subopt_active');
}) ;

  
function check_details()
{
  var answer =   $('#UserSecurityAnswer').val();
  var ques_id =    $('#UserSecurityQuestionId').val();
  var flag = 0;
  
  if(ques_id.length != 0 ){
	  if(answer.trim().length == 0)
      {
         $('.error_answer').html("<?php echo __('Enter answer for security question.');?>");
         $('.error_ques').html("");
         flag = 1;
      }else{
      	 $('.error_answer').html("");
      	 $('.error_ques').html("");
      }
  }else{
  	$('.error_answer').html("");
  	 if(answer.trim().length != 0)
      {
         $('.error_ques').html("<?php echo __('Please select security question.');?>");
         $('.error_answer').html("");
         flag = 1;
      }else{
      	 $('.error_ques').html("");
      }
  } 

  if(flag == 0 )
  {
     
     return true;
  }
  else
  {
     return false;
  }
}
</script>