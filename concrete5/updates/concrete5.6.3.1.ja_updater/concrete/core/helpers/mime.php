<?php
/**
 * @package Helpers
 * @category Concrete
 * @author Jeremy Logan <jeremy.logan@gmail.com>
 * @copyright  Copyright (c) 2003-2008 Concrete5. (http://www.concrete5.org)
 * @license    http://www.concrete5.org/license/     MIT License
 */

/**
 * Functions useful functions for working with mime-types.
 * @package Helpers
 * @category Concrete
 * @author Jeremy Logan <jeremy.logan@gmail.com>
 * @copyright  Copyright (c) 2003-2008 Concrete5. (http://www.concrete5.org)
 * @license    http://www.concrete5.org/license/     MIT License
 */

defined('C5_EXECUTE') or die("Access Denied.");

class Concrete5_Helper_Mime {

	static $mime_types_and_extensions = array(
			'atom' => 'application/atom+xml',
			'hqx' => 'application/mac-binhex40',
			'mathml' => 'application/mathml+xml',
			'doc' => 'application/msword',
			'oda' => 'application/oda',
			'ogx' => 'application/ogg',
			'pdf' => 'application/pdf',
			'ps' => 'application/postscript',
			'rdf' => 'application/rdf+xml',
			'smil' => 'application/smil',
			'dxr' => 'application/x-director',
			'dvi' => 'application/x-dvi',
			'spl' => 'application/x-futuresplash',
			'js' => 'application/x-javascript',
			'latex' => 'application/x-latex',
			'swf' => 'application/x-shockwave-flash',
			'sit' => 'application/x-stuffit',
			'tar' => 'application/x-tar',
			'tex' => 'application/x-tex',
			'texinfo' => 'application/x-texinfo',
			'xhtml' => 'application/xhtml+xml',
			'xsl' => 'application/xml',
			'dtd' => 'application/xml-dtd',
			'xslt' => 'application/xslt+xml',
			'zip' => 'application/zip',
			'midi' => 'audio/midi',
			'm4p' => 'audio/mp4a-latm',
			'mpga' => 'audio/mpeg',
			'aiff' => 'audio/x-aiff',
			'm3u' => 'audio/x-mpegurl',
			'ram' => 'audio/x-pn-realaudio',
			'wav' => 'audio/x-wav',
			'ogg' => 'audio/ogg',
			'oga' => 'audio/ogg',
			'pdb' => 'chemical/x-pdb',
			'xyz' => 'chemical/x-xyz',
			'bmp' => 'image/bmp',
			'cgm' => 'image/cgm',
			'gif' => 'image/gif',
			'ief' => 'image/ief',
			'jp2' => 'image/jp2',
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'pict' => 'image/pict',
			'png' => 'image/png',
			'svg' => 'image/svg+xml',
			'tiff' => 'image/tiff',
			'djvu' => 'image/vnd.djvu',
			'wbmp' => 'image/vnd.wap.wbmp',
			'ras' => 'image/x-cmu-raster',
			'ico' => 'image/x-icon',
			'pntg' => 'image/x-macpaint',
			'pnm' => 'image/x-portable-anymap',
			'pbm' => 'image/x-portable-bitmap',
			'pgm' => 'image/x-portable-graymap',
			'ppm' => 'image/x-portable-pixmap',
			'qtif' => 'image/x-quicktime',
			'xbm' => 'image/x-xbitmap',
			'xpm' => 'image/x-xpixmap',
			'wrl' => 'model/vrml',
			'css' => 'text/css',
			'html' => 'text/html',
			'txt' => 'text/plain',
			'rtx' => 'text/richtext',
			'rtf' => 'text/rtf',
			'sgml' => 'text/sgml',
			'mp4' => 'video/mp4',
			'mpg' => 'video/mpeg',
			'qt' => 'video/quicktime',
			'm4v' => 'video/x-m4v',
			'avi' => 'video/x-msvideo',
			'ogv' => 'video/ogg',
			'webm' => 'video/webm',
			'wmv' => 'video/x-ms-wmv'
	);

	/**
	 * Converts a file extension into a mime type
	 * @param string $ext
	 * @return string|boolean mime type string or false
	 */
	public function mimeFromExtension($ext) {
		$ext = strtolower($ext);
		if (array_key_exists($ext, MimeHelper::$mime_types_and_extensions)) {
			return MimeHelper::$mime_types_and_extensions[$ext];
		}
		return false;
	}
	
	/** 
	 * Converts a known mime-type into it's common file extension. 
	 * Returns the first match from $mime_types_and_extensions
	 * @param string $mimeType
	 * @return string|boolean extension string or false
	 */
	public function mimeToExtension($mimeType) {
		$mimeType = strtolower($mimeType);
		$mime = array_search($mimeType, MimeHelper::$mime_types_and_extensions);
		return $mime;
	}
	
}
?>
