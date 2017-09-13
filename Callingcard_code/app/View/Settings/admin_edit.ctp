<style type="text/css">
.hidden_credit{
display: none;
}
.btn{
background-color: unset !important;
background-image:none !important;
}
.btn-info{
background-color: #5bc0de !important;
border-color: #46b8da !important;
color: #fff;
}
.btn-primary{
background-color: #337ab7 !important;
border-color: #2e6da4 !important;
color: #fff !important;
}
.error{
	color: #F00;
	font-size: 12px;
	text-align: left;
	margin-top: 12px;
}

.grid_table_box .row .col-md-1 div.checkbox{ margin-left: -50px; }

</style>
<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
    <div class="sub_title"><i class="icon-gear home_icon"></i> <span class="sub_litle_m">Settings</span> <i class="icon-angle-right home_icon"></i> <span>Edit WebSettings</span></div>
  </div>
</div>

<div class="main_subdiv">
	<div class="gird_button">
	        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
	</div>    
	  
	<div class="clear10"></div>
	
	<div align="left" class="grid_table_box">

		<?php echo $this->Form->create('Websetting', array('id' => 'frm_websetting','name'=>'frm_websetting'));
				$i = 0;
				if(isset($web_setting) && !empty($web_setting))
				{
					foreach($web_setting as $websettings ){ 
						$str =explode($prefix.'.',$websettings['Websetting']['key']); ?>
		        		<div class="clear10"></div>
		       			 <div class="row">
		          
		          <?php if($str[1] != "url" && $str[1] != "status") {

                        if($str[1] == "servicesupport_email")
                        $str[1] = "Service Support Email" ; 
                        
                        if($str[1] == "servicesupport_phone")
                        $str[1] = "Service Support Phone" ; 

                        if($str[1] == "text_field1")
                        $str[1] = "Text Field 1 Invoice" ; 

                        if($str[1] == "text_field2")
                        $str[1] = "Text Field 2 Invoice" ; 

                        if($str[1] == "text_field3")
                        $str[1] = "Text Field 3 Invoice" ; 

                        if($str[1] == "text_field4")
                        $str[1] = "Text Field 4 Invoice" ; 

                        if($str[1] == "bank_details")
                        $str[1] = "Bank Details" ; 

                        if($str[1] == "distributor_name")
                        $str[1] = "Distributor Name" ; 

                ?>
			          <div class="col-md-3 sb_left_pad"><?php echo ucfirst($str[1]); ?><sup class="MandatoryFields">*</sup></div>
			      <?php } ?>    
		          
		          <div class="col-md-5 sb_left_mar">
		              <?php 
										$inputType = 'text';
										
										if ($websettings['Websetting']['input_type'] != null) {
											$inputType = $websettings['Websetting']['input_type'];
										}


										/*if($websettings['Websetting']['input_type']=='checkbox'){
												
												if($websettings['Websetting']['value']==1){
											
													 $checked = true;
													 
												}else{
													
													 $checked = false;
													
												}
												
											echo $this->Form->checkbox("Websetting.$i.value", array('label'=>false,'checked'=>$checked));
											echo $this->Form->input("Websetting.$i.id",array('type'=>'hidden','class' => 'input_textbox','label' => false,'value'=>$websettings['Websetting']['id']));
												
										}
										else 
										{*/
												
                        if( $websettings['Websetting']['key'] != 'Site.url' && 
                        $websettings['Websetting']['key'] != 'Site.status' )
                        {
                        echo $this->Form->input("Websetting.$i.value", array(
                        'label' => false,
                        'type' => $inputType,
                        'value' => $websettings['Websetting']['value'],
                        'class' => 'form-control',
												    ));
												
												echo $this->Form->input("Websetting.$i.id",array('type'=>'hidden','class' => 'input_textbox','label' => false,'value'=>$websettings['Websetting']['id']));
                        }
										//}
										$i++;
									?>
		          </div>
		          <div class="col-md-4 error" id = "error_<?php echo $i-1;?>"></div>
		        </div>

		<?php }?>
					<div class="clear10"></div>
		      <div class="row">
		        <div class="col-md-3 sb_left_pad">&nbsp;</div>
		        <div class="col-md-3 sb_left_mar"><?php echo $this->Form->submit('Update', array('type'=>'submit', 'class'=>'btn btn-primary','label'=>false,'id'=>'submit_btn','div'=>false,'onclick'=>'return check_valid();')); ?>
		        </div> 
		      </div>	
		<?php 
				}
				echo $this->Form->end();?>  
				
		</div>
		
