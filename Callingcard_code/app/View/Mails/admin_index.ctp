<style type="text/css">
.hidden_credit{
display: none;
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
}

.grid_table_box .row .col-md-6 ul{ border-radius:0px; margin-left: 0px;  }

.mail_icon{ font-size: 16px !important; }
</style>
<?php 
    echo $this->Html->css(array('tagit/css/jquery.tagit'));
    echo $this->Html->script(array('tagit/js/tag-it.min')); 
    echo $this->Html->css(array('tagit/css/jquery.tagit'));
    echo $this->Html->script(array('tagit/js/tag-it.min')); 
?>
<?php 

     $showstring=@$arr;
     $flag=0;
     if(isset($user_emails) && !empty($user_emails))
     {
      $flag=1;
	$showstring = '';		
	$tot=count($user_emails);
      foreach($user_emails as $user_mail)
			{ 
      	if(!empty($user_mail))
      	$showstring.= ucfirst($user_mail).",";
			}    
      $showstring =rtrim($showstring,',');
     }
?>
<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
    <div class="sub_title"><i class="icon-envelope mail_icon"></i><span>Compose Mail</span></div>
  </div>
</div>
<div class="clear10"></div>

<div class="main_subdiv">
	<div class="gird_button">
	        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
	</div>    
	  
	<div class="clear10"></div>
	
	<div align="left" class="grid_table_box">
	
		<?php echo $this->Form->create('Mails', array('id' => 'frm_mail', 'name'=>'frm_mail')); ?>
		
		<div class="row">
		  <div class="col-md-3 sb_left_pad"><?php echo __('To')?><sup class="MandatoryFields">*</sup></div>
		  <div class="col-md-6 sb_left_mar">
		      <?php 
						echo $this->Form->input('email',array('type'=>'hidden',
																									'class' => 'form-control',
																									'label' => false,
																									'div'=>false,
																									'required'=>true,
																									'value'=>$showstring)); 
				 ?>
		      <ul id="myTags"  style="font-size:12px; font-weight:normal; border:1px solid #c6c6c6;" class="userrighttestouter"></ul>  
		</div>
		  <div class="col-md-3">&nbsp;</div>
		</div>
		
		<div class="row error_mail" >
		  <div class="col-md-3 sb_left_pad"></div>
		  <div class="col-md-6 sb_left_mar" id="invalid_to_emails"></div>
		</div>
		<div class="clear10"></div>
		
		<div class="row">
		  <div class="col-md-3 sb_left_pad"><?php echo __('CC')?><sup class="MandatoryFields"></sup></div>
		  <div class="col-md-6 sb_left_mar">
		      <?php 
						echo $this->Form->input('cc',array('type'=>'hidden',
																									'class' => 'form-control',
																									'label' => false,
																									'div'=>false,
																									
																									)); 
				 ?>
		     <ul id="myTags_cc"  style="font-size:12px; font-weight:normal; border:1px solid #c6c6c6;" class="userrighttestouter"></ul>  
		</div>
		<div class="col-md-3">&nbsp;</div>
		</div>
		
		<div class="clear10"></div>
		<div class="row error_cc" >
		  <div class="col-md-3 sb_left_pad"></div>
		  <div class="col-md-6 sb_left_mar" id="invalid_cc_emails"></div>
		</div>
		
		<div class="row" style="margin-bottom:15px;">
		  <div class="col-md-3 sb_left_pad"><?php echo __('Subject')?><sup class="MandatoryFields">*</sup></div>
		  <div class="col-md-6 sb_left_mar">
		      <?php echo $this->Form->input('subject',array('type'=>'text','class' => 'form-control','label' => false,'div'=>false,'required'=>true)); ?>
		  </div>
		  <div class="col-md-3">&nbsp;</div>
		</div>
		<div class="row error_subject" >
		  <div class="col-md-3 sb_left_pad"></div>
		  <div class="col-md-6 sb_left_mar" id="invalid_to_subject"></div>
		</div>
		<div class="clear10"></div>
    
    
		<div class="row">
		  <div class="col-md-3 sb_left_pad"><?php echo __('Message')?><sup class="MandatoryFields">*</sup></div>
		  <div class="col-md-6 sb_left_mar">
		      <?php echo $this->Form->input('message',array('type'=>'textarea','class' => 'form-control','label' => false,'div'=>false)); ?>
		  </div>
		  <div class="col-md-3">&nbsp;</div>
		</div>
		<div class="clear10"></div>
		
		<div class="row">
		  <div class="col-md-3 sb_left_pad">&nbsp;</div>
		  <div class="col-md-6 sb_left_mar"><?php echo $this->Form->submit('Send Mail', array('type'=>'submit', 'class'=>'btn btn-primary','label'=>false,'id'=>'submit_btn','div'=>false ,'onclick'=>'return check_fields();')); ?>
         <button class="cancel btn btn-warning" type="button" >Cancel</button>
		  </div> 
		</div>	
		<div class="clear10"></div>
		
		<?php echo $this->Form->end();?> 
		
	</div>
		
