<?php ?>
<style type="text/css">
.filter-option ul li{
padding-left:24px;
}
</style>
<?php echo $this->Html->css('tinycarousel');?>
<?php echo $this->Html->script('jquery.tinycarousel');?>
<script type="text/javascript">    

	$(document).ready(function()
	 {
		 <?php if(isset($all_cat) && !empty($all_cat)){ 
		 					if(count($all_cat) > 9){?> 
								$('#slider1').tinycarousel();			
				<?php }else{ ?>
								//$('#sb_card_names').css('overflow','unset');
								//$('#sb_card_names').attr('overflow','unset');
				<?php	}
		 }?>
	 
	     var selected_card_name = '<?php echo @$selected_card_name; ?>';
		 var selected_card_stock = '<?php echo @$selected_stock; ?>';
		 var selected_card_rate = '<?php echo @$selected_rate; ?>';
		 var selected_card_icon = '<?php echo @$selected_view; ?>';
	     
		 if(selected_card_icon.length >0)
		 {
			var div = '';
			var new_div_id = "card_icon"+selected_card_icon;
			var check_box_id = "CardView"+selected_card_icon;
			var card_icon = $("label[for='" + check_box_id+ "']").text();
			var div ="<div class='selected_filter card_icon' id= '"+new_div_id+"' onclick = 'remove_it(this,"+selected_card_icon+")'>Card View : "+card_icon+"&nbsp;&nbsp;<small>X</small></div>";
			$(div).insertAfter('#append_selection');
		   
		 }
		 
		 if(selected_card_stock.length >0)
		 {
			var div = '';
			var new_div_id = "card_stock"+selected_card_stock;
			var check_box_id = "CardStock"+selected_card_stock;
			var card_stock = $("label[for='" + check_box_id+ "']").text();
			var div ="<div class='selected_filter card_stock' id= '"+new_div_id+"' onclick = 'remove_it(this,"+selected_card_stock+")'>Card Stock: "+card_stock+"&nbsp;&nbsp;<small>X</small></div>";
			$(div).insertAfter('#append_selection');
		   
		 }
		 
		 if(selected_card_rate.length >0)
		 {
			var div = '';
			var new_div_id = "card_rate"+selected_card_rate;
			var check_box_id = "CardRate"+selected_card_rate;
			var card_rate = $("label[for='" + check_box_id+ "']").text();
			var div ="<div class='selected_filter card_rate' id= '"+new_div_id+"' onclick = 'remove_it(this,"+selected_card_rate+")'>Card Rate: "+card_rate+"&nbsp;&nbsp;<small>X</small></div>";
			$(div).insertAfter('#append_selection');
		   
		 }
		 
		 if(selected_card_name.length >0)
		 {
		 	var total_cards = selected_card_name.split(",");
       		var counter =0 ;
			var card_id = '';
			var div = '';
			var new_div_id ='';
			while(total_cards[counter])
			{
			   card_id = total_cards[counter];
			   counter++;
			   new_div_id = "card_name"+card_id;
			   check_box_id = "CardCId"+card_id;
			   var card_name = $("label[for='" + check_box_id+ "']").text();
			   var div ="<div class='selected_filter card_name' id= '"+new_div_id+"' onclick = 'remove_it(this,"+card_id+")'>Card : "+card_name+"&nbsp;&nbsp;<small>X</small></div>";
			   $(div).insertAfter('#append_selection');
			}
		 }
	    
	 });

function display_cards()
{
	$('.filter-option').css('display','block');
}

function hide_cards()
{
	$('.filter-option').css('display','none');
}

/*    $(document).ready(function(){
       // header user info drow down
        // left panel bottom menu
        $('#card_slider li').click(function(){  
            $(this).addClass('active');
            //$('.sub_slider').css('display','block');
            $('#card_slider li.active ul').css('display','block');
            $('#card_slider li.active div.active_down_arrow img').attr('src','images/down_arrow.png');
            $('#card_slider li.active div:first').addClass('.selected');
        });
        
    });
	*/
