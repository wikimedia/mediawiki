<?php
/**
 * Module defining helper functions for detecting and dealing with MIME types.
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
 */

/**
 * Defines a set of well known MIME types
 * This is used as a fallback to mime.types files.
 * An extensive list of well known MIME types is provided by
 * the file mime.types in the includes directory.
 *
 * This list concatenated with mime.types is used to create a MIME <-> ext
 * map. Each line contains a MIME type followed by a space separated list of
 * extensions. If multiple extensions for a single MIME type exist or if
 * multiple MIME types exist for a single extension then in most cases
 * MediaWiki assumes that the first extension following the MIME type is the
 * canonical extension, and the first time a MIME type appears for a certain
 * extension is considered the canonical MIME type.
 *
 * (Note that appending $wgMimeTypeFile to the end of MM_WELL_KNOWN_MIME_TYPES
 * sucks because you can't redefine canonical types. This could be fixed by
 * appending MM_WELL_KNOWN_MIME_TYPES behind $wgMimeTypeFile, but who knows
 * what will break? In practice this probably isn't a problem anyway -- Bryan)
 */
define( 'MM_WELL_KNOWN_MIME_TYPES', <<<END_STRING
application/ogg ogx ogg ogm ogv oga spx
application/pdf pdf
application/vnd.oasis.opendocument.chart odc
application/vnd.oasis.opendocument.chart-template otc
application/vnd.oasis.opendocument.database odb
application/vnd.oasis.opendocument.formula odf
application/vnd.oasis.opendocument.formula-template otf
application/vnd.oasis.opendocument.graphics odg
application/vnd.oasis.opendocument.graphics-template otg
application/vnd.oasis.opendocument.image odi
application/vnd.oasis.opendocument.image-template oti
application/vnd.oasis.opendocument.presentation odp
application/vnd.oasis.opendocument.presentation-template otp
application/vnd.oasis.opendocument.spreadsheet ods
application/vnd.oasis.opendocument.spreadsheet-template ots
application/vnd.oasis.opendocument.text odt
application/vnd.oasis.opendocument.text-master otm
application/vnd.oasis.opendocument.text-template ott
application/vnd.oasis.opendocument.text-web oth
application/javascript js
application/x-shockwave-flash swf
audio/midi mid midi kar
audio/mpeg mpga mpa mp2 mp3
audio/x-aiff aif aiff aifc
audio/x-wav wav
audio/ogg oga spx ogg
image/x-bmp bmp
image/gif gif
image/jpeg jpeg jpg jpe
image/png png
image/svg+xml svg
image/svg svg
image/tiff tiff tif
image/vnd.djvu djvu
image/x.djvu djvu
image/x-djvu djvu
image/x-portable-pixmap ppm
image/x-xcf xcf
text/plain txt
text/html html htm
video/ogg ogv ogm ogg
video/mpeg mpg mpeg
END_STRING
);

/**
 * Defines a set of well known MIME info entries
 * This is used as a fallback to mime.info files.
 * An extensive list of well known MIME types is provided by
 * the file mime.info in the includes directory.
 */
define( 'MM_WELL_KNOWN_MIME_INFO', <<<END_STRING
application/pdf [OFFICE]
application/vnd.oasis.opendocument.chart [OFFICE]
application/vnd.oasis.opendocument.chart-template [OFFICE]
application/vnd.oasis.opendocument.database [OFFICE]
application/vnd.oasis.opendocument.formula [OFFICE]
application/vnd.oasis.opendocument.formula-template [OFFICE]
application/vnd.oasis.opendocument.graphics [OFFICE]
application/vnd.oasis.opendocument.graphics-template [OFFICE]
application/vnd.oasis.opendocument.image [OFFICE]
application/vnd.oasis.opendocument.image-template [OFFICE]
application/vnd.oasis.opendocument.presentation [OFFICE]
application/vnd.oasis.opendocument.presentation-template [OFFICE]
application/vnd.oasis.opendocument.spreadsheet [OFFICE]
application/vnd.oasis.opendocument.spreadsheet-template [OFFICE]
application/vnd.oasis.opendocument.text [OFFICE]
application/vnd.oasis.opendocument.text-template [OFFICE]
application/vnd.oasis.opendocument.text-master [OFFICE]
application/vnd.oasis.opendocument.text-web [OFFICE]
application/javascript text/javascript application/x-javascript [EXECUTABLE]
application/x-shockwave-flash [MULTIMEDIA]
audio/midi [AUDIO]
audio/x-aiff [AUDIO]
audio/x-wav [AUDIO]
audio/mp3 audio/mpeg [AUDIO]
application/ogg audio/ogg video/ogg [MULTIMEDIA]
image/x-bmp image/x-ms-bmp image/bmp [BITMAP]
image/gif [BITMAP]
image/jpeg [BITMAP]
image/png [BITMAP]
image/svg+xml [DRAWING]
image/tiff [BITMAP]
image/vnd.djvu [BITMAP]
image/x-xcf [BITMAP]
image/x-portable-pixmap [BITMAP]
text/plain [TEXT]
text/html [TEXT]
video/ogg [VIDEO]
video/mpeg [VIDEO]
unknown/unknown application/octet-stream application/x-empty [UNKNOWN]
END_STRING
);

