<?php
/**
 */

/**
 * NOTE FOR WINDOWS USERS:
 * To enable EXIF functions, add the folloing lines to the
 * "Windows extensions" section of php.ini:
 *
 * extension=extensions/php_mbstring.dll
 * extension=extensions/php_exif.dll
 */

/**
 * Bump this number when serialized cache records may be incompatible.
 */
define( 'MW_IMAGE_VERSION', 2 );

/**
 * Class to represent an image
 *
 * Provides methods to retrieve paths (physical, logical, URL),
 * to generate thumbnails or for uploading.
 *
 * @addtogroup Media
 */
class Image
{
	const DELETED_FILE = 1;
	const DELETED_COMMENT = 2;
	const DELETED_USER = 4;
	const DELETED_RESTRICTED = 8;
	const RENDER_NOW = 1;
    
	/**#@+
	 * @private
	 */
	var	$name,          # name of the image (constructor)
		$imagePath,     # Path of the image (loadFromXxx)
		$url,           # Image URL (accessor)
		$title,         # Title object for this image (constructor)
		$fileExists,    # does the image file exist on disk? (loadFromXxx)
		$fromSharedDirectory, # load this image from $wgSharedUploadDirectory (loadFromXxx)
		$historyLine,	# Number of line to return by nextHistoryLine() (constructor)
		$historyRes,	# result of the query for the image's history (nextHistoryLine)
		$width,         # \
		$height,        #  |
		$bits,          #   --- returned by getimagesize (loadFromXxx)
		$attr,          # /
		$type,          # MEDIATYPE_xxx (bitmap, drawing, audio...)
		$mime,          # MIME type, determined by MimeMagic::guessMimeType
		$extension,     # The file extension (constructor)
		$size,          # Size in bytes (loadFromXxx)
		$metadata,      # Metadata
		$dataLoaded,    # Whether or not all this has been loaded from the database (loadFromXxx)
		$page,		# Page to render when creating thumbnails
		$lastError;     # Error string associated with a thumbnail display error


	/**#@-*/

	/**
	 * Create an Image object from an image name
	 *
	 * @param string $name name of the image, used to create a title object using Title::makeTitleSafe
	 * @return Image
	 * @public
	 */
	public static function newFromName( $name ) {
		$title = Title::makeTitleSafe( NS_IMAGE, $name );
		if ( is_object( $title ) ) {
			return new Image( $title );
		} else {
			return NULL;
		}
	}

	/**
	 * Obsolete factory function, use constructor
	 * @param Title $title
	 * @return Image
	 * @deprecated
	 */
	function newFromTitle( $title ) {
		return new Image( $title );
	}

	/**
	 * Constructor
	 * @param Title $title
	 * @return void
	 */
	function Image( $title ) {
		if( !is_object( $title ) ) {
			throw new MWException( 'Image constructor given bogus title.' );
		}
		$this->title =& $title;
		$this->name = $title->getDBkey();
		$this->metadata = '';

		$n = strrpos( $this->name, '.' );
		$this->extension = Image::normalizeExtension( $n ?
			substr( $this->name, $n + 1 ) : '' );
		$this->historyLine = 0;

		$this->dataLoaded = false;
	}

	/**
	 * Normalize a file extension to the common form, and ensure it's clean.
	 * Extensions with non-alphanumeric characters will be discarded.
	 *
	 * @param string $ext (without the .)
	 * @return string
	 */
	static function normalizeExtension( $ext ) {
		$lower = strtolower( $ext );
		$squish = array(
			'htm' => 'html',
			'jpeg' => 'jpg',
			'mpeg' => 'mpg',
			'tiff' => 'tif' );
		if( isset( $squish[$lower] ) ) {
			return $squish[$lower];
		} elseif( preg_match( '/^[0-9a-z]+$/', $lower ) ) {
			return $lower;
		} else {
			return '';
		}
	}

	/**
	 * Get the memcached keys
	 * @return array[int]mixed Returns an array, first element is the local cache key, second is the shared cache key, if there is one
	 */
	function getCacheKeys( ) {
		global $wgUseSharedUploads, $wgSharedUploadDBname, $wgCacheSharedUploads;

		$hashedName = md5($this->name);
		$keys = array( wfMemcKey( 'Image', $hashedName ) );
		if ( $wgUseSharedUploads && $wgSharedUploadDBname && $wgCacheSharedUploads ) {
			$keys[] = wfForeignMemcKey( $wgSharedUploadDBname, false, 'Image', $hashedName );
		}
		return $keys;
	}

	/**
	 * Try to load image metadata from memcached. Returns true on success.
	 */
	function loadFromCache() {
		global $wgUseSharedUploads, $wgMemc;
		wfProfileIn( __METHOD__ );
		$this->dataLoaded = false;
		$keys = $this->getCacheKeys();
		$cachedValues = $wgMemc->get( $keys[0] );

		// Check if the key existed and belongs to this version of MediaWiki
		if (!empty($cachedValues) && is_array($cachedValues)
		  && isset($cachedValues['version']) && ( $cachedValues['version'] == MW_IMAGE_VERSION )
		  && isset( $cachedValues['mime'] ) && isset( $cachedValues['metadata'] ) )
		{
			if ( $wgUseSharedUploads && $cachedValues['fromShared']) {
				# if this is shared file, we need to check if image
				# in shared repository has not changed
				if ( isset( $keys[1] ) ) {
					$commonsCachedValues = $wgMemc->get( $keys[1] );
					if (!empty($commonsCachedValues) && is_array($commonsCachedValues)
					  && isset($commonsCachedValues['version'])
					  && ( $commonsCachedValues['version'] == MW_IMAGE_VERSION )
					  && isset($commonsCachedValues['mime'])) {
						wfDebug( "Pulling image metadata from shared repository cache\n" );
						$this->name = $commonsCachedValues['name'];
						$this->imagePath = $commonsCachedValues['imagePath'];
						$this->fileExists = $commonsCachedValues['fileExists'];
						$this->width = $commonsCachedValues['width'];
						$this->height = $commonsCachedValues['height'];
						$this->bits = $commonsCachedValues['bits'];
						$this->type = $commonsCachedValues['type'];
						$this->mime = $commonsCachedValues['mime'];
						$this->metadata = $commonsCachedValues['metadata'];
						$this->size = $commonsCachedValues['size'];
						$this->fromSharedDirectory = true;
						$this->dataLoaded = true;
						$this->imagePath = $this->getFullPath(true);
					}
				}
			} else {
				wfDebug( "Pulling image metadata from local cache\n" );
				$this->name = $cachedValues['name'];
				$this->imagePath = $cachedValues['imagePath'];
				$this->fileExists = $cachedValues['fileExists'];
				$this->width = $cachedValues['width'];
				$this->height = $cachedValues['height'];
				$this->bits = $cachedValues['bits'];
				$this->type = $cachedValues['type'];
				$this->mime = $cachedValues['mime'];
				$this->metadata = $cachedValues['metadata'];
				$this->size = $cachedValues['size'];
				$this->fromSharedDirectory = false;
				$this->dataLoaded = true;
				$this->imagePath = $this->getFullPath();
			}
		}
		if ( $this->dataLoaded ) {
			wfIncrStats( 'image_cache_hit' );
		} else {
			wfIncrStats( 'image_cache_miss' );
		}

		wfProfileOut( __METHOD__ );
		return $this->dataLoaded;
	}

	/**
	 * Save the image metadata to memcached
	 */
	function saveToCache() {
		global $wgMemc, $wgUseSharedUploads;
		$this->load();
		$keys = $this->getCacheKeys();
		// We can't cache negative metadata for non-existent files,
		// because if the file later appears in commons, the local
		// keys won't be purged.
		if ( $this->fileExists || !$wgUseSharedUploads ) {
			$cachedValues = array(
				'version'    => MW_IMAGE_VERSION,
				'name'       => $this->name,
				'imagePath'  => $this->imagePath,
				'fileExists' => $this->fileExists,
				'fromShared' => $this->fromSharedDirectory,
				'width'      => $this->width,
				'height'     => $this->height,
				'bits'       => $this->bits,
				'type'       => $this->type,
				'mime'       => $this->mime,
				'metadata'   => $this->metadata,
				'size'       => $this->size );

			$wgMemc->set( $keys[0], $cachedValues, 60 * 60 * 24 * 7 ); // A week
		} else {
			// However we should clear them, so they aren't leftover
			// if we've deleted the file.
			$wgMemc->delete( $keys[0] );
		}
	}

