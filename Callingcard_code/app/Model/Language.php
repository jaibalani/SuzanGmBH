<?php

App::uses('AppModel', 'Model');


class Language extends AppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'Language';


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
                'message' => 'This field is required.'
            ),
		),
		'locale' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'This field is required.',
				'allowEmpty' => false
			),
		),
		'native' => array(
				'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'This field is required.'
            ),
		),
		'lan_order' => array(
				'required' => array(
									'rule' => array('notEmpty'),
									'message' => ' This field is required.'
						 ),
					'rule1' => array(
					'rule'    => 'naturalNumber',
					'message' => 'Please supply order no.'
				),
				'rule2' => array(
					'rule' => array('maxLength',3),
					'message' => 'Max 3 digits are allowed.'
				),
			),
		'language_flag' => array(
			'rule1' => array(
				'rule'    => array('extension', array('gif', 'jpeg', 'png', 'jpg')),
        		'message' => 'Please supply a valid image.'
			),
		),
	);

}
