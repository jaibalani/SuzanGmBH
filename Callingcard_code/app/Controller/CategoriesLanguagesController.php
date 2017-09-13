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

class CategoriesLanguagesController extends AppController {

	public function beforeFilter() {
			parent::beforeFilter();
			$this->Auth->allow('');
	}	

	public function admin_edit($cat_id=NULL,$cl_id=NULL){
		$this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout', __('Edit - Category'));
		$this->set("title",__('Edit - Category'));	
		$this->LoadModel('Language');
		$lang_list = $this->Language->find('list',array(
				'recursive'=>-1,
				'fields'=>array('alias','title'),	
				'conditions'=>array('status'=>1),
		));
		$this->set('lang_list',$lang_list);
		//for language select box header element, pass parameters
		$ids = $cat_id;
		$this->set('form_name','Category');
		$this->set('controller_name','Categories');
		$this->set('ids_name','cl_cat_id');
		$this->set('ids_val',$cat_id);
		$this->set('lan_name','cl_alias');
		
   		$this->set('id',$cl_id);
		$this->set('cl_cat_id',$cat_id);
		
		$cat_lang= $this->CategoriesLanguage->find('first',array(
				'conditions'=>array('cl_id'=>$cl_id),
		));
		
		
		if (count($cat_lang)==0) {
			$this->Session->setFlash('Invalid Category Id', 'default', array('class' => 'error'));
			//$this->redirect(array('action' => 'index','controller'=>'Categories'));
		}
		$this->set('language_alias',$cat_lang['CategoriesLanguage']['cl_alias']);
		
		//prd($cat_lang);
		if(!empty($this->request->data)) 
		{
			
			$validate = 1;
			//if($cat_lang['CategoriesLanguage']['cl_alias'] == 'en'){ //if english language check if title already exist
			 $this->loadModel('Category');
				
				/*$already_exists = $this->Category->hasAny(
							rray('cat_title' => trim($this->request->data['CategoriesLanguage']['cl_title']), 
			 				'cat_id <>'=>$cat_id,'cat_parent_id' => NULL));*/
				
				$already_exists = $this->Category->find('first',
			      array(
				   'recursive' => -1,
	                'joins' => array(
						array(
							'table' => 'ecom_categories_languages',
							'alias' => 'CategoriesLanguage',
							'type' => 'INNER', 	
							'conditions' =>'Category.cat_id = CategoriesLanguage.cl_cat_id' 
	                         )
						),
	                    'conditions'=>array('Category.cat_id <>' => $cat_id,
	                    					'Category.cat_parent_id' => NULL,
	                    					'CategoriesLanguage.cl_alias' => $cat_lang['CategoriesLanguage']['cl_alias'],
	                    					'CategoriesLanguage.cl_title'=>trim($this->request->data['CategoriesLanguage']['cl_title'])),
	                    'fields'=>array('CategoriesLanguage.*','Category.*')
	                 )); 

            if($already_exists){
				$validate = 0;
				$this->Session->setFlash('Category title already exist in German.', 'default', array('class' => 'error'));
				return;
			}
			//}
			if($validate){ 
				
				$this->request->data['CategoriesLanguage']['cl_title'] = trim($this->request->data['CategoriesLanguage']['cl_title']);
				if($this->CategoriesLanguage->save($this->request->data)){
				
				if($cat_lang['CategoriesLanguage']['cl_alias'] == 'en')
				{
					$this->loadModel('Category');
					$array_data = $this->Category->findBycatId($cat_id);
					$array_data['Category']['cat_title'] = trim($this->request->data['CategoriesLanguage']['cl_title']);
					$array_data['Category']['cat_desc'] = $this->request->data['CategoriesLanguage']['cl_desc'];
					$this->Category->save($array_data);
				}
				$this->Session->setFlash('Category has been edited successfully.', 'default', array('class' => 'success'));
				//$this->redirect(array('controller'=>'Categories','action' => 'index'));
				
			}else{
				
				$this->Session->setFlash('Category could not be saved. Please, try again.', 'default', array('class' => 'error'));
				
			}
		 }
		}
		
		if (empty($this->request->data)) {
			
      $this->request->data = $this->CategoriesLanguage->read(null, $cl_id);
			
		}
	}
	

