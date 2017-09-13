<?php ?>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <p class="copyright text-muted small">Copyright &copy; Calling Card 2014. All Rights Reserved</p>
                </div>
                <div class="col-lg-6">
                    <ul class="list-inline pull-right">
                        <li>
											     <a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=>'index','CMS.ABOUT'));?>">
                            <?php  echo __('About');?>
                            </a>
                        </li>
                        <li class="footer-menu-divider">|</li>
                        <li>
										     <a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=>'contactus','CMS.CONTACTUS'));?>">
	                          <?php  echo __('Contact');?>
                         </a>
                        </li>
                        <li class="footer-menu-divider">|</li>
                        <li>
                             <a href="<?php echo $this->Html->url(array('controller'=>'Faqs'));?>"><?php echo __("FAQ's");?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>