</script>

<div class="right-part right-panel">
     <!--Card Container Start-->
      <div id="card_container">
        <div id="crad_name_select">
          <div id="sb_card_names">
                     <?php 
                        $class = 'active';
                        if(isset($active_tab) && !empty($active_tab)){
                            //if any tab was chosen
                         $class = '';
                        }
                     ?>
             <ul>
             <li class="<?php echo $class;?> card_cursor" onclick="submitForm()"><?php echo __('ALL CARDS');?></li>
             <?php  //prd($all_cat);
             if(isset($all_cat) && !empty($all_cat))
             {
              foreach($all_cat as $cat)
              {
                    $class= 'card_cursor';
                    if(isset($active_tab) && !empty($active_tab))
                    {
                        if($active_tab==$cat['Category']['cat_id'])
                        {
                            $class = 'active card_cursor';
                        }
                    }
             ?> 
            <li class="<?php echo $class;?>" onclick="submitForm('<?php echo $cat['Category']['cat_id']?>')">
              <?php echo $cat['CategoriesLanguage'][0]['cl_title']?>
              <div class="sub-card-menu">
                     <?php $parent_cat_id = $cat['Category']['cat_id'];
					 		foreach($sub_cat[$parent_cat_id] as $key =>$value)
							{
					           $sub_class= 'sub-sub-card-menu';
								if(isset($sub_search_tab) && !empty($sub_search_tab))
								{
									if($sub_search_tab==$key)
									{
										$sub_class = 'sub-sub-card-menu sub-menu-card-active';
									}
								}
					 ?>
                     <div class="<?php echo $sub_class;?>" onclick="submitSubForm('<?php echo $key;?>')"><?php  echo ucwords($value);?></div>
                     <?php } ?>
                 </div>
            </li>	
          <?php } }?>
        </ul>
     </div>
     
     <?php if(isset($all_cat) && !empty($all_cat)){ 
				//if(count($all_cat) > 9){ 
	?>
                  <div id="sb_scroll_btn">
                     
                 </div> 
     <?php } 
     
     			//}
     
     ?>
    </div>  
   </div> 
                        <!--Card Container End-->
                        <div id="other-filter">
                            <div id="sb_selected_card">
                                <div class="left-part"><strong><?php echo __('Total Cards')?><span id="sb_total_card"> ( <?php echo @$total_cards_pagination;?> )</span></strong></div>
                            </div>
                            <div id="selected-filter">
                                <div class="left-part">
                                    <div id="append_selection" style="float:left;"><?php echo __('Your Selection:')?></div>
                                    <!--<div class="selected-name">Adidas by Stella Mccartney &nbsp;&nbsp;<small>X</small></div>
                                    <div class="selected-name">Adidas by Stella Mccartney &nbsp;&nbsp;<small>X</small></div>-->
                                </div>
                                <div class="right-part">
                                    <div id = "clear_filter"><?php echo __('Clear all filters');?>  &nbsp;&nbsp;<small>X</small></div>
                                </div>
                            </div>
                               <?php echo $this->Form->create('searches',array('controller'=>'searches','action'=>'online_card','id'=>'searchFormCat'));?>
                               <?php echo $this->Form->input('Card.c_cat_id',array('type'=>'hidden','id'=>'tabs_cat')).' '.$this->Form->input('Card.c_sub_cat_id',array('type'=>'hidden','id'=>'sub_cat_id'));?>
                               <?php echo $this->form->end(); ?>
                            
                            <!--This section is under construction-->
                            <div id="card-main-filter">
                               <?php echo $this->Form->create('searches',array('controller'=>'searches','action'=>'online_card','id'=>'searchForm'));?>
                               <?php echo $this->Form->input('Card.c_cat_id',array('type'=>'hidden','id'=>'tabs_cat','value'=>@$c_cat_id)).' '.$this->Form->input('Card.c_sub_cat_id',array('type'=>'hidden','id'=>'sub_cat_id','value'=>@$c_sub_cat_id));?>
                                  <div id="filter-by-name" class="main-filter-opt">
                                    <div class="filter-title" > <!--Use class to active this option-->
                                        <?php echo __('Card Name');?>
                                    </div>
                                    <div class="filter-option">
                                        <div class="search-filter-option">
                                            <!--<input type="text" name="" value="" />-->
                                        </div>
                                        <ul>
                                            <li>
											     <?php 
                                                        echo $this->Form->input('Card.c_id',array(
                                                                                'label' 	 => false, 
                                                                                'required' => false, 
                                                                                'class'=>'card_name',
                                                                                'multiple'=>'checkbox',
																				'div'=>false,
                                                                                'options'  => $card_names,
                                                                                'selected'=>@$selected_card,
																				'hiddenField'=>false,
																				));
                                                 ?>
                                            </li>                                        
                                        </ul>
                                        <div class="select-filter-result">
                                            <!--<input type="button" name="select" value="select" />-->
                                        </div>
                                    </div> 
                                    
                                </div>
                                <div id="filter-by-price" class="main-filter-opt">
                                    <div class="filter-title">
                                        <?php echo __('Price');?>
                                    </div>
                                    
                                    <div class="filter-option">
                                        <div class="search-filter-option">
                                            <!--<input type="text" name="" value="" />-->
                                        </div>
                                        <ul>
                                            <li>
                                            <?php
                                            $options = array("0"=>__("All"),
															 "1" => "<1",
                        				"2" => "1 - 2",
                        				"3" => "2 - 5",
                        				"4" => "5 - 10",
                        				"5" => "> 10",);
											echo $this->Form->input('Card.rate',array(
																	'label' 	 => false, 
																	'required' => false, 
																	'class'=>'card_rate',
																	'multiple'=>'checkbox',
																	'div'=>false,
																	'options'  => $options,
																	'selected'=>@$selected_rate,
																	));
                                            
											?>
                                            </li>   
                                        </ul>
                                        <div class="select-filter-result">
                                            <!--<input type="button" name="select" value="select" />-->
                                        </div>
                                    </div>
                                    
                                </div>
                                <div id="filter-by-show" class="main-filter-opt">
                                    <div class="filter-title">
                                        <?php echo __('Show Only');?>
                                    </div>
                                    
                                    <div class="filter-option">
                                        <div class="search-filter-option">
                                            <!--<input type="text" name="" value="" />-->
                                        </div>
                                        <ul>
                                            <li>
                                             <?php
                                            $options = array("1"=>__("All Cards"),
															 "2" => __("Available stock"), 
															 );
											echo $this->Form->input('Card.stock',array(
																	'label' 	 => false, 
																	'required' => false, 
																	'class'=>'card_stock',
																	'multiple'=>'checkbox',
																	'div'=>false,
																	'options'  => $options,
																	'selected'=>@$selected_stock,
																	));
                                            
											?>
                                            </li>
                                        </ul>
                                        <div class="select-filter-result">
