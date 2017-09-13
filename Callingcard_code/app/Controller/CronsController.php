<?php
App::uses('AppController', 'Controller');

class CronsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();		
		$this->Auth->allow('card_price_update','index','update_card_data','minimum_balance','purchase_limit_exceed','generate_invoices','entry_in_transaction_table','change_user');
	}
	
	public function index(){
		 
		 $this->minimum_balance();
		 $this->purchase_limit_exceed();
		 $this->generate_invoices();
	     $this->update_card_data();
		 $this->redirect(array('controller'=>'Pages','action'=>'dashboard'));
		
	}
	
	
	public function change_user(){
		$this->loadModel('User');
		$get_mediator = $this->User->find('all');
	    //prd($get_mediator);
		foreach($get_mediator as $k=>$val)
		{
			$username = $val['User']['username'];
			$name = str_replace('.','_',$username);
			//echo $name;
			$get_mediator[$k]['User']['username'] = $name;
			//$get_mediator[$k]['User']['username'] = $username;
			$this->User->save($get_mediator[$k]);
			//$this->User->updateAll(array('User.username'=>"'".$name."'"),array('User.id'=>$val['User']['id']));
		}
		exit;
	}
	public function entry_in_transaction_table(){
		
        $this->loadModel('Transaction');
        $this->loadModel('User');

		$conditions =array();
		$conditions['User.role_id'] = array('2');
        $conditions['User.status'] = array('1','2');
		$fields = array('added_by','id');
		$get_mediator = $this->User->find('all',array('conditions'=>$conditions,'fields'=>$fields));
		
		foreach($get_mediator as $med)
		{				
			$get_transaction_data = $this->Transaction->findByUserId($med['User']['id']);
			if(empty($get_transaction_data))
			{
				$new_transaction = array();
				$new_transaction['Transaction']['user_id'] = $med['User']['id'];
				$new_transaction['Transaction']['allocator_id'] = $med['User']['added_by'];
				$new_transaction['Transaction']['total_amount'] = 0;
				$new_transaction['Transaction']['balance'] = 0;
				$new_transaction['Transaction']['role_id'] = 2;
				$new_transaction['Transaction']['created'] = date('Y-m-d H:i:s');
				$new_transaction['Transaction']['updated'] = date('Y-m-d H:i:s');
				$this->Transaction->create();
				$this->Transaction->save($new_transaction);
			}
		}
		
		/* Retailer*/
		$conditions['User.role_id'] = array('3');
		$fields = array('added_by','id');
		$get_retailer = $this->User->find('all',array('conditions'=>$conditions,'fields'=>$fields));
		
		foreach($get_retailer as $ret)
		{				
			$get_transaction_data = $this->Transaction->findByUserId($ret['User']['id']);
			if(empty($get_transaction_data))
			{
				$new_transaction = array();
				$new_transaction['Transaction']['user_id'] = $ret['User']['id'];
				$new_transaction['Transaction']['allocator_id'] = $ret['User']['added_by'];
				$new_transaction['Transaction']['total_amount'] = 0;
				$new_transaction['Transaction']['balance'] = 0;
				$new_transaction['Transaction']['role_id'] = 3;
				$new_transaction['Transaction']['created'] = date('Y-m-d H:i:s');
				$new_transaction['Transaction']['updated'] = date('Y-m-d H:i:s');
				$this->Transaction->create();
				$this->Transaction->save($new_transaction);
			}
		}
	}
	public function minimum_balance(){

		$this->loadModel('User');
		$this->loadModel('Transaction');
		$this->loadModel('EmailContent');
	    
		$conditions =array();
		$conditions['User.role_id'] = array('2','3');
        $conditions['User.status'] = array('1','2');
		$fields = array('fname','lname','email','minimum_balance','role_id','id');
		$get_mediator_retailer = $this->User->find('all',array('conditions'=>$conditions,'fields'=>$fields));
        
		if(!empty($get_mediator_retailer))
		{
			foreach($get_mediator_retailer as $user)
			{
				$user_id = $user['User']['id'];
				$role_id = $user['User']['role_id'];
				$user_email = $user['User']['email'];
				$user_name = ucwords($user['User']['fname']." ".$user['User']['lname']);
				$user_minimum_balance = $user['User']['minimum_balance'];
				
				$transaction_conditions = array();
				$transaction_conditions['user_id'] = $user_id;
				$get_transaction = $this->Transaction->findByUserId($user_id);
				$transaction_balance = 0.00;
				
				if($get_transaction)
				{
					$transaction_balance = $get_transaction['Transaction']['balance'];
				}
				
				if($transaction_balance <= $user_minimum_balance)
				{
				  if($role_id ==2)
				  {
				  	if($user_email)
					$this->EmailContent->_MediatorBalnceFallingMinimum($user_email,$user_name,$transaction_balance,$user_minimum_balance); 
				  }
				  else
				  {
				  	if($user_email)
					$this->EmailContent->_RetailerBalnceFallingMinimum($user_email,$user_name,$transaction_balance,$user_minimum_balance); 
				  }
				}
			}
		}
	}
	
	public function purchase_limit_exceed(){
	
		$this->loadModel('User');
		$this->loadModel('Sale');
		$this->loadModel('EmailContent');
	    
		$conditions =array();
		$conditions['User.role_id'] = 3;
        $conditions['User.status'] = array('1','2');
		$fields = array('fname','lname','email','purchase_limit','role_id','id','added_by');
		$get_retailer = $this->User->find('all',array('conditions'=>$conditions,'fields'=>$fields));
		
		if(!empty($get_retailer))
		{
			foreach($get_retailer as $retailer)
			{
				$retailer_name = ucwords($retailer['User']['fname']." ".$retailer['User']['lname']);
				$retailer_email = $retailer['User']['email'];
				$retailer_id = $retailer['User']['id'];
				$retailer_purchase_limit = $retailer['User']['purchase_limit'];
			    if($retailer_purchase_limit)
				{
					$total_purchase = $this->Sale->find('first',array('conditions'=>array('Sale.s_u_id'=>$retailer_id),
														'fields'=>'sum(Sale.s_total_purchase) as total_amount',
														));
				    if(!empty($total_purchase[0]['total_amount']))
					{
						// If Retailer Exceeding Their Purchase Limit
						if($total_purchase[0]['total_amount'] >= $retailer_purchase_limit)
						{
						  $mediator_id = $retailer['User']['added_by'];
						  $get_mediator_data = $this->User->findById($mediator_id);
						  $mediator_email = $get_mediator_data['User']['email'];
						  $mediator_name = ucwords($get_mediator_data['User']['fname']." ".$get_mediator_data['User']['lname']);

						  /*Mail To Ratailer*/
						  if($retailer_email)
						  $this->EmailContent->_RetailerPurchaseLimitCross($retailer_email,$retailer_name,$total_purchase[0]['total_amount'],$retailer_purchase_limit); 
				  		 /*Mail To Its Mediator*/
						  if($mediator_email)
				  		  $this->EmailContent->_RetailerPurchaseLimitCrossMediator($mediator_email,$mediator_name,$retailer_name,$retailer_email,$total_purchase[0]['total_amount'],$retailer_purchase_limit);
						}
					}
				}
			}
		}
	}
	
	
	public function generate_invoices(){
	  
	    $this->loadModel('User');
	    $this->loadModel('Card');
		$this->loadModel('Invoice');
		/* Getting Retailer Details*/
		$conditions =array();
		$conditions['User.role_id'] = 3;
		$conditions['User.status'] = array('1','2');
		$fields = array('id','added_by');
		$get_retailer = $this->User->find('all',array('conditions'=>$conditions,'fields'=>$fields));	
		
		/* Getting Card Details*/
		$fields_card = array('c_id','c_title');
		$get_cards = $this->Card->find('list',array('fields'=>$fields_card,'order'=>'c_title ASC'));	
		
		$current_date = date('Y-m-d');
		$current_day = date("d");
    	$new_invoice_data = array();
		if($current_day == 3)
		{
			
			$last_month_date = date("Y-m-d", strtotime("$current_date -2 day"));
			$last_month_year = date("Y", strtotime("$current_date -2 day"));
			$last_month = date("m", strtotime("$current_date -2 day"));
			$last_month_name = date("F", strtotime("$current_date -2 day"));

			if(!empty($get_retailer))
			{
				foreach($get_retailer as $retailer)
				{
				  $sales_conditions = array();
				  $sales_conditions['s_u_id'] = $retailer['User']['id'];
				  $sales_conditions['YEAR(s_date)']  = $last_month_year;
				  $sales_conditions['MONTH(s_date)'] = $last_month;
				  
				  // Check If File uploaded or not 
				  $check_file_upload_conditions = array();
				  $check_file_upload_conditions['user_id']= $retailer['User']['id'];
				  $check_file_upload_conditions['file_name <>']= NULL;
				  $check_file_upload_conditions['invoice_date_month'] = $last_month_date;
				  $check_file_upload_conditions['invoice_number'] = "Invoice_".$last_month."_".$last_month_year."_".$retailer['User']['id'];

				  $get_invoice_data = $this->Invoice->find('all',array('conditions'=>$check_file_upload_conditions));
				  if(empty($get_invoice_data))
				  {
				  /*$sales_fields =array( 
				                        'sum(Sale.s_total_purchase) as total_purchase',
				  						'sum(Sale.s_total_sales) as total_sales',
										'sum(Sale.card_sale_count) as total_cards'
									   );*/
				  $sales_fields =array( 
				                        'Sale.s_total_purchase',
				  						'Sale.s_total_sales',
										'Sale.card_sale_count',
										'Sale.s_purchase_price',
										'Sale.s_selling_price'
									   );
				  $counter =0;
				  foreach($get_cards as $k=>$v)
				  {
					$sales_conditions['s_c_id'] = $k;
					//$group_by = 'YEAR(s_date),MONTH(s_date)';
					$sales_record = $this->Sale->find('all',array('conditions'=>$sales_conditions,'fields'=>$sales_fields));
					
					foreach($sales_record as $record)
					{
						$invoice_exist = array();
						/*$invoice_exist['card_id']= $k;
						$invoice_exist['added_by_user']= $retailer['User']['added_by'];
						$invoice_exist['user_id']= $retailer['User']['id'];
						$invoice_exist['invoice_date_month']=  $last_month_date;*/
	 
						$get_invoice_data = $this->Invoice->find('all',array('conditions'=>$invoice_exist));
						if(!empty($sales_record))
						{
							$new_invoice_data[$counter]['invoice_number'] = "Invoice_".$last_month."_".$last_month_year."_".$retailer['User']['id'];
							$new_invoice_data[$counter]['user_id'] = $retailer['User']['id'];
							$new_invoice_data[$counter]['added_by_user'] = $retailer['User']['added_by'];
							$new_invoice_data[$counter]['invoice_description'] = "Invoice ".$last_month_name." ".$last_month_year;
							$new_invoice_data[$counter]['card_id'] = $k;
							$new_invoice_data[$counter]['total_cards'] = $record['Sale']['card_sale_count'];
							$new_invoice_data[$counter]['total_purchase'] = $record['Sale']['s_total_purchase'];						 
							$new_invoice_data[$counter]['total_sales'] = $record['Sale']['s_total_sales'];	
							$new_invoice_data[$counter]['buying_price'] =  $record['Sale']['s_purchase_price'];	
							$new_invoice_data[$counter]['selling_price'] =  $record['Sale']['s_selling_price'];					 
							$new_invoice_data[$counter]['profit_money'] = $record['Sale']['s_total_sales'] - $record['Sale']['s_total_purchase'];
							$new_invoice_data[$counter]['profit_percentage'] =  ($new_invoice_data[$counter]['profit_money']/$record['Sale']['s_total_purchase'])*100;
							$new_invoice_data[$counter]['profit_percentage'] = round($new_invoice_data[$counter]['profit_percentage'],2);
							$new_invoice_data[$counter]['invoice_date_month'] = $last_month_date;
							$new_invoice_data[$counter]['invoice_created'] = $current_date;
							
							if(!empty($new_invoice_data[$counter]))
							{
								 $this->Invoice->create();
								 $save_invoice = $this->Invoice->save($new_invoice_data[$counter]);
							}    
							$counter++;
						  }
						// Sales Record End
						}
				      // End Of Cards
					 }
				}
			   //End Of Retailer
			  }
			}
			
		}
	}

	public function update_card_data(){


        $this->loadModel('Card');
		$this->loadModel('PinsCard');
        
        $cards = $this->Card->find('list',array('fields'=>'c_id,c_title'));

        foreach ($cards as $key =>$value )
        {

            $card_id = $key;
            $pin_card_conditions = array();

		    $pin_card_conditions['pc_c_id'] = $card_id;
		    $pin_card_conditions['pc_status'] = 2;
		    
		    $sold_pins = $this->PinsCard->find('count',array('conditions'=>$pin_card_conditions));
		    
		    $pin_card_conditions['pc_status'] = 1;
		    $unused_pin = $this->PinsCard->find('count',array('conditions'=>$pin_card_conditions));

		    $pin_card_conditions['pc_status'] = 3;
		    $parked_pin = $this->PinsCard->find('count',array('conditions'=>$pin_card_conditions));
		    
		    $pin_card_conditions['pc_status'] = 4;  
		    $rejected_pin = $this->PinsCard->find('count',array('conditions'=>$pin_card_conditions));
		    
		    $pin_card_conditions['pc_status'] = 5;  
		    $returned_pin = $this->PinsCard->find('count',array('conditions'=>$pin_card_conditions));
		    
		    $pin_card_count = $sold_pins + $unused_pin + $parked_pin + $rejected_pin
		    + $returned_pin;
		    
		    $update_cards_table = $this->Card->updateAll(
		    				array(
		    					    'Card.pin_card_count'=>$pin_card_count,  
		    						'Card.pin_card_sold_count'=>$sold_pins,
		    						'Card.pin_card_remain_count'=>$unused_pin,
		    						'Card.pin_card_count_parked'=>$parked_pin,
		    						'Card.pin_card_count_rejected'=>$rejected_pin,
		    						'Card.pin_card_count_returned'=>$returned_pin,
		    					 ),
		    				array('Card.c_id'=>$card_id));
	     }
	}

	public function card_price_update(){
   		 
   		$this->loadModel('CardsPrice');
   		$conditions = array();
   		$conditions['cp_u_role'] = 3;
   		$get_all_price_data = $this->CardsPrice->find('all',
   			array('conditions'=>$conditions,
   				  'recursive'=>-1));
   		foreach ($get_all_price_data as $key => $value) {
   			$get_all_price_data[$key]['CardsPrice']['cp_selling_price'] =  $value['CardsPrice']['cp_buying_price'];
   			$this->CardsPrice->delete($get_all_price_data[$key]['CardsPrice']['cp_id']);
   			# code...cp_buying_price
   		}


       // prd($get_all_price_data);
   }

}