</div>	
<script>
   $(document).ready(function(){

   $('#setting').addClass('sb_active_opt');
   $('#setting').removeClass('has_submenu');
   $('#websetting_active').addClass('sb_active_subopt_active');
   
    $('#Websetting5Value').attr('maxlength','6'); // Zipcode
    $('#Websetting6Value').attr('maxlength','15'); // Phone
    $('#Websetting7Value').attr('maxlength','15'); // Fax
    $('#Websetting12Value').attr('maxlength','15'); // Service Support

   // Zipcode Validation
   $('#Websetting5Value').keypress(function(event){
	     var element = 5;
	     var key = isNumber_web(event ,element );
	     if(!key){
	     	
	     	return false;
	     }
	     return key;
   });
    // Phone Validation
   $('#Websetting6Value').keypress(function(event){
	     
	     var element = 6;
	     var key = isNumber_web(event,element);
	     if(!key){
	     	
	     	return false;
	     }
	     return key;
   });
   // Fax Validation
   $('#Websetting7Value').keypress(function(event){
	     
	     var element = 7;
	     var key = isNumber_web(event,element);
	     if(!key){
	     	
	     	return false;
	     }
	     return key;
   });
   // Service Support Number Validation
   $('#Websetting12Value').keypress(function(event){
	     
	     var element = 12;
	     var key = isNumber_web(event,element);
	     if(!key){
	     	
	     	return false;
	     }
	     return key;
   });

   

}) ;
 
