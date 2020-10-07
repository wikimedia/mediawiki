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
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\Mime\MimeMap;
use Wikimedia\Mime\MimeMapMinimal;

/**
 * Implements functions related to MIME types such as detection and mapping to file extension
 *
 * @since 1.28
 */
class MimeAnalyzer implements LoggerAwareInterface {
	/** @var string */
	protected $typeFile;
	/** @var string */
	protected $infoFile;
	/** @var string */
	protected $xmlTypes;
	/** @var callable */
	protected $initCallback;
	/** @var callable */
	protected $detectCallback;
	/** @var callable */
	protected $guessCallback;
	/** @var callable */
	protected $extCallback;
	/** @var array Mapping of media types to arrays of MIME types */
	protected $mediaTypes = null;
	/** @var array Map of MIME type aliases */
	protected $mimeTypeAliases = null;
	/** @var array<string,string[]> Map of MIME types to file extensions */
	protected $mimeToExts = [];
	/** @var array<string,string[]> Map of file extensions to MIME types */
	protected $extToMimes = [];

	/** @var array Map of file extensions types to MIME types (as a space separated list) */
	public $mExtToMime = []; // legacy name; field accessed by hooks

	/** @var IEContentAnalyzer */
	protected $IEAnalyzer;

	/** @var string Extra MIME types, set for example by media handling extensions */
	private $extraTypes = '';
	/** @var string Extra MIME info, set for example by media handling extensions */
	private $extraInfo = '';

	/** @var LoggerInterface */
	private $logger;

	/** @var string Use the full, built-in MIME mapping rather than load from a file */
	public const USE_INTERNAL = 'internal';

	/**
	 * @param array $params Configuration map, includes:
	 *   - typeFile: path to file with the list of known MIME types
	 *   - infoFile: path to file with the MIME type info
	 *   - xmlTypes: map of root element names to XML MIME types
	 *   - initCallback: initialization callback that is passed this object [optional]
	 *   - detectCallback: alternative to finfo that returns the mime type for a file.
	 *      For example, the callback can return the output of "file -bi". [optional]
	 *   - guessCallback: callback to improve the guessed MIME type using the file data.
	 *      This is intended for fixing mistakes in fileinfo or "detectCallback". [optional]
	 *   - extCallback: callback to improve the guessed MIME type using the extension. [optional]
	 *   - logger: PSR-3 logger [optional]
	 * @note Constructing these instances is expensive due to file reads.
	 *  A service or singleton pattern should be used to avoid creating instances again and again.
	 */
	public function __construct( array $params ) {
		$this->typeFile = $params['typeFile'];
		$this->infoFile = $params['infoFile'];
		$this->xmlTypes = $params['xmlTypes'];
		$this->initCallback = $params['initCallback'] ?? null;
		$this->detectCallback = $params['detectCallback'] ?? null;
		$this->guessCallback = $params['guessCallback'] ?? null;
		$this->extCallback = $params['extCallback'] ?? null;
		$this->logger = $params['logger'] ?? new NullLogger();

		$this->loadFiles();
	}

