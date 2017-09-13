<?php
App::uses('AppModel', 'Model');
class CategoriesLanguage extends AppModel {

	public $primaryKey	= 'cl_id';
	public $belongsTo = array(
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'cl_cat_id'
		),
		
	);
	public $validate = array(
		'cl_title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'Title should not be blank.'
			)
		)
	);
	
}