<?php
/**
 * Simulation of Microsoft Internet Explorer's MIME type detection algorithm.
 *
 * @file
 * @todo Define the exact license of this file.
 */

/**
 * This class simulates Microsoft Internet Explorer's terribly broken and
 * insecure MIME type detection algorithm. It can be used to check web uploads
 * with an apparently safe type, to see if IE will reinterpret them to produce
 * something dangerous.
 *
 * It is full of bugs and strange design choices should not under any
 * circumstances be used to determine a MIME type to present to a user or
 * client. (Apple Safari developers, this means you too.)
 *
 * This class is based on a disassembly of IE 5.0, 6.0 and 7.0. Although I have
 * attempted to ensure that this code works in exactly the same way as Internet
 * Explorer, it does not share any source code, or creative choices such as
 * variable names, thus I (Tim Starling) claim copyright on it.
 *
 * It may be redistributed without restriction. To aid reuse, this class does
 * not depend on any MediaWiki module.
 */
class IEContentAnalyzer {
	/**
	 * Relevant data taken from the type table in IE 5
	 */
	protected $baseTypeTable = array(
		'ambiguous' /*1*/ => array(
			'text/plain',
			'application/octet-stream',
			'application/x-netcdf', // [sic]
		),
		'text' /*3*/ => array(
			'text/richtext', 'image/x-bitmap', 'application/postscript', 'application/base64',
			'application/macbinhex40', 'application/x-cdf', 'text/scriptlet'
		),
		'binary' /*4*/ => array(
			'application/pdf', 'audio/x-aiff', 'audio/basic', 'audio/wav', 'image/gif',
			'image/pjpeg', 'image/jpeg', 'image/tiff', 'image/x-png', 'image/png', 'image/bmp',
			'image/x-jg', 'image/x-art', 'image/x-emf', 'image/x-wmf', 'video/avi',
			'video/x-msvideo', 'video/mpeg', 'application/x-compressed',
			'application/x-zip-compressed', 'application/x-gzip-compressed', 'application/java',
			'application/x-msdownload'
		),
		'html' /*5*/ => array( 'text/html' ),
	);

	/**
	 * Changes to the type table in later versions of IE
	 */
	protected $addedTypes = array(
		'ie07' => array(
			'text' => array( 'text/xml', 'application/xml' )
		),
	);

