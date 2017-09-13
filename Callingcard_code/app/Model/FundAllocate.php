<?php
App::uses('AuthComponent', 'Controller/Component');

class FundAllocate extends AppModel{

public $name = 'FundAllocate';

/**
 * Validation
 *
 * @var array
 * @access public
 */
public $validate = array(
	 
	 'total_amount' => array(
				'required' => array(
					'rule' => array('notEmpty'),
					'message' => 'Fund amount is required.'
				),
				'numericval' => array(
				'rule' => 'numeric',
				'message' => 'Enter only digits in amount.'
				),
				'maxLength' => array(
				'rule' => array('maxLength', 10),
				'message' => 'length of fund amount should not be greater than 10.'
				),
				'format' => array(
                'rule' => array('decimal'),
                'message' => 'Please enter amount in this format(1.00)',
	            ),
				'comparison'=>array(
											'rule' => array('comparison','>',0),
											'allowEmpty' => false,
											'message' =>'Amount cannot be less than equal to 0.'
											)		
	   ),	
		/*'bank_name' => array(
				'required' => array(
	            'rule' => array('notEmpty'),
	            'message' => 'Bank name is required.'
	        ),
		),*/
		
		'check_number' => array(
				/*'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Check number is required.'
            ),*/
		'numericval' => array(
								'rule' => 'numeric',		
								'allowEmpty' => true,		
								'message' => 'Either enter digits or none.'
							),
		'maxLength' => array(
		'rule' => array('maxLength',10),
		'message' => 'Maximum length should be 10.'
		)),	
	);
}