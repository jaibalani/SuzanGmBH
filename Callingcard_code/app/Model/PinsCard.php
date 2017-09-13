<?php
App::uses('AppModel', 'Model');
class PinsCard extends AppModel {
	public $primaryKey	= 'pc_id';
	public $belongsTo = array(
		'Pin' => array(
			'className' => 'Pin',
			'foreignKey' => 'pc_p_id'
		),
	 'Card' => array(
	 				'className' 	 => 'Card',
					'foreignKey' 	 => 'pc_c_id',
					'counterCache' => array(
							'pin_card_count' => array('PinsCard.pc_status !=' => 0),
							'pin_card_sold_count' => array('PinsCard.pc_status' => 2),
							'pin_card_remain_count' => array('PinsCard.pc_status' => 1),
							'pin_card_count_parked' => array('PinsCard.pc_status' =>3 ),
							'pin_card_count_rejected' => array('PinsCard.pc_status' =>4),
							'pin_card_count_returned' => array('PinsCard.pc_status' =>5)
					)
			),
		'CardMergedFrom' => array(
	 				'className' 	 => 'Card',
					'foreignKey' 	 => 'pc_merged_from_c_id',
		),	
			/*'CardCount' => array(
				'counterCache' => true,
				'foreignKey' => 'pc_c_id',
				'className' => 'Card',
				'conditions' => array('Activity.type' => 'walk'),
				'counterScope' => array('Activity.type' => 'walk')
	
							 ),
			 'CardSold' => array(
				'counterCache' => true,
				'foreignKey' => 'user_id',
				'className' => 'Card',
				'conditions' => array('Activity.type' => 'run'),
				'counterScope' => array('Activity.type' => 'run')
	
							 ),*/
		
	);
	
}