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
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cakeDescription = __d('cake_dev', 'My Site');
$controller		=	strtolower($this->params['controller']);
$action			=	strtolower($this->params['action']);
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
    	<?php echo Configure::read('Site.title') ?>:
		<?php echo $title_for_layout; ?>        
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->General->viewmeta();
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
<body>
     <?php echo $this->element("loading_image");?>
    <?php echo $this->fetch('content'); ?>
</body>
</html>