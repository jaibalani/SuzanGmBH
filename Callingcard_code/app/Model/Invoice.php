<?php
App::uses('AppModel', 'Model');
class Invoice extends AppModel {

	public $primaryKey	= 'id'; 
	public $belongsTo = array(
		'User' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
				/*'counterCache' => true*/
		),
		'Card' => array(
				'className' => 'Card',
				'foreignKey' => 'card_id',
				/*'counterCache' => true*/
		)
			
	);
	public $validate = array();
	
}