	/**
	 * An approximation of the "Content Type" values in HKEY_CLASSES_ROOT in a
	 * typical Windows installation.
	 *
	 * Used for extension to MIME type mapping if detection fails.
	 */
	protected $registry = array(
		'.323' => 'text/h323',
		'.3g2' => 'video/3gpp2',
		'.3gp' => 'video/3gpp',
		'.3gp2' => 'video/3gpp2',
		'.3gpp' => 'video/3gpp',
		'.aac' => 'audio/aac',
		'.ac3' => 'audio/ac3',
		'.accda' => 'application/msaccess',
		'.accdb' => 'application/msaccess',
		'.accdc' => 'application/msaccess',
		'.accde' => 'application/msaccess',
		'.accdr' => 'application/msaccess',
		'.accdt' => 'application/msaccess',
		'.ade' => 'application/msaccess',
		'.adp' => 'application/msaccess',
		'.adts' => 'audio/aac',
		'.ai' => 'application/postscript',
		'.aif' => 'audio/aiff',
		'.aifc' => 'audio/aiff',
		'.aiff' => 'audio/aiff',
		'.amc' => 'application/x-mpeg',
		'.application' => 'application/x-ms-application',
		'.asf' => 'video/x-ms-asf',
		'.asx' => 'video/x-ms-asf',
		'.au' => 'audio/basic',
		'.avi' => 'video/avi',
		'.bmp' => 'image/bmp',
		'.caf' => 'audio/x-caf',
		'.cat' => 'application/vnd.ms-pki.seccat',
		'.cbo' => 'application/sha',
		'.cdda' => 'audio/aiff',
		'.cer' => 'application/x-x509-ca-cert',
		'.conf' => 'text/plain',
		'.crl' => 'application/pkix-crl',
		'.crt' => 'application/x-x509-ca-cert',
		'.css' => 'text/css',
		'.csv' => 'application/vnd.ms-excel',
		'.der' => 'application/x-x509-ca-cert',
		'.dib' => 'image/bmp',
		'.dif' => 'video/x-dv',
		'.dll' => 'application/x-msdownload',
		'.doc' => 'application/msword',
		'.docm' => 'application/vnd.ms-word.document.macroEnabled.12',
		'.docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'.dot' => 'application/msword',
		'.dotm' => 'application/vnd.ms-word.template.macroEnabled.12',
		'.dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
		'.dv' => 'video/x-dv',
		'.dwfx' => 'model/vnd.dwfx+xps',
		'.edn' => 'application/vnd.adobe.edn',
		'.eml' => 'message/rfc822',
		'.eps' => 'application/postscript',
		'.etd' => 'application/x-ebx',
		'.exe' => 'application/x-msdownload',
		'.fdf' => 'application/vnd.fdf',
		'.fif' => 'application/fractals',
		'.gif' => 'image/gif',
		'.gsm' => 'audio/x-gsm',
		'.hqx' => 'application/mac-binhex40',
		'.hta' => 'application/hta',
		'.htc' => 'text/x-component',
		'.htm' => 'text/html',
		'.html' => 'text/html',
		'.htt' => 'text/webviewhtml',
		'.hxa' => 'application/xml',
		'.hxc' => 'application/xml',
		'.hxd' => 'application/octet-stream',
		'.hxe' => 'application/xml',
		'.hxf' => 'application/xml',
		'.hxh' => 'application/octet-stream',
		'.hxi' => 'application/octet-stream',
		'.hxk' => 'application/xml',
		'.hxq' => 'application/octet-stream',
		'.hxr' => 'application/octet-stream',
		'.hxs' => 'application/octet-stream',
		'.hxt' => 'application/xml',
		'.hxv' => 'application/xml',
		'.hxw' => 'application/octet-stream',
		'.ico' => 'image/x-icon',
		'.iii' => 'application/x-iphone',
		'.ins' => 'application/x-internet-signup',
		'.iqy' => 'text/x-ms-iqy',
		'.isp' => 'application/x-internet-signup',
		'.jfif' => 'image/jpeg',
		'.jnlp' => 'application/x-java-jnlp-file',
		'.jpe' => 'image/jpeg',
		'.jpeg' => 'image/jpeg',
		'.jpg' => 'image/jpeg',
		'.jtx' => 'application/x-jtx+xps',
		'.latex' => 'application/x-latex',
		'.log' => 'text/plain',
		'.m1v' => 'video/mpeg',
		'.m2v' => 'video/mpeg',
		'.m3u' => 'audio/x-mpegurl',
		'.mac' => 'image/x-macpaint',
		'.man' => 'application/x-troff-man',
		'.mda' => 'application/msaccess',
		'.mdb' => 'application/msaccess',
		'.mde' => 'application/msaccess',
		'.mfp' => 'application/x-shockwave-flash',
		'.mht' => 'message/rfc822',
		'.mhtml' => 'message/rfc822',
		'.mid' => 'audio/mid',
		'.midi' => 'audio/mid',
		'.mod' => 'video/mpeg',
		'.mov' => 'video/quicktime',
		'.mp2' => 'video/mpeg',
		'.mp2v' => 'video/mpeg',
		'.mp3' => 'audio/mpeg',
		'.mp4' => 'video/mp4',
		'.mpa' => 'video/mpeg',
		'.mpe' => 'video/mpeg',
		'.mpeg' => 'video/mpeg',
		'.mpf' => 'application/vnd.ms-mediapackage',
		'.mpg' => 'video/mpeg',
		'.mpv2' => 'video/mpeg',
		'.mqv' => 'video/quicktime',
		'.NMW' => 'application/nmwb',
		'.nws' => 'message/rfc822',
		'.odc' => 'text/x-ms-odc',
		'.ols' => 'application/vnd.ms-publisher',
		'.p10' => 'application/pkcs10',
		'.p12' => 'application/x-pkcs12',
		'.p7b' => 'application/x-pkcs7-certificates',
		'.p7c' => 'application/pkcs7-mime',
		'.p7m' => 'application/pkcs7-mime',
		'.p7r' => 'application/x-pkcs7-certreqresp',
		'.p7s' => 'application/pkcs7-signature',
		'.pct' => 'image/pict',
		'.pdf' => 'application/pdf',
		'.pdx' => 'application/vnd.adobe.pdx',
		'.pfx' => 'application/x-pkcs12',
		'.pic' => 'image/pict',
		'.pict' => 'image/pict',
		'.pinstall' => 'application/x-picasa-detect',
		'.pko' => 'application/vnd.ms-pki.pko',
		'.png' => 'image/png',
		'.pnt' => 'image/x-macpaint',
		'.pntg' => 'image/x-macpaint',
		'.pot' => 'application/vnd.ms-powerpoint',
		'.potm' => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
		'.potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
		'.ppa' => 'application/vnd.ms-powerpoint',
		'.ppam' => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
		'.pps' => 'application/vnd.ms-powerpoint',
		'.ppsm' => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
		'.ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		'.ppt' => 'application/vnd.ms-powerpoint',
		'.pptm' => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
		'.pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'.prf' => 'application/pics-rules',
		'.ps' => 'application/postscript',
		'.pub' => 'application/vnd.ms-publisher',
		'.pwz' => 'application/vnd.ms-powerpoint',
		'.py' => 'text/plain',
		'.pyw' => 'text/plain',
		'.qht' => 'text/x-html-insertion',
		'.qhtm' => 'text/x-html-insertion',
		'.qt' => 'video/quicktime',
		'.qti' => 'image/x-quicktime',
		'.qtif' => 'image/x-quicktime',
		'.qtl' => 'application/x-quicktimeplayer',
		'.rat' => 'application/rat-file',
		'.rmf' => 'application/vnd.adobe.rmf',
		'.rmi' => 'audio/mid',
		'.rqy' => 'text/x-ms-rqy',
		'.rtf' => 'application/msword',
		'.sct' => 'text/scriptlet',
		'.sd2' => 'audio/x-sd2',
		'.sdp' => 'application/sdp',
		'.shtml' => 'text/html',
		'.sit' => 'application/x-stuffit',
		'.sldm' => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
		'.sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
		'.slk' => 'application/vnd.ms-excel',
		'.snd' => 'audio/basic',
		'.so' => 'application/x-apachemodule',
		'.sol' => 'text/plain',
		'.sor' => 'text/plain',
		'.spc' => 'application/x-pkcs7-certificates',
		'.spl' => 'application/futuresplash',
		'.sst' => 'application/vnd.ms-pki.certstore',
		'.stl' => 'application/vnd.ms-pki.stl',
		'.swf' => 'application/x-shockwave-flash',
		'.thmx' => 'application/vnd.ms-officetheme',
		'.tif' => 'image/tiff',
		'.tiff' => 'image/tiff',
		'.txt' => 'text/plain',
		'.uls' => 'text/iuls',
		'.vcf' => 'text/x-vcard',
		'.vdx' => 'application/vnd.ms-visio.viewer',
		'.vsd' => 'application/vnd.ms-visio.viewer',
		'.vss' => 'application/vnd.ms-visio.viewer',
		'.vst' => 'application/vnd.ms-visio.viewer',
		'.vsx' => 'application/vnd.ms-visio.viewer',
		'.vtx' => 'application/vnd.ms-visio.viewer',
		'.wav' => 'audio/wav',
		'.wax' => 'audio/x-ms-wax',
		'.wbk' => 'application/msword',
		'.wdp' => 'image/vnd.ms-photo',
		'.wiz' => 'application/msword',
		'.wm' => 'video/x-ms-wm',
		'.wma' => 'audio/x-ms-wma',
		'.wmd' => 'application/x-ms-wmd',
		'.wmv' => 'video/x-ms-wmv',
		'.wmx' => 'video/x-ms-wmx',
		'.wmz' => 'application/x-ms-wmz',
		'.wpl' => 'application/vnd.ms-wpl',
		'.wsc' => 'text/scriptlet',
		'.wvx' => 'video/x-ms-wvx',
		'.xaml' => 'application/xaml+xml',
		'.xbap' => 'application/x-ms-xbap',
		'.xdp' => 'application/vnd.adobe.xdp+xml',
		'.xfdf' => 'application/vnd.adobe.xfdf',
		'.xht' => 'application/xhtml+xml',
		'.xhtml' => 'application/xhtml+xml',
		'.xla' => 'application/vnd.ms-excel',
		'.xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
		'.xlk' => 'application/vnd.ms-excel',
		'.xll' => 'application/vnd.ms-excel',
		'.xlm' => 'application/vnd.ms-excel',
		'.xls' => 'application/vnd.ms-excel',
		'.xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
		'.xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
		'.xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'.xlt' => 'application/vnd.ms-excel',
		'.xltm' => 'application/vnd.ms-excel.template.macroEnabled.12',
		'.xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
		'.xlw' => 'application/vnd.ms-excel',
		'.xml' => 'text/xml',
		'.xps' => 'application/vnd.ms-xpsdocument',
		'.xsl' => 'text/xml',
	);

