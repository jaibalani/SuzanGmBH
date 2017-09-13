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

class FaqsLanguagesController extends AppController {

public function beforeFilter() {
	 	parent::beforeFilter();
		$this->Auth->allow('');
}	

/**
 * Admin index
 *
 * @return void
 * @access public
 */
	public function admin_index() {
	   $this->admin_redirect_to_dashboard_distributor();	
	   $this->set('title_for_layout', 'View FAQ Languages');
	}
	
/**
 * Admin edit Cms Content
 *
 * @param integer $id
 * @return void
 * @access public
 */
	public function admin_editcontent($fl_f_id=NULL,$fl_id=NULL){
		$this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout', __('Edit - FAQ'));
		$this->set("title",__('Edit - FAQ'));	
		$this->LoadModel('Language');
		$lang_list = $this->Language->find('list',array(
				'recursive'=>-1,
				'fields'=>array('alias','title'),	
				'conditions'=>array('status'=>1),
		));
		$this->set('lang_list',$lang_list);
     	$this->set('id',$fl_id);
		$this->set('fl_f_id',$fl_f_id);
		
		$faq_lang= $this->FaqsLanguage->find('first',array(
				'recursive'=>-1,
				'fields'=>array('fl_alias'),	
				'conditions'=>array('fl_id'=>$fl_id),
		));
		$this->set('language_alias',$faq_lang['FaqsLanguage']['fl_alias']);

		if (!$fl_f_id && empty($this->request->data)) {
			
			$this->Session->setFlash('Invalid FAQ Content Id', 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
			
		}

		if(!empty($this->request->data)) 
		{
			$validate = 1;
			/*if($faq_lang['FaqsLanguage']['fl_alias'] == 'en')
			{*/
				$this->loadModel('Faq');
				
				/*$already_exists = $this->Faq->hasAny(
						array('f_title' => trim($this->request->data['FaqsLanguage']['fl_title']), 
							  'f_id <>'=>$fl_f_id));*/

                $already_exists = $this->Faq->find('all',
			      array(
				   'recursive' => -1,
	                'joins' => array(
						array(
							'table' => 'ecom_faqs_languages',
							'alias' => 'FaqsLanguage',
							'type' => 'INNER', 	
							'conditions' =>'Faq.f_id = FaqsLanguage.fl_f_id' 
	                         )
						),
	                    'conditions'=>array('Faq.f_id <>' => $fl_f_id,
	                    					'FaqsLanguage.fl_title'=>trim($this->request->data['FaqsLanguage']['fl_title'])),
	                    'fields'=>array('Faq.*','FaqsLanguage.*')
	                 )); 
                
                //prd($already_exists);

				if($already_exists)
				{
					$validate = 0;
					$this->Session->setFlash('Faq title already exist in German or English language.', 'default', array('class' => 'error'));
					//$this->Session->setFlash('Faq title already exist.','error');	
				}
			//}
			if($validate){
				if($this->FaqsLanguage->save($this->request->data)){
				
				if($faq_lang['FaqsLanguage']['fl_alias'] == 'en')
				{
					$this->loadModel('Faq');
					$faq_data = $this->Faq->findByfId($fl_f_id);
					$faq_data['Faq']['f_title'] = trim($this->request->data['FaqsLanguage']['fl_title']);
					$faq_data['Faq']['f_desc'] = trim($this->request->data['FaqsLanguage']['fl_desc']);
					$faq_data['Faq']['f_modified_date'] = date('Y-m-d');
					$update_cmspage = $this->Faq->save($faq_data);
				}
				$this->Session->setFlash('FAQ has been edited successfully.', 'default', array('class' => 'success'));
				//$this->redirect(array('controller'=>'Faqs','action' => 'index'));
				
			}else{
				
				$this->Session->setFlash('Faq could not be saved. Please, try again.', 'default', array('class' => 'error'));
				
			}
			}
		}
		
		if (empty($this->request->data)) {
			
      $this->request->data = $this->FaqsLanguage->read(null, $fl_id);
			
		}
	}
	

	public function admin_addcontent() {
		
		$this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout', "Add FAQ");
		$this->set('title', "Add FAQ");
		
		$this->LoadModel('Language');
		$lang_list = $this->Language->find('list',array(
				'recursive'=>-1,
				'fields'=>array('alias','title'),	
				'conditions'=>array('status'=>1),
		));
		
		
		$this->set('lang_list',$lang_list);

		if (!empty($this->request->data)) {
			$data = $this->request->data;
			$i = 0;
			foreach($lang_list as $alias=>$val){
				$data['FaqsLanguage'][$i]['fl_alias'] = $alias;
				$data['FaqsLanguage'][$i]['fl_title'] = trim($data['Faq']['f_title']);
				$data['FaqsLanguage'][$i]['fl_desc'] = trim($data['Faq']['f_desc']);	
				$i++;			
			}
			//prd($data);
			$this->loadModel('Faq');
			$data['Faq']['f_modified_date'] = date('Y-m-d');
				
				$already_exists = $this->FaqsLanguage->hasAny(array('fl_title' => trim($data['Faq']['f_title'])));
				$this->Faq->create();				
				if(!$already_exists)
				{
					if($this->Faq->validates()){
 						if ($this->Faq->saveAssociated($data)) 
 						{
				
				        $this->Session->setFlash('FAQ has been added successfully.', 'default', array('class' => 'success'));
				          $this->redirect(array('controller'=>'FaqsLanguages','action' => 'addcontent'));
				
			             } 
						else 
						{
							
							$this->Session->setFlash('FAQ could not be added. Please, try again.', 'default', array('class' => 'error'));
							
						}
					}
				}
				else
				{
					$this->Session->setFlash('Faq title already exist in German or English language', 'default', array('class' => 'error'));
					//$this->Session->setFlash('Faq title already exist.','error');	
				}
		}
	}
	


}