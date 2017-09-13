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


.grid_table_box .row .col-md-1 div.checkbox { margin-left: -50px; }

.gird_button .row .col-md-3 button{ margin-top: 3px; background-color: #418BCA !important; border-color:#418BCA !important;}
</style>
<div>
	<div class="page_title"><?php echo $pageTitle; ?></div>
    <?php if($this->Session->read('Auth.User.role_id') == 1) {?>
    <div class="sub_title"><i class="icon-user"></i> <span class="sub_litle_m">Distributor Profile Manage</span></div>
    <?php } else {?>
    <div class="sub_title"><i class="icon-user"></i> <span class="sub_litle_m">Mediator Profile Manage</span></div>
    <?php } ?>
  <div class="row">
  	<div class="col-md-9"></div>
    <div class="col-md-3"> </div>
  </div>
 <div class="clear10"></div>
 
 
 <div class="main_subdiv">

		<div class="gird_button">
				<div class="row">

				  <div class="col-md-5"><div class="main_sub_title"><?php echo $pageTitle; ?></div></div>
				  <div class="col-md-4"></div>                
				  <div class="col-md-3"><?php echo $this->Form->button('Change Password',array('class'=>'btn btn-primary','onclick'=>'change_password();')); ?></div>
				
				</div>
		        
		</div>    
		  
		<div class="clear10"></div>
		<div align="left" class="grid_table_box">
		  <?php echo $this->Form->create('User');?>
		 
		  <div class="row">
		  		<div class="col-md-3 sb_left_pad"><?php echo __('First Name')?><sup class="MandatoryFields">*</sup></div>
			 	  <div class="col-md-6 sb_left_mar"><?php echo $this->Form->input('fname',array('type'=>'text','class' => 'form-control','div'=>false,'maxlength'=>'255', 'autofocus'=>true,'label'=>false,'required'=>'required','placeholder'=>__('First Name'))); ?>
		       <?php echo $this->Form->input('id',array('required' =>true,
							     'label'=>false,'type'=>'hidden')); ?>
		    </div>
		  </div>
		  <div class="clear10"></div>
		  
		  <div class="row">
		  		<div class="col-md-3 sb_left_pad"><?php echo __('Last Name')?><sup class="MandatoryFields">*</sup></div>
			 	  <div class="col-md-6 sb_left_mar"><?php echo $this->Form->input('lname',array('type'=>'text','class' => 'form-control','div'=>false,'maxlength'=>'255','label'=>false,'required'=>'required','placeholder'=>__('Last Name'))); ?>
		    </div>
		  </div>
		  <div class="clear10"></div>
		  
          <div class="row">
		  		<div class="col-md-3 sb_left_pad"><?php echo __('Account Number')?><sup class="MandatoryFields">*</sup></div>
			 	  <div class="col-md-6 sb_left_mar"><?php echo $this->Form->input('username',array('type'=>'text','class' => 'form-control','div'=>false,'maxlength'=>'255','label'=>false,'required'=>'required','disabled'=>true,'placeholder'=>__('Account Number'))); ?>
		    </div>
		  </div>
		  <div class="clear10"></div>
          
		  <div class="row">
		  		<div class="col-md-3 sb_left_pad"><?php echo __('Email')?><sup class="MandatoryFields"></sup></div>
			 	  <div class="col-md-6 sb_left_mar"><?php echo $this->Form->input('email',array('class' => 'form-control','div'=>false,'label'=>false,'required'=>'false','placeholder'=>__('Email'))); ?>
		    </div>
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
															'maxLength'=>12,
															'label'=>false)); ?>
</div>
		    <div class="col-md-2">&nbsp;</div>
		 </div>
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
		  <div class="col-md-2">&nbsp;</div>
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
		  <div class="col-md-2">&nbsp;</div>
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
      <div onclick='document.getElementById("fileupload").click()'  class='btn btn-info'><?php echo __('Browse');?>
      </div>
		      <div style="display: none; "><?php echo $this->Form->input('',array('type'=>'file','id'=>'fileupload','name'=>'files[]','onclick'=>'PdfContent("fileupload","bar1","progress","upload_content")','label' => false)); ?></div>
		      <div class="supported_file"><?php echo __('Supported Files : jpg-jpeg-bmp-gif-png')?></div>
		      <div id="progress" class="progress progress-success progress-striped  userprocess" ><div class="bar" id="bar1"></div></div>
		       <div class="error_image"></div>
		    </div>
		    <div class="col-md-3">
		    	&nbsp;
		    </div>
		 </div>
		<div class="clear10"></div>
		             <div class="row">
		             <div class="col-md-3">&nbsp;</div>
		             <div class="col-md-7">
								 		<?php 
										 
										if(isset($this->request->data['User']['image']))
										{
											$image = $this->request->data['User']['image'];
										}
										else
										{
											$image = 'noimage.jpg';
										}
										if(file_exists(WWW_ROOT.'img/users/'.$image) && !empty($image))
										{
										    echo $this->Html->image('users/'.$image,
											array('class'=>'','border'=>'0','div'=>true,'width'=>100,'height'=>'100','label'=>'Profile Image'));
										}
										else
										{
											
											echo $this->Html->image('users/noimage.jpg',
											     array('class'=>'','border'=>'0','div'=>true,'width'=>100,'height'=>'100','label'=>'Profile Image'));
										}
									?>	
		                                    	
		             </div>
		             <div class="col-md-2">&nbsp;</div>
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
		  		<div class="col-md-3">&nbsp;</div>
			 	  <div class="col-md-8"><?php echo $this->Form->submit('Update',array('class'=>'btn btn-primary','div'=>false,'label'=>false,'onclick'=>'return check_details();')).' '.$this->Form->button('Cancel',array('class'=>'btn btn-warning cancel','div'=>false,'label'=>false,'type'=>'button','onclick'=>'redirect_to();')); ?></div>
		  </div>
		  <div class="clear10"></div>
		  
		  
		  <?php echo $this->Form->end();?>
		  
	 </div>
	 
</div>
  
<!-- </div> -->
 <script type="text/javascript">
function change_password()
{
	var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'changepassword','admin'=>'true'));?>";
  window.location.href = url;
}

/*$('.cancel').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'dashboard','admin'=>'true'));?>";
    window.location.href = url;
	//history.go(-1);
});*/

function redirect_to()
{
	var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'dashboard','admin'=>'true'));?>";
    window.location.href = url;
}

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
                        	$('.error_image').html('');
                        	$("#"+upload_content).val(file.name);
						    //$('#upload_path').val(file.url);
						    var file_size = getReadableFileSizeString(file.size); 
						    $('#'+bar_id).text(file.name+'( '+file_size+' )');
                        }
						
					});
				},

				fail:function (e, data) {
				     
				    alert("Test"); 
				    // data.errorThrown
				    // data.textStatus;
				    // data.jqXHR;
				},

				progressall: function (e, data) {
					//alert("Test2");
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

function check_details()
{
  var answer =   $('#UserSecurityAnswer').val();
  var ques_id =    $('#UserSecurityQuestionId').val();
  var flag = 0;
  
  
  answer = answer.trim();
  ques_id = ques_id.trim();
  

  //alert(answer+" "+ques_id);
  if(ques_id.length != 0 ){
	  if(answer.length == 0)
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
  	 if(answer.length != 0)
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