	/**
	 * IE versions which have been analysed to bring you this class, and for
	 * which some substantive difference exists. These will appear as keys
	 * in the return value of getRealMimesFromData(). The names are chosen to sort correctly.
	 */
	protected $versions = array( 'ie05', 'ie06', 'ie07', 'ie07.strict', 'ie07.nohtml' );

	/**
	 * Type table with versions expanded
	 */
	protected $typeTable = array();

	/** constructor */
	function __construct() {
		// Construct versioned type arrays from the base type array plus additions
		$types = $this->baseTypeTable;
		foreach ( $this->versions as $version ) {
			if ( isset( $this->addedTypes[$version] ) ) {
				foreach ( $this->addedTypes[$version] as $format => $addedTypes ) {
					$types[$format] = array_merge( $types[$format], $addedTypes );
				}
			}
			$this->typeTable[$version] = $types;
		}
	}

	/**
	 * Get the MIME types from getMimesFromData(), but convert the result from IE's
	 * idiosyncratic private types into something other apps will understand.
	 *
	 * @param $fileName String: the file name (unused at present)
	 * @param $chunk String: the first 256 bytes of the file
	 * @param $proposed String: the MIME type proposed by the server
	 *
	 * @return Array: map of IE version to detected mime type
	 */
	public function getRealMimesFromData( $fileName, $chunk, $proposed ) {
		$types = $this->getMimesFromData( $fileName, $chunk, $proposed );
		$types = array_map( array( $this, 'translateMimeType' ), $types );
		return $types;
	}