/**
 * Implements functions related to MIME types such as detection and mapping to
 * file extension.
 *
 * Instances of this class are stateless, there only needs to be one global instance
 * of MimeMagic. Please use MimeMagic::singleton() to get that instance.
 */
class MimeMagic {
	/**
	 * @var array Mapping of media types to arrays of MIME types.
	 * This is used by findMediaType and getMediaType, respectively
	 */
	protected $mMediaTypes = null;

	/** @var array Map of MIME type aliases
	 */
	protected $mMimeTypeAliases = null;

	/** @var array Map of MIME types to file extensions (as a space separated list)
	 */
	protected $mMimeToExt = null;

	/** @var array Map of file extensions types to MIME types (as a space separated list)
	 */
	public $mExtToMime = null;

	/** @var IEContentAnalyzer
	 */
	protected $mIEAnalyzer;

	/** @var string Extra MIME types, set for example by media handling extensions
	 */
	private $mExtraTypes = '';

	/** @var string Extra MIME info, set for example by media handling extensions
	 */
	private $mExtraInfo = '';

	/** @var Config */
	private $mConfig;

	/** @var MimeMagic The singleton instance
	 */
	private static $instance = null;

	/** Initializes the MimeMagic object. This is called by MimeMagic::singleton().
	 *
	 * This constructor parses the mime.types and mime.info files and build internal mappings.
	 *
	 * @todo Make this constructor private once everything uses the singleton instance
	 * @param Config $config
	 */
	function __construct( Config $config = null ) {
		if ( !$config ) {
			wfDebug( __METHOD__ . ' called with no Config instance passed to it' );
			$config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
		}
		$this->mConfig = $config;

		/**
		 *   --- load mime.types ---
		 */

		global $IP;

		# Allow media handling extensions adding MIME-types and MIME-info
		Hooks::run( 'MimeMagicInit', [ $this ] );

		$types = MM_WELL_KNOWN_MIME_TYPES;

		$mimeTypeFile = $this->mConfig->get( 'MimeTypeFile' );
		if ( $mimeTypeFile == 'includes/mime.types' ) {
			$mimeTypeFile = "$IP/$mimeTypeFile";
		}

		if ( $mimeTypeFile ) {
			if ( is_file( $mimeTypeFile ) && is_readable( $mimeTypeFile ) ) {
				wfDebug( __METHOD__ . ": loading mime types from $mimeTypeFile\n" );
				$types .= "\n";
				$types .= file_get_contents( $mimeTypeFile );
			} else {
				wfDebug( __METHOD__ . ": can't load mime types from $mimeTypeFile\n" );
			}
		} else {
			wfDebug( __METHOD__ . ": no mime types file defined, using built-ins only.\n" );
		}

		$types .= "\n" . $this->mExtraTypes;

		$types = str_replace( [ "\r\n", "\n\r", "\n\n", "\r\r", "\r" ], "\n", $types );
		$types = str_replace( "\t", " ", $types );

		$this->mMimeToExt = [];
		$this->mExtToMime = [];

		$lines = explode( "\n", $types );
		foreach ( $lines as $s ) {
			$s = trim( $s );
			if ( empty( $s ) ) {
				continue;
			}
			if ( strpos( $s, '#' ) === 0 ) {
				continue;
			}

			$s = strtolower( $s );
			$i = strpos( $s, ' ' );

			if ( $i === false ) {
				continue;
			}

			$mime = substr( $s, 0, $i );
			$ext = trim( substr( $s, $i + 1 ) );

			if ( empty( $ext ) ) {
				continue;
			}

			if ( !empty( $this->mMimeToExt[$mime] ) ) {
				$this->mMimeToExt[$mime] .= ' ' . $ext;
			} else {
				$this->mMimeToExt[$mime] = $ext;
			}

			$extensions = explode( ' ', $ext );

			foreach ( $extensions as $e ) {
				$e = trim( $e );
				if ( empty( $e ) ) {
					continue;
				}

				if ( !empty( $this->mExtToMime[$e] ) ) {
					$this->mExtToMime[$e] .= ' ' . $mime;
				} else {
					$this->mExtToMime[$e] = $mime;
				}
			}
		}

		/**
		 *   --- load mime.info ---
		 */

		$mimeInfoFile = $this->mConfig->get( 'MimeInfoFile' );
		if ( $mimeInfoFile == 'includes/mime.info' ) {
			$mimeInfoFile = "$IP/$mimeInfoFile";
		}

		$info = MM_WELL_KNOWN_MIME_INFO;

		if ( $mimeInfoFile ) {
			if ( is_file( $mimeInfoFile ) && is_readable( $mimeInfoFile ) ) {
				wfDebug( __METHOD__ . ": loading mime info from $mimeInfoFile\n" );
				$info .= "\n";
				$info .= file_get_contents( $mimeInfoFile );
			} else {
				wfDebug( __METHOD__ . ": can't load mime info from $mimeInfoFile\n" );
			}
		} else {
			wfDebug( __METHOD__ . ": no mime info file defined, using built-ins only.\n" );
		}

		$info .= "\n" . $this->mExtraInfo;

		$info = str_replace( [ "\r\n", "\n\r", "\n\n", "\r\r", "\r" ], "\n", $info );
		$info = str_replace( "\t", " ", $info );

		$this->mMimeTypeAliases = [];
		$this->mMediaTypes = [];

		$lines = explode( "\n", $info );
		foreach ( $lines as $s ) {
			$s = trim( $s );
			if ( empty( $s ) ) {
				continue;
			}
			if ( strpos( $s, '#' ) === 0 ) {
				continue;
			}

			$s = strtolower( $s );
			$i = strpos( $s, ' ' );

			if ( $i === false ) {
				continue;
			}

			# print "processing MIME INFO line $s<br>";

			$match = [];
			if ( preg_match( '!\[\s*(\w+)\s*\]!', $s, $match ) ) {
				$s = preg_replace( '!\[\s*(\w+)\s*\]!', '', $s );
				$mtype = trim( strtoupper( $match[1] ) );
			} else {
				$mtype = MEDIATYPE_UNKNOWN;
			}

			$m = explode( ' ', $s );

			if ( !isset( $this->mMediaTypes[$mtype] ) ) {
				$this->mMediaTypes[$mtype] = [];
			}

			foreach ( $m as $mime ) {
				$mime = trim( $mime );
				if ( empty( $mime ) ) {
					continue;
				}

				$this->mMediaTypes[$mtype][] = $mime;
			}

			if ( count( $m ) > 1 ) {
				$main = $m[0];
				$mCount = count( $m );
				for ( $i = 1; $i < $mCount; $i += 1 ) {
					$mime = $m[$i];
					$this->mMimeTypeAliases[$mime] = $main;
				}
			}
		}
	}

