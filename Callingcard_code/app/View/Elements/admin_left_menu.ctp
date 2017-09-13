<div class="left_menu_outer">
    <a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'dashboard','admin'=>'true'));?>">
		 <div class="left_menu_inner">
  		 <?php echo __('Dashboard');?>
	   </div>
    </a>
    <a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'profile_edit','admin'=>'true'));?>">
		 <div class="left_menu_inner">
  		 <?php echo __('Profile Manage');?>
	   </div>
    </a>
    <a href="<?php echo $this->Html->url(array('controller'=>'CmsPages','action'=>'index','admin'=>'true'));?>">
		 <div class="left_menu_inner">
  		 <?php echo __('CMS Manage');?>
	   </div>
    </a>
    <a href="<?php echo $this->Html->url(array('controller'=>'EmailContents','action'=>'index','admin'=>'true'));?>">
		 <div class="left_menu_inner">
  		 <?php echo __('Email Content');?>
	   </div>
    </a>
    <a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'manage_mediator','admin'=>'true'));?>">
		 <div class="left_menu_inner">
  		 <?php echo __('Manage Mediator');?>
	   </div>
    </a>
    <a href="<?php echo $this->Html->url(array('controller'=>'Settings','action'=>'edit','admin'=>'true','Site'));?>">
		 <div class="left_menu_inner">
  		 <?php echo __('Setting');?>
	   </div>
    </a>
    <a href="<?php echo $this->Html->url(array('controller'=>'Languages','action'=>'index','admin'=>'true'));?>">
		 <div class="left_menu_inner">
  		 <?php echo __('Language Manage');?>
	   </div>
    </a>
    <a href="<?php echo $this->Html->url(array('controller'=>'Faqs','action'=>'index','admin'=>'true'));?>">
		 <div class="left_menu_inner">
  		 <?php echo __('FAQ');?>
	   </div>
    </a>
     <a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'logout','admin'=>'true'));?>">
		 <div class="left_menu_inner">
  		 <?php echo __('Log Out');?>
	   </div>
    </a>

</div>