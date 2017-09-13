<?php ?>			
			<div id="header">
				<!--Float_cleaner used to clean floating effect on div's-->
				<!--Website Logo start-->
				<div id="logo"><a href="<?php echo Router::url(array('controller'=>'Pages', 'action'=>'dashboard')); ?>"><?php echo $this->Html->image("logo.png"); ?></a></div>
				<!--Website Logo end-->
				<!--Language Converter Start-->
				<div id="user_info" style="cursor:pointer;">
                   <?php if($this->Session->read('Auth.User.id')) {?>
                    <div class="dropdown">
                        <a id="dLabel" data-target="#"  data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                            <div id="user_name"> <strong> Welcome <span class="caret"></span></strong><br/><span><?php echo ucwords($this->Session->read('Auth.User.fname')." ".$this->Session->read('Auth.User.lname'));?></span></div>
                          <div id="user_img">
						   <?php 
						       $image = $this->Session->read('Auth.User.image');
						   		if(file_exists(WWW_ROOT.'img/users/'.$image) && !empty($image))
								{
									echo $this->Html->image('users/'.$this->Session->read('Auth.User.image'),
									array('class'=>'','border'=>'0','div'=>true,'width'=>35,'height'=>'35'));
								}
								else
								{
									echo $this->Html->image("avatar.png"); 
								}
						   ?>
                          </div>
                        </a>
                            
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'logout')); ?>"><i class="icon-power-off"></i> <?php echo __('Logout');?></li>
                            <li><a href="#"></a></li>
                            <!--<li><a href="#">Microfile</a></li>
                            <li><a href="#">Change Password</a></li>-->
                        </ul>
                      </div>
					 <?php } ?>
				</div>
				<div id="languages"> 
					<p> Language: <?php echo $this->Html->image("english.png",array('onclick'=>'change_language("en")','style'=>'cursor:pointer;')); ?><?php echo $this->Html->image("other.png",array('onclick'=>'change_language("deu")','style'=>'cursor:pointer;')); ?> </p>
	
				</div>
				<!--Language Converter End-->
          </div>

<script type="text/javascript">

function change_language(language_code)
{
			  $.ajax({
			    beforeSend: function (XMLHttpRequest) {
					 $("#loading-image").fadeIn();
				},
				complete: function (XMLHttpRequest, textStatus) {
					$("#loading-image").fadeOut();
				},
				dataType: "html",
				type: "POST",
				evalScripts: true,
				url: "<?php echo $this->Html->Url(array('controller'=>'Users','action'=>'change_language','admin'=>false));?>",
				data: ({language_code:language_code}),
				success: function (data)
				{
					 location.reload();
 				}
				});
}
</script>