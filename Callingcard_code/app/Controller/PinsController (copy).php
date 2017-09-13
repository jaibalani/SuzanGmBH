<?php
App::uses('AppController', 'Controller');

class PinsController extends AppController 
{
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('');
	}
	public function admin_index($card_id=NULL, $from_card = NULL){

		$this->set("title",'Manage card PINs');
		$back_cat = '';
		$c_id = '';
		
		$url = Router::url(array('controller' => 'cards', 'action' => 'index',$card_id));
		if($from_card == 1)
		{
			$url = Router::url(array('controller' => 'cards', 'action' => 'index'));
		}
		$this->set('url',$url);
		
		if(isset($card_id) && !empty($card_id)){
			$this->loadModel('Card');
			$res = $this->Card->find('first',array(
				'conditions' => array('c_id'=>$card_id),
				'recursive'	 => 0
			));
			if(count($res)==0){
					$this->Session->setFlash(__('Unauthorized access.'), 'default', array('class' => 'error'));
					$this->redirect(array('action' => 'index','admin'=>true));	
			}
			$this->set('title', "Manage Pins for ".$res['Card']['c_title']);
			$back_cat = $res['Card']['c_cat_id'];
			$c_id = $res['Category']['cat_parent_id'];
		}
		//prd($res);
		$this->set('card_id',$card_id);
		$this->set('sub_cat_id',$back_cat);
		$this->set('cat_id',$c_id);


		if($this->request->is('Post')) {

			$data = $this->request->data ;
			
			$url = '';
			if(isset($data['Pin']['cat_id']) && !empty($data['Pin']['cat_id']) ) {
				$subCatConditions['cat_parent_id'] = $data['Pin']['cat_id'];
				$url .= '/cat_id:'.$data['Pin']['cat_id'];
			}

			if(isset($data['Pin']['sub_cat_id']) && !empty($data['Pin']['sub_cat_id']) ) {
				$url .= '/sub_cat_id:'.$data['Pin']['sub_cat_id'];
			}

			if(isset($data['Pin']['card_id']) && !empty($data['Pin']['card_id']) ) {
				$url .= '/card_id:'.$data['Pin']['card_id'];
			}

			$this->redirect(array('admin'=>true,'controller'=>'Pins','action'=>'index'.$url));
		}


		$this->loadModel('Category');
		$this->loadModel('Card');

		$res = $this->Category->find('list',array(
			'conditions' => array('cat_status !='=>2,'cat_parent_id'=>null),
			'fields' => array('cat_id','cat_title'),
			'order'=>'cat_title asc',
			'recursive'	 => -1
		));

    	foreach($res as $k => $v)
		$res[$k] = ucwords(strtolower($v));

		$named = $this->request->named;

		$subCatConditions = array();
		$subCatConditions['NOT']['cat_status'] = 2;
		$subCatConditions['NOT']['cat_parent_id'] = null;

		if(isset($named['cat_id']) && !empty($named['cat_id'])) {
			$this->set('cat_id',$named['cat_id']);	
			$subCatConditions['cat_parent_id'] = $named['cat_id'];
		}

		if(isset($named['cat_id']) && !empty($named['cat_id'])) {
			$this->set('cat_id',$named['cat_id']);	
			$subCatConditions['cat_parent_id'] = $named['cat_id'];
		}
        
		$resSubCat = $this->Category->find('list',array(
						'conditions' => $subCatConditions,
						'fields' => array('cat_id','cat_title'),
						'recursive'	 => -1,
						'order'=>'cat_title asc',
					));
		
    	foreach($resSubCat as $k => $v)
		$resSubCat[$k] = ucwords(strtolower($v));

        $cardCondition = array();
		$cardCondition['c_status !='] = 2 ;
		$cardCondition['c_cat_id'] = array_keys($resSubCat) ;

		if(isset($named['sub_cat_id']) && !empty($named['sub_cat_id']) && in_array($named['sub_cat_id'],array_keys($resSubCat))) {
			$this->set('sub_cat_id',$named['sub_cat_id']);	
			$cardCondition['c_cat_id'] = $named['sub_cat_id'] ;
		}
        
		$cardRes = $this->Card->find('list',array(
			'conditions' => $cardCondition,
			'fields' => array('c_id','c_title'),
			'recursive'	 => -1,
			'order'=>'c_title'
		));
		
		if(isset($named['card_id']) && !empty($named['card_id']) && in_array($named['card_id'], array_keys($cardRes)) ) {
			$this->set('card_id',$named['card_id']);	
		}
		

		$this->set('catList',$res);
		$this->set('subCatList',$resSubCat);
		$this->set('cardList',$cardRes);

	}
	public function admin_json($card_id=NULL){
		if($this->request->is('ajax')){
			$this->autoRender = false;

			$this->loadModel('Card');
			$this->loadModel('Category');

			$params = $this->request->query;

			$page 	= $params['page'];
			$limit  = $params['rows'];
			$sidx 	= $params['sidx'];
			$sort 	= $params['sord'];

	        
			$card_id = '';
			$cat_id = '';
			$sub_cat_id = '';
            $conditions = array();				
      		if(isset($this->request->query['filters'])){
				$filters = json_decode($this->request->query['filters'], true);
				foreach($filters['rules'] as $each_filter){
					if($each_filter['field'] == 'p_serial'){
						$conditions['Pin.p_serial LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
					}
					if($each_filter['field'] == 'p_pin'){
						$conditions['Pin.p_pin LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
					}
					if($each_filter['field'] == 'c_title'){
						$conditions['Card.c_title LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
					}
					if($each_filter['field'] == 'pc_status'){
						if($each_filter['data'] != 0)
						$conditions['PinsCard.pc_status'] = Sanitize::clean($each_filter['data']);
					}
				}
			}
           
			$named = $this->request->named;

			
			if(isset($named['card_id']) && !empty($named['card_id'])) {
				$conditions['PinsCard.pc_c_id'] = $named['card_id'];
				$card_id = $named['card_id'];
			}
			else {

				if(isset($named['sub_cat_id']) && !empty($named['sub_cat_id'])) {
					
					$cardCondition = array();
					$cardCondition['c_status !='] = 2 ;
					$cardCondition['c_cat_id'] = $named['sub_cat_id'] ;
                    $sub_cat_id = $named['sub_cat_id'];
					
					$cardRes = $this->Card->find('list',array(
						'conditions' => $cardCondition,
						'fields' => array('c_id','c_title'),
						'recursive'	 => -1
					));
					$conditions['PinsCard.pc_c_id'] = array_keys($cardRes);
				}
				else 
				{
				       if(isset($named['cat_id']) && !empty($named['cat_id'])) {
						
						$subCatConditions = array();
						$subCatConditions['NOT']['cat_status'] = 2;
						$subCatConditions['cat_parent_id'] = $named['cat_id'];
                        $cat_id = $named['cat_id'];
						
						$resSubCat = $this->Category->find('list',array(
							'conditions' => $subCatConditions,
							'fields' => array('cat_id','cat_title'),
							'recursive'	 => -1
						));
						

						$cardCondition = array();
						$cardCondition['c_status !='] = 2 ;
						$cardCondition['c_cat_id'] = array_keys($resSubCat) ;

						$cardRes = $this->Card->find('list',array(
							'conditions' => $cardCondition,
							'fields' => array('c_id','c_title'),
							'recursive'	 => -1
						));
						$conditions['PinsCard.pc_c_id'] = array_keys($cardRes);
					}
				}
			}
            $this->set('card_id',$card_id);
			$this->set('cat_id',$cat_id);
			$this->set('sub_cat_id',$sub_cat_id);
            						
			if(!$limit){$limit = 10;}
			if(!$page){$page = 1;}
			if(!$sidx){$sidx = 1;}

			$this->loadModel('PinsCard');
			$count  = $this->PinsCard->find('count',array('conditions'=>$conditions));
			
			if ($count > 0) { 
				$total_pages = ceil($count/$limit); 
			} else { 
				$total_pages = 1; 
			}
			if ($page > $total_pages) $page=$total_pages; 		
			$start = $limit*$page - $limit;	
			//echo $sidx;exit;
			$sidx_array = array('p_serial','p_pin','pc_status');
			if(in_array($sidx,$sidx_array)){
				$sidx='CAST('.$sidx.' as UNSIGNED)';
			}
			$this->PinsCard->Behaviors->attach('Containable');
			$resultSet = $this->PinsCard->find('all', 
						array('conditions'=>$conditions,
									'order'=>(array($sidx.' '.$sort)),
									'limit'=>$limit,
									'offset'=>$start,
									'contain'=>array(
													'Pin' => array(),
													'Card'=>array(
															'fields' => array('c_title'),
															'Category' => array(
																'fields' => array('cat_title','cat_parent_id')
															)
													)),								
									));
			$this->PinsCard->Behaviors->detach('Containable');
		//prd($resultSet);
			$response = new stdClass();
			$response->page 	= $page;
			$response->total 	= $total_pages;
			$response->records 	= $count;
			foreach($resultSet as $key=>$val){
				$content = Router::url(array('controller'=>'Pins','action'=>'edit','admin'=>true,$val['Pin']['p_id']));
				//<img src="'.$this->webroot.'img/edit.png" alt="Edit" title="Edit" border="0" />
				$edit = '<a  href="'.$content.'" style="color:#438CE5 !important;">Edit</a>';
				switch($val['Pin']['p_status']){
					case 1 : $status = 'Unused';
										break;
					case 2 : $status = 'Sold';
										break;		
					case 3 : $status = 'Parked';
										break;
					case 4 : $status = 'Rejected';
										break;						
					case 5 : $status = 'Returned';
										break;																																		
				}
				$cards = '';
				$sub_cat_title = '';
				$cat_title = '';
				
                                if(isset($val['PinsCard']['pin_file']) && !empty($val['PinsCard']['pin_file']))
                                {
                                    $pin_file = $val['PinsCard']['pin_file'];
                                }
				else
                                {
                                    $pin_file = 'NA';
                                }
                                
                                if(isset($val['PinsCard']['pin_created']) && !empty($val['PinsCard']['pin_created']))
                                {
                                    $pin_created = date('d.m.Y',strtotime($val['PinsCard']['pin_created']));
                                }
				else
                                {
                                    $pin_created = 'NA';
                                }
                                
                                if(isset($val['PinsCard']) && !empty($val['PinsCard'])){

					if(isset($val['Card']['c_title']) && !empty($val['Card']['c_title'])){
						$cards = ucwords(strtolower($val['Card']['c_title']));	
					}

					if(isset($val['Card']['Category']['cat_title']) && !empty($val['Card']['Category']['cat_title'])) {
						$sub_cat_title = ucwords(strtolower($val['Card']['Category']['cat_title']));	
					}

					if(isset($val['Card']['Category']['cat_parent_id']) && !empty($val['Card']['Category']['cat_parent_id'])) {
						$this->loadModel('Category');
						$main_cat_data = $this->Category->findByCatId($val['Card']['Category']['cat_parent_id']);
						$cat_title =  ucwords(strtolower($main_cat_data['Category']['cat_title']));
					}
				}
				$response->rows[$key]['id']   = $val['Pin']['p_id'];			
				$response->rows[$key]['cell'] = array($val['Pin']['p_serial'],$val['Pin']['p_pin'],$pin_file,$pin_created,$cards,$cat_title,$sub_cat_title, $edit,$status);
			}

			echo json_encode($response);
		}else{
			$this->redirect(array('action' => 'index','controller'=>'Categories'));
		}
	}
	public function admin_contentUpload()
	{		
		App::import('Vendor', '', array('file' => 'Pin_UploadHandler.php'));
		$upload_handler = new UploadHandler();			
		exit;
	}
	function save_document($filepath,$newname){
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $filepath);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$rawdata=curl_exec($ch);
		curl_close ($ch);
	    $file = new File($filepath);
		$content = $file->read();
		$file->close(); 
		
		$file = new File(WWW_ROOT.'img/admin_uploads/pins_uploaded/'.$newname);
		$result=$file->write($content);
		$file->close();
		if($result)
		return $newname;
		else
		return 0;
  }
	public function admin_add($card_id = NULL){
		$this->loadModel('Category');
		/*$this->Category->Behaviors->attach('Containable');		
		$this->Category->contain(array(
				'Child' =>array(
					
				)
		));
		$parent_categories = $this->Category->find('all',array(
			'conditions' => array('Category.cat_parent_id IS NULL','Category.cat_status'=>1)
		));
		$this->set('parent_categories',$parent_categories);*/
		$this->set('title_for_layout','Import Pins');
		$this->loadModel('Card');
		$CardType = $this->Card->find("list",array("fields"=>array("c_id","c_title"),"conditions"=>array("c_status"=>'1','c_id'=>$card_id)));
		
		if(empty($CardType))
		{
			$this->Session->setFlash('Pins could not be imported as card is not enabled', 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index','controller'=>'Cards'));
		}
		
		$CardDet = $this->Card->find("first",array("fields"=>array("c_id","c_title",'c_cat_id'),"conditions"=>array("c_status"=>'1','c_id'=>$card_id),'recursive'=>-2));
		//prd($CardDet);
		//$category_card = $this->Category->findByCatId($card_id);
		$category_card = $this->Category->findByCatId($CardDet['Card']['c_cat_id']);
		//prd($category_card);
		$main_cat= '';
		$sub_cat = '';
		if($category_card)
		{
			$main_cat = $category_card['Parent']['cat_id'];
			$sub_cat = $category_card['Category']['cat_id'];	
		}
		//echo $main_cat.' '.$sub_cat;exit;
		$this->set('main_cat',$main_cat);
		$this->set('sub_cat',$sub_cat);
		$this->set('card_id',$card_id);
		
		foreach($CardType as $k=>$v)
		$card_id = $k;
		
		$this->set('CardType',$CardType);
		$this->loadModel('Pin');
		$this->set('card_id',$card_id);

		$cat_names = $this->Category->find('list',array(
				'fields'		 => array('cat_id','cat_title'),
				'conditions' => array('cat_status'=>1,'cat_parent_id'=>NULL,'cat_id'=>$main_cat)
		));
		
		$sub_categories = $this->Category->find('list',array(
				'fields'		 => array('cat_id','cat_title'),
				'conditions' => array('cat_status'=>1,'cat_parent_id'=>$main_cat)
		));
	//	pr($cat_names);
		//prd($sub_categories);
		$this->set('subCatList',$sub_categories);
		$this->set('catList',$cat_names);

		if($this->request->is('post') || $this->request->is('put')){
			    //prd($this->request->data);
				//prd($_SESSION);
				ini_set('max_execution_time', 3000);
				include(APP.'Vendor/Excel/reader.php');
				$file_name	= $_SESSION['upload_pin_excel'];		
				
				include(APP . 'Vendor/PHPExcel/Classes/PHPExcel.php');
				include(APP . 'Vendor/PHPExcel/Classes/PHPExcel/IOFactory.php');
				
				unset($_SESSION['upload_pin_excel']);
				$file_path = WWW_ROOT .'img/admin_uploads/pins_uploaded/'.$file_name;
				$ext = pathinfo($file_name, PATHINFO_EXTENSION);
				$ext = pathinfo($file_name, PATHINFO_EXTENSION);
				$newname = "Pin_".time(). ".". $ext;
				$upload_doc = $this->save_document($file_path,$newname);
				if($upload_doc)
				{
					$new_path=WWW_ROOT .'img/admin_uploads/pins_uploaded/'.$upload_doc;
					$objPHPExcel = PHPExcel_IOFactory::load($new_path);
					//prd($data);
					$objWorksheet = $objPHPExcel->setActiveSheetIndex(0); 
					//$highestRow = $objWorksheet->getHighestRow(); 
					$highestRow = $objWorksheet->getHighestDataRow(); 
					
					$highestColumn = $objWorksheet->getHighestColumn();  
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);  
					//prd($highestRow);
					for ($row = 1; $row <= $highestRow;++$row) 
					{  
						for ($col = 0; $col <$highestColumnIndex;++$col)
						{  
							if($col == 2){
								$value= PHPExcel_Shared_Date::ExcelToPHPObject($objWorksheet->getCellByColumnAndRow($col, $row)->getValue())->format('m/d/Y');;  
							}else{
								$value=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();  
							}
							
							$arraydata[$row-1][$col + 1]=$value; 
						}  

					}
					
					$this->loadModel('PinsCard');
					foreach($arraydata as $key => $val)
					{
						//if($key <= 5){//AVOID FIRST LINE IN EXCEL FILE
							$x = 0;
							$this->loadModel('Pin');
							$new_pin = array('1');
							$outer = 0;
							$pin = array();
							$pin['PinsCard'] = array();
							if((isset($val[2]) && !empty($val[2])) && (isset($val[1]) && !empty($val[1]))){
									$pin['Pin']['p_serial'] 	   = $val[1];
									$pin['Pin']['p_pin'] 	   		 = $val[2];
									if(count($val)==3){
										if (strpos($val[2],'.') !== false) {
											$pin['Pin']['p_pin'] 	   		 = $val[3];
										}
									}
									
								if(isset($this->request->data['PinsCard']['pc_c_id_hidden']) && !empty($this->request->data['PinsCard']['pc_c_id_hidden'])){
									$new_array_card_id = array($this->request->data['PinsCard']['pc_c_id_hidden']);
									foreach($new_array_card_id as $card_id)
									{
										$already_exists_pin = $this->Pin->find('first',array('fields'=>array('p_id','p_status'),
																					'conditions'=>array('Pin.p_pin' => trim($pin['Pin']['p_pin'])),'recursive'=>0));
										
                                                                                
                                                                                if(isset($already_exists_pin) && !empty($already_exists_pin))
										{
											//pin already exists
											$already_exists = $this->PinsCard->hasAny(array('PinsCard.pc_p_id' => trim($already_exists_pin['Pin']['p_id']),
                                                                                        									'PinsCard.pc_c_id'=>$card_id));
											if(isset($already_exists) && !empty($already_exists))
											{
												//same pin and same card
												//$pin['PinsCard'][$x]['pc_c_id'] = '';
												unset($pin['PinsCard']);
											}
											else
											{
												//new pin
                                                                                                $pin['PinsCard'][$x]['pin_file'] = $newname;
                                                                                                $pin['PinsCard'][$x]['pin_created'] = date('Y-m-d H:i:s');
												$pin['PinsCard'][$x]['pc_c_id'] = $card_id;
												$pin['PinsCard'][$x]['pc_status']= $already_exists_pin['Pin']['p_status'];
												$pin['PinsCard'][$x]['pc_p_id'] = $already_exists_pin['Pin']['p_id'];
												$pin['Pin']['p_id'] = $already_exists_pin['Pin']['p_id'];
												$x++;
											}
										}
										else
										{
											//new pin
											$pin['PinsCard'][$x]['pc_c_id'] = $card_id;
                                                                                        $pin['PinsCard'][$x]['pin_file'] = $newname;
                                                                                        $pin['PinsCard'][$x]['pin_created'] = date('Y-m-d H:i:s');
											
										}
										$x++;	
									}

								}
								else
								{
									//check if this pin already exists
									$already_exists_pin = $this->Pin->find('first',array('fields'=>array('p_id','p_status'),'conditions'=>array('Pin.p_pin' => trim($pin['Pin']['p_pin'])),'recursive'=>0));
									if(isset($already_exists_pin) && !empty($already_exists_pin))
                                                                        {
											$already_exists = $this->PinsCard->hasAny(array('PinsCard.pc_p_id' => trim($already_exists_pin['Pin']['p_id']),'PinsCard.pc_c_id IS NULL'));
											if(isset($already_exists) && !empty($already_exists)){
												//same pin available for all card
												//$pin['PinsCard'][$x]['pc_c_id'] = '';
												unset($pin['PinsCard']);
											}else{
												//new pin for pc_c_id NULL but pin already exists
												$pin['PinsCard'][$x]['pc_c_id'] = NULL;
												$pin['PinsCard'][$x]['pc_status']= $already_exists_pin['Pin']['p_status'];
												$pin['PinsCard'][$x]['pc_p_id'] = $already_exists_pin['Pin']['p_id'];
												$pin['Pin']['p_id'] = $already_exists_pin['Pin']['p_id'];
												$pin['PinsCard'][$x]['pin_file'] = $newname;
                                                                                                $pin['PinsCard'][$x]['pin_created'] = date('Y-m-d H:i:s');
                                                                                                $x++;
											}
									}
                                                                        else
                                                                        {
										// no such pin exists
										$pin['PinsCard'][$x]['pc_c_id'] = array();
										$x++;
									}
										
								}
								
								if(isset($pin['PinsCard']) && !empty($pin['PinsCard'])){
									$this->Pin->create();
									$this->Pin->saveAssociated($pin);
									$outer++;
								}
							}
							
						//}
					}
					if($outer)
                                        {
						//prd($pin);
						/*$this->Pin->create();
						$this->Pin->saveMany($pin, array('deep' => true));*/
						//@unlink(WWW_ROOT.'img/admin_uploads/pins_uploaded/'.$newname);
						$this->loadModel('Card');
						$this->Session->setFlash(__('Pins imported successfully.'), 'default', array('class' => 'success'));
						$this->redirect(array('controller'=>'Cards','action'=>'index'));
					}
					else
					{
						@unlink(WWW_ROOT.'img/admin_uploads/pins_uploaded/'.$newname);
						@unlink(WWW_ROOT.'img/admin_uploads/pins_uploaded/'.$this->request->data['Pin']['excel']);
						$this->request->data['Pin']['excel'] = '';
						$this->Session->setFlash('Pins could not be imported. Please, try again by uploading excel file format(s.no pin_no)', 'default', array('class' => 'error'));
     				$this->redirect(array('controller'=>'Pins','action'=>'add',$card_id));
					}
					
				
					
				}
		}		
	}
	public function admin_edit($id=NULL){
		if(isset($id) && !empty($id)){
			$this->set('title_for_layout','Edit Pins');
			$this->loadModel('Card');
			$this->loadModel('PinsCard');
			$this->loadModel('Category');
			
			$get_sub_category = $this->PinsCard->findByPcPId($id);
			$selected_sub_cat = array();
			if($get_sub_category)
			{
				$card_sub_pategory= $get_sub_category['Card']['c_cat_id'];
				$get_parent =   $this->Category->findByCatId($card_sub_pategory)	;	
				if($get_parent)
				{
					$parent_id = $get_parent['Category']['cat_parent_id'];		
					$get_all_sub_cat = $this->Category->find('list',array('conditions'=>array('cat_parent_id'=>$parent_id),'fields'=>'cat_id,cat_title'));
					foreach($get_all_sub_cat as $k =>$v)
					{
						$selected_sub_cat[] =$k;
					}
				}
			}
			$conditions_card = array();
			$conditions_card['Card.c_status'] = 1;
			$conditions_card['Card.c_cat_id'] = $selected_sub_cat;
			
			$CardType = $this->Card->find("list",array('conditions' => $conditions_card ,'fields'=>'c_id,c_title'));
			
			$this->set('CardType',$CardType);
			$this->Pin->Behaviors->attach('Containable');
			$resultSet = $this->Pin->find('first', 
						array('conditions'=>array('Pin.p_id'=>$id),
									'contain'=>array(
												'PinsCard'=>array(
													'Card'=>array(
															'fields' => array('c_title'),
															'Category' => array(
																'fields' => array('cat_title')
															)
														)
													)),								
									));
			$this->Pin->Behaviors->detach('Containable');
			$selected_cards = array();
			$cards_id_array = array();
			if(isset($resultSet) && !empty($resultSet)){
				//pin exists
				if(isset($resultSet['PinsCard']) && !empty($resultSet['PinsCard'])){
					foreach($resultSet['PinsCard'] as $val){
						$selected_cards[] = $val['pc_c_id']; //previously sleected cards
						$previous_cards[] = $val['pc_c_id']; //previously sleected cards
						$cards_id_array[$val['pc_c_id']] = $val['pc_id']; // pc_id of those cards
					}
				}
			}else{
				//no such pin exists
				$this->Session->setFlash('Unauthorized access', 'default', array('class' => 'error'));
				$this->redirect(array('action'=>'index'));
			}
			
			
			$this->set('selected',$selected_cards);
			if ($this->request->is('post') || $this->request->is('put')){
				$data = $this->request->data;
				//prd($data);
				$final_data = array();
				$card_array = array();
				$count = 0;
				if(isset($data['Pin']) && !empty($data['Pin'])){
					$this->Pin->create();
					$pin_data['Pin']['p_id'] = $resultSet['Pin']['p_id'];
					$pin_data['Pin']['p_status'] = $data['Pin']['p_status'];
					$this->Pin->save($pin_data['Pin']);
				}
				if(isset($data['PinsCard']) && !empty($data['PinsCard'])){
					//if cards are selected
					if(isset($selected_cards) && !empty($selected_cards)){
						foreach($selected_cards as $pc_id=>$val){
							if (!in_array($val, $data['PinsCard']['pc_c_id'])) {
								//it was previously selected but now it is not selected, removed from selection
								$this->PinsCard->delete(array('PinsCard.pc_id'=>$cards_id_array[$val]));
								unset($previous_cards[$pc_id]);
								$previous_cards = array_values($previous_cards);
							}else{
								//same selection
								$final_data['PinsCard'][$count]['pc_id'] = $cards_id_array[$val];
								$final_data['PinsCard'][$count]['pc_c_id'] = $val;
								$final_data['PinsCard'][$count]['pc_p_id'] = $resultSet['Pin']['p_id'];
								$final_data['PinsCard'][$count]['pc_status'] = $data['Pin']['p_status'];
								$card_array[]=$val;
								$count++;
							}
						}
					}
					//new entries of cards
					foreach($data['PinsCard']['pc_c_id'] as $val){
						if (!in_array($val, $card_array)) {
							//new selected card entry not yet appenede in the array
							if(isset($final_data['PinsCard']) && !empty($final_data['PinsCard'])){
								$count =  count($final_data['PinsCard']);
							}
							$final_data['PinsCard'][$count]['pc_c_id'] = $val;
							$final_data['PinsCard'][$count]['pc_p_id'] = $resultSet['Pin']['p_id'];
							$final_data['PinsCard'][$count]['pc_status'] = $data['Pin']['p_status'];
						}
					}
			}else{
					//no card selected now for this pin
					//check if previously any card were selected for this pin card
					if(isset($selected_cards) && !empty($selected_cards)){
						foreach($selected_cards as $ctr=>$pc_id_index){
							if(isset($cards_id_array[$pc_id_index]) && !empty($cards_id_array[$pc_id_index])){
								if($ctr==(count($selected_cards) - 1)){
									//if last record for this pin card, dont delete instead remove pc_c_id
									$this->PinsCard->updateAll(
											array('PinsCard.pc_c_id' => NULL,'PinsCard.pc_status'=> $data['Pin']['p_status']), 
											array('PinsCard.pc_id' => $cards_id_array[$pc_id_index])
									);
									
									$this->PinsCard->query("UPDATE ecom_pins SET p_status = ".$data['Pin']['p_status']." WHERE p_id IN (
															SELECT pc_p_id
															FROM (
															SELECT pc_p_id AS pc_p_id
															FROM ecom_pins_cards
															WHERE pc_id IN (".implode(",",$cards_id_array[$pc_id_index]).")
															) AS tmptable)");
															
								}else{
								//if more then one cards are available for the same pin then delete all pins except last
										$this->PinsCard->delete(array('PinsCard.pc_id'=>$cards_id_array[$pc_id_index]));
								}
							}
						}
					}
				}
				if(isset($final_data) && !empty($final_data)){
					//$final_data['Pin']['p_id'] = $resultSet['Pin']['p_id'];
					//prd($final_data);
					
					$this->PinsCard->saveMany($final_data['PinsCard']);
					//$this->PinsCard->saveAll($final_data);	
				}
				$this->Session->setFlash('Pin has been updated', 'default', array('class' => 'success'));
				//$this->redirect(array('controller'=>'Pins','action' => 'index'));
			}
			if(empty($this->request->data)){
				$this->request->data['PinsCard']['pc_c_id'] = $selected_cards;
				$this->request->data['Pin']['p_status'] = $resultSet['Pin']['p_status'];
			}
		}else{
			$this->Session->setFlash('Unauthorized access', 'default', array('class' => 'error'));
			$this->redirect(array('action'=>'add'));
		}
	}
	public function admin_downloadexcel($c_id=NULL){
		$this->autoRender = false;
		$this->loadModel('Card');
		$conditions = array('c_status'=>1);
		
		if(isset($c_id) && !empty($c_id)){
			$conditions = array('c_status'=>1,'c_id'=>$c_id);	
		}
		$this->Card->Behaviors->attach('Containable');
		
		$result = $this->Card->find('all', 
						array('conditions'=>$conditions,
									'contain'=>array(
													'CardsFreeText' => array(
													),
													'Category' => array(
														'fields' => array('cat_title'),
														'Parent' => array(
															'fields' => array('cat_title'),
														)
													),
													'PinsCard' => array(
															'Pin' => array(),
													//		'limit' => 10
														),
													),								
									));
		
        // Setting File Name
		$sub_category = str_replace(" ", "_", ucwords($result[0]['Category']['cat_title']));
        $category = str_replace(" ", "_", ucwords($result[0]['Category']['Parent']['cat_title']));
        $card_name = str_replace(" ", "_", ucwords($result[0]['Card']['c_title']));

		$file_name = $category."-".$sub_category."-".$card_name;
		
		$this->Card->Behaviors->detach('Containable');
		
// Create new PHPExcel object
	
		error_reporting(E_ALL);
		$DataNumber	 = 8;
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Calling Cards")->setLastModifiedBy("Calling Cards")->setTitle("Office 2007 XLSX Document")
																 ->setSubject("Calling Cards List")
																 ->setDescription("Calling Cards List")
																 ->setKeywords("office 2007 openxml php")
																 ->setCategory("Export To Excel");		
		
		$objPHPExcel->setActiveSheetIndex(0);		
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		
		// Title of the Report		
		$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setCellValue('A6','List of PINs');			
		$objPHPExcel->getActiveSheet()->getStyle('A6:C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		//prd($result);
		ini_set('max_execution_time', 3000);
		foreach($result as $keynew=>$val){
			if($keynew==0){
				$Orderheading = $DataNumber;
				$OrderDataNum = $Orderheading+2;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$Orderheading,'Card Category');
				
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$Orderheading,"Card SubCategory");
				
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$Orderheading,"Card Name");
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$Orderheading,"Selling Price");
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$Orderheading,"Purchase Price");
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$Orderheading,'Card Image');
				$objPHPExcel->getActiveSheet()->getStyle('F'.$Orderheading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$Orderheading,"Free Text1");
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$Orderheading,"Phone No1 - German");
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$Orderheading,"Phone No2 - English");
				
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$Orderheading,'Local No1');
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$Orderheading,'Local No2');
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$Orderheading,'Local No3');
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$Orderheading,'Local No4');
				$objPHPExcel->getActiveSheet()->setCellValue('N'.$Orderheading,'Local No5');
				$objPHPExcel->getActiveSheet()->setCellValue('O'.$Orderheading,'Local No6');
				$objPHPExcel->getActiveSheet()->setCellValue('P'.$Orderheading,"Free Text2");
				$objPHPExcel->getActiveSheet()->setCellValue('Q'.$Orderheading,"Free Text3");
				$objPHPExcel->getActiveSheet()->setCellValue('R'.$Orderheading,"Free Text4");
				$objPHPExcel->getActiveSheet()->setCellValue('S'.$Orderheading,"Free Text5");
				$objPHPExcel->getActiveSheet()->setCellValue('T'.$Orderheading,"Free Text6");
				$objPHPExcel->getActiveSheet()->setCellValue('V'.$Orderheading,"Web Page");
				$objPHPExcel->getActiveSheet()->setCellValue('W'.$Orderheading,"Card Inventory Threshold");
				$objPHPExcel->getActiveSheet()->setCellValue('X'.$Orderheading,"PINs per card");
				$objPHPExcel->getActiveSheet()->getStyle('A'.$Orderheading.':X'.$Orderheading)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$Orderheading.':X'.$Orderheading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$new_line = $Orderheading+1;
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$new_line,"Pin Serial#");
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$new_line,"Pin #");
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$new_line,"Status");
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$new_line,"Alias");
				$objPHPExcel->getActiveSheet()->getStyle('A'.$new_line.':E'.$new_line)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				
			}else{
				$Orderheading = $OrderItemdataNum+2;
				$OrderDataNum = $Orderheading;
			}
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$OrderDataNum,isset($val['Category']['Parent']['cat_title']) ? $val['Category']['Parent']['cat_title'] : '');
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$OrderDataNum,isset($val['Category']['cat_title']) ? $val['Category']['cat_title'] : '');
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$OrderDataNum,ucwords($val['Card']['c_title']));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$OrderDataNum,number_format($val['Card']['c_selling_price'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$OrderDataNum,number_format($val['Card']['c_buying_price'],2));
			//$objPHPExcel->getActiveSheet()->setCellValue('F'.$OrderDataNum,'<img src="'.WWW_ROOT.'img/card_icons/'.$val['Card']['c_image'].'"></img>');
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			if(isset($val['Card']['c_image']) && !empty($val['Card']['c_image']))
			{
				
			}
			else
			{
				$val['Card']['c_image'] = 'card_not_availabe.png';
			}
			$objDrawing->setPath('./img/card_icons/'.$val['Card']['c_image']);
			
			$objDrawing->setOffsetX(10);
			$objDrawing->setOffsetY(5);
			$objDrawing->setCoordinates('F'.$OrderDataNum);
			$objDrawing->setHeight(50);
            $objDrawing->setWidth(70);
			$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
			
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$OrderDataNum,$val['Card']['c_contact_number_1']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$OrderDataNum,$val['Card']['c_contact_number_2']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$OrderDataNum,$val['Card']['c_local_number_1']);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$OrderDataNum,$val['Card']['c_local_number_2']);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$OrderDataNum,$val['Card']['c_local_number_3']);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$OrderDataNum,$val['Card']['c_local_number_4']);
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$OrderDataNum,$val['Card']['c_local_number_5']);
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$OrderDataNum,$val['Card']['c_local_number_6']);
			$objPHPExcel->getActiveSheet()->setCellValue('V'.$OrderDataNum,$val['Card']['c_webpage']);
			$objPHPExcel->getActiveSheet()->setCellValue('W'.$OrderDataNum,$val['Card']['c_inventory_threshold']);
			$objPHPExcel->getActiveSheet()->setCellValue('X'.$OrderDataNum,$val['Card']['c_pin_per_card']);
			$OrderItemdataNum = $OrderDataNum+1;
			$FirstItemNumber  = $OrderItemdataNum;
			$objPHPExcel->getActiveSheet()->getStyle('A'.$OrderDataNum.':X'.$OrderDataNum)->getFont()->setBold(true)->setSize(11);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$OrderDataNum.':X'.$OrderDataNum)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$OrderDataNum.':X'.$OrderDataNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);	
			if(isset($val['CardsFreeText']) && !empty($val['CardsFreeText'])){
				$freetextnum = $OrderItemdataNum;
				foreach($val['CardsFreeText'] as $freetext_details){
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$freetextnum,$freetext_details['cf_alias']);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$freetextnum,$freetext_details['cf_freetext1']);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.$freetextnum,$freetext_details['cf_freetext2']);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$freetextnum,$freetext_details['cf_freetext3']);
					$objPHPExcel->getActiveSheet()->setCellValue('R'.$freetextnum,$freetext_details['cf_freetext4']);
					$objPHPExcel->getActiveSheet()->setCellValue('S'.$freetextnum,$freetext_details['cf_freetext5']);
					$objPHPExcel->getActiveSheet()->setCellValue('T'.$freetextnum,$freetext_details['cf_freetext6']);
					$freetextnum++;
				}
			}
			if(isset($val['PinsCard']) && !empty($val['PinsCard'])){
				 foreach($val['PinsCard'] as $pin_details){
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$OrderItemdataNum,'');
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$OrderItemdataNum,$pin_details['Pin']['p_serial']);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$OrderItemdataNum,$pin_details['Pin']['p_pin']);
					switch($pin_details['Pin']['p_status']){
						case '1' : $status='Unused';
											break;
						case '2' : $status='Sold';
											break;
						case '3' : $status='Parked';
											break;
						case '4' : $status='Rejected';
											break;
						case '5' : $status='Returned';
											break;
						default  : $status = '';
											break;
					}
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$OrderItemdataNum,$status);
					$objPHPExcel->getActiveSheet()->getStyle('B'.$OrderItemdataNum.':D'.$OrderItemdataNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$OrderItemdataNum++;
				}
				$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$Orderheading.':X'.($OrderItemdataNum-1))->applyFromArray($styleThinBlackBorderOutline);
			
			$AllBorder = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN	)));		
			$objPHPExcel->getActiveSheet()->getStyle('B'.$OrderDataNum.':E'.($OrderItemdataNum-1))->applyFromArray($AllBorder);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$OrderDataNum.':X'.($OrderItemdataNum-1))->applyFromArray($AllBorder);
			}
			$objPHPExcel->getActiveSheet()->getStyle('B'.$FirstItemNumber.':X'.$OrderItemdataNum)->getAlignment()->setWrapText(true);
			
			$objPHPExcel->getActiveSheet()->getStyle('B'.$FirstItemNumber.':X'.$OrderItemdataNum)->applyFromArray(			
			array('alignment' => array('vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP)
						//,'borders' => array('top'=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
						//,'left'     => array('style' => PHPExcel_Style_Border::BORDER_THIN))
						//,'fill' => array('type'=> PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,'rotation'   => 90
						//,'startcolor' => array('argb' => '0F0F0F')
						//,'endcolor'   => array('argb' => '0F0F0F'))
						)
			);	
		}
		$objPHPExcel->getActiveSheet()->getStyle('A8:X8')->applyFromArray(
			array('font'    => array('bold' => true,'color' => array('rgb' => '000000'))
						,'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
						,'borders' => array('top'=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
						,'left'     => array('style' => PHPExcel_Style_Border::BORDER_THIN)
						,'right'     => array('style' => PHPExcel_Style_Border::BORDER_THIN)
						,'bottom'     => array('style' => PHPExcel_Style_Border::BORDER_THIN)
						,'vertical' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
        ))
						,'fill' => array('type'=> PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,'rotation'   => 90
						,'startcolor' => array('argb' => 'FFFFFF')
						,'endcolor'   => array('argb' => 'FFFFFF'))
						)
			);
			
			$objPHPExcel->getActiveSheet()->getStyle('A9:X9')->applyFromArray(
			array('font'    => array('bold' => true,'color' => array('rgb' => '000000'))
						,'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
						,'borders' => array('top'=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
						,'left'     => array('style' => PHPExcel_Style_Border::BORDER_THIN)
						,'right'     => array('style' => PHPExcel_Style_Border::BORDER_THIN)
						,'bottom'     => array('style' => PHPExcel_Style_Border::BORDER_THIN)
						,'vertical' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
        ))
						,'fill' => array('type'=> PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,'rotation'   => 90
						,'startcolor' => array('argb' => 'FFFFFF')
						,'endcolor'   => array('argb' => 'FFFFFF'))
						)
			);
		
		// Company Logo
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Company Logo');
		$objDrawing->setDescription('Company Logo');
		$objDrawing->setPath('./img/logo.png');
		$objDrawing->setCoordinates('A1');
		$objDrawing->setHeight(80);
		//$objDrawing->setWidth(240);
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);		
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(13);
		$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(15);	
		//Merge Cells
		$objPHPExcel->getActiveSheet()->mergeCells('A1:D5');		// Header Logo
		$objPHPExcel->getActiveSheet()->mergeCells('A6:C6');		// Report Title
		
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	
		$objPHPExcel->getActiveSheet()->setTitle('List Of PINs');
		$objPHPExcel->setActiveSheetIndex(0);
		
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
		
		// set page margin
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');		
		$objWriter->save(WWW_ROOT.$file_name.'.xlsx');
		$fullPath = WWW_ROOT.$file_name.'.xlsx';
		if ($fd = fopen ($fullPath, "r")) {
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
		}
		fclose ($fd);
		unlink($fullPath);	
		exit();
		
	}
	public function admin_unmerge($id=NULL,$card_id=NULL)
	{
		if(isset($card_id) && !empty($card_id))
		{
			$this->loadModel('Card');
			$this->loadModel('PinsCard');
			$res = $this->Card->find('first',array(
					'conditions' => array('c_id'=>$card_id),
					'recursive'	 => -1
			));
			if(count($res)==0){
				$this->Session->setFlash(__('Unauthorized access'), 'default', array('class' => 'error'));
				$this->redirect(array('controller'=>'Categories','action' => 'admin_index'));
			}
			$this->set('title', "Merge Pins for ".$res['Card']['c_title']);
			$this->set('title_for_layout', "Unmerge Pins for  ".$res['Card']['c_title']);
			
			$card_cat = $res['Card']['c_cat_id'];
			$this->set('card_id',$card_id);
			
			$this->PinsCard->Behaviors->attach('Containable');	
			$this->PinsCard->contain(array('CardMergedFrom'));
			$all_cards = $this->PinsCard->find('all',array(
					'fields'		 => array('CardMergedFrom.c_title,PinsCard.pc_c_id,PinsCard.pc_merged_from_c_id,PinsCard.pc_merged_from_c_id' /*,'pin_card_count','pin_card_sold_count','pin_card_remain_count'*/),
					'conditions' => array('PinsCard.pc_c_id ='=>$card_id,'PinsCard.pc_merged_from_c_id IS NOT NULL'),
					'group'=>'PinsCard.pc_merged_from_c_id',
					'recursive'	 => -1
			));
			if(count($all_cards)==0){
				$this->Session->setFlash(__('Unmerge not posible.'), 'default', array('class' => 'success'));
				$this->redirect(array('controller'=>'Cards','action'=>'index',$card_id));
			}
			$unmerge = array();
			
			foreach($all_cards as $key => $value)
			{
				$unmerge[$value['CardMergedFrom']['c_id']] = $value['CardMergedFrom']['c_title'];
			}
			$this->set('all_cards',$unmerge);
			$this->set('id',$id);
			
			if($this->request->is('post') || $this->request->is('put')){
				$data = $this->request->data;
				if(isset($data) && !empty($data))
				{
					ini_set('max_execution_time', 3000);
					//prd($data['PinsCard']['unmerge_from_c_id']);
					foreach($data['PinsCard']['unmerge_from_c_id'] as $val)
					{
						$condition = array('PinsCard.pc_c_id'=>$card_id,'PinsCard.pc_merged_from_c_id'=>$val,'PinsCard.pc_status !='=>'2');
						$delete_pins = $this->PinsCard->find('count',array('conditions'=>$condition,'recursive'=>'-1'));
						
						$condition_unused = array('PinsCard.pc_c_id'=>$card_id,'OR' => array(
                                'PinsCard.pc_merged_from_c_id !=' => $val,
                                'PinsCard.pc_merged_from_c_id IS NULL',
															),'PinsCard.pc_status IN (1,3)');
						$unused_pins = $this->PinsCard->find('count',array('conditions'=>$condition_unused,'recursive'=>'-1'));
						
						
						$pincard = $this->Card->find('all',array('fields'=>'Card.pin_card_count,Card.pin_card_sold_count,Card.pin_card_remain_count,Card.sale_count','conditions'=>array('Card.c_id ='=>$card_id),'recursive'=>'-1'));
						//pr($delete_pins);
						//prd($pincard);
						if(!empty($pincard)){
							$this->Card->create();
							$this->Card->id=$card_id;
							$final_count  = $pincard[0]['Card']['pin_card_count']-$delete_pins;
							$save_arr = array('pin_card_count'=>$final_count,'pin_card_sold_count'=>$pincard[0]['Card']['pin_card_sold_count'],'pin_card_remain_count'=>$unused_pins,'sale_count'=>$pincard[0]['Card']['sale_count']);
						//	prd($save_arr);
							$this->Card->save($save_arr);
						}
						$this->PinsCard->deleteAll($condition,true);
						$this->PinsCard->updateAll(
								array('PinsCard.pc_status' => 1,'Pin.p_status' => 1),
								array('PinsCard.pc_c_id = ' => $card_id,'PinsCard.pc_status != ' => 2,'PinsCard.pc_status = '=>3)
						);
						
						$this->PinsCard->query("UPDATE ecom_pins_cards SET pc_status = '1' WHERE pc_id IN (
															SELECT pc_id
															FROM (
															SELECT pc_id AS pc_id
															FROM ecom_pins_cards
															WHERE pc_p_id IN (select pc_p_id From ecom_pins_cards WHERE `pc_c_id`=".$card_id." AND `pc_status` != 2)
															) AS tmptable)");
						$this->Session->setFlash(__('Unmerge Sucessfully done.'), 'default', array('class' => 'success'));
						$this->redirect(array('controller'=>'Cards','action'=>'index',$card_id));
					}
				}else{
					$this->redirect(array('controller'=>'Cards','action'=>'index',$card_id));
				}
			}
			
		}
	}
	public function admin_merge($id=NULL,$card_id=NULL)
	{
		if(isset($card_id) && !empty($card_id))
		{
			//check if this card exists or not
			$this->loadModel('Card');
			$res = $this->Card->find('first',array(
				'conditions' => array('c_id'=>$card_id),
				'recursive'	 => -1
			));
			if(count($res)==0)
			{
					$this->Session->setFlash(__('Unauthorized access'), 'default', array('class' => 'error'));
					$this->redirect(array('controller'=>'Categories','action' => 'admin_index'));
			}
			$this->set('title', "Merge Pins for ".$res['Card']['c_title']);
			$this->set('title_for_layout', "Merge Pins for ".$res['Card']['c_title']);
			
			//get all cards under the same sub category
			$card_cat = $res['Card']['c_cat_id'];
			
			$this->set('card_id',$card_id);
			$this->set('id',$id);
			
			$this->Card->virtualFields = array(
    'full_title' => "CONCAT(Card.c_title, '(total ',Card.pin_card_count,' pins, Remaining for merge ', Card.pin_card_remain_count, ' pins)')"
);
			
			$all_cards = $this->Card->find('list',array(
				'fields'		 => array('c_id','full_title' /*,'pin_card_count','pin_card_sold_count','pin_card_remain_count'*/),
				'conditions' => array('c_status'=>1,'c_id !='=>$card_id,'c_cat_id'=>$card_cat),
				'recursive'	 => -1
			));
			$this->loadModel('PinsCard');
			$final = array();
			//prd($all_cards);
			foreach($all_cards as $key => $value){
				$count = $this->PinsCard->find('count',array('conditions'=>'PinsCard.pc_status=1  AND pc_c_id='.$key));
				if($count!=0){
					$final[$key]= $value;					
				}
			}
			$this->set('all_cards',$final);
			
			if($this->request->is('post') || $this->request->is('put')){
				$data = $this->request->data;
				$this->loadModel('PinsCard');
				if(isset($data) && !empty($data))
				{
					//prd($data);
					//cards selected for merging
					$final_data = array();
					ini_set('max_execution_time', 3000);
					foreach($data['PinsCard']['merge_from_c_id'] as $val)
					{
						$selectedcard_pins = $this->PinsCard->find('all',array(
							'conditions' => array('PinsCard.pc_c_id'=>$val,'PinsCard.pc_status IN(1)'),
							'recursive'  => -1
						));				
						//prd($selectedcard_pins);		
						if(isset($selectedcard_pins) && !empty($selectedcard_pins))
						{
							$already_exists1 = $this->PinsCard->hasAny(array('PinsCard.pc_c_id'=>$card_id));
							if(isset($already_exists1) && !empty($already_exists1))
							{
								$this->PinsCard->bindModel(array('hasMany' => array('Pin')));
								$this->PinsCard->updateAll(
										array('PinsCard.pc_status' => 3,'Pin.p_status' => 3),
										array('PinsCard.pc_c_id = ' => $card_id,'PinsCard.pc_status != ' => 2,'PinsCard.pc_status = ' => 1)
								);
								$this->PinsCard->query("UPDATE ecom_pins_cards SET pc_status = '3' WHERE pc_id IN (
															SELECT pc_id
															FROM (
															SELECT pc_id AS pc_id
															FROM ecom_pins_cards
															WHERE pc_p_id IN (select pc_p_id From ecom_pins_cards WHERE `pc_c_id`=".$card_id." AND `pc_status` != 2)
															) AS tmptable)");
								
								
								
								//$pincard = $this->PinsCard->find('all',array('conditions'=>'PinsCard.pc_c_id'=>$card_id));
							}
							foreach($selectedcard_pins as $key=>$pc_data)
							{
								$already_exists = $this->PinsCard->hasAny(array('PinsCard.pc_p_id' => trim($selectedcard_pins[$key]['PinsCard']['pc_p_id']),'PinsCard.pc_c_id'=>$card_id));
								if(isset($already_exists) && !empty($already_exists))
								{
									unset($selectedcard_pins[$key]);			
								}
								else
								{
									unset($selectedcard_pins[$key]['PinsCard']['pc_id']);
									$selectedcard_pins[$key]['PinsCard']['pc_is_merged'] = 1;
									$selectedcard_pins[$key]['PinsCard']['pc_merged_from_c_id'] = $selectedcard_pins[$key]['PinsCard']['pc_c_id'];
									$selectedcard_pins[$key]['PinsCard']['pc_c_id'] = $card_id;
									
								}
							}
						}
					}
					//prd($selectedcard_pins);
					if(count($selectedcard_pins)){
						if($this->PinsCard->saveMany($selectedcard_pins))
						{
							$this->Card->create();
							$this->Card->id=$card_id;
							$save_arr = array('pin_card_count'=>$res['Card']['pin_card_count'] + count($selectedcard_pins),'pin_card_sold_count'=>$res['Card']['pin_card_sold_count'],'pin_card_remain_count'=>$res['Card']['pin_card_remain_count'] + count($selectedcard_pins),'sale_count'=>$res['Card']['sale_count']);
							
							$this->Card->save($save_arr);
							
							$this->Session->setFlash(__('Pins merged successfully.'), 'default', array('class' => 'success'));
							$this->redirect(array('controller'=>'Cards','action'=>'index'));
							//$this->redirect(array('action'=>'index',$card_cat));
						}
						else
						{
							$this->Session->setFlash('Pins could not be merged. Please, try again', 'default', array('class' => 'error'));
						}
					}
					else
					{
						//posted cards pins are already available for this card
						$this->Session->setFlash(__('Pins already available for '.$res['Card']['c_title'].' card.'), 'default', array('class' => 'success'));
						$this->redirect(array('controller'=>'Cards','action'=>'index'));
						//$this->redirect(array('controller'=>'Cards','action'=>'index',$card_cat));
					}	
				}
				else
				{
					//no thing posted
	    			$this->redirect(array('controller'=>'Cards','action'=>'index'));
				}
			}
		}
	}
	public function admin_unmerge_list($id=NULL,$card_id=NULL, $from_card = NULL){
		$this->set("title",'Manage Unmerged Pins');
		$back_cat = '';
		
		$url = Router::url(array('controller' => 'cards', 'action' => 'index',$id));
		if($from_card == 1)
		{
			$url = Router::url(array('controller' => 'cards', 'action' => 'index'));
		}
		$this->set('url',$url);
		$this->set('id',$id);
		if(isset($card_id) && !empty($card_id)){
			$this->loadModel('Card');
			$res = $this->Card->find('first',array(
					'conditions' => array('c_id'=>$card_id),
					'recursive'	 => -1
			));
			
			if(count($res)==0){
				$this->Session->setFlash(__('Unauthorized access.'), 'default', array('class' => 'error'));
				$this->redirect(array('action' => 'index','admin'=>true));
			}
			$this->set('title', "Manage Merged Pins for ".$res['Card']['c_title']);
			$back_cat = $res['Card']['c_cat_id'];
		}else{
			$this->Session->setFlash(__('Unauthorized access.'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index','admin'=>true));
		}
		$this->set('card_id',$card_id);
		$this->set('back_cat',$back_cat);
	}
	
	public function admin_merge_list($id=NULL,$card_id=NULL, $from_card = NULL)
	{
		$this->set("title",'Manage Merged Pins');
		$back_cat = '';
		
		$url = Router::url(array('controller' => 'cards', 'action' => 'index',$id));
		if($from_card == 1)
		{
			$url = Router::url(array('controller' => 'cards', 'action' => 'index'));
		}
		$this->set('url',$url);
		$this->set('id',$id);
		if(isset($card_id) && !empty($card_id)){
			$this->loadModel('Card');
			$res = $this->Card->find('first',array(
				'conditions' => array('c_id'=>$card_id),
				'recursive'	 => -1
			));
			
			if(count($res)==0){
					$this->Session->setFlash(__('Unauthorized access.'), 'default', array('class' => 'error'));
					$this->redirect(array('action' => 'index','admin'=>true));	
			}
			$this->set('title', "Manage Merged Pins for ".$res['Card']['c_title']);
			$back_cat = $res['Card']['c_cat_id'];
		}else{
			$this->Session->setFlash(__('Unauthorized access.'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index','admin'=>true));	
		}
		$this->set('card_id',$card_id);
		$this->set('back_cat',$back_cat);
		
	}
	public function admin_merge_json($card_id=NULL)
	{
		
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
				if($each_filter['field'] == 'p_serial'){
					$conditions['Pin.p_serial LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}
				if($each_filter['field'] == 'p_pin'){
					$conditions['Pin.p_pin LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}
				if($each_filter['field'] == 'c_title'){
					$conditions['CardMergedFrom.c_title LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}
					
			}
		}
			if(!empty($card_id))
			{
				//show pincs of selected card only
				$conditions['PinsCard.pc_c_id ='] = $card_id;
				$conditions['PinsCard.pc_is_merged ='] = 1;
			}
			if(!$limit){$limit = 10;}
			if(!$page){$page = 1;}
			if(!$sidx){$sidx = 1;}
			$this->loadModel('PinsCard');
			$count  = $this->PinsCard->find('count',array('conditions'=>$conditions));
			if ($count > 0) { 
				$total_pages = ceil($count/$limit); 
			} else { 
				$total_pages = 1; 
			}
			if ($page > $total_pages) $page=$total_pages; 		
			$start = $limit*$page - $limit;	
			
			$this->PinsCard->Behaviors->attach('Containable');
			$resultSet = $this->PinsCard->find('all', 
						array('conditions'=>$conditions,
									'order'=>(array($sidx.' '.$sort)),
									'limit'=>$limit,
									'offset'=>$start,
									'contain'=>array(
													'Pin' => array(),
													'CardMergedFrom' => array(
															'fields' => array('c_title'),
															'Category' => array(
																'fields' => array('cat_title')
															)
													),
													/*'Card'=>array(
															'fields' => array('c_title'),
															'Category' => array(
																'fields' => array('cat_title')
															)
													)*/
													),								
									));
			//prd($resultSet);
			$this->PinsCard->Behaviors->detach('Containable');
			
			$response = new stdClass();
			$response->page 	= $page;
			$response->total 	= $total_pages;
			$response->records 	= $count;
			
			foreach($resultSet as $key=>$val){
				
				$content = Router::url(array('controller'=>'Pins','action'=>'edit','admin'=>true,$val['Pin']['p_id']));
				
				$edit = '<a title="Edit Pin" href="'.$content.'"><img src="'.$this->webroot.'img/edit.png" alt="Edit" title="Edit" border="0" /></a>';
				switch($val['Pin']['p_status']){
					case 1 : $status = 'Unused';
										break;
					case 2 : $status = 'Sold';
										break;		
					case 3 : $status = 'Parked';
										break;
					case 4 : $status = 'Rejected';
										break;						
					case 5 : $status = 'Returned';
										break;																																		
				}
				/*$cards = '';
				if(isset($val['PinsCard']) && !empty($val['PinsCard'])){
					if(isset($val['Card']['c_title']) && !empty($val['Card']['c_title'])){
						$cards = $val['Card']['c_title'].' ('.$val['Card']['Category']['cat_title'].')';	
					}
				}*/
				$merged_from_cards = '';
				if(isset($val['PinsCard']) && !empty($val['PinsCard'])){
					if(isset($val['CardMergedFrom']['c_title']) && !empty($val['CardMergedFrom']['c_title'])){
						$merged_from_cards = $val['CardMergedFrom']['c_title'].' ('.$val['CardMergedFrom']['Category']['cat_title'].')';	
					}
				}
				$response->rows[$key]['id']   = $val['Pin']['p_id'];			
				$response->rows[$key]['cell'] = array($val['Pin']['p_serial'],$val['Pin']['p_pin'],/*$cards,*/ $merged_from_cards,$status);
			}

			echo json_encode($response);
		}else{
			$this->redirect(array('action' => 'index','controller'=>'Categories'));
		}
	}

	public function admin_get_card()
	{
		$this->loadModel('Card');
		if($this->request->is('ajax'))
		{
			$data = $this->request->data;
			$sub_cat_id 	= $data['id'];
			$card_names = $this->Card->find('list',array(
					'fields'	=> array('c_id','c_title'),
					'conditions' => array('c_status'=>1,'c_cat_id'=>$sub_cat_id)
			));
			
			foreach($card_names as $k => $v)
			$card_names[$k] = ucwords(strtolower($v));
			
			echo json_encode($card_names);
			exit;
		}
	}

	public function admin_hello($card_id = 0){
		$this->loadModel('PinsCard');
		echo "Dasd";exit;
		$this->loadModel('Card');
		$this->loadModel('Pin');
		$this->loadModel('Card');
		$this->loadModel('Pin');

		if($card_id!=0){
			$cardRes = $this->Card->find('all',array(
					'conditions' => array('Card.c_status'=>1,'Card.c_id'=>$card_id),
					'fields' => array('*'),
					//'recursive'	 => -1,
					'order'=>'c_title'
				));
		}else{
			$cardRes = $this->Card->find('all',array(
					'conditions' => array('Card.c_status'=>1),
					'fields' => array('*'),
					//'recursive'	 => -1,
					'order'=>'c_title'
				));
		}
	//	prd($cardRes[0]['PinsCard']);
		foreach ($cardRes[0]['PinsCard'] as $key => $value) {
			$pinRes = $this->Pin->find('all',array(
					'conditions' => array('Pin.p_id'=>$value['pc_p_id']),
					'fields' => array('*'),
					//'recursive'	 => -1,
					//'order'=>'c_title'
				));
			//$new_arr['PinsCard']['serial_num'] = $value['pc_p_id'];
			$cardRes['Pin_detail'] = $pinRes[]['Pin'];
		}
              prd($cardRes);
	}


}