	public function admin_add() {
		$this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout', "Add Category");
		$this->set('title', "Add Category");
		//Check if such category exists
		
		if (!empty($this->request->data)) {
			$data = $this->request->data;
			
			$i = 0;
			$this->LoadModel('Language');
			$lang_list = $this->Language->find('list',array(
					'recursive'=>-1,
					'fields'=>array('alias','title'),	
					'conditions'=>array('status'=>1),
			));
			$data['Category']['cat_title'] = trim($data['Category']['cat_title']);
			foreach($lang_list as $alias=>$val){
				$data['CategoriesLanguage'][$i]['cl_alias'] = $alias;
				$data['CategoriesLanguage'][$i]['cl_title'] = trim($data['Category']['cat_title']);
				$data['CategoriesLanguage'][$i]['cl_desc']  = $data['Category']['cat_desc'];
				$i++;			
			}
			//prd($data);
			$this->loadModel('Category');
			$this->loadModel('CategoriesLanguage');
            

			/*$already_exists = $this->Category->hasAny(array('cat_title' => trim($data['Category']['cat_title']),
															'cat_parent_id' => NULL));*/


			$already_exists = $this->Category->find('first',
				   array(
					   'recursive' => -1,
                        'joins' => array(
							array(
								'table' => 'ecom_categories_languages',
								'alias' => 'CategoriesLanguage',
								'type' => 'INNER', 	
								'conditions' =>'Category.cat_id = CategoriesLanguage.cl_cat_id' 
                                 )
							),
                            'conditions'=>array('Category.cat_parent_id' => NULL,
                            					'CategoriesLanguage.cl_title'=>trim($data['Category']['cat_title'])),
                            'fields'=>array('CategoriesLanguage.*','Category.*')
                         ));
            
			$this->Category->create();			

			if(!$already_exists){
				if($this->Category->validates()){

					  if ($this->Category->saveAssociated($data)) 
					  {
							$new_id = $post_id=$this->Category->getLastInsertId();
                            $get_lnaguage_data = $this->CategoriesLanguage->find('first',array('conditions'=>array('cl_cat_id'=>$new_id,'cl_alias'=>'en')));
							
							$this->Session->setFlash('Category has been added successfully.', 'default', array('class' => 'success'));
							$this->redirect(array('controller'=>'CategoriesLanguages','action' => 'add'));
					
							//$this->redirect(array('controller'=>'CategoriesLanguages','action' => 'edit',$new_id ,$get_lnaguage_data['CategoriesLanguage']['cl_id']));
					   }
					   else
					   {
							$this->Session->setFlash('Category could not be added. Please, try again.', 'default', array('class' => 'error'));
					   }
			  }
			}else{
				$this->Session->setFlash('Category title already exist in German or English language.', 'default', array('class' => 'error'));	
			}
		}
	}
	