	/**
	 * Get an instance of this class
	 * @return MimeMagic
	 */
	public static function singleton() {
		if ( self::$instance === null ) {
			self::$instance = new MimeMagic(
				ConfigFactory::getDefaultInstance()->makeConfig( 'main' )
			);
		}
		return self::$instance;
	}

	/**
	 * Adds to the list mapping MIME to file extensions.
	 * As an extension author, you are encouraged to submit patches to
	 * MediaWiki's core to add new MIME types to mime.types.
	 * @param string $types
	 */
	public function addExtraTypes( $types ) {
		$this->mExtraTypes .= "\n" . $types;
	}

	/**
	 * Adds to the list mapping MIME to media type.
	 * As an extension author, you are encouraged to submit patches to
	 * MediaWiki's core to add new MIME info to mime.info.
	 * @param string $info
	 */
	public function addExtraInfo( $info ) {
		$this->mExtraInfo .= "\n" . $info;
	}

	/**
	 * Returns a list of file extensions for a given MIME type as a space
	 * separated string or null if the MIME type was unrecognized. Resolves
	 * MIME type aliases.
	 *
	 * @param string $mime
	 * @return string|null
	 */
	public function getExtensionsForType( $mime ) {
		$mime = strtolower( $mime );

		// Check the mime-to-ext map
		if ( isset( $this->mMimeToExt[$mime] ) ) {
			return $this->mMimeToExt[$mime];
		}

		// Resolve the MIME type to the canonical type
		if ( isset( $this->mMimeTypeAliases[$mime] ) ) {
			$mime = $this->mMimeTypeAliases[$mime];
			if ( isset( $this->mMimeToExt[$mime] ) ) {
				return $this->mMimeToExt[$mime];
			}
		}

		return null;
	}

	/**
	 * Returns a list of MIME types for a given file extension as a space
	 * separated string or null if the extension was unrecognized.
	 *
	 * @param string $ext
	 * @return string|null
	 */
	public function getTypesForExtension( $ext ) {
		$ext = strtolower( $ext );

		$r = isset( $this->mExtToMime[$ext] ) ? $this->mExtToMime[$ext] : null;
		return $r;
	}

	/**
	 * Returns a single MIME type for a given file extension or null if unknown.
	 * This is always the first type from the list returned by getTypesForExtension($ext).
	 *
	 * @param string $ext
	 * @return string|null
	 */
	public function guessTypesForExtension( $ext ) {
		$m = $this->getTypesForExtension( $ext );
		if ( is_null( $m ) ) {
			return null;
		}

		// TODO: Check if this is needed; strtok( $m, ' ' ) should be sufficient
		$m = trim( $m );
		$m = preg_replace( '/\s.*$/', '', $m );

		return $m;
	}

	/**
	 * Tests if the extension matches the given MIME type. Returns true if a
	 * match was found, null if the MIME type is unknown, and false if the
	 * MIME type is known but no matches where found.
	 *
	 * @param string $extension
	 * @param string $mime
	 * @return bool|null
	 */
	public function isMatchingExtension( $extension, $mime ) {
		$ext = $this->getExtensionsForType( $mime );

		if ( !$ext ) {
			return null; // Unknown MIME type
		}

		$ext = explode( ' ', $ext );

		$extension = strtolower( $extension );
		return in_array( $extension, $ext );
	}

