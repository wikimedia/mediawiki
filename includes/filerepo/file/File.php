<?php
/**
 * @defgroup FileAbstraction File abstraction
 * @ingroup FileRepo
 *
 * Represents files in a repository.
 */

/**
 * Base code for files.
 *
 * @file
 * @ingroup FileAbstraction
 */

/**
 * Implements some public methods and some protected utility functions which
 * are required by multiple child classes. Contains stub functionality for
 * unimplemented public methods.
 *
 * Stub functions which should be overridden are marked with STUB. Some more
 * concrete functions are also typically overridden by child classes.
 *
 * Note that only the repo object knows what its file class is called. You should
 * never name a file class explictly outside of the repo class. Instead use the
 * repo's factory functions to generate file objects, for example:
 *
 * RepoGroup::singleton()->getLocalRepo()->newFile($title);
 *
 * The convenience functions wfLocalFile() and wfFindFile() should be sufficient
 * in most cases.
 *
 * @ingroup FileAbstraction
 */
abstract class File {
	const DELETED_FILE = 1;
	const DELETED_COMMENT = 2;
	const DELETED_USER = 4;
	const DELETED_RESTRICTED = 8;

	/** Force rendering in the current process */
	const RENDER_NOW   = 1;
	/**
	 * Force rendering even if thumbnail already exist and using RENDER_NOW
	 * I.e. you have to pass both flags: File::RENDER_NOW | File::RENDER_FORCE
	 */
	const RENDER_FORCE = 2;

	const DELETE_SOURCE = 1;

	/**
	 * Some member variables can be lazy-initialised using __get(). The
	 * initialisation function for these variables is always a function named
	 * like getVar(), where Var is the variable name with upper-case first
	 * letter.
	 *
	 * The following variables are initialised in this way in this base class:
	 *    name, extension, handler, path, canRender, isSafeFile,
	 *    transformScript, hashPath, pageCount, url
	 *
	 * Code within this class should generally use the accessor function
	 * directly, since __get() isn't re-entrant and therefore causes bugs that
	 * depend on initialisation order.
	 */

	/**
	 * The following member variables are not lazy-initialised
	 */

	/**
	 * @var FileRepo|false
	 */
	var $repo;

	/**
	 * @var Title|false
	 */
	var $title;

	var $lastError, $redirected, $redirectedTitle;

	/**
	 * @var FSFile|false
	 */
	protected $fsFile;

	/**
	 * @var MediaHandler
	 */
	protected $handler;

	/**
	 * @var string
	 */
	protected $url, $extension, $name, $path, $hashPath, $pageCount, $transformScript;

	/**
	 * @var bool
	 */
	protected $canRender, $isSafeFile;

	/**
	 * @var string Required Repository class type
	 */
	protected $repoClass = 'FileRepo';

	/**
	 * Call this constructor from child classes.
	 *
	 * Both $title and $repo are optional, though some functions
	 * may return false or throw exceptions if they are not set.
	 * Most subclasses will want to call assertRepoDefined() here.
	 *
	 * @param $title Title|string|false
	 * @param $repo FileRepo|false
	 */
	function __construct( $title, $repo ) {
		if ( $title !== false ) { // subclasses may not use MW titles
			$title = self::normalizeTitle( $title, 'exception' );
		}
		$this->title = $title;
		$this->repo = $repo;
	}

	/**
	 * Given a string or Title object return either a
	 * valid Title object with namespace NS_FILE or null
	 *
	 * @param $title Title|string
	 * @param $exception string|false Use 'exception' to throw an error on bad titles
	 * @return Title|null
	 */
	static function normalizeTitle( $title, $exception = false ) {
		$ret = $title;
		if ( $ret instanceof Title ) {
			# Normalize NS_MEDIA -> NS_FILE
			if ( $ret->getNamespace() == NS_MEDIA ) {
				$ret = Title::makeTitleSafe( NS_FILE, $ret->getDBkey() );
			# Sanity check the title namespace
			} elseif ( $ret->getNamespace() !== NS_FILE ) {
				$ret = null;
			}
		} else {
			# Convert strings to Title objects
			$ret = Title::makeTitleSafe( NS_FILE, (string)$ret );
		}
		if ( !$ret && $exception !== false ) {
			throw new MWException( "`$title` is not a valid file title." );
		}
		return $ret;
	}

	function __get( $name ) {
		$function = array( $this, 'get' . ucfirst( $name ) );
		if ( !is_callable( $function ) ) {
			return null;
		} else {
			$this->$name = call_user_func( $function );
			return $this->$name;
		}
	}

	/**
	 * Normalize a file extension to the common form, and ensure it's clean.
	 * Extensions with non-alphanumeric characters will be discarded.
	 *
	 * @param $ext string (without the .)
	 * @return string
	 */
	static function normalizeExtension( $ext ) {
		$lower = strtolower( $ext );
		$squish = array(
			'htm' => 'html',
			'jpeg' => 'jpg',
			'mpeg' => 'mpg',
			'tiff' => 'tif',
			'ogv' => 'ogg' );
		if( isset( $squish[$lower] ) ) {
			return $squish[$lower];
		} elseif( preg_match( '/^[0-9a-z]+$/', $lower ) ) {
			return $lower;
		} else {
			return '';
		}
	}

	/**
	 * Checks if file extensions are compatible
	 *
	 * @param $old File Old file
	 * @param $new string New name
	 *
	 * @return bool|null
	 */
	static function checkExtensionCompatibility( File $old, $new ) {
		$oldMime = $old->getMimeType();
		$n = strrpos( $new, '.' );
		$newExt = self::normalizeExtension( $n ? substr( $new, $n + 1 ) : '' );
		$mimeMagic = MimeMagic::singleton();
		return $mimeMagic->isMatchingExtension( $newExt, $oldMime );
	}

	/**
	 * Upgrade the database row if there is one
	 * Called by ImagePage
	 * STUB
	 */
	function upgradeRow() {}

