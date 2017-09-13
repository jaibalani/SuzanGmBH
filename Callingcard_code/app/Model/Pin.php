<?php
App::uses('AppModel', 'Model');
class Pin extends AppModel {

	public $primaryKey	= 'p_id'; 
	public $hasMany = array(
			'PinsCard' => array(
				'className' => 'PinsCard',
				'foreignKey' => 'pc_p_id'
		),
	);	
}
