<?php
App::uses('AuthComponent', 'Controller/Component');

class ContactUs extends AppModel {

public $useTable = false;

/**
 * Validation
 *
 * @var array
 * @access public
 */
public $validate = array(
	
		'email' => array(
			'rule1' => array(
				'rule' => array('email', true),
				'message' => 'Please enter valid email.',
				'allowEmpty' => true,
			 )
			),
			
        'subject' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Subject is required.'
            )
        ),
				
		'name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Name of sender is required.'
            )
        ),		
				
		'message' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Message is required.'
            )
        )
  );
	
	public function changevalid(){
		$this->validator()->getField('email')->getRule('rule1')->allowEmpty = true;
	}
}