<!--                                            <input type="button" name="select" value="select" />
-->                                        </div>
                                    </div>
                                    
                                </div>
                                <div id="filter-by-view" class="main-filter-opt">
                                    <div class="filter-title">
                                        <?php echo __('View Card');?>
                                    </div>
                                    
                                    <div class="filter-option">
                                        <div class="search-filter-option">
                                            <!--<input type="text" name="" value="" />-->
                                        </div>
                                        <ul>
                                           <li>
                                           <li>
                                             <?php
                                            $options = array("1"=>__("Icon"),
															 "2" => __("List"), 
															 );
											echo $this->Form->input('Card.view',array(
																	'label' 	 => false, 
																	'required' => false, 
																	'class'=>'card_icon',
																	'multiple'=>'checkbox',
																	'div'=>false,
																	'options'  => $options,
																	'selected'=>@$selected_view,
																	));
                                            
											?>
                                            </li>
                                           </li>
									   </ul>
                                        <div class="select-filter-result">
<!--                                            <input type="button" name="select" value="select" />
-->                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="right-part">
                                    <button name="search" class="button-gradient search-by-filter"><?php echo __('Search');?></button>
                                </div>
                               
                               <div id="sort-card">
                                <div class="left-part">
                                    <?php echo __('Sort by price');?>
                                    <?php
									echo $this->Form->input('Card.price',array(
																	'label' 	 => false, 
																	'required' => false, 
																	'class'=>'',
																	'div'=>false,
																	'type'=>'select',
																	'options'  => array('1'=>__('Low'),'2'=>__('High')),
																	'value'=>@$price_order,
																	));
                                            
                                    ?>
                                </div>
                              </div> 
                               <?php echo $this->Form->end(); ?>
                            </div>
                            
                            <!--This section is under construction-->
                            
                            <!--<div id="sort-card">
                                <div class="left-part">
                                    Sort by price
                                    <?php
									/*echo $this->Form->input('Card.price',array(
																	'label' 	 => false, 
																	'required' => false, 
																	'class'=>'',
																	'div'=>false,
																	'type'=>'select',
																	'options'  => array('1'=>__('Low'),'2'=>__('High')),
																	'default'=>1,
																	'value'=>@$selected_price_order,
																	));*/
                                            
                                    ?>
                                </div>
                                <div class="right-part">
                                    <div id="card-pagging">
                                    <?php 
								    	/*$actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
										$this->Paginator->options(array('path'=> $actual_link));
										echo $this->Paginator->numbers(array(
										  'modulus' => 4,   /* Controls the number of page links to display */
										  /*'first' => '< First',
										  'last' => 'Last >',
										  'before' => ' ', 'after' => ' ')
										);*/
									?>
								     </div>
                                </div>
                            </div> -->
                            
                            <div id="card-details">
                                 <div id="cards"> 
                                  <?php 
									if(isset($selected_view) && !empty($selected_view) && $selected_view==2){//icon ?>
                    <div id="sales-reports">
                    <table class="table table-bordered">
                        <thead>
                          <tr>
                                <th><?php echo __('Card Name');?></th>
                                <th><?php echo __('Price');?></th>
                                <th><?php echo __('Action');?></th>
                          </tr>
                        </thead>
                        <tbody>
                         <?php 
												 if(isset($cards) && !empty($cards)){
												 foreach ($cards as $k=>$val) {?>  
                          <tr>
                                <td><?php  echo substr(ucfirst($val['Card']['c_title']),0,20);?></td>
                                <td>&euro;<?php echo $val[0]['price'];?></td>
                                <td><?php echo $this->Form->create('Cart',array('id'=>'add-form-'.$k,'class'=>'add-form','url'=>array('controller'=>'carts','action'=>'add')));?>
                                		<?php echo $this->Form->hidden('card_id',array('value'=>$val['Card']['c_id']))?>
                                     <?php echo $this->Form->submit('Add to cart',array('class'=>'button-gradient search-by-filter add-form-'.$k));?>
                                    <?php echo $this->Form->end();?>
                                </td>
                          </tr>
                          <?php }
													echo '<tr><td colspan="3">&nbsp;</td></tr>';
												 }else{ ?>
													<tr><td colspan="3"><?php echo __('No records found.');?></td></tr>
									<?php }?>                                
                         
                        </tbody>
                      </table>
            
                </div>							
<?php							}else	if(isset($cards) && !empty($cards))
									{ 
								    	$cnt = 0; 
									   //cards available  
									   foreach($cards as $k=>$val)
									   {
										$image = 'card_not_availabe.png';
										if(!empty($val['Card']['c_image']))
										{
											$image_path = 'img/card_icons/'.$val['Card']['c_image'];
											if (file_exists($image_path))
											{
												$image = $val['Card']['c_image'];				
											}
										}
								  ?>
                                   <?php echo $this->Form->create('Cart',array('id'=>'add-form-'.$k,'class'=>'add-form','url'=>array('controller'=>'carts','action'=>'add')));?>
                                     <div class="SearchMain">
                                        <div class="card">
                                        <?php if($view!=2){?>
                                        <?php echo $this->Html->image('../img/card_icons/'.$image, array('width'=>'180','height'=>'100'))?><br/>
                                        <?php }?>  
                                        <span><?php echo substr(ucfirst($val['Card']['c_title']),0,20);?>&euro;<?php echo $val[0]['price'];?></span> 
                                        <?php echo $this->Form->hidden('card_id',array('value'=>$val['Card']['c_id']))?>
                                        <?php echo $this->Form->submit('Add to cart',array('class'=>'btn-success btn btn-lg add-form-'.$k,'style'=>'display:none'));?>
	                                </div>
    	                        </div> 
                                <?php echo $this->Form->end();?>
								<?php		
								$cnt++;
								if($cnt==4)
								{
									echo '<div class="clear10"></div>';
								}
							  }
							 ?> 
							 <?php }else{ ?>
                                <div class="card_blocks no_record_found">
                					<?php echo __('No records found.');?>    
                                </div>
                                <?php } ?>
                           </div><!--cards-->
                       </div><!--card-details-->
                    </div>
                 </div>
