<style type="text/css">
	
	#sb-left-panel11{ position: absolute;
		margin-left: -225px;
		min-height: 98%;
		display: inline-block; }

	#sb-left-panel11 ul.left-panel{ position:inherit !important; margin-top: -47px; height:100%;}
	#sb-left-panel11 li a{ color: #585858; }
	
	
</style>
<?php $class="";
      $active_manage='has_submenu';
      $active_product='has_submenu';
      $active_pin='has_submenu';
      $active_setting='has_submenu';
      $active_ret='has_submenu';
?>
	<div id="sb-left-panel11">
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
                <li>
			        <a id="reports" href="javascript:;" class="hidden_sub <?php echo $active_ret; ?>"><i class="icon-book"></i><?php echo __('Reports');?></a>
			    	<ul class ="navigation"> 
			            <li><a id="sales_report" href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'sales_report_distributor')); ?>"><?php echo __('Sales Report');?></a></li>
			            <li><a id="daily_sales_report" href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'daily_sales_distributor')); ?>"><?php echo __('Daily Sales Report');?></a></li>
			    		<li><a id="profitability_report" href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'profit_profile_distributor','admin'=>true)); ?>"><?php echo __('Profitability Report');?></a></li>
			    		<li><a id="balance_report" href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'balance_report_distributor','admin'=>true)); ?>"><?php echo __('Retailer Balance Report');?></a></li>
			    		<li><a id="mediator_balance_report" href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'mediator_balance_report_distributor','admin'=>true)); ?>"><?php echo __('Mediator Balance Report');?></a></li>
                        <li><a id="card_inventory_report" href="<?php echo Router::url(array('controller'=>'Cards', 'action'=>'card_inventory','admin'=>true)); ?>"><?php echo __('Card Inventory Report');?></a></li>
                                                
			    	   </ul>
			    </li>
			    <li><a id="manage-front-image" class="sb_front_image" href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'front_image','admin'=>true)); ?>"><i class="icon-picture"></i><?php echo __('Manage Front Images');?></a></li>
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
                <li>
			        <a id="reports" href="javascript:;" class="hidden_sub <?php echo $active_ret; ?>"><i class="icon-book"></i><?php echo __('Reports');?></a>
			    	<ul class ="navigation"> 
			            <li><a id="sales_report" href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'sales_report')); ?>"><?php echo __('Sales Report');?></a></li>
			            <li><a id="daily_sales_report" href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'daily_sales')); ?>"><?php echo __('Daily Sales Report');?></a></li>
			    		<li><a id="profitability_report" href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'profit_profile','admin'=>true)); ?>"><?php echo __('Profitability Report');?></a></li>
			    		<li><a id="retailer_balance_report" href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'retailer_balance_report','admin'=>true)); ?>"><?php echo __('Retailer Balance Report');?></a></li>
                                                
			    	   </ul>
			    </li>
			    <li><a id="retailer_active"  href="<?php echo Router::url(array('controller'=>'Transactions', 'action'=>'minimum_balance_retailer','admin'=>true)); ?>"><i class="icon-briefcase"></i><?php echo __('Minimum Balance'); ?></a></li>
			    <li><a class="sb_compose_mail"  href="<?php echo Router::url(array('controller'=>'Mails', 'action'=>'index','admin'=>true)); ?>"><i class="icon-envelope"></i><?php echo __('Compose Mail');?></a></li>
			    <li><a id="online_card_opt" class="sb_online_card"href="<?php echo Router::url(array('controller'=>'Searches', 'action'=>'index')); ?>"><i class="icon-barcode"></i><?php echo __('Online Cards');?></a></li>
			  
			  <?php } ?>
			 		<li><a id="custom_invoice_opt" href="<?php echo Router::url(array('controller'=>'Pages', 'action'=>'invoice_list')); ?>"><i class="icon-euro"></i><?php echo __('Invoices');?></a></li>
			 
			     <li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'logout')); ?>"><i class="icon-power-off"></i> <?php echo __('Log Out');?></a></li>
		  </ul>
	</div>