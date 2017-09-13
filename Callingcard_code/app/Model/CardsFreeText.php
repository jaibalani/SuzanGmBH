<?php
App::uses('AppModel', 'Model');
class CardsFreeText extends AppModel {

	public $primaryKey	= 'cf_id';
	public $belongsTo = array(
		'Card' => array(
			'className' => 'Card',
			'foreignKey' => 'cf_c_id'
		),
		'Language' => array(
			'className' => 'Language',
			 'foreignKey' => false,
			//'foreignKey' => 'cf_alias',
			//'conditions' => array('Language.alias = CardsFreeText.cf_alias')
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