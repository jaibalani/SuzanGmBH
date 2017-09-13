<!--<title>CALLING CARD - Online Card</title>
-->
<style type="text/css">
.search_label{
float:left;
width:100px;
}
</style>
<?php echo $this->Html->css('tinycarousel');?>
<?php echo $this->Html->script('jquery.tinycarousel');?>
<script type="text/javascript">
	$(document).ready(function()
	 {
		 <?php if(isset($all_cat) && !empty($all_cat)){ 
		 					if(count($all_cat) > 6){?> 
								$('#slider1').tinycarousel();			
				<?php }
		 }?>
	 });
</script>
<script type="text/javascript">    
    $(document).ready(function(){
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
	
</script>

<div id="right_panel">
                     <div id="title">
                          Online Cards
                     </div>
                    
                    <div id="card_container">
                        <div id="slider1">
		                    <div class="viewport">
                             	<?php 
								$class = 'active';
								if(isset($active_tab) && !empty($active_tab)){
									//if any tab was chosen
								 $class = '';
								}?>
		                     	<ul class="overview">
                                     <li class="<?php echo $class;?> card_cursor" onclick="submitForm()">ALL CARDS</li>
                                     <?php if(isset($all_cat) && !empty($all_cat)){
																						foreach($all_cat as $cat){
																						$class= 'card_cursor';
																						if(isset($active_tab) && !empty($active_tab)){
																							if($active_tab==$cat['Category']['cat_id']){
																								$class = 'active card_cursor';
																								}
																						}?> 
									<li class="<?php echo $class;?>" onclick="submitForm('<?php echo $cat['Category']['cat_id']?>')"><?php echo $cat['CategoriesLanguage'][0]['cl_title']?></a></li>	
								<?php } 
			 					}?>
                                </ul>
                             </div>
                            <?php if(isset($all_cat) && !empty($all_cat)){ 
																if(count($all_cat) > 6){ ?>
																<div id="prev_next1">
																<a class="buttons prev lanslideleftarrow" href="#">&lt;</a>
																 <a class="buttons next lansliderightarrow" href="#">&gt;</a>
																</div>
													<?php }
													}?>
                        </div>
                        
                    </div>
                    
                    <div id="container" >
                        <div id="card_form">
                            <?php echo $this->Form->create('searches',array('controller'=>'searches','action'=>'index','id'=>'searchForm'));?>
                                <div class="fliter_block"> 
                                    <div class="fliter">
                                          <div class="search_label">Card Name</div>
                                          <?php echo $this->Form->input('Card.c_id',array(
																								'label' 	 => false, 
																								'required' => false, 
																								'id' 	 => 'filter1',
																								'default'	 => '',
																								'empty' 	 => '--- Select Card---',
																								'options'  => $card_names,
																								));?>
                                    </div>
                                    <div class="fliter">
                                        <div class="search_label">Price</div>
																					<?php echo $this->Form->input('Card.rate',array(
                                            'label' 	 => false, 
                                            'type' 		 => 'select',
                                            'required' => false, 
                                            'id' 	 => 'filter2',
                                            'empty' 	 => '--- Select Price---',
                                            'options'  => array("1" => "Under 100", "2" => "101 - 200","3" => "201 - 300", "4" => "301 - 400","5" => "401 - 500", "6" => "501 - 600", "7" => "More Than 600")));?>
                                    </div>
                                </div>
                                <div class="fliter_block">
                                    <div class="fliter">
                                        <div class="search_label">Show only</div>
                                        <?php echo $this->Form->input('Card.stock',array(
																						'label' 	 => false, 
																						'required' => false, 
																						'id' 	 => 'filter3',
																						'default'	 => '',
																						'empty' 	 => '--- Select Card---',
																						'options'  => array("1" => "All Cards", "2" => "Available stock"),
																					));?>
																			</div>
                                    <div class="fliter">
                                        <div class="search_label">View Card</div>
                                        <?php echo $this->Form->input('Card.view',array(
																					'label' 	 => false, 
																					'required' => false, 
																					'type' 		 => 'select',
																					'id' 	 => 'filter4',
																					'empty' 	 => '--- Select View---',
																					'options'  => array("1" => "Icon", "2" => "List")));?>
                                    </div>
                                </div>
                                <div id="search_card">
                                    <?php echo $this->Form->submit('SEARCH', array('type'=>'submit','label'=>false,'id'=>'submit_btn','div'=>false));?>
                                </div>
                               <?php echo $this->Form->input('Card.c_cat_id',array('type'=>'hidden','id'=>'tabs_cat')).' '.$this->Form->input('Card.cards_char',array('type'=>'hidden','id'=>'cards_char'));?>
   
                            <?php echo $this->Form->end();?>
                            
                        </div>
                        
                        <div class="alphabets">
                            <?php foreach (range('A', 'Z') as $char){
																		$class = 'letters';
																		if($selchar==$char){
																			$class = 'letters active';
																		}?>
                                    <div class="<?php echo $class?>" onclick="submitCharForm('<?php echo $char?>')" > <strong><?php echo $char?></strong></div>
							<?php }?>
                        </div>
                        
                        <div id="cards"> 
                           
							<?php if(isset($cards) && !empty($cards)){ $cnt = 0; 
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
                              <div class="card_blocks SearchMain">
                                <div class="card">
									<?php if($view!=2){?>
                                    <?php echo $this->Html->image('../img/card_icons/'.$image, array('width'=>'180','height'=>'100'))?><br/>
                                    <?php }?>  
                                    <span><?php echo substr(ucfirst($val['Card']['c_title']),0,35);?>&euro;<?php echo $val[0]['price'];?></span> 
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
                            </div>
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
	$('#searchForm').submit();
}
function submitCharForm(char){
	$('#cards_char').val(char);
	$('#searchForm').submit();
}
</script>						
                
              
