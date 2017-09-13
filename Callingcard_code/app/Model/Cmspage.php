<?php

App::uses('AppModel', 'Model');


class Cmspage extends AppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'Cmspage';


/**
 * Validation
 *
 * @var array
 * @access public
 */
	public $validate = array(
	
		'title' => array(
				'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'This field is required.'
            ),
		)
	);

}
