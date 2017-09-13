<style>
.error-message{	
	text-align:left
}
.sb-submit{
width: 140px !important; 
}
</style>
<div class="head_bg">
    <div class="head_center">
        <div class='container'>
            <div class='head-Text' style="float:left;">
                <?php echo __('Recover Password');;?>
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
                <h3 class="page-subHeading" style="float:left;"><?php echo __('Enter New Password');?></h3>
              </div>
            </div>
          </div>

          <div class='contact-form'>
						<?php echo $this->Form->create('User'); ?>
            <div class='row'>
            	<div class="col-lg-12">
           		 <?php 
							      echo $this->Form->input('password',array('label'=>false,
							 																								'type'=>'password',
																															'placeholder'=>__('Password'),
																						)); 
  									echo $this->Form->input('id',array('label'=>false,
							 																								'type'=>'hidden',
																															'value'=>$id,
																						)); 
																						
							 ?>
            	</div>
            </div>
            <div class='row'>
            	<div class="col-lg-12">
           		 <?php 
							      echo $this->Form->input('confirm_password',array('label'=>false,
							 																								'type'=>'password',
																															'placeholder'=>__('Confirm Password'),
																						)); 
							 ?>
            	</div>
            </div>
            <div class='row'>
            	<div class="col-lg-12">
            		<?php echo $this->Form->submit(__('Update Password'),array('class'=>'contact-submit sb-submit','style'=>'float:left;')); ?>
            	</div>
            </div>
            <?php echo $this->Form->end(); ?>
          </div>
      </div>

		</div>
	</div>
</div>