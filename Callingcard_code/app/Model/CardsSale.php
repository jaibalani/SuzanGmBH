<?php
App::uses('AppModel', 'Model');
class CardsSale extends AppModel {

	public $primaryKey	= 'cs_id';
	public $belongsTo = array(
		'Sale' => array(
			'className' => 'Sale',
			'foreignKey' => 'cs_s_id',
			'counterCache' => 'card_sale_count'//for counting cards sold = net qty during current purchase
		),
		'Card' => array(
			'className' => 'Card',
			'foreignKey' => 'cs_c_id',
			'counterCache' => 'sale_count'//how many cards are sold of this c_id 
		),
		
	);
		public $hasMany = array(
			'CardsPinsSale' => array(
				'className' => 'CardsPinsSale',
				'foreignKey' => 'cps_cs_id'
		)
	);	
	public $validate = array();
	
}