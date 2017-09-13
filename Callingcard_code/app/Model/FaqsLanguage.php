<?php
App::uses('AppModel', 'Model');
class FaqsLanguage extends AppModel {

	public $primaryKey	= 'fl_id';
	public $belongsTo = array(
		'Faq' => array(
			'className' => 'Faq',
			'foreignKey' => 'fl_f_id'
		),
		
	);
	public $validate = array(
		'fl_title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'Title should not be blank.'
			)
		),
		'fl_desc' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'Description should not be blank.'
			)
		)
	);
	
}