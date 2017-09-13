<?php
App::uses('AppController', 'Controller');

class TransactionsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('');
	}
	
    public function admin_manage_fund($mediator_id = 0){
		if($this->Auth->User('role_id') == 1)
		{
	  	$this->set('title_for_layout',__('Manage Mediator Fund'));
		}
		else
		{
	  	$this->set('title_for_layout',__('Manage Retailer Fund'));
		}
		
		$this->loadModel('User');
		$mediator_data = $this->User->find('all',array('conditions'=>array('status'=>array('1'),'role_id'=>2),'order'=>'fname,lname asc'));
	    $mediator_list =array();
	 	$mediator_list[0] =__('All');
		foreach($mediator_data as $data)
		{
			$mid = $data['User']['id'];
			$name = ucwords($data['User']['fname']." ".$data['User']['lname']);
		    $mediator_list[$mid] = $name;
		}
		$this->set('mediator_list',$mediator_list);
		$this->set('mediator_id',$mediator_id);
	}
	
	public function admin_generategrid($mediator_id = NULL)
	{
		$page  = $this->request->query['page'];
		$limit = $this->request->query['rows'];
		$sidx  = $this->request->query['sidx'];
		$sord  = $this->request->query['sord'];
		
		$this->set('mediator_id',$mediator_id);
		if(!$sidx) $sidx =1;
		$order_by = $sidx.' '.$sord;
        $this->loadModel('Users');
		$conditions = array();
		$conditions['User.status'] = array('1','2');
		if($mediator_id)
		{
			$conditions['Transaction.user_id'] = $mediator_id;
		}
		
		if($this->Auth->User('role_id') == 1)
		{
			$conditions['Transaction.role_id'] = '2';
		}
		else
		{
  			$conditions['Transaction.role_id'] = '3';
		}
		
		if($this->Auth->User('role_id') == 2)
		{
			$conditions['User.added_by'] = $this->Auth->User('id');
		}
    
		if(isset($this->request->query['filters']))
		{
			$filters = json_decode($this->request->query['filters'], true);
			foreach($filters['rules'] as $each_filter)
			{
			
				if($each_filter['field'] == 'id'){
					$conditions['Transaction.id LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'Transaction.total_amount'){
					$conditions['Transaction.total_amount LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'minimum_balance'){
					$conditions['User.minimum_balance LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'balance'){
					$conditions['Transaction.balance LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
				}
			
				if($each_filter['field'] == 'fname'){
					$conditions['User.fname LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}
				
				if($each_filter['field'] == 'mname'){
					$conditions['User.mname LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}
				
				if($each_filter['field'] == 'lname'){
					$conditions['User.lname LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}

				if($each_filter['field'] == 'email'){
					$conditions['User.email LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
				}
			}
		}
		
		$count = $this->Transaction->find('count',array(
				'recursive' => -1,
				'joins'=>array(
										array(
												'table' => 'ecom_users',
												'alias' => 'User',
												'type' => 'left',
												'conditions' => array('Transaction.user_id=User.id')
										)
									), 
			  	'conditions' => $conditions,
			)
		);
		
		if($count >0)
		{ 
			$total_pages = ceil($count/$limit); 
		}
		else
		{
			$total_pages = 0; 
		}
		
		if ($page > $total_pages) 
		{
			$page=$total_pages;
		}
		
		$start = $limit*$page - $limit; 
		$Transaction = $this->Transaction->find('all', array(
			'recursive' => -1, //int
			'joins'=>array(
										array(
												'table' => 'ecom_users',
												'alias' => 'User',
												'type' => 'left',
												'conditions' => array('Transaction.user_id=User.id')
										)
									),
			'conditions' => $conditions,
			'order' => $order_by,
			'limit' => $limit,
			'offset' => $start,
			'fields'=>array('Transaction.*','User.fname','User.lname','User.email','User.image','User.status','User.minimum_balance'),
		));
    
		$responce = new stdClass();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count; 
		
		$i=0;
		
		if(is_array($Transaction))
		{
			foreach($Transaction as $trans)
			{
               // App::import('Helper', 'Image'); 
				//$image = new ImageHelper(new View());
				
				$first_name =ucwords(strtolower($trans['User']['fname']));
				$last_name  = ucwords(strtolower($trans['User']['lname']));
				$imagename = $trans['User']['image'];
				$email = $trans['User']['email'];
				if($imagename!='' && file_exists('img/users/'.$imagename))
				{
					$image_cat = '<img style="cursor:pointer;margin-top:3px;" src="'.$this->webroot.'img/users/'.$imagename.'" border="0" width="50" height="50"/>';
					//$image_cat ='<img style="cursor:pointer;margin-top:3px;" src="'.IMAGE_PATH.'image.php?image=img/users/'.$imagename.'&amp;width=50&amp;height=50"/>';
				}
				else 
				{ 
					$image_cat = '<img style="cursor:pointer;margin-top:3px;" src="'.$this->webroot.'img/users/noimage.jpg" border="0" width="35" height="35"/>';
					//$image_cat ='<img style="cursor:pointer;margin-top:3px;" src="'.IMAGE_PATH.'image.php?image=img/users/noimage.jpg&amp;width=35&amp;height=35"/>';
				}
				
			  // View Reailer List
				$manage_transaction = Router::url(array('controller'=>'Transactions','action'=>'manage_transaction','admin'=>true,$trans['Transaction']['id']));
				if($trans['User']['status'] == 1)
				{
					$link_manage_transaction = '<a  class="grid_link" href="'.$manage_transaction.'" style="color:#222222 ;" class="link_color">View Allocation</a>';
				}
				else if($trans['User']['status'] == 2)
				{
					//'.$this->frontContImage('detail.png','View').'
					$link_manage_transaction = '<a title="User Disabled" class="grid_link" style="opacity:0.6;color:#222222;" class="link_color">View Allocation</a>';
				}
				else
				{
					$link_manage_transaction = '<a title="User Deleted" class="grid_link" style="opacity:0.6;color:#222222 ;" class="link_color">View Allocation</a>';
				}
				$action = $link_manage_transaction;

				$total_amount =$trans['Transaction']['total_amount'];
				$balance =$trans['Transaction']['balance'];
				$updated = date('d.m.Y',strtotime($trans['Transaction']['updated']));
				$minimum_balance = $trans['User']['minimum_balance'];					
				$responce->rows[$i]['id']=$trans['Transaction']['id'];
				$responce->rows[$i]['cell']=array($trans['Transaction']['id'],$first_name,$last_name,$total_amount,$balance,$minimum_balance,$updated,$action); 
				$i++; 
			} 
		}
		echo json_encode($responce); exit;
	} 

  public function admin_manage_transaction($id = NULL){
  	
	  if(!$id)
		{
			$this->Session->setFlash(__('Invalid Id.'), 'default', array('class' => 'error'));
  		$this->redirect(array('controller'=>'Transactions','action' => 'manage_fund'));
		}
		else
		{
			$this->set('transaction_parent_id',$id);
			$get_main_transaction = $this->Transaction->findById($id);
			if($get_main_transaction)
			{
				$mediator_id = $get_main_transaction['Transaction']['user_id'];
				$this->loadModel('User');
				$mediator_data = $this->User->findById($mediator_id);
			  $mediator_name = ucwords($mediator_data['User']['fname']." ".$mediator_data['User']['lname']);
			  $this->set('title_for_layout',__('Fund Allocation For ').$mediator_name);
			}
			else
			{
				$this->set('title_for_layout',__('Fund Allocation'));
			}
		}
	}
	
	public function admin_transaction_details_generategrid($transaction_parent_id = NULL)
	{
		$page  = $this->request->query['page'];
		$limit = $this->request->query['rows'];
		$sidx  = $this->request->query['sidx'];
		$sord  = $this->request->query['sord'];
		
		if(!$sidx) $sidx =1;
		$order_by = $sidx.' '.$sord;
	  
		$this->loadModel('Users');
		$this->loadModel('FundAllocate');
		
		$conditions = array();
		
		$conditions['FundAllocate.parent_id'] = $transaction_parent_id;
    
		if(isset($this->request->query['filters']))
		{
			$filters = json_decode($this->request->query['filters'], true);
			foreach($filters['rules'] as $each_filter)
			{
			
				if($each_filter['field'] == 'id'){
					$conditions['FundAllocate.id LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'total_amount'){
					$conditions['FundAllocate.total_amount LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'bank_name'){
					$conditions['FundAllocate.bank_name LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
				}

				if($each_filter['field'] == 'check_number'){
						$conditions['FundAllocate.check_number LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
				}
	
				if($each_filter['field'] == 'previous_balance'){
					$conditions['FundAllocate.previous_balance LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
				}
			}
		}
		
		$count = $this->FundAllocate->find('count',array(
				'recursive' => -1, 
			  	'conditions' => $conditions,
			)
		);
		
		if($count >0)
		{ 
			$total_pages = ceil($count/$limit); 
		}
		else
		{
			$total_pages = 0; 
		}
		
		if ($page > $total_pages) 
		{
			$page=$total_pages;
		}
		
		$start = $limit*$page - $limit; 
		$FundAllocate = $this->FundAllocate->find('all', array(
			'recursive' => -1, //int
			'joins'=>array(
										array(
												'table' => 'ecom_transactions',
												'alias' => 'Transaction',
												'type' => 'left',
												'conditions' => array('FundAllocate.parent_id=Transaction.id')
										)
									),
			'conditions' => $conditions,
			'order' => $order_by,
			'limit' => $limit,
			'offset' => $start,
			'fields'=>array('FundAllocate.*'),
		));
    
		$responce = new stdClass();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count; 
		
		$i=0;
		
		
		if(is_array($FundAllocate))
		{
	
			foreach($FundAllocate as $trans)
			{
                //App::import('Helper', 'Image'); 
				//$image = new ImageHelper(new View());
				
				$main_transaction_id  = ucwords(strtolower($trans['FundAllocate']['parent_id']));
				$payment_mode  = $trans['FundAllocate']['payment_mode'];
				if($payment_mode == 1)
				{
					$payment_mode = __('Cash');
				  $bank_name = __('N/A');
					$check_number = __('N/A');
				}
				else if($payment_mode == 2)
				{
					$payment_mode = __('Check');
					$bank_name = $trans['FundAllocate']['bank_name'];
					$check_number = $trans['FundAllocate']['check_number'];
				}
				else
				{
					$payment_mode = __('Other');
				  $bank_name = __('N/A');
					$check_number = __('N/A');
				}
				
				$total_amount =$trans['FundAllocate']['total_amount'];
				$previous_balance = $trans['FundAllocate']['previous_balance'];
			    $remarks = ucwords($trans['FundAllocate']['remarks']);
				$created = date('d.m.Y',strtotime($trans['FundAllocate']['created']));
				
				$responce->rows[$i]['id']=$trans['FundAllocate']['id'];
				$responce->rows[$i]['cell']=array($trans['FundAllocate']['id'],$payment_mode,$transaction_parent_id,$bank_name,$check_number,$previous_balance,$total_amount, $created,$remarks); 
		
				$i++; 
			} 
		}
		echo json_encode($responce); exit;
	} 
	
	public function admin_add_fund_parent(){
			$this->set('title_for_layout',__('Add Fund For Mediator'));
			$this->loadModel('User');
			$this->loadModel('EmailContent');
			$mediator_data = $this->User->find('all',array('conditions'=>array('status'=>1,'role_id'=>2),'order'=>'fname,lname asc'));
			$mediator_list =array();
			foreach($mediator_data as $data)
			{
				$mid = $data['User']['id'];
				$name = ucwords($data['User']['fname']." ".$data['User']['lname']);
				$mediator_list[$mid] = $name;
			}
			$this->set('mediator_list',$mediator_list);
			
			if($this->request->data)
			{
				if($this->request->data['Transaction']['payment_mode'] != 2)
				{
					unset($this->request->data['Transaction']['bank_name']);
					unset($this->request->data['Transaction']['check_number']);
				}
  			
				$get_main_transaction = $this->Transaction->findByUserId($this->request->data['Transaction']['user_id']);		
			    // First Allocation For Mediator
				if(empty($get_main_transaction))
				{	
					$new_transaction = array();
					$new_transaction['Transaction']['user_id'] = $this->request->data['Transaction']['user_id'];
					
					$new_transaction['Transaction']['allocator_id'] = $this->Auth->User('id');
					$new_transaction['Transaction']['total_amount'] = $this->request->data['Transaction']['total_amount'];
					$new_transaction['Transaction']['balance'] = $this->request->data['Transaction']['total_amount'];
					$new_transaction['Transaction']['role_id'] = 2;
					$new_transaction['Transaction']['created'] = date('Y-m-d H:i:s');
					$new_transaction['Transaction']['updated'] = date('Y-m-d H:i:s');
					
					$this->Transaction->create();
					$new_record = $this->Transaction->save($new_transaction);
				    if($new_record)
					{
						$new_fundallocate =array();
						$new_fundallocate['FundAllocate']['remarks'] = $this->request->data['Transaction']['remarks'];
						$new_fundallocate['FundAllocate']['parent_id'] = $new_record['Transaction']['id'];
						$new_fundallocate['FundAllocate']['total_amount'] = $new_record['Transaction']['total_amount'];
						$new_fundallocate['FundAllocate']['previous_balance'] ='0.00';
						$new_fundallocate['FundAllocate']['created'] =date('Y-m-d H:i:s');
						$new_fundallocate['FundAllocate']['payment_mode'] =$this->request->data['Transaction']['payment_mode'];

					    if($this->request->data['Transaction']['payment_mode'] == 2)
						{
							$new_fundallocate['FundAllocate']['bank_name'] =   $this->request->data['Transaction']['bank_name'];
							$new_fundallocate['FundAllocate']['check_number'] = $this->request->data['Transaction']['check_number'];
						}
						$this->loadModel('FundAllocate');
						$this->FundAllocate->create();
						$update_fundallocate = $this->FundAllocate->save($new_fundallocate);
					    if($update_fundallocate)
						{
							  /* Mail To Mediator + Distributor On Fund Allocation*/
						 $mediator_current_balance = 	$new_record['Transaction']['total_amount']; 
						 $mediator_previous_balance =0;
						 $mediator_id = $new_record['Transaction']['user_id'];
						 $get_mediator_data = $this->User->findById($mediator_id);
						 $mediators_minimum_balance = $get_mediator_data['User']['minimum_balance'];
						 $mediators_name = ucwords($get_mediator_data['User']['fname']." ".$get_mediator_data['User']['lname']);
						 $mediators_email = $get_mediator_data['User']['email'];
						 
						 $distributor_id = $this->Auth->User('id');
						 $distributor_name = ucwords($this->Auth->User('fname')." ".$this->Auth->User('lname'));
						 $distributor_email = $this->Auth->User('email');
						 
						 $allocated_amount = $update_fundallocate['FundAllocate']['total_amount'];
						 $allocation_date = date('Y-m-d H:i:s');
						 /*Mail To Distributr*/
						 if($distributor_email)
						 $this->EmailContent->_DistributorFundAllocateMail($mediators_email,$mediators_name,$mediator_previous_balance,$mediator_current_balance,$allocated_amount,$distributor_name,$distributor_email,$allocation_date);
						 /*Mail To Mediator*/
						  if($mediators_email)
						 $this->EmailContent->_DistributorFundAllocateMailMediator($mediators_email,$mediators_name,$mediator_previous_balance,$mediator_current_balance,$allocated_amount,$distributor_name,$distributor_email,$allocation_date);
						 
						 if($mediator_current_balance < $get_mediator_data['User']['minimum_balance'])
						 {
							 if($mediators_email)
							 $this->EmailContent->_MediatorBalnceFallingMinimum($mediators_email,$mediators_name,$mediator_current_balance,$get_mediator_data['User']['minimum_balance']); 
						 }

							$this->Session->setFlash(__('Fund has been added successfully.'), 'default', array('class' => 'success'));
      				        //$this->redirect(array('controller'=>'Transactions','action' => 'manage_fund','admin'=>true));
						}
						else{
							if(isset($this->FundAllocate->validationErrors['total_amount'][0]) && !empty($this->FundAllocate->validationErrors['total_amount'][0])){
							$this->Session->setFlash(__('Fund could not be added now as '.$this->FundAllocate->validationErrors['total_amount'][0]), 'default', array('class' => 'error'));
								}else{
									$this->Session->setFlash(__('Fund could not be added now.'), 'default', array('class' => 'error'));		
								}
						    return;
						}
					}
					else
					{
							if(isset($this->FundAllocate->validationErrors['total_amount'][0]) && !empty($this->FundAllocate->validationErrors['total_amount'][0])){
							$this->Session->setFlash(__('Fund could not be added now as '.$this->FundAllocate->validationErrors['total_amount'][0]), 'default', array('class' => 'error'));
						}else{		
							$this->Session->setFlash(__('Fund could not be added now.'), 'default', array('class' => 'error'));
						}
  						return;
					}
				}
				else
				{
					$parent_id = $get_main_transaction['Transaction']['id'];
					$new_fundallocate =array();
					$new_fundallocate['FundAllocate']['parent_id'] = $parent_id;
					$new_fundallocate['FundAllocate']['total_amount'] = $this->request->data['Transaction']['total_amount'];
					$new_fundallocate['FundAllocate']['remarks'] = $this->request->data['Transaction']['remarks'];
					$new_fundallocate['FundAllocate']['previous_balance'] =$get_main_transaction['Transaction']['balance'];
					$new_fundallocate['FundAllocate']['created'] =date('Y-m-d H:i:s');
					$new_fundallocate['FundAllocate']['payment_mode'] =$this->request->data['Transaction']['payment_mode'];

					if($this->request->data['Transaction']['payment_mode'] == 2)
					{
						$new_fundallocate['FundAllocate']['bank_name'] =$this->request->data['Transaction']['bank_name'];
						$new_fundallocate['FundAllocate']['check_number'] =$this->request->data['Transaction']['check_number'];
					}
					$this->loadModel('FundAllocate');
					$this->FundAllocate->create();
					$update_fundallocate = $this->FundAllocate->save($new_fundallocate);
                    $mediator_previous_balance = $get_main_transaction['Transaction']['balance'];
					if($update_fundallocate)
					{
					 $get_main_transaction['Transaction']['balance'] = $get_main_transaction['Transaction']['balance'] + $update_fundallocate['FundAllocate']['total_amount'];	
					 $get_main_transaction['Transaction']['total_amount'] = $get_main_transaction['Transaction']['total_amount'] + $update_fundallocate['FundAllocate']['total_amount'];	
 					 $get_main_transaction['Transaction']['updated'] = date('Y-m-d H:i:s');	

					 $update_parent = $this->Transaction->save($get_main_transaction);
					 if($update_parent)
					 {
					      /* Mail To Mediator + Distributor On Fund Allocation*/
						 $mediator_current_balance = 	$get_main_transaction['Transaction']['balance']; 
						 $mediator_id = $get_main_transaction['Transaction']['user_id'];
						 $get_mediator_data = $this->User->findById($mediator_id);
						 $mediators_minimum_balance = $get_mediator_data['User']['minimum_balance'];
						 $mediators_name = ucwords($get_mediator_data['User']['fname']." ".$get_mediator_data['User']['lname']);
						 $mediators_email = $get_mediator_data['User']['email'];
						 
						 $distributor_id = $this->Auth->User('id');
						 $distributor_name = ucwords($this->Auth->User('fname')." ".$this->Auth->User('lname'));
						 $distributor_email = $this->Auth->User('email');
						 
						 $allocated_amount = $update_fundallocate['FundAllocate']['total_amount'];
						 $allocation_date = date('Y-m-d H:i:s');
						 /*Mail To Distributr*/
						 if($distributor_email)
						 $this->EmailContent->_DistributorFundAllocateMail($mediators_email,$mediators_name,$mediator_previous_balance,$mediator_current_balance,$allocated_amount,$distributor_name,$distributor_email,$allocation_date);
						 /*Mail To Mediator*/
						 if($mediators_email)
						 $this->EmailContent->_DistributorFundAllocateMailMediator($mediators_email,$mediators_name,$mediator_previous_balance,$mediator_current_balance,$allocated_amount,$distributor_name,$distributor_email,$allocation_date);
						 
						 if($mediator_current_balance < $get_mediator_data['User']['minimum_balance'])
						 {
							 if($mediators_email)
							 $this->EmailContent->_MediatorBalnceFallingMinimum($mediators_email,$mediators_name,$mediator_current_balance,$get_mediator_data['User']['minimum_balance']); 
						 }

						 $this->Session->setFlash(__('Fund has been added successfully.'), 'default', array('class' => 'success'));
  	   				     //$this->redirect(array('controller'=>'Transactions','action' => 'manage_fund','admin'=>true));
					 }
					 else
					 {
						 if(isset($this->FundAllocate->validationErrors['total_amount'][0]) && !empty($this->FundAllocate->validationErrors['total_amount'][0])){
							$this->Session->setFlash(__('Fund could not be added now as '.$this->FundAllocate->validationErrors['total_amount'][0]), 'default', array('class' => 'error'));
						}else{
							$this->Session->setFlash(__('Fund could not be added now.'), 'default', array('class' => 'error'));
						}
					    return;
					 }
					}else
					{	
						if(isset($this->FundAllocate->validationErrors['total_amount'][0]) && !empty($this->FundAllocate->validationErrors['total_amount'][0])){
							$this->Session->setFlash(__('Fund could not be added now as '.$this->FundAllocate->validationErrors['total_amount'][0]), 'default', array('class' => 'error'));
						}else{
							$this->Session->setFlash(__('Fund could not be added now.'), 'default', array('class' => 'error'));
						}
					  return;
					}
				}
			   $this->redirect(array('controller'=>'Transactions','action' => 'add_fund_parent','admin'=>true));	
			   
			}
	}
   
	public function admin_manage_fund_retailer($retailer_id = 0){
		
		$this->set('title_for_layout',__('Manage Fund'));
		$this->loadModel('User');
		$retailer_data = $this->User->find('all',array('conditions'=>array('status'=>1,'role_id'=>3,'added_by'=>$this->Auth->User('id')),'order'=>'fname,lname asc'));
	    $retailer_list =array();
		$retailer_list[0] =__('All');
		foreach($retailer_data as $data)
		{
			$rid = $data['User']['id'];
			$name = ucwords($data['User']['fname']." ".$data['User']['lname']);
		  $retailer_list[$rid] = $name;
		}
		
		// Get Current Balance To Allocate
		$get_transaction_data = $this->Transaction->findByUserId($this->Auth->User('id'));
		if($get_transaction_data)
		{
			$balance = $get_transaction_data['Transaction']['balance'];
			$total_amount_funded =  $get_transaction_data['Transaction']['total_amount'];
		}
		else
		{
			$balance = 0;
			$total_amount_funded = 0 ;
		}
		
		$this->set('retailer_list',$retailer_list);
		$this->set('retailer_id',$retailer_id);
		$this->set('balance',$balance);
		$this->set('total_amount_funded',$total_amount_funded);
	}
	
	public function admin_generategrid_retailer($retailer_id = NULL)
	{
		$page  = $this->request->query['page'];
		$limit = $this->request->query['rows'];
		$sidx  = $this->request->query['sidx'];
		$sord  = $this->request->query['sord'];
		
		if(!$sidx) $sidx =1;
		$order_by = $sidx.' '.$sord;
	  
		$this->loadModel('Users');
		$conditions = array();
		
		if($retailer_id)
		{
			$conditions['Transaction.user_id'] = $retailer_id;
		}
		$conditions['Transaction.role_id'] = '3';
		
		$conditions['User.added_by'] = $this->Auth->User('id');
		$conditions['User.status'] = array('1','2');
    
		if(isset($this->request->query['filters']))
		{
			$filters = json_decode($this->request->query['filters'], true);
			foreach($filters['rules'] as $each_filter)
			{
			
				if($each_filter['field'] == 'id'){
					$conditions['Transaction.id LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'total_amount'){
					$conditions['Transaction.total_amount LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'minimum_balance'){
					$conditions['User.minimum_balance LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'balance'){
					$conditions['Transaction.balance LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
			
				if($each_filter['field'] == 'fname'){
					$conditions['User.fname LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}
				
				if($each_filter['field'] == 'mname'){
					$conditions['User.mname LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}
				
				if($each_filter['field'] == 'lname'){
					$conditions['User.lname LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}

				if($each_filter['field'] == 'email'){
					$conditions['User.email LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
			}
		}
		
		$count = $this->Transaction->find('count',array(
				'recursive' => -1,
				'joins'=>array(
										array(
												'table' => 'ecom_users',
												'alias' => 'User',
												'type' => 'left',
												'conditions' => array('Transaction.user_id=User.id')
										)
									), 
			  	'conditions' => $conditions,
			)
		);
		
		if($count >0)
		{ 
			$total_pages = ceil($count/$limit); 
		}
		else
		{
			$total_pages = 0; 
		}
		
		if ($page > $total_pages) 
		{
			$page=$total_pages;
		}
		
		$start = $limit*$page - $limit; 
		$Transaction = $this->Transaction->find('all', array(
			'recursive' => -1, //int
			'joins'=>array(
										array(
												'table' => 'ecom_users',
												'alias' => 'User',
												'type' => 'left',
												'conditions' => array('Transaction.user_id=User.id')
										)
									),
			'conditions' => $conditions,
			'order' => $order_by,
			'limit' => $limit,
			'offset' => $start,
			'fields'=>array('Transaction.*','User.fname','User.lname','User.email','User.image','User.status','User.minimum_balance'),
		));
    
		$responce = new stdClass();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count; 
		
		$i=0;
		
		if(is_array($Transaction))
		{
			foreach($Transaction as $trans)
			{
                //App::import('Helper', 'Image'); 
				//$image = new ImageHelper(new View());
				
				$first_name =ucwords(strtolower($trans['User']['fname']));
				$last_name  = ucwords(strtolower($trans['User']['lname']));
				$imagename = $trans['User']['image'];
				$email = $trans['User']['email'];
				if($imagename!='' && file_exists('img/users/'.$imagename))
				{
					$image_cat = '<img style="cursor:pointer;margin-top:3px;" src="'.$this->webroot.'img/users/'.$imagename.'" border="0" width="50" height="50"/>';
					//$image_cat ='<img style="cursor:pointer;margin-top:3px;" src="'.IMAGE_PATH.'image.php?image=img/users/'.$imagename.'&amp;width=50&amp;height=50"/>';
				}
				else 
				{ 
					$image_cat = '<img style="cursor:pointer;margin-top:3px;" src="'.$this->webroot.'img/users/noimage.jpg" border="0" width="35" height="35"/>';
					//$image_cat ='<img style="cursor:pointer;margin-top:3px;" src="'.IMAGE_PATH.'image.php?image=img/users/noimage.jpg&amp;width=35&amp;height=35"/>';
				}
				
			  // View Reailer List
				$manage_transaction = Router::url(array('controller'=>'Transactions','action'=>'manage_transaction','admin'=>true,$trans['Transaction']['id']));
				if($trans['User']['status'] == 1)
				{
					$link_manage_transaction = '<a class="grid_link" href="'.$manage_transaction.'" style="color:#222222 ;">View Allocation</a>';
				}
				else if($trans['User']['status'] == 2)
				{
					$link_manage_transaction = '<a title="User Disabled" class="grid_link" style="opacity:0.6;color:#222222 ;">View Allocation</a>';
				}
				else
				{
					//'.$this->frontContImage('detail.png','View').'
					$link_manage_transaction = '<a title="User Deleted" class="grid_link" style="opacity:0.6;color:#222222 ;">View Allocation</a>';
				}
				$action = $link_manage_transaction;

				$total_amount = $trans['Transaction']['total_amount'];
				$balance = $trans['Transaction']['balance'];
				$updated = date('d.m.Y',strtotime($trans['Transaction']['updated']));
				
				$minimum_balance = $trans['User']['minimum_balance'];					
				$responce->rows[$i]['id']=$trans['Transaction']['id'];
				$responce->rows[$i]['cell']=array($trans['Transaction']['id'],$first_name,$last_name,$total_amount,$balance,$minimum_balance,$updated,$action); 
				$i++; 
			} 
		}
		echo json_encode($responce); exit;
	}
	
	public function admin_add_fund_parent_retailer(){
		
			$this->set('title_for_layout',__('Add Fund For Retailer'));
			$this->loadModel('User');
			$this->loadModel('EmailContent');

			$retailer_data = $this->User->find('all',array('conditions'=>array('status'=>1,'role_id'=>3,'added_by'=>$this->Auth->User('id')),'order'=>'fname,lname asc'));
			$retailer_list =array();
			foreach($retailer_data as $data)
			{
				$rid = $data['User']['id'];
				$name = ucwords($data['User']['fname']." ".$data['User']['lname']);
				$retailer_list[$rid] = $name;
			}
			$this->set('retailer_list',$retailer_list);
			
			// Get Current Balance To Allocate
			$get_transaction_data = $this->Transaction->findByUserId($this->Auth->User('id'));
			$user_list = $this->User->find('count',array('conditions'=>array('added_by'=>$this->Auth->User('id'),'status'=>1)));
			if($get_transaction_data)
			{
				$balance = $get_transaction_data['Transaction']['balance'];
			}
			else
			{
				$balance = 0;
			}
       
			if($balance <= 0 || $user_list == 0)
			{
				if($balance <= 0 && $user_list !=0)
				{
					$this->Session->setFlash(__('You have not sufficient balance to allocate.'), 'default', array('class' => 'error'));
				}
				else if($balance <= 0 && $user_list ==0)
				{
					$this->Session->setFlash(__('You have not sufficient balance and no retailer to allocate.'), 'default', array('class' => 'error'));
				}
				else
				{
					$this->Session->setFlash(__('You have no retailer to allocate.'), 'default', array('class' => 'error'));
				}
				$this->redirect(array('controller'=>'Transactions','action' => 'manage_fund_retailer','admin'=>true));
			}
			else
			{
				$this->set('balance',$balance);
			}
			
			if($this->request->data)
			{
				if($this->request->data['Transaction']['payment_mode'] != 2)
				{
					unset($this->request->data['Transaction']['bank_name']);
					unset($this->request->data['Transaction']['check_number']);
				}
  			
				$get_main_transaction = $this->Transaction->findByUserId($this->request->data['Transaction']['user_id']);
 				$get_parent_transaction_data = $this->Transaction->findByUserId($this->Auth->User('id'));
        		if($get_main_transaction)
				{
					$retailer_balance = $get_main_transaction['Transaction']['balance'];
				}
				else
				{
					$retailer_balance = 0.00;
				}
				// First Allocation For Retailer
				if(empty($get_main_transaction))
				{
					$new_transaction = array();
					$new_transaction['Transaction']['user_id'] = $this->request->data['Transaction']['user_id'];
					$new_transaction['Transaction']['allocator_id'] = $this->Auth->User('id');
					$new_transaction['Transaction']['total_amount'] = $this->request->data['Transaction']['total_amount'];
					$new_transaction['Transaction']['balance'] = $this->request->data['Transaction']['total_amount'];
					$new_transaction['Transaction']['role_id'] = 3;
					$new_transaction['Transaction']['created'] = date('Y-m-d H:i:s');
					$new_transaction['Transaction']['updated'] = date('Y-m-d H:i:s');
					
					$this->Transaction->create();
					$new_record = $this->Transaction->save($new_transaction);
				    
					$mediator_previous_balance = 0;
  				    if($get_parent_transaction_data)
					{  
	   				    $mediator_previous_balance = 	$get_parent_transaction_data['Transaction']['balance'];
						$get_parent_transaction_data['Transaction']['balance'] = $get_parent_transaction_data['Transaction']['balance'] - $this->request->data['Transaction']['total_amount'];
					  $this->Transaction->save($get_parent_transaction_data);
					}

					if($new_record)
					{
						$new_fundallocate =array();
						$new_fundallocate['FundAllocate']['remarks'] = $this->request->data['Transaction']['remarks'];
						$new_fundallocate['FundAllocate']['parent_id'] = $new_record['Transaction']['id'];
						$new_fundallocate['FundAllocate']['total_amount'] = $new_record['Transaction']['total_amount'];
						$new_fundallocate['FundAllocate']['previous_balance'] = $retailer_balance;
						$new_fundallocate['FundAllocate']['created'] =date('Y-m-d H:i:s');
						$new_fundallocate['FundAllocate']['payment_mode'] =$this->request->data['Transaction']['payment_mode'];

					    if($this->request->data['Transaction']['payment_mode'] == 2)
						{
							$new_fundallocate['FundAllocate']['bank_name'] =$this->request->data['Transaction']['bank_name'];
							$new_fundallocate['FundAllocate']['check_number'] =$this->request->data['Transaction']['check_number'];
						}
						$this->loadModel('FundAllocate');
						$this->FundAllocate->create();
						$update_fundallocate = $this->FundAllocate->save($new_fundallocate);
					    if($update_fundallocate)
						{
						    /* Mail To Mediator + Retailer On Fund Allocation*/
						 $mediator_current_balance = 	$get_parent_transaction_data['Transaction']['balance']; 
						 $mediator_id = $get_parent_transaction_data['Transaction']['user_id'];
						 $get_mediator_data = $this->User->findById($mediator_id);
						 $mediators_minimum_balance = $get_mediator_data['User']['minimum_balance'];
						 $mediators_name = ucwords($get_mediator_data['User']['fname']." ".$get_mediator_data['User']['lname']);
						 $mediators_email = $get_mediator_data['User']['email'];
						 
						 $allocated_retailer = $new_record['Transaction']['user_id'];
						 $get_retailer_data = $this->User->findById($allocated_retailer);
						 $retailer_name = ucwords($get_retailer_data['User']['fname']." ".$get_retailer_data['User']['lname']);
						 $retailer_email = $get_retailer_data['User']['email'];
						 $allocated_amount = $update_fundallocate['FundAllocate']['total_amount'];
						 $allocation_date = date('Y-m-d H:i:s');
						 
						 if($mediators_email) 
						 $this->EmailContent->_MediatorFundAllocateMail($mediators_email,$mediators_name,$mediator_previous_balance,$mediator_current_balance,$allocated_amount,$retailer_name,$retailer_email,$allocation_date);
						 
						 $retailer_total_balance = $new_record['Transaction']['balance'];
						 
						 $retailer_previous_balance = 0.00;
    				 	 if($retailer_email) 
						 $this->EmailContent->_MediatorFundAllocateMailRetailer($mediators_email,$mediators_name,$retailer_total_balance,$retailer_previous_balance,$allocated_amount,$retailer_name,$retailer_email,$allocation_date);
						 
					 /* Mediators Current Balance Falling To Less Than Equal To Minimum Balance Defined*/
					 if($mediator_current_balance < $get_mediator_data['User']['minimum_balance'])
					 {
						if($mediators_email) 
					  $this->EmailContent->_MediatorBalnceFallingMinimum($mediators_email,$mediators_name,$mediator_current_balance,$get_mediator_data['User']['minimum_balance']); 
					 }
						 
					 $this->Session->setFlash(__('Fund has been added successfully.'), 'default', array('class' => 'success'));
 				     //$this->redirect(array('controller'=>'Transactions','action' => 'manage_fund_retailer','admin'=>true));
					}
					else
					{
						if(isset($this->FundAllocate->validationErrors['total_amount'][0]) && !empty($this->FundAllocate->validationErrors['total_amount'][0])){
							$this->Session->setFlash(__('Fund could not be added now as '.$this->FundAllocate->validationErrors['total_amount'][0]), 'default', array('class' => 'error'));
						}else{
							$this->Session->setFlash(__('Fund could not be added now.'), 'default', array('class' => 'error'));
						}
					    return;
					}
					}
					else
					{
						if(isset($this->FundAllocate->validationErrors['total_amount'][0]) && !empty($this->FundAllocate->validationErrors['total_amount'][0])){
							$this->Session->setFlash(__('Fund could not be added now as '.$this->FundAllocate->validationErrors['total_amount'][0]), 'default', array('class' => 'error'));
						}else{
							$this->Session->setFlash(__('Fund could not be added now.'), 'default', array('class' => 'error'));
						}
					    return;
					}
				}
				else
				{
					$parent_id = $get_main_transaction['Transaction']['id'];
					$new_fundallocate =array();
					$new_fundallocate['FundAllocate']['remarks'] = $this->request->data['Transaction']['remarks'];
					$new_fundallocate['FundAllocate']['parent_id'] = $parent_id;
					$new_fundallocate['FundAllocate']['total_amount'] = $this->request->data['Transaction']['total_amount'];
					$new_fundallocate['FundAllocate']['previous_balance'] = $get_main_transaction['Transaction']['balance'];
					$new_fundallocate['FundAllocate']['created'] =date('Y-m-d H:i:s');
					$new_fundallocate['FundAllocate']['payment_mode'] =$this->request->data['Transaction']['payment_mode'];

					if($this->request->data['Transaction']['payment_mode'] == 2)
					{
						$new_fundallocate['FundAllocate']['bank_name'] =$this->request->data['Transaction']['bank_name'];
						$new_fundallocate['FundAllocate']['check_number'] =$this->request->data['Transaction']['check_number'];
					}
					$this->loadModel('FundAllocate');
					$this->FundAllocate->create();
					
					$update_fundallocate = $this->FundAllocate->save($new_fundallocate);
				    $retailer_previous_balance = 0.00;
					if($update_fundallocate)
					{
                     //Updating Retailers Account
                     $retailer_previous_balance = $get_main_transaction['Transaction']['balance'];
					 $get_main_transaction['Transaction']['balance'] = $get_main_transaction['Transaction']['balance'] + $update_fundallocate['FundAllocate']['total_amount'];	
        	         $get_main_transaction['Transaction']['total_amount'] = $get_main_transaction['Transaction']['total_amount'] + $update_fundallocate['FundAllocate']['total_amount'];	
 					 $get_main_transaction['Transaction']['updated'] = date('Y-m-d H:i:s');	
					 $update_parent = $this->Transaction->save($get_main_transaction);
                      
					 // Updating Mediators Account
				     $mediator_previous_balance = 	$get_parent_transaction_data['Transaction']['balance'];
					 $get_parent_transaction_data['Transaction']['balance'] = $get_parent_transaction_data['Transaction']['balance'] - $update_fundallocate['FundAllocate']['total_amount'];	
 					 $get_parent_transaction_data['Transaction']['updated'] = date('Y-m-d H:i:s');	
					 $update_main_parent = $this->Transaction->save($get_parent_transaction_data);
                     
					 // Email To Mediator && Retailer On Fund Allocation By Mediator
					 if($update_main_parent) 
					 {
						 $mediator_current_balance = 	$get_parent_transaction_data['Transaction']['balance']; 
						 $mediator_id = $get_parent_transaction_data['Transaction']['user_id'];
						 $get_mediator_data = $this->User->findById($mediator_id);
						 $mediators_minimum_balance = $get_mediator_data['User']['minimum_balance'];
						 $mediators_name = ucwords($get_mediator_data['User']['fname']." ".$get_mediator_data['User']['lname']);
						 $mediators_email = $get_mediator_data['User']['email'];
						 
						 $allocated_retailer = $get_main_transaction['Transaction']['user_id'];
						 $get_retailer_data = $this->User->findById($allocated_retailer);
						 $retailer_name = ucwords($get_retailer_data['User']['fname']." ".$get_retailer_data['User']['lname']);
						 $retailer_email = $get_retailer_data['User']['email'];
						 $allocated_amount = $update_fundallocate['FundAllocate']['total_amount'];
						 $allocation_date = date('Y-m-d H:i:s');
						 // Mail To Mediator
						  if($mediators_email)
						 $this->EmailContent->_MediatorFundAllocateMail($mediators_email,$mediators_name,$mediator_previous_balance,$mediator_current_balance,$allocated_amount,$retailer_name,$retailer_email,$allocation_date);
						 
						 $retailer_total_balance = $get_main_transaction['Transaction']['balance'];
						 //Mail To Retailer
						  if($retailer_email)
    				 	 $this->EmailContent->_MediatorFundAllocateMailRetailer($mediators_email,$mediators_name,$retailer_total_balance,$retailer_previous_balance,$allocated_amount,$retailer_name,$retailer_email,$allocation_date);
					     /* Mediators Current Balance Falling To Less Than Equal To Minimum Balance Defined*/
						 if($mediator_current_balance < $get_mediator_data['User']['minimum_balance'])
						 {
							 if($mediators_email)
							 $this->EmailContent->_MediatorBalnceFallingMinimum($mediators_email,$mediators_name,$mediator_current_balance,$get_mediator_data['User']['minimum_balance']); 
						 }
					 }
					 if($update_parent && $update_main_parent)
					 {
							$this->Session->setFlash(__('Fund has been added successfully.'), 'default', array('class' => 'success'));
  	   				      //  $this->redirect(array('controller'=>'Transactions','action' => 'manage_fund_retailer','admin'=>true));
					 }
					 else
					 {
						 if(isset($this->FundAllocate->validationErrors['total_amount'][0]) && !empty($this->FundAllocate->validationErrors['total_amount'][0])){
							$this->Session->setFlash(__('Fund could not be added now as '.$this->FundAllocate->validationErrors['total_amount'][0]), 'default', array('class' => 'error'));
						}else{
							$this->Session->setFlash(__('Fund could not be added now.'), 'default', array('class' => 'error'));
						}
					    return;
					 }
					}
					else
					{
						if(isset($this->FundAllocate->validationErrors['total_amount'][0]) && !empty($this->FundAllocate->validationErrors['total_amount'][0])){
							$this->Session->setFlash(__('Fund could not be added now as '.$this->FundAllocate->validationErrors['total_amount'][0]), 'default', array('class' => 'error'));
						}else{
							$this->Session->setFlash(__('Fund could not be added now.'), 'default', array('class' => 'error'));
						}
					    return;
					}
				}
			 
			 $this->redirect(array('controller'=>'Transactions','action' => 'add_fund_parent_retailer','admin'=>true));
			}
	}  
	
	public function admin_delete_fund(){
		
		    $ids =$this->request->data['ids'];
			$count = count($ids);
			$count_success = 0;
			foreach($ids as $id)
			{
				$get_main_transaction = $this->Transaction->findById($id);
				$delete_record = $this->Transaction->delete($id);
		        if($delete_record)
				$count_success++;
			  
				//Assigned to Mediator and mediator's furthur funded data to retailer
				$as_allocator_user = $this->Transaction->find('all',array('conditions'=>array('allocator'=>$get_main_transaction['Transaction']['user_id'])));
			    foreach($as_allocator_user as $user)
				{
					 $delete_record_child = $this->Transaction->delete($user['Transaction']['id']);
				}
			}

			if($count_success == $count)
			echo "1";
			else if($count_success != 0)
			echo "2";
			else
			echo "3";
			exit;
	}
	
	public function admin_delete_retailer_fund(){
		  $ids =$this->request->data['ids'];
			$count = count($ids);
			$count_success = 0;
			foreach($ids as $id)
			{
				$get_main_transaction = $this->Transaction->findById($id);
				$allocator_id = $get_main_transaction['Transaction']['allocator_id'];
				$total_amount =  $get_main_transaction['Transaction']['balance'];
				$delete_record = $this->Transaction->delete($id);
		        if($delete_record)
				{
					$count_success++;
					$get_allocator_transaction = $this->Transaction->findByUserId($allocator_id);
					$get_allocator_transaction['Transaction']['balance'] = $total_amount + $get_allocator_transaction['Transaction']['balance'] ; 
					$update_main_parent = $this->Transaction->saveAll($get_allocator_transaction);
				}
			}
			if($count_success == $count)
			$this->Session->setFlash(__('Selected record(s) deleted successfully.'), 'default', array('class' => 'success'));
			else if($count_success != 0)
			$this->Session->setFlash(__('Some selected record(s) deleted successfully.'), 'default', array('class' => 'success'));
			else
			$this->Session->setFlash(__('Record(s) could not be deleted.'), 'default', array('class' => 'error'));
			exit;
	}
	
	public function admin_delete_child_fund(){
		    $this->loadModel('FundAllocate'); 
		    $ids =$this->request->data['ids'];
			$count = count($ids);
			$count_success = 0;
			$flag_parent_id = 0;
			
			foreach($ids as $id)
			{
				$get_main_transaction = $this->FundAllocate->findById($id);
				if($flag_parent_id == 0)
				{
					$parent_id = $get_main_transaction['FundAllocate']['parent_id'];
				    $flag_parent_id = 1;
				}
				
				$total_amount =  $get_main_transaction['FundAllocate']['total_amount'];
				$delete_record = $this->FundAllocate->delete($id);
		        //$delete_record = 1;
				if($delete_record)
				{
					$count_success++;
					$get_parent_transaction = $this->Transaction->findById($parent_id);
					$get_parent_transaction['Transaction']['balance'] = $get_parent_transaction['Transaction']['balance'] - $total_amount; 
					$get_parent_transaction['Transaction']['total_amount'] = $get_parent_transaction['Transaction']['total_amount'] -$total_amount; 
					$update_main_parent = $this->Transaction->saveAll($get_parent_transaction);
					
					$main_allocator =  $get_parent_transaction['Transaction']['allocator_id'];
					$main_allocator_data = $this->Transaction->findByUserId($main_allocator);
                   
					// Updating Mediator in Case Of Retaile's Sub allocation delete
					if($main_allocator_data)
					{
						$main_allocator_data['Transaction']['balance'] = $main_allocator_data['Transaction']['balance'] + $total_amount; 
						$update_main_allocator = $this->Transaction->saveAll($main_allocator_data);
					}
				}
			}
			
			
			//Updating Previous Balance
			$get_all_allocation = $this->FundAllocate->find('all',array('conditions'=>array('parent_id'=>$parent_id), 'order'=>'created asc'));
			if($get_all_allocation)
			{
				$previous_balance =0;
				foreach($get_all_allocation as $allocation)
				{
					$allocation['FundAllocate']['previous_balance'] = $previous_balance;
				    $allocation_update = $this->FundAllocate->updateAll(array('FundAllocate.previous_balance'=>$previous_balance),array('FundAllocate.id'=>$allocation['FundAllocate']['id']));
					$previous_balance = $previous_balance + $allocation['FundAllocate']['total_amount'];
				}
			}
		    
			if($count_success == $count)
			//echo "1";
			$this->Session->setFlash(__('Selected record(s) deleted successfully.'), 'default', array('class' => 'success'));
			else if($count_success != 0)
			//echo "2";
			$this->Session->setFlash(__('Some selected record(s) deleted successfully.'), 'default', array('class' => 'success'));
			else
			//echo "3";
			$this->Session->setFlash(__('Record(s) could not be deleted.'), 'default', array('class' => 'error'));
			exit;
	}
	
    public function admin_minimum_balance_retailer(){
	  $this->set('title_for_layout',__('Minimum Balance Reached Retailers'));
	  if($this->Auth->User('role_id') == 1)
		{
					$this->Session->setFlash(__('You are not authorized to access this page.'), 'default', array('class' => 'error'));				
					$this->redirect(array('controller'=>'users','action' => 'dashboard'));
		}
	}

	public function admin_generategrid_minimum_balance_retailer()
	{
		$page  = $this->request->query['page'];
		$limit = $this->request->query['rows'];
		$sidx  = $this->request->query['sidx'];
		$sord  = $this->request->query['sord'];
		
		if(!$sidx) $sidx =1;
		$order_by = $sidx.' '.$sord;
		
	
		$conditions = array();
		$conditions['Transaction.role_id'] = '3';
		$conditions['User.status'] = '1';
		$conditions['Transaction.allocator_id'] = $this->Auth->User('id');

		if(isset($this->request->query['filters']))
		{
			$filters = json_decode($this->request->query['filters'], true);
			foreach($filters['rules'] as $each_filter)
			{
				if($each_filter['field'] == 'id'){
					$conditions['Transaction.id LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
			    
				if($each_filter['field'] == 'fname'){
					$conditions['User.fname LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'lname'){
					$conditions['User.lname LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'email'){
					$conditions['User.email LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'balance'){
					$conditions['Transaction.balance LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}
				
				if($each_filter['field'] == 'minimum_balance'){
					$conditions['User.minimum_balance LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
			}
		}
		
		$count = $this->User->find('count',array(
				'recursive' => -1,
				'joins'=>array(
										array(
												'table' => 'ecom_transactions',
												'alias' => 'Transaction',
												'type' => 'left',
												'conditions' => 'Transaction.user_id = User.id and User.minimum_balance >= Transaction.Balance',
										)
									), 
			  	'conditions' => $conditions,
			)
		);
		
		if($count >0)
		{ 
			$total_pages = ceil($count/$limit); 
		}
		else
		{
			$total_pages = 0; 
		}
		
		if ($page > $total_pages) 
		{
			$page=$total_pages;
		}
		
		$start = $limit*$page - $limit; 
		$transactions = $this->User->find('all', array(
			'recursive' => -1, //int
			'joins'=>array(
										array(
												'table' => 'ecom_transactions',
												'alias' => 'Transaction',
												'type' => 'left',
												'conditions' => 'Transaction.user_id = User.id and User.minimum_balance >= Transaction.Balance',
										)
									), 
			'conditions' => $conditions,
			'order' => $order_by,
			'limit' => $limit,
			'fields'=>array('User.fname','User.lname','User.email','User.image','User.minimum_balance','Transaction.*'),
			'offset' => $start
		));
    
	    
		$responce = new stdClass();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count; 
		
		$i=0;
		
		if(is_array($transactions))
		{
			foreach($transactions as $trans)
			{
                //App::import('Helper', 'Image'); 
				//$image = new ImageHelper(new View());
				
				$first_name =ucwords(strtolower( $trans['User']['fname']));
				$last_name  = ucwords(strtolower($trans['User']['lname']));
				$imagename = $trans['User']['image'];
				$email = $trans['User']['email'];
				if($imagename!='' && file_exists('img/users/'.$imagename))
				{
					$image_cat = '<img style="cursor:pointer;margin-top:3px;" src="'.$this->webroot.'img/users/'.$imagename.'" border="0" width="50" height="50"/>';
					//$image_cat ='<img style="cursor:pointer;margin-top:3px;" src="'.IMAGE_PATH.'image.php?image=img/users/'.$imagename.'&amp;width=50&amp;height=50"/>';
				}
				else 
				{ 
					$image_cat = '<img style="cursor:pointer;margin-top:3px;" src="'.$this->webroot.'img/users/noimage.jpg" border="0" width="35" height="35"/>';
					//$image_cat ='<img style="cursor:pointer;margin-top:3px;" src="'.IMAGE_PATH.'image.php?image=img/users/noimage.jpg&amp;width=35&amp;height=35"/>';
				}
        
		        $minimum_balance = "&euro;".$trans['User']['minimum_balance'];		
				
			    $balance = "&euro;".$trans['Transaction']['balance'];
				
				$responce->rows[$i]['id']=$trans['Transaction']['id'];
				$responce->rows[$i]['cell']=array($trans['Transaction']['id'],$first_name,$last_name,$email,$image_cat,$minimum_balance,$balance); 
				$i++; 
			} 
		}
		echo json_encode($responce); exit;
	}
	
   public function admin_minimum_balance_mediator(){
	  $this->set('title_for_layout',__('Minimum Balance Reached Mediator'));
	  if($this->Auth->User('role_id') == 2)
		{
					$this->Session->setFlash(__('You are not authorized to access this page.'), 'default', array('class' => 'error'));				
					$this->redirect(array('controller'=>'users','action' => 'dashboard'));
		}
	}
	
	public function admin_generategrid_minimum_balance_mediator()
	{
		$page  = $this->request->query['page'];
		$limit = $this->request->query['rows'];
		$sidx  = $this->request->query['sidx'];
		$sord  = $this->request->query['sord'];
		
		if(!$sidx) $sidx =1;
		$order_by = $sidx.' '.$sord;
		
	
		$conditions = array();
		$conditions['Transaction.role_id'] = '2';
		$conditions['User.status'] = '1';
		$conditions['Transaction.allocator_id'] = $this->Auth->User('id');

		if(isset($this->request->query['filters']))
		{
			$filters = json_decode($this->request->query['filters'], true);
			foreach($filters['rules'] as $each_filter)
			{
				if($each_filter['field'] == 'id'){
					$conditions['Transaction.id LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
			    
				if($each_filter['field'] == 'fname'){
					$conditions['User.fname LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'lname'){
					$conditions['User.lname LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'email'){
					$conditions['User.email LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'balance'){
					$conditions['Transaction.balance LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}
				
				if($each_filter['field'] == 'minimum_balance'){
					$conditions['User.minimum_balance LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
			}
		}
		
		$count = $this->User->find('count',array(
				'recursive' => -1,
				'joins'=>array(
										array(
												'table' => 'ecom_transactions',
												'alias' => 'Transaction',
												'type' => 'left',
												'conditions' => 'Transaction.user_id = User.id and User.minimum_balance >= Transaction.Balance',
										)
									), 
			  	'conditions' => $conditions,
			)
		);
	
		if($count >0)
		{ 
			$total_pages = ceil($count/$limit); 
		}
		else
		{
			$total_pages = 0; 
		}
		
		if ($page > $total_pages) 
		{
			$page=$total_pages;
		}
		
		$start = $limit*$page - $limit; 
		
		$transactions = $this->User->find('all', array(
			'recursive' => -1, //int
			'joins'=>array(
							array(
									'table' => 'ecom_transactions',
									'alias' => 'Transaction',
									'type' => 'left',
									'conditions' => 'Transaction.user_id = User.id and User.minimum_balance >= Transaction.Balance',
							)
						), 
			'conditions' => $conditions,
			'order' => $order_by,
			'limit' => $limit,
			'fields'=>array('User.fname','User.lname','User.email','User.image','User.minimum_balance','Transaction.*'),
			'offset' => $start
		));
    
	    $responce = new stdClass();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count; 
		
		$i=0;
		
		if(is_array($transactions))
		{
			foreach($transactions as $trans)
			{
                $first_name =ucwords(strtolower( $trans['User']['fname']));
				$last_name  = ucwords(strtolower($trans['User']['lname']));
				$imagename = $trans['User']['image'];
				$email = $trans['User']['email'];
				if($imagename!='' && file_exists('img/users/'.$imagename))
				{
					$image_cat = '<img style="cursor:pointer;margin-top:3px;" src="'.$this->webroot.'img/users/'.$imagename.'" border="0" width="50" height="50"/>';
					//$image_cat ='<img style="cursor:pointer;margin-top:3px;" src="'.IMAGE_PATH.'image.php?image=img/users/'.$imagename.'&amp;width=50&amp;height=50"/>';
				}
				else 
				{ 
					$image_cat = '<img style="cursor:pointer;margin-top:3px;" src="'.$this->webroot.'img/users/noimage.jpg" border="0" width="35" height="35"/>';
					//$image_cat ='<img style="cursor:pointer;margin-top:3px;" src="'.IMAGE_PATH.'image.php?image=img/users/noimage.jpg&amp;width=35&amp;height=35"/>';
				}
        
		        $minimum_balance = "&euro;".$trans['User']['minimum_balance'];		
			    $balance = "&euro;".$trans['Transaction']['balance'];
				
				$responce->rows[$i]['id']=$trans['Transaction']['id'];
				$responce->rows[$i]['cell']=array($trans['Transaction']['id'],$first_name,$last_name,$email,$image_cat,$minimum_balance,$balance); 
				$i++; 
			} 
		}
		echo json_encode($responce); exit;
	}
}