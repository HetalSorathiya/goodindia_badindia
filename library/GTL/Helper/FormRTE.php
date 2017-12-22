<?php 
/** 
 * Helper to generate a "textarea" element 
 * FCKeditor/CKEditor PHP class
 * @category   Zend 
 * @package    Zend_View 
 * @subpackage Helper 
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com) 
 * @license    http://framework.zend.com/license/new-bsd     New BSD License 
 */ 
class GTL_Helper_FormRTE extends Zend_View_Helper_FormElement 
{ 
    /** 
     * Generates a richtext element using FCKeditor. 
     * 
     * @access public 
     * 
     * @param string|array $name If a string, the element name.  If an 
     * array, all other parameters are ignored, and the array elements 
     * are extracted in place of added parameters. 
     * 
     * @param mixed $value The element value. 
     * 
     * @param array $attribs Attributes for the element tag. 
     * 
     * @return string The element XHTML. 
     */ 
    public function formRTE1($name = null, $value = null, $attribs = null) 
    { 
        require_once APPLICATION_PATH . '/../public/js/fckeditor/fckeditor.php';
    	if(is_null($name) && is_null($value) && is_null($attribs)) { 
            return $this; 
        } 
        $info = $this->_getInfo($name, $value, $attribs); 
        extract($info); // name, value, attribs, options, listsep, disable 
        $editor = new FCKeditor($name);
		
		$domainName = Zend_Registry::get('domainName');
        // set variables
        $editor->BasePath   = $domainName . '/public/js/fckeditor/' ; 
        $editor->ToolbarSet = empty($attribs['ToolbarSet']) ? 'Artist' : $attribs['ToolbarSet'];        $editor->Width      = empty($attribs['Width']) ? '90%' : $attribs['Width']; 
        $editor->Height     = empty($attribs['Height']) ? 500 : $attribs['Height']; 
        $editor->Value      = $value; 
         
        // set Config  
        $editor->Config['BaseHref'] = $editor->BasePath;
        $editor->Config['CustomConfigurationsPath'] = $editor->BasePath.'editor/fckconfig.js'; 
        $editor->Config['SkinPath'] = $editor->BasePath.'editor/skins/office2003/';   		//silver/';         
        return $editor->createHtml(); 
    } 
    /**
     * Generates a richtext element using CKeditor. 
     *  
     * @param $name
     * @param $value
     * @param $attribs
     * @return xtml output
     */
    public function formRTE($name = null, $value = "", $attribs = null) 
    { 
        require_once APPLICATION_PATH . '/../public/js/ckeditor/ckeditor.php';
        include_once APPLICATION_PATH . '/../public/js/ckfinder/ckfinder.php';
        
    	if(is_null($name) && is_null($value) && is_null($attribs)) { 
            return $this; 
        } 
        $Height     = empty($attribs['height']) ? 500 : $attribs['height'];
        $Width      = empty($attribs['width']) ? 400 : $attribs['width'];    
        $info = $this->_getInfo($name, $value, $attribs); 
        extract($info); // name, value, attribs, options, listsep, disable 
		/*$config['toolbar'] = array(
	      array( 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike' ),
	     array( 'Image', 'Link', 'Unlink', 'Anchor' )
	 	 ); */
	 	 //full toolbar set of ckeditor
	 	 $config1['toolbar'] = 
			array(
			   array('Source','-','Save','NewPage','Preview','-','Templates'),
			    array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'),
			    array('Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'),
			    array('Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'),
			    '/',
			    array('Bold','Italic','Underline','Strike','-','Subscript','Superscript'),
			     array('NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'),
			     array('JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'),
			   array('BidiLtr', 'BidiRtl' ),
			     array('Link','Unlink','Anchor'),
			    array('Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'),
			    '/',
			    array('Styles','Format','Font','FontSize'),
			     array('TextColor','BGColor'),
			    array('Maximize', 'ShowBlocks','-','About')
			);
	 	 $config['toolbar'] = 
			array(
			    array('Source','-','Save','NewPage','Preview','-','Templates'),
			    array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'),
			    array('Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'),
			    '/',
			    array('Bold','Italic','Underline','Strike','-','Subscript','Superscript'),
			    array('NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'),
			    array('JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'),
			  	array('BidiLtr', 'BidiRtl' ),
			    array('Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'),
			    '/',
			    array('Styles','Format','Font','FontSize'),
			    array('TextColor','BGColor'),
			    array('Maximize', 'ShowBlocks','-','About'),
			    array('Link','Unlink','Anchor')
			);
        //$editor = new FCKeditor($name);
		$CKEditor = new CKEditor();
		$CKEditor->basePath   = $domainName . '/public/js/ckeditor/' ;
		$domainName = Zend_Registry::get('domainName');
		CKFinder::SetupCKEditor($CKEditor, '/ckfinder/');
		$CKEditor->config['height'] = $Height;
		$CKEditor->config['width'] = $Width;
		$CKEditor->config['background-color'] = '#000000';
		$CKEditor->config['filebrowserBrowseUrl'] = '/public/js/ckfinder/ckfinder.html';
		$CKEditor->config['filebrowserImageBrowseUrl'] = '/public/js/ckfinder/ckfinder.html?type=Images';
		$CKEditor->config['filebrowserFlashBrowseUrl'] = '/public/js/ckfinder/ckfinder.html?type=Flash';
		$CKEditor->config['filebrowserUploadUrl'] = '/public/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
		$CKEditor->config['filebrowserImageUploadUrl'] = '/public/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
		$CKEditor->config['filebrowserFlashUploadUrl'] = '/public/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
		
	/*filebrowserBrowseUrl :'js/ckeditor/filemanager/browser/default/browser.html?Connector=http://www.mixedwaves.com/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php',
      filebrowserImageBrowseUrl : 'js/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=http://www.mixedwaves.com/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php',
      filebrowserFlashBrowseUrl :'js/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=http://www.mixedwaves.com/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php'} */
		
		
		
		
		$CKEditor->returnOutput = true;
		$code = $CKEditor->editor($name, $value,$config);
		return $code;
    } 
         
}