	/**
	 * Split an internet media type into its two components; if not
	 * a two-part name, set the minor type to 'unknown'.
	 *
	 * @param string $mime "text/html" etc
	 * @return array ("text", "html") etc
	 */
	public static function splitMime( $mime ) {
		if( strpos( $mime, '/' ) !== false ) {
			return explode( '/', $mime, 2 );
		} else {
			return array( $mime, 'unknown' );
		}
	}

	/**
	 * Return the name of this file
	 *
	 * @return string
	 */
	public function getName() {
		if ( !isset( $this->name ) ) {
			$this->assertRepoDefined();
			$this->name = $this->repo->getNameFromTitle( $this->title );
		}
		return $this->name;
	}

	/**
	 * Get the file extension, e.g. "svg"
	 *
	 * @return string
	 */
	function getExtension() {
		if ( !isset( $this->extension ) ) {
			$n = strrpos( $this->getName(), '.' );
			$this->extension = self::normalizeExtension(
				$n ? substr( $this->getName(), $n + 1 ) : '' );
		}
		return $this->extension;
	}

	/**
	 * Return the associated title object
	 *
	 * @return Title|false
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Return the title used to find this file
	 *
	 * @return Title
	 */
	public function getOriginalTitle() {
		if ( $this->redirected ) {
			return $this->getRedirectedTitle();
		}
		return $this->title;
	}

	/**
	 * Return the URL of the file
	 *
	 * @return string
	 */
	public function getUrl() {
		if ( !isset( $this->url ) ) {
			$this->assertRepoDefined();
			$this->url = $this->repo->getZoneUrl( 'public' ) . '/' . $this->getUrlRel();
		}
		return $this->url;
	}

	/**
	 * Return a fully-qualified URL to the file.
	 * Upload URL paths _may or may not_ be fully qualified, so
	 * we check. Local paths are assumed to belong on $wgServer.
	 *
	 * @return String
	 */
	public function getFullUrl() {
		return wfExpandUrl( $this->getUrl(), PROTO_RELATIVE );
	}

	/**
	 * @return string
	 */
	public function getCanonicalUrl() {
		return wfExpandUrl( $this->getUrl(), PROTO_CANONICAL );
	}

	/**
	 * @return string
	 */
	function getViewURL() {
		if ( $this->mustRender() ) {
			if ( $this->canRender() ) {
				return $this->createThumb( $this->getWidth() );
			} else {
				wfDebug( __METHOD__.': supposed to render ' . $this->getName() .
					' (' . $this->getMimeType() . "), but can't!\n" );
				return $this->getURL(); #hm... return NULL?
			}
		} else {
			return $this->getURL();
		}
	}

	/**
	* Return the storage path to the file. Note that this does
	* not mean that a file actually exists under that location.
	*
	* This path depends on whether directory hashing is active or not,
	* i.e. whether the files are all found in the same directory,
	* or in hashed paths like /images/3/3c.
	*
	* Most callers don't check the return value, but ForeignAPIFile::getPath
	* returns false.
	 *
	 * @return string|false
	*/
	public function getPath() {
		if ( !isset( $this->path ) ) {
			$this->assertRepoDefined();
			$this->path = $this->repo->getZonePath( 'public' ) . '/' . $this->getRel();
		}
		return $this->path;
	}

	/**
	 * Get an FS copy or original of this file and return the path.
	 * Returns false on failure. Callers must not alter the file.
	 * Temporary files are cleared automatically.
	 *
	 * @return string|false
	 */
	public function getLocalRefPath() {
		$this->assertRepoDefined();
		if ( !isset( $this->fsFile ) ) {
			$this->fsFile = $this->repo->getLocalReference( $this->getPath() );
			if ( !$this->fsFile ) {
				$this->fsFile = false; // null => false; cache negative hits
			}
		}
		return ( $this->fsFile )
			? $this->fsFile->getPath()
			: false;
	}

	/**
	 * Return the width of the image. Returns false if the width is unknown
	 * or undefined.
	 *
	 * STUB
	 * Overridden by LocalFile, UnregisteredLocalFile
	 *
	 * @param $page int
	 *
	 * @return number
	 */
	public function getWidth( $page = 1 ) {
		return false;
	}

	/**
	 * Return the height of the image. Returns false if the height is unknown
	 * or undefined
	 *
	 * STUB
	 * Overridden by LocalFile, UnregisteredLocalFile
	 *
	 * @param $page int
	 *
	 * @return false|number
	 */
	public function getHeight( $page = 1 ) {
		return false;
	}

	/**
	 * Returns ID or name of user who uploaded the file
	 * STUB
	 *
	 * @param $type string 'text' or 'id'
	 *
	 * @return string|int
	 */
	public function getUser( $type = 'text' ) {
		return null;
	}

	/**
	 * Get the duration of a media file in seconds
	 *
	 * @return number
	 */
	public function getLength() {
		$handler = $this->getHandler();
		if ( $handler ) {
			return $handler->getLength( $this );
		} else {
			return 0;
		}
	}

	/**
	 * Return true if the file is vectorized
	 *
	 * @return bool
	 */
	public function isVectorized() {
		$handler = $this->getHandler();
		if ( $handler ) {
			return $handler->isVectorized( $this );
		} else {
			return false;
		}
	}

	/**
	 * Get handler-specific metadata
	 * Overridden by LocalFile, UnregisteredLocalFile
	 * STUB
	 */
	public function getMetadata() {
		return false;
	}

	/**
	* get versioned metadata
	*
	* @param $metadata Mixed Array or String of (serialized) metadata
	* @param $version integer version number.
	* @return Array containing metadata, or what was passed to it on fail (unserializing if not array)
	*/
	public function convertMetadataVersion($metadata, $version) {
		$handler = $this->getHandler();
		if ( !is_array( $metadata ) ) {
			// Just to make the return type consistent
			$metadata = unserialize( $metadata );
		}
		if ( $handler ) {
			return $handler->convertMetadataVersion( $metadata, $version );
		} else {
			return $metadata;
		}
	}

	/**
	 * Return the bit depth of the file
	 * Overridden by LocalFile
	 * STUB
	 */
	public function getBitDepth() {
		return 0;
	}

