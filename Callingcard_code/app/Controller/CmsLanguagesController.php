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

class CmsLanguagesController extends AppController {

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
	public $name = 'CmsLanguages';

/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
	public $uses = array('CmsLanguage');

/**
 * Admin index
 *
 * @return void
 * @access public
 */
	public function admin_index() {
	   $this->admin_redirect_to_dashboard_distributor();	
	   $this->set('title_for_layout', 'View Cms Languages');
	}
	
/**
 * Admin edit Cms Content
 *
 * @param integer $id
 * @return void
 * @access public
 */
	public function admin_editcmscontent($cmspages_id=NULL,$cms_lang_id=NULL){
		$this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout', "Edit Cms Page Content");
		 $this->set('Back','Back');
		$this->LoadModel('Language');
		$lang_list = $this->Language->find('list',array(
				'recursive'=>-1,
				'fields'=>array('alias','title'),	
				'conditions'=>array('status'=>1),
		));
		
		$this->set('lang_list',$lang_list);
   	$this->set('id',$cms_lang_id);
		$this->set('cmspage_id',$cmspages_id);
		
		$cms_lang= $this->CmsLanguage->find('all',array(
				'recursive'=>-1,
				'fields'=>array('language_alias'),	
				'conditions'=>array('id'=>$cms_lang_id),
		));
		
		$this->set('language_alias',$cms_lang[0]['CmsLanguage']['language_alias']);

		if (!$cmspages_id && empty($this->request->data)) {
			
			$this->Session->setFlash('Invalid Cms Content Id', 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
			
		}

		if(!empty($this->request->data)) {
			
			if($this->CmsLanguage->save($this->request->data)){
				
				if($cms_lang[0]['CmsLanguage']['language_alias'] == 'en')
				{
					$this->loadModel('Cmspage');
					$cmspage_data = $this->Cmspage->findById($cmspages_id);
					$cmspage_data['Cmspage']['title'] = $this->request->data['CmsLanguage']['title'];
					$update_cmspage = $this->Cmspage->save($cmspage_data);
				}
				$this->Session->setFlash('Cms Content has been edited successfully', 'default', array('class' => 'success'));
				//$this->redirect(array('controller'=>'CmsPages','action' => 'index'));
				
			}else{
				
				$this->Session->setFlash('Cms Content could not be edited. Please, try again.', 'default', array('class' => 'error'));
				
			}
		}
		
		if (empty($this->request->data)) {
			
      $this->request->data = $this->CmsLanguage->read(null, $cms_lang_id);
			
		}
	}
	
/**
 * Admin add Cms Content
 *
 * @return void
 * @access public
 */
	public function admin_addcmscontent($cmspages_id=NULL,$language_alias=NULL) {
		$this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout', "Add Cms Content");
		
		$this->LoadModel('Language');
		$lang_list = $this->Language->find('list',array(
				'recursive'=>-1,
				'fields'=>array('alias','title'),	
				'conditions'=>array('status'=>1),
		));
		
		
		$this->set('lang_list',$lang_list);

		
		$this->set('cmspage_id',$cmspages_id);
		$this->set('language_alias',$language_alias);

		if (!empty($this->request->data)) {
 			if ($this->CmsLanguage->save($this->request->data)) {
				
				$this->Session->setFlash('Cms Page has been added', 'default', array('class' => 'success'));
				$this->redirect(array('controller'=>'CmsPages','action' => 'index'));
				
			} else {
				
				$this->Session->setFlash('Cms Page could not be added. Please, try again.', 'default', array('class' => 'error'));
				
			}
		}
	}
	


}