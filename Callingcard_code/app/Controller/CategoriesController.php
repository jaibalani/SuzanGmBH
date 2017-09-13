<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeEmail', 'Network/Email');

class CategoriesController extends AppController {

	public $uses = array();
	public $components = array('Auth');
	public $helpers = array('Html','Form');	

	public function beforeFilter($options = array()){
		parent::beforeFilter();
		$this->disableCache();			
		$this->Auth->allow('index');
	}

	public function admin_index(){
		
		$this->admin_redirect_to_dashboard_distributor();
		$this->set("title","Categories");
         ini_set('memory_limit', '256M'); 		
	}
    
    public function admin_json(){
    	
    	ini_set('memory_limit', '256M');
		if($this->request->is('ajax')){
			$this->autoRender = false;
             
            $this->loadModel('Card');
            $this->loadModel('Sale');
            $this->loadModel('PinsCard');
             
			$params = $this->request->query;

			$page 	= $params['page'];
			$limit  = $params['rows'];
			$sidx 	= $params['sidx'];
			$sort 	= $params['sord'];

			$conditions = array();
			$conditions['Category.cat_status <>'] = 3;
			if(isset($this->request->query['filters'])){
				$filters = json_decode($this->request->query['filters'], true);
				foreach($filters['rules'] as $each_filter){
					if($each_filter['field'] == 'Category.cat_title'){
						$conditions['Category.cat_title LIKE'] = '%'.Sanitize::escape($each_filter['data']). '%';
					}
					
					if($each_filter['field'] == 'Category.cat_status'){
					if($each_filter['data'] != '3')
					$conditions['Category.cat_status'] = Sanitize::clean($each_filter['data']);
			     	}
				}
			}
			
			$conditions['Category.cat_parent_id'] = NULL;
			
		
			if(!$limit){$limit = 10;}
			if(!$page){$page = 1;}
			if(!$sidx){$sidx = 1;}

			$count  = $this->Category->find('count',array('conditions'=>$conditions));
			if ($count > 0) { 
				$total_pages = ceil($count/$limit); 
			} else { 
				$total_pages = 1; 
			}
			if ($page > $total_pages) $page=$total_pages; 		
			$start = $limit*$page - $limit;	
			

			$resultSet = $this->Category->find('all', array('conditions'=>$conditions,'order'=>array($sidx.' '.$sort),'limit'=>$limit,'offset'=>$start));
			
			$response = new stdClass();
			$response->page 	= $page;
			$response->total 	= $total_pages;
			$response->records 	= $count;
			foreach($resultSet as $key=>$val){
				
				$delete_flag = 0;
				// No Child Can Delete The Category
				
				
				if(empty($val['Child']))
				{
                   $delete_flag = 1;
				}
                else
                {
                     $sub_cat_id = array();
                     foreach($val['Child'] as $sub_category)
                     {
                     	$sub_cat_id[] = $sub_category['cat_id'];
                     }
                     $get_card = $this->Card->find('all',array(
                     			 'conditions'=>array(
                     				'c_cat_id'=>$sub_cat_id
                     				)));
                     
                     /*Any One Card Have Sales History Or Pins Upload
                         Then this category can not be deleted
                     
                     */
                     $sales_pins_history = 0;    
                     foreach($get_card as $card_test)
                     {
                          $pins_count_delete = count($card_test['PinsCard']);
                          $sales_count_delete = $this->Sale->find('count',array('conditions'
                		  			=>array('s_c_id'=>$card_test['Card']['c_id'])));
                          
                          if($pins_count_delete || $sales_count_delete)
                          {
                          	$sales_pins_history =1;
                          	break; 
                          }
                     }
                     if($sales_pins_history == 0)
                     {
                     	$delete_flag = 1;
                     }	
                } 


				$len=strlen($val['Category']['cat_title']);
				if($len > 30)			
				$title = ucwords(strtolower(substr($val['Category']['cat_title'],0,35)."..."));				
				else
				$title = ucwords(strtolower($val['Category']['cat_title']));
				
				
				$this->loadModel('CategoriesLanguage');
				$lang = $this->CategoriesLanguage->find('first',array(
						'recursive' => -1,
			  		'conditions' => array('cl_cat_id'=>$val['Category']['cat_id'],'cl_alias'=>'en')
					)
				);
			//	prd($lang);
				$content = '';
				$link_manage = '';
				$cards_manage = '<a href="'.$this->webroot.'admin/Cards/index/'.$val['Category']['cat_id'].'"><img src="'.$this->webroot.'img/detail.png" alt="Manage Cards" title="Manage Cards" border="0" /> ('.$val['Category']['card_count'].') </a>';
				//$cards_manage = '';
				
				$child_count = '0';
				if(count($val['Child']) > 0){
					$child_count = count($val['Child']);
				}

				if(isset($lang['CategoriesLanguage']['cl_id']) && !empty($lang['CategoriesLanguage']['cl_id']))
				{
					$content = Router::url(array('controller'=>'CategoriesLanguages','action'=>'edit','admin'=>true,$val['Category']['cat_id'],$lang['CategoriesLanguage']['cl_id']));                   //'.$this->frontContImage('translate_admin.png','Edit').'
                    $link_manage .= '&nbsp;&nbsp;<span class="grid_link" title="edit" style="display:inline-block;" id="action_edit_'.$val['Category']['cat_id'].'" onclick="inplaceEdit('.$val['Category']['cat_id'].')"> Edit </span>';
                    $link_manage .= '&nbsp;&nbsp;<span class="grid_link" title="save" style="display:none;" id="action_save_'.$val['Category']['cat_id'].'" onclick="inplaceSave('.$val['Category']['cat_id'].')"> Save </span>';
                    $link_manage .= '&nbsp;&nbsp;<span class="grid_link" title="cancel" style="display:none;" id="action_cancel_'.$val['Category']['cat_id'].'" onclick="inplaceCancel('.$val['Category']['cat_id'].')"> Cancel </span>'; 
					$link_manage .= '&nbsp;&nbsp;<a class="grid_link" id="action_view_'.$val['Category']['cat_id'].'" title="view" href="'.$content.'" style="color:#438CE5 !important;" >View</a>';
					if($delete_flag)
					{
						$link_manage .= '&nbsp;&nbsp;<span class="grid_link" title="delete" style="display:inline-block;" id="action_del_'.$val['Category']['cat_id'].'" onclick="delete_main_category('.$val['Category']['cat_id'].')"> Delete </span>';
					}
				}

				$statusDropDown = '<div style="float:left; padding:4px;width:100%;"><select disabled id="action_status_'.$val['Category']['cat_id'].'" class="form-control" title="Change Status" onchange="changeStatus('.$val['Category']['cat_id'].',this,'.count($val['Child']).')" >';
				
				if($val['Category']['cat_status']==1){
					
					$statusDropDown .= '<option value="0">Disabled</option>
											<option selected="selected" value="1">Enabled</option>
										</select>';
					
				}else{
					$statusDropDown .= '<option selected="selected" value="0">Disabled</option>
											<option value="1">Enabled</option>
										</select></div>';
				}

				$show_child_links = '<a href="'.$this->webroot.'admin/Categories/index/'.$val['Category']['cat_id'].'"><img src="'.$this->webroot.'img/detail.png" alt="Child Categories" title="Child Categories" border="0" /> ('.$child_count.') </a>';
				if(!empty($id)){
					//Child category, show manage cards
					$manage = $cards_manage;
				}else{
					//Parent Category, show manage sub categories
					$manage = $show_child_links;
				}
				$response->rows[$key]['id']   = $val['Category']['cat_id'];			
				//$response->rows[$key]['cell'] = array($title,$manage, $link_manage,$status_link);
				$response->rows[$key]['cell'] = array($title, $link_manage,$statusDropDown);
			}

			echo json_encode($response);
		}else{
			// $this->_helper->redirector('accessdenied','index','admin');
		}
	}

