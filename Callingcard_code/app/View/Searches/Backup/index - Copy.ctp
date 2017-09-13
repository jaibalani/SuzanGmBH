<div id="right_panel">
<div class="row">
  <div class="col-md-12">
    <h1><?php echo $title_for_layout; ?></h1>
  </div>
</div>


<div class="clear10"></div>
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
						<li role="presentation" <?php echo $class;?>><a href="javascript:void(0)" onclick="submitForm('<?php echo $cat['Category']['cat_id']?>')"><?php echo $cat['CategoriesLanguage'][0]['cl_title']?></a></li>	
		<?php } 
			 }?>
  
</ul>
<div class="inner-main">
<?php echo $this->Form->create('searches',array('controller'=>'searches','action'=>'index','id'=>'searchForm'));?>
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
  <?php echo $this->Form->input('Card.c_cat_id',array('type'=>'hidden','id'=>'tabs_cat')).' '.$this->Form->input('Card.cards_char',array('type'=>'hidden','id'=>'cards_char'));?>
  <?php echo $this->Form->end();?>
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
							$image = 'card_not_availabe.png';
							if(!empty($val['Card']['c_image'])){
								$image_path = 'img/card_icons/'.$val['Card']['c_image'];
								if (file_exists($image_path)){
									$image = $val['Card']['c_image'];				
								}
							}?>
              	<?php echo $this->Form->create('Cart',array('id'=>'add-form-'.$k,'class'=>'add-form','url'=>array('controller'=>'carts','action'=>'add')));?>
              <div class="col-md-3 SearchMain">
								<?php if($view!=2){?>
                				<div class="SearchImg" style="cursor:pointer;"><?php echo $this->Html->image('../img/card_icons/'.$image, array())?></div>	
                <?php }?>
                <div class="SearchName"><?php echo substr(ucfirst($val['Card']['c_title']),0,35);?></div>
                <div class="SearchName">&euro;<?php echo $val[0]['price'];?></div>
                <?php echo $this->Form->hidden('card_id',array('value'=>$val['Card']['c_id']))?>
                <?php echo $this->Form->submit('Add to cart',array('class'=>'btn-success btn btn-lg add-form-'.$k,'style'=>'display:none'));?>
              </div><!--SearchMain-->	
              
              <?php echo $this->Form->end();?>
			<?php		$cnt++;
							if($cnt==4){
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
</div>

<script>
$(document).ready(function(){
	$('#show_all').click(function(){
		window.location.href = "<?php echo APPLICATION_URL?>/searches/index";
	});
	var submit_btn = '';
	$('.SearchMain').click(function(){
		//submit_btn = $(this).parent().attr('id'); //submit form
		//submitfrm(submit_btn);
		$(this).parent().submit();
	});
	function submitfrm(id){
		var tis = $('#'+submit_btn);
		$.post(tis.attr('action'),tis.serialize(),function(data){
			$('#cart-counter').text(data);
		});
	}
	/*$('.add-form').submit(function(e){
		e.preventDefault();
		var tis = $(this);
		$.post(tis.attr('action'),tis.serialize(),function(data){
			$('#cart-counter').text(data);
		});
	});*/
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