	/**
	 * Returns true if the MIME type is known to represent an image format
	 * supported by the PHP GD library.
	 *
	 * @param string $mime
	 *
	 * @return bool
	 */
	public function isPHPImageType( $mime ) {
		// As defined by imagegetsize and image_type_to_mime
		static $types = [
			'image/gif', 'image/jpeg', 'image/png',
			'image/x-bmp', 'image/xbm', 'image/tiff',
			'image/jp2', 'image/jpeg2000', 'image/iff',
			'image/xbm', 'image/x-xbitmap',
			'image/vnd.wap.wbmp', 'image/vnd.xiff',
			'image/x-photoshop',
			'application/x-shockwave-flash',
		];

		return in_array( $mime, $types );
	}

	/**
	 * Returns true if the extension represents a type which can
	 * be reliably detected from its content. Use this to determine
	 * whether strict content checks should be applied to reject
	 * invalid uploads; if we can't identify the type we won't
	 * be able to say if it's invalid.
	 *
	 * @todo Be more accurate when using fancy MIME detector plugins;
	 *       right now this is the bare minimum getimagesize() list.
	 * @param string $extension
	 * @return bool
	 */
	function isRecognizableExtension( $extension ) {
		static $types = [
			// Types recognized by getimagesize()
			'gif', 'jpeg', 'jpg', 'png', 'swf', 'psd',
			'bmp', 'tiff', 'tif', 'jpc', 'jp2',
			'jpx', 'jb2', 'swc', 'iff', 'wbmp',
			'xbm',

			// Formats we recognize magic numbers for
			'djvu', 'ogx', 'ogg', 'ogv', 'oga', 'spx',
			'mid', 'pdf', 'wmf', 'xcf', 'webm', 'mkv', 'mka',
			'webp',

			// XML formats we sure hope we recognize reliably
			'svg',
		];
		return in_array( strtolower( $extension ), $types );
	}

	/**
	 * Improves a MIME type using the file extension. Some file formats are very generic,
	 * so their MIME type is not very meaningful. A more useful MIME type can be derived
	 * by looking at the file extension. Typically, this method would be called on the
	 * result of guessMimeType().
	 *
	 * @param string $mime The MIME type, typically guessed from a file's content.
	 * @param string $ext The file extension, as taken from the file name
	 *
	 * @return string The MIME type
	 */
	public function improveTypeFromExtension( $mime, $ext ) {
		if ( $mime === 'unknown/unknown' ) {
			if ( $this->isRecognizableExtension( $ext ) ) {
				wfDebug( __METHOD__ . ': refusing to guess mime type for .' .
					"$ext file, we should have recognized it\n" );
			} else {
				// Not something we can detect, so simply
				// trust the file extension
				$mime = $this->guessTypesForExtension( $ext );
			}
		} elseif ( $mime === 'application/x-opc+zip' ) {
			if ( $this->isMatchingExtension( $ext, $mime ) ) {
				// A known file extension for an OPC file,
				// find the proper MIME type for that file extension
				$mime = $this->guessTypesForExtension( $ext );
			} else {
				wfDebug( __METHOD__ . ": refusing to guess better type for $mime file, " .
					".$ext is not a known OPC extension.\n" );
				$mime = 'application/zip';
			}
		} elseif ( $mime === 'text/plain' && $this->findMediaType( ".$ext" ) === MEDIATYPE_TEXT ) {
			// Textual types are sometimes not recognized properly.
			// If detected as text/plain, and has an extension which is textual
			// improve to the extension's type. For example, csv and json are often
			// misdetected as text/plain.
			$mime = $this->guessTypesForExtension( $ext );
		}

		# Media handling extensions can improve the MIME detected
		Hooks::run( 'MimeMagicImproveFromExtension', [ $this, $ext, &$mime ] );

		if ( isset( $this->mMimeTypeAliases[$mime] ) ) {
			$mime = $this->mMimeTypeAliases[$mime];
		}

		wfDebug( __METHOD__ . ": improved mime type for .$ext: $mime\n" );
		return $mime;
	}

	/**
	 * MIME type detection. This uses detectMimeType to detect the MIME type
	 * of the file, but applies additional checks to determine some well known
	 * file formats that may be missed or misinterpreted by the default MIME
	 * detection (namely XML based formats like XHTML or SVG, as well as ZIP
	 * based formats like OPC/ODF files).
	 *
	 * @param string $file The file to check
	 * @param string|bool $ext The file extension, or true (default) to extract it from the filename.
	 *   Set it to false to ignore the extension. DEPRECATED! Set to false, use
	 *   improveTypeFromExtension($mime, $ext) later to improve MIME type.
	 *
	 * @return string The MIME type of $file
	 */
	public function guessMimeType( $file, $ext = true ) {
		if ( $ext ) { // TODO: make $ext default to false. Or better, remove it.
			wfDebug( __METHOD__ . ": WARNING: use of the \$ext parameter is deprecated. " .
				"Use improveTypeFromExtension(\$mime, \$ext) instead.\n" );
		}

		$mime = $this->doGuessMimeType( $file, $ext );

		if ( !$mime ) {
			wfDebug( __METHOD__ . ": internal type detection failed for $file (.$ext)...\n" );
			$mime = $this->detectMimeType( $file, $ext );
		}

		if ( isset( $this->mMimeTypeAliases[$mime] ) ) {
			$mime = $this->mMimeTypeAliases[$mime];
		}

		wfDebug( __METHOD__ . ": guessed mime type of $file: $mime\n" );
		return $mime;
	}