<script type="application/javascript">
$(document).ready(function(){
	$('.SearchMain').click(function(){
		//submit_btn = $(this).parent().attr('id'); //submit form
		//submitfrm(submit_btn);
		$(this).parent().submit();
	});
	var submit_btn = '';
	function submitfrm(id){
		var tis = $('#'+submit_btn);
		$.post(tis.attr('action'),tis.serialize(),function(data){
			$('#cart-counter').text(data);
		});
	}

	//Highlight active menu
	$('#sb-opt-online-card').addClass('opt-selected');
	$('#sb-opt-online-card').next().toggle('sliderup');
	$('#sb-subopt-online-card').addClass('opt-selected');

});

function submitForm(tab_id){
	$('#tabs_cat').val(tab_id);
	$('#searchFormCat').submit();
}

$('#search-by-filter').click(function(){
	$('#searchForm').submit();
});

function submitSubForm(sub_cat_id){
	$('#sub_cat_id').val(sub_cat_id);
	$('#searchFormCat').submit();
}

function submitCharForm(char){
	$('#cards_char').val(char);
	$('#searchForm').submit();
}

$(function() {
    $('[class=card_rate] input[type="checkbox"]').on('click' , function(){
        // Caching all the checkboxes into a variable
        var checkboxes =  $('[class=card_rate] input[type="checkbox"]');
	    $('[class=card_rate] input[type="checkbox"]').each(function(){
        		if($(this).is(':checked')){
					 var val = $(this).val();
           			 $('#card_rate'+val).remove();
				}
		});
        // If one item is checked.. Uncheck all and
        // check current item..
        if($(this).is(':checked')){
             checkboxes.prop('checked', false);
             $(this).prop('checked', 'checked');
			 var val = $(this).val();
			 var card_rate = $("label[for='" + this.id + "']").text();
			 var new_div_id = 'card_rate'+val;
			 var div ="<div class='selected_filter card_rate' id= '"+new_div_id+"' onclick = 'remove_it(this,"+val+")'>Card Rate: "+card_rate+"&nbsp;&nbsp;<small>X</small></div>";
			 $(div).insertAfter('#append_selection');
        }
		else
		{
			 var val = $(this).val();
			 $('#card_rate'+val).remove();
		}
    });    
});


