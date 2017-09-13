

      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>


 <?php if($this->Session->read('Auth.User.id')){ ?>
 <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
     <ul class="nav navbar-nav side-nav left-panel">
        <li><a href="<?php echo Router::url(array('controller'=>'Pages', 'action'=>'dashboard')); ?>"><i class="icon-home"></i><?php echo __('Dashboard');?></a></li>
		<li><a href="<?php echo Router::url(array('controller'=>'Mails', 'action'=>'index')); ?>"><i class="icon-envelope"></i><?php echo __('Compose Mail');?></a></li>
		<li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'profile_manage',$this->Session->read('Auth.User.id'))); ?>"><i class="icon-envelope"></i><?php echo __('Manage Profile');?></a></li> 
        <li><a href="<?php echo Router::url(array('controller'=>'Cards', 'action'=>'manage_price_retailer')); ?>"><i class="icon-envelope"></i><?php echo __('Manage Price');?></a></li> 
        <li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'logout')); ?>"><i class="icon-power-off"></i> <?php echo __('Log Out');?></a></li>
     </ul>

          <ul class="nav navbar-nav navbar-right navbar-user">
            <li class="dropdown user-dropdown">
            <?php  
					 $admin_image = '<i class="icon-user"></i>'; ?>
					     <a href="#" class="dropdown-toggle user_icon" data-toggle="dropdown">
                <div style="float:left;width:20px;"><?php echo $admin_image?></div><?php echo $this->Session->read('Auth.User.u_name')?><b class="caret"></b></a>
              <ul class="dropdown-menu">
               <li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'profile_edit'))?>">
                <div style="float:left;width:32px;"><?php echo $admin_image; ?></div> <?php echo __('Profile Manage');?></a></li>
                
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