	/**
	 * Translate a MIME type from IE's idiosyncratic private types into
	 * more commonly understood type strings
	 * @param $type
	 * @return string
	 */
	public function translateMimeType( $type ) {
		static $table = array(
			'image/pjpeg' => 'image/jpeg',
			'image/x-png' => 'image/png',
			'image/x-wmf' => 'application/x-msmetafile',
			'image/bmp' => 'image/x-bmp',
			'application/x-zip-compressed' => 'application/zip',
			'application/x-compressed' => 'application/x-compress',
			'application/x-gzip-compressed' => 'application/x-gzip',
			'audio/mid' => 'audio/midi',
		);
		if ( isset( $table[$type] ) ) {
			$type = $table[$type];
		}
		return $type;
	}

	/**
	 * Get the untranslated MIME types for all known versions
	 *
	 * @param $fileName String: the file name (unused at present)
	 * @param $chunk String: the first 256 bytes of the file
	 * @param $proposed String: the MIME type proposed by the server
	 *
	 * @return Array: map of IE version to detected mime type
	 */
	public function getMimesFromData( $fileName, $chunk, $proposed ) {
		$types = array();
		foreach ( $this->versions as $version ) {
			$types[$version] = $this->getMimeTypeForVersion( $version, $fileName, $chunk, $proposed );
		}
		return $types;
	}

