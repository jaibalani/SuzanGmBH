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
#download_excel,#print{
   
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
                <div class="right-part">
                <input type="button" name="" value="<?php echo __('Download Excel');?>" class="button-gradient" id="download_excel" />
                </div>
        </div> 
      
        <div id="sales-reports"  class="main_print_div">

           <?php $sales_counter = 0; if(!empty($final_card_sale_bills)) { 
               
            foreach ($final_card_sale_bills as $card_sales) 
            { 
                             $sales_counter++;
            ?>
            <div style="border: 1px solid #CCCCCC;padding: 10px;margin-bottom: 10px; height: auto; float: left;width: 100%;"> 

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
           <?php } ?>
  <!--Bill End-->
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
</script>