function isNumber_web(evt,element)
{
	
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	
	if(charCode == 0 || charCode == 8 || charCode == 37 || charCode == 39)
	{
		return true;
	}

	if(charCode > 111 && charCode < 124) 
	{
			return true;
	}
	
	if (charCode > 31 && (charCode < 48 || charCode > 57)) 
	{
			if(element == 5)
			$('#error_5').html('Enter maximum 6 digits in zipcode.');
			else if(element == 6 )
			$('#error_6').html('Enter maximum 15 digits in phone number.');
     		else if(element == 7 )
 			$('#error_7').html('Enter maximum 15 digits in fax number.');
 		    else if(element == 12 )
 			$('#error_12').html('Enter maximum 15 digits in service support number.');
			return false;
	}
	return true;
}

 function check_valid()
 {
    
    var title = $('#Websetting0Value').val();
    var tag_line = $('#Websetting1Value').val();
    var email = $('#Websetting2Value').val();
    var address = $('#Websetting4Value').val();

    var zipcode = $('#Websetting5Value').val();
    var phone = $('#Websetting6Value').val();
    var fax = $('#Websetting7Value').val();

    var facebook_url = $('#Websetting8Value').val();
    var twitter_url = $('#Websetting9Value').val();
    var google_url = $('#Websetting10Value').val();

    var service_support_email = $('#Websetting11Value').val();
    var service_support = $('#Websetting12Value').val();

    var tax_id = $('#Websetting14Value').val();
    var text_field1 = $('#Websetting15Value').val();
    var text_field2 = $('#Websetting16Value').val();
    var text_field3 = $('#Websetting17Value').val();
    var text_field4 = $('#Websetting18Value').val();
    var bank_details = $('#Websetting19Value').val();
    var distributor_name = $('#Websetting20Value').val();
 	
 	  var flag = 0;
    
    var emailReg = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
     
    if( emailReg.test(email)) 
    {
      $('#error_2').html('');
    }
    else
    {
        flag = 1;
        if(email.length == 0)
        $('#error_2').html('Enter email.');
        else
        $('#error_2').html('Invalid email.');
    }


    if(emailReg.test(service_support_email)) 
    {
      $('#error_11').html('');
    }
    else
    {
        flag = 1;
        if(service_support_email.length == 0)
        $('#error_11').html('Enter service support email.');
        else
        $('#error_11').html('Invalid service support email.');
    }


 	if(title.trim().length == 0)
 	{
         flag = 1;
         $('#error_0').html('Enter title.');
 	}
 	else
 	{
         $('#error_0').html('');
 	}

 	if(tag_line.trim().length == 0)
 	{
         flag = 1;
         $('#error_1').html('Enter tag line.');
 	}
 	else
 	{
         $('#error_1').html('');
 	}

    
    if(address.trim().length == 0)
 	{
         flag = 1;
         $('#error_4').html('Enter address.');
 	}
 	else
 	{
         $('#error_4').html('');
 	}

 	
    if( !(/^\d+$/.test(zipcode)) )
    {
         if(zipcode.length == 0)
         $('#error_5').html('Enter zipcode.');
         else   
         $('#error_5').html('Zipcode can have only digits.');	
         flag = 1;
    }
    else
    {
    	  if(zipcode.length > 6)   
        $('#error_5').html('Zipcode can have only 6 digits.');   
        else
        $('#error_5').html('');
    }

    if( !(/^\d+$/.test(phone)) )
    {
    	if(phone.length == 0)
        $('#error_6').html('Enter phone number.');
        else	
    	$('#error_6').html('Phone number can have only digits.');
    	flag = 1;
    }
    else
    {
    	if(phone.length > 15)   
        $('#error_6').html('Phone number can have only 15 digits.');   
        else
        $('#error_6').html('');
    }

    if( !(/^\d+$/.test(fax)) )
    {
    	if(fax.length == 0)
        $('#error_7').html('Enter fax number.');
        else
    	$('#error_7').html('Fax number can have only digits.');
    	flag = 1;
    }
    else
    {
    	if(fax.length > 15)   
        $('#error_7').html('Fax number can have only 15 digits.');   
        else
        $('#error_7').html('');
    }

    if( !(/^\d+$/.test(service_support)) )
    {
    	  if(service_support.length == 0)
        $('#error_12').html('Enter service support number.');
        else
    	  $('#error_12').html('Service support number can have only digits.');
    	  flag = 1;
    }
    else
    {
    	  if(service_support.length > 15)   
        $('#error_12').html('Service support number can have only 15 digits.');   
        else
        $('#error_12').html('');
    }
   
    // Facebook Url Validation
    if (!(/^(https?:\/\/){0,1}(www\.){0,1}facebook\.com/.test(facebook_url)))
    {
        $('#error_8').html('Enter facebook url.');
        flag = 1;
    }
    else
    {
    	  $('#error_8').html('');
    }

    // twitter Url Validation
    if (!(/^(https?:\/\/){0,1}(www\.){0,1}twitter\.com/.test(twitter_url)))
    {
        $('#error_9').html('Enter twitter url.');
        flag = 1;
    }
    else
    {
    	$('#error_9').html('');
    }

  
    // twitter Url Validation
    if (!(/^(https?:\/\/){0,1}(www\.){0,1}plus.google\.com/.test(google_url)))
    {
        $('#error_10').html('Enter google plus url.');
        flag = 1;
    }
    else
    {
    	$('#error_10').html('');
    }
    
    if(tax_id.trim().length == 0)
    {
           flag = 1;
           $('#error_14').html('Enter tax_id.');
    }
    else
    {
           $('#error_14').html('');
    }

    if(text_field1.trim().length == 0)
    {
           flag = 1;
           $('#error_15').html('Enter text.');
    }
    else
    {
           $('#error_15').html('');
    }

    if(text_field2.trim().length == 0)
    {
           flag = 1;
           $('#error_16').html('Enter text.');
    }
    else
    {
           $('#error_16').html('');
    }

    if(text_field3.trim().length == 0)
    {
           flag = 1;
           $('#error_17').html('Enter text.');
    }
    else
    {
           $('#error_17').html('');
    }

    if(text_field4.trim().length == 0)
    {
           flag = 1;
           $('#error_18').html('Enter text.');
    }
    else
    {
           $('#error_18').html('');
    }

     if(bank_details.trim().length == 0)
    {
           flag = 1;
           $('#error_19').html('Enter bank details.');
    }
    else
    {
           $('#error_19').html('');
    }

    if(distributor_name.trim().length == 0)
    {
           flag = 1;
           $('#error_20').html('Enter distributor name.');
    }
    else
    {
           $('#error_20').html('');
    }


    if(flag)
    {
    	return false;
    }
    else
    {
    	return true;
    }

 }   
</script>