	/**
	 * Get the MIME type for a given named version
	 * @param $version
	 * @param $fileName
	 * @param $chunk
	 * @param $proposed
	 * @return bool|string
	 */
	protected function getMimeTypeForVersion( $version, $fileName, $chunk, $proposed ) {
		// Strip text after a semicolon
		$semiPos = strpos( $proposed, ';' );
		if ( $semiPos !== false ) {
			$proposed = substr( $proposed, 0, $semiPos );
		}

		$proposedFormat = $this->getDataFormat( $version, $proposed );
		if ( $proposedFormat == 'unknown'
			&& $proposed != 'multipart/mixed'
			&& $proposed != 'multipart/x-mixed-replace' )
		{
			return $proposed;
		}
		if ( strval( $chunk ) === '' ) {
			return $proposed;
		}

		// Truncate chunk at 255 bytes
		$chunk = substr( $chunk, 0, 255 );

		// IE does the Check*Headers() calls last, and instead does the following image
		// type checks by directly looking for the magic numbers. What I do here should
		// have the same effect since the magic number checks are identical in both cases.
		$result = $this->sampleData( $version, $chunk );
		$sampleFound = $result['found'];
		$counters = $result['counters'];
		$binaryType = $this->checkBinaryHeaders( $version, $chunk );
		$textType = $this->checkTextHeaders( $version, $chunk );

		if ( $proposed == 'text/html' && isset( $sampleFound['html'] ) ) {
			return 'text/html';
		}
		if ( $proposed == 'image/gif' && $binaryType == 'image/gif' ) {
			return 'image/gif';
		}
		if ( ( $proposed == 'image/pjpeg' || $proposed == 'image/jpeg' )
			&& $binaryType == 'image/pjpeg' )
		{
			return $proposed;
		}
		// PNG check added in IE 7
		if ( $version >= 'ie07'
			&& ( $proposed == 'image/x-png' || $proposed == 'image/png' )
			&& $binaryType == 'image/x-png' )
		{
			return $proposed;
		}

		// CDF was removed in IE 7 so it won't be in $sampleFound for later versions
		if ( isset( $sampleFound['cdf'] ) ) {
			return 'application/x-cdf';
		}

		// RSS and Atom were added in IE 7 so they won't be in $sampleFound for
		// previous versions
		if ( isset( $sampleFound['rss'] ) ) {
			return 'application/rss+xml';
		}
		if ( isset( $sampleFound['rdf-tag'] )
			&& isset( $sampleFound['rdf-url'] )
			&& isset( $sampleFound['rdf-purl'] ) )
		{
			return 'application/rss+xml';
		}
		if ( isset( $sampleFound['atom'] ) ) {
			return 'application/atom+xml';
		}

		if ( isset( $sampleFound['xml'] ) ) {
			// TODO: I'm not sure under what circumstances this flag is enabled
			if ( strpos( $version, 'strict' ) !== false ) {
				if ( $proposed == 'text/html' || $proposed == 'text/xml' ) {
					return 'text/xml';
				}
			} else {
				return 'text/xml';
			}
		}
		if ( isset( $sampleFound['html'] ) ) {
			// TODO: I'm not sure under what circumstances this flag is enabled
			if ( strpos( $version, 'nohtml' ) !== false ) {
				if ( $proposed == 'text/plain' ) {
					return 'text/html';
				}
			} else {
				return 'text/html';
			}
		}
		if ( isset( $sampleFound['xbm'] ) ) {
			return 'image/x-bitmap';
		}
		if ( isset( $sampleFound['binhex'] ) ) {
			return 'application/macbinhex40';
		}
		if ( isset( $sampleFound['scriptlet'] ) ) {
			if ( strpos( $version, 'strict' ) !== false ) {
				if ( $proposed == 'text/plain' || $proposed == 'text/scriptlet' ) {
					return 'text/scriptlet';
				}
			} else {
				return 'text/scriptlet';
			}
		}

		// Freaky heuristics to determine if the data is text or binary
		// The heuristic is of course broken for non-ASCII text
		if ( $counters['ctrl'] != 0 && ( $counters['ff'] + $counters['low'] )
			< ( $counters['ctrl'] + $counters['high'] ) * 16 )
		{
			$kindOfBinary = true;
			$type = $binaryType ? $binaryType : $textType;
			if ( $type === false ) {
				$type = 'application/octet-stream';
			}
		} else {
			$kindOfBinary = false;
			$type = $textType ? $textType : $binaryType;
			if ( $type === false ) {
				$type = 'text/plain';
			}
		}

		// Check if the output format is ambiguous
		// This generally means that detection failed, real types aren't ambiguous
		$detectedFormat = $this->getDataFormat( $version, $type );
		if ( $detectedFormat != 'ambiguous' ) {
			return $type;
		}

		if ( $proposedFormat != 'ambiguous' ) {
			// FormatAgreesWithData()
			if ( $proposedFormat == 'text' && !$kindOfBinary ) {
				return $proposed;
			}
			if ( $proposedFormat == 'binary' && $kindOfBinary ) {
				return $proposed;
			}
			if ( $proposedFormat == 'html' ) {
				return $proposed;
			}
		}

		// Find a MIME type by searching the registry for the file extension.
		$dotPos = strrpos( $fileName, '.' );
		if ( $dotPos === false ) {
			return $type;
		}
		$ext = substr( $fileName, $dotPos );
		if ( isset( $this->registry[$ext] ) ) {
			return $this->registry[$ext];
		}

		// TODO: If the extension has an application registered to it, IE will return
		// application/octet-stream. We'll skip that, so we could erroneously
		// return text/plain or application/x-netcdf where application/octet-stream
		// would be correct.

		return $type;
	}

