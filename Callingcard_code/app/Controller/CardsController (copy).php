<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeEmail', 'Network/Email');

include(APP.'Vendor/PHPExcel/Classes/PHPExcel.php');
include(APP.'Vendor/PHPExcel/Classes/PHPExcel/IOFactory.php');

class CardsController extends AppController
{
	public $uses = array();
		
	public $components = array('Auth','RequestHandler','Commonfunctions');
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->disableCache();			
	}


	public function admin_contentUpload()
	{		
		App::import('Vendor', '', array('file' => 'Card_UploadHandler.php'));
		$upload_handler = new UploadHandler();			
		exit;
	}

	public function admin_get_subcat()
	{
		$this->loadModel('Category');
		if($this->request->is('ajax'))
		{
			$data = $this->request->data;
			$id 	= $data['id'];
			$conditions =array();
			$conditions['cat_status'] = 1;
			if($id)
			$conditions['cat_parent_id'] = $id;
			else
			$conditions['cat_parent_id <>'] = NULL;
			
			$sub_cat_names = $this->Category->find('list',array(
					'fields'		 => array('cat_id','cat_title'),
					'conditions' => $conditions,
					'order'=>'cat_title asc'
			));
			
			foreach($sub_cat_names as $k => $v)
			$sub_cat_names[$k] = ucwords(strtolower($v));

			echo json_encode($sub_cat_names);
			exit;
		}
	}
	
	public function admin_get_cards()
	{
		if($this->request->is('ajax'))
		{
			$data = $this->request->data;
			$id 	= $data['id'];
			$conditions =array();
			$conditions['c_status'] = 1;
			if($id)
			$conditions['c_cat_id'] = $id;
			
			$cards = $this->Card->find('list',array(
					'fields'		 => array('c_id','c_title'),
					'conditions' => $conditions,
					'order'=>'c_title asc'
			));
			
			foreach($cards as $k => $v)
			$cards[$k] = ucwords($v);
			
			echo json_encode($cards);
			exit;
		}
	}
	
	public function admin_get_cards_parent_cat()
	{
        $this->loadModel('Category');
		if($this->request->is('ajax'))
		{
			$data = $this->request->data;
			$id 	= $data['id'];
		
			$conditions =array();
			$conditions['cat_status'] = 1;
			if($id)
			$conditions['cat_parent_id'] = $id;
			else
			$conditions['cat_parent_id <>'] = NULL;
			
			$sub_cat_names = $this->Category->find('list',array(
					'fields'		 => array('cat_id','cat_title'),
					'conditions' => $conditions,
					'order'=>'cat_title asc'
			));
            
			foreach($sub_cat_names as $k => $v)
   			$sub_cat_names[$k] = ucwords(strtolower($v)); 

			$conditions =array();
			$conditions['c_status'] = 1;
				
			if(empty($sub_cat_names) && !empty($id))
			{
				echo json_encode($sub_cat_names);
			    exit;
			}
			else if(empty($id))
			{
				$cards = $this->Card->find('list',array(
					'fields'		 => array('c_id','c_title'),
					'conditions' => $conditions,
					'order'=>'c_title asc'
				));
				
				foreach($cards as $k => $v)
    			$cards[$k] = ucwords(strtolower($v));
    
				echo json_encode($cards);
				exit;
			}
			else
			{
				 $cat_array =array();
				 foreach($sub_cat_names as $k=>$v)
				 {
					$cat_array[] = $k;
				 }
				 $conditions['c_cat_id'] = $cat_array;
			     $cards = $this->Card->find('list',array(
					'fields'		 => array('c_id','c_title'),
					'conditions' => $conditions,
					'order'=>'c_title asc'
				));
				
				foreach($cards as $k => $v)
     			$cards[$k] = ucwords(strtolower($v));
    
				echo json_encode($cards);
				exit;
			}
		}
	}
	public function admin_index()
	{
		$title = 'Manage Cards';
		$this->set("title",$title);
		$selchar = 'All';
		$rate = '';
		$this->loadModel('Category');
		
		$res = $this->Category->find('list',array(
			'conditions' => array('cat_status'=>1,'cat_parent_id'=>null),
			'fields' => array('cat_id','cat_title'),
			'recursive'	 => -1,
			'order'=>'cat_title asc'
		));
        
		foreach($res as $k => $v)
		$res[$k] = ucwords(strtolower($v));
		
        if($this->request->is('Post')) {

			$data = $this->request->data ;

			$url = '';
			if(isset($data['Card']['cat_id']) && !empty($data['Card']['cat_id']) ) {
				$subCatConditions['Category.cat_parent_id'] = $data['Card']['cat_id'];
				$url .= '/cat_id:'.$data['Card']['cat_id'];
			}

			if(isset($data['Card']['sub_cat_id']) && !empty($data['Card']['sub_cat_id']) ) {
				$url .= '/sub_cat_id:'.$data['Card']['sub_cat_id'];
			}


			if(isset($data['Card']['selchar']) && !empty($data['Card']['selchar']) ) {
				$url .= '/char:'.$data['Card']['selchar'];
			}

			if(isset($data['Card']['card_rate']) && !empty($data['Card']['card_rate']) ) {
				$url .= '/rate:'.$data['Card']['card_rate'];
				
			}

			$this->redirect(array('controller'=>'Cards','action'=>'index'.$url));
		}


		$named = $this->request->named;

		$subCatConditions = array();
		$subCatConditions['Category.cat_status'] = 1;
		$subCatConditions['Category.cat_parent_id <>'] = null;

		if(isset($named['cat_id']) && !empty($named['cat_id'])) {
			$this->set('cat_id',$named['cat_id']);	
			$subCatConditions['cat_parent_id'] = $named['cat_id'];
		}
        
        $subCatConditions['Category_Parent.cat_status'] = 1;
        $resSubCat = $this->Category->find('list',array(
						'conditions' => $subCatConditions,
						'fields' => array('cat_id','cat_title'),
						'recursive'	 => -1,
						'joins' => array(
							array(
								'table' => 'ecom_categories',
								'alias' => 'Category_Parent',
								'type' => 'inner',
								'conditions' => 'Category_Parent.cat_id =Category.cat_parent_id'
							)
						),
						'order'=>'cat_title asc'
					));

        foreach($resSubCat as $k => $v)
		$resSubCat[$k] = ucwords(strtolower($v));
		
		if(isset($named['rate']) && !empty($named['rate'])) {
			$rate = $named['rate'];
		}

		if(isset($named['char']) && !empty($named['char'])) {
			$selchar = $named['char'];
		}

		if(isset($named['sub_cat_id']) && !empty($named['sub_cat_id']) && in_array($named['sub_cat_id'], array_keys($resSubCat))) {
			$this->set('sub_cat_id',$named['sub_cat_id']);	
		}
		
		if($resSubCat)
		{
			$cardRes = $this->Card->find('list',array(
				'conditions' => array('Card.c_status'=>1,'Card.c_cat_id'=>array_keys($resSubCat)),
				'fields' => array('c_id','c_title'),
				'recursive'	 => -1,
				'order'=>'c_title'
			));
		}
		else
		{
			$cardRes = $this->Card->find('list',array(
				'conditions' => array('Card.c_status'=>1),
				'fields' => array('c_id','c_title'),
				'recursive'	 => -1,
				'order'=>'c_title'
			));
		}
        
		foreach($cardRes as $k => $v)
		$cardRes[$k] = ucwords(strtolower($v));

		$this->set('cardList',$cardRes);	
		$this->set('rate',$rate);	
		$this->set('selchar',$selchar);	
		$this->set('catList',$res);
		$this->set('subCatList',$resSubCat);
         

	}
	
	public function admin_json($id=null)
	{
		$this->loadModel('Category');
		if($this->request->is('ajax'))
		{
			$this->autoRender = false;

			$params = $this->request->query;

			$page 	= $params['page'];
			$limit  = $params['rows'];
			$sidx 	= $params['sidx'];
			$sort 	= $params['sord'];

            
            $conditions = array();
            $conditions['Card.c_status'] = 1;
			if(isset($this->request->named['cat_id']) && !empty($this->request->named['cat_id'])) {
				
				$all_cats = $this->Category->find('list',array(
					'fields' 		=> array('Category.cat_id'),
					'conditions'=> array('Category.cat_parent_id'=>$this->request->params['named']['cat_id'],'Category.cat_status'=>1),

					'recursive'	=> -2
				));
				
				if(count($all_cats) > 0){
					$conditions['Card.c_cat_id'] = array($all_cats);
				}
				
				$sub_cat_names = $this->Category->find('list',array(
					'fields'		 => array('cat_id','cat_title'),
					'conditions' => array('Category.cat_parent_id'=>$this->request->named['cat_id']),
					'order'=>'cat_title asc'
			    ));
				if(empty($sub_cat_names))
				{
				  $conditions['Card.c_cat_id'] = array();
				}
				else
				{
					//Getting Sub Category
					$new_sub =array();
					foreach($sub_cat_names as $k=>$v)
					{
						$new_sub[] = $k;
					}
					$conditions['Card.c_cat_id'] = $new_sub;
				}
			}
			
			if(isset($this->request->named['sub_cat_id']) && !empty($this->request->named['sub_cat_id']) ) {
				$id = $this->request->named['sub_cat_id'];
				$conditions['Card.c_cat_id'] = array($id);	
			}
			
			if(isset($this->request->named['card_id']) && !empty($this->request->named['card_id']) ) {
				$id = $this->request->named['card_id'];
				unset($conditions['Card.c_cat_id']);
				$conditions['Card.c_id'] = $id;	
			}
			
			if(isset($this->request->named['selchar']) && !empty($this->request->named['selchar']) ) {
				if($this->request->named['selchar'] != 'All')
				$conditions['Card.c_title LIKE'] = $this->request->named['selchar']. '%';
			}
						
			$group = 'Card.c_id';
			$having = '';
			
			if(isset($this->request->named['rate']) && !empty($this->request->named['rate'])){
				switch($this->request->named['rate']){
					case '1'	: $having = ' price < 1';	//Under 1
											break;
					case '2'	: $having = ' price >= 1 and price <= 2';	
											break;
					case '3'	: $having = ' price >= 2 and price <= 5';	
											break;
					case '4'	: $having = ' price >= 5 and price <= 10';	
											break;										
					case '5'	: $having = ' price > 10';	
											break;
				}
			  }
			if($having!=''){
				$group .=' having'.$having; 
			}
			
			$case = 'case 
					when CardsPrice.cp_u_role = "'.$this->Session->read('Auth.User.role_id').'" and CardsPrice.cp_u_id IS NULL then  CardsPrice.cp_u_role = "'.$this->Session->read('Auth.User.role_id').'" and CardsPrice.cp_u_id IS NULL  and CardsPrice.cp_c_id = Card.c_id
				end';
			
			if(isset($this->request->query['filters'])){
				$filters = json_decode($this->request->query['filters'], true);
				foreach($filters['rules'] as $each_filter){
					if($each_filter['field'] == 'c_title'){
						$conditions['Card.c_title LIKE'] = '%'.Sanitize::escape($each_filter['data']). '%';
						//pr($this->request);
					}
					if($each_filter['field'] == 'cat_title'){
						$conditions['Category.cat_title LIKE'] = '%'.Sanitize::escape($each_filter['data']). '%';
					}
					if($each_filter['field'] == 'pin_card_count'){
						$conditions['Card.pin_card_count LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
					}
					if($each_filter['field'] == 'pin_card_remain_count'){
						$conditions['Card.pin_card_remain_count LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
					}
					if($each_filter['field'] == 'pin_card_sold_count'){
						$conditions['Card.pin_card_sold_count LIKE'] = '%'.Sanitize::escape($each_filter['data']).'%';
					   
					}
					
					if($each_filter['field'] == 'c_status'){
						if($each_filter['data'] != '')
						$conditions['Card.c_status'] = Sanitize::clean($each_filter['data']);
					}
				}
			}
			
			if(!$limit){$limit = 10;}
			if(!$page){$page = 1;}
			if(!$sidx){$sidx = 1;}
            
            if(!isset($conditions['Card.c_cat_id']) && !isset($conditions['Card.c_id'])) 
            {

				$subCatConditions = array();
				$subCatConditions['Category.cat_status'] = 1;
				$subCatConditions['Category.cat_parent_id <>'] = null;
				        
		        $subCatConditions['Category_Parent.cat_status'] = 1;
		        $resSubCat = $this->Category->find('list',array(
								'conditions' => $subCatConditions,
								'fields' => array('cat_id','cat_title'),
								'recursive'	 => -1,
								'joins' => array(
									array(
										'table' => 'ecom_categories',
										'alias' => 'Category_Parent',
										'type' => 'inner',
										'conditions' => 'Category_Parent.cat_id =Category.cat_parent_id'
									)
								),
								'order'=>'cat_title asc'
							));
		        $all_sub_cat = array();

		        foreach($resSubCat as $k => $v)
		        $all_sub_cat[] = $k;

		        //prd($all_sub_cat);

		        $conditions['Card.c_cat_id'] = $all_sub_cat; 

		    }
            
			$count  = $this->Card->find('all',array(
				'conditions'=>$conditions,
				'joins' => array(
					array(
						'table' => 'ecom_cards_prices ',
						'alias' => 'CardsPrice',
						'type' => 'left',
						'conditions' => $case
					)
				),
				'fields' => array('*','CASE WHEN CardsPrice.cp_selling_price IS NOT NULL THEN CardsPrice.cp_selling_price ELSE Card.c_selling_price END AS price'),
				'group'=>$group,
			));

			$count = count($count);

			if ($count > 0) { 
				$total_pages = ceil($count/$limit); 
			} else { 
				$total_pages = 1; 
			}
			if ($page > $total_pages) $page=$total_pages; 		
			$start = $limit*$page - $limit;	
			
			if($sidx == 1)
			{
				$order ='Card.c_inventory_threshold * Card.c_pin_per_card > Card.pin_card_remain_count desc , Card.c_title asc';
			}
			else
			{
				$order = array($sidx.' '.$sort);
			}

			$resultSet = $this->Card->find('all', array(
				'conditions'=>$conditions,
				'joins' => array(
					array(
						'table' => 'ecom_cards_prices ',
						'alias' => 'CardsPrice',
						'type' => 'left',
						'conditions' => $case
					)
				),
				'fields'		 => array('*','CASE WHEN CardsPrice.cp_selling_price IS NOT NULL THEN CardsPrice.cp_selling_price ELSE Card.c_selling_price END AS price'),
				'order'=>$order,
				'limit'=>$limit,
				'offset'=>$start,
				'group'=>$group
			));
			
			$response = new stdClass();
			$response->page 	= $page;
			$response->total 	= $total_pages;
			$response->records 	= $count;
			
			foreach($resultSet as $key=>$val){

				$total_pins = 0;
				$pins_unused = 0;
				$pins_sold = 0;
				$other_pins = 0;
				foreach($val['PinsCard'] as $pin )
				{
                     //prd($val['Pin']);
                     
                     $total_pins++;
                     if($pin['pc_status'] == 1)
                     $pins_unused++;
                     else
                     if($pin['pc_status'] == 2)
                     $pins_sold++;
                     else
                     $other_pins++;	
                }
                
				$val['Card']['pin_card_sold_count']   = $pins_sold;
				//$val['Card']['pin_card_remain_count'] = $pins_unused;

				$len=strlen($val['Card']['c_title']);
				if($len > 30)			
				$title = ucwords(strtolower(substr($val['Card']['c_title'],0,35)."..."));				
				else
				$title = ucwords(strtolower($val['Card']['c_title']));
				
				$cat_len=strlen($val['Category']['cat_title']);
				if($cat_len > 30)			
				$cat_title = ucwords(strtolower(substr($val['Category']['cat_title'],0,35)."..."));				
				else
				$cat_title = ucwords(strtolower($val['Category']['cat_title']));
				
				if($val['Card']['c_status']==1){
					$status_link = '<img title="Enabled" alt="Enabled" src="'.$this->webroot.'img/greenStatus.png" border="0" onclick="changeStatus('.$val['Card']['c_id'].',0)" style="cursor:pointer;"/>';
				}else{
					$status_link = '<img title="Disabled" alt="Disabled" src="'.$this->webroot.'img/redstatus.png" border="0" onclick="changeStatus('.$val['Card']['c_id'].',1)" style="cursor:pointer;"/>';
				}

				$statusDropDown = '<div style="float:left; padding:4px;width:100%;"><select class="form-control" title="Change Status" onchange="changeStatus('.$val['Card']['c_id'].',this)" >';
				if($val['Card']['c_status']==1){
					
					$statusDropDown .= '<option value="0">Disabled</option>
											<option selected="selected" value="1">Enabled</option>
										</select>';
					
				}else{
					$statusDropDown .= '<option selected="selected" value="0">Disabled</option>
											<option value="1">Enabled</option>
										</select></div>';
				}

				$edit = '';
				$imagename = $val['Card']['c_image'];
				if($imagename!='' && file_exists('img/card_icons/icon/'.$imagename))
				{
					$card_image = '<img style="cursor:pointer;margin-top:3px;" src="'.$this->webroot.'img/card_icons/icon/'.$imagename.'" border="0"/>';
				}
				else 
				{ 
					$card_image = '<img style="cursor:pointer;margin-top:3px;" src="'.$this->webroot.'img/users/noimage.jpg" border="0" width="35" height="35"/>';
				}
				
				$content = Router::url(array('controller'=>'Cards','action'=>'add','admin'=>true,$val['Category']['cat_id'],$val['Card']['c_id']));
				if($id == NULL){
					$content = Router::url(array('controller'=>'Cards','action'=>'add','admin'=>true,$val['Category']['cat_id'],$val['Card']['c_id'],1));
				}
				
				$edit = '<a title="Edit Card" style="color:#222222;" href="'.$content.'">Edit Details|</a>';
				
				//
				$import_pins = '<a style="color:#222222;" href="'.Router::url(array('controller'=>'Pins','action'=>'add','admin'=>true,$val['Card']['c_id'])).'">Import Pins|</a>';
                //" <img src="'.$this->webroot.'img/download.png" alt="Export" title="Export" border="0" />
				$export_pins = '<a style="color:#222222;" href="'.Router::url(array('controller'=>'Pins','action'=>'downloadexcel','admin'=>true,$val['Card']['c_id'])).'">Export Pins </br></a>';
				
				//
				$pins_manage = '<a href="'.$this->webroot.'admin/Pins/index/'.$val['Card']['c_id'].'/1"><img src="'.$this->webroot.'img/detail.png" alt="Manage Cards" title="Manage Cards" border="0" /> ('.$val['Card']['pin_card_count'].') </a>';
				
				//<img src="'.$this->webroot.'img/detail.png" alt="Merge Pins" title="Merge Pins" border="0" />
				$merge_pins = '<a style="color:#222222 ;" href="'.$this->webroot.'admin/Pins/merge_list/'.$val['Card']['c_cat_id'].'/'.$val['Card']['c_id'].'/1">Merge Pins |</a>';
				//<img src="'.$this->webroot.'img/detail.png" alt="Unmerge Pins" title="Unmerge Pins" border="0" />
				$unmerge_pins = '<a style="color:#222222;" href="'.$this->webroot.'admin/Pins/unmerge_list/'.$val['Card']['c_cat_id'].'/'.$val['Card']['c_id'].'/1">Unmerge Pins |</a>';
				
				$del = '<a title="Delete Card" onclick="changeStatus('.$val['Card']['c_id'].',3)" style="cursor:pointer; color:#222222;">Delete</a>';
			
				$main_cat = $val['Category']['cat_parent_id'];
				$main_cat_data = $this->Category->findByCatId($main_cat);
				
				$total_pins_for_card_required =  $val['Card']['c_inventory_threshold'] * $val['Card']['c_pin_per_card'];
				$response->rows[$key]['id']   = $val['Card']['c_id'];	
				
				$action = "&nbsp;".$edit." ".$import_pins." ".$export_pins." ".$merge_pins." ".$unmerge_pins." ".$del;
				/*$response->rows[$key]['cell'] = array($title,ucwords($main_cat_data['Category']['cat_title']),$cat_title,$pins_manage,$val['Card']['pin_card_sold_count'],$val['Card']['pin_card_remain_count'],$import_pins,$export_pins,$merge_pins,$unmerge_pins,$statusDropDown,$edit,$total_pins_for_card);*/
				$response->rows[$key]['cell'] = array($title,ucwords($main_cat_data['Category']['cat_title']),$cat_title,$pins_manage,$val['Card']['pin_card_sold_count'],$other_pins+$val['Card']['pin_card_remain_count'],$pins_unused,$statusDropDown,$action,$total_pins_for_card_required);

				
			}

			echo json_encode($response);
		}else{
			$this->redirect(array('action' => 'index','controller'=>'Categories'));
		}
	}
	public function admin_add($id=NULL,$card_id = NULL, $from_card = NULL){
		//$id= category_id and $card_id = card's id
		$this->set("title",__('Add - Card'));	
		$this->set("title_for_layout",__('Add - Card'));	
		
		$url = Router::url(array('controller' => 'cards', 'action' => 'index',$id));
		if($from_card == 1)
		{
			$url = Router::url(array('controller' => 'cards', 'action' => 'index'));
		}
		$this->set('url',$url);
		
		if(isset($card_id) && !empty($card_id))
		{
			//edit case
			$this->set("title",__('Edit - Card'));	
			$this->set("title_for_layout",__('Edit - Card'));	
		}
		
		$this->loadModel('Category');
		
		$parent_details = $this->Category->find('first', array(
								'conditions'=>array('Category.cat_id'=>$id,'Category.cat_parent_id IS NOT NULL'),
								'recursive' => -2,
								));
		$cat_names = $this->Category->find('list',array(
				'fields'		 => array('cat_id','cat_title'),
				'conditions' => array('cat_parent_id'=>NULL)
		));
		$sub_cat_names = $this->Category->find('list',array(
				'fields'		 => array('cat_id','cat_title'),
				'conditions' => array('cat_parent_id'=>(isset($parent_details['Parent']['cat_id']) ? $parent_details['Parent']['cat_id'] : 0))
		));
		$this->set('cat_names',$cat_names);
		$this->set('sub_cat_names',$sub_cat_names);
		
		/*if(empty($parent_details))
		{
			//if selected is not valid category
			$this->Session->setFlash(__('Unauthorized access'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index','controller'=>'Categories'));
		}*/
		$this->set('parent_details',$parent_details);
		//prd($parent_details);
		$this->set('id',$id);
		
		if(isset($card_id) && !empty($card_id)){
			//edit case
			$card = $this->Card->find('first',array(
								'fields'		 => array('Card.c_id','Card.c_image','Card.c_image_back'),
								'conditions' => array('Card.c_id'=>$card_id),
								'recursive'	 => 0  
			));
			if(empty($card)){
				//if selected card is not valid card
				$this->Session->setFlash(__('Unauthorized access'), 'default', array('class' => 'error'));
				$this->redirect(array('controller'=>'Categories','action' => 'index',$id));
			}
		}
		$this->LoadModel('Language');
		$lang_list = $this->Language->find('all',array(
				'recursive'=>-1,
				'fields'=>array('alias','title'),	
				'conditions'=>array('status'=>1),
		));
		$this->set('lang_list',$lang_list);
		//prd($lang_list);
		if ($this->request->is('post') || $this->request->is('put')){
				//prd($this->request->data);
				$valid_image = 1;
				$this->request->data['Card']['c_cat_id'] = $this->request->data['Card']['sub_cat_id'];
				$data = $this->request->data;
				//prd($data);
				
				if(isset($data['Card']['c_image']) && !empty($data['Card']['c_image']))
				{
					   /*$this->Card->validate = Set::merge($this->Card->validate, array(
							'c_image'	=>	array(
													'type'		    =>array(
													'rule'			  =>	array('extension',array('jpg','jpeg','png','gif')),
													'allowEmpty'	=>	false,
													'message'			=>	'Please supply a valid image.'
											),
										)));
						
                       
                        
						$size_byte = filesize($this->request->data['Card']['c_image']['tmp_name']); //returns in bytes
						if($size_byte > 2097152)
						{ 
						    //2MB
							$valid_image = 0;
						}*/
				}
				else
				{
					if(empty($card['Card']['c_image']) && ((isset($card_id) && !empty($card_id))))
					{
						//edit case
						//if no image is uploaded now and intially also there was no image uploaded
						$valid_image = 0;
						unset($data['Card']['c_image']);
					}
					else if($card_id==NULL)
					{
						//add case
						$valid_image = 0;
					}
				}
				
				if(isset($data['Card']['c_image_back']) && !empty($data['Card']['c_image_back']))
				{
						/*    $this->Card->validate = Set::merge($this->Card->validate, array(
							'c_image_back'		=>	array(
													'type'		    =>array(
													'rule'			  =>	array('extension',array('jpg','jpeg','png','gif')),
													'allowEmpty'	=>	false,
													'message'			=>	'Please supply a valid image.'
											),
										)));
						
                        
                        
						$size_byte = filesize($this->request->data['Card']['c_image_back']['tmp_name']); //returns in bytes
						if($size_byte > 2097152)
						{ 
						    //2MB
							$valid_image = 0;
						}*/
				}
				else
				{
					  if(empty($card['Card']['c_image_back']) && ((isset($card_id) && !empty($card_id))))
					  {
						//edit case
						//if no image is uploaded now and intially also there was no image uploaded
						//$valid_image = 0;
						unset($data['Card']['c_image_back']);
					  }
					  else if($card_id==NULL)
					  {
						//add case
						//$valid_image = 0;
					  }
				}

				if($card_id == NULL)
				{
					//add case
                	if($valid_image==0)
					{
						$parent_details['Parent']['cat_id'] = $this->request->data['Card']['cat_id'];
						$parent_details['Category']['cat_id'] = $this->request->data['Card']['sub_cat_id'];
						$tempconditions['cat_status'] = 1;
						$tempconditions['cat_parent_id'] = $parent_details['Parent']['cat_id'];
						$sub_cat_names = $this->Category->find('list',array(
								'fields'		 => array('cat_id','cat_title'),
								'conditions' => $tempconditions,
								'order'=>'cat_title asc'
						));
						$this->set('sub_cat_names',$sub_cat_names);
						unset($this->request->data['Card']['c_image']);
						unset($this->request->data['Card']['c_image_back']);
						$this->set('parent_details',$parent_details);
						$this->Session->setFlash('A valid card image with resolutions minimum : 110px X 70px and maximum 1760px X 2480px is required.', 'default', array('class' => 'error'));
						return;
					} 
				}
				
				if(isset($data['Card']['c_image']) && !empty($data['Card']['c_image']))
				{
						/*$this->Card->validate = Set::merge($this->Card->validate, array(
							'c_image'		=>	array(
													'type'		    =>array(
													'rule'			  =>	array('extension',array('jpg','jpeg','png','gif')),
													'allowEmpty'	=>	false,
													'message'			=>	'Please supply a valid image.'
											),
										)));*/
                        
                        /* 21 Jan : Image Height width validation */
                        //prd($data['Card']['c_image']);
                        $image_file = $data['Card']['c_image']; 
                        $validateImage = TRUE;
                        //$validateImage = $this->_validatePinImageSize($image_file);
                        
                        if($validateImage !== TRUE)
						{
                            $valid_image = 0;
                            $imgHW = $validateImage;
                        }
						
						/*$size_byte = filesize($this->request->data['Card']['c_image']['tmp_name']); //returns in bytes
						if($size_byte > 2097152)
						{ 
						    //2MB
							$valid_image = 0;
						}*/
						$this->Card->set($this->request->data);
						
						
						if($valid_image && $this->Card->validates())
						{
							$img_name = $data['Card']['c_image'];
                            $img_path = WWW_ROOT.'img/card_icons/'.$img_name;
							/*$extension = explode(".",$data['Card']['c_image']);
							$img_name = strtolower(str_replace(' ','_',rand().$data['Card']['c_image']));
							$img_tmp = $data['Card']['c_image']['tmp_name'];
							$img_path = WWW_ROOT.'img/card_icons/'.$img_name;
							if(!empty($card['Card']['c_image']))
							{	
								@unlink(WWW_ROOT.'img/card_icons/'.$card['Card']['c_image']);
								@unlink(WWW_ROOT.'img/card_icons/icon/'.$card['Card']['c_image']);
							}
							if(move_uploaded_file($img_tmp,$img_path))
							{*/
								/*$this->Commonfunctions->create_thumb($img_path, $img_path, 300, 300);
								$this->Commonfunctions->resize_with_crop($img_path, $img_path, 180, 100); 
								
								$thumbnail_destination = WWW_ROOT.'img/card_icons/icon/'.$img_name;
								$this->Commonfunctions->resize_with_crop($img_path,$thumbnail_destination,24,24);*/
								
								$data['Card']['c_image'] = $img_name;
							//}
						}
						else
						{
                            

    						$parent_details['Parent']['cat_id'] = $this->request->data['Card']['cat_id'];
		    				$parent_details['Category']['cat_id'] = $this->request->data['Card']['sub_cat_id'];

                            $tempconditions['cat_status'] = 1;
							$tempconditions['cat_parent_id'] = $parent_details['Parent']['cat_id'];
							$sub_cat_names = $this->Category->find('list',array(
									'fields'		 => array('cat_id','cat_title'),
									'conditions' => $tempconditions,
									'order'=>'cat_title asc'
							));
							$this->set('sub_cat_names',$sub_cat_names);
							$this->set('parent_details',$parent_details);

						    if(empty($this->request->data['Card']['c_image_back']))
						    unset($this->request->data['Card']['c_image_back']);

						    if(empty($this->request->data['Card']['c_image']))
						    unset($this->request->data['Card']['c_image']);

						}
				   }
				   else if(empty($card['Card']['c_image']) && ((isset($card_id) && !empty($card_id))))
				   {
					//edit case
					//if no image is uploaded now and intially also there was no image uploaded
					$valid_image = 0;
					unset($data['Card']['c_image']);
				   }
				   else
				   {
					 unset($data['Card']['c_image']);
				    }
               
                    if(isset($data['Card']['c_inventory_threshold']))
                    {
                        if(empty($data['Card']['c_inventory_threshold']) || $data['Card']['c_inventory_threshold'] == NULL)
                        $data['Card']['c_inventory_threshold'] = 0;
                    }
                    else 
                    {
                        $data['Card']['c_inventory_threshold'] = 0;
                    }
                    
                    if(isset($data['Card']['c_image_back']) && !empty($data['Card']['c_image_back']))
					{
						/*$this->Card->validate = Set::merge($this->Card->validate, array(
							'c_image_back'		=>	array(
													'type'		    =>array(
													'rule'			  =>	array('extension',array('jpg','jpeg','png','gif')),
													'allowEmpty'	=>	false,
													'message'			=>	'Please supply a valid image.'
											),
										)));
						
                        */
                        /* 21 Jan : Image Height width validation */
                        //prd($data['Card']['c_image']);
                        $image_file = $data['Card']['c_image_back']; 
                        $validateImage  = TRUE;
                       /* $validateImage = $this->_validatePinImageSize($image_file);
                        
                        if($validateImage !== TRUE)
						{
                            $valid_image = 0;
                            $imgHW = $validateImage;
                        }*/
                        
						//prd($valid_image);      
						/*$size_byte = filesize($this->request->data['Card']['c_image_back']['tmp_name']); //returns in bytes
						if($size_byte > 2097152)
						{ 
						    //2MB
							$valid_image = 0;
						}*/

						$this->Card->set($this->request->data);
						/*if($valid_image && $this->Card->validates())
						{*/
							$img_name = $data['Card']['c_image_back'];
							$img_path = WWW_ROOT.'img/card_icons/'.$img_name;

							/*$extension = explode(".",$data['Card']['c_image_back']['name']);
							$img_name = strtolower(str_replace(' ','_',rand().$data['Card']['c_image_back']['name']));
							$img_tmp = $data['Card']['c_image_back']['tmp_name'];
							
							if(!empty($card['Card']['c_image_back'])){	
								@unlink(WWW_ROOT.'img/card_icons/'.$card['Card']['c_image_back']);
								@unlink(WWW_ROOT.'img/card_icons/icon/'.$card['Card']['c_image_back']);
							}
							if(move_uploaded_file($img_tmp,$img_path))
							{*/
								/*$this->Commonfunctions->create_thumb($img_path, $img_path, 300, 300);
								$this->Commonfunctions->resize_with_crop($img_path, $img_path, 180, 100); 
								
								$thumbnail_destination = WWW_ROOT.'img/card_icons/icon/'.$img_name;
								$this->Commonfunctions->resize_with_crop($img_path,$thumbnail_destination,24,24);*/
								
								$data['Card']['c_image_back'] = $img_name;
							//}
						//}
				}
				else if(empty($card['Card']['c_image_back']) && ((isset($card_id) && !empty($card_id))))
				{
					//edit case
					//if no image is uploaded now and intially also there was no image uploaded
					//$valid_image = 0;
					unset($data['Card']['c_image_back']);
				}
				else
				{
					//edit case, image uploaded preoviously
					unset($data['Card']['c_image_back']);
				}
                
				//prd($data);
				if(isset($card_id) && !empty($card_id))
				{
					//edit case
					$already_exists = $this->Card->hasAny(array('Card.c_title' => trim($data['Card']['c_title']),'Card.c_cat_id'=>$id, 'Card.c_id <>'=>$card_id));		
					$data['Card']['c_id'] = $card_id;
				}
				else
				{
					//add case
					$already_exists = $this->Card->hasAny(array('Card.c_title' => trim($data['Card']['c_title']),'Card.c_cat_id'=>$id));		
				}
				//prd($data);
				if(isset($data['CardsFreeText']) && !empty($data['CardsFreeText']))
				{
					   foreach($this->request->data['CardsFreeText']['cf_alias'] as $key=>$val)
					   {
						$data['CardsFreeText'][$key]['cf_alias'] = $val;
						$data['CardsFreeText'][$key]['cf_id'] = $this->request->data['CardsFreeText']['cf_id'][$key];
						if(isset($card_id) && !empty($card_id)){
							$data['CardsFreeText'][$key]['cf_c_id'] = $card_id;
					  }
						$data['CardsFreeText'][$key]['cf_freetext1'] = $this->request->data['CardsFreeText']['cf_freetext1'][$key];
						$data['CardsFreeText'][$key]['cf_freetext2'] = $this->request->data['CardsFreeText']['cf_freetext2'][$key];
						$data['CardsFreeText'][$key]['cf_freetext3'] = $this->request->data['CardsFreeText']['cf_freetext3'][$key];
						$data['CardsFreeText'][$key]['cf_freetext4'] = $this->request->data['CardsFreeText']['cf_freetext4'][$key];
						$data['CardsFreeText'][$key]['cf_freetext5'] = $this->request->data['CardsFreeText']['cf_freetext5'][$key];
						$data['CardsFreeText'][$key]['cf_freetext6'] = $this->request->data['CardsFreeText']['cf_freetext6'][$key];
					}
					unset($data['CardsFreeText']['cf_alias']);
					unset($data['CardsFreeText']['cf_id']);
					unset($data['CardsFreeText']['cf_freetext1']);
					unset($data['CardsFreeText']['cf_freetext2']);
					unset($data['CardsFreeText']['cf_freetext3']);
					unset($data['CardsFreeText']['cf_freetext4']);
					unset($data['CardsFreeText']['cf_freetext5']);
					unset($data['CardsFreeText']['cf_freetext6']);
				}
				
				$diffrence = $data['Card']['c_selling_price']-$data['Card']['c_buying_price'];
				
				if($data['Card']['c_buying_price']>0)
				$profit =  ($diffrence/$data['Card']['c_buying_price'])*100;
				else
				$profit =0 ;
				$new_card = 1;	
				if($id == NULL && $card_id == NULL)
				{
					$data['CardsPrice'][0]['cp_u_id']  = NULL;
					$data['CardsPrice'][0]['cp_u_role']  = 1;
					$data['CardsPrice'][0]['cp_buying_price']  = $data['Card']['c_buying_price'];
					$data['CardsPrice'][0]['cp_selling_price']  = $data['Card']['c_selling_price'];
					$data['CardsPrice'][0]['cp_profit']  =  $profit;
					$data['CardsPrice'][0]['cp_created_by']  = $this->Auth->User('id');
					$data['CardsPrice'][0]['cp_updated_by']  = $this->Auth->User('id');
					$data['CardsPrice'][0]['cp_created_date']  = date('Y-m-d H:i:s');
					$data['CardsPrice'][0]['cp_updated_date']  = date('Y-m-d H:i:s');
				}
				else
				{
					$new_card = 0;	
					$this->loadModel('CardsPrice');
					$get_card_price = $this->CardsPrice->find('first',array('conditions'=>array('cp_c_id'=>$card_id,'cp_u_id'=>NULL,'cp_u_role'=>1)));
				    if($get_card_price)
					{
						$data['CardsPrice'][0]['cp_id']  = $get_card_price['CardsPrice']['cp_id'];
						$data['CardsPrice'][0]['cp_selling_price']  = $data['Card']['c_selling_price'];
						$data['CardsPrice'][0]['cp_profit']  =  $profit;
						$data['CardsPrice'][0]['cp_updated_date']  = date('Y-m-d H:i:s');
					}
				}
				
				$data['Card']['updated'] = date('Y-m-d H:i:s');
				//$this->Card->create();	
                //prd($valid_image);
				if(!$already_exists)
				{
				    /*if($this->Card->validates())
					{*/
						if($valid_image)
						{
							   //$data['Card']['c_cat_id'] =  $id;
							   if ($this->Card->saveAssociated($data)) 
							   {
									//function to update parent categorie's count of cards
									$new_card_id = $this->Card->id; 
                                     									
									$this->_updatecountercacheparent();
									
									if($new_card)
									{
     									$this->Session->setFlash('Card has been saved successfully.', 'default', array('class' => 'success'));
									    $this->redirect(array('controller'=>'Cards','action' => 'add'));
									}
									
									$this->Session->setFlash('Card has been updated successfully.', 'default', array('class' => 'success'));

									if($new_card_id && isset($id))
									{
										$this->redirect(array('controller'=>'Cards','action'=>'add',$id,$new_card_id));
									}
									else
									{
										$get_card_data = $this->Card->findByCId($new_card_id);
										$cat_id = $get_card_data['Card']['c_cat_id'];
										$this->redirect(array('controller'=>'Cards','action'=>'add',$cat_id,$new_card_id));
									}
								    //else
									//$this->redirect(array('action'=>'index/'));
								} 
								else
								{
									$this->Session->setFlash('Card could not be saved. Please, try again.', 'default', array('class' => 'error'));
								}
						}
						else
						{
							/*if(isset($data['Card']['c_image']) && !empty($data['Card']['c_image']))
							{*/
								@unlink(WWW_ROOT.'img/card_icons/'.$img_name);
								@unlink(WWW_ROOT.'img/card_icons/icon/'.$img_name);
                                if(!empty($imgHW))
								{
                                    $this->Session->setFlash($imgHW, 'default', array('class' => 'error'));
                                } 
								else 
								{
                                    $this->Session->setFlash('Please upload both the images.', 'default', array('class' => 'error'));
                                }
							/*}*/
						}
					//}
				}
				else
				{
					$this->Session->setFlash('Card name already exist.', 'default', array('class' => 'error'));	
				}
			    
			}
		if(isset($card_id) && !empty($card_id) && empty($this->request->data))
		{
			$this->Card->Behaviors->attach('Containable');
			$this->Card->contain(array(
					'Category' =>array(
						'fields' => array('cat_title'),
					),
					'CardsFreeText' => array(
					)
			));
			$this->request->data = $this->Card->read(null, $card_id);
		
			$this->Card->Behaviors->detach('Containable');
			if(isset($this->request->data)&& !empty($this->request->data)){
				if(isset($this->request->data['CardsFreeText']) && !empty($this->request->data['CardsFreeText'])){
					foreach($this->request->data['CardsFreeText'] as $key=>$val){
						$this->request->data['CardsFreeText']['cf_alias'][$key] = $this->request->data['CardsFreeText'][$key]['cf_alias'];
						$this->request->data['CardsFreeText']['cf_id'][$key] = $this->request->data['CardsFreeText'][$key]['cf_id'];
						$this->request->data['CardsFreeText']['cf_freetext1'][$key] = $this->request->data['CardsFreeText'][$key]['cf_freetext1'];
						$this->request->data['CardsFreeText']['cf_freetext2'][$key] = $this->request->data['CardsFreeText'][$key]['cf_freetext2'];
						$this->request->data['CardsFreeText']['cf_freetext3'][$key] = $this->request->data['CardsFreeText'][$key]['cf_freetext3'];
						$this->request->data['CardsFreeText']['cf_freetext4'][$key] = $this->request->data['CardsFreeText'][$key]['cf_freetext4'];
						$this->request->data['CardsFreeText']['cf_freetext5'][$key] = $this->request->data['CardsFreeText'][$key]['cf_freetext5'];
						$this->request->data['CardsFreeText']['cf_freetext6'][$key] = $this->request->data['CardsFreeText'][$key]['cf_freetext6'];
						unset($this->request->data['CardsFreeText'][$key]);
					}
					
				}
				
			}
			//prd($this->request->data);
		}
		
	}

	public function admin_delete(){
		if($this->request->is('ajax')){
			foreach($this->request['data']['ids'] as $k=>$v){
				$del_res=$this->Card->delete(array($v));
			}
			$this->_updatecountercacheparent();
			echo 1;exit;	
			
		}
	}	

	public function admin_changestatus(){
		if($this->request->is('ajax')){	
			$id = $this->request->data['id'];
			$status = $this->request->data['st'];
			unset($this->request->data);
			$this->Card->set(array('Card' => array('c_id'=>$id, 'c_status'=>$status)));
			if($this->Card->save($this->request->data)){
				echo 1; exit;
			}else{
				echo 0; exit;
			}
		}
			
	}
	
    public function admin_manage_price($char_code = 'All',$mediator_id = 0,$selected_card_category = 0,$selected_sub_category = 0,$card_rate = 0,$card_id = 0,$overrideen = 0){
		$this->set("title_for_layout",__('Mediator Card Price'));	
		$this->loadModel('Category');
		$this->loadModel('User');
		$this->loadModel('Card');
		$this->loadModel('CardsPrice');

        $this->set('sub_cat_id',$selected_sub_category);	
        $this->set('cat_id',$selected_card_category);	
        $this->set('mediator_id',$mediator_id);	
        $this->set('selchar',$char_code);	
        $this->set('rate',$card_rate);	
        $this->set('card_id',$card_id);	
        $this->set('overridden',$overrideen);	

		
		
		// Categories
		$cat_res = $this->Category->find('list',array(
			'conditions' => array('cat_status'=>1,'cat_parent_id'=>null),
			'fields' => array('cat_id','cat_title'),
			'order'=>'cat_title asc',
			'recursive'	 => -1
		));
		
		// Sub Category
		$subCatConditions = array();
		$card_price_conditions =array();
        
		if($card_id)
		$card_price_conditions['Card.c_id'] = $card_id;
		
		$subCatConditions['Category.cat_status'] = 1;
		$subCatConditions['Category.cat_parent_id <>'] = null;

		$named = $this->request->data;
		
		if(isset($card_rate) && !empty($card_rate)) {
		
			$this->set('rate',$card_rate);	
			$rate = $card_rate;
			
			if($rate == 1)
			$card_price_conditions['Card.c_buying_price <'] = 1;
			else if($rate == 2) 	
		    {
				$card_price_conditions['Card.c_buying_price >='] = 1;
				$card_price_conditions['Card.c_buying_price <='] = 2;
			}
			else if($rate == 3) 	
		    {
				$card_price_conditions['Card.c_buying_price >='] = 2;
				$card_price_conditions['Card.c_buying_price <='] = 5;
			}
			else if($rate == 4) 	
		    {
				$card_price_conditions['Card.c_buying_price >='] = 5;
				$card_price_conditions['Card.c_buying_price <='] = 10;
			}
			else if($rate == 5) 	
		    {
				$card_price_conditions['Card.c_buying_price >'] = 10;
			}
		}
		
		if(isset($selected_card_category) && !empty($selected_card_category)) {
			$this->set('cat_id',$selected_card_category);	
			$subCatConditions['cat_parent_id'] = $selected_card_category;
		}
		
		if(isset($selected_sub_category) && !empty($selected_sub_category)) {
			$this->set('sub_cat_id',$selected_sub_category);	
			$this->set('cat_id',$selected_card_category);
			$card_price_conditions['Card.c_cat_id'] = $selected_sub_category;
		}

		$resSubCat = array();
		if(isset($subCatConditions['cat_parent_id']))
		{
			$resSubCat = $this->Category->find('list',array(
						'conditions' => $subCatConditions,
						'fields' => array('cat_id','cat_title'),
						'order'=>'cat_title asc',
						'recursive'	 => -1
			));
		}
		else
		{
			$resSubCat = $this->Category->find('list',array(
								'conditions' => $subCatConditions,
								'fields' => array('cat_id','cat_title'),
								'recursive'	 => -1,
								'joins' => array(
									array(
										'table' => 'ecom_categories',
										'alias' => 'Category_Parent',
										'type' => 'inner',
										'conditions' => 'Category_Parent.cat_id =Category.cat_parent_id'
									)
								),
								'order'=>'cat_title asc'
							));
		}

		$sub_cat_array= array();
		foreach($resSubCat as $k => $v)
		{
			$sub_cat_array[] = $k;
		}
		
		// Cards
		$card_conditions = array();
		$card_conditions['Card.c_status'] = 1;
		if($selected_sub_category)
		$card_conditions['Card.c_cat_id'] = $selected_sub_category;
	    else
	    $card_conditions['Card.c_cat_id'] = $sub_cat_array;	

		$card_res = $this->Card->find('list',array(
			'conditions' => $card_conditions,
			'fields' => array('c_id','c_title'),
			'order'=>'c_title asc',
			'recursive'	 => -1
		));
		
		foreach($card_res as $k => $v)
		$card_res[$k] = ucwords(strtolower($v));
 
		$this->set('cardList',$card_res);


	    if($sub_cat_array)
		{
			if(isset($selected_sub_category) && !empty($selected_sub_category))
			$card_price_conditions['Card.c_cat_id'] = $selected_sub_category;
			else
			$card_price_conditions['Card.c_cat_id'] = $sub_cat_array;	
		}
		
		//prd($card_price_conditions);
		foreach($cat_res as $k => $v)
		$cat_res[$k] = ucwords(strtolower($v));
		
		foreach($resSubCat as $k => $v)
		$resSubCat[$k] = ucwords(strtolower($v));
		
		foreach ($cat_res as $key => $value) {
			$cat_res[$key] = ucwords(strtolower($value));
		}

		foreach ($resSubCat as $key => $value) {
			$resSubCat[$key] = ucwords(strtolower($value));
		}
		
		$this->set('catList',$cat_res);
		$this->set('subCatList',$resSubCat);
		
		$mediator_list = $this->User->find('all',array('conditions'=>array('User.status'=>1,'User.role_id'=>2),'order'=>'User.fname ,User.lname asc','fields'=>array('User.id','User.fname','User.lname')));
		$array_mediator = array();
		foreach($mediator_list as $list_m)
		{
			$mid = $list_m['User']['id'];
			$array_mediator[$mid] = ucwords($list_m['User']['fname']." ".$list_m['User']['lname']);
		}
		
	   	$mediator_name = '';
		if(!empty($mediator_id))
		{
			$get_mediator_data = $this->User->findById($mediator_id);
			$mediator_name = ucwords($get_mediator_data['User']['fname']." ".$get_mediator_data['User']['lname']);
		}
		 
		$this->set('mediator_list',$array_mediator);
		$this->set('mediator_id',$mediator_id);	
		$this->set('mediator_name',$mediator_name);	
		 
		/*$card_type_data = $this->Category->find('all',array('conditions'=>array('Category.cat_parent_id'=>NULL,'Category.cat_status'=>1),'order'=>'Category.cat_title asc'));
		$card_category =array();
		$card_category[0] = 'All';
		foreach($card_type_data as $categories)
		{
			$cat_id = ucwords($categories['Category']['cat_id']);
			$title = ucwords($categories['Category']['cat_title']);
			$card_category[$cat_id] = $title;
		}*/
        
		if($char_code && $char_code != 'All')
		{
			$card_price_conditions['Card.c_title LIKE'] =$char_code."%";
		}
		 
		//$this->set('card_categories',$card_category);
		if((!isset($card_price_conditions['Card.c_cat_id'])) && empty($card_price_conditions['Card.c_cat_id']))
		{
		  if($selected_card_category == 0)
		  $get_selected_child_data = $this->Category->find('all',array('conditions'=>array('Category.cat_status'=>1)));	
		  else
		  $get_selected_child_data = $this->Category->find('all',array('conditions'=>array('Category.cat_id'=>$selected_card_category,'Category.cat_status'=>1)));	
		  
		  $all_sub_category = array();
		  if(!empty($get_selected_child_data[0]['Child']))
		  {
			  for($i = 0;$i<count($get_selected_child_data);$i++)
			  {
			  	foreach($get_selected_child_data[$i]['Child'] as $data)
			  	{
				  $sub_cat_id = $data['cat_id'];
				  $all_sub_category[] = $sub_cat_id;
			  	}
			  }
		  }
		  $card_price_conditions['Card.c_cat_id'] =  $all_sub_category;
		}
		
		$card_price_conditions['Card.c_status'] =  1;
		//prd($card_price_conditions);
		if($overrideen)
		{
			$card_price_conditions['CardsPrice.cp_u_id']=$mediator_id;
			$get_cards = $this->Card->find('all',array(
														'joins' => array(
															array(
																'table' => 'ecom_cards_prices ',
																'alias' => 'CardsPrice',
																'type' => 'left',
																'conditions' => 'CardsPrice.cp_c_id = Card.c_id',
															)
														),
														'conditions'=>$card_price_conditions,
														'order'=>'Card.c_title asc')
										   );
		
		}
		else
		{
			$get_cards = $this->Card->find('all',array('conditions'=>$card_price_conditions,'order'=>'Card.c_title asc'));
		}
		$this->set('get_cards',$get_cards);
		
		//prd($this->request->data);
		if(!empty($this->request->data))
		{
			$new_data = $this->request->data;
			$new_post_data = array();
			$count = 0;
			$update = 0;
			$old_key = '';
			 foreach($new_data as $key => $value)
			{
				$explode_data = explode('_',$key);
			    if($old_key !=  $explode_data[1])
				{
					$old_key = $explode_data[1];
				    $new_post_data[$old_key]['Card']['c_id']  = $explode_data[1];
				}
				if($explode_data[0] == 'totalamount')
				$new_post_data[$old_key]['Card']['c_selling_price']  = $value;
				else
				$new_post_data[$old_key]['Card']['c_buying_price']  = $value;
			}
			
			$count = count($new_post_data);
			$new_card_data = array();
			$new_card_price_data = array();
			
			$counter = 0;
			
			foreach($new_post_data as $post_data)
		    {
				$new_card_data['Card'][$counter]['c_id'] = $post_data['Card']['c_id'];
				//$new_card_data['Card'][$counter]['c_buying_price'] = $post_data['Card']['c_buying_price'];
				$new_card_data['Card'][$counter]['c_selling_price'] = $post_data['Card']['c_selling_price'];
                $already_exists = $this->CardsPrice->find('first',array('conditions'=>array('CardsPrice.cp_u_id' =>$mediator_id,'CardsPrice.cp_u_role' =>2,'CardsPrice.cp_c_id'=>$post_data['Card']['c_id'])));
				//prd($already_exists);
				//unset($new_card_data['Card']);
				if($already_exists && $mediator_id)
				{
					$diffrence = $post_data['Card']['c_selling_price']-$already_exists['CardsPrice']['cp_buying_price'];
					$profit =  ($diffrence/$already_exists['CardsPrice']['cp_buying_price'])*100;
					$new_card_price_data['CardsPrice'][$counter]['cp_role_id'] = 2;
    				$new_card_price_data['CardsPrice'][$counter]['cp_id'] = $already_exists['CardsPrice']['cp_id'];
					$new_card_price_data['CardsPrice'][$counter]['cp_buying_price'] = $post_data['Card']['c_selling_price'];
					$new_card_price_data['CardsPrice'][$counter]['cp_updated_date'] =  date('Y-m-d H:i:s');
					$new_card_price_data['CardsPrice'][$counter]['cp_id'] = $already_exists['CardsPrice']['cp_id'];
					$new_card_price_data['CardsPrice'][$counter]['cp_updated_by'] = $this->Auth->User('id');
					$new_card_price_data['CardsPrice'][$counter]['cp_profit'] = $profit;;
				}
				else
				{
					if($mediator_id)
					{
					//$diffrence = $post_data['Card']['c_selling_price']-$post_data['Card']['c_buying_price'];
					//$profit =  ($diffrence/$post_data['Card']['c_buying_price'])*100;
					$new_card_price_data['CardsPrice'][$counter]['cp_u_id']  = $mediator_id;
					$new_card_price_data['CardsPrice'][$counter]['cp_u_role']  = 2;
					$new_card_price_data['CardsPrice'][$counter]['cp_c_id']  = $post_data['Card']['c_id'];
					$new_card_price_data['CardsPrice'][$counter]['cp_buying_price']  = $post_data['Card']['c_selling_price'];
					//$new_card_price_data['CardsPrice'][$counter]['cp_selling_price']  = $post_data['Card']['c_selling_price'];
					//$new_card_price_data['CardsPrice'][$counter]['cp_profit']  =  $profit;
					$new_card_price_data['CardsPrice'][$counter]['cp_created_by']  = $this->Auth->User('id');
					$new_card_price_data['CardsPrice'][$counter]['cp_updated_by']  = $this->Auth->User('id');
					$new_card_price_data['CardsPrice'][$counter]['cp_created_date']  = date('Y-m-d H:i:s');
					$new_card_price_data['CardsPrice'][$counter]['cp_updated_date']  = date('Y-m-d H:i:s');
				  }
				}
		     $counter++;
		   }
			
			if(!$mediator_id)
			{
				foreach($new_card_data as $new)
				{
					$res= $this->Card->saveAll($new);
				}
			}

			foreach($new_card_price_data as $new)
			{
				$update_child = $this->CardsPrice->saveAll($new);
				
			}
			
			
			if(isset($update_child) || isset($res))
			{
				 $this->Session->setFlash(__('Records has been updated successfully.'), 'default', array('class' => 'success'));
			}
			else
			{
				$this->Session->setFlash(__('Records could not be updated.'), 'default', array('class' => 'error'));
			}
            $this->redirect(array('action'=>'manage_price',$char_code,$mediator_id,$selected_card_category,$selected_sub_category,$card_rate,$card_id));
		}
	}
	
    public function admin_manage_price_mediator($char_code = '0',$retailer_id = 0,$selected_card_category = 0,$selected_sub_category = 0,$card_rate = 0,$card_id=0,$overridden = 0){
		$this->set("title_for_layout",__('Manage Price'));	
		$this->loadModel('Category');
		$this->loadModel('CardsPrice');
		$this->loadModel('User');
		$this->loadModel('Card');
		$this->set('card_id',$card_id);	

		

		$this->set('sub_cat_id',$selected_sub_category);	
		$this->set('cat_id',$selected_card_category);	
		$this->set('retailer_id',$retailer_id);	
		$this->set('selchar',$char_code);	
		$this->set('rate',$card_rate);
		$this->set('overridden',$overridden);	
		
		
		// Categories
		$cat_res = $this->Category->find('list',array(
			'conditions' => array('cat_status'=>1,'cat_parent_id'=>null),
			'fields' => array('cat_id','cat_title'),
			'order'=>'cat_title asc',
			'recursive'	 => -1
		));
		
		// Sub Category
		$subCatConditions = array();
		$card_price_conditions =array();
        
		if($card_id)
		$card_price_conditions['Card.c_id'] = $card_id;
		
		$subCatConditions['Category.cat_status'] = 1;
		$subCatConditions['Category.cat_parent_id <>'] = null;

		$named = $this->request->data;
		
		if(isset($card_rate) && !empty($card_rate)) {
		    
		    $this->set('rate',$card_rate);	
			$rate = $card_rate;
			
			if($rate == 1)
			$card_price_conditions['Card.c_buying_price <'] = 1;
			else if($rate == 2) 	
		    {
				$card_price_conditions['Card.c_buying_price >='] = 1;
				$card_price_conditions['Card.c_buying_price <='] = 2;
			}
			else if($rate == 3) 	
		    {
				$card_price_conditions['Card.c_buying_price >='] = 2;
				$card_price_conditions['Card.c_buying_price <='] = 5;
			}
			else if($rate == 4) 	
		    {
				$card_price_conditions['Card.c_buying_price >='] = 5;
				$card_price_conditions['Card.c_buying_price <='] = 10;
			}
			else if($rate == 5) 	
		    {
				$card_price_conditions['Card.c_buying_price >'] = 10;
			}
		}
		
		/*if(isset($card_rate) && !empty($card_rate)) {
		
			$this->set('rate',$card_rate);	
			$rate = $card_rate;
			
			if($rate == 1)
			$card_price_conditions['Card.c_buying_price <'] = 100;
			else if($rate == 2) 	
		    {
				$card_price_conditions['Card.c_buying_price >'] = 100;
				$card_price_conditions['Card.c_buying_price <='] = 200;
			}
			else if($rate == 3) 	
		    {
				$card_price_conditions['Card.c_buying_price >'] = 200;
				$card_price_conditions['Card.c_buying_price <='] = 300;
			}
			else if($rate == 4) 	
		    {
				$card_price_conditions['Card.c_buying_price >'] = 300;
				$card_price_conditions['Card.c_buying_price <='] = 400;
			}
			else if($rate == 5) 	
		    {
				$card_price_conditions['Card.c_buying_price >'] = 400;
				$card_price_conditions['Card.c_buying_price <='] = 500;
			}
			else if($rate == 6) 	
		    {
				$card_price_conditions['Card.c_buying_price >'] = 500;
				$card_price_conditions['Card.c_buying_price <='] = 600;
			}
			else if($rate == 7) 	
		    {
				$card_price_conditions['Card.c_buying_price >'] = 600;
			}
		}*/
		
		if(isset($selected_card_category) && !empty($selected_card_category)) {
			$this->set('cat_id',$selected_card_category);	
			$subCatConditions['cat_parent_id'] = $selected_card_category;
		}
		
		if(isset($selected_sub_category) && !empty($selected_sub_category)) {
			$this->set('sub_cat_id',$selected_sub_category);	
			$this->set('cat_id',$selected_card_category);
			$card_price_conditions['Card.c_cat_id'] = $selected_sub_category;
		}

		$resSubCat = array();
		if(isset($subCatConditions['cat_parent_id']))
		{
			$resSubCat = $this->Category->find('list',array(
						'conditions' => $subCatConditions,
						'fields' => array('cat_id','cat_title'),
						'order'=>'cat_title asc',
						'recursive'	 => -1
			));
		}
		else
		{
			$resSubCat = $this->Category->find('list',array(
								'conditions' => $subCatConditions,
								'fields' => array('cat_id','cat_title'),
								'recursive'	 => -1,
								'joins' => array(
									array(
										'table' => 'ecom_categories',
										'alias' => 'Category_Parent',
										'type' => 'inner',
										'conditions' => 'Category_Parent.cat_id =Category.cat_parent_id'
									)
								),
								'order'=>'cat_title asc'
							));
		}

		$sub_cat_array= array();
		foreach($resSubCat as $k => $v)
		{
			$sub_cat_array[] = $k;
		}
		
		// Cards
		$card_conditions = array();
		$card_conditions['Card.c_status'] = 1;
		if($selected_sub_category)
		$card_conditions['Card.c_cat_id'] = $selected_sub_category;
	    else
	    $card_conditions['Card.c_cat_id'] = $sub_cat_array;
	    	
		
		$card_res = $this->Card->find('list',array(
			'conditions' => $card_conditions,
			'fields' => array('c_id','c_title'),
			'order'=>'c_title asc',
			'recursive'	 => -1
		));
        

		foreach ($cat_res as $key => $value) {
			$cat_res[$key] = ucwords(strtolower($value));
		}

		foreach ($resSubCat as $key => $value) {
			$resSubCat[$key] = ucwords(strtolower($value));
		}

		$this->set('catList',$cat_res);
		$this->set('subCatList',$resSubCat);

		$this->set('cardList',$card_res);

	    if($sub_cat_array)
		{
			if(isset($selected_sub_category) && !empty($selected_sub_category))
			$card_price_conditions['Card.c_cat_id'] = $selected_sub_category;
			else
			$card_price_conditions['Card.c_cat_id'] = $sub_cat_array;	
		}
		
		//prd($card_price_conditions);
		
		$retailer_list = $this->User->find('all',array('conditions'=>array('User.status'=>1,'User.role_id'=>3,'User.added_by'=>$this->Auth->User('id')),'order'=>'User.fname ,User.lname asc','fields'=>array('User.id','User.fname','User.lname')));
		$array_retailer = array();
		foreach($retailer_list as $list_r)
		{
			$rid = $list_r['User']['id'];
			$array_retailer[$rid] = ucwords($list_r['User']['fname']." ".$list_r['User']['lname']);
		}
		
	   	$retailer_name = '';
		if(!empty($retailer_id))
		{
			$get_retailer_data = $this->User->findById($retailer_id);
			$retailer_name = ucwords($get_retailer_data['User']['fname']." ".$get_retailer_data['User']['lname']);
		}
	     
		$this->set('retailer_list',$array_retailer);
		$this->set('retailer_id',$retailer_id);	
		$this->set('retailer_name',$retailer_name);	
		
		/*$card_type_data = $this->Category->find('all',array('conditions'=>array('Category.cat_parent_id'=>NULL,'Category.cat_status'=>1),'order'=>'Category.cat_title asc'));
		$card_category =array();
		$card_category[0] = 'All';
		foreach($card_type_data as $categories)
		{
			$cat_id = ucwords($categories['Category']['cat_id']);
			$title = ucwords($categories['Category']['cat_title']);
			$card_category[$cat_id] = $title;
		}*/
		
		
		if($char_code)
		{
			$card_price_conditions['Card.c_title LIKE'] =$char_code."%";
		}
		//$this->set('card_categories',$card_category);
	    
		
		if((!isset($card_price_conditions['Card.c_cat_id'])) && empty($card_price_conditions['Card.c_cat_id']))
		{
		  if($selected_card_category == 0)
		  $get_selected_child_data = $this->Category->find('all',array('conditions'=>array('Category.cat_status'=>1)));	
		  else
		  $get_selected_child_data = $this->Category->find('all',array('conditions'=>array('Category.cat_id'=>$selected_card_category,'Category.cat_status'=>1)));	
		  
		  $all_sub_category = array();
		  if(!empty($get_selected_child_data[0]['Child']))
		  {
			  for($i = 0;$i<count($get_selected_child_data);$i++)
			  {
			  	foreach($get_selected_child_data[$i]['Child'] as $data)
			  	{
				  $sub_cat_id = $data['cat_id'];
				  $all_sub_category[] = $sub_cat_id;
			  	}
			  }
		  }
		  $card_price_conditions['Card.c_cat_id'] =  $all_sub_category;
		}
		
    	$card_price_conditions['Card.c_status'] =  1;
        //pr($card_price_conditions);
		if($overridden)
		{
			$card_price_conditions['CardsPrice.cp_u_id']=$retailer_id;
			$get_cards = $this->Card->find('all',array(
							'joins' => array(
								array(
									'table' => 'ecom_cards_prices ',
									'alias' => 'CardsPrice',
									'type' => 'left',
									'conditions' => 'CardsPrice.cp_c_id = Card.c_id',
								)
							),
							'conditions'=>$card_price_conditions,
							'order'=>'Card.c_title asc')
							);
		
		}
		else
		{
			$get_cards = $this->Card->find('all',array('conditions'=>$card_price_conditions,'order'=>'Card.c_title asc'));
		}
		
		//prd($get_cards);
		$this->set('get_cards',$get_cards);
		if(!empty($this->request->data))
		{
			$new_data = $this->request->data;
			$new_post_data = array();
			$count = 0;
			$update = 0;
			$old_key = '';
			foreach($new_data as $key => $value)
			{
				$explode_data = explode('_',$key);
			    if($old_key !=  $explode_data[1])
				{
					$old_key = $explode_data[1];
				    $new_post_data[$old_key]['Card']['c_id']  = $explode_data[1];
				}
				if($explode_data[0] == 'totalamount')
				$new_post_data[$old_key]['Card']['c_selling_price']  = $value;
				else
				$new_post_data[$old_key]['Card']['c_buying_price']  = $value;
			}
			
			$count = count($new_post_data);
			$new_card_data = array();
			$new_card_price_data = array();
			
			$counter = 0;
			foreach($new_post_data as $post_data)
		    {
				if($retailer_id != 0)
				{
				$already_exists = $this->CardsPrice->find('first',array('conditions'=>array('CardsPrice.cp_u_id' =>$retailer_id,'CardsPrice.cp_u_role' =>3,'CardsPrice.cp_c_id'=>$post_data['Card']['c_id'])));
				}
				else
				{
					$already_exists = $this->CardsPrice->find('first',array('conditions'=>array('CardsPrice.cp_u_id' =>$this->Auth->User('id'),'CardsPrice.cp_u_role' =>2,'CardsPrice.cp_c_id'=>$post_data['Card']['c_id'])));
				}
				if($already_exists)
				{
					$diffrence = $post_data['Card']['c_selling_price']-$post_data['Card']['c_buying_price'];
					$profit =  ($diffrence/$already_exists['CardsPrice']['cp_buying_price'])*100;
					
					if($retailer_id)
					$new_card_price_data['CardsPrice'][$counter]['cp_u_id'] = $retailer_id;
					else
					$new_card_price_data['CardsPrice'][$counter]['cp_u_id'] = $this->Auth->User('id');
					
					$new_card_price_data['CardsPrice'][$counter]['cp_id'] = $already_exists['CardsPrice']['cp_id'];
					
					if($retailer_id)
					$new_card_price_data['CardsPrice'][$counter]['cp_buying_price'] = $post_data['Card']['c_selling_price'];
					else
					$new_card_price_data['CardsPrice'][$counter]['cp_selling_price'] = $post_data['Card']['c_selling_price'];
					
					$new_card_price_data['CardsPrice'][$counter]['cp_updated_date'] =  date('Y-m-d H:i:s');
					$new_card_price_data['CardsPrice'][$counter]['cp_id'] = $already_exists['CardsPrice']['cp_id'];
					$new_card_price_data['CardsPrice'][$counter]['cp_updated_by'] = $this->Auth->User('id');
					$new_card_price_data['CardsPrice'][$counter]['cp_profit'] = $profit;;
				}
				else
				{
					if($retailer_id)
					$new_card_price_data['CardsPrice'][$counter]['cp_u_id'] = $retailer_id;
					else
					$new_card_price_data['CardsPrice'][$counter]['cp_u_id'] = $this->Auth->User('id');

					$new_card_price_data['CardsPrice'][$counter]['cp_u_role']  = 3;
					$new_card_price_data['CardsPrice'][$counter]['cp_c_id']  = $post_data['Card']['c_id'];
					
					if($retailer_id)
					{
						$new_card_price_data['CardsPrice'][$counter]['cp_buying_price']  = $post_data['Card']['c_selling_price'];
						$new_card_price_data['CardsPrice'][$counter]['cp_selling_price']  = $post_data['Card']['c_selling_price'];
					}
					else
					{
						$new_card_price_data['CardsPrice'][$counter]['cp_buying_price']  = $post_data['Card']['c_selling_price'];
					}
	
					$diffrence = $post_data['Card']['c_selling_price']-$post_data['Card']['c_buying_price'];
					$profit =  ($diffrence/$post_data['Card']['c_buying_price'])*100;
					$new_card_price_data['CardsPrice'][$counter]['cp_profit']  = $profit;
					
					$new_card_price_data['CardsPrice'][$counter]['cp_created_by']  = $this->Auth->User('id');
					$new_card_price_data['CardsPrice'][$counter]['cp_updated_by']  = $this->Auth->User('id');
					$new_card_price_data['CardsPrice'][$counter]['cp_created_date']  = date('Y-m-d H:i:s');
					$new_card_price_data['CardsPrice'][$counter]['cp_updated_date']  = date('Y-m-d H:i:s');
				}
		     $counter++;
		   }
           	
			foreach($new_card_price_data as $new)
			{
				$update_child = $this->CardsPrice->saveAll($new);
			}
			
			if($update_child)
			{
				 $this->Session->setFlash(__('Records has been updated successfully.'), 'default', array('class' => 'success'));
			}
			else
			{
				$this->Session->setFlash(__('Records could not be updated.'), 'default', array('class' => 'error'));
			}
			$this->redirect(array('action'=>'manage_price_mediator',$char_code,$retailer_id,$selected_card_category,$selected_sub_category,$card_rate,$card_id));
		}
	}
	
	
	public function manage_price_retailer($selected_card_category = 0,$char_code = 0,$selected_sub_category = 0){
		
		$this->set("title_for_layout",__('Selling Price Details'));	
		$this->loadModel('Category');
		$this->loadModel('CardsPrice');
		
		
		$added_by = $this->Auth->User('added_by');
		
		$card_type_data = $this->Category->find('all',array('conditions'=>array('Category.cat_parent_id'=>NULL,'Category.cat_status'=>1),'order'=>'Category.cat_title asc'));
		$card_category =array();
		$card_category[0] = __('All');
		foreach($card_type_data as $categories)
		{
			$cat_id = ucwords($categories['Category']['cat_id']);
			$title = ucwords($categories['Category']['cat_title']);
			$card_category[$cat_id] = $title;
		}
		
		$card_price_conditions =array();
		if($char_code != '0')
		{
			$card_price_conditions['Card.c_title LIKE'] =$char_code."%";
			$this->set('selected_card_category',$selected_card_category);
			$this->set('selchar',$char_code);
		}
		else if($selected_card_category != 0)
		{
		  $get_selected_cat_data = $this->Category->findByCatId($selected_card_category);
		  $this->set('selected_card_category',$get_selected_cat_data['Category']['cat_id']);
		  $this->set('selchar',$char_code);
		}
		else
		{
    		$this->set('selected_card_category','0');
			$this->set('selchar','0');
		}
		$this->set('card_categories',$card_category);
	    
		
        $subCateList = array();
        $subCateList[0] = __("All");
	      
		if($selected_card_category != '0')
		{
		  $get_selected_child_data = $this->Category->find('first',
		  				array('conditions'=>
		  					array('Category.cat_id'=>$selected_card_category,
		  						  'Category.cat_status'=>1),
		  				   'order'=>'Category.cat_title asc'));	
          //prd($get_selected_child_data);
		  $all_sub_category = array();
		  if(!empty($get_selected_child_data['Child']))
		  {
              foreach($get_selected_child_data['Child'] as $key => $data)
			  {
				  if($data['cat_status'] == 1)
				  {
				  	$sub_cat_id = $data['cat_id'];
				  	$all_sub_category[] = $sub_cat_id;
                  	$subCateList[$data['cat_id']] = $data['cat_title'];
				  }
				  
			  }
              //pr($subCateList);
		  }
		   $card_price_conditions['Card.c_cat_id'] =  $all_sub_category;
        }
        else
        {
           $resSubCat = $this->Category->find('list',array(
								'conditions' =>array('Category.cat_status'=>1,'Category.cat_parent_id <>'=>null),
								'fields' => array('cat_id','cat_title'),
								'recursive'	 => -1,
								'joins' => array(
									array(
										'table' => 'ecom_categories',
										'alias' => 'Category_Parent',
										'type' => 'inner',
										'conditions' => 'Category_Parent.cat_id =Category.cat_parent_id'
									)
								),
								'order'=>'cat_title asc'
							));
          $all_sub_category = array();
          foreach( $resSubCat as $k =>$v)
          $all_sub_category[] = $k;
          $card_price_conditions['Card.c_cat_id'] =  $all_sub_category;
           
        }
        
        
        if($selected_sub_category != 0)
        {
            		 $card_price_conditions['Card.c_cat_id'] =  $selected_sub_category;
        }
        
        
    	$card_price_conditions['Card.c_status'] =  1;
		$this->set('selected_sub_category',$selected_sub_category);
        $this->set('subCatList',$subCateList);
        
		$get_cards = $this->Card->find('all',array('conditions'=>$card_price_conditions,'order'=>'Card.c_title asc'));
		$this->set('get_cards',$get_cards);
		if(!empty($this->request->data['Card']))
		{
			$new_data = $this->request->data['Card'];
			$new_post_data = array();
			$count = 0;
			$update = 0;
			$old_key = '';
			foreach($new_data as $key => $value)
			{
				$explode_data = explode('_',$key);
			    if($old_key !=  $explode_data[1])
				{
					$old_key = $explode_data[1];
				    $new_post_data[$old_key]['Card']['c_id']  = $explode_data[1];
				}
				if($explode_data[0] == 'totalamount')
				$new_post_data[$old_key]['Card']['c_selling_price']  = $value;
				else
				$new_post_data[$old_key]['Card']['c_buying_price']  = $value;
			}
			
			$count = count($new_post_data);
			$new_card_data = array();
			$new_card_price_data = array();
			
			$counter = 0;
			
			foreach($new_post_data as $post_data)
		    {
				$already_exists = $this->CardsPrice->find('first',array('conditions'=>array('CardsPrice.cp_u_id' =>$this->Auth->User('id'),'CardsPrice.cp_u_role' =>3,'CardsPrice.cp_c_id'=>$post_data['Card']['c_id'])));
				if($already_exists)
				{
					$diffrence = $post_data['Card']['c_selling_price']-$post_data['Card']['c_buying_price'];

					if($already_exists['CardsPrice']['cp_buying_price'] == 0)
					$already_exists['CardsPrice']['cp_buying_price'] = 1;

					$profit =  ($diffrence/$already_exists['CardsPrice']['cp_buying_price'])*100;
					$new_card_price_data['CardsPrice'][$counter]['cp_id'] = $already_exists['CardsPrice']['cp_id'];
					$new_card_price_data['CardsPrice'][$counter]['cp_selling_price'] = $post_data['Card']['c_selling_price'];
					$new_card_price_data['CardsPrice'][$counter]['cp_updated_date'] =  date('Y-m-d H:i:s');
					$new_card_price_data['CardsPrice'][$counter]['cp_id'] = $already_exists['CardsPrice']['cp_id'];
					$new_card_price_data['CardsPrice'][$counter]['cp_updated_by'] = $this->Auth->User('id');
					$new_card_price_data['CardsPrice'][$counter]['cp_profit'] = $profit;;
				}
				else
				{
					$new_card_price_data['CardsPrice'][$counter]['cp_u_id']  =  $this->Auth->User('id');
					$new_card_price_data['CardsPrice'][$counter]['cp_u_role']  = 3;
					$new_card_price_data['CardsPrice'][$counter]['cp_c_id']  = $post_data['Card']['c_id'];
					$new_card_price_data['CardsPrice'][$counter]['cp_buying_price']  = $post_data['Card']['c_buying_price'];
					$new_card_price_data['CardsPrice'][$counter]['cp_selling_price']  = $post_data['Card']['c_selling_price'];
					
					$diffrence = $post_data['Card']['c_selling_price']-$post_data['Card']['c_buying_price'];
					
					if($post_data['Card']['c_buying_price']>0)
					$profit =  ($diffrence/$post_data['Card']['c_buying_price'])*100;
					else
					$profit = 0.00;
					
					$new_card_price_data['CardsPrice'][$counter]['cp_profit']  = $profit;

					$new_card_price_data['CardsPrice'][$counter]['cp_created_by']  = $this->Auth->User('id');
					$new_card_price_data['CardsPrice'][$counter]['cp_updated_by']  = $this->Auth->User('id');
					
					$new_card_price_data['CardsPrice'][$counter]['cp_created_date']  = date('Y-m-d H:i:s');
					$new_card_price_data['CardsPrice'][$counter]['cp_updated_date']  = date('Y-m-d H:i:s');
					
					//prd($new_card_price_data);
				}
		     $counter++;
		   }

			foreach($new_card_price_data as $new)
			{
				$update_child = $this->CardsPrice->saveAll($new);
				
			}
			
			if($update_child)
			{
				 $this->Session->setFlash(__('Records has been updated successfully.'), 'default', array(),'success');
			}
			else
			{
				$this->Session->setFlash(__('Records could not be updated.'), 'default', array(),'error');
			}
			
			if($selected_card_category || $char_code || $selected_sub_category)
			$this->redirect(array('action'=>'manage_price_retailer',$selected_card_category,$char_code,$selected_sub_category));
		    else
		    $this->redirect(array('action'=>'manage_price_retailer'));
		}
    }
    
    
        
     /**
	 * 
	 * @param array $image
	 * @return bool TRUE or string ERROR Msg.
	 */
	protected function _validatePinImageSize($image = array())
	{
		$imgSz = getimagesize($image["tmp_name"]);
        
		$valid['minW'] = '110';
		$valid['minH'] = '70';
		$valid['maxW'] = '1760';
		$valid['maxH'] = '2480';

		if (($imgSz[0] >= $valid['minW'] && $imgSz[1] >= $valid['minH']) && ($imgSz[0] <= $valid['maxW'] && $imgSz[1] <= $valid['maxH']))
		{
			return true;
		}
		else
		{
			$msg = "Image size should be within " . $valid['minW'] . 'px X ' . $valid['minH'] . "px and " . $valid['maxW'] . 'px X ' . $valid['maxH'] . 'px';
			return $msg;
		}
	}
	
	
	public function admin_card_inventory($cat_id = 0, $sub_cat_id= 0,$card_id = 0)
	{
		$this->set("title_for_layout",__('Card Inventory Report'));	
		$this->loadModel('Card');
		$this->loadModel('Sale');
		$this->loadModel('Category');
		
		$this->set('card_id',$card_id);
		
		$res = $this->Category->find('list',array(
			'conditions' => array('cat_parent_id'=>null),
			'fields' => array('cat_id','cat_title'),
			'recursive'	 => -1,
			'order'=>'cat_title asc'
		));
            
		foreach($res as $k => $v)
		$res[$k] = ucwords($v);
		
		$this->set('cateList',$res);
		$this->set('selected_cat',$cat_id);
        
        $card_conditions = array();
        
        $resSubCat = array();
		$subCatConditions = array();
		//$subCatConditions['Category.cat_status'] = 1;
		
		if(!empty($cat_id) && $cat_id > 0)
		{
			if (isset($cat_id) && !empty($cat_id)) 
			{
				$this->set('cat_id', $cat_id);
				$subCatConditions['Category.cat_parent_id'] = $cat_id;
			    
			    $resSubCat = $this->Category->find('list', array(
				'conditions' => $subCatConditions,
				'fields' => array('cat_id', 'cat_title'),
				'recursive' => -1,
				'order' => 'cat_title asc'
			   ));
			}
			
		}
		else
		{
			$subCatConditions['Category.cat_parent_id <>'] = null;
			$resSubCat = $this->Category->find('list',array(
							'conditions' => $subCatConditions,
							'fields' => array('cat_id','cat_title'),
							'recursive'	 => -1,
							'joins' => array(
								array(
									'table' => 'ecom_categories',
									'alias' => 'Category_Parent',
									'type' => 'inner',
									'conditions' => 'Category_Parent.cat_id =Category.cat_parent_id'
								)
							),
							'order'=>'cat_title asc'
						));

		}
		$this->set('subCateList',$resSubCat);
		$this->set('selected_sub_cat',$sub_cat_id);
		/* END Category and subcategory Listing */
		
		
		//$card_conditions['c_status'] = 1;
		
		if($card_id)
		{
		   $card_conditions['c_id'] = $card_id;	
		}
		else if($sub_cat_id > 0 && $cat_id > 0)
		{
			$card_conditions['c_cat_id'] = $sub_cat_id;
		}
		else if($cat_id > 0)
		{
			$card_conditions['c_cat_id'] = array_keys($resSubCat);
		}
			//pr($card_conditions);
		$order = 'c_title asc';
		$fields = array('c_id','c_title');

		if(!isset($card_conditions['c_cat_id']))
		{
			$all_sub_cat = array();
			foreach($resSubCat as $k => $v)
			{
				
				$all_sub_cat[] = $k;
			}
			$card_conditions['c_cat_id'] = $all_sub_cat;
		}
		

		$get_cards = $this->Card->find('list',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$fields));
		//pr($get_cards);
		$all_cards =array();
		$all_cards[0] = __('--- All ---');
		foreach($get_cards as $k=>$v)
		{
			$all_cards[$k] = ucwords($v);
		}
		
		$card_fields = array('Card.c_title','Card.c_pin_per_card','Card.sale_count','Card.pin_card_count','Card.pin_card_sold_count','Card.pin_card_remain_count','Card.c_id','Category.cat_title','Category.cat_parent_id');
		
		$get_card_details = $this->Card->find('all',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$card_fields));
		$final_card_array = array();
		$counter = 0;
		foreach($get_card_details as $details)
		{
		   $final_card_array[$counter]['id']    = $details['Card']['c_id'];
		   $final_card_array[$counter]['title'] = ucwords(strtolower($details['Card']['c_title']));
	       $final_card_array[$counter]['card_sold'] = intval($details['Card']['pin_card_sold_count'] / $details['Card']['c_pin_per_card']);
		   $final_card_array[$counter]['card_remaining'] = intval($details['Card']['pin_card_remain_count'] / $details['Card']['c_pin_per_card']);
		   $final_card_array[$counter]['subcategory'] =  ucwords(strtolower($details['Category']['cat_title']));
		   $final_card_array[$counter]['category'] =  ucwords(strtolower($res[$details['Category']['cat_parent_id']]));
		   $counter++; 
		}
		
		$this->set('all_cards',$all_cards);
		$this->set('final_card_array',$final_card_array);
	}
	
	
	public function  admin_excel_card_inventory($cat_id=0,$sub_cat_id = 0,$card_id = 0){
	
  		ini_set('max_execution_time', 0);
		// Create new PHPExcel object
		$this->loadModel('Card');
        $this->loadModel('Category');	
		
		
		
		$res = $this->Category->find('list',array(
			'conditions' => array('cat_parent_id'=>null),
			'fields' => array('cat_id','cat_title'),
			'recursive'	 => -1,
			'order'=>'cat_title asc'
			));
		
		foreach($res as $k => $v)
		$res[$k] = ucwords($v);
		
		
		$card_conditions = array();
		//$card_conditions['c_status'] = 1;
		if($card_id)
		{
			$card_conditions['Card.c_id'] =$card_id;
		}
		else if($sub_cat_id)
		{
			$card_conditions['Card.c_cat_id'] =$sub_cat_id;
		}
		else if(!empty($cat_id) && $cat_id > 0)
		{
			$subCatConditions = array();
			//$subCatConditions['cat_status'] = 1;
			$subCatConditions['cat_parent_id'] = $cat_id;

			$resSubCat = $this->Category->find('list', array(
				'conditions' => $subCatConditions,
				'fields' => array('cat_id', 'cat_title'),
				'recursive' => -1,
				'order' => 'cat_title asc'
			));

			$card_conditions['c_cat_id'] = array_keys($resSubCat);
		}
		else
		{
            $subCatConditions = array();
			//$subCatConditions['Category.cat_status'] = 1;
			$subCatConditions['Category.cat_parent_id <>'] = null;
			$resSubCat = $this->Category->find('list',array(
							'conditions' => $subCatConditions,
							'fields' => array('cat_id','cat_title'),
							'recursive'	 => -1,
							'joins' => array(
								array(
									'table' => 'ecom_categories',
									'alias' => 'Category_Parent',
									'type' => 'inner',
									'conditions' => 'Category_Parent.cat_id =Category.cat_parent_id'
								)
							),
							'order'=>'cat_title asc'
						));
		   $card_conditions['c_cat_id'] = array_keys($resSubCat);
		}
		/* END Category and subcategory Listing */
		
			//pr($card_conditions);
		$order = 'c_title asc';
		$card_fields = array('Card.c_title','Card.c_pin_per_card','Card.sale_count','Card.pin_card_count','Card.pin_card_sold_count','Card.pin_card_remain_count','Card.c_id','Category.cat_title','Category.cat_parent_id');
		$get_card_details = $this->Card->find('all',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$card_fields));
        
		//pr($card_conditions);prd($get_card_details); 
		$counter = 0;
        $total_sold = 0;
		$total_remaining=0;
		$final_card_array = array();
		foreach($get_card_details as $details)
		{
		   $final_card_array[$counter]['id']    = $details['Card']['c_id'];
		   $final_card_array[$counter]['title'] = ucwords(strtolower($details['Card']['c_title']));
	       $final_card_array[$counter]['card_sold'] = intval($details['Card']['pin_card_sold_count'] / $details['Card']['c_pin_per_card']);
		   $final_card_array[$counter]['card_remaining'] = intval($details['Card']['pin_card_remain_count'] / $details['Card']['c_pin_per_card']);
		   $final_card_array[$counter]['subcategory'] =  ucwords(strtolower($details['Category']['cat_title']));
		   $final_card_array[$counter]['category'] =  ucwords(strtolower($res[$details['Category']['cat_parent_id']]));

		   $total_remaining = $total_remaining +  $final_card_array[$counter]['card_remaining'] ;
		   $total_sold = $total_sold +  $final_card_array[$counter]['card_sold'] ;

		   $counter++; 
		}
		
		//error_reporting(E_ALL);
		$objPHPExcel = new PHPExcel();
		
		// Setting Column Width
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		
		$objPHPExcel->getProperties()->setCreator("Calling Cards")->setLastModifiedBy("Calling Cards")->setTitle("Office 2007 XLSX Document")
																 ->setSubject("Calling Cards")
																 ->setDescription("Calling Cards")
																 ->setKeywords("office 2007 openxml php")
																 ->setCategory("Export To Excel");		
		
		$objPHPExcel->setActiveSheetIndex(0);		
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		
		// Title of the Report		
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValue('A2','Card Inventory Report');			
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		
		$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
		
		$heading = 4;
		$FirstItemNumber = $heading + 2;

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,'S.No');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,"Category");
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,"Sub Category");
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,"Card");
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$heading,'Total Sold');
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$heading,"Remaining");
		
         

		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':F'.$heading)->getFont()->setBold(true);;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':F'.$heading)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':F'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		
		$serial_counter = 0;
		foreach($final_card_array as $data)
		{
			$serial_counter++;
   
		    $objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,$data['category']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,$data['subcategory']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,$data['title']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,$data['card_sold']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,$data['card_remaining']);
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getFont()->setBold(false)->setSize(9);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);

			$FirstItemNumber++;
		}
		
		// Setting Last Line Style For Net Total
		$FirstItemNumber++;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getFont()->setBold(True)->setSize(11);

        // Merging Column
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':D'.$FirstItemNumber);

		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,'Net Quantity');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,$total_sold);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,$total_remaining);
		
		// Set page margin
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);

        $file_name = 'CardInventory.xlsx';    
		$fullPath = WWW_ROOT.$file_name;
 
     	$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');		
		$objWriter->save($fullPath);
		
	   if ($fd = fopen ($fullPath, "r")) 
		{
				$fsize = filesize($fullPath);
				$path_parts = pathinfo($fullPath);
				$ext = strtolower($path_parts["extension"]);
				switch ($ext) {
					case "xlsx":
						header("Content-type: application/vnd.ms-excel;charset:UTF-8"); // add here more headers for diff. extensions
						header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
					break;
					default;
						header("Content-type: application/octet-stream");
						header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
				}
				header("Content-length: $fsize");
				header("Cache-control: private"); //use this to open files directly
				while(!feof($fd)) {
					$buffer = fread($fd, 2048);
					echo $buffer;
				}
		    fclose ($fd);
    		unlink($fullPath);
		}
		exit();
	}
}