	/**
	 * Guess the MIME type from the file contents.
	 *
	 * @todo Remove $ext param
	 *
	 * @param string $file
	 * @param mixed $ext
	 * @return bool|string
	 * @throws MWException
	 */
	private function doGuessMimeType( $file, $ext ) {
		// Read a chunk of the file
		MediaWiki\suppressWarnings();
		$f = fopen( $file, 'rb' );
		MediaWiki\restoreWarnings();

		if ( !$f ) {
			return 'unknown/unknown';
		}

		$fsize = filesize( $file );
		if ( $fsize === false ) {
			return 'unknown/unknown';
		}

		$head = fread( $f, 1024 );
		$tailLength = min( 65558, $fsize ); // 65558 = maximum size of a zip EOCDR
		if ( fseek( $f, -1 * $tailLength, SEEK_END ) === -1 ) {
			throw new MWException(
				"Seeking $tailLength bytes from EOF failed in " . __METHOD__ );
		}
		$tail = $tailLength ? fread( $f, $tailLength ) : '';
		fclose( $f );

		wfDebug( __METHOD__ . ": analyzing head and tail of $file for magic numbers.\n" );

		// Hardcode a few magic number checks...
		$headers = [
			// Multimedia...
			'MThd'             => 'audio/midi',
			'OggS'             => 'application/ogg',

			// Image formats...
			// Note that WMF may have a bare header, no magic number.
			"\x01\x00\x09\x00" => 'application/x-msmetafile', // Possibly prone to false positives?
			"\xd7\xcd\xc6\x9a" => 'application/x-msmetafile',
			'%PDF'             => 'application/pdf',
			'gimp xcf'         => 'image/x-xcf',

			// Some forbidden fruit...
			'MZ'               => 'application/octet-stream', // DOS/Windows executable
			"\xca\xfe\xba\xbe" => 'application/octet-stream', // Mach-O binary
			"\x7fELF"          => 'application/octet-stream', // ELF binary
		];

		foreach ( $headers as $magic => $candidate ) {
			if ( strncmp( $head, $magic, strlen( $magic ) ) == 0 ) {
				wfDebug( __METHOD__ . ": magic header in $file recognized as $candidate\n" );
				return $candidate;
			}
		}

		/* Look for WebM and Matroska files */
		if ( strncmp( $head, pack( "C4", 0x1a, 0x45, 0xdf, 0xa3 ), 4 ) == 0 ) {
			$doctype = strpos( $head, "\x42\x82" );
			if ( $doctype ) {
				// Next byte is datasize, then data (sizes larger than 1 byte are very stupid muxers)
				$data = substr( $head, $doctype + 3, 8 );
				if ( strncmp( $data, "matroska", 8 ) == 0 ) {
					wfDebug( __METHOD__ . ": recognized file as video/x-matroska\n" );
					return "video/x-matroska";
				} elseif ( strncmp( $data, "webm", 4 ) == 0 ) {
					wfDebug( __METHOD__ . ": recognized file as video/webm\n" );
					return "video/webm";
				}
			}
			wfDebug( __METHOD__ . ": unknown EBML file\n" );
			return "unknown/unknown";
		}

		/* Look for WebP */
		if ( strncmp( $head, "RIFF", 4 ) == 0 && strncmp( substr( $head, 8, 7 ), "WEBPVP8", 7 ) == 0 ) {
			wfDebug( __METHOD__ . ": recognized file as image/webp\n" );
			return "image/webp";
		}

		/**
		 * Look for PHP.  Check for this before HTML/XML...  Warning: this is a
		 * heuristic, and won't match a file with a lot of non-PHP before.  It
		 * will also match text files which could be PHP. :)
		 *
		 * @todo FIXME: For this reason, the check is probably useless -- an attacker
		 * could almost certainly just pad the file with a lot of nonsense to
		 * circumvent the check in any case where it would be a security
		 * problem.  On the other hand, it causes harmful false positives (bug
		 * 16583).  The heuristic has been cut down to exclude three-character
		 * strings like "<? ", but should it be axed completely?
		 */
		if ( ( strpos( $head, '<?php' ) !== false ) ||
			( strpos( $head, "<\x00?\x00p\x00h\x00p" ) !== false ) ||
			( strpos( $head, "<\x00?\x00 " ) !== false ) ||
			( strpos( $head, "<\x00?\x00\n" ) !== false ) ||
			( strpos( $head, "<\x00?\x00\t" ) !== false ) ||
			( strpos( $head, "<\x00?\x00=" ) !== false ) ) {

			wfDebug( __METHOD__ . ": recognized $file as application/x-php\n" );
			return 'application/x-php';
		}

		/**
		 * look for XML formats (XHTML and SVG)
		 */
		$xml = new XmlTypeCheck( $file );
		if ( $xml->wellFormed ) {
			$xmlMimeTypes = $this->mConfig->get( 'XMLMimeTypes' );
			if ( isset( $xmlMimeTypes[$xml->getRootElement()] ) ) {
				return $xmlMimeTypes[$xml->getRootElement()];
			} else {
				return 'application/xml';
			}
		}

		/**
		 * look for shell scripts
		 */
		$script_type = null;

		# detect by shebang
		if ( substr( $head, 0, 2 ) == "#!" ) {
			$script_type = "ASCII";
		} elseif ( substr( $head, 0, 5 ) == "\xef\xbb\xbf#!" ) {
			$script_type = "UTF-8";
		} elseif ( substr( $head, 0, 7 ) == "\xfe\xff\x00#\x00!" ) {
			$script_type = "UTF-16BE";
		} elseif ( substr( $head, 0, 7 ) == "\xff\xfe#\x00!" ) {
			$script_type = "UTF-16LE";
		}

		if ( $script_type ) {
			if ( $script_type !== "UTF-8" && $script_type !== "ASCII" ) {
				// Quick and dirty fold down to ASCII!
				$pack = [ 'UTF-16BE' => 'n*', 'UTF-16LE' => 'v*' ];
				$chars = unpack( $pack[$script_type], substr( $head, 2 ) );
				$head = '';
				foreach ( $chars as $codepoint ) {
					if ( $codepoint < 128 ) {
						$head .= chr( $codepoint );
					} else {
						$head .= '?';
					}
				}
			}

			$match = [];

			if ( preg_match( '%/?([^\s]+/)(\w+)%', $head, $match ) ) {
				$mime = "application/x-{$match[2]}";
				wfDebug( __METHOD__ . ": shell script recognized as $mime\n" );
				return $mime;
			}
		}

		// Check for ZIP variants (before getimagesize)
		if ( strpos( $tail, "PK\x05\x06" ) !== false ) {
			wfDebug( __METHOD__ . ": ZIP header present in $file\n" );
			return $this->detectZipType( $head, $tail, $ext );
		}

		MediaWiki\suppressWarnings();
		$gis = getimagesize( $file );
		MediaWiki\restoreWarnings();

		if ( $gis && isset( $gis['mime'] ) ) {
			$mime = $gis['mime'];
			wfDebug( __METHOD__ . ": getimagesize detected $file as $mime\n" );
			return $mime;
		}

		// Also test DjVu
		$deja = new DjVuImage( $file );
		if ( $deja->isValid() ) {
			wfDebug( __METHOD__ . ": detected $file as image/vnd.djvu\n" );
			return 'image/vnd.djvu';
		}

		# Media handling extensions can guess the MIME by content
		# It's intentionally here so that if core is wrong about a type (false positive),
		# people will hopefully nag and submit patches :)
		$mime = false;
		# Some strings by reference for performance - assuming well-behaved hooks
		Hooks::run(
			'MimeMagicGuessFromContent',
			[ $this, &$head, &$tail, $file, &$mime ]
		);

		return $mime;
	}

