<?php ?>

<?php if($this->Session->read('Auth.User.id')) { ?>
<style type="text/css">
.mainContent{
width:100% !important; 
padding-top:0px !important;
}
.mainContent p {
font-size: 12px !important;
}
.front_main{
	 width:1170px !important;
 	 padding-top:30px !important;
 }
.container{
width:100% !important;
}
</style>
 
 <?php 

  $facebook = Configure::read('Site.facebook');
  $twitter = Configure::read('Site.twitter');
  $googleplus = Configure::read('Site.googleplus');

   if(strpos($facebook,'http://') === false && strpos($facebook,'https://') === false) 
  {
    $facebook = "https://".$facebook;
  }

  if (strpos($twitter,'http://') === false && strpos($twitter,'https://') === false) 
  {
    $twitter = "https://".$twitter;
  }

  if (strpos($googleplus,'http://') === false && strpos($googleplus,'https://') === false) 
  {
    $googleplus = "https://".$googleplus;
  }

?>
 <div class="right-part right-panel">
    <!--Compose Start-->
        <div class="sb-page-title">
            <strong><?php echo __('Contact Us'); ?></strong>
        </div>
        <div id="contact-content">
          <div id="sb-contact-info">
            <div class="contact-info left-part">
                <div><?php echo __('Calling Card Address');?></div>
                <p><?php if($Address) echo $Address;?></p>
                <p><?php if($phone) echo "Phone:"." ".$phone;?></p>
                <p><?php if($fax)echo "Fax:"." ".$fax;?></p>
                <p><?php if($site_email)	echo "Email:"." ".$site_email;?></p>
            </div>
            <div class="social-info right-part">
                <div><?php echo  __('Social Network Profile');?></div>
                <p><?php echo $this->html->image('f.png',array('id'=>'contact-fb')); ?><a target="_blank" href="<?php echo $facebook;?>"><?php echo Configure::read('Site.facebook');?></a></p>
                <p><?php echo $this->html->image('t.png',array('id'=>'contact-twitter')); ?><a target="_blank" href="<?php echo $twitter;?>"><?php echo Configure::read('Site.twitter');?></a></p>
                <p><?php echo $this->html->image('g.png',array('id'=>'contact-gp')); ?><a target="_blank" href="<?php echo $googleplus;;?>"><?php echo Configure::read('Site.googleplus');?></a></p>
            </div>
        </div>
        <div id="sb-contact-form">
            <div class="contact-form-title">
                <?php echo __('Get in Touch');?>
            </div>
            <p>
               <?php echo $content['CmsLanguage']['content'];?>
            </p>

     	<?php echo $this->Form->create('Contactus'); ?>
            <div id="contact-form1">
                <div class="contact-inputs">
					<?php
						echo $this->Form->input('name',array('label'=>false,
															'placeholder'=>__('Full Name'),
															'value'=>$name,
															'readonly'=>'readonly',
															'class'=>'half-length-input',
															'div'=>false					 
												));
					  	echo $this->Form->input('email',array('label'=>false,
															'placeholder'=>__('Email Address'),
															'value' =>$email,
															'readonly'=>'readonly',
															'class'=>'half-length-input',
															'div'=>false					 
											   ));  
					?>
                </div>

                <div class="contact-inputs">
                 <?php 
					  echo $this->Form->input('subject',array('label'=>false,
												'placeholder'=>__('Subject'),
												'class'=>'full-length-input',
                        'value' =>@$subject,
												'required'=>'required',
												)); 
				 ?>
                </div>
                <div class="contact-inputs">
            		<?php 
					echo $this->Form->input('message',array('label'=>false,
							'placeholder'=>__('Message'),
							'class'=>'full-length-input',
							'type'=>'textarea',
              'value' =>@$message,
							'required'=>'required',
						)); 
				    ?>
                </div>
                <div class="contact-inputs">
	           		<?php echo $this->Form->submit(__('GET IN TOUCH'),array('class'=>'button-gradient')); ?>
                </div>
            </div>    
        </div>
 </div>
 <!--Compose End-->
</div>

<?php } else { ?>
<style type="text/css">
  #footer-section{
    margin-top: 0px !important;
  }
  .mainContent p{
    font-size: 12px;
  }
