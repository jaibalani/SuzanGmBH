<?php 

  $facebook = Configure::read('Site.facebook');
  $twitter = Configure::read('Site.twitter');
  $googleplus = Configure::read('Site.googleplus');
  
  if (strpos($facebook,'http://') === false && strpos($facebook,'https://') === false) 
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
<div id="footer-section">
    <div id="footer">
        <div id="copyright">  <?php echo __('Copyright')?> <?php echo date('Y')?>. <?php echo __('All Rights Reserved')?>. </div>
        <div id="social_links">
            <div>
                <a target="_blank" href="<?php echo $facebook;?>">
                 <?php echo $this->Html->image("fb.png",array('id'=>'fb')); ?>
                </a>
            </div>
            <div>
                <a target="_blank" href="<?php echo $twitter;?>">
                 <?php echo $this->Html->image("twite.png",array('id'=>'twitter')); ?>
                </a>
            </div>
            <div>
                <a target="_blank" href="<?php echo $googleplus;?>">
                 <?php echo $this->Html->image("g+.png",array('id'=>'gplus')); ?>
                </a>
            </div>
        </div>
        <div id="links">
            <ul>

                <li><a href="<?php echo $this->Html->url(array('controller'=>'Faqs'));?>"> <?php echo __('FAQ')?>'<small>s</small> </a> </li>
                <li> <strong>|</strong> </li>
                <li><a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=>'contactus','CMS.CONTACTUS'));?>"> <?php echo __('Contact')?></a></li>
                <li> <strong>|</strong> </li>
                <li> <a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=>'index','CMS.ABOUT'));?>"> <?php echo __('About')?> </a></li>				
            </ul>
        </div>
    </div>
</div>
