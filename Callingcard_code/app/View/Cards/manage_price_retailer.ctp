<!-- <title>CALLING CARD - Selling Price Details</title> -->
				<div class="right-part right-panel">
                     <div class="sb-page-title">
                          <?php echo __('Manage Price');?>
                     </div>
                    <div id="data_filters">
                        <?php /*
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
                         */ ?>
                        <div id="typewise_filter">
                            <div class="alphabets">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-4">
                                            <label><?php echo __('Category:') ?></label>
                                        </div>
                                        <div class="col-md-8">
                                <?php echo $this->Form->input('Cat.id',array(
                                    'label'    => false, 
                                    'class'=>'form-control selectbox_graditent',
                                    'type' 	   => 'select',
                                    'required' => false, 
                                    'value'=>@$selected_card_category,
                                    'options' => @$card_categories,
                       ));
                  ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-4">
                                            <label><?php echo __('Sub Category:') ?></label>
                                        </div>
                                        <div class="col-md-8">
                                        <?php echo $this->Form->input('SubCat.id',array(
                                    'label'    => false, 
                                    'class'=>'form-control selectbox_graditent',
                                    'type' 	   => 'select',
                                    'required' => false, 
                                    'value'=>@$selected_sub_category,
                                    'options' => @$subCatList,
                       ));
                  ?>
                                    </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div id="typewise_filter">
                            <div class="alphabets">
                            <?php if ($selchar =='0' ) {?>
                            <div class="letters active"  onclick="submitChar('0','<?php echo $selected_card_category?>','<?php echo $selected_sub_category?>')"> <strong><?php echo __('All');?></strong></div>
							<?php } else {?>
                            <div class="letters"  onclick="submitChar('0','<?php echo $selected_card_category?>','<?php echo $selected_sub_category?>')"> <strong><?php echo __('All');?></strong></div>
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

                   <div class="<?php echo $class; ?>"  onclick="submitChar('<?php echo $char?>','<?php echo $selected_card_category?>','<?php echo $selected_sub_category; ?>')"> <strong><?php echo $char;?></strong>
                   </div>
                   
                   <?php } ?> 
                  </div>
                </div>
                        
                    </div>
                    <div id="price_details">
                       <?php echo $this->Form->create('Card',array('id'=>'cardForm')); ?> 
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th><?php echo __('Card Name');?></th>
                                <!--<th><?php //echo __('Denomination Rate');?></th>-->
                                <th><?php echo __('Purchase Rate');?></th>
                                <th><?php echo __('Selling Rate / Denomination Rate');?></th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php  foreach ($get_cards as $data) { ?>
                              <tr>
                                <td><?php echo ucwords($data['Card']['c_title']);?></td>
                                <!--<td><?php //echo '&euro;'.ucwords($data['Card']['c_selling_price']);?></td>-->
                               <td>
									<?php 
                                        if(isset($data['CardsPrice']))
                                        { 
                                            
                                            if( !empty($data['CardsPrice']))
                                            {
                                                $flag = 0;
												// Viewing Own Set Details
                                                foreach($data['CardsPrice'] as $price)
                                                {
                                                   	    if($price['cp_u_role'] == 3 && $price['cp_u_id'] == $this->Session->read('Auth.User.id') && $flag == 0)                							  														{
															$data['Card']['c_buying_price'] =$price['cp_buying_price'];
															$data['Card']['c_selling_price'] =$price['cp_selling_price'];
															$flag= 1; 
														}
												 }
												
                                             }
											
											if($flag == 0)
											{
												     foreach($data['CardsPrice'] as $price)
                                                     {    
														if($price['cp_u_role'] == 2 && $price['cp_u_id'] == $this->Session->read('Auth.User.added_by') && $flag == 0)									                                                        {
															$data['Card']['c_buying_price'] =$price['cp_buying_price'];
															$data['Card']['c_selling_price'] =$price['cp_buying_price'];
															$flag= 1; 
														}
													 }
											}
											
											if($flag == 0)
                                            {
                                                $data['Card']['c_buying_price'] =  $data['Card']['c_selling_price'];
												$data['Card']['c_selling_price'] =  $data['Card']['c_selling_price'];
                                            }
                                            echo $data['Card']['c_buying_price'];
						    			}
                                        else
                                        {
											$data['Card']['c_buying_price'] =  $data['Card']['c_selling_price'];
											$data['Card']['c_selling_price'] =  $data['Card']['c_selling_price'];
                                            echo $data['Card']['c_buying_price'];
                                        }
                                    ?>
                			   </td>
                               <td>
                     <?php 
                      /*echo $this->Form->input('totalamount_'.$data['Card']['c_id'],array('class'=>'form-control amount_validation',
																									'required' =>true,
																									'type'=>'text',
																									'value'=>$data['Card']['c_selling_price'],
																									'style'=>'width:100px',
                                                  'readonly'=>'readonly',
																									'label'=>false)); */
                       echo $data['Card']['c_denomination_rate'];
                     ?>
									   <?php echo $this->Form->input('purchase_'.$data['Card']['c_id']        ,array('class'=>'form-control',
															'required' =>true,
															'type'=>'hidden',
															'value'=>$data['Card']['c_buying_price'],
									           	'label'=>false)); ?>
                                 </td>
                              </tr>
                             <?php } ?>
                            </tbody>
                          </table>
                   <?php echo $this->form->create('Card'); ?> 
                        <div id="action" style="margin-bottom:10px;" >
                        	
                         <!-- <button class="button-gradient button-gradient_front"  type="button"  onclick="return check_submit();" >&nbsp;<?php //echo __('Update');?></button>
                        	
                          <button class="input-box-gradient cancel-button-gradient"  type="button"  onclick="go_dashboard();" style="float:left;margin-left:10px;">&nbsp;<?php //echo __('Cancel');?></button>-->
                        </div>
                    </div>
                   
                    
				</div>				
