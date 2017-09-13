<?php
App::uses('AppModel', 'Model');

class EmailContent extends AppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'EmailContent';

/**
 * Validation
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'title' => array(
				'required' => array(
									'rule' => array('notEmpty'),
									'message' => ' Title is required.'
						 ),
		),		
		'unique_name' => array(
				'required' => array(
									'rule' => array('notEmpty'),
									'message' => ' Unique name is required.'
						 ),
		),		
		'message' => array(
				'required' => array(
									'rule' => array('notEmpty'),
									'message' => 'Message is required.'
						 ),
		),		
		'subject' => array(
				'required' => array(
									'rule' => array('notEmpty'),
									'message' => ' Subject is required.'
						 ),
		)
	);	
	
	//Send mail of forgot password.......
	public function forgotPassword($name,$email,$old_pass,$link,$oldpass_link) {
		$mail_content = $this->getMailContent('FORGOT_PASSWORD');
        if(is_array($mail_content) && !empty($mail_content)){
		$myLink='<a href="'.$link.'" target="_blank">Click Here</a>';
		$login='<a href="'.$oldpass_link.'" target="_blank">Click Here</a>';
		$subject = $mail_content['subject'];
		$mail_refined_content = $mail_content['message'];
		$mail_refined_content = str_replace('{{receiver}}',$name,$mail_refined_content);
		//$mail_refined_content = str_replace('{{old_pass}}',$old_pass,$mail_refined_content);
		$mail_refined_content = str_replace('{{loginlink}}',$login,$mail_refined_content);
		$mail_refined_content = str_replace('{{resetlink}}',$myLink,$mail_refined_content);
		$this->__SendMail($email,$subject,$mail_refined_content);	
		}
	}
	
	//Common Function for getting mail content using unique name..................
	private function getMailContent($unique_name)
	{
		$conditions = array(
			'conditions' => array('EmailContent.unique_name LIKE' => $unique_name), //array of conditions
			'recursive' => 1 //int
		);
		$mail_content = $this->find('first', $conditions);
		
		if(is_array($mail_content)){
			return $mail_content['EmailContent'];
		}else{
			return false;
		}
	}

	//Send mail to new registered mediator up user.............
	public function _RegisterMail($email,$first_name, $login_ink) {
		$mail_content = $this->getMailContent('MEDIATOR_REGISTRATION');
		if(is_array($mail_content) && !empty($mail_content)){
			
			$subject = $mail_content['subject'];
			$mail_refined_content = str_replace('{{receiver}}',$first_name,$mail_content['message']);
			$mail_refined_content = str_replace('{{link}}',$login_ink,$mail_refined_content);
			$this->__SendMail($email,$subject,$mail_refined_content);		
		}		
	}
	
	//Send mail to Mediator on fund Reach To Minimum Balance 
	public function _MediatorBalnceFallingMinimum($mediators_email,$mediators_name,$mediator_current_balance,$minimum_balance) 
	{
		$mail_content = $this->getMailContent('MEDIATOR_MINIMUM_BALANCE_REACH');
		if(is_array($mail_content) && !empty($mail_content)){
			$subject = $mail_content['subject'];
			$mail_refined_content = str_replace('{{mediators_name}}',$mediators_name,$mail_content['message']);
			$mail_refined_content = str_replace('{{mediator_current_balance}}',$mediator_current_balance,$mail_refined_content);
			$mail_refined_content = str_replace('{{minimum_balance}}',$minimum_balance,$mail_refined_content);
			$this->__SendMail($mediators_email,$subject,$mail_refined_content);		
		}		
	}
	
	//Send mail to Retailer on fund Reach To Minimum Balance 
	public function _RetailerBalnceFallingMinimum($retailer_email,$retailer_name,$retailer_current_balance,$minimum_balance) 
	{
		$mail_content = $this->getMailContent('RETAILER_MINIMUM_BALANCE_REACH');
		if(is_array($mail_content) && !empty($mail_content)){
			$subject = $mail_content['subject'];
			$mail_refined_content = str_replace('{{retailer_name}}',$retailer_name,$mail_content['message']);
			$mail_refined_content = str_replace('{{retailer_current_balance}}',$retailer_current_balance,$mail_refined_content);
			$mail_refined_content = str_replace('{{minimum_balance}}',$minimum_balance,$mail_refined_content);
			$this->__SendMail($retailer_email,$subject,$mail_refined_content);		
		}		
	}
	
	//Send mail to Retailer on Purchase Limit Cross 
	public function _RetailerPurchaseLimitCross($retailer_email,$retailer_name,$current_purchase,$purchase_limit) 
	{
		$mail_content = $this->getMailContent('RETAILER_PURCHASE_LIMIT_CROSS');
		if(is_array($mail_content) && !empty($mail_content)){
			$subject = $mail_content['subject'];
			$mail_refined_content = str_replace('{{retailer_name}}',$retailer_name,$mail_content['message']);
			$mail_refined_content = str_replace('{{current_purchase}}',$current_purchase,$mail_refined_content);
			$mail_refined_content = str_replace('{{purchase_limit}}',$purchase_limit,$mail_refined_content);
			$this->__SendMail($retailer_email,$subject,$mail_refined_content);		
		}		
	}
	
	//Send mail to Mediator on Purchase Limit Cross Retailer 
	public function _RetailerPurchaseLimitCrossMediator($mediator_email,$mediator_name,$retailer_name,$retailer_email,$current_purchase,$purchase_limit) 
	{
		$mail_content = $this->getMailContent('RETAILER_PURCHASE_LIMIT_CROSS_MEDIATOR_MAIL');
		if(is_array($mail_content) && !empty($mail_content)){
			$subject = $mail_content['subject'];
			$mail_refined_content = str_replace('{{mediator_name}}',$mediator_name,$mail_content['message']);
			$mail_refined_content = str_replace('{{current_purchase}}',$current_purchase,$mail_refined_content);
			$mail_refined_content = str_replace('{{retailer_name}}',$retailer_name,$mail_refined_content);
			$mail_refined_content = str_replace('{{retailer_email}}',$retailer_email,$mail_refined_content);
			$mail_refined_content = str_replace('{{purchase_limit}}',$purchase_limit,$mail_refined_content);
			$this->__SendMail($mediator_email,$subject,$mail_refined_content);		
		}		
	}
	
	//Send mail to Distributor on fund allocation to Mediator 
	public function _DistributorFundAllocateMail($mediators_email,$mediators_name,$mediator_previous_balance,$mediator_current_balance,$allocated_amount,$distributor_name,$distributor_email,$allocation_date)
	{
		$mail_content = $this->getMailContent('DISTRIBUTOR_FUND_ALLOCATION');
		if(is_array($mail_content) && !empty($mail_content)){
			$subject = $mail_content['subject'];
			$mail_refined_content = str_replace('{{distributor_name}}',$distributor_name,$mail_content['message']);
			$mail_refined_content = str_replace('{{mediators_name}}',$mediators_name,$mail_refined_content);
			$mail_refined_content = str_replace('{{mediator_previous_balance}}',$mediator_previous_balance,$mail_refined_content);
			$mail_refined_content = str_replace('{{mediator_current_balance}}',$mediator_current_balance,$mail_refined_content);
			$mail_refined_content = str_replace('{{allocated_amount}}',$allocated_amount,$mail_refined_content);
			$mail_refined_content = str_replace('{{mediator_email}}',$mediators_email,$mail_refined_content);
			$mail_refined_content = str_replace('{{allocation_date}}',$allocation_date,$mail_refined_content);
			$this->__SendMail($distributor_email,$subject,$mail_refined_content);		
		}		
	}
	
	//Send mail to Mediator on fund allocation to Mediator 
	public function _DistributorFundAllocateMailMediator($mediators_email,$mediators_name,$mediator_previous_balance,$mediator_current_balance,$allocated_amount,$distributor_name,$distributor_email,$allocation_date)
	{
		$mail_content = $this->getMailContent('DISTRIBUTOR_FUND_ALLOCATION_MEDIATOR_MAIL');
		if(is_array($mail_content) && !empty($mail_content)){
			$subject = $mail_content['subject'];
			$mail_refined_content = str_replace('{{mediators_name}}',$mediators_name,$mail_content['message']);
			$mail_refined_content = str_replace('{{distributor_name}}',$distributor_name,$mail_refined_content);
			$mail_refined_content = str_replace('{{mediator_previous_balance}}',$mediator_previous_balance,$mail_refined_content);
			$mail_refined_content = str_replace('{{mediator_current_balance}}',$mediator_current_balance,$mail_refined_content);
			$mail_refined_content = str_replace('{{allocated_amount}}',$allocated_amount,$mail_refined_content);
			$mail_refined_content = str_replace('{{distributor_email}}',$distributor_email,$mail_refined_content);
			$mail_refined_content = str_replace('{{allocation_date}}',$allocation_date,$mail_refined_content);
			$this->__SendMail($mediators_email,$subject,$mail_refined_content);		
		}		
	}
	
	
	//Send mail to Mediator on fund allocation to retailer 
	public function _MediatorFundAllocateMail($mediators_email,$mediators_name,$mediator_previous_balance,$mediator_current_balance,$allocated_amount,$retailer_name,$retailer_email,$allocation_date) 
	{
		$mail_content = $this->getMailContent('MEDIATOR_FUND_ALLOCATION');
		if(is_array($mail_content) && !empty($mail_content)){
			$subject = $mail_content['subject'];
			$mail_refined_content = str_replace('{{mediators_name}}',$mediators_name,$mail_content['message']);
			$mail_refined_content = str_replace('{{mediator_previous_balance}}',$mediator_previous_balance,$mail_refined_content);
			$mail_refined_content = str_replace('{{mediator_current_balance}}',$mediator_current_balance,$mail_refined_content);
			$mail_refined_content = str_replace('{{allocated_amount}}',$allocated_amount,$mail_refined_content);
			$mail_refined_content = str_replace('{{retailer_name}}',$retailer_name,$mail_refined_content);
			$mail_refined_content = str_replace('{{retailer_email}}',$retailer_email,$mail_refined_content);
			$mail_refined_content = str_replace('{{allocation_date}}',$allocation_date,$mail_refined_content);
			$this->__SendMail($mediators_email,$subject,$mail_refined_content);		
		}		
	}

   //Send mail to Retailer on fund allocation to them 
	public function _MediatorFundAllocateMailRetailer($mediators_email,$mediators_name,$retailer_total_balance,$retailer_previous_balance,$allocated_amount,$retailer_name,$retailer_email,$allocation_date)
	{
		$mail_content = $this->getMailContent('MEDIATOR_FUND_ALLOCATION_RETAILER');
		if(is_array($mail_content) && !empty($mail_content)){
			$subject = $mail_content['subject'];
			$mail_refined_content = str_replace('{{retailer_name}}',$retailer_name,$mail_content['message']);
			$mail_refined_content = str_replace('{{mediators_name}}',$mediators_name,$mail_refined_content);
			$mail_refined_content = str_replace('{{mediator_email}}',$mediators_email,$mail_refined_content);
			$mail_refined_content = str_replace('{{retailer_total_balance}}',$retailer_total_balance,$mail_refined_content);
     		$mail_refined_content = str_replace('{{retailer_previous_balance}}',$retailer_previous_balance,$mail_refined_content);
			$mail_refined_content = str_replace('{{allocated_amount}}',$allocated_amount,$mail_refined_content);
			$mail_refined_content = str_replace('{{allocation_date}}',$allocation_date,$mail_refined_content);
			$this->__SendMail($retailer_email,$subject,$mail_refined_content);		
		}		
	}

	
	//Send mail to new registered distributor 
	public function _RegisterMailRetailer($mediator_name,$mediator_email,$email,$first_name, $login_ink) {
		$mail_content = $this->getMailContent('RETAILER_REGISTRATION');
		if(is_array($mail_content) && !empty($mail_content)){
			
			$subject = $mail_content['subject'];
			$mail_refined_content = str_replace('{{receiver}}',$first_name,$mail_content['message']);
			$mail_refined_content = str_replace('{{link}}',$login_ink,$mail_refined_content);
			$mail_refined_content = str_replace('{{mediator_name}}',$mediator_name,$mail_refined_content);
			$mail_refined_content = str_replace('{{mediator_email}}',$mediator_email,$mail_refined_content);
			$this->__SendMail($email,$subject,$mail_refined_content);		
		}		
	}
	public function _ContactUs($email_username, $to, $name, $subject, $message,$admin_name = 'Admin')
	{
			
			$mail_content = $this->getMailContent('CONTACT');
			if(is_array($mail_content) && !empty($mail_content)){
				
				$mail_refined_content = str_replace('{{receiver}}',$admin_name,$mail_content['message']);
				$mail_refined_content = str_replace('{{from}}',$email_username,$mail_refined_content);
				$mail_refined_content = str_replace('{{name}}',$name,$mail_refined_content);
				$mail_refined_content = str_replace('{{message}}',$message,$mail_refined_content);
				/*pr($to);
				pr($email);
			  pr($mail_refined_content); exit;*/
				$this->__SendMail($to,$subject,$mail_refined_content);		
			}		
			
			/*App::uses('CakeEmail', 'Network/Email');
			$cake_email = new CakeEmail();
			
			$cake_email->config('default');
			$cake_email->from($email);
			$cake_email->to($to);
			$cake_email->subject($subject);
			$cake_email->template('default', 'default');
			$cake_email->emailFormat('html');
		 	$cake_email->viewVars(array(
				'mail_message' => $message,
			));
			try 
			{
				$cake_email->send();
			} 
			catch (Exception $e)
			{
				return false;
			}
			return true;*/
	}

	public function __SendMail($to, $subject, $content, $cc =array()){
		
		App::uses('CakeEmail', 'Network/Email');
		$cake_email = new CakeEmail();		
		$cake_email->config('default');
		$cake_email->to($to);
		$cake_email->subject($subject);
		$cake_email->template('default', 'default');
		$cake_email->emailFormat('html');
		
		if(!empty($cc))
		{
			$cake_email->cc($cc);
		}
		$cake_email->viewVars(array(
			'mail_message' => $content,
		));

		try 
		{  
		  $cake_email->send();
		} 
		catch (Exception $e) 
		{
			return false;
		}
		return true;
	}	
	
}