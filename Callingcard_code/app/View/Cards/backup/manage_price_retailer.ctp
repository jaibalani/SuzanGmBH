<!-- <title>CALLING CARD - Selling Price Details</title> -->
				<div id="right_panel">
                     <div id="title">
                          Selling Price Details
                     </div>
                    <div id="data_filters">
                        <div id="typewise_filter">
                           <div class="alphabets">
                            <?php
								foreach($card_categories as $category_id => $category_value)
								{
									if($selected_card_category == $category_id)
									$class = "letters active";
									else
									$class = "letters";
							  ?>
                               <div class = "<?php echo $class; ?>";  onclick='change_type("<?php echo $selchar?>","<?php echo $category_id?>");'> <strong><?php echo $category_value; ?></strong></div>
                           <?php  } ?>
                        </div>
                            
                        </div>
                        <div id="letter_filter">
                            <div class="alphabets">
                            <?php if ($selchar =='0' ) {?>
                            <div class="letters active"  onclick="submitChar('0','<?php echo $selected_card_category?>')"> <strong><?php echo __('All');?></strong></div>
							<?php } else {?>
                            <div class="letters"  onclick="submitChar('0','<?php echo $selected_card_category?>')"> <strong><?php echo __('All');?></strong></div>
							<?php } ?>
                          	<?php  
							//echo $selchar;exit;
							foreach (range('A', 'Z') as $char){
									
									if($selchar == $char)
									{
										$class = 'letters active';
									}
									else
									{
										$class = 'letters';
									}
									?>
                                    <div class="<?php echo $class; ?>"  onclick="submitChar('<?php echo $char?>','<?php echo $selected_card_category?>')"> <strong><?php echo $char;?></strong></div>
                            <?php } ?> 
                        </div>
                            
                        </div>
                        
                    </div>
                    <div id="price_details">
                       <?php echo $this->Form->create('Card',array('id'=>'cardForm')); ?> 
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th>Card Name</th>
                                <th>Denomination Rate</th>
                                <th>Purchase Rate</th>
                                <th>Selling Rate</th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php  foreach ($get_cards as $data) { ?>
                              <tr>
                                                              <td><?php echo ucwords($data['Card']['c_title']);?></td>
                                <td><?php echo '&euro;'.ucwords($data['Card']['c_selling_price']);?></td>
                               <td>
									<?php 
                                        if(isset($data['CardsPrice']))
                                        { 
                                            $flag = 0;
                   							$update_selling =0;
                                            if( !empty($data['CardsPrice']))
                                            {
                                                // Viewing Own Set Details
                                                foreach($data['CardsPrice'] as $price)
                                                {
                                                    if($price['cp_u_role'] == 3 && $price['cp_u_id'] == $this->Session->read('Auth.User.id') && $price['cp_updated_by'] == $this->Session->read('Auth.User.added_by') && $data['Card']['c_id'] == $price['cp_c_id'])                {
                                                        $data['Card']['c_buying_price'] =$price['cp_selling_price'];
                                                        
														if($update_selling ==0)
														$data['Card']['c_selling_price'] =$price['cp_selling_price'];
                                                        
														$flag= 1; 
                                                    }
													if($price['cp_u_role'] == 3 && $price['cp_u_id'] == $this->Session->read('Auth.User.id') && $price['cp_updated_by'] == $this->Session->read('Auth.User.id') && $data['Card']['c_id'] == $price['cp_c_id'])                      {
                                                        $data['Card']['c_selling_price'] =$price['cp_selling_price'];
                                                        $flag= 1; 
														$update_selling = 1;
											        }
                                                }
                                                
												//If Not Set then viewing set by its mediator
                                                if($flag == 0)
                                                {
                                                    foreach($data['CardsPrice'] as $price)
                                                    {
                                                        if($price['cp_u_role'] == 2 && $price['cp_u_id'] == $this->Session->read('Auth.User.added_by') && $data['Card']['c_id'] == $price['cp_c_id'])
                                                        {
                                                            $data['Card']['c_buying_price'] =$price['cp_selling_price'];
                                                            if($update_selling ==0)
															$data['Card']['c_selling_price'] =$price['cp_selling_price'];
                                                            $flag= 1; 
                                                        }
                                                    }
                                                }
                                            }
                                            
                                            if($flag == 0)
                                            {
                                                $data['Card']['c_buying_price'] =  $data['Card']['c_selling_price'];
                                            }
                                            echo $data['Card']['c_buying_price'];
                                        }
                                        else
                                        {
                                            $data['Card']['c_buying_price'] =  $data['Card']['c_selling_price'];
                                            echo $data['Card']['c_buying_price'];
                                        }
                                    ?>
                			   </td>
                               <td>
                               <?php 
				   
									   echo $this->Form->input('totalamount_'.$data['Card']['c_id'],array('class'=>'form-control amount_validation',
																														'required' =>true,
																														'type'=>'text',
																														'value'=>$data['Card']['c_selling_price'],
																														'style'=>'width:100px',
																														'label'=>false)); ?>
									   <?php echo $this->Form->input('purchase_'.$data['Card']['c_id'],array('class'=>'form-control',
																														'required' =>true,
																														'type'=>'hidden',
																														'value'=>$data['Card']['c_buying_price'],
																									'					label'=>false)); ?>
                                 </td>
                              </tr>
                             <?php } ?>
                            </tbody>
                          </table>
                   <?php echo $this->form->create('Card'); ?> 
                        <div id="action" style="margin-bottom:10px;" >
                        	<button class="button_gradient"  style="color:#FFF;" type="submit"  onclick="return check_submit();" >&nbsp;Update</button>
                        	<button class="input_box_gradient"  type="button"  onclick="go_dashboard();">&nbsp;Cancel</button>
                        </div>
                    </div>
                   
                    
				</div>				
<script type="text/javascript">

function check_submit()
{
	var ans = confirm("<?php echo __('Are you sure you want to update the selling prices ?');?>");	
	if(ans)
	{  
	     flag = 0;
		$('.amount_validation').each(function(){
			
			price = $(this).val();
			if(price == '' || price <= 0)
			{
				flag = 1; 
				$(this).css('border','1px solid #F00');
			}
		});
		if(flag == 1)
		{
		  alert('<?php echo __('Invalid Price.')?>')	
		  return false;	
		}
		$('#cardForm').submit();
	}
	else
	{
		
	}
}

function change_type(char,card_category)
{
	var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price_retailer'));?>/"+card_category+"/"+char;
	window.location.href = url;
}

function go_dashboard()
{
	var url = "<?php echo $this->Html->url(array('controller'=>'Pages','action'=>'dashboard'));?>";
	window.location.href = url;
}

function submitChar(char,card_category){
	
	var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price_retailer'));?>/"+card_category+"/"+char;
	window.location.href = url;
}
</script>