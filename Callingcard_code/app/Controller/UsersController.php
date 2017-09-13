<?php
App::uses('AppController', 'Controller');
class UsersController extends AppController {
  
  public $components = array(
  'Auth' => array(
   'authenticate' => array(
    'Form' => array(
     'fields' => array('username' => 'username'),
    )
   )
  )
  );
  
  public function beforeFilter(){
	 	parent::beforeFilter();
		$this->Auth->allow('admin_captcha','captcha','change_language','admin_login','login','forget_password','verifypass','admin_forgotpassword','admin_verifypass');
  }
	
	public function admin_contentUpload()
	{		
		App::import('Vendor', '', array('file' => 'User_UploadHandler.php'));
		$upload_handler = new UploadHandler();			
		exit;
	}
	
	public function admin_contentUpload_front_image()
	{		
		App::import('Vendor', '', array('file' => 'User_UploadHandler_FrontImage.php'));
		$upload_handler = new UploadHandler();			
		exit;
	}
	
	public function contentUpload()
	{		
		App::import('Vendor', '', array('file' => 'User_UploadHandler.php'));
		$upload_handler = new UploadHandler();			
		exit;
	}
	public function login(){
		
		$this->set('title_for_layout',__('Login'));
		$userId=$this->Auth->user('id');	
		
		if($this->Auth->User('id'))
		{
				if($this->Auth->User('role_id') == 1 || $this->Auth->User('role_id') == 2)
				{
					$this->redirect(array('controller'=>'users','action'=>'dashboard','admin'=>true));
				}
				else
				{
					$this->redirect(array('controller'=>'Searches','action'=>'online_card'));
				}
		}
		//SETTING COOKIES VALUES ON LOGIN FORM
		if(isset($this->Cookie)){
			$this->set('cookie_username',$this->Cookie->read('cookie_username'));
			$this->set('cookie_pass',$this->Cookie->read('cookie_password'));
		}
		
		$this->loadModel('CmsImage');
		$keyword = "CMS.WELCOMECALLING";
		// Get values according to user language
		
		$this->loadModel('Cmspage');
		$language=Configure::read('Config.language');
		if(empty($language))
		$language="en";
		

			$this->loadModel('FrontImage');
			$image_data = $this->FrontImage->find('all');
			$image_array = array();
			$counter = 0;
			if($language == "en")
			{
				foreach($image_data as $img)
				{
					$image_array[$counter]['image'] = $img['FrontImage']['image'];
					$image_array[$counter]['content'] = $img['FrontImage']['content_english'];
					$counter++;
				}
			}
			else
			{
				foreach($image_data as $img)
				{
					$image_array[$counter]['content'] = $img['FrontImage']['content_german'];
					$image_array[$counter]['image'] = $img['FrontImage']['image'];
					$counter++;
				}
			}
		$this->set('image_array',$image_array); 

		$cms_content = $this->Cmspage->find('first',array(
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'cms_languages',
					'alias' => 'CmsLanguage',
					'type' => 'INNER', 	
					'conditions' => 'Cmspage.id = CmsLanguage.cmspage_id'
				)
			),
			'conditions' => array('Cmspage.keyword LIKE' => $keyword, 'CmsLanguage.language_alias LIKE' =>$language),
			'fields' => array('Cmspage.id', 'Cmspage.keyword', 'Cmspage.title','Cmspage.image', 'CmsLanguage.id', 'CmsLanguage.language_alias', 'CmsLanguage.title', 'CmsLanguage.content')
		
		));
		//pr($cms_content);//	exit;
		if(!isset($cms_content['CmsLanguage']['content'])){
			$this->Session->setFlash(__('Invalid content.'), 'default', array(),'error');
			$this->redirect(array('controller' => 'Searches', 'action' => 'online_card'));
		}
		
		$this->set('title_for_layout', __($cms_content['Cmspage']['title']));
		$this->set('content',$cms_content);
		
		
		$cms_image = $this->CmsImage->find('all',array(
					'conditions' =>array('CmsImage.cmspages_id =' =>$cms_content['Cmspage']['id']),
					'order'=>array('id DESC')
				)
		);
		$this->set('cms_image',$cms_image);	

		if(!empty($userId))
		{
			$this->Session->setFlash(__('You are already login.'), 'default', array(), 'error');
			$this->redirect(array('controller'=>'Searches','action'=>'online_card' ));			
		}	
		if ($this->request->is('post')) 
		{
		 
		  $user = $this->User->findByUsername($this->data['User']['username']);
			// Check if user account is disabled or deleted.
			$this->checkUserStatus($user);
			$this->Auth->authenticate = array(
				'Form' => array(
					'fields' => array('username' => 'username', 'password' => 'password')
				),
			);
			if(isset($this->data['remember_me']) && !empty($this->data['remember_me']))
			{
				//echo "Test";exit;
				$year = time() + 31536000;
				$this->Cookie->write('cookie_username', $this->data['User']['username'], $encrypt = false, $expires = $year);
				$this->Cookie->write('cookie_password', $this->data['User']['password'], $encrypt = false, $expires = $year);
				//prd($this->Cookie->read('cookie_password'));
			}
			else
			{
			//DOING EMPTY COOKIES VALUES
				$this->Cookie->write('cookie_username', '', $encrypt = false, $expires = '');
				$this->Cookie->write('cookie_password', '', $encrypt = false, $expires = '');
			}
			if($this->Auth->login())
			{
				if($this->Auth->User('role_id')==1 || $this->Auth->User('role_id')==2)
				{
					//1= Distributor 2= Mediator....
					CakeSession::destroy();
					$this->Session->setFlash(__('Incorrect account number or password. Please try again.'), 'default', array(), 'error');
					$this->redirect($this->Auth->logout());
				}
				
				$this->Session->write('language_on_logout',$language);

				$this->userLoginSetLanguage();
				$this->Session->setFlash(__('Hi %s, Welcome to %s!',$this->Auth->user('fname'),Configure::read('Site.title')), 'default', array(), 'success');
			  $this->redirect(array('controller'=>'Searches','action'=>'online_card'));
			} 
			else 
			{
				$this->Session->setFlash(__('Incorrect account number or password. Please try again.'), 'default', array(), 'error');
				$this->redirect(array('controller'=>'users','action'=>'login'));
			}
		}
	}
	
	public function logout()
	{
		$language_last = $this->Session->read('language_on_logout');
;
		CakeSession::destroy();
		
		if(empty($language_last))
		$language_last = "en";

		$this->Session->write('Config.language', $language_last);
		Configure::write('Config.language', $language_last);	
		
		$this->Session->setFlash(__('Log out successful.'), 'default', array(), 'success');
		$this->redirect(array('controller'=>'users', 'action'=>'login'));
	}
	
	private function checkUserStatus($user='')
	{
		if(isset($user['User']['id'])) 
		{			
			 if ($user['User']['status'] == 0)
			 { //Disabled
				$this->Session->setFlash(__('Your account is disabled.'), 'default', array(), 'error');
				$this->redirect(array('controller'=>'users','action'=>'login'));
			 }			
			 if ($user['User']['status'] == 2) 
			 {
				$this->Session->setFlash(__('Your account activation is pending.'), 'default', array(), 'error');
				$this->redirect(array('controller'=>'users','action'=>'login'));
			 }						
			if ($user['User']['status'] == 3) 
			{
				$this->Session->setFlash(__('Your account has been deleted.'), 'default', array(), 'error');
				$this->redirect(array('controller'=>'users','action'=>'login'));
			}
		}
	}	
    
	public function admin_manage_mediator_redirect($admin_id = 0){
		  $name = ucwords($this->Auth->User('fname')." ".$this->Auth->User('lname'));
		  
		  if($this->Auth->User('role_id') == 2)
		  $this->Session->setFlash(__('You have successfully logged out from mediator :'.$name."'s account"), 'default', array('class' => 'success'));
		  else
		  $this->Session->setFlash(__('You have successfully logged out from retailer :'.$name."'s account"), 'default', array('class' => 'success'));
		  
		  $get_user_data = $this->User->findById($admin_id);
		  $this->Session->write('Auth', $this->User->read(null, $admin_id));
		  $this->Session->write('Auth.User.distributor_login_mediator',0);
		  
		  $this->redirect(array('controller'=>'Users','action'=>'manage_mediator','admin'=>true));
	}


    public function one_another_redirect($admin_id = 0){
		  
		$name = ucwords($this->Auth->User('fname')." ".$this->Auth->User('lname'));
		$this->Session->setFlash(__('You have successfully logged out from retailer :'.$name."'s account"), 'default', array('class' => 'success'));
		$get_user_data = $this->User->findById($admin_id);
		
		$distributor_login_mediator = $this->Session->read('Auth.User.distributor_login_mediator');
		$distributor_login_retailer = $this->Session->read('Auth.User.distributor_login_retailer');
		$mediator_login_retailer = $this->Session->read('Auth.User.mediator_login_retailer');
		
		$this->Session->write('Auth', $this->User->read(null, $admin_id));
		
		$this->Session->write('Auth.User.distributor_login_mediator',0);
		$this->Session->write('Auth.User.distributor_login_retailer',0);
		$this->Session->write('Auth.User.mediator_login_retailer',0);
		
		if($distributor_login_mediator)
		$this->redirect(array('controller'=>'Users','action'=>'manage_mediator','admin'=>true));
	    else if($distributor_login_retailer)
	    $this->redirect(array('controller'=>'Users','action'=>'view_retailer','admin'=>true));
	    else
		$this->redirect(array('controller'=>'Users','action'=>'manage_retailer','admin'=>true));
	    
	}

	public function admin_one_to_another_login($switch_user_id = 0){
		if($switch_user_id)
		{
			$get_user_data = $this->User->findById($switch_user_id);
			$admin_id = $this->Auth->User('id');
			$admin_image = $this->Auth->User('image');
			$login_user_role_id = $this->Auth->User('role_id');
			if($get_user_data )
			{
				$this->Session->write('Auth', $this->User->read(null, $switch_user_id));
				$name = ucwords($this->Auth->User('fname')." ".$this->Auth->User('lname'));
				
				if($this->Auth->User('role_id') != 3)
				$this->Session->setFlash(__('Welcome '.$name), 'default', array('class' => 'success'));
				else
				$this->Session->setFlash(__('Welcome '.$name), 'default', array(), 'success');
				
				if($login_user_role_id == 1 && $this->Auth->User('role_id') == 2)
				{
					$this->Session->write('Auth.User.distributor_login_mediator',1);
					$this->Session->write('Auth.User.distributor_login_retailer',0);
					$this->Session->write('Auth.User.mediator_login_retailer',0);
				}
				else if($login_user_role_id == 1 && $this->Auth->User('role_id') == 3)
				{
					$this->Session->write('Auth.User.distributor_login_mediator',0);
					$this->Session->write('Auth.User.distributor_login_retailer',1);
					$this->Session->write('Auth.User.mediator_login_retailer',0);
				}
				else
				{
					$this->Session->write('Auth.User.distributor_login_mediator',0);
					$this->Session->write('Auth.User.distributor_login_retailer',0);
					$this->Session->write('Auth.User.mediator_login_retailer',1);
				}
				
				$this->Session->write('Auth.User.admin_id',$admin_id);
				$this->Session->write('Auth.User.admin_image',$admin_image);
				$this->redirect(array('controller'=>'Users','action'=>'login','admin'=>true));
			}
			else
			{
				$this->Session->write('Auth.User.distributor_login_mediator',0);
				$this->Session->write('Auth.User.distributor_login_retailer',0);
				$this->Session->write('Auth.User.mediator_login_retailer',0);
				$this->Session->setFlash(__('Invalid User.'), 'default', array('class' => 'error'));
			}
		}
		else
		{
			$this->Session->write('Auth.User.distributor_login_mediator',0);
			$this->Session->write('Auth.User.distributor_login_retailer',0);
			$this->Session->write('Auth.User.mediator_login_retailer',0);
			$this->Session->setFlash(__('Invalid User.'), 'default', array('class' => 'error'));
		}
		
		if($login_user_role_id == 1)
		$this->redirect(array('controller'=>'Users','action'=>'manage_mediator','admin'=>true));
		else
		$this->redirect(array('controller'=>'Users','action'=>'manage_retailer','admin'=>true));
	}
	
	public function admin_login()
	{
		$this->set('title_for_layout','Admin Login');
		$this->pageTitle = __('Login');
		$this->set('pageTitle',$this->pageTitle );
		
		// Distributor Login To Mediator and Revert Back 
		if($this->Auth->User('id'))
		{
				if($this->Auth->User('role_id') == 1 || $this->Auth->User('role_id') == 2)
				{
					$this->redirect(array('controller'=>'users','action'=>'dashboard','admin'=>true));
				}
				else
				{
					$this->redirect(array('controller'=>'Searches','action'=>'online_card'));
				}
		}
		
		if($this->request->data)
		{
				//prd($this->Auth->authenticate);
				if ($this->Auth->login())
				{
					
				  if($this->Auth->User('role_id')!=1 && $this->Auth->User('role_id')!=2)
					{					
						CakeSession::destroy();
						$this->Session->setFlash(__('Invalid Account Number or Password, try again.'), 'default', array('class' => 'error'));
						$this->redirect($this->Auth->logout());					
					}
					
					if($this->Auth->User('status') == 2)
					{
						CakeSession::destroy();
						$this->Session->setFlash(__('Your account is disabled.'), 'default', array('class' => 'error'));
						$this->redirect($this->Auth->logout());	
					}
					
					if($this->Auth->User('status') == 3)
					{
						CakeSession::destroy();
						$this->Session->setFlash(__('Your account is deleted.'), 'default', array('class' => 'error'));
						$this->redirect($this->Auth->logout());	
					}
					$name = ucwords($this->Auth->User('fname')." ".$this->Auth->User('lname'));
					$this->Session->setFlash(__('Welcome '.$name), 'default', array('class' => 'success'));
					
					$this->Session->write('Auth.User.distributor_login_mediator',0);
					$this->Session->write('Auth.User.distributor_login_retailer',0);
					$this->Session->write('Auth.User.mediator_login_retailer',0);
					
					$this->redirect($this->Auth->loginRedirect);					
				}
				else
				{
					$this->Session->setFlash(__('Invalid Account Number or Password, try again.'), 'default', array('class' => 'error'));
					$this->redirect($this->Auth->loginAction);
				}
			}
	}
	
	public function admin_dashboard(){
        $cuser = $this->Session->read("Auth.User");
        if($cuser['role_id'] == 2){
            $this->set('title_for_layout','Mediator Dashboard');		
        }else{
            $this->set('title_for_layout','Admin Dashboard');		
            $this->loadModel('Category');
            $this->loadModel('Card');
            $this->loadModel('Pin');

            $categoryDataActive = $this->Category->find('count',array(
            		'conditions' => array('Category.cat_parent_id' => null, 'Category.cat_status' => 1),
            		'recursive' => -1,
            		'fields' => array('cat_status')
            	)
            );

            $categoryDataInactive = $this->Category->find('count',array(
            		'conditions' => array('Category.cat_parent_id' => null, 'Category.cat_status' => 0),
            		'recursive' => -1,
            		'fields' => array('cat_status')
            	)
            );

            $subcategoryDataActive = $this->Category->find('count',array(
            		'conditions' => array('Category.cat_parent_id !=' => null, 'Category.cat_status' => 1),
            	)
            );

            $subcategoryDataInactive = $this->Category->find('count',array(
            		'conditions' => array('Category.cat_parent_id !=' => null, 'Category.cat_status' => 0),
            	)
            );

            $cardDataActive = $this->Card->find('count',array(
            		'conditions' => array('c_status' => 1),
            	)
            );

            $cardDataInactive = $this->Card->find('count',array(
            		'conditions' => array('c_status' => 0),
            	)
            );

            $mediatorsDataActive = $this->User->find('count',array(
            			'conditions' => array('role_id' => 2, 'status' => 1 ),
            	)
            );

            $mediatorsDataInactive = $this->User->find('count',array(
            			'conditions' => array('role_id' => 2, 'status' => 2 ),
            	)
            );

            $retailersDataActive = $this->User->find('count',array(
            			'conditions' => array('role_id' => 3, 'status' => 1 ),
            	)
            );

            $retailersDataInactive = $this->User->find('count',array(
            			'conditions' => array('role_id' => 3, 'status' => 2 ),
            	)
            );

            $pinsNotUsedData = $this->Pin->find('count',array(
            			'conditions' => array('p_status' => 1 ),
            	)
            );

            $pinsSoldData = $this->Pin->find('count',array(
            			'conditions' => array('p_status' => 2 ),
            	)
            );

            $pinsParkedData = $this->Pin->find('count',array(
            			'conditions' => array('p_status' => 3 ),
            	)
            );

            $pinsRejectedData = $this->Pin->find('count',array(
            			'conditions' => array('p_status' => 4 ),
            	)
            );

            $pinsReturnData = $this->Pin->find('count',array(
            			'conditions' => array('p_status' => 5 ),
            	)
            );

            $this->set('category_active',$categoryDataActive);
            $this->set('category_inactive',$categoryDataInactive);
            $this->set('subcategory_active',$subcategoryDataActive);
            $this->set('subcategory_inactive',$subcategoryDataInactive);
            $this->set('card_active',$cardDataActive);
            $this->set('card_inactive',$cardDataInactive);
            $this->set('mediators_active',$mediatorsDataActive);
            $this->set('mediators_inactive',$mediatorsDataInactive);
            $this->set('retailers_active',$retailersDataActive);
            $this->set('retailers_inactive',$retailersDataInactive);
            $this->set('pins_not_used',$pinsNotUsedData);
            $this->set('pins_sold',$pinsSoldData);
            $this->set('pins_park',$pinsParkedData);
            $this->set('pins_reject',$pinsRejectedData);
            $this->set('pins_return',$pinsReturnData);
        }
    }
	
	public function admin_logout() {
		CakeSession::destroy();
		$this->redirect($this->Auth->logout());
	}
	
	public function admin_profile_edit(){
		$this->pageTitle = __('Manage Profile');
		$this->set('pageTitle',$this->pageTitle );
		$admin_id = $this->Auth->User('id');
		$admin_data = $this->User->findById($admin_id);
	    
		$this->set('role_id',$this->Auth->User('role_id'));
		$this->loadModel('SecurityQuestion');
		$questions = $this->SecurityQuestion->find('list',array('conditions'=>array('status'=>1),'fields'=>'id,question_english'));
		$this->set('questions',$questions);
		$this->loadModel('Country');
		$all_country = $this->Country->find('all',array('order'=>'country_name asc'));
		$set_countries = array();
		foreach($all_country as $country)
		{
			$code = $country['Country']['country_code'];
			$name = $country['Country']['country_name'];
			$set_countries[$code] = ucwords($name); 
		}
		$this->set('set_countries',$set_countries);
		
		if($this->request->data)
		{
    	    $get_user_data = $this->User->findById($this->request->data['User']['id']);
			if($this->request->data['User']['image'])
			{
			 if(file_exists(WWW_ROOT.'img/users/'.$this->request->data['User']['image']))
			 {
				$resolution = getimagesize (WWW_ROOT.'img/users/'.$this->request->data['User']['image']);
				$width = $resolution[0];
				$height = $resolution[1];
				if($width < 200 || $height < 200 || $width >1024 || $height >768)
				{
				 //$this->Session->setFlash(__('Your image resolution is '.$width." * ".$height." Minimum required resolution is 200 * 200."), 'default', array('class' => 'error'));
				 $this->Session->setFlash(__("Please upload image with resolution between ".'200 * 200 - 1024 * 768'."."), 'default', array('class' => 'error'));
				 if($get_user_data ['User']['image'] != $this->request->data['User']['image'] )
				 {
					 unlink(WWW_ROOT.'img/users/'.$this->request->data['User']['image']);
				     $this->request->data['User']['image'] = $get_user_data ['User']['image'];	
					 $this->request->data['User']['files'] = array(); 
				 }
				
				 
				 return;
				}
			   }
			   else
			   {
				   $this->request->data['User']['image'] = NULL;
			   }
			}
			
			if(empty($this->request->data['User']['email']))
			{
			    $this->User->validator()->remove('email', 'isUnique');
			}
			
            foreach ($this->request->data['User'] as $key => $value){
             $this->request->data['User'][$key] = trim($value);
            }
            unset($this->request->data['User']['username']);
            $this->User->validator()->remove('username');
            $save_data =$this->User->save($this->request->data) ;
			if ($save_data)
			{
				$this->Session->write('Auth.User.fname',$save_data['User']['fname']);
				$this->Session->write('Auth.User.lname',$save_data['User']['lname']);
				$this->Session->write('Auth.User.email',$save_data['User']['email']);
				$this->Session->write('Auth.User.phone',$save_data['User']['phone']);
				$this->Session->write('Auth.User.image',$save_data['User']['image']);
				$this->Session->write('Auth.User.address',$save_data['User']['address']);
				$this->Session->write('Auth.User.country_code',$save_data['User']['country_code']);
				
				if($save_data['User']['image'] != $get_user_data['User']['image'] && !empty($get_user_data['User']['image']))
				{
					 if(file_exists(WWW_ROOT.'img/users/'.$get_user_data['User']['image']))
					 unlink(WWW_ROOT.'img/users/'.$get_user_data['User']['image']);
				}
				
				$this->Session->setFlash(__('Profile has been updated successfully.'), 'default', array('class' => 'success'));
				$this->redirect(array('controller'=>'Users','action' => 'profile_edit'));
			} 
			else 
			{
				$this->Session->setFlash(__('Profile could not be edited. Please, try again.'), 'default', array('class' => 'error'));
			}
		}
		else
		{
			$this->request->data = $admin_data;
		}
	
	}
	
	public function admin_changepassword(){
		$this->pageTitle = __('Change Password');
		$this->set('pageTitle',$this->pageTitle );
      	if (!empty($this->request->data)) {

      		$pass = $this->request->data['User']['password'];
      		$confirm =  $this->request->data['User']['confirm_password'];

      		$this->request->data['User']['password'] = $this->request->data['User']['password'];
      		$this->request->data['User']['confirm_password'] = $this->request->data['User']['confirm_password'];

			$this->request->data['User']['original_password']=$this->request->data['User']['password'];
			$this->request->data['User']['id'] = $this->Auth->User('id');
			
			$this->User->validator()->remove('username');

			if ($this->User->save($this->request->data)) 
			{
				$this->Session->setFlash(__('Password has been changed successfully.'), 'default', array('class' => 'success'));
				$this->redirect(array('controller'=>'users','action' => 'changepassword'));
			}
			else
			{
				 $this->request->data['User']['password'] = $pass;
      		     $this->request->data['User']['confirm_password'] = $confirm;
				 $this->Session->setFlash(__('Password could not be changed. Please, try again.'), 'default', array('class' => 'error'));				
			}
		}
	}
	
	public function admin_changestatus_new(){
		if($this->request->is('ajax')){	
			
			$id = $this->request->data['id'];
			$status = $this->request->data['st'];
			
			unset($this->request->data);
			//$this->User->set(array('User' => array('id'=>$id, 'status'=>$status)));
			$get_user_data = $this->User->findById($id);

			$res =$this->User->updateAll(array('User.status'=>$status),array('User.id'=>$id));
			if($res)
			{
				if($get_user_data['User']['role_id'] == 2)
				{
					$res = $this->User->updateAll(array('User.status'=>$status),array('User.added_by'=>$id));
				}
				echo '1'; 
				exit;
			}
			else
			{
				echo '0'; 
				exit;
			}
		}
	}
	
	public function admin_manage_mediator(){
	  $this->set('title_for_layout',__('Mediator Personal Data'));
	  if($this->Auth->User('role_id') != 1)
		{
					$this->Session->setFlash(__('You are not authorized to access this page.'), 'default', array('class' => 'error'));				
					$this->redirect(array('controller'=>'users','action' => 'dashboard'));
		}
	}
    
    public function admin_inline_mediator() {
       
        if($this->Auth->User('id'))
        {
			$req = $this->request;
        	if($req->is('Ajax'))
        	{
	            $saveData =array();
	            $reqData = $req->data;
	            $saveData['User']['id'] = $reqData['id'];
	            $saveData['User']['fname'] = $reqData['fname'];
	            $saveData['User']['lname'] = $reqData['lname'];
	            $saveData['User']['email'] = $reqData['email'];
	            //$saveData['User']['username'] = $reqData['username'];
	            $this->User->validator()->remove('username');
	            $this->User->set($saveData);
	           
	            if($this->User->validates())
	            {
	                $this->User->save();
	                echo "1";
	            }
	            else
	            {
	                echo json_encode($this->User->validationErrors);
	            }
	        }
	        else
	        {
	            echo "Invalid request";
	        }
	        
	        exit;
        }
        else
        {
        	
        	$this->Session->setFlash(__('Please login to edit the details'), 'default', array('class' => 'success'));
			$this->redirect(array('controller'=>'Users', 'action'=>'login','admin'=>true));
        }
        
	}
	
	public function admin_generategrid()
	{
		$page  = $this->request->query['page'];
		$limit = $this->request->query['rows'];
		$sidx  = $this->request->query['sidx'];
		$sord  = $this->request->query['sord'];
		
		if(!$sidx) $sidx =1;
		$order_by = $sidx.' '.$sord;
		
	
		$conditions = array();
		$conditions['User.role_id'] = '2';
		$conditions['User.status'] = array('1','2');
		$conditions['User.added_by'] = $this->Auth->User('id');

		if(isset($this->request->query['filters']))
		{
			$filters = json_decode($this->request->query['filters'], true);
			foreach($filters['rules'] as $each_filter)
			{
			
				if($each_filter['field'] == 'id'){
					$conditions['User.id LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
			
				if($each_filter['field'] == 'fname'){
					$conditions['User.fname LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}
				
				
				if($each_filter['field'] == 'mname'){
					$conditions['User.mname LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}
				
				if($each_filter['field'] == 'lname'){
					$conditions['User.lname LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}

				if($each_filter['field'] == 'email'){
					$conditions['User.email LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
				if($each_filter['field'] == 'username'){
					$conditions['User.username LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'status'){
					if($each_filter['data'] != 0)
					$conditions['User.status'] = Sanitize::clean($each_filter['data']);
				}
			}
		}
		
		$count = $this->User->find('count',array(
				'recursive' => -1, 
			  	'conditions' => $conditions,
			)
		);
		
		if($count >0)
		{ 
			$total_pages = ceil($count/$limit); 
		}
		else
		{
			$total_pages = 0; 
		}
		
		if ($page > $total_pages) 
		{
			$page=$total_pages;
		}
		
		$start = $limit*$page - $limit; 
		$user = $this->User->find('all', array(
			'recursive' => -1, //int
			'conditions' => $conditions,
			'order' => $order_by,
			'limit' => $limit,
			'offset' => $start
		));
    
		$responce = new stdClass();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count; 
		
		$i=0;

		if(is_array($user))
		{
			foreach($user as $users)
			{
                // App::import('Helper', 'Image'); 
				//$image = new ImageHelper(new View());
				
				$first_name =ucwords(strtolower( $users['User']['fname']));
				$last_name  = ucwords(strtolower($users['User']['lname']));
				$imagename = $users['User']['image'];
				$email = $users['User']['email'];
				$username = $users['User']['username'];
				if($imagename!='' && file_exists('img/users/'.$imagename))
				{
					$image_cat = '<img style="cursor:pointer;margin-top:3px;" src="'.$this->webroot.'img/users/'.$imagename.'" border="0" width="50" height="50"/>';
					//$image_cat ='<img style="cursor:pointer;margin-top:3px;" src="'.IMAGE_PATH.'image.php?image=img/users/'.$imagename.'&amp;width=50&amp;height=50"/>';
				}
				else 
				{ 
					$image_cat = '<img style="cursor:pointer;margin-top:3px;" src="'.$this->webroot.'img/users/noimage.jpg" border="0" width="35" height="35"/>';
					//$image_cat ='<img style="cursor:pointer;margin-top:3px;" src="'.IMAGE_PATH.'image.php?image=img/users/noimage.jpg&amp;width=35&amp;height=35"/>';
				}
				
				//Edit User Link
				$edit = Router::url(array('controller'=>'Users','action'=>'edit','admin'=>true,$users['User']['id']));
				if($users['User']['status'] == 3)
				{
					//$link_edit = '<a  class="grid_link"  style="opacity:0.5;">'.$this->frontContImage('edit_admin.png','Edit').'</a>';
					$link_edit = '&nbsp;&nbsp;<a  class="grid_link" id="action_view_'.$users['User']['id'].'" style="opacity:0.5;color:#438CE5;">View</a>';
				}
				else
				{
					//$link_edit = '<a title="Edit Record" class="grid_link" href="'.$edit.'">'.$this->frontContImage('edit_admin.png','Edit').'</a>';
					$link_edit = '&nbsp;&nbsp;<a title="Edit Record" id="action_view_'.$users['User']['id'].'" class="grid_link" href="'.$edit.'" style="color:#438CE5;">View</a>';
				}
			
				// View Mediator Link
				$view = Router::url(array('controller'=>'Users','action'=>'view','admin'=>true,$users['User']['id']));
				//$link_view = '<a title="View Record" class="grid_link" href="'.$view.'">'.$this->frontContImage('view_admin.png','View').'</a>';
                $link_view = '&nbsp;&nbsp;<a title="View Record" id="action_viewdetail_'.$users['User']['id'].'" class="grid_link" href="'.$view.'" style="color:#438CE5;">View Detail </a>';

				
				// View Reailer List
				$view_retailer = Router::url(array('controller'=>'Users','action'=>'view_retailer','admin'=>true,$users['User']['id']));
				//$link_view_retailer = '<a title="Retailer List" class="grid_link" href="'.$view_retailer.'">'.$this->frontContImage('view_retailer.png','View').'</a>';
				$link_view_retailer = '<a title="Retailer List" class="grid_link" href="'.$view_retailer.'" style="color:#438CE5;">Retailers |</a>';

				// Delete User Link
                 if($users['User']['status'] == 3)
				{
					$link_delete = '<a style="opacity:0.5;" class="grid_link">'.$this->frontContImage('delete_admin.png','Deleted').'</a>';
				}
				else
				{
					$link_delete = '<a title="Delete Record" onclick="deleteuser('.$users['User']['id'].')" class="grid_link">'.$this->frontContImage('delete_admin.png','Delete').'</a>';
				}
				
				if($users['User']['status'] != 3)
				{
					$switch_account = '<a title="Switch Account" onclick="switch_account('.$users['User']['id'].')" style="color:#438CE5;" class="grid_link" id="action_switch_'.$users['User']['id'].'">Login</a>';
				}
				else
				{
					$switch_account = '<a title="Disabled"   class="grid_link" style="opacity:0.5;color:#438CE5;">Login</a>';
				}
				
				//$action = $link_view_retailer." ".$link_view." ".$link_edit." ".$link_delete;
				//$action = $link_view_retailer." ".$link_view." ".$link_edit;
                    $link_manage = "";
                    $link_manage .= '<span class="grid_link" title="edit" style="display:inline-block;" id="action_edit_'.$users['User']['id'].'" onclick="inplaceEdit('.$users['User']['id'].')"> Edit </span>';
                    $link_manage .= '&nbsp;<span class="grid_link" title="save" style="display:none;" id="action_save_'.$users['User']['id'].'" onclick="inplaceSave('.$users['User']['id'].')"> Save </span>';
                    $link_manage .= '&nbsp;&nbsp;<span class="grid_link" title="cancel" style="display:none;" id="action_cancel_'.$users['User']['id'].'" onclick="inplaceCancel('.$users['User']['id'].')"> Cancel </span>'; 
					
				$action = $link_manage.$link_view."&nbsp;&nbsp;".$link_edit."&nbsp;&nbsp;&nbsp;".$switch_account;
				
				$status_link = '<div style="float:left; padding:4px;width:100%;"><select disabled id="action_status_'.$users['User']['id'].'" class="form-control" title="Change Status" onchange="changeStatus('.$users['User']['id'].',this)" >';
				if($users['User']['status'] == 1)
				{
					$status = "Active";
					$status_link .= '<option value="2">Disabled</option>
											<option selected="selected" value="1">Enabled</option>
										</select></div>';
					/*$status_link = '<img title="Enabled" alt="Enabled" src="'.$this->webroot.'img/greenStatus.png" border="0" onclick="changeStatus('.$users['User']['id'].',2)" style="cursor:pointer;"/>';*/
				}
				else if($users['User']['status'] == 2)
				{
					$status = "Pending";
				  /*$status_link = '<img title="Disabled" alt="Disabled" src="'.$this->webroot.'img/redstatus.png" border="0" onclick="changeStatus('.$users['User']['id'].',1)" style="cursor:pointer;"/>';*/
				  $status_link .= '<option selected="selected" value="2">Disabled</option>
											<option value="1">Enabled</option>
										</select></div>';
				}
				else if($users['User']['status'] == 3)
				{
					$status = "Deleted";
					$status_link .= '<option selected="selected" value="2">Disabled</option>
											<option value="1">Enabled</option>
										</select></div>';
				}
				else
				{
					$status = "Not Defined";
					$status_link .= '<option selected="selected" value="2">Disabled</option>
											<option value="1">Enabled</option>
										</select></div>';
				}
				
				$responce->rows[$i]['id']=$users['User']['id'];
				$responce->rows[$i]['cell']=array($users['User']['id'],$first_name,$last_name,$email,$username,$status_link,$action); 
				$i++; 
			} 
		}
		echo json_encode($responce); exit;
	}
	
	public function admin_manage_retailer(){
	  $this->set('title_for_layout',__('Manage Retailer'));
	  if($this->Auth->User('role_id') == 1)
		{
					$this->Session->setFlash(__('You are not authorized to access this page.'), 'default', array('class' => 'error'));				
					$this->redirect(array('controller'=>'users','action' => 'dashboard'));
		}
	}
	
	public function admin_generategrid_retailer()
	{
		$page  = $this->request->query['page'];
		$limit = $this->request->query['rows'];
		$sidx  = $this->request->query['sidx'];
		$sord  = $this->request->query['sord'];
		
		if(!$sidx) $sidx =1;
		$order_by = $sidx.' '.$sord;
		
	
		$conditions = array();
		$conditions['User.role_id'] = '3';
		$conditions['User.status'] = array('1','2');
		$conditions['User.added_by'] = $this->Auth->User('id');
		

		if(isset($this->request->query['filters']))
		{
			$filters = json_decode($this->request->query['filters'], true);
			foreach($filters['rules'] as $each_filter)
			{
			
				if($each_filter['field'] == 'id'){
					$conditions['User.id LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
			
				if($each_filter['field'] == 'fname'){
					$conditions['User.fname LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}

				if($each_filter['field'] == 'username'){
					$conditions['User.username LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}
				
				if($each_filter['field'] == 'mname'){
					$conditions['User.mname LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}
				
				if($each_filter['field'] == 'lname'){
					$conditions['User.lname LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}

				if($each_filter['field'] == 'email'){
					$conditions['User.email LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'status'){
					if($each_filter['data'] != 0)
					$conditions['User.status'] = Sanitize::clean($each_filter['data']);
				}
			}
		}
		
		$count = $this->User->find('count',array(
				'recursive' => -1, 
			  	'conditions' => $conditions,
			)
		);
		
		if($count >0)
		{ 
			$total_pages = ceil($count/$limit); 
		}
		else
		{
			$total_pages = 0; 
		}
		
		if ($page > $total_pages) 
		{
			$page=$total_pages;
		}
		
		$start = $limit*$page - $limit; 
		$user = $this->User->find('all', array(
			'recursive' => -1, //int
			'conditions' => $conditions,
			'order' => $order_by,
			'limit' => $limit,
			'offset' => $start
		));
    
		$responce = new stdClass();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count; 
		
		$i=0;
	
		if(is_array($user))
		{
			foreach($user as $users)
			{
               // App::import('Helper', 'Image'); 
				//$image = new ImageHelper(new View());
				
				$first_name =ucwords(strtolower( $users['User']['fname']));
				$last_name  = ucwords(strtolower($users['User']['lname']));
				$imagename = $users['User']['image'];
				$email = $users['User']['email'];
				if($imagename!='' && file_exists('img/users/'.$imagename))
				{
					$image_cat = '<img style="cursor:pointer;margin-top:3px;" src="'.$this->webroot.'img/users/'.$imagename.'" border="0" width="50" height="50"/>';
					//$image_cat ='<img style="cursor:pointer;margin-top:3px;" src="'.IMAGE_PATH.'image.php?image=img/users/'.$imagename.'&amp;width=50&amp;height=50"/>';
				}
				else 
				{ 
					$image_cat = '<img style="cursor:pointer;margin-top:3px;" src="'.$this->webroot.'img/users/noimage.jpg" border="0" width="35" height="35"/>';
					//$image_cat ='<img style="cursor:pointer;margin-top:3px;" src="'.IMAGE_PATH.'image.php?image=img/users/noimage.jpg&amp;width=35&amp;height=35"/>';
				}
				
				//Edit User Link
				$edit = Router::url(array('controller'=>'Users','action'=>'edit','admin'=>true,$users['User']['id']));
				if($users['User']['status'] == 3)
				{
					//$link_edit = '<a class="grid_link"  style="opacity:0.5;">'.$this->frontContImage('edit_admin.png','Edit').'</a>';
					$link_edit = '<a class="grid_link" id="action_view_'.$users['User']['id'].'" title="view"  style="opacity:0.5;color:#438CE5;">View</a>';
				}
				else
				{
					//$link_edit = '<a class="grid_link" href="'.$edit.'">'.$this->frontContImage('edit_admin.png','Edit').'</a>';
					$link_edit = '<a class="grid_link" id="action_view_'.$users['User']['id'].'" title="view" href="'.$edit.'" style="color:#438CE5;">View</a>';
				}
				// View User Link
				$view = Router::url(array('controller'=>'Users','action'=>'view','admin'=>true,$users['User']['id']));
				//$link_view = '<a class="grid_link" href="'.$view.'">'.$this->frontContImage('view_admin.png','View').'</a>';
				$link_view = '<a class="grid_link" id="action_viewdetail_'.$users['User']['id'].'" title="view detail" href="'.$view.'" style="color:#438CE5;">View Detail</a>';
        
				// Delete User Link
                if($users['User']['status'] == 3)
				{
					$link_delete = '<a style="opacity:0.5;" class="grid_link">'.$this->frontContImage('delete_admin.png','Deleted').'</a>';
				}
				else
				{
					$link_delete = '<a onclick="deleteuser('.$users['User']['id'].')" class="grid_link">'.$this->frontContImage('delete_admin.png','Delete').'</a>';
				}
				
				//$action = $link_view." ".$link_edit." ".$link_delete;
                
                    $link_manage = "";
                    $link_manage .= '<span class="grid_link" title="edit" style="display:inline-block;" id="action_edit_'.$users['User']['id'].'" onclick="inplaceEdit('.$users['User']['id'].')"> Edit </span>';
                    $link_manage .= '&nbsp;<span class="grid_link" title="save" style="display:none;" id="action_save_'.$users['User']['id'].'" onclick="inplaceSave('.$users['User']['id'].')"> Save </span>';
                    $link_manage .= '&nbsp;&nbsp;<span class="grid_link" title="cancel" style="display:none;" id="action_cancel_'.$users['User']['id'].'" onclick="inplaceCancel('.$users['User']['id'].')"> Cancel </span>'; 
                
      				
				if($users['User']['status'] != 3)
				{
					$switch_account = '<a title="Switch Account" onclick="switch_account('.$users['User']['id'].')" style="color:#438CE5;" class="grid_link" id="action_switch_'.$users['User']['id'].'">Login</a>';
				}
				else
				{
					$switch_account = '<a title="Disabled"   class="grid_link" style="opacity:0.5;color:#438CE5;">Login</a>';
				}
				
				if($this->Session->read('Auth.User.distributor_login_mediator') == 0)
       			{
					$action = $link_manage."&nbsp;&nbsp;&nbsp;".$link_view."&nbsp;&nbsp;&nbsp;".$link_edit."&nbsp;&nbsp;&nbsp;".$switch_account;				
				}
				else
				{
					$action = $link_manage."&nbsp;&nbsp;&nbsp;".$link_view."&nbsp;&nbsp;&nbsp;".$link_edit;	
				}
				$status_link = '<div style="float:left; padding:4px;width:100%;"><select disabled id="action_status_'.$users['User']['id'].'" class="form-control" title="Change Status" onchange="changeStatus('.$users['User']['id'].',this)" >';

				if($users['User']['status'] == 1)
				{
					$status = "Active";
					/*$status_link = '<img title="Enabled" alt="Enabled" src="'.$this->webroot.'img/greenStatus.png" border="0" onclick="changeStatus('.$users['User']['id'].',2)" style="cursor:pointer;"/>';*/
					$status_link .= '<option value="2">Disabled</option>
									<option selected="selected" value="1">Enabled</option>
								</select></div>';
				}
				else if($users['User']['status'] == 2)
				{
					$status = "Pending";
				   /*$status_link = '<img title="Disabled" alt="Disabled" src="'.$this->webroot.'img/redstatus.png" border="0" onclick="changeStatus('.$users['User']['id'].',1)" style="cursor:pointer;"/>';*/
					$status_link .= '<option selected="selected" value="2">Disabled</option>
										<option value="1">Enabled</option>
									</select></div>';
				}
				else if($users['User']['status'] == 3)
				{
					$status = "Deleted";
					$status_link .= '<option selected="selected" value="2">Disabled</option>
								<option selected="selected" value="1">Enabled</option>
								</select></div>';
				}
				else
				{
					$status = "Not Defined";
					$status_link .= '<option selected="selected" value="2">Disabled</option>
									<option selected="selected" value="1">Enabled</option>
								</select></div>';
				}
				$responce->rows[$i]['id']=$users['User']['id'];
				$responce->rows[$i]['cell']=array($users['User']['id'],$first_name,$last_name,$email,$users['User']['username'],$status_link,$action); 
				$i++; 
			} 
		}
		echo json_encode($responce); exit;
	}
	
	public function admin_view_retailer($mediator_id = 0){
	  $this->set('title_for_layout',__('View Retailers'));
	  if($this->Auth->User('role_id') != 1)
		{
					$this->Session->setFlash(__('You are not authorized to access this page.'), 'default', array('class' => 'error'));				
					$this->redirect(array('controller'=>'users','action' => 'dashboard'));
		}
		$this->set('mediator_id',$mediator_id);
		
		$mediator_list = $this->User->find('all',array('conditions'=>array('User.status'=>1,'User.role_id'=>2),'order'=>'User.fname ,User.lname asc','fields'=>array('User.id','User.fname','User.lname'),'order'=>'fname asc'));
		$array_mediator = array();
		
		$mediator_search_list = "0:"."'All',";
		foreach($mediator_list as $list_m)
		{
			$mid = $list_m['User']['id'];
			$array_mediator[$mid] = ucwords($list_m['User']['fname']." ".$list_m['User']['lname']);
		    $mediator_search_list = $mediator_search_list.$mid.":'".ucwords($list_m['User']['fname']." ".$list_m['User']['lname'])."',";
		}
		
		$mediator_search_list = rtrim($mediator_search_list,',');
		$this->set('mediator_search_list',$mediator_search_list);
		$mediator_name = '';
		//0:'All', 1: 'Enable',2: 'Disable'
		if(!empty($mediator_id))
		{
			$get_mediator_data = $this->User->findById($mediator_id);
			$mediator_name = ucwords($get_mediator_data['User']['fname']." ".$get_mediator_data['User']['lname']);
		}
	    $this->set('mediator_list',$array_mediator);
		$this->set('mediator_name',$mediator_name);	
	}
	
	public function admin_generategrid_retailer_list($mediator_id = 0)
	{
		$page  = $this->request->query['page'];
		$limit = $this->request->query['rows'];
		$sidx  = $this->request->query['sidx'];
		$sord  = $this->request->query['sord'];
		
		if(!$sidx) $sidx =1;
		$order_by = $sidx.' '.$sord;
		
	
		$conditions = array();
		$conditions['User.role_id'] = '3';
		$conditions['User.status'] = array('1','2');
		if($mediator_id)
		$conditions['User.added_by'] = $mediator_id;
		

		if(isset($this->request->query['filters']))
		{
			$filters = json_decode($this->request->query['filters'], true);
			foreach($filters['rules'] as $each_filter)
			{
			
				if($each_filter['field'] == 'id'){
					$conditions['User.id LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
			
				if($each_filter['field'] == 'fname'){
					$conditions['User.fname LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}
				
				if($each_filter['field'] == 'mname'){
					$conditions['User.mname LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}
				
				if($each_filter['field'] == 'lname'){
					$conditions['User.lname LIKE'] = '%'.Sanitize::clean($each_filter['data']). '%';
				}

				if($each_filter['field'] == 'email'){
					$conditions['User.email LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
				if($each_filter['field'] == 'username'){
					$conditions['User.username LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
				
				if($each_filter['field'] == 'status'){
					if($each_filter['data'] != 0)
					$conditions['User.status'] = Sanitize::clean($each_filter['data']);
				}
				
				if($each_filter['field'] == 'added_by'){
					if($each_filter['data'] != 0)
					$conditions['User.added_by'] = Sanitize::clean($each_filter['data']);
				}
				
			}
		}
		
		$count = $this->User->find('count',array(
				'recursive' => -1, 
			  	'conditions' => $conditions,
			)
		);
		
		if($count >0)
		{ 
			$total_pages = ceil($count/$limit); 
		}
		else
		{
			$total_pages = 0; 
		}
		
		if ($page > $total_pages) 
		{
			$page=$total_pages;
		}
		
		$start = $limit*$page - $limit; 
		$user = $this->User->find('all', array(
			'recursive' => -1, //int
			'conditions' => $conditions,
			'order' => $order_by,
			'limit' => $limit,
			'offset' => $start
		));
       
		$responce = new stdClass();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count; 
		
		$i=0;
		
		if(is_array($user))
		{
			foreach($user as $users)
	     		{
                   // App::import('Helper', 'Image'); 
		     		//$image = new ImageHelper(new View());
				
				$first_name =ucwords(strtolower( $users['User']['fname']));
				$last_name  = ucwords(strtolower($users['User']['lname']));
				$imagename = $users['User']['image'];
				$email = $users['User']['email'];
				$username  = $users['User']['username'];
				if($imagename!='' && file_exists('img/users/'.$imagename))
				{
					$image_cat = '<img style="cursor:pointer;margin-top:3px;border-radius:7px;" src="'.$this->webroot.'img/users/'.$imagename.'" border="0" width="50" height="50"/>';
					//$image_cat ='<img style="cursor:pointer;margin-top:3px;" src="'.IMAGE_PATH.'image.php?image=img/users/'.$imagename.'&amp;width=50&amp;height=50"/>';
				}
				else 
				{ 
					$image_cat = '<img style="cursor:pointer;margin-top:3px;border-radius:7px;" src="'.$this->webroot.'img/users/noimage.jpg" border="0" width="35" height="35"/>';
					//$image_cat ='<img style="cursor:pointer;margin-top:3px;" src="'.IMAGE_PATH.'image.php?image=img/users/noimage.jpg&amp;width=35&amp;height=35"/>';
				}
				
				$created = date('d.m.Y',strtotime($users['User']['created']));
                
				$status_link = '<div style="float:left; padding:4px;width:100%;"><select disabled id="action_status_'.$users['User']['id'].'" class="form-control" title="Change Status" onchange="changeStatus('.$users['User']['id'].',this)" >';
				
				if($users['User']['status'] == 1)
				{
					$status = "Active";
					$status_link .= '<option value="2">Disabled</option>
											<option selected="selected" value="1">Enabled</option>
										</select></div>';

				}
				else if($users['User']['status'] == 2)
				{
					$status = "Pending";
					$status_link .= '<option selected="selected" value="2">Disabled</option>
											<option  value="1">Enabled</option>
										</select></div>';

				}
				else if($users['User']['status'] == 3)
				{
					$status = "Deleted";
					$status_link .= '<option selected="selected" value="2">Disabled</option>
											<option  value="1">Enabled</option>
										</select></div>';
				}
				else
				{
					$status = "Not Defined";
					$status_link .= '<option selected="selected"  value="2">Disabled</option>
											<option value="1">Enabled</option>
										</select></div>';

				}
                
                //Edit User Link
				$edit = Router::url(array('controller'=>'Users','action'=>'edit','admin'=>true,$users['User']['id']));
				if($users['User']['status'] == 3)
				{
					//$link_edit = '<a  class="grid_link"  style="opacity:0.5;">'.$this->frontContImage('edit_admin.png','Edit').'</a>';
					$link_edit = '<a  class="grid_link" id="action_view_'.$users['User']['id'].'" style="opacity:0.5;color:#438CE5;">View</a>';
				}
				else
				{
					//$link_edit = '<a title="Edit Record" class="grid_link" href="'.$edit.'">'.$this->frontContImage('edit_admin.png','Edit').'</a>';
					$link_edit = '<a title="Edit Record" id="action_view_'.$users['User']['id'].'" class="grid_link" href="'.$edit.'" style="color:#438CE5;">View</a>';
				}

                    $link_manage = "";
                    $link_manage .= '&nbsp;&nbsp;<span class="grid_link" title="edit" style="display:inline-block;" id="action_edit_'.$users['User']['id'].'" onclick="inplaceEdit('.$users['User']['id'].')"> Edit </span>';
                    $link_manage .= '&nbsp;&nbsp;<span class="grid_link" title="save" style="display:none;" id="action_save_'.$users['User']['id'].'" onclick="inplaceSave('.$users['User']['id'].')"> Save </span>';
                    $link_manage .= '&nbsp;&nbsp;<span class="grid_link" title="cancel" style="display:none;" id="action_cancel_'.$users['User']['id'].'" onclick="inplaceCancel('.$users['User']['id'].')"> Cancel </span>'; 
				    $link_manage .= $link_edit."&nbsp;&nbsp;&nbsp;";

				if($users['User']['status'] != 3)
				{
					$switch_account = '<a title="Switch Account" onclick="switch_account('.$users['User']['id'].')" style="color:#438CE5;" class="grid_link" id="action_switch_'.$users['User']['id'].'">Login</a>';
				}
				else
				{
					$switch_account = '<a title="Disabled"   class="grid_link" style="opacity:0.5;color:#438CE5;">Login</a>';
				}
				
				$link_manage .= $switch_account;
				$mediator_name = 'NA';
				if(!empty($users['User']['added_by']))
				{
					$get_mediator_data = $this->User->findById($users['User']['added_by']);
					$mediator_name = ucwords($get_mediator_data['User']['fname']." ".$get_mediator_data['User']['lname']);
				}

				$responce->rows[$i]['id']=$users['User']['id'];
				$responce->rows[$i]['cell']=array($users['User']['id'],$first_name,$last_name,$mediator_name,$email,$username,$image_cat,$status_link,$created,$link_manage); 
				$i++; 
			} 
		}
		echo json_encode($responce); exit;
	}
	
	public function admin_add(){
		if($this->Auth->User('role_id') == 1)
		{
			$this->set('title_for_layout',"Add Mediator");
		}
		else
		{
			$this->set('title_for_layout',"Add Retailer");
		}
		$this->set('role_id',$this->Auth->User('role_id'));
		$this->loadModel('SecurityQuestion');
		$questions = $this->SecurityQuestion->find('list',array('conditions'=>array('status'=>1),'fields'=>'id,question_english'));
       	$user_name = $this->check_account();
       	$this->set('user_name',$user_name);
       	$this->set('questions',$questions);
		$this->loadModel('Country');
		$all_country = $this->Country->find('all',array('order'=>'country_name asc'));
		$set_countries = array();
		foreach($all_country as $country)
		{
			$code = $country['Country']['country_code'];
			$name = $country['Country']['country_name'];
			$set_countries[$code] = ucwords($name); 
		}

		$this->set('set_countries',$set_countries);
		
		if(!empty($this->request->data)) 
		{
			$postdata=$this->request->data;
			$this->User->create();
			$data = array();
			
			if($postdata['User']['image'])
			{
			 if(file_exists(WWW_ROOT.'img/users/'.$postdata['User']['image']))
			 {
				
				$resolution = getimagesize (WWW_ROOT.'img/users/'.$postdata['User']['image']);
				$width = $resolution[0];
				$height = $resolution[1];
				 if($width < 200 || $height < 200 || $width > 1024 || $height > 768)
				 {
				 	 $this->Session->setFlash(__("Please upload image with resolution between ".'200 * 200 - 1024 * 768'."."), 'default', array('class' => 'error'));
				 	 if(file_exists(WWW_ROOT.'img/users/'.$postdata['User']['image']))
				 	 unlink(WWW_ROOT.'img/users/'.$postdata['User']['image']);
				 	 $this->request->data['User']['files'] = array(); 
					 $this->request->data['User']['image'] = NULL;
   			   		 return;
				 }
				 
				}
			   }
			   else
			   {
				   $this->request->data['User']['image'] = NULL;
			   }
			
			   
			$data['User']['activation_key'] = md5($this->request->data['User']['email'].time());
			$data['User']['fname'] =  trim($this->request->data['User']['fname']);
			$data['User']['lname'] = trim($this->request->data['User']['lname']);
			$data['User']['email'] = trim($this->request->data['User']['email']);
			$data['User']['username'] = $user_name;
			$data['User']['original_password'] = $this->request->data['User']['password'];
			$data['User']['password'] = $this->request->data['User']['password'];
			$data['User']['country_code'] = $this->request->data['User']['country_code'];
			$data['User']['confirm_password'] = $this->request->data['User']['confirm_password'];
			$data['User']['phone'] = $this->request->data['User']['phone'];
			$data['User']['front_admin_user_identify_status'] = '1';
			$data['User']['image'] =$this->request->data['User']['image'];
			$data['User']['address'] = trim($this->request->data['User']['address']);
			$data['User']['security_question_id'] = trim($this->request->data['User']['security_question_id']);
			$data['User']['security_answer'] = trim($this->request->data['User']['security_answer']);
			
			if(isset($this->request->data['User']['purchase_limit']))
			{
				$data['User']['purchase_limit'] =$this->request->data['User']['purchase_limit'] ;
			}
			
			if( isset($this->request->data['User']['minimum_balance']))
			{
				$data['User']['minimum_balance'] = $this->request->data['User']['minimum_balance'];
			}
			else
			{
				$data['User']['minimum_balance'] = 0.00;
			}
			if(isset($this->request->data['User']['allow_credit_check']))
			{
				$data['User']['allow_credit_check'] = $this->request->data['User']['allow_credit_check'];
				$data['User']['allow_credit'] = $this->request->data['User']['allow_credit'];
			}
			else
			{
				$data['User']['allow_credit_check'] = 0;
				$data['User']['allow_credit'] = NULL;
				$this->User->validator()->remove('allow_credit', 'numericval');
				$this->User->validator()->remove('allow_credit', 'maxLength');
				$this->User->validator()->remove('allow_credit', 'format');
				$this->User->validator()->remove('allow_credit', 'comparison');
			}
			
			if(empty($data['User']['email']))
			{
			    $this->User->validator()->remove('email', 'isUnique');
			}
			
			if($this->Auth->User('role_id') == 1)
			{
				$data['User']['role_id'] = '2'; //Mediator...
				$this->User->validator()->remove('allow_credit', 'numericval');
				$this->User->validator()->remove('allow_credit', 'maxLength');
				$this->User->validator()->remove('allow_credit', 'format');
				$this->User->validator()->remove('allow_credit', 'comparison');
				/*$this->User->validator()->remove('minimum_balance', 'numericval');
				$this->User->validator()->remove('minimum_balance', 'maxLength');
				$this->User->validator()->remove('minimum_balance', 'format');
				$this->User->validator()->remove('minimum_balance', 'comparison');*/
			}
			else
			{
				$data['User']['role_id'] = '3'; //Retailer...
			}
			$data['User']['status'] = '1';
			$data['User']['added_by'] = $this->Auth->User('id');

			$name = ucwords($data['User']['fname']." ".$data['User']['lname']);
            
			//$this->User->create();
			
            foreach ($data['User'] as $key => $value) {
              if($key != 'password' && $key != 'confirm_password')
	          $data['User'][$key] = trim($value);
            }
			$new_mediator = $this->User->save($data);
			if($new_mediator)
			{
				$new_transaction = array();
				$new_transaction['Transaction']['user_id'] = $new_mediator['User']['id'];
				$new_transaction['Transaction']['allocator_id'] = $new_mediator['User']['added_by'];
				$new_transaction['Transaction']['total_amount'] = 0;
				$new_transaction['Transaction']['balance'] = 0;

				if($this->Auth->User('role_id') == 1)
				$new_transaction['Transaction']['role_id'] = 2;
				else
				$new_transaction['Transaction']['role_id'] = 3;
				
				$new_transaction['Transaction']['created'] = date('Y-m-d H:i:s');
				$new_transaction['Transaction']['updated'] = date('Y-m-d H:i:s');
				$this->Transaction->create();
				$this->Transaction->save($new_transaction);

				if($this->Auth->User('role_id') == 1)
				{
					$this->Session->setFlash(__($name.' has been successfully added as a mediator.'), 'default', array('class' => 'success'));
				}
				else
				{
					$this->Session->setFlash(__($name.' has been successfully added as a retailer.'), 'default', array('class' => 'success'));
				}
				$this->loadModel('EmailContent');
				
				if($data['User']['role_id'] == 2)
				{
                    $login_link = "<a href='".Router::url('/', true)."admin/' >Click Here</a>";
				}
				else
				{
                   $login_link = "<a href='".Router::url('/', true)."Users/login/' >Click Here</a>";
				}
			
				if($this->Auth->User('role_id') == 1)
				{
					if($data['User']['email'])
					$this->EmailContent->_RegisterMail($data['User']['email'],$name,$login_link);		
 					//$this->redirect(array('action' => 'manage_mediator'));
				}
				else
				{
					$mediator_name = ucwords($this->Auth->User('fname')." ".$this->Auth->User('lname'));
				    $mediator_email = 	$this->Auth->User('email');
					if($mediator_email && $data['User']['email'])
					$this->EmailContent->_RegisterMailRetailer($mediator_name,$mediator_email,$data['User']['email'],$name,$login_link);		
 					//$this->redirect(array('action' => 'manage_retailer'));
				}
                $this->redirect(array('action' => 'add'));
                //$this->redirect(array('action' => 'edit',$new_mediator['User']['id']));
			} 
			else 
			{
				if($this->Auth->User('role_id') == 1)
				{
					$this->Session->setFlash(__('User could not be added as a mediator. Please, try again.'), 'default', array('class' => 'error'));		
				}
				else
				{
					$this->Session->setFlash(__('User could not be added as a retailer. Please, try again.'), 'default', array('class' => 'error'));		
				}
			}
		}
	}

	protected function check_account(){
		$random = rand(100000,999999);
		$this->loadModel('User');
		$exists_number = $this->User->find('first',array('conditions'=>array('User.username'=>$random)));
		if(empty($exists_number)){
			return $random;
		}else{
			$this->check_account();
		}
	}
	
	public function admin_edit($id = NULL){
		
		if($this->Auth->User('role_id') == 1)
		{
			$this->set('title_for_layout',"Edit");
		}
		else
		{
			$this->set('title_for_layout',"Edit");
		}
    
		$this->set('role_id',$this->Auth->User('role_id'));

		$this->loadModel('SecurityQuestion');
		$questions = $this->SecurityQuestion->find('list',array('conditions'=>array('status'=>1),'fields'=>'id,question_english'));
       // prd($questions);
        $this->set('questions',$questions);
    
		$this->loadModel('Country');
		$all_country = $this->Country->find('all',array('order'=>'country_name asc'));
		$set_countries = array();
		foreach($all_country as $country)
		{
			$code = $country['Country']['country_code'];
			$name = $country['Country']['country_name'];
			$set_countries[$code] = ucwords($name); 
		}
		
		$this->set('set_countries',$set_countries);
		
	  if(!empty($this->request->data))
	  {
  	        $get_user_data = $this->User->findById($this->request->data['User']['id']);
			if($this->request->data['User']['image'])
			{
			 if(file_exists(WWW_ROOT.'img/users/'.$this->request->data['User']['image']))
			 {
				$resolution = getimagesize (WWW_ROOT.'img/users/'.$this->request->data['User']['image']);
				$width = $resolution[0];
				$height = $resolution[1];
				if($width < 200 || $height < 200 || $height > 768 || $width > 1024)
				{
				 $this->Session->setFlash(__("Please upload image with resolution between ".'200 * 200 - 1024 * 768'."."), 'default', array('class' => 'error'));
				 if($get_user_data ['User']['image'] != $this->request->data['User']['image'] )
				 {
					 unlink(WWW_ROOT.'img/users/'.$this->request->data['User']['image']);
				     $this->request->data['User']['image'] = $get_user_data ['User']['image'];	
					 $this->request->data['User']['files'] = array(); 
				 }
				 $this->set('role_id',$this->Auth->User('role_id'));
				 return;
				}
			   }
			   
			   else
			   {
				   $this->request->data['User']['image'] = NULL;
			   }
			}
			
			$data = $this->request->data;
			
			if(!isset($data['User']['change_password']))
			$data['User']['change_password'] = 0;
			
			if($data['User']['change_password'] == 1)
			{
			  
			}
			else
			{
				unset($data['User']['password']);
				unset($data['User']['confirm_password']);
			}
			
			if(isset($data['User']['allow_credit_check']))
			{
				
			}
			else
			{
				//$data['User']['allow_credit'] = NULL;
				//$data['User']['allow_credit_check'] = 0;
				$this->User->validator()->remove('allow_credit', 'numericval');
				$this->User->validator()->remove('allow_credit', 'maxLength');
				$this->User->validator()->remove('allow_credit', 'format');
				$this->User->validator()->remove('allow_credit', 'comparison');
			}
			
			if(empty($data['User']['email']))
			{
				$this->User->validator()->remove('email', 'isUnique');
			}
			
			//unset($data['User']['allow_credit_check']);
			
	            foreach ($data['User'] as $key => $value) {
	                 
	                 if($key != 'password' && $key != 'confirm_password')
	                 $data['User'][$key] = trim($value);
	            }
	            unset($data['User']['username']);
            	$this->User->validator()->remove('username');
            	$save_data = $this->User->save($data);
			   if($save_data)
			   {
				if($save_data['User']['image'] != $get_user_data['User']['image'] && !empty($get_user_data['User']['image']))
				{
					 if(file_exists(WWW_ROOT.'img/users/'.$get_user_data['User']['image']))
					 unlink(WWW_ROOT.'img/users/'.$get_user_data['User']['image']);
				}
				
				if($save_data['User']['role_id'] == 2)
				{
					$this->Session->setFlash(__('Mediator has been edited successfully.'), 'default', array('class' => 'success'));
				}
				else
				{
					$this->Session->setFlash(__('Retailer has been edited successfully.'), 'default', array('class' => 'success'));
				}
                
                $this->redirect(array('action' => 'edit',$data['User']['id']));
				if($this->Auth->User('role_id') == 1)
				{
					//$this->redirect(array('action' => 'manage_mediator'));
				}
				else
				{
					//$this->redirect(array('action' => 'manage_retailer'));
				}
			}
			else
			{
                if($this->request->data['User']['role_id'] == 2)
				{
					$this->Session->setFlash(__('Mediator could not be edited.'), 'default', array('class' => 'error'));
				}
				else
				{
					$this->Session->setFlash(__('Retailer could not be edited.'), 'default', array('class' => 'error'));
				}
				$this->set('role_id',$this->Auth->User('role_id'));
				return;
			}
		}
		else
		{
                        $get_user_data = $this->User->findById($id);
			if(!empty($get_user_data))
			{
				unset($get_user_data['User']['password']);
				unset($get_user_data['User']['original_password']);
				$this->request->data = $get_user_data;
			}
			else
			{
  			$this->Session->setFlash(__('Invalid user.'), 'default', array('class' => 'error'));
				if($this->Auth->User('role_id') == 1)
				{
					//$this->redirect(array('action' => 'manage_mediator'));
				}
				else
				{
					//$this->redirect(array('action' => 'manage_retailer'));
				}
			}
		}
	}
	
	public function admin_view($id = NULL) {
	   	
			if($this->Auth->User('role_id') == 1)
			{
				$this->pageTitle = __('View');
			}
			else
			{
				$this->pageTitle = __('View');
			}
			$this->set('pageTitle',$this->pageTitle );
      
			$this->set('role_id',$this->Auth->User('role_id'));
			$get_user_data = $this->User->findById($id);
			if(!empty($get_user_data))
			{
				$this->request->data = $get_user_data;
				if(!empty($get_user_data['User']['country_code']))
				{
					$this->loadModel('Country');
					$country = $this->Country->findByCountryCode($get_user_data['User']['country_code']);
				  if($country)
					$this->request->data['User']['country_code'] = $country['Country']['country_name'];
				}
			}
			else
			{
  			$this->Session->setFlash(__('Invalid user.'), 'default', array('class' => 'error'));
				$this->redirect(array('action' => 'manage_mediator'));
			}
	}
	
	public function admin_deleteuser(){
	    $ids =$this->request->data['ids'];
			foreach($ids as $id)
			{
				$get_user_data = $this->User->findById($id);
				if(!empty($get_user_data))
				{
				$get_user_data['User']['email'] = $get_user_data['User']['email']."_".$get_user_data['User']['id']."deleted";
				$res = $this->User->updateAll(array('User.email'=>"'".$get_user_data['User']['email']."'",'User.status'=>3,'User.updated'=>"'".date('Y-m-d H:i:s')."'"),array('User.id'=>$id));
				
				$get_user_child_data = $this->User->find('all',array('conditions'=>array('added_by'=>$id)));
				foreach($get_user_child_data as $child_data)
				{
				$res_child = $this->User->updateAll(array('User.email'=>"'".$child_data['User']['email']."'",'User.status'=>3,'User.updated'=>"'".date('Y-m-d H:i:s')."'"),array('User.id'=>$child_data['User']['id']));
				}
			 }
			else
			{
  	
			}
		}
		echo "1";
		exit;
	}
	
	public function change_language(){
		$language_code = $this->request->data['language_code'];
		if($this->Auth->User('id'))
		{
			$userdata = $this->User->findById($this->Auth->User('id'));
		}
		else
		{
			$userdata = array();
			$this->Session->write('Config.language', $language_code);
			Configure::write('Config.language', $language_code);		
		}
		if($userdata)
		{
			$res = $this->User->updateAll(array('User.language_code'=>"'".$language_code."'"),array('User.id'=>$this->Auth->User('id')));
 		}
		echo "1";
		exit;
	}
	
	
	public function admin_changeStatus($user_id = NULL , $status = NULL){
	  
		if($user_id == NULL || $status == NULL)
		{
			$this->Session->setFlash(__('Invalid mediator.'), 'default', array('class' => 'error'));
			if($this->Auth->User('role_id') == 1)
			{
				$this->redirect(array('controller' => 'Users', 'action' => 'manage_mediator'));
			}
			else
			{
				$this->redirect(array('controller' => 'Users', 'action' => 'manage_retailer'));
			}
		}
		
		$get_user_data = $this->User->findById($user_id);
	  if($get_user_data)
		{
			$res = $this->User->updateAll(array('User.status'=>$status),array('User.id'=>$user_id));
			$get_user_data = $this->User->findById($user_id);
			if($res)
			{
			  $this->Session->setFlash(__('Status updated successfully.'), 'default', array('class' => 'success'));
			}
			else
			{
				$this->Session->setFlash(__('Status could not be updated now.'), 'default', array('class' => 'error'));
			}
		}
		else
		{
		  $this->Session->setFlash(__('Invalid mediator.'), 'default', array('class' => 'error'));
		}
		
		if($this->Auth->User('role_id') == 1)
		{
			$this->redirect(array('controller' => 'Users', 'action' => 'manage_mediator'));
		}
		else
		{
			$this->redirect(array('controller' => 'Users', 'action' => 'manage_retailer'));
		}
	}
	
	public function admin_send_mail($ids = NULL)
	{
		if($ids == NULL)
		{
		  $this->Session->setFlash(__('Invalid ids.'), 'default', array('class' => 'error'));
		  if($this->Auth->User('role_id') == 1)
			{
				$this->redirect(array('controller' => 'Users', 'action' => 'manage_mediator'));
			}
			else
			{
				$this->redirect(array('controller' => 'Users', 'action' => 'manage_retailer'));
			}
		}
		else
		{
			$new_ids = explode(',',$ids);
			$user_emails =array();
			foreach($new_ids as $uid)
			{
				$user_data = $this->User->find('first',array('conditions'=>array('id'=>$uid),'fields'=>'email,status'));
				if(!empty($user_data))
				{
					// Enabled user will get the mail
					/*if($user_data['User']['status'] == 1)
					{*/
						if(!empty($user_data['User']['email']))
						$user_emails[] = $user_data['User']['email'];
					//}
				}
			}
			$this->set('user_emails',$user_emails);
			$redirect_to = "user_grid";
			if(empty($user_emails))
			{
				
				if($this->Auth->User('role_id')==1)
				{
				    $this->Session->setFlash(__('Selected Mediator(s) does not have email address.'), 'default', array('class' => 'error'));
					$this->redirect(array('controller' => 'Users', 'action' => 'manage_mediator','admin'=>true,'user_emails'=>$user_emails,$redirect_to));
				}
				else
				{
		        	$this->Session->setFlash(__('Selected Retailor(s) does not have email address.'), 'default', array('class' => 'error'));
					$this->redirect(array('controller' => 'Users', 'action' => 'manage_retailer','admin'=>true,'user_emails'=>$user_emails,$redirect_to));
				}
			}
			$this->redirect(array('controller' => 'Mails', 'action' => 'index','admin'=>true,'user_emails'=>$user_emails,$redirect_to));
		}
	}
	
	public function profile_manage($id = NULL){
		
		$this->set('title_for_layout',__("Edit Profile"));
		$this->set('role_id',$this->Auth->User('role_id'));
		$is_error = 0;
		$this->loadModel('Country');
	    $all_country = $this->Country->find('all',array('order'=>'country_name asc'));
		$set_countries = array();
		
        $language=Configure::read('Config.language');
		if(empty($language))
		$language="en";

        $this->loadModel('SecurityQuestion');
		
		if($language == "en")
		$questions = $this->SecurityQuestion->find('list',array('conditions'=>array('status'=>1),'fields'=>'id,question_english'));
		else
		$questions = $this->SecurityQuestion->find('list',array('conditions'=>array('status'=>1),'fields'=>'id,question_german'));
			
		$this->set('questions',$questions); 

		foreach($all_country as $country)
		{
			$code = $country['Country']['country_code'];
			$name = $country['Country']['country_name'];
			$set_countries[$code] = ucwords($name); 
		}
		
		$this->set('set_countries',$set_countries);
		
	    if(!empty($this->request->data))
		{
		    $image_data = '';
			$get_user_data = $this->User->findById($this->request->data['User']['id']);
			if(isset($this->request->data['User']['image']) && !empty($this->request->data['User']['image']))
			{
			 if(file_exists(WWW_ROOT.'img/users/'.$this->request->data['User']['image']))
			 {
				$resolution = getimagesize (WWW_ROOT.'img/users/'.$this->request->data['User']['image']);
				$width = $resolution[0];
				$height = $resolution[1];
				if($width < 200 || $height < 200 || $width >1024 || $height >768)
				{
                    //$this->Session->setFlash(__('Your image resolution is '.$width." * ".$height." Minimum required resolution is 200 * 200."), 'default', array(),'error');
		 			$this->Session->setFlash(__("Please upload image with resolution between ").'200*200 - 1024*768'.".", 'default', array(),'error');
					$is_error = 1;
					$this->set('is_error',$is_error);
				 if($get_user_data ['User']['image'] != $this->request->data['User']['image'] )
				 {
				     unlink(WWW_ROOT.'img/users/'.$this->request->data['User']['image']);
				     $this->request->data['User']['image'] = $get_user_data ['User']['image'];	
				     $this->request->data['User']['files'] = array(); 
				 }
				 return;
				}
			   }
			   else
			   {
				   $this->request->data['User']['image'] = NULL;
			   }
			}
			
			$data = $this->request->data;
			
			$data['User']['password'] = $data['User']['password'];
      		$data['User']['confirm_password'] = $data['User']['confirm_password'];

			if(empty($data['User']['password']) && empty($data['User']['confirm_password']))
			{
				unset($data['User']['password']);
				unset($data['User']['confirm_password']);
			}
			
			$this->set('username',$data['User']['username']);
			unset($data['User']['username']);
            $this->User->validator()->remove('username');
			$save_data = $this->User->save($data);
			if($save_data)
			{
				$is_error = 1;
				$this->Session->write('Auth.User.fname',$save_data['User']['fname']);
				$this->Session->write('Auth.User.lname',$save_data['User']['lname']);
				if(isset($save_data['User']['image']))
				{
					$this->Session->write('Auth.User.image',$save_data['User']['image']);
				}
				$this->Session->write('Auth.User.email',$save_data['User']['email']);
				$this->Session->write('Auth.User.phone',$save_data['User']['phone']);
				$this->Session->write('Auth.User.address',$save_data['User']['address']);
				$this->Session->write('Auth.User.country_code',$save_data['User']['country_code']);
				
				$user_country = $this->Country->find('first',array('conditions'=>array('country_code'=>$save_data['User']['country_code'])));
				

				if(!empty($user_country))
				{
					$this->set('country_name',$user_country['Country']['country_name']);
				}
				else
				{
					$this->set('country_name','');
				}

				if(isset($save_data['User']['image']))
				{
					if($save_data['User']['image'] != $get_user_data['User']['image'] && !empty($get_user_data['User']['image']))
					{
					 if(file_exists(WWW_ROOT.'img/users/'.$get_user_data['User']['image']))
					 unlink(WWW_ROOT.'img/users/'.$get_user_data['User']['image']);
					}
				}
				
				$this->Session->setFlash(__('Profile has been edited successfully.'), 'default', array(),'success');
				unset($this->request->data['User']['password']);
				unset($this->request->data['User']['confirm_password']);
				//$this->redirect(array('action' => 'profile_manage',$this->Auth->User('id')));
				$this->set('return_profile',0);
				$this->set('is_error',$is_error);
			}
			else
			{
				$is_error = 1;
				$get_user_data = $this->User->findById($this->request->data['User']['id']);
				//$this->request->data = $get_user_data;
				//prd($this->request->data);
				$user_country = $this->Country->find('first',array('conditions'=>array('country_code'=>$get_user_data['User']['country_code'])));
				if(!empty($user_country))
				{
					$this->set('country_name',$user_country['Country']['country_name']);
				}
				else
				{
					$this->set('country_name','');
				}
			    $this->Session->setFlash(__('Profile could not be updated.'), 'default', array(),'error');
				$this->set('return_profile',1);
				$this->set('is_error',$is_error);
				return;
			}
		}
		else
		{
            $this->set('return_profile',0);
			$get_user_data = $this->User->findById($id);
			if(!empty($get_user_data))
			{
				unset($get_user_data['User']['password']);
				unset($get_user_data['User']['original_password']);
				$this->request->data = $get_user_data;
				$user_country = $this->Country->find('first',array('conditions'=>array('country_code'=>$get_user_data['User']['country_code'])));
				

				if(!empty($user_country))
				{
					$this->set('country_name',$user_country['Country']['country_name']);
				}
				else
				{
					$this->set('country_name','');
				}
			}
			else
			{
  			   $this->Session->setFlash(__('Invalid User.'), 'default', array(),'error');
			   $this->redirect(array('controller'=>'Searches','action' => 'online_card'));
			}
		}
	}

    
	public function account_information(){
	    
		$this->loadModel('FundAllocate');
		$login_user = $this->Auth->User('id');
		
		$total_paid_amount = 0.00;
		$available_balance = 0.00;
        $parent_transaction_id = 0;
		$get_fund_allocation_data = array();
	    $records = 0;
		$start_date = date('d.m.Y');
		$end_date = date('d.m.Y');
		
		$get_user_transaction_details = $this->get_transaction_details($this->Auth->User('id'));
		
		if($this->request->data)
		{
		  
		  if(!empty($this->request->data['User']['datepicker1']))
		  {
		  	  $transaction_start_date_array = explode('.',$this->request->data['User']['datepicker1']);
			  $transaction_start_date = $transaction_start_date_array[2]."-".$transaction_start_date_array[1]."-".$transaction_start_date_array[0];
			  
			  $transaction_end_date_array = explode('.',$this->request->data['User']['datepicker2']);
			  $transaction_end_date = $transaction_end_date_array[2]."-".$transaction_end_date_array[1]."-".$transaction_end_date_array[0];
	 		  
			  $records = $this->request->data['User']['total_records'];
			  $start_date = $this->request->data['User']['datepicker1'];
			  $end_date = $this->request->data['User']['datepicker2'];

			  $this->set('start_date',$start_date);
			  $this->set('end_date',$end_date);
		  }
		  
		}
		
		if(!isset($transaction_end_date))
		{
			$transaction_start_date = date('Y-m-d');
			if(!empty($get_user_transaction_details))
			{
				$first_transaction_date = date('Y-m-d',strtotime($get_user_transaction_details['Transaction']['created']));
				$start_date = date('d.m.Y',strtotime($get_user_transaction_details['Transaction']['created']));
				$transaction_start_date = $first_transaction_date;
			}
			$transaction_end_date = date('Y-m-d');
		}
		
		//pr(strtotime($transaction_end_date) -strtotime($transaction_start_date));
        $get_days = floor((strtotime($transaction_end_date) -strtotime($transaction_start_date))/(60*60*24));
		$get_days++;

		// Filtering Data Count
		if($records != 0 && $get_days >= $records)
		$get_days = $records;
		
		$all_dates =array();
		$all_dates[0] = $transaction_end_date;
		for($i = 1; $i<$get_days; $i++)
		{
			$previous_counter = $i -1;
			$all_dates[$i] =  date("Y-m-d", strtotime("$all_dates[$previous_counter] -1 day"));
 		}
		
		if(!empty($get_user_transaction_details))
		{
			$total_paid_amount = $get_user_transaction_details['Transaction']['total_amount'];
			$available_balance = $get_user_transaction_details['Transaction']['balance'];
			$parent_transaction_id = $get_user_transaction_details['Transaction']['id'];
		}
		
	    // Getting Day Wise Transaction
		$set_all_transactions = array();
		$total_dates =count($all_dates);
		
		$transaction_count = 0;
		for($counter = 0 ;$counter <$total_dates ; $counter++)
		{
			$date = $all_dates[$counter];
			$date_sale = $this->user_sale_by_date($login_user,$date);
   			$get_fund_allocation_data = $this->FundAllocate->find('all',array('conditions'=>array('parent_id'=>$parent_transaction_id,'DATE(created)'=>$date),'order'=>'id desc'));
			$previous_balance = '';
			$amount_paid = '';
			$remarks = '';
			$payment_mode = '';
			
			if($date_sale != 0 || !empty($get_fund_allocation_data))
			{
				if(!empty($get_fund_allocation_data))
				{
					foreach($get_fund_allocation_data as $fund_allocation)
					{
						$previous_balance = $fund_allocation['FundAllocate']['previous_balance'];
						$amount_paid = $fund_allocation['FundAllocate']['total_amount'];
						$remarks = $fund_allocation['FundAllocate']['remarks'];
						if($fund_allocation['FundAllocate']['payment_mode'] == 1)
						{
							$payment_mode = __("Cash");
						}
						else if($fund_allocation['FundAllocate']['payment_mode'] == 2)
						{
							$payment_mode = __("Bank");
						}
						else if($fund_allocation['FundAllocate']['payment_mode'] == 3)
						{
							$payment_mode = __("Other");
						}
					  
						  /*Sale Of Card On Date*/
						$set_all_transactions[$transaction_count]['date'] = date('d.m.Y',strtotime($date));
						$set_all_transactions[$transaction_count]['date_sale'] = $date_sale;
						/*Fund Allocation On Same Date*/
						$set_all_transactions[$transaction_count]['previous_balance'] = $previous_balance;
						$set_all_transactions[$transaction_count]['total_amount'] = $amount_paid;
						$set_all_transactions[$transaction_count]['payment_mode'] = $payment_mode;
						$set_all_transactions[$transaction_count]['remarks'] = $remarks;
						$transaction_count++;
					}
				}
				else
				{
					/*Sale Of Card On Date*/
					$set_all_transactions[$transaction_count]['date'] = date('d.m.Y',strtotime($date));
					$set_all_transactions[$transaction_count]['date_sale'] = $date_sale;
					/*Fund Allocation On Same Date*/
					$set_all_transactions[$transaction_count]['previous_balance'] = $previous_balance;
					$set_all_transactions[$transaction_count]['total_amount'] = $amount_paid;
					$set_all_transactions[$transaction_count]['payment_mode'] = $payment_mode;
					$set_all_transactions[$transaction_count]['remarks'] = $remarks;
					$transaction_count++;
			    }
			}
		}
			
		$total_purchase = $this->get_total_purchase($this->Auth->User('id'));
		$card_sale_count = $this->card_sale_count_totay($this->Auth->User('id'));
        
    	$this->set('set_all_transactions',$set_all_transactions);
		$this->set('card_sale_count',$card_sale_count);
		$this->set('total_paid_amount',$total_paid_amount);
		$this->set('available_balance',$available_balance);
		$this->set('total_purchase',$total_purchase);
		$this->set('records',$records);

	}
	
	public function admin_front_image(){
	  $this->admin_redirect_to_dashboard_distributor();
	  $this->set('title_for_layout',__('Manage Front Image'));
	  if($this->Auth->User('role_id') != 1)
		{
					$this->Session->setFlash(__('You are not authorized to access this page.'), 'default', array('class' => 'error'));				
					$this->redirect(array('controller'=>'users','action' => 'dashboard'));
		}
	}
	
	public function admin_generategrid_front_image()
	{
		$this->loadModel('FrontImage');
		$page  = $this->request->query['page'];
		$limit = $this->request->query['rows'];
		$sidx  = $this->request->query['sidx'];
		$sord  = $this->request->query['sord'];
		
		if(!$sidx) $sidx =1;
		$order_by = $sidx.' '.$sord;
		
	    $conditions =array();
		if(isset($this->request->query['filters']))
		{
			$filters = json_decode($this->request->query['filters'], true);
			foreach($filters['rules'] as $each_filter)
			{
			
				if($each_filter['field'] == 'id'){
					$conditions['FrontImage.id LIKE'] = '%'.Sanitize::clean($each_filter['data']).'%';
				}
			
				if($each_filter['field'] == 'content_english'){
					$conditions['FrontImage.content_english LIKE'] = '%'.Sanitize::escape($each_filter['data']). '%';
				}

				if($each_filter['field'] == 'status'){
					if($each_filter['data'] != 0)
					$conditions['FrontImage.status'] = Sanitize::clean($each_filter['data']);
				}
			}
		}
		
		$count = $this->FrontImage->find('count',array(
				'recursive' => -1, 
			  	'conditions' => $conditions,
			)
		);
		
		if($count >0)
		{ 
			$total_pages = ceil($count/$limit); 
		}
		else
		{
			$total_pages = 0; 
		}
		
		if ($page > $total_pages) 
		{
			$page=$total_pages;
		}
		
		$start = $limit*$page - $limit; 
		$front_images = $this->FrontImage->find('all', array(
			'recursive' => -1, //int
			'conditions' => $conditions,
			'order' => $order_by,
			'limit' => $limit,
			'offset' => $start
		));
    
		$responce = new stdClass();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count; 
		
		$i=0;
		
		if(is_array($front_images))
		{
			foreach($front_images as $images)
			{
				$img  = $images['FrontImage']['image'];
				$content = $images['FrontImage']['content_english'];
				
				if(strlen($content) >100)
				{
					$content = substr($content, 0,100)."....";
				}
				$status = $images['FrontImage']['status'];
				if($img!='' && file_exists('img/front_images/'.$img))
				{
					$image_cat = '<img style="cursor:pointer;margin-top:3px;margin-left:6px;" src="'.$this->webroot.'img/front_images/'.$img.'" border="0" width="200" height="50"/>';
				}
				else 
				{ 
					$image_cat = '<img style="cursor:pointer;margin-top:3px;margin-left:6px;" src="'.$this->webroot.'img/no_bg_available.jpg" border="0" width="150" height="75"/>';
				}
				
				//Edit Front Image && Content
				$edit = Router::url(array('controller'=>'Users','action'=>'front_image_edit','admin'=>true,$images['FrontImage']['id']));
				$link_edit = '<a title="Edit Record" class="grid_link" href="'.$edit.'" style="color:#438CE5;">Edit</a>';
				
				// View Front Image && Content
				$view = Router::url(array('controller'=>'Users','action'=>'view_front_image','admin'=>true,$images['FrontImage']['id']));
                $link_view = '<a title="View Record" class="grid_link" href="'.$view.'" style="color:#438CE5;">View |</a>';

				
				$action = $link_view." ".$link_edit;
				
				$status_link = '<div style="float:left; padding:4px;width:100%;"><select class="form-control" title="Change Status" onchange="changeStatus('.$images['FrontImage']['id'].',this)" >';
				if($images['FrontImage']['status'] == 1)
				{
					$status = "Active";
					$status_link .= '<option value="2">Disabled</option>
											<option selected="selected" value="1">Enabled</option>
										</select></div>';
				}
				else if($images['FrontImage']['status'] == 2)
				{
					$status = "Pending";
   				    $status_link .= '<option selected="selected" value="2">Disabled</option>
											<option value="1">Enabled</option>
										</select></div>';
				}
				else
				{
					$status = "Not Defined";
					$status_link .= '<option selected="selected" value="2">Disabled</option>
											<option value="1">Enabled</option>
										</select></div>';
				}
				
				$responce->rows[$i]['id']=$images['FrontImage']['id'];
				$responce->rows[$i]['cell']=array($images['FrontImage']['id'],$content,$image_cat,$action); 
				$i++; 
			} 
		}
		echo json_encode($responce); exit;
	}
	
	public function admin_front_image_edit($front_image_id = NULL){
	    	$this->admin_redirect_to_dashboard_distributor();
			$this->set('title_for_layout',"Edit Front Image");
       		$this->loadModel('FrontImage');
			$image_data = $this->FrontImage->find('first',array('conditions'=>array('id'=>$front_image_id)));
			
			$default_images = array('bg1.png','bg2.png','bg3.png','bg4.png');
			if($this->request->data)
			{
				$get_img_data = $this->FrontImage->findById($this->request->data['FrontImage']['id']);
				//prd($get_img_data);
			  if($this->request->data['FrontImage']['image'])
			  {
				if(!in_array($this->request->data['FrontImage']['image'],$default_images)) 
			    {
				 if(file_exists(WWW_ROOT.'img/users/'.$this->request->data['FrontImage']['image']))
				 {
					$resolution = getimagesize (WWW_ROOT.'img/front_images/'.$this->request->data['FrontImage']['image']);
					$width = $resolution[0];
					$height = $resolution[1];
					if($width != 1600 && $height != 850)
					{
					 //$this->Session->setFlash(__('Your image resolution is '.$width." * ".$height." Required resolution is 1600 * 850."), 'default', array('class' => 'error'));
					 $this->Session->setFlash(__("Please upload image with resolution  ".'1600 * 850'."."), 'default', array('class' => 'error'));
					 if($get_img_data ['FrontImage']['image'] != $this->request->data['FrontImage']['image'] )
					 {
						 unlink(WWW_ROOT.'img/front_images/'.$this->request->data['FrontImage']['image']);
						 $this->request->data['FrontImage']['image'] = $get_img_data ['FrontImage']['image'];	
						 $this->request->data['FrontImage']['files'] = array(); 
					 }
					 return;
					}
				   }
				  }
				}
				
				$this->request->data['FrontImage']['updated'] = date('Y-m-d h:i:s');
				$save_data =$this->FrontImage->save($this->request->data) ;
				if ($save_data)
				{
					if(isset($save_data['FrontImage']['image']))
					{
						if($save_data['FrontImage']['image'] != $get_img_data['FrontImage']['image'] && !empty($get_img_data['FrontImage']['image']))
						{
							 if(!in_array($save_data['FrontImage']['image'],$default_images)) 
							 {
								if(file_exists(WWW_ROOT.'img/users/'.$get_img_data['FrontImage']['image']))
								unlink(WWW_ROOT.'img/users/'.$get_user_data['User']['image']);
							 }
						}
					}
					$this->Session->setFlash(__('Front image data has been updated successfully.'), 'default', array('class' => 'success'));
					//$this->redirect(array('controller'=>'Users','action' => 'front_image','admin'=>true));
				} 
				else 
				{
					$this->Session->setFlash(__('Front image data could not be edited. Please, try again.'), 'default', array('class' => 'error'));
				}
			}
			else
			{
				$this->request->data = $image_data;
			}
    	}
	
	public function admin_unlink_file(){
	
	  $this->loadModel('FrontImage');
	  $id = $this->request->data['id'];
	  $file_name = $this->request->data['file_name'];
	  $get_image_data = $this->FrontImage->find('first',array('conditions'=>array('id'=>$id)));
	  if(!empty($get_image_data))
	  {
		  if($get_image_data['FrontImage']['image'] != $file_name)
		  {
			 if(file_exists(WWW_ROOT.'img/front_images/'.$file_name))
			 unlink(WWW_ROOT.'img/front_images/'.$file_name);
		  }
	  }
	  exit;
	}
	
	public function admin_unlink_file_pins(){
	  $file_name = $this->request->data['file_name'];
  	  if(file_exists(WWW_ROOT.'img/admin_uploads/pins_uploaded/'.$file_name))
	  unlink(WWW_ROOT.'img/admin_uploads/pins_uploaded/'.$file_name);
	  exit;
	}
    
	public function admin_view_front_image($id = NULL) {
	   	    $this->admin_redirect_to_dashboard_distributor();
		    $this->loadModel('FrontImage');
			$this->pageTitle = __('View Front Image');
			$this->set('pageTitle',$this->pageTitle );
      
			$get_img_data = $this->FrontImage->findById($id);
			if(!empty($get_img_data))
			{
				$this->request->data = $get_img_data;
			}
			else
			{
    			$this->Session->setFlash(__('Invalid user.'), 'default', array('class' => 'error'));
				$this->redirect(array('action' => 'front_image'));
			}
	}
   
   
   public function admin_unlink_add_file(){
   
      $file_name = $this->request->data['file_name'];
	  if(!empty($file_name ))
	  {
			 if(file_exists(WWW_ROOT.'img/users/'.$file_name))
			 unlink(WWW_ROOT.'img/users/'.$file_name);
	  }
	  exit;
   }

   public function forget_password(){

        
        $this->loadModel('User');
        // -- $this->Captcha = $this->Components->load('Captcha', array('captchaType'=>'math', 'jquerylib'=>true, 'modelName'=>'User', 'fieldName'=>'captcha')); //load it
		
		if (!isset($this->Captcha))
		{
			$this->Captcha = $this->Components->load('Captcha');
		}

		$this->loadModel('SecurityQuestion');
		
		$language=Configure::read('Config.language');
		if(empty($language))
		$language="en";
		
		if($language == "en")
		$questions = $this->SecurityQuestion->find('list',array('conditions'=>array('status'=>1),'fields'=>'id,question_english'));
        else         
   		$questions = $this->SecurityQuestion->find('list',array('conditions'=>array('status'=>1),'fields'=>'id,question_german'));

        $this->set('questions',$questions);

		$this->set('title_for_layout',__('Forgot Password'));
		if(!empty($this->request->data))
		{			
			//pr($this->request->data);
			$condition = array();	
			$condition['User.role_id'] = 3;		
			$email_username_search = 0; // Email Search
			if($this->request->data['User']['email'])
			{
				$condition['User.email ='] = trim($this->request->data['User']['email']);
				$email_username_search = 1;	
				unset($this->request->data['User']['username']);
			}
			else
			{
				$condition['User.username ='] = trim($this->request->data['User']['username']);
				unset($this->request->data['User']['email']);
            }

			$condition['User.security_question_id ='] = $this->request->data['User']['security_question_id'];
			$condition['User.security_answer ='] = trim($this->request->data['User']['security_answer']);	

					
			$this->User->setCaptchaValidation();
            $this->User->setCaptcha($this->Captcha->getVerCode()); //getting from component and passing to model to make proper validation check


		    $this->User->set($this->request->data); 					
			
			$this->User->validator()->remove('email', 'isUnique');
	        $this->User->validator()->remove('username', 'isUnique');
	        $this->User->validator()->remove('username', 'alphaNumeric');
	        $this->User->validator()->remove('username', 'required');
			
			$userRow = $this->User->find('all',array(
						'recursive' => -1,
						'fields'=>array('*'), 
						'conditions' => $condition,
						)
					);
			

			if($this->User->validates())	
			{ //as usual data save call	
				if ($userRow)
				{
					if (isset($this->request->data['User']['email']) && !empty($this->request->data['User']['email']))
						$verification_code = md5($this->request->data['User']['email'] . time());
					else
						$verification_code = md5($this->request->data['User']['username'] . time());

					$updateData = array();

					$updateData['User']['id'] = $userRow[0]['User']['id'];
					$updateData['User']['activation_key'] = $verification_code;

					unset($this->request->data['User']['email']);
					unset($this->request->data['User']['username']);


					$this->User->set = null;
					if ($this->User->save($updateData))
					{
						$name = ucwords(strtolower($userRow[0]['User']['fname']) . ' ' . strtolower($userRow[0]['User']['lname']));
						$email = strtolower($userRow[0]['User']['email']);
						$username = strtolower($userRow[0]['User']['username']);
						$old_pass = $userRow[0]['User']['original_password'];

						if ($email_username_search)
						{
							$username = 0;
							$link = Router::url('/', true) . 'users/verifypass/' . $userRow[0]['User']['email'] . "/" . $username . "/" . $verification_code;
							$oldpass_link = Router::url('/', true) . 'users/login/';
							$this->loadModel("EmailContent");
							$modelObj = new EmailContent();
							$modelObj->forgotPassword($name, $email, $old_pass, $link, $oldpass_link);
							
							$this->Session->setFlash(__('The link to reset your password has been sent to your email.'), 'default', array(), 'success');
							$this->redirect(Router::url('/', true));
							echo "Text";exit;
						}
						else
						{
							$email = 0;
							$this->redirect(array('controller' => 'users', 'action' => 'verifypass', $email, $username, $verification_code));
						}
					}
				}
				else
	            {
					if($email_username_search)
					$this->Session->setFlash(__('You have entered either invalid email or security question or answer. Please try again.'),'default', array(), 'error');
	                else
					$this->Session->setFlash(__('You have entered either invalid account number or security question or answer. Please try again.'),'default', array(), 'error');
	                	
	            }		
				
			}
			else
			{
                  $this->Session->setFlash(__('Data validation failure.'),'default', array(), 'error');
			}
	    
	    }

	}
	
 public function verifypass($email = 0, $username = 0, $verification_code = null){
			
	$this->set('title_for_layout',__( 'Reset Password'));
		
	if($this->request->is('get'))
	{
		if($email)
		{
           $user = $this->User->find('first',array(
			'recursive' =>-1,
			'fields'=>array('User.id','User.password'),
			'conditions'=>array(array('email' => $email, 'activation_key' => $verification_code)),
		   ));
		}
		else
		{
           $user = $this->User->find('first',array(
			'recursive' =>-1,
			'fields'=>array('User.id','User.password'),
			'conditions'=>array(array('username' => $username, 'activation_key' => $verification_code)),
		   ));
		}
		
		if(empty($user))
		{		
			$this->Session->setFlash(__('This activation code has already been used or not exists.'), 'default', array(), 'error');
			$this->redirect(array('controller'=>'Searches','action' => 'online_card'));
		}
		$this->set('id',$user['User']['id']);
	}
	else
	{
			$this->set('id',$this->request->data['User']['id']);
			
            
            $password = trim($this->request->data['User']['password']); 
            if(empty($password))
            {
                $this->Session->setFlash(__('You cannot use a blank password.'), 'default', array(),'error');
                return;
            }

			if($this->request->data['User']['password']!=$this->request->data['User']['confirm_password'])
			{
				$this->Session->setFlash(__('Password mismatched. Please, try again.'), 'default', array(), 'error');
			}
			else
			{			
				$data =array();
				$data['User']['activation_key']= '';
				$data['User']['original_password']= "'".$this->request->data['User']['password']."'";
				$data['User']['password'] = $this->request->data['User']['password'];
				$data['User']['id'] =$this->request->data['User']['id'];
				
				

				$this->User->validator()->remove('username');
				if($this->User->save($data)) 
				{
					$this->Session->setFlash(__('Your password has been changed successfully.'), 'default', array(),'success');
					$this->redirect(array('controller' => 'Users', 'action' => 'login'));
				} 
				else 
				{
					$this->Session->setFlash(__('Password could not be updated. Please, try again.'), 'default', array(), 'error');
				}
  			
			}
		}
  }

  public function admin_forgotpassword() {
		$this->set('title_for_layout','Admin Forgot Password');
		$this->pageTitle = __('Forgot Password');
 		$this->set('pageTitle',$this->pageTitle );
        
       // $this->Captcha = $this->Components->load('Captcha', array('captchaType'=>'image', 'jquerylib'=>true, 'modelName'=>'User', 'fieldName'=>'captcha')); //load it
		if (!isset($this->Captcha))
		{
			$this->Captcha = $this->Components->load('Captcha');
		}
		
        $this->loadModel('SecurityQuestion');
		$questions = $this->SecurityQuestion->find('list',array('conditions'=>array('status'=>1),'fields'=>'id,question_english'));
        $this->set('questions',$questions);


		if($this->request->data)
		{
			$condition = array();
			$condition['User.role_id'] = array('1','2');		
			$email_username_search = 0; // Email Search
			if($this->request->data['User']['email'])
			{
				$condition['User.email ='] = trim($this->request->data['User']['email']);
				$email_username_search = 1;	
				unset($this->request->data['User']['username']);
			}
			else
			{
				$condition['User.username ='] = trim($this->request->data['User']['username']);
                unset($this->request->data['User']['email']);
            }

			$condition['User.security_question_id ='] = $this->request->data['User']['security_question_id'];
			$condition['User.security_answer ='] = trim($this->request->data['User']['security_answer']);	
			
            $this->User->setCaptchaValidation();

            $this->User->setCaptcha($this->Captcha->getVerCode()); //getting from component and passing to model to make proper validation check
		    $this->User->set($this->request->data); 					
			
			
			$this->User->validator()->remove('email', 'isUnique');
	        $this->User->validator()->remove('username', 'isUnique');
	        $this->User->validator()->remove('username', 'alphaNumeric');
	        $this->User->validator()->remove('username', 'required');
			
			
			$this->User->set($this->data);

	    	if($this->User->validates())
			{			 
				$user = $this->User->find('first', array(
				   'conditions' => $condition));

				if(isset($user['User']['id']) and !empty($user['User']['id']))
				{
					if($email_username_search)
					{
						$verification_code = md5($user['User']['email'].time());
					}
					else
					{
						$verification_code = md5($user['User']['username'].time());
					}
									
					$user['User']['activation_key'] = $verification_code;
					$updatedata = $this->User->updateAll(array('User.activation_key'=>"'".$verification_code."'"),
														 array('User.id'=>$user['User']['id']));
					
					if($updatedata)
					{
						$name = ucwords($user['User']['fname']." ".$user['User']['lname']);
						$email = $user['User']['email'];
						$username = $user['User']['username'];
						if($email_username_search)
						{
							$username = 0;
							$old_pass=$user['User']['original_password'];
							$link   = Router::url('/',true).'admin/Users/verifypass/'.$user['User']['email']."/".$username."/".$verification_code;
							$oldpass_link=Router::url('/',true).'admin/users/login/';
							
							$this->loadModel("EmailContent");
							$this->EmailContent->forgotPassword($name,$email,$old_pass,$link,$oldpass_link);
							$this->Session->setFlash(__('The link to reset your password has been sent to your email.'), 'default', array('class' => 'success'));
							$this->redirect(array('controller'=>'Users', 'action'=>'login','admin'=>true));
						}
						else
						{
							$email = 0;
					   		$this->redirect(array('controller'=>'Users','action'=>'verifypass',$email, $username,$verification_code));

						}

						
					}
					else
					{
						$this->Session->setFlash(__('The link to reset your password could not be generated now.'), 'default', array('class' => 'error'));
						$this->redirect(array('controller'=>'Users', 'action'=>'login','admin'=>true));
					}
				}
				else
				{ 

                    if($email_username_search)
					$this->Session->setFlash(__('You have entered either invalid email or security question or answer. Please try again.'),'default', array('class' => 'error'));
	                else
					$this->Session->setFlash(__('You have entered either invalid account number or security question or answer. Please try again.'),'default', array('class' => 'error'));
	                
				}
			}
			else
			{
			   $this->Session->setFlash('Data Validation Failure','default',array('class' => 'error'));
			}
		}
	}
	
	public function admin_verifypass($email = 0,$username = 0,$verification_code = null){
			
		$this->set('title_for_layout','Reset Admin Password');
		$this->pageTitle = __('Reset Password');
		$this->set('pageTitle',$this->pageTitle );
		
		if($this->request->is('get'))
		{
			if($email)
			{
				$user = $this->User->find('first',array(
							'recursive' =>-1,
							'fields'=>array('User.id','User.password'),
							'conditions'=>array(array('email' => $email, 'activation_key' => $verification_code)),
				));
			}
			else
			{
				$user = $this->User->find('first',array(
							'recursive' =>-1,
							'fields'=>array('User.id','User.password'),
							'conditions'=>array(array('username' => $username, 'activation_key' => $verification_code)),
				));
			}
			
			if(empty($user))
			{		
	  		    $this->Session->setFlash(__('Invalid User.'), 'default', array('class' => 'error'));
				$this->redirect(array('controller'=>'Users','action' => 'login','admin'=>true));
			}
			$this->set('id',$user['User']['id']);
	    }
	    else
	    {
			$this->set('id',$this->request->data['User']['id']);
			
			$password = trim($this->request->data['User']['password']); 
            if(empty($password))
            {
                $this->Session->setFlash(__('You cannot use a blank password.'), 'default', array('class' => 'error'));
                return;
            }

			if($this->request->data['User']['password']!=$this->request->data['User']['confirm_password'])
			{
			  $this->Session->setFlash(__('Password mismatched. Please, try again.'), 'default', array('class' => 'error'));
			}
			else
			{			
				$data =array();
				$data['User']['activation_key']= '';
				$data['User']['original_password']= "'".$this->request->data['User']['password']."'";
				$data['User']['password'] = AuthComponent::password($this->request->data['User']['password']);
				$data['User']['id'] =$this->request->data['User']['id'];
				$activation_key = '';

                $this->User->validator()->remove('username');
				$updatedata = $this->User->updateAll(array('User.activation_key'=>"'".$activation_key."'",'User.password'=>"'".$data['User']['password']."'",'User.original_password'=>$data['User']['original_password']),array('User.id'=>$data['User']['id']));

				if($updatedata) 
				{
				  $this->Session->setFlash(__('Your password has been changed successfully.'), 'default', array('class' => 'success'));
				} 
				else 
				{
				  $this->Session->setFlash(__('Password could not be updated. Please, try again.'), 'default', array('class' => 'error'));
				}
				$this->redirect(array('controller' => 'Users', 'action' => 'login','admin'=>true));
			}
		}
  }

  
   public function captcha()
	{
		$this->autoRender = false;
		$this->layout = 'ajax';
		if (!isset($this->Captcha))
		{ //if Component was not loaded throug $components array()
			$this->Captcha = $this->Components->load('Captcha', array(
				'width' => 100,
				'height' => 50,
				'theme' => 'default', //possible values : default, random ; No value means 'default'
			)); //load it
		}
		$this->Captcha->create();
	}

	public function admin_captcha()
	{
		$this->autoRender = false;
		$this->layout = 'ajax';
		if (!isset($this->Captcha))
		{ //if Component was not loaded throug $components array()
			$this->Captcha = $this->Components->load('Captcha', array(
				'width' => 100,
				'height' => 50,
				'theme' => 'default', //possible values : default, random ; No value means 'default'
			)); //load it
		}
		$this->Captcha->create();
	}
}