	/**
	 * Return the size of the image file, in bytes
	 * Overridden by LocalFile, UnregisteredLocalFile
	 * STUB
	 */
	public function getSize() {
		return false;
	}

	/**
	 * Returns the mime type of the file.
	 * Overridden by LocalFile, UnregisteredLocalFile
	 * STUB
	 *
	 * @return string
	 */
	function getMimeType() {
		return 'unknown/unknown';
	}

	/**
	 * Return the type of the media in the file.
	 * Use the value returned by this function with the MEDIATYPE_xxx constants.
	 * Overridden by LocalFile,
	 * STUB
	 */
	function getMediaType() {
		return MEDIATYPE_UNKNOWN;
	}

	/**
	 * Checks if the output of transform() for this file is likely
	 * to be valid. If this is false, various user elements will
	 * display a placeholder instead.
	 *
	 * Currently, this checks if the file is an image format
	 * that can be converted to a format
	 * supported by all browsers (namely GIF, PNG and JPEG),
	 * or if it is an SVG image and SVG conversion is enabled.
	 *
	 * @return bool
	 */
	function canRender() {
		if ( !isset( $this->canRender ) ) {
			$this->canRender = $this->getHandler() && $this->handler->canRender( $this );
		}
		return $this->canRender;
	}

	/**
	 * Accessor for __get()
	 */
	protected function getCanRender() {
		return $this->canRender();
	}

	/**
	 * Return true if the file is of a type that can't be directly
	 * rendered by typical browsers and needs to be re-rasterized.
	 *
	 * This returns true for everything but the bitmap types
	 * supported by all browsers, i.e. JPEG; GIF and PNG. It will
	 * also return true for any non-image formats.
	 *
	 * @return bool
	 */
	function mustRender() {
		return $this->getHandler() && $this->handler->mustRender( $this );
	}

	/**
	 * Alias for canRender()
	 *
	 * @return bool
	 */
	function allowInlineDisplay() {
		return $this->canRender();
	}

	/**
	 * Determines if this media file is in a format that is unlikely to
	 * contain viruses or malicious content. It uses the global
	 * $wgTrustedMediaFormats list to determine if the file is safe.
	 *
	 * This is used to show a warning on the description page of non-safe files.
	 * It may also be used to disallow direct [[media:...]] links to such files.
	 *
	 * Note that this function will always return true if allowInlineDisplay()
	 * or isTrustedFile() is true for this file.
	 *
	 * @return bool
	 */
	function isSafeFile() {
		if ( !isset( $this->isSafeFile ) ) {
			$this->isSafeFile = $this->_getIsSafeFile();
		}
		return $this->isSafeFile;
	}

	/**
	 * Accessor for __get()
	 *
	 * @return bool
	 */
	protected function getIsSafeFile() {
		return $this->isSafeFile();
	}

