<?php
$parameters = $this->request->params;
//prd($parameters);
?>
<style>
.navigation li li a {
    
    padding-left: 30px;
}
.navigation a {
    color: #5A5A5A;
    display: block;
    font-family: "Droid Sans",Arial,Verdana;
    font-size: 14px;
    height: 45px;
    line-height: 45px;
    padding-left: 14px;
    position: relative;
    text-decoration: none;
    margin-left: -40px;
	padding-left: 40px;
}
ol, ul {
    list-style: none outside none;
}
.navigation{
 display:none;
}

.sb_active_subopt_active{

background-color: #EFEFEF !important;
}

.navigation a:hover{

background-color: #E0E0E0 !important;
}

.sb_active_single_opt{ 
    background-color: #3B5999 !important;
    color: #fff !important;
    vertical-align: top !important; 
    background-image: url(<?php echo $this->webroot?>img/aroow.png);
    background-repeat: no-repeat;
    background-position: 200px 16px;
}

.left-panel li a.sb_active_single_opt:hover, .left-panel li a.sb_active_single_opt:focus{
background-color: #3B5999 !important;
    color: #fff !important;
    vertical-align: top !important;    
}

.sb_active_opt{
    background-color: #3B5999 !important;
    color: #fff !important;
    vertical-align: top !important; 
    background-image: url(<?php echo $this->webroot?>img/down_arrow.png), url(<?php echo $this->webroot?>img/aroow.png);
    background-repeat: no-repeat,no-repeat;
    background-position: 175px 23px, 200px 15px;
}


.left-panel li a.sb_active_opt + ul{ 
    display: block; 
}

.has_submenu{ background-image: url(<?php echo $this->webroot?>img/right_arrow.png);
    background-repeat: no-repeat;
    background-position: 175px 23px; }
    
.left-panel li a.has_submenu + ul{ 
    display: none; 
}

    .left-panel li a.sb_active_opt:hover, .left-panel li a.sb_active_opt:focus{ background-color: #3B5999 !important;
    color: #fff !important;
    vertical-align: top !important;
    background-image: url(<?php echo $this->webroot?>img/down_arrow.png), url(<?php echo $this->webroot?>img/aroow.png);
    background-repeat: no-repeat,no-repeat;
    background-position: 175px 23px, 200px 15px; 
}

.left-panel li li{ 
    border-bottom:0px !important;
}

.left-panel li li:first-child{ 
    border-top:0px !important;
}
.show{
    display: block;
}

.left-panel li a{ width: 210px; }

.left-panel{
padding-top: 12px;

background: rgba(255,255,255,1);
background: -moz-linear-gradient(left, rgba(255,255,255,1) 0px, rgba(255,255,255,1) 210px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 15px);
background: -webkit-gradient(left top, right top, color-stop(0px, rgba(255,255,255,1)), color-stop(210px, rgba(255,255,255,1)), color-stop(10px, rgba(242,242,242,1)), color-stop(10px, rgba(242,242,242,1)), color-stop(10px, rgba(242,242,242,1)), color-stop(10px, rgba(242,242,242,1)), color-stop(15px;, rgba(242,242,242,1)));
background: -webkit-linear-gradient(left, rgba(255,255,255,1) 0px, rgba(255,255,255,1) 210px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 15px);
background: -o-linear-gradient(left, rgba(255,255,255,1) 0px, rgba(255,255,255,1) 210px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 15px);
background: -ms-linear-gradient(left, rgba(255,255,255,1) 0px, rgba(255,255,255,1) 210px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 15px);
background: linear-gradient(to right, rgba(255,255,255,1) 0px, rgba(255,255,255,1) 210px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 10px, rgba(242,242,242,1) 15px);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f2f2f2', GradientType=1 );

}
</style>   
<?php $class="";
      $active_manage='has_submenu';
      $active_product='has_submenu';
      $active_pin='has_submenu';
      $active_setting='has_submenu';
      $active_ret='has_submenu';