<script type="text/javascript">
$(document).ready(function(){
	//Highlight active menu
	$('#sb-opt-manage-price').addClass('opt-selected');
});

function check_submit()
{
  var empty_selling = 0;
	var ans = confirm("<?php echo __('Are you sure you want to update the selling prices ?');?>");	
	if(ans)
	{  
	  flag = 0;
		$('.amount_validation').each(function(){
			
			price = $(this).val();
      var price = parseFloat(price).toFixed(2);
			if(price == '' || price <= 0)
			{
				if(price == '')
        empty_selling = 1;  
        flag = 1; 
				$(this).css('border','1px solid #F00');
			}
		});
		if(flag == 1)
		{
		  if(empty_selling == 1)
       alert('<?php echo __("Enter selling price.")?>');
       else  
       alert('<?php echo __("Selling price should be greater than zero.")?>'); 	
		  return false;	
		}
		$('#cardForm').submit();
	}
	else
	{
     location.reload(true);
	}
}

$('#CatId').change(function(){
    var cat_id =  $( this ).val();

    if(!cat_id){
        cat_id = 0;
    }
    chars = 0;
    subcate = 0;
    change_type(chars,cat_id,subcate);
});

$('#SubCatId').change(function(){
    var cat_id = <?php echo @$selected_card_category; ?>;
    var sub_cat_id =  $( this ).val();
    if(!sub_cat_id){
        sub_cat_id = 0;
    }
    
    chars = 0;
    subcate = sub_cat_id;
    change_type(chars,cat_id,subcate);
});

function change_type(chars,card_category,subcate)
{
	var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price_retailer'));?>/"+card_category+"/"+chars+"/"+subcate;
    window.location.href = url;
}

function go_dashboard()
{
	var url = "<?php echo $this->Html->url(array('controller'=>'Pages','action'=>'dashboard'));?>";
	window.location.href = url;
}

function submitChar(chars,card_category,subcate){
	
	//alert(chars+' '+card_category+' '+subcate);

  var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price_retailer'));?>/"+card_category+"/"+chars+"/"+subcate;
	window.location.href = url;
}
</script>