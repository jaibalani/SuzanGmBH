<style>
ul {
	padding:0px;
	margin: 0px;
}
#list2 li {
	margin: 0 0 3px;
	padding:8px;
	color:#000000;
	width:740px;
	list-style: none;
	font-weight:bold;
	background-color:#D0D2D0;
  cursor:move;
  background-image: linear-gradient(to bottom, #E5E6E5, #D0D2D0);
  border-radius: 3px 3px 3px 3px;
  color: #000000;
  font-family: arial,helvetica,sans-serif;
}
.navigation a{ margin-left: 0px; }

.cancel{  
margin-left: 30px;
}



.ui-sortable{ margin-left: 30px; }

.grid_table_box .row .col-md-1 div.checkbox { margin-left: -50px; }

</style>
<script type="text/javascript">
$(document).ready(function(){ 	
	$(function() {
	$("#list2 ul").sortable({ opacity: 0.8, cursor: 'move', update: function() {
			
			var order = $(this).sortable("serialize"); 
			
			$.post("<?php echo $this->Html->url(array("controller" => "Languages","action" => "updateorder")); ?>", order, function(theResponse){
			}); 															 
		}								  
		});
	});

});	
</script>

<div class="row">
  <div class="col-md-12">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
    <div class="sub_title"><i class="icon-gear"></i> <span class="sub_litle_m">Settings</span> <i class="icon-angle-right home_icon"></i> <span>Language Manage</span> <i class="icon-angle-right home_icon"></i> <span>Manage Order</span></div>
  </div>
</div>
<div class="clear10"></div>
<div class="main_subdiv">

		<div class="gird_button">
		        <div class="main_sub_title"><?php echo $title_for_layout; ?></div>
		</div>    
		  
		<div class="clear10"></div>
		<div align="left" class="grid_table_box">
		<div class="row" id="list2">
		 <div class="col-md-7">
		      <ul>
		        <?php
		           foreach($language_list as $lan){ ?>
		            <li id="arrayorder_<?php echo $lan['Language']['id'] ?>"><?php echo $lan['Language']['title'];?>
		             <div style="height:5px;"></div>
		            </li>
		     <?php } ?>
		     </ul>
		  </div> 
		</div>	
		
		<div class="row" style="margin-top:10px;">
		    <div class="col-md-7">
		   	 	 <button class="btn btn-warning back cancel" type="button" style="cursor:pointer;"><span class="icon-backward icon-white"></span>&nbsp;Back</button>
		    </div>
		</div>
		
	</div>
	
</div>	 

<script type="text/javascript">
$('.back').click(function(){
	var url = "<?php echo $this->Html->url(array('controller'=>'Languages','action'=>'index','admin'=>'true'));?>";
	//window.location.href = url;
    history.go(-1);
});
$(document).ready(function(){
   $('#setting').addClass('sb_active_opt');
   $('#setting').removeClass('has_submenu');
   $('#lag_active').addClass('sb_active_subopt_active');
}) ;
</script>