?>

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
              <li><a id="deshboard" href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'dashboard')); ?>"><i class="icon-home"></i><?php echo __('Dashboard');?></a></li>
            
			   <?php if($login_user_roleid == 1) { ?>
			   
			    <li>
			        <a id="Mediator" href="javascript:;" class="hidden_sub <?php echo $active_manage; ?>"><i class="icon-user"></i><?php echo __('Mediator');?></a>
			    	<ul class ="navigation"> 
			            <li><a id="mediator_active" href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'manage_mediator')); ?>"><?php echo __('Mediator Personal Data');?></a></li>
			    		<li><a id="fund_m" href="<?php echo Router::url(array('controller'=>'Transactions', 'action'=>'manage_fund')); ?>"><?php echo __('Manage Mediator Fund');?></a></li>
			    		<li><a id="retailer_active" href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'view_retailer')); ?>"><?php echo __('View Retailers');?></a></li>
			    		<li><a id="manage_active" href="<?php echo Router::url(array('controller'=>'Cards', 'action'=>'manage_price','admin'=>true)); ?>"><?php echo __('Mediator Card Price');?></a></li> 
			           </ul>
			    </li>
			    <li>
			    	<a id="product" href="javascript:;" class="hidden_sub <?php echo $active_product; ?>"><i class="icon-book"></i><?php echo __('Product Master');?></a>
			    	<ul class ="navigation"> 
			            <li><a id="cat_active"  href="<?php echo Router::url(array('controller'=>'Categories', 'action'=>'index')); ?>"><?php echo __('Main Category');?></a></li>
			    		<li><a id="subcat_active" href="<?php echo Router::url(array('controller'=>'Categories', 'action'=>'subcategory')); ?>"><?php echo __('Sub Category');?></a></li>
			            <li><a id="addlist_active" href="<?php echo Router::url(array('controller'=>'Searches', 'action'=>'index','admin'=>true)); ?>"><?php echo __('Card List');?></a></li>

                        <li><a id="addcard_active" href="<?php echo Router::url(array('controller'=>'Cards', 'action'=>'admin_index')); ?>"><?php echo __('Card Management');?></a></li>
			    	   </ul>
			    </li>
			    <li>
			        <a id="pin" href="javascript:;" class="hidden_sub <?php echo $active_pin; ?>"><i class="icon-list-alt"></i><?php echo __('PIN Management');?></a>
			    	<ul class ="navigation"> 
			        	<li><a id="managecard_active" href="<?php echo Router::url(array('controller'=>'Pins', 'action'=>'admin_index')); ?>"><?php echo __('Manage card PINs');?></a></li>
			    	</ul>
			    </li>
			    <li>
			        <a id="setting" href="javascript:;" class="hidden_sub <?php echo $active_setting; ?>"><i class="icon-gear"></i><?php echo __('Settings');?></a>
			    	<ul class ="navigation"> 
			            <li><a id="websetting_active" href="<?php echo Router::url(array('controller'=>'Settings', 'action'=>'edit','Site')); ?>"><?php echo __('Edit Web Settings');?></a></li>
			    		<li><a id="emailcontent_active" href="<?php echo Router::url(array('controller'=>'EmailContents', 'action'=>'index')); ?>"><?php echo __('Email Content');?></a></li>
			    		<li><a id="lag_active" href="<?php echo Router::url(array('controller'=>'Languages', 'action'=>'index')); ?>"><?php echo __('Language Manage');?></a></li>
			    		<li><a id="cmspage_active" href="<?php echo Router::url(array('controller'=>'CmsPages', 'action'=>'index')); ?>"><?php echo __('CMS Manage');?></a></li>
			    		<li><a id="faq_active" href="<?php echo Router::url(array('controller'=>'Faqs', 'action'=>'index')); ?>"><?php echo __('Manage FAQ');?></a></li>
			    	   </ul>
			    </li>
			    <li><a class="sb_compose_mail" href="<?php echo Router::url(array('controller'=>'Mails', 'action'=>'index','admin'=>true)); ?>"><i class="icon-envelope"></i><?php echo __('Compose Email');?></a></li>
			    <li><a id="minimus_bal" href="<?php echo Router::url(array('controller'=>'Transactions', 'action'=>'minimum_balance_mediator','admin'=>true)); ?>"><i class="icon-briefcase"></i>
				 <?php echo __('Minimum Balance'); ?></a></li>
			
			    <?php } else { ?>
			    <li>
			        <a id="retailer" href="javascript:;" class="hidden_sub <?php echo $active_ret; ?>"><i class="icon-user"></i><?php echo __('Retailer');?></a>
			    	<ul class ="navigation"> 
			            <li><a id="manage_retailer" href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'manage_retailer')); ?>"><?php echo __('Manage Retailer');?></a></li>
			            <li><a id="manage_fund" href="<?php echo Router::url(array('controller'=>'Transactions', 'action'=>'manage_fund_retailer')); ?>"><?php echo __('Manage Fund');?></a></li>
			    		<li><a id="manage_price" href="<?php echo Router::url(array('controller'=>'Cards', 'action'=>'manage_price_mediator','admin'=>true)); ?>"><?php echo __('Manage Price');?></a></li>
			    	   </ul>
			    </li>
			    <li><a id="retailer_active"  href="<?php echo Router::url(array('controller'=>'Transactions', 'action'=>'minimum_balance_retailer','admin'=>true)); ?>"><i class="icon-briefcase"></i><?php echo __('Minimum Balance'); ?></a></li>
			    <li><a class="sb_compose_mail"  href="<?php echo Router::url(array('controller'=>'Mails', 'action'=>'index','admin'=>true)); ?>"><i class="icon-envelope"></i><?php echo __('Compose Mail');?></a></li>
			    <li><a id="online_card_opt" class="sb_online_card"href="<?php echo Router::url(array('controller'=>'Searches', 'action'=>'index')); ?>"><i class="icon-barcode"></i><?php echo __('Online Cards');?></a></li>
			  
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
$('.hidden_sub').click(function(){

	$(this).next().toggle('sliderup');

	$('a.sb_active_single_opt').removeClass('sb_active_single_opt');

	if(!$(this).hasClass('sb_active_opt')){

			$('a.sb_active_opt').each(function() {
            $('a.sb_active_opt + ul.navigation').slideUp( "slow");
            $(this).removeClass('sb_active_opt').addClass('has_submenu');
            
        	});
		
		$(this).addClass('sb_active_opt').removeClass('has_submenu');
		$('.navigation li:first-child a').addClass('sb_active_subopt');
		

    }else{
		
         $(this).removeClass('sb_active_opt');
        $(this).addClass('has_submenu');

    }
    
    
});

</script>      
