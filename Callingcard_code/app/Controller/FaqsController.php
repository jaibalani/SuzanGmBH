<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
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
App::uses('Sanitize', 'Utility');
App::uses('CakeEmail', 'Network/Email');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class FaqsController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();
	public $components = array('Auth');
	public $helpers = array('Html','Form');	
/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */
	public function beforeFilter($options = array()){
		parent::beforeFilter();
		$this->disableCache();			
		$this->Auth->allow('index');
	}
	public function admin_add(){
			$this->set("title",__('Add - FAQ'));	
		if ($this->Auth->login()){	
			if ($this->request->is('post')){
				$data = $this->request->data;
				$data['Faq']['f_modified_date'] = date(PHP_DB_SAVE_DATE_FORMAT);
				$already_exists = $this->Faq->hasAny(array('f_title' => trim($data['Faq']['f_title'])));			
				$this->Faq->create();				
				if(!$already_exists){
					if($this->Faq->validates()){
						if ($this->Faq->save($data)) {
							$this->Session->setFlash('Faq has been saved','success');
							$this->redirect(array('action'=>'index'));
						} else {
							$this->Session->setFlash('Faq could not be saved. Please, try again.','error');
						}
					}
				}else{
					$this->Session->setFlash('Faq title already exist.','warning');	
				}
			}
		}else{
			$this->Session->setFlash(__('Unauthorized access'),'error');
			$this->redirect($this->Auth->redirect());
		}
	}
    
	public function index(){	
		$language=Configure::read('Config.language');
		if(empty($language))
			$language="en";
		//echo $language;exit;
		$this->Faq->Behaviors->attach('Containable');
		$this->Faq->unbindModel(array(
				'hasMany'   => array('FaqsLanguage')
			));
			$this->Faq->bindModel(array(
          'hasMany' => array(
							'FaqsLanguage' => array(
								'foreignKey' => 'fl_f_id',
								'conditions' => 'FaqsLanguage.fl_alias LIKE "'.$language.'"',
						)),
			
        ));
		$faqData = $this->Faq->find('all', 
		   array(
					'conditions' => array('f_status' => '1'),
				)
		 );
		//prd($faqData);
		$this->set("title","FAQ's");
		$this->set("faqData",$faqData);


	}
	
	public function admin_index(){
		$this->admin_redirect_to_dashboard_distributor();
		$this->set("title","FAQ");
	}
	
	public function admin_json(){
		if($this->request->is('ajax')){
			$this->autoRender = false;

			$params = $this->request->query;

			$page 	= $params['page'];
			$limit  = $params['rows'];
			$sidx 	= $params['sidx'];
			$sort 	= $params['sord'];

			$conditions = array();
		if(isset($this->request->query['filters'])){
			$filters = json_decode($this->request->query['filters'], true);
			foreach($filters['rules'] as $each_filter){
				if($each_filter['field'] == 'f_title'){
					$conditions['Faq.f_title LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'f_status'){
						if($each_filter['data'] != '')
						$conditions['Faq.f_status'] = Sanitize::clean($each_filter['data']);
					}
			}
		}

			if(!$limit){$limit = 10;}
			if(!$page){$page = 1;}
			if(!$sidx){$sidx = 1;}

			$count  = $this->Faq->find('count');
			if ($count > 0) { 
				$total_pages = ceil($count/$limit); 
			} else { 
				$total_pages = 1; 
			}
			if ($page > $total_pages) $page=$total_pages; 		
			$start = $limit*$page - $limit;	
			
			$resultSet = $this->Faq->find('all', array('conditions'=>$conditions,'order'=>(array($sidx.' '.$sort)),'limit'=>$limit,'offset'=>$start));
			//prd($resultSet);

			$response = new stdClass();
			$response->page 	= $page;
			$response->total 	= $total_pages;
			$response->records 	= $count;
			foreach($resultSet as $key=>$val){
				$len=strlen($val['Faq']['f_title']);
				if($len > 30)			
				$title = ucwords(strtolower(substr($val['Faq']['f_title'],0,35)."..."));				
				else
				$title = ucwords(strtolower($val['Faq']['f_title']));
				
				$edit_link = '<a href="'.$this->webroot.'admin/Faqs/edit/'.$val['Faq']['f_id'].'"><img src="'.$this->webroot.'img/images/edit_admin.png" alt="Edit" title="Edit" border="0" /></a>';
				
				
				/*if($val['Faq']['f_status']==1){
					$status_link = '<img title="Enabled" alt="Enabled" src="'.$this->webroot.'img/greenStatus.png" border="0" onclick="changeStatus('.$val['Faq']['f_id'].',0)" style="cursor:pointer;"/>';
				}else{
					$status_link = '<img title="Disabled" alt="Disabled" src="'.$this->webroot.'img/redstatus.png" border="0" onclick="changeStatus('.$val['Faq']['f_id'].',1)" style="cursor:pointer;"/>';
				}*/
				
				$status_link = '<div style="float:left; padding:4px;width:100%;"><select class="form-control" title="Change Status" onchange="changeStatus('.$val['Faq']['f_id'].',this)" >';
						
				if($val['Faq']['f_status']==1){
					$status_link .= '<option value="0">Disabled</option>
											<option selected="selected" value="1">Enabled</option>
										</select>';

				}else{
				  $status_link .= '<option selected="selected" value="0">Disabled</option>
						<option value="1">Enabled</option>
					</select></div>';
				}
				
				$this->loadModel('FaqsLanguages');
				$lang = $this->FaqsLanguages->find('all',array(
						'recursive' => -1,
			  		'conditions' => array('fl_f_id'=>$val['Faq']['f_id'],'fl_alias'=>'en')
					)
				);
				$content = '';
				$link_manage = '';
				if(count($lang))
				{
					$content = Router::url(array('controller'=>'FaqsLanguages','action'=>'editcontent','admin'=>true,$val['Faq']['f_id'],$lang[0]['FaqsLanguages']['fl_id']));
					//'.$this->frontContImage('translate_admin.png','Translate').'
					$link_manage = '<a class="grid_link" href="'.$content.'" style="color:#438CE5 !important;">Edit</a>';
				}
				$modified_date = date('m/d/Y', strtotime($val['Faq']['f_modified_date']));	
				$response->rows[$key]['id']   = $val['Faq']['f_id'];			
				$response->rows[$key]['cell'] = array($title, $modified_date, $link_manage,$status_link);
			}

			echo json_encode($response);
		}else{
			// $this->_helper->redirector('accessdenied','index','admin');
		}
	}

	public function admin_delete(){
		$this->admin_redirect_to_dashboard_distributor();
		if($this->request->is('ajax')){
			$ids = implode(',',$this->request['data']['ids']);
			if($this->Faq->deleteAll(array('Faq.f_id IN ('.$ids.')'))) {
					echo 1;exit;	
			}else{
				echo 0; exit;
			}
		}
	}	

	public function admin_changestatus(){
		$this->admin_redirect_to_dashboard_distributor();
		if($this->request->is('ajax')){	
			$id = $this->request->data['id'];
			$status = $this->request->data['st'];
			unset($this->request->data);
			$this->Faq->set(array('Faq' => array('f_id'=>$id, 'f_status'=>$status)));
			if($this->Faq->save($this->request->data)){
				echo 1; exit;
			}else{
				echo 0; exit;
			}
		}
			
	}
	
	public function admin_checklanguage()
	{
		$this->admin_redirect_to_dashboard_distributor();
		$this->loadModel('FaqsLanguage');
        $faq_languages = $this->FaqsLanguage->find('first',array(
						'conditions' =>array('FaqsLanguage.fl_f_id' =>$this->request->data['Faq']['fl_f_id'],'FaqsLanguage.fl_alias'=>$this->request->data['Faq']['fl_alias'])
				));	
		if(isset($faq_languages['FaqsLanguage']['fl_id']) && !empty($faq_languages['FaqsLanguage']['fl_id'])){
			$this->redirect(array('controller'=>'FaqsLanguages','action' => 'editcontent','admin'=>true,$this->request->data['Faq']['fl_f_id'],$faq_languages['FaqsLanguage']['fl_id']));	
		
		}else{
			$this->redirect(array('controller'=>'FaqsLanguages','action' => 'addcontent','admin'=>true));	
		}
		exit;
	}

	
}
