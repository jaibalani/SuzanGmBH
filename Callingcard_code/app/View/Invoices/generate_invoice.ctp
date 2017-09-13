<html>
  <head>
  </head>
  <body>
      <div id="main" align="center">
        <div id="page-title" style="border: 1px solid #818181; height: 20px; width: 1000px; font-weight: bold; padding: 6px 0px; background-color: #dcdcdc; margin-top: 30px; font-size: 20px;">
            INVOICE
        </div>
        <table cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <!--blank1-->
                </td>
                <td>
                    <!--blank2-->
                </td>
                <td>
                    <!--blank3-->
                </td>
            </tr>
            <tr>
                <td>
                    <!--blank1-->
                </td>
                <td>
                    <!--blank2-->
                </td>
                <td>
                    <table cellspacing="0" cellpadding="0" style="margin-top:20px; font-size:10px; text-align:left; padding-left:10px; border:1px solid #000;">
                        <tr>
                            <td style="padding-left:10px; font-size:14px;"><strong>&nbsp;<?php echo $setting_data['distributor_name'];?></strong></td>
                        </tr>
                        <tr>
                            <td style="padding-left:10px;">&nbsp;<?php echo __('Address')." : ".$setting_data['address'];?></td>
                        </tr>
                        <tr>
                            <td style="padding-left:10px;">&nbsp;<?php echo __('Tel no / Fax no / Email')." : ".$setting_data['phone']." / ".$setting_data['fax']." / ".$setting_data['email'];;?></td>
                        </tr>
                        <tr>
                            <td style="padding-left:10px;">&nbsp;<?php echo __('TAX ID')." - ".$setting_data['tax_id'];?></td>
                        </tr>
                        <tr>
                            <td style="padding-left:10px;">&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="spacer-10"></div>
        <table cellspacing="0" cellpadding="0" style="font-size:9px;">
            <tr>
                <td align="left">
                    <table cellspacing="0" cellpadding="0">
                        <tr>
                          <td style="font-size:9px;"><I>An Customer</I></td>
                        </tr>
                        <tr>
                          <td style="font-size:9px;"><strong><?php echo ucwords($get_user_data['User']['fname']." ".$get_user_data['User']['lname']);?></strong></td>
                        </tr>
                        <tr>
                          <td style="font-size:9px;"><strong><?php echo ucwords($get_user_data['User']['address']);?></strong></td>
                        </tr>
                        <tr>
                          <td style="font-size:9px;"><strong></strong></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <!--blank2-->
                </td>
                <td>
                    <table cellspacing="0" cellpadding="0" style="font-size:9px; text-align:left;">
                        <tr>
                          <td><?php echo __('Invoice Number')." : ";?></td>
                          <td><?php echo $get_invoice_data[0]['Invoice']['invoice_number']?></td>
                        </tr>
                        <tr>
                          <td><?php echo __('Date')." : ";?></td>
                          <td><?php echo date('d-F-Y',strtotime($get_invoice_data[0]['Invoice']['invoice_date_month']));?></td>
                        </tr>
                        <tr>
                          <td><?php echo __('Retailer Code')." : ";?></td>
                          <td>2087</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td align="right">
                  <I>( Euro )</I>
              </td>
            </tr>
        </table>
        <div class="spacer-10"></div>
        <table id="items-detail" cellspacing="0" cellpadding="0" style="width: 1030px; font-size:9px; width:1030px; text-align:center;">
            <thead>
              <tr>
                <th style="border-bottom: 1px solid #000; width:100px; font-weight:bold;"> <?php echo __('Product code');?></th>
                <th style="border-bottom: 1px solid #000; width:130px; font-weight:bold;"> <?php echo __('Product Name');?></th>
                <th style="border-bottom: 1px solid #000; width:100px; font-weight:bold;"> <?php echo __('Product Quantity');?></th>
                <th style="border-bottom: 1px solid #000; width:100px; font-weight:bold;"> <?php echo __('Gross Price');?></th>
                <th style="border-bottom: 1px solid #000; width:100px; font-weight:bold;"> <?php echo __('Discount %');?></th>
                <th style="border-bottom: 1px solid #000; width:60px; font-weight:bold;"> <?php echo __('Discount val.');?></th>
                <th style="border-bottom: 1px solid #000; width:60px; font-weight:bold;"> <?php echo __('Per Pc. Price');?></th>
                <th style="border-bottom: 1px solid #000; width:60px; font-weight:bold;"> <?php echo __('Net Value');?></th>
              </tr>
            </thead>
            <tbody>
              <?php  
                $counter = 1; 
                $cards_count = 0; 
                $purchase_count = 0 ;
                $sale_count =0 ;
                $sale_discount =0 ;
                $tax_total = 0;

                foreach($get_invoice_data as $data ) 
                { 
   ?>
              <tr>
                <td style="text-align: center; padding-top: 7px; width:100px;"><?php echo $data['Invoice']['card_id'];?></td>
                <td style="text-align: center; padding-top: 7px; width:130px;"><?php echo $data['Card']['c_title'];?></td>
                
                <td style="text-align: center; padding-top: 7px; width:100px;">
                <?php 
                    echo $data['Invoice']['total_cards']; 
                    $cards_count = $cards_count + $data['Invoice']['total_cards'];
                ?>
                </td>

                <td style="text-align: center; padding-top: 7px; width:100px;">
                <?php 
                    echo $data['Invoice']['total_sales']; 
                    $sale_count = $sale_count + $data['Invoice']['total_sales'];
                ?>
                </td>

                <td style="text-align: center; padding-top: 7px; width:100px;">
                <?php 
                    $buying_price = $data['Invoice']['buying_price'];
                    $selling_price = $data['Invoice']['selling_price'];  
                    $discount = $selling_price -$buying_price;
                    $sale_discount= $sale_discount + $discount;
                    $discount_persentage = ($discount*100)/$selling_price;
                    $discount_persentage =number_format($discount_persentage, 2, '.', '');
                    echo $discount_persentage; 
                ?>
                </td>

                <td style="text-align: center; padding-top: 7px; width:60px;"><?php echo $discount;?></td>
                <td style="text-align: center; padding-top: 7px; width:60px;"><?php echo $buying_price;?></td>
                <td style="text-align: center; padding-top: 7px; width:60px;"><?php 
                        echo $data['Invoice']['total_purchase'];
                        $purchase_count = $purchase_count + $data['Invoice']['total_purchase'];
                      ?>
                </td>
              </tr>
            <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td style="text-align: center; padding-top: 7px; font-weight: bold; border-top: 1px solid #000; font-size:11px;">
                  <?php echo __('Total Quantity :');?>
                </td>
                <td style="text-align: center; padding-top: 7px; font-weight: bold; border-top: 1px solid #000; font-size:11px;">
                  <span style="text-decoration: underline; margin-right: 12px;"><?php echo $cards_count;?></span> Pcs.
                </td>
                <td style="text-align: center; padding-top: 7px; font-weight: bold; border-top: 1px solid #000;" colspan="6"></td>
              </tr>
            </tfoot>
          </table>
          <table cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <!--blank1-->
                </td>
                <td>
                    <!--blank2-->
                </td>
                <td>
                    <!--blank3-->
                </td>
            </tr>
            <tr>
                <td>
                    <!--blank1-->
                </td>
                <td>
                    <!--blank2-->
                </td>
                <td>
                   <table cellspacing="0" cellpadding="0" style="margin-top:20px; font-size:10px; text-align:left; padding-left:10px; border:1px solid #000;">
                      <tr>
                        <td>&nbsp;&nbsp;Netto Teil (Aus MwsT):</td>
                        <td align="right" style="width:100px;">
                            <?php
                             $purchase_count =number_format($purchase_count, 2, '.', '');
                             $sale_count =number_format($sale_count, 2, '.', '');
                             $sale_discount =number_format($sale_discount, 2, '.', ''); 

                             $per_81 = $purchase_count *.81;
                             $per_19 = $purchase_count *.19;
                             $per_81 =number_format($per_81, 2, '.', '');
                             $per_19 =number_format($per_19, 2, '.', '');
                             echo $per_81;
                            ?>
                        <span>&nbsp;&nbsp;EU&nbsp;&nbsp;</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;&nbsp;zzgl. 19%MwsT :</td>
                        <td align="right" style="border-bottom:1px solid #000; width:100px;"><?php echo $per_19;?><span>&nbsp;&nbsp;EU&nbsp;&nbsp;</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;&nbsp;Gesamt Netto</td>
                        <td align="right" style="width:100px;"><?php echo $purchase_count;?><span>&nbsp;&nbsp;EU&nbsp;&nbsp;</span></td>
                      </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="spacer-10"></div>
        <table cellspacing="0" cellpadding="0" style="font-size:9px;">
            <tr>
                <td align="left">
                    <table cellspacing="0" cellpadding="0">
                        <tr>
                          <td style="font-size:9px;">Brutto Kartenwert</td>
                        </tr>
                        <tr>
                          <td style="font-size:9px;">Rabatt</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <!--blank2-->
                </td>
                <td align="right">
                    <table cellspacing="0" cellpadding="0" style="font-size:9px;">
                        <tr>
                          <td><?php echo $sale_count;?><span>&nbsp;&nbsp;EU&nbsp;&nbsp;</span></td>
                        </tr>
                        <tr>
                          <td><?php echo $sale_discount;?><span>&nbsp;&nbsp;EU&nbsp;&nbsp;</span></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="spacer-10"></div>
        <table cellspacing="0" cellpadding="0" style="font-size:12px; font-weight:bold;">
            <tr>
                <td align="left">
                  Rechnungsbetrag / Zu zahlender betrag:
                </td>
                <td>
                    <!--blank2-->
                </td>
                <td align="right">
                  <?php echo $purchase_count;?><span>&nbsp;&nbsp;EU&nbsp;&nbsp;</span>      
                </td>
            </tr>
        </table>
        <div class="spacer-10"></div>
        <table cellspacing="0" cellpadding="0" style="font-size:9px;">
            <tr>
                <td align="left">
                  <?php echo $setting_data['text_field1'];?>
                </td>
            </tr>
        </table>

          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
          <br/>
        <table cellspacing="0" cellpadding="0" style="font-size:9px;">
            <tr>
                <td align="left">
                 <?php echo $setting_data['text_field2'];?>
                </td>
            </tr>
        </table>
        <div class="spacer-10"></div>
        <table cellspacing="0" cellpadding="0" style="font-size:12px; font-weight:bold; border:1px solid #000;">
            <tr>
                <td align="left">
                  <?php echo __('Bank account Details')." : ".$setting_data['bank_details'];?> 
                </td>
            </tr>
        </table>
        <div class="spacer-10"></div> 
        <table cellspacing="0" cellpadding="0" style="font-size:9px;">
            <tr>
                <td align="left">
                  <?php echo $setting_data['text_field3'];?>
                </td>
            </tr>
        </table>
        <div class="spacer-10"></div> 
        <table cellspacing="0" cellpadding="0" style="font-size:9px;">
            <tr>
                <td align="left">
                  <?php echo $setting_data['text_field4'];?>
                </td>
            </tr>
        </table>

  </body>
</html>