<?php

App::uses('AppController', 'Controller');
include(APP.'Vendor/PHPExcel/Classes/PHPExcel.php');
include(APP.'Vendor/PHPExcel/Classes/PHPExcel/IOFactory.php');
/*App::import('Vendor', 'Spreadsheet_Excel_Reader', array('file' => 'excel_reader2.php'));
App::import('Vendor','PHPExcel',array('file' => 'PHPExcel.php'));
App::import('Vendor','PHPExcelWriter',array('file' => 'PHPExcel/Writer/Excel5.php'));
App::import('Vendor','PHPExcel_IOFactory',array('file' => 'PHPExcel/Classes/PHPExcel/IOFactory.php'));*/
class ReportsController extends AppController
{
	public $uses = array();

	public $components = array('Auth','RequestHandler','Commonfunctions');
    

	public function beforeFilter()
	{
		$this->loadModel('User');
		$this->User->virtualFields = array(
											  'full_name' => "CONCAT(User.fname, ' ',User.lname)"
											);
		parent::beforeFilter();
		$this->disableCache();			
	}
   
   public function admin_get_retailers()
	{
		$this->loadModel('User');
		if($this->request->is('ajax'))
		{
			$data = $this->request->data;
			$id 	= $data['id'];
			$conditions =array();
			$conditions['User.added_by'] = $id;
			$conditions['User.status'] = array('1','2');
			
			$get_retailers = $this->User->find('list',array(
					'fields'		 => array('User.id','User.full_name'),
					'conditions' => $conditions,
					'order'=>'User.fname , User.lname asc'
			));
			
			foreach($get_retailers as $k =>$v)
			$get_retailers[$k] = ucwords($v);
			echo json_encode($get_retailers);
			exit;
			
		}
   }
		
   public function admin_sales_report($retailer_id =0,$card_id = 0,$start_range = NULL ,$end_range =NULL,$cat_id = 0, $sub_cat_id = 0){
			
			$this->admin_redirect_to_dashboard_mediator();
			$this->set("title_for_layout",__('Sales Report'));	
	        
			$login_user = $this->Auth->User('id');
			
			$this->loadModel('Card');
   	        $this->loadModel('Sale');
			

			$this->loadModel('Category');
            
            
			/* 13 March : Category and subcategory List */
			$res = $this->Category->find('list',array(
					'conditions' => array('cat_parent_id'=>null),
					'fields' => array('cat_id','cat_title'),
					'recursive'	 => -1,
					'order'=>'cat_title asc'
			));
			
			foreach($res as $k => $v)
    		$res[$k] = ucwords(strtolower($v));
			
			$this->set('cateList',$res);
			$this->set('selected_cat',$cat_id);
			//pr($cat_id);
			
            if(!empty($cat_id) && $cat_id > 0){
                $subCatConditions = array();
               // $subCatConditions['Category.cat_status'] = 1;
                $subCatConditions['Category.cat_parent_id <>'] = null;

                if (isset($cat_id) && !empty($cat_id)) {
                    $this->set('cat_id', $cat_id);
                    $subCatConditions['Category.cat_parent_id'] = $cat_id;
                }

                $resSubCat = $this->Category->find('list', array(
                    'conditions' => $subCatConditions,
                    'fields' => array('cat_id', 'cat_title'),
                    'recursive' => -1,
                    'order' => 'cat_title asc'
                ));

				foreach($resSubCat as $k => $v)
        		$resSubCat[$k] = ucwords(strtolower($v));
        
		        $this->set('subCateList',$resSubCat);
                $this->set('selected_sub_cat',$sub_cat_id);
            }
            /* END Category and subcategory Listing */


			/* Get Card List*/
			
			$card_conditions = array();
			//$card_conditions['c_status'] = 1;
            if($sub_cat_id > 0 && $cat_id > 0){
                $card_conditions['c_cat_id'] = $sub_cat_id;
            }else if($cat_id > 0){
                $card_conditions['c_cat_id'] = array_keys($resSubCat);
            }

			
			$order = 'c_title asc';
			$fields = array('c_id','c_title');
			$get_cards = $this->Card->find('list',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$fields));
            
			$all_cards =array();
			foreach($get_cards as $k=>$v)
			{
				$all_cards[$k] = ucwords(strtolower($v));
			}
			