	/**
	 * Check for text headers at the start of the chunk
	 * Confirmed same in 5 and 7.
	 * @param $version
	 * @param $chunk
	 * @return bool|string
	 */
	private function checkTextHeaders( $version, $chunk ) {
		$chunk2 = substr( $chunk, 0, 2 );
		$chunk4 = substr( $chunk, 0, 4 );
		$chunk5 = substr( $chunk, 0, 5 );
		if ( $chunk4 == '%PDF' ) {
			return 'application/pdf';
		}
		if ( $chunk2 == '%!' ) {
			return 'application/postscript';
		}
		if ( $chunk5 == '{\\rtf' ) {
			return 'text/richtext';
		}
		if ( $chunk5 == 'begin' ) {
			return 'application/base64';
		}
		return false;
	}

	/**
	 * Check for binary headers at the start of the chunk
	 * Confirmed same in 5 and 7.
	 * @param $version
	 * @param $chunk
	 * @return bool|string
	 */
	private function checkBinaryHeaders( $version, $chunk ) {
		$chunk2 = substr( $chunk, 0, 2 );
		$chunk3 = substr( $chunk, 0, 3 );
		$chunk4 = substr( $chunk, 0, 4 );
		$chunk5 = substr( $chunk, 0, 5 );
		$chunk5uc = strtoupper( $chunk5 );
		$chunk8 = substr( $chunk, 0, 8 );
		if ( $chunk5uc == 'GIF87' || $chunk5uc == 'GIF89' ) {
			return 'image/gif';
		}
		if ( $chunk2 == "\xff\xd8" ) {
			return 'image/pjpeg'; // actually plain JPEG but this is what IE returns
		}

		if ( $chunk2 == 'BM'
			&& substr( $chunk, 6, 2 ) == "\000\000"
			&& substr( $chunk, 8, 2 ) == "\000\000" )
		{
			return 'image/bmp'; // another non-standard MIME
		}
		if ( $chunk4 == 'RIFF'
			&& substr( $chunk, 8, 4 ) == 'WAVE' )
		{
			return 'audio/wav';
		}
		// These were integer literals in IE
		// Perhaps the author was not sure what the target endianness was
		if ( $chunk4 == ".sd\000"
			|| $chunk4 == ".snd"
			|| $chunk4 == "\000ds."
			|| $chunk4 == "dns." )
		{
			return 'audio/basic';
		}
		if ( $chunk3 == "MM\000" ) {
			return 'image/tiff';
		}
		if ( $chunk2 == 'MZ' ) {
			return 'application/x-msdownload';
		}
		if ( $chunk8 == "\x89PNG\x0d\x0a\x1a\x0a" ) {
			return 'image/x-png'; // [sic]
		}
		if ( strlen( $chunk ) >= 5 ) {
			$byte2 = ord( $chunk[2] );
			$byte4 = ord( $chunk[4] );
			if ( $byte2 >= 3 && $byte2 <= 31 && $byte4 == 0 && $chunk2 == 'JG' ) {
				return 'image/x-jg';
			}
		}
		// More endian confusion?
		if ( $chunk4 == 'MROF' ) {
			return 'audio/x-aiff';
		}
		$chunk4_8 = substr( $chunk, 8, 4 );
		if ( $chunk4 == 'FORM' && ( $chunk4_8 == 'AIFF' || $chunk4_8 == 'AIFC' ) ) {
			return 'audio/x-aiff';
		}
		if ( $chunk4 == 'RIFF' && $chunk4_8 == 'AVI ' ) {
			return 'video/avi';
		}
		if ( $chunk4 == "\x00\x00\x01\xb3" || $chunk4 == "\x00\x00\x01\xba" ) {
			return 'video/mpeg';
		}
		if ( $chunk4 == "\001\000\000\000"
			&& substr( $chunk, 40, 4 ) == ' EMF' )
		{
			return 'image/x-emf';
		}
		if ( $chunk4 == "\xd7\xcd\xc6\x9a" ) {
			return 'image/x-wmf';
		}
		if ( $chunk4 == "\xca\xfe\xba\xbe" ) {
			return 'application/java';
		}
		if ( $chunk2 == 'PK' ) {
			return 'application/x-zip-compressed';
		}
		if ( $chunk2 == "\x1f\x9d" ) {
			return 'application/x-compressed';
		}
		if ( $chunk2 == "\x1f\x8b" ) {
			return 'application/x-gzip-compressed';
		}
		// Skip redundant check for ZIP
		if ( $chunk5 == "MThd\000" ) {
			return 'audio/mid';
		}
		if ( $chunk4 == '%PDF' ) {
			return 'application/pdf';
		}
		return false;
	}

