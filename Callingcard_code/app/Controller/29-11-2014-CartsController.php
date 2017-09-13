<?php
App::uses('AppController', 'Controller');

class CartsController extends AppController {

	public $uses = array('Product','Cart');
	
	public function add() {
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$this->Cart->addProduct($this->request->data['Cart']['card_id']);
		}
		$temp = $this->Cart->getCount();
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
		
		$case = 'case 
										when CardsPrice.cp_u_role = "'.$this->Session->read('Auth.User.role_id').'" and CardsPrice.cp_u_id ='.$this->Session->read('Auth.User.id').' then CardsPrice.cp_u_role = "'.$this->Session->read('Auth.User.role_id').'" and CardsPrice.cp_c_id = Card.c_id and CardsPrice.cp_u_id ='.$this->Session->read('Auth.User.id').'
										when CardsPrice.cp_u_role = "'.$this->Session->read('Auth.User.role_id').'" and CardsPrice.cp_u_id IS NULL then  CardsPrice.cp_u_role = "'.$this->Session->read('Auth.User.role_id').'" and CardsPrice.cp_u_id IS NULL  and CardsPrice.cp_c_id = Card.c_id
										when CardsPrice.cp_u_id ='.$this->Session->read('Auth.User.added_by').' then CardsPrice.cp_u_id ='.$this->Session->read('Auth.User.id').' and CardsPrice.cp_c_id = Card.c_id
									end';
		$products = array();
		//echo "<pre>";
		//print_r($carts);
		//exit;
		if (null!=$carts) {
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
		
	public function update() {
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
				$total_amount = $total_amount + $val['Card']['count'] * $val[0]['price'];
			}
			
			if($total_amount > $balance)
			{
				if($balance+ $balance_below_0 < $total_amount)
				{
					 $this->Session->setFlash(__('You have not sufficient balance to purchase the cards.'), 'default', array(),'error');
	   				 $this->Session->delete('products');
					 $this->Session->delete('cart');
					 $this->redirect(array('controller'=>'Searches','action' => 'index'));	
				}
			}
			
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
						foreach($pin_details as $pinvalue)
						{
							$pin_array[] = $pinvalue['PinsCard']['pc_p_id'];
							$sale_data['CardsPinsSale'][$j]['cps_p_id'] = $pinvalue['PinsCard']['pc_p_id'];
							$j++;
						}
						//prd($data);
						$save = $this->CardsSale->saveassociated($sale_data, array('deep' => true));
						
						$total_pin_sold = $val['Card']['pin_card_sold_count'] + $val['Card']['count'] * $val['Card']['c_pin_per_card'];
						$remian_count = $val['Card']['pin_card_count'] - $total_pin_sold;
						$update_pin_sold = $this->Card->updateAll(array('Card.pin_card_sold_count'=>$total_pin_sold,'Card.pin_card_remain_count'=>$remian_count),array('Card.c_id'=>$val['Card']['c_id']));
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
						$update_pin = $this->Pin->updateAll(array('Pin.p_status'=>2),array('Pin.p_id'=>$pin));
						$update_pins_card = $this->PinsCard->updateAll(array('PinsCard.pc_status'=>2),array('PinsCard.pc_p_id'=>$pin));
					}
					$get_available_balance = $this->Transaction->findByUserId($this->Auth->User('id'));
					$get_available_balance['Transaction']['balance'] =  $get_available_balance['Transaction']['balance'] - $data['Sale']['s_total_sales'];
					$update_balance = $this->Transaction->updateAll(array('Transaction.balance'=>$get_available_balance['Transaction']['balance']),array('Transaction.user_id'=>$this->Auth->User('id')));
				    $this->Card->updateCounterCache(array('Card.c_id'=>$val['Card']['c_id']));
				    $this->Session->write('sale_ids',$sale_ids);
				    $this->redirect(array('controller'=>'carts','action' => 'printcard'));
				    //$this->PrintCard($sale_ids);
				}
				
				
			}
		 	
		 if(isset($save))
		 {
			if(empty($not_available_pins))
			$this->Session->setFlash(__('You have successfully purchased the cards.'), 'default', array(),'success');
		    else
			$this->Session->setFlash(__('You have successfully purchased some  cards. But these cards could not be purchased as pins are not available for these cards. '.$not_available_pins), 'default', array(),'success');
		 }
		 else
		 {
		 	if(!empty($not_available_pins))
			$this->Session->setFlash(__('These cards could not be purchased as pins are not available for these cards. '.$not_available_pins), 'default', array(),'error');
			else
			$this->Session->setFlash(__('You can not purchase the cards now.'), 'default', array(),'error');
		 }
		 $this->Session->delete('products');
		 $this->Session->delete('cart');
		 $this->redirect(array('controller'=>'Searches','action' => 'index'));	
		}
		else
		{
			$this->Session->delete('cart');
			$this->Session->delete('products');
			$this->Session->setFlash(__('No Card Selected'), 'default', array(),'error');
			$this->redirect(array('controller'=>'Searches','action' => 'index'));
		}
	}
	public function printcard()
	{
		$language=Configure::read('Config.language');
		//echo $language;
		//exit;
		$sales_id1 = $this->Session->read('sale_ids');
		$sales_ids_str="''";
		$this->loadModel('CardsSale');
		$this->loadModel('Card');
		if(count($sales_id1)!=0){
			$sales_ids_str = implode(',',$sales_id1); 
		}
		$this->Card->unBindModel(array('hasMany' => array('PinsCard')));
		$order_ids  = $this->CardsSale->find('all',array('conditions'=>'CardsSale.cs_id IN ('.$sales_ids_str.')','recursive'=>'2'));
		$card_str ='';
		$index=0;
		if($language=='deu'){
			$index=1;
		}
		foreach($order_ids as $key => $value)
		{
			if($value['Card']['c_contact_number_2']!='' && $value['Card']['c_contact_number_1']!=''){
				$str1 = $value['Card']['c_contact_number_1'].', '.$value['Card']['c_contact_number_2'];
			}else if($value['Card']['c_contact_number_2']!='' && $value['Card']['c_contact_number_1']==''){
				$str1 = $value['Card']['c_contact_number_2'];
			}else if($value['Card']['c_contact_number_2']=='' && $value['Card']['c_contact_number_1']!=''){
				$str1 = $value['Card']['c_contact_number_1'];
			}else {
				$str1='';
			}
			
			$card_str .= '<br><table border="1" RULES=NONE FRAME=BOX cellspacing="5" style="margin: 0 auto; width:100%;" >
							<tr >
								<td colspan="2" align="center">'.$value['Card']['c_title'].'</td>
							</tr>
							<tr>
								<td colspan="2" align="center"><img src="'.IMAGE_PATH.'img/card_icons/'.$value['Card']['c_image'].'" /></td>
							</tr>
							<tr>
								<td align="center">Toll Free : </td>
								<td align="left">'.$str1.'</td>
							</tr>
							<tr>
								<td align="center">Toll Free : </td>
								<td align="left">'.$value['Card']['c_local_number_1'].','.$value['Card']['c_local_number_2'].','.$value['Card']['c_local_number_3'].'</td>
							</tr>
							<tr>
								<td align="center">Pin Number</td>
								<td ></td>
							</tr>';
						foreach($value['CardsPinsSale'] as $key1 => $value1){
							$card_str .=	'<tr><td colspan="2" align="center">'.$value1['Pin']['p_pin'].'</td></tr>';
						}
						$card_str .=	'	<tr>
								<td align="center">SMS Number</td>
								<td ></td>
							</tr>
							<tr>
								<td colspan="2" align="center">'.$value['Card']['c_local_number_2'].'</td>
								
							</tr>
								
							<tr>
								<td align="center">'.$value['Card']['CardsFreeText'][$index]['cf_freetext1'].'</td>
								<td ></td>
							</tr>
							<tr>
								<td align="center">'.$value['Card']['CardsFreeText'][$index]['cf_freetext2'].'</td>
								<td ></td>
							</tr>
							<tr>
								<td align="center">'.$value['Card']['CardsFreeText'][$index]['cf_freetext3'].'</td>
								<td ></td>
							</tr>
							<tr>
								<td align="center">'.$value['Card']['CardsFreeText'][$index]['cf_freetext4'].'</td>
								<td ></td>
							</tr>
							<tr>
								<td align="center">'.$value['Card']['CardsFreeText'][$index]['cf_freetext5'].'</td>
										<td ></td>
							</tr>
							<tr>
								<td align="center">'.$value['Card']['CardsFreeText'][$index]['cf_freetext6'].'</td>
										<td ></td>
							</tr>
							<tr>
								<td colspan="2" align="center">'.$value['Card']['c_webpage'].'</td>
									
							</tr>
							<tr>
								<td colspan="2" align="center">Website Name----YYYY---sdfsf</td>
									
							</tr>
		
					</table>';
		}
		//$this->Session->delete('sale_ids');
		$this->set("card_str",$card_str);
	}
	
}