	public function admin_subcategory($cat_parent_id=null){
        
		$this->admin_redirect_to_dashboard_distributor();
		$this->set('parent_id',$cat_parent_id);
		$this->set("title","Sub Categories");
		$res = $this->Category->find('list',array(
			'conditions' => array('cat_status !='=>2,'cat_parent_id'=>null),
			'fields' => array('cat_id','cat_title'),
			'recursive'	 => -1,
			'order'=>'cat_title asc'
		));
		
		foreach($res as $k => $v)
		$res[$k] = ucwords(strtolower($v));
		
		$this->set('catList',$res);

		if(count($res) == 0 ) {
			$this->Session->setFlash(__('Please first add category then subcategory'), 'default', array('class' => 'error'));
			$this->redirect(array('controller' => 'categories','action' => 'index','admin'=>true));	
		}

		if(!empty($cat_parent_id)) {
			if(in_array($cat_parent_id, array_keys($res))) {
			$this->set('parent_id',$cat_parent_id);
			}
			else {
				$this->redirect(array('controller' => 'categories','action' => 'subcategory','admin'=>true));		
			}
		}
	}

	public function admin_subcategory_json($cat_parent_id=null){
		if($this->request->is('ajax')){
			$this->autoRender = false;

			$params = $this->request->query;

			$page 	= $params['page'];
			$limit  = $params['rows'];
			$sidx 	= $params['sidx'];
			$sort 	= $params['sord'];
            
            $this->loadModel('Card');

			$conditions = array();
			$conditions['Category.cat_status !='] = 3;
			if(isset($this->request->query['filters'])){
				$filters = json_decode($this->request->query['filters'], true);
				foreach($filters['rules'] as $each_filter){
					if($each_filter['field'] == 'Category.cat_title'){
						$conditions['Category.cat_title LIKE'] = '%'.Sanitize::escape($each_filter['data']). '%';
					}
					if($each_filter['field'] == 'Category.cat_status'){
						if($each_filter['data'] != '3')
							$conditions['Category.cat_status'] = Sanitize::clean($each_filter['data']);
			   	}
				}
			}
			if(!empty($cat_parent_id)){
				//child category
				$conditions['Category.cat_parent_id'] = $cat_parent_id;
			}
			else {
				$conditions['NOT']['Category.cat_parent_id'] = null;
			}
		
			if(!$limit){$limit = 10;}
			if(!$page){$page = 1;}
			if(!$sidx){$sidx = 1;}

			$count  = $this->Category->find('count',array(
				'conditions'=>$conditions,
			));

			if ($count > 0) { 
				$total_pages = ceil($count/$limit); 
			} else { 
				$total_pages = 1; 
			}
			if ($page > $total_pages) $page=$total_pages; 		
			$start = $limit*$page - $limit;	
			
			$resultSet = $this->Category->find('all', array(
				'conditions'=>$conditions,
				'order'=>(array($sidx.' '.$sort)),
				'limit'=>$limit,
				'offset'=>$start
			));
			
			//prd($resultSet);
			$response = new stdClass();
			$response->page 	= $page;
			$response->total 	= $total_pages;
			$response->records 	= $count;
			
            //prd($resultSet);
            //echo $sidx.' '.$sort;exit;
			foreach($resultSet as $key=>$val)
			{
				$delete_flag = 0;
				// No Card Can Delete The Sub Category
				
				if(empty($val['Card']))
				{
					$delete_flag = 1;
				}
				else
				{
                     /*
                         Any One Card Have Sales History Or Pins Upload
                         Then this category can not be deleted
                     */
                     $sales_pins_history = 0;    
                     foreach($val['Card'] as $card_test)
                     {
                          $card_details = $this->Card->findByCId($card_test['c_id']);
                          $pins_count_delete = count($card_details['PinsCard']);
                          $sales_count_delete = $this->Sale->find('count',array('conditions'
                		  			=>array('s_c_id'=>$card_test['c_id'])));
                          
                          if($pins_count_delete || $sales_count_delete)
                          {
                          	$sales_pins_history =1;
                          	break; 
                          }
                     }
                     if($sales_pins_history == 0)
                     {
                     	$delete_flag = 1;
                     }	
                } 



				$len=strlen($val['Category']['cat_title']);
				if($len > 30)			
				$title = ucwords(strtolower(substr($val['Category']['cat_title'],0,35)."..."));				
				else
				$title = ucwords(strtolower($val['Category']['cat_title']));
				
				$this->loadModel('CategoriesLanguage');
				$lang = $this->CategoriesLanguage->find('first',array(
						'recursive' => -1,
			  		'conditions' => array('cl_cat_id'=>$val['Category']['cat_id'],'cl_alias'=>'en')
					)
				);
			    //	prd($lang);
				$content = '';
				$link_manage = '';
				$cards_manage = '<a href="'.$this->webroot.'admin/Cards/index/'.$val['Category']['cat_id'].'"><img src="'.$this->webroot.'img/detail.png" alt="Manage Cards" title="Manage Cards" border="0" /> ('.$val['Category']['card_count'].') </a>';
				
				if(isset($lang['CategoriesLanguage']['cl_id']) && !empty($lang['CategoriesLanguage']['cl_id'])){
					$content = Router::url(array('controller'=>'CategoriesLanguages','action'=>'edit_subcategory','admin'=>true,$val['Category']['cat_id'],$lang['CategoriesLanguage']['cl_id']));
					
                    $link_manage .= '&nbsp;&nbsp;<span class="grid_link" title="edit" style="display:inline-block;" id="action_edit_'.$val['Category']['cat_id'].'" onclick="inplaceEdit('.$val['Category']['cat_id'].')"> Edit </span>';
                    $link_manage .= '&nbsp;&nbsp;<span class="grid_link" title="save" style="display:none;" id="action_save_'.$val['Category']['cat_id'].'" onclick="inplaceSave('.$val['Category']['cat_id'].')"> Save </span>';
                    $link_manage .= '&nbsp;&nbsp;<span class="grid_link" title="cancel" style="display:none;" id="action_cancel_'.$val['Category']['cat_id'].'" onclick="inplaceCancel('.$val['Category']['cat_id'].')"> Cancel </span>'; 
					$link_manage .= '&nbsp;&nbsp;<a class="grid_link showhide" id="action_view_'.$val['Category']['cat_id'].'" title="view" href="'.$content.'" style="color:#438CE5 !important;">View</a>';
					if($delete_flag)
					{
						$link_manage .= '&nbsp;&nbsp;<span class="grid_link showhide" title="delete" style="display:inline-block;" id="action_del_'.$val['Category']['cat_id'].'" onclick="delete_sub_category('.$val['Category']['cat_id'].')"> Delete </span>';
					}
					
				}
					
				$child_count = '0';
				if(count($val['Child']) > 0){
					$child_count = count($val['Child']);
				}

				$statusDropDown = '<div style="float:left; padding:4px;width:100%;"><select disabled id="action_status_'.$val['Category']['cat_id'].'" class="form-control" title="Change Status" onchange="changeStatus('.$val['Category']['cat_id'].',this,'.count($val['Child']).')" >';
				
				if($val['Category']['cat_status']==1){
					
					$statusDropDown .= '<option value="0">Disabled</option>
											<option selected="selected" value="1">Enabled</option>
										</select>';
					
				}else {
					$statusDropDown .= '<option selected="selected" value="0">Disabled</option>
											<option value="1">Enabled</option>
										</select></div>';
				}

				$len=strlen($val['Parent']['cat_title']);
				if($len > 30){
					$category = ucwords(strtolower(substr($val['Parent']['cat_title'],0,35)."..."));	
				}
				else {
					$category = ucwords(strtolower($val['Parent']['cat_title']));	
				}
				
				
				$response->rows[$key]['id']   = $val['Category']['cat_id'];			
				$response->rows[$key]['cell'] = array($title,$category,$link_manage,$statusDropDown);
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
			if($this->Category->deleteAll(array('Category.cat_id IN ('.$ids.')'))) {
					echo 1;exit;	
			}else{
				echo 0; exit;
			}
			$this->_updatecountercacheparent();
		}
	}	
    
    public function admin_delete_main_category($cat_id){

        $this->admin_redirect_to_dashboard_distributor();
        $cat_detail =$this->Category->findByCatId($cat_id);

		if($cat_detail)
		{
			$cat_name = ucwords(strtolower($cat_detail['Category']['cat_title']));
			
			$delete_category = $this->Category->delete($cat_id);
	        if($delete_category)
	        {
	          $this->Session->setFlash(__('You have successfully deleted the category <b>'.$cat_name),'default', array('class'=>'success'));
	        } 
	        else
	        {
	          $this->Session->setFlash(__('You could not delete the category <b>'.$cat_name.'</b> now. Please try again later.'), 'default', array('class'=>'error'));
	        }
		} 
		else
		{
          $this->Session->setFlash(__('Invalid category selection.'),'default', array('class'=>'error'));
		}
		
		 $this->redirect(array('action'=>'index'));
    }

    public function admin_delete_sub_category($cat_id){

        $this->admin_redirect_to_dashboard_distributor();
        $cat_detail =$this->Category->findByCatId($cat_id);

		if($cat_detail)
		{
			$cat_name = ucwords(strtolower($cat_detail['Category']['cat_title']));
			
			$delete_category = $this->Category->delete($cat_id);
	        if($delete_category)
	        {
	          $this->Session->setFlash(__('You have successfully deleted the sub category <b>'.$cat_name),'default', array('class'=>'success'));
	        } 
	        else
	        {
	          $this->Session->setFlash(__('You could not delete the sub category <b>'.$cat_name.'</b> now. Please try again later.'), 'default', array('class'=>'error'));
	        }
		} 
		else
		{
          $this->Session->setFlash(__('Invalid sub category selection.'),'default', array('class'=>'error'));
		}
		
		 $this->redirect(array('action'=>'subcategory'));
    }


	public function admin_changestatus(){
		$this->admin_redirect_to_dashboard_distributor();
		if($this->request->is('ajax')){	
			$id 	= $this->request->data['id'];
			$status = $this->request->data['st'];
			$child_exists = $this->request->data['child_count'];
			unset($this->request->data);
			$this->Category->set(array('Category' => array('cat_id'=>$id, 'cat_status'=>$status)));
			if($this->Category->save($this->request->data)){
				if($child_exists > 0){
						$this->Category->updateAll(
								array('Category.cat_status' 	 => $status), 
								array('Category.cat_parent_id' => $id)
						);
				}
				echo 1; exit;
			}else{
				echo 0; exit;
			}
		}
			
	}
	
	public function admin_checklanguage()
	{	
		$this->admin_redirect_to_dashboard_distributor();
		$this->loadModel('CategoriesLanguage');
    	$cat_languages = $this->CategoriesLanguage->find('first',array(
						'conditions' =>array('CategoriesLanguage.cl_cat_id' =>$this->request->data['Category']['cl_cat_id'],'CategoriesLanguage.cl_alias'=>$this->request->data['Category']['cl_alias'])
				));	
		//	prd($cat_languages);
		if(isset($cat_languages['CategoriesLanguage']['cl_id']) && !empty($cat_languages['CategoriesLanguage']['cl_id'])){
			$this->redirect(array('controller'=>'CategoriesLanguages','action' => 'edit','admin'=>true,$this->request->data['Category']['cl_cat_id'],$cat_languages['CategoriesLanguage']['cl_id']));	
		
		}else{
			$this->redirect(array('controller'=>'CategoriesLanguages','action' => 'add','admin'=>true));	
		}
		exit;
	}

	public function admin_checklanguagesubcat()
	{	
		$this->admin_redirect_to_dashboard_distributor();
		$this->loadModel('CategoriesLanguage');
    	$cat_languages = $this->CategoriesLanguage->find('first',array(
						'conditions' =>array('CategoriesLanguage.cl_cat_id' =>$this->request->data['Category']['cl_cat_id'],'CategoriesLanguage.cl_alias'=>$this->request->data['Category']['cl_alias'])
				));	
		//	prd($cat_languages);
		if(isset($cat_languages['CategoriesLanguage']['cl_id']) && !empty($cat_languages['CategoriesLanguage']['cl_id'])){
			$this->redirect(array('controller'=>'CategoriesLanguages','action' => 'edit_subcategory','admin'=>true,$this->request->data['Category']['cl_cat_id'],$cat_languages['CategoriesLanguage']['cl_id']));	
		
		}else{
			$this->redirect(array('controller'=>'CategoriesLanguages','action' => 'add_subcategory','admin'=>true));	
		}
		exit;
	}
	
     public function admin_inline_category() {
        
        $this->admin_redirect_to_dashboard_distributor();
        $req = $this->request;
        $this->loadModel('CategoriesLanguage');
        
        if($req->is('Ajax')){
            
            $saveData =array();
            $reqData = $req->data;
            
            $cat_data = $this->Category->findByCatId($reqData['id']);
            
            $parent_id = $cat_data['Category']['cat_parent_id'];
            if($parent_id == NULL)
            {
            	// Main Category is editing
               // $already_exists = $this->Category->hasAny(array('cat_title' => trim( $reqData['Category_cat_title']), 'cat_id <>'=>$reqData['id'],'cat_parent_id' => NULL));
                

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
	                    'conditions'=>array('Category.cat_id <>' => $reqData['id'],
	                    					'Category.cat_parent_id' => NULL,
	                    					'CategoriesLanguage.cl_alias' => 'en',
	                    					'CategoriesLanguage.cl_title'=>trim($reqData['Category_cat_title'])),
	                    'fields'=>array('CategoriesLanguage.*','Category.*')
	                 ));  




                if($already_exists)
                {
                	echo "Category title already exists";
                    exit;
                }
                
            }
            else
            {
            	// Sub Category is editing
                //$already_exists = $this->Category->hasAny(array('cat_title' => trim( $reqData['Category_cat_title']), 'cat_id <>'=>$reqData['id'],'cat_parent_id' => $parent_id));
               
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
	                    'conditions'=>array('Category.cat_id <>' => $reqData['id'],
	                    					'Category.cat_parent_id' => $parent_id,
	                    					'CategoriesLanguage.cl_alias' => 'en',
	                    					'CategoriesLanguage.cl_title'=>trim($reqData['Category_cat_title'])),
	                    'fields'=>array('CategoriesLanguage.*','Category.*')
	                 )); 

                if($already_exists)
                {
                	echo "Sub category title already exist in this category.";
                    exit;
                }
            }

            $saveData['Category']['cat_id'] = $reqData['id'];
            $saveData['Category']['cat_title'] = trim($reqData['Category_cat_title']);
            $this->Category->set($saveData);
           
            if($this->Category->validates())
            {
                $this->Category->save();
                $language_data = $this->CategoriesLanguage->find('first',
                											array('conditions'=>array('cat_id'=>$reqData['id'],'cl_alias'=>'en')));
 
                $language_data['CategoriesLanguage']['cl_title']  =trim($reqData['Category_cat_title']);
                
                $this->CategoriesLanguage->save($language_data);
                /*$this->CategoriesLanguage->updateAll(
                    array(
                        'CategoriesLanguage.cl_title' => "'" . $reqData['Category_cat_title'] ."'" 
                    ),
                    array(
                        'CategoriesLanguage.cl_cat_id' => $reqData['id'],
                        'CategoriesLanguage.cl_alias' => 'en',
                        )
                    );*/
                
                echo "1";
            }
            else
            {
                echo "Can not be updated.";
            }
            
        }
        else
        {
            echo "Invalid request";
        }
        
        exit;
	}


}
