<html>
  <head>
    <title></title>
    <style type="text/css">
      body{ margin: 0px; padding: 0px; }
      #main{ width: 100%; font-size: 13px;}
      #page-title{ border: 1px solid #818181; height: 20px; width: 1000px; font-weight: bold; padding: 6px 0px; background-color: #dcdcdc; margin-top: 30px; font-size: 20px;}
      .spacer-10{ margin-top: 10px; }
      .invoice-block{ width: 1030px; display: inline-block;}
      #distributor-info{ border: 1px solid #000; width: 300px; margin-right: 15px; text-align: left; padding-left: 10px; padding-top: 4px; font-size: 13px; padding-bottom: 4px;}
      #distributor-info div:first-child{ font-size: 20px; }
      .right{ float: right; }
      .left{ float: left; }
      #customer-info{ text-align: left; line-height: 1.8; padding-left: 10px;}
      #customer-info span{ font-style: italic; text-align: left;}
      #customer-info div#retailer-name{ font-size: 14px; text-align: left;}
      #customer-info div{ font-weight: bold;  }
      #invoice-details{ margin-right: 95px; text-align: left; line-height: 1.6; }
      #invoice-details div:first-child span{ margin-left: 50px; }
      #invoice-details div:nth-child(2) span{ margin-left: 111px; }
      #invoice-details div:nth-child(3) span{ margin-left: 65px; }
      #invoice-details div:nth-child(5){ font-style: italic; text-align: right; margin-right: -62px;}
      #items-detail{ width: 1030px; }
      #items-detail tr td{ text-align: center; padding-top: 7px;  }
      #items-detail tr th{ border-bottom: 1px solid #000; }
      #items-detail tfoot tr td{ font-weight: bold; border-top: 1px solid #000;}
      #items-detail tfoot tr td span{ text-decoration: underline; margin-right: 12px; }
      #net-total{ width: 300px; border: 1px solid #000; }
      #net-total table{ width: 300px; padding: 10px 10px;  }
      #net-total table tr td{ line-height: 1.8; }
      #net-total table tr td:nth-child(2){ text-align: center; font-weight: bold;}
      #net-total table tr:nth-child(2) td:nth-child(2){ border-bottom: 1px solid #000; }
      .other-detail-left{ margin-top: 20px; }
      .other-detail-right{ margin-top: 20px; }
      .other-detail-right span{ margin-left: 31px; }
      .other-detail-mleft{ margin-top: 20px; font-weight: bold; font-size: 18px;}
      .other-detail-mright{ margin-top: 20px; font-weight: bold; font-size: 18px;}
      .other-detail-mright span{ margin-left: 20px; }
      .bank-details{ border: 1px solid #000; text-align: left; font-size: 16px; padding: 7px 4px; font-weight: bold;}
    </style>
  </head>
  <body>
      <div id="main" align="center">
        <div id="page-title">
            INVOICE
        </div>
        <div class="spacer-10"></div>
        <div class="invoice-block">
          <div id="distributor-info" class="right">
            <div><?php echo __('Distributor')." : ".$setting_data['distributor_name'];?></div>
            <div><?php echo __('Address')." : ".$setting_data['address'];?></div>
            <div><?php echo __('Tel no / Fax no / Email ')." : ".$setting_data['phone']." / ".$setting_data['fax']." / ".$setting_data['email'];?></div>
            <div><?php echo __('TAX ID')." : ".$setting_data['tax_id'];?></div>
          </div>
        </div>
        <div class="invoice-block">
          <div class="left"> 
            <div id="customer-info">
              <span><?php echo __('An Customer:');?></span><br/>
              <div id="retailer-name"><?php echo ucwords($get_user_data['User']['fname']." ".$get_user_data['User']['lname']);?></div>
              <div><?php echo ucwords($get_user_data['User']['address']);?></div>
              <div> </div>
            </div>
          </div>
          <div class="right"> 
            <div id="invoice-details">
              <div> <?php echo __('Invoice Number : ');?><span><?php echo $get_invoice_data[0]['Invoice']['invoice_number']?></span></div>
              <div> <?php echo __('Date : ');?><span><?php echo date('d-F-Y',strtotime($get_invoice_data[0]['Invoice']['invoice_date_month']));?></span></div>
              <div> <?php echo __('Retailer Code : ');?><span><?php echo $get_user_data['User']['username'];?></span></div>
              <div> <?php echo __('Retailer Tax ID : ');?><span></span></div>
              <div> <?php echo __('( Euro )');?></div>
            </div>
          </div>
        </div>
        <div class="spacer-10"></div>
        <div class="invoice-block">
          <table id="items-detail" cellspacing="0" cellpadding="0">
            <thead>
              <tr>
                <th> <?php echo __('Product code');?></th>
                <th> <?php echo __('Product Name');?></th>
                <th> <?php echo __('Product Quantity');?></th>
                <th> <?php echo __('Gross Price');?></th>
                <th> <?php echo __('Discount %');?></th>
                <th> <?php echo __('Discount val.');?></th>
                <th> <?php echo __('Per Pc. Price');?></th>
                <th> <?php echo __('Net Value');?></th>
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
                <td><?php echo __('Product code');?></td>
                <td><?php echo $data['Card']['c_title'];?></td>
                
                <td>
                <?php 
                    echo $data['Invoice']['total_cards']; 
                    $cards_count = $cards_count + $data['Invoice']['total_cards'];
                ?>
                </td>

                <td>
                <?php 
                    echo $data['Invoice']['total_sales']; 
                    $sale_count = $sale_count + $data['Invoice']['total_sales'];
                ?>
                </td>

                <td>
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

                <td><?php echo $discount;?></td>
                <td><?php echo $buying_price;?></td>
                <td><?php 
                        echo $data['Invoice']['total_purchase'];
                        $purchase_count = $purchase_count + $data['Invoice']['total_purchase'];
                      ?>
                </td>
              </tr>
            <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td>
                  <?php echo __('Total Quantity :');?>
                </td>
                <td>
                  <span><?php echo $cards_count;?></span> Pcs.
                </td>
                <td colspan="6"></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="invoice-block">
          <div class="right" id="net-total">
            <table cellspacing="0" cellpadding="0">
              <tr>
                <td>Netto Teil (Aus MwsT):</td>
                <td>
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
                <span>EU</span></td>
              </tr>
              <tr>
                <td>zzgl. 19%MwsT :</td>
                <td><?php echo $per_19;?><span>EU</span></td>
              </tr>
              <tr>
                <td>Gesamt Netto</td>
                <td><?php echo $purchase_count;?><span>EU</span></td>
              </tr>
            </table>
          </div>
        </div>
        <div class="invoice-block">
          <div class="left other-detail-left"> Brutto Kartenwert</div>
          <div class="right other-detail-right"><?php echo $sale_count;?><span>EU</span></div>
        </div>
        <div class="invoice-block">
          <div class="left other-detail-left"> Rabatt</div>
          <div class="right other-detail-right"><?php echo $sale_discount;?><span>EU</span></div>
        </div>
        <div class="invoice-block">
          <div class="left other-detail-mleft"> Rechnungsbetrag / Zu zahlender betrag:</div>
          <div class="right other-detail-mright"><?php echo $purchase_count;?><span>EU</span></div>
        </div>
        <div class="spacer-10"></div>
        <div class="invoice-block">
          <div class="left"><?php echo $setting_data['text_field1'];?></div>
        </div>
        <br/><br/><br/><br/><br/>
        <div class="invoice-block">
          <div class="left"><?php echo $setting_data['text_field2'];?></div>
        </div>
        <div class="invoice-block bank-details">
          
          <?php echo __('Bank account Details')." : ".$setting_data['bank_details'];?> 
        </div>
        <div class="spacer-10"></div>
        <div class="invoice-block">
          <div class="left"><?php echo $setting_data['text_field3'];?></div>
        </div>
        <div class="spacer-10"></div>
        <div class="invoice-block">
          <div class="left"><?php echo $setting_data['text_field4'];?></div>
        </div>
        <div class="spacer-10"></div>
        <div class="spacer-10"></div>
        <div class="spacer-10"></div>
        <div class="spacer-10"></div>
        <div class="spacer-10"></div>
        <div class="spacer-10"></div>
      </div>
  </body>
</html>