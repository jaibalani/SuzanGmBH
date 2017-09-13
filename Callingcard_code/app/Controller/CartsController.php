<?php
App::uses('AppController', 'Controller');

class CartsController extends AppController {

	public $uses = array('Product','Cart');
	
	public function add() {
		$this->autoRender = false;
		if ($this->request->is('post')) {
		    $this->loadModel('Card');
		    $this->loadModel('PinsCard');
                   $card_data = $this->Card->findByCId($this->request->data['Cart']['card_id']);
                   //loaded_url_cart_icon
                    
                    $pin_details = $this->PinsCard->find('count',array(
							'conditions' =>array('PinsCard.pc_c_id'=>$this->request->data['Cart']['card_id'],'PinsCard.pc_status'=>1), //unsold pin 
							'order' => 'rand()',
						   ));


                    if($pin_details > 0)
                    {
                        $this->Cart->addProduct($this->request->data['Cart']['card_id']);
                    }
                    else
                    {
                        $this->Session->setFlash(__('The selected quantity is not available !'), 'default', array(),'error');
                        if(!empty($this->request->data['Cart']['loaded_url']))
                        {
                        	$this->redirect($this->request->data['Cart']['loaded_url']);
                        }
                        else
                        $this->redirect(array('controller'=>'Searches','action' => 'online_card'));
                    }
                }
		$temp = $this->Cart->getCount();

		if(!empty($this->request->data['Cart']['loaded_url']))
		$this->Session->write('loaded_url_session',$this->request->data['Cart']['loaded_url']);	
	    else
	    $this->Session->write('loaded_url_session','');	
	   	
		$this->redirect(array('action'=>'view'));

	}
	
