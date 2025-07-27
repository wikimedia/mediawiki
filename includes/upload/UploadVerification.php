<?php
/**
 * Base class for the backend of file upload.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Upload
 */

namespace MediaWiki\Upload;

use MediaHandler;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Shell\Shell;
use UploadBase;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Mime\MimeAnalyzer;
use Wikimedia\Mime\XmlTypeCheck;

/**
 * @ingroup Upload
 *
 * Service to verify file uploads are safe.
 *
 * This is responsible for checks on the file contents themselves. It
 * is not responsible for on wiki checks like if the user has permission
 * or if the upload target is protected.
 *
 * @author Brian Wolff
 */
class UploadVerification {

	private const SAFE_XML_ENCODINGS = [
		'UTF-8',
		'US-ASCII',
		'ISO-8859-1',
		'ISO-8859-2',
		'UTF-16',
		'UTF-32',
		'WINDOWS-1250',
		'WINDOWS-1251',
		'WINDOWS-1252',
		'WINDOWS-1253',
		'WINDOWS-1254',
		'WINDOWS-1255',
		'WINDOWS-1256',
		'WINDOWS-1257',
		'WINDOWS-1258',
	];

	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::VerifyMimeType,
		MainConfigNames::MimeTypeExclusions,
		MainConfigNames::DisableUploadScriptChecks,
		MainConfigNames::Antivirus,
		MainConfigNames::AntivirusSetup,
		MainConfigNames::AntivirusRequired
	];

	private ServiceOptions $config;
	private MimeAnalyzer $mimeAnalyzer;

	/**
	 * @param ServiceOptions $config
	 * @param MimeAnalyzer $mimeAnalyzer
	 */
	public function __construct(
		ServiceOptions $config,
		MimeAnalyzer $mimeAnalyzer,
	) {
		$config->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->config = $config;
		$this->mimeAnalyzer = $mimeAnalyzer;
	}

	/**
	 * Verify the MIME type.
	 *
	 * @note Only checks that it is not an evil MIME.
	 *  The "does it have the correct file extension given its MIME type?" check is in verifyFile.
	 * @param string $mime Representing the MIME
	 * @return array|bool True if the file is verified, an array otherwise
	 */
	private function verifyMimeType( $mime ) {
		$verifyMimeType = $this->config->get( MainConfigNames::VerifyMimeType );
		if ( $verifyMimeType ) {
			wfDebug( "mime: <$mime>" );
			$mimeTypeExclusions = $this->config
				->get( MainConfigNames::MimeTypeExclusions );
			if ( UploadBase::checkFileExtension( $mime, $mimeTypeExclusions ) ) {
				return [ 'filetype-badmime', $mime ];
			}
		}

		return true;
	}

	/**
	 * Verifies that the upload file is safe
	 *
	 * @note This verifies the contents of the file. It is not
	 *  responsible for verifying if the file has a valid name, is
	 *  too big, meets on wiki permission checks, etc. If you are
	 *  implementing your own upload support, see
	 *  UploadBase::verifyUpload for other neccessary checks.
	 *
	 * @param string $path Path to the (temporary) file to check
	 * @param string $ext Final extension of file (UploadBase->mFinalExtension)
	 * @param array $fileProps Result of $mwProps->getPropsFromPath.
	 * FIXME final ext can sometimes be null, but should we require casting to string?
	 * @return array|true True of the file is verified, array otherwise.
	 */
	public function verifyFile( string $path, string $ext, array $fileProps ) {
		$config = $this->config;
		$verifyMimeType = $config->get( MainConfigNames::VerifyMimeType );
		$disableUploadScriptChecks = $config->get( MainConfigNames::DisableUploadScriptChecks );
		$status = $this->verifyPartialFile( $path, $ext, $fileProps );
		if ( $status !== true ) {
			return $status;
		}

		$mime = $fileProps['mime'];

		if ( $verifyMimeType ) {
			# XXX: Missing extension will be caught by validateName() via getTitle()
			if ( $ext !== '' &&
				!$this->verifyExtension( $mime, $ext )
			) {
				return [ 'filetype-mime-mismatch', $ext, $mime ];
			}
		}

		# check for htmlish code and javascript
		if ( !$disableUploadScriptChecks ) {
			if ( $ext === 'svg' || $mime === 'image/svg+xml' ) {
				$svgStatus = $this->detectScriptInSvg( $path, false );
				if ( $svgStatus !== false ) {
					return $svgStatus;
				}
			}
		}

		$handler = MediaHandler::getHandler( $mime );
		if ( $handler ) {
			$handlerStatus = $handler->verifyUpload( $path );
			if ( !$handlerStatus->isOK() ) {
				$errors = $handlerStatus->getErrorsArray();

				return reset( $errors );
			}
		}

		// TODO: Perhaps we should have a hook here akin to UploadVerifyFile
		// except that it doesn't pass an UploadBase, and it would run
		// even when someone is verifying a file not going through UploadBase.

		wfDebug( __METHOD__ . ": all clear; passing." );

		return true;
	}

	/**
	 * A verification routine suitable for partial files
	 *
	 * Runs the deny list checks, but not any checks that may
	 * assume the entire file is present.
	 *
	 * fileProps can be very expensive to calculate, so the calling class is
	 * responsible for caching it.
	 *
	 * @param string $path Path to the (temporary) file to check
	 * @param string $ext Final extension of file (UploadBase->mFinalExtension)
	 * @param array $fileProps Result of $mwProps->getPropsFromPath
	 * @return array|true True, if the file is valid, else an array with error message key.
	 * @phan-return non-empty-array|true
	 */
	public function verifyPartialFile( string $path, string $ext, array $fileProps ) {
		$config = $this->config;
		$disableUploadScriptChecks = $config->get( MainConfigNames::DisableUploadScriptChecks );

		# check MIME type, if desired
		$mime = $fileProps['file-mime'];
		$status = $this->verifyMimeType( $mime );
		if ( $status !== true ) {
			return $status;
		}

		# check for htmlish code and javascript
		if ( !$disableUploadScriptChecks ) {
			if ( $this->detectScript( $path, $mime, $ext ) ) {
				return [ 'uploadscripted' ];
			}
			if ( $ext === 'svg' || $mime === 'image/svg+xml' ) {
				$svgStatus = $this->detectScriptInSvg( $path, true );
				if ( $svgStatus !== false ) {
					return $svgStatus;
				}
			}
		}

		# Scan the uploaded file for viruses
		$virus = $this->detectVirus( $path );
		if ( $virus ) {
			return [ 'uploadvirus', $virus ];
		}

		return true;
	}

	/**
	 * Checks if the MIME type of the uploaded file matches the file extension.
	 *
	 * @internal Will become private once UploadBase::verifyExtension is removed
	 * @param string $mime The MIME type of the uploaded file
	 * @param string $extension The filename extension that the file is to be served with
	 * @return bool
	 */
	public function verifyExtension( $mime, $extension ) {
		$magic = $this->mimeAnalyzer;

		if ( !$mime || $mime === 'unknown' || $mime === 'unknown/unknown' ) {
			if ( !$magic->isRecognizableExtension( $extension ) ) {
				wfDebug( __METHOD__ . ": passing file with unknown detected mime type; " .
					"unrecognized extension '$extension', can't verify" );

				return true;
			}

			wfDebug( __METHOD__ . ": rejecting file with unknown detected mime type; " .
				"recognized extension '$extension', so probably invalid file" );
			return false;
		}

		$match = $magic->isMatchingExtension( $extension, $mime );

		if ( $match === null ) {
			if ( $magic->getMimeTypesFromExtension( $extension ) !== [] ) {
				wfDebug( __METHOD__ . ": No extension known for $mime, but we know a mime for $extension" );

				return false;
			}

			wfDebug( __METHOD__ . ": no file extension known for mime type $mime, passing file" );
			return true;
		}

		if ( $match ) {
			wfDebug( __METHOD__ . ": mime type $mime matches extension $extension, passing file" );

			/** @todo If it's a bitmap, make sure PHP or ImageMagick resp. can handle it! */
			return true;
		}

		wfDebug( __METHOD__
			. ": mime type $mime mismatches file extension $extension, rejecting file" );

		return false;
	}

	/**
	 * Heuristic for detecting files that *could* contain JavaScript instructions or
	 * things that may look like HTML to a browser and are thus
	 * potentially harmful. The present implementation will produce false
	 * positives in some situations.
	 *
	 * @internal This is public for back-compat. Some extensions call this, however
	 *  this is probably not the method they want. Instead they should call verifyFile().
	 *  Calling this outside this class should be considered deprecated and the method
	 *  may become private in the future.
	 * @param string|null $file Pathname to the temporary upload file
	 * @param string $mime The MIME type of the file
	 * @param string|null $extension The extension of the file
	 * @return bool True if the file contains something looking like embedded scripts
	 */
	public function detectScript( $file, $mime, $extension ) {
		# ugly hack: for text files, always look at the entire file.
		# For binary field, just check the first K.

		if ( str_starts_with( $mime ?? '', 'text/' ) ) {
			$chunk = file_get_contents( $file );
		} else {
			$fp = fopen( $file, 'rb' );
			if ( !$fp ) {
				return false;
			}
			$chunk = fread( $fp, 1024 );
			fclose( $fp );
		}

		$chunk = strtolower( $chunk );

		if ( !$chunk ) {
			return false;
		}

		# decode from UTF-16 if needed (could be used for obfuscation).
		if ( str_starts_with( $chunk, "\xfe\xff" ) ) {
			$enc = 'UTF-16BE';
		} elseif ( str_starts_with( $chunk, "\xff\xfe" ) ) {
			$enc = 'UTF-16LE';
		} else {
			$enc = null;
		}

		if ( $enc !== null ) {
			AtEase::suppressWarnings();
			$chunk = iconv( $enc, "ASCII//IGNORE", $chunk );
			AtEase::restoreWarnings();
		}

		$chunk = trim( $chunk );

		/** @todo FIXME: Convert from UTF-16 if necessary! */
		wfDebug( __METHOD__ . ": checking for embedded scripts and HTML stuff" );

		# check for HTML doctype
		if ( preg_match( "/<!DOCTYPE *X?HTML/i", $chunk ) ) {
			return true;
		}

		// Some browsers will interpret obscure xml encodings as UTF-8, while
		// PHP/expat will interpret the given encoding in the xml declaration (T49304)
		if ( $extension === 'svg' || str_starts_with( $mime ?? '', 'image/svg' ) ) {
			if ( $this->checkXMLEncodingMismatch( $file ) ) {
				return true;
			}
		}

		// Quick check for HTML heuristics in old IE and Safari.
		//
		// The exact heuristics IE uses are checked separately via verifyMimeType(), so we
		// don't need them all here as it can cause many false positives.
		//
		// Check for `<script` and such still to forbid script tags and embedded HTML in SVG:
		$tags = [
			'<body',
			'<head',
			'<html', # also in safari
			'<script', # also in safari
		];

		foreach ( $tags as $tag ) {
			if ( strpos( $chunk, $tag ) !== false ) {
				wfDebug( __METHOD__ . ": found something that may make it be mistaken for html: $tag" );

				return true;
			}
		}

		/*
		 * look for JavaScript
		 */

		# resolve entity-refs to look at attributes. may be harsh on big files... cache result?
		$chunk = Sanitizer::decodeCharReferences( $chunk );

		# look for script-types
		if ( preg_match( '!type\s*=\s*[\'"]?\s*(?:\w*/)?(?:ecma|java)!im', $chunk ) ) {
			wfDebug( __METHOD__ . ": found script types" );

			return true;
		}

		# look for html-style script-urls
		if ( preg_match( '!(?:href|src|data)\s*=\s*[\'"]?\s*(?:ecma|java)script:!im', $chunk ) ) {
			wfDebug( __METHOD__ . ": found html-style script urls" );

			return true;
		}

		# look for css-style script-urls
		if ( preg_match( '!url\s*\(\s*[\'"]?\s*(?:ecma|java)script:!im', $chunk ) ) {
			wfDebug( __METHOD__ . ": found css-style script urls" );

			return true;
		}

		wfDebug( __METHOD__ . ": no scripts found" );

		return false;
	}

	/**
	 * Check an allowed list of xml encodings that are known not to be interpreted differently
	 * by the server's xml parser (expat) and some common browsers.
	 *
	 * @param string $file Pathname to the temporary upload file
	 * @return bool True if the file contains an encoding that could be misinterpreted
	 */
	private function checkXMLEncodingMismatch( $file ) {
		// https://mimesniff.spec.whatwg.org/#resource-header says browsers
		// should read the first 1445 bytes. Do 4096 bytes for good measure.
		// XML Spec says XML declaration if present must be first thing in file
		// other than BOM
		$contents = file_get_contents( $file, false, null, 0, 4096 );
		$encodingRegex = '!encoding[ \t\n\r]*=[ \t\n\r]*[\'"](.*?)[\'"]!si';

		if ( preg_match( "!<\?xml\b(.*?)\?>!si", $contents, $matches ) ) {
			if ( preg_match( $encodingRegex, $matches[1], $encMatch )
				&& !in_array( strtoupper( $encMatch[1] ), self::SAFE_XML_ENCODINGS )
			) {
				wfDebug( __METHOD__ . ": Found unsafe XML encoding '{$encMatch[1]}'" );

				return true;
			}
		} elseif ( preg_match( "!<\?xml\b!i", $contents ) ) {
			// Start of XML declaration without an end in the first 4096 bytes
			// bytes. There shouldn't be a legitimate reason for this to happen.
			wfDebug( __METHOD__ . ": Unmatched XML declaration start" );

			return true;
		} elseif ( str_starts_with( $contents, "\x4C\x6F\xA7\x94" ) ) {
			// EBCDIC encoded XML
			wfDebug( __METHOD__ . ": EBCDIC Encoded XML" );

			return true;
		}

		// It's possible the file is encoded with multibyte encoding, so re-encode attempt to
		// detect the encoding in case it specifies an encoding not allowed in self::SAFE_XML_ENCODINGS
		$attemptEncodings = [ 'UTF-16', 'UTF-16BE', 'UTF-32', 'UTF-32BE' ];
		foreach ( $attemptEncodings as $encoding ) {
			AtEase::suppressWarnings();
			$str = iconv( $encoding, 'UTF-8', $contents );
			AtEase::restoreWarnings();
			if ( $str != '' && preg_match( "!<\?xml\b(.*?)\?>!si", $str, $matches ) ) {
				if ( preg_match( $encodingRegex, $matches[1], $encMatch )
					&& !in_array( strtoupper( $encMatch[1] ), self::SAFE_XML_ENCODINGS )
				) {
					wfDebug( __METHOD__ . ": Found unsafe XML encoding '{$encMatch[1]}'" );

					return true;
				}
			} elseif ( $str != '' && preg_match( "!<\?xml\b!i", $str ) ) {
				// Start of XML declaration without an end in the first 4096 bytes
				// bytes. There shouldn't be a legitimate reason for this to happen.
				wfDebug( __METHOD__ . ": Unmatched XML declaration start" );

				return true;
			}
		}

		return false;
	}

	/**
	 * Looks for bad SVG files
	 *
	 * @warning This function is only safe for the XML serialization of SVGs.
	 * @param string $filename
	 * @param bool $partial
	 * @return bool|array
	 */
	private function detectScriptInSvg( $filename, $partial ) {
		$check = new XmlTypeCheck(
			$filename,
			$this->checkSvgScriptCallback( ... ),
			true,
			[
				'processing_instruction_handler' => $this->checkSvgPICallback( ... ),
				'external_dtd_handler' => $this->checkSvgExternalDTD( ... ),
			]
		);
		if ( $check->wellFormed !== true ) {
			// Invalid xml (T60553)
			// But only when non-partial (T67724)
			return $partial ? false : [ 'uploadinvalidxml' ];
		}

		if ( $check->filterMatch ) {
			return $check->filterMatchType;
		}

		return false;
	}

	/**
	 * Callback to filter SVG Processing Instructions.
	 *
	 * @param string $target Processing instruction name
	 * @param string $data Processing instruction attribute and value
	 * @return bool|array
	 */
	private function checkSvgPICallback( $target, $data ) {
		// Don't allow external stylesheets (T59550)
		if ( preg_match( '/xml-stylesheet/i', $target ) ) {
			return [ 'upload-scripted-pi-callback' ];
		}

		return false;
	}

	/**
	 * Verify that DTD URLs referenced are only the standard DTDs.
	 *
	 * Browsers seem to ignore external DTDs.
	 *
	 * However, just to be on the safe side, only allow DTDs from the SVG standard.
	 *
	 * @param string $type PUBLIC or SYSTEM
	 * @param string $publicId The well-known public identifier for the dtd
	 * @param string $systemId The url for the external dtd
	 * @return bool|array
	 */
	private function checkSvgExternalDTD( $type, $publicId, $systemId ) {
		// This doesn't include the XHTML+MathML+SVG doctype since we don't
		// allow XHTML anyway.
		static $allowedDTDs = [
			'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd',
			'http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd',
			'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11-basic.dtd',
			'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11-tiny.dtd',
			// https://phabricator.wikimedia.org/T168856
			'http://www.w3.org/TR/2001/PR-SVG-20010719/DTD/svg10.dtd',
		];
		if ( $type !== 'PUBLIC'
			|| !in_array( $systemId, $allowedDTDs )
			|| !str_starts_with( $publicId, "-//W3C//" )
		) {
			return [ 'upload-scripted-dtd' ];
		}
		return false;
	}

	/**
	 * Callback to XML parsing checking individual tags for evilness
	 * @warning This assumes that the SVG is using the XML serialization.
	 *  It is not safe if the SVG is directly embedded in HTML.
	 * @todo Replace this with a allow list filter!
	 * @param string $element
	 * @param array $attribs
	 * @param string|null $data
	 * @return bool|array
	 */
	private function checkSvgScriptCallback( $element, $attribs, $data = null ) {
		[ $namespace, $strippedElement ] = self::splitXmlNamespace( $element );

		// We specifically don't include:
		// http://www.w3.org/1999/xhtml (T62771)
		static $validNamespaces = [
			'',
			'adobe:ns:meta/',
			'http://creativecommons.org/ns#',
			'http://inkscape.sourceforge.net/dtd/sodipodi-0.dtd',
			'http://ns.adobe.com/adobeillustrator/10.0/',
			'http://ns.adobe.com/adobesvgviewerextensions/3.0/',
			'http://ns.adobe.com/extensibility/1.0/',
			'http://ns.adobe.com/flows/1.0/',
			'http://ns.adobe.com/illustrator/1.0/',
			'http://ns.adobe.com/imagereplacement/1.0/',
			'http://ns.adobe.com/pdf/1.3/',
			'http://ns.adobe.com/photoshop/1.0/',
			'http://ns.adobe.com/saveforweb/1.0/',
			'http://ns.adobe.com/variables/1.0/',
			'http://ns.adobe.com/xap/1.0/',
			'http://ns.adobe.com/xap/1.0/g/',
			'http://ns.adobe.com/xap/1.0/g/img/',
			'http://ns.adobe.com/xap/1.0/mm/',
			'http://ns.adobe.com/xap/1.0/rights/',
			'http://ns.adobe.com/xap/1.0/stype/dimensions#',
			'http://ns.adobe.com/xap/1.0/stype/font#',
			'http://ns.adobe.com/xap/1.0/stype/manifestitem#',
			'http://ns.adobe.com/xap/1.0/stype/resourceevent#',
			'http://ns.adobe.com/xap/1.0/stype/resourceref#',
			'http://ns.adobe.com/xap/1.0/t/pg/',
			'http://purl.org/dc/elements/1.1/',
			'http://purl.org/dc/elements/1.1',
			'http://schemas.microsoft.com/visio/2003/svgextensions/',
			'http://sodipodi.sourceforge.net/dtd/sodipodi-0.dtd',
			'http://taptrix.com/inkpad/svg_extensions',
			'http://web.resource.org/cc/',
			'http://www.freesoftware.fsf.org/bkchem/cdml',
			'http://www.inkscape.org/namespaces/inkscape',
			'http://www.opengis.net/gml',
			'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
			'http://www.w3.org/2000/svg',
			'http://www.w3.org/tr/rec-rdf-syntax/',
			'http://www.w3.org/2000/01/rdf-schema#',
			'http://www.w3.org/2000/02/svg/testsuite/description/', // https://phabricator.wikimedia.org/T278044
		];

		// Inkscape mangles namespace definitions created by Adobe Illustrator.
		// This is nasty but harmless. (T144827)
		$isBuggyInkscape = preg_match( '/^&(#38;)*ns_[a-z_]+;$/', $namespace );

		if ( !( $isBuggyInkscape || in_array( $namespace, $validNamespaces ) ) ) {
			wfDebug( __METHOD__ . ": Non-svg namespace '$namespace' in uploaded file." );
			return [ 'uploadscriptednamespace', $namespace ];
		}

		// check for elements that can contain javascript
		if ( $strippedElement === 'script' ) {
			wfDebug( __METHOD__ . ": Found script element '$element' in uploaded file." );

			return [ 'uploaded-script-svg', $strippedElement ];
		}

		// e.g., <svg xmlns="http://www.w3.org/2000/svg">
		//  <handler xmlns:ev="http://www.w3.org/2001/xml-events" ev:event="load">alert(1)</handler> </svg>
		if ( $strippedElement === 'handler' ) {
			wfDebug( __METHOD__ . ": Found scriptable element '$element' in uploaded file." );

			return [ 'uploaded-script-svg', $strippedElement ];
		}

		// SVG reported in Feb '12 that used xml:stylesheet to generate javascript block
		if ( $strippedElement === 'stylesheet' ) {
			wfDebug( __METHOD__ . ": Found scriptable element '$element' in uploaded file." );

			return [ 'uploaded-script-svg', $strippedElement ];
		}

		// Block iframes, in case they pass the namespace check
		if ( $strippedElement === 'iframe' ) {
			wfDebug( __METHOD__ . ": iframe in uploaded file." );

			return [ 'uploaded-script-svg', $strippedElement ];
		}

		// Check <style> css
		if ( $strippedElement === 'style'
			&& self::checkCssFragment( Sanitizer::normalizeCss( $data ) )
		) {
			wfDebug( __METHOD__ . ": hostile css in style element." );

			return [ 'uploaded-hostile-svg' ];
		}

		static $cssAttrs = [ 'font', 'clip-path', 'fill', 'filter', 'marker',
			'marker-end', 'marker-mid', 'marker-start', 'mask', 'stroke' ];

		foreach ( $attribs as $attrib => $value ) {
			// If attributeNamespace is '', it is relative to its element's namespace
			[ $attributeNamespace, $stripped ] = self::splitXmlNamespace( $attrib );
			$value = strtolower( $value );

			if ( !(
					// Inkscape element's have valid attribs that start with on and are safe, fail all others
					// We are assuming here that the SVG will be interpreted
					// under XML serialization. This is not safe for SVGs
					// embedded directly in HTML.
					$namespace === 'http://www.inkscape.org/namespaces/inkscape' &&
					$attributeNamespace === ''
				) && str_starts_with( $stripped, 'on' )
			) {
				wfDebug( __METHOD__
					. ": Found event-handler attribute '$attrib'='$value' in uploaded file." );

				return [ 'uploaded-event-handler-on-svg', $attrib, $value ];
			}

			// Do not allow relative links, or unsafe url schemas.
			// For <a> tags, only data:, http: and https: and same-document
			// fragment links are allowed.
			// For all other tags, only 'data:' and fragments (#) are allowed.
			if (
				$stripped === 'href'
				&& $value !== ''
				&& !str_starts_with( $value, 'data:' )
				&& !str_starts_with( $value, '#' )
				&& !( $strippedElement === 'a' && preg_match( '!^https?://!i', $value ) )
			) {
				wfDebug( __METHOD__ . ": Found href attribute <$strippedElement "
					. "'$attrib'='$value' in uploaded file." );

				return [ 'uploaded-href-attribute-svg', $strippedElement, $attrib, $value ];
			}

			// Only allow 'data:\' targets that should be safe.
			// This prevents vectors like image/svg, text/xml, application/xml, and text/html, which can contain scripts
			if ( $stripped === 'href' && strncasecmp( 'data:', $value, 5 ) === 0 ) {
				// RFC2397 parameters.
				// This is only slightly slower than (;[\w;]+)*.
				// phpcs:ignore Generic.Files.LineLength
				$parameters = '(?>;[a-zA-Z0-9\!#$&\'*+.^_`{|}~-]+=(?>[a-zA-Z0-9\!#$&\'*+.^_`{|}~-]+|"(?>[\0-\x0c\x0e-\x21\x23-\x5b\x5d-\x7f]+|\\\\[\0-\x7f])*"))*(?:;base64)?';

				if ( !preg_match( "!^data:\s*image/(gif|jpeg|jpg|png)$parameters,!i", $value ) ) {
					wfDebug( __METHOD__ . ": Found href to allow listed data: uri "
						. "\"<$strippedElement '$attrib'='$value'...\" in uploaded file." );
					return [ 'uploaded-href-unsafe-target-svg', $strippedElement, $attrib, $value ];
				}
			}

			// Change href with animate from (http://html5sec.org/#137).
			if ( $stripped === 'attributename'
				&& $strippedElement === 'animate'
				&& $this->stripXmlNamespace( $value ) === 'href'
			) {
				wfDebug( __METHOD__ . ": Found animate that might be changing href using from "
					. "\"<$strippedElement '$attrib'='$value'...\" in uploaded file." );

				return [ 'uploaded-animate-svg', $strippedElement, $attrib, $value ];
			}

			// Use set/animate to add event-handler attribute to parent.
			if ( ( $strippedElement === 'set' || $strippedElement === 'animate' )
				&& $stripped === 'attributename'
				&& str_starts_with( $value, 'on' )
			) {
				wfDebug( __METHOD__ . ": Found svg setting event-handler attribute with "
					. "\"<$strippedElement $stripped='$value'...\" in uploaded file." );

				return [ 'uploaded-setting-event-handler-svg', $strippedElement, $stripped, $value ];
			}

			// use set to add href attribute to parent element.
			if ( $strippedElement === 'set'
				&& $stripped === 'attributename'
				&& str_contains( $value, 'href' )
			) {
				wfDebug( __METHOD__ . ": Found svg setting href attribute '$value' in uploaded file." );

				return [ 'uploaded-setting-href-svg' ];
			}

			// use set to add a remote / data / script target to an element.
			if ( $strippedElement === 'set'
				&& $stripped === 'to'
				&& preg_match( '!(http|https|data|script):!im', $value )
			) {
				wfDebug( __METHOD__ . ": Found svg setting attribute to '$value' in uploaded file." );

				return [ 'uploaded-wrong-setting-svg', $value ];
			}

			// use handler attribute with remote / data / script.
			if ( $stripped === 'handler' && preg_match( '!(http|https|data|script):!im', $value ) ) {
				wfDebug( __METHOD__ . ": Found svg setting handler with remote/data/script "
					. "'$attrib'='$value' in uploaded file." );

				return [ 'uploaded-setting-handler-svg', $attrib, $value ];
			}

			// use CSS styles to bring in remote code.
			if ( $stripped === 'style'
				&& self::checkCssFragment( Sanitizer::normalizeCss( $value ) )
			) {
				wfDebug( __METHOD__ . ": Found svg setting a style with "
					. "remote url '$attrib'='$value' in uploaded file." );
				return [ 'uploaded-remote-url-svg', $attrib, $value ];
			}

			// Several attributes can include css, css character escaping isn't allowed.
			if ( in_array( $stripped, $cssAttrs, true )
				&& self::checkCssFragment( $value )
			) {
				wfDebug( __METHOD__ . ": Found svg setting a style with "
					. "remote url '$attrib'='$value' in uploaded file." );
				return [ 'uploaded-remote-url-svg', $attrib, $value ];
			}

			// image filters can pull in url, which could be svg that executes scripts.
			// Only allow url( "#foo" ).
			// Do not allow url( http://example.com )
			if ( $strippedElement === 'image'
				&& $stripped === 'filter'
				&& preg_match( '!url\s*\(\s*["\']?[^#]!im', $value )
			) {
				wfDebug( __METHOD__ . ": Found image filter with url: "
					. "\"<$strippedElement $stripped='$value'...\" in uploaded file." );

				return [ 'uploaded-image-filter-svg', $strippedElement, $stripped, $value ];
			}
		}

		return false; // No scripts detected
	}

	/**
	 * Check a block of CSS or CSS fragment for anything that looks like
	 * it is bringing in remote code.
	 *
	 * @todo Consider replacing with css-sanitizer that TemplateStyles uses.
	 * @param string $value a string of CSS
	 * @return bool true if the CSS contains an illegal string, false if otherwise
	 */
	private function checkCssFragment( $value ) {
		# Forbid external stylesheets, for both reliability and to protect viewer's privacy
		if ( stripos( $value, '@import' ) !== false ) {
			return true;
		}

		# We allow @font-face to embed fonts with data: urls, so we snip the string
		# 'url' out so that this case won't match when we check for urls below
		$pattern = '!(@font-face\s*{[^}]*src:)url(\("data:;base64,)!im';
		$value = preg_replace( $pattern, '$1$2', $value );

		# Check for remote and executable CSS. Unlike in Sanitizer::checkCss, the CSS
		# properties filter and accelerator don't seem to be useful for xss in SVG files.
		# Expression and -o-link don't seem to work either, but filtering them here in case.
		# Additionally, we catch remote urls like url("http:..., url('http:..., url(http:...,
		# but not local ones such as url("#..., url('#..., url(#....
		if ( preg_match( '!expression
				| -o-link\s*:
				| -o-link-source\s*:
				| -o-replace\s*:!imx', $value ) ) {
			return true;
		}

		if ( preg_match_all(
				"!(\s*(url|image|image-set)\s*\(\s*[\"']?\s*[^#]+.*?\))!sim",
				$value,
				$matches
			) !== 0
		) {
			# TODO: redo this in one regex. Until then, url("#whatever") matches the first
			foreach ( $matches[1] as $match ) {
				if ( !preg_match( "!\s*(url|image|image-set)\s*\(\s*(#|'#|\"#)!im", $match ) ) {
					return true;
				}
			}
		}

		return (bool)preg_match( '/[\000-\010\013\016-\037\177]/', $value );
	}

	/**
	 * Divide the element name passed by the XML parser to the callback into URI and prefix.
	 *
	 * @param string $element
	 * @return array Containing the namespace URI and prefix
	 */
	private function splitXmlNamespace( $element ) {
		// 'http://www.w3.org/2000/svg:script' -> [ 'http://www.w3.org/2000/svg', 'script' ]
		$parts = explode( ':', strtolower( $element ) );
		$name = array_pop( $parts );
		$ns = implode( ':', $parts );

		return [ $ns, $name ];
	}

	/**
	 * @param string $element
	 * @return string
	 */
	private function stripXmlNamespace( $element ) {
		// 'http://www.w3.org/2000/svg:script' -> 'script'
		return self::splitXmlNamespace( $element )[1];
	}

	/**
	 * Generic wrapper function for a virus scanner program.
	 * This relies on the $wgAntivirus and $wgAntivirusSetup variables.
	 * $wgAntivirusRequired may be used to deny upload if the scan fails.
	 *
	 * @note In most cases, external callers would call verifyFile() to run
	 *  all tests, instead of just doing a virus scan.
	 * @param string $file Pathname to the temporary upload file
	 * @return bool|null|string False if not virus is found, null if the scan fails or is disabled,
	 *   or a string containing feedback from the virus scanner if a virus was found.
	 *   If textual feedback is missing but a virus was found, this function returns true.
	 */
	public function detectVirus( $file ) {
		global $wgOut;
		$mainConfig = $this->config;
		$antivirus = $mainConfig->get( MainConfigNames::Antivirus );
		$antivirusSetup = $mainConfig->get( MainConfigNames::AntivirusSetup );
		$antivirusRequired = $mainConfig->get( MainConfigNames::AntivirusRequired );
		if ( !$antivirus ) {
			wfDebug( __METHOD__ . ": virus scanner disabled" );

			return null;
		}

		if ( !$antivirusSetup[$antivirus] ) {
			// FIXME, we should not directly output in the middle of the page.
			// And will definitely not work from the API.
			wfDebug( __METHOD__ . ": unknown virus scanner: {$antivirus}" );
			$wgOut->wrapWikiMsg( "<div class=\"error\">\n$1\n</div>",
				[ 'virus-badscanner', $antivirus ] );
			return wfMessage( 'virus-unknownscanner' )->text() . " {$antivirus}";
		}

		# look up scanner configuration
		$command = $antivirusSetup[$antivirus]['command'];
		$exitCodeMap = $antivirusSetup[$antivirus]['codemap'];
		$msgPattern = $antivirusSetup[$antivirus]['messagepattern'] ?? null;

		if ( !str_contains( $command, "%f" ) ) {
			# simple pattern: append file to scan
			$command .= " " . Shell::escape( $file );
		} else {
			# complex pattern: replace "%f" with file to scan
			$command = str_replace( "%f", Shell::escape( $file ), $command );
		}

		wfDebug( __METHOD__ . ": running virus scan: $command " );

		# execute virus scanner
		$exitCode = false;

		# NOTE: there's a 50-line workaround to make stderr redirection work on windows, too.
		#  that does not seem to be worth the pain.
		#  Ask me (Duesentrieb) about it if it's ever needed.
		$output = wfShellExecWithStderr( $command, $exitCode );

		# map exit code to AV_xxx constants.
		$mappedCode = $exitCode;
		if ( $exitCodeMap ) {
			if ( isset( $exitCodeMap[$exitCode] ) ) {
				$mappedCode = $exitCodeMap[$exitCode];
			} elseif ( isset( $exitCodeMap["*"] ) ) {
				$mappedCode = $exitCodeMap["*"];
			}
		}

		# NB: AV_NO_VIRUS is 0, but AV_SCAN_FAILED is false,
		# so we need the strict equalities === and thus can't use a switch here
		if ( $mappedCode === AV_SCAN_FAILED ) {
			# scan failed (code was mapped to false by $exitCodeMap)
			wfDebug( __METHOD__ . ": failed to scan $file (code $exitCode)." );

			$output = $antivirusRequired
				? wfMessage( 'virus-scanfailed', [ $exitCode ] )->text()
				: null;
		} elseif ( $mappedCode === AV_SCAN_ABORTED ) {
			# scan failed because filetype is unknown (probably immune)
			wfDebug( __METHOD__ . ": unsupported file type $file (code $exitCode)." );
			$output = null;
		} elseif ( $mappedCode === AV_NO_VIRUS ) {
			# no virus found
			wfDebug( __METHOD__ . ": file passed virus scan." );
			$output = false;
		} else {
			$output = trim( $output );

			if ( !$output ) {
				$output = true; # if there's no output, return true
			} elseif ( $msgPattern ) {
				$groups = [];
				if ( preg_match( $msgPattern, $output, $groups ) && $groups[1] ) {
					$output = $groups[1];
				}
			}

			wfDebug( __METHOD__ . ": FOUND VIRUS! scanner feedback: $output" );
		}

		return $output;
	}
}
