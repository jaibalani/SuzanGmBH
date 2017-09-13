<table style="width:100%">
   <tr>
     <td align="center"><h3>Statement</h3></td>
   </tr>
</table>

<table style="width:100%" cellpadding="10">
   <tr>
     <td align="center"></td>
   </tr>
</table>

<table style="width:100%;">
  <tr style="width:100%;">
   <td style="width:65%;" align="left"></td>
   <td style="width:35%" align="right">
    <table align="right" style="border: 2px solid #600;width:100%;float:right;">
        <tr>
         <td align="right"><?php echo ucwords($get_mediator_data['User']['fname']." ".$get_mediator_data['User']['lname']) ;?></td>
        </tr>
        <tr>
         <td align="right"><?php echo "Address :".ucwords($get_mediator_data['User']['address']) ;?></td>
        </tr>
        <tr>
         <td align="right"><?php echo "Tel  :".ucwords($get_mediator_data['User']['phone']) ;?></td>
        </tr>
    </table>
   </td>
  </tr>
</table>

<table style="width:100%" cellpadding="10">
   <tr>
     <td align="center"></td>
   </tr>
</table>


<table style="width:100%">
	
    <tr style="width:100%;">
      <td align="left" style="width:50%;">
        <!-- Retailer Info -->
        <table>
            <tr>
            <td><?php echo "To Company ";?></td>
            </tr>
            <tr>
            <td><?php echo ucwords($this->Session->read('Auth.User.fname')." ".$this->Session->read('Auth.User.lname'));?></td>
            </tr>
            <tr>
            <td><?php echo "Address :".ucwords($this->Session->read('Auth.User.fname'));;?></td>
            </tr>
        </table>
       </td>
        
        <td align="right" style="width:50%;">
        	<!-- Invoice Data -->
            <table>
                <tr>
                    <td>
                    <?php 
                    if(isset($get_invoice_data[0]['Invoice']['invoice_number']))				
                    echo __("Invoice Number :").$get_invoice_data[0]['Invoice']['invoice_number'];
                    else
                    echo __("Invoice Number : Invalid Invoice");
                    ?>
                    </td>
                </tr>
                <tr>
                    <td>
                    <?php 
                    if(isset($get_invoice_data[0]['Invoice']['invoice_date_month']))				
                    echo __("Date :").$get_invoice_data[0]['Invoice']['invoice_date_month'];
                    else
                    echo __("Date : Not Available");
                    ?>
                    </td>
                </tr>
                <tr>
                    <td>
                    <?php 
                    echo __("Customer Number :").$this->Session->read('Auth.User.id');
                    ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table style="width:100%" cellpadding="5">
   <tr>
     <td align="center"></td>
   </tr>
</table>

<table style="border-top:2px solid #600;border-bottom:2px solid #600;">
   <tr>
    <th style="width:8%;"><?php echo __('Item No.');?></th>
    <th style="width:14%;"><?php echo __('Title');?></th>
    <th style="width:10%;"><?php echo __('Buying');?></th>
    <th style="width:10%;"><?php echo __('Selling');?></th>
    <th style="width:10%;"><?php echo __('Quantity');?></th>
    <th style="width:14%;"><?php echo __('Gross Purchase');?></th>
    <th style="width:12%;"><?php echo __('Gross Sales');?></th>
    <th style="width:12%;"><?php echo __('Ust');?></th>
    <th style="width:10%;"><?php echo __('Net (EUR)');?></th>  
   </tr>
   <?php  
         $counter = 1; $cards_count = 0; $purchase_count = 0 ;$sale_count =0 ;$tax_total = 0;
         foreach($get_invoice_data as $data ) 
         { 
   ?>
   <tr>	
    <td><?php echo $counter;?></td>
    <td><?php echo ucwords($data['Card']['c_title']);?></td>
    <td><?php echo $data['Invoice']['buying_price'];?></td>
    <td><?php echo $data['Invoice']['selling_price'];?></td>
    <td><?php echo $data['Invoice']['total_cards']; $cards_count = $cards_count + $data['Invoice']['total_cards'];?></td>
    <td><?php echo $data['Invoice']['total_purchase']; $purchase_count = $purchase_count + $data['Invoice']['total_purchase'];?></td>
    <td><?php echo $data['Invoice']['total_sales']; $sale_count = $sale_count + $data['Invoice']['total_sales'];?></td>
    <td>
        <?php 
            $taxable_amount = 0.19 * $data['Invoice']['total_sales'];
            $tax_total =$tax_total + $taxable_amount;
            echo $taxable_amount;
            
        ?>
    </td>
    <td><?php echo $data['Invoice']['total_sales'] - $taxable_amount ;?></td>  
    </tr>
   <?php $counter++; } ?>   
</table> 

<table style="width:100%" cellpadding="5">
   <tr>
     <td align="center"></td>
   </tr>
</table>

<table style="width:100%;">
	<tr style="width:100%">
      <td style="width:40%;" align="left">
      <!-- Net Pay Info -->
        <table>
           <tr>
             <td><?php echo __("Total Cards:").$cards_count;?></td>
           </tr>
           <tr>
             <td></td>
           </tr>
           <tr>
           <td></td>
         </tr>
        </table>
      </td>
      
      <td style="width:60%;" align="right">
        <table>
            <tr>
            <td align="right"><?php echo __("Net Part (Off On Quantity): ").($sale_count - $tax_total)."  EU";?></td>
            </tr>
            <tr>
            <td align="right"><?php echo __("19% Sales Tax On Quantity: ").$tax_total."  EU";?></td>
            </tr>
            <tr>
            <td align="right"><?php echo __("Total net: ").$sale_count."  EU";?></td>
            </tr>
        </table>
        </td>
    </tr>
</table>

<table style="width:100%" cellpadding="5">
   <tr>
     <td align="center"></td>
   </tr>
</table>


 <!-- Net Pay Info -->
<table align="left" style="width:100%;">
   <tr>
     <td align="left"><?php echo "<b>".__("Invoice / Payble Amount :")."</b>"?></td>
     <td align="right"><?php echo "<b>".$sale_count." EU</b>";?></td>
   </tr>
</table>

<table style="width:100%" cellpadding="1">
   <tr>
     <td align="center"></td>
   </tr>
</table>


<table align="left" style="width:100%;">
   <tr>
     <td align="left">
      <?php 
          echo "<b>";
          echo __('Terms of payment:');
          echo "&nbsp;&nbsp;&nbsp;&nbsp;";
          echo __('This invoice is payable immediately and without departing');
          echo "</b>" 
      ;?>
     </td>
   </tr>
</table>

<table style="width:100%" cellpadding="1">
   <tr>
     <td align="center"></td>
   </tr>
</table>

<table align="left" style="width:100%; border:2px solid #600;">
   <tr>
     <td align="left"><?php echo "<b> Bank: Dresdner Bank,&nbsp;&nbsp;&nbsp;&nbsp; Account No. 0921022200 BLZ 20080000. </b>"?></td>
   </tr>
</table>

<table style="width:100%" cellpadding="1">
   <tr>
     <td align="center"></td>
   </tr>
</table>

<table align="left" style="width:100%;">
   <tr>
     <td align="left"><?php echo __("Upon delivery of the phone cards is not a taxable transaction under § 1 of the VAT Act.")?></td>
   </tr>
</table>

<table style="width:100%" cellpadding="1">
   <tr>
     <td align="center"></td>
   </tr>
</table>

<table align="left" style="width:100%;">
   <tr>
     <td align="left"><?php echo __("For phone card return 1% processing fee will be charged!.")?></td>
   </tr>
</table>
<?php echo ""; exit; ?>
