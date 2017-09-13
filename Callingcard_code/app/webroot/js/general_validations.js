// JavaScript Document
// Amount Validation in 100.00 Format
$(document).ready(function(){
		$('.amount_validation').keypress(function(evt){
			
				var charCode = evt.which ;
				//alert(charCode)
				if(charCode == 0 || charCode == 8 || charCode == 37 || charCode == 39)
				return true;
				
				/*// 27 = Escape 116 = Refresh 144 = Num Lock  112- 123 F1-F12
				if(charCode == 09 ||  charCode == 27  || (charCode >=112 && charCode <=123)  || charCode == 144 )
				return true;
				
				// 08 = Backspace 46 = Dot  48 -57 Numbers (0 -9) 37 Left Arrow 39 = Right Arrow
				if(charCode == 08 ||  charCode == 37 ||  charCode == 39)
				return true;*/
				
				if ((charCode < 48 || charCode > 57)  &&  charCode != 46) 
				{
						return false;
				}
				
				var total_amount = $(this).val() ;
				
				// Not placing dot at startup
				if((total_amount == '' || total_amount == 0) && charCode == 46 )
				{
					//return false;
				}
				
				// Only One Dot is required
				var dot_count = 0;
				for(i = 0 ; i<total_amount.length ; i++)
				{
					if( total_amount[i] == '.')
					{
						dot_count =dot_count + 1;
					}
					if(dot_count && charCode == 46)
					{
						return false
					}
				}
				
				new_amount = total_amount.split(".");
				if(new_amount.length == 2)
				{
					var length_after_dot = new_amount[1].length;
					// Already Two numbers are exists after decimal and not pressing backspace.
					if(length_after_dot == 2)
					{
						//return false;
					}
				}
				return true;
		});
		
		// First Character Can not be Spacebar
		$(".not_empty_first").on("keydown", function (e) {
				var name = $(this).val();
				if(name.length == 0)
				{
					return e.which !== 32;
				}
		});

});

// Entered Number is Digit Or Not
function isNumber(evt)
{

	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	
	if(charCode == 0 || charCode == 8 || charCode == 37 || charCode == 39)
	return true;

	if(charCode > 111 && charCode < 124) 
	{
			return true;
	}
	else if (charCode > 31 && (charCode < 48 || charCode > 57)) 
	{
			return false;
	}

		

		return true;
}

