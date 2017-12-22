/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
//var KCFINDER_PATH = '/webpros/webpros_sites/webpros/gilletecollege/Site/admin/lib/kcfinder';
var KCFINDER_PATH = 'http://45.55.152.215/dev/landforsale/library/kcfinder'
CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	config.filebrowserBrowseUrl = KCFINDER_PATH + '/browse.php?type=files';
	config.filebrowserImageBrowseUrl = KCFINDER_PATH +'/browse.php?type=images';
	config.filebrowserFlashBrowseUrl = KCFINDER_PATH +'/browse.php?type=flash';
	config.filebrowserUploadUrl = KCFINDER_PATH +'/upload.php?type=files';
	config.filebrowserImageUploadUrl = KCFINDER_PATH +'/upload.php?type=images';
	config.filebrowserFlashUploadUrl = KCFINDER_PATH + '/upload.php?type=flash';

	config.toolbar = 'MyToolbar';
 
	config.toolbar_MyToolbar =
	[
		{ name: 'document', items : [ 'Source', '-' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','Scayt' ] },
		{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','SpecialChar' ] },
                '/',
		{ name: 'styles', items : [ 'Font','FontSize' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
		{ name: 'tools', items : [ 'Maximize','-','About' ] }
	];
};