			/* Getting Retailer data*/
			$retailer_conditions = array();
			$retailer_conditions['User.added_by'] = $login_user;
			$retailer_conditions['User.status'] = array('1','2');
			$fields_retailer = array('User.id','User.full_name');
			$order_retailer = 'fname,lname asc';
			$get_retailer_data = $this->User->find('list',array('conditions'=>$retailer_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
			
			foreach($get_retailer_data as $k => $v)
       		$get_retailer_data[$k] = ucwords(strtolower($v));

			$retailer_list = array();
			foreach($get_retailer_data as $k=>$v)
			{
				$retailer_list[]= $k;
			}
			
			/* Getting Sales Conditions */
			$sales_conditions = array();
			// If Any Retailer is Selected
			if($retailer_id != 0)
			{
				$sales_conditions['s_u_id'] = $retailer_id;
			}
			else
			{  
				//If No Retailer Exists
				if(empty($retailer_list))
				{
					$sales_conditions['s_u_id'] = 0;
				}
				else
				{
					 // If Retailer Exists
				$sales_conditions['s_u_id'] = $retailer_list;
				}
			}
             
            if($card_id != 0)
			{
				$sales_conditions['s_c_id'] = $card_id;
			}else{
                $sales_conditions['s_c_id'] = array_keys($get_cards);
            }
            
			if($start_range != NULL && $end_range != NULL)
			{
				$start_range_explode = explode('-',$start_range);
				$end_range_explode = explode('-',$end_range);
				
				if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
				{   
				    $start_date = $start_range_explode[2];
					$start_month = $start_range_explode[1];
					$start_year = $start_range_explode[0];
					
					$end_date = $end_range_explode[2];
					$end_month = $end_range_explode[1];
					$end_year = $end_range_explode[0];
					
					if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
					{
						$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
						$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					    $date_set_start = $start_date.".".$start_month.".".$start_year;
					    $date_set_end = $end_date.".".$end_month.".".$end_year;
					}
				}
			}
			
			if(isset($date_set_end))
			{
			
			}
			else
			{
				$date_set_end   = '';
				$date_set_start = '';
			}

		 $get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>'Sale.s_date desc, Sale.s_time desc','fields'=>array('Sale.*','Card.c_title',"CONCAT(User.fname,' ',User.lname) as retailer_name")));
		 $total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount',
														));
		 $card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
			$card_count = 0;
			if(!empty($card_sale_count[0]['total_card']))
			{
				$card_count = $card_sale_count[0]['total_card'];
			}

			if(!empty($total_sales[0]['total_amount']))
			{
				$total_sales_amount = $total_sales[0]['total_amount'];
			}
			else
			{
				$total_sales_amount = 0.00;
			}

			$this->set('retailer_id',$retailer_id);
			$this->set('card_id',$card_id);
			$this->set('retailer_list',$get_retailer_data);
			$this->set('all_cards',$all_cards);
			$this->set('get_sales_data',$get_sales_data);
			$this->set('total_sales_amount',$total_sales_amount);
			$this->set('card_count',$card_count);
			$this->set('date_set_end',$date_set_end);
			$this->set('date_set_start',$date_set_start);
	 }
  
   public function  admin_sales_report_excel($cat_id=0,$sub_cat_id = 0,$retailer_id = 0,$card_id = 0,$start_range = 0,$end_range = 0,$sales_id=0){
		$this->admin_redirect_to_dashboard_mediator();
  		ini_set('max_execution_time', 0);
		// Create new PHPExcel object
		$this->loadModel('Card');
   	    $this->loadModel('Sale');
   	    $this->loadModel('User');
		$this->loadModel('Category');
			
		$login_user = $this->Auth->User('id');
		
		$res = $this->Category->find('list',array(
					'conditions' => array('cat_parent_id'=>null),
					'fields' => array('cat_id','cat_title'),
					'recursive'	 => -1,
					'order'=>'cat_title asc'
			));
			
		if(!empty($cat_id) && $cat_id > 0)
		{
			$subCatConditions = array();
			$subCatConditions['Category.cat_parent_id <>'] = null;

			if (isset($cat_id) && !empty($cat_id)) {
					$this->set('cat_id', $cat_id);
					$subCatConditions['Category.cat_parent_id'] = $cat_id;
			}

			$resSubCat = $this->Category->find('list', array(
					'conditions' => $subCatConditions,
					'fields' => array('cat_id', 'cat_title'),
					'recursive' => -1,
					'order' => 'cat_title asc'
			));
		}
	    /* END Category and subcategory Listing */
			
		/* Get Card List*/
		$card_conditions = array();
		//$card_conditions['c_status'] = 1;
        
		if($sub_cat_id > 0 && $cat_id > 0)
		{
				$card_conditions['c_cat_id'] = $sub_cat_id;
		}
		else if($cat_id > 0)
		{
				$card_conditions['c_cat_id'] = array_keys($resSubCat);
		} 

		$order = 'c_title asc';
		$fields = array('c_id','c_title');
		$get_cards = $this->Card->find('list',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$fields));
        
		$all_cards =array();
		foreach($get_cards as $k=>$v)
		{
			$all_cards[$k] = ucwords($v);
		}
			

		/* Getting Sales Conditions */
		$sales_conditions = array();
		// If Any Retailer is Selected
		if($retailer_id != 0)
		{
			$sales_conditions['s_u_id'] = $retailer_id;
		}
		else if($sales_id == 0)
		{  
			/* Getting Retailer data*/
			$retailer_conditions = array();
			$retailer_conditions['User.added_by'] = $login_user;
			$retailer_conditions['User.status'] = array('1','2');
			$fields_retailer = array('User.id','User.full_name');
			$get_retailer_data = $this->User->find('list',array('conditions'=>$retailer_conditions,'fields'=>$fields_retailer));
			
			$retailer_list = array();
			foreach($get_retailer_data as $k=>$v)
			{
				$retailer_list[]= $k;
			}
			
			//If No Retailer Exists
			if(empty($retailer_list))
			{
				$sales_conditions['s_u_id'] = 0;
			}
			else
			{
				 // If Retailer Exists
			$sales_conditions['s_u_id'] = $retailer_list;
			}
		}
			
		$total_sales_amount = 0.00;
		if($card_id != 0)
		{
			$sales_conditions['s_c_id'] = $card_id;
		}
        else
        {
			$sales_conditions['s_c_id'] = array_keys($get_cards);
		} 



		if($start_range != 0 && $end_range != 0 && $sales_id == 0)
		{
			$start_range_explode = explode('-',$start_range);
			$end_range_explode = explode('-',$end_range);
			
			if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
			{   
				$start_date = $start_range_explode[2];
				$start_month = $start_range_explode[1];
				$start_year = $start_range_explode[0];
				
				$end_date = $end_range_explode[2];
				$end_month = $end_range_explode[1];
				$end_year = $end_range_explode[0];
				
				if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
				{
					$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
					$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					$date_set_start = $start_date.".".$start_month.".".$start_year;
					$date_set_end = $end_date.".".$end_month.".".$end_year;
				}
			}
		}
		
		if($sales_id != 0)
		{
			$sales_conditions['s_id'] = $sales_id;
		}
		
		$get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>'Sale.s_date desc, Sale.s_time desc','fields'=>array('Sale.*','Card.c_title',"CONCAT(User.fname,' ',User.lname) as retailer_name",'User.id')));
	    //prd($get_sales_data);
		$total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount',
														));
		$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
		$card_count = 0;
		if(!empty($card_sale_count[0]['total_card']))
		{
			$card_count = $card_sale_count[0]['total_card'];
		}

		if(!empty($total_sales[0]['total_amount']))
		{
			$total_sales_amount = $total_sales[0]['total_amount'];
		}
		else
		{
			$total_sales_amount = 0.00;
		}
	    
		//error_reporting(E_ALL);
		$objPHPExcel = new PHPExcel();
		
		// Setting Column Width
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
   		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		
		$objPHPExcel->getProperties()->setCreator("Calling Cards")->setLastModifiedBy("Calling Cards")->setTitle("Office 2007 XLSX Document")
																 ->setSubject("Calling Cards")
																 ->setDescription("Calling Cards")
																 ->setKeywords("office 2007 openxml php")
																 ->setCategory("Export To Excel");		
		
		$objPHPExcel->setActiveSheetIndex(0);		
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		
		// Title of the Report		
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setWrapText(true);

		$reports_of = "Sales Report";
		if($retailer_id != 0)
		{
			if(isset($get_sales_data[0][0]['retailer_name']))
			{
				$retailer_name = $get_sales_data[0][0]['retailer_name'];
			}
			else
			{
				$get_ret_name = $this->User->find('first',array('conditions'=>array('id'=>$retailer_id),'fields'=>'full_name'));
			    if($get_ret_name)
				$retailer_name = $get_ret_name['User']['full_name'];
			}
			
			if(!empty($retailer_name))
			{
				if(strlen($retailer_name) > 30)
				$retailer_name = substr($retailer_name,0,35)."..";
				$reports_of = 'Sales Report Of Retailer: '.ucwords($retailer_name);
			}
		}
		
		if($retailer_id == 0 && $sales_id != 0)
		{
			$retailer_id = $get_sales_data[0]['User']['id'];
		   
		    $ret_name = $this->User->find('first',array('conditions'=>array('id'=>$retailer_id),'fields'=>'full_name'));
		    if(!empty($ret_name))
			{
				if(strlen($ret_name['User']['full_name']) > 35)
				$ret_name['User']['full_name'] = substr($ret_name['User']['full_name'],0,35)."..";
				$reports_of = 'Sales Report Of Retailer: '.ucwords($ret_name['User']['full_name']);
			}
		}
		
		$objPHPExcel->getActiveSheet()->setCellValue('A2',$reports_of);			
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		
		$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
		
		$heading = 4;
		$FirstItemNumber = $heading + 2;

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,'S.No');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,"Retailer");
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,"Card Name");
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,"Selling Price(€)");
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$heading,"Quantity");
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$heading,"Date-Time");
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$heading,'Total Sales(€)');
		 

		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getFont()->setBold(true);;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		
		$serial_counter = 0;
		foreach($get_sales_data as $keynew=>$val)
		{
			$serial_counter++;
   
		    $objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,ucwords($val['0']['retailer_name']));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,ucwords($val['Card']['c_title']));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,number_format($val['Sale']['s_selling_price'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,$val['Sale']['card_sale_count']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,date('d.m.Y',strtotime($val['Sale']['s_date']))." ".$val['Sale']['s_time']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$FirstItemNumber,number_format($val['Sale']['s_total_sales'],2));
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);

			$FirstItemNumber++;
		}
		
		// Setting Last Line Style For Net Total
		$FirstItemNumber++;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(True)->setSize(13);

        // Merging Column
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':D'.$FirstItemNumber);

		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,'Net Quantity');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,$card_count);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$FirstItemNumber,$total_sales_amount);
		
		// Set page margin
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);

        $file_name = 'SalesReport'.$login_user.'.xls';    
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
 
   public function admin_daily_sales($retailer_id =0,$start_range = NULL ,$end_range =NULL){
			$this->admin_redirect_to_dashboard_mediator();
			$this->set("title_for_layout",__('Daily Sales Report'));	
	        
			$login_user = $this->Auth->User('id');
			
			$this->loadModel('Card');
   	        $this->loadModel('Sale');
			
			/* Getting Retailer data*/
			$retailer_conditions = array();
			$retailer_conditions['User.added_by'] = $login_user;
			$retailer_conditions['User.status'] = array('1','2');
			$fields_retailer = array('User.id','User.full_name');
			$order_retailer = 'fname,lname asc';
			$get_retailer_data = $this->User->find('list',array('conditions'=>$retailer_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
			
			$retailer_list = array();
			foreach($get_retailer_data as $k=>$v)
			{
				$retailer_list[]= $k;
			}
			
			/* Getting Sales Conditions */
			$sales_conditions = array();
			// If Any Retailer is Selected
			if($retailer_id != 0)
			{
				$sales_conditions['s_u_id'] = $retailer_id;
			}
			else
			{  
				//If No Retailer Exists
				if(empty($retailer_list))
				{
					$sales_conditions['s_u_id'] = 0;
				}
				else
				{
					 // If Retailer Exists
				$sales_conditions['s_u_id'] = $retailer_list;
				}
			}

			if($start_range != NULL && $end_range != NULL)
			{
				$start_range_explode = explode('-',$start_range);
				$end_range_explode = explode('-',$end_range);
				
				if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
				{   
				    $start_date = $start_range_explode[2];
					$start_month = $start_range_explode[1];
					$start_year = $start_range_explode[0];
					
					$end_date = $end_range_explode[2];
					$end_month = $end_range_explode[1];
					$end_year = $end_range_explode[0];
					
					if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
					{
						$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
						$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					    $date_set_start = $start_date.".".$start_month.".".$start_year;
					    $date_set_end = $end_date.".".$end_month.".".$end_year;
					}
				}
			}
			
			if(isset($date_set_end))
			{
			
			}
			else
			{
				$date_set_end   = '';
				$date_set_start = '';
			}
		 
		 $order_sales = 'Sale.s_date desc, Sale.s_time desc';
		 $group_sales = 'Sale.s_date,Sale.s_u_id';
		 
		 $get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>$order_sales,'group'=>$group_sales,'fields'=>array('Sale.s_u_id','sum(Sale.card_sale_count) as total_card','(Sale.s_date) as sale_date','sum(Sale.s_total_sales) as total_sales','sum(Sale.s_total_purchase) as total_purchase',"CONCAT(User.fname,' ',User.lname) as retailer_name")));
    			
		 $total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount_sale',
														));
            
			$total_purchase = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_purchase) as total_amount_purchase',
														));
														
			$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
			$card_count = 0;
			if(!empty($card_sale_count[0]['total_card']))
			{
				$card_count = $card_sale_count[0]['total_card'];
			}
            // Total Sales
			if(!empty($total_sales[0]['total_amount_sale']))
			{
				$total_sales_amount = $total_sales[0]['total_amount_sale'];
			}
			else
			{
				$total_sales_amount = 0.00;
			}
			// Total Purchase
			if(!empty($total_purchase[0]['total_amount_purchase']))
			{
				$total_purchase_amount = $total_purchase[0]['total_amount_purchase'];
			}
			else
			{
				$total_purchase_amount = 0.00;
			}
			
			$this->set('retailer_id',$retailer_id);
			$this->set('retailer_list',$get_retailer_data);
			
			$this->set('get_sales_data',$get_sales_data);
			$this->set('total_sales_amount',$total_sales_amount);
			$this->set('total_purchase_amount',$total_purchase_amount);
			
			$this->set('card_count',$card_count);
			$this->set('date_set_end',$date_set_end);
			$this->set('date_set_start',$date_set_start);
			
	 }

   public function  admin_daily_sales_excel($retailer_id = 0,$start_range = 0,$end_range = 0,$sales_user_id=0,$sales_date=0){
		$this->admin_redirect_to_dashboard_mediator();
  		ini_set('max_execution_time', 0);
		// Create new PHPExcel object
		$this->loadModel('Card');
   	    $this->loadModel('Sale');
   	    $this->loadModel('User');
			
		$login_user = $this->Auth->User('id');
		/* Getting Sales Conditions */
		$sales_conditions = array();
		// If Any Retailer is Selected
		if($retailer_id != 0)
		{
			$sales_conditions['s_u_id'] = $retailer_id;
		}
		else if($sales_date == 0 && $sales_user_id == 0)
		{  
			/* Getting Retailer data*/
			$retailer_conditions = array();
			$retailer_conditions['User.added_by'] = $login_user;
			$retailer_conditions['User.status'] = array('1','2');
			$fields_retailer = array('User.id','User.full_name');
			$get_retailer_data = $this->User->find('list',array('conditions'=>$retailer_conditions,'fields'=>$fields_retailer));
			
			$retailer_list = array();
			foreach($get_retailer_data as $k=>$v)
			{
				$retailer_list[]= $k;
			}
			
			//If No Retailer Exists
			if(empty($retailer_list))
			{
				$sales_conditions['s_u_id'] = 0;
			}
			else
			{
				 // If Retailer Exists
			$sales_conditions['s_u_id'] = $retailer_list;
			}
		}
			
		$total_sales_amount = 0.00;
		if($start_range != 0 && $end_range != 0 && $sales_date == 0 && $sales_user_id == 0)
		{
			$start_range_explode = explode('-',$start_range);
			$end_range_explode = explode('-',$end_range);
			
			if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
			{   
				$start_date = $start_range_explode[2];
				$start_month = $start_range_explode[1];
				$start_year = $start_range_explode[0];
				
				$end_date = $end_range_explode[2];
				$end_month = $end_range_explode[1];
				$end_year = $end_range_explode[0];
				
				if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
				{
					$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
					$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					$date_set_start = $start_date.".".$start_month.".".$start_year;
					$date_set_end = $end_date.".".$end_month.".".$end_year;
				}
			}
		}
		
		if($sales_user_id != 0 && $sales_date != 0)
		{
			$sales_conditions['s_u_id'] = $sales_user_id;
			$sales_conditions['s_date'] = $sales_date;
		}
		
		
        $order_sales = 'Sale.s_date desc, Sale.s_time desc';
		$group_sales = 'Sale.s_date,Sale.s_u_id';
		 
		 $get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>$order_sales,'group'=>$group_sales,'fields'=>array('sum(Sale.card_sale_count) as total_card','(Sale.s_date) as sale_date','sum(Sale.s_total_sales) as total_sales','sum(Sale.s_total_purchase) as total_purchase',"CONCAT(User.fname,' ',User.lname) as retailer_name",'User.id')));
		
		$total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount',
														));
		$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
		$total_purchase = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_purchase) as total_amount_purchase',
														));
		$card_count = 0;
		if(!empty($card_sale_count[0]['total_card']))
		{
			$card_count = $card_sale_count[0]['total_card'];
		}

		if(!empty($total_sales[0]['total_amount']))
		{
			$total_sales_amount = $total_sales[0]['total_amount'];
		}
		else
		{
			$total_sales_amount = 0.00;
		}

        if(!empty($total_purchase[0]['total_amount_purchase']))
		{
			$total_purchase_amount = $total_purchase[0]['total_amount_purchase'];
		}
		else
		{
			$total_purchase_amount = 0.00;
		}
	    
		//error_reporting(E_ALL);
		$objPHPExcel = new PHPExcel();
		
		// Setting Column Width
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		
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
		$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setWrapText(true);

		$reports_of = "Daily Sales Report";
		if($retailer_id != 0)
		{
			if(isset($get_sales_data[0][0]['retailer_name']))
			{
				$retailer_name = $get_sales_data[0][0]['retailer_name'];
			}
			else
			{
				$get_ret_name = $this->User->find('first',array('conditions'=>array('id'=>$retailer_id),'fields'=>'full_name'));
			    if($get_ret_name)
				$retailer_name = $get_ret_name['User']['full_name'];
			}
			
			if(!empty($retailer_name))
			{
				if(strlen($retailer_name) > 30)
				$retailer_name = substr($retailer_name,0,30)."..";
				$reports_of = 'Daily Sales Report Of Retailer: '.ucwords($retailer_name);
			}
		}
		
		if($retailer_id == 0 && $sales_date != 0 && $sales_user_id !=0)
		{
			$retailer_id = $get_sales_data[0]['User']['id'];
		    $ret_name = $this->User->find('first',array('conditions'=>array('id'=>$retailer_id),'fields'=>'full_name'));
		    if(!empty($ret_name))
			{
				if(strlen($ret_name['User']['full_name']) > 30)
				$ret_name['User']['full_name'] = substr($ret_name['User']['full_name'],0,30)."..";
				$reports_of = 'Daily Sales Report Of Retailer: '.ucwords($ret_name['User']['full_name']);
			}
		}
		
		$objPHPExcel->getActiveSheet()->setCellValue('A2',$reports_of);			
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		
		$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
		
		$heading = 4;
		$FirstItemNumber = $heading + 2;

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,'S.No');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,"Retailer");
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,"Date");
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,"Quantity");
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$heading,"Total Purchase(€)");
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$heading,"Total Sales(€)");

		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':F'.$heading)->getFont()->setBold(true);;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':F'.$heading)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':F'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		
		$serial_counter = 0;
		foreach($get_sales_data as $keynew=>$val)
		{
			$serial_counter++;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,ucwords($val['0']['retailer_name']));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,date('d.m.Y',strtotime($val['Sale']['sale_date'])));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,$val[0]['total_card']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,number_format($val[0]['total_purchase'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,number_format($val[0]['total_sales'],2));
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);

			$FirstItemNumber++;
		}
		
		// Setting Last Line Style For Net Total
		$FirstItemNumber++;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getFont()->setBold(True)->setSize(13);

        // Merging Column
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':C'.$FirstItemNumber);

		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
  		$objPHPExcel->getActiveSheet()->getStyle('F'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
   
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,'Net Quantity');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,$card_count);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,$total_purchase_amount);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,$total_sales_amount);
		
		// Set page margin
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);

        $file_name = 'DailySales'.$login_user.'.xls';    
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

   public function admin_profit_profile($retailer_id =0,$card_id = 0,$start_range = NULL ,$end_range =NULL,$cat_id = 0 ,$sub_cat_id =0){
			$this->admin_redirect_to_dashboard_mediator();
			$this->set("title_for_layout",__('Profitability Report'));	
	        
			$login_user = $this->Auth->User('id');
			
			$this->loadModel('Card');
   	        $this->loadModel('Sale');
			$this->loadModel('Category');
            
            
            /* 2 JAN 2015 : Category and subcategory List */
            $res = $this->Category->find('list',array(
                'conditions' => array('cat_parent_id'=>null),
                'fields' => array('cat_id','cat_title'),
                'recursive'	 => -1,
                'order'=>'cat_title asc'
            ));
			
            foreach($res as $k =>$v)
			$res[$k] = ucwords(strtolower($v));
			
            $this->set('cateList',$res);
            $this->set('selected_cat',$cat_id);
            
            if(!empty($cat_id) && $cat_id > 0){
                $subCatConditions = array();
                //$subCatConditions['NOT']['cat_status'] = 2;
                $subCatConditions['cat_parent_id <>'] = null;

                if (isset($cat_id) && !empty($cat_id)) {
                    $this->set('cat_id', $cat_id);
                    $subCatConditions['cat_parent_id'] = $cat_id;
                }

                $resSubCat = $this->Category->find('list', array(
                    'conditions' => $subCatConditions,
                    'fields' => array('cat_id', 'cat_title'),
                    'recursive' => -1,
                    'order' => 'cat_title asc'
                ));
                
				foreach($resSubCat as $k =>$v)
     			$resSubCat[$k] = ucwords(strtolower($v));  
        
		        $this->set('subCateList',$resSubCat);
                $this->set('selected_sub_cat',$sub_cat_id);
            }
            /* END Category and subcategory Listing */
			
			/* Get Card List*/
			
			$card_conditions = array();
			//$card_conditions['c_status'] = 1;

            if($sub_cat_id > 0 && $cat_id > 0){
                $card_conditions['c_cat_id'] = $sub_cat_id;
            }else if($cat_id > 0){
                $card_conditions['c_cat_id'] = array_keys($resSubCat);
            }
			
			$order = 'c_title asc';
			$fields = array('c_id','c_title');
			$get_cards = $this->Card->find('list',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$fields));
            
			$all_cards =array();
			foreach($get_cards as $k=>$v)
			{
				$all_cards[$k] = ucwords($v);
			}
			
			/* Getting Retailer data*/
			$retailer_conditions = array();
			$retailer_conditions['User.added_by'] = $login_user;
			$retailer_conditions['User.status'] = array('1','2');
			$fields_retailer = array('User.id','User.full_name');
			$order_retailer = 'fname,lname asc';
			$get_retailer_data = $this->User->find('list',array('conditions'=>$retailer_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
			
			$retailer_list = array();
			foreach($get_retailer_data as $k=>$v)
			{
				$retailer_list[]= $k;
			}
			
			/* Getting Sales Conditions */
			$sales_conditions = array();
			// If Any Retailer is Selected
			if($retailer_id != 0)
			{
				$sales_conditions['s_u_id'] = $retailer_id;
			}
			else
			{  
				//If No Retailer Exists
				if(empty($retailer_list))
				{
					$sales_conditions['s_u_id'] = 0;
				}
				else
				{
					 // If Retailer Exists
				$sales_conditions['s_u_id'] = $retailer_list;
				}
			}

			if($card_id != 0)
			{
				$sales_conditions['s_c_id'] = $card_id;
			}
			else
			{
                $sales_conditions['s_c_id'] = array_keys($get_cards);
            }
			
			
			if($start_range != NULL && $end_range != NULL)
			{
				$start_range_explode = explode('-',$start_range);
				$end_range_explode = explode('-',$end_range);
				
				if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
				{   
				    $start_date = $start_range_explode[2];
					$start_month = $start_range_explode[1];
					$start_year = $start_range_explode[0];
					
					$end_date = $end_range_explode[2];
					$end_month = $end_range_explode[1];
					$end_year = $end_range_explode[0];
					
					if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
					{
						$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
						$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					    $date_set_start = $start_date.".".$start_month.".".$start_year;
					    $date_set_end = $end_date.".".$end_month.".".$end_year;
					}
				}
			}
			
			if(isset($date_set_end))
			{
			
			}
			else
			{
				$date_set_end   = '';
				$date_set_start = '';
			}

			$get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>'Sale.s_date desc, Sale.s_time desc','fields'=>array('Sale.*','Card.c_title',"CONCAT(User.fname,' ',User.lname) as retailer_name")));
			
		   	$total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount_sale',
														));
            
			$total_purchase = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_purchase) as total_amount_purchase',
														));
														
			$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
			$card_count = 0;
			if(!empty($card_sale_count[0]['total_card']))
			{
				$card_count = $card_sale_count[0]['total_card'];
			}
            // Total Sales
			if(!empty($total_sales[0]['total_amount_sale']))
			{
				$total_sales_amount = $total_sales[0]['total_amount_sale'];
			}
			else
			{
				$total_sales_amount = 0.00;
			}
			// Total Purchase
			if(!empty($total_purchase[0]['total_amount_purchase']))
			{
				$total_purchase_amount = $total_purchase[0]['total_amount_purchase'];
			}
			else
			{
				$total_purchase_amount = 0.00;
			}
			
			$this->set('retailer_id',$retailer_id);
			$this->set('card_id',$card_id);
			$this->set('retailer_list',$get_retailer_data);
			
			$this->set('all_cards',$all_cards);
			$this->set('get_sales_data',$get_sales_data);
			
			$this->set('get_sales_data',$get_sales_data);
			$this->set('total_sales_amount',$total_sales_amount);
			$this->set('total_purchase_amount',$total_purchase_amount);
			
			$this->set('card_count',$card_count);
			$this->set('date_set_end',$date_set_end);
			$this->set('date_set_start',$date_set_start);
	 }

   public function  admin_profit_profile_excel($cat_id=0,$sub_cat_id = 0,$retailer_id = 0,$card_id = 0,$start_range = 0,$end_range = 0,$sales_id=0){
  		$this->admin_redirect_to_dashboard_mediator();
		ini_set('max_execution_time', 0);
		// Create new PHPExcel object
		$this->loadModel('Card');
   	    $this->loadModel('Sale');
   	    $this->loadModel('User');
   	     $this->loadModel('Category');
			
		$login_user = $this->Auth->User('id');

		$res = $this->Category->find('list',array(
				'conditions' => array('cat_parent_id'=>null),
				'fields' => array('cat_id','cat_title'),
				'recursive'	 => -1,
				'order'=>'cat_title asc'
		));
		
		if(!empty($cat_id) && $cat_id > 0){
				$subCatConditions = array();
				//$subCatConditions['NOT']['cat_status'] = 2;
				$subCatConditions['cat_parent_id <>'] = null;

				if (isset($cat_id) && !empty($cat_id)) {
						$this->set('cat_id', $cat_id);
						$subCatConditions['cat_parent_id'] = $cat_id;
				}

				$resSubCat = $this->Category->find('list', array(
						'conditions' => $subCatConditions,
						'fields' => array('cat_id', 'cat_title'),
						'recursive' => -1,
						'order' => 'cat_title asc'
				));
				
		}
			
		/* Get Card List*/
		$card_conditions = array();
		//$card_conditions['c_status'] = 1;
        
		if($sub_cat_id > 0 && $cat_id > 0)
		{
			$card_conditions['c_cat_id'] = $sub_cat_id;
		}
		else if($cat_id > 0)
		{
			$card_conditions['c_cat_id'] = array_keys($resSubCat);
		}

		$order = 'c_title asc';
		$fields = array('c_id','c_title');
		$get_cards = $this->Card->find('list',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$fields));
		//prd($card_conditions);
        
		$all_cards =array();
		foreach($get_cards as $k=>$v)
		{
			$all_cards[$k] = ucwords($v);
		}



		/* Getting Sales Conditions */
		$sales_conditions = array();
		// If Any Retailer is Selected
		if($retailer_id != 0)
		{
			$sales_conditions['s_u_id'] = $retailer_id;
		}
		else if($sales_id == 0)
		{  
			/* Getting Retailer data*/
			$retailer_conditions = array();
			$retailer_conditions['User.added_by'] = $login_user;
			$retailer_conditions['User.status'] = array('1','2');
			$fields_retailer = array('User.id','User.full_name');
			$get_retailer_data = $this->User->find('list',array('conditions'=>$retailer_conditions,'fields'=>$fields_retailer));
			
			$retailer_list = array();
			foreach($get_retailer_data as $k=>$v)
			{
				$retailer_list[]= $k;
			}
			
			//If No Retailer Exists
			if(empty($retailer_list))
			{
				$sales_conditions['s_u_id'] = 0;
			}
			else
			{
				 // If Retailer Exists
			$sales_conditions['s_u_id'] = $retailer_list;
			}
		}
			
		$total_sales_amount = 0.00;
		if($card_id != 0)
		{
			$sales_conditions['s_c_id'] = $card_id;
		}
		else if($sales_id ==0)
		{
			$sales_conditions['s_c_id'] = array_keys($get_cards);
		}
			
		if($start_range != 0 && $end_range != 0 && $sales_id == 0)
		{
			$start_range_explode = explode('-',$start_range);
			$end_range_explode = explode('-',$end_range);
			
			if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
			{   
				$start_date = $start_range_explode[2];
				$start_month = $start_range_explode[1];
				$start_year = $start_range_explode[0];
				
				$end_date = $end_range_explode[2];
				$end_month = $end_range_explode[1];
				$end_year = $end_range_explode[0];
				
				if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
				{
					$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
					$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					$date_set_start = $start_date.".".$start_month.".".$start_year;
					$date_set_end = $end_date.".".$end_month.".".$end_year;
				}
			}
		}
		
		if($sales_id != 0)
		{
			$sales_conditions['s_id'] = $sales_id;
		}
		
		//prd($sales_conditions);
		$get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>'Sale.s_date desc, Sale.s_time desc','fields'=>array('Sale.*','Card.c_title',"CONCAT(User.fname,' ',User.lname) as retailer_name",'User.id')));
			
		   	$total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount_sale',
														));
            
			$total_purchase = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_purchase) as total_amount_purchase',
														));
														
			$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
			$card_count = 0;
			if(!empty($card_sale_count[0]['total_card']))
			{
				$card_count = $card_sale_count[0]['total_card'];
			}
            // Total Sales
			if(!empty($total_sales[0]['total_amount_sale']))
			{
				$total_sales_amount = $total_sales[0]['total_amount_sale'];
			}
			else
			{
				$total_sales_amount = 0.00;
			}
			// Total Purchase
			if(!empty($total_purchase[0]['total_amount_purchase']))
			{
				$total_purchase_amount = $total_purchase[0]['total_amount_purchase'];
			}
			else
			{
				$total_purchase_amount = 0.00;
			}
		//error_reporting(E_ALL);
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(9);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(11);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(9);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
		
		$objPHPExcel->getProperties()->setCreator("Calling Cards")->setLastModifiedBy("Calling Cards")->setTitle("Office 2007 XLSX Document")
																 ->setSubject("Calling Cards Profit Report")
																 ->setDescription("Calling Cards")
																 ->setKeywords("office 2007 openxml php")
																 ->setCategory("Export To Excel");		
		
		$objPHPExcel->setActiveSheetIndex(0);		
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(9);
		
		// Title of the Report		
		$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('A2:J2');
		$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getAlignment()->setWrapText(true);

		$reports_of = "Profitability Report";
		if($retailer_id != 0)
		{
			if(isset($get_sales_data[0][0]['retailer_name']))
			{
				$retailer_name = $get_sales_data[0][0]['retailer_name'];
			}
			else
			{
				$get_ret_name = $this->User->find('first',array('conditions'=>array('id'=>$retailer_id),'fields'=>'full_name'));
			    if($get_ret_name)
				$retailer_name = $get_ret_name['User']['full_name'];
			}
			
			if(!empty($retailer_name))
			{
				if(strlen($retailer_name) > 40)
				$retailer_name = substr($retailer_name,0,40)."..";
				$reports_of = 'Profitability Report Of Retailer: '.ucwords($retailer_name);
			}
		}
		
		if($retailer_id == 0 && $sales_id != 0)
		{
			$retailer_id = $get_sales_data[0]['User']['id'];
		    $ret_name = $this->User->find('first',array('conditions'=>array('id'=>$retailer_id),'fields'=>'full_name'));
		    if(!empty($ret_name))
			{
				if(strlen($ret_name['User']['full_name']) > 40)
				$ret_name['User']['full_name'] = substr($ret_name['User']['full_name'],0,40)."..";
				$reports_of = 'Profitability Report Of Retailer: '.ucwords($ret_name['User']['full_name']);
			}
		}
		
		$objPHPExcel->getActiveSheet()->setCellValue('A2',$reports_of);			
		$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		
		$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
		
		$heading = 4;
		$FirstItemNumber = $heading + 2;

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,'S.No');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,"Retailer");
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,"Card Name");
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,"Purchase Price(€)");
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$heading,"Selling Price(€)");
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$heading,"Quantity");
 		$objPHPExcel->getActiveSheet()->setCellValue('G'.$heading,'Total Purchase(€)');
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$heading,"Total Sales(€)");
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$heading,"Profit(€)");
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$heading,'Date');
         

		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':J'.$heading)->getFont()->setBold(true);;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':J'.$heading)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':J'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		$serial_counter = 0;
		foreach($get_sales_data as $keynew=>$val)
		{
			$serial_counter++;
   
		    $objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,ucwords($val['0']['retailer_name']));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,ucwords($val['Card']['c_title']));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,number_format($val['Sale']['s_purchase_price'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,number_format($val['Sale']['s_selling_price'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,$val['Sale']['card_sale_count']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$FirstItemNumber,number_format($val['Sale']['s_total_purchase'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$FirstItemNumber,number_format($val['Sale']['s_total_sales'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$FirstItemNumber,number_format($val['Sale']['s_total_sales']-$val['Sale']['s_total_purchase'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$FirstItemNumber,date('d.m.Y',strtotime($val['Sale']['s_date']))." ".$val['Sale']['s_time']);
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':J'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':J'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':J'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':J'.$FirstItemNumber)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':J'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);

			$FirstItemNumber++;
		}
		
		// Setting Last Line Style For Net Total
		$FirstItemNumber++;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':J'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':J'.$FirstItemNumber)->getFont()->setBold(True)->setSize(13);

        // Merging Column
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':E'.$FirstItemNumber);

		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$FirstItemNumber.':I'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,'Net Quantity');
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,$card_count);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$FirstItemNumber,$total_purchase_amount);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$FirstItemNumber,$total_sales_amount);

		$objPHPExcel->getActiveSheet()->setCellValue('I'.$FirstItemNumber,($total_sales_amount - $total_purchase_amount));
		
		// Set page margin
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);

		$file_name = "ProfitabilityReport".$login_user.".xls";
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


   public function admin_sales_report_distributor($mediator_id =0,$retailer_id =0,$card_id = 0,$start_range = NULL ,$end_range =NULL,$cat_id = 0, $sub_cat_id = 0){
			
			$this->admin_redirect_to_dashboard_distributor();
			$this->set("title_for_layout",__('Sales Report'));	
			$login_user = $this->Auth->User('id');
			
			$this->loadModel('Card');
			$this->loadModel('Sale');
			$this->loadModel('Category');
            
            
			/* 2 JAN 2015 : Category and subcategory List */
			$res = $this->Category->find('list',array(
					'conditions' => array('cat_parent_id'=>null),
					'fields' => array('cat_id','cat_title'),
					'recursive'	 => -1,
					'order'=>'cat_title asc'
			));
			
			foreach($res as $k => $v)
    		$res[$k] = ucwords(strtolower($v));
			
			$this->set('cateList',$res);
			$this->set('selected_cat',$cat_id);
			
            if(!empty($cat_id) && $cat_id > 0){
                $subCatConditions = array();
               // $subCatConditions['Category.cat_status'] = 1;
                $subCatConditions['Category.cat_parent_id <>'] = null;

                if (isset($cat_id) && !empty($cat_id)) {
                    $this->set('cat_id', $cat_id);
                    $subCatConditions['Category.cat_parent_id'] = $cat_id;
                }

                $resSubCat = $this->Category->find('list', array(
                    'conditions' => $subCatConditions,
                    'fields' => array('cat_id', 'cat_title'),
                    'recursive' => -1,
                    'order' => 'cat_title asc'
                ));

				foreach($resSubCat as $k => $v)
        		$resSubCat[$k] = ucwords(strtolower($v));
        
		        $this->set('subCateList',$resSubCat);
                $this->set('selected_sub_cat',$sub_cat_id);
            }
            /* END Category and subcategory Listing */
            
            
			
			/* Get Card List*/
			$card_conditions = array();
			//$card_conditions['c_status'] = 1;
            
            if($sub_cat_id > 0 && $cat_id > 0){
                $card_conditions['c_cat_id'] = $sub_cat_id;
            }else if($cat_id > 0){
                $card_conditions['c_cat_id'] = array_keys($resSubCat);
            }
			
			$order = 'c_title asc';
			$fields = array('c_id','c_title');
			$get_cards = $this->Card->find('list',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$fields));
            
			$all_cards =array();
			foreach($get_cards as $k=>$v)
			{
				$all_cards[$k] = ucwords($v);
			}
			
			$retailer_list = array();

			/* Getting Mediator  data*/
			$mediator_conditions = array();
			$mediator_conditions['User.added_by'] = $login_user;
			$mediator_conditions['User.status'] = array('1','2');
			$mediator_conditions['User.role_id'] = 2;
			$fields_mediator = array('User.id','User.full_name');
			$order_mediator = 'fname,lname asc';
			$get_mediator_data = $this->User->find('list',array('conditions'=>$mediator_conditions,'fields'=>$fields_mediator,'order'=>$order_mediator));
            foreach($get_mediator_data as $k=>$v)
			{
				$get_mediator_data[$k] = ucwords(strtolower($v));
			}
			 
			if($mediator_id != 0)
			{
								
				$retailers_conditions = array();
				$retailers_conditions['User.status'] = array('1','2');
				$retailers_conditions['User.role_id'] = 3;
				$fields_retailer = array('User.id','User.full_name');
				$order_retailer = 'fname,lname asc';
				$retailers_conditions['User.added_by'] = $mediator_id;
				
				$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
				foreach($get_retailer_data as $key =>$value)	
				$retailer_list[]= $key;
				
				foreach($get_retailer_data as $k=>$v)
				{
					$get_retailer_data[$k] = ucwords(strtolower($v));
				}
				
			}
			else
			{
				$retailers_conditions = array();
				$retailers_conditions['User.status'] = array('1','2');
				$retailers_conditions['User.role_id'] = 3;
				$fields_retailer = array('User.id','User.full_name');
				$order_retailer = 'fname,lname asc';
				
				$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
				
				foreach($get_retailer_data as $key =>$value)
				{	
					$retailer_list[]= $key;
					$get_retailer_data[$key] = ucwords(strtolower($value));
				}
			}
			
			
			/* Getting Sales Conditions */
			$sales_conditions = array();
			// If Any Retailer is Selected
			if($retailer_id != 0)
			{
				$sales_conditions['s_u_id'] = $retailer_id;
			}
			else
			{  
				//If No Retailer Exists
				if(empty($retailer_list))
				{
					$sales_conditions['s_u_id'] = 0;
				}
				else
				{
					 // If Retailer Exists
				$sales_conditions['s_u_id'] = $retailer_list;
				}
			}
            
            if($card_id != 0)
			{
				$sales_conditions['s_c_id'] = $card_id;
			}else{
                $sales_conditions['s_c_id'] = array_keys($get_cards);
            }
			
			if($start_range != NULL && $end_range != NULL)
			{
				$start_range_explode = explode('-',$start_range);
				$end_range_explode = explode('-',$end_range);
				
				if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
				{   
				    $start_date = $start_range_explode[2];
					$start_month = $start_range_explode[1];
					$start_year = $start_range_explode[0];
					
					$end_date = $end_range_explode[2];
					$end_month = $end_range_explode[1];
					$end_year = $end_range_explode[0];
					
					if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
					{
						$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
						$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					    $date_set_start = $start_date.".".$start_month.".".$start_year;
					    $date_set_end = $end_date.".".$end_month.".".$end_year;
					}
				}
			}
			
			if(isset($date_set_end))
			{
			
			}
			else
			{
				$date_set_end   = '';
				$date_set_start = '';
			}

		 $get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>'Sale.s_date desc, Sale.s_time desc','fields'=>array('Sale.*','Card.c_title',"CONCAT(User.fname,' ',User.lname) as retailer_name")));
		 $total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount',
														));
		 $card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
			$card_count = 0;
			if(!empty($card_sale_count[0]['total_card']))
			{
				$card_count = $card_sale_count[0]['total_card'];
			}

			if(!empty($total_sales[0]['total_amount']))
			{
				$total_sales_amount = $total_sales[0]['total_amount'];
			}
			else
			{
				$total_sales_amount = 0.00;
			}

			$this->set('retailer_id',$retailer_id);
		    $this->set('mediator_id',$mediator_id);

			if($mediator_id != 0)
			{
				$this->set('retailer_list',$get_retailer_data);
			}
			
			$this->set('mediator_list',$get_mediator_data);
			
			$this->set('card_id',$card_id);
			$this->set('all_cards',$all_cards);
			$this->set('get_sales_data',$get_sales_data);
			$this->set('total_sales_amount',$total_sales_amount);
			$this->set('card_count',$card_count);
			$this->set('date_set_end',$date_set_end);
			$this->set('date_set_start',$date_set_start);
	 }
   
   public function  admin_sales_report_excel_distributor($cat_id=0,$sub_cat_id = 0,$mediator_id = 0,$retailer_id = 0,$card_id = 0,$start_range = 0,$end_range = 0,$sales_id=0){
  		$this->admin_redirect_to_dashboard_distributor();
  		ini_set('max_execution_time', 0);
		// Create new PHPExcel object
		$this->loadModel('Card');
   		$this->loadModel('Sale');
		$this->loadModel('Category');
			
		$login_user = $this->Auth->User('id');
		
		$res = $this->Category->find('list',array(
					'conditions' => array('cat_parent_id'=>null),
					'fields' => array('cat_id','cat_title'),
					'recursive'	 => -1,
					'order'=>'cat_title asc'
			));
			
			$this->set('cateList',$res);
			$this->set('selected_cat',$cat_id);
			
			if(!empty($cat_id) && $cat_id > 0){
					$subCatConditions = array();
					//$subCatConditions['Category.cat_status'] =1;
					$subCatConditions['Category.cat_parent_id <>'] = null;

					if (isset($cat_id) && !empty($cat_id)) {
							$this->set('cat_id', $cat_id);
							$subCatConditions['Category.cat_parent_id'] = $cat_id;
					}

					$resSubCat = $this->Category->find('list', array(
							'conditions' => $subCatConditions,
							'fields' => array('cat_id', 'cat_title'),
							'recursive' => -1,
							'order' => 'cat_title asc'
					));

					$this->set('subCateList',$resSubCat);
					$this->set('selected_sub_cat',$sub_cat_id);
			}
			/* END Category and subcategory Listing */
			
            
			
			/* Get Card List*/
			$card_conditions = array();
			//$card_conditions['c_status'] = 1;
            
			if($sub_cat_id > 0 && $cat_id > 0)
			{
					$card_conditions['c_cat_id'] = $sub_cat_id;
			}
			else if($cat_id > 0)
			{
					$card_conditions['c_cat_id'] = array_keys($resSubCat);
			}
			
			$order = 'c_title asc';
			$fields = array('c_id','c_title');
			$get_cards = $this->Card->find('list',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$fields));
            
			$all_cards =array();
			foreach($get_cards as $k=>$v)
			{
				$all_cards[$k] = ucwords($v);
			}
			
			$retailer_list = array();

			/* Getting Mediator  data*/
			$mediator_conditions = array();
			$mediator_conditions['User.added_by'] = $login_user;
			$mediator_conditions['User.status'] = array('1','2');
			$mediator_conditions['User.role_id'] = 2;
			$fields_mediator = array('User.id','User.full_name');
			$order_mediator = 'fname,lname asc';
			$get_mediator_data = $this->User->find('list',array('conditions'=>$mediator_conditions,'fields'=>$fields_mediator,'order'=>$order_mediator));
            foreach($get_mediator_data as $k=>$v)
			{
				$get_mediator_data[$k] = ucwords($v);
			}
			 
		if($mediator_id != 0)
		{
			$retailers_conditions = array();
			$retailers_conditions['User.status'] = array('1','2');
			$retailers_conditions['User.role_id'] = 3;
			$fields_retailer = array('User.id','User.full_name');
			$order_retailer = 'fname,lname asc';
			$retailers_conditions['User.added_by'] = $mediator_id;
			
			$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
			foreach($get_retailer_data as $key =>$value)	
				$retailer_list[]= $key;
		}else
		{
			$retailers_conditions = array();
			$retailers_conditions['User.status'] = array('1','2');
			$retailers_conditions['User.role_id'] = 3;
			$fields_retailer = array('User.id','User.full_name');
			$order_retailer = 'fname,lname asc';
			
			$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
			foreach($get_retailer_data as $key =>$value)	
			$retailer_list[]= $key;
		}
			
		/* Getting Sales Conditions */
			$sales_conditions = array();
			// If Any Retailer is Selected
			if($retailer_id != 0)
			{
				$sales_conditions['s_u_id'] = $retailer_id;
			}
			else
			{  
				//If No Retailer Exists
				if(empty($retailer_list))
				{
					$sales_conditions['s_u_id'] = 0;
				}
				else
				{
					 // If Retailer Exists
				$sales_conditions['s_u_id'] = $retailer_list;
				}
			}
            
      if($card_id != 0){
				$sales_conditions['s_c_id'] = $card_id;
			}else{
					$sales_conditions['s_c_id'] = array_keys($get_cards);
			}
			
			
		if($start_range != 0 && $end_range != 0 && $sales_id == 0)
		{
			$start_range_explode = explode('-',$start_range);
			$end_range_explode = explode('-',$end_range);
			
			if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
			{   
				$start_date = $start_range_explode[2];
				$start_month = $start_range_explode[1];
				$start_year = $start_range_explode[0];
				
				$end_date = $end_range_explode[2];
				$end_month = $end_range_explode[1];
				$end_year = $end_range_explode[0];
				
				if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
				{
					$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
					$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					$date_set_start = $start_month."/".$start_date."/".$start_year;
					$date_set_end = $end_month."/".$end_date."/".$end_year;
				}
			}
		}
		
		if($sales_id != 0)
		{
			$sales_conditions['s_id'] = $sales_id;
		}
		
    	$get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>'Sale.s_date desc, Sale.s_time desc','fields'=>array('Sale.*','Card.c_title',"CONCAT(User.fname,' ',User.lname) as retailer_name",'User.added_by')));

		$total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount',
														));
		
		$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
		$card_count = 0;
		if(!empty($card_sale_count[0]['total_card']))
		{
			$card_count = $card_sale_count[0]['total_card'];
		}

		if(!empty($total_sales[0]['total_amount']))
		{
			$total_sales_amount = $total_sales[0]['total_amount'];
		}
		else
		{
			$total_sales_amount = 0.00;
		}
		
		//error_reporting(E_ALL);
		$objPHPExcel = new PHPExcel();
		
		//Setting Column Width
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
   		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		
		$objPHPExcel->getProperties()->setCreator("Calling Cards")->setLastModifiedBy("Calling Cards")->setTitle("Office 2007 XLSX Document")
																 ->setSubject("Calling Cards")
																 ->setDescription("Calling Cards")
																 ->setKeywords("office 2007 openxml php")
																 ->setCategory("Export To Excel");		
		
		$objPHPExcel->setActiveSheetIndex(0);		
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		
		// Title of the Report		
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
       
		$med_name = array();
		$retailer_name = array();
		$reports_of = "Sales Report";
        if($retailer_id != 0)
		{
			if(isset($get_sales_data[0][0]['retailer_name']))
			{
				$retailer_name = $get_sales_data[0][0]['retailer_name'];
			}
			else
			{
				$ret_name = $this->User->find('first',array('conditions'=>array('id'=>$retailer_id),'fields'=>'full_name'));
				if(!empty($ret_name))
			    $retailer_name = $ret_name['User']['full_name'];
			}
			if(isset($get_sales_data[0]['User']['added_by']))
			$med_name = $this->User->find('first',array('conditions'=>array('id'=>$get_sales_data[0]['User']['added_by']),'fields'=>'full_name'));
			else
			$med_name = $this->User->find('first',array('conditions'=>array('id'=>$mediator_id),'fields'=>'full_name'));
			
			if(!empty($med_name))
			{
				if(strlen($retailer_name) > 21)
				$retailer_name = substr($retailer_name,0,21)."..";
				
				if(strlen($med_name['User']['full_name']) > 21)
				$med_name['User']['full_name'] = substr($med_name['User']['full_name'],0,21)."..";
				
				$reports_of = 'Sales Report Of Mediator: '.ucwords($med_name['User']['full_name']).'  '.'Retailer :'.ucwords($retailer_name);
			}
		}
		else if($mediator_id != 0)
		{
			$med_name = $this->User->find('first',array('conditions'=>array('id'=>$mediator_id),'fields'=>'full_name'));
		    if(!empty($med_name))
			{
				if(strlen($med_name['User']['full_name']) > 40)
				$med_name['User']['full_name'] = substr($med_name['User']['full_name'],0,40)."..";

				$reports_of = 'Sales Report Of Mediator: '.ucwords($med_name['User']['full_name']);	
			}
		}
		
		if($retailer_id == 0 && $mediator_id == 0 && $sales_id !=0)
		{
			$mediator_id = $get_sales_data[0]['User']['added_by'];
		    $med_name = $this->User->find('first',array('conditions'=>array('id'=>$mediator_id),'fields'=>'full_name'));
		    if(!empty($med_name))
			{
				if(strlen($med_name['User']['full_name']) > 40)
				$med_name['User']['full_name'] = substr($med_name['User']['full_name'],0,40)."..";
                $retailer_name =  $get_sales_data[0][0]['retailer_name'];
				$reports_of = 'Sales Report Of Mediator: '.ucwords($med_name['User']['full_name']).'  '.'Retailer :'.ucwords($retailer_name);
			}
		}
		
		$objPHPExcel->getActiveSheet()->setCellValue('A2',$reports_of);
		$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setWrapText(true);
		
		$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
		
		$heading = 4;
		$FirstItemNumber = $heading + 2;

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,'S.No');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,"Retailer");
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,"Card Name");
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,"Selling Price(€)");
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$heading,"Quantity");
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$heading,"Date-Time");
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$heading,'Total Sales(€)');
		 

		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getFont()->setBold(true);;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		
		$serial_counter = 0;
		foreach($get_sales_data as $keynew=>$val)
		{
			$serial_counter++;
   
		    $objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,ucwords(strtolower($val['0']['retailer_name'])));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,ucwords(strtolower($val['Card']['c_title'])));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,number_format($val['Sale']['s_selling_price'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,$val['Sale']['card_sale_count']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,date('d.m.Y',strtotime($val['Sale']['s_date']))." ".$val['Sale']['s_time']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$FirstItemNumber,number_format($val['Sale']['s_total_sales'],2));
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);

			$FirstItemNumber++;
		}
		
		// Setting Last Line Style For Net Total
		$FirstItemNumber++;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(True)->setSize(13);

        // Merging Column
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':D'.$FirstItemNumber);

		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,'Net Quantity');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,$card_count);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$FirstItemNumber,$total_sales_amount);
		
		// Set page margin
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);

        $file_name = 'SalesReport'.$login_user.'.xls';    
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

   public function admin_daily_sales_distributor($mediator_id =0,$retailer_id =0,$start_range = NULL ,$end_range =NULL){
			$this->admin_redirect_to_dashboard_distributor();
			$this->set("title_for_layout",__('Daily Sales Report'));	
	        $login_user = $this->Auth->User('id');
			
			$this->loadModel('User');
			$this->loadModel('Card');
			$this->loadModel('Sale');
			
		    /* Getting Mediator  data*/
			$mediator_conditions = array();
			$mediator_conditions['User.added_by'] = $login_user;
			$mediator_conditions['User.status'] = array('1','2');
			$mediator_conditions['User.role_id'] = 2;
			$fields_mediator = array('User.id','User.full_name');
			$order_mediator = 'fname,lname asc';
			$get_mediator_data = $this->User->find('list',array('conditions'=>$mediator_conditions,'fields'=>$fields_mediator,'order'=>$order_mediator));
            foreach($get_mediator_data as $k=>$v)
			{
				$get_mediator_data[$k] = ucwords(strtolower($v));
			}
			 
			if($mediator_id != 0)
			{
				$retailers_conditions = array();
				$retailers_conditions['User.status'] = array('1','2');
				$retailers_conditions['User.role_id'] = 3;
				$fields_retailer = array('User.id','User.full_name');
				$order_retailer = 'fname,lname asc';
				$retailers_conditions['User.added_by'] = $mediator_id;
				
				$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
				foreach($get_retailer_data as $key =>$value)
				{	
					$retailer_list[]= $key;
					$get_retailer_data[$key] = ucwords(strtolower($value));
				}
			}
			else
			{
				$retailers_conditions = array();
				$retailers_conditions['User.status'] = array('1','2');
				$retailers_conditions['User.role_id'] = 3;
				$fields_retailer = array('User.id','User.full_name');
				$order_retailer = 'fname,lname asc';
				
				$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
				foreach($get_retailer_data as $key =>$value)
				{	
					$retailer_list[]= $key;
				    $get_retailer_data[$key] = ucwords(strtolower($value));
				}
			}
			
			/* Getting Sales Conditions */
			$sales_conditions = array();
			// If Any Retailer is Selected
			if($retailer_id != 0)
			{
				$sales_conditions['s_u_id'] = $retailer_id;
			}
			else
			{  
				//If No Retailer Exists
				if(empty($retailer_list))
				{
					$sales_conditions['s_u_id'] = 0;
				}
				else
				{
					 // If Retailer Exists
				$sales_conditions['s_u_id'] = $retailer_list;
				}
			}
			
			$total_sales_amount = 0.00;
			if($start_range != NULL && $end_range != NULL)
			{
				$start_range_explode = explode('-',$start_range);
				$end_range_explode = explode('-',$end_range);
				
				if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
				{   
				    $start_date = $start_range_explode[2];
					$start_month = $start_range_explode[1];
					$start_year = $start_range_explode[0];
					
					$end_date = $end_range_explode[2];
					$end_month = $end_range_explode[1];
					$end_year = $end_range_explode[0];
					
					if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
					{
						$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
						$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					    $date_set_start = $start_date.".".$start_month.".".$start_year;
					    $date_set_end = $end_date.".".$end_month.".".$end_year;
					}
				}
			}
			
			if(isset($date_set_end))
			{
			
			}
			else
			{
				$date_set_end   = '';
				$date_set_start = '';
			}
		 
		 $order_sales = 'Sale.s_date desc, Sale.s_time desc';
		 $group_sales = 'Sale.s_date,Sale.s_u_id';
		 
		 $get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>$order_sales,'group'=>$group_sales,'fields'=>array('Sale.s_u_id','sum(Sale.card_sale_count) as total_card','(Sale.s_date) as sale_date','sum(Sale.s_total_sales) as total_sales','sum(Sale.s_total_purchase) as total_purchase',"CONCAT(User.fname,' ',User.lname) as retailer_name")));
    			
		 $total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount_sale',
														));
            
		 $total_purchase = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_purchase) as total_amount_purchase',
														));
														
		 $card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
		$card_count = 0;
			if(!empty($card_sale_count[0]['total_card']))
			{
				$card_count = $card_sale_count[0]['total_card'];
			}
            // Total Sales
			if(!empty($total_sales[0]['total_amount_sale']))
			{
				$total_sales_amount = $total_sales[0]['total_amount_sale'];
			}
			else
			{
				$total_sales_amount = 0.00;
			}
			// Total Purchase
			if(!empty($total_purchase[0]['total_amount_purchase']))
			{
				$total_purchase_amount = $total_purchase[0]['total_amount_purchase'];
			}
			else
			{
				$total_purchase_amount = 0.00;
			}
			
			$this->set('retailer_id',$retailer_id);
		    $this->set('mediator_id',$mediator_id);

			if($mediator_id != 0)
			{
				$this->set('retailer_list',$get_retailer_data);
			}
			$this->set('mediator_list',$get_mediator_data);
			
			$this->set('get_sales_data',$get_sales_data);
			$this->set('total_sales_amount',$total_sales_amount);
			$this->set('total_purchase_amount',$total_purchase_amount);
			
			$this->set('card_count',$card_count);
			$this->set('date_set_end',$date_set_end);
			$this->set('date_set_start',$date_set_start);
	 }
	
   public function admin_daily_sales_excel_distributor($mediator_id = 0,$retailer_id = 0,$start_range = 0,$end_range = 0,$sales_user_id=0,$sales_date = 0){
  		$this->admin_redirect_to_dashboard_distributor();
		ini_set('max_execution_time', 0);
		// Create new PHPExcel object
		$this->loadModel('Card');
   	    $this->loadModel('Sale');
		$this->loadModel('User');
			
		    $login_user = $this->Auth->User('id');
		    /* Getting Mediator  data*/
			$mediator_conditions = array();
			$mediator_conditions['User.added_by'] = $login_user;
			$mediator_conditions['User.status'] = array('1','2');
			$mediator_conditions['User.role_id'] = 2;
			$fields_mediator = array('User.id','User.full_name');
			$order_mediator = 'fname,lname asc';
			$get_mediator_data = $this->User->find('list',array('conditions'=>$mediator_conditions,'fields'=>$fields_mediator,'order'=>$order_mediator));
            foreach($get_mediator_data as $k=>$v)
			{
				$get_mediator_data[$k] = ucwords($v);
			}
			 
			if($mediator_id != 0)
			{
				$retailers_conditions = array();
				$retailers_conditions['User.status'] = array('1','2');
				$retailers_conditions['User.role_id'] = 3;
				$fields_retailer = array('User.id','User.full_name');
				$order_retailer = 'fname,lname asc';
				$retailers_conditions['User.added_by'] = $mediator_id;
				
				$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
				foreach($get_retailer_data as $key =>$value)	
				$retailer_list[]= $key;
			}
			else
			{
				$retailers_conditions = array();
				$retailers_conditions['User.status'] = array('1','2');
				$retailers_conditions['User.role_id'] = 3;
				$fields_retailer = array('User.id','User.full_name');
				$order_retailer = 'fname,lname asc';
				
				$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
				foreach($get_retailer_data as $key =>$value)	
				$retailer_list[]= $key;
			}
			/* Getting Sales Conditions */
			$sales_conditions = array();
			// If Any Retailer is Selected
			if($retailer_id != 0)
			{
				$sales_conditions['s_u_id'] = $retailer_id;
			}
			else
			{  
				//If No Retailer Exists
				if(empty($retailer_list))
				{
					$sales_conditions['s_u_id'] = 0;
				}
				else
				{
					 // If Retailer Exists
				$sales_conditions['s_u_id'] = $retailer_list;
				}
			}
			
			$total_sales_amount = 0.00;
			if($start_range != NULL && $end_range != NULL)
			{
				$start_range_explode = explode('-',$start_range);
				$end_range_explode = explode('-',$end_range);
				
				if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
				{   
				    $start_date = $start_range_explode[2];
					$start_month = $start_range_explode[1];
					$start_year = $start_range_explode[0];
					
					$end_date = $end_range_explode[2];
					$end_month = $end_range_explode[1];
					$end_year = $end_range_explode[0];
					
					if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
					{
						$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
						$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					    $date_set_start = $start_month."/".$start_date."/".$start_year;
					    $date_set_end = $end_month."/".$end_date."/".$end_year;
					}
				}
			}
			
			if(isset($date_set_end))
			{
			
			}
			else
			{
				$date_set_end   = '';
				$date_set_start = '';
			}
		 
		 if($sales_user_id != 0 && $sales_date !=0)
		 {
			 $sales_conditions['s_u_id'] = $sales_user_id;
			 $sales_conditions['s_date'] = $sales_date;
		 }
		 $order_sales = 'Sale.s_date desc, Sale.s_time desc';
		 $group_sales = 'Sale.s_date,Sale.s_u_id';
		 
		 $get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>$order_sales,'group'=>$group_sales,'fields'=>array('User.added_by','sum(Sale.card_sale_count) as total_card','(Sale.s_date) as sale_date','sum(Sale.s_total_sales) as total_sales','sum(Sale.s_total_purchase) as total_purchase',"CONCAT(User.fname,' ',User.lname) as retailer_name")));
    			
		 $total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount_sale',
														));
            
		$total_purchase = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_purchase) as total_amount_purchase',
														));
														
		$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
		$card_count = 0;
		if(!empty($card_sale_count[0]['total_card']))
		{
				$card_count = $card_sale_count[0]['total_card'];
		}
        // Total Sales
		if(!empty($total_sales[0]['total_amount_sale']))
		{
				$total_sales_amount = $total_sales[0]['total_amount_sale'];
		}
		else
		{
				$total_sales_amount = 0.00;
		}
		// Total Purchase
		if(!empty($total_purchase[0]['total_amount_purchase']))
		{
				$total_purchase_amount = $total_purchase[0]['total_amount_purchase'];
		}
		else
		{
				$total_purchase_amount = 0.00;
		}
		//error_reporting(E_ALL);
		$objPHPExcel = new PHPExcel();
		
		// Setting Column Width
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		
		$objPHPExcel->getProperties()->setCreator("Calling Cards")->setLastModifiedBy("Calling Cards")->setTitle("Office 2007 XLSX Document")
																 ->setSubject("Calling Cards")
																 ->setDescription("Calling Cards")
																 ->setKeywords("office 2007 openxml php")
																 ->setCategory("Export To Excel");		
		
		$objPHPExcel->setActiveSheetIndex(0);		
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		
		// Title of the Report		
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setWrapText(true);
     	$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');

		$med_name = array();
		$retailer_name = array();
		$reports_of = "Daily Sales Report";
		
		if($retailer_id != 0)
		{
			if(isset($get_sales_data[0][0]['retailer_name']))
			{
				$retailer_name = $get_sales_data[0][0]['retailer_name'];
			}
			else
			{
				$ret_name = $this->User->find('first',array('conditions'=>array('id'=>$retailer_id),'fields'=>'full_name'));
				if(!empty($ret_name))
			    $retailer_name = $ret_name['User']['full_name'];
			}
			if(isset($get_sales_data[0]['User']['added_by']))
			$med_name = $this->User->find('first',array('conditions'=>array('id'=>$get_sales_data[0]['User']['added_by']),'fields'=>'full_name'));
			else
			$med_name = $this->User->find('first',array('conditions'=>array('id'=>$mediator_id),'fields'=>'full_name'));
			
			if(!empty($med_name))
			{
				$reports_of = 'Daily Sales Report Of Mediator: '.ucwords($med_name['User']['full_name']).'  '.'Retailer :'.ucwords($retailer_name);

				if(strlen($retailer_name) > 21)
				$retailer_name = substr($retailer_name,0,21)."..";
				
				if(strlen($med_name['User']['full_name']) > 21)
				$med_name['User']['full_name'] = substr($med_name['User']['full_name'],0,21)."..";
			}
		}
		else if($mediator_id != 0)
		{
			$med_name = $this->User->find('first',array('conditions'=>array('id'=>$mediator_id),'fields'=>'full_name'));
		    if(!empty($med_name))
			{
				if(strlen($med_name['User']['full_name']) > 40)
				$med_name['User']['full_name'] = substr($med_name['User']['full_name'],0,40)."..";

				$reports_of = 'Daily Sales Report Of Mediator: '.ucwords($med_name['User']['full_name']);	
			}
		}
        
		if($retailer_id == 0 && $mediator_id == 0 && $sales_date !=0 && $sales_user_id !=0)
		{
			$mediator_id = $get_sales_data[0]['User']['added_by'];
		    $med_name = $this->User->find('first',array('conditions'=>array('id'=>$mediator_id),'fields'=>'full_name'));
		    if(!empty($med_name))
			{
				if(strlen($med_name['User']['full_name']) > 40)
				$med_name['User']['full_name'] = substr($med_name['User']['full_name'],0,40)."..";
                $retailer_name =  $get_sales_data[0][0]['retailer_name'];
				$reports_of = 'Daily Sales Report Of Mediator: '.ucwords($med_name['User']['full_name']).'  '.'Retailer :'.ucwords($retailer_name);
			}
		}

		$objPHPExcel->getActiveSheet()->setCellValue('A2',$reports_of);			
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		
		$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
		
		$heading = 4;
		$FirstItemNumber = $heading + 2;

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,'S.No');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,"Retailer");
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,"Date");
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,"Quantity");
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$heading,"Total Purchase(€)");
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$heading,"Total Sales(€)");

		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':F'.$heading)->getFont()->setBold(true);;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':F'.$heading)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':F'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		
		$serial_counter = 0;
		foreach($get_sales_data as $keynew=>$val)
		{
			$serial_counter++;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,ucwords(strtolower($val['0']['retailer_name'])));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,date('d.m.Y',strtotime($val['Sale']['sale_date'])));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,$val[0]['total_card']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,number_format($val[0]['total_purchase'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,number_format($val[0]['total_sales'],2));
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);

			$FirstItemNumber++;
		}
		
		// Setting Last Line Style For Net Total
		$FirstItemNumber++;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getFont()->setBold(True)->setSize(13);

        // Merging Column

		$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':C'.$FirstItemNumber);

		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
  		$objPHPExcel->getActiveSheet()->getStyle('F'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
   
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,'Net Quantity');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,$card_count);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,$total_purchase_amount);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,$total_sales_amount);
		
		// Set page margin
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);

        $file_name = 'DailySales'.$login_user.'.xls';    
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

    public function admin_profit_profile_distributor($mediator_id = 0,$retailer_id =0,$card_id = 0,$start_range = NULL ,$end_range =NULL,$cat_id = 0, $sub_cat_id = 0){
			$this->admin_redirect_to_dashboard_distributor();
			$this->set("title_for_layout",__('Profitability Report'));	
			$login_user = $this->Auth->User('id');
		    $this->loadModel('Card');
   	        $this->loadModel('Sale');
            $this->loadModel('Category');
            
            
            /* 2 JAN 2015 : Category and subcategory List */
            $res = $this->Category->find('list',array(
                'conditions' => array('cat_parent_id'=>null),
                'fields' => array('cat_id','cat_title'),
                'recursive'	 => -1,
                'order'=>'cat_title asc'
            ));
			
            foreach($res as $k =>$v)
			$res[$k] = ucwords(strtolower($v));
			
            $this->set('cateList',$res);
            $this->set('selected_cat',$cat_id);
            
            if(!empty($cat_id) && $cat_id > 0){
                $subCatConditions = array();
                //$subCatConditions['NOT']['cat_status'] = 2;
                $subCatConditions['cat_parent_id <>'] = null;

                if (isset($cat_id) && !empty($cat_id)) {
                    $this->set('cat_id', $cat_id);
                    $subCatConditions['cat_parent_id'] = $cat_id;
                }

                $resSubCat = $this->Category->find('list', array(
                    'conditions' => $subCatConditions,
                    'fields' => array('cat_id', 'cat_title'),
                    'recursive' => -1,
                    'order' => 'cat_title asc'
                ));
                
				foreach($resSubCat as $k =>$v)
     			$resSubCat[$k] = ucwords(strtolower($v));  
        
		        $this->set('subCateList',$resSubCat);
                $this->set('selected_sub_cat',$sub_cat_id);
            }
            /* END Category and subcategory Listing */
			
			/* Get Card List*/
			$card_conditions = array();
			//$card_conditions['c_status'] = 1;
            
            if($sub_cat_id > 0 && $cat_id > 0){
                $card_conditions['c_cat_id'] = $sub_cat_id;
            }else if($cat_id > 0){
                $card_conditions['c_cat_id'] = array_keys($resSubCat);
            }
			
			$order = 'c_title asc';
			$fields = array('c_id','c_title');
			$get_cards = $this->Card->find('list',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$fields));
            
			$all_cards =array();
			foreach($get_cards as $k=>$v)
			{
				$all_cards[$k] = ucwords(strtolower($v));
			}
			
			$retailer_list = array();
			/* Getting Mediator  data*/
			$mediator_conditions = array();
			$mediator_conditions['User.added_by'] = $login_user;
			$mediator_conditions['User.status'] = array('1','2');
			$mediator_conditions['User.role_id'] = 2;
			$fields_mediator = array('User.id','User.full_name');
			$order_mediator = 'fname,lname asc';
			$get_mediator_data = $this->User->find('list',array('conditions'=>$mediator_conditions,'fields'=>$fields_mediator,'order'=>$order_mediator));
            foreach($get_mediator_data as $k=>$v)
			{
				$get_mediator_data[$k] = ucwords(strtolower($v));
			}
			 
			if($mediator_id != 0)
			{
				$retailers_conditions = array();
				$retailers_conditions['User.status'] = array('1','2');
				$retailers_conditions['User.role_id'] = 3;
				$fields_retailer = array('User.id','User.full_name');
				$order_retailer = 'fname,lname asc';
				$retailers_conditions['User.added_by'] = $mediator_id;
				
				$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
				foreach($get_retailer_data as $key =>$value)
				{	
					$retailer_list[]= $key;
					$get_retailer_data[$key] = ucwords(strtolower($value));
				}
			}
			else
			{
				$retailers_conditions = array();
				$retailers_conditions['User.status'] = array('1','2');
				$retailers_conditions['User.role_id'] = 3;
				$fields_retailer = array('User.id','User.full_name');
				$order_retailer = 'fname,lname asc';
				
				$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
				foreach($get_retailer_data as $key =>$value)
				{	
					$retailer_list[]= $key;
					$get_retailer_data[$key] = ucwords(strtolower($value));
				}
			}
			
			/* Getting Sales Conditions */
			$sales_conditions = array();
			// If Any Retailer is Selected
			if($retailer_id != 0)
			{
				$sales_conditions['s_u_id'] = $retailer_id;
			}
			else
			{  
				//If No Retailer Exists
				if(empty($retailer_list))
				{
					$sales_conditions['s_u_id'] = 0;
				}
				else
				{
					 // If Retailer Exists
				$sales_conditions['s_u_id'] = $retailer_list;
				}
			}

			if($card_id != 0)
			{
				$sales_conditions['s_c_id'] = $card_id;
			}else{
                $sales_conditions['s_c_id'] = array_keys($get_cards);
            }
			
			if($start_range != NULL && $end_range != NULL)
			{
				$start_range_explode = explode('-',$start_range);
				$end_range_explode = explode('-',$end_range);
				
				if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
				{   
				    $start_date = $start_range_explode[2];
					$start_month = $start_range_explode[1];
					$start_year = $start_range_explode[0];
					
					$end_date = $end_range_explode[2];
					$end_month = $end_range_explode[1];
					$end_year = $end_range_explode[0];
					
					if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
					{
						$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
						$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					    $date_set_start = $start_date.".".$start_month.".".$start_year;
					    $date_set_end = $end_date.".".$end_month.".".$end_year;
					}
				}
			}
			
			if(isset($date_set_end))
			{
			
			}
			else
			{
				$date_set_end   = '';
				$date_set_start = '';
			}

			$get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>'Sale.s_date desc, Sale.s_time desc','fields'=>array('Sale.*','Card.c_title',"CONCAT(User.fname,' ',User.lname) as retailer_name")));
			
		   	$total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount_sale',
														));
            
			$total_purchase = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_purchase) as total_amount_purchase',
														));
														
			$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
			$card_count = 0;
			if(!empty($card_sale_count[0]['total_card']))
			{
				$card_count = $card_sale_count[0]['total_card'];
			}
            // Total Sales
			if(!empty($total_sales[0]['total_amount_sale']))
			{
				$total_sales_amount = $total_sales[0]['total_amount_sale'];
			}
			else
			{
				$total_sales_amount = 0.00;
			}
			// Total Purchase
			if(!empty($total_purchase[0]['total_amount_purchase']))
			{
				$total_purchase_amount = $total_purchase[0]['total_amount_purchase'];
			}
			else
			{
				$total_purchase_amount = 0.00;
			}
			
			$this->set('retailer_id',$retailer_id);
		    $this->set('mediator_id',$mediator_id);

			if($mediator_id != 0)
			{
				$this->set('retailer_list',$get_retailer_data);
			}
			
			$this->set('mediator_list',$get_mediator_data);
			$this->set('card_id',$card_id);
			
			$this->set('all_cards',$all_cards);
			$this->set('get_sales_data',$get_sales_data);
			
			$this->set('get_sales_data',$get_sales_data);
			$this->set('total_sales_amount',$total_sales_amount);
			$this->set('total_purchase_amount',$total_purchase_amount);
			
			$this->set('card_count',$card_count);
			$this->set('date_set_end',$date_set_end);
			$this->set('date_set_start',$date_set_start);
	 }

   public function  admin_profit_profile_excel_distributor($cat_id=0,$sub_cat_id = 0,$mediator_id = 0,$retailer_id = 0,$card_id = 0,$start_range = 0,$end_range = 0,$sales_id=0){
  		$this->admin_redirect_to_dashboard_distributor();
		ini_set('max_execution_time', 0);
		// Create new PHPExcel object
		$this->loadModel('Card');
     	$this->loadModel('Sale');
		$this->loadModel('Category');	
			
		$login_user = $this->Auth->User('id');
		$res = $this->Category->find('list',array(
				'conditions' => array('cat_parent_id'=>null),
				'fields' => array('cat_id','cat_title'),
				'recursive'	 => -1,
				'order'=>'cat_title asc'
		));
		
			if(!empty($cat_id) && $cat_id > 0){
					$subCatConditions = array();
					//$subCatConditions['NOT']['cat_status'] = 2;
					$subCatConditions['cat_parent_id <>'] = null;

					if (isset($cat_id) && !empty($cat_id)) {
							$this->set('cat_id', $cat_id);
							$subCatConditions['cat_parent_id'] = $cat_id;
					}

					$resSubCat = $this->Category->find('list', array(
							'conditions' => $subCatConditions,
							'fields' => array('cat_id', 'cat_title'),
							'recursive' => -1,
							'order' => 'cat_title asc'
					));
					
			}
			
			/* Get Card List*/
			$card_conditions = array();
			//$card_conditions['c_status'] = 1;
            
			if($sub_cat_id > 0 && $cat_id > 0){
					$card_conditions['c_cat_id'] = $sub_cat_id;
			}else if($cat_id > 0){
					$card_conditions['c_cat_id'] = array_keys($resSubCat);
			}
			
			$order = 'c_title asc';
			$fields = array('c_id','c_title');
			$get_cards = $this->Card->find('list',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$fields));
			//prd($card_conditions);
            
			$all_cards =array();
			foreach($get_cards as $k=>$v)
			{
				$all_cards[$k] = ucwords($v);
			}
			
			$retailer_list = array();
			/* Getting Mediator  data*/
			$mediator_conditions = array();
			$mediator_conditions['User.added_by'] = $login_user;
			$mediator_conditions['User.status'] = array('1','2');
			$mediator_conditions['User.role_id'] = 2;
			$fields_mediator = array('User.id','User.full_name');
			$order_mediator = 'fname,lname asc';
			$get_mediator_data = $this->User->find('list',array('conditions'=>$mediator_conditions,'fields'=>$fields_mediator,'order'=>$order_mediator));
            foreach($get_mediator_data as $k=>$v){
				$get_mediator_data[$k] = ucwords($v);
			}
	 	if($mediator_id != 0)
		{
			$retailers_conditions = array();
			$retailers_conditions['User.status'] = array('1','2');
			$retailers_conditions['User.role_id'] = 3;
			$fields_retailer = array('User.id','User.full_name');
			$order_retailer = 'fname,lname asc';
			$retailers_conditions['User.added_by'] = $mediator_id;
			
			$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
			foreach($get_retailer_data as $key =>$value)	
			$retailer_list[]= $key;
		}
		else
		{
			$retailers_conditions = array();
			$retailers_conditions['User.status'] = array('1','2');
			$retailers_conditions['User.role_id'] = 3;
			$fields_retailer = array('User.id','User.full_name');
			$order_retailer = 'fname,lname asc';
			
			$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
			foreach($get_retailer_data as $key =>$value)	
			$retailer_list[]= $key;
		}
			
			/* Getting Sales Conditions */
			$sales_conditions = array();
			// If Any Retailer is Selected
			if($retailer_id != 0)
			{
				$sales_conditions['s_u_id'] = $retailer_id;
			}
			else
			{  
				//If No Retailer Exists
				if(empty($retailer_list))
				{
					$sales_conditions['s_u_id'] = 0;
				}
				else
				{
					 // If Retailer Exists
				$sales_conditions['s_u_id'] = $retailer_list;
				}
			}

			if($card_id != 0)
			{
				$sales_conditions['s_c_id'] = $card_id;
			}
			else if($sales_id == 0)
			{
					$sales_conditions['s_c_id'] = array_keys($get_cards);
			}
			
		if($start_range != 0 && $end_range != 0 && $sales_id == 0)
		{
			$start_range_explode = explode('-',$start_range);
			$end_range_explode = explode('-',$end_range);
			
			if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
			{   
				$start_date = $start_range_explode[2];
				$start_month = $start_range_explode[1];
				$start_year = $start_range_explode[0];
				
				$end_date = $end_range_explode[2];
				$end_month = $end_range_explode[1];
				$end_year = $end_range_explode[0];
				
				if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
				{
					$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
					$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					$date_set_start = $start_month."/".$start_date."/".$start_year;
					$date_set_end = $end_month."/".$end_date."/".$end_year;
				}
			}
		}
		
		if($sales_id != 0)
		{
			$sales_conditions['s_id'] = $sales_id;
		}
		
    	$get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>'Sale.s_date desc, Sale.s_time desc','fields'=>array('Sale.*','Card.c_title',"CONCAT(User.fname,' ',User.lname) as retailer_name",'User.added_by')));

	   	$total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount_sale',
														));
            
		$total_purchase = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_purchase) as total_amount_purchase',
														));
														
		$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
		$card_count = 0;
		if(!empty($card_sale_count[0]['total_card']))
		{
			$card_count = $card_sale_count[0]['total_card'];
		}
		// Total Sales
		if(!empty($total_sales[0]['total_amount_sale']))
		{
			$total_sales_amount = $total_sales[0]['total_amount_sale'];
		}
		else
		{
			$total_sales_amount = 0.00;
		}
		// Total Purchase
		if(!empty($total_purchase[0]['total_amount_purchase']))
		{
			$total_purchase_amount = $total_purchase[0]['total_amount_purchase'];
		}
		else
		{
			$total_purchase_amount = 0.00;
		}
		//error_reporting(E_ALL);
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(9);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(11);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(9);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
		
		$objPHPExcel->getProperties()->setCreator("Calling Cards")->setLastModifiedBy("Calling Cards")->setTitle("Office 2007 XLSX Document")
																 ->setSubject("Calling Cards Profit Report")
																 ->setDescription("Calling Cards")
																 ->setKeywords("office 2007 openxml php")
																 ->setCategory("Export To Excel");		
		
		$objPHPExcel->setActiveSheetIndex(0);		
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(9);
		
		// Title of the Report		
		$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getFont()->setBold(true);
    	$objPHPExcel->getActiveSheet()->mergeCells('A2:J2');
		$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getAlignment()->setWrapText(true);
       
		$med_name = array();
		$retailer_name = array();
		$reports_of = "Profitability Report";
		//prd($get_sales_data);
		if($retailer_id != 0)
		{
			if(isset($get_sales_data[0][0]['retailer_name']))
			{
				$retailer_name = $get_sales_data[0][0]['retailer_name'];
			}
			else
			{
				$ret_name = $this->User->find('first',array('conditions'=>array('id'=>$retailer_id),'fields'=>'full_name'));
				if(!empty($ret_name))
			    $retailer_name = $ret_name['User']['full_name'];
			}
			if(isset($get_sales_data[0]['User']['added_by']))
			$med_name = $this->User->find('first',array('conditions'=>array('id'=>$get_sales_data[0]['User']['added_by']),'fields'=>'full_name'));
			else
			$med_name = $this->User->find('first',array('conditions'=>array('id'=>$mediator_id),'fields'=>'full_name'));
			
			if(!empty($med_name))
			{
				if(strlen($retailer_name) > 21)
				$retailer_name = substr($retailer_name,0,21)."..";
				
				if(strlen($med_name['User']['full_name']) > 21)
				$med_name['User']['full_name'] = substr($med_name['User']['full_name'],0,21)."..";

				$reports_of = 'Profitability Report Of Mediator: '.ucwords($med_name['User']['full_name']).'  '.'Retailer :'.ucwords($retailer_name);
			}
		}
		else if($mediator_id != 0)
		{
			$med_name = $this->User->find('first',array('conditions'=>array('id'=>$mediator_id),'fields'=>'full_name'));
		    if(!empty($med_name))
			{
				if(strlen($med_name['User']['full_name']) > 40)
				$med_name['User']['full_name'] = substr($med_name['User']['full_name'],0,40)."..";

				$reports_of = 'Profitability Report Of Mediator: '.ucwords($med_name['User']['full_name']);	
			}
		}
        
		if($retailer_id == 0 && $mediator_id == 0 && $sales_id != 0)
		{
			$mediator_id = $get_sales_data[0]['User']['added_by'];
		    $med_name = $this->User->find('first',array('conditions'=>array('id'=>$mediator_id),'fields'=>'full_name'));
		    if(!empty($med_name))
			{
				if(strlen($med_name['User']['full_name']) > 40)
				$med_name['User']['full_name'] = substr($med_name['User']['full_name'],0,40)."..";
                $retailer_name =  $get_sales_data[0][0]['retailer_name'];
				$reports_of = 'Profitability Report Of Mediator: '.ucwords($med_name['User']['full_name']).'  '.'Retailer :'.ucwords($retailer_name);
			}
		}
		
		$objPHPExcel->getActiveSheet()->setCellValue('A2',$reports_of);
		$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		
		$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
		
		$heading = 4;
		$FirstItemNumber = $heading + 2;

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,'S.No');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,"Retailer");
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,"Card Name");
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,"Purchase Price(€)");
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$heading,"Selling Price(€)");
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$heading,"Quantity");
 		$objPHPExcel->getActiveSheet()->setCellValue('G'.$heading,__('Total Purchase').'(€)');
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$heading,"Total Sales(€)");
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$heading,"Profit(€)");
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$heading,'Date');
         

		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':J'.$heading)->getFont()->setBold(true);;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':J'.$heading)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':J'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		$serial_counter = 0;
		foreach($get_sales_data as $keynew=>$val)
		{
			$serial_counter++;
   
		    $objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,ucwords(strtolower($val['0']['retailer_name'])));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,ucwords(strtolower($val['Card']['c_title'])));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,number_format($val['Sale']['s_purchase_price'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,number_format($val['Sale']['s_selling_price'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,$val['Sale']['card_sale_count']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$FirstItemNumber,number_format($val['Sale']['s_total_purchase'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$FirstItemNumber,number_format($val['Sale']['s_total_sales'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$FirstItemNumber,number_format($val['Sale']['s_total_sales']-$val['Sale']['s_total_purchase'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$FirstItemNumber,date('d.m.Y',strtotime($val['Sale']['s_date']))." ".$val['Sale']['s_time']);
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':J'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':J'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':J'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':J'.$FirstItemNumber)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':J'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);

			$FirstItemNumber++;
		}
		
		// Setting Last Line Style For Net Total
		$FirstItemNumber++;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':J'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':J'.$FirstItemNumber)->getFont()->setBold(True)->setSize(13);

        // Merging Column
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':E'.$FirstItemNumber);

		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$FirstItemNumber.':I'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,'Net Quantity');
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,$card_count);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$FirstItemNumber,$total_purchase_amount);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$FirstItemNumber,$total_sales_amount);

		$objPHPExcel->getActiveSheet()->setCellValue('I'.$FirstItemNumber,($total_sales_amount - $total_purchase_amount));
		
		// Set page margin
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);

		$file_name = "ProfitabilityReport".$login_user.".xls";
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
   

   public function sales_report($card_id = 0,$start_range = NULL ,$end_range =NULL,$cat_id = 0, $sub_cat_id = 0){
   			
   			$this->set("title_for_layout",__('Sales Report'));	
	        $this->loadModel('Card');
   	        $this->loadModel('Sale');
            $this->loadModel('Category');
			
			$login_user = $this->Auth->User('id');
			
            $today_date = date("Y-m-d");
            $days_back_date180 = date("Y-m-d", strtotime("$today_date -180 days"));
           
            
            /* 2 JAN 2015 : Category and subcategory List */
            $res = $this->Category->find('list',array(
                'conditions' => array('cat_parent_id'=>null),
                'fields' => array('cat_id','cat_title'),
                'recursive'	 => -1,
                'order'=>'cat_title asc'
            ));
            
            foreach ($res as $key => $value) {
            	$res[$key] = ucwords(strtolower($value));
            }

            $this->set('cateList',$res);
            $this->set('selected_cat',$cat_id);
            
            if(!empty($cat_id) && $cat_id > 0){
                $subCatConditions = array();
                //$subCatConditions['NOT']['cat_status'] = 2;
                $subCatConditions['cat_parent_id <>'] = null;

                if (isset($cat_id) && !empty($cat_id)) {
                    $this->set('cat_id', $cat_id);
                    $subCatConditions['cat_parent_id'] = $cat_id;
                }

                $resSubCat = $this->Category->find('list', array(
                    'conditions' => $subCatConditions,
                    'fields' => array('cat_id', 'cat_title'),
                    'recursive' => -1,
                    'order' => 'cat_title asc'
                ));
                
                foreach ($resSubCat as $key => $value) {
            	$resSubCat[$key] = ucwords(strtolower($value));
                }

                $this->set('subCateList',$resSubCat);
                $this->set('selected_sub_cat',$sub_cat_id);
            }
            /* END Category and subcategory Listing */
            
			$card_conditions = array();
			//$card_conditions['c_status'] = 1;
			
            if($sub_cat_id > 0 && $cat_id > 0){
                $card_conditions['c_cat_id'] = $sub_cat_id;
            }else if($cat_id > 0){
                $card_conditions['c_cat_id'] = array_keys($resSubCat);
            }
                //pr($card_conditions);
			$order = 'c_title asc';
			$fields = array('c_id','c_title');
			$get_cards = $this->Card->find('list',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$fields));
            //pr($get_cards);
			$all_cards =array();
			$all_cards[0] = ' --- '.__('All').' --- ';
			foreach($get_cards as $k=>$v)
			{
				$all_cards[$k] = ucwords($v);
			}
            
			$sales_conditions = array();
			$sales_conditions['s_u_id'] = $login_user;
			if($card_id != 0)
			{
				$sales_conditions['s_c_id'] = $card_id;
            }else{
                $sales_conditions['s_c_id'] = array_keys($get_cards);
            }
			
			if($start_range != NULL && $end_range != NULL)
			{
				$start_range_explode = explode('-',$start_range);
				$end_range_explode = explode('-',$end_range);
				
				if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
				{   
				    $start_date = $start_range_explode[2];
					$start_month = $start_range_explode[1];
					$start_year = $start_range_explode[0];
					
					$end_date = $end_range_explode[2];
					$end_month = $end_range_explode[1];
					$end_year = $end_range_explode[0];
					
					if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
					{
						$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
						$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					    $date_set_start = $start_date.".".$start_month.".".$start_year;
					    $date_set_end = $end_date.".".$end_month.".".$end_year;
					}
				}
			}
			else
			{
				$sales_conditions['s_date >='] = $days_back_date180;
			    $sales_conditions['s_date <='] = $today_date;
			}
			
			if(isset($date_set_end))
			{
			
			}
			else
			{
				$date_set_end   = '';
				$date_set_start = '';
			}
			
			//pr($sales_conditions);
			$get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>'Sale.s_date desc, Sale.s_time desc','fields'=>array('Sale.*','Card.c_title')));
	
			$total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount',
														));
			$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
			$card_count = 0;
			if(!empty($card_sale_count[0]['total_card']))
			{
				$card_count = $card_sale_count[0]['total_card'];
			}

			if(!empty($total_sales[0]['total_amount']))
			{
				$total_sales_amount = $total_sales[0]['total_amount'];
			}
			else
			{
				$total_sales_amount = 0.00;
			}
			$this->set('all_cards',$all_cards);
			$this->set('get_sales_data',$get_sales_data);
			$this->set('total_sales_amount',$total_sales_amount);
			$this->set('card_count',$card_count);
			$this->set('card_id',$card_id);
			$this->set('date_set_end',$date_set_end);
			$this->set('date_set_start',$date_set_start);
	 }
	 
   public function daily_sales($start_range = NULL ,$end_range =NULL){

	 	    $this->set("title_for_layout",__('Daily Sales Report'));	
	        $this->loadModel('Card');
   	        $this->loadModel('Sale');
			
			$login_user = $this->Auth->User('id');
			
			$sales_conditions = array();
			$sales_conditions['s_u_id'] = $login_user;
			
			if($start_range != NULL && $end_range != NULL)
			{
				$start_range_explode = explode('-',$start_range);
				$end_range_explode = explode('-',$end_range);
				
				if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
				{   
				    $start_date = $start_range_explode[2];
					$start_month = $start_range_explode[1];
					$start_year = $start_range_explode[0];
					
					$end_date = $end_range_explode[2];
					$end_month = $end_range_explode[1];
					$end_year = $end_range_explode[0];
					
					if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
					{
						$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
						$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					    $date_set_start = $start_date.".".$start_month.".".$start_year;
					    $date_set_end = $end_date.".".$end_month.".".$end_year;
					}
				}
			}
			
			if(isset($date_set_end))
			{
			
			}
			else
			{
				$date_set_end   = '';
				$date_set_start = '';
			}
			
	       $order_sales = 'Sale.s_date desc, Sale.s_time desc';
		   $group_sales = 'Sale.s_date';
		
		   $get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>$order_sales,'group'=>$group_sales,'fields'=>array('sum(Sale.card_sale_count) as total_card','(Sale.s_date) as sale_date','sum(Sale.s_total_sales) as total_sales','sum(Sale.s_total_purchase) as total_purchase')));
    			
			$total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount_sale',
														));
            
			$total_purchase = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_purchase) as total_amount_purchase',
														));
														
			$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
			$card_count = 0;
			if(!empty($card_sale_count[0]['total_card']))
			{
				$card_count = $card_sale_count[0]['total_card'];
			}
            // Total Sales
			if(!empty($total_sales[0]['total_amount_sale']))
			{
				$total_sales_amount = $total_sales[0]['total_amount_sale'];
			}
			else
			{
				$total_sales_amount = 0.00;
			}
			// Total Purchase
			if(!empty($total_purchase[0]['total_amount_purchase']))
			{
				$total_purchase_amount = $total_purchase[0]['total_amount_purchase'];
			}
			else
			{
				$total_purchase_amount = 0.00;
			}
			$this->set('get_sales_data',$get_sales_data);
			$this->set('total_sales_amount',$total_sales_amount);
			$this->set('total_purchase_amount',$total_purchase_amount);
			
			$this->set('card_count',$card_count);
			$this->set('date_set_end',$date_set_end);
			$this->set('date_set_start',$date_set_start);
	 }

   public function daily_sales_popup($date){

    	$this->layout = 'fancybox';
    	$formated_date = date('d.m.Y',strtotime($date));
		$this->set('title_for_layout', __('Sales Repord Of').' '.$formated_date);
        
        $this->loadModel('Sale');

        $sales_history = array();
        $sales_history['s_u_id'] = $this->Auth->User('id');
        $sales_history['s_date'] = $date;
        
        $sales_data = $this->Sale->find('all',
        	array(
        	'recursive'=>-1,
			'joins' => array(
				array(
					'table' => 'ecom_cards',
					'alias' => 'Card',
					'type' => 'left',
					'conditions' => 'Card.c_id = Sale.s_c_id'
				),
			),
			'fields' => array('Card.*','Sale.*'),
        	'conditions' => $sales_history,
        	'order'=>'s_id desc'
        	)
        );
        
        $sales_ordered_data = array(); 
        $total_cards = 0;
        $total_sales = 0;
        $total_purchase = 0; 
        foreach ($sales_data as $key => $value) 
        {
         	 $sales_ordered_data[$key]['card_name'] = ucwords(strtolower($value['Card']['c_title']));
             $sales_ordered_data[$key]['buying_price'] =  $value['Sale']['s_purchase_price'];
             $sales_ordered_data[$key]['selling_price'] =  $value['Sale']['s_selling_price'];
             $sales_ordered_data[$key]['quantity'] =  $value['Sale']['card_sale_count'];
             $sales_ordered_data[$key]['total_purchase'] =  $value['Sale']['s_total_purchase'];
             $sales_ordered_data[$key]['total_sales'] =  $value['Sale']['s_total_sales'];
             $sales_ordered_data[$key]['profit'] =  $value['Sale']['s_total_profit'];
             $sales_ordered_data[$key]['time'] =  $value['Sale']['s_time'];
             $total_cards =  $total_cards + $value['Sale']['card_sale_count'];
             $total_sales =  $total_sales + $value['Sale']['s_total_sales'];
             $total_purchase =  $total_purchase + $value['Sale']['s_total_purchase'];
        }

        $this->set('total_cards',$total_cards); 
        $this->set('total_sales',$total_sales); 
        $this->set('total_purchase',$total_purchase); 
        $this->set('sales_ordered_data',$sales_ordered_data); 
   }	 
   
   public function profit_report($card_id = 0,$start_range = NULL ,$end_range =NULL,$cat_id = 0, $sub_cat_id = 0){

	 	    $this->set("title_for_layout",__('Profitability Report'));	
				$this->loadModel('Card');
				$this->loadModel('Sale');
				$this->loadModel('Category');
			
			$login_user = $this->Auth->User('id');
            
            $today_date = date("Y-m-d");
            $days_back_date180 = date("Y-m-d", strtotime("$today_date -180 days"));
    
            $res = $this->Category->find('list',array(
                'conditions' => array('cat_parent_id'=>null),
                'fields' => array('cat_id','cat_title'),
                'recursive'	 => -1,
                'order'=>'cat_title asc'
            ));
            
            foreach ($res as $key => $value) {
            	$res[$key] = ucwords(strtolower($value));
                }

            $this->set('cateList',$res);
            $this->set('selected_cat',$cat_id);
            
            if(!empty($cat_id) && $cat_id > 0){
                $subCatConditions = array();
                //$subCatConditions['NOT']['cat_status'] = 2;
                $subCatConditions['cat_parent_id <>'] = null;

                if (isset($cat_id) && !empty($cat_id)) {
                    $this->set('cat_id', $cat_id);
                    $subCatConditions['cat_parent_id'] = $cat_id;
                }

                $resSubCat = $this->Category->find('list', array(
                    'conditions' => $subCatConditions,
                    'fields' => array('cat_id', 'cat_title'),
                    'recursive' => -1,
                    'order' => 'cat_title asc'
                ));
                
                foreach ($res as $key => $value) {
            	 $res[$key] = ucwords(strtolower($value));
                }

                $this->set('subCateList',$resSubCat);
                $this->set('selected_sub_cat',$sub_cat_id);
            }
            /* END Category and subcategory Listing */
			
			$card_conditions = array();
			$card_conditions['c_status'] = 1;
			
            if($sub_cat_id > 0 && $cat_id > 0){
                $card_conditions['c_cat_id'] = $sub_cat_id;
            }else if($cat_id > 0){
                $card_conditions['c_cat_id'] = array_keys($resSubCat);
            }
            
			$order = 'c_title asc';
			$fields = array('c_id','c_title');
			$get_cards = $this->Card->find('list',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$fields));
            
			$all_cards =array();
			$all_cards[0] = ' --- '.__('All').' --- ';
			foreach($get_cards as $k=>$v)
			{
				$all_cards[$k] = ucwords($v);
			}
			
			$sales_conditions = array();
			$sales_conditions['s_u_id'] = $login_user;
            
            if($card_id != 0)
			{
				$sales_conditions['s_c_id'] = $card_id;
            }else{
                $sales_conditions['s_c_id'] = array_keys($get_cards);
            }
            
			if($card_id != 0)
			{
				$sales_conditions['s_c_id'] = $card_id;
			}
			
			if($start_range != NULL && $end_range != NULL)
			{
				$start_range_explode = explode('-',$start_range);
				$end_range_explode = explode('-',$end_range);
				
				if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
				{   
				    $start_date = $start_range_explode[2];
					$start_month = $start_range_explode[1];
					$start_year = $start_range_explode[0];
					
					$end_date = $end_range_explode[2];
					$end_month = $end_range_explode[1];
					$end_year = $end_range_explode[0];
					
					if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
					{
						$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
						$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					    $date_set_start = $start_date.".".$start_month.".".$start_year;
					    $date_set_end = $end_date.".".$end_month.".".$end_year;
					}
				}
			}
			else
			{
				$sales_conditions['s_date >='] = $days_back_date180;
			    $sales_conditions['s_date <='] = $today_date;
			}

			
			if(isset($date_set_end))
			{
			
			}
			else
			{
				$date_set_end   = '';
				$date_set_start = '';
			}
			
		   $get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>'Sale.s_date desc, Sale.s_time desc','fields'=>array('Sale.*','Card.c_title')));
			
		   $total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount_sale',
														));
            
			$total_purchase = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_purchase) as total_amount_purchase',
														));
														
			$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
			$card_count = 0;
			if(!empty($card_sale_count[0]['total_card']))
			{
				$card_count = $card_sale_count[0]['total_card'];
			}
            // Total Sales
			if(!empty($total_sales[0]['total_amount_sale']))
			{
				$total_sales_amount = $total_sales[0]['total_amount_sale'];
			}
			else
			{
				$total_sales_amount = 0.00;
			}
			// Total Purchase
			if(!empty($total_purchase[0]['total_amount_purchase']))
			{
				$total_purchase_amount = $total_purchase[0]['total_amount_purchase'];
			}
			else
			{
				$total_purchase_amount = 0.00;
			}
			
			$this->set('all_cards',$all_cards);
			$this->set('card_id',$card_id);
			$this->set('get_sales_data',$get_sales_data);
			
			$this->set('get_sales_data',$get_sales_data);
			$this->set('total_sales_amount',$total_sales_amount);
			$this->set('total_purchase_amount',$total_purchase_amount);
			
			$this->set('card_count',$card_count);
			$this->set('date_set_end',$date_set_end);
			$this->set('date_set_start',$date_set_start);
	 }

   public function today_sale(){
	 
			$this->set("title_for_layout",__("Today"."'s "."Sale"));	
	        $this->loadModel('Sale');
			
			$login_user = $this->Auth->User('id');
			
			$sales_conditions = array();
			$sales_conditions['s_u_id'] = $login_user;
			$sales_conditions['s_date'] = date('Y-m-d');

			$get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>'Sale.s_time desc','fields'=>array('Sale.*','Card.c_title')));
	
			$total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount',
														));
			$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
			$card_count = 0;
			if(!empty($card_sale_count[0]['total_card']))
			{
				$card_count = $card_sale_count[0]['total_card'];
			}

			if(!empty($total_sales[0]['total_amount']))
			{
				$total_sales_amount = $total_sales[0]['total_amount'];
			}
			else
			{
				$total_sales_amount = 0.00;
			}
			
			$this->set('get_sales_data',$get_sales_data);
			$this->set('total_sales_amount',$total_sales_amount);
			$this->set('card_count',$card_count);
		}
   
   public function detailed_sales_report($card_id = 0,$start_range = NULL ,$end_range =NULL,$cat_id = 0, $sub_cat_id = 0){
   			
   			$this->set("title_for_layout",__('Detailed Sales Report'));	
	        $this->loadModel('Card');
   	        $this->loadModel('Sale');
            $this->loadModel('Category');
			
			$sales_report_category = array();

			$login_user = $this->Auth->User('id');
			
            $today_date = date("Y-m-d");
            $days_back_date180 = date("Y-m-d", strtotime("$today_date -180 days"));
            
            /* Category and subcategory List */
            $res = $this->Category->find('list',array(
                'conditions' => array('cat_parent_id'=>null),
                'fields' => array('cat_id','cat_title'),
                'recursive'	 => -1,
                'order'=>'cat_title asc'
            ));
            
            $counter = 0;
            foreach ($res as $key => $value) 
            {
            	$res[$key] = ucwords(strtolower($value));

            	$subCatConditions = array();
                $subCatConditions['cat_parent_id'] = $key;
                $resSubCat = $this->Category->find('list', array(
			                    'conditions' => $subCatConditions,
			                    'fields' => array('cat_id', 'cat_title'),
			                    'recursive' => -1,
			                    'order' => 'cat_title asc'
			                    ));
                $new_sub_cat = 0;
                
                if(empty($resSubCat))
                {
	                 $sales_report_category[$counter]['main_cat'] = $key;
	            	 $sales_report_category[$counter]['main_cat_title'] = ucwords(strtolower($value));
	            	 $sales_report_category[$counter]['sub_cat'] = '';
	            	 $sales_report_category[$counter]['sub_cat_title'] = __('There is no sub category in this category.');
	            	 $sales_report_category[$counter]['chnage_cat'] = 1;
	            	 $counter++;
                }
                else
                {
                	foreach( $resSubCat as $sub_id => $sub_title)
	                {
	                	 if($new_sub_cat == 0)
	                	 {
	                	 	$new_sub_cat = 1;
	                	 	// For Showing One Cat In Table
	                	 	 $sales_report_category[$counter]['chnage_cat'] = 1;
	                	 }
	                	 else
	                	 {
	                	 	 $sales_report_category[$counter]['chnage_cat'] = 0;
	                	 }
	                	 $sales_report_category[$counter]['main_cat'] = $key;
	                	 $sales_report_category[$counter]['main_cat_title'] = ucwords(strtolower($value));
	                	 $sales_report_category[$counter]['sub_cat'] = $sub_id;
	                	 $sales_report_category[$counter]['sub_cat_title'] = ucwords(strtolower($sub_title));
	                     
	                     $counter++;
	                }
                }
                
            }
            
            $this->set('cateList',$res);
            $this->set('selected_cat',$cat_id);
            
            if(!empty($cat_id) && $cat_id > 0)
            {
                foreach ($sales_report_category as $key => $value) {
                	if($cat_id != $value['main_cat'])
               		unset($sales_report_category[$key]);
                }
                
                $subCatConditions = array();
                //$subCatConditions['NOT']['cat_status'] = 2;
                $subCatConditions['cat_parent_id <>'] = null;
                
                if (isset($cat_id) && !empty($cat_id)) {
                    $this->set('cat_id', $cat_id);
                    $subCatConditions['cat_parent_id'] = $cat_id;
                }

                $resSubCat = $this->Category->find('list', array(
                    'conditions' => $subCatConditions,
                    'fields' => array('cat_id', 'cat_title'),
                    'recursive' => -1,
                    'order' => 'cat_title asc'
                ));
                
                foreach ($resSubCat as $key => $value) {
            	$resSubCat[$key] = ucwords(strtolower($value));
                }
                
                $this->set('subCateList',$resSubCat);
                $this->set('selected_sub_cat',$sub_cat_id);
            }
            /* END Category and subcategory Listing */
            
            $card_conditions = array();
			//$card_conditions['c_status'] = 1;
			
            /* In case card is selected reseting values of categorrie and subcategories */
            if($card_id != 0)
			{
				$get_card_cat  = $this->Card->find('first',array(
				'conditions'=>array('c_id'=>$card_id),
				'recursive'=>-1,
				'joins' => array(
					array(
						'table' => 'ecom_categories',
						'alias' => 'MainCategory',
						'type' => 'left',
						'conditions' => 'MainCategory.cat_id = Card.c_cat_id'
					),
					array(
						'table' => 'ecom_categories',
						'alias' => 'Parent',
						'type' => 'left',
						'conditions' => 'Parent.cat_id = MainCategory.cat_parent_id'
					)

				),
				'fields' => array('MainCategory.*','Parent.*'),
			));
				
				$sub_cat_id = $get_card_cat['MainCategory']['cat_id'];
				$cat_id = $get_card_cat['Parent']['cat_id'];
            }

            if($sub_cat_id > 0 && $cat_id > 0)
            {
                $card_conditions['c_cat_id'] = $sub_cat_id;
                foreach ($sales_report_category as $key => $value) {
                	if($sub_cat_id != $value['sub_cat'])
               		unset($sales_report_category[$key]);
                }
            }
            else if($cat_id > 0)
            {
                $card_conditions['c_cat_id'] = array_keys($resSubCat);
            }
                //pr($card_conditions);
			$order = 'c_title asc';
			$fields = array('c_id','c_title');

            $get_cards = $this->Card->find('list',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$fields));
            //pr($get_cards);
			$all_cards =array();
			$all_cards[0] = ' --- '.__('All').' --- ';
			foreach($get_cards as $k=>$v)
			{
				$all_cards[$k] = ucwords($v);
			}
            
			$sales_conditions = array();
			$sales_conditions['s_u_id'] = $login_user;
			
			if($start_range != NULL && $end_range != NULL)
			{
				$start_range_explode = explode('-',$start_range);
				$end_range_explode = explode('-',$end_range);
				
				if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
				{   
				    $start_date = $start_range_explode[2];
					$start_month = $start_range_explode[1];
					$start_year = $start_range_explode[0];
					
					$end_date = $end_range_explode[2];
					$end_month = $end_range_explode[1];
					$end_year = $end_range_explode[0];
					
					if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
					{
						$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
						$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					    $date_set_start = $start_date.".".$start_month.".".$start_year;
					    $date_set_end = $end_date.".".$end_month.".".$end_year;
					}
				}
			}
			else
			{
				$sales_conditions['s_date >='] = $days_back_date180;
			    $sales_conditions['s_date <='] = $today_date;
			}
			
			if(isset($date_set_end))
			{
			
			}
			else
			{
				$date_set_end   = '';
				$date_set_start = '';
			}
			
			//pr($sales_conditions);
		 $sales_data_category_wise = array();
            
         $counter = 0;
		 
		 $final_total_card = 0;
		 $final_total_sales_amount = 0;
         
        
         foreach ($sales_report_category as $key => $value) 
		 {
			$sales_data_category_wise[$counter]['main_cat'] = $value['main_cat'];
			$sales_data_category_wise[$counter]['main_cat_title'] = $value['main_cat_title'];
			$sales_data_category_wise[$counter]['sub_cat'] = $value['sub_cat'];
			$sales_data_category_wise[$counter]['sub_cat_title'] = $value['sub_cat_title'];
            $sales_data_category_wise[$counter]['chnage_cat'] = $value['chnage_cat'];
            
            if(!empty($value['sub_cat']))
            {
            	$card_conditions['c_cat_id'] = $value['sub_cat'];
            	if($card_id != 0)
				{
					$card_conditions['c_id'] = $card_id;
	            }
	            $get_cards = $this->Card->find('list',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$fields));

				$sales_conditions['s_c_id'] = array_keys($get_cards);
		 
	            $get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>'Sale.s_date desc, Sale.s_time desc','fields'=>array('Sale.*','Card.c_title')));

	            $total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
															'fields'=>'sum(Sale.s_total_sales) as total_amount',
															));
				$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
													'fields'=>'sum(Sale.card_sale_count) as total_card',
												));

				$card_count = 0;
				if(!empty($card_sale_count[0]['total_card']))
				{
					$card_count = $card_sale_count[0]['total_card'];
				}

				if(!empty($total_sales[0]['total_amount']))
				{
					$total_sales_amount = $total_sales[0]['total_amount'];
				}
				else
				{
					$total_sales_amount = 0.00;
				}
            }
            else
            {
            	$total_sales_amount = 0;
            	$get_sales_data = array();
            	$card_count = 0;
            }    

            $sales_data_category_wise[$counter]['sales_data'] = $get_sales_data;
            $sales_data_category_wise[$counter]['card_count'] = $card_count;
            $sales_data_category_wise[$counter]['total_sales_amount'] = $total_sales_amount;
            
            $final_total_card = $final_total_card + $card_count;
            $final_total_sales_amount = $final_total_sales_amount + $total_sales_amount;

            $counter++;
             
        }
		
		$this->set('all_cards',$all_cards);
		$this->set('sales_data_category_wise',$sales_data_category_wise);
		$this->set('total_sales_amount',$final_total_sales_amount);
		$this->set('card_count',$final_total_card);
		$this->set('card_id',$card_id);
		$this->set('date_set_end',$date_set_end);
		$this->set('date_set_start',$date_set_start);
   }		
   
   public function profit_report_excel($cat_id=0,$sub_cat_id = 0,$card_id = 0,$start_range = 0,$end_range = 0,$sales_id=0){
	
  		ini_set('max_execution_time', 0);
		// Create new PHPExcel object
		$this->loadModel('Card');
   	    $this->loadModel('Sale');
		$this->loadModel('Category');	
		$login_user = $this->Auth->User('id');
		
		$today_date = date("Y-m-d");
        $days_back_date180 = date("Y-m-d", strtotime("$today_date -180 days"));

		$sales_conditions = array();
		$total_purchase_amount = 0.00;
		$total_sales_amount = 0.00;
		
		$sales_conditions['s_u_id'] = $login_user;
		if($card_id != 0)
		{
			$sales_conditions['s_c_id'] = $card_id;
		}
		
		$res = $this->Category->find('list',array(
                'conditions' => array('cat_parent_id'=>null),
                'fields' => array('cat_id','cat_title'),
                'recursive'	 => -1,
                'order'=>'cat_title asc'
            ));
            
            $this->set('cateList',$res);
            $this->set('selected_cat',$cat_id);
            
            if(!empty($cat_id) && $cat_id > 0){
                $subCatConditions = array();
                //$subCatConditions['NOT']['cat_status'] = 2;
                $subCatConditions['cat_parent_id <>'] = null;

                if (isset($cat_id) && !empty($cat_id)) {
                    $this->set('cat_id', $cat_id);
                    $subCatConditions['cat_parent_id'] = $cat_id;
                }

                $resSubCat = $this->Category->find('list', array(
                    'conditions' => $subCatConditions,
                    'fields' => array('cat_id', 'cat_title'),
                    'recursive' => -1,
                    'order' => 'cat_title asc'
                ));

                $this->set('subCateList',$resSubCat);
                $this->set('selected_sub_cat',$sub_cat_id);
            }
            /* END Category and subcategory Listing */
			
			$card_conditions = array();
			//$card_conditions['c_status'] = 1;
			
            if($sub_cat_id > 0 && $cat_id > 0){
                $card_conditions['c_cat_id'] = $sub_cat_id;
            }else if($cat_id > 0){
                $card_conditions['c_cat_id'] = array_keys($resSubCat);
            }
            
			$order = 'c_title asc';
			$fields = array('c_id','c_title');
			$get_cards = $this->Card->find('list',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$fields));
            
			$all_cards =array();
			$all_cards[0] = __('All');
			foreach($get_cards as $k=>$v)
			{
				$all_cards[$k] = ucwords($v);
			}
			
			$sales_conditions = array();
			$sales_conditions['s_u_id'] = $login_user;
            
            if($card_id != 0)
			{
				$sales_conditions['s_c_id'] = $card_id;
			}else{
				$sales_conditions['s_c_id'] = array_keys($get_cards);
			}
            
			if($card_id != 0)
			{
				$sales_conditions['s_c_id'] = $card_id;
			}
			
		if($start_range != 0 && $end_range != 0 && $sales_id == 0)
		{
			$start_range_explode = explode('-',$start_range);
			$end_range_explode = explode('-',$end_range);
			
			if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
			{   
				$start_date = $start_range_explode[2];
				$start_month = $start_range_explode[1];
				$start_year = $start_range_explode[0];
				
				$end_date = $end_range_explode[2];
				$end_month = $end_range_explode[1];
				$end_year = $end_range_explode[0];
				
				if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
				{
					$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
					$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					$date_set_start = $start_month."/".$start_date."/".$start_year;
					$date_set_end = $end_month."/".$end_date."/".$end_year;
				}
			}
		}
		else
		{
			$sales_conditions['s_date >='] = $days_back_date180;
		    $sales_conditions['s_date <='] = $today_date;
		}
		
		if($sales_id != 0)
		{
			$sales_conditions['s_id'] = $sales_id;
		}
		
		$get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>'Sale.s_date desc, Sale.s_time desc','fields'=>array('Sale.*','Card.c_title')));
	    //prd($get_sales_data);
		$total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount',
														));
		$total_purchase = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_purchase) as total_purchase_amount',
														));
		
		$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
		$card_count = 0;
		if(!empty($card_sale_count[0]['total_card']))
		{
			$card_count = $card_sale_count[0]['total_card'];
		}

		if(!empty($total_sales[0]['total_amount']))
		{
			$total_sales_amount = $total_sales[0]['total_amount'];
		}
		else
		{
			$total_sales_amount = 0.00;
		}
		
		if(!empty($total_purchase[0]['total_purchase_amount']))
		{
			$total_purchase_amount = $total_purchase[0]['total_purchase_amount'];
		}
		else
		{
			$total_sales_amount = 0.00;
		}
	    
		//error_reporting(E_ALL);
		$objPHPExcel = new PHPExcel();
		
		// Setting Column Width
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		
		$objPHPExcel->getProperties()->setCreator("Calling Cards")->setLastModifiedBy("Calling Cards")->setTitle("Office 2007 XLSX Document")
																 ->setSubject("Calling Cards Profit Report")
																 ->setDescription("Calling Cards")
																 ->setKeywords("office 2007 openxml php")
																 ->setCategory("Export To Excel");		
		
		$objPHPExcel->setActiveSheetIndex(0);		
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(9);
		
		// Title of the Report		
			
    	$retailer_name = ucwords(strtolower($this->Auth->User('fname').' '.$this->Auth->User('lname')));
		$retailer_name = __('Retailer').": ".$retailer_name;
	    
		$retailer_account = __('Account Number').": ".$this->Auth->User('username');
        
        if($this->Auth->User('address'))
		{
			$retailer_address = __('Address').": ".$this->Auth->User('address');
		}
		else
		{
			$retailer_address = __('Address').": N/A";
		}

        $objPHPExcel->getActiveSheet()->mergeCells('A2:I2');
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A2',__('Profitability Report'));

 		$objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
        $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A3',$retailer_name);

        $objPHPExcel->getActiveSheet()->mergeCells('A4:I4');
        $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A4',$retailer_account);

        $objPHPExcel->getActiveSheet()->mergeCells('A5:I5');
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A5',$retailer_address);
        
        $heading = 7;

        if($cat_id !=0)
        {
             $get_cat_dat= $this->Category->findByCatId($cat_id);
             $cat_title_display = __('Category').": ".ucwords(strtolower($get_cat_dat['Category']['cat_title']));
             
			$objPHPExcel->getActiveSheet()->mergeCells('A6:I6');
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue('A6',$cat_title_display); 
			$heading++;
        } 

        if($sub_cat_id !=0)
        {
             $get_cat_dat= $this->Category->findByCatId($sub_cat_id);
             $cat_title_display = __('Sub Category').": ".ucwords(strtolower($get_cat_dat['Category']['cat_title']));
             
			$objPHPExcel->getActiveSheet()->mergeCells('A7:I7');
			$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue('A7',$cat_title_display); 
			$heading++;
        } 

        if($card_id !=0)
        {
             $get_card_details= $this->Card->findByCId($card_id);
             $card_name = __('Card').": ".ucwords(strtolower($get_card_details['Card']['c_title']));
             
			$objPHPExcel->getActiveSheet()->mergeCells('A8:I8');
			$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue('A8',$card_name); 
			$heading++;
        } 
        
        if($start_range !=0)
        {
			$start_range =date('d.m.Y',strtotime($start_range));
			$end_range =date('d.m.Y',strtotime($end_range));
			$range = $start_range." - ".$end_range;
			$date_range_set = __('Date Selection').": ".$range;
             
			$objPHPExcel->getActiveSheet()->mergeCells('A9:I9');
			$objPHPExcel->getActiveSheet()->getStyle('A9')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A9')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue('A9',$date_range_set); 
			$heading++;
        }
		$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
		
		$FirstItemNumber = $heading + 2;

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,__('S.No'));
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,__("Card Name"));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,__('Purchase Price').'(€)');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,__('Selling Price').'(€)');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$heading,__("Quantity"));
 		$objPHPExcel->getActiveSheet()->setCellValue('F'.$heading,__('Total Purchase').'(€)');
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$heading,__('Total Sales').'(€)');
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$heading,__('Profit').'(€)');
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$heading,__('Date'));
         

		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':I'.$heading)->getFont()->setBold(true);;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':I'.$heading)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':I'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		$serial_counter = 0;
		foreach($get_sales_data as $keynew=>$val)
		{
			$serial_counter++;
   
		    $objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,ucwords($val['Card']['c_title']));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,number_format($val['Sale']['s_purchase_price'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,number_format($val['Sale']['s_selling_price'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,$val['Sale']['card_sale_count']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,number_format($val['Sale']['s_total_purchase'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$FirstItemNumber,number_format($val['Sale']['s_total_sales'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$FirstItemNumber,number_format($val['Sale']['s_total_sales']-$val['Sale']['s_total_purchase'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$FirstItemNumber,date('d.m.Y',strtotime($val['Sale']['s_date'])));
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':I'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':I'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':I'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':I'.$FirstItemNumber)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':I'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);

			$FirstItemNumber++;
		}
		
		// Setting Last Line Style For Net Total
		$FirstItemNumber++;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':I'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':I'.$FirstItemNumber)->getFont()->setBold(True)->setSize(13);

        // Merging Column
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':D'.$FirstItemNumber);

		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$FirstItemNumber.':H'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,__('Net Quantity'));
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,$card_count);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,$total_purchase_amount);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$FirstItemNumber,$total_sales_amount);

		$objPHPExcel->getActiveSheet()->setCellValue('H'.$FirstItemNumber,($total_sales_amount - $total_purchase_amount));
		
		// Set page margin
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);

		$file_name = "ProfitabilityReport".$login_user.".xls";
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
	
   public function daily_sales_excel($start_range = 0,$end_range = 0,$sales_date=0){
	    ini_set('max_execution_time', 0);
		// Create new PHPExcel object
		$this->loadModel('Card');
   	    $this->loadModel('Sale');
			
		$login_user = $this->Auth->User('id');
		
		$sales_conditions = array();
		$total_purchase_amount = 0.00;
		$total_sales_amount = 0.00;
		$sales_conditions['s_u_id'] = $login_user;
		
		if($start_range != 0 && $end_range != 0 && $sales_date == 0)
		{
			$start_range_explode = explode('-',$start_range);
			$end_range_explode = explode('-',$end_range);
			
			if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
			{   
				$start_date = $start_range_explode[2];
				$start_month = $start_range_explode[1];
				$start_year = $start_range_explode[0];
				
				$end_date = $end_range_explode[2];
				$end_month = $end_range_explode[1];
				$end_year = $end_range_explode[0];
				
				if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
				{
					$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
					$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					$date_set_start = $start_month."/".$start_date."/".$start_year;
					$date_set_end = $end_month."/".$end_date."/".$end_year;
				}
			}
		}
		
		if($sales_date != 0)
		{
			$sales_conditions['s_date'] = $sales_date;
		}
		
		$order_sales = 'Sale.s_date desc, Sale.s_time desc';
		$group_sales = 'Sale.s_date';
		
		$get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>$order_sales,'group'=>$group_sales,'fields'=>array('sum(Sale.card_sale_count) as total_card','(Sale.s_date) as sale_date','sum(Sale.s_total_sales) as total_sales','sum(Sale.s_total_purchase) as total_purchase')));
       
		$total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount',
														));
		$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
		$total_purchase = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_purchase) as total_amount_purchase',
														));
		$card_count = 0;
		if(!empty($card_sale_count[0]['total_card']))
		{
			$card_count = $card_sale_count[0]['total_card'];
		}

		if(!empty($total_sales[0]['total_amount']))
		{
			$total_sales_amount = $total_sales[0]['total_amount'];
		}
		else
		{
			$total_sales_amount = 0.00;
		}

        if(!empty($total_purchase[0]['total_amount_purchase']))
		{
			$total_purchase_amount = $total_purchase[0]['total_amount_purchase'];
		}
		else
		{
			$total_purchase_amount = 0.00;
		}
	    
		//error_reporting(E_ALL);
		$objPHPExcel = new PHPExcel();
		
		// Setting Column Width
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		
		$objPHPExcel->getProperties()->setCreator("Calling Cards")->setLastModifiedBy("Calling Cards")->setTitle("Office 2007 XLSX Document")
																 ->setSubject("Calling Cards")
																 ->setDescription("Calling Cards")
																 ->setKeywords("office 2007 openxml php")
																 ->setCategory("Export To Excel");		
		
		$objPHPExcel->setActiveSheetIndex(0);		
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		
		// Title of the Report		
		$retailer_name = ucwords(strtolower($this->Auth->User('fname').' '.$this->Auth->User('lname')));
		$retailer_name = __('Retailer').": ".$retailer_name;
	    
		$retailer_account = __('Account Number').": ".$this->Auth->User('username');
        
        if($this->Auth->User('address'))
		{
			$retailer_address = __('Address').": ".$this->Auth->User('address');
		}
		else
		{
			$retailer_address = __('Address').": N/A";
		}

        $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A2',__('Daily Sales Report'));

 		$objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
        $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A3',$retailer_name);

        $objPHPExcel->getActiveSheet()->mergeCells('A4:E4');
        $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A4',$retailer_account);

        $objPHPExcel->getActiveSheet()->mergeCells('A5:E5');
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A5',$retailer_address);
        
        $heading = 7;

        if($start_range !=0)
        {
			$start_range =date('d.m.Y',strtotime($start_range));
			$end_range =date('d.m.Y',strtotime($end_range));
			$range = $start_range." - ".$end_range;
			$date_range_set = __('Date Selection').": ".$range;
             
			$objPHPExcel->getActiveSheet()->mergeCells('A6:E6');
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue('A6',$date_range_set); 
			$heading++;
        }
		$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
		
		$FirstItemNumber = $heading + 2;

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,__('S.No'));
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,__("Date"));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,__("Quantity"));
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,__('Purchase Price').'(€)');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$heading,__('Selling Price').'(€)');

		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':E'.$heading)->getFont()->setBold(true);;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':E'.$heading)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':E'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		
		$serial_counter = 0;
		foreach($get_sales_data as $keynew=>$val)
		{
			$serial_counter++;
		    $objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,date('d.m.Y',strtotime($val['Sale']['sale_date'])));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,$val[0]['total_card']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,number_format($val[0]['total_purchase'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,number_format($val[0]['total_sales'],2));

			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':E'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':E'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':E'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':E'.$FirstItemNumber)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':E'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);

			$FirstItemNumber++;
		}
		
		// Setting Last Line Style For Net Total
		$FirstItemNumber++;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':E'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':E'.$FirstItemNumber)->getFont()->setBold(True)->setSize(13);

        // Merging Column
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':B'.$FirstItemNumber);

		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('C'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,__('Net Quantity'));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,$card_count);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,$total_purchase_amount);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,$total_sales_amount);
		
		// Set page margin
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);


		$fullPath = WWW_ROOT.'DailySalesReport.xls';
		 
     	$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');		
		$objWriter->save(WWW_ROOT.'DailySalesReport.xls');

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
   
    public function  detailed_sales_report_excel($cat_id=0,$sub_cat_id = 0,$card_id = 0,$start_range = 0,$end_range = 0,$sales_id=0)
    {
	
  		ini_set('max_execution_time', 0);
		// Create new PHPExcel object
		$this->loadModel('Card');
   	    $this->loadModel('Sale');
		$this->loadModel('Category');	
		$login_user = $this->Auth->User('id');
		
		$sales_conditions = array();
		$total_sales_amount = 0.00;
		$sales_conditions['s_u_id'] = $login_user;
		
		$today_date = date("Y-m-d");
        $days_back_date180 = date("Y-m-d", strtotime("$today_date -180 days"));

		
		$res = $this->Category->find('list',array(
                'conditions' => array('cat_parent_id'=>null),
                'fields' => array('cat_id','cat_title'),
                'recursive'	 => -1,
                'order'=>'cat_title asc'
            ));
            
        
        $counter = 0;
        $sales_report_category = array();
        foreach ($res as $key => $value) 
        {
        	$subCatConditions = array();
            $subCatConditions['cat_parent_id'] = $key;
            $resSubCat = $this->Category->find('list', array(
		                    'conditions' => $subCatConditions,
		                    'fields' => array('cat_id', 'cat_title'),
		                    'recursive' => -1,
		                    'order' => 'cat_title asc'
		                    ));
            $new_sub_cat = 0;
            if(empty($resSubCat))
            {
                 $sales_report_category[$counter]['main_cat'] = $key;
            	 $sales_report_category[$counter]['main_cat_title'] = ucwords(strtolower($value));
            	 $sales_report_category[$counter]['sub_cat'] = '';
            	 $sales_report_category[$counter]['sub_cat_title'] = __('There is no sub category in this category.');
            	 $sales_report_category[$counter]['chnage_cat'] = 1;
            	 $counter++;
            }
            else
            {
            	foreach( $resSubCat as $sub_id => $sub_title)
	            {
	            	 if($new_sub_cat == 0)
	            	 {
	            	 	$new_sub_cat = 1;
	            	 	// For Showing One Cat In Table
	            	 	 $sales_report_category[$counter]['chnage_cat'] = 1;
	            	 }
	            	 else
	            	 {
	            	 	 $sales_report_category[$counter]['chnage_cat'] = 0;
	            	 }
	            	 $sales_report_category[$counter]['main_cat'] = $key;
	            	 $sales_report_category[$counter]['main_cat_title'] = ucwords(strtolower($value));
	            	 $sales_report_category[$counter]['sub_cat'] = $sub_id;
	            	 $sales_report_category[$counter]['sub_cat_title'] = ucwords(strtolower($sub_title));
	                 
	                 $counter++;
	            }
            }
            
        }   

	    if(!empty($cat_id) && $cat_id > 0)
	    {
				$subCatConditions = array();
				//$subCatConditions['NOT']['cat_status'] = 2;
				$subCatConditions['cat_parent_id <>'] = null;
                
                foreach ($sales_report_category as $key => $value) {
                	if($cat_id != $value['main_cat'])
               		unset($sales_report_category[$key]);
                }

				if (isset($cat_id) && !empty($cat_id)) {
						$this->set('cat_id', $cat_id);
						$subCatConditions['cat_parent_id'] = $cat_id;
				}
                
				$resSubCat = $this->Category->find('list', array(
						'conditions' => $subCatConditions,
						'fields' => array('cat_id', 'cat_title'),
						'recursive' => -1,
						'order' => 'cat_title asc'
				));

				$this->set('subCateList',$resSubCat);
				$this->set('selected_sub_cat',$sub_cat_id);
	     }
        /* END Category and subcategory Listing */
        
		$card_conditions = array();
		//$card_conditions['c_status'] = 1;
		
		/* In case card is selected reseting values of categorrie and subcategories */
        if($card_id != 0)
		{
			$get_card_cat  = $this->Card->find('first',array(
			'conditions'=>array('c_id'=>$card_id),
			'recursive'=>-1,
			'joins' => array(
				array(
					'table' => 'ecom_categories',
					'alias' => 'MainCategory',
					'type' => 'left',
					'conditions' => 'MainCategory.cat_id = Card.c_cat_id'
				),
				array(
					'table' => 'ecom_categories',
					'alias' => 'Parent',
					'type' => 'left',
					'conditions' => 'Parent.cat_id = MainCategory.cat_parent_id'
				)

			),
			'fields' => array('MainCategory.*','Parent.*'),
		));
			
			$sub_cat_id = $get_card_cat['MainCategory']['cat_id'];
			$cat_id = $get_card_cat['Parent']['cat_id'];
        }
        else if($sales_id != 0)
        {
        	$get_card_cat  = $this->Sale->find('first',array(
			'conditions'=>array('s_id'=>$sales_id),
			'recursive'=>-1,
			'joins' => array(
				array(
					'table' => 'ecom_cards',
					'alias' => 'Card',
					'type' => 'left',
					'conditions' => 'Card.c_id = Sale.s_c_id'
				),
				array(
					'table' => 'ecom_categories',
					'alias' => 'MainCategory',
					'type' => 'left',
					'conditions' => 'MainCategory.cat_id = Card.c_cat_id'
				),
				array(
					'table' => 'ecom_categories',
					'alias' => 'Parent',
					'type' => 'left',
					'conditions' => 'Parent.cat_id = MainCategory.cat_parent_id'
				)

			),
			'fields' => array('Card.*','MainCategory.*','Parent.*'),
		  ));
          
          $card_id =  $get_card_cat['Card']['c_id'];
          $sub_cat_id =  $get_card_cat['Card']['c_cat_id'];
          $cat_id =  $get_card_cat['Parent']['cat_id'];
         	
        }
        

        if($sub_cat_id > 0 && $cat_id > 0)
		{
			$card_conditions['c_cat_id'] = $sub_cat_id;
            foreach ($sales_report_category as $key => $value) 
            {
            	if($sub_cat_id != $value['sub_cat'])
           		unset($sales_report_category[$key]);
            }

			$card_conditions['c_cat_id'] = $sub_cat_id;
		}
		else if($cat_id > 0)
		{
				$card_conditions['c_cat_id'] = array_keys($resSubCat);
		}

       //pr($card_conditions);
		$order = 'c_title asc';
		$fields = array('c_id','c_title');
		    
		$sales_conditions = array();
		$sales_conditions['s_u_id'] = $login_user;
		
		if($start_range != 0 && $end_range != 0 && $sales_id == 0)
		{
			$start_range_explode = explode('-',$start_range);
			$end_range_explode = explode('-',$end_range);
			
			if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
			{   
				$start_date = $start_range_explode[2];
				$start_month = $start_range_explode[1];
				$start_year = $start_range_explode[0];
				
				$end_date = $end_range_explode[2];
				$end_month = $end_range_explode[1];
				$end_year = $end_range_explode[0];
				
				if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
				{
					$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
					$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					$date_set_start = $start_month."/".$start_date."/".$start_year;
					$date_set_end = $end_month."/".$end_date."/".$end_year;
				}
			}
		}
		else
		{
			$sales_conditions['s_date >='] = $days_back_date180;
		    $sales_conditions['s_date <='] = $today_date;
		}
		
		 $sales_data_category_wise = array();
            
         $counter = 0;
		 
		 $final_total_card = 0;
		 $final_total_sales_amount = 0;

        foreach ($sales_report_category as $key => $value) 
		 {
			$sales_data_category_wise[$counter]['main_cat'] = $value['main_cat'];
			$sales_data_category_wise[$counter]['main_cat_title'] = $value['main_cat_title'];
			$sales_data_category_wise[$counter]['sub_cat'] = $value['sub_cat'];
			$sales_data_category_wise[$counter]['sub_cat_title'] = $value['sub_cat_title'];
            $sales_data_category_wise[$counter]['chnage_cat'] = $value['chnage_cat'];
            
            if(empty($value['sub_cat']))
            {
            	$total_sales_amount = 0;
            	$get_sales_data = array();
            	$card_count = 0;
            }
            else
            {
	            $card_conditions['c_cat_id'] = $value['sub_cat'];
				
				if($card_id != 0)
				{
					$card_conditions['c_id'] = $card_id;
	            }

				$get_cards = $this->Card->find('list',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$fields));

				$sales_conditions['s_c_id'] = array_keys($get_cards);
		        
		        if($sales_id != 0)
				{
					$sales_conditions['s_id'] = $sales_id;
				}  
	    
	            $get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>'Sale.s_date desc, Sale.s_time desc','fields'=>array('Sale.*','Card.c_title')));

	            $total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
															'fields'=>'sum(Sale.s_total_sales) as total_amount',
															));
				$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
													'fields'=>'sum(Sale.card_sale_count) as total_card',
												));

				$card_count = 0;
				if(!empty($card_sale_count[0]['total_card']))
				{
					$card_count = $card_sale_count[0]['total_card'];
				}

				if(!empty($total_sales[0]['total_amount']))
				{
					$total_sales_amount = $total_sales[0]['total_amount'];
				}
				else
				{
					$total_sales_amount = 0.00;
				}
            }

			


            $sales_data_category_wise[$counter]['sales_data'] = $get_sales_data;
            $sales_data_category_wise[$counter]['card_count'] = $card_count;
            $sales_data_category_wise[$counter]['total_sales_amount'] = $total_sales_amount;
            
            $final_total_card = $final_total_card + $card_count;
            $final_total_sales_amount = $final_total_sales_amount + $total_sales_amount;

            $counter++;
             
        }

		//error_reporting(E_ALL);
		$objPHPExcel = new PHPExcel();
		
		// Setting Column Width
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		
		$objPHPExcel->getProperties()->setCreator("Calling Cards")->setLastModifiedBy("Calling Cards")->setTitle("Office 2007 XLSX Document")
																 ->setSubject("Calling Cards")
																 ->setDescription("Calling Cards")
																 ->setKeywords("office 2007 openxml php")
																 ->setCategory("Export To Excel");		
		
		$objPHPExcel->setActiveSheetIndex(0);		
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		
		// Title of the Report		
		$retailer_name = ucwords(strtolower($this->Auth->User('fname').' '.$this->Auth->User('lname')));
		$retailer_name = __('Retailer').": ".$retailer_name;
	    
		$retailer_account = __('Account Number').": ".$this->Auth->User('username');
        
        if($this->Auth->User('address'))
		{
			$retailer_address = __('Address').": ".$this->Auth->User('address');
		}
		else
		{
			$retailer_address = __('Address').": N/A";
		}

        $objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A2',__('Detailed Sales Report'));

 		$objPHPExcel->getActiveSheet()->mergeCells('A3:F3');
        $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A3',$retailer_name);

        $objPHPExcel->getActiveSheet()->mergeCells('A4:F4');
        $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A4',$retailer_account);

        $objPHPExcel->getActiveSheet()->mergeCells('A5:F5');
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A5',$retailer_address);
        
        $heading = 7;

        if($cat_id !=0)
        {
             $get_cat_dat= $this->Category->findByCatId($cat_id);
             $cat_title_display = __('Category').": ".ucwords(strtolower($get_cat_dat['Category']['cat_title']));
             
			$objPHPExcel->getActiveSheet()->mergeCells('A6:F6');
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue('A6',$cat_title_display); 
			$heading++;
        } 

        if($sub_cat_id !=0)
        {
             $get_cat_dat= $this->Category->findByCatId($sub_cat_id);
             $cat_title_display = __('Sub Category').": ".ucwords(strtolower($get_cat_dat['Category']['cat_title']));
             
			$objPHPExcel->getActiveSheet()->mergeCells('A7:F7');
			$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue('A7',$cat_title_display); 
			$heading++;
        } 

        if($card_id !=0)
        {
             $get_card_details= $this->Card->findByCId($card_id);
             $card_name = __('Card').": ".ucwords(strtolower($get_card_details['Card']['c_title']));
             
			$objPHPExcel->getActiveSheet()->mergeCells('A8:F8');
			$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue('A8',$card_name); 
			$heading++;
        } 
        
        if($start_range !=0)
        {
			$start_range =date('d.m.Y',strtotime($start_range));
			$end_range =date('d.m.Y',strtotime($end_range));
			$range = $start_range." - ".$end_range;
			$date_range_set = __('Date Selection').": ".$range;
             
			$objPHPExcel->getActiveSheet()->mergeCells('A9:F9');
			$objPHPExcel->getActiveSheet()->getStyle('A9')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A9')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue('A9',$date_range_set); 
			$heading++;
        }
		$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':F'.$heading)->getFont()->setBold(true);;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':F'.$heading)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':F'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		
		$FirstItemNumber = $heading;
		$counter_start = 0; 

		$total_sales_for_main_cat =0;
        $total_cards_for_main_cat =0;
		
		$all_card = 0;
		$all_cards_sales_amount = 0;

		foreach($sales_data_category_wise as $keynew=>$val)
		{
			
			if($val['chnage_cat'] == 1 || $sales_id != 0)
			{
				if($counter_start == 0)
				{
					$counter_start = 1;
				}
				else
				{
					$FirstItemNumber++;
				}
				
				if($keynew != 0)
				{
					$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
				    $objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getFont()->setBold(True)->setSize(11);

					$total_sales_details = __('Total Card')." : ". $total_cards_for_main_cat."  ".__('Total Sales')." : ". $total_sales_for_main_cat ;  
					$total_sales_for_main_cat =0;
	                $total_cards_for_main_cat =0;
					// Merging Column
			    	$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':F'.$FirstItemNumber);
			    	$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$total_sales_details);
	    
			    	$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.":".'F'.$FirstItemNumber)->applyFromArray(
								    array(
								        'fill' => array(
								            'type' => PHPExcel_Style_Fill::FILL_SOLID,
								            'color' => array('rgb' => '03549f')
								        ),
								        'font' => array(
				                        'color' => array( 'rgb' => 'FFFFFF'), 
				                        )
								    )
								);
	                
	                $total_sales_for_main_cat =0;
	                $total_cards_for_main_cat =0;
				}
				
                if($keynew != 0)
                {
	                $FirstItemNumber = $FirstItemNumber + 3  ;
	            }
	            else
	            {
	            	$FirstItemNumber++ ;
	            }
		    	
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
			    $objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getFont()->setBold(True)->setSize(12);

				// Merging Column
		    	$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':F'.$FirstItemNumber);
		    	$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,__('Category').": ".$val['main_cat_title']);

		    	$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.":".'F'.$FirstItemNumber)->applyFromArray(
							    array(
							        'fill' => array(
							            'type' => PHPExcel_Style_Fill::FILL_SOLID,
							            'color' => array('rgb' => 'e8e8e8')
							        ),
							        'font' => array(
			                        'color' => array( 'rgb' => '333333'), 
			                        )
							    )
							);
		    	
			}

			$FirstItemNumber = $FirstItemNumber + 2 ;
			
			 $total_sales_for_main_cat =$total_sales_for_main_cat+$val['total_sales_amount'];
             $total_cards_for_main_cat =$total_cards_for_main_cat+$val['card_count'];

			 $all_cards_sales_amount =$all_cards_sales_amount+$val['total_sales_amount'];
             $all_card =$all_card+$val['card_count'];
            // SubCategory Name
            // Merging Column
            $objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
			    $objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getFont()->setBold(True)->setSize(10);

	    	$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':F'.$FirstItemNumber);
	    	$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,__('Sub Category').": ".$val['sub_cat_title']);

	    	$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.":".'F'.$FirstItemNumber)->applyFromArray(
							    array(
							        'fill' => array(
							            'type' => PHPExcel_Style_Fill::FILL_SOLID,
							            'color' => array('rgb' => 'bebebe')
							        ),
							        'font' => array(
			                        'color' => array( 'rgb' => '333333'), 
			                        )
							    )
							);


            $FirstItemNumber++;  

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,__('S.No'));
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,__("Card Name"));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,__('Selling Price').'(€)');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,__("Quantity"));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,__('Net Total').'(€)');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,__("Date-Time"));
             
            $FirstItemNumber++;
              
			$serial_counter = 0;
         	foreach ($val['sales_data'] as $sales_key => $sales_value) 
			{
				$serial_counter++;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,ucwords($sales_value['Card']['c_title']));
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,number_format($sales_value['Sale']['s_selling_price'],2));
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,$sales_value['Sale']['card_sale_count']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,number_format($sales_value['Sale']['s_total_sales'],2));
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,date('d.m.Y H:i:s',strtotime($sales_value['Sale']['s_date']." ".$sales_value['Sale']['s_time'])));
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);

				$FirstItemNumber++;
				
			}
             
		    // Setting Last Line Style For Net Total
			$FirstItemNumber++;
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getFont()->setBold(True)->setSize(10);

		    
		     // Merging Column
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':C'.$FirstItemNumber);

			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,__('Net Quantity'));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,$val['card_count']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,$val['total_sales_amount']);

		}
		
		if(!empty($sales_report_category))
		{
			$FirstItemNumber++;
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
		    $objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getFont()->setBold(True)->setSize(11);

			$total_sales_details = __('Total Card')." : ". $total_cards_for_main_cat."  ".__('Total Sales')." : ". $total_sales_for_main_cat ;  
			// Merging Column
	    	$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':F'.$FirstItemNumber);
	    	$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$total_sales_details);

	    	$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.":".'F'.$FirstItemNumber)->applyFromArray(
						    array(
						        'fill' => array(
						            'type' => PHPExcel_Style_Fill::FILL_SOLID,
						            'color' => array('rgb' => '03549f')
						        ),
						        'font' => array(
		                        'color' => array( 'rgb' => 'FFFFFF'), 
		                        )
						    )
						);
            
            $FirstItemNumber = $FirstItemNumber + 2;
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
		    $objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getFont()->setBold(True)->setSize(11);

			$total_sales_details = __('Final Details').' -- '.__('Total Card')." : ". $all_card."  ".__('Total Sales')." : ". $all_cards_sales_amount ;  
			// Merging Column
	    	$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':F'.$FirstItemNumber);
	    	$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$total_sales_details);

	    	$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.":".'F'.$FirstItemNumber)->applyFromArray(
						    array(
						        'fill' => array(
						            'type' => PHPExcel_Style_Fill::FILL_SOLID,
						            'color' => array('rgb' => '00ffff')
						        ),
						        'font' => array(
		                        'color' => array( 'rgb' => '000000'), 
		                        )
						    )
						);
      	}
		
		// Set page margin
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);

        $file_name = 'SalesReport'.$login_user.'.xls';    
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
   public function  sales_report_excel($cat_id=0,$sub_cat_id = 0,$card_id = 0,$start_range = 0,$end_range = 0,$sales_id=0){
	
  		ini_set('max_execution_time', 0);
		// Create new PHPExcel object
		$this->loadModel('Card');
   	    $this->loadModel('Sale');
		$this->loadModel('Category');	
		$login_user = $this->Auth->User('id');
		
		$sales_conditions = array();
		$total_sales_amount = 0.00;
		$sales_conditions['s_u_id'] = $login_user;
		
		$today_date = date("Y-m-d");
        $days_back_date180 = date("Y-m-d", strtotime("$today_date -180 days"));

		
		$res = $this->Category->find('list',array(
                'conditions' => array('cat_parent_id'=>null),
                'fields' => array('cat_id','cat_title'),
                'recursive'	 => -1,
                'order'=>'cat_title asc'
            ));
            
            
     if(!empty($cat_id) && $cat_id > 0){
					$subCatConditions = array();
					//$subCatConditions['NOT']['cat_status'] = 2;
					$subCatConditions['cat_parent_id <>'] = null;

					if (isset($cat_id) && !empty($cat_id)) {
							$this->set('cat_id', $cat_id);
							$subCatConditions['cat_parent_id'] = $cat_id;
					}

					$resSubCat = $this->Category->find('list', array(
							'conditions' => $subCatConditions,
							'fields' => array('cat_id', 'cat_title'),
							'recursive' => -1,
							'order' => 'cat_title asc'
					));

					$this->set('subCateList',$resSubCat);
					$this->set('selected_sub_cat',$sub_cat_id);
			}
            /* END Category and subcategory Listing */
            
			$card_conditions = array();
			//$card_conditions['c_status'] = 1;
			
			if($sub_cat_id > 0 && $cat_id > 0){
					$card_conditions['c_cat_id'] = $sub_cat_id;
			}else if($cat_id > 0){
					$card_conditions['c_cat_id'] = array_keys($resSubCat);
			}
           //pr($card_conditions);
			$order = 'c_title asc';
			$fields = array('c_id','c_title');
			$get_cards = $this->Card->find('list',array('conditions'=>$card_conditions,'order'=>$order,'fields'=>$fields));
            //pr($get_cards);
			$all_cards =array();
			$all_cards[0] = __('All');
			foreach($get_cards as $k=>$v)
			{
				$all_cards[$k] = ucwords($v);
			}
            
			$sales_conditions = array();
			$sales_conditions['s_u_id'] = $login_user;
			if($card_id != 0)
			{
				$sales_conditions['s_c_id'] = $card_id;
			}else{
					$sales_conditions['s_c_id'] = array_keys($get_cards);
			}
		
		
		
		if($card_id != 0)
		{
			$sales_conditions['s_c_id'] = $card_id;
		}
			
		if($start_range != 0 && $end_range != 0 && $sales_id == 0)
		{
			$start_range_explode = explode('-',$start_range);
			$end_range_explode = explode('-',$end_range);
			
			if(count($start_range_explode) == 3 && count($end_range_explode) == 3)
			{   
				$start_date = $start_range_explode[2];
				$start_month = $start_range_explode[1];
				$start_year = $start_range_explode[0];
				
				$end_date = $end_range_explode[2];
				$end_month = $end_range_explode[1];
				$end_year = $end_range_explode[0];
				
				if(checkdate($start_month,$start_date,$start_year) && checkdate($end_month,$end_date,$end_year))
				{
					$sales_conditions['s_date >='] = $start_year."-".$start_month."-".$start_date;
					$sales_conditions['s_date <='] = $end_year."-".$end_month."-".$end_date;
					$date_set_start = $start_month."/".$start_date."/".$start_year;
					$date_set_end = $end_month."/".$end_date."/".$end_year;
				}
			}
		}
		else
		{
			$sales_conditions['s_date >='] = $days_back_date180;
		    $sales_conditions['s_date <='] = $today_date;
		}
		
		if($sales_id != 0)
		{
			$sales_conditions['s_id'] = $sales_id;
		}
		
		
		$get_sales_data = $this->Sale->find('all',array('conditions'=>$sales_conditions,'order'=>'Sale.s_date desc, Sale.s_time desc','fields'=>array('Sale.*','Card.c_title')));
	    //prd($get_sales_data);
		$total_sales = $this->Sale->find('first',array('conditions'=>$sales_conditions,
														'fields'=>'sum(Sale.s_total_sales) as total_amount',
														));
		$card_sale_count = $this->Sale->find('first',array('conditions'=>$sales_conditions,
												'fields'=>'sum(Sale.card_sale_count) as total_card',
											));
		$card_count = 0;
		if(!empty($card_sale_count[0]['total_card']))
		{
			$card_count = $card_sale_count[0]['total_card'];
		}

		if(!empty($total_sales[0]['total_amount']))
		{
			$total_sales_amount = $total_sales[0]['total_amount'];
		}
		else
		{
			$total_sales_amount = 0.00;
		}
	    
		//error_reporting(E_ALL);
		$objPHPExcel = new PHPExcel();
		
		// Setting Column Width
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		
		$objPHPExcel->getProperties()->setCreator("Calling Cards")->setLastModifiedBy("Calling Cards")->setTitle("Office 2007 XLSX Document")
																 ->setSubject("Calling Cards")
																 ->setDescription("Calling Cards")
																 ->setKeywords("office 2007 openxml php")
																 ->setCategory("Export To Excel");		
		
		$objPHPExcel->setActiveSheetIndex(0);		
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
		
  		// Title of the Report		
    	$retailer_name = ucwords(strtolower($this->Auth->User('fname').' '.$this->Auth->User('lname')));
		$retailer_name = __('Retailer').": ".$retailer_name;
	    
		$retailer_account = __('Account Number').": ".$this->Auth->User('username');
        
        if($this->Auth->User('address'))
		{
			$retailer_address = __('Address').": ".$this->Auth->User('address');
		}
		else
		{
			$retailer_address = __('Address').": N/A";
		}

        $objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A2',__('Sales Report'));

 		$objPHPExcel->getActiveSheet()->mergeCells('A3:F3');
        $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A3',$retailer_name);

        $objPHPExcel->getActiveSheet()->mergeCells('A4:F4');
        $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A4',$retailer_account);

        $objPHPExcel->getActiveSheet()->mergeCells('A5:F5');
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A5',$retailer_address);
        
        $heading = 7;

        if($cat_id !=0)
        {
             $get_cat_dat= $this->Category->findByCatId($cat_id);
             $cat_title_display = __('Category').": ".ucwords(strtolower($get_cat_dat['Category']['cat_title']));
             
			$objPHPExcel->getActiveSheet()->mergeCells('A6:F6');
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue('A6',$cat_title_display); 
			$heading++;
        } 

        if($sub_cat_id !=0)
        {
             $get_cat_dat= $this->Category->findByCatId($sub_cat_id);
             $cat_title_display = __('Sub Category').": ".ucwords(strtolower($get_cat_dat['Category']['cat_title']));
             
			$objPHPExcel->getActiveSheet()->mergeCells('A7:F7');
			$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue('A7',$cat_title_display); 
			$heading++;
        } 

        if($card_id !=0)
        {
             $get_card_details= $this->Card->findByCId($card_id);
             $card_name = __('Card').": ".ucwords(strtolower($get_card_details['Card']['c_title']));
             
			$objPHPExcel->getActiveSheet()->mergeCells('A8:F8');
			$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue('A8',$card_name); 
			$heading++;
        } 
        
        if($start_range !=0)
        {
			$start_range =date('d.m.Y',strtotime($start_range));
			$end_range =date('d.m.Y',strtotime($end_range));
			$range = $start_range." - ".$end_range;
			$date_range_set = __('Date Selection').": ".$range;
             
			$objPHPExcel->getActiveSheet()->mergeCells('A9:F9');
			$objPHPExcel->getActiveSheet()->getStyle('A9')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A9')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue('A9',$date_range_set); 
			$heading++;
        }

 		$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
		

		
		$FirstItemNumber = $heading + 2;

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,__('S.No'));
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,__("Card Name"));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,__('Selling Price').'(€)');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,__("Quantity"));
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$heading,__('Net Total').'(€)');
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$heading,__("Date-Time"));
		
         

		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':F'.$heading)->getFont()->setBold(true);;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':F'.$heading)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':F'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		
		$serial_counter = 0;
		foreach($get_sales_data as $keynew=>$val)
		{
			$serial_counter++;
   
		    $objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,ucwords($val['Card']['c_title']));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,number_format($val['Sale']['s_selling_price'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,$val['Sale']['card_sale_count']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,number_format($val['Sale']['s_total_sales'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$FirstItemNumber,date('d.m.Y H:i:s',strtotime($val['Sale']['s_date']." ".$val['Sale']['s_time'])));
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);

			$FirstItemNumber++;
		}
		
		// Setting Last Line Style For Net Total
		$FirstItemNumber++;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':F'.$FirstItemNumber)->getFont()->setBold(True)->setSize(13);

        // Merging Column
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':C'.$FirstItemNumber);

		$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,__('Net Quantity'));
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,$card_count);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,$total_sales_amount);
		
		// Set page margin
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);

        $file_name = 'SalesReport'.$login_user.'.xls';    
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
  
  public function invoice(){ 
	    $this->set("title_for_layout",__('Daily Sales Report'));	
  }
	//search from online cards, ajax to get sub categories
	public function get_subcat()
	{
		$this->loadModel('Category');
		if($this->request->is('ajax'))
		{
			$data = $this->request->data;
			$id 	= $data['id'];
			
 		    $subCatConditions = array();
			$subCatConditions['cat_status'] = 1;
			$subCatConditions['cat_parent_id <>'] = null;

			if (isset($id) && !empty($id)) 
			{
					$subCatConditions['cat_parent_id'] = $id;
			}

			$sub_cat_names = $this->Category->find('list', array(
					'conditions' => $subCatConditions,
					'fields' => array('cat_id', 'cat_title'),
					'recursive' => -1,
					'order' => 'cat_title asc'
			));
			
			foreach($sub_cat_names as $k => $v)
			$sub_cat_names[$k] = ucwords(strtolower($v));
				
			echo json_encode($sub_cat_names);
			exit;
		}
	}
	public function get_cards_parent_cat()
	{
        $this->loadModel('Category');
		$this->loadModel('Card');
		if($this->request->is('ajax'))
		{
			$data = $this->request->data;
			$id 	= $data['id'];
		
			$conditions =array();
			//$conditions['cat_status'] = 1;
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
                asort($cards);
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
                asort($cards);
				echo json_encode($cards);
				exit;
			}
		}
	}
	public function get_cards()
	{
		$this->loadModel('Category');
		if($this->request->is('ajax'))
		{
			$this->loadModel('Card');
			$data = $this->request->data;
			$id 	= $data['id'];
			$conditions =array();
			//$conditions['c_status'] = 1;
			
			if(isset($data['main_cat_id']))
            {
            	$main_cat_id = $data['main_cat_id'];

            } 

            $conditions =array();
			//$conditions['c_status'] = 1;
			if($id && !empty($id))
			{
				$conditions['c_cat_id'] = $id;
			}
			else if(isset($main_cat_id))
			{
				$subCatConditions = array();
				$subCatConditions['cat_status'] = 1;
				$subCatConditions['cat_parent_id'] = $main_cat_id;
                $resSubCat = $this->Category->find('list',array(
					'conditions' => $subCatConditions,
					'fields' => array('cat_id','cat_title'),
					'recursive'	 => -1
				));
				$conditions['c_cat_id'] = array_keys($resSubCat) ;
			}
			
			$cards = $this->Card->find('list',array(
					'fields'		 => array('c_id','c_title'),
					'conditions' => $conditions,
					'order'=>'c_title asc'
			));
			
			foreach($cards as $k => $v)
   			$cards[$k] = ucwords(strtolower($v));
            asort($cards);
			echo json_encode($cards);
			exit;
		}
	}
	public function admin_get_subcat(){
		$this->loadModel('Category');
		if($this->request->is('ajax'))
		{
			$data = $this->request->data;
			$id 	= $data['id'];
			
 		    $subCatConditions = array();
			//$subCatConditions['NOT']['cat_status'] = 2;
			$subCatConditions['cat_parent_id <>'] = null;

			if (isset($id) && !empty($id)) {
					$subCatConditions['cat_parent_id'] = $id;
			}

			$sub_cat_names = $this->Category->find('list', array(
					'conditions' => $subCatConditions,
					'fields' => array('cat_id', 'cat_title'),
					'recursive' => -1,
					'order' => 'cat_title asc'
			));
            
			foreach($sub_cat_names as $k => $v)
    		$sub_cat_names[$k] = ucwords(strtolower($v));
	
			echo json_encode($sub_cat_names);
			exit;
		}
	}
	public function admin_get_cards_parent_cat(){
    $this->loadModel('Category');
		$this->loadModel('Card');
		if($this->request->is('ajax'))
		{
			$data = $this->request->data;
			$id 	= $data['id'];
		
			$conditions =array();
			//$conditions['cat_status'] = 1;
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
			//$conditions['c_status'] = 1;
				
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
					'order'=>'c_title ASC'
				));
	
				foreach($cards as $k => $v)
        		$cards[$k] = ucwords(strtolower($v));
                
				echo json_encode($cards);
				exit;
			}
		}
	}
	public function admin_get_cards(){
		
		$this->loadModel('Category');
		if($this->request->is('ajax'))
		{
			$this->loadModel('Card');
			$data = $this->request->data;
			$id 	= $data['id'];

			if(isset($data['main_cat_id']))
            {
            	$main_cat_id = $data['main_cat_id'];

            } 

            $conditions =array();
			//$conditions['c_status'] = 1;
			if($id && !empty($id))
			{
				$conditions['c_cat_id'] = $id;
			}
			else if(isset($main_cat_id))
			{
				$subCatConditions = array();
				$subCatConditions['cat_status'] = 1;
				$subCatConditions['cat_parent_id'] = $main_cat_id;
                $resSubCat = $this->Category->find('list',array(
					'conditions' => $subCatConditions,
					'fields' => array('cat_id','cat_title'),
					'recursive'	 => -1
				));
				$conditions['c_cat_id'] = array_keys($resSubCat) ;
			}
			
			
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

	 public function admin_balance_report_distributor($mediator_id =0,$retailer_id =0){
			$this->admin_redirect_to_dashboard_distributor();
			//echo "Das";exit;
			$this->set("title_for_layout",__('Retailer Balance Report'));	
			$login_user = $this->Auth->User('id');
		
			$this->loadModel('Sale');
			$this->loadModel('Category');
          
			
			$retailer_list = array();

			/* Getting Mediator  data*/
			$mediator_conditions = array();
			$mediator_conditions['User.added_by'] = $login_user;
			$mediator_conditions['User.status'] = array('1','2');
			$mediator_conditions['User.role_id'] = 2;
			$fields_mediator = array('User.id','User.full_name');
			$order_mediator = 'fname,lname asc';
			$get_mediator_data = $this->User->find('list',array('conditions'=>$mediator_conditions,'fields'=>$fields_mediator,'order'=>$order_mediator));
            foreach($get_mediator_data as $k=>$v)
			{
				$get_mediator_data[$k] = ucwords($v);
			}
			 
			if($mediator_id != 0)
			{
								
				$retailers_conditions = array();
				$retailers_conditions['User.status'] = array('1','2');
				$retailers_conditions['User.role_id'] = 3;
				$fields_retailer = array('User.id','User.full_name');
				$order_retailer = 'fname,lname asc';
				$retailers_conditions['User.added_by'] = $mediator_id;
				
				$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
				foreach($get_retailer_data as $key =>$value)	
				$retailer_list[]= $key;
				
			}
			else
			{
				$retailers_conditions = array();
				$retailers_conditions['User.status'] = array('1','2');
				$retailers_conditions['User.role_id'] = 3;
				$fields_retailer = array('User.id','User.full_name');
				$order_retailer = 'fname,lname asc';
				
				$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
				foreach($get_retailer_data as $key =>$value)	
				$retailer_list[]= $key;
			}
			
			
			/* Getting Balance Conditions */
			$bal_conditions = array();
			// If Any Retailer is Selected
			if($retailer_id != 0)
			{
				$bal_conditions['user_id'] = $retailer_id;
			}
			else
			{  
				//If No Retailer Exists
				if(empty($retailer_list))
				{
					$bal_conditions['user_id'] = 0;
				}
				else
				{
					 // If Retailer Exists
				$bal_conditions['user_id'] = $retailer_list;
				}
			}
            
            
			$this->set('retailer_id',$retailer_id);
		    $this->set('mediator_id',$mediator_id);

			if($mediator_id != 0)
			{
				$this->set('retailer_list',$get_retailer_data);
			}
			
			$this->set('mediator_list',$get_mediator_data);
			//echo "ads".$login_user;exit;
			$get_account_balance_data = $this->Transaction->find('all', array(
			'recursive' => -1, //int
			'joins'=>array(
							array(
									'table' => 'ecom_users',
									'alias' => 'User',
									'type' => 'left',
									'conditions' => array('Transaction.user_id=User.id')
							)
						),
			'conditions' => $bal_conditions,
			'order' =>'User.fname, User.lname asc',
			'fields'=>array('Transaction.*',"CONCAT(User.fname,' ',User.lname) as retailer_name"),
		));
			
			$total_amount = $this->Transaction->find('first',array('conditions'=>$bal_conditions,
														'fields'=>'sum(Transaction.total_amount) as total_amount',
														));

		 	$total_balance = $this->Transaction->find('first',array('conditions'=>$bal_conditions,
												'fields'=>'sum(Transaction.balance) as total_balance',
											));

		 	if(!empty($total_amount[0]['total_amount']))
			{
				$total_retailer_amount = $total_amount[0]['total_amount'];
			}
			else
			{
				$total_retailer_amount = 0.00;
			}

			if(!empty($total_balance[0]['total_balance']))
			{
				$total_retailer_balance = $total_balance[0]['total_balance'];
			}
			else
			{
				$total_retailer_balance = 0.00;
			}
			//echo "tt=".$total_retailer_amount;exit;
			$this->set('get_account_balance_data',$get_account_balance_data);
			$this->set('total_retailer_amount',$total_retailer_amount);
			$this->set('total_retailer_balance',$total_retailer_balance);
			
	 }

	 public function  admin_balance_report_excel_distributor($mediator_id = 0,$retailer_id = 0,$transaction_id = 0){
  		$this->admin_redirect_to_dashboard_distributor();
  		ini_set('max_execution_time', 0);
		// Create new PHPExcel object
			
		$login_user = $this->Auth->User('id');
		
		$retailer_list = array();

		/* Getting Mediator  data*/
		$mediator_conditions = array();
		$mediator_conditions['User.added_by'] = $login_user;
		$mediator_conditions['User.status'] = array('1','2');
		$mediator_conditions['User.role_id'] = 2;
		$fields_mediator = array('User.id','User.full_name');
		$order_mediator = 'fname,lname asc';
		$get_mediator_data = $this->User->find('list',array('conditions'=>$mediator_conditions,'fields'=>$fields_mediator,'order'=>$order_mediator));
        foreach($get_mediator_data as $k=>$v)
		{
			$get_mediator_data[$k] = ucwords($v);
		}
			 
		if($mediator_id != 0)
		{
			$retailers_conditions = array();
			$retailers_conditions['User.status'] = array('1','2');
			$retailers_conditions['User.role_id'] = 3;
			$fields_retailer = array('User.id','User.full_name');
			$order_retailer = 'fname,lname asc';
			$retailers_conditions['User.added_by'] = $mediator_id;
			
			$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
			foreach($get_retailer_data as $key =>$value)	
				$retailer_list[]= $key;
		}else
		{
			$retailers_conditions = array();
			$retailers_conditions['User.status'] = array('1','2');
			$retailers_conditions['User.role_id'] = 3;
			$fields_retailer = array('User.id','User.full_name');
			$order_retailer = 'fname,lname asc';
			
			$get_retailer_data = $this->User->find('list',array('conditions'=>$retailers_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
			foreach($get_retailer_data as $key =>$value)	
			$retailer_list[]= $key;
		}
			
		/* Getting Balance Conditions */
			$bal_conditions = array();
			// If Any Retailer is Selected
			if($retailer_id != 0)
			{
				$bal_conditions['user_id'] = $retailer_id;
			}
			else
			{  
				//If No Retailer Exists
				if(empty($retailer_list))
				{
					$bal_conditions['user_id'] = 0;
				}
				else
				{
					 // If Retailer Exists
				$bal_conditions['user_id'] = $retailer_list;
				}
			}
		      if($transaction_id != 0)
			{
				$bal_conditions['Transaction.id'] = $transaction_id;
			}
            
			$this->set('retailer_id',$retailer_id);
		    $this->set('mediator_id',$mediator_id);

			if($mediator_id != 0)
			{
				$this->set('retailer_list',$get_retailer_data);
			}
			
			$this->set('mediator_list',$get_mediator_data);
			//echo "ads".$login_user;exit;
			$get_account_balance_data = $this->Transaction->find('all', array(
			'recursive' => -1, //int
			'joins'=>array(
							array(
									'table' => 'ecom_users',
									'alias' => 'User',
									'type' => 'left',
									'conditions' => array('Transaction.user_id=User.id')
							)
						),
			'conditions' => $bal_conditions,
			'order' =>'User.fname, User.lname asc',
			'fields'=>array('Transaction.*',"CONCAT(User.fname,' ',User.lname) as retailer_name,added_by"),
			));
			
			$total_amount = $this->Transaction->find('first',array('conditions'=>$bal_conditions,
														'fields'=>'sum(Transaction.total_amount) as total_amount',
														));

		 	$total_balance = $this->Transaction->find('first',array('conditions'=>$bal_conditions,
												'fields'=>'sum(Transaction.balance) as total_balance',
											));

		 	if(!empty($total_amount[0]['total_amount']))
			{
				$total_retailer_amount = $total_amount[0]['total_amount'];
			}
			else
			{
				$total_retailer_amount = 0.00;
			}

			if(!empty($total_balance[0]['total_balance']))
			{
				$total_retailer_balance = $total_balance[0]['total_balance'];
			}
			else
			{
				$total_retailer_balance = 0.00;
			}
			//echo "tt=".$total_retailer_amount;exit;
			$this->set('get_account_balance_data',$get_account_balance_data);
			$this->set('total_retailer_amount',$total_retailer_amount);
			$this->set('total_retailer_balance',$total_retailer_balance);
			//echo "SDasd".$total_retailer_balance;exit;
			//prd($get_account_balance_data);
			//error_reporting(E_ALL);
			$objPHPExcel = new PHPExcel();
			
			//Setting Column Width
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			
			
			$objPHPExcel->getProperties()->setCreator("Calling Cards")->setLastModifiedBy("Calling Cards")->setTitle("Office 2007 XLSX Document")
																	 ->setSubject("Calling Cards")
																	 ->setDescription("Calling Cards")
																	 ->setKeywords("office 2007 openxml php")
																	 ->setCategory("Export To Excel");		
			
			$objPHPExcel->setActiveSheetIndex(0);		
			$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
			$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
			
			// Title of the Report		
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	       
			$med_name = array();
			$retailer_name = array();
			$reports_of = "Retailer Account Balance Report";
	        if($retailer_id != 0)
			{
				if(isset($get_account_balance_data[0][0]['retailer_name']))
				{
					$retailer_name = $get_account_balance_data[0][0]['retailer_name'];
				}
				else
				{
					$ret_name = $this->User->find('first',array('conditions'=>array('id'=>$retailer_id),'fields'=>'full_name'));
					if(!empty($ret_name))
				    	$retailer_name = $ret_name['User']['full_name'];
				}
				if(isset($get_account_balance_data[0]['User']['added_by']))
					$med_name = $this->User->find('first',array('conditions'=>array('id'=>$get_account_balance_data[0]['User']['added_by']),'fields'=>'full_name'));
				else
					$med_name = $this->User->find('first',array('conditions'=>array('id'=>$mediator_id),'fields'=>'full_name'));
				
				if(!empty($med_name))
				{
					if(strlen($retailer_name) > 21)
					$retailer_name = substr($retailer_name,0,21)."..";
					
					if(strlen($med_name['User']['full_name']) > 21)
					$med_name['User']['full_name'] = substr($med_name['User']['full_name'],0,21)."..";
					
					$reports_of = 'Account Balance Report Of Retailer :'.ucwords($retailer_name). ' of  Mediator: '.ucwords($med_name['User']['full_name']);
				}
			}
			else if($mediator_id != 0)
			{
				$med_name = $this->User->find('first',array('conditions'=>array('id'=>$mediator_id),'fields'=>'full_name'));
			    if(!empty($med_name))
				{
					if(strlen($med_name['User']['full_name']) > 40)
					$med_name['User']['full_name'] = substr($med_name['User']['full_name'],0,40)."..";

					$reports_of = 'Account Balance Report Of Retailers Of Mediator: '.ucwords($med_name['User']['full_name']);	
				}
			}
			
			
			if($retailer_id == 0 && $mediator_id == 0 && $transaction_id !=0)
			{
				
                $retailer_name =  $get_account_balance_data[0][0]['retailer_name'];
				$reports_of = 'Account Balance Report Of Retailer: '.ucwords($retailer_name);
				
			}
			
			$objPHPExcel->getActiveSheet()->setCellValue('A2',$reports_of);
			$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setWrapText(true);
			
			$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
			
			$heading = 4;
			$FirstItemNumber = $heading + 2;

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,'S.No');
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,"Retailer");
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,"Total Amount(€)");
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,"Available Balance(€)");
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$heading,"Modified Date");
		//	$objPHPExcel->getActiveSheet()->setCellValue('G'.$heading,'Total Sales(€)');
			 

			$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getFont()->setBold(true);;
			$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			
			$serial_counter = 0;
			foreach($get_account_balance_data as $keynew=>$val)
			{
				$serial_counter++;
	   
			    $objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,ucwords($val['0']['retailer_name']));
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,number_format($val['Transaction']['total_amount'],2));
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,number_format($val['Transaction']['balance'],2));
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,date('d.m.Y',strtotime($val['Transaction']['updated'])));
				//$objPHPExcel->getActiveSheet()->setCellValue('G'.$FirstItemNumber,number_format($val['Sale']['s_total_sales'],2));
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
				$FirstItemNumber++;
			}
			
			// Setting Last Line Style For Net Total
			$FirstItemNumber++;
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(True)->setSize(13);

	        // Merging Column
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':B'.$FirstItemNumber);

			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,'Total(€)');
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,number_format($total_retailer_amount,2));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,number_format($total_retailer_balance,2));
			
			// Set page margin
			$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);

	        $file_name = 'AccountBalance'.$login_user.'.xls';    
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
	
	public function admin_mediator_balance_report_distributor($mediator_id =0){
			$this->admin_redirect_to_dashboard_distributor();
			//echo "Das".$mediator_id;exit;
			$this->set("title_for_layout",__('Mediator Balance Report'));	
			$login_user = $this->Auth->User('id');
		

			/* Getting Mediator  data*/
			$mediator_conditions = array();
			$mediator_conditions['User.added_by'] = $login_user;
			$mediator_conditions['User.status'] = array('1','2');
			$mediator_conditions['User.role_id'] = 2;
			$fields_mediator = array('User.id','User.full_name');
			$order_mediator = 'fname,lname asc';
			$get_mediator_data = $this->User->find('list',array('conditions'=>$mediator_conditions,'fields'=>$fields_mediator,'order'=>$order_mediator));
                         foreach($get_mediator_data as $k=>$v)
			{
				$get_mediator_data[$k] = ucwords(strtolower($v));
			}
			
			
			$mediator_conditions = array();
			$mediator_conditions['User.status'] = array('1','2');
			$mediator_conditions['User.role_id'] = 2;
			$fields_mediator = array('User.id','User.full_name');
			$order_mediator = 'fname,lname asc';
			
			$get_mediator_data = $this->User->find('list',array('conditions'=>$mediator_conditions,'fields'=>$fields_mediator,'order'=>$order_mediator));
			foreach($get_mediator_data as $key =>$value)
                        {
                            $mediator_list[]= $key;
                            $get_mediator_data[$key]= ucwords(strtolower($value));                    
                        }
			
			//prd($get_mediator_data);
			/* Getting Balance Conditions */
			$bal_conditions = array();
			// If Any Mediator is Selected
			if($mediator_id != 0)
			{
				$bal_conditions['user_id'] = $mediator_id;
			}
			else
			{  
				//If No Mediator Exists
				if(empty($mediator_list))
				{
					$bal_conditions['user_id'] = 0;
				}
				else
				{
					 // If Mediator Exists
				$bal_conditions['user_id'] = $mediator_list;
				}
			}
            
			$this->set('mediator_id',$mediator_id);
			$this->set('mediator_list',$get_mediator_data);
			//echo "ads".$login_user;exit;
			//prd($bal_conditions);exit;
			$get_account_balance_data = $this->Transaction->find('all', array(
			'recursive' => -1, //int
			'joins'=>array(
							array(
									'table' => 'ecom_users',
									'alias' => 'User',
									'type' => 'left',
									'conditions' => array('Transaction.user_id=User.id')
							)
						),
			'order' =>'User.fname, User.lname asc',
			'conditions' => $bal_conditions,
			'fields'=>array('Transaction.*',"CONCAT(User.fname,' ',User.lname) as mediator_name"),
			));
			
			$total_amount = $this->Transaction->find('first',array('conditions'=>$bal_conditions,
														'fields'=>'sum(Transaction.total_amount) as total_amount',
														));

		 	$total_balance = $this->Transaction->find('first',array('conditions'=>$bal_conditions,
												'fields'=>'sum(Transaction.balance) as total_balance',
											));

		 	if(!empty($total_amount[0]['total_amount']))
			{
				$total_retailer_amount = $total_amount[0]['total_amount'];
			}
			else
			{
				$total_retailer_amount = 0.00;
			}

			if(!empty($total_balance[0]['total_balance']))
			{
				$total_retailer_balance = $total_balance[0]['total_balance'];
			}
			else
			{
				$total_retailer_balance = 0.00;
			}
			//echo "tt=".$total_retailer_amount;exit;
			//prd($get_account_balance_data);
			$this->set('get_account_balance_data',$get_account_balance_data);
			$this->set('total_retailer_amount',$total_retailer_amount);
			$this->set('total_retailer_balance',$total_retailer_balance);
			
	 }
	 
	public function admin_mediator_balance_report_excel_distributor ($mediator_id = 0,$transaction_id = 0){
  		$this->admin_redirect_to_dashboard_distributor();
  		ini_set('max_execution_time', 0);
		// Create new PHPExcel object
			
		$login_user = $this->Auth->User('id');
		

		/* Getting Mediator  data*/
			$mediator_conditions = array();
			$mediator_conditions['User.added_by'] = $login_user;
			$mediator_conditions['User.status'] = array('1','2');
			$mediator_conditions['User.role_id'] = 2;
			$fields_mediator = array('User.id','User.full_name');
			$order_mediator = 'fname,lname asc';
			$get_mediator_data = $this->User->find('list',array('conditions'=>$mediator_conditions,'fields'=>$fields_mediator,'order'=>$order_mediator));
            foreach($get_mediator_data as $k=>$v)
			{
				$get_mediator_data[$k] = ucwords($v);
			}
			 
			
			$mediator_conditions = array();
			$mediator_conditions['User.status'] = array('1','2');
			$mediator_conditions['User.role_id'] = 2;
			$fields_mediator = array('User.id','User.full_name');
			$order_mediator = 'fname,lname asc';
			
			$get_mediator_data = $this->User->find('list',array('conditions'=>$mediator_conditions,'fields'=>$fields_mediator,'order'=>$order_mediator));
			foreach($get_mediator_data as $key =>$value)	
			$mediator_list[]= $key;
			
			
			//prd($get_mediator_data);
			/* Getting Balance Conditions */
			$bal_conditions = array();
			// If Any Mediator is Selected
			if($mediator_id != 0)
			{
				$bal_conditions['user_id'] = $mediator_id;
			}
			else
			{  
				//If No Mediator Exists
				if(empty($mediator_list))
				{
					$bal_conditions['user_id'] = 0;
				}
				else
				{
					 // If Mediator Exists
				$bal_conditions['user_id'] = $mediator_list;
				}
			}
            
            
			
		    $this->set('mediator_id',$mediator_id);

			
			if($transaction_id != 0)
			{
				$bal_conditions['Transaction.id'] = $transaction_id;
			}
			
			$this->set('mediator_list',$get_mediator_data);
			//echo "ads".$login_user;exit;
			//prd($bal_conditions);exit;
			$get_account_balance_data = $this->Transaction->find('all', array(
			'recursive' => -1, //int
			'joins'=>array(
							array(
									'table' => 'ecom_users',
									'alias' => 'User',
									'type' => 'left',
									'conditions' => array('Transaction.user_id=User.id')
							)
						),
			'conditions' => $bal_conditions,
			'order' =>'User.fname, User.lname asc',
			'fields'=>array('Transaction.*',"CONCAT(User.fname,' ',User.lname) as mediator_name"),
		));
			//prd($get_account_balance_data);
			$total_amount = $this->Transaction->find('first',array('conditions'=>$bal_conditions,
														'fields'=>'sum(Transaction.total_amount) as total_amount',
														));

		 	$total_balance = $this->Transaction->find('first',array('conditions'=>$bal_conditions,
												'fields'=>'sum(Transaction.balance) as total_balance',
											));

		 	if(!empty($total_amount[0]['total_amount']))
			{
				$total_retailer_amount = $total_amount[0]['total_amount'];
			}
			else
			{
				$total_retailer_amount = 0.00;
			}

			if(!empty($total_balance[0]['total_balance']))
			{
				$total_retailer_balance = $total_balance[0]['total_balance'];
			}
			else
			{
				$total_retailer_balance = 0.00;
			}
			//echo "tt=".$total_retailer_amount;exit;
			$this->set('get_account_balance_data',$get_account_balance_data);
			$this->set('total_retailer_amount',$total_retailer_amount);
			$this->set('total_retailer_balance',$total_retailer_balance);
			//echo "SDasd".$total_retailer_balance;exit;
			//prd($get_account_balance_data);
			//error_reporting(E_ALL);
			$objPHPExcel = new PHPExcel();
			
			//Setting Column Width
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			
			
			$objPHPExcel->getProperties()->setCreator("Calling Cards")->setLastModifiedBy("Calling Cards")->setTitle("Office 2007 XLSX Document")
																	 ->setSubject("Calling Cards")
																	 ->setDescription("Calling Cards")
																	 ->setKeywords("office 2007 openxml php")
																	 ->setCategory("Export To Excel");		
			
			$objPHPExcel->setActiveSheetIndex(0);		
			$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
			$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
			
			// Title of the Report		
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	       
			$med_name = array();
			$retailer_name = array();
			$reports_of = "Mediator Account Balance Report";
	        if($mediator_id != 0)
			{
				if(isset($get_account_balance_data[0][0]['mediator_name']))
				{
					$mediator_name = $get_account_balance_data[0][0]['mediator_name'];
				}
				else
				{
					$med_name = $this->User->find('first',array('conditions'=>array('id'=>$mediator_id),'fields'=>'full_name'));
					if(!empty($med_name))
				    	$mediator_name = $med_name['User']['full_name'];
				}
				if(isset($get_account_balance_data[0]['User']['added_by']))
					$med_name = $this->User->find('first',array('conditions'=>array('id'=>$get_account_balance_data[0]['User']['added_by']),'fields'=>'full_name'));
				else
					$med_name = $this->User->find('first',array('conditions'=>array('id'=>$mediator_id),'fields'=>'full_name'));
				
				if(!empty($med_name))
				{
					if(strlen($mediator_name) > 21)
					$mediator_name = substr($mediator_name,0,21)."..";
					
					if(strlen($med_name['User']['full_name']) > 21)
					$med_name['User']['full_name'] = substr($med_name['User']['full_name'],0,21)."..";
					
					$reports_of = 'Account Balance Report Of Mediator: '.ucwords($med_name['User']['full_name']);
				}
			}
			
			
			if($mediator_id == 0 && $transaction_id !=0)
			{
				
                $mediator_name =  $get_account_balance_data[0][0]['mediator_name'];
				$reports_of = 'Account Balance Report Of Mediator: '.ucwords($mediator_name);
				
			}
			
			$objPHPExcel->getActiveSheet()->setCellValue('A2',$reports_of);
			$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setWrapText(true);
			
			$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
			
			$heading = 4;
			$FirstItemNumber = $heading + 2;

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,'S.No');
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,"Mediator");
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,"Total Amount(€)");
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,"Available Balance(€)");
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$heading,"Modified Date");
		//	$objPHPExcel->getActiveSheet()->setCellValue('G'.$heading,'Total Sales(€)');
			 

			$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getFont()->setBold(true);;
			$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			
			$serial_counter = 0;
			foreach($get_account_balance_data as $keynew=>$val)
			{
				$serial_counter++;
	   
			    $objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,ucwords($val['0']['mediator_name']));
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,number_format($val['Transaction']['total_amount'],2));
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,number_format($val['Transaction']['balance'],2));
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,date('d.m.Y',strtotime($val['Transaction']['updated'])));
				//$objPHPExcel->getActiveSheet()->setCellValue('G'.$FirstItemNumber,number_format($val['Sale']['s_total_sales'],2));
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);

				$FirstItemNumber++;
			}
			
			// Setting Last Line Style For Net Total
			$FirstItemNumber++;
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(True)->setSize(13);

	        // Merging Column
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':B'.$FirstItemNumber);

			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,'Total(€)');
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,number_format($total_retailer_amount,2));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,number_format($total_retailer_balance,2));
			
			// Set page margin
			$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);

	        $file_name = 'AccountBalance'.$login_user.'.xls';    
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

	///////////

	public function admin_retailer_balance_report($retailer_id =0){
			$this->admin_redirect_to_dashboard_mediator();
			//echo "Das".$mediator_id;exit;
			$this->set("title_for_layout",__('Retailer Balance Report'));	
			$login_user = $this->Auth->User('id');
		

			/* Getting Retailer  data*/
			$retailer_conditions = array();
			$retailer_conditions['User.added_by'] = $login_user;
			$retailer_conditions['User.status'] = array('1','2');
			$retailer_conditions['User.role_id'] = 3;
			$fields_retailer = array('User.id','User.full_name');
			$order_retailer = 'fname,lname asc';
			$get_retailer_data = $this->User->find('list',array('conditions'=>$retailer_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
            foreach($get_retailer_data as $k=>$v)
			{
				$get_retailer_data[$k] = ucwords($v);
			}
			 
			
			$retailer_conditions = array();
			$retailer_conditions['User.added_by'] = $login_user;
			$retailer_conditions['User.status'] = array('1','2');
			$retailer_conditions['User.role_id'] = 3;
			$fields_retailer = array('User.id','User.full_name');
			$order_retailer = 'fname,lname asc';
			
			$get_retailer_data = $this->User->find('list',array('conditions'=>$retailer_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
			foreach($get_retailer_data as $key =>$value)	
			$retailer_list[]= $key;
			
			
			//prd($get_retailer_data);
			/* Getting Balance Conditions */
			$bal_conditions = array();
			$bal_conditions['allocator_id'] = $login_user;
			// If Any Retailer is Selected
			if($retailer_id != 0)
			{
				$bal_conditions['user_id'] = $retailer_id;
			}
			else
			{  
				//If No Retailer Exists
				if(empty($retailer_list))
				{
					$bal_conditions['user_id'] = 0;
				}
				else
				{
					 // If Retailer Exists
				$bal_conditions['user_id'] = $retailer_list;
				}
			}
            
            
			
		    $this->set('retailer_id',$retailer_id);
			$this->set('retailer_list',$get_retailer_data);
			//echo "ads".$login_user;exit;
			//prd($bal_conditions);exit;
			$get_account_balance_data = $this->Transaction->find('all', array(
			'recursive' => -1, //int
			'joins'=>array(
							array(
									'table' => 'ecom_users',
									'alias' => 'User',
									'type' => 'left',
									'conditions' => array('Transaction.user_id=User.id')
							)
						),
			'conditions' => $bal_conditions,
			'order' =>'User.fname, User.lname asc',
			'fields'=>array('Transaction.*',"CONCAT(User.fname,' ',User.lname) as retailer_name"),
			));
			
			$total_amount = $this->Transaction->find('first',array('conditions'=>$bal_conditions,
														'fields'=>'sum(Transaction.total_amount) as total_amount',
														));

		 	$total_balance = $this->Transaction->find('first',array('conditions'=>$bal_conditions,
												'fields'=>'sum(Transaction.balance) as total_balance',
											));

		 	if(!empty($total_amount[0]['total_amount']))
			{
				$total_retailer_amount = $total_amount[0]['total_amount'];
			}
			else
			{
				$total_retailer_amount = 0.00;
			}

			if(!empty($total_balance[0]['total_balance']))
			{
				$total_retailer_balance = $total_balance[0]['total_balance'];
			}
			else
			{
				$total_retailer_balance = 0.00;
			}
			//echo "tt=".$total_retailer_amount;exit;
			//prd($get_account_balance_data);
			$this->set('get_account_balance_data',$get_account_balance_data);
			$this->set('total_retailer_amount',$total_retailer_amount);
			$this->set('total_retailer_balance',$total_retailer_balance);
			
	 }
	 
	public function admin_retailer_balance_report_excel($retailer_id = 0,$transaction_id = 0){
  		$this->admin_redirect_to_dashboard_mediator();
  		ini_set('max_execution_time', 0);
		// Create new PHPExcel object
			
		$login_user = $this->Auth->User('id');
		

		/* Getting Retailer  data*/
			$retailer_conditions = array();
			$retailer_conditions['User.added_by'] = $login_user;
			$retailer_conditions['User.status'] = array('1','2');
			$retailer_conditions['User.role_id'] = 3;
			$fields_retailer = array('User.id','User.full_name');
			$order_retailer = 'fname,lname asc';
			$get_retailer_data = $this->User->find('list',array('conditions'=>$retailer_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
            foreach($get_retailer_data as $k=>$v)
			{
				$get_retailer_data[$k] = ucwords($v);
			}
			 
			
			$retailer_conditions = array();
			$retailer_conditions['User.added_by'] = $login_user;
			$retailer_conditions['User.status'] = array('1','2');
			$retailer_conditions['User.role_id'] = 3;
			$fields_retailer = array('User.id','User.full_name');
			$order_retailer = 'fname,lname asc';
			
			$get_retailer_data = $this->User->find('list',array('conditions'=>$retailer_conditions,'fields'=>$fields_retailer,'order'=>$order_retailer));
			foreach($get_retailer_data as $key =>$value)	
			$retailer_list[]= $key;
			//prd($retailer_list);
			//prd($get_retailer_data);
			/* Getting Balance Conditions */
			$bal_conditions = array();
			$bal_conditions['allocator_id'] = $login_user;
			// If Any Retailer is Selected
			if($retailer_id != 0)
			{
				$bal_conditions['user_id'] = $retailer_id;
			}
			else
			{  
				//If No Retailer Exists
				if(empty($retailer_list))
				{
					$bal_conditions['user_id'] = 0;
				}
				else
				{
					 // If Retailer Exists
				$bal_conditions['user_id'] = $retailer_list;
				}
			}
            
            
			
		    $this->set('retailer_id',$retailer_id);

			
			if($transaction_id != 0)
			{
				$bal_conditions['Transaction.id'] = $transaction_id;
			}
			
			$this->set('mediator_list',$get_retailer_data);
			//echo "ads".$login_user;exit;
			//prd($bal_conditions);exit;
			$get_account_balance_data = $this->Transaction->find('all', array(
			'recursive' => -1, //int
			'joins'=>array(
							array(
									'table' => 'ecom_users',
									'alias' => 'User',
									'type' => 'left',
									'conditions' => array('Transaction.user_id=User.id')
							)
						),
			'conditions' => $bal_conditions,
			'order' =>'User.fname, User.lname asc',
			'fields'=>array('Transaction.*',"CONCAT(User.fname,' ',User.lname) as retailer_name"),
			));
			//prd($get_account_balance_data);
			$total_amount = $this->Transaction->find('first',array('conditions'=>$bal_conditions,
														'fields'=>'sum(Transaction.total_amount) as total_amount',
														));

		 	$total_balance = $this->Transaction->find('first',array('conditions'=>$bal_conditions,
												'fields'=>'sum(Transaction.balance) as total_balance',
											));

		 	if(!empty($total_amount[0]['total_amount']))
			{
				$total_retailer_amount = $total_amount[0]['total_amount'];
			}
			else
			{
				$total_retailer_amount = 0.00;
			}

			if(!empty($total_balance[0]['total_balance']))
			{
				$total_retailer_balance = $total_balance[0]['total_balance'];
			}
			else
			{
				$total_retailer_balance = 0.00;
			}
			//echo "tt=".$total_retailer_amount;exit;
			$this->set('get_account_balance_data',$get_account_balance_data);
			$this->set('total_retailer_amount',$total_retailer_amount);
			$this->set('total_retailer_balance',$total_retailer_balance);
			//echo "SDasd".$total_retailer_balance;exit;
			//prd($get_account_balance_data);
			//error_reporting(E_ALL);
			$objPHPExcel = new PHPExcel();
			
			//Setting Column Width
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			
			
			$objPHPExcel->getProperties()->setCreator("Calling Cards")->setLastModifiedBy("Calling Cards")->setTitle("Office 2007 XLSX Document")
																	 ->setSubject("Calling Cards")
																	 ->setDescription("Calling Cards")
																	 ->setKeywords("office 2007 openxml php")
																	 ->setCategory("Export To Excel");		
			
			$objPHPExcel->setActiveSheetIndex(0);		
			$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
			$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
			
			// Title of the Report		
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	       
			$med_name = array();
			$retailer_name = array();
			$reports_of = "Retailer Account Balance Report";

	        if($retailer_id != 0)
			{
				if(isset($get_account_balance_data[0][0]['retailer_name']))
				{
					$retailer_name = $get_account_balance_data[0][0]['retailer_name'];
				}
				else
				{
					$ret_name = $this->User->find('first',array('conditions'=>array('id'=>$retailer_id),'fields'=>'full_name'));
					if(!empty($ret_name))
				    	$retailer_name = $ret_name['User']['full_name'];
				}
				if(isset($get_account_balance_data[0]['User']['added_by']))
					$med_name = $this->User->find('first',array('conditions'=>array('id'=>$get_account_balance_data[0]['User']['added_by']),'fields'=>'full_name'));
				else
					$med_name = $this->User->find('first',array('conditions'=>array('id'=>$retailer_id),'fields'=>'full_name'));

				if(!empty($med_name))
				{
					if(strlen($retailer_name) > 21)
					$retailer_name = substr($retailer_name,0,21)."..";
					
					if(strlen($med_name['User']['full_name']) > 21)
					$med_name['User']['full_name'] = substr($med_name['User']['full_name'],0,21)."..";
					
					$reports_of = 'Account Balance Report Of Retailer: '.ucwords($med_name['User']['full_name']);
				}
			}
			
			
			if($retailer_id == 0 && $transaction_id !=0)
			{
				
                $retailer_name =  $get_account_balance_data[0][0]['retailer_name'];
				$reports_of = 'Account Balance Report Of Retailer: '.ucwords($retailer_name);
				
			}
			
			$objPHPExcel->getActiveSheet()->setCellValue('A2',$reports_of);
			$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setWrapText(true);
			
			$styleThinBlackBorderOutline = array('borders' =>array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),),),);
			
			$heading = 4;
			$FirstItemNumber = $heading + 2;

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$heading,'S.No');
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$heading,"Retailer");
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$heading,"Total Amount(€)");
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$heading,"Available Balance(€)");
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$heading,"Modified Date");
		//	$objPHPExcel->getActiveSheet()->setCellValue('G'.$heading,'Total Sales(€)');
			 

			$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getFont()->setBold(true);;
			$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$heading.':G'.$heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			
			$serial_counter = 0;
			foreach($get_account_balance_data as $keynew=>$val)
			{
				$serial_counter++;
	   
			    $objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,$serial_counter);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$FirstItemNumber,ucwords($val['0']['retailer_name']));
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,number_format($val['Transaction']['total_amount'],2));
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,number_format($val['Transaction']['balance'],2));
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$FirstItemNumber,date('d.m.Y',strtotime($val['Transaction']['updated'])));
				//$objPHPExcel->getActiveSheet()->setCellValue('G'.$FirstItemNumber,number_format($val['Sale']['s_total_sales'],2));
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(false)->setSize(11);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);

				$FirstItemNumber++;
			}
			
			// Setting Last Line Style For Net Total
			$FirstItemNumber++;
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber.':G'.$FirstItemNumber)->getFont()->setBold(True)->setSize(13);

	        // Merging Column
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$FirstItemNumber.':B'.$FirstItemNumber);

			$objPHPExcel->getActiveSheet()->getStyle('A'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$FirstItemNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$FirstItemNumber,'Total(€)');
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$FirstItemNumber,number_format($total_retailer_amount,2));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$FirstItemNumber,number_format($total_retailer_balance,2));
			
			// Set page margin
			$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);

	        $file_name = 'AccountBalance'.$login_user.'.xls';    
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
