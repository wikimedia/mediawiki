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
 * RepoGroup::singleton()->getLocalRepo()->newFile( $title );
 *
 * The convenience functions wfLocalFile() and wfFindFile() should be sufficient
 * in most cases.
 *
 * @ingroup FileAbstraction
 */
abstract class File implements IDBAccessObject {
	// Bitfield values akin to the Revision deletion constants
	const DELETED_FILE = 1;
	const DELETED_COMMENT = 2;
	const DELETED_USER = 4;
	const DELETED_RESTRICTED = 8;

	/** Force rendering in the current process */
	const RENDER_NOW = 1;
	/**
	 * Force rendering even if thumbnail already exist and using RENDER_NOW
	 * I.e. you have to pass both flags: File::RENDER_NOW | File::RENDER_FORCE
	 */
	const RENDER_FORCE = 2;

	const DELETE_SOURCE = 1;

	// Audience options for File::getDescription()
	const FOR_PUBLIC = 1;
	const FOR_THIS_USER = 2;
	const RAW = 3;

	// Options for File::thumbName()
	const THUMB_FULL_NAME = 1;

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

	/** @var FileRepo|LocalRepo|ForeignAPIRepo|bool */
	public $repo;

	/** @var Title|string|bool */
	protected $title;

	/** @var string Text of last error */
	protected $lastError;

	/** @var string Main part of the title, with underscores (Title::getDBkey) */
	protected $redirected;

	/** @var Title */
	protected $redirectedTitle;

	/** @var FSFile|bool False if undefined */
	protected $fsFile;

	/** @var MediaHandler */
	protected $handler;

	/** @var string The URL corresponding to one of the four basic zones */
	protected $url;

	/** @var string File extension */
	protected $extension;

	/** @var string The name of a file from its title object */
	protected $name;

	/** @var string The storage path corresponding to one of the zones */
	protected $path;

	/** @var string Relative path including trailing slash */
	protected $hashPath;

	/** @var string Number of pages of a multipage document, or false for
	 *    documents which aren't multipage documents
	 */
	protected $pageCount;

	/** @var string URL of transformscript (for example thumb.php) */
	protected $transformScript;

	/** @var Title */
	protected $redirectTitle;

	/** @var bool Whether the output of transform() for this file is likely to be valid. */
	protected $canRender;

	/** @var bool Whether this media file is in a format that is unlikely to
	 *    contain viruses or malicious content
	 */
	protected $isSafeFile;

	/** @var string Required Repository class type */
	protected $repoClass = 'FileRepo';

	/** @var array Cache of tmp filepaths pointing to generated bucket thumbnails, keyed by width */
	protected $tmpBucketedThumbCache = array();

	/**
	 * Call this constructor from child classes.
	 *
	 * Both $title and $repo are optional, though some functions
	 * may return false or throw exceptions if they are not set.
	 * Most subclasses will want to call assertRepoDefined() here.
	 *
	 * @param Title|string|bool $title
	 * @param FileRepo|bool $repo
	 */
	function __construct( $title, $repo ) {
		// Some subclasses do not use $title, but set name/title some other way
		if ( $title !== false ) {
			$title = self::normalizeTitle( $title, 'exception' );
		}
		$this->title = $title;
		$this->repo = $repo;
	}

	/**
	 * Given a string or Title object return either a
	 * valid Title object with namespace NS_FILE or null
	 *
	 * @param Title|string $title
	 * @param string|bool $exception Use 'exception' to throw an error on bad titles
	 * @throws MWException
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
	 * Normalize a file extension to the common form, making it lowercase and checking some synonyms,
	 * and ensure it's clean. Extensions with non-alphanumeric characters will be discarded.
	 * Keep in sync with mw.Title.normalizeExtension() in JS.
	 *
	 * @param string $extension File extension (without the leading dot)
	 * @return string File extension in canonical form
	 */
	static function normalizeExtension( $extension ) {
		$lower = strtolower( $extension );
		$squish = array(
			'htm' => 'html',
			'jpeg' => 'jpg',
			'mpeg' => 'mpg',
			'tiff' => 'tif',
			'ogv' => 'ogg' );
		if ( isset( $squish[$lower] ) ) {
			return $squish[$lower];
		} elseif ( preg_match( '/^[0-9a-z]+$/', $lower ) ) {
			return $lower;
		} else {
			return '';
		}
	}

	/**
	 * Checks if file extensions are compatible
	 *
	 * @param File $old Old file
	 * @param string $new New name
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
	function upgradeRow() {
	}

	/**
	 * Split an internet media type into its two components; if not
	 * a two-part name, set the minor type to 'unknown'.
	 *
	 * @param string $mime "text/html" etc
	 * @return array ("text", "html") etc
	 */
	public static function splitMime( $mime ) {
		if ( strpos( $mime, '/' ) !== false ) {
			return explode( '/', $mime, 2 );
		} else {
			return array( $mime, 'unknown' );
		}
	}

	/**
	 * Callback for usort() to do file sorts by name
	 *
	 * @param File $a
	 * @param File $b
	 * @return int Result of name comparison
	 */
	public static function compare( File $a, File $b ) {
		return strcmp( $a->getName(), $b->getName() );
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
	 * @return Title
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
			$ext = $this->getExtension();
			$this->url = $this->repo->getZoneUrl( 'public', $ext ) . '/' . $this->getUrlRel();
		}

