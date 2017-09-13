<?php
App::uses('AppModel', 'Model');
class Category extends AppModel {

	public $primaryKey	= 'cat_id'; 
	
	public $belongsTo = array(
		'Parent' =>array(
					'className' => 'Category',
          'foreignKey' => 'cat_parent_id',
				 	'counterCache' => 'card_count'
        ),
	);
	
	public $hasMany = array(
			'Child' =>array('className' => 'Category',
				'foreignKey' => 'cat_parent_id',
				'dependent' => true
			),
			'CategoriesLanguage' => array(
				'className' => 'CategoriesLanguage',
				'foreignKey' => 'cl_cat_id'
			),
			'Card' => array(
				'className' => 'Card',
				'foreignKey' => 'c_cat_id'
			),
	);
	public $validate = array(
		'cat_title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'Title should not be blank.'
			)
		),
		'cat_status' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' =>  'Status should not be blank.'
			)
		)
	);
	
}
