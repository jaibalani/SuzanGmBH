<?php 
          $sales_counter = 0; 
          if(!empty($final_card_sale_bills)) 
          { 
				      foreach ($final_card_sale_bills as $card_sales) 
              { 
                //prd($card_sales); 
                if($sales_counter%$card_per_page == 0 && $sales_counter != 0 ) 
                {
                ?>
                 <p style="page-break-before: always">&nbsp;</p>
                <?php  }
              $sales_counter++;
    ?>
    <div style="border: 1px solid #CCCCCC;padding: 10px;margin-bottom: 10px; height: auto; float: left;width: 640px;font-size:10px;"> 
           <br/>
          <div style="float: left; width: 20%;">
                <?php echo $this->Html->image($card_sales['card_image'],array('alt'=>'Card Icon','width'=>'110px;')); ?>
          </div>
          
          <div style="float: right; width: 80%;"> 
              
              <div style="text-align:left;width: 50%;float:left;" class="class_font"><?php echo __('Card')." : "."<b>".$card_sales['card_name']."</b>"; ?></div>
              
              <div style="text-align:left;width: 50%;float:left;" class="class_font"><?php echo __('Retailer')." : "."<b>".ucwords($this->Session->read('Auth.User.fname')." ".$this->Session->read('Auth.User.lname'))."</b>";?></div>
              
              <div style="text-align:left;width: 50%;float:left;margin-top:5px; " class="class_font"><?php echo __('Serial Number')." : "."<b>".$card_sales['serial']."</b>"; ?></div>

              <div style="text-align:left;width: 50%;float:left;margin-top: 5px;" class="class_font"><?php echo __('Pin Number')." : "."<b>".$card_sales['pins']."</b>"; ?></div>
              
              <div style="text-align:left;width: 50%;float:left;margin-top: 5px;" class="class_font"><?php echo __('Price')." : "."<b>&euro;&nbsp".$card_sales['selling_price']."</b>"; ?>
              </div>
              
              <div style="text-align:left;width: 50%;float:left;margin-top: 5px;" class="class_font"><?php echo __('Date')." : "."<b>".$card_sales['s_date']."</b>"; ?></div>

              <div style="text-align:left;width: 50%;float:left;margin-top: 5px;" class="class_font"><?php echo __('URL')." : "."<b>".APPLICATION_PATH."</b>"; ?></div>

              <div style="text-align:left;width: 34%;float:left; margin-top: 5px;" class="class_font"><?php echo __('Dial')." : "."<b>".$card_sales['contact']."</b>"; ?></div>

              <div style="text-align:left;width: 100%;float:left; margin-top: 5px;" class="class_font"><?php echo __('Contact')." : "."<b>".$card_sales['local_number']."</b>"; ?></div>

               <div style="text-align:left;width: 100%;float:left; margin-top: 5px;" class="class_font"><?php echo __('Hotline Number')." : "."<b>".$card_sales['hotline_number']."</b>"; ?></div>
          </div>

          <div style="text-align:left;width: 100%;float:left; margin-top: 5px;" class="class_font">
            <?php 
             if(strlen($card_sales['free_text']) >150)
             $card_sales['free_text'] = substr($card_sales['free_text'], 0,150)."...";
             echo $card_sales['free_text']; ?>
          </div>
          
      </div>
     <?php } ?>
     <?php } else { ?>
     <div> 
     <div colspan="11" align="center"><?php echo __('No records found.');?></div>
     </div>
     <?php }  exit; ?>
    <!--Bill End-->