$(function() {
    $('[class=card_stock] input[type="checkbox"]').on('click' , function(){
        // Caching all the checkboxes into a variable
        var checkboxes =  $('[class=card_stock] input[type="checkbox"]');
        $('[class=card_stock] input[type="checkbox"]').each(function(){
        		if($(this).is(':checked')){
					 var val = $(this).val();
           			 $('#card_stock'+val).remove();
				}
		});
		// If one item is checked.. Uncheck all and
        // check current item..
        if($(this).is(':checked')){
            checkboxes.prop('checked', false);
             $(this).prop('checked', 'checked'); 
			 var val = $(this).val();
			 var card_stock = $("label[for='" + this.id + "']").text();
		 	 var new_div_id = 'card_stock'+val;
		  var div ="<div class='selected_filter card_stock' id= '"+new_div_id+"' onclick = 'remove_it(this,"+val+")'>Card Stock: "+card_stock+"&nbsp;&nbsp;<small>X</small></div>";
			 $(div).insertAfter('#append_selection');
        }
		else
		{
		   var val = $(this).val();
           $('#card_stock'+val).remove();
		}
    });    
});


$(function() {
    $('[class=card_icon] input[type="checkbox"]').on('click' , function(){
        // Caching all the checkboxes into a variable
        var checkboxes =  $('[class=card_icon] input[type="checkbox"]');
        $('[class=card_icon] input[type="checkbox"]').each(function(){
        		if($(this).is(':checked')){
					 var val = $(this).val();
           			 $('#card_icon'+val).remove();
				}
		});
        // If one item is checked.. Uncheck all and
        // check current item..
		if($(this).is(':checked')){
            checkboxes.prop('checked', false);
            $(this).prop('checked', 'checked');   
			 var val = $(this).val();
			 var card_icon = $("label[for='" + this.id + "']").text();
			 var new_div_id = 'card_icon'+val;
			 var div ="<div class='selected_filter card_icon' id= '"+new_div_id+"' onclick = 'remove_it(this,"+val+")'>Card View: "+card_icon+"&nbsp;&nbsp;<small>X</small></div>";
			 $(div).insertAfter('#append_selection');
        }
		else
		{
			 var val = $(this).val();
			 $('#card_icon'+val).remove();
		}
    });    
});

