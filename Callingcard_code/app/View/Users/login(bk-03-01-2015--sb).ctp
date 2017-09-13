<style type="text/css">
.cms_page_div{
color:#f8f8f8;
font-size:14px;
}
.home-point{
font-size:13px;
padding-bottom:20px;
}
#second,#third,#fourth{
display : none;
}
.homepage-bg{
  background: url("<?php echo IMAGE_PATH.'img/front_images/'.$image_array[0]['image']; ?>") no-repeat center center fixed !important; 
 -webkit-background-size: cover;
 -moz-background-size: cover;
 -o-background-size: cover;
 background-size: cover;
    

}
</style> 
  
<script type="text/javascript">
counter = 0;
function  change_background_text()
{ 
	$("body").backgroundCycle({
			imageUrls: [
			"<?php echo IMAGE_PATH.'img/front_images/'.$image_array[0]['image']; ?>",
			"<?php echo IMAGE_PATH.'img/front_images/'.$image_array[1]['image']; ?>",
			"<?php echo IMAGE_PATH.'img/front_images/'.$image_array[2]['image']; ?>",
			"<?php echo IMAGE_PATH.'img/front_images/'.$image_array[3]['image']; ?>"                        
			],
			fadeSpeed: 1000,
			duration: 4000,
			backgroundSize: SCALING_MODE_COVER
		});

	 setInterval(function(){
		  if(counter == 4)
		  counter =0;
		  if(counter == 0)
		  {
		  	$('#first').css('display','block');
			$('#second,#third,#fourth').css('display','none'); 
		  }
		  else if(counter == 1)
		  {
		  	$('#second').css('display','block');
			$('#first,#third,#fourth').css('display','none'); 
		  }
		  else if(counter == 2)
		  {
		  	$('#third').css('display','block');
			$('#first,#second,#fourth').css('display','none'); 
		  }
		  else if(counter == 3)
		  {
		  	$('#fourth').css('display','block');
			$('#first,#second,#third').css('display','none'); 
		  }
          counter++;
	 }, 4000);
}
$(document).load(function() {
change_background_text();
});
</script>

   <div class="intro-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 cms_page_div">
                    <div class="intro-message">
                        <h3 class="front-text"><?php echo $content['CmsLanguage']['title'];?></h3>
                        <?php foreach($cms_image as $rec){?>
                      	<?php 
                        	echo $this->Html->image("admin_uploads/cms_uploads/".$rec['CmsImage']['image'], 
																																		array('border' =>'0','width'=>'1000')); 
                        	}
                      	?>
                        <p class="front-text">
							<?php echo $content['CmsLanguage']['content'];?>                           
                        </p>
                        
                        <div class="home-point"  data-cycle-fx="fade"  data-cycle-pager=".example-pager" data-cycle-speed=1000  data-cycle-delay = 1000 data-cycle-timeout=4000   data-cycle-slides="> span" >
                           <span style="width:100%;float:left;" id="first" ><?php echo $image_array[0]['content']?></span>
                           <span style="width:100%;float:left;" id="second"><?php echo $image_array[1]['content']?></span>
                           <span style="width:100%;float:left;" id="third"><?php echo $image_array[2]['content']?></span>
                           <span style="width:100%;float:left;" id="fourth"><?php echo $image_array[3]['content']?></span>
                        </div>

                        <div class="home-social">
                        		 <a target="_blank" href="<?php echo Configure::read('Site.facebook')?>"><i class="fa fa-facebook fa-lg"></i></a>
                            	<a target="_blank" href="<?php echo Configure::read('Site.twitter')?>"><i class="fa fa-twitter fa-lg"></i></a>
                            	<a target="_blank" href="<?php echo Configure::read('Site.googleplus')?>"><i class="fa fa-google-plus fa-lg"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-5">
                    <div class="loginBox">
                        <div class="innerBox">
                        <h2><?php echo __('Welcome to Calling Card');?></h2>
                        <p><?php echo __('Please Sign in to get access');?></p>
                        <div class="loginform">
                            <?php echo $this->Form->create('User'); ?>
                            <?php echo $this->Form->input('email',array('label'=>false,'placeholder'=>__('Email Address'),'required'=>true,'value'=>isset($cookie_email)?$cookie_email:'')); ?>
                            <?php echo $this->Form->input('password',array('label'=>false,'placeholder'=>__('Password'),'required'=>true,'value'=>isset($cookie_pass)?$cookie_pass:'')); ?>
                            <div class="input remember">
                                <span><?php echo __('Remember Me ?');?></span>
                            <div class="switch">
                                <input id="cmn-toggle-1" class="cmn-toggle cmn-toggle-round" type="checkbox" name="remember_me">
                                <label for="cmn-toggle-1"></label>
                            </div>
                            </div>
                            <?php //echo $this->Form->input('remember',array('type'=>'checkbox','label' => 'Remember Me','hidden'=>false));?>
                            <?php echo $this->Form->submit(__('LOGIN'),array('class'=>'social-Btn login')); ?>
                            <?php echo $this->Form->end(); ?>
                           
                        </div> </div>
                        <!--<div class="sociallogin">
                                <a href="" class="social-Btn google"><?php //echo __('Sign in with Google+');?></a>
                                <a href="" class="social-Btn facebook"><?php //echo __('Sign in with Facebook');?></a>
                        </div>
-->                        
                    </div>
                    
                    <div class="forgetBox">
                        <h4><?php echo __('Forgot password ?');?></h4>
                        <p>
                          <a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'forget_password'));?>">
													<?php echo __('Click Here')." ";?></a><?php echo __('to get new password.')?>
                        </p>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container -->

    </div>
    <!-- /.intro-header -->