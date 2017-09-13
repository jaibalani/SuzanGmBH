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
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class LanguagesController extends AppController {

/**
 * Controller name
 *
 * @var string
 * @access public
 */
	public $name = 'Languages';

/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
	public $uses = array('Language');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('changeSiteLanguage');
	}
	
	

/**
 * Admin index
 *
 * @return void
 * @access public
 */
	public function admin_index() {
		//pr(Configure::read('Config.language')); exit;
		$this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout','View Languages');
	}

/**
  * Generate grid action in Language Controller(Generate grid for Index action)
  *
  */ 
	public function admin_generategrid(){
		
		$language_cont = $this->Language->find('all');
 		
		$page  = $this->request->query['page'];
		$limit = $this->request->query['rows'];
		$sidx  = $this->request->query['sidx'];
		$sord  = $this->request->query['sord'];
		
		if(!$sidx) $sidx =1;
		
		$order_by = $sidx.' '.$sord;

		$conditions = array();
		
		if(isset($this->request->query['filters'])){
			$filters = json_decode($this->request->query['filters'], true);
			foreach($filters['rules'] as $each_filter){
			
				if($each_filter['field'] == 'id'){
					$conditions['Language.id'] = Sanitize::clean($each_filter['data']);
				}
			
				if($each_filter['field'] == 'title'){
					$conditions['Language.title LIKE'] ="%". Sanitize::escape($each_filter['data']). '%';
				}
				
				
			}
			
		}

		$count = $this->Language->find('count',array(
				'recursive' => -1, 
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
		
		
		$language_cont = $this->Language->find('all',array(
			'conditions' => $conditions,
			'order' => $order_by,
			'limit' => $limit,
			'offset' => $start
			)
		);
		
		$responce = new stdClass();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count; 
		
		$i=0;
		
		
		if(is_array($language_cont))
		{
			foreach($language_cont as $language):
			{

				$title = $language['Language']['title'];
				$native = $language['Language']['native'];
				$edit_locale = Router::url(array('controller'=>'Languages','action'=>'editlocale','admin'=>true,$language['Language']['id']));
				
				if($language['Language']['id']==1){
					$edit_locale = '#';
				}
				
				//'.$this->frontContImage("translate_admin.png","Edit").'
				$link_edit_locale = '<a  href="'.$edit_locale.'" style="color:#438CE5 !important;">Manage Keywords</a>';
				$edit = Router::url(array('controller'=>'Languages','action'=>'edit','admin'=>true,$language['Language']['id']));
				//'.$this->frontContImage("edit_admin.png","Edit").'
				$link_edit = '<a href="'.$edit.'" style="color:#438CE5 !important;">Edit |</a>';
				
                $action = $link_edit." ".$link_edit_locale;
				//$responce->rows[$i]['cell']=array(($i+1),$title,$native,$link_edit,$link_edit_locale);
				$responce->rows[$i]['cell']=array(($i+1),$title,$native,$action); 
				$i++; 
			} 
			
			endforeach;
		}
		echo json_encode($responce); exit;
	}

	public function admin_add() {
		$this->set('title_for_layout', __('Add a new language'));
        $this->set('Back','Back');
		if ($this->request->is('post') && !empty($this->request->data)) {
				// if destination folder already exist, then redirect.
				$locale = $this->request->data['Language']['locale'];
				if (is_dir(APP . 'Locale' . DS . $locale)) {
					$this->Session->setFlash(__('Locale already exists.'), 'default', array('class' => 'error'));
					$this->redirect(array('action' => 'add'));
				}
				$this->request->data['Language']['alias'] = $this->request->data['Language']['locale'];
				
				$file = $this->request->data['Language']['language_flag'];
				unset($this->request->data['Language']['language_flag']);
				
				$moved = false;
				if (!empty($file['name'])) {
						
						$newFileName = substr(md5(time()),0,5) . '-' . $file['name'];
						$destination = WWW_ROOT . 'img'. DS . 'admin_uploads'. DS .'flags' . DS . $newFileName;
						
						$moved = move_uploaded_file($file['tmp_name'], $destination);
						if ($moved){
							$this->request->data['Language']['language_flag'] = $newFileName;
						}
				}
				
				$this->Language->create();
				
				$condition ="status <> 2";
			  	$languageorder = $this->Language->find('all',
											array(
												'recursive' =>-1,
												'fields'=>array('lan_order'),
												'conditions'=>array($condition),
												'order'=>array('lan_order DESC'),
												'limit' => 1
										));

				
				$this->request->data['Language']['lan_order'] = $languageorder[0]['Language']['lan_order'] + 1;
								
				if ($moved && $this->Language->save($this->request->data)) {
					$this->Session->setFlash('The Language added successfully.', 'default', array('class' => 'success'));
					
					// Create the destination folder.
					$destination_folder = APP . 'Locale' . DS . $locale;
					$dir = new Folder($destination_folder, true, 0755);
					// Base folder pointer
					$base_folder = APP . 'Locale' . DS . 'base';
					$folder1 = new Folder($base_folder);
					// Copy base folder content to destination folder.
					$folder1->copy($destination_folder);
					$this->redirect(array('action' => 'index'));
					
				} else {
					$this->Session->setFlash('The Language could not be added. Please, try again.', 'default', array('class' => 'error'));
				}
		
		}
		
		$languages = $this->getLanguage();
		$this->set('language_list',$languages);
		
	}
	
	public function admin_checkper(){
			
			$dir = new Folder(APP . 'Locale' . DS . 'tha', false);
			//$per = $dir->mode;
			//$per = $dir->chmod(APP . 'Locale' . DS . 'eus', 0777, true);
			
			/*$per = new File(APP . 'Locale' . DS . 'ind' . DS . 'default.po', false, 0777);
			$per = new File(APP . 'Locale' . DS . 'ind' . DS . 'default.po', false, 0777);*/
			$per = $dir->delete();
			pr($per); exit;
			
			//$file = new File(APP . 'Locale' . DS . 'gle' . DS . 'LC_MESSAGES' . DS . 'default.po', false, 0644);
			
	}
	
	public function getLanguage(){
		
		return $languages = array(
		/* Afrikaans */ 'afr' => 'Afrikaans',
		/* Albanian */ 'sqi' => 'Albanian',
		/* Arabic */ 'ara' => 'Arabic',
		/* Armenian/Armenia */ 'hye' => 'Armenian/Armenia',
		/* Basque */ 'eus' => 'Basque',
		/* Basque */ 'baq' => 'Basque',
		/* Tibetan */ 'bod' => 'Tibetan',
		/* Bosnian */ 'bos' => 'Bosnian',
		/* Bulgarian */ 'bul' => 'Bulgarian',
		/* Byelorussian */ 'bel' => 'Byelorussian',
		/* Catalan */ 'cat' => 'Catalan',
		/* Chinese */ 'zho' => 'Chinese',
		/* Croatian */ 'hrv' => 'Croatian',
		/* Czech */ 'ces' => 'Czech',
		/* Danish */ 'dan' => 'Danish',
		/* Dutch (Standard) */ 'nld' => 'Dutch',
		/* Estonian */ 'est' => 'Estonian',
		/* Faeroese */ 'fao' => 'Faeroese',
		/* Farsi/Persian */ 'fas' => 'Farsi/Persian',
		/* Finnish */ 'fin' => 'Finnish',
		/* French (Standard) */ 'fra' => 'French',
		/* Gaelic (Scots) */ 'gla' => 'Gaelic',
		/* Galician */ 'glg' => 'Galician',
		/* German (Standard) */ 'deu' => 'German',
		/* Greek */ 'gre' => 'Greek',
		/* Hebrew */ 'heb' => 'Hebrew',
		/* Hindi */ 'hin' => 'Hindi',
		/* Hungarian */ 'hun' => 'Hungarian',
		/* Icelandic */ 'isl' => 'Icelandic',
		/* Indonesian */ 'ind' => 'Indonesian',
		/* Irish */ 'gle' => 'Irish',
		/* Italian */ 'ita' => 'Italian',
		/* Japanese */ 'jpn' => 'Japanese',
		/* Korean */ 'kor' => 'Korean',
		/* Latvian */ 'lav' => 'Latvian',
		/* Lithuanian */ 'lit' => 'Lithuanian',
		/* Macedonian */ 'mkd' => 'Macedonian',
		/* Malaysian */ 'msa' => 'Malaysian',
		/* Maltese */ 'mlt' => 'Maltese',
		/* Norwegian */ 'nor' => 'Norwegian',
		/* Norwegian Bokmal */ 'nob' => 'Norwegian Bokmal',
		/* Norwegian Nynorsk */ 'nno' => 'Norwegian Nynorsk',
		/* Polish */ 'pol' => 'Polish',
		/* Portuguese (Portugal) */ 'por' => 'Portuguese (Portugal)',
		/* Rhaeto-Romanic */ 'roh' => 'Rhaeto-Romanic',
		/* Romanian */ 'ron' => 'Romanian',
		/* Russian */ 'rus' => 'Russian',
		/* Sami (Lappish) */ 'smi' => 'Sami',
		/* Serbian */ 'srp' => 'Serbian',
		/* Slovak */ 'slk' => 'Slovak',
		/* Slovenian */ 'slv' => 'Slovenian',
		/* Sorbian */ 'wen' => 'Sorbian',
		/* Spanish (Spain - Traditional) */ 'spa' => 'Spanish',
		/* Swedish */ 'swe' => 'Swedish',
		/* Thai */ 'tha' => 'Thai',
		/* Tsonga */ 'tso' => 'Tsonga',
		/* Tswana */ 'tsn' => 'Tswana',
		/* Turkish */ 'tur' => 'Turkish',
		/* Ukrainian */ 'ukr' => 'Ukrainian',
		/* Urdu */ 'urd' => 'Urdu',
		/* Venda */ 'ven' => 'Venda',
		/* Vietnamese */ 'vie' => 'Vietnamese',
		/* Welsh */ 'cym' => 'Welsh',
		/* Xhosa */ 'xho' => 'Xhosa',
		/* Yiddish */ 'yid' => 'Yiddish',
		/* Zulu */ 'zul' => 'Zulu'
	);
	
	}

/**
 * Admin edit
 *
 * @param integer $id
 * @return void
 * @access public
 */
	public function admin_edit($id=NULL) {
		
		$this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout', "Edit Language");
        $this->set('Back','Back');
		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash('Invalid Language', 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		
		if ($this->request->is('post') || !empty($this->request->data)) {
			
			$file = $this->request->data['Language']['language_flag'];
			unset($this->request->data['Language']['language_flag']);
			
			if (!empty($file['name'])) {
					
					$newFileName = substr(md5(time()),0,5) . '-' . $file['name'];
					$destination = WWW_ROOT . 'img'. DS . 'admin_uploads'. DS .'flags' . DS . $newFileName;
					
					$moved = move_uploaded_file($file['tmp_name'], $destination);
					
					if ($moved){
						$this->request->data['Language']['language_flag'] = $newFileName;
					}
			}
			
			if ($this->Language->save($this->request->data)) 
			{
				$this->Session->setFlash('The Language has been edited successfully.', 'default', array('class' => 'success'));
				//$this->redirect(array('action' => 'index'));
			} 
			else 
			{
				$this->Session->setFlash('The Language could not be edited. Please, try again.', 'default', array('class' => 'error'));
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->Language->read(null, $id);
		}
		
		$lan_flag = $this->Language->find('all', array(
			'recursive' => -1,
			'conditions' => array('id'=>$id),
			'fields' => array('language_flag')
		));
		
		//prd($lan_flag);
		$this->set('lan_flag', $lan_flag['0']['Language']['language_flag']);
		
	}

/**
 * Admin edit Locale
 *
 * @param integer $id
 * @return void
 * @access public
 */
	public function admin_editlocale($id=NULL) {

		$this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout', "Edit Locale");

		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash('Invalid Language', 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
				
		$record = $this->Language->find('all',array(
				'recursive' => -1,
				'conditions' => array('Language.id'=>$id),
				'fields' => array('id','locale')
			));
		if(isset($record['0']['Language']['locale'])){
			
			if (!file_exists(APP . 'Locale' . DS . $record['0']['Language']['locale'] . DS . 'LC_MESSAGES' . DS . 'default.po')) {
				$this->Session->setFlash('The file default.po does not exist.', 'default', array('class' => 'error'));
				$this->redirect(array('action' => 'index'));
			}
		}else{
			$this->Session->setFlash('No Locale is defined for this language.', 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		
		$file =& new File(APP . 'Locale' . DS . $record['0']['Language']['locale'] . DS . 'LC_MESSAGES' . DS . 'default.po', true);
		$content = $file->read();
		if (!empty($this->request->data)) 
		{
			
			if($file->write($this->request->data['Locale']['content'])) 
			{
				$this->Session->setFlash('The Language has been edited successfully.', 'default', array('class' => 'success'));
			}
			else
			{
				$this->Session->setFlash('The Locale could not be edited. Please, try again.', 'default', array('class' => 'error'));
			}	
        	
        	$record = $this->Language->find('all',array(
				'recursive' => -1,
				'conditions' => array('Language.id'=>$this->request->data['Language']['id']),
				'fields' => array('id','locale')
			));

			$this->request->data['Language']['id'] = $record['0']['Language']['id'];

			$file =& new File(APP . 'Locale' . DS . $record['0']['Language']['locale'] . DS . 'LC_MESSAGES' . DS . 'default.po', true);
		    $content = $file->read();
		   
			$this->set('content',$content);
 			//$this->redirect(array('action' => 'index'));
		}
		if (empty($this->request->data)) 
		{
			$this->request->data['Language']['id'] = $record['0']['Language']['id'];
			//pr($this->request->data); exit;
			$this->set('content',$content);
		}
	}


	public function admin_editbase() {
		$this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout', "Edit Locale");

		$file =& new File(APP . 'Locale' . DS . 'base' . DS . 'LC_MESSAGES' . DS . 'default.po', true);
		$content = $file->read();
		
		if (!empty($this->request->data)) {
			
			if ($file->write($this->request->data['Locale']['content'])) {
				$this->Session->setFlash('The Language has been edited successfully.', 'default', array('class' => 'success'));
			}else{
				$this->Session->setFlash('The Locale could not be edited. Please, try again.', 'default', array('class' => 'error'));
			}	

			$this->redirect(array('action' => 'index'));
							
		}
		if (empty($this->request->data)) {
			$this->set('content',$content);
		}
	}
	
	
	public function changeSiteLanguage($key=NULL){
		$this->admin_redirect_to_dashboard_distributor();
		$this->changelanguage($key);
		$this->redirect($this->referer());
		
	}
	
	public function admin_test(){
		$this->layout = 'ajax';		
		$this->loadModel('EmailContent');
	}
	
	public function admin_manageorder(){
		
		$this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout', 'Manage Order');
	    $this->set('Back','Back');
		$this->loadModel('Language');
		$condition ="status <> 2";
		
		$languageList = $this->Language->find('all',
			array(
				'recursive' =>-1,
				'fields'=>array('*'),
				'conditions'=>array($condition),
				'order'=>array('lan_order ASC')
		));

		$this->set('language_list',$languageList);
		
	}
	
	public function admin_updateorder()
	{
		$this->admin_redirect_to_dashboard_distributor();
		$count=1;
		$this->loadModel('Language');
		
		foreach($this->request['data']['arrayorder'] as $val){
			
			$this->request->data['Language']['id'] = $val;
			$this->request->data['Language']['lan_order'] = $count;
			$this->Language->save($this->request->data);
			$count++;
			
		}
		
		exit;
	
	}


/**
 * View Languages 
 */
	/*public function admin_view($id=NULL)
	{
		 $this->set('title_for_layout', __("View Language"));
		 
		 if(!$id){
		  throw new NotFoundException(__('Invalid Post'));
		 }
		 
		 $language=$this->Language->findById($id);
		 
	   $this->set('language',$language);
	}*/

}