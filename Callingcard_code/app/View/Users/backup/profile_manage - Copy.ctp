<?php
    echo $this->Html->script('upload/jquery.ui.widget.js'); 
    echo $this->Html->script('upload/jquery.fileupload.js'); 
    
    echo $this->Html->css('upload/jquery.fileupload-ui.css'); 
   // echo $this->Html->css('upload/bootstrap.min.css'); 
    echo $this->Html->script('upload/jquery.fileupload-process.js'); 
    echo $this->Html->script('upload/jquery.fileupload-validate.js');
?>
<div class="row">
  <div class="col-md-12">
    <h1><?php echo $title_for_layout; ?></h1>
  </div>
</div>
<?php echo $this->Form->create('User'); ?>
<div class="clear10"></div>

<div class="row">
  <div class="col-md-3"><?php echo __('First Name')?><sup class="MandatoryFields">*</sup></div>
  <div class="col-md-7">
      <?php echo $this->Form->input('fname',array('type'=>'text','class' => 'form-control','label' => false,'div'=>false)); ?>
      <?php echo $this->Form->input('id',array('type'=>'hidden','class' => 'input_textbox','label' => false)); ?>
  </div>
  <div class="col-md-2">&nbsp;</div>
</div>
<div class="clear10"></div>

<div class="row">
  <div class="col-md-3"><?php echo __('Last Name')?><sup class="MandatoryFields">*</sup></div>
  <div class="col-md-7">
      <?php echo $this->Form->input('lname',array('type'=>'text','class' => 'form-control','label' => false,'div'=>false)); ?>
  </div>
  <div class="col-md-2">&nbsp;</div>
</div>
<div class="clear10"></div>

<div class="row">
  <div class="col-md-3"><?php echo __('Email')?><sup class="MandatoryFields">*</sup></div>
  <div class="col-md-7">
       <?php echo $this->Form->input('email',array('class'=>'form-control',
																									'placeholder'=>__('Email'),
																									'required' =>true,
																									'label'=>false,'value'=>@$email)); ?>
  </div>
  <div class="col-md-2">&nbsp;</div>
</div>
<div class="clear10"></div>

<div class="row">
  <div class="col-md-3"><?php echo __('Change Password')?></div>
  <div class="col-md-1">
       <?php echo $this->Form->input('change_password',array('class'=>'',
																									'hiddenField' => false,
																									'type' =>'checkbox',
																									'label'=>false)); ?>
  </div>
  <div class="col-md-2">&nbsp;</div>
</div>
<div class="clear10"></div>

<div class="hidden_password"> 
	<div class="row">
    <div class="col-md-3"><?php echo __('Password')?><sup class="MandatoryFields">*</sup></div>
    <div class="col-md-7">
         <?php $this->request->data['User']['password'] = '';
   					  echo $this->Form->input('password',array('class'=>'form-control',
                                                'placeholder'=>__('Password'),
                                                'type'=>'password','value'=>'',
                                                'required' =>false,
                                                'label'=>false)); ?>
    </div>
    <div class="col-md-2">&nbsp;</div>
  </div>
  <div class="clear10"></div>
  
  <div class="row">
    <div class="col-md-3"><?php echo __('Confirm Password')?><sup class="MandatoryFields">*</sup></div>
    <div class="col-md-7">
         <?php echo $this->Form->input('confirm_password',array('class'=>'form-control',
                                                'placeholder'=>__('Confirm Password'),
                                                'type'=>'password','value'=>'',
                                                'required' =>false,
                                                'label'=>false)); ?>
    </div>
    <div class="col-md-2">&nbsp;</div>
  </div>
  <div class="clear10"></div>
</div>
<div class="row">
    <div class="col-md-3"><?php echo __('Contact Number')?></div>
    <div class="col-md-7">
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
  <div class="col-md-3"><?php echo __('Country')?></div>
  <div class="col-md-7">
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
    <div class="col-md-3"><?php echo __('Address')?></div>
    <div class="col-md-7">
        <?php echo $this->Form->input('address',array('class'=>'form-control',
																									'placeholder'=>__('Address'),
																									'required' =>false,
																									'label'=>false)); ?>
    </div>
    <div class="col-md-2">&nbsp;</div>
 </div>
