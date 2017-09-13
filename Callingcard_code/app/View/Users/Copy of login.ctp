<?php ?>
<div class="login_form_admin_outer"> 
 <div class="login_form_admin">
		<?php echo $this->Form->create('User'); ?>
    <div class="login_username_textbox_admin">
			<?php echo __('Email')?>
	    <?php echo $this->Form->input('email',array('class'=>'login_textbox_admin',
																									'placeholder'=>__('Email Address'),
																									'required' =>true,
																									'label'=>false,'value'=>@$email)); ?>
    </div>
    <div class="space"></div>
    <div class="login_username_textbox_admin">
				<?php echo __('Password')?>
        <?php echo $this->Form->input('password', array('type'=>'password',
																												'placeholder'=>__('Password'),
																												'required' =>true,
																												'class'=>'login_textbox_admin',
																												'label'=>false));?>
    </div>
	  <?php echo $this->Form->button(__('Login'), array('type' => 'submit',
																											'class'=>'login_button_admin',
																											'label'=>false,
																											'style'=>'cursor:pointer;'));?>
		<?php echo $this->Form->end();?>
 </div>
</div>