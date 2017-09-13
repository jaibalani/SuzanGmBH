<?php ?> 
<!--Top Panel Start-->
<div id="top-panel">
    <div id="title">
       <?php echo __('Retailer Account Info'); ?>
    </div>
    <div class="user-data">
        <div class="left" ><?php echo __('Customer No'); ?></div>
        <div class="right"><?php echo $this->Session->read('Auth.User.id');?></div>
    </div>

    <!-- <div class="user-data">
        <div class="left" ><?php //echo __('Account Number'); ?></div>
        <div class="right"><?php //echo $this->Session->read('Auth.User.username');?></div>
    </div>
    -->

    <div class="user-data">
        <div class="left" ><?php echo __('Today Sales'); ?>(&euro;)</div>
        <div class="right"><?php echo $todays_sales['sales_amount'];?></div>
    </div>
    
    <div class="user-data">
        <div class="left" ><?php echo __('Qty'); ?></div>
        <div class="right"><?php echo $todays_sales['sales_quantity'];?></div>
    </div>

    <div class="user-data bal-bg">
        <div class="left" ><?php echo __('Balance'); ?>(&euro;)</div>
        <?php if($avalable_balance <= 0) {?>
        <div class="right low_balance"><?php echo $avalable_balance; ?></div>
        <?php }  else {?>
        <div class="right"><?php echo $avalable_balance; ?></div>
       <?php } ?>
    </div>
</div>
<!--Top Panel End-->
<!--Left Navigation Start-->
<div id="navigation-panel" class="spacer12">
   
    <!--Navigation Menu Start-->
    <ul>
        <!--<li><h3 id="sb-opt-dashboard"><a href ="<?php //echo Router::url(array('controller'=>'Pages', 'action'=>'dashboard')); ?>">Home</a></h3></li>-->
        <li class="sb-has-submenu">
            <h3 id="sb-opt-online-card"><?php echo __('Online Cards')?></h3>
            <ul>
                <li><h3 id="sb-subopt-online-card"><a href="<?php echo Router::url(array('controller'=>'Searches', 'action'=>'online_card')); ?>"><?php echo __('Online Cards')?></a></h3></li>
                <li><h3 id="sb-subopt-shopping"><a href="<?php echo Router::url(array('controller'=>'Carts', 'action'=>'view')); ?>"><?php echo __('Shopping Cart')?></a></h3></li>
            </ul>
        </li>
         <li class="sb-has-submenu">
            <h3 id="sb-opt-users"><?php echo __('Users');?></h3>
            <ul>
				<li><h3 id="sb-subopt-user-info"><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'profile_manage',$this->Session->read('Auth.User.id'))); ?>"><i class="icon-envelope"></i><?php echo __('User Information');?></a></h3></li>
            </ul>
        </li>
       
        <li><h3 id="sb-opt-manage-price"><a href="<?php echo Router::url(array('controller'=>'Cards', 'action'=>'manage_price_retailer')); ?>"><i class="icon-envelope"></i><?php echo __('Manage Price');?></a></li>
        
        <li><h3 id="sb-opt-invoice"><a href="<?php echo Router::url(array('controller'=>'Invoices', 'action'=>'index')); ?>"><?php echo __('Invoice');?></a></h3></li>
        
        <li class="sb-has-submenu">
           <h3 id="sb-opt-report"><?php echo __('Report');?></h3>
           <ul>
              <li><h3 id="sb-subopt-sales-report"><a href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'sales_report')); ?>"><?php echo __('Sales Report')?></a></h3></li>
              <li><h3 id="sb-subopt-sales-report_detailed"><a href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'detailed_sales_report')); ?>"><?php echo __('Detailed Sales Report')?></a></h3></li>
              <li><h3 id="sb-subopt-profit-report"><a href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'profit_report')); ?>"><?php echo __('Profitability Report');?></a></h3></li>
              <li><h3 id="sb-subopt-daily-report"><a href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'daily_sales')); ?>"><?php echo __('Daily Sales Report');?></a></h3></li>
              <li><h3 id="sb-subopt-today-sale"><a href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'today_sale')); ?>"><?php echo __("Today's Sale");?></a></h3></li>
	          <li><h3 id="sb-subopt-account-info"><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'account_information')); ?>"><i class="icon-envelope"></i><?php echo __('Contract');?></a></h3></li>
           </ul>
        </li>
        <li><h3 id="sb-opt-Mail"><a href="<?php echo Router::url(array('controller'=>'Mails', 'action'=>'index')); ?>"><?php echo __('Mail Option')?></a></h3></li>
        <!--<li><h3><a href="#">Test Card</a></h3></li> -->
    </ul>
    <!--Navigation Menu End-->

</div>
<!--Left Navigation End-->
                        
                   
 
	
                    
	
