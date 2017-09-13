<div class="head_bg">
    <div class="head_center">
        <div class='container'>
            <div class='head-Text' style="float:left;">
                <?php echo __('Forgot Password');;?>
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
            	<div class="col-lg-12">
           		 <?php 
							      echo $this->Form->input('email',array('label'=>false,
							 																								'placeholder'=>__('Email'),
																						)); 
							 ?>
            	</div>
            </div>
            <div class='row'>
            	<div class="col-lg-12">
            		<?php echo $this->Form->submit(__('Submit'),array('class'=>'contact-submit','style'=>'float:left;')); ?>
            	</div>
            </div>
            <?php echo $this->Form->end(); ?>
          </div>
      </div>

		</div>
	</div>
</div>