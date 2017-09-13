<?php 
     $showstring=@$email;
?>
<div class="right-part right-panel">
        <?php echo $this->Form->create('Mails', array('id' => 'frm_mail', 'name'=>'frm_mail')); ?>
        <!--Compose Start-->
        <div class="sb-page-title">
            <strong><?php echo $title_for_layout; ?></strong>
        </div>
        <div id="email-form">
            <div id="mail-to">
                <strong><?php echo __('To');?></strong> : <?php echo $showstring;?>
                <?php 
				echo $this->Form->input('email',array('type'=>'hidden',
													  'label' => false,
													  'div'=>false,
													  'required'=>true,
													  'value'=>$showstring));
		        ?>
            </div>

            <div class="input-group">
                <div class="input-group-addon"><?php echo __('Subject')?>:</div>
                <?php echo $this->Form->input('subject',array('type'=>'text','class' => 'form-control','label' => false,'div'=>false,'required'=>true)); ?>
            </div>

            <div class="input-group">
                <div class="input-group-addon" style="border:none;background:#FFFFFF;"><?php echo __('Message');?>: </div>
                <?php echo $this->Form->input('message',array('type'=>'textarea','class' => 'form-control','label' => false,'div'=>false)); ?>
            </div>

            <div id="action">
                <?php echo $this->Form->submit(__('Send Mail'), array('type'=>'submit', 'class'=>'button-gradient','label'=>false,'id'=>'submit_btn','div'=>false ,'onclick'=>'return check_fields();')); ?>
                 <?php echo $this->Form->button(__('Cancel'), array('class'=>'input-box-gradient cancel-button-gradient cancel','type'=>'button','label'=>false,'div'=>false)); ?>
            </div>
        </div>
    <!--Compose End-->
</div>

<?php echo $this->Form->end();?>  
<script type="text/javascript">
var editor =CKEDITOR.replace('MailsMessage', {height:200,width:'auto',toolbar:'MyToolbar'});


function check_fields()
{
	var flag = 0;
	var to_email= $('#MailsEmail').val();
	var regex =  /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	// To Email Validation
	if(to_email == '')
	{
	
	}
	else
	{
		var to_emails = to_email.split(","); 
		var length = to_emails.length;
		var invalid_emails = ''; 
		for(i=0;i<length; i++)
		{
			if(!regex.test(to_emails[i]))
			{
				 invalid_emails =invalid_emails+" | "+to_emails[i];
      }
    }
		if(invalid_emails != '')
		{
			//$('#invalid_to_emails').html("Invalid Emails : "+invalid_emails);
			//$('.error_mail').css('display','block');
			flag = 2;
		}
		else
		{
			//$('#invalid_to_emails').html("");
			//$('.error_mail').css('display','none');
		}
	}
	
	if(validate(editor,'MailsMessage'))//Editor validation......
	{
		flag = 3;
	}
	
	if(flag == 0)
	{
		$('#frm_mail').submit();
	}
	else
	{
		return false;
	}
 } 
 
function validate(obj,MailsMessage) //ckeditor validation....
{
	$("#errDiv").remove();
	if(validateCKEDITORforBlank($.trim(CKEDITOR.instances.MailsMessage.getData().replace(/<[^>]*>|\s/g, '')))){
	$("#"+MailsMessage).parent().append("<span class='error-message' id='errDiv' style='margin-left:10px; padding:0px; color:#F00;'><?php echo __('This field is required.')?></span>");
	CKEDITOR.instances.MailsMessage.setData("");
	return true;
	}
	return false;
}

function validateCKEDITORforBlank(field) //ckeditor validation....
{
	var vArray = new Array();
	vArray = field.split("&nbsp;");
	var vFlag = 0;
	for(var i=0;i<vArray.length;i++)
	{
		if(vArray[i] == '' || vArray[i] == "")
		{
			continue;
		}
		else
		{
			vFlag = 1;
			break;
		}
	}
	if(vFlag == 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

$('.cancel').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Searches','action'=>'online_cards','admin'=>'true'));?>";
	window.location.href = url;	
});



$(document).ready(function(){
	//Highlight active menu
	$('#sb-opt-Mail').addClass('opt-selected');
});

</script> 
