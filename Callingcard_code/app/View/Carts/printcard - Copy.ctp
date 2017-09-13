<style>
.card_details{
    background-color: #f5f5f5;
    border-bottom: 1px solid #e5e5e5;
    border-top: 1px solid #e5e5e5;
    font-size: 16px;
    margin-bottom: 12px;
    margin-right: 10px;
    margin-top: 17px;
    padding: 10px 0;
    font-family: TitilliumWeb_light;
}
#other_details p
{
	padding-left:0px !important;
}
#other_details p label{
	width:270px !important;
}
#other_details{
width:100%;
}
</style>
	<div class="right-part right-panel">
                     <div class="sb-page-title">  
                          Print Bill            
                     </div>
                    <div id="print_bill">
                        <div id="print_panel">
                            <form>
                                <div>Page Size</div> 
                                <div>
                                <select class="form-control selectbox_graditent" id="paper_size">
                                    <option value="A4">A4</option>
                                    <option value="A3">A3</option>
                                    <option value="A2">A2</option>
                                </select>
                                </div>
                                <div>
                                <input id="print" type="submit" name="" value="Print" class="button-gradient" />
                                </div>
                            </form>
                            
                        </div>
                        <div id="print_table" style="clear:both; y-overflow:auto;">
                        	<div class="print_class">
                        		<?php echo $card_str;?>
                        	</div>
                        </div>
                        
                    </div>
                    
				</div>				
                
                <div class="float_cleaner"></div>
        
<script >
$('#print').click(function(){
	var thePopup = window.open( '', "Card Printing", "menubar=0,location=0,height=700,width=700" );
	thePopup.document.write($('#print_table').html());
    $('#popup-content').clone().appendTo( thePopup.document.body );
    thePopup.print();
});
	$('#paper_size').on('change',function(){
		if($(this).val()=='A4'){
			$('.print_details_card').css('width','620px');	
		}else if($(this).val()=='A3'){
			$('.print_details_card').css('width','675px');
		}else if($(this).val()=='A2'){
			$('.print_details_card').css('width','745px');
		}
	});

</script>