<?php
App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel{

public $name = 'User';
var $captcha = ''; //intializing captcha var
/**
 * Validation
 *
 * @var array
 * @access public
 */

public $validate = array(

	    'role_type_id' => array(
            'notEmpty' => array(
            'rule' => array('notEmpty'),
            'message' => 'This field is required.',
            'allowEmpty' => false
        ),
        ),
		
		'email' => array(
           'ruleName2' => array(
				'rule' => array('email', true),
				'allowEmpty' => true,
				'message' => 'Please enter valid email.'
			),
			'isUnique' => array (
				'rule' => 'isUnique',
				'message' => 'This email address already exists.'),
      ),
    	
	'username' => array(
		'required' => array(
			'rule' => array('notEmpty'),
			'message' => 'Account number is required.'
		),
		'isUnique' => array (
				'rule' => 'isUnique',
				'message' => 'This account number has already been taken.'),
		'alphaNumeric' => array(
                //'rule' => 'alphaNumeric',
				'rule' => array('custom', '/^[0-9]+$/'),
                'required' => true,
                'message' => 'Account number must contain numbers only.'
            ),
            'maxLength' => array(
                'rule'    => array('maxLength', 25),
                'message' => 'Account number can not contain more than %d characters.'
            )
	  ),

		
		'fname' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'First name is required.'
            ),
			 'allowedCharacters'=> array(
                'rule' => '|^[a-zA-Z ]*$|',
                'message' => 'Please insert only alphabets in first name.'
            )
        ),
		
		'lname' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Last name is required.'
            ),
			'allowedCharacters'=> array(
                'rule' => '|^[a-zA-Z ]*$|',
                'message' => 'Please insert only alphabets in last name.'
            ), 
        ),
	 	
	 	'password' => array(
		    /*	 'required' => array(
					'rule' => array('notEmpty'),
					'message' =>  'Enter password.'
			), */
	    	'match'=>array(
        	'rule' => 'checkpasswords_blank',
        	'message' => 'You cannot use a blank password.'
            ), 
 
			'passlength' => array(
					'rule' => array('between', 6,15),
					'message' => 'Password length should be between 6 to 15.'
			)
		),
		
		'confirm_password' => array(
			/*'required' => array(
					'rule' => array('notEmpty'),
					'message' => 'Enter confirm password.'
			),*/
		
			'match'=>array(
        	'rule' => 'checkpasswords',
        	'message' => 'Password and Confirm password does not match.'
            ),
			'passlength' => array(
					'rule' => array('between', 6,15),
					'message' => 'Confirm Password length should be between 6 to 15.'
			),
			
	),
	'phone' => array(
				'numericval' => array(
				'rule' => 'numeric',
				'allowEmpty' => true,
				'message' => 'Enter only digits.'
				),
				'maxLength' => array(
				'rule' => array('maxLength',15),
				'allowEmpty' => true,
				'message' => 'Maximum length should be 15.'
				),
				'minLength' => array(
				'rule' => array('minLength',10),
				'allowEmpty' => true,
				'message' => 'Minimum length should be 10.'
				)),	
	'minimum_balance' => array(
				
				'numericval' => array(
						'rule' => 'numeric',
						'allowEmpty' => true,
						'message' => 'Enter only digits.'
						),
				'maxLength' => array(
				'rule' => array('maxLength', 10),
				'message' => 'Maximum length should be 10.'
				),
				'format' => array(
                'rule' => array('decimal'),
                'message' => 'Please Enter value in this format(1.00)',
	            ),
				'comparison'=>array(
											'rule' => array('comparison','>=',0),
											'allowEmpty' => false,
											'message' =>'Minimum balance cannot be less than 0.'
											)		
	   ),
	   'purchase_limit' => array(
				'numericval' => array(
				'rule' => 'numeric',
				'allowEmpty' => true,
				'message' => 'Enter only digits.Set it or remove it.'
				),
				'maxLength' => array(
				'rule' => array('maxLength', 10),
				'allowEmpty' => true,
				'message' => 'Maximum length should be 10.Set it or remove it.'
				),
				'format' => array(
                'rule' => array('decimal'),
				'allowEmpty' => true,
                'message' => 'Please Enter value in this format(1.00).Set it or remove it.',
	            ),
				'comparison'=>array(
							'rule' => array('comparison','>',0),
							'allowEmpty' => false,
							'allowEmpty' => true,
							'message' =>'Alert purchase limit cannot be less than equal to 0. Set it or remove it.'
							)		
	   ),
	  'allow_credit' => array(
	  			'numericval' => array(
				'rule' => 'numeric',
				'message' => 'Enter allowed credit amount  in digits.'
				),
				'maxLength' => array(
				'rule' => array('maxLength', 10),
				'message' => 'Maximum length should be 10.'
				),
				'format' => array(
                'rule' => array('decimal'),
                'message' => 'Please Enter value in this format(1.00)',
	            ),
				'comparison'=>array(
											'rule' => array('comparison','>',0),
											'allowEmpty' => false,
											'message' =>'Allowed credit amount cannot be less than equal to 0.'
											)		
	   ),				
	);

/*
 *Function to check password and confirm password
 *
 */		
    public function setCaptchaValidation()
	{
		$this->validate = Set::merge($this->validate, array(
				'captcha' => array(
					'rule' => array('matchCaptcha'),
					'message' => 'Captcha does not match.'
				),
		));
	}

	public function matchCaptcha($inputValue)
	{
		return $inputValue['captcha'] == $this->getCaptcha(); //return true or false after comparing submitted value with set value of captcha
	}

	public function setCaptcha($value)
	{
		$this->captcha = $value; //setting captcha value
	}

	public function getCaptcha()
	{
		return $this->captcha; //getting captcha value
	}

	public function checkpasswords()
	{
		if($this->data['User']['password'] !='' && $this->data['User']['confirm_password']!=''){
			if($this->data['User']['password']==$this->data['User']['confirm_password']){ 
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


	public function checkpasswords_blank()
	{
		if(trim($this->data['User']['password']) == '' && strlen($this->data['User']['password']) != 0)
		{
            return false;
		}
		else
		{
             return true;  
		}
		
	}
		
	public function beforeSave($options = array()){
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}
	
	public function getUserDetails($user_id){
		$conditions = ' id='.$user_id;
		
		$group = $this->find('all',
			array('conditions' => $conditions)
		);
		
		if(is_array($group)){
			return $group[0]['User'];
		}else{
			return false;
		}
	}
}