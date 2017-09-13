<style type="text/css">
#card-list{ width: 760px; display: inline-block; margin-bottom: -5px;}
.buy-card-details-left{ width: 380px; min-height: 360px; /*height: 368px;*/ float: left; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;overflow: hidden;}
.buy-card-details-right{ width: 378px; min-height: 360px; /*height: 368px;*/ float: left; border-bottom: 1px solid #ccc;overflow: hidden;}
.buy-card-info{ width: 168px; min-height: 240px; /*height: 348px;*/ border:1px solid #ccc; margin: 35px 10px; margin-bottom: 0px; margin-top: 20px;}
.buy-card-img{padding-top: 10px; padding-bottom: 5px; border-bottom:1px solid #ccc; position: relative; }
.buy-card-img span{ font-size: 10px; }
.buy-card-rate{position: absolute;font-weight: bold;top: 14px;right: 2px; font-size: 11px; }
.cards-number{ border-bottom:1px solid #ccc; padding: 5px 0px; text-align: left; padding-left: 8px; font-size: 9px; }
.pin-number{ border-bottom:1px solid #ccc; font-weight: bold; font-size: 12px; padding: 5px 0px; text-align: left; padding-left: 8px; }
.pin-number span{ font-weight: normal; font-size: 12px;}
.buy-other-details{ border-bottom:1px solid #ccc; padding: 5px 0px; text-align: left; padding-left: 8px; font-size: 8px; padding-right: 4px;}
.buy-card-info .buy-other-details:last-child{ border-bottom:0px; }
.buy-empty{ border:0px; }
.buy-other-details span{ word-break: break-word; white-space: normal; }
.buy-card-img span{ word-break: break-word; white-space: normal; }
.sb-buy-serial-no{ font-size: 9px;}
.cards-number{ font-size: 11px; }
.pin-number{ font-size: 14px; }
.buy-card-info{ width: 220px; }
.buy-card-img img{ width: 128px; }
.buy-other-details{ font-size: 10px; }
.buy-card-details-left{ height: 360px; }
.buy-card-details-right{ height: 360px; }
</style>
      <div id="card-list" align="center">
        <?php 
          $sales_counter = 0; 
          $total_rec = count($final_card_sale_bills);
          if(!empty($final_card_sale_bills)) 
          { 
              foreach ($final_card_sale_bills as $card_sales){ 
              $sales_counter++;
              if($card_per_page == 1){
        ?>
                  <div class="buy-card-details-left">
                      <div class="buy-card-info">
                          <div class="buy-card-img">
                              <?php echo $this->Html->image($card_sales['card_image'],array('alt'=>'Card Icon','width'=>'90px;','height' => '60px;')); ?><br/>
                                <span>
                                    <strong><?php echo $card_sales['card_name']?></strong>
                                </span>
                                <div class="buy-card-rate">&euro;<?php echo number_format($card_sales['selling_price'],1);?></div>
                          </div>
                          <div class="cards-number">
                                <span><?php echo __('Cell Number');?>: <?php echo $card_sales['contact'];?></span><br/>
                              <span><?php echo __('Local Number1');?>: <?php echo $card_sales['local_number'][0];?></span><br/>
                              <span><?php echo __('Local Number2');?>: <?php echo $card_sales['local_number'][1];?></span><br/>
                              <span><?php echo __('Local Number3');?>: <?php echo $card_sales['local_number'][2];?></span><br/>
                              <span><?php echo __('Local Number4');?>: <?php echo $card_sales['local_number'][3];?></span><br/>
                              <span><?php echo __('Local Number5');?>: <?php echo $card_sales['local_number'][4];?></span><br/>
                              <span><?php echo __('Local Number6');?>: <?php echo $card_sales['local_number'][5];?></span><br/>
                          </div>
                          <div class="pin-number">
                            <?php echo __('Pin Number');?>: <span><?php echo $card_sales['pins'];?></span>
                          </div>
                           <?php 
                                if(isset($card_sales['free_text']) && $card_sales['free_text'] != ''){
                                    if(strlen($card_sales['free_text']) >105)
                                        $card_sales['free_text'] = substr($card_sales['free_text'], 0,105)."...";
                           ?>
                           <div class="buy-other-details">
                              <?php echo 'Description Text: '.$card_sales['free_text'];?>
                          </div>
                           <?php           
                                }else{
                                  echo '<div class="buy-other-details">';
                                  echo 'Description Text: N/A';
                                  echo '</div>';
                                }
                           ?>
                          <div class="buy-other-details">
                              <span class="sb-buy-serial-no"><?php echo __('Serial Number');?>: <?php echo $card_sales['serial'];?></span><br/>
                              <span><?php echo __('Retailer');?>: <?php echo ucwords($this->Session->read('Auth.User.fname')." ".$this->Session->read('Auth.User.lname'));?></span><br/>
                              <span><?php echo APPLICATION_PATH;?></span><br/>
                          </div>
                          <div class="buy-other-details">
                              <?php echo $card_sales['s_date'];?>
                          </div>
                      </div>
                  </div>
                  <div class="buy-card-details-right">
                        <div class="buy-card-info buy-empty">

                        </div>
                    </div>
                    <div class="buy-card-details-left">
                        <div class="buy-card-info buy-empty">

                        </div>
                    </div>
                    <div class="buy-card-details-right">
                        <div class="buy-card-info buy-empty">

                        </div>
                    </div>
                    <div class="buy-card-details-left">
                        <div class="buy-card-info buy-empty">

                        </div>
                    </div>
                    <div class="buy-card-details-right">
                        <div class="buy-card-info buy-empty">

                        </div>
                    </div>
                    <?php if($total_rec != $sales_counter){?>
                    <p style="page-break-before: always">&nbsp;</p>
                    <?php } ?>
              <?php
              }elseif($card_per_page == 4){

                  ?>

                  <?php
                  if($sales_counter % 2 == 0 && $sales_counter != 0){
                  ?>
                  <div class="buy-card-details-right">
                        <div class="buy-card-info">
                            <div class="buy-card-img">
                                <?php echo $this->Html->image($card_sales['card_image'],array('alt'=>'Card Icon','width'=>'90px;','height' => '60px;')); ?><br/>
                                  <span>
                                      <strong><?php echo $card_sales['card_name']?></strong>
                                  </span>
                                  <div class="buy-card-rate">$<?php echo number_format($card_sales['selling_price'],1);?></div>
                            </div>
                            <div class="cards-number">
                                  <span><?php echo __('Cell Number');?>: <?php echo $card_sales['contact'];?></span><br/>
                                  <span><?php echo __('Local Number1');?>: <?php echo $card_sales['local_number'][0];?></span><br/>
                                  <span><?php echo __('Local Number2');?>: <?php echo $card_sales['local_number'][1];?></span><br/>
                                  <span><?php echo __('Local Number3');?>: <?php echo $card_sales['local_number'][2];?></span><br/>
                                  <span><?php echo __('Local Number4');?>: <?php echo $card_sales['local_number'][3];?></span><br/>
                                  <span><?php echo __('Local Number5');?>: <?php echo $card_sales['local_number'][4];?></span><br/>
                                  <span><?php echo __('Local Number6');?>: <?php echo $card_sales['local_number'][5];?></span><br/>
                            </div>
                            <div class="pin-number">
                              <?php echo __('Pin Number');?>: <span><?php echo $card_sales['pins'];?></span>
                            </div>
                            <?php 
                                  if(isset($card_sales['free_text']) && $card_sales['free_text'] != ''){
                                      if(strlen($card_sales['free_text']) >105)
                                          $card_sales['free_text'] = substr($card_sales['free_text'], 0,105)."...";
                             ?>
                             <div class="buy-other-details">
                                <?php echo 'Description Text: '.$card_sales['free_text'];?>
                            </div>
                             <?php           
                                  }else{
                                  echo '<div class="buy-other-details">';
                                  echo 'Description Text: N/A';
                                  echo '</div>';
                              }
                             ?>
                            <div class="buy-other-details">
                                <span class="sb-buy-serial-no"><?php echo __('Serial Number');?>: <?php echo $card_sales['serial'];?></span><br/>
                                <span><?php echo __('Retailer');?>: <?php echo  ucwords($this->Session->read('Auth.User.fname')." ".$this->Session->read('Auth.User.lname'));?></span><br/>
                                <span><?php echo APPLICATION_PATH;?></span><br/>
                            </div>
                            <div class="buy-other-details">
                                <?php echo $card_sales['s_date'];?>
                            </div>
                        </div>
                    </div>
                  <?php
                  }else{
                  ?>
                  <div class="buy-card-details-left">
                        <div class="buy-card-info">
                            <div class="buy-card-img">
                                <?php echo $this->Html->image($card_sales['card_image'],array('alt'=>'Card Icon','width'=>'90px;','height' => '60px;')); ?><br/>
                                  <span>
                                      <strong><?php echo $card_sales['card_name']?></strong>
                                  </span>
                                  <div class="buy-card-rate">$<?php echo number_format($card_sales['selling_price'],1);?></div>
                            </div>
                            <div class="cards-number">
                                  <span><?php echo __('Cell Number');?>: <?php echo $card_sales['contact'];?></span><br/>
                                  <span><?php echo __('Local Number1');?>: <?php echo $card_sales['local_number'][0];?></span><br/>
                                  <span><?php echo __('Local Number2');?>: <?php echo $card_sales['local_number'][1];?></span><br/>
                                  <span><?php echo __('Local Number3');?>: <?php echo $card_sales['local_number'][2];?></span><br/>
                                  <span><?php echo __('Local Number4');?>: <?php echo $card_sales['local_number'][3];?></span><br/>
                                  <span><?php echo __('Local Number5');?>: <?php echo $card_sales['local_number'][4];?></span><br/>
                                  <span><?php echo __('Local Number6');?>: <?php echo $card_sales['local_number'][5];?></span><br/>
                            </div>
                            <div class="pin-number">
                              <?php echo __('Pin Number');?>: <span><?php echo $card_sales['pins'];?></span>
                            </div>
                             <?php 
                                  if(isset($card_sales['free_text']) && $card_sales['free_text'] != ''){
                                      if(strlen($card_sales['free_text']) >105)
                                          $card_sales['free_text'] = substr($card_sales['free_text'], 0,105)."...";
                             ?>
                             <div class="buy-other-details">
                                <?php echo 'Description Text: '.$card_sales['free_text'];?>
                            </div>
                             <?php           
                                  }else{
                                  echo '<div class="buy-other-details">';
                                  echo 'Description Text: N/A';
                                  echo '</div>';
                              }
                             ?>
                            <div class="buy-other-details">
                                <span class="sb-buy-serial-no"><?php echo __('Serial Number');?>: <?php echo $card_sales['serial'];?></span><br/>
                                <span><?php echo __('Retailer');?>: <?php echo ucwords($this->Session->read('Auth.User.fname')." ".$this->Session->read('Auth.User.lname'));?></span><br/>
                                <span><?php echo APPLICATION_PATH;?></span><br/>
                            </div>
                            <div class="buy-other-details">
                                <?php echo $card_sales['s_date'];?>
                            </div>
                        </div>
                  </div>
                  <?php
                  }
                  if($sales_counter%$card_per_page == 0 && $sales_counter != 0 ) 
                   {
                ?>
                  <div class="buy-card-details-left">
                        <div class="buy-card-info buy-empty">

                        </div>
                    </div>
                  <div class="buy-card-details-right">
                        <div class="buy-card-info buy-empty">

                        </div>
                  </div>
                  <?php 
                        if($sales_counter%$card_per_page == 0 && $sales_counter != 0 && $sales_counter != $total_rec) 
                        {
                    ?>
                      <p style="page-break-before: always">&nbsp;</p>
                  <?php } ?>
              <?php }
              }elseif ($card_per_page == 6) {
                    if($sales_counter % 2 == 0 && $sales_counter != 0){
                ?>
                      <div class="buy-card-details-right">
                          <div class="buy-card-info">
                              <div class="buy-card-img">
                                  <?php echo $this->Html->image($card_sales['card_image'],array('alt'=>'Card Icon','width'=>'90px;','height' => '60px;')); ?><br/>
                                    <span>
                                        <strong><?php echo $card_sales['card_name']?></strong>
                                    </span>
                                    <div class="buy-card-rate">$<?php echo number_format($card_sales['selling_price'],1);?></div>
                              </div>
                              <div class="cards-number">
                                    <span><?php echo __('Cell Number');?>: <?php echo $card_sales['contact'];?></span><br/>
                                    <span><?php echo __('Local Number1');?>: <?php echo $card_sales['local_number'][0];?></span><br/>
                                    <span><?php echo __('Local Number2');?>: <?php echo $card_sales['local_number'][1];?></span><br/>
                                    <span><?php echo __('Local Number3');?>: <?php echo $card_sales['local_number'][2];?></span><br/>
                                    <span><?php echo __('Local Number4');?>: <?php echo $card_sales['local_number'][3];?></span><br/>
                                    <span><?php echo __('Local Number5');?>: <?php echo $card_sales['local_number'][4];?></span><br/>
                                    <span><?php echo __('Local Number6');?>: <?php echo $card_sales['local_number'][5];?></span><br/>
                              </div>
                              <div class="pin-number">
                                <?php echo __('Pin Number');?>: <span><?php echo $card_sales['pins'];?></span>
                              </div>
                              <?php 
                                    if(isset($card_sales['free_text']) && $card_sales['free_text'] != ''){
                                        if(strlen($card_sales['free_text']) >105)
                                            $card_sales['free_text'] = substr($card_sales['free_text'], 0,105)."...";
                               ?>
                               <div class="buy-other-details">
                                  <?php echo 'Description Text: '.$card_sales['free_text'];?>
                              </div>
                               <?php           
                                    }else{
                                  echo '<div class="buy-other-details">';
                                  echo 'Description Text: N/A';
                                  echo '</div>';
                                }
                               ?>
                              <div class="buy-other-details">
                                  <span class="sb-buy-serial-no"><?php echo __('Serial Number');?>: <?php echo $card_sales['serial'];?></span><br/>
                                  <span><?php echo __('Retailer');?>: <?php echo  ucwords($this->Session->read('Auth.User.fname')." ".$this->Session->read('Auth.User.lname'));?></span><br/>
                                  <span><?php echo APPLICATION_PATH;?></span><br/>
                              </div>
                              <div class="buy-other-details">
                                  <?php echo $card_sales['s_date'];?>
                              </div>
                          </div>
                      </div>
                <?php
                    }else{
                ?>
                      <div class="buy-card-details-left">
                          <div class="buy-card-info">
                              <div class="buy-card-img">
                                  <?php echo $this->Html->image($card_sales['card_image'],array('alt'=>'Card Icon','width'=>'90px;','height' => '60px;')); ?><br/>
                                    <span>
                                        <strong><?php echo $card_sales['card_name']?></strong>
                                    </span>
                                    <div class="buy-card-rate">$<?php echo number_format($card_sales['selling_price'],1);?></div>
                              </div>
                              <div class="cards-number">
                                    <span><?php echo __('Cell Number');?>: <?php echo $card_sales['contact'];?></span><br/>
                                    <span><?php echo __('Local Number1');?>: <?php echo $card_sales['local_number'][0];?></span><br/>
                                    <span><?php echo __('Local Number2');?>: <?php echo $card_sales['local_number'][1];?></span><br/>
                                    <span><?php echo __('Local Number3');?>: <?php echo $card_sales['local_number'][2];?></span><br/>
                                    <span><?php echo __('Local Number4');?>: <?php echo $card_sales['local_number'][3];?></span><br/>
                                    <span><?php echo __('Local Number5');?>: <?php echo $card_sales['local_number'][4];?></span><br/>
                                    <span><?php echo __('Local Number6');?>: <?php echo $card_sales['local_number'][5];?></span><br/>
                              </div>
                              <div class="pin-number">
                                <?php echo __('Pin Number');?>: <span><?php echo $card_sales['pins'];?></span>
                              </div>
                               <?php 
                                    if(isset($card_sales['free_text']) && $card_sales['free_text'] != ''){
                                        if(strlen($card_sales['free_text']) >105)
                                            $card_sales['free_text'] = substr($card_sales['free_text'], 0,105)."...";
                               ?>
                               <div class="buy-other-details">
                                  <?php echo 'Description Text: '.$card_sales['free_text'];?>
                              </div>
                               <?php           
                                    }else{
                                  echo '<div class="buy-other-details">';
                                  echo 'Description Text: N/A';
                                  echo '</div>';
                                }
                               ?>
                              <div class="buy-other-details">
                                  <span class="sb-buy-serial-no"><?php echo __('Serial Number');?>: <?php echo $card_sales['serial'];?></span><br/>
                                  <span><?php echo __('Retailer');?>: <?php echo ucwords($this->Session->read('Auth.User.fname')." ".$this->Session->read('Auth.User.lname'));?></span><br/>
                                  <span><?php echo APPLICATION_PATH;?></span><br/>
                              </div>
                              <div class="buy-other-details">
                                  <?php echo $card_sales['s_date'];?>
                              </div>
                          </div>
                      </div>
                <?php
                    }
                     if($sales_counter%$card_per_page == 0 && $sales_counter != 0 && $sales_counter != $total_rec) 
                        {
                    ?>
                      <p style="page-break-before: always">&nbsp;</p>
                  <?php } 
              }
              ?>

            <?php } 
                if($total_rec%$card_per_page != 0){
                    $extra_divs = 6 - $total_rec%$card_per_page;
                   // echo '<br/>Extra div'.$extra_divs;
                    for($i=0;$i<$extra_divs;$i++){
                        $sales_counter++;
                        if($sales_counter % 2 != 0){
                          ?>
                          <div class="buy-card-details-left">
                              <div class="buy-card-info buy-empty">
                              </div>
                          </div>
                          <?php }else{ ?>
                          <div class="buy-card-details-right">
                              <div class="buy-card-info buy-empty">
                              </div>
                          </div>
                          <?php
                        }
                    }
                } 
            ?>
          </div>

     <?php } else { ?>
     <div> 
     <div colspan="11" align="center"><?php echo __('No records found.');?></div>
     </div>
     <?php }  exit; ?>
    <!--Bill End-->