	protected function loadFiles() {
		# Allow media handling extensions adding MIME-types and MIME-info
		if ( $this->initCallback ) {
			call_user_func( $this->initCallback, $this );
		}

		$rawTypes = $this->extraTypes;
		if ( $this->typeFile === self::USE_INTERNAL ) {
			$this->mimeToExts = MimeMap::MIME_EXTENSIONS;
		} else {
			$this->mimeToExts = MimeMapMinimal::MIME_EXTENSIONS;
			if ( $this->typeFile ) {
				$rawTypes = file_get_contents( $this->typeFile ) . "\n" . $this->extraTypes;
			}
		}
		if ( $rawTypes ) {
			$this->parseMimeTypes( $rawTypes );
		}

		// Build the reverse mapping (extension => MIME type).
		foreach ( $this->mimeToExts as $mime => $exts ) {
			foreach ( $exts as $ext ) {
				$this->extToMimes[$ext][] = $mime;
			}
		}

		// Migrate items from the legacy $this->mExtToMime field.
		// TODO: Remove this when mExtToMime is finally removed.
		foreach ( $this->mExtToMime as $ext => $mimes ) {
			foreach ( explode( ' ', $mimes ) as $mime ) {
				$this->extToMimes[$ext][] = $mime;
			}
		}

		$rawInfo = $this->extraInfo;
		if ( $this->infoFile === self::USE_INTERNAL ) {
			$this->mimeTypeAliases = MimeMap::MIME_TYPE_ALIASES;
			$this->mediaTypes = MimeMap::MEDIA_TYPES;
		} else {
			$this->mimeTypeAliases = MimeMapMinimal::MIME_TYPE_ALIASES;
			$this->mediaTypes = MimeMapMinimal::MEDIA_TYPES;
			if ( $this->infoFile ) {
				$rawInfo = file_get_contents( $this->infoFile ) . "\n" . $this->extraInfo;
			}
		}
		if ( $rawInfo ) {
			$this->parseMimeInfo( $rawInfo );
		}
	}

