<?php
App::uses('AppModel', 'Model');
class Sale extends AppModel {

	public $primaryKey	= 's_id'; 
	public $belongsTo = array(
		'User' => array(
				'className' => 'User',
				'foreignKey' => 's_u_id',
				/*'counterCache' => true*/
		)
	);
	public $hasMany = array(
			'CardsSale' => array(
				'className' => 'CardsSale',
				'foreignKey' => 'cs_s_id'
		)
	);	
	public $validate = array();
	
}
