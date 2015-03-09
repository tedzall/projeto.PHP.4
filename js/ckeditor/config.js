/*
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config ) 
{

	config.height = 500;
	config.toolbar = "MyToolBar";
	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbar_MyToolBar = [
		{ name: 'document',    items : [ 'Source'] },
		{ name: 'insert',  items: ['HorizontalRule','SpecialChar']},
		{ name: 'links',	items: ['Link','Unlink']},
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
		{ name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
		{ name: 'styles',	items: ['Styles','Format','Font','FontSize'] },
		{ name: 'colors', items: ['TextColor', 'BGColor'] }
	];

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';
};

