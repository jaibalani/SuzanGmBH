<style type="text/css">
.cms_page_div{
color:#f8f8f8;
font-size:14px;
}
.front-text{
float:left;
width:100%;

}
</style>   
<script type="text/javascript">

$(function() {
	$("body").backgroundCycle({
		imageUrls: [
			"<?php echo IMAGE_PATH.'img/front_images/'.$image_array[0]['image']; ?>",
			"<?php echo IMAGE_PATH.'img/front_images/'.$image_array[1]['image'];?>",
			"<?php echo IMAGE_PATH.'img/front_images/'.$image_array[2]['image']; ?>",
			"<?php echo IMAGE_PATH.'img/front_images/'.$image_array[3]['image']; ?>"
		],
		fadeSpeed: 0,
		duration: 5000,
		backgroundSize: SCALING_MODE_STRETCH
	});
});
			
</script>

   <div class="intro-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 cms_page_div">
                   <div class="intro-message">
                       <div class="front-text cycle-slideshow" data-cycle-fx="fade"  data-cycle-pager=".example-pager" data-cycle-speed=1000 data-cycle-timeout=5000   data-cycle-slides="> div">
                            <div style="float:left;width:100%" class="front-text first_div">
								<h3 style="width:100%;float:left;" class="front-text"><?php echo $image_array[0]['title']?></h3>
                                <span style="width:100%;float:left;margin-top:15px;"><?php echo $image_array[0]['content']?></span>
                            </div>

                            <div style="float:left;width:100%" class="front-text second_div">
								<h3 style="width:100%;float:left;" class="front-text"><?php echo $image_array[1]['title']?></h3>
                                <span style="width:100%;float:left;margin-top:15px;"><?php echo $image_array[1]['content']?></span>
                            </div>

                            <div style="float:left;width:100%" class= " front-text third_div">
								<h3 style="width:100%;float:left;" class="front-text"><?php echo $image_array[2]['title']?></h3>
                                <span style="width:100%;float:left;margin-top:15px;"><?php echo $image_array[2]['content']?></span>
                            </div>


                            <div style="float:left;width:100%" class="front-text forth_div">
								<h3 style="width:100%;float:left;"><?php echo $image_array[3]['title']?></h3>
                                <span style="width:100%;float:left;margin-top:15px;"><?php echo $image_array[3]['content']?></span>
                            </div>

                        </div>
                     <div class="example-pager"></div>
                    <!--    <div class="home-point"> <i class="fa fa-thumbs-o-up fa-lg"></i> Lorem Ipsum is simply dummy text of the printing. </div>
                        <div class="home-point"> <i class="fa fa-camera-retro fa-lg"></i> Lorem Ipsum is simply dummy text of the printing. </div>
                        <div class="home-point"> <i class="fa fa-briefcase fa-lg"></i> Lorem Ipsum is simply dummy text of the printing. </div>-->
                        
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
                                <span><?php echo __('Remember Me');?></span>
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
                        <h4><?php echo __('Forget password ?');?></h4>
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