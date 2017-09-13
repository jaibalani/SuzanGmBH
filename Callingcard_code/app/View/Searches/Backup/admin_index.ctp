<style type="text/css">

  .SearchMain {
    padding-left: 0px !important;
    margin: 10px 0px !important;
    width: 25%;
  }

</style>
<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <?php if($this->Session->read('Auth.User.role_id') == 1) {?>
  <div class="sub_title"><i class="icon-list-alt home_icon"></i> <span class="sub_litle_m">Product Master</span> <i class="icon-angle-right home_icon"></i> <span>Card List</span>
  </div>
  <?php } else { ?>
  <div class="sub_title"><i class="icon-list-alt home_icon"></i> <span class="sub_litle_m">Online Cards</span> <i class="icon-angle-right home_icon"></i> </div>
  <?php } ?>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title mediator_w"><?php echo $title_for_layout; ?></div>
        <button class="new_button back" type="button" style="cursor:pointer;float:right;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
    </div>    
  
  <div class="clear10"></div>
  <div align="left" class="grid_table_box">
   <?php echo $this->Form->create('searches',array('controller'=>'searches','action'=>'admin_index','id'=>'searchForm'));?>
   <?php echo $this->Form->input('Card.cards_char',array('type'=>'hidden','id'=>'cards_char'));?>
<div class="clear10"></div>
<?php /* ?>
<ul class="nav nav-tabs">
	<?php 
		$class = 'class="active"';
		if(isset($active_tab) && !empty($active_tab)){
			//if any tab was chosen
					$class = '';
		}?>
  <li role="presentation" <?php echo $class;?>><a href="javascript:void(0)" onclick="submitForm()">All Cards</a></li>
  <?php if(isset($all_cat) && !empty($all_cat)){
					foreach($all_cat as $cat){
						$class= '';
						if(isset($active_tab) && !empty($active_tab)){
							if($active_tab==$cat['Category']['cat_id']){
								$class = 'class="active"';
							}
						}?> 
						<li role="presentation" <?php echo $class;?>><a href="javascript:void(0)" onclick="submitForm('<?php echo $cat['Category']['cat_id']?>')"><?php echo $cat['Category']['cat_title']?></a></li>	
		<?php } 
			 }?>
  
</ul>
<php */ ?>
<div class="inner-main">
    <div class="clear10"></div>
    <div class="row">
    <div class="col-md-12">
      <div class="col-md-3">Category</div>
      <div class="col-md-3"><?php echo $this->Form->input('Card.c_cat_id',array(
                          'label' 	 => false, 
													'required' => false, 
                          'class' 	 => 'form-control',
                          'id' => 'cat_id',
                          'default'	 => '',
                          'empty' 	 => '--- Select Category ---',
                          'options'  => $all_catList,
                          ))?></div>
      <div class="col-md-3">Sub Category</div>  
      <div class="col-md-3"><?php echo $this->Form->input('Card.sub_cat_id', 
                                    array(
                                    	'type' => 'button',
                                        'class'=>'form-control',
                                        'type'=>'select',
                                        'options'=> @$subCatList,
	                                    'value'=>@$sub_cat_id,
		                                'label'=>false,
                                        'style'=>'cursor:pointer;',
                                        'empty'=>'--- Select Sub Category ---'
                                        )
                                    );
                             ?></div>  
    </div>
  </div><!--row-->
  <div class="clear10"></div>
  <div class="row">
    <div class="col-md-12">
      <div class="col-md-3">Card Name</div>
      <div class="col-md-3"><?php echo $this->Form->input('Card.c_id',array(
                          'label' 	 => false, 
													'required' => false, 
                          'class' 	 => 'form-control',
                          'default'	 => '',
                          'empty' 	 => '--- Select Card---',
                          'options'  => $card_names,
                          ))?></div>
      <div class="col-md-3">Price</div>  
      <div class="col-md-3"><?php echo $this->Form->input('Card.rate',array(
                        'label' 	 => false, 
                        'type' 		 => 'select',
												'required' => false, 
                        'class' 	 => 'form-control',
                        'empty' 	 => '--- Select Price---',
                        'options'  => array("1" => "Under 100", "2" => "101 - 200","3" => "201 - 300", "4" => "301 - 400","5" => "401 - 500", "6" => "501 - 600", "7" => "More Than 600")));?></div>  
    </div>
  </div><!--row-->
  <div class="clear10"></div>
  <div class="row">
    <div class="col-md-12">
      <div class="col-md-3">Show Only</div>
      <div class="col-md-3"><?php echo $this->Form->input('Card.stock',array(
                          'label' 	 => false, 
													'required' => false, 
                          'class' 	 => 'form-control',
                          'default'	 => '',
                          'empty' 	 => '--- Select Card---',
                          'options'  => array("1" => "All Cards", "2" => "Available stock"),
                          ))?></div>
      <div class="col-md-3">View Card</div>  
      <div class="col-md-3"><?php echo $this->Form->input('Card.view',array(
                        'label' 	 => false, 
												'required' => false, 
                        'type' 		 => 'select',
                        'class' 	 => 'form-control',
                        'empty' 	 => '--- Select View---',
                        'options'  => array("1" => "Icon", "2" => "List")));?></div>  
    </div>
  </div><!--row-->
  <div class="clear10"></div>
	<div class="row">
  	<div class="col-md-3">&nbsp;</div>
    <div class="col-md-3"><?php echo $this->Form->submit('SEARCH', array('type'=>'submit', 'class'=>'btn btn-primary','label'=>false,'id'=>'submit_btn','div'=>false)).'&nbsp;&nbsp;'.$this->Form->button('SHOW ALL', array('type'=>'button', 'class'=>'btn btn-primary','label'=>false,'id'=>'show_all')); ?></div>
    <div class="col-md-6">&nbsp;</div>
  </div>
  <div class="clear20"></div>
  <nav role="navigation" class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div class="navbar-collapse collapse" id="navbar">
            <ul class="nav navbar-nav">
            	<?php foreach (range('A', 'Z') as $char){
											$class = '';
											if($selchar==$char){
												$class = 'class="active"';
											}?>
											<li <?php echo $class?>><a href="javascript:void(0)" onclick="submitCharForm('<?php echo $char?>')"><?php echo $char?></a></li>
							<?php }?>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
  
  <div class="clear20"></div>
  <div class="row">
  	<div class="col-md-1">&nbsp;</div>
  	<div class="col-md-10">
		<?php if(isset($cards) && !empty($cards)){ //cards available
						$cnt = 0;
						foreach($cards as $k=>$val){
                          //  pr($val);
							$image = 'card_not_availabe.png';
							if(!empty($val['Card']['c_image'])){
								$image_path = 'img/card_icons/'.$val['Card']['c_image'];
								if (file_exists($image_path)){
									$image = $val['Card']['c_image'];				
								}
							}?>
              <div class="col-md-3 SearchMain">
								<?php if($view!=2){?>
                				<div class="SearchImg"><?php echo $this->Html->image('../img/card_icons/'.$image, array('width'=>'180','height'=>'100'))?></div>	
                <?php }?>
                <div class="SearchName"><?php echo substr(ucfirst($val['Card']['c_title']),0,35);?></div>
                <div class="SearchName">&euro;<?php echo $val[0]['price'];?></div>
                <div class="SearchName"><?php echo $val['MainCate']['cat_title'];?></div>
                <div class="SearchName"><?php echo $val['SubCate']['cat_title'];?></div>
                <div class="SearchName">PINs per card : <?php echo $val['Card']['c_pin_per_card'];?></div>
                <div class="SearchName">Threshold : <?php echo $val['Card']['c_inventory_threshold'];?></div>
                <div class="SearchName">Status : <?php echo ($val['Card']['c_status'] == 1)?" Enable":" Disable";?></div>
                <div class="SearchName"><?php echo $val['Card']['created'];?></div>
                
              </div>	
			<?php		$cnt++;
							if($cnt%4==0 || count($cards) == $cnt){
								echo '<div class="clear10"></div>';
							}
						}?> 
		<?php }else{
						echo '<div class="col-md-4">&nbsp;</div>
  							<div class="col-md-6">';
						echo 'No Records Found!!!';
						echo '</div><div class="col-md-2">&nbsp;</div>';
					}?>
    </div>
   	<div class="col-md-1">&nbsp;</div>
  </div>
