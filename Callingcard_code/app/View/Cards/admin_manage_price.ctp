<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title_for_layout; ?></div>

  <div class="sub_title">
    <i class="icon-user home_icon">
  	</i> 
  	<span class="sub_litle_m">
  		Mediator
  	</span> 
  	<i class="icon-angle-right home_icon"></i> 
  	<span>
  		Mediator Card Price
  	</span>
  </div>

  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title mediator_w" style="width:30%;"><?php echo $title_for_layout; ?></div>
        <button class="new_button clear_filer_class" style="float:right;" type="button" style="cursor:pointer;">
            <span class="icon-remove icon-white"></span>&nbsp;&nbsp;Clear Filter
        </button>
		<button class="new_button back " type="button" style="cursor:pointer;float:right;margin-right:10px;"><span class="icon-backward icon-white"></span>&nbsp;&nbsp;Back</button>
    </div>    
  
  <div class="clear10"></div>
  <div align="left" class="grid_table_box">
  
  	<div class="clear10"></div>

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
     			 <?php if ($selchar =='All' ) {?>
				<li class="active"><a href="javascript:void(0)" onclick="submitChar('All')"><?php echo __('All');?></a></li>
			    <?php } else {?>
            	<li class=""><a href="javascript:void(0)" onclick="submitChar('All')"><?php echo __('All');?></a></li>
			    <?php } ?>
				
				<?php  foreach (range('A', 'Z') as $char){
	            $class = '';
				if($selchar==$char){
					$class = 'class="active"';
				}?>
			<li <?php echo $class?>>
				<a href="javascript:void(0)" onclick="submitChar('<?php echo $char?>')">
				<?php echo $char?>
				</a>
			</li>
							<?php }?>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
</nav>
<div class="clear10"></div>
<?php echo $this->Form->create('CardCats',array('id'=>'card_from')); ?>
<div class="selectbox_div">
    <div class="col-md-3 " align="left" style="padding-left:0px !important;">
            <?php
             echo $this->Form->input('cat_id', 
                   array('type' => 'button',
                        'class'=>'select_boxfrom form_submit',
                        'type'=>'select',
                        'options'=> @$catList,
                        'value'=>@$cat_id,
                        'label'=>false,
                        'style'=>'cursor:pointer;',
                        'empty'=>'--- All Category ---'
                       )
                   );
            ?>
    </div>  
    <div class="col-md-3 " align="right" style="">
                 <?php echo $this->Form->input('sub_cat_id', 
                        array(
                        	'type' => 'button',
                            'class'=>'select_boxfrom form_submit',
                            'type'=>'select',
                            'options'=> @$subCatList,
                            'value'=>@$sub_cat_id,
                            'label'=>false,
                            'style'=>'cursor:pointer;',
                            'empty'=>'--- All Sub Category ---'
                            )
                        );
                 ?>
    </div>
    <div class="col-md-3 " align="left" style="padding-left:0px !important;">
            <?php
             echo $this->Form->input('c_id', 
                   array('type' => 'button',
                        'class'=>'select_boxfrom form_submit',
                        'type'=>'select',
                        'options'=> @$cardList,
                        'value'=>@$card_id,
                        'label'=>false,
                        'style'=>'cursor:pointer;',
                        'empty'=>'--- All Card ---'
                       )
                   );
            ?>
    </div>
    <div class="col-md-3 "><?php echo $this->Form->input('card_rate',array(
            'label' 	 => false, 
            'type' 		 => 'select',
			'required' => false, 
			'value' => @$rate,
            'class' 	 => 'select_boxfrom form_submit',
            'empty' 	 => '--- Select Purchase Price---',
            'options'  => array(
            				"1" => "0<=1",
            				"2" => ">1<=2.5",
            				"3" => ">2.5<=5",
            				"4" => ">5<=10",
            				"5" => ">10"
            			)));?>
             <?php 
				echo $this->Form->input('mediator_id',array('class'=>'form-control',
											'required' =>true,
											'type'=>'hidden',
											'value'=>@$mediator_id,
											'label'=>false)); ?>
             <?php 
				echo $this->Form->input('char_code',array('class'=>'form-control',
											'required' =>true,
											'type'=>'hidden',
											'value'=>0,
											'label'=>false)); ?>
   	</div>
