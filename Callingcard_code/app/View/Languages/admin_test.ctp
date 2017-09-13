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
 * @package       app.View.Layouts.Email.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<?php
/**
  * Mail system developed by Vijay Choudhary.
  */ 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title><?php echo $title_for_layout; ?></title>
</head>
<body>
	<table align="center" width="100%" height="100%" border="0" style="background-color:#dce8f6;font-family:Open Sans;">
	<tr>
    <td align="center" valign="middle">
        <table align="center" width="666px" >
        <tr>
        	<td><div style="clear:both;height:20px"></div></td>
        </tr>
        <tr>
        	<td align="left"> 
        		<a href="<?php $this->Html->Url('/admin');?>"><?php echo Configure::read('Site.title');?></a>
        		<div style="clear:both;height:5px"></div>
        	</td>
        </tr>
        <tr>
        	<td align="center"> 
        		<div align="left" style="border:solid 1px #92B2D8;border-radius:10px;background-color:#FFF;padding:20px" >
        		Hello vijay You have been registered successfully. Please click on the link below to complete your registration process {{link}}
        		</div>
        	</td>
        </tr>
        <tr>
        	<td><div style="clear:both;height:20px"></div></td>
        </tr>
        </table>
	</td>
    </tr>
</table>
</body>
</html>