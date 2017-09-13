<?php ?>	
<style type="text/css">
.header_user_account{
    float: left;;
    font-weight: normal;
    font-size: 12px;
    text-transform:none !important;
}    
.user_info_header{
    float: right;
    width: auto !important;
    padding-top: 2.5px;
    margin-right: 30px;
}
#languages{

    font-size: 11px;
}
.dropdown-menu > li > a{
  padding: 3px 4px;
}

</style>		
<div id="header-section">
                <!--Top strip Start-->
    <div id="top-strip">
        <div id="strip-content">
            <div class="left-part">
                <span class="text-size11"><?php echo __('CALLING HOTLINE')?> <!--0698 258 369--><?php echo "<b>".$hotline_number_front."</b>";?></span>
            </div>
            <div class="right-part">
                <div id="languages"> 

                    <p> <?php echo __('Language:')?> <?php echo $this->Html->image(IMAGE_PATH.'img/admin_uploads/flags/'.$languages_flag_default['en'],array('onclick'=>'change_language("en")','style'=>'cursor:pointer;','width'=>'30','height'=>'20','title'=>__('English'))); ?>
                       <?php echo $this->Html->image(IMAGE_PATH.'img/admin_uploads/flags/'.$languages_flag_default['deu'],array('onclick'=>'change_language("deu")','style'=>'cursor:pointer;','width'=>'30','height'=>'20','title'=>__('German'))); ?> </p>

                </div>

                <div class="user_info_header"> 
                       <div class="header_user_account">
                       <?php echo __('Welcome').": <b>".ucwords($this->Session->read('Auth.User.fname')." ".$this->Session->read('Auth.User.lname'))."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;</b>";?>
                       </div>
                       <div class="header_user_account">
                        <?php echo __('Account Number').": <b>".$this->Session->read('Auth.User.username')."</b>";?>
                       </div>
                </div>

            </div>
        </div>
    </div> 
    <!--Top strip End-->
    <div id="header-container">
        <!--Header content Start-->
        <div id="header-content">
            <div class="left-part">
                <div id="logo">
                 <a href="<?php echo Router::url(array('controller'=>'Searches', 'action'=>'online_card')); ?>">
				 <?php echo $this->Html->image("logo.png",array('style'=>'cursor:pointer;')); ?> 
                 </a>
                </div>
            </div>
            <div class="right-part">
                <div id="user_info" style="margin-right:-2px;">
                 <?php if($this->Session->read('Auth.User.id')) {?>
                    <div class="dropdown">
                        <a id="dLabel" data-target="#" href="http://example.com" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                            <div id="user_name">
							    <span class="sb_arrow">
                                     <?php echo $this->Html->image("arrow1.png",array('alt'=>'Arrow')); ?> 
                                </span>
                            </div>
                            <div id="user_img"> 
								<?php 
                                   $image = $this->Session->read('Auth.User.image');
                                    if(file_exists(WWW_ROOT.'img/users/'.$image) && !empty($image))
                                    {
                                        echo $this->Html->image('users/'.$this->Session->read('Auth.User.image'),
                                        array('class'=>'','border'=>'0','div'=>true,'width'=>35,'height'=>'35','style'=>'border-radius:4px;'));
                                    }
                                    else
                                    {
                                        echo $this->Html->image("u_icon.png"); 
                                    }
                               ?>
                           </div>
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel" style="font-size:12px;min-width: 132px !important;">
                           
                            <li><a href="<?php echo Router::url(array('controller'=>'Users', 'action'=>'logout')); ?>"><?php echo __('Logout');?></a></li>
                            <?php if($this->Session->read('Auth.User.distributor_login_mediator') == 1) 
							{
							?>
							<li><a href="<?php echo Router::url(array('controller'=>'Users', 
																	 'action'=>'one_another_redirect',$this->Session->read('Auth.User.admin_id')))?>">
                    			<?php echo __('Distributor Account');?>
                            </a>
                    	  </li>
                          <?php } else if($this->Session->read('Auth.User.distributor_login_retailer') == 1) 
							{
							?>
							<li><a href="<?php echo Router::url(array('controller'=>'Users', 
																	 'action'=>'one_another_redirect',$this->Session->read('Auth.User.admin_id')))?>">
                    			<?php echo __('Distributor Account');?>
                            </a>
                    	  </li>
                          <?php } else if($this->Session->read('Auth.User.mediator_login_retailer') == 1) 
							{
							?>
							<li><a href="<?php echo Router::url(array('controller'=>'Users', 
																	 'action'=>'one_another_redirect',$this->Session->read('Auth.User.admin_id')))?>">
                    			<?php echo __('Mediator Account');?>
                            </a>
                    	  </li>
                          <?php  } ?> 
                          <li><a href="#"></a></li>
                            <!--<li><a href="#">Microfile</a></li>
                            <li><a href="#">Change Password</a></li>-->

                        </ul>
                     </div>
                     <?php } ?>
                </div>
            </div>
        </div>
        <!--Header content Start-->
    </div>
    <div id="latest-news">
        <div id="news-content">
            <div class="transparent-edge_left"></div>
            <marquee scrolldelay="250">
             <?php echo $todays_news_update;?>
            </marquee>
            <div class="transparent-edge_right"></div>
        </div>
    </div>
</div>

<script type="text/javascript">

function change_language(language_code)
{
			  $.ajax({
			    beforeSend: function (XMLHttpRequest) {
					 $("#loading-image").fadeIn();
				},
				complete: function (XMLHttpRequest, textStatus) {
					//$("#loading-image").fadeOut();
				},
				dataType: "html",
				type: "POST",
				evalScripts: true,
				url: "<?php echo $this->Html->Url(array('controller'=>'Users','action'=>'change_language','admin'=>false));?>",
				data: ({language_code:language_code}),
					success: function (data)
					{
						// $("#loading-image").fadeOut();
						 location.reload();
					}
				});
}
$(document).ready(function(){
	$("#loading-image").fadeOut();
});
</script>