</div>
<div class="clear10"></div>
<?php echo $this->Form->end(); ?>
	<div class="row">
	   <div class="col-md-6">
	       <div class="col-md-3 selectbox_title">Select Mediator</div>
	       <div class="col-md-9">	 
				<?php 
				echo $this->Form->input('mediator_id',array(
					'class'=>'form-control',
					'type'=>'select',
					'div'=>array('class' => 'sb-select-margin'),
					'options'=>@$mediator_list,
					'value'=>@$mediator_id,
					'empty'=>'Select Mediator',
					'label'=>false)); ?>
	      </div>
      </div>
      <?php if(isset($mediator_id) && !empty($mediator_id)) { ?> 
      <div class="col-md-2 selectbox_title">Show Overridden Card</div>
      <div class="col-md-2">	 
			<?php 
			echo $this->Form->input('over_riddend_cards',array(
				'class'=>'form-control',
				'type'=>'checkbox',
				'hiddenField'=>false,
				'div'=>'false',
				'label'=>false)); ?>
       </div>
      <?php }?>
    </div>
<div class="clear10"></div>
    <?php 
		if($mediator_id || !$mediator_id)
		{
	?>
    <?php echo $this->form->create('Card',array('id'=>'card')); ?>
    <table class="table table-striped">
        <?php if(!$mediator_id) 
        	{ 
        ?>
        <caption>Card Price Management For Distributor <b>
        <?php echo ucwords($this->Session->read('Auth.User.fname')." ".$this->Session->read('Auth.User.lname'));?></b></caption>
        <?php } else {?>
        <caption>Distributor's Card Price Management For <b><?php echo $mediator_name;?></b></caption>
        <?php } ?>
        <thead>
            <tr>
                <th>Card Name</th>
                <th>Denomination Rate</th>
                <th>Purchase Rate</th>
                <?php if ($mediator_id) { ?>
                <th>Selling Rate</th>
                <?php } ?>
                <!--<th>Card Status</th>-->
            </tr>
        </thead>

        <tbody>
           <?php  if(!empty($get_cards)) { ?>
            <?php foreach ($get_cards as $data) {?>
            <tr>
               <td><?php echo ucwords($data['Card']['c_title']);?></td>
               <td><?php echo ucwords($data['Card']['c_denomination_rate']); ?></td>
                <td><?php echo $data['Card']['c_buying_price'];?></td>
                <?php
				        if(isset($data['CardsPrice']))
						{ 
						    $flag = 0;
							if( !empty($data['CardsPrice']))
							{
								// Viewing Own Set Details
								foreach($data['CardsPrice'] as $price)
								{
									if($price['cp_u_role'] == 2 && $price['cp_u_id'] == $mediator_id )
									{
										$data['Card']['c_selling_price'] =$price['cp_buying_price'];
									    $flag= 1; 
									}
									
								}
							}
						}
				?>
                <?php if ($mediator_id) { ?>
                   <td>
					<?php echo $this->Form->input('totalamount_'.$data['Card']['c_id'],array('class'=>'form-control amount_validation',
										'required' =>true,
										'type'=>'text',
										 'id'=>$data['Card']['c_denomination_rate'],
										'value'=>$data['Card']['c_selling_price'],
										'style'=>'width:100px',
										'label'=>false)); ?>
					<?php echo $this->Form->input('purchase_'.$data['Card']['c_id'],array('class'=>'form-control',
						'required' =>true,
						'type'=>'hidden',
						'value'=>$data['Card']['c_buying_price'],
						'label'=>false)); ?>
				  </td>
                 <?php } ?>
            </tr>
           <?php } ?>
           <tr>
                <td  colspan="4">
                <button class="btn btn-primary"  style="margin-top:0px !important;" type="button" onclick="return check_submit();">
                <span class="icon-white"></span>&nbsp;Update</button>
  	            <button class="btn btn-warning cancel"  style="margin-top:0px !important;" type="button" ><span class="icon-white"></span>
  	            &nbsp;Cancel</button>
                </td>
            </tr>
        <?php } else { ?>
        <tr>
           <td colspan="4" align="center"><?php echo __('No record found');?></td>
        </tr>
        <?php } ?>
        
        </tbody>
    </table>
   <?php echo $this->form->end(); ?>
    <?php } ?>
  </div>
  <div class="clear10"></div>
  </div>
</div>
<script type="text/javascript">

$('.back').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'dashboard','admin'=>'true'));?>";
    //window.location.href = url;
			history.go(-1);

});

