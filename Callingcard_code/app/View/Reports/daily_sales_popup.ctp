<style type = "text/css">
.net_total{
  text-align: right;
  font-weight: 600;
}
.dashboard_toptitle_fancy_box {
    background: linear-gradient(to bottom, #535559 0%, #252528 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);
    color: #ffffff;
    float: left;
    font-size: 16px;
    font-weight: 600;
    margin: 0 !important;
    padding: 8px 8px;
    width: 100%;
    height: 40px;
}
.dashboard_inner_fancy_box{
  color: #333333;
  height: 100%;
  float: left;
}
.main_div{
  width: 100% !important;
  float: left;
  height: 80%;
  overflow-x: hidden;
  overflow-y:scroll;
}
.fancybox-inner{
  height: 400px !important;
}
</style>

<div class="dashboard_inner_fancy_box">

  <div class="dashboard_toptitle_fancy_box"  >
    <?php echo __($title_for_layout)?>
  </div> 

  <div class= "main_div">
    <table class="table table-bordered" id="daily_filter">
            <thead>
              <tr>
                <th><?php echo __('S. No.')?></th>  
                <th><?php echo __('Card')?></th>
                <th><?php echo __('Quantity')?></th>
                <th><?php echo __('Purchase Price')?>(&euro;)</th>
                <th><?php echo __('Selling Price')?>(&euro;)</th>
                <th><?php echo __('Total Purchase')?>(&euro;)</th>
                <th><?php echo __('Total Selling')?>(&euro;)</th>
                <th><?php echo __('Profit')?>(&euro;)</th>
                <th><?php echo __('Time')?></th>
              </tr>
            </thead>
            <tbody>
             <?php 
                 $counter = 1;
                 if(isset($sales_ordered_data) && !empty($sales_ordered_data))
                 {
                  foreach ($sales_ordered_data as $data) 
                  {

              ?>    
                <tr>
                      <td><?php echo $counter ; $counter++; ?></td>
                      <td><?php echo $data['card_name'] ;?></td>
                      <td><?php echo $data['quantity'] ;?></td>
                      <td><?php echo $data['buying_price'] ;?></td>
                      <td><?php echo $data['selling_price'] ;?></td>
                      <td><?php echo $data['total_purchase'] ;?></td>
                      <td><?php echo $data['total_sales'] ;?></td>
                      <td><?php echo $data['profit'] ;?></td>
                      <td><?php echo $data['time'] ;?></td>        
                   
                </tr>
              <?php } } ?>
              <tr>
                      <td colspan="2" class="net_total"><?php echo __('Net Quantity');?></td>
                      <td ><?php echo $total_cards;?></td>
                      <td></td>
                      <td></td>
                      <td><?php echo $total_purchase ;?></td>
                      <td><?php echo $total_sales ;?></td>
                      <td><?php echo $total_sales - $total_purchase;?></td>
                      <td></td>        
                   
                </tr>

           </tbody>
    </table>
  </div>
</div>

              