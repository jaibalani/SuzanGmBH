   
   <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
     <!-- Brand and toggle get grouped for better mobile display -->
     <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo APPLICATION_PATH?>admin"><?php echo APPLICATION_NAME?></a>
        </div>


        <?php if($this->Session->read('Auth.User.id')){ ?>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav side-nav left-panel">
            <li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'dashboard')); ?>"><i class="icon-home"></i><?php echo __('Dashboard');?></a></li>
            
   <?php if($login_user_roleid == 1) { ?>
    <li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'manage_mediator')); ?>"><i class="icon-user"></i><?php echo __('Manage Mediator');?></a></li>
    <li><a href="<?php echo Router::url(array('controller'=>'Transactions', 'action'=>'manage_fund')); ?>"><i class="icon-briefcase"></i><?php echo __('Manage Fund');?></a></li>
    <li><a href="<?php echo Router::url(array('controller'=>'Cards', 'action'=>'manage_price','admin'=>true)); ?>"><i class="icon-euro"></i><?php echo __('Manage Price');?></a></li>
    <li><a href="<?php echo Router::url(array('controller'=>'Mails', 'action'=>'index','admin'=>true)); ?>"><i class="icon-envelope"></i><?php echo __('Compose Mail');?></a></li>
    <li><a href="<?php echo Router::url(array('controller'=>'Searches', 'action'=>'index')); ?>"><i class="icon-barcode"></i><?php echo __('Online Cards');?></a></li>
    <li><a href="<?php echo Router::url(array('controller'=>'Transactions', 'action'=>'minimum_balance_mediator','admin'=>true)); ?>"><i class="icon-briefcase"></i>
	 <?php echo __('Minimum Balance'); ?></a></li>
    <li><a href="<?php echo Router::url(array('controller'=>'CmsPages', 'action'=>'index')); ?>"><i class="icon-edit"></i><?php echo __('CMS Manage');?></a></li>
    <li><a href="<?php echo Router::url(array('controller'=>'Categories', 'action'=>'index')); ?>"><i class="icon-list"></i><?php echo __('Categories and PINs');?></a></li>
    <li><a href="<?php echo Router::url(array('controller'=>'Cards', 'action'=>'index','admin'=>true)); ?>"><i class="icon-list"></i><?php echo __('Cards');?></a></li>

    <!--<li><a href="<?php echo Router::url(array('controller'=>'Pins', 'action'=>'admin_index')); ?>"><i class="icon-pushpin"></i><?php echo __('Manage PIN');?></a></li>-->
    <li><a href="<?php echo Router::url(array('controller'=>'Faqs', 'action'=>'index')); ?>"><i class="icon-question-sign"></i><?php echo __('FAQ');?></a></li>
    <li><a href="<?php echo Router::url(array('controller'=>'EmailContents', 'action'=>'index')); ?>"><i class="icon-envelope"></i><?php echo __('Email Content');?></a></li>
    <li><a href="<?php echo Router::url(array('controller'=>'Languages', 'action'=>'index')); ?>"><i class="icon-edit"></i><?php echo __('Language Manage');?></a></li>
    <li><a href="<?php echo Router::url(array('controller'=>'Settings', 'action'=>'edit','Site')); ?>"><i class="icon-gear"></i><?php echo __('Settings');?></a></li>       

    <?php } else { ?>
    
     <li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'manage_retailer')); ?>"><i class="icon-briefcase"></i><?php echo __('Manage Retailer');?></a></li>     <li><a href="<?php echo Router::url(array('controller'=>'Transactions', 'action'=>'manage_fund_retailer')); ?>"><i class="icon-briefcase"></i><?php echo __('Manage Fund');?></a></li>
     <li><a href="<?php echo Router::url(array('controller'=>'Cards', 'action'=>'manage_price_mediator','admin'=>true)); ?>"><i class="icon-euro"></i><?php echo __('Manage Price');?></a></li>
     <li><a href="<?php echo Router::url(array('controller'=>'Transactions', 'action'=>'minimum_balance_retailer','admin'=>true)); ?>"><i class="icon-briefcase"></i>
	 <?php echo __('Minimum Balance'); ?></a></li>
    <li><a href="<?php echo Router::url(array('controller'=>'Mails', 'action'=>'index','admin'=>true)); ?>"><i class="icon-envelope"></i><?php echo __('Compose Mail');?></a></li>
    <li><a href="<?php echo Router::url(array('controller'=>'Searches', 'action'=>'index')); ?>"><i class="icon-barcode"></i><?php echo __('Online Cards');?></a></li>
  
  <?php } ?>
 
 
     <li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'logout')); ?>"><i class="icon-power-off"></i> <?php echo __('Log Out');?></a></li>
  </ul>

          <ul class="nav navbar-nav navbar-right navbar-user">
            <li class="dropdown user-dropdown" >
            <?php  
			  
			  $image = $this->Session->read('Auth.User.image');
			  if(file_exists(WWW_ROOT.'img/users/'.$image) && !empty($image))
			  {
			  	$admin_image= $this->Html->image('users/'.$this->Session->read('Auth.User.image'),
																			array('class'=>'','border'=>'0','div'=>true,'width'=>45));
			  
			  }
			  else
			  {
				 $admin_image = '<i class="icon-user"></i>';  
			  }
			 
			  ?>
			  <a href="#" class="dropdown-toggle user_icon" data-toggle="dropdown" style="padding-top:10px;">
                <div style="float:left;"><?php echo $admin_image?></div><?php echo $this->Session->read('Auth.User.u_name')?><b class="caret"></b></a>
              <ul class="dropdown-menu" style="margin-left:0px;padding-left:0px;">
               <li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'profile_edit'))?>">
                <div style="float:left;width:auto;"><?php echo $admin_image; ?></div> <?php echo __('Profile Manage');?></a></li>
                
                <li class="divider"></li>
                <li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'logout'))?>"><i class="icon-power-off"></i> <?php echo __('Log Out');?></a></li>
              </ul>
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
        <?php } ?>
      </nav>

 

<script>

</script>      
