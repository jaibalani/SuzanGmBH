<style type="text/css">
  
.dashboard_toptitle_fancy_box {
    background: linear-gradient(to bottom, #535559 0%, #252528 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);
    color: #ffffff;
    float: left;
    font-size: 10px;
    font-weight: 600;
    margin: 0 !important;
    padding: 10px 10px;
    width: 100%;
    height: auto;
    padding-right: 25px;
}
.buy-card-info{
  margin: 15px 10px 0 !important;
  height: 375px !important;
}
.fancybox-image, .fancybox-iframe {
    display: block;
    width: 100%;
}

.dashboard_inner_fancy_box{
  color: #333333;
  float: left;
}
.main_div{
  width: 100% !important;
  float: left;
  overflow: hidden;
  overflow-y:scroll;
}
.cards-number{
  font-size: 9px;
}
.buy-other-details{
  font-size: 8px;
}
</style>

<div class="dashboard_inner_fancy_box" >

  <div class="dashboard_toptitle_fancy_box">
    <?php 
        echo __($title_for_layout);
    ?>
  </div> 

<div class= "main_div">
  <div class="buy-card-info">
      
      <div class="buy-card-img" style="text-align:center;">
          <?php 
              echo $this->Html->image($set_card_data['card_image'],array('alt'=>'Card Icon','width'=>'80px;')); 
          ?>
            <br/>
            <span >
                <strong>
                <?php 
                  echo $set_card_data['name'];
                ?>
              </strong>
            </span>
            <div class="buy-card-rate">
            <?php 
              echo "&euro;".$set_card_data['price'];
            ?>
            </div>
      </div>

      <div class="cards-number">
            <?php 
              if($set_card_data['cell_number'])
              echo "<span>".__('Cell Number').":".$set_card_data['cell_number']."</span><br/>";
              else
              echo "<span>".__('Cell Number').": N/A"."</span><br/>";             

            ?>
            <?php 
              if($set_card_data['local_number'])
              {
                 $counter = 0;
                 foreach($set_card_data['local_number'] as $key => $local)
                 {
                   echo "<span>".__('Local Number')." ".++$counter.":".$local."</span><br/>"; 
                 }
              }
              else
              echo __('Local Number').": N/A";              
            ?>
      </div>

      <div class="pin-number">
        <?php echo __('Pin Number');?>
        <span>
          <?php echo $set_card_data['pin'];?>
        </span>
      </div>
      
      <div class="buy-other-details">
          <?php 
            echo __('Description Text').":";
            if(strlen($set_card_data['free_text']) >105)
            {
              $set_card_data['free_text'] = substr($set_card_data['free_text'], 0,105)."...";
            }
            if($set_card_data['free_text'])
            {
              echo $set_card_data['free_text'];
            }
            else
            {
              echo "N/A";    
            }
          ?>
      </div>
      
      <div class="buy-other-details">
          <span style ="font-size:9px;"> <?php echo __('Serial Number').":";?><?php echo $set_card_data['serial'];?></span><br/>
          <span><?php echo __('Retailer').":";?><?php echo $set_card_data['retailer'];?>
          </span><br/>
          <span><?php echo $set_card_data['url'];?></span><br/>
      </div>
      
      <div class="buy-other-details">
          <?php 
            echo $set_card_data['date_time'];
          ?>
      </div>

    </div>
  </div>
</div>
    