		return $this->url;
	}

	/**
	 * Return a fully-qualified URL to the file.
	 * Upload URL paths _may or may not_ be fully qualified, so
	 * we check. Local paths are assumed to belong on $wgServer.
	 *
	 * @return string
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
				wfDebug( __METHOD__ . ': supposed to render ' . $this->getName() .
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
	 * @return string|bool ForeignAPIFile::getPath can return false
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
	 * @return string|bool False on failure
	 */
	public function getLocalRefPath() {
		$this->assertRepoDefined();
		if ( !isset( $this->fsFile ) ) {
			$starttime = microtime( true );
			$this->fsFile = $this->repo->getLocalReference( $this->getPath() );

			$statTiming = microtime( true ) - $starttime;
			RequestContext::getMain()->getStats()->timing(
				'media.thumbnail.generate.fetchoriginal', 1000 * $statTiming );

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
	 * @param int $page
	 * @return int|bool
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
	 * @param int $page
	 * @return bool|int False on failure
	 */
	public function getHeight( $page = 1 ) {
		return false;
	}

	/**
	 * Return the smallest bucket from $wgThumbnailBuckets which is at least
	 * $wgThumbnailMinimumBucketDistance larger than $desiredWidth. The returned bucket, if any,
	 * will always be bigger than $desiredWidth.
	 *
	 * @param int $desiredWidth
	 * @param int $page
	 * @return bool|int
	 */
	public function getThumbnailBucket( $desiredWidth, $page = 1 ) {
		global $wgThumbnailBuckets, $wgThumbnailMinimumBucketDistance;

		$imageWidth = $this->getWidth( $page );

		if ( $imageWidth === false ) {
			return false;
		}

		if ( $desiredWidth > $imageWidth ) {
			return false;
		}

		if ( !$wgThumbnailBuckets ) {
			return false;
		}

		$sortedBuckets = $wgThumbnailBuckets;

		sort( $sortedBuckets );

		foreach ( $sortedBuckets as $bucket ) {
			if ( $bucket >= $imageWidth ) {
				return false;
			}

			if ( $bucket - $wgThumbnailMinimumBucketDistance > $desiredWidth ) {
				return $bucket;
			}
		}

		// Image is bigger than any available bucket
		return false;
	}

	/**
	 * Returns ID or name of user who uploaded the file
	 * STUB
	 *
	 * @param string $type 'text' or 'id'
	 * @return string|int
	 */
	public function getUser( $type = 'text' ) {
		return null;
	}

	/**
	 * Get the duration of a media file in seconds
	 *
	 * @return int
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
	 * Gives a (possibly empty) list of languages to render
	 * the file in.
	 *
	 * If the file doesn't have translations, or if the file
	 * format does not support that sort of thing, returns
	 * an empty array.
	 *
	 * @return array
	 * @since 1.23
	 */
	public function getAvailableLanguages() {
		$handler = $this->getHandler();
		if ( $handler ) {
			return $handler->getAvailableLanguages( $this );
		} else {
			return array();
		}
	}

	/**
	 * In files that support multiple language, what is the default language
	 * to use if none specified.
	 *
	 * @return string Lang code, or null if filetype doesn't support multiple languages.
	 * @since 1.23
	 */
	public function getDefaultRenderLanguage() {
		$handler = $this->getHandler();
		if ( $handler ) {
			return $handler->getDefaultRenderLanguage( $this );
		} else {
			return null;
		}
	}

	/**
	 * Will the thumbnail be animated if one would expect it to be.
	 *
	 * Currently used to add a warning to the image description page
	 *
	 * @return bool False if the main image is both animated
	 *   and the thumbnail is not. In all other cases must return
	 *   true. If image is not renderable whatsoever, should
	 *   return true.
	 */
	public function canAnimateThumbIfAppropriate() {
		$handler = $this->getHandler();
		if ( !$handler ) {
			// We cannot handle image whatsoever, thus
			// one would not expect it to be animated
			// so true.
			return true;
		} else {
			if ( $this->allowInlineDisplay()
				&& $handler->isAnimatedImage( $this )
				&& !$handler->canAnimateThumbnail( $this )
			) {
				// Image is animated, but thumbnail isn't.
				// This is unexpected to the user.
				return false;
			} else {
				// Image is not animated, so one would
				// not expect thumb to be
				return true;
			}
		}
	}

	/**
	 * Get handler-specific metadata
	 * Overridden by LocalFile, UnregisteredLocalFile
	 * STUB
	 * @return bool|array
	 */
	public function getMetadata() {
		return false;
	}

	/**
	 * Like getMetadata but returns a handler independent array of common values.
	 * @see MediaHandler::getCommonMetaArray()
	 * @return array|bool Array or false if not supported
	 * @since 1.23
	 */
	public function getCommonMetaArray() {
		$handler = $this->getHandler();

		if ( !$handler ) {
			return false;
		}

		return $handler->getCommonMetaArray( $this );
	}

	/**
	 * get versioned metadata
	 *
	 * @param array|string $metadata Array or string of (serialized) metadata
	 * @param int $version Version number.
	 * @return array Array containing metadata, or what was passed to it on fail
	 *   (unserializing if not array)
	 */
	public function convertMetadataVersion( $metadata, $version ) {
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
	 * @return int
	 */
	public function getBitDepth() {
		return 0;
	}

	/**
	 * Return the size of the image file, in bytes
	 * Overridden by LocalFile, UnregisteredLocalFile
	 * STUB
	 * @return bool
	 */
	public function getSize() {
		return false;
	}

	/**
	 * Returns the MIME type of the file.
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
	 * @return string
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
			$this->canRender = $this->getHandler() && $this->handler->canRender( $this ) && $this->exists();
		}

		return $this->canRender;
	}

	/**
	 * Accessor for __get()
	 * @return bool
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
			$this->isSafeFile = $this->getIsSafeFileUncached();
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
	protected function getIsSafeFileUncached() {
		global $wgTrustedMediaFormats;

		if ( $this->allowInlineDisplay() ) {
			return true;
		}
		if ( $this->isTrustedFile() ) {
			return true;
		}

		$type = $this->getMediaType();
		$mime = $this->getMimeType();
		#wfDebug( "LocalFile::isSafeFile: type= $type, mime= $mime\n" );

		if ( !$type || $type === MEDIATYPE_UNKNOWN ) {
			return false; #unknown type, not trusted
		}
		if ( in_array( $type, $wgTrustedMediaFormats ) ) {
			return true;
		}

		if ( $mime === "unknown/unknown" ) {
			return false; #unknown type, not trusted
		}
		if ( in_array( $mime, $wgTrustedMediaFormats ) ) {
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
	 * Load any lazy-loaded file object fields from source
	 *
	 * This is only useful when setting $flags
	 *
	 * Overridden by LocalFile to actually query the DB
	 *
	 * @param integer $flags Bitfield of File::READ_* constants
	 */
	public function load( $flags = 0 ) {
	}

	/**
	 * Returns true if file exists in the repository.
	 *
	 * Overridden by LocalFile to avoid unnecessary stat calls.
	 *
	 * @return bool Whether file exists in the repository.
	 */
	public function exists() {
		return $this->getPath() && $this->repo->fileExists( $this->path );
	}

	/**
	 * Returns true if file exists in the repository and can be included in a page.
	 * It would be unsafe to include private images, making public thumbnails inadvertently
	 *
	 * @return bool Whether file exists in the repository and is includable.
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
					$this->transformScript = wfAppendQuery( $script, array( 'f' => $this->getName() ) );
				}
			}
		}

		return $this->transformScript;
	}

	/**
	 * Get a ThumbnailImage which is the same size as the source
	 *
	 * @param array $handlerParams
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
		// be sure to ignore any height specification as well (bug 62258)
		unset( $hp['height'] );

		return $this->transform( $hp );
	}

	/**
	 * Return the file name of a thumbnail with the specified parameters.
	 * Use File::THUMB_FULL_NAME to always get a name like "<params>-<source>".
	 * Otherwise, the format may be "<params>-<source>" or "<params>-thumbnail.<ext>".
	 *
	 * @param array $params Handler-specific parameters
	 * @param int $flags Bitfield that supports THUMB_* constants
	 * @return string
	 */
	public function thumbName( $params, $flags = 0 ) {
		$name = ( $this->repo && !( $flags & self::THUMB_FULL_NAME ) )
			? $this->repo->nameForThumb( $this->getName() )
			: $this->getName();

		return $this->generateThumbName( $name, $params );
	}

	/**
	 * Generate a thumbnail file name from a name and specified parameters
	 *
	 * @param string $name
	 * @param array $params Parameters which will be passed to MediaHandler::makeParamString
	 * @return string
	 */
	public function generateThumbName( $name, $params ) {
		if ( !$this->getHandler() ) {
			return null;
		}
		$extension = $this->getExtension();
		list( $thumbExt, ) = $this->getHandler()->getThumbType(
			$extension, $this->getMimeType(), $params );
		$thumbName = $this->getHandler()->makeParamString( $params ) . '-' . $name;
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
	 * @param int $width Maximum width of the generated thumbnail
	 * @param int $height Maximum height of the image (optional)
	 *
	 * @return string
	 */
	public function createThumb( $width, $height = -1 ) {
		$params = array( 'width' => $width );
		if ( $height != -1 ) {
			$params['height'] = $height;
		}
		$thumb = $this->transform( $params );
		if ( !$thumb || $thumb->isError() ) {
			return '';
		}

		return $thumb->getUrl();
	}

	/**
	 * Return either a MediaTransformError or placeholder thumbnail (if $wgIgnoreImageErrors)
	 *
	 * @param string $thumbPath Thumbnail storage path
	 * @param string $thumbUrl Thumbnail URL
	 * @param array $params
	 * @param int $flags
	 * @return MediaTransformOutput
	 */
	protected function transformErrorOutput( $thumbPath, $thumbUrl, $params, $flags ) {
		global $wgIgnoreImageErrors;

		$handler = $this->getHandler();
		if ( $handler && $wgIgnoreImageErrors && !( $flags & self::RENDER_NOW ) ) {
			return $handler->getTransform( $this, $thumbPath, $thumbUrl, $params );
		} else {
			return new MediaTransformError( 'thumbnail_error',
				$params['width'], 0, wfMessage( 'thumbnail-dest-create' )->text() );
		}
	}

	/**
	 * Transform a media file
	 *
	 * @param array $params An associative array of handler-specific parameters.
	 *   Typical keys are width, height and page.
	 * @param int $flags A bitfield, may contain self::RENDER_NOW to force rendering
	 * @return MediaTransformOutput|bool False on failure
	 */
	function transform( $params, $flags = 0 ) {
		global $wgThumbnailEpoch;

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

			$handler = $this->getHandler();
			$script = $this->getTransformScript();
			if ( $script && !( $flags & self::RENDER_NOW ) ) {
				// Use a script to transform on client request, if possible
				$thumb = $handler->getScriptedTransform( $this, $script, $params );
				if ( $thumb ) {
					break;
				}
			}

			$normalisedParams = $params;
			$handler->normaliseParams( $this, $normalisedParams );

			$thumbName = $this->thumbName( $normalisedParams );
			$thumbUrl = $this->getThumbUrl( $thumbName );
			$thumbPath = $this->getThumbPath( $thumbName ); // final thumb path

			if ( $this->repo ) {
				// Defer rendering if a 404 handler is set up...
				if ( $this->repo->canTransformVia404() && !( $flags & self::RENDER_NOW ) ) {
					wfDebug( __METHOD__ . " transformation deferred.\n" );
					// XXX: Pass in the storage path even though we are not rendering anything
					// and the path is supposed to be an FS path. This is due to getScalerType()
					// getting called on the path and clobbering $thumb->getUrl() if it's false.
					$thumb = $handler->getTransform( $this, $thumbPath, $thumbUrl, $params );
					break;
				}
				// Check if an up-to-date thumbnail already exists...
				wfDebug( __METHOD__ . ": Doing stat for $thumbPath\n" );
				if ( !( $flags & self::RENDER_FORCE ) && $this->repo->fileExists( $thumbPath ) ) {
					$timestamp = $this->repo->getFileTimestamp( $thumbPath );
					if ( $timestamp !== false && $timestamp >= $wgThumbnailEpoch ) {
						// XXX: Pass in the storage path even though we are not rendering anything
						// and the path is supposed to be an FS path. This is due to getScalerType()
						// getting called on the path and clobbering $thumb->getUrl() if it's false.
						$thumb = $handler->getTransform( $this, $thumbPath, $thumbUrl, $params );
						$thumb->setStoragePath( $thumbPath );
						break;
					}
				} elseif ( $flags & self::RENDER_FORCE ) {
					wfDebug( __METHOD__ . " forcing rendering per flag File::RENDER_FORCE\n" );
				}

				// If the backend is ready-only, don't keep generating thumbnails
				// only to return transformation errors, just return the error now.
				if ( $this->repo->getReadOnlyReason() !== false ) {
					$thumb = $this->transformErrorOutput( $thumbPath, $thumbUrl, $params, $flags );
					break;
				}
			}

			$tmpFile = $this->makeTransformTmpFile( $thumbPath );

			if ( !$tmpFile ) {
				$thumb = $this->transformErrorOutput( $thumbPath, $thumbUrl, $params, $flags );
			} else {
				$thumb = $this->generateAndSaveThumb( $tmpFile, $params, $flags );
			}
		} while ( false );

		return is_object( $thumb ) ? $thumb : false;
	}

	/**
	 * Generates a thumbnail according to the given parameters and saves it to storage
	 * @param TempFSFile $tmpFile Temporary file where the rendered thumbnail will be saved
	 * @param array $transformParams
	 * @param int $flags
	 * @return bool|MediaTransformOutput
	 */
	public function generateAndSaveThumb( $tmpFile, $transformParams, $flags ) {
		global $wgIgnoreImageErrors;

		$stats = RequestContext::getMain()->getStats();

		$handler = $this->getHandler();

		$normalisedParams = $transformParams;
		$handler->normaliseParams( $this, $normalisedParams );

		$thumbName = $this->thumbName( $normalisedParams );
		$thumbUrl = $this->getThumbUrl( $thumbName );
		$thumbPath = $this->getThumbPath( $thumbName ); // final thumb path

		$tmpThumbPath = $tmpFile->getPath();

		if ( $handler->supportsBucketing() ) {
			$this->generateBucketsIfNeeded( $normalisedParams, $flags );
		}

		$starttime = microtime( true );

		// Actually render the thumbnail...
		$thumb = $handler->doTransform( $this, $tmpThumbPath, $thumbUrl, $transformParams );
		$tmpFile->bind( $thumb ); // keep alive with $thumb

		$statTiming = microtime( true ) - $starttime;
		$stats->timing( 'media.thumbnail.generate.transform', 1000 * $statTiming );

		if ( !$thumb ) { // bad params?
			$thumb = false;
		} elseif ( $thumb->isError() ) { // transform error
			$this->lastError = $thumb->toText();
			// Ignore errors if requested
			if ( $wgIgnoreImageErrors && !( $flags & self::RENDER_NOW ) ) {
				$thumb = $handler->getTransform( $this, $tmpThumbPath, $thumbUrl, $transformParams );
			}
		} elseif ( $this->repo && $thumb->hasFile() && !$thumb->fileIsSource() ) {
			// Copy the thumbnail from the file system into storage...

			$starttime = microtime( true );

			$disposition = $this->getThumbDisposition( $thumbName );
			$status = $this->repo->quickImport( $tmpThumbPath, $thumbPath, $disposition );
			if ( $status->isOK() ) {
				$thumb->setStoragePath( $thumbPath );
			} else {
				$thumb = $this->transformErrorOutput( $thumbPath, $thumbUrl, $transformParams, $flags );
			}

			$statTiming = microtime( true ) - $starttime;
			$stats->timing( 'media.thumbnail.generate.store', 1000 * $statTiming );

			// Give extensions a chance to do something with this thumbnail...
			Hooks::run( 'FileTransformed', array( $this, $thumb, $tmpThumbPath, $thumbPath ) );
		}

		return $thumb;
	}

	/**
	 * Generates chained bucketed thumbnails if needed
	 * @param array $params
	 * @param int $flags
	 * @return bool Whether at least one bucket was generated
	 */
	protected function generateBucketsIfNeeded( $params, $flags = 0 ) {
		if ( !$this->repo
			|| !isset( $params['physicalWidth'] )
			|| !isset( $params['physicalHeight'] )
			|| !( $bucket = $this->getThumbnailBucket( $params['physicalWidth'] ) )
			|| $bucket == $params['physicalWidth'] ) {
			return false;
		}

		$bucketPath = $this->getBucketThumbPath( $bucket );

		if ( $this->repo->fileExists( $bucketPath ) ) {
			return false;
		}

		$starttime = microtime( true );

		$params['physicalWidth'] = $bucket;
		$params['width'] = $bucket;

		$params = $this->getHandler()->sanitizeParamsForBucketing( $params );

		$tmpFile = $this->makeTransformTmpFile( $bucketPath );

		if ( !$tmpFile ) {
			return false;
		}

		$thumb = $this->generateAndSaveThumb( $tmpFile, $params, $flags );

		$buckettime = microtime( true ) - $starttime;

		if ( !$thumb || $thumb->isError() ) {
			return false;
		}

		$this->tmpBucketedThumbCache[$bucket] = $tmpFile->getPath();
		// For the caching to work, we need to make the tmp file survive as long as
		// this object exists
		$tmpFile->bind( $this );

		RequestContext::getMain()->getStats()->timing(
			'media.thumbnail.generate.bucket', 1000 * $buckettime );

		return true;
	}

	/**
	 * Returns the most appropriate source image for the thumbnail, given a target thumbnail size
	 * @param array $params
	 * @return array Source path and width/height of the source
	 */
	public function getThumbnailSource( $params ) {
		if ( $this->repo
			&& $this->getHandler()->supportsBucketing()
			&& isset( $params['physicalWidth'] )
			&& $bucket = $this->getThumbnailBucket( $params['physicalWidth'] )
		) {
			if ( $this->getWidth() != 0 ) {
				$bucketHeight = round( $this->getHeight() * ( $bucket / $this->getWidth() ) );
			} else {
				$bucketHeight = 0;
			}

			// Try to avoid reading from storage if the file was generated by this script
			if ( isset( $this->tmpBucketedThumbCache[$bucket] ) ) {
				$tmpPath = $this->tmpBucketedThumbCache[$bucket];

				if ( file_exists( $tmpPath ) ) {
					return array(
						'path' => $tmpPath,
						'width' => $bucket,
						'height' => $bucketHeight
					);
				}
			}

			$bucketPath = $this->getBucketThumbPath( $bucket );

			if ( $this->repo->fileExists( $bucketPath ) ) {
				$fsFile = $this->repo->getLocalReference( $bucketPath );

				if ( $fsFile ) {
					return array(
						'path' => $fsFile->getPath(),
						'width' => $bucket,
						'height' => $bucketHeight
					);
				}
			}
		}

		// Thumbnailing a very large file could result in network saturation if
		// everyone does it at once.
		if ( $this->getSize() >= 1e7 ) { // 10MB
			$that = $this;
			$work = new PoolCounterWorkViaCallback( 'GetLocalFileCopy', sha1( $this->getName() ),
				array(
					'doWork' => function () use ( $that ) {
						return $that->getLocalRefPath();
					}
				)
			);
			$srcPath = $work->execute();
		} else {
			$srcPath = $this->getLocalRefPath();
		}

		// Original file
		return array(
			'path' => $srcPath,
			'width' => $this->getWidth(),
			'height' => $this->getHeight()
		);
	}

	/**
	 * Returns the repo path of the thumb for a given bucket
	 * @param int $bucket
	 * @return string
	 */
	protected function getBucketThumbPath( $bucket ) {
		$thumbName = $this->getBucketThumbName( $bucket );
		return $this->getThumbPath( $thumbName );
	}

	/**
	 * Returns the name of the thumb for a given bucket
	 * @param int $bucket
	 * @return string
	 */
	protected function getBucketThumbName( $bucket ) {
		return $this->thumbName( array( 'physicalWidth' => $bucket ) );
	}

	/**
	 * Creates a temp FS file with the same extension and the thumbnail
	 * @param string $thumbPath Thumbnail path
	 * @return TempFSFile
	 */
	protected function makeTransformTmpFile( $thumbPath ) {
		$thumbExt = FileBackend::extensionFromPath( $thumbPath );
		return TempFSFile::factory( 'transform_', $thumbExt );
	}

	/**
	 * @param string $thumbName Thumbnail name
	 * @param string $dispositionType Type of disposition (either "attachment" or "inline")
	 * @return string Content-Disposition header value
	 */
	function getThumbDisposition( $thumbName, $dispositionType = 'inline' ) {
		$fileName = $this->name; // file name to suggest
		$thumbExt = FileBackend::extensionFromPath( $thumbName );
		if ( $thumbExt != '' && $thumbExt !== $this->getExtension() ) {
			$fileName .= ".$thumbExt";
		}

		return FileBackend::makeContentDisposition( $dispositionType, $fileName );
	}

	/**
	 * Hook into transform() to allow migration of thumbnail files
	 * STUB
	 * Overridden by LocalFile
	 * @param string $thumbName
	 */
	function migrateThumbFile( $thumbName ) {
	}

	/**
	 * Get a MediaHandler instance for this file
	 *
	 * @return MediaHandler|bool Registered MediaHandler for file's MIME type
	 *   or false if none found
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
		global $wgResourceBasePath, $IP;
		$assetsPath = "$wgResourceBasePath/resources/assets/file-type-icons/";
		$assetsDirectory = "$IP/resources/assets/file-type-icons/";

		$try = array( 'fileicon-' . $this->getExtension() . '.png', 'fileicon.png' );
		foreach ( $try as $icon ) {
			if ( file_exists( $assetsDirectory . $icon ) ) { // always FS
				$params = array( 'width' => 120, 'height' => 120 );

				return new ThumbnailImage( $this, $assetsPath . $icon, false, $params );
			}
		}

		return null;
	}

	/**
	 * Get last thumbnailing error.
	 * Largely obsolete.
	 * @return string
	 */
	function getLastError() {
		return $this->lastError;
	}

	/**
	 * Get all thumbnail names previously generated for this file
	 * STUB
	 * Overridden by LocalFile
	 * @return array
	 */
	function getThumbnails() {
		return array();
	}

	/**
	 * Purge shared caches such as thumbnails and DB data caching
	 * STUB
	 * Overridden by LocalFile
	 * @param array $options Options, which include:
	 *   'forThumbRefresh' : The purging is only to refresh thumbnails
	 */
	function purgeCache( $options = array() ) {
	}

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
	 * @param int $limit Limit of rows to return
	 * @param string $start Only revisions older than $start will be returned
	 * @param string $end Only revisions newer than $end will be returned
	 * @param bool $inc Include the endpoints of the time range
	 *
	 * @return array
	 */
	function getHistory( $limit = null, $start = null, $end = null, $inc = true ) {
		return array();
	}

	/**
	 * Return the history of this file, line by line. Starts with current version,
	 * then old versions. Should return an object similar to an image/oldimage
	 * database row.
	 *
	 * STUB
	 * Overridden in LocalFile
	 * @return bool
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
	public function resetHistory() {
	}

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
	 * This function is overridden in OldLocalFile to be like getArchiveRel().
	 *
	 * @return string
	 */
	function getRel() {
		return $this->getHashPath() . $this->getName();
	}

	/**
	 * Get the path of an archived file relative to the public zone root
	 *
	 * @param bool|string $suffix If not false, the name of an archived thumbnail file
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
	 * @param bool|string $suffix If not false, the name of a thumbnail file
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
	 * This function is overridden in OldLocalFile to be like getArchiveUrl().
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
	 * @param string $archiveName The timestamped name of an archived image
	 * @param bool|string $suffix If not false, the name of a thumbnail file
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
	 * @param bool|string $suffix If not false, the name of an archived file.
	 * @return string
	 */
	function getArchivePath( $suffix = false ) {
		$this->assertRepoDefined();

		return $this->repo->getZonePath( 'public' ) . '/' . $this->getArchiveRel( $suffix );
	}

	/**
	 * Get the path of an archived file's thumbs, or a particular thumb if $suffix is specified
	 *
	 * @param string $archiveName The timestamped name of an archived image
	 * @param bool|string $suffix If not false, the name of a thumbnail file
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
	 * @param bool|string $suffix If not false, the name of a thumbnail file
	 * @return string
	 */
	function getThumbPath( $suffix = false ) {
		$this->assertRepoDefined();

		return $this->repo->getZonePath( 'thumb' ) . '/' . $this->getThumbRel( $suffix );
	}

	/**
	 * Get the path of the transcoded directory, or a particular file if $suffix is specified
	 *
	 * @param bool|string $suffix If not false, the name of a media file
	 * @return string
	 */
	function getTranscodedPath( $suffix = false ) {
		$this->assertRepoDefined();

		return $this->repo->getZonePath( 'transcoded' ) . '/' . $this->getThumbRel( $suffix );
	}

	/**
	 * Get the URL of the archive directory, or a particular file if $suffix is specified
	 *
	 * @param bool|string $suffix If not false, the name of an archived file
	 * @return string
	 */
	function getArchiveUrl( $suffix = false ) {
		$this->assertRepoDefined();
		$ext = $this->getExtension();
		$path = $this->repo->getZoneUrl( 'public', $ext ) . '/archive/' . $this->getHashPath();
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
	 * @param string $archiveName The timestamped name of an archived image
	 * @param bool|string $suffix If not false, the name of a thumbnail file
	 * @return string
	 */
	function getArchiveThumbUrl( $archiveName, $suffix = false ) {
		$this->assertRepoDefined();
		$ext = $this->getExtension();
		$path = $this->repo->getZoneUrl( 'thumb', $ext ) . '/archive/' .
			$this->getHashPath() . rawurlencode( $archiveName ) . "/";
		if ( $suffix === false ) {
			$path = substr( $path, 0, -1 );
		} else {
			$path .= rawurlencode( $suffix );
		}

		return $path;
	}

	/**
	 * Get the URL of the zone directory, or a particular file if $suffix is specified
	 *
	 * @param string $zone Name of requested zone
	 * @param bool|string $suffix If not false, the name of a file in zone
	 * @return string Path
	 */
	function getZoneUrl( $zone, $suffix = false ) {
		$this->assertRepoDefined();
		$ext = $this->getExtension();
		$path = $this->repo->getZoneUrl( $zone, $ext ) . '/' . $this->getUrlRel();
		if ( $suffix !== false ) {
			$path .= '/' . rawurlencode( $suffix );
		}

		return $path;
	}

	/**
	 * Get the URL of the thumbnail directory, or a particular file if $suffix is specified
	 *
	 * @param bool|string $suffix If not false, the name of a thumbnail file
	 * @return string Path
	 */
	function getThumbUrl( $suffix = false ) {
		return $this->getZoneUrl( 'thumb', $suffix );
	}

	/**
	 * Get the URL of the transcoded directory, or a particular file if $suffix is specified
	 *
	 * @param bool|string $suffix If not false, the name of a media file
	 * @return string Path
	 */
	function getTranscodedUrl( $suffix = false ) {
		return $this->getZoneUrl( 'transcoded', $suffix );
	}

	/**
	 * Get the public zone virtual URL for a current version source file
	 *
	 * @param bool|string $suffix If not false, the name of a thumbnail file
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
	 * @param bool|string $suffix If not false, the name of a thumbnail file
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
	 * @param bool|string $suffix If not false, the name of a thumbnail file
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

		return (bool)$this->repo->getHashLevels();
	}

	/**
	 * @throws MWException
	 */
	function readOnlyError() {
		throw new MWException( get_class( $this ) . ': write operations are not supported' );
	}

	/**
	 * Record a file upload in the upload log and the image table
	 * STUB
	 * Overridden by LocalFile
	 * @param string $oldver
	 * @param string $desc
	 * @param string $license
	 * @param string $copyStatus
	 * @param string $source
	 * @param bool $watch
	 * @param string|bool $timestamp
	 * @param null|User $user User object or null to use $wgUser
	 * @return bool
	 * @throws MWException
	 */
	function recordUpload( $oldver, $desc, $license = '', $copyStatus = '', $source = '',
		$watch = false, $timestamp = false, User $user = null
	) {
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
	 * Options to $options include:
	 *   - headers : name/value map of HTTP headers to use in response to GET/HEAD requests
	 *
	 * @param string $srcPath Local filesystem path to the source image
	 * @param int $flags A bitwise combination of:
	 *   File::DELETE_SOURCE    Delete the source file, i.e. move rather than copy
	 * @param array $options Optional additional parameters
	 * @return FileRepoStatus On success, the value member contains the
	 *   archive name, or an empty string if it was a new file.
	 *
	 * STUB
	 * Overridden by LocalFile
	 */
	function publish( $srcPath, $flags = 0, array $options = array() ) {
		$this->readOnlyError();
	}

	/**
	 * @param bool|IContextSource $context Context to use (optional)
	 * @return bool
	 */
	function formatMetadata( $context = false ) {
		if ( !$this->getHandler() ) {
			return false;
		}

		return $this->getHandler()->formatMetadata( $this, $context );
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
	 * @return FileRepo|LocalRepo|bool
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
	 * @param int $field One of DELETED_* bitfield constants
	 * @return bool
	 */
	function isDeleted( $field ) {
		return false;
	}

	/**
	 * Return the deletion bitfield
	 * STUB
	 * @return int
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
	 * @param Title $target New file name
	 * @return FileRepoStatus
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
	 * @param string $reason
	 * @param bool $suppress Hide content from sysops?
	 * @param User|null $user
	 * @return bool Boolean on success, false on some kind of failure
	 * STUB
	 * Overridden by LocalFile
	 */
	function delete( $reason, $suppress = false, $user = null ) {
		$this->readOnlyError();
	}

	/**
	 * Restore all or specified deleted revisions to the given file.
	 * Permissions and logging are left to the caller.
	 *
	 * May throw database exceptions on error.
	 *
	 * @param array $versions Set of record ids of deleted items to restore,
	 *   or empty to restore all revisions.
	 * @param bool $unsuppress Remove restrictions on content upon restoration?
	 * @return int|bool The number of file revisions restored if successful,
	 *   or false on failure
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
	 * @return bool
	 */
	function isMultipage() {
		return $this->getHandler() && $this->handler->isMultiPage( $this );
	}

	/**
	 * Returns the number of pages of a multipage document, or false for
	 * documents which aren't multipage documents
	 *
	 * @return bool|int
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
	 * @param int $srcWidth
	 * @param int $srcHeight
	 * @param int $dstWidth
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
	 * can't be determined. Loads the image size directly from the file ignoring caches.
	 *
	 * @note Use getWidth()/getHeight() instead of this method unless you have a
	 *  a good reason. This method skips all caches.
	 *
	 * @param string $filePath The path to the file (e.g. From getLocalPathRef() )
	 * @return array The width, followed by height, with optionally more things after
	 */
	function getImageSize( $filePath ) {
		if ( !$this->getHandler() ) {
			return false;
		}

		return $this->getHandler()->getImageSize( $this, $filePath );
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
	 * @param bool|Language $lang Optional language to fetch description in
	 * @return string
	 */
	function getDescriptionText( $lang = false ) {
		global $wgMemc, $wgLang;
		if ( !$this->repo || !$this->repo->fetchDescription ) {
			return false;
		}
		if ( !$lang ) {
			$lang = $wgLang;
		}
		$renderUrl = $this->repo->getDescriptionRenderUrl( $this->getName(), $lang->getCode() );
		if ( $renderUrl ) {
			if ( $this->repo->descriptionCacheExpiry > 0 ) {
				wfDebug( "Attempting to get the description from cache..." );
				$key = $this->repo->getLocalCacheKey(
					'RemoteFileDescription',
					'url',
					$lang->getCode(),
					$this->getName()
				);
				$obj = $wgMemc->get( $key );
				if ( $obj ) {
					wfDebug( "success!\n" );

					return $obj;
				}
				wfDebug( "miss\n" );
			}
			wfDebug( "Fetching shared description from $renderUrl\n" );
			$res = Http::get( $renderUrl, array(), __METHOD__ );
			if ( $res && $this->repo->descriptionCacheExpiry > 0 ) {
				$wgMemc->set( $key, $res, $this->repo->descriptionCacheExpiry );
			}

			return $res;
		} else {
			return false;
		}
	}

	/**
	 * Get description of file revision
	 * STUB
	 *
	 * @param int $audience One of:
	 *   File::FOR_PUBLIC       to be displayed to all users
	 *   File::FOR_THIS_USER    to be displayed to the given user
	 *   File::RAW              get the description regardless of permissions
	 * @param User $user User object to check for, only if FOR_THIS_USER is
	 *   passed to the $audience parameter
	 * @return string
	 */
	function getDescription( $audience = self::FOR_PUBLIC, User $user = null ) {
		return null;
	}

	/**
	 * Get the 14-character timestamp of the file upload
	 *
	 * @return string|bool TS_MW timestamp or false on failure
	 */
	function getTimestamp() {
		$this->assertRepoDefined();

		return $this->repo->getFileTimestamp( $this->getPath() );
	}

	/**
	 * Returns the timestamp (in TS_MW format) of the last change of the description page.
	 * Returns false if the file does not have a description page, or retrieving the timestamp
	 * would be expensive.
	 * @since 1.25
	 * @return string|bool
	 */
	public function getDescriptionTouched() {
		return false;
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
	 * Get the deletion archive key, "<sha1>.<ext>"
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
	 * @param int $field
	 * @param User $user User object to check, or null to use $wgUser
	 * @return bool
	 */
	function userCan( $field, User $user = null ) {
		return true;
	}

	/**
	 * @return array HTTP header name/value map to use for HEAD/GET request responses
	 */
	function getStreamHeaders() {
		$handler = $this->getHandler();
		if ( $handler ) {
			return $handler->getStreamHeaders( $this->getMetadata() );
		} else {
			return array();
		}
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
	 * @return string
	 */
	function getRedirected() {
		return $this->redirected;
	}

	/**
	 * @return Title|null
	 */
	function getRedirectedTitle() {
		if ( $this->redirected ) {
			if ( !$this->redirectTitle ) {
				$this->redirectTitle = Title::makeTitle( NS_FILE, $this->redirected );
			}

			return $this->redirectTitle;
		}

		return null;
	}

	/**
	 * @param string $from
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
	 * Check if this file object is small and can be cached
	 * @return bool
	 */
	public function isCacheable() {
		return true;
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

	/**
	 * True if creating thumbnails from the file is large or otherwise resource-intensive.
	 * @return bool
	 */
	public function isExpensiveToThumbnail() {
		$handler = $this->getHandler();
		return $handler ? $handler->isExpensiveToThumbnail( $this ) : false;
	}

	/**
	 * Whether the thumbnails created on the same server as this code is running.
	 * @since 1.25
	 * @return bool
	 */
	public function isTransformedLocally() {
		return true;
	}
}
