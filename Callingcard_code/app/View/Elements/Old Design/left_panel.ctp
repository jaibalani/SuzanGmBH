<?php ?> 
 <div id="top_panel">
    <div id="title">
        RETAILER ACCOUNT INFO
    </div>
                        
    <div class="user_data">
        <div class="left" ><?php echo __('Customer No'); ?></div>
        <div class="right"><?php echo $this->Session->read('Auth.User.id');?></div>
    </div>
                        
    <div class="user_data">
        <div class="left" ><?php echo __('Today Sales (&euro;) '); ?></div>
        <div class="right"><?php echo $todays_sales['sales_amount'];?></div>
    </div>
                        
    <div class="user_data">
        <div class="left" ><?php echo __('Qty'); ?></div>
        <div class="right"><?php echo $todays_sales['sales_quantity'];?></div>
    </div>
                        
    <div class="user_data bal_bg">
        <div class="left" ><?php echo __('BALANCES (&euro;)'); ?></div>
        <div class="right"><?php echo $avalable_balance; ?></div>
    </div>
                            
</div>	
                    
<div id="bottom_panel">
<div class="vertical_menu">
    <div id="accordian">
        <ul>
            <li>
                <h3><a href="<?php echo Router::url(array('controller'=>'Pages', 'action'=>'dashboard')); ?>"><i class="icon-home"></i><?php echo __('Home');?></a></h3>
            </li>
           
            <li>
                <h3><a href="<?php echo Router::url(array('controller'=>'Cards', 'action'=>'manage_price_retailer')); ?>"><i class="icon-envelope"></i><?php echo __('Manage Price');?></a></h3></li>
           
           <!-- used to hight light--class="active_opt"-->
            <li class="is_menu">
                <h3 style="cursor:pointer">Online Cards</h3>
                <ul>
                  <li><a href="<?php echo Router::url(array('controller'=>'Searches', 'action'=>'index')); ?>"><i class="icon-barcode"></i><?php echo __('Online Cards');?></a></li>
                  <li><a href="<?php echo Router::url(array('controller'=>'Carts', 'action'=>'view')); ?>">Shopping Cart</a></li>
                  <li><a href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'today_sale')); ?>">Today's Sale</a></li>
                </ul>
            </li>
            <li class="is_menu">
                <h3 style="cursor:pointer">User</h3>
                <ul>
                  <li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'profile_manage',$this->Session->read('Auth.User.id'))); ?>"><i class="icon-envelope"></i><?php echo __('User Information');?></a></li>
                  <li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'account_information')); ?>"><i class="icon-envelope"></i><?php echo __('Account Information');?></a></li>
                </ul>
                
            </li>
            <!--<li class="is_menu">
                <h3><a href="#">Contract</a></h3>
                <ul>
                    <li><a href="#">Load History(trans. page )</a></li>
                    <li><a>My Discount</a></li>
                </ul>
            </li>-->
            
            <li class="is_menu">
                <h3><a href="#">Invoice</a></h3>
                <ul>
                    <li><a href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'sales_report')); ?>"><?php echo __('Sales Report');?></a></li>
                    <li><a href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'profit_report')); ?>">Profitabilty Report</a></li>
                    <li><a href="<?php echo Router::url(array('controller'=>'Reports', 'action'=>'daily_sales')); ?>">Daily Sales Report</a></li>
                </ul>
            </li>
            
            <li>
                <h3><a href="<?php echo Router::url(array('controller'=>'Mails', 'action'=>'index')); ?>"><i class="icon-envelope"></i><?php echo __('Mail Option');?></a></h3>
            </li>
           <!-- <li>
                <h3><a href="#">Test Card</a></h3>
            </li>-->
        </ul>
        </div>
   
    
</div>
        
</div>	
