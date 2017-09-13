<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('Sanitize', 'Utility');

class SearchesController extends AppController {

    public $components = array('RequestHandler','Email','Paginator');
	
	public $helpers = array('Html','Form','Paginator');	
	
	public function beforeFilter($options = array()){
		parent::beforeFilter();	
	}
	public function admin_index(){
		$this->set('title_for_layout','Card List');
		$this->loadModel('Category');
		
   		$all_catList = $this->Category->find('list',array(
			'fields'        => array('Category.cat_id','Category.cat_title'),
			'conditions'	=> array('Category.cat_parent_id'=> null,'Category.cat_status'=>1),
			'recursive'		=> -1,
			'order' =>'Category.cat_title asc'
		));

        //prd($all_cat);
		
		foreach($all_catList as $k => $v)
		$all_catList[$k] = ucwords(strtolower($v));

		$this->set('all_catList',$all_catList);
		$active_tab = '';
		
	
		if($this->request->is('post')){
			$url   = '';
			$PostData=$this->request->data;
			
			$Search_tab = '';
            //prd($PostData);
			if(isset($this->request->data['Card']['c_cat_id']) && !empty($this->request->data['Card']['c_cat_id'])){
				$url .= '/cat:'.$this->request->data['Card']['c_cat_id'];
			}
            if(isset($PostData['Card']['sub_cat_id']) && !empty($PostData['Card']['sub_cat_id']) ) {
				$url .= '/sub_cat_id:'.$PostData['Card']['sub_cat_id'];
			}
            if(isset($PostData['Card']['c_id']) and !empty($PostData['Card']['c_id'])){
				$url .= '/c_id:'.$PostData['Card']['c_id'];
			}
			if(isset($PostData['Card']['stock']) and !empty($PostData['Card']['stock'])){
				$url .= '/stock:'.$PostData['Card']['stock'];
			}
			if(isset($PostData['Card']['view']) and !empty($PostData['Card']['view'])){
				$url .= '/view:'.$PostData['Card']['view'];
			}
			if(isset($PostData['Card']['rate']) and !empty($PostData['Card']['rate'])){
				$url .= '/rate:'.$PostData['Card']['rate'];
			}
			if(isset($PostData['Card']['cards_char']) and !empty($PostData['Card']['cards_char'])){
				$url .= '/char:'.$PostData['Card']['cards_char'];
			}
			else
			{
				$url .= '/char:All';
			}
			$this->redirect(array('controller'=>'searches','action'=>'index/'.$url));
		}
        
        $named = $this->request->named;

		$subCatConditions = array();
		$subCatConditions['Category.cat_status'] = 1;
		$subCatConditions['Category.cat_parent_id <>'] = null;

		if(isset($named['cat']) && !empty($named['cat'])) {
			$this->set('cat',$named['cat']);	
			$subCatConditions['Category.cat_parent_id'] = $named['cat'];
		}

		/*$resSubCat = $this->Category->find('list',array(
						'conditions' => $subCatConditions,
						'fields' => array('cat_id','cat_title'),
						'recursive'	 => -1
					));
		*/

		$resSubCat = $this->Category->find('list',array(
						'conditions' => $subCatConditions,
						'fields' => array('cat_id','cat_title'),
						'recursive'	 => -1,
						'joins' => array(
							array(
								'table' => 'ecom_categories',
								'alias' => 'Category_Parent',
								'type' => 'inner',
								'conditions' => 'Category_Parent.cat_id =Category.cat_parent_id'
							)
						),
						'order'=>'cat_title asc'
					));

		
		$query_condition = array('Card.c_status' => 1);
		$Search_tab = '';
		$selchar = 'All';
		$conditions_card = array();
		if(isset($this->request->params['named']['cat']) && !empty($this->request->params['named']['cat'])){
			$all_cats = $this->Category->find('first',array(
				'fields' 		=> array('group_concat(Category.cat_id) as cat_ids'),
				'conditions'=> array('Category.cat_parent_id'=>$this->request->params['named']['cat']),
				'recursive'	=> -2
			));
			
			if(isset($all_cats[0]['cat_ids']) && !empty($all_cats[0]['cat_ids'])){
				$query_condition[] = 'Card.c_cat_id IN('.$all_cats[0]['cat_ids'].')';
				$conditions_card[] = 'Card.c_cat_id IN('.$all_cats[0]['cat_ids'].')';
			}else{
				$query_condition[] = 'Card.c_cat_id = '.$this->request->params['named']['cat']; //which will never be possible as cards can only be added in child categories
			}
			$Search_tab = $this->request->params['named']['cat'];
			$this->request->data['Card']['c_cat_id'] = $this->request->params['named']['cat'];
            
		}
        
        if(isset($this->request->params['named']['sub_cat_id']) && !empty($this->request->params['named']['sub_cat_id'])){
			$conditions_card[] = 'Card.c_cat_id = '.$this->request->params['named']['sub_cat_id'];
			
			$query_condition['Card.c_cat_id'] = $this->request->params['named']['sub_cat_id'];
			$this->request->data['Card']['c_catsub_id'] = $this->request->params['named']['sub_cat_id'];
            $this->set('sub_cat_id',$this->request->params['named']['sub_cat_id']);	
		}
        
		if(isset($this->request->params['named']['c_id']) && !empty($this->request->params['named']['c_id'])){
			$query_condition['Card.c_id'] = $this->request->params['named']['c_id'];
			$this->request->data['Card']['c_id'] = $this->request->params['named']['c_id'];
		}
        
		if(isset($this->request->params['named']['stock']) && !empty($this->request->params['named']['stock'])){
			if($this->request->params['named']['stock']==1){
				//all cards
			}else if($this->request->params['named']['stock']==2){
				//available stock
				$query_condition['Card.pin_card_remain_count > '] = 0;
			}
			
			$this->request->data['Card']['stock'] = $this->request->params['named']['stock'];
		}
		$view = '';
		if(isset($this->request->params['named']['view']) && !empty($this->request->params['named']['view'])){
			$this->request->data['Card']['view'] = $this->request->params['named']['view'];
			$view = $this->request->params['named']['view'];
		}
		$group = 'Card.c_id';
		$having = '';	
		if(isset($this->request->params['named']['rate']) && !empty($this->request->params['named']['rate'])){
			switch($this->request->params['named']['rate']){
				case '1'	: $having = ' price >=0 and price <= 1';	//Under 100
										break;
				case '2'	: $having = ' price >1 and price <= 2.5';	
										break;
				case '3'	: $having = ' price > 2.5 and price <= 5';	
										break;
				case '4'	: $having = ' price > 5 and price <= 10';	
										break;										
				case '5'	: $having = ' price > 10';	
										break;
			}
			$this->request->data['Card']['rate'] = $this->request->params['named']['rate'];
		
		}
		if(isset($this->request->params['named']['char']) && !empty($this->request->params['named']['char'])){
			if($this->request->params['named']['char'] != 'All')
			$query_condition['Card.c_title LIKE'] = Sanitize::clean($this->request->params['named']['char']).'%';
			$selchar = $this->request->params['named']['char'];
			$this->request->data['Card']['cards_char'] = $this->request->params['named']['char'];
		}
		
		
		
		//CASE 1 - Login user and his id are available in cash price table this means they have updated their price once
		//CASE 2 - If login user's id is not available but in case of mediator, distributor will set selling price for all its mediator.
		// CASE 3 - If login user's id is not available and also no price is set for such user role, then we will check prices of their parent.
		
		/*$case = 'case 
					when CardsPrice.cp_u_role = "'.$this->Session->read('Auth.User.role_id').'" and CardsPrice.cp_u_id ='.$this->Session->read('Auth.User.id').' then CardsPrice.cp_u_role = "'.$this->Session->read('Auth.User.role_id').'" and CardsPrice.cp_c_id = Card.c_id and CardsPrice.cp_u_id ='.$this->Session->read('Auth.User.id').'
					when CardsPrice.cp_u_role = "'.$this->Session->read('Auth.User.role_id').'" and CardsPrice.cp_u_id IS NULL then  CardsPrice.cp_u_role = "'.$this->Session->read('Auth.User.role_id').'" and CardsPrice.cp_u_id IS NULL  and CardsPrice.cp_c_id = Card.c_id
					when CardsPrice.cp_u_id ='.$this->Session->read('Auth.User.added_by').' then CardsPrice.cp_u_id ='.$this->Session->read('Auth.User.id').' and CardsPrice.cp_c_id = Card.c_id
				end';
		*/

		  $case = 'CASE
	    			 
	    			WHEN  EXISTS (
	    				           SELECT * FROM ecom_cards_prices as CardsPrice
    				               WHERE 
    				              
    				              (
    				              	CardsPrice.cp_u_id = "'.$this->Session->read('Auth.User.added_by').'" 
                                    and   CardsPrice.cp_c_id = Card.c_id
                                  )
                                  
                                  and NOT EXISTS
                                  (
                                  	  SELECT * FROM ecom_cards_prices as CardsPrice
	    				              WHERE 
	    				              CardsPrice.cp_u_id = "'.$this->Session->read('Auth.User.id').'" 
                                      and   CardsPrice.cp_c_id = Card.c_id
                                  )

	    				         ) 
					then 
		            CardsPrice.cp_u_id ="'.$this->Session->read('Auth.User.added_by').'" 
		            and CardsPrice.cp_c_id = Card.c_id


	    			WHEN EXISTS (
	    				           SELECT * FROM ecom_cards_prices as CardsPrice
	    				              WHERE 
	    				              CardsPrice.cp_u_id = "'.$this->Session->read('Auth.User.id').'" 
                                      and   CardsPrice.cp_c_id = Card.c_id
	    				         ) 
					then 
		            CardsPrice.cp_u_id ="'.$this->Session->read('Auth.User.id').'" 
		            and CardsPrice.cp_c_id = Card.c_id

		          
	             END
	            ';
	    //$this->Card->Behaviors->attach('Containable');		
		/*$this->Card->contain(array(
				'CardsPrice' =>array(
					'fields'     => array('CardsPrice.cp_selling_price'),
					'conditions' => $case//array('cp_u_role'=>$this->Session->read('Auth.User.role'))
				)
		));*/
		if($having!=''){
			$group .=' having'.$having; 
		}
		 if($this->Session->read('Auth.User.role_id') == 1)
		{
			$query_case ='CASE WHEN CardsPrice.cp_buying_price IS NOT NULL THEN CardsPrice.cp_buying_price ELSE Card.c_buying_price END AS price';
		}
		else
		{
			$query_case = 'CASE WHEN CardsPrice.cp_buying_price IS NOT NULL THEN CardsPrice.cp_buying_price ELSE Card.c_selling_price END AS price';
		}
      
        if(!isset($this->request->params['named']['sub_cat_id']) && !isset($this->request->params['named']['cat']) && !isset($this->request->params['named']['c_id']))
		{
             
             $resSubCat = $this->Category->find('list',array(
								'conditions' =>array('Category.cat_status'=>1,'Category.cat_parent_id <>'=>null),
								'fields' => array('cat_id','cat_title'),
								'recursive'	 => -1,
								'joins' => array(
									array(
										'table' => 'ecom_categories',
										'alias' => 'Category_Parent',
										'type' => 'inner',
										'conditions' => 'Category_Parent.cat_id =Category.cat_parent_id'
									)
								),
								'order'=>'cat_title asc'
							));
	          $all_sub_category = array();
	          foreach( $resSubCat as $k =>$v)
	          $all_sub_category[] = $k;
	          $query_condition['Card.c_cat_id'] =  $all_sub_category;
	          $conditions_card['c_cat_id'] = $all_sub_category;
		} 


		$this->loadModel('Card');
		$conditions_card['c_status'] = 1;
		$card_names = $this->Card->find('list',array(
			'fields'		 => array('c_id','c_title'),
			'conditions' => $conditions_card,
			'order'=>'c_title asc'
		));	
        


		$cards = $this->Card->find('all',array(
			'joins' => array(
					array(
						'table' => 'ecom_cards_prices ',
						'alias' => 'CardsPrice',
						'type' => 'left',
						'conditions' => $case
					),
                    array(
						'table' => 'ecom_categories ',
						'alias' => 'SubCate',
						'type' => 'left',
						'conditions' => 'SubCate.cat_id = Card.c_cat_id'
					),
                    array(
						'table' => 'ecom_categories',
						'alias' => 'MainCate',
						'type' => 'left',
						'conditions' => 'MainCate.cat_id = SubCate.cat_parent_id'
					)
				),
			'conditions' => $query_condition,
			'fields'		 => array('*',$query_case),
			'recursive'	 => -1,
    		'order'=>'Card.c_title asc',
			'group'			 => $group,
		));
        
        //prd($cards);
		//$this->Card->Behaviors->detach('Containable');
     	//prd($cards);
         $counter_card =0;                   
         $this->loadModel('PinsCard');
         foreach ($cards as $c_data)
         {
             $id = $c_data['Card']['c_id'];
             $get_pins_count = $this->PinsCard->find('count', array('conditions'=>array('pc_status'=>1,'pc_c_id'=>$id)));
             $cards[$counter_card]['Card']['pin_card_remain_count'] = $get_pins_count;               
             $counter_card++;               
         }
             
             //prd($cards);   
		foreach($resSubCat as $k => $v)
		$resSubCat[$k] = ucwords(strtolower($v));

		foreach($card_names as $k => $v)
		$card_names[$k] = ucwords(strtolower($v));
		
		//prd($card_names);
		$this->set('view',$view);
		$this->set('cards',$cards);
		$this->set('active_tab',$Search_tab);
		$this->set('selchar',$selchar);
		$this->set('card_names',$card_names);
        $this->set('subCatList',$resSubCat);
		
	}
	public function online_card(){

		$this->set('title_for_layout','Online Cards');
		$this->loadModel('Category');
		$language=Configure::read('Config.language');
		
		if(empty($language))
		{
			$language="en";	
		}
		
		//echo $language;exit;
		$this->Category->Behaviors->attach('Containable');
		
		$this->Category->unbindModel(array(
				'hasMany'   => array('CategoriesLanguage')
			));
		
		$this->Category->bindModel(array(
          'hasMany' => array(
							'CategoriesLanguage' => array(
								'foreignKey' => 'cl_cat_id',
								'conditions' => 'CategoriesLanguage.cl_alias LIKE "'.$language.'"',
						)),
			
        ));
				
		$all_cat = $this->Category->find('all',array(
			'fields' 			=> array('*'),
			'conditions'	=> array('Category.cat_parent_id' => NULL,'Category.cat_status'=>1),
			'order'=>'Category.cat_title asc',
			'contain'=>array('CategoriesLanguage' => array()),
			'recursive'		=> -1
		));
		
		//prd($all_cat);

		$cat_counter = 0;
		foreach($all_cat as $al_cat)
		{
			$exist_sub = $this->Category->hasAny(array('Category.cat_parent_id' => $al_cat['Category']['cat_id'],'Category.cat_status'=>1));
			if(empty($exist_sub))
			unset($all_cat[$cat_counter]);
			$cat_counter++;		
		}
		
		$sub_cat = array();
		$sub_cat_for_card =array();
		$all_sub_category_default = array();
		foreach($all_cat as $cat)
		{
			$all_sub_cat = $this->Category->find('list',array(
			'fields' 			=> array('*'),
			'conditions'	=> array('Category.cat_parent_id'=>$cat['Category']['cat_id'],'Category.cat_status'=>1),
			'fields'=>array('Category.cat_id','Category.cat_title'),
			'contain'=>array('CategoriesLanguage' => array()),
			'order'=>'Category.cat_title asc',
			'recursive'		=> -1
		    ));
			$main_id = $cat['Category']['cat_id'];
			$sub_cat[$main_id] = $all_sub_cat;
		    foreach($all_sub_cat  as $k=>$v)
			{
				$sub_cat_for_card[$main_id][]=$k;
				$all_sub_category_default[] = $k;
			}
			if(empty($all_sub_cat))
			$sub_cat_for_card[$main_id] =array();
		}
		
		$this->set('all_cat',$all_cat);
		$this->set('sub_cat',$sub_cat);
        
       // pr($sub_cat);
		$active_tab = '';
		$this->loadModel('Card');
		
    	if(isset($this->request->params['named']['char']) && !empty($this->request->params['named']['char']))
    	{
			if($this->request->params['named']['char'] != 'All')
			{
				$card_names = $this->Card->find('list',array(
							'fields'		 => array('c_id','c_title'),
							'order'=>'c_title asc',
							'conditions' => array('c_status'=>1,
							'c_cat_id'=>$all_sub_category_default,
							'c_title LIKE' => Sanitize::clean($this->request->params['named']['char']).'%'
							)
		     ));
			}
			
		}
		else
		{
			$card_names = $this->Card->find('list',array(
							'fields'		 => array('c_id','c_title'),
							'order'=>'c_title asc',
							'conditions' => array('c_status'=>1,'c_cat_id'=>$all_sub_category_default)
		));
		}

		
			
		if($this->request->is('post'))
		{
			//prd($this->request);
			$url   = '';
			$PostData=$this->request->data;
			$Search_tab = '';
			$sub_search_tab = '';
			$sub_cat_id = 0;
			$cat_id = 0;
			
			//prd($this->request->data);
			
			if(isset($this->request->data['Card']['c_cat_id']) && !empty($this->request->data['Card']['c_cat_id'])){
				$url .= '/cat:'.$this->request->data['Card']['c_cat_id'];
			    $cat_id =$this->request->data['Card']['c_cat_id'];
			}
			if(isset($this->request->data['Card']['c_sub_cat_id']) && !empty($this->request->data['Card']['c_sub_cat_id'])){
				$url   = '';
				$url .= '/sub_cat_id:'.$this->request->data['Card']['c_sub_cat_id'];
				$sub_cat_id =$this->request->data['Card']['c_sub_cat_id'];
			}
			
			if(isset($PostData['Card']['c_id']) and !empty($PostData['Card']['c_id'])){
				$implode_cid = implode(',',$PostData['Card']['c_id']);
				$url .= '/c_id:'.$implode_cid;
			}
			if(isset($PostData['Card']['stock']) and !empty($PostData['Card']['stock'])){
		        $implode_stock = implode(',',$PostData['Card']['stock']);
				$url .= '/stock:'.$implode_stock;
			}
			if(isset($PostData['Card']['view']) and !empty($PostData['Card']['view'])){
			    $implode_view = implode(',',$PostData['Card']['view']);
				$url .= '/view:'.$implode_view;
			}
			if(isset($PostData['Card']['rate']) and !empty($PostData['Card']['rate'])){
			    $implode_rate = implode(',',$PostData['Card']['rate']);
				$url .= '/rate:'.$implode_rate;
			}
			if(isset($PostData['Card']['c_char']) and !empty($PostData['Card']['c_char'])){
				$url .= '/char:'.$PostData['Card']['c_char'];
			}
    		if(isset($PostData['Card']['price']) and !empty($PostData['Card']['price'])){
				$url .= '/price_order:'.$PostData['Card']['price'];
			}

			$this->redirect(array('controller'=>'Searches','action'=>'online_card/'.$url));
		}
		
		
		$query_condition = array('Card.c_status' => 1);
		$Search_tab = '';
		$sub_search_tab = '';

		$selchar = '';
		
		$card_order = 'price ASC,Card.c_title ASC';
		if(isset($this->request->params['named']['price_order']) && !empty($this->request->params['named']['price_order'])){
                $order_card =$this->request->params['named']['price_order'];
			if($order_card == 1) // Ascending Price
			$card_order = 'price ASC,Card.c_title ASC';
			else
			$card_order = 'price DESC,Card.c_title ASC';
			$this->set('price_order',$this->request->params['named']['price_order']);	
		}

		if(isset($this->request->params['named']['cat']) && !empty($this->request->params['named']['cat']))
		{
			$all_cats = $this->Category->find('first',array(
				'fields' 		=> array('group_concat(Category.cat_id) as cat_ids'),
				'conditions'=> array('Category.cat_parent_id'=>$this->request->params['named']['cat']),
				'recursive'	=> -2
			));
			if(isset($all_cats[0]['cat_ids']) && !empty($all_cats[0]['cat_ids'])){
				$query_condition[] = 'Card.c_cat_id IN('.$all_cats[0]['cat_ids'].')';
			}else{
				$query_condition[] = 'Card.c_cat_id = '.$this->request->params['named']['cat']; //which will never be possible as cards can only be added in child categories
			}
			$Search_tab = $this->request->params['named']['cat'];
			$this->request->data['Card']['c_cat_id'] = $this->request->params['named']['cat'];
		
		    if(isset($this->request->params['named']['char']) && !empty($this->request->params['named']['char']))
	    	{
				if($this->request->params['named']['char'] != 'All')
				{
					 $card_names = $this->Card->find('list',array(
					'fields'		 => array('c_id','c_title'),
					'conditions' => array(
										'c_status'=>1,
										'c_cat_id'=>$sub_cat_for_card[$Search_tab],
										'c_title LIKE' => Sanitize::clean($this->request->params['named']['char']).'%'
									)
				     ));
				}
				
			}
			else
			{
				 $card_names = $this->Card->find('list',array(
				'fields'		 => array('c_id','c_title'),
				'conditions' => array('c_status'=>1,'c_cat_id'=>$sub_cat_for_card[$Search_tab])
			     ));
			}

			$this->set('c_cat_id',$Search_tab);

		
		}
		else if(isset($this->request->params['named']['sub_cat_id']) && !empty($this->request->params['named']['sub_cat_id'])){
			
			$query_condition['Card.c_cat_id'] = $this->request->params['named']['sub_cat_id'];
			$parent_id = $this->Category->findByCatId($this->request->params['named']['sub_cat_id']);
			$Search_tab = $parent_id['Category']['cat_parent_id'];
			$sub_search_tab = $this->request->params['named']['sub_cat_id'];
			$this->request->data['Card']['c_cat_id'] = $this->request->params['named']['sub_cat_id'];
			
			
			if(isset($this->request->params['named']['char']) && !empty($this->request->params['named']['char']))
	    	{
				if($this->request->params['named']['char'] != 'All')
				{
					 $card_names = $this->Card->find('list',array(
						'fields'		 => array('c_id','c_title'),
						'conditions' => array('c_status'=>1,
											  'c_cat_id'=>$sub_search_tab,
											  'c_title LIKE' => Sanitize::clean($this->request->params['named']['char']).'%'
											 )
					));
				}
				
			}
			else
			{
				 $card_names = $this->Card->find('list',array(
				'fields'		 => array('c_id','c_title'),
			 	'conditions' => array('c_status'=>1,'c_cat_id'=>$sub_search_tab)
			    ));
			}

			$this->set('c_sub_cat_id',$sub_search_tab);
			
			$get_category_data = $this->Category->findByCatId($sub_search_tab);
            $sub_cat_name = ucwords(strtolower($get_category_data['Category']['cat_title']));
            
            //prd($sub_Cat_name);
            $this->set('sub_cat_name',$sub_cat_name);
		}
		
		if(isset($this->request->params['named']['c_id']) && !empty($this->request->params['named']['c_id'])){
			$query_condition['Card.c_id'] = explode(',',$this->request->params['named']['c_id']);
			$this->request->data['Card']['c_id'] = $this->request->params['named']['c_id'];
			$this->set('selected_card',$query_condition['Card.c_id']);
			$this->set('selected_card_name',implode(',',$query_condition['Card.c_id']));
		}
		
		if(isset($this->request->params['named']['char']) && !empty($this->request->params['named']['char'])){
			if($this->request->params['named']['char'] != 'All')
			$query_condition['Card.c_title LIKE'] = Sanitize::clean($this->request->params['named']['char']).'%';
			$selchar = $this->request->params['named']['char'];
			$this->request->data['Card']['c_char'] = $this->request->params['named']['char'];
			$this->set('selchar',$selchar);
		}

		if(isset($this->request->params['named']['stock']) && !empty($this->request->params['named']['stock'])){
    		
			$selected_stock = $this->request->params['named']['stock'];
			if($selected_stock==1){
				//all cards
			}else if($selected_stock==2){
				//available stock
				$query_condition['(Card.pin_card_remain_count - Card.c_pin_per_card) >='] = 0;
			}
			$this->request->data['Card']['stock'] = $selected_stock;
			$this->set('selected_stock',$selected_stock);
		}
		
		$view = '';
		if(isset($this->request->params['named']['view']) && !empty($this->request->params['named']['view'])){
   		    $selected_view = $this->request->params['named']['view'];
			$this->request->data['Card']['view'] = $selected_view;
			$view = $selected_view;
			$this->set('selected_view',$selected_view);
		}
		$group = 'Card.c_id';
		$having = '';	
		if(isset($this->request->params['named']['rate']) && !empty($this->request->params['named']['rate'])){
   		    $selected_rate = $this->request->params['named']['rate'];
			/*switch($selected_rate){
				case '1'	: $having = ' price >= 0';	//Under 100
										break;
				case '2'	: $having = ' price >= 101 and price < 200';	
										break;
				case '3'	: $having = ' price >= 201 and price < 300';	
										break;
				case '4'	: $having = ' price >= 301 and price < 400';	
										break;										
				case '5'	: $having = ' price >= 401 and price < 500';	
										break;
				case '6'	: $having = ' price >= 501 and price < 600';	
										break;	
				case '7'	: $having = ' price >= 600';	
										break;																														
			}*/
			switch($selected_rate){
				case '1'	: $having = ' price >0 and price <= 1';	//Under 100
										break;
				case '2'	: $having = ' price >1 and price <= 2.5';	
										break;
				case '3'	: $having = ' price >2.5 and price <= 5';	
										break;
				case '4'	: $having = ' price >5 and price <= 10';	
										break;										
				case '5'	: $having = ' price >10';	
										break;
			}
			$this->request->data['Card']['rate'] = $selected_rate;
			$this->set('selected_rate',$selected_rate);
		}
		
		$case = 'CASE
	    			 
	    			WHEN  EXISTS (
	    				           SELECT * FROM ecom_cards_prices as CardsPrice
    				               WHERE 
    				              
    				              (
    				              	CardsPrice.cp_u_id = "'.$this->Session->read('Auth.User.added_by').'" 
                                    and   CardsPrice.cp_c_id = Card.c_id
                                  )
                                  
                                  and NOT EXISTS
                                  (
                                  	  SELECT * FROM ecom_cards_prices as CardsPrice
	    				              WHERE 
	    				              CardsPrice.cp_u_id = "'.$this->Session->read('Auth.User.id').'" 
                                      and   CardsPrice.cp_c_id = Card.c_id
                                  )

	    				         ) 
					then 
		            CardsPrice.cp_u_id ="'.$this->Session->read('Auth.User.added_by').'" 
		            and CardsPrice.cp_c_id = Card.c_id


	    			WHEN EXISTS (
	    				           SELECT * FROM ecom_cards_prices as CardsPrice
	    				              WHERE 
	    				              CardsPrice.cp_u_id = "'.$this->Session->read('Auth.User.id').'" 
                                      and   CardsPrice.cp_c_id = Card.c_id
	    				         ) 
					then 
		            CardsPrice.cp_u_id ="'.$this->Session->read('Auth.User.id').'" 
		            and CardsPrice.cp_c_id = Card.c_id

		          
	             END
	            ';
	    if($having!='')
	    {
			$group .=' having'.$having; 
		}
		
		if(!isset($this->request->params['named']['sub_cat_id']) && !isset($this->request->params['named']['cat']) && !isset($this->request->params['named']['c_id']))
		{
            $resSubCat = $this->Category->find('list',array(
								'conditions' =>array('Category.cat_status'=>1,'Category.cat_parent_id <>'=>null),
								'fields' => array('cat_id','cat_title'),
								'recursive'	 => -1,
								'joins' => array(
									array(
										'table' => 'ecom_categories',
										'alias' => 'Category_Parent',
										'type' => 'inner',
										'conditions' => 'Category_Parent.cat_id =Category.cat_parent_id'
									)
								),
								'order'=>'cat_title asc'
							));
	        $all_sub_category = array();
	        foreach( $resSubCat as $k =>$v)
	        $all_sub_category[] = $k;
	        $query_condition['Card.c_cat_id'] =  $all_sub_category;
		}
       
        //prd($query_condition);
        $cards = $this->Card->find('all',array(
				'recursive'=>-1,
				'joins' => array(
					array(
						'table' => 'ecom_cards_prices ',
						'alias' => 'CardsPrice',
						'type' => 'left',
						'conditions' => $case
					)
				),
			'conditions' => $query_condition,
			'fields'=> array('*','CASE WHEN CardsPrice.cp_selling_price IS NOT NULL 
								  THEN CardsPrice.cp_selling_price ELSE 
								  Card.c_selling_price END AS price'),
			'order'=>$card_order,
			'group'	 => $group,
		   // 'limit'=>9,
			));

        /* For Card Name Drop Down*/
         
        if(isset($query_condition['Card.c_id']))
		{
             unset($query_condition['Card.c_id']);
		}

		if(isset($query_condition['(Card.pin_card_remain_count - Card.c_pin_per_card) >=']))
		{
             unset($query_condition['(Card.pin_card_remain_count - Card.c_pin_per_card) >=']);
		}
		
        //pr($query_condition);

        $query_condition['Category.cat_status'] = 1;
        $query_condition['Parent.cat_status'] = 1;

        //prd($query_condition);
        $cards_name_drop_down = $this->Card->find('all',array(
				'recursive'=>-1,
				'joins' => array(
					array(
						'table' => 'ecom_categories',
						'alias' => 'Category',
						'type' => 'left',
						'conditions' => 'Category.cat_id = Card.c_cat_id'
					),
					array(
						'table' => 'ecom_categories',
						'alias' => 'Parent',
						'type' => 'left',
						'conditions' => 'Parent.cat_id = Category.cat_parent_id'
					),
				),
			'conditions' => $query_condition,
			'order'=>'Card.c_title ASC',
			//'fields'=>'*',
		));

        $unset_card_id = array();
        foreach ($cards_name_drop_down as $key => $car) {
            
            $pin_remain_count 	= $car['Card']['pin_card_remain_count'];
            $pin_parked_count 	= $car['Card']['pin_card_count_parked'];
        	$pin_returned_count = $car['Card']['pin_card_count_rejected'];
        	$pin_rejected_count = $car['Card']['pin_card_count_returned'];

            if($pin_remain_count == 0 && $pin_parked_count != 0)
            {
            	$unset_card_id[] = $car['Card']['c_id'];
            	if(in_array($car['Card']['c_id'],$unset_card_id))
	        	{
	                  unset($card_names[$car['Card']['c_id']]);
	                  continue;
	        	}	
            	continue;
            }
        	$card_names[$car['Card']['c_id']] = ucwords($car['Card']['c_title']);
        }
       
		foreach($cards as $key => $car)
        {
        	if(in_array($car['Card']['c_id'],$unset_card_id))
        	{
                  unset($cards[$key]);
                  continue;
        	}
        	
        	if($car[0]['price'] == 0 && $car['CardsPrice']['cp_selling_price'] == 0)
        	{
        		if(!empty($car['CardsPrice']['cp_buying_price']))
        		$cards[$key][0]['price'] = $car['CardsPrice']['cp_buying_price'];
        	    else
        	    $cards[$key][0]['price'] = $car['Card']['c_selling_price'];
        	}
        	else
        	{
        		if(!empty($car['CardsPrice']['cp_buying_price']))
        		$cards[$key][0]['price'] = $car['CardsPrice']['cp_buying_price'];
        		else
        	    $cards[$key][0]['price'] = $car['Card']['c_selling_price'];
        	}
        	/* New Changes Selling + Buying Price 27 March 2015*/
            $cards[$key][0]['buying_price'] = $cards[$key][0]['price'];
            $cards[$key][0]['price'] = $cards[$key]['Card']['c_denomination_rate'];
        }
        
        //prd($cards);
		/*$this->paginate = array(
				'joins' => array(
					array(
						'table' => 'ecom_cards_prices ',
						'alias' => 'CardsPrice',
						'type' => 'left',
						'conditions' => $case
					)
				),
			'conditions' => $query_condition,
			'fields'=> array('*','CASE WHEN CardsPrice.cp_selling_price IS NOT NULL THEN CardsPrice.cp_selling_price ELSE Card.c_selling_price END AS price'),
			'order'=>'Card.c_title asc',
			'group'	 => $group,
		    'limit'=>9,
			);
		$cards = $this->paginate('Card');*/
		//$this->Card->Behaviors->detach('Containable');
  	    //prd($cards);
	   
		$this->set('view',$view);
		//prd($cards);
		$this->set('cards',$cards);
		$this->set('active_tab',$Search_tab);
		$this->set('sub_search_tab',$sub_search_tab);
		$this->set('selchar',$selchar);
		asort($card_names);
		$this->set('card_names',$card_names);

		//$total_cards =$this->request->params['paging']['Card']['count'];
		
		$this->set('total_cards_pagination',count($cards));
	}
	
	public function print_preview_popup($card_id,$sellig_price){

    	$this->layout = 'fancybox';
    	$this->loadModel('Card');
    	$card_data = $this->Card->find('first',array('conditions'=>array('c_id'=>$card_id),
    												  /*'recursive'=>'-1',*/	
    												));
    	//prd($card_data);
    	$this->set('title_for_layout', __('Card Print Preview'));
    	$card_preview_details = array();
    	if(!empty($card_data['Card']['c_image']))
		{
			if(file_exists(WWW_ROOT.'img/card_icons/'.$card_data['Card']['c_image']))
			{
				$card_image = IMAGE_PATH.'/img/card_icons/'.$card_data['Card']['c_image'];
                                    $image_name = $card_data['Card']['c_image'];
			}
			else
			{
				$card_image = IMAGE_PATH.'/img/card_icons/card_not_availabe.png';
                                    $image_name = 'card_not_availabe.png';
			}
		}
		else
		{
		        $card_image = IMAGE_PATH.'/img/card_icons/card_not_availabe.png';
                             $image_name = 'card_not_availabe.png';
		}
        
		$language=Configure::read('Config.language');
		if($language == "en")
		{
			// English
			$free_text = $card_data['CardsFreeText'][0];
		}
		else
		{
			// German // Code = "deu"
			$free_text = $card_data['CardsFreeText'][1];
		}
        
        $free_card_text = '';
        foreach($free_text as $k=>$text)
        {
            
            if($k !='cf_id' && $k!='cf_c_id' && $k!='cf_alias')
            {
                if(!empty($free_text[$k]))
                {
                 $free_card_text = $free_card_text.ucfirst(strtolower($text))." ";
                }
            }
        }

        $local_number = array();
			
		if(!empty($card_data['Card']['c_local_number_1']))
	        $local_number[] = $card_data['Card']['c_local_number_1'];
	    else
        $local_number[] = 'N/A';
		
		if(!empty($card_data['Card']['c_local_number_2']))
	    $local_number[] = $card_data['Card']['c_local_number_2'];
        else
        $local_number[] = 'N/A';
		
		if(!empty($card_data['Card']['c_local_number_3']))
	        $local_number[] = $card_data['Card']['c_local_number_3'];
 		else
        $local_number[] = 'N/A';
		
		if(!empty($card_data['Card']['c_local_number_4']))
	        $local_number[] = $card_data['Card']['c_local_number_4'];
	    else
        $local_number[] = 'N/A';
		
		if(!empty($card_data['Card']['c_local_number_5']))
	        $local_number[] = $card_data['Card']['c_local_number_5'];
	    else
        $local_number[] = 'N/A';
		
		if(!empty($card_data['Card']['c_local_number_6']))
	        $local_number[] = $card_data['Card']['c_local_number_6'];
		else
        $local_number[] = 'N/A';
			  
        $contact = '';
		if($language=='deu')
        {
			if(!empty($card_data['Card']['c_contact_number_1']))
			{
				$contact=$card_data['Card']['c_contact_number_1'];
			}
		}
        else
        {
			if(!empty($ids['Card']['c_contact_number_2']))
			{
				$contact=$ids['Card']['c_contact_number_2'];
			}
		} 
         
        $set_card_data = array();
        $set_card_data['name'] = $card_data['Card']['c_title'];
        $set_card_data['card_image'] = $card_image;
        $set_card_data['cell_number'] = $contact;
        $set_card_data['free_text'] = $free_card_text;
        $set_card_data['local_number'] = $local_number;
        $set_card_data['pin'] = 'TestPin123';
        $set_card_data['serial'] = 'TestSerial123';
        $set_card_data['date_time'] = date('d.m.Y')." | ".date('H:i.s');
        $set_card_data['price'] = $sellig_price;
        $set_card_data['retailer'] = ucwords(strtolower($this->Auth->User('fname')."".$this->Auth->User('lname')));
	    $set_card_data['url'] = APPLICATION_PATH;

	    $this->set('set_card_data',$set_card_data);
	    //prd($set_card_data);
	}
}