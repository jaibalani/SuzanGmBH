<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5022asdsdf
 *sdfgsdfsdfsd
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

class CmsPagesController extends AppController {

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
	public $name = 'CmsPages';

/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
	public $uses = array('Cmspage');

/**
 * Admin index
 *
 * @return void
 * @access public
 */
	public function admin_index() {
		$this->admin_redirect_to_dashboard_distributor();
	    $this->set('title_for_layout', 'View Cms Pages');
		$this->set('title', 'View Cms Pages');
	}
	
/**
 *Generate grid action in Cmspage Controller(Generate grid for Index action)
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
		$conditions['Cmspage.status !='] = '2';
		
		if(isset($this->request->query['filters'])){
			$filters = json_decode($this->request->query['filters'], true);
			foreach($filters['rules'] as $each_filter){
			
				if($each_filter['field'] == 'id'){
					$conditions['Cmspage.id LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
			
				if($each_filter['field'] == 'title'){
					$conditions['Cmspage.title LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
				}
				if($each_filter['field'] == 'created'){
					$conditions['Cmspage.created LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
				if($each_filter['field'] == 'updated'){
					$conditions['Cmspage.updated LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}
			}
		}
		
		$count = $this->Cmspage->find('count',array(
				'recursive' => -1, //int
			  	'conditions' => $conditions,
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
		
		$cms_page = $this->Cmspage->find('all', array(
			'recursive' => -1, //int
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
		
		if(is_array($cms_page))
		{
			foreach($cms_page as $cms_pages){
				$len=strlen($cms_pages['Cmspage']['title']);
				if($len > 30)			
				$title = ucwords(strtolower(substr($cms_pages['Cmspage']['title'],0,35)."..."));				
				else
				$title = ucwords(strtolower($cms_pages['Cmspage']['title']));

				$edit = Router::url(array('controller'=>'CmsPages','action'=>'edit','admin'=>true,$cms_pages['Cmspage']['id']));
				$link_edit = '<a class="grid_link" href="'.$edit.'">'.$this->frontContImage('edit_admin.png','Edit').'</a>';
				
				$this->loadModel('CmsLanguage');
				$lang = $this->CmsLanguage->find('all',array(
						'recursive' => -1,
			  		'conditions' => array('cmspage_id'=>$cms_pages['Cmspage']['id'],'language_alias'=>'en')
					)
				);
				
				$content = Router::url(array('controller'=>'CmsLanguages','action'=>'editcmscontent','admin'=>true,$cms_pages['Cmspage']['id'],$lang[0]['CmsLanguage']['id']));
				//'.$this->frontContImage('translate_admin.png','Translate').'
				$link_manage = '<a class="grid_link" href="'.$content.'" style="color:#438CE5 !important;">Edit Content</a>';
				
				$created = date('m/d/Y', strtotime( $cms_pages['Cmspage']['created']));
				$updated =date('m/d/Y', strtotime($cms_pages['Cmspage']['updated']));
				
				$responce->rows[$i]['id']=$cms_pages['Cmspage']['id'];
				$responce->rows[$i]['cell']=array(
					$cms_pages['Cmspage']['id'],
					$title,
					$created,
					$updated,
					$link_manage
				); 
				
				$i++; 
			} 
		}
		echo json_encode($responce); exit;
	}

/**
 * Cms page edit
 *
 * @param integer $id
 * @return void
 * @access public
 */
 	public function admin_edit($id=NULL){		
		$this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout', "Edit Cms Page");
		$this->set('title', 'View Cms Pages');
		$this->set('Back','Back');
		$this->set('id',$id);
		if (!$id && empty($this->request->data)){
			$this->Session->setFlash('Invalid Cms Page', 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		if(!empty($this->request->data)){			
			$this->request->data['Cmspage']['image']['name'] = '';
			if(!empty($this->request->data['Cmspage']['image']['name']))
			{
				$img_ext = explode(".",$this->request->data['Cmspage']['image']['name']);
				if($img_ext[1]=="jpeg" || $img_ext[1]=="jpg" || $img_ext[1]=="png" || $img_ext[1]=="gif")
				{ 
					list($width, $height) = getimagesize($this->request->data['Cmspage']['image']['tmp_name']);
					if($width==330 && $height==228){					
						$newFileName = time().'_cmspage_'.$this->request->data['Cmspage']['image']['name'];
						$destination = WWW_ROOT .'img/admin_uploads/cms_uploads/'.$newFileName; 
						$moved = move_uploaded_file($this->request->data['Cmspage']['image']['tmp_name'], $destination);
						$this->request->data['Cmspage']['image'] = $newFileName;	
						//unlink old file..........
						$cms_rec = $this->Cmspage->read(null, $id);

						if($cms_rec['Cmspage']['image']!='')
						unlink(WWW_ROOT .'img/admin_uploads/cms_uploads/'.$cms_rec['Cmspage']['image']);
						
						if($this->Cmspage->save($this->request->data))
						{
							$this->Session->setFlash('Cms Page has been edited', 'default', array('class' => 'success'));
							//CmsLanguage
							$this->loadModel('CmsLanguage');
							$cms_language_data = $this->CmsLanguage->findByCmspageId($id);
							$cms_language_data['CmsLanguage']['title'] = $this->request->data['Cmspage']['title'];
							
							$this->CmsLanguage->save($cms_language_data);
							$this->redirect(array('action' => 'index'));
						}
						else 
						{
							$this->Session->setFlash('Cms Page could not be edited. Please, try again.', 'default', array('class' => 'error'));
						}																
					}
					else
					{
						$this->Session->setFlash('Please provide image of size 330 X 228 pixels.', 'default', array('class' => 'error'));
					}
				}
				else
				{
					$this->Session->setFlash(__('Invalid file format. Please provide only .jpeg,.png,.gif,.jpg images'), 'default', array('class' => 'error'));
				}			
			}
			else
			{
				unset($this->request->data['Cmspage']['image']);
				if($this->Cmspage->save($this->request->data))
				{
						$this->loadModel('CmsLanguage');
						$cms_language_data = $this->CmsLanguage->findByCmspageId($id);
						$cms_language_data['CmsLanguage']['title'] = $this->request->data['Cmspage']['title'];
						$this->CmsLanguage->save($cms_language_data);
						$this->Session->setFlash('Cms Page has been edited', 'default', array('class' => 'success'));
						$this->redirect(array('action' => 'index'));
				} 
				else 
				{
					$this->Session->setFlash('Cms Page could not be edited. Please, try again.', 'default', array('class' => 'error'));
				}
			}			
		}
		$this->loadModel('CmsImage');
		$cms_image = $this->CmsImage->find('all',array(
																						'conditions' =>array('CmsImage.cmspages_id =' =>$id),
																						'order'=>array('id DESC')
				)
			);
		$this->request->data = $this->Cmspage->read(null, $id);
		$this->set('cms_image',$cms_image);
	}
 
/**
 * Admin index
 *
 * @return void
 * @access public
 */
	public function admin_languageindex($id=NULL) {
	   $this->admin_redirect_to_dashboard_distributor();
	   $this->set('title_for_layout','View Languages');
	   $this->set('id',$id);
	}
	
/**
 *Generate grid of Languages in Cmspages Controller(Generate grid for Index action)
 *
 */ 
	public function admin_checklanguage()
	{
		$this->admin_redirect_to_dashboard_distributor();
		$this->loadModel('CmsLanguage');
        $cms_languages = $this->CmsLanguage->find('all',array(
						'conditions' =>array('CmsLanguage.cmspage_id' =>$this->request->data['CmsPages']['cmspage_id'],'CmsLanguage.language_alias'=>$this->request->data['CmsPages']['language_alias'])
				));	
		
		if(isset($cms_languages[0]['CmsLanguage']['id'])){
			$this->redirect(array('controller'=>'CmsLanguages','action' => 'editcmscontent','admin'=>true,$this->request->data['CmsPages']['cmspage_id'],$cms_languages[0]['CmsLanguage']['id']));	
		
		}else{
			$this->redirect(array('controller'=>'CmsLanguages','action' => 'addcmscontent','admin'=>true,$this->request->data['CmsPages']['cmspage_id'],$this->request->data['CmsPages']['language_alias']));	
		}
		exit;
	}
}