	/**
	 * Uncached accessor
	 *
	 * @return bool
	 */
	protected function _getIsSafeFile() {
		global $wgTrustedMediaFormats;

		if ( $this->allowInlineDisplay() ) {
			return true;
		}
		if ($this->isTrustedFile()) {
			return true;
		}

		$type = $this->getMediaType();
		$mime = $this->getMimeType();
		#wfDebug("LocalFile::isSafeFile: type= $type, mime= $mime\n");

		if ( !$type || $type === MEDIATYPE_UNKNOWN ) {
			return false; #unknown type, not trusted
		}
		if ( in_array( $type, $wgTrustedMediaFormats ) ) {
			return true;
		}

		if ( $mime === "unknown/unknown" ) {
			return false; #unknown type, not trusted
		}
		if ( in_array( $mime, $wgTrustedMediaFormats) ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns true if the file is flagged as trusted. Files flagged that way
	 * can be linked to directly, even if that is not allowed for this type of
	 * file normally.
	 *
	 * This is a dummy function right now and always returns false. It could be
	 * implemented to extract a flag from the database. The trusted flag could be
	 * set on upload, if the user has sufficient privileges, to bypass script-
	 * and html-filters. It may even be coupled with cryptographics signatures
	 * or such.
	 *
	 * @return bool
	 */
	function isTrustedFile() {
		#this could be implemented to check a flag in the database,
		#look for signatures, etc
		return false;
	}

	/**
	 * Returns true if file exists in the repository.
	 *
	 * Overridden by LocalFile to avoid unnecessary stat calls.
	 *
	 * @return boolean Whether file exists in the repository.
	 */
	public function exists() {
		return $this->getPath() && $this->repo->fileExists( $this->path );
	}

	/**
	 * Returns true if file exists in the repository and can be included in a page.
	 * It would be unsafe to include private images, making public thumbnails inadvertently
	 *
	 * @return boolean Whether file exists in the repository and is includable.
	 */
	public function isVisible() {
		return $this->exists();
	}

	/**
	 * @return string
	 */
	function getTransformScript() {
		if ( !isset( $this->transformScript ) ) {
			$this->transformScript = false;
			if ( $this->repo ) {
				$script = $this->repo->getThumbScriptUrl();
				if ( $script ) {
					$this->transformScript = "$script?f=" . urlencode( $this->getName() );
				}
			}
		}
		return $this->transformScript;
	}

	/**
	 * Get a ThumbnailImage which is the same size as the source
	 *
	 * @param $handlerParams array
	 *
	 * @return string
	 */
	function getUnscaledThumb( $handlerParams = array() ) {
		$hp =& $handlerParams;
		$page = isset( $hp['page'] ) ? $hp['page'] : false;
		$width = $this->getWidth( $page );
		if ( !$width ) {
			return $this->iconThumb();
		}
		$hp['width'] = $width;
		return $this->transform( $hp );
	}

	/**
	 * Return the file name of a thumbnail with the specified parameters
	 *
	 * @param $params Array: handler-specific parameters
	 * @private -ish
	 *
	 * @return string
	 */
	function thumbName( $params ) {
		return $this->generateThumbName( $this->getName(), $params );
	}

	/**
	 * Generate a thumbnail file name from a name and specified parameters
	 *
	 * @param string $name
	 * @param array $params Parameters which will be passed to MediaHandler::makeParamString
	 *
	 * @return string
	 */
	function generateThumbName( $name, $params ) {
		if ( !$this->getHandler() ) {
			return null;
		}
		$extension = $this->getExtension();
		list( $thumbExt, $thumbMime ) = $this->handler->getThumbType(
			$extension, $this->getMimeType(), $params );
		$thumbName = $this->handler->makeParamString( $params ) . '-' . $name;
		if ( $thumbExt != $extension ) {
			$thumbName .= ".$thumbExt";
		}
		return $thumbName;
	}

	/**
	 * Create a thumbnail of the image having the specified width/height.
	 * The thumbnail will not be created if the width is larger than the
	 * image's width. Let the browser do the scaling in this case.
	 * The thumbnail is stored on disk and is only computed if the thumbnail
	 * file does not exist OR if it is older than the image.
	 * Returns the URL.
	 *
	 * Keeps aspect ratio of original image. If both width and height are
	 * specified, the generated image will be no bigger than width x height,
	 * and will also have correct aspect ratio.
	 *
	 * @param $width Integer: maximum width of the generated thumbnail
	 * @param $height Integer: maximum height of the image (optional)
	 *
	 * @return string
	 */
	public function createThumb( $width, $height = -1 ) {
		$params = array( 'width' => $width );
		if ( $height != -1 ) {
			$params['height'] = $height;
		}
		$thumb = $this->transform( $params );
		if ( is_null( $thumb ) || $thumb->isError() ) {
			return '';
		}
		return $thumb->getUrl();
	}

	/**
	 * Return either a MediaTransformError or placeholder thumbnail (if $wgIgnoreImageErrors)
	 * 
	 * @param $thumbPath string Thumbnail storage path
	 * @param $thumbUrl string Thumbnail URL
	 * @param $params Array
	 * @param $flags integer
	 * @return MediaTransformOutput
	 */
	protected function transformErrorOutput( $thumbPath, $thumbUrl, $params, $flags ) {
		global $wgIgnoreImageErrors;

		if ( $wgIgnoreImageErrors && !( $flags & self::RENDER_NOW ) ) {
			return $this->handler->getTransform( $this, $thumbPath, $thumbUrl, $params );
		} else {
			return new MediaTransformError( 'thumbnail_error',
				$params['width'], 0, wfMsg( 'thumbnail-dest-create' ) );
		}
	}

	/**
	 * Transform a media file
	 *
	 * @param $params Array: an associative array of handler-specific parameters.
	 *                Typical keys are width, height and page.
	 * @param $flags Integer: a bitfield, may contain self::RENDER_NOW to force rendering
	 * @return MediaTransformOutput|false
	 */
	function transform( $params, $flags = 0 ) {
		global $wgUseSquid, $wgIgnoreImageErrors, $wgThumbnailEpoch;

		wfProfileIn( __METHOD__ );
		do {
			if ( !$this->canRender() ) {
				$thumb = $this->iconThumb();
				break; // not a bitmap or renderable image, don't try
			}

			// Get the descriptionUrl to embed it as comment into the thumbnail. Bug 19791.
			$descriptionUrl = $this->getDescriptionUrl();
			if ( $descriptionUrl ) {
				$params['descriptionUrl'] = wfExpandUrl( $descriptionUrl, PROTO_CANONICAL );
			}

			$script = $this->getTransformScript();
			if ( $script && !( $flags & self::RENDER_NOW ) ) {
				// Use a script to transform on client request, if possible
				$thumb = $this->handler->getScriptedTransform( $this, $script, $params );
				if ( $thumb ) {
					break;
				}
			}

			$normalisedParams = $params;
			$this->handler->normaliseParams( $this, $normalisedParams );

			$thumbName = $this->thumbName( $normalisedParams );
			$thumbUrl = $this->getThumbUrl( $thumbName );
			$thumbPath = $this->getThumbPath( $thumbName ); // final thumb path

			if ( $this->repo ) {
				// Defer rendering if a 404 handler is set up...
				if ( $this->repo->canTransformVia404() && !( $flags & self::RENDER_NOW ) ) {
					wfDebug( __METHOD__ . " transformation deferred." );
					// XXX: Pass in the storage path even though we are not rendering anything
					// and the path is supposed to be an FS path. This is due to getScalerType()
					// getting called on the path and clobbering $thumb->getUrl() if it's false.
					$thumb = $this->handler->getTransform( $this, $thumbPath, $thumbUrl, $params );
					break;
				}
				// Clean up broken thumbnails as needed
				$this->migrateThumbFile( $thumbName );
				// Check if an up-to-date thumbnail already exists...
				wfDebug( __METHOD__.": Doing stat for $thumbPath\n" );
				if ( $this->repo->fileExists( $thumbPath ) && !( $flags & self::RENDER_FORCE ) ) {
					$timestamp = $this->repo->getFileTimestamp( $thumbPath );
					if ( $timestamp !== false && $timestamp >= $wgThumbnailEpoch ) {
						// XXX: Pass in the storage path even though we are not rendering anything
						// and the path is supposed to be an FS path. This is due to getScalerType()
						// getting called on the path and clobbering $thumb->getUrl() if it's false.
						$thumb = $this->handler->getTransform( $this, $thumbPath, $thumbUrl, $params );
						$thumb->setStoragePath( $thumbPath );
						break;
					}
				} elseif ( $flags & self::RENDER_FORCE ) {
					wfDebug( __METHOD__ . " forcing rendering per flag File::RENDER_FORCE\n" );
				}
			}

			// Create a temp FS file with the same extension and the thumbnail
			$thumbExt = FileBackend::extensionFromPath( $thumbPath );
			$tmpFile = TempFSFile::factory( 'transform_', $thumbExt );
			if ( !$tmpFile ) {
				$thumb = $this->transformErrorOutput( $thumbPath, $thumbUrl, $params, $flags );
				break;
			}
			$tmpThumbPath = $tmpFile->getPath(); // path of 0-byte temp file

			// Actually render the thumbnail...
			$thumb = $this->handler->doTransform( $this, $tmpThumbPath, $thumbUrl, $params );
			$tmpFile->bind( $thumb ); // keep alive with $thumb

			if ( !$thumb ) { // bad params?
				$thumb = null;
			} elseif ( $thumb->isError() ) { // transform error
				$this->lastError = $thumb->toText();
				// Ignore errors if requested
				if ( $wgIgnoreImageErrors && !( $flags & self::RENDER_NOW ) ) {
					$thumb = $this->handler->getTransform( $this, $tmpThumbPath, $thumbUrl, $params );
				}
			} elseif ( $this->repo && $thumb->hasFile() && !$thumb->fileIsSource() ) {
				$backend = $this->repo->getBackend();
				// Copy the thumbnail from the file system into storage. This avoids using
				// FileRepo::store(); getThumbPath() uses a different zone in some subclasses.
				$backend->prepare( array( 'dir' => dirname( $thumbPath ) ) );
				$status = $backend->store(
					array( 'src' => $tmpThumbPath, 'dst' => $thumbPath, 'overwrite' => 1 ),
					array( 'force' => 1, 'nonLocking' => 1, 'allowStale' => 1 )
				);
				if ( $status->isOK() ) {
					$thumb->setStoragePath( $thumbPath );
				} else {
					$thumb = $this->transformErrorOutput( $thumbPath, $thumbUrl, $params, $flags );
				}
			}

			// Purge. Useful in the event of Core -> Squid connection failure or squid
			// purge collisions from elsewhere during failure. Don't keep triggering for
			// "thumbs" which have the main image URL though (bug 13776)
			if ( $wgUseSquid ) {
				if ( !$thumb || $thumb->isError() || $thumb->getUrl() != $this->getURL() ) {
					SquidUpdate::purge( array( $thumbUrl ) );
				}
			}
		} while ( false );

		wfProfileOut( __METHOD__ );
		return is_object( $thumb ) ? $thumb : false;
	}

	/**
	 * Hook into transform() to allow migration of thumbnail files
	 * STUB
	 * Overridden by LocalFile
	 */
	function migrateThumbFile( $thumbName ) {}

	/**
	 * Get a MediaHandler instance for this file
	 *
	 * @return MediaHandler
	 */
	function getHandler() {
		if ( !isset( $this->handler ) ) {
			$this->handler = MediaHandler::getHandler( $this->getMimeType() );
		}
		return $this->handler;
	}

	/**
	 * Get a ThumbnailImage representing a file type icon
	 *
	 * @return ThumbnailImage
	 */
	function iconThumb() {
		global $wgStylePath, $wgStyleDirectory;

		$try = array( 'fileicon-' . $this->getExtension() . '.png', 'fileicon.png' );
		foreach ( $try as $icon ) {
			$path = '/common/images/icons/' . $icon;
			$filepath = $wgStyleDirectory . $path;
			if ( file_exists( $filepath ) ) { // always FS
				return new ThumbnailImage( $this, $wgStylePath . $path, 120, 120 );
			}
		}
		return null;
	}

	/**
	 * Get last thumbnailing error.
	 * Largely obsolete.
	 */
	function getLastError() {
		return $this->lastError;
	}

	/**
	 * Get all thumbnail names previously generated for this file
	 * STUB
	 * Overridden by LocalFile
	 */
	function getThumbnails() {
		return array();
	}

	/**
	 * Purge shared caches such as thumbnails and DB data caching
	 * STUB
	 * Overridden by LocalFile
	 * @param $options Array Options, which include:
	 *     'forThumbRefresh' : The purging is only to refresh thumbnails
	 */
	function purgeCache( $options = array() ) {}

	/**
	 * Purge the file description page, but don't go after
	 * pages using the file. Use when modifying file history
	 * but not the current data.
	 */
	function purgeDescription() {
		$title = $this->getTitle();
		if ( $title ) {
			$title->invalidateCache();
			$title->purgeSquid();
		}
	}

	/**
	 * Purge metadata and all affected pages when the file is created,
	 * deleted, or majorly updated.
	 */
	function purgeEverything() {
		// Delete thumbnails and refresh file metadata cache
		$this->purgeCache();
		$this->purgeDescription();

		// Purge cache of all pages using this file
		$title = $this->getTitle();
		if ( $title ) {
			$update = new HTMLCacheUpdate( $title, 'imagelinks' );
			$update->doUpdate();
		}
	}

	/**
	 * Return a fragment of the history of file.
	 *
	 * STUB
	 * @param $limit integer Limit of rows to return
	 * @param $start timestamp Only revisions older than $start will be returned
	 * @param $end timestamp Only revisions newer than $end will be returned
	 * @param $inc bool Include the endpoints of the time range
	 *
	 * @return array
	 */
	function getHistory($limit = null, $start = null, $end = null, $inc=true) {
		return array();
	}

	/**
	 * Return the history of this file, line by line. Starts with current version,
	 * then old versions. Should return an object similar to an image/oldimage
	 * database row.
	 *
	 * STUB
	 * Overridden in LocalFile
	 */
	public function nextHistoryLine() {
		return false;
	}

	/**
	 * Reset the history pointer to the first element of the history.
	 * Always call this function after using nextHistoryLine() to free db resources
	 * STUB
	 * Overridden in LocalFile.
	 */
	public function resetHistory() {}

	/**
	 * Get the filename hash component of the directory including trailing slash,
	 * e.g. f/fa/
	 * If the repository is not hashed, returns an empty string.
	 *
	 * @return string
	 */
	function getHashPath() {
		if ( !isset( $this->hashPath ) ) {
			$this->assertRepoDefined();
			$this->hashPath = $this->repo->getHashPath( $this->getName() );
		}
		return $this->hashPath;
	}

	/**
	 * Get the path of the file relative to the public zone root.
	 * This function is overriden in OldLocalFile to be like getArchiveRel().
	 *
	 * @return string
	 */
	function getRel() {
		return $this->getHashPath() . $this->getName();
	}

	/**
	 * Get the path of an archived file relative to the public zone root
	 *
	 * @param $suffix bool|string if not false, the name of an archived thumbnail file
	 *
	 * @return string
	 */
	function getArchiveRel( $suffix = false ) {
		$path = 'archive/' . $this->getHashPath();
		if ( $suffix === false ) {
			$path = substr( $path, 0, -1 );
		} else {
			$path .= $suffix;
		}
		return $path;
	}

	/**
	 * Get the path, relative to the thumbnail zone root, of the
	 * thumbnail directory or a particular file if $suffix is specified
	 *
	 * @param $suffix bool|string if not false, the name of a thumbnail file
	 *
	 * @return string
	 */
	function getThumbRel( $suffix = false ) {
		$path = $this->getRel();
		if ( $suffix !== false ) {
			$path .= '/' . $suffix;
		}
		return $path;
	}

	/**
	 * Get urlencoded path of the file relative to the public zone root.
	 * This function is overriden in OldLocalFile to be like getArchiveUrl().
	 *
	 * @return string
	 */
	function getUrlRel() {
		return $this->getHashPath() . rawurlencode( $this->getName() );
	}

	/**
	 * Get the path, relative to the thumbnail zone root, for an archived file's thumbs directory
	 * or a specific thumb if the $suffix is given.
	 *
	 * @param $archiveName string the timestamped name of an archived image
	 * @param $suffix bool|string if not false, the name of a thumbnail file
	 *
	 * @return string
	 */
	function getArchiveThumbRel( $archiveName, $suffix = false ) {
		$path = 'archive/' . $this->getHashPath() . $archiveName . "/";
		if ( $suffix === false ) {
			$path = substr( $path, 0, -1 );
		} else {
			$path .= $suffix;
		}
		return $path;
	}

	/**
	 * Get the path of the archived file.
	 *
	 * @param $suffix bool|string if not false, the name of an archived file.
	 *
	 * @return string
	 */
	function getArchivePath( $suffix = false ) {
		$this->assertRepoDefined();
		return $this->repo->getZonePath( 'public' ) . '/' . $this->getArchiveRel( $suffix );
	}

	/**
	 * Get the path of an archived file's thumbs, or a particular thumb if $suffix is specified
	 *
	 * @param $archiveName string the timestamped name of an archived image
	 * @param $suffix bool|string if not false, the name of a thumbnail file
	 *
	 * @return string
	 */
	function getArchiveThumbPath( $archiveName, $suffix = false ) {
		$this->assertRepoDefined();
		return $this->repo->getZonePath( 'thumb' ) . '/' .
			$this->getArchiveThumbRel( $archiveName, $suffix );
	}

	/**
	 * Get the path of the thumbnail directory, or a particular file if $suffix is specified
	 *
	 * @param $suffix bool|string if not false, the name of a thumbnail file
	 *
	 * @return string
	 */
	function getThumbPath( $suffix = false ) {
		$this->assertRepoDefined();
		return $this->repo->getZonePath( 'thumb' ) . '/' . $this->getThumbRel( $suffix );
	}

	/**
	 * Get the URL of the archive directory, or a particular file if $suffix is specified
	 *
	 * @param $suffix bool|string if not false, the name of an archived file
	 *
	 * @return string
	 */
	function getArchiveUrl( $suffix = false ) {
		$this->assertRepoDefined();
		$path = $this->repo->getZoneUrl( 'public' ) . '/archive/' . $this->getHashPath();
		if ( $suffix === false ) {
			$path = substr( $path, 0, -1 );
		} else {
			$path .= rawurlencode( $suffix );
		}
		return $path;
	}

	/**
	 * Get the URL of the archived file's thumbs, or a particular thumb if $suffix is specified
	 *
	 * @param $archiveName string the timestamped name of an archived image
	 * @param $suffix bool|string if not false, the name of a thumbnail file
	 *
	 * @return string
	 */
	function getArchiveThumbUrl( $archiveName, $suffix = false ) {
		$this->assertRepoDefined();
		$path = $this->repo->getZoneUrl( 'thumb' ) . '/archive/' .
			$this->getHashPath() . rawurlencode( $archiveName ) . "/";
		if ( $suffix === false ) {
			$path = substr( $path, 0, -1 );
		} else {
			$path .= rawurlencode( $suffix );
		}
		return $path;
	}

	/**
	 * Get the URL of the thumbnail directory, or a particular file if $suffix is specified
	 *
	 * @param $suffix bool|string if not false, the name of a thumbnail file
	 *
	 * @return path
	 */
	function getThumbUrl( $suffix = false ) {
		$this->assertRepoDefined();
		$path = $this->repo->getZoneUrl( 'thumb' ) . '/' . $this->getUrlRel();
		if ( $suffix !== false ) {
			$path .= '/' . rawurlencode( $suffix );
		}
		return $path;
	}

	/**
	 * Get the public zone virtual URL for a current version source file
	 *
	 * @param $suffix bool|string if not false, the name of a thumbnail file
	 *
	 * @return string
	 */
	function getVirtualUrl( $suffix = false ) {
		$this->assertRepoDefined();
		$path = $this->repo->getVirtualUrl() . '/public/' . $this->getUrlRel();
		if ( $suffix !== false ) {
			$path .= '/' . rawurlencode( $suffix );
		}
		return $path;
	}

	/**
	 * Get the public zone virtual URL for an archived version source file
	 *
	 * @param $suffix bool|string if not false, the name of a thumbnail file
	 *
	 * @return string
	 */
	function getArchiveVirtualUrl( $suffix = false ) {
		$this->assertRepoDefined();
		$path = $this->repo->getVirtualUrl() . '/public/archive/' . $this->getHashPath();
		if ( $suffix === false ) {
			$path = substr( $path, 0, -1 );
		} else {
			$path .= rawurlencode( $suffix );
		}
		return $path;
	}

	/**
	 * Get the virtual URL for a thumbnail file or directory
	 *
	 * @param $suffix bool|string if not false, the name of a thumbnail file
	 *
	 * @return string
	 */
	function getThumbVirtualUrl( $suffix = false ) {
		$this->assertRepoDefined();
		$path = $this->repo->getVirtualUrl() . '/thumb/' . $this->getUrlRel();
		if ( $suffix !== false ) {
			$path .= '/' . rawurlencode( $suffix );
		}
		return $path;
	}

	/**
	 * @return bool
	 */
	function isHashed() {
		$this->assertRepoDefined();
		return $this->repo->isHashed();
	}

	/**
	 * @throws MWException
	 */
	function readOnlyError() {
		throw new MWException( get_class($this) . ': write operations are not supported' );
	}

	/**
	 * Record a file upload in the upload log and the image table
	 * STUB
	 * Overridden by LocalFile
	 * @param $oldver
	 * @param $desc
	 * @param $license string
	 * @param $copyStatus string
	 * @param $source string
	 * @param $watch bool
	 */
	function recordUpload( $oldver, $desc, $license = '', $copyStatus = '', $source = '', $watch = false ) {
		$this->readOnlyError();
	}

	/**
	 * Move or copy a file to its public location. If a file exists at the
	 * destination, move it to an archive. Returns a FileRepoStatus object with
	 * the archive name in the "value" member on success.
	 *
	 * The archive name should be passed through to recordUpload for database
	 * registration.
	 *
	 * @param $srcPath String: local filesystem path to the source image
	 * @param $flags Integer: a bitwise combination of:
	 *     File::DELETE_SOURCE    Delete the source file, i.e. move
	 *         rather than copy
	 * @return FileRepoStatus object. On success, the value member contains the
	 *     archive name, or an empty string if it was a new file.
	 *
	 * STUB
	 * Overridden by LocalFile
	 */
	function publish( $srcPath, $flags = 0 ) {
		$this->readOnlyError();
	}

	/**
	 * @return bool
	 */
	function formatMetadata() {
		if ( !$this->getHandler() ) {
			return false;
		}
		return $this->getHandler()->formatMetadata( $this, $this->getMetadata() );
	}

	/**
	 * Returns true if the file comes from the local file repository.
	 *
	 * @return bool
	 */
	function isLocal() {
		return $this->repo && $this->repo->isLocal();
	}

	/**
	 * Returns the name of the repository.
	 *
	 * @return string
	 */
	function getRepoName() {
		return $this->repo ? $this->repo->getName() : 'unknown';
	}

	/**
	 * Returns the repository
	 *
	 * @return FileRepo|false
	 */
	function getRepo() {
		return $this->repo;
	}

	/**
	 * Returns true if the image is an old version
	 * STUB
	 *
	 * @return bool
	 */
	function isOld() {
		return false;
	}

	/**
	 * Is this file a "deleted" file in a private archive?
	 * STUB
	 *
	 * @param $field
	 *
	 * @return bool
	 */
	function isDeleted( $field ) {
		return false;
	}

	/**
	 * Return the deletion bitfield
	 * STUB
	 */
	function getVisibility() {
		return 0;
	}

	/**
	 * Was this file ever deleted from the wiki?
	 *
	 * @return bool
	 */
	function wasDeleted() {
		$title = $this->getTitle();
		return $title && $title->isDeletedQuick();
	}

	/**
	 * Move file to the new title
	 *
	 * Move current, old version and all thumbnails
	 * to the new filename. Old file is deleted.
	 *
	 * Cache purging is done; checks for validity
	 * and logging are caller's responsibility
	 *
	 * @param $target Title New file name
	 * @return FileRepoStatus object.
	 */
	 function move( $target ) {
		$this->readOnlyError();
	 }

	/**
	 * Delete all versions of the file.
	 *
	 * Moves the files into an archive directory (or deletes them)
	 * and removes the database rows.
	 *
	 * Cache purging is done; logging is caller's responsibility.
	 *
	 * @param $reason String
	 * @param $suppress Boolean: hide content from sysops?
	 * @return true on success, false on some kind of failure
	 * STUB
	 * Overridden by LocalFile
	 */
	function delete( $reason, $suppress = false ) {
		$this->readOnlyError();
	}

	/**
	 * Restore all or specified deleted revisions to the given file.
	 * Permissions and logging are left to the caller.
	 *
	 * May throw database exceptions on error.
	 *
	 * @param $versions array set of record ids of deleted items to restore,
	 *                    or empty to restore all revisions.
	 * @param $unsuppress bool remove restrictions on content upon restoration?
	 * @return int|false the number of file revisions restored if successful,
	 *         or false on failure
	 * STUB
	 * Overridden by LocalFile
	 */
	function restore( $versions = array(), $unsuppress = false ) {
		$this->readOnlyError();
	}

	/**
	 * Returns 'true' if this file is a type which supports multiple pages,
	 * e.g. DJVU or PDF. Note that this may be true even if the file in
	 * question only has a single page.
	 *
	 * @return Bool
	 */
	function isMultipage() {
		return $this->getHandler() && $this->handler->isMultiPage( $this );
	}

	/**
	 * Returns the number of pages of a multipage document, or false for
	 * documents which aren't multipage documents
	 *
	 * @return false|int
	 */
	function pageCount() {
		if ( !isset( $this->pageCount ) ) {
			if ( $this->getHandler() && $this->handler->isMultiPage( $this ) ) {
				$this->pageCount = $this->handler->pageCount( $this );
			} else {
				$this->pageCount = false;
			}
		}
		return $this->pageCount;
	}

	/**
	 * Calculate the height of a thumbnail using the source and destination width
	 *
	 * @param $srcWidth
	 * @param $srcHeight
	 * @param $dstWidth
	 *
	 * @return int
	 */
	static function scaleHeight( $srcWidth, $srcHeight, $dstWidth ) {
		// Exact integer multiply followed by division
		if ( $srcWidth == 0 ) {
			return 0;
		} else {
			return round( $srcHeight * $dstWidth / $srcWidth );
		}
	}

	/**
	 * Get an image size array like that returned by getImageSize(), or false if it
	 * can't be determined.
	 *
	 * @param $fileName String: The filename
	 * @return Array
	 */
	function getImageSize( $fileName ) {
		if ( !$this->getHandler() ) {
			return false;
		}
		return $this->handler->getImageSize( $this, $fileName );
	}

	/**
	 * Get the URL of the image description page. May return false if it is
	 * unknown or not applicable.
	 *
	 * @return string
	 */
	function getDescriptionUrl() {
		if ( $this->repo ) {
			return $this->repo->getDescriptionUrl( $this->getName() );
		} else {
			return false;
		}
	}

	/**
	 * Get the HTML text of the description page, if available
	 *
	 * @return string
	 */
	function getDescriptionText() {
		global $wgMemc, $wgLang;
		if ( !$this->repo || !$this->repo->fetchDescription ) {
			return false;
		}
		$renderUrl = $this->repo->getDescriptionRenderUrl( $this->getName(), $wgLang->getCode() );
		if ( $renderUrl ) {
			if ( $this->repo->descriptionCacheExpiry > 0 ) {
				wfDebug("Attempting to get the description from cache...");
				$key = $this->repo->getLocalCacheKey( 'RemoteFileDescription', 'url', $wgLang->getCode(),
									$this->getName() );
				$obj = $wgMemc->get($key);
				if ($obj) {
					wfDebug("success!\n");
					return $obj;
				}
				wfDebug("miss\n");
			}
			wfDebug( "Fetching shared description from $renderUrl\n" );
			$res = Http::get( $renderUrl );
			if ( $res && $this->repo->descriptionCacheExpiry > 0 ) {
				$wgMemc->set( $key, $res, $this->repo->descriptionCacheExpiry );
			}
			return $res;
		} else {
			return false;
		}
	}

	/**
	 * Get discription of file revision
	 * STUB
	 *
	 * @return string
	 */
	function getDescription() {
		return null;
	}

	/**
	 * Get the 14-character timestamp of the file upload
	 *
	 * @return string|false TS_MW timestamp or false on failure
	 */
	function getTimestamp() {
		$this->assertRepoDefined();
		return $this->repo->getFileTimestamp( $this->getPath() );
	}

	/**
	 * Get the SHA-1 base 36 hash of the file
	 *
	 * @return string
	 */
	function getSha1() {
		$this->assertRepoDefined();
		return $this->repo->getFileSha1( $this->getPath() );
	}

	/**
	 * Get the deletion archive key, <sha1>.<ext>
	 *
	 * @return string
	 */
	function getStorageKey() {
		$hash = $this->getSha1();
		if ( !$hash ) {
			return false;
		}
		$ext = $this->getExtension();
		$dotExt = $ext === '' ? '' : ".$ext";
		return $hash . $dotExt;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this file, if it's marked as deleted.
	 * STUB
	 * @param $field Integer
	 * @param $user User object to check, or null to use $wgUser
	 * @return Boolean
	 */
	function userCan( $field, User $user = null ) {
		return true;
	}

	/**
	 * Get an associative array containing information about a file in the local filesystem.
	 *
	 * @param $path String: absolute local filesystem path
	 * @param $ext Mixed: the file extension, or true to extract it from the filename.
	 *             Set it to false to ignore the extension.
	 *
	 * @return array
	 */
	static function getPropsFromPath( $path, $ext = true ) {
		wfDebug( __METHOD__.": Getting file info for $path\n" );
		wfDeprecated( __METHOD__, '1.19' );

		$fsFile = new FSFile( $path );
		return $fsFile->getProps();
	}

	/**
	 * Get a SHA-1 hash of a file in the local filesystem, in base-36 lower case
	 * encoding, zero padded to 31 digits.
	 *
	 * 160 log 2 / log 36 = 30.95, so the 160-bit hash fills 31 digits in base 36
	 * fairly neatly.
	 *
	 * @param $path string
	 *
	 * @return false|string False on failure
	 */
	static function sha1Base36( $path ) {
		wfDeprecated( __METHOD__, '1.19' );

		$fsFile = new FSFile( $path );
		return $fsFile->getSha1Base36();
	}

	/**
	 * @return string
	 */
	function getLongDesc() {
		$handler = $this->getHandler();
		if ( $handler ) {
			return $handler->getLongDesc( $this );
		} else {
			return MediaHandler::getGeneralLongDesc( $this );
		}
	}

	/**
	 * @return string
	 */
	function getShortDesc() {
		$handler = $this->getHandler();
		if ( $handler ) {
			return $handler->getShortDesc( $this );
		} else {
			return MediaHandler::getGeneralShortDesc( $this );
		}
	}

	/**
	 * @return string
	 */
	function getDimensionsString() {
		$handler = $this->getHandler();
		if ( $handler ) {
			return $handler->getDimensionsString( $this );
		} else {
			return '';
		}
	}

	/**
	 * @return
	 */
	function getRedirected() {
		return $this->redirected;
	}

	/**
	 * @return Title
	 */
	function getRedirectedTitle() {
		if ( $this->redirected ) {
			if ( !$this->redirectTitle ) {
				$this->redirectTitle = Title::makeTitle( NS_FILE, $this->redirected );
			}
			return $this->redirectTitle;
		}
	}

	/**
	 * @param  $from
	 * @return void
	 */
	function redirectedFrom( $from ) {
		$this->redirected = $from;
	}

	/**
	 * @return bool
	 */
	function isMissing() {
		return false;
	}

	/**
	 * Assert that $this->repo is set to a valid FileRepo instance
	 * @throws MWException
	 */
	protected function assertRepoDefined() {
		if ( !( $this->repo instanceof $this->repoClass ) ) {
			throw new MWException( "A {$this->repoClass} object is not set for this File.\n" );
		}
	}

	/**
	 * Assert that $this->title is set to a Title
	 * @throws MWException
	 */
	protected function assertTitleDefined() {
		if ( !( $this->title instanceof Title ) ) {
			throw new MWException( "A Title object is not set for this File.\n" );
		}
	}
}