$('#clear_filter').click(function(){
	if($('.left-part > div').length > 2){ // if any filter chosen
		var ans = confirm("<?php echo "Are you sure you want to clear all the filters ?";?>");
		if(ans)
		{
			var checkboxes =  $('input[type="checkbox"]');
			checkboxes.prop('checked', false);
			$('.selected_filter').remove();
			var new_url = "<?php echo $this->Html->url(array('controller'=>'Searches','action'=>'online_card'));?>";
			window.location = new_url;
		}
	}else{
		alert("<?php echo "There is no filter"?>");	
	}
	
});

$(function() {
    $('[class=card_name] input[type="checkbox"]').on('click' , function(){
        // Caching all the checkboxes into a variable
        var checkboxes =  $('[class=card_icon] input[type="checkbox"]');
        // If one item is checked.. Uncheck all and
        // check current item..
         if($(this).is(':checked'))
		 {
			 var val = $(this).val();
			 var card_name = $("label[for='" + this.id + "']").text();
			 var new_div_id = 'card_name'+val;
			 var div ="<div class='selected_filter card_name' id= '"+new_div_id+"' onclick = 'remove_it(this,"+val+")'>Card : "+card_name+"&nbsp;&nbsp;<small>X</small></div>";
			 $(div).insertAfter('#append_selection');
		}
		else
		{
			 var val = $(this).val();
			 $('#card_name'+val).remove();
		}
    });    
});

function remove_it(obj,value){
	var class_new = $(obj).attr('class');
    if(class_new == "selected_filter card_name")
	{
        $('#CardCId'+value).prop('checked', false);
		$('#card_name'+value).remove(); 
	}
	else if(class_new == "selected_filter card_stock")
	{
        $('#CardStock'+value).prop('checked', false);
		$('#card_stock'+value).remove(); 
	}
	else if(class_new == "selected_filter card_rate")
	{
        $('#CardRate'+value).prop('checked', false);
		$('#card_rate'+value).remove(); 
	}
	else if(class_new == "selected_filter card_icon")
	{
        $('#CardView'+value).prop('checked', false);
		$('#card_icon'+value).remove(); 
	}
	
}

$('#CardPrice').change(function(){
/*var order = $(this).val();
<?php
//$actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
?>
var path = "<?php //echo $actual_link?>";
path = path+"/price_order:"+order;
window.location.href =path;
*/});

</script>						
                
              
