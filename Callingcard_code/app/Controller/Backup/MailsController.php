<?php
App::uses('AppController', 'Controller');

class MailsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('');
	}
	
	public function admin_index($redirect_to = 'Main'){
		$this->set('title_for_layout', __('Compose Mail'));
		
		$_SESSION['email'] = '';
		$this->set('role_id',$this->Auth->User('role_id'));
		
		// Mail Request from Mediator/Retailor Grid
		if(isset($this->request->params['named']['user_emails']))
		{
		  $this->set('user_emails',$this->request->params['named']['user_emails']);
		}
		else
		{
			$this->set('user_emails',array());
		}
		
		$this->Mail->changevalid();
				
		if ($this->request->is('post') || !empty($this->request->data)) 
		{
			$postdata=$this->request->data;
			if(empty($postdata['Mail']['email']))
			{
				$this->Session->setFlash('Please enter email address.', 'default', array('class' => 'error'));
				return;
			}
			else
			{
				$emailArr = explode(',',$postdata['Mail']['email']);
			  $cc = $postdata['Mail']['cc'];
			}
			
			foreach($emailArr as $emailid)
			{
				$this->request->data['Mail']['email']=$emailid;
				$this->Mail->set($this->request->data);
				if($this->Mail->validates()) 
				{
				   $this->loadModel('EmailContent');
					 $message	= $this->request->data['Mail']['message'];
					 $subject = $this->request->data['Mail']['subject'];
				   $this->EmailContent->__SendMail($emailid,$subject,$message);
					 if($_SESSION['email'] == '')
						{
						$this->Session->setFlash(__('Mail has been sent successfully.'), 'default',array('class'=>'success'));
						}
						else
						{
						$this->Session->setFlash(__('Mail has been sent successfully. But not delivered to these emails - '.$_SESSION['email']), 'default',array('class'=>'success'));
						}	
				}
				else
				{
						$arr[]=$emailid;	
				}
			}
			if(isset($arr))
			{
				$str='';
				foreach ($arr as $invalid)
				{
					$str= $str." ".$invalid." , ";
				}
				$this->Session->setFlash(__('Not sent to :'.$str.' Reason: Invalid email id.'),'default',array('class'=>'error'));
			}
			// Mail Request was from user grid (MEdiator / Retailer)
			if($redirect_to == 'user_grid')
			{
				if($this->Auth->User('id') == 1)
				{
				 $this->redirect(array('controller'=>'Users', 'action' => 'manage_mediator','admin'=>true));	
				}
				else
				{
					$this->redirect(array('controller'=>'Users', 'action' => 'manage_retailer','admin'=>true));
				}
			}
			else
			{
				$this->redirect(array('controller'=>'Mails', 'action' => 'index','admin'=>true));
			}
		}
	}
}