	public function view() {
		$carts = $this->Cart->readProduct();
		//prd($carts);
		$this->loadModel('Card');
		$this->loadModel('PinsCard');
		
		//CASE 1 - Login user and his id are available in cash price table this means they have updated their price once
		//CASE 2 - If login user's id is not available but in case of mediator, distributor will set selling price for all its mediator.
		// CASE 3 - If login user's id is not available and also no price is set for such user role, then we will check prices of their parent.
		
		/*$case = 'case 
				when CardsPrice.cp_u_role = "'.$this->Session->read('Auth.User.role_id').'" and CardsPrice.cp_u_id ='.$this->Session->read('Auth.User.id').' then CardsPrice.cp_u_role = "'.$this->Session->read('Auth.User.role_id').'" and CardsPrice.cp_c_id = Card.c_id and CardsPrice.cp_u_id ='.$this->Session->read('Auth.User.id').'
				when CardsPrice.cp_u_role = "'.$this->Session->read('Auth.User.role_id').'" and CardsPrice.cp_u_id IS NULL then  CardsPrice.cp_u_role = "'.$this->Session->read('Auth.User.role_id').'" and CardsPrice.cp_u_id IS NULL  and CardsPrice.cp_c_id = Card.c_id
				when CardsPrice.cp_u_id ='.$this->Session->read('Auth.User.added_by').' then CardsPrice.cp_u_id ='.$this->Session->read('Auth.User.id').' and CardsPrice.cp_c_id = Card.c_id
			end';*/

		
	    /* $case = 'CASE 
	                   when CardsPrice.cp_u_id ="'.$this->Session->read('Auth.User.id').'" 
	                   and CardsPrice.cp_c_id = Card.c_id and 
	                   NOT (CardsPrice.cp_u_id ="'.$this->Session->read('Auth.User.added_by').'" 
	                   and CardsPrice.cp_c_id = Card.c_id) 
		               then 
		               CardsPrice.cp_u_id ="'.$this->Session->read('Auth.User.id').'" 
		               and CardsPrice.cp_c_id = Card.c_id

                       when CardsPrice.cp_u_id ="'.$this->Session->read('Auth.User.added_by').'" 
	                   and CardsPrice.cp_c_id = Card.c_id and 
	                   NOT (CardsPrice.cp_u_id ="'.$this->Session->read('Auth.User.id').'" 
	                   and CardsPrice.cp_c_id = Card.c_id) 
		               then 
		               CardsPrice.cp_u_id ="'.$this->Session->read('Auth.User.added_by').'" 
		               and CardsPrice.cp_c_id = Card.c_id
	             END';
		*/

		 
	    $case = 'CASE
	    			 
	    			WHEN  EXISTS (
	    				           SELECT * FROM ecom_cards_prices as CardsPrice
    				               WHERE 
    				              
    				              (
    				              	CardsPrice.cp_u_id = "'.$this->Session->read('Auth.User.added_by').'" 
                                    and   CardsPrice.cp_c_id = Card.c_id
                                  )
                                  
                                  and NOT EXISTS
                                  (
                                  	  SELECT * FROM ecom_cards_prices as CardsPrice
	    				              WHERE 
	    				              CardsPrice.cp_u_id = "'.$this->Session->read('Auth.User.id').'" 
                                      and   CardsPrice.cp_c_id = Card.c_id
                                  )

	    				         ) 
					then 
		            CardsPrice.cp_u_id ="'.$this->Session->read('Auth.User.added_by').'" 
		            and CardsPrice.cp_c_id = Card.c_id


	    			WHEN EXISTS (
	    				           SELECT * FROM ecom_cards_prices as CardsPrice
	    				              WHERE 
	    				              CardsPrice.cp_u_id = "'.$this->Session->read('Auth.User.id').'" 
                                      and   CardsPrice.cp_c_id = Card.c_id
	    				         ) 
					then 
		            CardsPrice.cp_u_id ="'.$this->Session->read('Auth.User.id').'" 
		            and CardsPrice.cp_c_id = Card.c_id

		          
	             END
	            ';

		$products = array();
		//echo "<pre>";
		//print_r($carts);
		//exit;
		if ($carts != NULL) {
			foreach ($carts as $cardId => $count) {
				//$product = $this->Card->read(null,$cardId);
			$product = $this->Card->find('first',array(
			'joins' => array(
					array(
						'table' => 'ecom_cards_prices',
						'alias' => 'CardsPrice',
						'type' => 'left',
						'conditions' => $case,
					)
				),
			'conditions' => array('Card.c_id'=>$cardId),
			'fields'		 => array('*','CASE WHEN CardsPrice.cp_selling_price IS NOT NULL THEN CardsPrice.cp_selling_price ELSE Card.c_selling_price END AS price','CASE WHEN CardsPrice.cp_buying_price IS NOT NULL THEN CardsPrice.cp_buying_price ELSE Card.c_buying_price END AS buyingprice'),
			'recursive'	 => -1,
			'group'			 => array('Card.c_id'),
		));
				//prd($product);
				
                if(empty($product['CardsPrice']['cp_id']))
                {
                	 $product[0]['buyingprice'] = $product[0]['price']; 
                }
				
				if($product[0]['price'] == 0)
				{
				  $product[0]['price'] = $product[0]['buyingprice'];	
				}
                
                 /* New Changes Selling + Buying Price 27 March 2015*/
            	$product[0]['price'] = $product['Card']['c_denomination_rate'];

                $product['Card']['count'] = $count;
				$products[]=$product;
			}
		} 
		$details = $this->Session->read('products');
		if(isset($details) && !empty($details)){
			$this->Session->delete('products');
		}
		//prd($products);
		$pin_array = array();
		$not_available_pins = '';
		$k = 0;
		
		foreach($products as $val)
		{
		     $count_card	= $val['Card']['count'];
			$products[$k]['Card']['max_available'] = 0;
			$pin_count_flag = 0;
			$pin_details = $this->PinsCard->find('count',array(
							'conditions' =>array('PinsCard.pc_c_id'=>$val['Card']['c_id'],'PinsCard.pc_status'=>1), //unsold pin 
							'order' => 'rand()',
						   ));
			if($pin_details)
			{
                           $products[$k]['Card']['max_available'] = floor( $pin_details / $val['Card']['c_pin_per_card']);
			}
			else
			{
			  $products[$k]['Card']['max_available'] = 0;
			}
		    $k++;
		}
		$this->Session->write('products',$products);
		$this->Card->Behaviors->detach('Containable');
		$this->set(compact('products'));
	}
		