	/**
	 * Load metadata from the file itself
	 */
	function loadFromFile() {
		global $wgUseSharedUploads, $wgSharedUploadDirectory, $wgContLang;
		wfProfileIn( __METHOD__ );
		$this->imagePath = $this->getFullPath();
		$this->fileExists = file_exists( $this->imagePath );
		$this->fromSharedDirectory = false;
		$gis = array();

		if (!$this->fileExists) wfDebug(__METHOD__.': '.$this->imagePath." not found locally!\n");

		# If the file is not found, and a shared upload directory is used, look for it there.
		if (!$this->fileExists && $wgUseSharedUploads && $wgSharedUploadDirectory) {
			# In case we're on a wgCapitalLinks=false wiki, we
			# capitalize the first letter of the filename before
			# looking it up in the shared repository.
			$sharedImage = Image::newFromName( $wgContLang->ucfirst($this->name) );
			$this->fileExists = $sharedImage && file_exists( $sharedImage->getFullPath(true) );
			if ( $this->fileExists ) {
				$this->name = $sharedImage->name;
				$this->imagePath = $this->getFullPath(true);
				$this->fromSharedDirectory = true;
			}
		}


		if ( $this->fileExists ) {
			$magic=& MimeMagic::singleton();

			$this->mime = $magic->guessMimeType($this->imagePath,true);
			$this->type = $magic->getMediaType($this->imagePath,$this->mime);
			$handler = MediaHandler::getHandler( $this->mime );

			# Get size in bytes
			$this->size = filesize( $this->imagePath );

			# Height, width and metadata
			if ( $handler ) {
				$gis = $handler->getImageSize( $this, $this->imagePath );
				$this->metadata = $handler->getMetadata( $this, $this->imagePath );
			} else {
				$gis = false;
				$this->metadata = '';
			}

			wfDebug(__METHOD__.': '.$this->imagePath." loaded, ".$this->size." bytes, ".$this->mime.".\n");
		}
		else {
			$this->mime = NULL;
			$this->type = MEDIATYPE_UNKNOWN;
			$this->metadata = '';
			wfDebug(__METHOD__.': '.$this->imagePath." NOT FOUND!\n");
		}

		if( $gis ) {
			$this->width = $gis[0];
			$this->height = $gis[1];
		} else {
			$this->width = 0;
			$this->height = 0;
		}

		#NOTE: $gis[2] contains a code for the image type. This is no longer used.

		#NOTE: we have to set this flag early to avoid load() to be called
		# be some of the functions below. This may lead to recursion or other bad things!
		# as ther's only one thread of execution, this should be safe anyway.
		$this->dataLoaded = true;

		if ( isset( $gis['bits'] ) )  $this->bits = $gis['bits'];
		else $this->bits = 0;

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Load image metadata from the DB
	 */
	function loadFromDB() {
		global $wgUseSharedUploads, $wgSharedUploadDBname, $wgSharedUploadDBprefix, $wgContLang;
		wfProfileIn( __METHOD__ );

		$dbr = wfGetDB( DB_SLAVE );

		$row = $dbr->selectRow( 'image',
			array( 'img_size', 'img_width', 'img_height', 'img_bits',
			       'img_media_type', 'img_major_mime', 'img_minor_mime', 'img_metadata' ),
			array( 'img_name' => $this->name ), __METHOD__ );
		if ( $row ) {
			$this->fromSharedDirectory = false;
			$this->fileExists = true;
			$this->loadFromRow( $row );
			$this->imagePath = $this->getFullPath();
			// Check for rows from a previous schema, quietly upgrade them
			$this->maybeUpgradeRow();
		} elseif ( $wgUseSharedUploads && $wgSharedUploadDBname ) {
			# In case we're on a wgCapitalLinks=false wiki, we
			# capitalize the first letter of the filename before
			# looking it up in the shared repository.
			$name = $wgContLang->ucfirst($this->name);
			$dbc = Image::getCommonsDB();

			$row = $dbc->selectRow( "`$wgSharedUploadDBname`.{$wgSharedUploadDBprefix}image",
				array(
					'img_size', 'img_width', 'img_height', 'img_bits',
					'img_media_type', 'img_major_mime', 'img_minor_mime', 'img_metadata' ),
				array( 'img_name' => $name ), __METHOD__ );
			if ( $row ) {
				$this->fromSharedDirectory = true;
				$this->fileExists = true;
				$this->imagePath = $this->getFullPath(true);
				$this->name = $name;
				$this->loadFromRow( $row );

				// Check for rows from a previous schema, quietly upgrade them
				$this->maybeUpgradeRow();
			}
		}

		if ( !$row ) {
			$this->size = 0;
			$this->width = 0;
			$this->height = 0;
			$this->bits = 0;
			$this->type = 0;
			$this->fileExists = false;
			$this->fromSharedDirectory = false;
			$this->metadata = '';
			$this->mime = false;
		}

		# Unconditionally set loaded=true, we don't want the accessors constantly rechecking
		$this->dataLoaded = true;
		wfProfileOut( __METHOD__ );
	}

	/*
	 * Load image metadata from a DB result row
	 */
	function loadFromRow( &$row ) {
		$this->size = $row->img_size;
		$this->width = $row->img_width;
		$this->height = $row->img_height;
		$this->bits = $row->img_bits;
		$this->type = $row->img_media_type;

		$major= $row->img_major_mime;
		$minor= $row->img_minor_mime;

		if (!$major) $this->mime = "unknown/unknown";
		else {
			if (!$minor) $minor= "unknown";
			$this->mime = $major.'/'.$minor;
		}
		$this->metadata = $row->img_metadata;

		$this->dataLoaded = true;
	}

	/**
	 * Load image metadata from cache or DB, unless already loaded
	 */
	function load() {
		global $wgSharedUploadDBname, $wgUseSharedUploads;
		if ( !$this->dataLoaded ) {
			if ( !$this->loadFromCache() ) {
				$this->loadFromDB();
				if ( !$wgSharedUploadDBname && $wgUseSharedUploads ) {
					$this->loadFromFile();
				} elseif ( $this->fileExists || !$wgUseSharedUploads ) {
					// We can do negative caching for local images, because the cache
					// will be purged on upload. But we can't do it when shared images
					// are enabled, since updates to that won't purge foreign caches.
					$this->saveToCache();
				} 
			}
			$this->dataLoaded = true;
		}
	}

	/**
	 * Upgrade a row if it needs it
	 * @return void
	 */
	function maybeUpgradeRow() {
		if ( is_null($this->type) || $this->mime == 'image/svg' ) {
			$this->upgradeRow();
		} else {
			$handler = $this->getHandler();
			if ( $handler && !$handler->isMetadataValid( $this, $this->metadata ) ) {
				$this->upgradeRow();
			}
		}
	}

	/**
	 * Fix assorted version-related problems with the image row by reloading it from the file
	 */
	function upgradeRow() {
		global $wgDBname, $wgSharedUploadDBname;
		wfProfileIn( __METHOD__ );

		$this->loadFromFile();

		if ( $this->fromSharedDirectory ) {
			if ( !$wgSharedUploadDBname ) {
				wfProfileOut( __METHOD__ );
				return;
			}

			// Write to the other DB using selectDB, not database selectors
			// This avoids breaking replication in MySQL
			$dbw = Image::getCommonsDB();
		} else {
			$dbw = wfGetDB( DB_MASTER );
		}

		list( $major, $minor ) = self::splitMime( $this->mime );

		wfDebug(__METHOD__.': upgrading '.$this->name." to the current schema\n");

		$dbw->update( 'image',
			array(
				'img_width' => $this->width,
				'img_height' => $this->height,
				'img_bits' => $this->bits,
				'img_media_type' => $this->type,
				'img_major_mime' => $major,
				'img_minor_mime' => $minor,
				'img_metadata' => $this->metadata,
			), array( 'img_name' => $this->name ), __METHOD__
		);
		if ( $this->fromSharedDirectory ) {
			$dbw->selectDB( $wgDBname );
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Split an internet media type into its two components; if not
	 * a two-part name, set the minor type to 'unknown'.
	 *
	 * @param string $mime "text/html" etc
	 * @return array ("text", "html") etc
	 */
	static function splitMime( $mime ) {
		if( strpos( $mime, '/' ) !== false ) {
			return explode( '/', $mime, 2 );
		} else {
			return array( $mime, 'unknown' );
		}
	}

	/**
	 * Return the name of this image
	 * @public
	 */
	function getName() {
		return $this->name;
	}

	/**
	 * Return the associated title object
	 * @public
	 */
	function getTitle() {
		return $this->title;
	}

	/**
	 * Return the URL of the image file
	 * @public
	 */
	function getURL() {
		if ( !$this->url ) {
			$this->load();
			if($this->fileExists) {
				$this->url = Image::imageUrl( $this->name, $this->fromSharedDirectory );
			} else {
				$this->url = '';
			}
		}
		return $this->url;
	}

	function getViewURL() {
		if( $this->mustRender()) {
			if( $this->canRender() ) {
				return $this->createThumb( $this->getWidth() );
			}
			else {
				wfDebug('Image::getViewURL(): supposed to render '.$this->name.' ('.$this->mime."), but can't!\n");
				return $this->getURL(); #hm... return NULL?
			}
		} else {
			return $this->getURL();
		}
	}

	/**
	 * Return the image path of the image in the
	 * local file system as an absolute path
	 * @public
	 */
	function getImagePath() {
		$this->load();
		return $this->imagePath;
	}

	/**
	 * @return mixed Return the width of the image; returns false on error.
	 * @param int $page Page number to find the width of.
	 * @public
	 */
	function getWidth( $page = 1 ) {
		$this->load();
		if ( $this->isMultipage() ) {
			$dim = $this->getHandler()->getPageDimensions( $this, $page );
			if ( $dim ) {
				return $dim['width'];
			} else {
				return false;
			}
		} else {
			return $this->width;
		}
	}

	/**
	 * @return mixed Return the height of the image; Returns false on error.
	 * @param int $page Page number to find the height of.
	 * @public
	 */
	function getHeight( $page = 1 ) {
		$this->load();
		if ( $this->isMultipage() ) {
			$dim = $this->getHandler()->getPageDimensions( $this, $page );
			if ( $dim ) {
				return $dim['height'];
			} else {
				return false;
			}
		} else {
			return $this->height;
		}
	}

	/**
	 * Get handler-specific metadata
	 */
	function getMetadata() {
		$this->load();
		return $this->metadata;
	}

	/**
	 * @return int the size of the image file, in bytes
	 * @public
	 */
	function getSize() {
		$this->load();
		return $this->size;
	}

	/**
	 * @return string the mime type of the file.
	 */
	function getMimeType() {
		$this->load();
		return $this->mime;
	}

	/**
	 * Return the type of the media in the file.
	 * Use the value returned by this function with the MEDIATYPE_xxx constants.
	 */
	function getMediaType() {
		$this->load();
		return $this->type;
	}

	/**
	 * Checks if the file can be presented to the browser as a bitmap.
	 *
	 * Currently, this checks if the file is an image format
	 * that can be converted to a format
	 * supported by all browsers (namely GIF, PNG and JPEG),
	 * or if it is an SVG image and SVG conversion is enabled.
	 *
	 * @todo remember the result of this check.
	 * @return boolean
	 */
	function canRender() {
		$handler = $this->getHandler();
		return $handler && $handler->canRender();
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
		$handler = $this->getHandler();
		return $handler && $handler->mustRender();
	}

	/**
	 * Determines if this media file may be shown inline on a page.
	 *
	 * This is currently synonymous to canRender(), but this could be
	 * extended to also allow inline display of other media,
	 * like flash animations or videos. If you do so, please keep in mind that
	 * that could be a security risk.
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
	 * @return boolean
	 */
	function isSafeFile() {
		if ($this->allowInlineDisplay()) return true;
		if ($this->isTrustedFile()) return true;

		global $wgTrustedMediaFormats;

		$type= $this->getMediaType();
		$mime= $this->getMimeType();
		#wfDebug("Image::isSafeFile: type= $type, mime= $mime\n");

		if (!$type || $type===MEDIATYPE_UNKNOWN) return false; #unknown type, not trusted
		if ( in_array( $type, $wgTrustedMediaFormats) ) return true;

		if ($mime==="unknown/unknown") return false; #unknown type, not trusted
		if ( in_array( $mime, $wgTrustedMediaFormats) ) return true;

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
	 * @return boolean
	 */
	function isTrustedFile() {
		#this could be implemented to check a flag in the database,
		#look for signatures, etc
		return false;
	}

	/**
	 * Return the escapeLocalURL of this image
	 * @param string $query URL query string
	 * @public
	 */
	function getEscapeLocalURL( $query=false) {
		return $this->getTitle()->escapeLocalURL( $query );
	}

	/**
	 * Return the escapeFullURL of this image
	 * @public
	 */
	function getEscapeFullURL() {
		$this->getTitle();
		return $this->title->escapeFullURL();
	}

	/**
	 * Return the URL of an image, provided its name.
	 *
	 * @param string $name	Name of the image, without the leading "Image:"
	 * @param boolean $fromSharedDirectory	Should this be in $wgSharedUploadPath?
	 * @return string URL of $name image
	 * @public
	 */
	static function imageUrl( $name, $fromSharedDirectory = false ) {
		global $wgUploadPath,$wgUploadBaseUrl,$wgSharedUploadPath;
		if($fromSharedDirectory) {
			$base = '';
			$path = $wgSharedUploadPath;
		} else {
			$base = $wgUploadBaseUrl;
			$path = $wgUploadPath;
		}
		$url = "{$base}{$path}" .  wfGetHashPath($name, $fromSharedDirectory) . "{$name}";
		return wfUrlencode( $url );
	}

	/**
	 * Returns true if the image file exists on disk.
	 * @return boolean Whether image file exist on disk.
	 * @public
	 */
	function exists() {
		$this->load();
		return $this->fileExists;
	}

	/**
	 * @todo document
	 * @param string $thumbName
	 * @param string $subdir
	 * @return string
	 * @private
	 */
	function thumbUrlFromName( $thumbName, $subdir = 'thumb' ) {
		global $wgUploadPath, $wgUploadBaseUrl, $wgSharedUploadPath;
		if($this->fromSharedDirectory) {
			$base = '';
			$path = $wgSharedUploadPath;
		} else {
			$base = $wgUploadBaseUrl;
			$path = $wgUploadPath;
		}
		if ( Image::isHashed( $this->fromSharedDirectory ) ) {
			$hashdir = wfGetHashPath($this->name, $this->fromSharedDirectory) .
			wfUrlencode( $this->name );
		} else {
			$hashdir = '';
		}
		$url = "{$base}{$path}/{$subdir}{$hashdir}/" . wfUrlencode( $thumbName );
		return $url;
	}

	/**
	 * @deprecated Use $image->transform()->getUrl() or thumbUrlFromName()
	 */
	function thumbUrl( $width, $subdir = 'thumb' ) {
		$name = $this->thumbName( array( 'width' => $width ) );
		if ( strval( $name ) !== '' ) {
			return array( false, $this->thumbUrlFromName( $name, $subdir ) );
		} else {
			return array( false, false );
		}
	}

	/**
	 * @return mixed
	 */
	function getTransformScript() {
		global $wgSharedThumbnailScriptPath, $wgThumbnailScriptPath;
		if ( $this->fromSharedDirectory ) {
			$script = $wgSharedThumbnailScriptPath;
		} else {
			$script = $wgThumbnailScriptPath;
		}
		if ( $script ) {
			return "$script?f=" . urlencode( $this->name );
		} else {
			return false;
		}
	}

	/**
	 * Get a ThumbnailImage which is the same size as the source
	 * @param mixed $page
	 * @return MediaTransformOutput
	 */
	function getUnscaledThumb( $page = false ) {
		if ( $page ) {
			$params = array(
				'page' => $page,
				'width' => $this->getWidth( $page )
			);
		} else {
			$params = array( 'width' => $this->getWidth() );
		}
		return $this->transform( $params );
	}

	/**
	 * Return the file name of a thumbnail with the specified parameters
	 *
	 * @param array $params Handler-specific parameters
	 * @return string file name of a thumbnail with the specified parameters
	 * @private
	 */
	function thumbName( $params ) {
		$handler = $this->getHandler();
		if ( !$handler ) {
			return null;
		}
		list( $thumbExt, /* $thumbMime */ ) = self::getThumbType( $this->extension, $this->mime );
		$thumbName = $handler->makeParamString( $params ) . '-' . $this->name;
		if ( $thumbExt != $this->extension ) {
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
	 * @param integer $width	maximum width of the generated thumbnail
	 * @param integer $height	maximum height of the image (optional)
	 * @public
	 */
	function createThumb( $width, $height = -1 ) {
		$params = array( 'width' => $width );
		if ( $height != -1 ) {
			$params['height'] = $height;
		}
		$thumb = $this->transform( $params );
		if( is_null( $thumb ) || $thumb->isError() ) return '';
		return $thumb->getUrl();
	}

	/**
	 * As createThumb, but returns a ThumbnailImage object. This can
	 * provide access to the actual file, the real size of the thumb,
	 * and can produce a convenient <img> tag for you.
	 *
	 * For non-image formats, this may return a filetype-specific icon.
	 *
	 * @param integer $width	maximum width of the generated thumbnail
	 * @param integer $height	maximum height of the image (optional)
	 * @param boolean $render	True to render the thumbnail if it doesn't exist,
	 *                       	false to just return the URL
	 *
	 * @return ThumbnailImage or null on failure
	 * @public
	 *
	 * @deprecated use transform()
	 */
	function getThumbnail( $width, $height=-1, $render = true ) {
		$params = array( 'width' => $width );
		if ( $height != -1 ) {
			$params['height'] = $height;
		}
		$flags = $render ? self::RENDER_NOW : 0;
		return $this->transform( $params, $flags );
	}
	
	/**
	 * Transform a media file
	 *
	 * @param array[string]mixed $params An associative array of handler-specific parameters.
	 *                  Typical keys are width, height and page.
	 * @param integer $flags A bitfield, may contain self::RENDER_NOW to force rendering
	 * @return MediaTransformOutput
	 */
	function transform( $params, $flags = 0 ) {
		global $wgGenerateThumbnailOnParse, $wgUseSquid, $wgIgnoreImageErrors;

		wfProfileIn( __METHOD__ );
		do {
			$handler = $this->getHandler();
			if ( !$handler || !$handler->canRender() ) {
				// not a bitmap or renderable image, don't try.
				$thumb = $this->iconThumb();
				break;
			}

			$script = $this->getTransformScript();
			if ( $script && !($flags & self::RENDER_NOW) ) {
				// Use a script to transform on client request
				$thumb = $handler->getScriptedTransform( $this, $script, $params );
				break;
			}

			$normalisedParams = $params;
			$handler->normaliseParams( $this, $normalisedParams );
			$thumbName = $this->thumbName( $normalisedParams );	
			$thumbPath = wfImageThumbDir( $this->name, $this->fromSharedDirectory ) .  "/$thumbName";
			$thumbUrl = $this->thumbUrlFromName( $thumbName );


			if ( !$wgGenerateThumbnailOnParse && !($flags & self::RENDER_NOW ) ) {
				$thumb = $handler->getTransform( $this, $thumbPath, $thumbUrl, $params );
				break;
			}
			
			wfDebug( "Doing stat for $thumbPath\n" );
			$this->migrateThumbFile( $thumbName );
			if ( file_exists( $thumbPath ) ) {
				$thumb = $handler->getTransform( $this, $thumbPath, $thumbUrl, $params );
				break;
			}

			$thumb = $handler->doTransform( $this, $thumbPath, $thumbUrl, $params );

			// Ignore errors if requested
			if ( !$thumb ) {
				$thumb = null;
			} elseif ( $thumb->isError() ) {
				$this->lastError = $thumb->toText();
				if ( $wgIgnoreImageErrors && !($flags & self::RENDER_NOW) ) {
					$thumb = $handler->getTransform( $this, $thumbPath, $thumbUrl, $params );
				}
			}
			
			if ( $wgUseSquid ) {
				wfPurgeSquidServers( array( $thumbUrl ) );
			}
		} while (false);

		wfProfileOut( __METHOD__ );
		return $thumb;
	}

	/**
	 * Fix thumbnail files from 1.4 or before, with extreme prejudice
	 * @param string $thumbName File name of thumbnail.
	 * @return void
	 */
	function migrateThumbFile( $thumbName ) {
		$thumbDir = wfImageThumbDir( $this->name, $this->fromSharedDirectory );
		$thumbPath = "$thumbDir/$thumbName";
		if ( is_dir( $thumbPath ) ) {
			// Directory where file should be
			// This happened occasionally due to broken migration code in 1.5
			// Rename to broken-*
			global $wgUploadDirectory;
			for ( $i = 0; $i < 100 ; $i++ ) {
				$broken = "$wgUploadDirectory/broken-$i-$thumbName";
				if ( !file_exists( $broken ) ) {
					rename( $thumbPath, $broken );
					break;
				}
			}
			// Doesn't exist anymore
			clearstatcache();
		}
		if ( is_file( $thumbDir ) ) {
			// File where directory should be
			unlink( $thumbDir );
			// Doesn't exist anymore
			clearstatcache();
		}
	}

	/**
	 * Get a MediaHandler instance for this image
	 */
	function getHandler() {
		return MediaHandler::getHandler( $this->getMimeType() );
	}

	/**
	 * Get a ThumbnailImage representing a file type icon
	 * @return ThumbnailImage
	 */
	function iconThumb() {
		global $wgStylePath, $wgStyleDirectory;

		$icons = array( 'fileicon-' . $this->extension . '.png', 'fileicon.png' );
		foreach( $icons as $icon ) {
			$path = '/common/images/icons/' . $icon;
			$filepath = $wgStyleDirectory . $path;
			if( file_exists( $filepath ) ) {
				return new ThumbnailImage( $wgStylePath . $path, 120, 120 );
			}
		}
		return null;
	}

	/**
	 * Get last thumbnailing error.
	 * Largely obsolete.
	 * @return mixed
	 */
	function getLastError() {
		return $this->lastError;
	}

	/**
	 * Get all thumbnail names previously generated for this image
	 * @param boolean $shared
	 * @return array[]string
	 */
	function getThumbnails( $shared = false ) {
		if ( Image::isHashed( $shared ) ) {
			$this->load();
			$files = array();
			$dir = wfImageThumbDir( $this->name, $shared );

			if ( is_dir( $dir ) ) {
				$handle = opendir( $dir );

				if ( $handle ) {
					while ( false !== ( $file = readdir($handle) ) ) {
						if ( $file[0] != '.' ) {
							$files[] = $file;
						}
					}
					closedir( $handle );
				}
			}
		} else {
			$files = array();
		}

		return $files;
	}

	/**
	 * Refresh metadata in memcached, but don't touch thumbnails or squid
	 * @return void
	 */
	function purgeMetadataCache() {
		clearstatcache();
		$this->loadFromFile();
		$this->saveToCache();
	}

	/**
	 * Delete all previously generated thumbnails, refresh metadata in memcached and purge the squid
	 * @param array $archiveFiles
	 * @param boolean $shared
	 * @return void
	 */
	function purgeCache( $archiveFiles = array(), $shared = false ) {
		global $wgUseSquid;

		// Refresh metadata cache
		$this->purgeMetadataCache();

		// Delete thumbnails
		$files = $this->getThumbnails( $shared );
		$dir = wfImageThumbDir( $this->name, $shared );
		$urls = array();
		foreach ( $files as $file ) {
			# Check that the base image name is part of the thumb name
			# This is a basic sanity check to avoid erasing unrelated directories
			if ( strpos( $file, $this->name ) !== false ) {
				$url = $this->thumbUrlFromName( $file );
				$urls[] = $url;
				@unlink( "$dir/$file" );
			}
		}

		// Purge the squid
		if ( $wgUseSquid ) {
			$urls[] = $this->getURL();
			foreach ( $archiveFiles as $file ) {
				$urls[] = wfImageArchiveUrl( $file );
			}
			wfPurgeSquidServers( $urls );
		}
	}

	/**
	 * Purge the image description page, but don't go after
	 * pages using the image. Use when modifying file history
	 * but not the current data.
	 * @return void
	 */
	function purgeDescription() {
		$page = Title::makeTitle( NS_IMAGE, $this->name );
		$page->invalidateCache();
		$page->purgeSquid();
	}

	/**
	 * Purge metadata and all affected pages when the image is created,
	 * deleted, or majorly updated.
	 * @param array $urlArray A set of additional URLs may be passed to purge, 
	 *        such as specific image files which have changed (param not used?)
	 * @return void
	 */
	function purgeEverything( $urlArr=array() ) {
		// Delete thumbnails and refresh image metadata cache
		$this->purgeCache();
		$this->purgeDescription();

		// Purge cache of all pages using this image
		$update = new HTMLCacheUpdate( $this->getTitle(), 'imagelinks' );
		$update->doUpdate();
	}

	/**
	 * Return the image history of this image, line by line.
	 * starts with current version, then old versions.
	 * uses $this->historyLine to check which line to return:
	 *  0      return line for current version
	 *  1      query for old versions, return first one
	 *  2, ... return next old version from above query
	 *
	 * @public
	 * @return mixed false on no next history, object otherwise.
	 */
	function nextHistoryLine() {
		$dbr = wfGetDB( DB_SLAVE );

		if ( $this->historyLine == 0 ) {// called for the first time, return line from cur
			$this->historyRes = $dbr->select( 'image',
				array(
					'img_size',
					'img_description',
					'img_user','img_user_text',
					'img_timestamp',
					'img_width',
					'img_height',
					"'' AS oi_archive_name"
				),
				array( 'img_name' => $this->title->getDBkey() ),
				__METHOD__
			);
			if ( 0 == $dbr->numRows( $this->historyRes ) ) {
				return FALSE;
			}
		} else if ( $this->historyLine == 1 ) {
			$this->historyRes = $dbr->select( 'oldimage',
				array(
					'oi_size AS img_size',
					'oi_description AS img_description',
					'oi_user AS img_user',
					'oi_user_text AS img_user_text',
					'oi_timestamp AS img_timestamp',
					'oi_width as img_width',
					'oi_height as img_height',
					'oi_archive_name'
				),
				array( 'oi_name' => $this->title->getDBkey() ),
				__METHOD__,
				array( 'ORDER BY' => 'oi_timestamp DESC' )
			);
		}
		$this->historyLine ++;

		return $dbr->fetchObject( $this->historyRes );
	}

	/**
	 * Reset the history pointer to the first element of the history
	 * @public
	 * @return void
	 */
	function resetHistory() {
		$this->historyLine = 0;
	}

	/**
	* Return the full filesystem path to the file. Note that this does
	* not mean that a file actually exists under that location.
	*
	* This path depends on whether directory hashing is active or not,
	* i.e. whether the images are all found in the same directory,
	* or in hashed paths like /images/3/3c.
	*
	* @public
	* @param boolean $fromSharedDirectory Return the path to the file
	*   in a shared repository (see $wgUseSharedRepository and related
	*   options in DefaultSettings.php) instead of a local one.
	* @return string Full filesystem path to the file.
	*/
	function getFullPath( $fromSharedRepository = false ) {
		global $wgUploadDirectory, $wgSharedUploadDirectory;

		$dir      = $fromSharedRepository ? $wgSharedUploadDirectory :
		                                    $wgUploadDirectory;

		// $wgSharedUploadDirectory may be false, if thumb.php is used
		if ( $dir ) {
			$fullpath = $dir . wfGetHashPath($this->name, $fromSharedRepository) . $this->name;
		} else {
			$fullpath = false;
		}

		return $fullpath;
	}

	/**
	 * @param boolean $shared
	 * @return bool
	 */
	public static function isHashed( $shared ) {
		global $wgHashedUploadDirectory, $wgHashedSharedUploadDirectory;
		return $shared ? $wgHashedSharedUploadDirectory : $wgHashedUploadDirectory;
	}

	/**
	 * Record an image upload in the upload log and the image table
	 * @param string $oldver
	 * @param string $desc
	 * @param string $license
	 * @param string $copyStatus
	 * @param string $source
	 * @param boolean $watch
	 * @return boolean
	 */
	function recordUpload( $oldver, $desc, $license = '', $copyStatus = '', $source = '', $watch = false ) {
		global $wgUser, $wgUseCopyrightUpload;

		$dbw = wfGetDB( DB_MASTER );

		// Delete thumbnails and refresh the metadata cache
		$this->purgeCache();

		// Fail now if the image isn't there
		if ( !$this->fileExists || $this->fromSharedDirectory ) {
			wfDebug( "Image::recordUpload: File ".$this->imagePath." went missing!\n" );
			return false;
		}

		if ( $wgUseCopyrightUpload ) {
			if ( $license != '' ) {
				$licensetxt = '== ' . wfMsgForContent( 'license' ) . " ==\n" . '{{' . $license . '}}' . "\n";
			}
			$textdesc = '== ' . wfMsg ( 'filedesc' ) . " ==\n" . $desc . "\n" .
			  '== ' . wfMsgForContent ( 'filestatus' ) . " ==\n" . $copyStatus . "\n" .
			  "$licensetxt" .
			  '== ' . wfMsgForContent ( 'filesource' ) . " ==\n" . $source ;
		} else {
			if ( $license != '' ) {
				$filedesc = $desc == '' ? '' : '== ' . wfMsg ( 'filedesc' ) . " ==\n" . $desc . "\n";
				 $textdesc = $filedesc .
					 '== ' . wfMsgForContent ( 'license' ) . " ==\n" . '{{' . $license . '}}' . "\n";
			} else {
				$textdesc = $desc;
			}
		}

		$now = $dbw->timestamp();

		#split mime type
		if (strpos($this->mime,'/')!==false) {
			list($major,$minor)= explode('/',$this->mime,2);
		}
		else {
			$major= $this->mime;
			$minor= "unknown";
		}

		# Test to see if the row exists using INSERT IGNORE
		# This avoids race conditions by locking the row until the commit, and also
		# doesn't deadlock. SELECT FOR UPDATE causes a deadlock for every race condition.
		$dbw->insert( 'image',
			array(
				'img_name' => $this->name,
				'img_size'=> $this->size,
				'img_width' => intval( $this->width ),
				'img_height' => intval( $this->height ),
				'img_bits' => $this->bits,
				'img_media_type' => $this->type,
				'img_major_mime' => $major,
				'img_minor_mime' => $minor,
				'img_timestamp' => $now,
				'img_description' => $desc,
				'img_user' => $wgUser->getID(),
				'img_user_text' => $wgUser->getName(),
				'img_metadata' => $this->metadata,
			),
			__METHOD__,
			'IGNORE'
		);

		if( $dbw->affectedRows() == 0 ) {
			# Collision, this is an update of an image
			# Insert previous contents into oldimage
			$dbw->insertSelect( 'oldimage', 'image',
				array(
					'oi_name' => 'img_name',
					'oi_archive_name' => $dbw->addQuotes( $oldver ),
					'oi_size' => 'img_size',
					'oi_width' => 'img_width',
					'oi_height' => 'img_height',
					'oi_bits' => 'img_bits',
					'oi_timestamp' => 'img_timestamp',
					'oi_description' => 'img_description',
					'oi_user' => 'img_user',
					'oi_user_text' => 'img_user_text',
				), array( 'img_name' => $this->name ), __METHOD__
			);

			# Update the current image row
			$dbw->update( 'image',
				array( /* SET */
					'img_size' => $this->size,
					'img_width' => intval( $this->width ),
					'img_height' => intval( $this->height ),
					'img_bits' => $this->bits,
					'img_media_type' => $this->type,
					'img_major_mime' => $major,
					'img_minor_mime' => $minor,
					'img_timestamp' => $now,
					'img_description' => $desc,
					'img_user' => $wgUser->getID(),
					'img_user_text' => $wgUser->getName(),
					'img_metadata' => $this->metadata,
				), array( /* WHERE */
					'img_name' => $this->name
				), __METHOD__
			);
		} else {
			# This is a new image
			# Update the image count
			$site_stats = $dbw->tableName( 'site_stats' );
			$dbw->query( "UPDATE $site_stats SET ss_images=ss_images+1", __METHOD__ );
		}

		$descTitle = $this->getTitle();
		$article = new Article( $descTitle );
		$minor = false;
		$watch = $watch || $wgUser->isWatched( $descTitle );
		$suppressRC = true; // There's already a log entry, so don't double the RC load

		if( $descTitle->exists() ) {
			// TODO: insert a null revision into the page history for this update.
			if( $watch ) {
				$wgUser->addWatch( $descTitle );
			}

			# Invalidate the cache for the description page
			$descTitle->invalidateCache();
			$descTitle->purgeSquid();
		} else {
			// New image; create the description page.
			$article->insertNewArticle( $textdesc, $desc, $minor, $watch, $suppressRC );
		}

		# Hooks, hooks, the magic of hooks...
		wfRunHooks( 'FileUpload', array( $this ) );

		# Add the log entry
		$log = new LogPage( 'upload' );
		$log->addEntry( 'upload', $descTitle, $desc );

		# Commit the transaction now, in case something goes wrong later
		# The most important thing is that images don't get lost, especially archives
		$dbw->immediateCommit();

		# Invalidate cache for all pages using this image
		$update = new HTMLCacheUpdate( $this->getTitle(), 'imagelinks' );
		$update->doUpdate();

		return true;
	}

	/**
	 * Get an array of Title objects which are articles which use this image
	 * Also adds their IDs to the link cache
	 *
	 * This is mostly copied from Title::getLinksTo()
	 *
	 * @deprecated Use HTMLCacheUpdate, this function uses too much memory
	 * @param string $options
	 * @return array[int]Title
	 */
	function getLinksTo( $options = '' ) {
		wfProfileIn( __METHOD__ );

		if ( $options ) {
			$db = wfGetDB( DB_MASTER );
		} else {
			$db = wfGetDB( DB_SLAVE );
		}
		$linkCache =& LinkCache::singleton();

		list( $page, $imagelinks ) = $db->tableNamesN( 'page', 'imagelinks' );
		$encName = $db->addQuotes( $this->name );
		$sql = "SELECT page_namespace,page_title,page_id FROM $page,$imagelinks WHERE page_id=il_from AND il_to=$encName $options";
		$res = $db->query( $sql, __METHOD__ );

		$retVal = array();
		if ( $db->numRows( $res ) ) {
			while ( $row = $db->fetchObject( $res ) ) {
				if ( $titleObj = Title::makeTitle( $row->page_namespace, $row->page_title ) ) {
					$linkCache->addGoodLinkObj( $row->page_id, $titleObj );
					$retVal[] = $titleObj;
				}
			}
		}
		$db->freeResult( $res );
		wfProfileOut( __METHOD__ );
		return $retVal;
	}

	/**
	 * @return array
	 */
	function getExifData() {
		$handler = $this->getHandler();
		if ( !$handler || $handler->getMetadataType( $this ) != 'exif' ) {
			return array();
		}
		if ( !$this->metadata ) {
			return array();
		}
		$exif = unserialize( $this->metadata );
		if ( !$exif ) {
			return array();
		}
		unset( $exif['MEDIAWIKI_EXIF_VERSION'] );
		$format = new FormatExif( $exif );

		return $format->getFormattedData();
	}

	/**
	 * Returns true if the image does not come from the shared
	 * image repository.
	 *
	 * @return bool
	 */
	function isLocal() {
		return !$this->fromSharedDirectory;
	}

	/**
	 * Was this image ever deleted from the wiki?
	 *
	 * @return bool
	 */
	function wasDeleted() {
		$title = Title::makeTitle( NS_IMAGE, $this->name );
		return ( $title->isDeleted() > 0 );
	}

	/**
	 * Delete all versions of the image.
	 *
	 * Moves the files into an archive directory (or deletes them)
	 * and removes the database rows.
	 *
	 * Cache purging is done; logging is caller's responsibility.
	 *
	 * @param string $reason
	 * @param boolean $suppress
	 * @return boolean true on success, false on some kind of failure
	 */
	function delete( $reason, $suppress=false ) {
		$transaction = new FSTransaction();
		$urlArr = array( $this->getURL() );

		if( !FileStore::lock() ) {
			wfDebug( __METHOD__.": failed to acquire file store lock, aborting\n" );
			return false;
		}

		try {
			$dbw = wfGetDB( DB_MASTER );
			$dbw->begin();

			// Delete old versions
			$result = $dbw->select( 'oldimage',
				array( 'oi_archive_name' ),
				array( 'oi_name' => $this->name ) );

			while( $row = $dbw->fetchObject( $result ) ) {
				$oldName = $row->oi_archive_name;

				$transaction->add( $this->prepareDeleteOld( $oldName, $reason, $suppress ) );

				// We'll need to purge this URL from caches...
				$urlArr[] = wfImageArchiveUrl( $oldName );
			}
			$dbw->freeResult( $result );

			// And the current version...
			$transaction->add( $this->prepareDeleteCurrent( $reason, $suppress ) );

			$dbw->immediateCommit();
		} catch( MWException $e ) {
			wfDebug( __METHOD__.": db error, rolling back file transactions\n" );
			$transaction->rollback();
			FileStore::unlock();
			throw $e;
		}

		wfDebug( __METHOD__.": deleted db items, applying file transactions\n" );
		$transaction->commit();
		FileStore::unlock();


		// Update site_stats
		$site_stats = $dbw->tableName( 'site_stats' );
		$dbw->query( "UPDATE $site_stats SET ss_images=ss_images-1", __METHOD__ );

		$this->purgeEverything( $urlArr );

		return true;
	}


	/**
	 * Delete an old version of the image.
	 *
	 * Moves the file into an archive directory (or deletes it)
	 * and removes the database row.
	 *
	 * Cache purging is done; logging is caller's responsibility.
	 *
	 * @param string $archiveName
	 * @param string $reason
	 * @param boolean $suppress
	 * @throws MWException or FSException on database or filestore failure
	 * @return boolean true on success, false on some kind of failure
	 */
	function deleteOld( $archiveName, $reason, $suppress=false ) {
		$transaction = new FSTransaction();
		$urlArr = array();

		if( !FileStore::lock() ) {
			wfDebug( __METHOD__.": failed to acquire file store lock, aborting\n" );
			return false;
		}

		$transaction = new FSTransaction();
		try {
			$dbw = wfGetDB( DB_MASTER );
			$dbw->begin();
			$transaction->add( $this->prepareDeleteOld( $archiveName, $reason, $suppress ) );
			$dbw->immediateCommit();
		} catch( MWException $e ) {
			wfDebug( __METHOD__.": db error, rolling back file transaction\n" );
			$transaction->rollback();
			FileStore::unlock();
			throw $e;
		}

		wfDebug( __METHOD__.": deleted db items, applying file transaction\n" );
		$transaction->commit();
		FileStore::unlock();

		$this->purgeDescription();

		// Squid purging
		global $wgUseSquid;
		if ( $wgUseSquid ) {
			$urlArr = array(
				wfImageArchiveUrl( $archiveName ),
			);
			wfPurgeSquidServers( $urlArr );
		}
		return true;
	}

	/**
	 * Delete the current version of a file.
	 * May throw a database error.
	 * @param string $reason
	 * @param boolean $suppress
	 * @return boolean true on success, false on failure
	 */
	private function prepareDeleteCurrent( $reason, $suppress=false ) {
		return $this->prepareDeleteVersion(
			$this->getFullPath(),
			$reason,
			'image',
			array(
				'fa_name'         => 'img_name',
				'fa_archive_name' => 'NULL',
				'fa_size'         => 'img_size',
				'fa_width'        => 'img_width',
				'fa_height'       => 'img_height',
				'fa_metadata'     => 'img_metadata',
				'fa_bits'         => 'img_bits',
				'fa_media_type'   => 'img_media_type',
				'fa_major_mime'   => 'img_major_mime',
				'fa_minor_mime'   => 'img_minor_mime',
				'fa_description'  => 'img_description',
				'fa_user'         => 'img_user',
				'fa_user_text'    => 'img_user_text',
				'fa_timestamp'    => 'img_timestamp' ),
			array( 'img_name' => $this->name ),
			$suppress,
			__METHOD__ );
	}

	/**
	 * Delete a given older version of a file.
	 * May throw a database error.
	 * @param string $archiveName
	 * @param string $reason
	 * @param boolean $suppress
	 * @return boolean true on success, false on failure
	 */
	private function prepareDeleteOld( $archiveName, $reason, $suppress=false ) {
		$oldpath = wfImageArchiveDir( $this->name ) .
			DIRECTORY_SEPARATOR . $archiveName;
		return $this->prepareDeleteVersion(
			$oldpath,
			$reason,
			'oldimage',
			array(
				'fa_name'         => 'oi_name',
				'fa_archive_name' => 'oi_archive_name',
				'fa_size'         => 'oi_size',
				'fa_width'        => 'oi_width',
				'fa_height'       => 'oi_height',
				'fa_metadata'     => 'NULL',
				'fa_bits'         => 'oi_bits',
				'fa_media_type'   => 'NULL',
				'fa_major_mime'   => 'NULL',
				'fa_minor_mime'   => 'NULL',
				'fa_description'  => 'oi_description',
				'fa_user'         => 'oi_user',
				'fa_user_text'    => 'oi_user_text',
				'fa_timestamp'    => 'oi_timestamp' ),
			array(
				'oi_name' => $this->name,
				'oi_archive_name' => $archiveName ),
			$suppress,
			__METHOD__ );
	}

	/**
	 * Do the dirty work of backing up an image row and its file
	 * (if $wgSaveDeletedFiles is on) and removing the originals.
	 *
	 * Must be run while the file store is locked and a database
	 * transaction is open to avoid race conditions.
	 *
	 * @return FSTransaction
	 */
	private function prepareDeleteVersion( $path, $reason, $table, $fieldMap, $where, $suppress=false, $fname ) {
		global $wgUser, $wgSaveDeletedFiles;

		// Dupe the file into the file store
		if( file_exists( $path ) ) {
			if( $wgSaveDeletedFiles ) {
				$group = 'deleted';

				$store = FileStore::get( $group );
				$key = FileStore::calculateKey( $path, $this->extension );
				$transaction = $store->insert( $key, $path,
					FileStore::DELETE_ORIGINAL );
			} else {
				$group = null;
				$key = null;
				$transaction = FileStore::deleteFile( $path );
			}
		} else {
			wfDebug( __METHOD__." deleting already-missing '$path'; moving on to database\n" );
			$group = null;
			$key = null;
			$transaction = new FSTransaction(); // empty
		}

		if( $transaction === false ) {
			// Fail to restore?
			wfDebug( __METHOD__.": import to file store failed, aborting\n" );
			throw new MWException( "Could not archive and delete file $path" );
			return false;
		}
		
		// Bitfields to further supress the image content
		// Note that currently, live images are stored elsewhere
		// and cannot be partially deleted
		$bitfield = 0;
		if ( $suppress ) {
			$bitfield |= self::DELETED_FILE;
			$bitfield |= self::DELETED_COMMENT;
			$bitfield |= self::DELETED_USER;
			$bitfield |= self::DELETED_RESTRICTED;
		}

		$dbw = wfGetDB( DB_MASTER );
		$storageMap = array(
			'fa_storage_group' => $dbw->addQuotes( $group ),
			'fa_storage_key'   => $dbw->addQuotes( $key ),

			'fa_deleted_user'      => $dbw->addQuotes( $wgUser->getId() ),
			'fa_deleted_timestamp' => $dbw->timestamp(),
			'fa_deleted_reason'    => $dbw->addQuotes( $reason ),
			'fa_deleted'		   => $bitfield);
		$allFields = array_merge( $storageMap, $fieldMap );

		try {
			if( $wgSaveDeletedFiles ) {
				$dbw->insertSelect( 'filearchive', $table, $allFields, $where, $fname );
			}
			$dbw->delete( $table, $where, $fname );
		} catch( DBQueryError $e ) {
			// Something went horribly wrong!
			// Leave the file as it was...
			wfDebug( __METHOD__.": database error, rolling back file transaction\n" );
			$transaction->rollback();
			throw $e;
		}

		return $transaction;
	}

	/**
	 * Restore all or specified deleted revisions to the given file.
	 * Permissions and logging are left to the caller.
	 *
	 * May throw database exceptions on error.
	 *
	 * @param $versions set of record ids of deleted items to restore,
	 *                    or empty to restore all revisions.
	 * @return the number of file revisions restored if successful,
	 *         or false on failure
	 */
	function restore( $versions=array(), $Unsuppress=false ) {
		global $wgUser;
	
		if( !FileStore::lock() ) {
			wfDebug( __METHOD__." could not acquire filestore lock\n" );
			return false;
		}

		$transaction = new FSTransaction();
		try {
			$dbw = wfGetDB( DB_MASTER );
			$dbw->begin();

			// Re-confirm whether this image presently exists;
			// if no we'll need to create an image record for the
			// first item we restore.
			$exists = $dbw->selectField( 'image', '1',
				array( 'img_name' => $this->name ),
				__METHOD__ );

			// Fetch all or selected archived revisions for the file,
			// sorted from the most recent to the oldest.
			$conditions = array( 'fa_name' => $this->name );
			if( $versions ) {
				$conditions['fa_id'] = $versions;
			}

			$result = $dbw->select( 'filearchive', '*',
				$conditions,
				__METHOD__,
				array( 'ORDER BY' => 'fa_timestamp DESC' ) );

			if( $dbw->numRows( $result ) < count( $versions ) ) {
				// There's some kind of conflict or confusion;
				// we can't restore everything we were asked to.
				wfDebug( __METHOD__.": couldn't find requested items\n" );
				$dbw->rollback();
				FileStore::unlock();
				return false;
			}

			if( $dbw->numRows( $result ) == 0 ) {
				// Nothing to do.
				wfDebug( __METHOD__.": nothing to do\n" );
				$dbw->rollback();
				FileStore::unlock();
				return true;
			}

			$revisions = 0;
			while( $row = $dbw->fetchObject( $result ) ) {
				if ( $Unsuppress ) {
				// Currently, fa_deleted flags fall off upon restore, lets be careful about this
				} else if ( ($row->fa_deleted & Revision::DELETED_RESTRICTED) && !$wgUser->isAllowed('hiderevision') ) {
				// Skip restoring file revisions that the user cannot restore
					continue;
				}
				$revisions++;
				$store = FileStore::get( $row->fa_storage_group );
				if( !$store ) {
					wfDebug( __METHOD__.": skipping row with no file.\n" );
					continue;
				}

				if( $revisions == 1 && !$exists ) {
					$destDir = wfImageDir( $row->fa_name );
					if ( !is_dir( $destDir ) ) {
						wfMkdirParents( $destDir );
					}
					$destPath = $destDir . DIRECTORY_SEPARATOR . $row->fa_name;

					// We may have to fill in data if this was originally
					// an archived file revision.
					if( is_null( $row->fa_metadata ) ) {
						$tempFile = $store->filePath( $row->fa_storage_key );

						$magic = MimeMagic::singleton();
						$mime = $magic->guessMimeType( $tempFile, true );
						$media_type = $magic->getMediaType( $tempFile, $mime );
						list( $major_mime, $minor_mime ) = self::splitMime( $mime );
						$handler = MediaHandler::getHandler( $mime );
						if ( $handler ) {
							$metadata = $handler->getMetadata( $image, $tempFile );
						} else {
							$metadata = '';
						}
					} else {
						$metadata   = $row->fa_metadata;
						$major_mime = $row->fa_major_mime;
						$minor_mime = $row->fa_minor_mime;
						$media_type = $row->fa_media_type;
					}

					$table = 'image';
					$fields = array(
						'img_name'        => $row->fa_name,
						'img_size'        => $row->fa_size,
						'img_width'       => $row->fa_width,
						'img_height'      => $row->fa_height,
						'img_metadata'    => $metadata,
						'img_bits'        => $row->fa_bits,
						'img_media_type'  => $media_type,
						'img_major_mime'  => $major_mime,
						'img_minor_mime'  => $minor_mime,
						'img_description' => $row->fa_description,
						'img_user'        => $row->fa_user,
						'img_user_text'   => $row->fa_user_text,
						'img_timestamp'   => $row->fa_timestamp );
				} else {
					$archiveName = $row->fa_archive_name;
					if( $archiveName == '' ) {
						// This was originally a current version; we
						// have to devise a new archive name for it.
						// Format is <timestamp of archiving>!<name>
						$archiveName =
							wfTimestamp( TS_MW, $row->fa_deleted_timestamp ) .
							'!' . $row->fa_name;
					}
					$destDir = wfImageArchiveDir( $row->fa_name );
					if ( !is_dir( $destDir ) ) {
						wfMkdirParents( $destDir );
					}
					$destPath = $destDir . DIRECTORY_SEPARATOR . $archiveName;

					$table = 'oldimage';
					$fields = array(
						'oi_name'         => $row->fa_name,
						'oi_archive_name' => $archiveName,
						'oi_size'         => $row->fa_size,
						'oi_width'        => $row->fa_width,
						'oi_height'       => $row->fa_height,
						'oi_bits'         => $row->fa_bits,
						'oi_description'  => $row->fa_description,
						'oi_user'         => $row->fa_user,
						'oi_user_text'    => $row->fa_user_text,
						'oi_timestamp'    => $row->fa_timestamp );
				}

				$dbw->insert( $table, $fields, __METHOD__ );
				// @todo this delete is not totally safe, potentially
				$dbw->delete( 'filearchive',
					array( 'fa_id' => $row->fa_id ),
					__METHOD__ );

				// Check if any other stored revisions use this file;
				// if so, we shouldn't remove the file from the deletion
				// archives so they will still work.
				$useCount = $dbw->selectField( 'filearchive',
					'COUNT(*)',
					array(
						'fa_storage_group' => $row->fa_storage_group,
						'fa_storage_key'   => $row->fa_storage_key ),
					__METHOD__ );
				if( $useCount == 0 ) {
					wfDebug( __METHOD__.": nothing else using {$row->fa_storage_key}, will deleting after\n" );
					$flags = FileStore::DELETE_ORIGINAL;
				} else {
					$flags = 0;
				}

				$transaction->add( $store->export( $row->fa_storage_key,
					$destPath, $flags ) );
			}

			$dbw->immediateCommit();
		} catch( MWException $e ) {
			wfDebug( __METHOD__." caught error, aborting\n" );
			$transaction->rollback();
			throw $e;
		}

		$transaction->commit();
		FileStore::unlock();

		if( $revisions > 0 ) {
			if( !$exists ) {
				wfDebug( __METHOD__." restored $revisions items, creating a new current\n" );

				// Update site_stats
				$site_stats = $dbw->tableName( 'site_stats' );
				$dbw->query( "UPDATE $site_stats SET ss_images=ss_images+1", __METHOD__ );

				$this->purgeEverything();
			} else {
				wfDebug( __METHOD__." restored $revisions as archived versions\n" );
				$this->purgeDescription();
			}
		}

		return $revisions;
	}

	/**
	 * Returns 'true' if this image is a multipage document, e.g. a DJVU
	 * document.
	 *
	 * @return Bool
	 */
	function isMultipage() {
		$handler = $this->getHandler();
		return $handler && $handler->isMultiPage();
	}

	/**
	 * Returns the number of pages of a multipage document, or NULL for
	 * documents which aren't multipage documents
	 */
	function pageCount() {
		$handler = $this->getHandler();
		if ( $handler && $handler->isMultiPage() ) {
			return $handler->pageCount( $this );
		} else {
			return null;
		}
	}

	static function getCommonsDB() {
		static $dbc;
		global $wgLoadBalancer, $wgSharedUploadDBname;
		if ( !isset( $dbc ) ) {
			$i = $wgLoadBalancer->getGroupIndex( 'commons' );
			$dbinfo = $wgLoadBalancer->mServers[$i];
			$dbc = new Database( $dbinfo['host'], $dbinfo['user'], 
				$dbinfo['password'], $wgSharedUploadDBname );
		}
		return $dbc;
	}

	/**
	 * Calculate the height of a thumbnail using the source and destination width
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
	 * Get an image size array like that returned by getimagesize(), or false if it 
	 * can't be determined.
	 *
	 * @param string $fileName The filename
	 * @return array
	 */
	function getImageSize( $fileName ) {
		$handler = $this->getHandler();
		return $handler->getImageSize( $this, $fileName );
	}

	/**
	 * Get the thumbnail extension and MIME type for a given source MIME type
	 * @return array thumbnail extension and MIME type
	 */
	static function getThumbType( $ext, $mime ) {
		$handler = MediaHandler::getHandler( $mime );
		if ( $handler ) {
			return $handler->getThumbType( $ext, $mime );
		} else {
			return array( $ext, $mime );
		}
	}

} //class


/**
 * @addtogroup Media
 */
class ArchivedFile
{
	/**
	 * Returns a file object from the filearchive table
	 * In the future, all current and old image storage
	 * may use FileStore. There will be a "old" storage 
	 * for current and previous file revisions as well as
	 * the "deleted" group for archived revisions
	 * @param $title, the corresponding image page title
	 * @param $id, the image id, a unique key
	 * @param $key, optional storage key
	 * @return ResultWrapper
	 */
	function ArchivedFile( $title, $id=0, $key='' ) {
		if( !is_object( $title ) ) {
			throw new MWException( 'Image constructor given bogus title.' );
		}
		$conds = ($id) ? "fa_id = $id" : "fa_storage_key = '$key'";
		if( $title->getNamespace() == NS_IMAGE ) {
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select( 'filearchive',
				array(
					'fa_id',
					'fa_name',
					'fa_storage_key',
					'fa_storage_group',
					'fa_size',
					'fa_bits',
					'fa_width',
					'fa_height',
					'fa_metadata',
					'fa_media_type',
					'fa_major_mime',
					'fa_minor_mime',
					'fa_description',
					'fa_user',
					'fa_user_text',
					'fa_timestamp',
					'fa_deleted' ),
				array( 
					'fa_name' => $title->getDbKey(),
					$conds ),
				__METHOD__,
				array( 'ORDER BY' => 'fa_timestamp DESC' ) );
				
			if ( $dbr->numRows( $res ) == 0 ) {
			// this revision does not exist?
				return;
			}
			$ret = $dbr->resultObject( $res );
			$row = $ret->fetchObject();
	
			// initialize fields for filestore image object
			$this->mId = intval($row->fa_id);
			$this->mName = $row->fa_name;
			$this->mGroup = $row->fa_storage_group;
			$this->mKey = $row->fa_storage_key;
			$this->mSize = $row->fa_size;
			$this->mBits = $row->fa_bits;
			$this->mWidth = $row->fa_width;
			$this->mHeight = $row->fa_height;
			$this->mMetaData = $row->fa_metadata;
			$this->mMime = "$row->fa_major_mime/$row->fa_minor_mime";
			$this->mType = $row->fa_media_type;
			$this->mDescription = $row->fa_description;
			$this->mUser = $row->fa_user;
			$this->mUserText = $row->fa_user_text;
			$this->mTimestamp = $row->fa_timestamp;
			$this->mDeleted = $row->fa_deleted;		
		} else {
			throw new MWException( 'This title does not correspond to an image page.' );
			return;
		}
		return true;
	}

	/**
	 * int $field one of DELETED_* bitfield constants
	 * for file or revision rows
	 * @return bool
	 */
	function isDeleted( $field ) {
		return ($this->mDeleted & $field) == $field;
	}
	
	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this FileStore image file, if it's marked as deleted.
	 * @param int $field					
	 * @return bool
	 */
	function userCan( $field ) {
		if( isset($this->mDeleted) && ($this->mDeleted & $field) == $field ) {
		// images
			global $wgUser;
			$permission = ( $this->mDeleted & Revision::DELETED_RESTRICTED ) == Revision::DELETED_RESTRICTED
				? 'hiderevision'
				: 'deleterevision';
			wfDebug( "Checking for $permission due to $field match on $this->mDeleted\n" );
			return $wgUser->isAllowed( $permission );
		} else {
			return true;
		}
	}
}

/**
 * Aliases for backwards compatibility with 1.6
 */
define( 'MW_IMG_DELETED_FILE', Image::DELETED_FILE );
define( 'MW_IMG_DELETED_COMMENT', Image::DELETED_COMMENT );
define( 'MW_IMG_DELETED_USER', Image::DELETED_USER );
define( 'MW_IMG_DELETED_RESTRICTED', Image::DELETED_RESTRICTED );

?>
