<style type="text/css">
.captcha_reload_inner{
	cursor: pointer;
}
</style>

<br>
<div class="container" align="left">
    <div class="row">
		<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default panel-primary">
			  	<div class="panel-heading">
			    	<h3 class="panel-title"><?php echo $pageTitle; ?></h3>
			 	</div>
			  	<div class="panel-body">
			    	<?php echo $this->Form->create('User');?>
                    <fieldset>
                        <div class='row'>
              				<div class="col-md-12" style="text-align:left;">
                       		<label><?php echo __('Select either account number or email.') ?></label>
              				</div>
            			</div> 
			    	  	
			    	  	<div class="form-group input-group" style="width:100%;">
			    	  		<span class="input-group-addon" style="width:150px;text-align:left;">Email</span>
                  				<?php echo $this->Form->input('email',array('type' => 'email', 'label' => false, 'required' => false, 'class' => 'form-control', 'placeholder'=>'E-mail','autofocus'=>true)); ?>
			    		</div>

			    		 <div class='form-group input-group' style="height:20px;margin-bottom:15px;width:100%;">
                 			<div style="float:left;width:41%;border-top:1px solid #CCC;margin:10px 15px 0px 0px;"></div>
                 			<div style="float:left;width:4%;"><?php echo __('OR');?></div>
                 			<div style="float:left;width:41%;border-top:1px solid #CCC;margin:10px 0px 0px 15px;"></div>
            			</div>

           				<div class="form-group input-group" style="width:100%;">
			    	  		<span class="input-group-addon" style="width:150px;text-align:left;">Account Number</span>
                  				<?php echo $this->Form->input('username',array('type' => 'text', 'label' => false, 'required' => false, 'class' => 'form-control', 'placeholder'=>'Account Number','autofocus'=>true)); ?>
			    		</div>
                         
                         <div class='row' style="text-align:left;">
                       		<div class="col-lg-12 error_email" style="margin-bottom: 10px !important;text-align:left;color:#F00;"></div> 
                         </div>
               
            
				    	<div class='row' style="margin-bottom:15px;">
	                        
	                        <div class="col-md-5" style="text-align:left;font-size:14px;">
	                            <?php echo __('Security Question') ?>
	                        </div>

	                        <div class="col-md-7">
	                            <?php
	                              echo $this->Form->input('security_question_id', array(
	                                'label' => false,
	                                'class' => 'form-control selectbox_graditent',
	                                'type' => 'select',
	                                'required' => true,
	                                'value' => @$questions_selected,
	                                'options' => @$questions,
	                            ));
	                            ?>
	                    	</div>
	         			 </div>
                            

	         			 <div class='row'>
				            <div class="col-md-5" style="text-align:left;margin-top: 10px !important;">
				                <?php echo __('Answer') ?>
				            </div>
				            <div class="col-lg-7" tyle="margin-top: 10px !important;">
				               <?php 
			                    echo $this->Form->input('security_answer',array('label'=>false,
			                                                              'required'=>'true',
			                                                              'class'=>'form-control',
			                                                              'placeholder'=>__('Answer'),
			                                            )); 
				               ?>
				            </div>  
				         </div>

				         <div class='row' style="text-align:left;">
                       		<div class="col-lg-12 error_answer" style="margin-bottom: 10px !important;text-align:left;color:#F00;"></div> 
                         </div>

                        
						<div class='row' style="margin-top:15px;">
						  <div class="col-md-5" style="text-align:left;">
						           <?php echo __('Captcha') ?>
						  </div>

						   <div class="col-lg-7" tyle="margin-top: 10px !important;">
						   <?php
								echo $this->Html->image($this->Html->url(array('controller' => 'users', 'action' => 'captcha'),true), array(
									'id' => 'img-captcha',
									'vspace' => 2,
									'width' => '118px',
									'height' => '38px',
									'class' => 'img-captcha'
								));
						   ?>
						    <div class="captcha_reload" style="width:100%;margin:5px 0px 5px 0px;">
								<img src="<?php echo $this->webroot . "img/" ?>signuploadimg.png" class ="captcha_reload_inner" />
								<?php echo __("Can't read? Reload"); ?>
							</div>

						   <?php
								echo $this->Form->input('captcha', array(
									'class' => 'signinpopuptextbox',
									'placeholder' => __('Security Code'),
									'label' => false,
									'div' => array('class' => 'input_wrapper'),
									'required' => true,
								));
							?>
                           
						  </div>  
						</div>

			    	    <div class="row" style="margin-top:15px;">
							<div class="col-md-3">
							<?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-md btn-primary','label'=>false,'onclick'=>'return validateForm()')); ?>
							</div>
							<div class="col-md-9" align="right" style="margin-top:5px;">
								<?php echo $this->Html->link('Admin Login', array('controller'=>'users', 'action'=>'login','admin'=>true))?>
							</div>
						</div>
						
			    	</fieldset>
			      	<?php echo $this->Form->end(); ?>			      	
			    </div>
			</div>
		</div>
	</div>
</div>
<script>

 function validateForm()
  {
      
      var answer =   $('#UserSecurityAnswer').val();
      var email =    $('#UserEmail').val();
      var username = $('#UserUsername').val();
      var flag = 0;

      answer = answer.trim();
      email = email.trim();
      username = username.trim();

      

      if(username.length !=0 && email.length !=0 )
      {
          $('.error_email').html("<?php echo __('Enter either account number or email not both.');?>");
          flag = 1;
      }
      else if(username.length == 0 && email.length ==0)
      {
        $('.error_email').html("<?php echo __('Enter either account number or email.');?>");
        flag = 1;

      }
      else
      {
        if(email.length != 0 )
        {
        	if(!$('#UserEmail').val().toLowerCase().match(/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/))
        	{
				$('.error_email').html("<?php echo __('Please enter valid email address');?>");
				return false;
	        }
	        else
	        {
	        	 $('.error_email').html("<?php echo __('');?>");
	        }
        }
        else
        {
            $('.error_email').html("<?php echo __('');?>");
        }
       
      }
      

      if(answer.length == 0)
      {
         $('.error_answer').html("<?php echo __('Enter answer for security question.');?>");
         flag = 1;
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

  $('.captcha_reload_inner').click(function(){
   
   var captcha = $("#img-captcha");
   captcha.attr('src', captcha.attr('src')+'?'+Math.random());
   return false;

  });
  
</script>