	public function update()
	{
		if ($this->request->is('post')) {
			if (!empty($this->request->data['Cart']['count'])) {
				$cart = array();
				$if_remove = $this->request->data['Cart']['delete_c_id'];
				if(isset($if_remove) && !empty($if_remove)){
					$del_index = array_search($if_remove,$this->request->data['Cart']['c_id']);
					unset($this->request->data['Cart']['c_id'][$del_index]);
					unset($this->request->data['Cart']['count'][$del_index]);
					$this->request->data['Cart']['c_id']  = array_values($this->request->data['Cart']['c_id']);
					$this->request->data['Cart']['count'] = array_values($this->request->data['Cart']['count']);
				}
				foreach ($this->request->data['Cart']['count'] as $index=>$count) {
					if ($count>0) {
						$productId = $this->request->data['Cart']['c_id'][$index];
						$cart[$productId] = $count;
					}
				}
				$this->Cart->saveProduct($cart);
			}
		}
		$this->redirect(array('action'=>'view'));
	}
	public function checkout()
	{
		
		$this->loadModel('EmailContent');
		$this->autoRender = false ;
		$cart_details = $this->Session->read('products');
		if(isset($cart_details) && !empty($cart_details))
		{
			//prd($cart_details);
			$pin_array = array();
			$this->loadModel('PinsCard');
			$this->loadModel('Card');
			$this->loadModel('Pin');
			$this->loadModel('Sale');
			$this->loadModel('CardsSale');
			$sale_ids = array();
			//prd($cart_details);
			$this->loadModel('Transaction');
			$total_amount = 0;
			$not_available_pins = '';
			$get_available_balance = $this->Transaction->findByUserId($this->Auth->User('id'));
			

			$allowed_credit = $this->Auth->User('allow_credit');
			if($allowed_credit)
			{
				$balance_below_0 = $allowed_credit;
			}
			else
			{
				$balance_below_0 = 0;
			}
			
			if($get_available_balance)
			{
				$balance = $get_available_balance['Transaction']['balance'];
			}
			else
			{
				$balance = 0;
			}
			
			foreach($cart_details as $val)
			{
				$total_amount = $total_amount + $val['Card']['count'] * $val[0]['buyingprice'];
			}
			
			if($total_amount > $balance)
			{
				if($balance+ $balance_below_0 < $total_amount)
				{
					 $this->Session->setFlash(__('You have not sufficient balance to purchase the cards.'), 'default', array(),'error');
	   				 $this->Session->delete('products');
					 $this->Session->delete('cart');
					 $this->redirect(array('controller'=>'Searches','action' => 'online_card'));	
				}
			}
			//prd($cart_details);
			foreach($cart_details as $val)
			{
				$pin_count_flag = 0;
				$data = array();
				$data['Sale']['s_u_id']	= $this->Session->read('Auth.User.id');
				$data['Sale']['s_c_id']	= $val['Card']['c_id'];
				$data['Sale']['s_purchase_price']	= $val[0]['buyingprice'];
				$data['Sale']['s_selling_price']	= $val[0]['price'];
				$data['Sale']['s_purchase_profit']	= ((($data['Sale']['s_selling_price'] - $data['Sale']['s_purchase_price']) / $data['Sale']['s_selling_price']) * 100);
				$data['Sale']['s_quantity']	= $val['Card']['count'];
				$data['Sale']['s_total_purchase']	= $val['Card']['count'] * $data['Sale']['s_purchase_price'];
				$data['Sale']['s_total_sales']		= $val['Card']['count'] * $data['Sale']['s_selling_price'];
				$data['Sale']['s_total_profit']	= ((($data['Sale']['s_total_sales'] - $data['Sale']['s_total_purchase']) / $data['Sale']['s_total_sales']) * 100);
				$data['Sale']['s_time']  = date('H:i:s');
				$data['Sale']['s_date']  = date('Y-m-d H:i:s');
				$this->Sale->create();
				$this->Sale->save($data);
				$s_id = $this->Sale->getLastInsertId();
				$sale_ids[] = $s_id;

				for($i=0; $i<$data['Sale']['s_quantity'];$i++)
				{
					//Card Sale Array
					$sale_data = array();
					$sale_data['CardsSale']['cs_c_id'] = $val['Card']['c_id'];
					$sale_data['CardsSale']['cs_s_id'] = $s_id;
					$pin_details = $this->PinsCard->find('all',array(
						'conditions' =>array('PinsCard.pc_c_id'=>$val['Card']['c_id'],'PinsCard.pc_status'=>1,'NOT'=>array('PinsCard.pc_p_id'=>$pin_array)), //unsold pin 
						'order' => 'rand()',
						'limit' => $val['Card']['c_pin_per_card']
					));
									
					//foreach pin array
					if(count($pin_details)==$val['Card']['c_pin_per_card'])
					{
						//if counts per card is equal to available pins
						$j = 0;
						//prd($pin_details);
						foreach($pin_details as $pinvalue)
						{
							$pin_array[] = $pinvalue['PinsCard']['pc_p_id'];
							$sale_data['CardsPinsSale'][$j]['cps_p_id'] = $pinvalue['PinsCard']['pc_p_id'];
							if(isset($pinvalue['PinsCard']['pc_merged_from_c_id']) && !empty($pinvalue['PinsCard']['pc_merged_from_c_id'])){
								//if merged from any any card
								$total_pin_sold = $pinvalue['CardMergedFrom']['pin_card_sold_count'] + $val['Card']['count'] * $val['Card']['c_pin_per_card'];
								$remian_count = $pinvalue['CardMergedFrom']['pin_card_count'] - $total_pin_sold;
							}
							$j++;
						}
						$card_of_same_pins = $this->PinsCard->find('first',array('fields'=>'group_concat(pc_c_id) as cards_ids','conditions'=>array('pc_p_id'=>$pin_array)));
						if(isset($card_of_same_pins[0]['cards_ids']) && !empty($card_of_same_pins[0]['cards_ids']))
						{
							/*$update_pin_sold = $this->Card->updateAll(array('Card.pin_card_sold_count'=>'Card.pin_card_sold_count + '.($val['Card']['c_pin_per_card']),'Card.pin_card_remain_count'=>'Card.pin_card_remain_count - '.( $val['Card']['c_pin_per_card'])),array('Card.c_id IN('.$card_of_same_pins[0]['cards_ids'].')'));*/
						}
						
						$save = $this->CardsSale->saveassociated($sale_data, array('deep' => true));
						
						$total_pin_sold = $val['Card']['pin_card_sold_count'] + $val['Card']['count'] * $val['Card']['c_pin_per_card'];
						$remian_count = $val['Card']['pin_card_count'] - $total_pin_sold;
						/*$update_pin_sold = $this->Card->updateAll(array('Card.pin_card_sold_count'=>$total_pin_sold,'Card.pin_card_remain_count'=>$remian_count),array('Card.c_id'=>$val['Card']['c_id']));*/
						
					}
					else
					{
						if($pin_count_flag == 0)
						{
							$not_available_pins = $not_available_pins ." -- ".$val['Card']['c_title'];
						    $pin_count_flag = 1;
						}
						//if count of pins not available as mentioned as qty
						$last_id  = array_pop($sale_ids); //get last saved id
						@$this->Sale->delete(array('Sale.s_id'=>$last_id));
						$delete_index = array_search($last_id,$sale_ids);
						unset($sale_ids[$delete_index]);
						$sale_ids = array_values($sale_ids); //remove last saved id as we don't have enough pins available
					}
				}
				if(isset($save))
				{					
					
					foreach($pin_array as $pin)
					{
						//$update_pin_sold = $this->Card->updateAll(array('Card.pin_card_sold_count'=>$total_pin_sold,'Card.pin_card_remain_count'=>$remian_count),array('Card.c_id'=>$pinvalue['PinsCard']['pc_merged_from_c_id']));
						
						$update_pin = $this->Pin->updateAll(array('Pin.p_status'=>2),array('Pin.p_id'=>$pin));
						$update_pins_card = $this->PinsCard->updateAll(array('PinsCard.pc_status'=>2),array('PinsCard.pc_p_id'=>$pin));
					}
                    
                    $card_id = $val['Card']['c_id'];

                    /* Updating Pins Details For Card */
                    $this->update_cards_pins_details($card_id);

					$get_available_balance = $this->Transaction->findByUserId($this->Auth->User('id'));
					
					if(!empty($get_available_balance))
					{
						$get_available_balance['Transaction']['balance'] =  $get_available_balance['Transaction']['balance'] - $data['Sale']['s_total_purchase'];
                        
                        $update_balance = $this->Transaction->updateAll(array('Transaction.balance'=>$get_available_balance['Transaction']['balance'],
							'Transaction.updated'=>"'".date('Y-m-d h:i:s')."'",
							),array('Transaction.user_id'=>$this->Auth->User('id')));
					}
					else
					{
						$new_transaction = array();
						$new_transaction['Transaction']['user_id'] = $this->Auth->User('id');
						$new_transaction['Transaction']['allocator_id'] = $this->Auth->User('added_by');
						$new_transaction['Transaction']['total_amount'] = 0;
						$new_transaction['Transaction']['balance'] = -$data['Sale']['s_total_purchase'];
						$new_transaction['Transaction']['role_id'] = 3;
						$new_transaction['Transaction']['created'] = date('Y-m-d H:i:s');
						$new_transaction['Transaction']['updated'] = date('Y-m-d H:i:s');
						$this->Transaction->create();
						$this->Transaction->save($new_transaction);
					}
				    $this->Card->updateCounterCache(array('Card.c_id'=>$val['Card']['c_id']));
				    //$this->PrintCard($sale_ids);
				}
			}

		 if(isset($save))
		 {
			if(empty($not_available_pins))
			{
				$this->Session->setFlash(__('You have successfully purchased the cards.'), 'default', array(),'success');
			}
			else
			$this->Session->setFlash(__('You have successfully purchased some  cards. But these cards could not be purchased as pins are not available for these cards. '.$not_available_pins), 'default', array(),'success');
			 $this->Session->delete('products');
    		 $this->Session->delete('cart');
   		     $this->Session->write('sale_ids',$sale_ids);
			 
			 $retailer_data = $this->User->findById($this->Auth->User('id'));
			 $allowed_purchase_limit = $retailer_data['User']['purchase_limit'];
			 $retailer_email = $retailer_data['User']['email'];;
			 $retailer_name = ucwords($retailer_data['User']['fname']." ".$retailer_data['User']['lname']);
             
			 $mediator_data  = $this->User->findById($this->Auth->User('added_by'));
			 $mediator_name  = ucwords($mediator_data['User']['fname']." ".$mediator_data['User']['lname']);
			 $mediator_email = $mediator_data['User']['email'];
			 
			 if($allowed_purchase_limit)
			 {
    			$total_purchase = $this->Sale->find('first',array('conditions'=>array('Sale.s_u_id'=>$this->Auth->User('id')),
				 												'fields'=>'sum(Sale.s_total_purchase) as total_amount',
																));
                $total_purchase_amount = $total_purchase[0]['total_amount'];
				if($total_purchase_amount >= $allowed_purchase_limit)
				{
				  /*Mail To Ratailer*/
				  if($retailer_email)
				  $this->EmailContent->_RetailerPurchaseLimitCross($retailer_email,$retailer_name,$total_purchase_amount,$allowed_purchase_limit); 
				  /*Mail To Its Mediator*/
				  if($mediator_email)
				  $this->EmailContent->_RetailerPurchaseLimitCrossMediator($mediator_email,$mediator_name,$retailer_name,$retailer_email,$total_purchase_amount,$allowed_purchase_limit); 
				}
			 }
			 $this->redirect(array('controller'=>'carts','action' => 'printcard'));
		 }
		 else
		 {
		 	if(!empty($not_available_pins))
			$this->Session->setFlash(__('These cards could not be purchased as pins are not available for these cards. '.$not_available_pins), 'default', array(),'error');
			else
			$this->Session->setFlash(__('You can not purchase the cards now.'), 'default', array(),'error');
			$this->Session->delete('products');
		 	$this->Session->delete('cart');
		    $this->redirect(array('controller'=>'Searches','action' => 'online_card'));	
		 }
	    }
		else
		{
			$this->Session->delete('cart');
			$this->Session->delete('products');
		    $this->Session->setFlash(__('No Card Selected'), 'default', array(),'error');
			$this->redirect(array('controller'=>'Searches','action' => 'online_card'));
		}
	}
	
