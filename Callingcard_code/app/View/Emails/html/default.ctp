<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Emails.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
 
?>
<div align="center">
  <div style="width:600px;margin-top:50px;">
  
    <div style="clear:both;width:585px;float:left;margin-top:-32px;padding: 5px 0 5px 14px;border:1px solid #CCCCCC;">
      <div style="text-align:center;">
      <a target="_new" href="<?php echo Router::url('/', true).'Pages/dashboard'?>">
         <img src="<?=$this->Html->url( '/', true ).'img/emailTemplate/email_logo.png'?>" alt="Calling Card" border="0"/>
      </a>
      </div>    
    </div>

    <div style="clear:both;width:583px;border:1px solid #CCC;text-align:justify;padding-left:15px;" >
        <div style="clear:both; min-height:300px;height:auto;text-align:justify;margin:-31px 5px 5px;background:#FFF;float:left;width:550px;">    
       <!--Message-->
         <?=$dataForView['mail_message']?>
      </div>
        <div style="clear:both;"></div>
        <!--<div style="clear:both; margin-bottom:-31px;color:#FF9393;width:96%;text-align:center;font-size:20px; font-family:Arial, Helvetica, sans-serif;">Calling Card Tag Line</div>-->
    </div>

    <?php /*?><div style="clear:both;width:600px;height:170px; margin-top:-49px;border:1px solid #343434;background:url('<?=$this->Html->url( '/', true ).'img/front-bg.png'?>') no-repeat scroll 20% 40% / cover  rgba(0, 0, 0, 0);"><?php */?>            
       <div style="clear:both;color:#ffffff;width:600px;text-align:center;font-size:14px; margin-top:50pxpx;font-family:Arial, Helvetica, sans-serif;">
         &copy; <?php echo date('Y');?> Calling Card All Right Reserved
      </div>
    <!--</div>-->
   
   </div>
 </div>