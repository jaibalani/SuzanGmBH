<?php

App::uses('AppHelper', 'View/Helper');
class GeneralHelper extends AppHelper {


	public $helpers = array(
		'Html',
		'Form',
		'Session',
		'Js'
	);

	public function viewmeta($view_meta = array()) {
		
		$default_meta = array();
		if (is_array(Configure::read('Meta'))) {
			$default_meta = Configure::read('Meta');
		}
		
		if (count($view_meta) == 0 && isset($this->_View->viewVars['page_meta']) && count($this->_View->viewVars['page_meta']) > 0) {
			$view_meta = array();
			foreach ($this->_View->viewVars['page_meta'] as $key => $value) {
				$view_meta[$value['Metatag']['keyword']] = $value['Metatag']['value'];
			}
		}

		$view_meta = array_merge($default_meta, $view_meta);
		
		$output = '';
		foreach ($view_meta as $name => $content) {
			$output .= '<meta name="' . $name . '" content="' . $content . '" />';
		}

		return $output;
	}

	
	
	
}