	public function admin_add_subcategory($parent_id=NULL) {
		$this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout', "Add Sub Category");
		$this->set('title', "Add Sub Category");
		//Check if such category exists
		
		$this->loadModel('Category');
		$res = $this->Category->find('list',array(
			'conditions' => array('cat_status'=>1,'cat_parent_id'=>null),
			'fields' => array('cat_id','cat_title'),
			'order'=>'cat_title asc',
			'recursive'	 => -1
		));
		
		foreach ($res as $key => $value) {
			$res[$key] = ucwords(strtolower($value));
		}

		$this->set('catList',$res);

		if(count($res)==0){
				$this->Session->setFlash(__('Unauthorized access.'), 'default', array('class' => 'error'));
				$this->redirect(array('controller'=>'Categories','action' => 'index','admin'=>true));	
		}
		
		if(!empty($parent_id) && in_array($parent_id, array_keys($res))) {
			$this->set('parent_id',$parent_id);
			
		}
		
		

		if (!empty($this->request->data)) {
			$data = $this->request->data;
			
			if(empty($this->request->data['Category']['cat_parent_id']))
			{
				$this->Session->setFlash('Please select the category.', 'default', array('class' => 'error'));
			    return;
			}
			//$data['Category']['cat_parent_id'] = $parent_id;
			
			$i = 0;
			$this->LoadModel('Language');
			$lang_list = $this->Language->find('list',array(
					'recursive'=>-1,
					'fields'=>array('alias','title'),	
					'conditions'=>array('status'=>1),
			));
			
			$data['Category']['cat_title'] = trim($data['Category']['cat_title']);
			foreach($lang_list as $alias=>$val){
				$data['CategoriesLanguage'][$i]['cl_alias'] = $alias;
				$data['CategoriesLanguage'][$i]['cl_title'] = trim($data['Category']['cat_title']);
				$data['CategoriesLanguage'][$i]['cl_desc']  = $data['Category']['cat_desc'];	
				$i++;			
			}
			//prd($data);
			$this->loadModel('Category');
			/*$already_exists = $this->Category->hasAny(array('cat_title' => trim($data['Category']['cat_title']),
															'cat_parent_id' => $data['Category']['cat_parent_id'],					
															));*/
            
			$already_exists = $this->Category->find('first',
			   array(
				   'recursive' => -1,
	                'joins' => array(
						array(
							'table' => 'ecom_categories_languages',
							'alias' => 'CategoriesLanguage',
							'type' => 'INNER', 	
							'conditions' =>'Category.cat_id = CategoriesLanguage.cl_cat_id' 
	                         )
						),
	                    'conditions'=>array('Category.cat_parent_id' => $data['Category']['cat_parent_id'],
	                    					'CategoriesLanguage.cl_title'=>trim($data['Category']['cat_title'])),
	                    'fields'=>array('CategoriesLanguage.*','Category.*')
	                 )); 
           
			$this->Category->create();				
			if(!$already_exists){
				if($this->Category->validates())
				{
					if ($this->Category->saveAssociated($data)) 
					 {
							$this->Session->setFlash('Sub category has been added successfully.', 'default', array('class' => 'success'));
					 		
					 		$new_id = $post_id=$this->Category->getLastInsertId();
                            $get_lnaguage_data = $this->CategoriesLanguage->find('first',array('conditions'=>array('cl_cat_id'=>$new_id,'cl_alias'=>'en')));
		                    $this->redirect(array('controller'=>'CategoriesLanguages','action' => 'add_subcategory'));
					
							//$this->redirect(array('controller'=>'CategoriesLanguages','action' => 'edit_subcategory',$new_id ,$get_lnaguage_data['CategoriesLanguage']['cl_id']));

					 		//$this->redirect(array('controller'=>'Categories','action' => 'subcategory',$parent_id));
					}
					else
					{
							$this->Session->setFlash('Sub category could not be added. Please, try again.', 'default', array('class' => 'error'));
					}
			  }
			}else{
				$this->Session->setFlash('Sub category title already exist in this category in German or english language.', 'default', array('class' => 'error'));
			}
		}
	}

	public function admin_edit_subcategory($cat_id=NULL,$cl_id=NULL){
        
        $this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout', __('Edit Sub Category'));
		$this->set("title",__('Edit Sub Category'));

		$this->loadModel('Category');
		$res = $this->Category->find('list',array(
			'conditions' => array('cat_status'=>1,'cat_parent_id'=>null),
			'fields' => array('cat_id','cat_title'),
			'order'=>'cat_title asc',
			'recursive'	 => -1
		));
        
        foreach ($res as $key => $value) {
			$res[$key] = ucwords(strtolower($value));
		}
		
		$this->set('catList',$res);

		if(count($res)==0){
			$this->Session->setFlash(__('Unauthorized access.'), 'default', array('class' => 'error'));
			$this->redirect(array('controller'=>'Categories','action' => 'index','admin'=>true));	
		}

		$this->LoadModel('Language');
		$lang_list = $this->Language->find('list',array(
				'recursive'=>-1,
				'fields'=>array('alias','title'),	
				'conditions'=>array('status'=>1),
		));
		$this->set('lang_list',$lang_list);
		//for language select box header element, pass parameters
		$ids = $cat_id;
		$this->set('form_name','Category');
		$this->set('controller_name','Categories');
		$this->set('ids_name','cl_cat_id');
		$this->set('ids_val',$cat_id);
		$this->set('lan_name','cl_alias');
		
   		$this->set('id',$cl_id);
		$this->set('cl_cat_id',$cat_id);
		
		$cat_lang= $this->CategoriesLanguage->find('first',array(
				'conditions'=>array('cl_id'=>$cl_id),
		));
		
		
		if (count($cat_lang)==0) {
			$this->Session->setFlash('Invalid Sub Category Id', 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'subcategory','controller'=>'Categories'));
		}

