<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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

App::uses('Controller', 'Controller');
App::uses('AuthComponent', 'Controller/Component');
App::uses('Sanitize', 'Utility');
App::uses('CakeTime', 'Utility');
App::uses('CakeNumber', 'Utility');


/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	
	public $components = array(
										'Auth',
										'Session',
										'Cookie',
										'RequestHandler',
										'Email'
							);

/*public $components = array('Session','Auth','Cookie','RequestHandler', 'Email',
		'Auth' => array(
		      'authenticate' => array(
		          'Form' => array(
		              'fields' => array('username' => 'email'),
		             // 'scope' => array("User.u_status" => 1)
		          )
		      )
		  )
	);														
*/	
	public $helpers = array(
		'Html',
		'Form',
		'Js',
		'Image',
		'Session',
		'Text',
		'Time',
		'General',
		'Captcha'
		);

	public function beforeFilter() {				
		
		parent::beforeFilter();						
	    if(isset($this->request->params['admin'])) 
		{
			// Case When User Is Login and tryin to access admin panel
			if($this->Auth->User('role_id'))
			{
				// If login User is not Distributor/ Mediator
				if($this->Auth->User('role_id') != 1 && $this->Auth->User('role_id') != 2)
				{
					//1= Distributor 2= Mediator....
					$this->layout = 'default';
					$this->Auth->loginAction 	= array('admin'=>false,'controller' => 'Users', 'action' => 'login');
					$this->Auth->loginRedirect 	= array('admin'=>false,'controller' => 'Searches', 'action' => 'online_card');	
					$this->Auth->logoutRedirect = array('admin'=>false,'controller' => 'Users', 'action' => 'login');
 					$this->redirect(array('controller'=>'Searches','action'=>'online_card','admin'=>false));
				}
				else
				{
					$this->layout = 'adminlayout';
					$this->Auth->loginAction 	= array('admin'=>true,'controller' => 'Users', 'action' => 'admin_login');
					$this->Auth->loginRedirect 	= array('admin'=>true,'controller' => 'Users', 'action' => 'admin_dashboard');	
					$this->Auth->logoutRedirect = array('admin'=>true,'controller' => 'Users', 'action' => 'admin_login');
				}
			}
			// If not login then can access admin login page
			else
			{
    		$this->layout = 'adminlayout';
				$this->Auth->loginAction 	= array('admin'=>true,'controller' => 'Users', 'action' => 'admin_login');
				$this->Auth->loginRedirect 	= array('admin'=>true,'controller' => 'Users', 'action' => 'admin_dashboard');	
				$this->Auth->logoutRedirect = array('admin'=>true,'controller' => 'Users', 'action' => 'admin_login');
			}
		}
		else
		{
  		// Case When User Is Login and tryin to access Front panel
			if($this->Auth->User('role_id'))
			{
				// If login user is Distributor/Mediator
				if($this->Auth->User('role_id') == 1 || $this->Auth->User('role_id') == 2)
				{
					//1= Distributor 2= Mediator....
					$this->layout = 'adminlayout';
					$this->Auth->loginAction 	= array('admin'=>true,'controller' => 'Users', 'action' => 'admin_login');
					$this->Auth->loginRedirect 	= array('admin'=>true,'controller' => 'Users', 'action' => 'admin_dashboard');	
					$this->Auth->logoutRedirect = array('admin'=>true,'controller' => 'Users', 'action' => 'admin_login');
					$this->redirect(array('controller'=>'Users','action'=>'dashboard','admin'=>true));
				}
				else
				{
					$this->layout = 'default';
					$this->Auth->loginAction 	= array('admin'=>false,'controller' => 'Users', 'action' => 'login');
					$this->Auth->loginRedirect 	= array('admin'=>false,'controller' => 'Searches', 'action' => 'online_card');	
					$this->Auth->logoutRedirect = array('admin'=>false,'controller' => 'Users', 'action' => 'login');
				}
		  }
			// If not login then can access front panel
			else
			{
				$this->layout = 'default';
				$this->Auth->loginAction 	= array('admin'=>false,'controller' => 'Users', 'action' => 'login');
				$this->Auth->loginRedirect 	= array('admin'=>false,'controller' => 'Searches', 'action' => 'online_card');	
				$this->Auth->logoutRedirect = array('admin'=>false,'controller' => 'Users', 'action' => 'login');
			}
		}
		
		if ($this->RequestHandler->isAjax()) 
		{
			$this->layout = 'ajax';
		}
		
		$this->SiteSettings();
  	    //$this->disableHome();
	    //$this->disableAdminLogin();
		$this->SetLanguage();
		
		if($this->Auth->User('id'))
		{
			$this->set('login_user',1);
		}
		else
		{
			$this->set('login_user',0);
		}
		
		if($this->Auth->User('role_id'))
		{
			$this->set('login_user_roleid',$this->Auth->User('role_id'));
		}
		else
		{
			$this->set('login_user_roleid',0);
		}
		
		$get_balance = $this->get_balance($this->Auth->User('id'));
		$this->set('avalable_balance',$get_balance);
		
		
		$get_today_sales = $this->today_sales($this->Auth->User('id'));
		$this->set('todays_sales',$get_today_sales);
        
		$get_current_news =$this->get_current_news();
		$this->set('todays_news_update',trim($get_current_news['CmsLanguage']['content']));
		//$this->Auth->authorize = array('Controller');
	}
  
    
	public function get_current_news(){
	
		$this->loadModel('Cmspage');
		$language=Configure::read('Config.language');
		if(empty($language))
		$language="en";
		
		$keyword = "CMS.NEWS";
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
		return $cms_content;
	}
    
	public function user_sale_by_date($login_id = NULL,$date = NULL)
	{
		 	$this->loadModel('Sale');
			$total_sale = 0;
		    
			$total_sale_data = $this->Sale->find('first',array('conditions'=>array('Sale.s_u_id'=>$login_id,'s_date'=>$date),
											'fields'=>'sum(Sale.s_total_purchase) as total_sales',
										));
   		    
			if(isset($total_sale_data[0]['total_sales']) && !empty($total_sale_data[0]['total_sales']))
			$total_sale = $total_sale_data[0]['total_sales'];
			
			return $total_sale;
	}
	
	public function card_sale_count_all($login_id = NULL)
	{
		 	$this->loadModel('Sale');
			$card_sale_count_user = 0;
		    
			$card_sale_count = $this->Sale->find('first',array('conditions'=>array('Sale.s_u_id'=>$login_id),
											'fields'=>'sum(Sale.card_sale_count) as total_card',
										));
   		    
			if(isset($card_sale_count[0]['total_card']) && !empty($card_sale_count[0]['total_card']))
			$card_sale_count_user = $card_sale_count[0]['total_card'];
			
			return $card_sale_count_user;
	}
	
	public function card_sale_count_totay($login_id = NULL)
	{
		 	$this->loadModel('Sale');
			$card_sale_count_user = 0;
		    
			$card_sale_count = $this->Sale->find('first',array('conditions'=>array('Sale.s_u_id'=>$login_id,'s_date'=>date('Y-m-d')),
											'fields'=>'sum(Sale.card_sale_count) as total_card',
										));
   		    
			if(isset($card_sale_count[0]['total_card']) && !empty($card_sale_count[0]['total_card']))
			$card_sale_count_user = $card_sale_count[0]['total_card'];
			
			return $card_sale_count_user;
	}
	
	public function get_total_purchase($login_id = NULL)
	{
		 	$this->loadModel('Transaction');
			$total_purchase_user = 0.00;
			$total_purchase = $this->Sale->find('first',array('conditions'=>array('Sale.s_u_id'=>$login_id),
														'fields'=>'sum(Sale.s_total_purchase) as total_amount_purchase',
														));
			if(isset($total_purchase[0]['total_amount_purchase']) && !empty($total_purchase[0]['total_amount_purchase']))
			$total_purchase_user = $total_purchase[0]['total_amount_purchase'];
			
			return $total_purchase_user;
	}
	
	public function get_transaction_details($login_id = NULL)
	{
		 	$this->loadModel('Transaction');
			$get_transaction = $this->Transaction->findByUserId($login_id);
			return $get_transaction;
	}
	
	public function get_balance($login_id = NULL)
	{
		 	$this->loadModel('Transaction');
			$get_transaction = $this->Transaction->findByUserId($login_id);
			if($get_transaction)
			{
				$balance = $get_transaction['Transaction']['balance'];
			}
			else
			{
				$balance = 0.00;
			}
			return $balance;
	}
	
	public function today_sales($login_id = NULL)
	{
		 	$this->loadModel('Sale');
			$get_sales = $this->Sale->find('all',array('conditions'=>array('Sale.s_u_id'=>$login_id,'Sale.s_date'=>date('Y-m-d'))));
			if($get_sales)
			{
				$total_amount = 0;
				$total_quantity = 0;
				foreach($get_sales as $sale)
				{
					$total_amount = $total_amount + $sale['Sale']['s_total_purchase'];
					$total_quantity = $total_quantity +$sale['Sale']['s_quantity'];
				}
				$sales['sales_amount'] = $total_amount; // Sales Amount
				$sales['sales_quantity'] = $total_quantity;  // Quantity
			}
			else
			{
				$sales['sales_amount'] = 0.00; // Sales Amount
				$sales['sales_quantity'] = 0;  // Quantity
			}
			return $sales;
	}
	
	public function userLoginSetLanguage()
	{
		if($this->Auth->User('id'))
		{			
			if($this->Auth->User('role_id')==3)
			{
					if($this->Auth->User('language_code')!='')
					{
						echo $this->Auth->User('');
						$this->Session->write('Config.language', $this->Auth->User('language_code'));
						Configure::write('Config.language', $this->Auth->User('language_code'));
						return;
					}
			}
		}
	}
	
	public function SiteSettings()
	{
		$this->loadModel('Websetting');
		$site_settings = $this->Websetting->find('all', array(
			'recursive' => -1, //int
			'fields' => array('key','value'),
			)
		);
			
		//pr($site_settings);
		foreach($site_settings as $each_setting){
			Configure::write($each_setting['Websetting']['key'],$each_setting['Websetting']['value']);
			defined($each_setting['Websetting']['key'])
				or define($each_setting['Websetting']['key'],$each_setting['Websetting']['value']);
		}
	//	echo APPLICATION_URL;exit;
		//exit;
	}

	public function SetLanguage(){

    $this->loadModel('User');
		$userdata = $this->User->findById($this->Auth->User('id'));
		if($userdata)
		{
			$this->Session->write('Config.language', $userdata['User']['language_code']);
			Configure::write('Config.language',$userdata['User']['language_code']);
			return;
		}
		else
		{
				$this->loadModel('Language');
				$def_lan_keyword = $this->Language->find('first', array(
																													'recursive' => -1, //int
																													'conditions' => array(
																													'Language.is_default' => '1', 
																													),
																													'fields' => array('Language.alias'),
																													));
		//pr(Configure::read('Config.language'));
		if($this->Session->check('Config.language'))
		{
				Configure::write('Config.language', $this->Session->read('Config.language'));		
		}
		else if(isset($def_lan_keyword['Language']['alias']))
		{
			$this->Session->write('Config.language', $def_lan_keyword['Language']['alias']);
			Configure::write('Config.language', $def_lan_keyword['Language']['alias']);		
		}
		else
		{
			$this->Session->write('Config.language', 'en');
			Configure::write('Config.language', 'en');		
		}
	 }
	 return;
 }
	
	/**
	 * Image tag in controller for jqgrid front.
	 */
	public function frontContImage($ImageName=NULL, $alt=NULL){
		
		if($ImageName){
			$alttxt = $alt;
			if ($alt==NULL) {
				$alttxt = '';
			} else {
				$alttxt = 'alt="'.$alt.'"';
			}
			return '<img src="'.$this->webroot.IMAGES_URL.'images/'.$ImageName.'" '.$alttxt.' border="0" />';
		}
	}
  public function _updatecountercacheparent(){
		//function to update parent categorie's count of cards
		$this->loadModel('Category');
		$this->Category->query("UPDATE ecom_categories AS parent_cat 
					INNER JOIN 
					(SELECT cat_parent_id,sum(card_count) tcount FROM ecom_categories GROUP BY cat_parent_id) child_cat 
					ON parent_cat.cat_id = child_cat.cat_parent_id  
					SET parent_cat.card_count=tcount");
	}
}
