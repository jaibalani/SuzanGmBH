<?php ?>
<div id="footer">
    <div id="copyright"> <strong>&copy;</strong>  Copyright 2014. All Rights Reserved. </div>
    <div id="social_links">
        
        <div> <?php echo $this->Html->image("fb.png",array('id'=>'fb')); ?> </div>
        <div>  <?php echo $this->Html->image("twite.png",array('id'=>'twitter')); ?> </div>
        <div> <?php echo $this->Html->image("g+.png",array('id'=>'gplus')); ?> </div>

    </div>
    <div id="links">
        <ul>
            <li>
            	<a href="<?php echo $this->Html->url(array('controller'=>'Faqs'));?>"><?php echo __("FAQ's");?></a>
            </li> 
			<li> <strong>|</strong> </li>
            <li>
            	<a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=>'contactus','CMS.CONTACTUS'));?>">
	            	<?php  echo __('Contact');?>
                </a>
            </li>
            <li> <strong>|</strong> </li>
            <li>  
            	<a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=>'index','CMS.ABOUT'));?>">
                   <?php  echo __('About');?>
                </a>
           </li>				
        </ul>
	</div>
</div>