		$this->set('language_alias',$cat_lang['CategoriesLanguage']['cl_alias']);
		$parnet_id = $cat_lang['Category']['cat_parent_id'];
		$this->set('parnet_id',$parnet_id);
		
		//prd($cat_lang);
		if(!empty($this->request->data)) {
           

           if(empty($this->request->data['CategoriesLanguage']['cat_parent_id']))
		   {
				//$this->set('id',$this->request->data['CategoriesLanguage']['cl_id']);
				$this->set('parnet_id',$this->request->data['CategoriesLanguage']['cat_parent_id']);
				$this->Session->setFlash('Please select the category.', 'default', array('class' => 'error'));
			    return;
		   }

           // prd($this->request->data);
			$validate = 1;
			//if($cat_lang['CategoriesLanguage']['cl_alias'] == 'en'){ //if english language check if title already exist
			$this->loadModel('Category');
			/*$already_exists = $this->Category->hasAny(
								array('cat_title' => trim($this->request->data['CategoriesLanguage']['cl_title']), 
										'cat_id <>'=>$cat_id,
										'cat_parent_id'=>$this->request->data['CategoriesLanguage']['cat_parent_id']));
				
			*/
                
                //prd($cat_lang['CategoriesLanguage']['cl_alias']);
				$already_exists = $this->Category->find('all',
			      array(
				   'recursive' => -1,
	                'joins' => array(
						array(
							'table' => 'ecom_categories_languages',
							'alias' => 'CategoriesLanguage',
							'type' => 'INNER', 	
							'conditions' =>'Category.cat_id = CategoriesLanguage.cl_cat_id' 
	                         )
						),
	                    'conditions'=>array('Category.cat_id <>' => $cat_id,
                 	                    	'CategoriesLanguage.cl_alias' => $cat_lang['CategoriesLanguage']['cl_alias'],
	                    					'Category.cat_parent_id' => $this->request->data['CategoriesLanguage']['cat_parent_id'],
	                    					'CategoriesLanguage.cl_title'=>trim($this->request->data['CategoriesLanguage']['cl_title'])),
	                    'fields'=>array('CategoriesLanguage.*','Category.*')
	                 )); 
                 
                //prd($already_exists); 
                
				if($already_exists)
				{
					$validate = 0;
					$this->Session->setFlash('Sub category title already exist in this category.', 'default', array('class' => 'error'));		
				    return;
				}
			//}
			
			if($validate){ 
				//prd($this->request->data);
				$this->request->data['CategoriesLanguage']['cl_title'] = trim($this->request->data['CategoriesLanguage']['cl_title']);
				if($this->CategoriesLanguage->save($this->request->data)){
				
					if($cat_lang['CategoriesLanguage']['cl_alias'] == 'en')
					{
						$this->loadModel('Category');
						$array_data = $this->Category->findBycatId($cat_id);
						$array_data['Category']['cat_title'] = trim($this->request->data['CategoriesLanguage']['cl_title']);
						$array_data['Category']['cat_desc'] = $this->request->data['CategoriesLanguage']['cl_desc'];
						$array_data['Category']['cat_parent_id'] = $this->request->data['CategoriesLanguage']['cat_parent_id'];
						$this->Category->save($array_data);
					}
					else
					{
						$this->loadModel('Category');
						$array_data = $this->Category->findBycatId($cat_id);
						//$array_data['Category']['cat_title'] = $this->request->data['CategoriesLanguage']['cl_title'];
						//$array_data['Category']['cat_desc'] = $this->request->data['CategoriesLanguage']['cl_desc'];
						$array_data['Category']['cat_parent_id'] = $this->request->data['CategoriesLanguage']['cat_parent_id'];
						$this->Category->save($array_data);
					}
				    $this->set('language_alias',$cat_lang['CategoriesLanguage']['cl_alias']);
					$this->set('parnet_id',$this->request->data['CategoriesLanguage']['cat_parent_id']);
				    $this->Session->setFlash('Sub category has been edited successfully.', 'default', array('class' => 'success'));
				//$this->redirect(array('controller'=>'Categories','action' => 'subcategory'));
				
			}else{
				$this->Session->setFlash('Sub category could not be saved. Please, try again.', 'default', array('class' => 'error'));
			}
		 }
		}
		
		if (empty($this->request->data)) {
      		$this->request->data = $this->CategoriesLanguage->read(null, $cl_id);			
		}
	}
	
}