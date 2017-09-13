<?php
App::uses('AppController', 'Controller');

class InvoicesController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('');
	}
	
   public function index(){ 
	    $this->set("title_for_layout",__('Invoice'));	
		
		if($this->request->data)
		{
			$invoice_conditions = array();
			if(!empty($this->request->data['datepicker1']) && $this->request->data['datepicker2'])
			{
				$start_date = $this->request->data['datepicker1'];
				$end_date = $this->request->data['datepicker2'];
				
				$new_start_date = explode('.',$start_date);
				$start_month = $new_start_date[1];
				$start_year = $new_start_date[2];
				$start_date = $new_start_date[0];
				
				$new_end_date = explode('.',$end_date);
				$end_month = $new_end_date[1];
				$end_year = $new_end_date[2];
				$end_date = $new_end_date[0];


				$invoice_conditions['invoice_date_month >='] = $start_year."-".$start_month."-".$start_date;
				$invoice_conditions['invoice_date_month <='] = $end_year."-".$end_month."-".$end_date;

			}
			//$invoice_conditions['MONTH(invoice_date_month)'] = $start_month;
			//$invoice_conditions['YEAR(invoice_date_month)'] = $start_year;
			$invoice_conditions['user_id'] = $this->Auth->User('id');
			$invoice_conditions['invoice_status'] = 1;
 
			//prd($invoice_conditions);

			$group = 'user_id,MONTH(invoice_date_month),YEAR(invoice_date_month)';
			$get_invoice_data = $this->Invoice->find('all',array('conditions'=>$invoice_conditions,'group'=>$group));
			$this->set('get_invoice_data',$get_invoice_data);
		}
		else
		{
			$invoice_conditions = array();
			$invoice_conditions['user_id'] = $this->Auth->User('id');
			$invoice_conditions['invoice_status'] = 1;

			$group = 'user_id,MONTH(invoice_date_month),YEAR(invoice_date_month)';
			$get_invoice_data = $this->Invoice->find('all',array('conditions'=>$invoice_conditions,'group'=>$group));
			$this->set('get_invoice_data',$get_invoice_data);
		}
   }
   
   public function generate_invoice(){
    
	 $this->layout = 'ajax';
	 $invoice_number = $this->request->data['invoice_number'];
     $invoice_conditions = array();
	 $invoice_conditions['invoice_number'] = $invoice_number;
	 
	 $last_invoice_number = explode('_',$invoice_number);
	 if(count ($last_invoice_number) != 4)
	 {
		$this->Session->setFlash(__('Invalid invoice number.'), 'default', array(), 'error');
		$this->redirect(array('controller'=>'Invoices', 'action'=>'index'));
	 }
	 else
	 {
	 	if($last_invoice_number[3] != $this->Auth->User('id'))
		{
			$this->Session->setFlash(__('Invalid invoice number.'), 'default', array(), 'error');
			$this->redirect(array('controller'=>'Invoices', 'action'=>'index'));
		}
		$group = 'card_id';
		 
		$get_invoice_data = $this->Invoice->find('all',array('conditions'=>$invoice_conditions));
		$get_user_data = $this->User->findById($get_invoice_data[0]['Invoice']['user_id']);

		$this->loadModel('Websetting');
        $keys_setting = array(
 								'Site.tax_id',
 								'Site.email',
 								'Site.text_field1',
 								'Site.text_field2',
 								'Site.text_field3',
 								'Site.text_field4',
 								'Site.bank_details',
 								'Site.phone',
 								'Site.fax',
 								'Site.address',
 								'Site.distributor_name'
        	                 );

		$web_setting = $this->Websetting->find('all', array(
				'conditions' =>array(
					'Websetting.key' =>$keys_setting,
					'is_show' =>'1'
				),
				'order' => array('setting_order ASC')));
        $setting_data = array(); 
        foreach ($web_setting as $value) 
        {
       	     if($value['Websetting']['key'] == 'Site.tax_id')
       	     {
       	     	$setting_data['tax_id'] = $value['Websetting']['value']; 
       	     }  
             if($value['Websetting']['key'] == 'Site.phone')
       	     {
       	     	$setting_data['phone'] = $value['Websetting']['value']; 
       	     }
       	     if($value['Websetting']['key'] == 'Site.fax')
       	     {
       	     	$setting_data['fax'] = $value['Websetting']['value']; 
       	     }  
       	     if($value['Websetting']['key'] == 'Site.email')
       	     {
       	     	$setting_data['email'] = $value['Websetting']['value']; 
       	     }  
       	     if($value['Websetting']['key'] == 'Site.distributor_name')
       	     {
       	     	$setting_data['distributor_name'] = $value['Websetting']['value']; 
       	     }  
       	     if($value['Websetting']['key'] == 'Site.text_field1')
       	     {
       	     	$setting_data['text_field1'] = $value['Websetting']['value']; 
       	     }  
       	     if($value['Websetting']['key'] == 'Site.text_field2')
       	     {
       	     	$setting_data['text_field2'] = $value['Websetting']['value']; 
       	     }  
       	     if($value['Websetting']['key'] == 'Site.text_field3')
       	     {
       	     	$setting_data['text_field3'] = $value['Websetting']['value']; 
       	     }  
       	     if($value['Websetting']['key'] == 'Site.text_field4')
       	     {
       	     	$setting_data['text_field4'] = $value['Websetting']['value']; 
       	     }  
       	     if($value['Websetting']['key'] == 'Site.bank_details')
       	     {
       	     	$setting_data['bank_details'] = $value['Websetting']['value']; 
       	     } 
       	     if($value['Websetting']['key'] == 'Site.address')
       	     {
       	     	$setting_data['address'] = $value['Websetting']['value']; 
       	     }    
         }
         $this->set('get_invoice_data',$get_invoice_data);
		 $this->set('get_user_data',$get_user_data);
		 $this->set('setting_data',$setting_data);  
	 }
   }

     public function admin_generate_invoice(){
    
	 $this->layout = 'ajax';
	 $invoice_number = $this->request->data['invoice_number'];
     $invoice_conditions = array();
	 $invoice_conditions['invoice_number'] = $invoice_number;
	 
	 $last_invoice_number = explode('_',$invoice_number);
	 if(count ($last_invoice_number) != 4)
	 {
		$this->Session->setFlash(__('Invalid invoice number.'), 'default', array(), 'error');
		$this->redirect(array('controller'=>'Invoices', 'action'=>'index'));
	 }
	 else
	 {
	 	$group = 'card_id';
		$get_invoice_data = $this->Invoice->find('all',array('conditions'=>$invoice_conditions));
		$get_user_data = $this->User->findById($get_invoice_data[0]['Invoice']['user_id']);

		$this->loadModel('Websetting');
        $keys_setting = array(
 								'Site.tax_id',
 								'Site.email',
 								'Site.text_field1',
 								'Site.text_field2',
 								'Site.text_field3',
 								'Site.text_field4',
 								'Site.bank_details',
 								'Site.phone',
 								'Site.fax',
 								'Site.address',
 								'Site.distributor_name'
        	                 );

		$web_setting = $this->Websetting->find('all', array(
				'conditions' =>array(
					'Websetting.key' =>$keys_setting,
					'is_show' =>'1'
				),
				'order' => array('setting_order ASC')));
        $setting_data = array(); 
        foreach ($web_setting as $value) 
        {
       	     if($value['Websetting']['key'] == 'Site.tax_id')
       	     {
       	     	$setting_data['tax_id'] = $value['Websetting']['value']; 
       	     }  
             if($value['Websetting']['key'] == 'Site.phone')
       	     {
       	     	$setting_data['phone'] = $value['Websetting']['value']; 
       	     }
       	     if($value['Websetting']['key'] == 'Site.fax')
       	     {
       	     	$setting_data['fax'] = $value['Websetting']['value']; 
       	     }  
       	     if($value['Websetting']['key'] == 'Site.email')
       	     {
       	     	$setting_data['email'] = $value['Websetting']['value']; 
       	     }  
       	     if($value['Websetting']['key'] == 'Site.distributor_name')
       	     {
       	     	$setting_data['distributor_name'] = $value['Websetting']['value']; 
       	     }  
       	     if($value['Websetting']['key'] == 'Site.text_field1')
       	     {
       	     	$setting_data['text_field1'] = $value['Websetting']['value']; 
       	     }  
       	     if($value['Websetting']['key'] == 'Site.text_field2')
       	     {
       	     	$setting_data['text_field2'] = $value['Websetting']['value']; 
       	     }  
       	     if($value['Websetting']['key'] == 'Site.text_field3')
       	     {
       	     	$setting_data['text_field3'] = $value['Websetting']['value']; 
       	     }  
       	     if($value['Websetting']['key'] == 'Site.text_field4')
       	     {
       	     	$setting_data['text_field4'] = $value['Websetting']['value']; 
       	     }  
       	     if($value['Websetting']['key'] == 'Site.bank_details')
       	     {
       	     	$setting_data['bank_details'] = $value['Websetting']['value']; 
       	     } 
       	     if($value['Websetting']['key'] == 'Site.address')
       	     {
       	     	$setting_data['address'] = $value['Websetting']['value']; 
       	     }    
         }
         $this->set('get_invoice_data',$get_invoice_data);
		 $this->set('get_user_data',$get_user_data);
		 $this->set('setting_data',$setting_data);
	 }

   }
    
 public function get_invoice_pdf() //Common Code for all pdf's......
 {
  	     $get_excutiontime =  ini_get('max_execution_time');
		 ini_set('max_execution_time',0);
  	     
		//	require_once(APPLICATION_PATH.'app/Vendor/tcpdf/config/lang/eng.php');
		//	require_once(APPLICATION_PATH.'app/Vendor/tcpdf/tcpdf.php');
		include(APP.'Vendor/tcpdf/config/lang/eng.php');
		include(APP.'Vendor/tcpdf/tcpdf.php');
	
		$html=$this->request->data['Invoice']['invoice_data'];
		$invoice_number=$this->request->data['Invoice']['invoice_number'];
		
		// create new PDF document
		$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				
		// set document information
		$pdf->SetCreator('Calling Cards');
		$pdf->SetAuthor('Calling Cards');
		$pdf->SetTitle('Calling Cards Invoice :'.$invoice_number);
		$pdf->SetSubject('Invoice');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		//set margins
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT,true);
		
		$pdf->SetMargins(10,10,10,true);
		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(15);
		
		$pdf->setPrintHeader(false);
		// $pdf->setPrintFooter(true);
		
		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 45);
		//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		//set some language-dependent strings
		$pdf->setLanguageArray($l);
		
		// set default font subsetting mode
		$pdf->setFontSubsetting(true);
		
		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$pdf->SetFont('helvetica', 'B', 10);
			
		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();
		
		// Print text using writeHTMLCell()
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();
		// Close and output PDF document
		ob_start();
		$pdf->output($invoice_number, 'D');
		ob_end_flush();
		exit;		
   }

	 public function admin_get_invoice_pdf() //Common Code for all pdf's......
	 {
	  	 $get_excutiontime =  ini_get('max_execution_time');
		 ini_set('max_execution_time',0);
  	     
		//	require_once(APPLICATION_PATH.'app/Vendor/tcpdf/config/lang/eng.php');
		//	require_once(APPLICATION_PATH.'app/Vendor/tcpdf/tcpdf.php');
		include(APP.'Vendor/tcpdf/config/lang/eng.php');
		include(APP.'Vendor/tcpdf/tcpdf.php');
	
		$html=$this->request->data['Invoices']['invoice_data'];
		$invoice_number=$this->request->data['Invoices']['invoice_number'];
		
		// create new PDF document
		$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				
		// set document information
		$pdf->SetCreator('Calling Cards');
		$pdf->SetAuthor('Calling Cards');
		$pdf->SetTitle('Calling Cards Invoice :'.$invoice_number);
		$pdf->SetSubject('Invoice');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		//set margins
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT,true);
		
		$pdf->SetMargins(10,10,10,true);
		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(15);
		
		$pdf->setPrintHeader(false);
		// $pdf->setPrintFooter(true);
		
		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 45);
		//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		//set some language-dependent strings
		$pdf->setLanguageArray($l);
		
		// set default font subsetting mode
		$pdf->setFontSubsetting(true);
		
		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$pdf->SetFont('helvetica', 'B', 10);
			
		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();
		
		// Print text using writeHTMLCell()
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();
		// Close and output PDF document
		ob_start();
		$pdf->output($invoice_number, 'D');
		ob_end_flush();
		exit;		
   }	
	
   public function download($file_name = NULL)
   {
		$file_path = WWW_ROOT .'img/card_icons/'.$file_name;
		if(file_exists($file_path))
		{
       		if($fd = fopen ($file_path, "r")) {
					$fsize 			= filesize($file_path);
					$path_parts = pathinfo($file_path);
					$ext 				= strtolower($path_parts["extension"]);
					switch ($ext) {
						case "doc":
						header("Content-type: application/doc"); // add here more headers for diff. extensions
						header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
						case "xls":
						header("Content-type: application/xls"); // add here more headers for diff. extensions
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
				}
		}
		else
		{
			$this->Session->setFlash(__('File Does not exists.'), 'default', array(), 'error');
			$this->redirect(array('controller'=>'Invoices', 'action'=>'index'));
		}
		exit;
	}
	
	public function admin_download($file_name = NULL)
   {
		$file_path = WWW_ROOT .'img/card_icons/'.$file_name;
		if(file_exists($file_path))
		{
       		if($fd = fopen ($file_path, "r")) {
					$fsize 			= filesize($file_path);
					$path_parts = pathinfo($file_path);
					$ext 				= strtolower($path_parts["extension"]);
					switch ($ext) {
						case "doc":
						header("Content-type: application/doc"); // add here more headers for diff. extensions
						header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
						case "xls":
						header("Content-type: application/xls"); // add here more headers for diff. extensions
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
				}
		}
		else
		{
			$this->Session->setFlash(__('File Does not exists.'), 'default', array('class'=>'error'));
			$this->redirect(array('controller'=>'Pages', 'action'=>'invoice_list'));
		}
		exit;
	}

	public function admin_changestatus(){
		
		if($this->request->is('ajax'))
		{	
			$id = $this->request->data['id'];
			$status = $this->request->data['st'];
			
			unset($this->request->data);
			//$this->User->set(array('User' => array('id'=>$id, 'status'=>$status)));
			$get_invoice_data = $this->Invoice->findById($id);
            $invoice_number = $get_invoice_data['Invoice']['invoice_number'];
			
			$res =$this->Invoice->updateAll(array('Invoice.invoice_status'=>$status),array('Invoice.invoice_number'=>$invoice_number));
			if($res)
			{
				echo '1'; 
			}
			else
			{
				echo '0'; 
			}
			exit;
		}
	}

	public function admin_delete_invoice(){
		
		if($this->request->is('ajax'))
		{	
			$invoice_number = $this->request->data['invoice_number'];
			unset($this->request->data);
			//$this->User->set(array('User' => array('id'=>$id, 'status'=>$status)));
			
			$get_invoice_data = $this->Invoice->find('all',array('conditions'=>array('invoice_number'=>$invoice_number)));
			
			foreach ($get_invoice_data as $value) {
				$res =$this->Invoice->delete($value['Invoice']['id']);
			}
			if($res)
			{
				echo '1'; 
			}
			else
			{
				echo '0'; 
			}
			exit;
		}
	}
}
