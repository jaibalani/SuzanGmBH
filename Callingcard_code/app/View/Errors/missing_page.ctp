<?php ?>

<div class="head_bg">
	<div class="head_center">
    <div class='container'>
       <div class='head-Text'>
					<?php echo __('Page Not Found.');?>
       </div>
    </div>
	</div>
</div>

<div class='mainContent'>
   <div class="container">
      <div class="row">
         <div class="col-lg-4"></div>
         <div class="col-lg-5">
                <?php 
                   echo $this->Html->image("images/page_not_found.jpg",array('class'=>'img-responsive','alt'=>__('Page Not Found'))); 
                ?>
				</div>
        <div class="col-lg-3"></div>
      </div>
   </div>
</div>



																