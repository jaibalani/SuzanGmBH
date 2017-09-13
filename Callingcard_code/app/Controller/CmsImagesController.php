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

class CmsImagesController extends AppController {

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
	public $name = 'CmsImages';

/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
	//public $uses = array('Cmspage');

/**
 * Admin index
 *
 * @return void
 * @access public
 */

 public function admin_add($id=NULL){
		
		$this->set('title_for_layout', "Upload Cms Page Image");
		$this->set('cmspages_id',$id);
		
		if(!empty($this->request->data['CmsImage']['image']['name'])){
			
			$img_ext = explode(".",$this->request->data['CmsImage']['image']['name']);
     
			if($img_ext[1]=="jpeg" || $img_ext[1]=="jpg" || $img_ext=="png" || $img_ext=="gif"){
				
      		list($width, $height) = getimagesize($this->request->data['CmsImage']['image']['tmp_name']);
				
				if($width==600 && $height==228){
				
					$newFileName = time().'_cmspage_'.$this->request->data['CmsImage']['image']['name'];
					$destination = WWW_ROOT .'img/admin_uploads/cms_uploads/'.$newFileName; 
					$moved = move_uploaded_file($this->request->data['CmsImage']['image']['tmp_name'], $destination);
					
					$this->request->data['CmsImage']['image'] = $newFileName;
					
					if($this->CmsImage->save($this->request->data)){
						
						$this->Session->setFlash('Cms Page Image has been uploaded.', 'default', array('class' => 'success'));
						$this->redirect(array('controller'=>'CmsPages','action' => 'index'));
					} else {
						$this->Session->setFlash('Cms Page could not be uploaded. Please, try again.', 'default', array('class' => 'error'));
					}
					
				}else{
					$this->Session->setFlash('Please provide image of size 600 X 228 pixels.', 'default', array('class' => 'error'));
				}
			
			}else{				
				$this->Session->setFlash(__('Invalid file format. Please provide only .jpeg,.png,.gif,.jpg images'), 'default', array('class' => 'error'));
				
			}			
		}
		
	}

	public function admin_delete($cmpage_id=NULL,$id=NULL){
	
	 if($id==NULL || $cmpage_id==NULL){
		 $this->Session->setFlash(__('Invalid id.'), 'default', array('class' => 'success'));
		 $this->redirect(array('controller'=>'CmsPages','action' => 'index'));
	 }
	 
	 $cms_image = $this->CmsImage->find('first',array(
			  'conditions' =>array('CmsImage.id =' =>$id)
			)
		);
	
	 if($this->CmsImage->delete($id)){
		$file_path = WWW_ROOT.'img'. DS . 'admin_uploads'. DS .'cms_uploads'. DS .$cms_image['CmsImage']['image']; 
		unlink($file_path); 
		$this->Session->setFlash(__('Cms Page Image has been deleted.'), 'default', array('class' => 'success'));
		$this->redirect(array('controller'=>'CmsPages','action' => 'edit',$cmpage_id,$id));
		
	 }else{
		 
	 	$this->Session->setFlash('Cms Page Image could not be deleted. Please, try again.', 'default', array('class' => 'error'));		
	 }
	
	}
}
