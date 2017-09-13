<?php
    echo $this->Html->script('upload/jquery.ui.widget.js'); 
    echo $this->Html->script('upload/jquery.fileupload.js'); 
    echo $this->Html->css('upload/jquery.fileupload-ui.css'); 
    echo $this->Html->css('upload/bootstrap.min.css'); 
    echo $this->Html->script('upload/jquery.fileupload-process.js'); 
    echo $this->Html->script('upload/jquery.fileupload-validate.js');
?>
<style type="text/css">
#edit-profile form div.input-fields label{
	float:left;
}
.error-message{
	padding-left:138px;
}
#error_answer, #error_question{
font-size:12px;
color: #F00;
}
.error_image{
  float: left;;
  width: 100%;
  font-size: 12px;
  color: #F00;
  margin-top: -15px;
}
</style>
<div class = "right-part right-panel">
     
     <div class = "sb-page-title">
          <?php echo __('Users')?>
     </div>
     
     <div id="user-details">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="home_tab" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><?php echo __('PRIMARY ACCOUNT DETAILS');?></a></li>
            <li role="presentation" class="profile_tab"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><?php echo __('EDIT ACCOUNT DETAILS');?></a></li>
       </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">
                <div id="view-profile"> 

                    <div class="info">
                       <strong><?php echo __('Name');?>:</strong> <br/> 
                       <?php echo ucwords($this->request->data['User']['fname']." ".$this->request->data['User']['lname']);?>
                    </div>

                    <div class="info">
                        <strong><?php echo __('Account Number');?>:</strong> <br/> 
                        <?php echo $this->request->data['User']['username'];?>
                    </div>
                    
                    <div class="info">
                        <strong><?php echo __('Email ID');?>:</strong> <br/> 
                        
                        <?php 
                        $email_new = $this->request->data['User']['email'];
                        if(empty($email_new))
                        $email_new = "-"; 
                        
                        echo $email_new;?>
                    </div>
                    
                    <div class="info">
                        <strong><?php echo __('Address');?>:</strong> <br/>
                         <?php 
						 if($this->request->data['User']['address'])
						 echo nl2br($this->request->data['User']['address']);
						 else
						 echo "-"; 
						 ?>
                    </div>
                    
<!--                    <div class="info">
                        <strong>State:</strong> <br/>
                    </div>
