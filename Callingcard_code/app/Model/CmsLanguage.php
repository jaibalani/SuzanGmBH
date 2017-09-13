<?php
App::uses('AuthComponent', 'Controller/Component');

class CmsLanguage extends AppModel {

public $name = 'CmsLanguage';

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
                'message' => ' This field is required.'
            )
        ),
		'content' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => ' This field is required.'
            )
		    )
  );

}
