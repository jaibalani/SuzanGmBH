<?php
App::uses('AppModel', 'Model');
class FrontImage extends AppModel {

	public $primaryKey	= 'id'; 
	
	
	public $validate = array(
	
	 'title_english' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Title For English is required.'
            ),
			 'allowedCharacters'=> array(
                'rule' => '|^[a-zA-Z ]*$|',
                'message' => 'Please Insert Only letters.'
            )
        ),
	 'title_german' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Title For German is required.'
            ),
			 'allowedCharacters'=> array(
                'rule' => '|^[a-zA-Z ]*$|',
                'message' => 'Please Insert Only letters.'
            )
        ),

     'content_english' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Content For English is required.'
            ),
        ),
	 'content_german' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Content For German is required.'
            ),
        ),
	);
}
