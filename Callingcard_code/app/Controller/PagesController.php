<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */
	public function beforeFilter() {
		$this->loadModel('User');
		$this->User->virtualFields = array(
			'full_name' => "CONCAT(User.fname, ' ',User.lname)"
		);
		parent::beforeFilter();
		$this->Auth->allow('index','contactus');
	}
	public $components = array('Commonfunctions');
	public function index($keyword=NULL)
	{		
	   
		$this->loadModel('CmsImage');
		if (!$keyword){
			$this->Session->setFlash(__('Invalid content.'), 'default', array('class' => 'error'));
			$this->redirect(array('controller' => 'Pages', 'action' => 'dashboard'));
		}
		// Get values according to user language
		$this->loadModel('Cmspage');
		$language=Configure::read('Config.language');
		if(empty($language))
		$language="en";
		
		$cms_content = $this->Cmspage->find('first',array(
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'cms_languages',
					'alias' => 'CmsLanguage',
					'type' => 'INNER', 	
					'conditions' => 'Cmspage.id = CmsLanguage.cmspage_id'
				)
			),
			'conditions' => array('Cmspage.keyword LIKE' => $keyword, 'CmsLanguage.language_alias LIKE' =>$language),
			'fields' => array('Cmspage.id', 'Cmspage.keyword', 'Cmspage.title','Cmspage.image', 'CmsLanguage.id', 'CmsLanguage.language_alias', 'CmsLanguage.title', 'CmsLanguage.content')
		
		));
		//pr($cms_content);//	exit;
		if(!isset($cms_content['CmsLanguage']['content'])){
			$this->Session->setFlash(__('Invalid content.'), 'default', array(),'error');
			$this->redirect(array('controller' => 'Pages', 'action' => 'dashboard'));
		}
		
		$this->set('title_for_layout', __($cms_content['Cmspage']['title']));
		$this->set('content',$cms_content);
		
		
		$cms_image = $this->CmsImage->find('all',array(
					'conditions' =>array('CmsImage.cmspages_id =' =>$cms_content['Cmspage']['id']),
					'order'=>array('id DESC')
				)
		);
		$this->set('cms_image',$cms_image);	
	}
  
	public function dashboard($keyword = 'CMS.WELCOMECALLINGLOGIN'){
		
		$this->redirect(array('controller' => 'Searches', 'action' => 'online_card'));
		$this->set('title_for_layout',__('Dashboard'));
		$this->loadModel('CmsImage');
		if (!$keyword){
			$this->Session->setFlash(__('Invalid content.'), 'default', array('class' => 'error'));
			$this->redirect(array('controller' => 'Pages', 'action' => 'dashboard'));
		}
		// Get values according to user language
		$this->loadModel('Cmspage');
		$language=Configure::read('Config.language');
		if(empty($language))
		$language="en";
		
		$cms_content = $this->Cmspage->find('first',array(
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'cms_languages',
					'alias' => 'CmsLanguage',
					'type' => 'INNER', 	
					'conditions' => 'Cmspage.id = CmsLanguage.cmspage_id'
				)
			),
			'conditions' => array('Cmspage.keyword LIKE' => $keyword, 'CmsLanguage.language_alias LIKE' =>$language),
			'fields' => array('Cmspage.id', 'Cmspage.keyword', 'Cmspage.title','Cmspage.image', 'CmsLanguage.id', 'CmsLanguage.language_alias', 'CmsLanguage.title', 'CmsLanguage.content')
		
		));
		//pr($cms_content);//	exit;
		if(!isset($cms_content['CmsLanguage']['content'])){
			$this->Session->setFlash(__('Invalid content.'), 'default', array(),'error');
			$this->redirect(array('controller' => 'Pages', 'action' => 'dashboard'));
		}
		
		$this->set('title_for_layout', __($cms_content['Cmspage']['title']));
		$this->set('content',$cms_content);
		
		
		$cms_image = $this->CmsImage->find('all',array(
					'conditions' =>array('CmsImage.cmspages_id =' =>$cms_content['Cmspage']['id']),
					'order'=>array('id DESC')
				)
		);
		$this->set('cms_image',$cms_image);	
	}
	
	public function contactus($keyword=NULL){
		
			$this->loadModel('CmsImage');
			$this->loadModel('Cmspage');
			
			$site_address=Configure::read('Site.address');
			$zipcode=Configure::read('Site.zipcode');
			$phone=Configure::read('Site.phone');
			$fax=Configure::read('Site.fax');
			$site_email=Configure::read('Site.email');
		    $address = $site_address." ".$zipcode;
			
			$this->set('Address'," ".$site_address." ");
			$this->set('phone',$phone);
			$this->set('fax',$fax);
			$this->set('site_email',$site_email);
			
			if ($keyword == NULL || empty($keyword))
			{
				$this->Session->setFlash(__('Invalid content.'), 'default', array('class' => 'error'));
				$this->redirect(array('controller' => 'Pages', 'action' => 'dashboard'));
			}
			// Get values according to user language
		
			$language=Configure::read('Config.language');
			if(empty($language))
			$language="en";
		
			$cms_content = $this->Cmspage->find('first',array(
			'recursive' => -1,
			  'joins' => array(
				array(
					'table' => 'cms_languages',
					'alias' => 'CmsLanguage',
					'type' => 'INNER', 	
					'conditions' => 'Cmspage.id = CmsLanguage.cmspage_id'
				)
			),
			'conditions' => array('Cmspage.keyword LIKE' => $keyword, 'CmsLanguage.language_alias LIKE' =>$language),
			'fields' => array('Cmspage.id', 'Cmspage.keyword', 'Cmspage.title','Cmspage.image', 'CmsLanguage.id', 'CmsLanguage.language_alias', 'CmsLanguage.title', 'CmsLanguage.content')
			));
			if(!isset($cms_content['CmsLanguage']['content']))
			{
				$this->Session->setFlash(__('Invalid content.'), 'default', array(),'error');
				$this->redirect(array('controller' => 'Pages', 'action' => 'home'));
			}
		
			$this->set('title_for_layout', __($cms_content['Cmspage']['title']));
			$this->set('content',$cms_content);
		
			$cms_image = $this->CmsImage->find('all',array(
					'conditions' =>array('CmsImage.cmspages_id =' =>$cms_content['Cmspage']['id']),
					'order'=>array('id DESC')
				)
	  	);
		  $this->set('cms_image',$cms_image);	

			$this->set('title_for_layout',__('Contact Us'));
			$this->loadModel('ContactUs');
			
			if($this->request->is('post') || !empty($this->request->data))
			{
				$admin_data = $this->User->findByRoleId('1');
				
				$admin_name = ucwords(strtolower($admin_data['User']['fname']." ".$admin_data['User']['lname']));
                $this->loadModel('Contactus');
				if($this->Auth->user("id"))
				{
					$this->loadModel('User');
					$user_email = $this->User->find('first',array(
						'recursive' =>-1,
						'fields'=>array('email'),
						'conditions'=>array('id'=>$this->Auth->user("id"))
					));	
				    
					$email = $user_email['User']['email'];
				
					if(!empty($email))
					{
					
					  $status = 1;
					  $this->set('status',$status);
					}
					else
					{
							$this->Contactus->validator()->remove('email');
					}
			        
			        
					$this->Contactus->set($this->request->data);
					if($this->Contactus->validates())
					{
						$this->loadModel('EmailContent');
						
						if(empty($email))
						{
                           $email = $this->Auth->User('username');
						}
						
						$this->EmailContent->_ContactUs($email,Configure::read('Site.email'),trim($this->request->data['Contactus']['name']),trim($this->request->data['Contactus']['subject']),trim($this->request->data['Contactus']['message']),$admin_name);		
				
						$this->Session->setFlash(__('Mail has been sent successfully.'), 'default', array(),'success');
 						$this->redirect(array('action' => 'contactus',$keyword));									
					}
					else
					{
						
						$this->set('subject',$this->request->data['Contactus']['subject']);
					    $this->set('message',$this->request->data['Contactus']['message']);

						$get_user_data = $this->User->findById($this->Auth->User('id'));
						$this->request->data = $get_user_data;
						$name = ucwords($get_user_data['User']['fname']." ".$get_user_data['User']['lname']);
					    $this->set('name',$name);
						if($get_user_data['User']['email'])
						$this->set('email',$get_user_data['User']['email']);
					    else
					    $this->set('email',$get_user_data['User']['username']);

					   
						
					}
				}
				else
				{
					$this->loadModel('Contactus');
					$this->Contactus->set($this->request->data);
				
					if($this->Contactus->validates())
					{
						$this->loadModel('EmailContent');
						$this->EmailContent->_Contactus($this->request->data['Contactus']['email'],Configure::read('Site.email'),$this->request->data['Contactus']['name'],$this->request->data['Contactus']['subject'],trim($this->request->data['Contactus']['message']),$admin_name);		
				
						$this->Session->setFlash(__('Mail has been sent successfully.'), 'default', array(),'success');
						$this->redirect(array('action' => 'contactus',$keyword));	
					}
					else
					{
						$this->set('subject',$this->request->data['Contactus']['subject']);
					    $this->set('message',$this->request->data['Contactus']['message']);

					}

			 }
		}
		else
		{
			if($this->Auth->User('id'))
			{
				$get_user_data = $this->User->findById($this->Auth->User('id'));
				$this->request->data = $get_user_data;
				$name = ucwords($get_user_data['User']['fname']." ".$get_user_data['User']['lname']);
			    $this->set('name',$name);
				if($get_user_data['User']['email'])
				$this->set('email',$get_user_data['User']['email']);
			    else
			    $this->set('email',$get_user_data['User']['username']);
			}
			else
			{
			  $this->set('name','');
			  $this->set('email','');
			}
		}
	}
	
	//Invoice Section
	public function admin_invoice_list(){
			
			$this->loadModel('User');
			$this->set("title_for_layout",__('Invoices'));
			$this->set("title",__('Invoices'));				
			$login_user = $this->Auth->User('id');
			$mediator_id = $retailer_id = 0;
			if($this->Session->read('Auth.User.role_id')==2){ //if mediator
				$mediator_id = $this->Session->read('Auth.User.id');
			}
			
			$retailer_list = array();
			$get_retailer_data = array();
			/* Getting Mediator  data*/
			$mediator_conditions = array();
			$mediator_conditions['User.added_by'] = $login_user;
			$mediator_conditions['User.status'] = array('1','2');
			$mediator_conditions['User.role_id'] = 2;
			$fields_mediator = array('User.id','User.full_name');
			$order_mediator = 'fname,lname asc';
			$get_mediator_data = $this->User->find('list',array('conditions'=>$mediator_conditions,'fields'=>$fields_mediator,'order'=>$order_mediator));
            foreach($get_mediator_data as $k=>$v)
			{
				$get_mediator_data[$k] = ucwords($v);
			}
			 
			if($mediator_id != 0)
			{
								
				$retailers_conditions = array();
				$retailers_conditions['User.status'] = array('1','2');
				$retailers_conditions['User.role_id'] = 3;
				$fields_retailer = array('User.id','User.full_name');
				$order_retailer = 'fname,lname asc';
				$retailers_conditions['User.added_by'] = $mediator_id;
				
				$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
				foreach($get_retailer_data as $key =>$value)	
				$retailer_list[]= $key;
				
			}
			else
			{
				$retailers_conditions = array();
				$retailers_conditions['User.status'] = array('1','2');
				$retailers_conditions['User.role_id'] = 3;
				$fields_retailer = array('User.id','User.full_name');
				$order_retailer = 'fname,lname asc';
				
				$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
				foreach($get_retailer_data as $key =>$value)	
				$retailer_list[]= $key;
			}
			$this->set('retailer_id',$retailer_id);
		    $this->set('mediator_id',$mediator_id);
			
			if($mediator_id != 0)
			{
				$this->set('retailer_list',$get_retailer_data);
			}
			
			$this->set('mediator_list',$get_mediator_data);
	}
	public function admin_generategrid()
	{
		if($this->request->is('ajax')){
			$this->autoRender = false;

			$this->loadModel('Invoice');
			
			$params = $this->request->query;

			$page 	= $params['page'];
			$limit  = $params['rows'];
			$sidx 	= $params['sidx'];
			$sort 	= $params['sord'];

	        
			$retailer_id = '';
			$mediator_id = '';
			
			$conditions = array();	
			if(isset($this->request->query['filters'])){
				$filters = json_decode($this->request->query['filters'], true);
				foreach($filters['rules'] as $each_filter){
					if($each_filter['field'] == 'id'){
						$conditions['Invoice.id ='] = Sanitize::clean($each_filter['data']);
					}
					if($each_filter['field'] == 'invoice_number'){
						$conditions['Invoice.invoice_number LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
					}
					if($each_filter['field'] == 'invoice_description'){
						$conditions['Invoice.invoice_description LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
					}
				}
			}
           
			$named = $this->request->named;
			//prd($named);
			
			if(isset($named['user_id']) && !empty($named['user_id'])) 
			{
				$conditions['Invoice.user_id'] = $named['user_id'];
				$retailer_id = $named['user_id'];
			}
			if(isset($named['added_by_user']) && !empty($named['added_by_user'])) 
			{
				$conditions['Invoice.added_by_user'] = $named['added_by_user'];
				$mediator_id = $named['added_by_user'];
			}
			
            						
			if(!$limit){$limit = 10;}
			if(!$page){$page = 1;}
			if(!$sidx){$sidx = 1;}

			$this->loadModel('Invoice');
			$count  = $this->Invoice->find('count',array('conditions'=>$conditions,'group'=>'invoice_number'));
			
			if ($count > 0) { 
				$total_pages = ceil($count/$limit); 
			} else { 
				$total_pages = 1; 
			}
			if ($page > $total_pages) $page=$total_pages; 		
			$start = $limit*$page - $limit;	
			
			$resultSet = $this->Invoice->find('all', 
						array('conditions'=>$conditions,
						'order'=>(array($sidx.' '.$sort)),
						'limit'=>$limit,
						'offset'=>$start,
						'group'=>'invoice_number',
						'fields'=>array('User.fname','User.lname','Parent.fname','Parent.lname','Invoice.*'),
						'recursive' => -1,
						'joins' => array(
							array(
								'table' => 'ecom_users',
								'alias' => 'User',
								'type' => 'left', 	
								'conditions' => 'User.id = Invoice.user_id'
							),
							array(
								'table' => 'ecom_users',
								'alias' => 'Parent',
								'type' => 'left', 	
								'conditions' => 'User.added_by = Parent.id'
							)
						),
					)
			 );
		   
		  	$response = new stdClass();
			$response->page 	= $page;
			$response->total 	= $total_pages;
			$response->records 	= $count;
			foreach($resultSet as $key=>$val){
				
				$img_path = WWW_ROOT .'img/card_icons/'.$val['Invoice']['file_name'];
				$type = __('Custom');
				if(empty($val['Invoice']['file_name']))
				{
					$val['Invoice']['file_name'] = 0;
					$type = __('Auto Generated');
				}
				$download = '<a onclick=invoice_generate("'.$val['Invoice']['invoice_number'].'","'.$val['Invoice']['file_name'].'") style="color:#438CE5;cursor:pointer;" title="Download">Download</a>';
				
            	$link_delete = '<a onclick=delete_invoice("'.$val['Invoice']['invoice_number'].'") style="color:#438CE5;cursor:pointer;" title="Delete">Delete</a>';
			  
				$status_link = '<div style="float:left; padding:4px;width:100%;"><select id="action_status_'.$val['Invoice']['id'].'" class="form-control" title="Change Status" onchange="changeStatus('.$val['Invoice']['id'].',this)" >';
                
                $download = $download."&nbsp;&nbsp;".$link_delete; 
			    if($val['Invoice']['invoice_status'] == 1)
				{
					$status = "Active";
					$status_link .= '<option value="0">Disabled</option>
											<option selected="selected" value="1">Enabled</option>
										</select>';
				}
				else 
				{
				  $status = "Pending";
				  $status_link .= '<option selected="selected" value="0">Disabled</option>
											<option value="1">Enabled</option>
										</select></div>';
				}
						
				//$download = '<a href="javascript:void(0)" onclick="'.$this->Commonfunctions->download($img_path).'"><img title="Download" alt="Download" src="'.$this->webroot.'img/download.png" border="0" style="cursor:pointer;"/></a>';
				$val['Invoice']['invoice_created'] = date('d.m.Y',strtotime($val['Invoice']['invoice_created']));
				$mediator = ucwords(strtolower($val['Parent']['fname']." ".$val['Parent']['lname']));
				$retailer = ucwords(strtolower($val['User']['fname']." ".$val['User']['lname']));
				$response->rows[$key]['id']   = $val['Invoice']['id'];			
				$response->rows[$key]['cell'] = array($val['Invoice']['id'],$mediator,$retailer,$val['Invoice']['invoice_number'],$val['Invoice']['invoice_created'],$type,$download,$status_link);
			}

			echo json_encode($response);
		}
		else
		{
			$this->redirect(array('action' => 'index','controller'=>'Categories'));
		}
	}
	
	public function admin_invoice_add(){
		$this->set("title",__('Add - Invoice'));	
		$this->set("title_for_layout",__('Add - Invoice'));	
		$retailer_id = '';
		$mediator_id = '';
		/* Getting Retailer data*/
		$this->loadModel('User');
		$login_user = $this->Auth->User('id');
		$retailer_conditions = array();
		$retailer_conditions['User.added_by'] = $login_user;
		$retailer_conditions['User.status'] = array('1','2');
		$fields_retailer = array('User.id','User.full_name');
		$order_retailer = 'fname,lname asc';
		$get_retailer_data = $this->User->find('list',array('conditions'=>$retailer_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
		//prd($get_retailer_data);
		
		if ($this->request->is('post') || $this->request->is('put')){
			$this->loadModel('Invoice');
			$data = $this->request->data;
			
			if($this->Session->read('Auth.User.role_id')==1){ //if distributor
				if(isset($data['Invoice']['added_by_user']) && !empty($data['Invoice']['added_by_user'])){
					$mediator_id = $data['Invoice']['added_by_user'];
					$data['Invoice']['added_by_user']   = $mediator_id;
				}
			}else{
				$data['Invoice']['added_by_user']   = $this->Auth->User('id');
			}
			$retailer_id = $data['Invoice']['user_id'];
			$this->set('retailer_id',$data['Invoice']['user_id']);
			if(isset($data['Invoice']['file_name']['name']) && !empty($data['Invoice']['file_name']['name'])){
						$this->Invoice->validate = Set::merge($this->Invoice->validate, array(
							'file_name'		=>	array(
													'type'		    =>array(
													'rule'			  =>	array('extension',array('pdf')),
													'allowEmpty'	=>	false,
													'message'			=>	'Please supply a valid pdf File.'
											),
										)));
						
						
						$this->Invoice->set($this->request->data);
						if($this->Invoice->validates())
						{
							$extension = explode(".",$data['Invoice']['file_name']['name']);
							$img_name = strtolower(str_replace(' ','_',rand().$data['Invoice']['file_name']['name']));
							$img_tmp = $data['Invoice']['file_name']['tmp_name'];
							$img_path = WWW_ROOT.'img/card_icons/'.$img_name;
							if(!empty($card['Invoice']['file_name'])){	
								@unlink(WWW_ROOT.'img/card_icons/'.$card['Invoice']['file_name']);
								@unlink(WWW_ROOT.'img/card_icons/icon/'.$card['Invoice']['file_name']);
							}
							$data['Invoice']['invoice_date_month'] = date('Y-m-d',strtotime($data['Invoice']['invoice_date_month']));
							
							$current_date = date('Y-m-d');
							$data['Invoice']['invoice_created'] = $current_date;
							$invoice_number = split('-',$data['Invoice']['invoice_date_month']);
							$data['Invoice']['invoice_number'] = "Invoice_".$invoice_number[1]."_".$invoice_number[0]."_".$data['Invoice']['user_id'];
							$already_exists = $this->Invoice->hasAny(array('Invoice.invoice_number' => trim($data['Invoice']['invoice_number'])));		
							
							if(!($already_exists)){
								if(move_uploaded_file($img_tmp,$img_path)){
									$data['Invoice']['file_name'] = $img_name;
								}
								
	
								$last_month_year = date("Y", strtotime($data['Invoice']['invoice_date_month']));
								$last_month_name = date("F", strtotime($data['Invoice']['invoice_date_month']));
	
								$data['Invoice']['invoice_description'] = "Invoice ".$last_month_name." ".$last_month_year;
								$this->Invoice->save($data);
								$this->Session->setFlash('Custom Invoice has been saved', 'default', array('class' => 'success'));
								$this->redirect(array('controller'=>'Pages', 'action'=>'invoice_add'));
							}
							else
							{
								$this->Session->setFlash('Invoice for selected month already exist.', 'default', array('class' => 'error'));	
							}
						}
				}else{
					$this->Session->setFlash(__('Please upload PDF.'), 'default', array('class' => 'error'));
				}
		}
		if($this->Session->read('Auth.User.role_id')==1){ //if distributor
		/* Getting Mediator  data*/
			$mediator_conditions = array();
			$mediator_conditions['User.added_by'] = $login_user;
			$mediator_conditions['User.status'] = array('1','2');
			$mediator_conditions['User.role_id'] = 2;
			$fields_mediator = array('User.id','User.full_name');
			$order_mediator = 'fname,lname asc';
			$get_mediator_data = $this->User->find('list',array('conditions'=>$mediator_conditions,'fields'=>$fields_mediator,'order'=>$order_mediator));
			$this->set('mediator_id',$mediator_id);
			$this->set('mediator_list',$get_mediator_data);	
			$get_retailer_data = array();
			$get_retailer_data[''] = 'Select Retailer';	
			
			if(isset($mediator_id) && !empty($mediator_id)){
				//Get retailers of this Mediator
				$retailers_conditions = array();
				$retailers_conditions['User.status'] = array('1','2');
				$retailers_conditions['User.role_id'] = 3;
				$fields_retailer = array('User.id','User.full_name');
				$order_retailer = 'fname,lname asc';
				$retailers_conditions['User.added_by'] = $mediator_id;
				
				$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
			}
		}
		$this->set('retailer_id',$retailer_id);
		$this->set('retailer_list',$get_retailer_data);
	}
}