</div>	

<script type="text/javascript">
var editor =CKEDITOR.replace('MailsMessage', {height:200,width:'auto',toolbar:'MyToolbar'});

$(function(){
		
		var readonly = "<?php echo $flag;?>"; 
		if(readonly == 1)
		{ 
		 $('#myTags').tagit({
				singleField: true,
				readOnly:true,
				singleFieldNode: $('#MailsEmail')
			});
		}
		else
		{
			 $('#myTags').tagit({
				singleField: true,
				singleFieldNode: $('#MailsEmail')
			});
		}
		$('#myTags_cc').tagit({
				singleField: true,
				singleFieldNode: $('#MailsCc')
			});
		
	});

function check_fields()
{
	var flag = 0;
	var cc			= $('#MailsCc').val();
	var to_email= $('#MailsEmail').val();
	var subject = $.trim($('#MailsSubject').val());
	var regex =  /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	// CC Email Validation
	if(cc != '')
	{
		var cc_emails = cc.split(","); 
		var length = cc_emails.length;
		var invalid_emails = ''; 
		for(i=0;i<length; i++)
		{
			if(!regex.test(cc_emails[i]))
			{
				 invalid_emails =invalid_emails+" | "+cc_emails[i];
      }
		}
		if(invalid_emails != '')
		{
			$('#invalid_cc_emails').html("Invalid Emails : "+invalid_emails);
			$('.error_cc').css('display','block');
			flag = 1;
		}
		else
		{
			$('#invalid_cc_emails').html("");
			$('.error_cc').css('display','none');
		}
	}
	else
	{
			$('#invalid_cc_emails').html("");
			$('.error_cc').css('display','none');
	}
  
	// To Email Validation
	if(to_email == '')
	{
		$('#invalid_to_emails').html("Enter Email Address");
		$('.error_mail').css('display','block');
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
			$('#invalid_to_emails').html("Invalid Emails : "+invalid_emails);
			$('.error_mail').css('display','block');
			flag = 2;
		}
		else
		{
			$('#invalid_to_emails').html("");
			$('.error_mail').css('display','none');
		}
	}
	
	if(validate(editor,'MailsMessage'))//Editor validation......
	{
		flag = 3;
	}
	if(subject !='' ){
		$('#invalid_to_subject').html("");
		$('.error_subject').css('display','none');
	}else{
		$('#invalid_to_subject').html("Enter Subject");
		$('.error_subject').css('display','block');
		flag = 4;
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
	$("#"+MailsMessage).parent().append("<span class='error-message' id='errDiv' style='margin-left:0px; padding:0px;'><?php echo __('This field is required.')?></span>");
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
	var readonly = "<?php echo $flag;?>"; 
	var role_id = "<?php echo $role_id;?>";
	if(readonly == 1)
	{
			if(role_id == 1)
			{
				var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'manage_mediator','admin'=>'true'));?>";
			   window.location.href = url;	
			}
			else
			{
				var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'manage_retailer','admin'=>'true'));?>";
			   window.location.href = url;	
			}
	}
	else
	{
		var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'dashboard','admin'=>'true'));?>";
		window.location.href = url;	
	}
	//istory.go(-1);
});

$("#MailsSubject").on("keydown", function (evt) {
    
	var sub = $(this).val();
    var charCode = (evt.which) ? evt.which : evt.keyCode;
	if(sub.length == 0)
	{
		if(charCode == 32)
		return false;
	}
});
$(document).ready(function(){
   $('.sb_compose_mail').addClass('sb_active_single_opt');
}) ;
</script> 
