<?php
App::uses('AppModel', 'Model');
class CardsPinsSale extends AppModel {

	public $primaryKey	= 'cps_id';
	public $belongsTo = array(
		'CardsSale' => array(
			'className' => 'CardsSale',
			'foreignKey' => 'cps_cs_id',
			'counterCache' => 'card_pin_sale'// for counting pins sold for this card
		),
		'Pin' => array(
			'className' => 'Pin',
			'foreignKey' => 'cps_p_id',
		),
	);
	public $validate = array();
	
}