	private function showDiv($str1,$str2)
	{
		$str3 = '';
		$style="display:block;";
		if($str1!='' && $str2!=''){
			$str3 = $str1.', '.$str2;
		}else if($str2!='' && $str1==''){
			$str3 = $str2;
		}else if($str2=='' && $str1!=''){
			$str3 = $str1;
		}else {
			$str3='';
			$style="display:none;";
		}
		return $str3.'~'.$style;
	}
	public function printcard($sales_id_print = NULL)
	{
		
		$this->loadModel('Websetting');
		$web_setting = $this->Websetting->find('first', array(
				'conditions' =>array(
					'Websetting.key' => 'Site.phone',
					//'is_show' =>'1'
				),
				//'order' => array('setting_order ASC')
			)
		);
       
		//prd($web_setting);

		$language=Configure::read('Config.language');
		//echo $language;
		//exit;
		$sales_id1 = $this->Session->read('sale_ids');
		$sales_ids_str="''";
		$this->loadModel('CardsSale');
		$this->loadModel('Card');
		$this->loadModel('Sale');
		
		if($sales_id_print != NULL)
		{
			$sales_ids_str =$sales_id_print;
                        $get_sales_data = $this->Sale->findBySId($sales_id_print);
                        if(!empty($get_sales_data))
                        {
                             if($get_sales_data['Sale']['s_u_id'] != $this->Auth->User('id'))
                             {
                               $this->Session->setFlash(__('Invalid sales.'), 'default', array(),'error');
			       $this->redirect(array('controller'=>'Searches','action' => 'online_card'));  
                             }
                        }
                        else
                        {
                            $this->Session->setFlash(__('Invalid sales.'), 'default', array(),'error');
			    $this->redirect(array('controller'=>'Searches','action' => 'online_card'));
                        }
		}
		else if(count($sales_id1)!=0){
			$sales_ids_str = implode(',',$sales_id1); 
		}
		//$sales_ids_str='82,83';

		$this->Sale->Behaviors->attach('Containable');
		$this->Sale->contain(array('Card'=>array('CardsFreeText'),
		'CardsSale' => array('CardsPinsSale'=>array(
		'Pin'=>array(
				'PinsCard'=>array(
							'CardMergedFrom'=>array('CardsFreeText'))
					)
		)
		)
		));

		$order_ids  = $this->Sale->find('all',array('conditions'=>'Sale.s_id IN ('.$sales_ids_str.')','recursive'=>3));
	
		$i=0;
		$index=0;
		$curreny  = '€';
		if($language=='deu'){
			$index=1;
		}
		if($language=='deu'){
			$curreny='€';
		}
		
		$final_card_sale_bills = array();
		$counter = 0;

		foreach($order_ids as $ids)
		{
			
            if(!empty($ids['Card']['c_image']))
			{
				if(file_exists(WWW_ROOT.'img/card_icons/'.$ids['Card']['c_image']))
				{
					$card_image = IMAGE_PATH.'/img/card_icons/'.$ids['Card']['c_image'];
                                        $image_name = $ids['Card']['c_image'];
				}
				else
				{
					$card_image = IMAGE_PATH.'/img/card_icons/card_not_availabe.png';
                                        $image_name = 'card_not_availabe.png';
				}
			}
			else
			{
			        $card_image = IMAGE_PATH.'/img/card_icons/card_not_availabe.png';
                                 $image_name = 'card_not_availabe.png';
			}
			
			/* Getting Text For Card*/
			$get_user_lang =  $language;//$this->Auth->User('language_code');
			if(!empty($get_user_lang))
			{
                            
			}
			else
			{
				$get_user_lang = "en";
			}
			//echo $get_user_lang;exit;
			//prd($ids);
			if($get_user_lang == "en")
			{
				// English
				$free_text = $ids['Card']['CardsFreeText'][0];
			}
			else
			{
				// German // Code = "deu"
				$free_text = $ids['Card']['CardsFreeText'][1];
			}
			//prd($free_text);
			
			/*Getting Contact Detail*/
			$contact = array();
			if($language=='deu')
                        {
				if(!empty($ids['Card']['c_contact_number_1']))
				{
					$contact[]=$ids['Card']['c_contact_number_1'];
				}
			}
                        else
                        {
				if(!empty($ids['Card']['c_contact_number_2']))
				{
					$contact[]=$ids['Card']['c_contact_number_2'];
				}
			}
			
                        /*$final_card_sale_bills[$counter]['contact'] =implode(',',$contact);
                        $final_card_sale_bills[$counter]['free_text'] = ucfirst($free_text['cf_freetext1'])."<br/> ".ucfirst($free_text['cf_freetext2'])."<br/>".ucfirst($free_text['cf_freetext3'])."<br/>".ucfirst($free_text['cf_freetext4'])."<br/>".ucfirst($free_text['cf_freetext5'])."<br/>".ucfirst($free_text['cf_freetext6']);
                        $final_card_sale_bills[$counter]['card_name'] = ucwords($ids['Card']['c_title']);
                        $final_card_sale_bills[$counter]['selling_price'] = $ids['Sale']['s_selling_price'];
                        $final_card_sale_bills[$counter]['card_sale_count'] = $ids['Sale']['card_sale_count'];
                        $final_card_sale_bills[$counter]['s_total_sales'] = $ids['Sale']['s_total_sales'];
                        $final_card_sale_bills[$counter]['s_date'] = date('m/d/y',strtotime($ids['Sale']['s_date']));
			
   		        $final_card_sale_bills[$counter]['pins'] = trim(implode(', ',$all_pins));
			$final_card_sale_bills[$counter]['serial'] = trim(implode(', ',$all_serial));*/
			
			/*Get Local Number*/
			$local_number = array();
			
			if(!empty($ids['Card']['c_local_number_1']))
		        $local_number[] = $ids['Card']['c_local_number_1'];
		    else
		    	$local_number[] = 'N/A';
			if(!empty($ids['Card']['c_local_number_2']))
		        $local_number[] = $ids['Card']['c_local_number_2'];
		    else
		    	$local_number[] = 'N/A';
			if(!empty($ids['Card']['c_local_number_3']))
		        $local_number[] = $ids['Card']['c_local_number_3'];
		    else
		    	$local_number[] = 'N/A';
			if(!empty($ids['Card']['c_local_number_4']))
		        $local_number[] = $ids['Card']['c_local_number_4'];
		    else
		    	$local_number[] = 'N/A';
			if(!empty($ids['Card']['c_local_number_5']))
		        $local_number[] = $ids['Card']['c_local_number_5'];
		    else
		    	$local_number[] = 'N/A';
			if(!empty($ids['Card']['c_local_number_6']))
		        $local_number[] = $ids['Card']['c_local_number_6'];			
		    else
		    	$local_number[] = 'N/A';
            $free_card_text = '';
           
            foreach($free_text as $k=>$text)
            {
                
                if($k !='cf_id' && $k!='cf_c_id' && $k!='cf_alias')
                {
                    if(!empty($free_text[$k]))
                    {
                     $free_card_text = $free_card_text.ucfirst(strtolower($text))." ";
                    }
                }
            }
                        /*Getting List of Pins */
			$all_pins =array();
			$all_serial =array();
                        
            if(empty($contact))
            $new_contact = "N/A";
            else
            $new_contact = implode(',',$contact);
            
            
            // if(empty($local_number))
            // $new_local = "N/A";
            // else
            // $new_local = implode(' , ',$local_number);        
                        
			foreach($ids['CardsSale'] as $card_sale)
			{
                foreach($card_sale['CardsPinsSale'] as $pins_sale)
                {
					$all_pins[] = $pins_sale['Pin']['p_pin'];
					$all_serial[] = $pins_sale['Pin']['p_serial'];


					$final_card_sale_bills[$counter]['contact'] =$new_contact;
					$final_card_sale_bills[$counter]['free_text'] = $free_card_text;
					$final_card_sale_bills[$counter]['card_name'] = ucwords($ids['Card']['c_title']);
					$final_card_sale_bills[$counter]['selling_price'] = $ids['Sale']['s_selling_price'];
					$final_card_sale_bills[$counter]['card_sale_count'] = $ids['Sale']['card_sale_count'];
					$final_card_sale_bills[$counter]['s_total_sales'] = $ids['Sale']['s_total_sales'];
					$final_card_sale_bills[$counter]['s_date'] = date('d.m.Y',strtotime($ids['Sale']['s_date'])).' | '.$ids['Sale']['s_time'];
					$final_card_sale_bills[$counter]['local_number'] = $local_number;
					$final_card_sale_bills[$counter]['pins'] = $pins_sale['Pin']['p_pin'];
					$final_card_sale_bills[$counter]['serial'] = $pins_sale['Pin']['p_serial'];
					$final_card_sale_bills[$counter]['card_image'] = $card_image;
					$final_card_sale_bills[$counter]['image_name'] = $image_name;
					$final_card_sale_bills[$counter]['hotline_number'] = $web_setting['Websetting']['value'];
                    $counter++;
                }
			}
		}
		$this->set('final_card_sale_bills',$final_card_sale_bills);
        $this->Session->write('final_card_sale_bills',$final_card_sale_bills);
		//$this->Session->delete('sale_ids');
	}