</style>
<div id="right_panel" style="width:100%;margin-bottom:0px;">
    <div class="head_bg">
        <div class="head_center">
        <div class='container'>
           <div class='head-Text' style="float:left;">
                <?php echo $content['CmsLanguage']['title'];?>
           </div>
        </div>
        </div>
    </div>

	<div class='mainContent'>
	    <div class="container">
		     <div class="row" >
                <div class="col-xs-8">
                  <div class="contact-upper">
                    <div class='row' >
                      <div class="col-xs-12">
                        <h3 class="page-subHeading"><?php echo __('Get In Touch');?></h3>
                 		 <?php echo $content['CmsLanguage']['content'];?>
              			</div>
            		</div>
         		   </div>

          <div class='contact-form'>
			 <?php echo $this->Form->create('Contactus'); ?>
               <div class='row'>
            	<div class="col-xs-6">
           			 <?php 
						echo $this->Form->input('name',array('label'=>false,
															'placeholder'=>__('Full Name'),
															)); 
  					 ?>
              </div>
            	<div class="col-xs-6">
            		<?php 
						echo $this->Form->input('email',array('label'=>false,
												'placeholder'=>__('Email Address'),
											 )); 
						?>
            	</div>
            </div>
            <div class='row'>
            	<div class="col-xs-12">
           		 <?php 
					  echo $this->Form->input('subject',array('label'=>false,
											'placeholder'=>__('Subject'),
						)); 
				  ?>
            	</div>
            </div>
            <div class='row'>
            	<div class="col-xs-12">
            		<?php 
						   echo $this->Form->input('message',array('label'=>false,
						  'placeholder'=>__('Message'),
						  'type'=>'textarea')); 
				   ?>
            	</div>
            </div>
            <div class='row'>
            	<div class="col-xs-12">
            		<?php echo $this->Form->submit(__('Get In Touch'),array('class'=>'contact-submit')); ?>
            	</div>
            </div>
            <?php echo $this->Form->end(); ?>
          </div>
      </div>

      <div class="col-xs-4">
			<?php echo $this->html->image('location.png'); ?>
	        <h3 class="page-subHeading contact-r-heading"><?php echo __('Calling Card Address');?></h3>
            <p>
          <?php 
					if($Address)
					echo $Address;
			?>
          </p>
          
          <p>
          <?php 
				if($phone)
				echo __("Phone:")." ".$phone;
		  ?>
          </p>

          <p>
          <?php 
				if($fax)
				echo __("Fax:")." ".$fax;
		  ?>
          </p>
          
          <p>
          <?php 
				if($site_email)
				echo "Email:"." ".$site_email;
		  ?>
          </p>
        
       <div class='contact-r-heading'>
        	<h3 class="page-subHeading"><?php echo __('Social Network Profile');?></h3>
        	<div class="contact-social"> <i class="fa fa-facebook fa-lg fbcolor"></i><a target="_blank" href="<?php echo Configure::read('Site.facebook')?>"><?php echo Configure::read('Site.facebook')?></a></div>
        	<div class="contact-social"> <i class="fa fa-twitter fa-lg twcolor"></i><a target="_blank" href="<?php echo Configure::read('Site.twitter')?>"><?php echo Configure::read('Site.twitter')?></a></div>
        	<div class="contact-social"> <i class="fa fa-google-plus fa-lg gpcolor"></i><a target="_blank" href="<?php echo Configure::read('Site.googleplus')?>"><?php echo Configure::read('Site.googleplus')?></a></div>
        </div>
      </div>
		</div>
	</div>
</div>
</div>
<?php } ?>
<script type="text/javascript">
$(document).ready(function(){
      $('.container').css('width','1000px');      
});
$(window).load(function(){
    $('#main_container').css('display','block');
  });
</script>