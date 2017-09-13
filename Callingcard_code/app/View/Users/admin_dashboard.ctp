<style type="text/css">

.main_subdiv{

min-height: 400px;

}
.grid_table_box .row .col-md-12{ padding-top: 10px; }

.dashborad_img{font-size: 120px !important;
color: #D0D0D0 !important;
}
#admin-info-panel{ padding: 0px 15px;}
#admin-info-panel .col-xs-12{ padding: 15px 0px; }
#admin-info-panel .col-xs-12 .row{ margin: 0px; margin-bottom: 20px; }
#admin-info-panel .col-xs-12 .row .col-xs-3{ border: 1px solid #ccc; border: 1px solid #ccc; margin-right: 6px; width: 24%; text-align: left; padding: 0px;}
#admin-info-panel .col-xs-12 .row .col-xs-3:first-child{ margin-left: 10px;}
.sb-block-title{ background-color: #e6e6e6; font-weight: bold; padding: 6px 6px;}
.sb-block-info{ padding: 6px 6px; }
</style>
<div align="left" style="padding-right:10px;">
    <div class="page_title"><?php echo $title_for_layout; ?></div>
<!--  <button class="btn btn-danger" id="delete_button" type="button"><span class="icon-remove icon-white"></span>&nbsp;Delete</button>
-->
  <div class="sub_title"><i class="icon-home home_icon"></i> <span class="sub_litle_m">Dashboard</span> <i class="icon-angle-right home_icon"></i> <span></span></div>
  <div class="main_subdiv">
    <div class="gird_button">
        <div class="main_sub_title mediator_w"><?php echo $title_for_layout; ?></div>
    </div>    
  
  <div class="clear10"></div>
  <div align="left" class="grid_table_box">
    <table id="list"></table> 
		<div id="pager"></div>
		<div class="row">
		
			<div class="col-md-12" align="center">
			
				<?php //echo $this->Html->image('setting.png',array('class'=> 'dashborad_img'));?>
				
				<!-- <i class="icon-cogs dashborad_img"></i> -->
        <?php 
            $cuser = $this->Session->read("Auth.User");
            if($cuser['role_id'] == 1){
              $active = 0;
              $inactive = 0;
        ?>
        <div id="admin-info-panel" class="row">
              <div class="col-xs-12">
                  <div class="row">
                      <div class="col-xs-3">
                          <div class="sb-block-title">
                              <span>Categories</span>
                          </div>
                          <?php  
                              $active = $category_active;
                              $inactive = $category_inactive;
                              $total_category = $active + $inactive;  
                          ?>
                          <div class="sb-block-info">
                              Active : <?php echo $active;?>
                          </div>
                          <div class="sb-block-info">
                              Inactive : <?php echo $inactive;?>
                          </div>
                          <div class="sb-block-info">
                              Total Categories : <?php echo $total_category;?>
                          </div>
                      </div>
                      <div class="col-xs-3">
                          <div class="sb-block-title">
                              <span>Sub - Categories</span>
                          </div>
                          <?php  
                              $active = $subcategory_active;
                              $inactive = $subcategory_inactive;
                              $total_subcategory = $active + $inactive;  
                          ?>
                          <div class="sb-block-info">
                              Active : <?php echo $active;?>
                          </div>
                          <div class="sb-block-info">
                              Inactive : <?php echo $inactive;?>
                          </div>
                          <div class="sb-block-info">
                              Total Sub Categories : <?php echo $total_subcategory;?>
                          </div>
                      </div>
                      <div class="col-xs-3">
                          <div class="sb-block-title">
                              <span>Cards</span>
                          </div>
                          <?php 
                              $active = $card_active;
                              $inactive = $card_inactive; 
                              $total_cards = $active + $inactive;
                          ?>
                          <div class="sb-block-info">
                              Active : <?php echo $active;?>
                          </div>
                          <div class="sb-block-info">
                              Inactive : <?php echo $inactive;?>
                          </div>
                          <div class="sb-block-info">
                              Total Products : <?php echo $total_cards;?>
                          </div>
                      </div>
                      <div class="col-xs-3">
                          <div class="sb-block-title">
                              <span>Mediators</span>
                          </div>
                          <?php  
                              $active = $mediators_active;
                              $inactive = $mediators_inactive;
                              $total_mediators = $active + $inactive;
                          ?>
                          <div class="sb-block-info">
                              Active : <?php echo $active;?>
                          </div>
                          <div class="sb-block-info">
                              Inactive : <?php echo $inactive;?>
                          </div>
                          <div class="sb-block-info">
                              Total Mediators : <?php echo $total_mediators; ?>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-xs-3">
                          <div class="sb-block-title">
                              <span>Retailers</span>
                          </div>
                          <?php 
                              $active = $retailers_active;
                              $inactive = $retailers_inactive; 
                              $total_retailers = $active + $inactive;
                          ?>
                         <div class="sb-block-info">
                              Active : <?php echo $active;?>
                          </div>
                          <div class="sb-block-info">
                              Inactive : <?php echo $inactive;?>
                          </div>
                          <div class="sb-block-info">
                              Total Retailers : <?php echo $total_retailers;?>
                          </div>
                      </div>
                      <div class="col-xs-3">
                          <div class="sb-block-title">
                              <span>Pins</span>
                          </div>
                          <?php 
                              $active = $retailers_active;
                              $inactive = $retailers_inactive; 
                              $total_retailers = $active + $inactive;
                          ?>
                         <div class="sb-block-info">
                              New Pins : <?php echo isset($pins_not_used) ? $pins_not_used : 0;?>
                          </div>
                          <div class="sb-block-info">
                              Sold Pins : <?php echo isset($pins_sold) ? $pins_sold : 0;?>
                          </div>
                          <div class="sb-block-info">
                              Parked Pins : <?php echo isset($pins_park) ? $pins_park : 0;?>
                          </div>
                          <div class="sb-block-info">
                              Reject Pins : <?php echo isset($pins_reject) ? $pins_reject : 0;?>
                          </div>
                          <div class="sb-block-info">
                              Return Pins : <?php echo isset($pins_return) ? $pins_return : 0;?>
                          </div>
                          <div class="sb-block-info">
                              Total Pins : <?php echo ($pins_not_used + $pins_sold + $pins_park + $pins_reject + $pins_return);?>
                          </div>
                      </div>
                  </div>
              </div>
        </div>
			 <?php  }?>
			</div>
		
		</div>
  </div>
  <div class="clear10"></div>
  </div>
</div>

<script type="text/javascript">
function logout_admin()
{
	var url = "<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'logout','admin'=>true))?>";
  window.location.href = url;
  
  
}
$(document).ready(function(){
   $('#deshboard').addClass('sb_active_single_opt');
 }) ;
</script>