<?php
App::uses('AppModel', 'Model');
class Faq extends AppModel {

	public $primaryKey	= 'f_id'; 
	public $hasMany = array(
			'FaqsLanguage' => array(
			'className' => 'FaqsLanguage',
			'foreignKey' => 'fl_f_id'
		),
	);
	public $validate = array(
		'f_title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'Title should not be blank.'
			)
		),
		'f_desc' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'Description should not be blank.'
			)
		),
		'f_status' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'Status should not be blank.'
			)
		)
	);
	
}
