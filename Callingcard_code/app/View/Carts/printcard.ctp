<?php ?>
<style type="text/css">

.main_print_div{
width:620px;
vertical-align:middle;
}
#print_details{
width:100% !important;
margin-left:unset;
padding-left:20px;
}
#single_card_detail{
width:100%;
}
#other_details{
width:100%;
}
#other_details p{
padding-left:25px;
min-height:40px;
}
#other_details p label{
padding-right:0px !important;
width:105px;
float:left;
min-height:40px;
}
.class_font{
    font-size: 12px;
}
.serial_pins{
width:80%;
white-space: pre;
word-wrap: break-word; /* IE 5.5+ and CSS3 */ 
white-space: pre-wrap; /* CSS3 */  
white-space: -moz-pre-wrap; /* Mozilla, since 1999 */  
white-space: -pre-wrap; /* Opera 4-6 */ 
white-space: -o-pre-wrap; /* Opera 7 */ 
height:auto !important;
display: inline-block;
}
#download_excel,#print,#download_csv{
   
    border: 0 none;
    color: #fff;
    font-weight: bold;
    padding: 7px 20px;
    background: linear-gradient(to bottom, rgba(32, 124, 202, 1) 19%, rgba(30, 87, 153, 1) 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);

}
.pagebreak
{
       display: none;
}
@media print{
   .pagebreak
   {
       display: block;
       page-break-after: always;
   }
   
   .class_font
   {
     font-size: 8px;    
   }
   
} 
.cards-number{ font-size: 11px; }
.pin-number{ font-size: 14px; }
.buy-card-info{ width: 238px; }
.buy-card-img img{ width: 128px; }
.buy-other-details{ font-size: 10px; }
.buy-card-details-left{ height: 420px; }
.buy-card-details-right{ height: 420px; }
</style>
<div class="right-part right-panel">
                        
    <!--Bill Start-->
    <div class="sb-page-title">
        <strong><?php echo __('Print Bill');?></strong>
    </div>
    
    <div id="print_bill">
        <div id="print_panel">
                <div class="left-part" id="page_size">Select Card Per Page</div> 
                <div class="left-part">
                <select class="form-control selectbox_graditent" id="paper_size">
                    <option value="1">1</option>
                    <option value="4">4</option>
                    <option value="6">6</option>
                </select>
                </div>
                <div class="right-part">
                <input type="button" name="" value="<?php echo __('Print');?>" class="button-gradient" id="print" />
                </div>
                <!-- <div class="right-part">
                <input type="button" name="" value="<?php echo __('Download Excel');?>" class="button-gradient" id="download_excel" />
                </div> -->
                <div class="right-part">
                <input type="button" name="" value="<?php echo __('Download CSV');?>" class="button-gradient" id="download_csv" />
                </div>
        </div> 
      
        <div id="sales-reports"  class="main_print_div">
            <div id="card-list">
              <?php 
                //pr($this->Session->read('Auth.User.fname'));
                  $sales_counter = 0; 
                  if(!empty($final_card_sale_bills)) { 
               
                    foreach ($final_card_sale_bills as $card_sales) 
                    { 
                                     $sales_counter++;
                        if($sales_counter % 2 != 0){
              ?>
                <div class="buy-card-details-left">
                    <div class="buy-card-info">
                        <div class="buy-card-img">
                            <?php echo $this->Html->image($card_sales['card_image'],array('alt'=>'Card Icon','width'=>'90px;','height' => '60px;')); ?><br/>
                              <span>
                                  <strong><?php echo $card_sales['card_name']?></strong>
                              </span>
                              <div class="buy-card-rate">&euro;<?php echo number_format($card_sales['selling_price'],2);?></div>
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
                            <?php echo $card_sales['free_text'];?>
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
                <?php }else{?>
                <div class="buy-card-details-right">
                    <div class="buy-card-info">
                        <div class="buy-card-img">
                            <?php echo $this->Html->image($card_sales['card_image'],array('alt'=>'Card Icon','width'=>'90px;','height' => '60px;')); ?><br/>
                              <span>
                                  <strong><?php echo $card_sales['card_name']?></strong>
                              </span>
                              <div class="buy-card-rate">&euro;<?php echo number_format($card_sales['selling_price'],2);?></div>
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
                            <?php echo $card_sales['free_text'];?>
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
                <!--Bill End-->
                <?php 
                        } //if - else end
                      }//foreach end

                    }else{
                ?>

                      <div> 
                          <div colspan="11" align="center"><?php echo __('No records found.');?></div>
                     </div>

                <?php

                    }
                ?>
            </div>
        </div>
    </div>
<script>
$('#print').click(function(){
      
      var page_data = $('#paper_size').val();
      $.ajax({
        /*beforeSend: function (XMLHttpRequest) {
           $("#loading-image").fadeIn();
        },
        complete: function (XMLHttpRequest, textStatus) {
          $("#loading-image").fadeOut();
        },*/
        url: "<?php echo $this->Html->Url(array('controller'=>'Carts','action'=>'print_preview'));?>",
        type: "POST",
        data: ({page_data:page_data}), 
        success: function (data)
        {
            //alert(data);
            var thePopup = window.open( '', "Card Printing", "menubar=0,location=0,width=700" );
            thePopup.document.write(data);
            $('#popup-content').clone().appendTo( thePopup.document.body );
            thePopup.print();
        }
    });
});

$('#download_excel').click(function(){
    var url = "<?php echo $this->Html->url(array('controller'=>'Carts','action'=>'download_excel'));?>";
    window.location.href = url;    
});
$('#download_csv').click(function(){
    var url = "<?php echo $this->Html->url(array('controller'=>'Carts','action'=>'download_csv'));?>";
    window.location.href = url;    
});
</script>