	/**
	 * Detect application-specific file type of a given ZIP file from its
	 * header data.  Currently works for OpenDocument and OpenXML types...
	 * If can't tell, returns 'application/zip'.
	 *
	 * @param string $header Some reasonably-sized chunk of file header
	 * @param string|null $tail The tail of the file
	 * @param string|bool $ext The file extension, or true to extract it from the filename.
	 *   Set it to false (default) to ignore the extension. DEPRECATED! Set to false,
	 *   use improveTypeFromExtension($mime, $ext) later to improve MIME type.
	 *
	 * @return string
	 */
	function detectZipType( $header, $tail = null, $ext = false ) {
		if ( $ext ) { # TODO: remove $ext param
			wfDebug( __METHOD__ . ": WARNING: use of the \$ext parameter is deprecated. " .
				"Use improveTypeFromExtension(\$mime, \$ext) instead.\n" );
		}

		$mime = 'application/zip';
		$opendocTypes = [
			'chart-template',
			'chart',
			'formula-template',
			'formula',
			'graphics-template',
			'graphics',
			'image-template',
			'image',
			'presentation-template',
			'presentation',
			'spreadsheet-template',
			'spreadsheet',
			'text-template',
			'text-master',
			'text-web',
			'text' ];

		// http://lists.oasis-open.org/archives/office/200505/msg00006.html
		$types = '(?:' . implode( '|', $opendocTypes ) . ')';
		$opendocRegex = "/^mimetype(application\/vnd\.oasis\.opendocument\.$types)/";

		$openxmlRegex = "/^\[Content_Types\].xml/";

		if ( preg_match( $opendocRegex, substr( $header, 30 ), $matches ) ) {
			$mime = $matches[1];
			wfDebug( __METHOD__ . ": detected $mime from ZIP archive\n" );
		} elseif ( preg_match( $openxmlRegex, substr( $header, 30 ) ) ) {
			$mime = "application/x-opc+zip";
			# TODO: remove the block below, as soon as improveTypeFromExtension is used everywhere
			if ( $ext !== true && $ext !== false ) {
				/** This is the mode used by getPropsFromPath
				 * These MIME's are stored in the database, where we don't really want
				 * x-opc+zip, because we use it only for internal purposes
				 */
				if ( $this->isMatchingExtension( $ext, $mime ) ) {
					/* A known file extension for an OPC file,
					 * find the proper mime type for that file extension
					 */
					$mime = $this->guessTypesForExtension( $ext );
				} else {
					$mime = "application/zip";
				}
			}
			wfDebug( __METHOD__ . ": detected an Open Packaging Conventions archive: $mime\n" );
		} elseif ( substr( $header, 0, 8 ) == "\xd0\xcf\x11\xe0\xa1\xb1\x1a\xe1" &&
				( $headerpos = strpos( $tail, "PK\x03\x04" ) ) !== false &&
				preg_match( $openxmlRegex, substr( $tail, $headerpos + 30 ) ) ) {
			if ( substr( $header, 512, 4 ) == "\xEC\xA5\xC1\x00" ) {
				$mime = "application/msword";
			}
			switch ( substr( $header, 512, 6 ) ) {
				case "\xEC\xA5\xC1\x00\x0E\x00":
				case "\xEC\xA5\xC1\x00\x1C\x00":
				case "\xEC\xA5\xC1\x00\x43\x00":
					$mime = "application/vnd.ms-powerpoint";
					break;
				case "\xFD\xFF\xFF\xFF\x10\x00":
				case "\xFD\xFF\xFF\xFF\x1F\x00":
				case "\xFD\xFF\xFF\xFF\x22\x00":
				case "\xFD\xFF\xFF\xFF\x23\x00":
				case "\xFD\xFF\xFF\xFF\x28\x00":
				case "\xFD\xFF\xFF\xFF\x29\x00":
				case "\xFD\xFF\xFF\xFF\x10\x02":
				case "\xFD\xFF\xFF\xFF\x1F\x02":
				case "\xFD\xFF\xFF\xFF\x22\x02":
				case "\xFD\xFF\xFF\xFF\x23\x02":
				case "\xFD\xFF\xFF\xFF\x28\x02":
				case "\xFD\xFF\xFF\xFF\x29\x02":
					$mime = "application/vnd.msexcel";
					break;
			}

			wfDebug( __METHOD__ . ": detected a MS Office document with OPC trailer\n" );
		} else {
			wfDebug( __METHOD__ . ": unable to identify type of ZIP archive\n" );
		}
		return $mime;
	}

