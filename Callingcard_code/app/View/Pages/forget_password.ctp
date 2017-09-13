<div class="head_bg">
    <div class="head_center">
        <div class='container'>
            <div class='head-Text' style="float:left;">
                <?php echo __('Forgot Password');?>
            </div>
        </div>
    </div>
</div>

<div class='mainContent'>
  <div class="container">
     <div class="row">
       <div class="col-lg-8">
          <div class="contact-upper">
            <div class='row'>
              <div class="col-lg-12">
                <h3 class="page-subHeading" style="float:left;"><?php echo __('Recover Your Password');?></h3>
              </div>
            </div>
          </div>

          <div class='contact-form'>
						<?php echo $this->Form->create('User'); ?>
          
             <div class='row'>
              <div class="col-md-12" style="text-align:left;margin-top: 10px !important;">
                       <label><?php echo __('Select either username or email.'); ?></label>
              </div>
            </div>

            <div class='row'>

              <div class="col-md-3" style="text-align:left;margin-top: 10px !important;">
                       <label><?php echo __('Email') ?></label>
              </div>

            	<div class="col-lg-9" style="margin-top: 10px !important;">
           		 <?php 
							      echo $this->Form->input('email',array('label'=>false,
					 																								'placeholder'=>__('Email'),
                                                          'required'=>false
																						)); 
							 ?>
            	</div>
            </div>
            
            <div class='row' style="height:20px;">
                 <div style="float:left;width:43%;border-top:1px solid #CCC;margin:10px 15px 0px 15px;"></div>
                 <div style="float:left;width:4%;"><?php echo __('OR');?></div>
                 <div style="float:left;width:43%;border-top:1px solid #CCC;margin:10px 15px 0px 15px;"></div>
            </div>
 
            <div class='row'>

              <div class="col-md-3" style="text-align:left;margin-top: 10px !important;">
                       <label><?php echo __('Username') ?></label>
              </div>
              
              <div class="col-lg-9" style="margin-top: 10px !important;">
               <?php 
                    echo $this->Form->input('username',array('label'=>false,
                                                            'placeholder'=>__('Username'),
                                                            'required'=>false
                                            )); 
               ?>
            </div>  
           </div>

           <div class='row'>

           <div class="col-md-3" style="text-align:left;margin-top: 10px !important;">
                       <label></label>
              </div>
              <div class="col-lg-9 error_email" style="margin-top: 10px !important;text-align:left;color:#F00;"></div>  
           </div>

           <div class='row'>
                        <div class="col-md-3" style="text-align:left;margin-top: 10px !important;">
                            <label><?php echo __('Security Question') ?></label>
                        </div>
                        <div class="col-md-9" style="margin-top: 10px !important;">
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
              
              <div class="col-md-3" style="text-align:left;margin-top: 10px !important;">
                       <label><?php echo __('Answer') ?></label>
              </div>

              <div class="col-lg-9" tyle="margin-top: 10px !important;">
               <?php 
                    echo $this->Form->input('security_answer',array('label'=>false,
                                                              'required'=>'true',
                                                              'placeholder'=>__('Answer'),
                                            )); 
               ?>
            </div>  
           </div>
           
            <div class='row'>

              <div class="col-md-3" style="text-align:left;margin-top: 10px !important;">
                       <label></label>
              </div>
              <div class="col-lg-9 error_answer" style="margin-top: 10px !important;text-align:left;color:#F00;"></div>  
           </div>


           <div class='row'>
              
              <div class="col-md-3" style="text-align:left;margin-top: 10px !important;">
                       <label><?php echo __('Captcha'); ?></label>
              </div>

              <div class="col-lg-9" tyle="margin-top: 10px !important;">
               <?php 
                    $this->Captcha->render($captchaSettings); 
               ?>
            </div>  
           </div>


          <div class='row'>
            	<div class="col-lg-12">
            		<?php echo $this->Form->submit(__('Submit'),array('class'=>'contact-submit','style'=>'float:left;','onclick'=>'return check_details();')); ?>
            	</div>
          </div>
          
          <?php echo $this->Form->end(); ?>
          </div>
      </div>

		</div>
	</div>
</div>


<script type="text/javascript">
  
  function check_details()
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
          $('.error_email').html("<?php echo __('Enter either username or email not both.');?>");
          flag = 1;
      }
      else if(username.length == 0 && email.length ==0)
      {
        $('.error_email').html("<?php echo __('Enter either username or email.'); ?>");
        flag = 1;

      }
      else
      {
        $('.error_email').html("");
      }
      

      if(answer.length == 0)
      {
         $('.error_answer').html("<?php echo __('Enter answer for security question.'); ?>");
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

  

</script>
