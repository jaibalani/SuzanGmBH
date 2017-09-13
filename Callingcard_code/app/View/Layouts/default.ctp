<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'E-Comm');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
   <link href='http://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>
	<?php
		echo $this->Html->meta('icon');
		//Bootstrap core CSS
		echo $this->Html->css(array(
										//'jquery.ui.all.css',
										'front',
										'font-awesome/font-awesome',
										'jquery.ui.theme',
										'jquery.ui.core',
										'demos',
										'jquery.ui.datepicker',
										'jqgrid/ui.jqgrid.css',
										'bootstrap.min',
										'jquery/jquery-ui.css',
										'style',

										//Bootstrap core CSS


								   ));
		echo $this->Html->script(array(
										'jquery-1.10.2',
										'general_validations',
										'background.cycle.min',
										'jquery.cycle2.min.js',
										'bootstrap.min',
										'jquery.ui.core',
										'jquery.ui.datepicker',
										'drop_down',
										'jqgrid/js/i18n/grid.locale-en',
										'jqgrid/js/jquery.jqGrid.min',
										'jqgrid/js/jquery.jqGrid.src',
										'ckeditor/ckeditor',
										'custom',
                                        'picnet.table.filter.min'
									));
     
		echo $this->Html->css('fancybox/jquery.fancybox.css?v=2.1.3');
		echo $this->Html->script('fancybox/jquery.fancybox.pack.js?v=2.1.3"');
		
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<?php if($this->request->params['action'] == 'login') {?>
<body class="homepage-bg">
<?php } else { ?>
<body>
<?php echo $this->element("loading_image");?>	
<?php } ?>
   <!--Website Main container start-->
    <?php if($this->request->params['action'] == 'login') {?>
	<div id="main_container" style="background:none !important;">
    <?php } else { ?>
    <div id="main_container">
    <?php } ?>
    	<!--Header Part Start-->	
        <div id="header_content" align="center">
			<?php 
				if($this->Session->read('Auth.User.id'))	
				echo $this->element('header'); 
				else
				echo $this->element('header_home'); 
			?>	
        </div>
         <?php echo $this->Session->flash(); ?>	
		 <?php echo $this->element('message'); ?>
	    <!--Header Part End-->
        <!--Main Content Part Start-->			
		<?php if($this->request->params['action'] == 'login') {?>
        <div id="main_content" align="center" style="background:none;">
        <?php } else{ ?>
        <div id="main_content" align="center">
        <?php } ?>
        
		<?php if($this->request->params['action'] == 'login' || $this->request->params['action'] == 'forget_password' || $this->request->params['action'] == 'verifypass' || $this->request->params['action'] == 'contactus' || ($this->request->params['controller'] == 'Faqs' && $this->request->params['action'] == 'index')|| ($this->request->params['controller'] == 'Pages' && $this->request->params['action'] == 'index')) {?>
         <div id="main" class="front_main" >
         <?php }  else {?>
         <div id="main">
         <?php } ?>
		
        		<div class="float_cleaner"></div>
                <?php if($this->Session->read('Auth.User.id') && ($this->request->params['controller'] != 'Faqs')) {?>
               
                <div id="left_panel" align="left">
                 
				  <?php echo $this->element('left_panel'); ?>	
                </div>
               <?php } ?>
                <?php echo $this->element("loading_image");?>
                <?php echo $this->fetch('content'); ?>
                <div class="float_cleaner"></div>
            </div>
        </div>
       	<!--Footer Part Start-->			
		<div id="footer_content" align="center">
			 <?php 
				 if($this->request->params['action'] == 'login') {
				 echo $this->element('footer_home'); 
				 }else{
				 	echo $this->element('footer');
				 } 
			 ?>		
		</div>		
		<!--Footer Part End-->
    </div>
	<?php //echo $this->element('sql_dump'); ?>
</body>
</html>

<script type="text/javascript">

//Front flash Message......
$('.frontSuccMessage').delay(10000).fadeOut('slow');
$(".frontSuccMessage").click( function()
{
  $(".frontSuccMessage").toggle();
});
$('.frontErrorMessage').delay(10000).fadeOut('slow');
$(".frontErrorMessage").click( function()
{
  $(".frontErrorMessage").toggle();
});

$(document).ready(function(){

	window_width = parseInt($(window).width());
	if(window_width <= 1100){
			$('#main_container').css('display','inline-table');
			$('#footer-section').css('margin-bottom','-12px');
		}else{
			$('#main_container').css('display','block');
			$('#footer-section').css('margin-bottom','0px');
		}
	
	$(window).resize(function () {  
		window_width = parseInt($(window).width());
		if(window_width <= 1100){
			$('#main_container').css('display','inline-table');
			$('#footer-section').css('margin-bottom','-12px');
		}else{
			$('#main_container').css('display','block');
			$('#footer-section').css('margin-bottom','0px');
		}	
   });
	
/*$("#accordian h3").click(function(){
		//slide up all the link lists
		$("#accordian ul ul").slideUp();
		//slide down the link list below the h3 clicked - only if its closed
		if(!$(this).next().is(":visible"))
		{  
			$(this).next().slideDown();
		}
	});
	
	 $('#accordian ul li').click(function(){
		
		if ($(this).children().length > 1) {
			
				if (!$(this).hasClass("active_opt")) { 
				$('#accordian ul li').removeClass('active_opt');
				$(this).addClass('active_opt');
				}else{
					$('#accordian ul li').removeClass('active_opt');
				}
			} 
		
		});
   */
   $("#accordian h3").click(function(){
		//slide up all the link lists
		    $("#accordian ul ul").slideUp();
		    
		    //slide down the link list below the h3 clicked - only if its closed
		    if(!$(this).next().is(":visible"))
		    {  
		        $(this).next().slideDown();
		    }
		    
		    //Check submenu Exist or not
		    if ($(this).siblings().size() > 0){
		        if(!$(this).parent().hasClass("active_opt")){
		            $('#accordian ul li').removeClass('active_opt');
		            $(this).parent().addClass('active_opt');
		        
		        }else{
		            $(this).parent().removeClass('active_opt');
		        }   
		    }
		});	
});

</script>
