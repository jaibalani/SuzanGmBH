<?php ?> 
<style type="text/css">
..navbar{
  margin-bottom: unset;
}
.card_preview{
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #dcdcdc;
    border-image: none;
    border-style: solid;
    border-width: 1px 1px 2px;
    float: left;
    margin-top: 152px;
    margin-left: -130px;
    font-size: 10px;
    padding: 2px 5px;
    color: #FFFFFF;
    background: linear-gradient(to bottom, rgba(32, 124, 202, 1) 19%, rgba(30, 87, 153, 1) 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);
}
.fancybox-image, .fancybox-iframe {
  max-height: 430px;
  width: 200px;
}
.card{
  margin-bottom: 35px !important;
  min-height: 148px;
}
.total_card_css
{
  font-size: 12px;
}
.filter-option ul li{
padding-left:24px;
}
.active_main_card{
  font-weight: bold;
}
.sub-sub-card-menu{
  font-weight: normal;
}
.table{
  margin-bottom: 0px !important;
}
input[type="checkbox"][name="data[Card][rate][]"],
input[type="checkbox"][name="data[Card][stock][]"],
input[type="checkbox"][name="data[Card][view][]"]
{
  opacity: 0;
  margin-left: -30px;
}
label{
  width:100px !important;
  cursor: pointer;
}
.card_rate{
  cursor: pointer;
}
.card{ position: relative; }
.sb-tooltip-block{ position: absolute; width: 30px; height: 30px; background-color: rgba(194, 194, 194, 0.6); right: 0px;}
.sb-tooltip-block-detail{ border: 1px solid #DCDCDC; padding: 8px 8px; position: absolute; background-color: #fff; right: 0px; top: 30px; display: none; text-align: left; font-size: 11px;}
.sb-tooltip-block:hover + .sb-tooltip-block-detail{ display: block; }
.sb-bottom-margin{ margin-bottom: 0px; min-height: 30px; width:760px;}
.container-fluid{ padding: 0px; }
.sb-navbar-css{ padding: 0px; }
.sb-navbar-css ul{padding-top: 3px; padding-left: 4px;}
.sb-navbar-css ul li a{ padding: 1px 10px; font-size: 11px;}
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

      $("label[for='" + check_box_id+ "']").css('background-color','#e2e2e2');
      $("label[for='" + check_box_id+ "']").css('width','100px');

			var div ="<div class='selected_filter card_icon' id= '"+new_div_id+"' onclick = 'remove_it(this,"+selected_card_icon+")'>Card View : "+card_icon+"&nbsp;&nbsp;<small>X</small></div>";
			$(div).insertAfter('#append_selection');
		   
		 }
		 
		 if(selected_card_stock.length >0)
		 {
			var div = '';
			var new_div_id = "card_stock"+selected_card_stock;
			var check_box_id = "CardStock"+selected_card_stock;
			var card_stock = $("label[for='" + check_box_id+ "']").text();

      $("label[for='" + check_box_id+ "']").css('background-color','#e2e2e2');
      $("label[for='" + check_box_id+ "']").css('width','100px');

			var div ="<div class='selected_filter card_stock' id= '"+new_div_id+"' onclick = 'remove_it(this,"+selected_card_stock+")'>Card Stock: "+card_stock+"&nbsp;&nbsp;<small>X</small></div>";
			$(div).insertAfter('#append_selection');
		   
		 }
		 
		 if(selected_card_rate.length >0)
		 {
			var div = '';
			var new_div_id = "card_rate"+selected_card_rate;
			var check_box_id = "CardRate"+selected_card_rate;
			var card_rate = $("label[for='" + check_box_id+ "']").text();

      $("label[for='" + check_box_id+ "']").css('background-color','#e2e2e2');
      $("label[for='" + check_box_id+ "']").css('width','100px');

			var div ="<div class='selected_filter card_rate' id= '"+new_div_id+"' onclick = 'remove_it(this,"+selected_card_rate+")'>Card Price: "+card_rate+"&nbsp;&nbsp;<small>X</small></div>";
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
                            $class = 'active card_cursor active_main_card';
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
    										$sub_class = 'sub-sub-card-menu sub-menu-card-active sub_active_font';
    									}
    								}
					  ?>
                     <div class="<?php echo $sub_class;?>" onclick="submitSubForm('<?php echo $key;?>',event)"><?php  echo ucwords(strtolower($value));?></div>
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
          
      <nav role="navigation" class="navbar navbar-default sb-bottom-margin" style= "float:left;">
        <div class="container-fluid">
          <div class="navbar-header">
            <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div class="navbar-collapse collapse sb-navbar-css" id="navbar">
           <ul class="nav navbar-nav alpha">
           <?php if ($selchar =='All' ) {?>
        <li class="active"><a href="javascript:void(0)" onclick="submitCharForm('All')"><?php echo __('All');?></a></li>
          <?php } else {?>
              <li class=""><a href="javascript:void(0)" onclick="submitCharForm('All')"><?php echo __('All');?></a></li>
          <?php } ?>

        <?php foreach (range('A', 'Z') as $char){
                      $class = '';
                      if($selchar==$char)
                      {
                        $class = 'class="active"';
                      }
        ?>
                      <li <?php echo $class?>><a href="javascript:void(0)" onclick="submitCharForm('<?php echo $char?>')"><?php echo $char?></a></li>
              <?php }?>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
      
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
          <?php /*echo $this->Form->create('searches',array('controller'=>'searches','action'=>'online_card','id'=>'searchFormCat'));?>
             <?php echo $this->Form->input('Card.c_cat_id',array('type'=>'hidden','id'=>'tabs_cat')).' '.$this->Form->input('Card.c_sub_cat_id',array('type'=>'hidden','id'=>'sub_cat_id'));?>
             <?php echo $this->form->end();*/ ?>
          
          <!--This section is under construction-->
        <div id="card-main-filter">
          <?php echo $this->Form->create('searches',array('controller'=>'searches','action'=>'online_card','id'=>'searchForm'));?>
            <?php 
                echo $this->Form->input('Card.c_cat_id',array('type'=>'hidden',
                                                              'id'=>'tabs_cat',
                                                              'value'=>@$c_cat_id)); 
                echo $this->Form->input('Card.c_sub_cat_id',array('type'=>'hidden',
                                                              'id'=>'sub_cat_id',
                                                              'value'=>@$c_sub_cat_id)); 
                echo $this->Form->input('Card.c_char',array('type'=>'hidden',
                                                              'id'=>'c_char',
                                                              'value'=>@$selchar)); 
            ?>
              
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
                            "1" => "0 <= 1",
                            "2" => "> 1 <= 2.5",
                            "3" => "2.5 <= 5",
                            "4" => "> 5 <= 10",
                            "5" => "> 10");
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
                    </div>
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
                  </div>
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
            <div class="right-part total_card_css">
                <strong>
                  <?php echo __('Total Cards')?>
                  <span id="sb_total_card"> ( <?php echo @$total_cards_pagination;?> )
                  </span>
                </strong>
            </div>
          </div> 
          <?php echo $this->Form->end(); ?>
    </div>
                            
    <?php if(isset($selected_view) && !empty($selected_view) && $selected_view==2){
           //icon ?>                       
    <div id="card-details" style="width:auto;">
        <div id="cards" style="padding:0px !important;"> 
    <? }else { ?>
     <div id="card-details">
        <div id="cards"> 
    <?php }  ?> 
             <?php 
		                	if(isset($selected_view) && !empty($selected_view) && $selected_view==2){//icon ?>
                        <div id="sales-reports">
                        <table class="table table-bordered">
                        <thead>
                          <tr>
                                <th><?php echo __('Card Name');?></th>
                                <th><?php echo __('Price');?></th>
                                <th><?php echo __('Available Pins');?></th>
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
                                <td>
                                <?php 
                                if($val['Card']['pin_card_remain_count']) 
                                echo $val['Card']['pin_card_remain_count'];
                                else
                                echo __('Sold Out');  
                                ?>
                                </td>
                                <td><?php echo $this->Form->create('Cart',array('id'=>'add-form-'.$k,'class'=>'add-form','url'=>array('controller'=>'carts','action'=>'add')));?>
                                		<?php echo $this->Form->hidden('card_id',array('value'=>$val['Card']['c_id']))?>
                                    <?php echo $this->Form->hidden('loaded_url',array('type'=>'hidden','class'=>'loaded_url'))?>

                                     <?php echo $this->Form->submit('Add to cart',array('class'=>'button-gradient search-by-filter add-form-'.$k));?>
                                    <?php echo $this->Form->end();?>
                                </td>
                          </tr>
                          <?php }
													//echo '<tr><td colspan="3">&nbsp;</td></tr>';
												   }
                          else
                            { 
                          ?>
													<tr><td colspan="3"><?php echo __('No records found.');?></td></tr>
									    <?php } ?>                                
                         
                        </tbody>
                      </table>
            
                </div>							
                <?php	} 
                      else	if(isset($cards) && !empty($cards))
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
                    <?php echo $this->Form->hidden('loaded_url',array('type'=>'hidden','class'=>'loaded_url'))?>

                   <div class="SearchMain">

                      <div class="card">
                            <div class="sb-tooltip-block"><?php echo $this->Html->image('info.png', array('width'=>'10','style' => 'width:28px; height:28px;'))?></div>
                            <div class="sb-tooltip-block-detail" style="height:50px;">
                                <?php 
                                
                                $discount = $val[0]['price'] -$val[0]['buying_price'];
                                $discount_persentage = ($discount*100)/$val[0]['price'];
                                $discount_persentage =number_format($discount_persentage, 2, '.', '');
                                  echo __('Buying Percentage').' : '.$discount_persentage;
                                ?>
                                <br/>
                                <?php echo __('Buying Price').' : '.$val[0]['buying_price'];?><br/>
                                <?php //echo __('Price').' : '.$val[0]['price'];?><br/>
                            </div>
                      <?php if($view!=2){?>
                      <?php echo $this->Html->image('../img/card_icons/'.$image, array('width'=>'180','height'=>'100'))?><br/>
                      <?php }?>  
                      <span><?php echo substr(ucfirst($val['Card']['c_title']),0,20);?>&nbsp; &euro;<?php echo $val[0]['price'];?></span> 
                      </br>
                      <div style="float:left;text-align: center; width: 100%;font-size: 12px;">

                      <?php 

                      if($val['Card']['pin_card_remain_count'])
                        //echo __('Available Pins').' : '.$val['Card']['pin_card_remain_count'];
                        echo '';
                      else  
                         echo __('Sold Out');

                      ?>
                      </div>
                      
                      <?php echo $this->Form->hidden('card_id',array('value'=>$val['Card']['c_id']))?>
                      <?php echo $this->Form->submit('Add to cart',array('class'=>'btn-success btn btn-lg add-form-'.$k,'style'=>'display:none'));?>
                </div>
              </div> 
              <?php echo $this->Form->end();?>
              <div class="card_preview">
              <a class="get_card_preview" style="color:#FFFFFF;text-decoration:none;" href="<?=$this->html->url(array('controller'=>'Searches', 'action'=>'print_preview_popup',$val['Card']['c_id'],$val[0]['price']))?>">
                    <?php echo __('Preview Card');?>
                  </a>
              </div>
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
  loaded_url = '';
  $(document).ready(function(){
   loaded_url = document.URL;
    $('.loaded_url').val(loaded_url);
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

$(".get_card_preview").fancybox({
      'autoDimensions'  : false,
      'hideOnOverlayClick': false,
      'scrolling'       : 'no',
      'width'           : 190,
      'transitionIn'    : 'none',
      'transitionOut'   : 'none',
      'autoScale'       : false,
      'type'        : 'iframe',
  });

function submitForm(tab_id){
	$('#tabs_cat').val(tab_id);
  $('#sub_cat_id').val('');
	 $('#searchForm').submit();
  //$('#searchFormCat').submit();
}

$('#search-by-filter').click(function(){
	$('#searchForm').submit();
});


function submitSubForm(sub_cat_id, event){
	event.stopPropagation();
  $('#tabs_cat').val('');
  $('#sub_cat_id').val(sub_cat_id);
  $('#searchForm').submit();
  //$('#searchFormCat').submit();
}

function submitCharForm(char_code){
  $('#c_char').val(char_code);
  $('#searchForm').submit();
  //$('#searchFormCat').submit();
}



$(function() {
    $('[class=card_rate] input[type="checkbox"]').on('click' , function(){
        // Caching all the checkboxes into a variable
        var checkboxes =  $('[class=card_rate] input[type="checkbox"]');
	    $('[class=card_rate] input[type="checkbox"]').each(function(){
        		if($(this).is(':checked')){
                 var val = $(this).val();
           	 		 $('#card_rate'+val).remove();
                 $("label[for='"+$(this).attr("id")+"']").css('background-color','#ffffff');
				}
		});
    // If one item is checked.. Uncheck all and
    // check current item..
    if($(this).is(':checked')){
             checkboxes.prop('checked', false);
             $(this).prop('checked', 'checked');
			 var val = $(this).val();
       
       $("label[for='"+$(this).attr("id")+"']").css('background-color','#e2e2e2');
       $("label[for='"+$(this).attr("id")+"']").css('width','100px;');
			 
       var card_rate = $("label[for='" + this.id + "']").text();
			 var new_div_id = 'card_rate'+val;
			 var div ="<div class='selected_filter card_rate' id= '"+new_div_id+"' onclick = 'remove_it(this,"+val+")'>Card Price: "+card_rate+"&nbsp;&nbsp;<small>X</small></div>";
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
       
       $("label[for='"+$(this).attr("id")+"']").css('background-color','#ffffff');
       $("label[for='"+$(this).attr("id")+"']").css('width','100px;');
           

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

       $("label[for='"+$(this).attr("id")+"']").css('background-color','#e2e2e2');
       $("label[for='"+$(this).attr("id")+"']").css('width','100px;');
       
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
                  $("label[for='"+$(this).attr("id")+"']").css('background-color','#ffffff');
                  $("label[for='"+$(this).attr("id")+"']").css('width','100px');
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
			 
        $("label[for='"+$(this).attr("id")+"']").css('background-color','#e2e2e2');
        $("label[for='"+$(this).attr("id")+"']").css('width','100px');

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
	  
    
    if($('.left-part > div').length > 2)
    { // if any filter chosen
		var ans = confirm("<?php echo __('Are you sure you want to clear all the filters ?');?>");
		if(ans)
		{
			var checkboxes =  $('input[type="checkbox"]');
			checkboxes.prop('checked', false);
			$('.selected_filter').remove();
    	var new_url = "<?php echo $this->Html->url(array('controller'=>'Searches','action'=>'online_card'));?>";
			window.location = new_url;
		}
	}
  else
  {
     if($('#tabs_cat').val() || $('#sub_cat_id').val())
     {
       $('#tabs_cat').val('');
       $('#sub_cat_id').val('');
       //$('#searchFormCat').submit();
       $('#searchForm').submit();
     }
  	 else
     {
       alert('<?php echo __("There is no filter")?>');  
     }
     
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

$(function() {
    
     var sub_cat_name = "<?php echo @$sub_cat_name?>";
     var sub_category = "<?php echo @$c_sub_cat_id?>";
     
     if(sub_category.trim().length != 0)
     {
       
        var new_div_id = 'card_sub_category'+sub_category;
        var div ="<div class='selected_filter card_sub_category' id= '"+new_div_id+"' onclick = 'remove_it(this,"+sub_category+")'>Card Sub Category: "+sub_cat_name+"&nbsp;&nbsp;<small>X</small></div>";
          $(div).insertAfter('#append_selection');  
     }
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
        var id = 'CardStock'+value;
        $("label[for="+id+"]").css('background-color','#ffffff');
        $("label[for="+id+"]").css('width','100px'); 
	}
	else if(class_new == "selected_filter card_rate")
	{
        $('#CardRate'+value).prop('checked', false);
		    $('#card_rate'+value).remove(); 
        var id = 'CardRate'+value;
        $("label[for="+id+"]").css('background-color','#ffffff');
        $("label[for="+id+"]").css('width','100px'); 
	}
	else if(class_new == "selected_filter card_icon")
	{
        $('#CardView'+value).prop('checked', false);
		    $('#card_icon'+value).remove(); 
        var id = 'CardView'+value;
        $("label[for="+id+"]").css('background-color','#ffffff');
        $("label[for="+id+"]").css('width','100px'); 
	}
  else if(class_new == "selected_filter card_sub_category")
  {
        $('input[name="data[Card][c_sub_cat_id]"]').val("");
        $('input[name="data[Card][c_cat_id]"]').val("");
        $('#card_sub_category'+value).remove(); 
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
                
              