	/**
	 * Internal MIME type detection. Detection is done using an external
	 * program, if $wgMimeDetectorCommand is set. Otherwise, the fileinfo
	 * extension is tried if it is available. If detection fails and $ext
	 * is not false, the MIME type is guessed from the file extension,
	 * using guessTypesForExtension.
	 *
	 * If the MIME type is still unknown, getimagesize is used to detect the
	 * MIME type if the file is an image. If no MIME type can be determined,
	 * this function returns 'unknown/unknown'.
	 *
	 * @param string $file The file to check
	 * @param string|bool $ext The file extension, or true (default) to extract it from the filename.
	 *   Set it to false to ignore the extension. DEPRECATED! Set to false, use
	 *   improveTypeFromExtension($mime, $ext) later to improve MIME type.
	 *
	 * @return string The MIME type of $file
	 */
	private function detectMimeType( $file, $ext = true ) {
		/** @todo Make $ext default to false. Or better, remove it. */
		if ( $ext ) {
			wfDebug( __METHOD__ . ": WARNING: use of the \$ext parameter is deprecated. "
				. "Use improveTypeFromExtension(\$mime, \$ext) instead.\n" );
		}

		$mimeDetectorCommand = $this->mConfig->get( 'MimeDetectorCommand' );
		$m = null;
		if ( $mimeDetectorCommand ) {
			$args = wfEscapeShellArg( $file );
			$m = wfShellExec( "$mimeDetectorCommand $args" );
		} elseif ( function_exists( "finfo_open" ) && function_exists( "finfo_file" ) ) {
			$mime_magic_resource = finfo_open( FILEINFO_MIME );

			if ( $mime_magic_resource ) {
				$m = finfo_file( $mime_magic_resource, $file );
				finfo_close( $mime_magic_resource );
			} else {
				wfDebug( __METHOD__ . ": finfo_open failed on " . FILEINFO_MIME . "!\n" );
			}
		} else {
			wfDebug( __METHOD__ . ": no magic mime detector found!\n" );
		}

		if ( $m ) {
			# normalize
			$m = preg_replace( '![;, ].*$!', '', $m ); # strip charset, etc
			$m = trim( $m );
			$m = strtolower( $m );

			if ( strpos( $m, 'unknown' ) !== false ) {
				$m = null;
			} else {
				wfDebug( __METHOD__ . ": magic mime type of $file: $m\n" );
				return $m;
			}
		}

		// If desired, look at extension as a fallback.
		if ( $ext === true ) {
			$i = strrpos( $file, '.' );
			$ext = strtolower( $i ? substr( $file, $i + 1 ) : '' );
		}
		if ( $ext ) {
			if ( $this->isRecognizableExtension( $ext ) ) {
				wfDebug( __METHOD__ . ": refusing to guess mime type for .$ext file, "
					. "we should have recognized it\n" );
			} else {
				$m = $this->guessTypesForExtension( $ext );
				if ( $m ) {
					wfDebug( __METHOD__ . ": extension mime type of $file: $m\n" );
					return $m;
				}
			}
		}

		// Unknown type
		wfDebug( __METHOD__ . ": failed to guess mime type for $file!\n" );
		return 'unknown/unknown';
	}