$('#CardCatsCatId').change(function(){
   	     	//$('#card_from').submit();
	    	var mediator_id = $('#mediator_id').val();
			var card_category = $(this).val();
			var sub_cat_id = 0;
			var char = 'All';
			var card_rate = $('#CardCatsCardRate').val();

			if(!sub_cat_id)
			sub_cat_id = 0;
			
			if(!mediator_id)
			mediator_id = 0;
			
			if(!card_category)
			card_category = 0;
			
			if(!char)
			char = 'All';
			
			if(!card_rate)
			card_rate = 0;
			
			var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price'));?>/"+char+"/"+mediator_id+"/"+card_category+"/"+sub_cat_id+"/"+card_rate;
			window.location.href = url;
	});

$('#CardCatsCardRate').change(function(){
   	     	//$('#card_from').submit();
	    	var mediator_id = $('#mediator_id').val();
			var card_category = $('#CardCatsCatId').val();
			var sub_cat_id = $('#CardCatsSubCatId').val();;
			var card_rate = $(this).val();;
			var char = 'All';

			if(!sub_cat_id)
			sub_cat_id = 0;
			
			if(!mediator_id)
			mediator_id = 0;
			
			if(!card_category)
			card_category = 0;
			
			if(!card_rate)
			card_rate = 0;		
			
			if(!char)
			char = 'All';	
			
			var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price'));?>/"+char+"/"+mediator_id+"/"+card_category+"/"+sub_cat_id+"/"+card_rate;
			window.location.href = url;
	});


$('#CardCatsCId').change(function(){
   	     	//$('#card_from').submit();
	    	var mediator_id = $('#mediator_id').val();
			var card_category = $('#CardCatsCatId').val();
			var sub_cat_id = $('#CardCatsSubCatId').val();;
			var card_rate = $('#CardCatsCardRate').val();;
			var card_id = $(this).val();;
			var char = 'All';
			
			if(!sub_cat_id)
			sub_cat_id = 0;
			
			if(!mediator_id)
			mediator_id = 0;
			
			if(!card_category)
			card_category = 0;
			
			if(!card_rate)
			card_rate = 0;		
			
			if(!char)
			char = 'All';	
			
			if(!card_id)
			card_id = 0;
			
			var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price'));?>/"+char+"/"+mediator_id+"/"+card_category+"/"+sub_cat_id+"/"+card_rate+"/"+card_id;
			window.location.href = url;
	});

$('#CardCatsSubCatId').change(function(){
   	     	//$('#card_from').submit();
	    	var mediator_id = $('#mediator_id').val();
			var card_category = $('#CardCatsCatId').val();
			var sub_cat_id = $(this).val();
			var char = 'All';
			var card_rate = $('#CardCatsCardRate').val();

			if(!sub_cat_id)
			sub_cat_id = 0;
			
			if(!mediator_id)
			mediator_id = 0;
			
			if(!card_category)
			card_category = 0;
			
			if(!char)
			char = 'All';

			if(!card_rate)
			card_rate = 0;
			
			var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price'));?>/"+char+"/"+mediator_id+"/"+card_category+"/"+sub_cat_id+"/"+card_rate;
			window.location.href = url;
	});


function check_submit()
{
	var ans = confirm("<?php echo __('Are you sure you want to update the selling prices ?');?>");	
	if(ans)
	{
		flag = 0;
		var empty_selling = 0;
		$('.amount_validation').each(function(){
			
			price = $(this).val();
			var price = parseFloat(price).toFixed(2);
			var denomination_rate = $(this).attr('id');
			if(price == '' || price <= 0)
			{
				if(price == '')
				empty_selling = 1;
					
				flag = 1; 
				$(this).css('border','1px solid #F00');
			}
			else if(price > parseFloat(denomination_rate))
            {
                flag = 1; 
                $(this).css('border','1px solid #F00');
            }
		});
		if(flag == 1)
		{
		   if(empty_selling == 1)
	       alert('<?php echo __("Enter selling price.")?>');
	       else  
	       alert('<?php echo __("Selling price should be greater than zero and not greater than denomination rate.")?>');  	
		  return false;	
		}
		
		$('#card').submit();
	}
	else
	{
		location.reload(true);
	}
}

$('.cancel').click(function(){
   var url = "<?php echo $this->Html->url(array('controller'=>'Users','action'=>'dashboard','admin'=>'true'));?>";
   window.location.href = url;
});

