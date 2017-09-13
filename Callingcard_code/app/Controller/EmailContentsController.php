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
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');

class EmailContentsController extends AppController {

public function beforeFilter() {
	 	parent::beforeFilter();
		$this->Auth->allow('');
}	

/**
 * Controller name
 *
 * @var string
 * @access public
 */
	public $name = 'EmailContents';

/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
	public $uses = array('EmailContent');

/**
 * Admin index
 *
 * @return void
 * @access public
 */
	public function admin_index() {
	   $this->admin_redirect_to_dashboard_distributor();	
	   $this->set('title_for_layout', 'View Email Contents');
	}
	
/**
	*Generate grid action in EmailContent Controller(Generate grid for Index action)
  *
  */ 
	public function admin_generategrid(){
		
		$page  = $this->request->query['page'];
		$limit = $this->request->query['rows'];
		$sidx  = $this->request->query['sidx'];
		$sord  = $this->request->query['sord'];
		
		if(!$sidx) $sidx =1;
		
		$order_by = $sidx.' '.$sord;
		
		$conditions = array();
		
		if(isset($this->request->query['filters'])){
			$filters = json_decode($this->request->query['filters'], true);
			foreach($filters['rules'] as $each_filter){
			
				if($each_filter['field'] == 'id'){
					$conditions['EmailContent.id LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
				}
			
				if($each_filter['field'] == 'title'){
					$conditions['EmailContent.title LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'subject'){
					$conditions['EmailContent.subject LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
				}
			}
		}
		$count = $this->EmailContent->find('count',array(
				'recursive' => -1,
			)
		);
		
		if($count >0){ 
			$total_pages = ceil($count/$limit); 
		}else{
			$total_pages = 0; 
		}
		
		if ($page > $total_pages) {
			$page=$total_pages;
		}
		
		$start = $limit*$page - $limit; 
		
		$email_content = $this->EmailContent->find('all',
			array(
				'recursive' => -1, 
				'conditions' => $conditions,
				'order' => $order_by,
				'limit' => $limit,
				'offset' => $start
		));
    
		$responce = new stdClass();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count; 
		
		$i=0; 
		if(is_array($email_content))
		{
			foreach($email_content as $email_contents):
			{

				$title = $email_contents['EmailContent']['title'];
				$subject = $email_contents['EmailContent']['subject'];
				$preview = Router::url(array('controller'=>'EmailContents','action'=>'view','admin'=>true,$email_contents['EmailContent']['id']));
				//'.$this->frontContImage('view_admin.png','View').'
				$link_preview = '<a class="grid_link" href="'.$preview.'" style="color:#438CE5 !important;">View |</a>';
				$edit = Router::url(array('controller'=>'EmailContents','action'=>'edit','admin'=>true,$email_contents['EmailContent']['id']));
				//'.$this->frontContImage('edit_admin.png','Edit').'
				$link_edit = '<a class="grid_link" href="'.$edit.'" style="color:#438CE5 !important;">Edit</a>';
				
				$action = $link_preview." ".$link_edit;
				$responce->rows[$i]['id']=$email_contents['EmailContent']['id'];
				//$responce->rows[$i]['cell']=array($email_contents['EmailContent']['id'],$title,$subject,$link_preview,$link_edit); 
				$responce->rows[$i]['cell']=array($email_contents['EmailContent']['id'],$title,$subject,$action); 

				$i++; 
				
			} 
			
			endforeach;
		}
		echo json_encode($responce); exit;
	}

/**
 * Email content edit
 *
 * @param integer $id
 * @return void
 * @access public
 */
	public function admin_edit($id=NULL){
		$this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout', "Edit Email Content");
		 $this->set('Back','Back');
		if (!$id && empty($this->request->data)){
			$this->Session->setFlash('Invalid Email Content', 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		
		if(!empty($this->request->data)){
				
				$keyword_exist = 1;
				
				$email_content = $this->EmailContent->read(null, $id);
				
				$keywords = explode(",",$email_content['EmailContent']['keywords']);
							
				foreach($keywords as $each_keyword):        /*Check if all the keywords are present in message or not*/
				  
					//pr($keywords);exit;
					if(!(strstr($this->request->data['EmailContent']['message'],$each_keyword))){
						
						  $keyword_exist = 0;
							
					}
				
				endforeach;
				
				$this->set('content',$email_content['EmailContent']['keywords']);
								
			  if($keyword_exist==0){                     /*If keywords are not present in message then display error*/
				
				   $this->Session->setFlash('Please provide all keywords in content.', 'default', array('class' => 'error'));
								
				}else{
				    
						if($this->EmailContent->save($this->request->data)){
							
								$this->Session->setFlash('Email content has been edited', 'default', array('class' => 'success'));
								//$this->redirect(array('action' => 'index'));
								
						} else {
							
  							$this->Session->setFlash('Email content could not be edited. Please, try again.', 'default', array('class' => 'error'));
						}
			 	}
		}
		
		if(empty($this->request->data)){
			
			$email_content = $this->EmailContent->read(null, $id);
			$this->set('content',$email_content['EmailContent']['keywords']);
			$this->request->data = $email_content;
			
		}
	}

/**
	*View Email Content  
	*
	*/
	public function admin_view($id=NULL)
	{
		$this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout', "View Email Content");
		$this->set('Back','Back');
		if(!$id)
		{
		  throw new NotFoundException('Invalid Post');
		}
		 $email_content=$this->EmailContent->findById($id);
	   $this->set('email_content',$email_content);
	}
	
}