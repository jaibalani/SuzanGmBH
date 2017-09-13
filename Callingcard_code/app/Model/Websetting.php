<?php

App::uses('AppModel', 'Model');


class Websetting extends AppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $validate = array(
		'value' => array(
				'required' => array(
									'rule' => array('notEmpty'),
									'message' => ' This field is required.'
						 ),
		));
}
