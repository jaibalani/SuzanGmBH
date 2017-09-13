<?php
/**
 *
 * PHP 5
 *
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

$cakeDescription = __d(APPLICATION_NAME, APPLICATION_NAME);
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		//Bootstrap core CSS
		echo $this->Html->css('admin/bootstrap');
		//Custom CSS
		echo $this->Html->css('admin/sb-admin.css');
		echo $this->Html->css('font-awesome/css/font-awesome.min');
		echo $this->Html->css('admin/admin-main');
 		echo $this->Html->css('admin/admin_flash');

		//grid CSS
		echo $this->Html->css('admin/jqgrid/ui.jqgrid');
		echo $this->Html->css('admin/jquery/jquery-ui.css');
		
		//custom css
		echo $this->Html->css('admin/custom-admin');
		//Page Specific
		//echo $this->Html->css('admin/morris-0.4.3.min');

		echo $this->Html->script(array(
										'jquery-1.11.1.min',
										'general_validations',
										'jqueryui/jquery-ui-1.10.3.custom',
										'jqgrid/js/i18n/grid.locale-en',
										'jqgrid/js/jquery.jqGrid.min',
										'jqgrid/js/jquery.jqGrid.src',
										'ckeditor/ckeditor',
										'jquery.ui.datepicker',
                                        'picnet.table.filter.min'
            						));
		echo $this->Html->script('bootstrap.min');

		//grid JS
		echo $this->Html->script('jqgrid/js/i18n/grid.locale-en');
		echo $this->Html->script('jqgrid/js/jquery.jqGrid.min');

		
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
  <?php echo $this->element("loading_image");?>	
  <?php 
	$class = '';
	if($this->Session->read('Auth.User.id')){
					$class = "afterlogin";
	}?>
	<div id="wrapper" class="<?php echo $class?>">
		
		<!--Admin left panel Start-->
			<?php echo $this->element('admin_left_panel'); ?>
		<!--Admin left panel end-->
		
      <!-- Sidebar -->
      <?php echo $this->element('admin_header_bar'); ?>

      <div id="page-wrapper">
          
        <?php echo $this->Session->flash(); ?>
				<?php echo $this->fetch('content'); ?>

		<?php //echo $this->element('sql_dump'); ?>

		<div class="clear20"></div>
       
      </div><!-- /#page-wrapper -->

    </div><!-- /#wrapper -->

</body>
</html>


<script type="text/javascript">
//Admin flash Message......
$('#flashMessage').delay(10000).fadeOut('slow');
$("#flashMessage").click( function()
{
  $("#flashMessage").toggle();
});
</script>    
