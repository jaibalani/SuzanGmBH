<?php
App::uses('AppModel', 'Model');
class CardsPrice extends AppModel {

	public $primaryKey	= 'cp_id';
	public $belongsTo = array(
		'Card' => array(
			'className' => 'Card',
			'foreignKey' => 'cp_c_id'
		),
		'CreatedFor' => array(
			'className' => 'User',
			'foreignKey' => 'cp_u_id'
		),
		'CreatedBy' => array(
			'className' => 'User',
			'foreignKey' => 'cp_created_by'
		),
		'UpdatedBy' => array(
			'className' => 'User',
			'foreignKey' => 'cp_updated_by'
		)
	);
	public $validate = array();
	
}