</div><!--inner-main-->
<?php echo $this->Form->end();?>
		
  </div>
  <div class="clear10"></div>
  </div>
</div>



<script>

$('.back').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'dashboard','admin'=>'true'));?>";
    //window.location.href = url;
	history.go(-1);
});


$(document).ready(function(){

  $('#cat_id').change(function(){
    $.ajax({
      beforeSend: function (XMLHttpRequest) {
         $("#loading-image").fadeIn();
      },
      complete: function (XMLHttpRequest, textStatus) {
        $("#loading-image").fadeOut();
      },
      url: "<?php echo Router::url(array('controller'=>'cards','action'=>'get_subcat'));?>", 
      type: "POST",
      data: ({id : $(this).val()}),
      dataType: 'json',
      success: function(json){
        $('#CardSubCatId').html('');
        $('#CardSubCatId').html('<option value="">--- Select Sub Category---</option>');
        $.each(json, function(i, value) {
          $('#CardSubCatId').append($('<option>').text(value).attr('value', i));
            });
      }
    });
  });

	$('#show_all').click(function(){
		window.location.href = "<?php echo APPLICATION_URL?>admin/searches/index";
	});
});
function submitForm(tab_id){
	$('#tabs_cat').val(tab_id);
	$('#searchForm').submit();
}
function submitCharForm(char){
	$('#cards_char').val(char);
	$('#searchForm').submit();
}

$(document).ready(function(){
   $('#product').addClass('sb_active_opt');
   $('#product').removeClass('has_submenu');
   $('#addlist_active').addClass('sb_active_subopt_active');
   $('#online_card_opt').addClass('sb_active_opt');
   $('#sb_online_card').removeClass('has_submenu');
}) ;
</script>