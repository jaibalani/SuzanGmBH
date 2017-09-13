<?php
App::uses('AppController', 'Controller');
include(APP.'Vendor/PHPExcel/Classes/PHPExcel.php');
include(APP.'Vendor/PHPExcel/Classes/PHPExcel/IOFactory.php');

class PinsController extends AppController 
{
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('');
	}

    public function admin_trim(){
           
           $pin = $this->Pin->find('all');
           foreach ($pin as $key => $value) {
           	      
           	      $pin[$key]['Pin']['p_serial'] = trim($pin[$key]['Pin']['p_serial']);
				  $pin[$key]['Pin']['p_pin'] = trim($pin[$key]['Pin']['p_pin']);

				  $this->Pin->save($pin[$key]);

           }

    }
	public function admin_index($card_id=NULL, $from_card = NULL){
        
        $this->admin_redirect_to_dashboard_distributor();
		$this->set("title",'Manage card PINs');
		$back_cat = '';
		$c_id = '';
		$pc_status = '';
		
		$this->Session->write('card_id_pin',$card_id);
		$this->Session->write('from_card_id_pin',$from_card);
    
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
		$this->set('pc_status',$pc_status);
		$this->set('sub_cat_id',$back_cat);
		$this->set('cat_id',$c_id);
		//echo "main=".$c_id." subcat=".$back_cat;exit;
		if($this->request->is('Post')) {

			$data = $this->request->data ;
			
			$url = '';
			if(isset($data['Pin']['cat_id']) && !empty($data['Pin']['cat_id']) ) {
				$subCatConditions['Category.cat_parent_id'] = $data['Pin']['cat_id'];
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
		$this->loadModel('PinsCard');
        
        if(!empty($card_id))
        {
        	$card_data = $this->Card->findByCId($card_id);
        	$main_category_select_card = $card_data['Category']['cat_parent_id'];
        	$sub_category_select_card = $card_data['Category']['cat_id']; 
        }
        
        
        $res = $this->Category->find('list',array(
			'conditions' => array('cat_status'=>1,'cat_parent_id'=>null),
			'fields' => array('cat_id','cat_title'),
			'order'=>'cat_title asc',
			'recursive'	 => -1
		));

    	foreach($res as $k => $v)
		$res[$k] = ucwords(strtolower($v));

		$named = $this->request->named;

		$subCatConditions = array();
		$subCatConditions['Category.cat_status'] = 1;
		$subCatConditions['Category.cat_parent_id <>'] = null;

		if(isset($named['cat_id']) && !empty($named['cat_id'])) {
			$this->set('cat_id',$named['cat_id']);	
			$subCatConditions['Category.cat_parent_id'] = $named['cat_id'];
		}

		if(isset($named['cat_id']) && !empty($named['cat_id'])) {
			$this->set('cat_id',$named['cat_id']);	
			$subCatConditions['Category.cat_parent_id'] = $named['cat_id'];
		}

		if(isset($main_category_select_card))
		{
			$subCatConditions['Category.cat_parent_id'] = $main_category_select_card;
		}
        

		/*$resSubCat = $this->Category->find('list',array(
						'conditions' => $subCatConditions,
						'fields' => array('cat_id','cat_title'),
						'recursive'	 => -1,
						'order'=>'cat_title asc',
					));*/
		
		
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

        $cardCondition = array();
		$cardCondition['c_status'] = 1 ;
		if(isset($sub_category_select_card))
        $cardCondition['c_cat_id'] = $sub_category_select_card ;
        else 
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
		
		$file_list_conditions = array();
		$file_list_conditions['pc_c_id'] = array_keys($cardRes);

		$fileList = $this->PinsCard->find('list',array(
			'conditions'=>$file_list_conditions,
			'fields' => array('pc_id','pin_file'),
			'order'=>'pin_file asc',
			'group'=>'pin_file',
			'recursive'	 => -1
		));


		if(isset($named['card_id']) && !empty($named['card_id']) && in_array($named['card_id'], array_keys($cardRes)) ) {
			$this->set('card_id',$named['card_id']);	
		}
		
		$this->set('fileList',$fileList);
		$this->set('catList',$res);
		$this->set('subCatList',$resSubCat);
		$this->set('cardList',$cardRes);

	}
	public function admin_json($card_id=NULL){
		
		ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');

		if($this->request->is('ajax')){
			$this->autoRender = false;

			$this->loadModel('Card');
			$this->loadModel('Category');
			$this->loadModel('PinsCard');

			$params = $this->request->query;

			$page 	= $params['page'];
			$limit  = $params['rows'];
			$sidx 	= $params['sidx'];
			$sort 	= $params['sord'];

	        
			$card_id = '';
			$cat_id = '';
			$sub_cat_id = '';
			$pc_status = '';
            $conditions = array();

            $this->PinsCard->virtualFields = array(
                    		'created_pin'=>'DATE_FORMAT(PinsCard.pin_created,"%d.%m.%Y %H:%i:%s")',
						 );

      		if(isset($this->request->query['filters'])){
				$filters = json_decode($this->request->query['filters'], true);
				foreach($filters['rules'] as $each_filter){
					if($each_filter['field'] == 'Pin.p_serial'){
						if(trim($each_filter['data']) !='')
						$conditions['Pin.p_serial LIKE'] = '%'.Sanitize::escape($each_filter['data']). '%';
					}
					if($each_filter['field'] == 'Pin.p_pin'){
						if(trim($each_filter['data']) !='')
						$conditions['Pin.p_pin LIKE'] = '%'.Sanitize::escape($each_filter['data']). '%';
					}
					if($each_filter['field'] == 'pin_file'){
						if(trim($each_filter['data']) !='')
						$conditions['PinsCard.pin_file LIKE'] = '%'.Sanitize::escape($each_filter['data']). '%';
					}

                    if($each_filter['field'] == 'pin_created'){

                    	if(trim($each_filter['data']) !='')
                    	$conditions['PinsCard.created_pin LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
					}

					if($each_filter['field'] == 'c_title'){
						if(trim($each_filter['data']) !='')
						$conditions['Card.c_title LIKE'] = '%'.Sanitize::escape($each_filter['data']). '%';
					}
					if($each_filter['field'] == 'pc_status'){
						if($each_filter['data'] != 0)
						$conditions['PinsCard.pc_status'] = Sanitize::clean($each_filter['data']);
					}
				}
			}
           
			$named = $this->request->named;

			//pr($named);
			if(isset($named['card_id']) && !empty($named['card_id'])) {
				$conditions['PinsCard.pc_c_id'] = $named['card_id'];
				$card_id = $named['card_id'];
			}
			else 
			{

				if(isset($named['sub_cat_id']) && !empty($named['sub_cat_id'])) 
				{
					$cardCondition = array();
					$cardCondition['c_status'] = 1 ;
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
				       
				       if(isset($named['cat_id']) && !empty($named['cat_id'])) 
				       {
							$subCatConditions = array();
							$subCatConditions['cat_status'] = 1;
							$subCatConditions['cat_parent_id'] = $named['cat_id'];
	                        $cat_id = $named['cat_id'];
							
							$resSubCat = $this->Category->find('list',array(
								'conditions' => $subCatConditions,
								'fields' => array('cat_id','cat_title'),
								'recursive'	 => -1
							));

							$cardCondition = array();
							$cardCondition['c_status'] = 1;
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

			if(isset($named['pc_status']) && !empty($named['pc_status'])){
					$conditions['Pin.p_status'] = $named['pc_status'];
					$pc_status = $named['pc_status'];
			}


			if(isset($named['url_start_date']) && !empty($named['url_start_date'])){
					$conditions['DATE(PinsCard.pin_created) >='] = $named['url_start_date'];
					$conditions['DATE(PinsCard.pin_created) <='] = $named['url_end_date'];
			}

            $this->set('card_id',$card_id);
            $this->set('pc_status',$pc_status);
			$this->set('cat_id',$cat_id);
			$this->set('sub_cat_id',$sub_cat_id);
            						
			if(!$limit){$limit = 10;}
			if(!$page){$page = 1;}
			if(!$sidx){$sidx = 1;}

			$this->loadModel('PinsCard');
			//pr($conditions);

			if(!isset($cardCondition['c_cat_id']) && !isset($conditions['PinsCard.pc_c_id']))
			{   
				$subCatConditions = array();
        		$subCatConditions['Category.cat_status'] = 1;
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
		            $cardCondition = array();
					$cardCondition['c_status'] = 1 ;
					$cardCondition['c_cat_id'] = array_keys($resSubCat);
                    $cardRes = $this->Card->find('list',array(
						'conditions' => $cardCondition,
						'fields' => array('c_id','c_title'),
						'recursive'	 => -1
					));
					$conditions['PinsCard.pc_c_id'] = array_keys($cardRes);
    	   }
           
           /*File Select Filter*/
           if(isset($named['file_name']) && !empty($named['file_name'])) 
           {
           	  $conditions['PinsCard.pin_file'] = $named['file_name'];
           }
           /*File Select Filter End*/
		   
		   $count  = $this->PinsCard->find('count',array('conditions'=>$conditions));
			
			if ($count > 0) { 
				$total_pages = ceil($count/$limit); 
			} else { 
				$total_pages = 1; 
			}
			if ($page > $total_pages) $page=$total_pages; 		
			$start = $limit*$page - $limit;	
			//echo $sidx;exit;
			
			$sidx_array = array('Pin.p_serial','Pin.p_pin','pc_status');
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
		    // echo $sidx.' '.$sort;exit;
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
                                    $pin_created = date('Y-m-d H:i:s',strtotime($val['PinsCard']['pin_created']));
                                //$pin_created = date('d.m.Y h:m:s',strtotime($val['PinsCard']['pin_created']));

                                }
				else
                                {
                                    $pin_created = '';
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
        
        //$this->Session->delete('existing_pin');

		$this->admin_redirect_to_dashboard_distributor();
		ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');
          
		$this->loadModel('Category');
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
        
        if($this->Session->check('existing_pin'))
        {
            $this->admin_existing_pins_file_download();
            $this->set('download_file',1);
        }
       
        if($this->request->is('post') || $this->request->is('put'))
		{
			    //prd($this->request->data);
				//prd($_SESSION);
				ini_set('max_execution_time', 3000);
				include(APP.'Vendor/Excel/reader.php');
				$file_name	= $_SESSION['upload_pin_excel'];		
				
				//include(APP . 'Vendor/PHPExcel/Classes/PHPExcel.php');
				//include(APP . 'Vendor/PHPExcel/Classes/PHPExcel/IOFactory.php');
				
				//unset($_SESSION['upload_pin_excel']);
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
							if($col == 2)
							{
								$value= PHPExcel_Shared_Date::ExcelToPHPObject($objWorksheet->getCellByColumnAndRow($col, $row)->getValue())->format('m/d/Y');;  
							}
							else
							{
								$value=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();  
							}
							
							$arraydata[$row-1][$col + 1]=$value; 
						}  

					}
					
					$total_pins_upload = 0;

                    $existing_pin = array(); 
					$this->loadModel('PinsCard');
					$save_pins = array();
					foreach($arraydata as $key => $val)
					{
						//if($key <= 5){//AVOID FIRST LINE IN EXCEL FILE
						$x = 0;
						$this->loadModel('Pin');
						$new_pin = array('1');
						$outer = 0;
						$pin_flag = 0;
						$pin = array();
						$pin['PinsCard'] = array();
						if((isset($val[2]) && !empty($val[2])) && (isset($val[1]) && !empty($val[1])))
						{
							$pin['Pin']['p_serial'] 	   =  trim($val[1]) ;
							$pin['Pin']['p_pin'] 	   	   =  trim($val[2]);
							if(count($val)==3){
								if (strpos($val[2],'.') !== false) {
									$pin['Pin']['p_pin'] = trim($val[3]);
								}
						}
						
						if(isset($this->request->data['PinsCard']['pc_c_id_hidden']) 
							&& !empty($this->request->data['PinsCard']['pc_c_id_hidden']))
						{
							$new_array_card_id = array($this->request->data['PinsCard']['pc_c_id_hidden']);
							foreach($new_array_card_id as $card_id)
							{
								$already_exists_pin = $this->Pin->find('first'
									,array('fields'=>array('p_id','p_status'),
									'conditions'=>array('Pin.p_pin' => 
										trim($pin['Pin']['p_pin'])),
									'recursive'=>0));
								 if(isset($already_exists_pin) && !empty($already_exists_pin))
								{
									//pin already exists
								    /*$already_exists = $this->PinsCard->hasAny(array('PinsCard.pc_p_id' => trim($already_exists_pin['Pin']['p_id']),
										'PinsCard.pc_c_id'=>$card_id));*/
								    $already_exists = $this->PinsCard->hasAny(array('PinsCard.pc_p_id' => trim($already_exists_pin['Pin']['p_id']),
										));

									if(isset($already_exists) && !empty($already_exists))
									{
										//same pin and same card
										//$pin['PinsCard'][$x]['pc_c_id'] = '';
										$pin_flag = 1;
										$pin['Pin']['p_id'] = $already_exists_pin['Pin']['p_id'];
										$existing_pin[] = $pin['Pin'];
										unset($pin['PinsCard']);
									}
									else
									{
										//new pin
                                        $pin['PinsCard'][$x]['pin_file'] = $file_name;
                                        $pin['PinsCard'][$x]['pin_created'] = date('Y-m-d H:i:s');
										$pin['PinsCard'][$x]['pc_c_id'] = $card_id;
										$pin['PinsCard'][$x]['pc_status']= $already_exists_pin['Pin']['p_status'];
										$pin['PinsCard'][$x]['pc_p_id'] = $already_exists_pin['Pin']['p_id'];
										$pin['Pin']['p_id'] = $already_exists_pin['Pin']['p_id'];
										$x++;
										$total_pins_upload++;
									}
								}
								else
								{
									//new pin
									$pin['PinsCard'][$x]['pc_c_id'] = $card_id;
									$pin['PinsCard'][$x]['pc_status'] = 1;
                                    $pin['PinsCard'][$x]['pin_file'] = $file_name;
                                    $pin['PinsCard'][$x]['pin_created'] = date('Y-m-d H:i:s');
                                    $total_pins_upload++;
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
									if(isset($already_exists) && !empty($already_exists))
									{
										//same pin available for all card
										//$pin['PinsCard'][$x]['pc_c_id'] = '';
										$pin_flag = 1;
										$pin['Pin']['p_id'] = $already_exists_pin['Pin']['p_id'];
										$existing_pin[] =$pin['Pin'];
										unset($pin['PinsCard']);
									}
									else
									{
										//new pin for pc_c_id NULL but pin already exists
										$pin['PinsCard'][$x]['pc_c_id'] = NULL;
										$pin['PinsCard'][$x]['pc_status']= $already_exists_pin['Pin']['p_status'];
										$pin['PinsCard'][$x]['pc_p_id'] = $already_exists_pin['Pin']['p_id'];
										$pin['Pin']['p_id'] = $already_exists_pin['Pin']['p_id'];
										$pin['PinsCard'][$x]['pin_file'] = $file_name;
                                        $pin['PinsCard'][$x]['pin_created'] = date('Y-m-d H:i:s');
                                        $total_pins_upload++;
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
						if(isset($pin['PinsCard']) && !empty($pin['PinsCard']))
						{
							$save_pins[] = $pin;
							$outer++;
						}
					}
				}
                
         	    if(empty($existing_pin))
			    {
			    	foreach($save_pins as $pin_save)
			    	{
                       $this->Pin->create();
                       $res_add_pin = $this->Pin->saveAssociated($pin_save);
			    	}
			    }
			    else
			    {
			    	$this->Session->write('existing_pin',$existing_pin);
			    	$this->Session->write('existing_pin_card',$card_id);
			    	@unlink(WWW_ROOT.'img/admin_uploads/pins_uploaded/'.$newname);
					@unlink(WWW_ROOT.'img/admin_uploads/pins_uploaded/'.$this->request->data['Pin']['excel']);
			        $this->redirect(array('controller'=>'Pins','action'=>'add',$card_id));
			    } 
					//echo $outer;exit;
					if($outer || $total_pins_upload)
                    {
						//prd($pin);
						/*$this->Pin->create();
						$this->Pin->saveMany($pin, array('deep' => true));*/
						@unlink(WWW_ROOT.'img/admin_uploads/pins_uploaded/'.$newname);
						$this->loadModel('Card');
						$this->Session->setFlash(__($total_pins_upload. ' pins have been imported successfully from the file  <b>'.$file_name.'</b>'), 'default', array('class' => 'success'));
						$this->redirect(array('controller'=>'Cards','action'=>'index'));
					}
					else if($pin_flag)
					{
						@unlink(WWW_ROOT.'img/admin_uploads/pins_uploaded/'.$newname);
						@unlink(WWW_ROOT.'img/admin_uploads/pins_uploaded/'.$this->request->data['Pin']['excel']);
						$this->request->data['Pin']['excel'] = '';
						$this->Session->setFlash('Pins already exists!! Try with new pins.', 'default', array('class' => 'error'));
     				    $this->redirect(array('controller'=>'Pins','action'=>'add',$card_id));
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
		    $this->admin_redirect_to_dashboard_distributor();
			
		    if(isset($id) && !empty($id)){
			$this->set('title_for_layout','Edit Pins');
			$this->loadModel('Card');
			$this->loadModel('PinsCard');
			$this->loadModel('Category');
			
			$get_sub_category = $this->PinsCard->findByPcPId($id);

			$selected_sub_cat = array();
			
			if($get_sub_category)
			{
				$card_sub_pategory = $get_sub_category['Card']['c_cat_id'];
				$selected_sub_cat[] = $card_sub_pategory;

				/*$get_parent =   $this->Category->findByCatId($card_sub_pategory)	;	
				if($get_parent)
				{
					$parent_id = $get_parent['Category']['cat_parent_id'];		
					$get_all_sub_cat = $this->Category->find('list',array('conditions'=>array('cat_parent_id'=>$parent_id),'fields'=>'cat_id,cat_title'));
					foreach($get_all_sub_cat as $k =>$v)
					{
						$selected_sub_cat[] =$k;
					}
				}*/
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
			if(isset($resultSet) && !empty($resultSet))
			{
				//pin exists
				if(isset($resultSet['PinsCard']) && !empty($resultSet['PinsCard']))
				{
					foreach($resultSet['PinsCard'] as $val)
					{
						$selected_cards[] = $val['pc_c_id']; //previously sleected cards
						$previous_cards[] = $val['pc_c_id']; //previously sleected cards
						$cards_id_array[$val['pc_c_id']] = $val['pc_id']; // pc_id of those cards
					}
				}
			}
			else
			{
				//no such pin exists
				$this->Session->setFlash('Unauthorized access', 'default', array('class' => 'error'));
				$this->redirect(array('action'=>'index'));
			}
			
			
			$this->set('selected',$selected_cards);
			if ($this->request->is('post') || $this->request->is('put'))
			{
				$data = $this->request->data;

				$final_data = array();
				$card_array = array();
				$count = 0;
				
				if(isset($data['Pin']) && !empty($data['Pin']))
				{
					$this->Pin->create();
					$pin_data['Pin']['p_id'] = $resultSet['Pin']['p_id'];
					$pin_data['Pin']['p_status'] = $data['Pin']['p_status'];
					$this->Pin->save($pin_data['Pin']);
				}
				
				if(isset($data['PinsCard']) && !empty($data['PinsCard']))
				{
					//if cards are selected
					if(isset($selected_cards) && !empty($selected_cards))
					{
						foreach($selected_cards as $pc_id=>$val)
						{
							if (!in_array($val, $data['PinsCard']['pc_c_id'])) 
							{
								//it was previously selected but now it is not selected, removed from selection
								$this->PinsCard->delete(array('PinsCard.pc_id'=>$cards_id_array[$val]));
								unset($previous_cards[$pc_id]);
								$previous_cards = array_values($previous_cards);
							}
							else
							{
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
					foreach($data['PinsCard']['pc_c_id'] as $val)
					{
						if (!in_array($val, $card_array)) 
						{
							//new selected card entry not yet appenede in the array
							if(isset($final_data['PinsCard']) && !empty($final_data['PinsCard']))
							{
								$count =  count($final_data['PinsCard']);
							}
							$final_data['PinsCard'][$count]['pc_c_id'] = $val;
							$final_data['PinsCard'][$count]['pc_p_id'] = $resultSet['Pin']['p_id'];
							$final_data['PinsCard'][$count]['pc_status'] = $data['Pin']['p_status'];
						}
					}
			}
			else
			{
					//no card selected now for this pin
					//check if previously any card were selected for this pin card
					if(isset($selected_cards) && !empty($selected_cards))
					{
						foreach($selected_cards as $ctr=>$pc_id_index)
						{
							if(isset($cards_id_array[$pc_id_index]) && !empty($cards_id_array[$pc_id_index]))
							{
								if($ctr==(count($selected_cards) - 1))
								{
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
															
								}
								else
								{
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
		$this->admin_redirect_to_dashboard_distributor();
		$this->autoRender = false;
		$this->loadModel('Card');
                
        ini_set('memory_limit','1024M');
		$conditions = array();
		
		if(isset($c_id) && !empty($c_id)){
			$conditions = array('c_id'=>$c_id);	
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
				
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$new_line,isset($val['Category']['cat_title']) ? $val['Category']['cat_title'] : '');

				$objPHPExcel->getActiveSheet()->setCellValue('C'.$new_line,ucwords($val['Card']['c_title']));
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$new_line,number_format($val['Card']['c_selling_price'],2));


				$objPHPExcel->getActiveSheet()->setCellValue('E'.$new_line,number_format($val['Card']['c_buying_price'],2));

				$objPHPExcel->getActiveSheet()->getStyle('A'.$new_line.':E'.$new_line)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				
			}else{
				$Orderheading = $OrderItemdataNum+2;
				$OrderDataNum = $Orderheading;
			}
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$OrderDataNum,isset($val['Category']['Parent']['cat_title']) ? $val['Category']['Parent']['cat_title'] : '');
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$OrderDataNum,"Pin Serial#");
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$OrderDataNum,"Pin #");
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$OrderDataNum,"Status");
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$OrderDataNum,"Alias");
			//$objPHPExcel->getActiveSheet()->setCellValue('F'.$OrderDataNum,'<img src="'.WWW_ROOT.'img/card_icons/'.$val['Card']['c_image'].'"></img>');
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			if(isset($val['Card']['c_image']) && !empty($val['Card']['c_image']))
			{
				
			}
			else
			{
				$val['Card']['c_image'] = 'card_not_availabe.png';
			}

			if(!file_exists(WWW_ROOT.'./img/card_icons/'.$val['Card']['c_image']))
		    $objDrawing->setPath('./img/card_icons/card_not_availabe.png');
		    else		
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

			/*$objPHPExcel->getActiveSheet()->getStyle('H'.$OrderDataNum.":".'X'.$OrderDataNum)->getNumberFormat()->setFormatCode('0');*/

		    $objPHPExcel->getActiveSheet()->getStyle('H'.$OrderDataNum.":".'X'.$OrderDataNum)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
            
            /*bjPHPExcel->getActiveSheet()
									    ->getStyle('H'.$OrderDataNum.":".'X'.$OrderDataNum)
									    ->getNumberFormat()
									    ->setFormatCode(
									        PHPExcel_Style_NumberFormat::FORMAT_GENERAL
									    );*/

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
		$file_name = preg_replace('/[^a-zA-Z0-9_]/', '_', $file_name);
		$objWriter->save(WWW_ROOT.$file_name.'.xls');
		$fullPath = WWW_ROOT.$file_name.'.xls';
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
	public function admin_unmerge($id=NULL,$card_id=NULL)
	{
		$this->admin_redirect_to_dashboard_distributor();
		if(isset($card_id) && !empty($card_id))
		{
			$card_session_array = array();
			$card_session_array[] = $card_id;
			
			$this->Session->write('card_session_array',$card_session_array);


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
                        $this->set('card_id',$id);
			
			if($this->request->is('post') || $this->request->is('put')){
				$data = $this->request->data;
				$card_session_array = 	$this->Session->read('card_session_array');
				if(isset($data) && !empty($data))
				{
					ini_set('max_execution_time', 3000);
					//prd($data['PinsCard']['unmerge_from_c_id']);
					$card_session_array[]  = $card_id;
					foreach($data['PinsCard']['unmerge_from_c_id'] as $val)
					{
						$card_session_array[]  = $val;
						$condition = array('PinsCard.pc_c_id'=>$card_id,'PinsCard.pc_merged_from_c_id'=>$val,'PinsCard.pc_status <>'=>'2');
						$delete_pins = $this->PinsCard->find('count',array('conditions'=>$condition,'recursive'=>'-1'));
						
						$condition_unused = array('PinsCard.pc_c_id'=>$card_id,'OR' => array(
                                'PinsCard.pc_merged_from_c_id <>' => $val,
                                'PinsCard.pc_merged_from_c_id IS NULL',
															),'PinsCard.pc_status IN (1,3)');
						$unused_pins = $this->PinsCard->find('count',array('conditions'=>$condition_unused,'recursive'=>'-1'));
						
						
						$pincard = $this->Card->find('all',array('fields'=>'Card.pin_card_count,Card.pin_card_sold_count,Card.pin_card_remain_count,Card.sale_count','conditions'=>array('Card.c_id ='=>$card_id),'recursive'=>'-1'));
						
						if(!empty($pincard))
						{
							$this->Card->create();
							$this->Card->id=$card_id;
							$final_count  = $pincard[0]['Card']['pin_card_count']-$delete_pins;
							//$save_arr = array('pin_card_count'=>$final_count,'pin_card_sold_count'=>$pincard[0]['Card']['pin_card_sold_count'],'pin_card_remain_count'=>$unused_pins,'sale_count'=>$pincard[0]['Card']['sale_count']);
						    //$this->Card->save($save_arr);
					        $this->admin_update_cards_pins_details($card_id);
						}
                        
						$this->PinsCard->deleteAll($condition,true);
						$this->PinsCard->updateAll(
								array('PinsCard.pc_status' => 1,'Pin.p_status' => 1),
								array('PinsCard.pc_c_id' => $card_id,'PinsCard.pc_status <>' => 2,'PinsCard.pc_status'=>3)
						);
						
						$this->admin_update_cards_pins_details($card_id);

						$this->PinsCard->query("UPDATE ecom_pins_cards SET pc_status = '1' WHERE pc_id IN (
															SELECT pc_id
															FROM (
															SELECT pc_id AS pc_id
															FROM ecom_pins_cards
															WHERE pc_p_id IN (select pc_p_id From ecom_pins_cards WHERE `pc_c_id`=".$card_id." AND `pc_status` != 2)
															) AS tmptable)");
						
						foreach ($card_session_array as $c_id)
						{
							  $this->admin_update_cards_pins_details($c_id);	
						}  
						$this->Session->delete('card_session_array');

						$this->Session->setFlash(__('Unmerge Sucessfully done.'), 'default', array('class' => 'success'));
						$this->redirect(array('controller'=>'Cards','action'=>'index',$card_id));
					}
				}
				else
				{
					$this->redirect(array('controller'=>'Cards','action'=>'index',$card_id));
				}
			}
		}
	}

	public function admin_merge($id=NULL,$card_id=NULL)
	{
		$this->admin_redirect_to_dashboard_distributor();
		if(isset($card_id) && !empty($card_id))
		{
			$card_session_array = array();
			$card_session_array[] = $card_id;
			
			$this->Session->write('card_session_array',$card_session_array);

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
				'conditions' => array('c_status'=>1,'c_id <>'=>$card_id,'c_cat_id'=>$card_cat),
				'recursive'	 => -1
			));
			
			$this->loadModel('PinsCard');
			$final = array();
			//prd($all_cards);
			foreach($all_cards as $key => $value)
			{
				$conditions_check_pins = array();
				$conditions_check_pins['PinsCard.pc_status'] = 1;
				$conditions_check_pins['PinsCard.pc_c_id'] = $key;
				
				$count = $this->PinsCard->find('count',array('conditions'=>$conditions_check_pins));

				if($count!=0)
				{
					$final[$key]= $value;					
				}
			}

			$this->set('all_cards',$final);
			if($this->request->is('post') || $this->request->is('put'))
			{
				$data = $this->request->data;
				$card_session_array = $this->Session->read('card_session_array');
				$this->loadModel('PinsCard');
				if(isset($data) && !empty($data))
				{
					//prd($data);
					//cards selected for merging
					$final_data = array();
					ini_set('max_execution_time', 3000);
					$conditions_check_pins = array();
					$conditions_check_pins['PinsCard.pc_status'] = 1;
					
					/* Parking The Pins which is unused*/
					$pre_available_pin = array();
					foreach($data['PinsCard']['merge_from_c_id'] as $val)
					{
						$card_session_array[] = $val;
						$conditions_check_pins['PinsCard.pc_c_id'] = $val;
						$selectedcard_pins = $this->PinsCard->find('all',array(
							'conditions' => $conditions_check_pins,
							'recursive'  => -1
						));				
								
						if(isset($selectedcard_pins) && !empty($selectedcard_pins))
						{
							foreach($selectedcard_pins as $key=>$pc_data)
							{
								$already_exists = $this->PinsCard->hasAny(array('PinsCard.pc_p_id' => trim($selectedcard_pins[$key]['PinsCard']['pc_p_id']),'PinsCard.pc_c_id'=>$card_id));
								if(isset($already_exists) && !empty($already_exists))
								{
									$pre_available_pin[] = $selectedcard_pins[$key]['PinsCard']['pc_p_id'];
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
					
					//prd($pre_available_pin);
					if(count($selectedcard_pins))
					{
						$already_exists1 = $this->PinsCard->hasAny(array('PinsCard.pc_c_id'=>$card_id));
							if(isset($already_exists1) && !empty($already_exists1))
							{
								$this->PinsCard->bindModel(array('hasMany' => array('Pin')));
								$this->PinsCard->updateAll
									  (
										array('PinsCard.pc_status' => 3,
											  'Pin.p_status' => 3
											 ),
										array(
											   'PinsCard.pc_c_id' => $card_id,
											   'PinsCard.pc_status <>' => 2,
											   'PinsCard.pc_p_id <>' => $pre_available_pin,

											  )
								);
								
								$pre_available = implode(',',$pre_available_pin);
								$this->PinsCard->query("UPDATE ecom_pins_cards SET pc_status = '3' WHERE pc_id IN (
															SELECT pc_id
															FROM (
															SELECT pc_id AS pc_id
															FROM ecom_pins_cards
															WHERE pc_p_id IN (select pc_p_id From ecom_pins_cards WHERE `pc_c_id`=". $card_id." AND `pc_status` != 2
                                                                and 'pc_p_id not in (".$pre_available.")'
																)
															) AS tmptable)");
							}
						
						foreach ($selectedcard_pins as $key =>$value)
						{
						
                           $selectedcard_pins[$key]['PinsCard']['pc_status'] = 1;
						}
						
						if($this->PinsCard->saveMany($selectedcard_pins))
						{
							$this->Card->create();
							$this->Card->id=$card_id;
							//$save_arr = array('pin_card_count'=>$res['Card']['pin_card_count'] + count($selectedcard_pins),'pin_card_sold_count'=>$res['Card']['pin_card_sold_count'],'pin_card_remain_count'=>$res['Card']['pin_card_remain_count'] + count($selectedcard_pins),'sale_count'=>$res['Card']['sale_count']);
							
							//$this->Card->save($save_arr);
							$this->admin_update_cards_pins_details($card_id);

							foreach ($card_session_array as $c_id){
							  $this->admin_update_cards_pins_details($c_id);	
							}  
							$this->Session->delete('card_session_array');

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
		$this->admin_redirect_to_dashboard_distributor();
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
		$this->admin_redirect_to_dashboard_distributor();
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
    
    public function admin_get_file_name(){
           
           $this->loadModel('Card');
           $this->loadModel('Category');
           $this->loadModel('PinsCard');
           $pins_file_conditions = array();
           if($this->request->is('ajax'))
		   {
			   $data = $this->request->data;
			   if(isset($data['url_start_date']) && !empty($data['url_start_date']))
	           {
	           	 $pins_file_conditions['DATE(PinsCard.pin_created) >='] = $data['url_start_date'];
				 $pins_file_conditions['DATE(PinsCard.pin_created) <='] = $data['url_end_date'];
	           }
	         	if(isset($data['card_id']) && !empty($data['card_id']))
				{
                   $pins_file_conditions['pc_c_id'] = $data['card_id'];
                   $files = $this->PinsCard->find('list',array(
                   						 	 'conditions'=>$pins_file_conditions,
                   						     'fields'=>'pc_id,pin_file',
                   						      'order'=>'pin_file asc',
                   						      'group'=>'pin_file'
                   						    )
                                        );
				   echo json_encode($files);
			       exit;
				}
				else if(isset($data['sub_cat_id']) && !empty($data['sub_cat_id']))
				{
                   $card_conditions['c_cat_id'] = $data['sub_cat_id'];
                   $card_conditions['c_status'] = 1;
                   $cards = $this->Card->find('list',array(
                   						 	 'conditions'=>$card_conditions,
                   						     'fields'=>'c_id,c_title',
                   						      'order'=>'c_title asc',
                   						    )
                                        );
                   $pins_file_conditions['pc_c_id'] = array_keys($cards);
                   $files = $this->PinsCard->find('list',array(
                   						 	 'conditions'=>$pins_file_conditions,
                   						     'fields'=>'pc_id,pin_file',
                   						      'order'=>'pin_file asc',
                   						      'group'=>'pin_file'
                   						    )
                                        );
				   echo json_encode($files);
			       exit;
				}
				else if(isset($data['cat_id']) && !empty($data['cat_id']))
				{
				   $sub_cat_conditions['cat_parent_id'] = $data['cat_id'];
				   $sub_cat_conditions['cat_status'] = 1;
				   $sub_cats = $this->Category->find('list',array(
                   						 	 'conditions'=>$sub_cat_conditions,
                   						     'fields'=>'cat_id,cat_title',
                   						      'order'=>'cat_title asc',
                   						    )
                                        );

				   $card_conditions['c_cat_id'] = array_keys($sub_cats);
                   $card_conditions['c_status'] = 1;
                   $cards = $this->Card->find('list',array(
                   						 	 'conditions'=>$card_conditions,
                   						     'fields'=>'c_id,c_title',
                   						      'order'=>'c_title asc',
                   						    )
                                        );
                   $pins_file_conditions['pc_c_id'] = array_keys($cards);
                   $files = $this->PinsCard->find('list',array(
                   						 	 'conditions'=>$pins_file_conditions,
                   						     'fields'=>'pc_id,pin_file',
                   						      'order'=>'pin_file asc',
                   						      'group'=>'pin_file'
                   						    )
                                        );
				   echo json_encode($files);
			       exit;
				}
				else
				{
					$files = $this->PinsCard->find('list',array(
                   						 	 'conditions'=>$pins_file_conditions,
                   						 	 'fields'=>'pc_id,pin_file',
                   						     'order'=>'pin_file asc',
                   						     'group'=>'pin_file'
                   						    )
                                        );
					echo json_encode($files);
			        exit;
				}
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

	public function admin_pins_detail_excel($card_id = 0,$cat_id = 0, $sub_cat_id = 0, $pc_status = 0,$file_name = 0,$url_start_date = 0,$url_end_date=0){
        
		$this->admin_redirect_to_dashboard_distributor();
		
		ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');
		$this->loadModel('Pin');
		// cat & subcat

		$this->loadModel('Category');
		$this->loadModel('Card');

		$res = $this->Category->find('list',array(
			'conditions' => array('cat_status'=>1,'cat_parent_id'=>null),
			'fields' => array('cat_id','cat_title'),
			'order'=>'cat_title asc',
			'recursive'	 => -1
		));

    	foreach($res as $k => $v)
		$res[$k] = ucwords(strtolower($v));

		
		$subCatConditions = array();
		$subCatConditions['Category.cat_status'] = 1;
		$subCatConditions['Category.cat_parent_id <>'] = null;

		if(isset($cat_id) && !empty($cat_id)) {	
			$subCatConditions['Category.cat_parent_id'] = $cat_id;
		}

        /*$resSubCat = $this->Category->find('list',array(
						'conditions' => $subCatConditions,
						'fields' => array('cat_id','cat_title'),
						'recursive'	 => -1,
						'order'=>'cat_title asc',
					));*/
        
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

        $cardCondition = array();
		$cardCondition['Card.c_status'] = 1 ;
		$cardCondition['Card.c_cat_id'] = array_keys($resSubCat) ;

		if(isset($sub_cat_id) && !empty($sub_cat_id) && in_array($sub_cat_id,array_keys($resSubCat))) {
			$cardCondition['Card.c_cat_id'] = $sub_cat_id;
		}

		if(isset($card_id) && !empty($card_id)){
			$cardCondition['Card.c_id'] = $card_id;
		} 
      
       /*File Select Filter*/
       if(!empty($file_name)) 
       {
       	  $cardCondition['PinsCard.pin_file'] = $file_name;
       }

       if($pc_status)
       {
       	 $cardCondition['PinsCard.pc_status'] = $pc_status;
       }

       if($url_end_date && $url_start_date)
       {
       	  $cardCondition['DATE(PinsCard.pin_created) >='] = $url_start_date;
		  $cardCondition['DATE(PinsCard.pin_created) <='] = $url_end_date;
       }

       /*File Select Filter End*/
       $cardRes = $this->Card->find('all',array(
			'conditions' => $cardCondition,
			'fields' => array('Card.*','PinsCard.*','Pin.*'),
			 'recursive'	 => -1,
			 'order'=>'c_title asc',
			    'joins' => array(
					array(
						'table' => 'ecom_pins_cards',
						'alias' => 'PinsCard',
						'type' => 'left',
						'conditions' => 'PinsCard.pc_c_id = Card.c_id'
					),
					array(
						'table' => 'ecom_pins',
						'alias' => 'Pin',
						'type' => 'left',
						'conditions' => 'Pin.p_id = PinsCard.pc_p_id'
					)
				),
    	));
        $card_results = array();
        $counter = -1;
        $previous_card_id = 0;
        
        
        foreach ($cardRes as $key => $value) 
        {
             $card_id = $value['Card']['c_id'];
             if($card_id != $previous_card_id)
             {
             	$counter++;
             	$previous_card_id = $card_id;
             	$card_results[$counter]['Card'] =$value['Card'];
             }
             $card_results[$counter]['PinsCard'][] = $value['PinsCard'];
             $card_results[$counter]['Pin_detail'][] = $value['Pin'];
        }
        
        $this->Session->write('cardRes_pin',$card_results);
		$this->Session->write('pc_status',$pc_status);
        
        $this->admin_download_csv_pins_details();
		
	}

    public function admin_downloadexcel_pins_grid(){
        
        $cardRes   = $this->Session->read('cardRes_pin');
        $pc_status = $this->Session->read('pc_status');
        
        ini_set('max_execution_time',0);
    	ini_set('memory_limit','1024M');

    	$objPHPExcel = new PHPExcel();
		
		//Setting Column Width
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(21);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(22);
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
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
          if(isset($pc_status) && !empty($pc_status))
          {
        	if($pc_status==1)
        	{
        		$reports_of = "Detail Report Of Unused Pins";
        	}
        	if($pc_status==2){
        		$reports_of = "Detail Report Of Sold Pins";
        	}
        	if($pc_status==3){
        		$reports_of = "Detail Report Of Parked Pins";
        	}
        	if($pc_status==4){
        		$reports_of = "Detail Report Of Rejected Pins";
        	}
        	if($pc_status==5){
        		$reports_of = "Detail Report Of Returned Pins";
        	}
        }
        else
        {
			$reports_of = "Pin Detail Report";
		}
		
		$objPHPExcel->getActiveSheet()->setCellValue('A2',$reports_of);
		$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setWrapText(true);
        
        $pins_status_array = array(
                                   '1'=>__('Unused'),
                                   '2'=>__('Sold'),
                                   '3'=>__('Parked'),
                                   '4'=>__('Rejected'),
                                   '5'=>__('Returned'),
        						  );

		$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
		$FirstItemNumber = 4;
		$heading = $FirstItemNumber + 2;
		
		if(empty($cardRes))
		{
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':E'.$FirstItemNumber);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,'No Record Found!!');
			$objPHPExcel->getActiveSheet()->mergeCells('D'.$FirstItemNumber.':G'.$FirstItemNumber);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(false)->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
		}
		else
		{
              
			foreach($cardRes as $keynew=>$val)
			{
				if(isset($pc_status) && !empty($pc_status))
				{
					$val['Card']['pin_card_remain_count'] = $val['Card']['pin_card_remain_count']+$val['Card']['pin_card_count_parked']+$val['Card']['pin_card_count_rejected']+$val['Card']['pin_card_count_returned'];
                  	
                  	if($pc_status == 2)
                  	$card_detail = "Card Name: ".$val['Card']['c_title']."   Pin Sold: ".$val['Card']['pin_card_sold_count'];

					else if($pc_status == 1)
					$card_detail = "Card Name: ".$val['Card']['c_title']."   Pin Unused: ".$val['Card']['pin_card_remain_count'];

				    else if($pc_status == 3)
				    $card_detail = "Card Name: ".$val['Card']['c_title']."   Pin Parked: ".$val['Card']['pin_card_count_parked'];
	
	                else if($pc_status == 4)
				    $card_detail = "Card Name: ".$val['Card']['c_title']."   Pin Rejected: ".$val['Card']['pin_card_count_rejected'];
	
	                else if($pc_status == 5)
				    $card_detail = "Card Name: ".$val['Card']['c_title']."   Pin Returned: ".$val['Card']['pin_card_count_returned'];

	 
						
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$card_detail);
					$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':G'.$FirstItemNumber);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
					
					$serial_counter = 0;
					$FirstItemNumber = $FirstItemNumber+3;
					$j = 0;
                    if(isset($val['Pin_detail'][0]['p_status']) && !empty($val['Pin_detail'][0]['p_status']))
					{
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,'S.No');
						$objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,"Serial Number");
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,"Pin Number");
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,"File Name");
						$objPHPExcel->getActiveSheet()->setCellValue('E'.$heading,"Date");
						$objPHPExcel->getActiveSheet()->setCellValue('F'.$heading,"Status");
						//0:'All', 1: 'Unused',2: 'Sold', 3: 'Parked',4: 'Rejected',5: 'Returned'
						$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getFont()->setBold(true);;
						$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getAlignment()->setWrapText(true);
						$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
						foreach ($val['Pin_detail'] as $key => $value) 
						{
							$serial_counter++;
							$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,$value['p_serial']);
							$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,$value['p_pin']);
							if(isset($val['PinsCard'][$j]['pin_file']) && !empty($val['PinsCard'][$j]['pin_file']))
							{
								$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,$val['PinsCard'][$j]['pin_file']);
							}
							else
							{
								$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,'NA');
							}
							if(isset($val['PinsCard'][$j]['pin_created']) && !empty($val['PinsCard'][$j]['pin_created']))
							{
								$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,date('d.m.Y h:m:s',strtotime($val['PinsCard'][$j]['pin_created'])));
							}
							else
							{
								$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,'NA');
							}
							
							$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,$pins_status_array[$value['p_status']]);
							$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
							$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setWrapText(true);
							$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
							
							$FirstItemNumber++;
							$heading = $FirstItemNumber + 2;

							$j++;
						}
					}
					else
					{
						$FirstItemNumber = $FirstItemNumber-2;
						$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':B'.$FirstItemNumber);
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,'No Pins Available!!');
						$objPHPExcel->getActiveSheet()->mergeCells('D'.$FirstItemNumber.':G'.$FirstItemNumber);
						$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
						$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setWrapText(true);
						$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
						$FirstItemNumber = $FirstItemNumber+1;

					}
				}
				else
				{
					if(isset($val['Pin_detail']) && !empty($val['Pin_detail']))
					{
						$card_detail = "Card Name: ".$val['Card']['c_title']."   Count: ".count($val['Pin_detail']);
					}
					else
					{
						$card_detail = "Card Name: ".$val['Card']['c_title'];
					}
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$card_detail);
					$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':G'.$FirstItemNumber);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				
					$serial_counter = 0;
					$FirstItemNumber = $FirstItemNumber+3;
					$j = 0;
                    if(isset($val['Pin_detail'][0]['p_status']) && !empty($val['Pin_detail'][0]['p_status']))
					{
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,'S.No');
						$objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,"Serial Number");
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,"Pin Number");
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,"File Name");
						$objPHPExcel->getActiveSheet()->setCellValue('E'.$heading,"Date");
						$objPHPExcel->getActiveSheet()->setCellValue('F'.$heading,"Status");
						$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getFont()->setBold(true);;
						$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getAlignment()->setWrapText(true);
						$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
						foreach ($val['Pin_detail'] as $key => $value) 
						{
							$serial_counter++;
							$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,$value['p_serial']);
							$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,$value['p_pin']);
							if(isset($val['PinsCard'][$j]['pin_file']) && !empty($val['PinsCard'][$j]['pin_file']))
							{
								$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,$val['PinsCard'][$j]['pin_file']);
							}
							else
							{
								$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,'NA');
							}
							if(isset($val['PinsCard'][$j]['pin_created']) && !empty($val['PinsCard'][$j]['pin_created']))
							{
								$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,date('d.m.Y h:m:s',strtotime($val['PinsCard'][$j]['pin_created'])));
							}
							else
							{
								$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,'NA');
							}
							
							$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,$pins_status_array[$value['p_status']]);
							$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
							$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setWrapText(true);
							$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
							$FirstItemNumber++;
							$heading = $FirstItemNumber + 2;

							$j++;
						}
					}
					else
					{
						$FirstItemNumber = $FirstItemNumber-2;
						$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':B'.$FirstItemNumber);
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,'No Pins Available!!');
						$objPHPExcel->getActiveSheet()->mergeCells('D'.$FirstItemNumber.':G'.$FirstItemNumber);
						$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
						$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setWrapText(true);
						$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
						$FirstItemNumber = $FirstItemNumber+1;
					}
		    }

            $heading = $FirstItemNumber + 3;
			$FirstItemNumber++;	
				
			}
		}

		// Set page margin
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);

        $file_name = 'PinDetail.xls';    
		$fullPath = WWW_ROOT.$file_name;
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');		
		$objWriter->save($fullPath);
		
	   	if ($fd = fopen ($fullPath, "r")) 
		{
			$fsize = filesize($fullPath);
			$path_parts = pathinfo($fullPath);
			$ext = strtolower($path_parts["extension"]);
			switch ($ext) 
			{
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
    
    public function admin_download_csv_pins_details(){

        	ini_set('max_execution_time', 0);
            ini_set('memory_limit', '1024M');

        	$cardRes   = $this->Session->read('cardRes_pin');
            $pc_status = $this->Session->read('pc_status');
            
            $pins_status_array = array(
                                   '1'=>__('Unused'),
                                   '2'=>__('Sold'),
                                   '3'=>__('Parked'),
                                   '4'=>__('Rejected'),
                                   '5'=>__('Returned'),
        						  );

            $out = '';
            
            if(empty($cardRes))
		    {
			   $out .= __('No pins found') .',';
			   $out .="\n";
			}
		    else
		    {
				$out .="\n";
				foreach($cardRes as $keynew=>$val)
				{
					$out .="\n";
					if(isset($pc_status) && !empty($pc_status))
					{
						//$val['Card']['pin_card_remain_count'] = $val['Card']['pin_card_remain_count']+$val['Card']['pin_card_count_parked']+$val['Card']['pin_card_count_rejected']+$val['Card']['pin_card_count_returned'];
	                  	
	                  	if($pc_status == 2)
	                  	{
	                  		$card_detail = "Card Name: ";
	                  		$pins_detail = "Pin Sold: ";
                            $out .= '"' . $card_detail .'",';
                            $out .= '"' . $val['Card']['c_title'] .'",';
                            $out .= '"' . $pins_detail .'",'; 
	                  	    $out .= '"' . $val['Card']['pin_card_sold_count'] .'",';
                            
	                  	}

	                  	if($pc_status == 1)
	                  	{
	                  		$card_detail = "Card Name: ";
	                  		$pins_detail = "Pin Remaining: ";
                            $out .= '"' . $card_detail .'",';
                            $out .= '"' . $val['Card']['c_title'] .'",';
                            $out .= '"' . $pins_detail .'",'; 
	                  	    $out .= '"' . $val['Card']['pin_card_remain_count'] .'",';
	                  	}

	                  	if($pc_status == 3)
	                  	{
	                  		$card_detail = "Card Name: ";
	                  		$pins_detail = "Pin Parked: ";
                            $out .= '"' . $card_detail .'",';
                            $out .= '"' . $val['Card']['c_title'] .'",';
                            $out .= '"' . $pins_detail .'",'; 
	                  	    $out .= '"' . $val['Card']['pin_card_count_parked'] .'",';
	                  	}

	                  	if($pc_status == 4)
	                  	{
	                  		$card_detail = "Card Name: ";
	                  		$pins_detail = "Pin Rejected: ";
                            $out .= '"' . $card_detail .'",';
                            $out .= '"' . $val['Card']['c_title'] .'",';
                            $out .= '"' . $pins_detail .'",'; 
	                  	    $out .= '"' . $val['Card']['pin_card_count_rejected'] .'",';
	                  	}

	                  	if($pc_status == 5)
	                  	{
	                  		$card_detail = "Card Name: ";
	                  		$pins_detail = "Pin Returned: ";
                            $out .= '"' . $card_detail .'",';
                            $out .= '"' . $val['Card']['c_title'] .'",';
                            $out .= '"' . $pins_detail .'",'; 
	                  	    $out .= '"' . $val['Card']['pin_card_count_returned'] .'",';
	                  	}
	                }
		 	        else
		 	        {
                  		$card_detail = "Card Name: ";
                  		$out .= '"' . $card_detail .'",'; 
                        $out .= '"' . $val['Card']['c_title'] .'",'; 
                    }    
	 	            
	 	            $out .="\n";

                    $serial_counter = 0;
					
					if(isset($val['Pin_detail'][0]['p_status']) && !empty($val['Pin_detail'][0]['p_status']))
					{
						$j = 0;
						$out .= '"' . __("S. No.") . '","' . __("Serial Number") . '","' . __("Pin Number") . '","' . __("File Name") . '","' . __("Date") . '","' . __("Status") . '"';
	 	            	$out .="\n";
	 	            	foreach ($val['Pin_detail'] as $key => $value) 
						{
							//prd($val['Pin_detail']);
							//$value['p_serial'] = strval($value['p_serial']);
 						    $serial_counter++;
 						    $out .=$serial_counter .',';
							$out .='" '.trim($value['p_serial']).' ",';
							$out .='" '.trim($value['p_pin']).' ",';
							$out .='" '.strval($val['PinsCard'][$j]['pin_file']).' ",';
							$out .='" '.date('d.m.Y h:m:s',strtotime($val['PinsCard'][$j]['pin_created'])).' ",';
							$out .='" '.$pins_status_array[$value['p_status']].' ",';
							$out .="\n";
						    $j++;
						}
					}
					else
					{
						$out .='"' . __('No pins found') .'",';
						$out .="\n";
					}
				}
		    }
		    header("Content-type: text/x-csv; charset=utf-8");
            header('Content-Disposition: attachment;IMEX=1; filename="PinsDetails_Report.csv"');
            
            echo $out;
            exit;

     }

     
    
    public function admin_existing_pins_file_download(){
        
        $this->loadModel('PinsCard');         
        $existing_pin = $this->Session->read('existing_pin');
   	    
   	    $this->Session->setFlash(count($this->Session->read('existing_pin')).' pins  are already exists!! Try with new pins.', 'default', array('class' => 'error'));
        
        $pins_card_conditions = array();
        $all_pins_file = array();
        $counter = 0;
        foreach ($existing_pin as $pins) 
        {
        	$pins_card_conditions['PinsCard.pc_p_id'] = $pins['p_id'];
            $pinscard_data = $this->PinsCard->find('first',
            	array('conditions'=>$pins_card_conditions,
                      'recursive'=>-1
            		 )
            	);  
            //$all_pins_file[$counter]['serial'] = $pins['p_serial'];
            $all_pins_file[$counter]['pin'] = $pins['p_pin'];
            $all_pins_file[$counter]['file'] = $pinscard_data['PinsCard']['pin_file'];
            $all_pins_file[$counter]['created'] = date('d.m.Y H:i:s',strtotime($pinscard_data['PinsCard']['pin_created']));
            $counter++;
        }
        $objPHPExcel = new PHPExcel();
		
		//Setting Column Width
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		
		
		$objPHPExcel->getProperties()->setCreator("Calling Cards")->setLastModifiedBy("Calling Cards")->setTitle("Pins Details")
																 ->setSubject("Calling Cards")
																 ->setDescription("Calling Cards")
																 ->setKeywords("office 2007 openxml php")
																 ->setCategory("Export To Excel");		
		
		$objPHPExcel->setActiveSheetIndex(0);		
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		
		// Title of the Report		
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFont()->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        
        $reports_of = "Existing Pins Details";
		$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
		$objPHPExcel->getActiveSheet()->setCellValue('A2',$reports_of);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);
		$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
        
        $heading = 4;
		$FirstItemNumber = $heading + 2;
        

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,'S.No');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,"Pin");
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,"File Name");
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,"Uploded On");
		
		$counter = 0;      
		foreach ($all_pins_file as $value) 
		{
		 	$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,++$counter);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,$value['pin']);
		    $objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,$value['file']);
		    $objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,$value['created']);
	
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':D'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':D'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':D'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':D'.$FirstItemNumber)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':D'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
			
			$FirstItemNumber++;	 			
		 }

		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);

        $file_name = 'ExistingPinsDetail.xls';    
		$fullPath = WWW_ROOT.$file_name;
		// prd($objPHPExcel);
     	$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');		
		$objWriter->save($fullPath);
		
		$card_id = $this->Session->read('existing_pin_card');

		$this->Session->delete('existing_pin');
		$this->Session->delete('existing_pin_card');
		
		return;
    } 

    public function admin_download_duplicate_pins(){

        	$fullPath = WWW_ROOT.'ExistingPinsDetail.xls';
			if(file_exists($fullPath))
			{
				//prd($fullPath);
				$fsize = filesize($fullPath);
				$path_parts = pathinfo($fullPath);
				$ext = strtolower($path_parts["extension"]);
				header("Content-type: application/octet-stream");
				header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
				header("Content-length: $fsize");
				header("Cache-control: private"); //use this to open files directly
				readfile($fullPath);
				//unlink($fullPath);
			}
			exit;
    }
}