	protected function parseMimeTypes( $rawMimeTypes ) {
		$rawMimeTypes = str_replace( [ "\r\n", "\n\r", "\n\n", "\r\r", "\r" ], "\n", $rawMimeTypes );
		$rawMimeTypes = str_replace( "\t", " ", $rawMimeTypes );

		$lines = explode( "\n", $rawMimeTypes );
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

			$tokens = preg_split( '/\s+/', $s, -1, PREG_SPLIT_NO_EMPTY );
			if ( count( $tokens ) > 1 ) {
				$mime = array_shift( $tokens );
				$this->mimeToExts[$mime] = array_values( array_unique(
					array_merge( $this->mimeToExts[$mime] ?? [], $tokens ) ) );
			}
		}
	}

	protected function parseMimeInfo( $rawMimeInfo ) {
		$rawMimeInfo = str_replace( [ "\r\n", "\n\r", "\n\n", "\r\r", "\r" ], "\n", $rawMimeInfo );
		$rawMimeInfo = str_replace( "\t", " ", $rawMimeInfo );

		$lines = explode( "\n", $rawMimeInfo );
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

			$m = preg_split( '/\s+/', $s, -1, PREG_SPLIT_NO_EMPTY );

			if ( !isset( $this->mediaTypes[$mtype] ) ) {
				$this->mediaTypes[$mtype] = [];
			}

			foreach ( $m as $mime ) {
				$mime = trim( $mime );
				if ( empty( $mime ) ) {
					continue;
				}

				$this->mediaTypes[$mtype][] = $mime;
			}

			if ( count( $m ) > 1 ) {
				$main = $m[0];
				$mCount = count( $m );
				for ( $i = 1; $i < $mCount; $i += 1 ) {
					$mime = $m[$i];
					$this->mimeTypeAliases[$mime] = $main;
				}
			}
		}
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Adds to the list mapping MIME to file extensions.
	 * As an extension author, you are encouraged to submit patches to
	 * MediaWiki's core to add new MIME types to MimeMap.php.
	 * @param string $types
	 */
	public function addExtraTypes( $types ) {
		$this->extraTypes .= "\n" . $types;
	}

	/**
	 * Adds to the list mapping MIME to media type.
	 * As an extension author, you are encouraged to submit patches to
	 * MediaWiki's core to add new MIME info to MimeMap.php.
	 * @param string $info
	 */
	public function addExtraInfo( $info ) {
		$this->extraInfo .= "\n" . $info;
	}

	/**
	 * Returns a list of file extensions for a given MIME type as a space
	 * separated string or null if the MIME type was unrecognized. Resolves
	 * MIME type aliases.
	 *
	 * @deprecated since 1.35 Use getExtensionsFromMimeType() instead.
	 * @param string $mime
	 * @return string|null
	 */
	public function getExtensionsForType( $mime ) {
		$exts = $this->getExtensionsFromMimeType( $mime );
		return $exts ? implode( ' ', $exts ) : null;
	}

	/**
	 * Returns an array of file extensions associated with a given MIME type.
	 * The returned array is empty if the MIME type was unrecognized. Resolves
	 * MIME type aliases.
	 *
	 * @since 1.35
	 * @param string $mime
	 * @return array
	 */
	public function getExtensionsFromMimeType( $mime ) {
		$mime = strtolower( $mime );
		if ( !isset( $this->mimeToExts[$mime] ) && isset( $this->mimeTypeAliases[$mime] ) ) {
			$mime = $this->mimeTypeAliases[$mime];
		}
		return $this->mimeToExts[$mime] ?? [];
	}

	/**
	 * Returns an array of MIME types associated with a given file extension.
	 * The returned array is empty if the file extension is not associated with
	 * any MIME types.
	 *
	 * @since 1.35
	 * @param string $ext
	 * @return array
	 */
	public function getMimeTypesFromExtension( $ext ) {
		$ext = strtolower( $ext );
		return $this->extToMimes[$ext] ?? [];
	}

	/**
	 * Returns a single MIME type for a given file extension or null if unknown.
	 * This is always the first type from the list returned by getMimeTypesFromExtension($ext).
	 *
	 * @since 1.35
	 * @param string $ext
	 * @return string|null
	 */
	public function getMimeTypeFromExtensionOrNull( $ext ) {
		$types = $this->getMimeTypesFromExtension( $ext );
		return $types[0] ?? null;
	}

	/**
	 * Returns a single file extension for a given MIME type or null if unknown.
	 * This is always the first type from the list returned by getExtensionsFromMimeType($mime).
	 *
	 * @deprecated since 1.35 Use getMimeTypeFromExtensionOrNull() instead.
	 * @param string $ext
	 * @return string|null
	 */
	public function guessTypesForExtension( $ext ) {
		return $this->getMimeTypeFromExtensionOrNull( $ext );
	}

	/**
	 * Returns a list of MIME types for a given file extension as a space
	 * separated string or null if the extension was unrecognized.
	 *
	 * @deprecated since 1.35 Use getMimeTypesFromExtension() instead.
	 * @param string $ext
	 * @return string|null
	 */
	public function getTypesForExtension( $ext ) {
		$types = $this->getMimeTypesFromExtension( $ext );
		return $types ? implode( ' ', $types ) : null;
	}

	/**
	 * Returns a single file extension for a given MIME type or null if unknown.
	 * This is always the first type from the list returned by getExtensionsFromMimeType($mime).
	 *
	 * @since 1.35
	 * @param string $mime
	 * @return string|null
	 */
	public function getExtensionFromMimeTypeOrNull( $mime ) {
		$exts = $this->getExtensionsFromMimeType( $mime );
		return $exts[0] ?? null;
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
		$exts = $this->getExtensionsFromMimeType( $mime );

		if ( !$exts ) {
			return null; // Unknown MIME type
		}

		return in_array( strtolower( $extension ), $exts );
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
	public function isRecognizableExtension( $extension ) {
		static $types = [
			// Types recognized by getimagesize()
			'gif', 'jpeg', 'jpg', 'png', 'swf', 'psd',
			'bmp', 'tiff', 'tif', 'jpc', 'jp2',
			'jpx', 'jb2', 'swc', 'iff', 'wbmp',
			'xbm',

			// Formats we recognize magic numbers for
			'djvu', 'ogx', 'ogg', 'ogv', 'oga', 'spx', 'opus',
			'mid', 'pdf', 'wmf', 'xcf', 'webm', 'mkv', 'mka',
			'webp', 'mp3',

			// XML formats we sure hope we recognize reliably
			'svg',

			// 3D formats
			'stl',
		];
		return in_array( strtolower( $extension ), $types );
	}

	/**
	 * Improves a MIME type using the file extension. Some file formats are very generic,
	 * so their MIME type is not very meaningful. A more useful MIME type can be derived
	 * by looking at the file extension. Typically, this method would be called on the
	 * result of guessMimeType().
	 *
	 * XXX: Null-returning behavior is probably an accident and definitely confusing (T253483).
	 *
	 * @param string $mime The MIME type, typically guessed from a file's content.
	 * @param string $ext The file extension, as taken from the file name
	 *
	 * @return string|null The improved MIME type, or null if the MIME type is
	 *   unknown/unknown and the extension is not recognized.
	 */
	public function improveTypeFromExtension( $mime, $ext ) {
		if ( $mime === 'unknown/unknown' ) {
			if ( $this->isRecognizableExtension( $ext ) ) {
				$this->logger->info( __METHOD__ . ': refusing to guess mime type for .' .
					"$ext file, we should have recognized it\n" );
			} else {
				// Not something we can detect, so simply
				// trust the file extension
				$mime = $this->getMimeTypeFromExtensionOrNull( $ext );
			}
		} elseif ( $mime === 'application/x-opc+zip' ) {
			if ( $this->isMatchingExtension( $ext, $mime ) ) {
				// A known file extension for an OPC file,
				// find the proper MIME type for that file extension
				$mime = $this->getMimeTypeFromExtensionOrNull( $ext );
			} else {
				$this->logger->info( __METHOD__ .
					": refusing to guess better type for $mime file, " .
					".$ext is not a known OPC extension.\n" );
				$mime = 'application/zip';
			}
		} elseif ( $mime === 'text/plain' && $this->findMediaType( ".$ext" ) === MEDIATYPE_TEXT ) {
			// Textual types are sometimes not recognized properly.
			// If detected as text/plain, and has an extension which is textual
			// improve to the extension's type. For example, csv and json are often
			// misdetected as text/plain.
			$mime = $this->getMimeTypeFromExtensionOrNull( $ext );
		}

		# Media handling extensions can improve the MIME detected
		$callback = $this->extCallback;
		if ( $callback ) {
			$callback( $this, $ext, $mime /* by reference */ );
		}

		if ( isset( $this->mimeTypeAliases[$mime] ) ) {
			$mime = $this->mimeTypeAliases[$mime];
		}

		$this->logger->info( __METHOD__ . ": improved mime type for .$ext: $mime\n" );
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
	 * @param string|bool $ext The file extension, or true (default) to extract
	 *   it from the filename. Set it to false to ignore the extension. DEPRECATED!
	 *   Set to false, use improveTypeFromExtension($mime, $ext) later to improve MIME type.
	 *
	 * @return string The MIME type of $file
	 */
	public function guessMimeType( $file, $ext = true ) {
		if ( $ext ) { // TODO: make $ext default to false. Or better, remove it.
			$this->logger->info( __METHOD__ .
				": WARNING: use of the \$ext parameter is deprecated. " .
				"Use improveTypeFromExtension(\$mime, \$ext) instead.\n" );
		}

		$mime = $this->doGuessMimeType( $file, $ext );

		if ( !$mime ) {
			$this->logger->info( __METHOD__ .
				": internal type detection failed for $file (.$ext)...\n" );
			$mime = $this->detectMimeType( $file, $ext );
		}

		if ( isset( $this->mimeTypeAliases[$mime] ) ) {
			$mime = $this->mimeTypeAliases[$mime];
		}

		$this->logger->info( __METHOD__ . ": guessed mime type of $file: $mime\n" );
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
	 * @throws UnexpectedValueException
	 */
	private function doGuessMimeType( $file, $ext ) {
		// Read a chunk of the file
		Wikimedia\suppressWarnings();
		$f = fopen( $file, 'rb' );
		Wikimedia\restoreWarnings();

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
			throw new UnexpectedValueException(
				"Seeking $tailLength bytes from EOF failed in " . __METHOD__ );
		}
		$tail = $tailLength ? fread( $f, $tailLength ) : '';

		$this->logger->info( __METHOD__ .
			": analyzing head and tail of $file for magic numbers.\n" );

		// Hardcode a few magic number checks...
		$headers = [
			// Multimedia...
			'MThd'             => 'audio/midi',
			'OggS'             => 'application/ogg',
			'ID3'              => 'audio/mpeg',
			"\xff\xfb"         => 'audio/mpeg', // MPEG-1 layer 3
			"\xff\xf3"         => 'audio/mpeg', // MPEG-2 layer 3 (lower sample rates)
			"\xff\xe3"         => 'audio/mpeg', // MPEG-2.5 layer 3 (very low sample rates)

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
				$this->logger->info( __METHOD__ .
					": magic header in $file recognized as $candidate\n" );
				return $candidate;
			}
		}

		/* Look for WebM and Matroska files */
		if ( strncmp( $head, pack( "C4", 0x1a, 0x45, 0xdf, 0xa3 ), 4 ) == 0 ) {
			$doctype = strpos( $head, "\x42\x82" );
			if ( $doctype ) {
				// Next byte is datasize, then data (sizes larger than 1 byte are stupid muxers)
				$data = substr( $head, $doctype + 3, 8 );
				if ( strncmp( $data, "matroska", 8 ) == 0 ) {
					$this->logger->info( __METHOD__ . ": recognized file as video/x-matroska\n" );
					return "video/x-matroska";
				} elseif ( strncmp( $data, "webm", 4 ) == 0 ) {
					// XXX HACK look for a video track, if we don't find it, this is an audio file
					$videotrack = strpos( $head, "\x86\x85V_VP" );

					if ( $videotrack ) {
						// There is a video track, so this is a video file.
						$this->logger->info( __METHOD__ . ": recognized file as video/webm\n" );
						return "video/webm";
					}

					$this->logger->info( __METHOD__ . ": recognized file as audio/webm\n" );
					return "audio/webm";
				}
			}
			$this->logger->info( __METHOD__ . ": unknown EBML file\n" );
			return "unknown/unknown";
		}

		/* Look for WebP */
		if ( strncmp( $head, "RIFF", 4 ) == 0 &&
			strncmp( substr( $head, 8, 7 ), "WEBPVP8", 7 ) == 0
		) {
			$this->logger->info( __METHOD__ . ": recognized file as image/webp\n" );
			return "image/webp";
		}

		/* Look for MS Compound Binary (OLE) files */
		if ( strncmp( $head, "\xd0\xcf\x11\xe0\xa1\xb1\x1a\xe1", 8 ) == 0 ) {
			$this->logger->info( __METHOD__ . ': recognized MS CFB (OLE) file' );
			return $this->detectMicrosoftBinaryType( $f );
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
			( strpos( $head, "<\x00?\x00=" ) !== false )
		) {
			$this->logger->info( __METHOD__ . ": recognized $file as application/x-php\n" );
			return 'application/x-php';
		}

		/**
		 * look for XML formats (XHTML and SVG)
		 */
		Wikimedia\suppressWarnings();
		$xml = new XmlTypeCheck( $file );
		Wikimedia\restoreWarnings();
		if ( $xml->wellFormed ) {
			$xmlTypes = $this->xmlTypes;
			return $xmlTypes[$xml->getRootElement()] ?? 'application/xml';
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
				$this->logger->info( __METHOD__ . ": shell script recognized as $mime\n" );
				return $mime;
			}
		}

		// Check for ZIP variants (before getimagesize)
		$eocdrPos = strpos( $tail, "PK\x05\x06" );
		if ( $eocdrPos !== false && $eocdrPos <= strlen( $tail ) - 22 ) {
			$this->logger->info( __METHOD__ . ": ZIP signature present in $file\n" );
			// Check if it really is a ZIP file, make sure the EOCDR is at the end (T40432)
			$commentLength = unpack( "n", substr( $tail, $eocdrPos + 20 ) )[1];
			if ( $eocdrPos + 22 + $commentLength !== strlen( $tail ) ) {
				$this->logger->info( __METHOD__ . ": ZIP EOCDR not at end. Not a ZIP file." );
			} else {
				return $this->detectZipType( $head, $tail, $ext );
			}
		}

		// Check for STL (3D) files
		// @see https://en.wikipedia.org/wiki/STL_(file_format)
		if ( $fsize >= 15 &&
			stripos( $head, 'SOLID ' ) === 0 &&
			preg_match( '/\RENDSOLID .*$/i', $tail ) ) {
			// ASCII STL file
			return 'application/sla';
		} elseif ( $fsize > 84 ) {
			// binary STL file
			$triangles = substr( $head, 80, 4 );
			$triangles = unpack( 'V', $triangles );
			$triangles = reset( $triangles );
			if ( $triangles !== false && $fsize === 84 + ( $triangles * 50 ) ) {
				return 'application/sla';
			}
		}

		Wikimedia\suppressWarnings();
		$gis = getimagesize( $file );
		Wikimedia\restoreWarnings();

		if ( $gis && isset( $gis['mime'] ) ) {
			$mime = $gis['mime'];
			$this->logger->info( __METHOD__ . ": getimagesize detected $file as $mime\n" );
			return $mime;
		}

		# Media handling extensions can guess the MIME by content
		# It's intentionally here so that if core is wrong about a type (false positive),
		# people will hopefully nag and submit patches :)
		$mime = false;
		# Some strings by reference for performance - assuming well-behaved hooks
		$callback = $this->guessCallback;
		if ( $callback ) {
			$callback( $this, $head, $tail, $file, $mime /* by reference */ );
		}

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
	public function detectZipType( $header, $tail = null, $ext = false ) {
		if ( $ext ) { # TODO: remove $ext param
			$this->logger->info( __METHOD__ .
				": WARNING: use of the \$ext parameter is deprecated. " .
				"Use improveTypeFromExtension(\$mime, \$ext) instead.\n" );
		}

		$mime = 'application/zip';
		$opendocTypes = [
			# In OASIS Open Document Format v1.2, Database front end document
			# has a recommended MIME type of:
			# application/vnd.oasis.opendocument.base
			# Despite the type registered at the IANA being 'database' which is
			# supposed to be normative.
			# T35515
			'base',

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

		// The list of document types is available in OASIS Open Document
		// Format version 1.2 under Appendix C. It is not normative though,
		// supposedly types registered at the IANA should be.
		// http://docs.oasis-open.org/office/v1.2/os/OpenDocument-v1.2-os-part1.html
		$types = '(?:' . implode( '|', $opendocTypes ) . ')';
		$opendocRegex = "/^mimetype(application\/vnd\.oasis\.opendocument\.$types)/";

		$openxmlRegex = "/^\[Content_Types\].xml/";

		if ( preg_match( $opendocRegex, substr( $header, 30 ), $matches ) ) {
			$mime = $matches[1];
			$this->logger->info( __METHOD__ . ": detected $mime from ZIP archive\n" );
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
					$mime = $this->getMimeTypeFromExtensionOrNull( $ext );
				} else {
					$mime = "application/zip";
				}
			}
			$this->logger->info( __METHOD__ .
				": detected an Open Packaging Conventions archive: $mime\n" );
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

			$this->logger->info( __METHOD__ .
				": detected a MS Office document with OPC trailer\n" );
		} else {
			$this->logger->info( __METHOD__ . ": unable to identify type of ZIP archive\n" );
		}
		return $mime;
	}

	/**
	 * Detect the type of a Microsoft Compound Binary a.k.a. OLE file.
	 * These are old style pre-ODF files such as .doc and .xls
	 *
	 * @param resource $handle An opened seekable file handle
	 * @return string The detected MIME type
	 */
	private function detectMicrosoftBinaryType( $handle ) {
		$info = MSCompoundFileReader::readHandle( $handle );
		if ( !$info['valid'] ) {
			$this->logger->info( __METHOD__ . ': invalid file format' );
			return 'unknown/unknown';
		}
		if ( !$info['mime'] ) {
			$this->logger->info( __METHOD__ . ": unrecognised document subtype" );
			return 'unknown/unknown';
		}
		return $info['mime'];
	}

	/**
	 * Internal MIME type detection. Detection is done using the fileinfo
	 * extension if it is available. It can be overriden by callback, which could
	 * use an external program, for example. If detection fails and $ext is not false,
	 * the MIME type is guessed from the file extension, using getMimeTypeFromExtensionOrNull.
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
			$this->logger->info( __METHOD__ .
				": WARNING: use of the \$ext parameter is deprecated. "
				. "Use improveTypeFromExtension(\$mime, \$ext) instead.\n" );
		}

		$callback = $this->detectCallback;
		$m = null;
		if ( $callback ) {
			$m = $callback( $file );
		} else {
			$m = mime_content_type( $file );
		}

		if ( $m ) {
			# normalize
			$m = preg_replace( '![;, ].*$!', '', $m ); # strip charset, etc
			$m = trim( $m );
			$m = strtolower( $m );

			if ( strpos( $m, 'unknown' ) !== false ) {
				$m = null;
			} else {
				$this->logger->info( __METHOD__ . ": magic mime type of $file: $m\n" );
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
				$this->logger->info( __METHOD__ . ": refusing to guess mime type for .$ext file, "
					. "we should have recognized it\n" );
			} else {
				$m = $this->getMimeTypeFromExtensionOrNull( $ext );
				if ( $m ) {
					$this->logger->info( __METHOD__ . ": extension mime type of $file: $m\n" );
					return $m;
				}
			}
		}

		// Unknown type
		$this->logger->info( __METHOD__ . ": failed to guess mime type for $file!\n" );
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
	 * @param string|null $path Full path to the image file, in case we have to look at the contents
	 *        (if null, only the MIME type is used to determine the media type code).
	 * @param string|null $mime MIME type. If null it will be guessed using guessMimeType.
	 *
	 * @return string A value to be used with the MEDIATYPE_xxx constants.
	 */
	public function getMediaType( $path = null, $mime = null ) {
		if ( !$mime && !$path ) {
			return MEDIATYPE_UNKNOWN;
		}

		// If MIME type is unknown, guess it
		if ( !$mime ) {
			$mime = $this->guessMimeType( $path, false );
		}

		// Special code for ogg - detect if it's video (theora),
		// else label it as sound.
		if ( $mime == 'application/ogg' && is_string( $path ) && file_exists( $path ) ) {
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
			} elseif ( strpos( $head, 'opus' ) !== false ) {
				return MEDIATYPE_AUDIO;
			} else {
				return MEDIATYPE_MULTIMEDIA;
			}
		}

		$type = null;
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
	 * @internal
	 * @param string $extMime
	 * @return int|string
	 */
	public function findMediaType( $extMime ) {
		if ( strpos( $extMime, '.' ) === 0 ) {
			// If it's an extension, look up the MIME types
			$m = $this->getTypesForExtension( substr( $extMime, 1 ) );
			if ( !$m ) {
				return MEDIATYPE_UNKNOWN;
			}

			$m = explode( ' ', $m );
		} else {
			// Normalize MIME type
			if ( isset( $this->mimeTypeAliases[$extMime] ) ) {
				$extMime = $this->mimeTypeAliases[$extMime];
			}

			$m = [ $extMime ];
		}

		foreach ( $m as $mime ) {
			foreach ( $this->mediaTypes as $type => $codes ) {
				if ( in_array( $mime, $codes, true ) ) {
					return $type;
				}
			}
		}

		return MEDIATYPE_UNKNOWN;
	}

	/**
	 * Returns an array of media types (MEDIATYPE_xxx constants)
	 *
	 * @return array
	 */
	public function getMediaTypes() {
		return array_keys( $this->mediaTypes );
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
		if ( $this->IEAnalyzer === null ) {
			$this->IEAnalyzer = new IEContentAnalyzer;
		}
		return $this->IEAnalyzer;
	}
}