	/**
	 * Determine the media type code for a file, using its MIME type, name and
	 * possibly its contents.
	 *
	 * This function relies on the findMediaType(), mapping extensions and MIME
	 * types to media types.
	 *
	 * @todo analyse file if need be
	 * @todo look at multiple extension, separately and together.
	 *
	 * @param string $path Full path to the image file, in case we have to look at the contents
	 *        (if null, only the MIME type is used to determine the media type code).
	 * @param string $mime MIME type. If null it will be guessed using guessMimeType.
	 *
	 * @return string A value to be used with the MEDIATYPE_xxx constants.
	 */
	function getMediaType( $path = null, $mime = null ) {
		if ( !$mime && !$path ) {
			return MEDIATYPE_UNKNOWN;
		}

		// If MIME type is unknown, guess it
		if ( !$mime ) {
			$mime = $this->guessMimeType( $path, false );
		}

		// Special code for ogg - detect if it's video (theora),
		// else label it as sound.
		if ( $mime == 'application/ogg' && file_exists( $path ) ) {

			// Read a chunk of the file
			$f = fopen( $path, "rt" );
			if ( !$f ) {
				return MEDIATYPE_UNKNOWN;
			}
			$head = fread( $f, 256 );
			fclose( $f );

			$head = str_replace( 'ffmpeg2theora', '', strtolower( $head ) );

			// This is an UGLY HACK, file should be parsed correctly
			if ( strpos( $head, 'theora' ) !== false ) {
				return MEDIATYPE_VIDEO;
			} elseif ( strpos( $head, 'vorbis' ) !== false ) {
				return MEDIATYPE_AUDIO;
			} elseif ( strpos( $head, 'flac' ) !== false ) {
				return MEDIATYPE_AUDIO;
			} elseif ( strpos( $head, 'speex' ) !== false ) {
				return MEDIATYPE_AUDIO;
			} else {
				return MEDIATYPE_MULTIMEDIA;
			}
		}

		// Check for entry for full MIME type
		if ( $mime ) {
			$type = $this->findMediaType( $mime );
			if ( $type !== MEDIATYPE_UNKNOWN ) {
				return $type;
			}
		}

		// Check for entry for file extension
		if ( $path ) {
			$i = strrpos( $path, '.' );
			$e = strtolower( $i ? substr( $path, $i + 1 ) : '' );

			// TODO: look at multi-extension if this fails, parse from full path
			$type = $this->findMediaType( '.' . $e );
			if ( $type !== MEDIATYPE_UNKNOWN ) {
				return $type;
			}
		}

		// Check major MIME type
		if ( $mime ) {
			$i = strpos( $mime, '/' );
			if ( $i !== false ) {
				$major = substr( $mime, 0, $i );
				$type = $this->findMediaType( $major );
				if ( $type !== MEDIATYPE_UNKNOWN ) {
					return $type;
				}
			}
		}

		if ( !$type ) {
			$type = MEDIATYPE_UNKNOWN;
		}

		return $type;
	}

	/**
	 * Returns a media code matching the given MIME type or file extension.
	 * File extensions are represented by a string starting with a dot (.) to
	 * distinguish them from MIME types.
	 *
	 * This function relies on the mapping defined by $this->mMediaTypes
	 * @access private
	 * @param string $extMime
	 * @return int|string
	 */
	function findMediaType( $extMime ) {
		if ( strpos( $extMime, '.' ) === 0 ) {
			// If it's an extension, look up the MIME types
			$m = $this->getTypesForExtension( substr( $extMime, 1 ) );
			if ( !$m ) {
				return MEDIATYPE_UNKNOWN;
			}

			$m = explode( ' ', $m );
		} else {
			// Normalize MIME type
			if ( isset( $this->mMimeTypeAliases[$extMime] ) ) {
				$extMime = $this->mMimeTypeAliases[$extMime];
			}

			$m = [ $extMime ];
		}

		foreach ( $m as $mime ) {
			foreach ( $this->mMediaTypes as $type => $codes ) {
				if ( in_array( $mime, $codes, true ) ) {
					return $type;
				}
			}
		}

		return MEDIATYPE_UNKNOWN;
	}

	/**
	 * Get the MIME types that various versions of Internet Explorer would
	 * detect from a chunk of the content.
	 *
	 * @param string $fileName The file name (unused at present)
	 * @param string $chunk The first 256 bytes of the file
	 * @param string $proposed The MIME type proposed by the server
	 * @return array
	 */
	public function getIEMimeTypes( $fileName, $chunk, $proposed ) {
		$ca = $this->getIEContentAnalyzer();
		return $ca->getRealMimesFromData( $fileName, $chunk, $proposed );
	}

	/**
	 * Get a cached instance of IEContentAnalyzer
	 *
	 * @return IEContentAnalyzer
	 */
	protected function getIEContentAnalyzer() {
		if ( is_null( $this->mIEAnalyzer ) ) {
			$this->mIEAnalyzer = new IEContentAnalyzer;
		}
		return $this->mIEAnalyzer;
	}
}
