<?php
App::uses('AppController', 'Controller');

class SettingsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('');
	}
	
/**
	* Edit Websettings
	*/	
	public function admin_edit($prefix = null){ 
	    $this->admin_redirect_to_dashboard_distributor();
		$this->set('title_for_layout',"Edit Websettings");
		$this->loadModel('Websetting');
		if (!empty($this->request->data)) 
		{
				$application_url = $this->Websetting->findBykey('APPLICATION_URL');
				if(count($application_url))
				{
				   $count = count($this->request->data['Websetting']);
				   foreach ($this->request->data['Websetting'] as $key => $val) {
						 if ($val['id'] == 25) { //Site.url
						 	 $this->request->data['Websetting'][$count]['id'] = $application_url['Websetting']['id'];
							 $this->request->data['Websetting'][$count]['value'] = $val['value'];
						 }
				 }
				}
				//prd($this->request->data);
				if ($this->Websetting->saveAll($this->request->data['Websetting'])) {
					$this->Session->setFlash(__('Websettings have been edited'), 'default', array('class' => 'success'));
					
				} 
				else 
				{
					$this->Session->setFlash(__('Websetting could not be edited. Please, try again.'), 'default', array('class' => 'error'));
				    return;
				}
		 }

		$web_setting = $this->Websetting->find('all', array(
				'conditions' =>array(
					'Websetting.key LIKE' => $prefix . '.%',
					'is_show' =>'1'
				),
				'order' => array('setting_order ASC')
			)
		);
		$this->set('web_setting',$web_setting);
		$this->set("prefix", $prefix);
	}  
}