	public function print_preview(){
        
        $this->layout = 'ajax'; 
		
		$final_card_sale_bills = $this->Session->read('final_card_sale_bills');
		$card_per_page = $this->request->data['page_data'];

		$this->set('final_card_sale_bills', $final_card_sale_bills);
		$this->set('card_per_page', $card_per_page);
	}
        
    public function download_excel(){
        
           $final_card_sale_bills = $this->Session->read('final_card_sale_bills');
           include(APP.'Vendor/PHPExcel/Classes/PHPExcel.php');
           include(APP.'Vendor/PHPExcel/Classes/PHPExcel/IOFactory.php');
	    		
	       $retailer_name = ucwords(strtolower($this->Auth->User('fname')." ".$this->Auth->User('lname')));

           //error_reporting(E_ALL);
           $objPHPExcel = new PHPExcel();

            // Setting Column Width
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);


            $objPHPExcel->getProperties()->setCreator("Calling Cards")->setLastModifiedBy("Calling Cards")->setTitle("Office 2007 XLSX Document");
		
            $objPHPExcel->setActiveSheetIndex(0);		
            $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
            $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);

            // Title of the Report		
            $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getFont()->setSize(16);
            $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getFont()->setBold(true);

            $objPHPExcel->getActiveSheet()->setCellValue('A2','Card-Pin-Sales');			
            $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
            $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            $styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);

            $heading = 4;
            $FirstItemNumber = $heading + 2;

            $objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,__('S.No'));
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,__("Retailer"));
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,__("Card"));
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,__("Serial"));
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$heading,__("Pin"));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$heading,__("Price")."(€)");
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$heading,__("Date"));
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$heading,__("Image"));
            

            $objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':H'.$heading)->getFont()->setBold(true);;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':H'.$heading)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':H'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                                        
		$serial_counter = 0;
		$total_sales = 0;
        $heading++;
        $total_sales_count = count($final_card_sale_bills);
        foreach($final_card_sale_bills as $keynew=>$val)
		{
		    $serial_counter++;
                    $heading++;
                    $total_sales = $total_sales + $val['selling_price'];
                    
                    if(file_exists(WWW_ROOT.'img/card_icons/'.$val['image_name']))
                    {
                            $card_image = $val['image_name'];
                    }
                    else
                    {
                            $card_image = 'card_not_availabe.png';
                    }
                    //$image_cat = '<img style="cursor:pointer;margin-top:3px;border-radius:7px;" src="'.$card_image.'" border="0" width="50"/>';
                    
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,$retailer_name);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,ucwords(strtolower($val['card_name'])));
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,$val['serial']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,$val['pins']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,$val['selling_price']);
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$FirstItemNumber,$val['s_date']);
                    
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    $objDrawing->setPath('./img/card_icons/'.$card_image);
                    $objDrawing->setOffsetX(10);
                    $objDrawing->setOffsetY(5);
                    $objDrawing->setCoordinates('H'.$FirstItemNumber);
                    $objDrawing->setHeight(40);
                    $objDrawing->setWidth(50);
                    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
                    
                    $objPHPExcel->getActiveSheet()->getRowDimension($heading)->setRowHeight(45);
                    
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':H'.$FirstItemNumber)->getAlignment()->setWrapText(true);                   
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':H'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':H'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':H'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':H'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);

		    $FirstItemNumber++;
		}
		
		// Setting Last Line Style For Net Total
		$FirstItemNumber++;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':H'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':H'.$FirstItemNumber)->getFont()->setBold(True)->setSize(13);

                // Merging Column
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':B'.$FirstItemNumber);

		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,__('Net Quantity'));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,$total_sales_count);
		
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,$total_sales);
		
		// Set page margin
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);

                
		$fullPath = WWW_ROOT.'Card-Pin_Sales.xlsx';

		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');		
		$objWriter->save(WWW_ROOT.'Card-Pin_Sales.xlsx');

	        if ($fd = fopen ($fullPath, "r")) 
		{
				$fsize = filesize($fullPath);
				$path_parts = pathinfo($fullPath);
				$ext = strtolower($path_parts["extension"]);
				switch ($ext) {
					case "xlsx":
						header("Content-type: application/vnd.ms-excel;charset:UTF-8"); // add here more headers for diff. extensions
						header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
					break;
					default;
						header("Content-type: application/octet-stream");
						header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
				}
				header("Content-length: $fsize");
				header("Cache-control: private"); //use this to open files directly
				while(!feof($fd)) {
					$buffer = fread($fd, 2048);
					echo $buffer;
				}
		    fclose ($fd);
    		unlink($fullPath);
		}
		exit();
	}
	public function download_csv(){
			$final_card_sale_bills = $this->Session->read('final_card_sale_bills');
			$out = '"' . __("S. No.") . '","' . __("Card name") . '","' . __("Serial no.") . '","' . __("Pin no.") . '"';
            $out .="\n";
            $i = 0;
            while (isset($final_card_sale_bills[$i])) {
                
                $out .='"' . ($i + 1) .'",';
                $out .='"' . $final_card_sale_bills[$i]['card_name'] .'",';
                $out .='"' . $final_card_sale_bills[$i]['serial'] . '",';
                $out .='"' . $final_card_sale_bills[$i]['pins'] . '",';
                $out .="\n";
                
                $i++;
            }
            header("Content-type: text/x-csv; charset=utf-8");
            header('Content-Disposition: attachment; filename="card_list.csv"');
            
            echo $out;
            exit;
	}
}