function change_type(card_category,char)
{
    var mediator_id = $('#mediator_id').val();
	if(card_category == 0)
	{
		char = 'All';
		var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price','admin'=>'true'));?>/"+card_category+"/"+char+"/"+mediator_id;
	}
	else
	{
		if(char == '' )
		char = 'All';
		var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price','admin'=>'true'));?>/"+card_category+"/"+char+"/"+mediator_id;
	}
	window.location.href = url;
}

function submitChar(char){

  var mediator_id = $('#mediator_id').val();
	var card_category = $('#CardCatsCatId').val();
	var sub_cat_id = $('#CardCatsSubCatId').val();
	var card_rate = $('#CardCatsCardRate').val();
	
	var card_category = 0;
	var sub_cat_id = 0;
	var card_rate = 0;

	if(!sub_cat_id)
	sub_cat_id = 0;
	
	if(!mediator_id)
	mediator_id = 0;
	
	if(!card_category)
	card_category = 0;
	
	if(!char)
	char = 'All';
	
	if(!card_rate)
	card_rate = 0;
    
	var isChecked = $('#over_riddend_cards').prop('checked');
	if (isChecked) 
	{
	overridden =1;
	}
	else
	{
	overridden =0;
	} 
    card_id = 0;
	var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price'));?>/"+char+"/"+mediator_id+"/"+card_category+"/"+sub_cat_id+"/"+card_rate+"/"+card_id+"/"+overridden;
	window.location.href = url;
}

$('#mediator_id').change(function(){
	var mediator_id = $(this).val();
	var card_category = '<?php echo $cat_id?>';
	var sub_cat_id = '<?php echo $sub_cat_id?>';
	var char = 'All';
	var card_rate = '<?php echo $rate?>';
	var card_id = $('#CardCatsCId').val();;

	if(!card_id)
	card_id = 0;

	if(!sub_cat_id)
	sub_cat_id = 0;
	
	if(!mediator_id)
	mediator_id = 0;
	
	if(!card_category)
	card_category = 0;
	
	if(!char)
	char = 'All';
	
	if(!card_rate)
	card_rate = 0;
	
   var isChecked = $('#over_riddend_cards').prop('checked');
   if (isChecked) 
   {
    overridden =1;
   }
   else
   {
   	overridden =0;
   }

	var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price'));?>/"+char+"/"+mediator_id+"/"+card_category+"/"+sub_cat_id+"/"+card_rate+"/"+card_id+"/"+overridden;
	window.location.href = url;
});
$(document).ready(function(){
   $('#Mediator').addClass('sb_active_opt');
   $('#Mediator').removeClass('has_submenu');
   $('#manage_active').addClass('sb_active_subopt_active');
   var over_riddend_cards = "<?php echo $overridden; ?>";
   if(over_riddend_cards == 1)
   $('#over_riddend_cards').prop('checked','checked');
   else
   $('#over_riddend_cards').prop('checked',false);
   
}) ;

$('#over_riddend_cards').click(function(){
   
    var mediator_id = $('#mediator_id').val();
	var card_category = '<?php echo $cat_id?>';
	var sub_cat_id = '<?php echo $sub_cat_id?>';
	var char = '<?php echo $selchar?>';
	var card_rate = '<?php echo $rate?>';
	var card_id = $('#CardCatsCId').val();
	
	if(!card_id)
	card_id = 0;

	if(!sub_cat_id)
	sub_cat_id = 0;
	
	if(!mediator_id)
	mediator_id = 0;
	
	if(!card_category)
	card_category = 0;
	
	if(!char)
	char = 'All';
	
	if(!card_rate)
	card_rate = 0;
   
   var isChecked = $('#over_riddend_cards').prop('checked');
   if (isChecked) 
   {
    overridden =1;
   }
   else
   {
   	overridden =0;
   }
	var url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price'));?>/"+char+"/"+mediator_id+"/"+card_category+"/"+sub_cat_id+"/"+card_rate+"/"+card_id+"/"+overridden;
	window.location.href = url;
});

$('.clear_filer_class').click(function(){
 		var new_url = "<?php echo $this->Html->url(array('controller'=>'Cards','action'=>'manage_price','admin'=>'true'));?>/0/0/0/0/0/0";
        window.location.href = new_url;
});

</script>