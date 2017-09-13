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
</style>
<div class="right-part right-panel">
                        
    <!--Bill Start-->
    <div class="sb-page-title">
        <strong>Print Bill</strong>
    </div>
    
    <div id="print_bill">
        <div id="print_panel">
            <form>
                <div class="left-part" id="page_size" style="display: none">Page Size</div> 
                <div class="left-part" style="display: none;">
                <select class="form-control selectbox_graditent" id="paper_size">
                    <option>A4</option>
                    <option>A3</option>
                    <option>A2</option>
                </select>
                </div>
                <div class="right-part">
                <input type="submit" name="" value="Print" class="button-gradient" id="print" />
                </div>
            </form>
        </div>
        
       <div class="main_print_div" align="center">
		<?php foreach ($final_card_sale_bills as $card_sales) { ?>
        <div id="print_details" class="print_details_card">
            <div id="single_card_detail">
                <div id="purchase_card">
                    <p>
                        <?php echo $this->Html->image($card_sales['card_image'],array('alt'=>'Card Icon','width'=>'180','height'=>'100')); ?>
                        <span><?php echo $card_sales['card_name'];?></span><br/>
                        <p><?php echo "&euro;".$card_sales['s_total_sales'];?></p>
                    </p>

                </div>
                <div id="card_info">
                    <span>Dial :<?php echo $card_sales['contact'];?></span><br/>
                    <span>Quantity :</span><?php echo $card_sales['card_sale_count'];?>
                </div>
            </div>
            <p>
            <?php echo $card_sales['free_text'];?>
            </p>
        </div>
        <div id="other_details">
                <p id="card_pin" style="height:auto;">
                    <label>Pin: </label> <span class="serial_pins"><?php echo trim($card_sales['pins']);?></span>
                </p>
                <p> <label>Retailer:</label> <span class="serial_pins"><?php echo ucwords($this->Session->read('Auth.User.fname')." ".$this->Session->read('Auth.User.lname'));?></span></p>
                <p> <label>Url:</label> <span class="serial_pins"><?php echo APPLICATION_PATH;?></span></p>
                <p> <label>Serial Number:</label> <span class="serial_pins"><?php echo trim($card_sales['serial']);?></span></p>
                <p> <label>Retail Price:</label><span class="serial_pins"> <?php echo trim($card_sales['selling_price']);?></span> </p>
                <p> <label>Contact:</label><span class="serial_pins"><?php echo " ".trim($card_sales['local_number']);?> </span> </p>
                <!--<p> <label>Ahmet Tasking - PPT Callshop [395]</label> </p>
                <p> morst 1.120145 Hamburg </p>-->
            </div>
        <?php } ?>
    </div>
  <!--Bill End-->
  </div>
</div>
<script >
$('#print').click(function(){
	var thePopup = window.open( '', "Card Printing", "menubar=0,location=0,height=700,width=700" );
	thePopup.document.write($('.main_print_div').html());
    $('#popup-content').clone().appendTo( thePopup.document.body );
    thePopup.print();
});
	$('#paper_size').on('change',function(){
		if($(this).val()=='A4')
		{
			$('.main_print_div').css('width','620px');	
		}
		else if($(this).val()=='A3')
		{
			$('.main_print_div').css('width','675px');
		}
		else if($(this).val()=='A2')
		{
			$('.main_print_div').css('width','745px');
		}
	});

</script>