<div class="clear10"></div>

<div class="row">
    <div class="col-md-3"><?php echo __('Image')?></div>
    <div class="col-md-7">
        <?php echo $this->Form->input('image',array('class'=>'',
																									'id'=>'upload_content',
																									'placeholder'=>__('Profile Image'),
																									'required' =>false,'readonly'=>'readonly',
																									'onclick'=>'document.getElementById("fileupload").click()',
																									'label'=>false)); ?>
      <div style="display: none; "><?php echo $this->Form->input('',array('type'=>'file','id'=>'fileupload','name'=>'files[]','onclick'=>'PdfContent("fileupload","bar1","progress","upload_content")','label' => false)); ?></div>
      <div class="supported_file"><?php echo __('Supported Files : pdf-jpg-jpeg-bmp-gif-png')?></div>
      <div id="progress" class="progress progress-success progress-striped  userprocess" ><div class="bar" id="bar1"></div></div>
    </div>
    <div class="col-md-2">
    	&nbsp;
    </div>
 </div>
<div class="clear10"></div>
<?php	if(isset($this->request->data['User']['image'])){
			 if(file_exists(WWW_ROOT.'img/users/'.$this->request->data['User']['image'])){	?>
             <div class="row">
             <div class="col-md-3">&nbsp;</div>
             <div class="col-md-7">
						 		<?php  
								        if($role_id == 1)
												{
													$alt = "Mediator";
												}
												else
												{
													$alt = "Retailer";
												}
												echo $this->Html->image('users/'.$this->request->data['User']['image'],
																			array('alt'=>$alt,'class'=>'','border'=>'0','div'=>true,'width'=>100,'height'=>'100'));?><?php //echo $this->Html->image(IMAGE_PATH.'image.php?image=img/users/'.$this->request->data['User']['image'].'&amp;width=100&amp;height=100', array('alt' => '','border'=>'0','div'=>true));?>		
             </div>
             <div class="col-md-2">&nbsp;</div>
          </div>
          <div class="clear10"></div>
	   <?php } 
		 }?>
<div class="row">
	  <div class="col-md-3">&nbsp;</div>
	  <div class="col-md-3"><?php echo $this->Form->submit('Update', array('type'=>'submit', 'class'=>'btn btn-primary','label'=>false,'id'=>'submit_btn','div'=>false)); ?>
     <?php echo $this->Form->button(__('Cancel'), array('type' => 'button',
																											'class'=>'btn btn-warning cancel',
																											'label'=>false,
																											'style'=>'cursor:pointer;'));?>
    </div> 
	</div>	
		
	<div class="clear10"></div>

<?php echo $this->Form->end(); ?>
<script type="text/javascript">

$(document).ready(function(){
		$('#UserPassword').val('');
		if($('#UserChangePassword').is(':checked'))
		{
			$('.hidden_password').fadeIn("slow");
		}
		else
		{
			$('.hidden_password').fadeOut("slow");
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

$('#UserChangePassword').click(function(){

  if($(this).is(':checked'))
	{
		$('.hidden_password').fadeIn("slow");
	}
	else
	{
		$('.hidden_password').fadeOut("slow");
	}
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
				acceptFileTypes: /(\.|\/)(jpg|jpeg|bmp|gif|png)$/i,
				done: function (e, data) {
				 		$.each(data.result.files, function (index, file) {				  
						//alert(file.url);
						$("#"+upload_content).val(file.name);
						
						//$('#upload_path').val(file.url);
						var file_size = getReadableFileSizeString(file.size); 
						$('#'+bar_id).text(file.name+'( '+file_size+' )');
						if(content){
                 
              		    /*$.ajax({
									beforeSend: function (XMLHttpRequest) {
										 $("#loading-image").fadeIn();
									},
									complete: function (XMLHttpRequest, textStatus) {
										$("#loading-image").fadeOut();
									},
									dataType: "html",
									type: "POST",
									evalScripts: true,
									url: "<?php //echo $this->Html->Url(array('controller'=>'Mails','action'=>'unlink_file'));?>",
									data: ({file_name:content}),
									success: function (data)
									{
										
									}
							});*/
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
</script>