-->                    
                    <div class="info">
                        <strong><?php echo __('Country');?>:</strong> <br/> 
                         <?php 
            						 if(isset($country_name) && !empty($country_name))
            						 echo $country_name;
            						 else
            						 echo "-"; 
						             ?>
                    </div>
                
                </div>
                
            </div>
            <div role="tabpanel" class="tab-pane" id="profile">
                <div id="edit-profile"> 
                    <?php echo $this->Form->create('User'); ?>
                        <div class="input-fields">
                            <?php echo $this->Form->input('fname',array('type'=>'text','label' => __('First Name'),'div'=>false)); ?>
                            <?php echo $this->Form->input('id',array('type'=>'hidden','label' => false)); ?>
                        </div>
                        
                        <div class="input-fields">
                            <?php echo $this->Form->input('lname',array('type'=>'text','label' => __('Last Name'),'div'=>false)); ?>
                        </div>
                        
                        <div class="input-fields">
                            <?php echo $this->Form->input('username',array('placeholder'=>__('Account Number'),
																		'required' =>false,
																		'label' => __('Account Number'),
                                    'readonly'=>true,
																		'value'=>@$username)); 
							?>
                        </div>
                        
                        <div class="input-fields">
                            <?php echo $this->Form->input('email',array('placeholder'=>__('Email Address'),
																		'required' =>false,
																		'label' => __('Email Address'),
						 												'value'=>@$email)); 
                               ?>
                        </div>
                        
                        <div class="input-fields">
                             <?php 
									//$this->request->data['User']['password'] = NULL;
									echo $this->Form->input('password',array(
									'placeholder'=>__('Password'),
									'type'=>'password',
									'required' =>false,
									'label'=>__('Password'))); 
							 ?>
                        </div>
                        
                        <div class="input-fields">
                           <?php 
						    // $this->request->data['User']['confirm_password'] = NULL;
									echo $this->Form->input('confirm_password',array(
									'placeholder'=>__('Confirm Password'),
									'type'=>'password',
									'required' =>false,
									'label'=>__('Confirm Password'),
									'div' => false)); 
							 ?>
                        </div>
                        
                        <div class="input-fields">
                            <?php echo $this->Form->input('address',array('placeholder'=>__('Address'),
							'required' =>false,
							'label' => __('Address'),
																		)); 
							?>
                        </div>
                        
                       <!-- <div class="input_fields">
                            <label>State</label>
                            <select class="selectbox_graditent">
                                <option>Berlin</option>
                            </select>
                        </div>-->
                        
                        <div class="input-fields">
                          <?php echo $this->Form->input('phone',array(
																	'placeholder'=>__('Contact Number'),
																	'required' =>false,
																	'onkeypress'=>'return isNumber(event);',
																	'minLength'=>10,
																	'maxLength'=>12,
																	'label'=>__('Contact Number'))); ?>
                        </div>
                        
                        <div class="input-fields">
                           <?php echo $this->Form->input('country_code', array(
                                                'type'=>'select',
												'options'=>$set_countries,
												'empty'=>__('Select Country'),
												'label'=>__('Country'),
                                                'style'=>'cursor:pointer;'));?>
                        </div>
                        

                        <div class="input-fields">
                           <?php echo $this->Form->input('security_question_id', array(
                                                'type'=>'select',
												'options'=>$questions,
												'empty'=>__('Select Question'),
												'label'=>__('Security Question'),
                                                'style'=>'cursor:pointer;'));?>
                        </div>

                       


                        <div class="input-fields">
                            <?php echo $this->Form->input('security_answer',array('placeholder'=>__('Answer'),
																		'required' =>false,
																		'label' => __('Answer'))
);																		
							?>
                        </div>
                         

                        <div class="input-fields" id="error_answer" style="padding-left:0px;">
                          
                        </div> 

                        <div class="input-fields">
                           <?php echo $this->Form->input('image',array(
																'id'=>'upload_content',
																'placeholder'=>__('Profile Image'),
																'required' =>false,'readonly'=>'readonly',
																'onclick'=>'document.getElementById("fileupload").click()',
																'label'=>__('Upload Image'))); ?>
     						<div style="display: none; "><?php echo $this->Form->input('',array('type'=>'file',
																						'id'=>'fileupload',
																						'name'=>'files[]',
																						'onclick'=>'PdfContent("fileupload","bar1","progress","upload_content")',
																						'label' => false)); ?>
                          	</div>
     						  <label>&nbsp;</label>
							 <?php echo __('Supported Files').' : jpg-jpeg-bmp-gif-png'?>
                             <div id="progress" class="progress progress-success progress-striped  userprocess" style="width:489px;"><div class="bar" id="bar1"></div></div>
                              <div class="error_image"></div>
        				</div>
        
                         <div class="input-fields">
                           <label>&nbsp;</label>
                           <?php	if(isset($this->request->data['User']['image']) && !empty($this->request->data['User']['image'])){
							        if(file_exists(WWW_ROOT.'img/users/'.$this->request->data['User']['image'])){	?>
									<?php  
									 echo $this->Html->image('users/'.$this->request->data['User']['image'],
										 array('class'=>'','border'=>'0','div'=>true,'width'=>100,'height'=>'100','label'=>'Profile Image'));?>		
      				      	<?php }  
						    		else
									{
										echo $this->Html->image('users/noimage.jpg',
									    array('class'=>'','border'=>'0','div'=>true,'width'=>100,'height'=>'100','label'=>'Profile Image'));	
									}
							}?>
                          </div>
                        
                        <div class="input-fields">
                            <label>&nbsp;</label>
                            <input type="submit" name="save" value="<?php echo __('SAVE');?>" class="button-gradient button-gradient_front" onclick ="return check_details();"/>
                            <input type="button" name="cancel" value="<?php echo __('CANCEL')?>" class="input-box-gradient cancel-button-gradient" onclick="go_dashboard();" style="float:left;margin-left:10px; width:auto;color:#FFF;"/>
                        </div>
                        
                 <?php echo $this->Form->end(); ?>
                
                </div>
            </div>
        </div>
    </div>
</div>				
                
<script type="application/javascript">

$(document).ready(function(){
	//Highlight active menu
	$('#sb-opt-users').addClass('opt-selected');
	$('#sb-opt-users').next().toggle('sliderup');
	$('#sb-subopt-user-info').addClass('opt-selected');
//	$('#profile').click();

<?php if(@$is_error==1){ //server side error?>
				$('#home').removeClass('active');
				$('#profile').addClass('active');
				$('.home_tab').removeClass('active');
				$('.profile_tab').addClass('active');
<?php }else{?> 
				$('#home').addClass('active');
				$('#profile').removeClass('active');
				$('.home_tab').addClass('active');
				$('.profile_tab').removeClass('active');

<?php }?>

});

function go_dashboard()
{
	var url = "<?php echo $this->Html->url(array('controller'=>'Pages','action'=>'dashboard'));?>";
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

function check_details()
{
  var answer =   $('#UserSecurityAnswer').val();
  var ques_id =    $('#UserSecurityQuestionId').val();
  var flag = 0;
  
  
  answer = answer.trim();
  ques_id = ques_id.trim();
  

  //alert(answer+" "+ques_id);
  if(ques_id.length != 0 )
  {
	  if(answer.length == 0)
      {
         $('#error_answer').html("<?php echo __('Enter answer for security question.');?>");
         $('#error_question').html("");
         flag = 1;
      }
      else
      {
      	 $('#error_answer').html("");
      	 $('#error_question').html("");
      }
  }
  else
  {
  	$('#error_answer').html("");
  	 if(answer.length != 0)
      {
         $('#error_question').html("<?php echo __('Please select security question.');?>");
         $('#error_answer').html("");
         flag = 1;
      }
      else
      {
      	 $('#error_question').html("");
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