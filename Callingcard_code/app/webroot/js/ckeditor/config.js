/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.width = "auto";
  config.height = "auto";
};
CKEDITOR.editorConfig = function( config )
{
	config.toolbar = 'MyToolbar';
 	config.uiColor = '#CCCCCC';
	config.toolbar_MyToolbar =
	[
	//{ name: 'document', items : [ 'Source','-','NewPage','DocProps','Preview','Print','Templates' ] },
	//{ name: 'clipboard', items : [ 'Cut','Undo','Redo' ] },
	//{ name: 'editing', items : [ 'Find','Replace','SelectAll' ] },
	//{ name: 'insert', items : [ 'Image','Table','HorizontalRule','Smiley','SpecialChar'] },
	{ name: 'insert', items : [ 'Image','HorizontalRule','SpecialChar'] },
	//{ name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton','HiddenField' ] },
	'/',
	{ name: 'basicstyles', items : [ 'Source','Bold','Italic','Underline'] },
	{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','JustifyLeft','JustifyBlock'] },	
  { name: 'links', items : [ 'Link','Unlink',] },
	
	{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
	{ name: 'colors', items : [ 'TextColor','BGColor' ] },
	//{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ,'PageBreak','Iframe','Paste','PasteText','PasteFromWord','-', 'Copy','CreateDiv','BidiLtr','BidiRtl''Anchor' , 'Flash','Blockquote'] }
];
};