<?php
App::uses('AppModel', 'Model');
class Card extends AppModel {

	public $primaryKey	= 'c_id'; 
	public $uploadDir = 'img/card_icons';
	public $belongsTo = array(
		'Category' => array(
				'className' => 'Category',
				'foreignKey' => 'c_cat_id',
				'counterCache' => true
		)
	);
	public $hasMany = array(
			'PinsCard' => array(
				'className' => 'PinsCard',
				'foreignKey' => 'pc_c_id'
		),
		'CardsFreeText' => array(
				'className' => 'CardsFreeText',
				'foreignKey' => 'cf_c_id'
		),
		'CardsPrice' => array(
				'className' => 'CardsPrice',
				'foreignKey' => 'cp_c_id'
		),
	);	
	public $validate = array(
		'c_title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'Title should not be blank.'
			),
			'maxLength' => array(
					'rule' => array('maxLength', 255),
					'message' => 'Maximum 255 characters are allowed for card name'
			),
		),
		'c_denomination_rate' => array(
				'required' => array(
					'rule' => array('notEmpty'),
					'message' => 'Denomination rate is required.'
				),
				'numericval' => array(
				'rule' => 'numeric',
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
									'rule' => array('comparison','>',0),
									'allowEmpty' => false,
									'message' =>'Denomination rate cannot be less than equal to 0.'
									)		
	   		),
		'c_buying_percentage' => array(
				'required' => array(
					'rule' => array('notEmpty'),
					'message' => 'Buying percentage is required.'
				),
				'numericval' => array(
				'rule' => 'numeric',
				'message' => 'Enter only digits.'
				),
				'format' => array(
                'rule' => array('decimal'),
                'message' => 'Please Enter value in this format(1.00)',
	            ),
				'comparison'=>array(
									'rule' => array('comparison','<',100),
									'allowEmpty' => false,
									'message' =>'Buying percentage cannot be greater than equal to 100.'
									)		
	   		),
		'c_buying_price' => array(

				'required' => array(
					'rule' => array('notEmpty'),
					'message' => 'Buying price is required.'
				),
				'numericval' => array(
				'rule' => 'numeric',
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
									'rule' => array('comparison','>',0),
									'allowEmpty' => false,
									'message' =>'Buying price cannot be less than equal to 0.'
									)		
	   		),
		'c_selling_price' => array(

				'required' => array(
					'rule' => array('notEmpty'),
					'message' => 'Selling price is required.'
				),
				'numericval' => array(
				'rule' => 'numeric',
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
											'rule' => array('comparison','>',0),
											'allowEmpty' => false,
											'message' =>'Selling price cannot be less than equal to 0.'
											)		
	   		),
		/*'c_contact_number_1' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'German Contact Number should not be blank.'
			)
		),
		'c_contact_number_2' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'English Contact Number should not be blank.'
			)
		),
		'c_local_number_1' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'This field should not be blank.'
			)
		),
		'c_local_number_2' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'This field should not be blank.'
			)
		),
		'c_local_number_3' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'This field should not be blank.'
			)
		),
		'c_local_number_4' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'This field should not be blank.'
			)
		),
		'c_local_number_5' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'This field should not be blank.'
			)
		),
		'c_local_number_6' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'This field should not be blank.'
			)
		),*/
		'c_pin_per_card' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'PINs per card should not be blank.'
			),
			'numeric' => array(
					'rule' => array('range', 0,5000000),
					'message' => 'Please enter PINs per card number greater then 0'
			)
		),
		/*'c_webpage' => array(
			'website' => array(
				'rule' => array('url'),
				'message' =>  'Please enter valid url.'
			)
		),*/
		'c_status' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'Status should not be blank.'
			)
		)
	);
	

}

