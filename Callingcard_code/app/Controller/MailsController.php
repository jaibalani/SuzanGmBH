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
		
		if ($this->request->is('post') || !empty($this->request->data)) 
		{
			$postdata=$this->request->data;
			//prd($postdata);
			if(empty($postdata['Mails']['email']))
			{
				$this->Session->setFlash('Please enter email address.', 'default', array('class' => 'error'));
				return;
			}else if(strlen(trim($postdata['Mails']['subject']))==0)
			{
				$this->Session->setFlash('Please enter subject.', 'default', array('class' => 'error'));
				return;
			}
			else
			{
				$emailArr = explode(',',$postdata['Mails']['email']);
				$cc = array();
				if(!empty($postdata['Mails']['cc'])){
					$cc = explode(',',$postdata['Mails']['cc']);
					foreach($cc as $k=>$v){
						/*if(in_array($v,$cc)){
							unset($cc[$k]);
						}*/
						if(in_array($v,$emailArr)){
							unset($cc[$k]);
						}
					}
				if(empty($cc))
					unset($cc);
				}else{
					$cc = array();
				}
				$to_email_array = '';
				$to_cc_array = '';
				$invalid_cc = '';
				$invalid_to = '';
				// To Email Validations
				foreach($emailArr as $email)
				{
					if(filter_var($email,FILTER_VALIDATE_EMAIL))
					{
						$to_email_array[] =$email;
					}
					else
					{
						$invalid_to = $invalid_to.$email." "."|";
					}
				}
			
				// CC Email Validations
				if(!empty($cc))
				{
					foreach($cc as $carbon)
					{
						if(filter_var($carbon,FILTER_VALIDATE_EMAIL))
						{
							$to_cc_array[] = $carbon;
						}
						else
						{
							$invalid_cc = $invalid_cc.$carbon." "."|";
						}
					}
				}
			}
			$this->loadModel('EmailContent');
			$message	= $this->request->data['Mails']['message'];
			$subject = $this->request->data['Mails']['subject'];
			//$to = $this->request->data['Mails']['email'];
			$to = $to_email_array;
			$carbon = $to_cc_array;
			
			$carbon_copy = $this->request->data['Mails']['cc'];
			
			if(!empty($to_cc_array))
			{
			$sent_mail = $this->EmailContent->__SendMail($to,$subject,$message,$carbon);
			}
			else
			{
			$sent_mail = $this->EmailContent->__SendMail($to,$subject,$message);
			}
			
			if($sent_mail)
			{
				if(!empty($invalid_to) && !empty($invalid_cc))
				$this->Session->setFlash(__('Mail has been sent out successfully. But not sent to '.$invalid_to." ".$invalid_cc),'default',array('class'=>'success'));
			    else if(!empty($invalid_to) && empty($invalid_cc))
				$this->Session->setFlash(__('Mail has been sent out successfully. But not sent to '.$invalid_to),'default',array('class'=>'success'));
       			else if(empty($invalid_to) && !empty($invalid_cc))
				$this->Session->setFlash(__('Mail has been sent out successfully. But not sent to '.$invalid_cc),'default',array('class'=>'success'));
				else
				$this->Session->setFlash(__('Mail has been sent out successfully.'),'default',array('class'=>'success'));
			}
			else
			{
				$this->Session->setFlash(__('Mail could not be sent now.'),'default',array('class'=>'error'));
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
	
	public function index($redirect_to = 'Main'){
		$this->set('title_for_layout', __('Compose Mail'));
		
		$_SESSION['email'] = '';
		$this->set('role_id',$this->Auth->User('role_id'));
		
		$this->loadModel('User');
		$added_by = $this->Auth->User('added_by');
		$mediator_data = $this->User->findById($added_by);
		
		if(empty($mediator_data['User']['email']))
		{ 
			$this->Session->setFlash(__('Receiver does not have email address.'), 'default', array(),'error');
            $this->redirect(array('controller'=>'Searches', 'action' => 'online_card'));
		}

		$this->set('email',$mediator_data['User']['email']);
		if ($this->request->is('post') || !empty($this->request->data)) 
		{
			$postdata=$this->request->data;
			if(empty($postdata['Mails']['email']))
			{
				$this->Session->setFlash(__('Please enter email address.'), 'default', array(),'error');
				return;
			}else if(strlen(trim($postdata['Mails']['subject']))==0)
			{
				$this->Session->setFlash(__('Please enter subject.'), 'default', array(),'error');
				return;
			}else{
			
				$this->loadModel('EmailContent');
				$message	= $this->request->data['Mails']['message'];
				$subject = $this->request->data['Mails']['subject'];
				//$to = $this->request->data['Mails']['email'];
				$to = $this->request->data['Mails']['email'];
				$sent_mail = $this->EmailContent->__SendMail($to,$subject,$message);
				
				if($sent_mail)
				{
					$this->Session->setFlash(__('Mail has been sent out successfully.'),'default',array(),'success');
				}
				else
				{
					$this->Session->setFlash(__('Mail could not be sent now.'),'default',array(),'error');
				}
			}
			// Mail Request was from user grid (MEdiator / Retailer)
			$this->redirect(array('controller'=>'Mails', 'action' => 'index'));
		}
	}
}