	/**
	 * Do heuristic checks on the bulk of the data sample.
	 * Search for HTML tags.
	 * @param $version
	 * @param $chunk
	 * @return array
	 */
	protected function sampleData( $version, $chunk ) {
		$found = array();
		$counters = array(
			'ctrl' => 0,
			'high' => 0,
			'low' => 0,
			'lf' => 0,
			'cr' => 0,
			'ff' => 0
		);
		$htmlTags = array(
			'html',
			'head',
			'title',
			'body',
			'script',
			'a href',
			'pre',
			'img',
			'plaintext',
			'table'
		);
		$rdfUrl = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';
		$rdfPurl = 'http://purl.org/rss/1.0/';
		$xbmMagic1 = '#define';
		$xbmMagic2 = '_width';
		$xbmMagic3 = '_bits';
		$binhexMagic = 'converted with BinHex';

		for ( $offset = 0; $offset < strlen( $chunk ); $offset++ ) {
			$curChar = $chunk[$offset];
			if ( $curChar == "\x0a" ) {
				$counters['lf']++;
				continue;
			} elseif ( $curChar == "\x0d" ) {
				$counters['cr']++;
				continue;
			} elseif ( $curChar == "\x0c" ) {
				$counters['ff']++;
				continue;
			} elseif ( $curChar == "\t" ) {
				$counters['low']++;
				continue;
			} elseif ( ord( $curChar ) < 32 ) {
				$counters['ctrl']++;
				continue;
			} elseif ( ord( $curChar ) >= 128 ) {
				$counters['high']++;
				continue;
			}

			$counters['low']++;
			if ( $curChar == '<' ) {
				// XML
				$remainder = substr( $chunk, $offset + 1 );
				if ( !strncasecmp( $remainder, '?XML', 4 ) ) {
					$nextChar = substr( $chunk, $offset + 5, 1 );
					if ( $nextChar == ':' || $nextChar == ' ' || $nextChar == "\t" ) {
						$found['xml'] = true;
					}
				}
				// Scriptlet (JSP)
				if ( !strncasecmp( $remainder, 'SCRIPTLET', 9 ) ) {
					$found['scriptlet'] = true;
					break;
				}
				// HTML
				foreach ( $htmlTags as $tag ) {
					if ( !strncasecmp( $remainder, $tag, strlen( $tag ) ) ) {
						$found['html'] = true;
					}
				}
				// Skip broken check for additional tags (HR etc.)

				// CHANNEL replaced by RSS, RDF and FEED in IE 7
				if ( $version < 'ie07' ) {
					if ( !strncasecmp( $remainder, 'CHANNEL', 7 ) ) {
						$found['cdf'] = true;
					}
				} else {
					// RSS
					if ( !strncasecmp( $remainder, 'RSS', 3 ) ) {
						$found['rss'] = true;
						break; // return from SampleData
					}
					if ( !strncasecmp( $remainder, 'rdf:RDF', 7 ) ) {
						$found['rdf-tag'] = true;
						// no break
					}
					if ( !strncasecmp( $remainder, 'FEED', 4 ) ) {
						$found['atom'] = true;
						break;
					}
				}
				continue;
			}
			// Skip broken check for -->

			// RSS URL checks
			// For some reason both URLs must appear before it is recognised
			$remainder = substr( $chunk, $offset );
			if ( !strncasecmp( $remainder, $rdfUrl, strlen( $rdfUrl ) ) ) {
				$found['rdf-url'] = true;
				if ( isset( $found['rdf-tag'] )
					&& isset( $found['rdf-purl'] ) ) // [sic]
				{
					break;
				}
				continue;
			}

			if ( !strncasecmp( $remainder, $rdfPurl, strlen( $rdfPurl ) ) ) {
				if ( isset( $found['rdf-tag'] )
					&& isset( $found['rdf-url'] ) ) // [sic]
				{
					break;
				}
				continue;
			}

			// XBM checks
			if ( !strncasecmp( $remainder, $xbmMagic1, strlen( $xbmMagic1 ) ) ) {
				$found['xbm1'] = true;
				continue;
			}
			if ( $curChar == '_' ) {
				if ( isset( $found['xbm2'] ) ) {
					if ( !strncasecmp( $remainder, $xbmMagic3, strlen( $xbmMagic3 ) ) ) {
						$found['xbm'] = true;
						break;
					}
				} elseif ( isset( $found['xbm1'] ) ) {
					if ( !strncasecmp( $remainder, $xbmMagic2, strlen( $xbmMagic2 ) ) ) {
						$found['xbm2'] = true;
					}
				}
			}

			// BinHex
			if ( !strncmp( $remainder, $binhexMagic, strlen( $binhexMagic ) ) ) {
				$found['binhex'] = true;
			}
		}
		return array( 'found' => $found, 'counters' => $counters );
	}

	/**
	 * @param $version
	 * @param $type
	 * @return int|string
	 */
	protected function getDataFormat( $version, $type ) {
		$types = $this->typeTable[$version];
		if ( $type == '(null)' || strval( $type ) === '' ) {
			return 'ambiguous';
		}
		foreach ( $types as $format => $list ) {
			if ( in_array( $type, $list ) ) {
				return $format;
			}
		}
		return 'unknown';
	}
}

