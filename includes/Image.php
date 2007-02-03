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
define( 'MW_IMAGE_VERSION', 1 );

/**
 * Class to represent an image
 *
 * Provides methods to retrieve paths (physical, logical, URL),
 * to generate thumbnails or for uploading.
 */
class Image
{
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
	 * @deprecated
	 */
	function newFromTitle( $title ) {
		return new Image( $title );
	}

	function Image( $title ) {
		if( !is_object( $title ) ) {
			throw new MWException( 'Image constructor given bogus title.' );
		}
		$this->title =& $title;
		$this->name = $title->getDBkey();
		$this->metadata = serialize ( array() ) ;

		$n = strrpos( $this->name, '.' );
		$this->extension = Image::normalizeExtension( $n ?
			substr( $this->name, $n + 1 ) : '' );
		$this->historyLine = 0;
		$this->page = 1;

		$this->dataLoaded = false;
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
	 * Returns an array, first element is the local cache key, second is the shared cache key, if there is one
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

			# Get size in bytes
			$this->size = filesize( $this->imagePath );

			$magic=& MimeMagic::singleton();

			# Height and width
			wfSuppressWarnings();
			if( $this->mime == 'image/svg' ) {
				$gis = wfGetSVGsize( $this->imagePath );
			} elseif( $this->mime == 'image/vnd.djvu' ) {
				$deja = new DjVuImage( $this->imagePath );
				$gis = $deja->getImageSize();
			} elseif ( !$magic->isPHPImageType( $this->mime ) ) {
				# Don't try to get the width and height of sound and video files, that's bad for performance
				$gis = false;
			} else {
				$gis = getimagesize( $this->imagePath );
			}
			wfRestoreWarnings();

			wfDebug(__METHOD__.': '.$this->imagePath." loaded, ".$this->size." bytes, ".$this->mime.".\n");
		}
		else {
			$this->mime = NULL;
			$this->type = MEDIATYPE_UNKNOWN;
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


		if ( $this->mime == 'image/vnd.djvu' ) {
			$this->metadata = $deja->retrieveMetaData();
		} else {
			$this->metadata = serialize( $this->retrieveExifData( $this->imagePath ) );
		}

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
		$this->checkDBSchema($dbr);

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
			if ( is_null($this->type) ) {
				$this->upgradeRow();
			}
		} elseif ( $wgUseSharedUploads && $wgSharedUploadDBname ) {
			# In case we're on a wgCapitalLinks=false wiki, we
			# capitalize the first letter of the filename before
			# looking it up in the shared repository.
			$name = $wgContLang->ucfirst($this->name);
			$dbc = wfGetDB( DB_SLAVE, 'commons' );

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
				if ( is_null($this->type) ) {
					$this->upgradeRow();
				}
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
			$this->metadata = serialize ( array() ) ;
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
		if ( $this->metadata == "" ) $this->metadata = serialize ( array() ) ;

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
	 * Metadata was loaded from the database, but the row had a marker indicating it needs to be
	 * upgraded from the 1.4 schema, which had no width, height, bits or type. Upgrade the row.
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
			$dbw = wfGetDB( DB_MASTER, 'commons' );
			$dbw->selectDB( $wgSharedUploadDBname );
		} else {
			$dbw = wfGetDB( DB_MASTER );
		}

		$this->checkDBSchema($dbw);

		list( $major, $minor ) = self::splitMime( $this->mime );

		wfDebug(__METHOD__.': upgrading '.$this->name." to 1.5 schema\n");

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
	 * @param $mime "text/html" etc
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
	 * Return the width of the image
	 *
	 * Returns -1 if the file specified is not a known image type
	 * @public
	 */
	function getWidth() {
		$this->load();
		return $this->width;
	}

	/**
	 * Return the height of the image
	 *
	 * Returns -1 if the file specified is not a known image type
	 * @public
	 */
	function getHeight() {
		$this->load();
		return $this->height;
	}

	/**
	 * Return the size of the image file, in bytes
	 * @public
	 */
	function getSize() {
		$this->load();
		return $this->size;
	}

	/**
	 * Returns the mime type of the file.
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
	 */
	function canRender() {
		global $wgUseImageMagick, $wgDjvuRenderer;

		if( $this->getWidth()<=0 || $this->getHeight()<=0 ) return false;

		$mime= $this->getMimeType();

		if (!$mime || $mime==='unknown' || $mime==='unknown/unknown') return false;

		#if it's SVG, check if there's a converter enabled
		if ($mime === 'image/svg') {
			global $wgSVGConverters, $wgSVGConverter;

			if ($wgSVGConverter && isset( $wgSVGConverters[$wgSVGConverter])) {
				wfDebug( "Image::canRender: SVG is ready!\n" );
				return true;
			} else {
				wfDebug( "Image::canRender: SVG renderer missing\n" );
			}
		}

		#image formats available on ALL browsers
		if (  $mime === 'image/gif'
		   || $mime === 'image/png'
		   || $mime === 'image/jpeg' ) return true;

		#image formats that can be converted to the above formats
		if ($wgUseImageMagick) {
			#convertable by ImageMagick (there are more...)
			if ( $mime === 'image/vnd.wap.wbmp'
			  || $mime === 'image/x-xbitmap'
			  || $mime === 'image/x-xpixmap'
			  #|| $mime === 'image/x-icon'   #file may be split into multiple parts
			  || $mime === 'image/x-portable-anymap'
			  || $mime === 'image/x-portable-bitmap'
			  || $mime === 'image/x-portable-graymap'
			  || $mime === 'image/x-portable-pixmap'
			  #|| $mime === 'image/x-photoshop'  #this takes a lot of CPU and RAM!
			  || $mime === 'image/x-rgb'
			  || $mime === 'image/x-bmp'
			  || $mime === 'image/tiff' ) return true;
		}
		else {
			#convertable by the PHP GD image lib
			if ( $mime === 'image/vnd.wap.wbmp'
			  || $mime === 'image/x-xbitmap' ) return true;
		}
		if ( $mime === 'image/vnd.djvu' && isset( $wgDjvuRenderer ) && $wgDjvuRenderer ) return true;

		return false;
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
		$mime= $this->getMimeType();

		if (  $mime === "image/gif"
		   || $mime === "image/png"
		   || $mime === "image/jpeg" ) return false;

		return true;
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

	/** Returns true if the file is flagged as trusted. Files flagged that way
	* can be linked to directly, even if that is not allowed for this type of
	* file normally.
	*
	* This is a dummy function right now and always returns false. It could be
	* implemented to extract a flag from the database. The trusted flag could be
	* set on upload, if the user has sufficient privileges, to bypass script-
	* and html-filters. It may even be coupled with cryptographics signatures
	* or such.
	*/
	function isTrustedFile() {
		#this could be implemented to check a flag in the databas,
		#look for signatures, etc
		return false;
	}

	/**
	 * Return the escapeLocalURL of this image
	 * @public
	 */
	function getEscapeLocalURL( $query=false) {
		$this->getTitle();
		if ( $query === false ) {
			if ( $this->page != 1 ) {
				$query = 'page=' . $this->page;
			} else {
				$query = '';
			}
		}
		return $this->title->escapeLocalURL( $query );
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
	 * @static
	 */
	function imageUrl( $name, $fromSharedDirectory = false ) {
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
	 * @private
	 */
	function thumbUrl( $width, $subdir='thumb') {
		global $wgUploadPath, $wgUploadBaseUrl, $wgSharedUploadPath;
		global $wgSharedThumbnailScriptPath, $wgThumbnailScriptPath;

		// Generate thumb.php URL if possible
		$script = false;
		$url = false;

		if ( $this->fromSharedDirectory ) {
			if ( $wgSharedThumbnailScriptPath ) {
				$script = $wgSharedThumbnailScriptPath;
			}
		} else {
			if ( $wgThumbnailScriptPath ) {
				$script = $wgThumbnailScriptPath;
			}
		}
		if ( $script ) {
			$url = $script . '?f=' . urlencode( $this->name ) . '&w=' . urlencode( $width );
			if( $this->mustRender() ) {
				$url.= '&r=1';
			}
		} else {
			$name = $this->thumbName( $width );
			if($this->fromSharedDirectory) {
				$base = '';
				$path = $wgSharedUploadPath;
			} else {
				$base = $wgUploadBaseUrl;
				$path = $wgUploadPath;
			}
			if ( Image::isHashed( $this->fromSharedDirectory ) ) {
				$url = "{$base}{$path}/{$subdir}" .
				wfGetHashPath($this->name, $this->fromSharedDirectory)
				. $this->name.'/'.$name;
				$url = wfUrlencode( $url );
			} else {
				$url = "{$base}{$path}/{$subdir}/{$name}";
			}
		}
		return array( $script !== false, $url );
	}

	/**
	 * Return the file name of a thumbnail of the specified width
	 *
	 * @param integer $width	Width of the thumbnail image
	 * @param boolean $shared	Does the thumbnail come from the shared repository?
	 * @private
	 */
	function thumbName( $width ) {
		$thumb = $width."px-".$this->name;
		if ( $this->page != 1 ) {
			$thumb = "page{$this->page}-$thumb";
		}

		if( $this->mustRender() ) {
			if( $this->canRender() ) {
				# Rasterize to PNG (for SVG vector images, etc)
				$thumb .= '.png';
			}
			else {
				#should we use iconThumb here to get a symbolic thumbnail?
				#or should we fail with an internal error?
				return NULL; //can't make bitmap
			}
		}
		return $thumb;
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
	function createThumb( $width, $height=-1 ) {
		$thumb = $this->getThumbnail( $width, $height );
		if( is_null( $thumb ) ) return '';
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
	 */
	function getThumbnail( $width, $height=-1, $render = true ) {
		wfProfileIn( __METHOD__ );
		if ($this->canRender()) {
			if ( $height > 0 ) {
				$this->load();
				if ( $width > $this->width * $height / $this->height ) {
					$width = wfFitBoxWidth( $this->width, $this->height, $height );
				}
			}
			if ( $render ) {
				$thumb = $this->renderThumb( $width );
			} else {
				// Don't render, just return the URL
				if ( $this->validateThumbParams( $width, $height ) ) {
					if ( !$this->mustRender() && $width == $this->width && $height == $this->height ) {
						$url = $this->getURL();
					} else {
						list( /* $isScriptUrl */, $url ) = $this->thumbUrl( $width );
					}
					$thumb = new ThumbnailImage( $url, $width, $height );
				} else {
					$thumb = null;
				}
			}
		} else {
			// not a bitmap or renderable image, don't try.
			$thumb = $this->iconThumb();
		}
		wfProfileOut( __METHOD__ );
		return $thumb;
	}

	/**
	 * @return ThumbnailImage
	 */
	function iconThumb() {
		global $wgStylePath, $wgStyleDirectory;

		$try = array( 'fileicon-' . $this->extension . '.png', 'fileicon.png' );
		foreach( $try as $icon ) {
			$path = '/common/images/icons/' . $icon;
			$filepath = $wgStyleDirectory . $path;
			if( file_exists( $filepath ) ) {
				return new ThumbnailImage( $wgStylePath . $path, 120, 120 );
			}
		}
		return null;
	}

	/**
	 * Validate thumbnail parameters and fill in the correct height
	 *
	 * @param integer &$width Specified width (input/output)
	 * @param integer &$height Height (output only)
	 * @return false to indicate that an error should be returned to the user. 
	 */
	function validateThumbParams( &$width, &$height ) {
		global $wgSVGMaxSize, $wgMaxImageArea;

		$this->load();

		if ( ! $this->exists() )
		{
			# If there is no image, there will be no thumbnail
			return false;
		}

		$width = intval( $width );

		# Sanity check $width
		if( $width <= 0 || $this->width <= 0) {
			# BZZZT
			return false;
		}

		# Don't thumbnail an image so big that it will fill hard drives and send servers into swap
		# JPEG has the handy property of allowing thumbnailing without full decompression, so we make
		# an exception for it.
		if ( $this->getMediaType() == MEDIATYPE_BITMAP &&
			$this->getMimeType() !== 'image/jpeg' &&
			$this->width * $this->height > $wgMaxImageArea )
		{
			return false;
		}

		# Don't make an image bigger than the source, or wgMaxSVGSize for SVGs
		if ( $this->mustRender() ) {
			$width = min( $width, $wgSVGMaxSize );
		} elseif ( $width > $this->width - 1 ) {
			$width = $this->width;
			$height = $this->height;
			return true;
		}

		$height = round( $this->height * $width / $this->width );
		return true;
	}

	/**
	 * Create a thumbnail of the image having the specified width.
	 * The thumbnail will not be created if the width is larger than the
	 * image's width. Let the browser do the scaling in this case.
	 * The thumbnail is stored on disk and is only computed if the thumbnail
	 * file does not exist OR if it is older than the image.
	 * Returns an object which can return the pathname, URL, and physical
	 * pixel size of the thumbnail -- or null on failure.
	 *
	 * @return ThumbnailImage or null on failure
	 * @private
	 */
	function renderThumb( $width, $useScript = true ) {
		global $wgUseSquid, $wgThumbnailEpoch;

		wfProfileIn( __METHOD__ );

		$this->load();
		$height = -1;
		if ( !$this->validateThumbParams( $width, $height ) ) {
			# Validation error
			wfProfileOut( __METHOD__ );
			return null;
		}

		if ( !$this->mustRender() && $width == $this->width && $height == $this->height ) {
			# validateThumbParams (or the user) wants us to return the unscaled image
			$thumb = new ThumbnailImage( $this->getURL(), $width, $height );
			wfProfileOut( __METHOD__ );
			return $thumb;
		}

		list( $isScriptUrl, $url ) = $this->thumbUrl( $width );
		if ( $isScriptUrl && $useScript ) {
			// Use thumb.php to render the image
			$thumb = new ThumbnailImage( $url, $width, $height );
			wfProfileOut( __METHOD__ );
			return $thumb;
		}

		$thumbName = $this->thumbName( $width, $this->fromSharedDirectory );
		$thumbDir = wfImageThumbDir( $this->name, $this->fromSharedDirectory );
		$thumbPath = $thumbDir.'/'.$thumbName;

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
			// Code below will ask if it exists, and the answer is now no
			clearstatcache();
		}

		$done = true;
		if ( !file_exists( $thumbPath ) ||
			filemtime( $thumbPath ) < wfTimestamp( TS_UNIX, $wgThumbnailEpoch ) ) 
		{
			// Create the directory if it doesn't exist
			if ( is_file( $thumbDir ) ) {
				// File where thumb directory should be, destroy if possible
				@unlink( $thumbDir );
			}
			wfMkdirParents( $thumbDir );

			$oldThumbPath = wfDeprecatedThumbDir( $thumbName, 'thumb', $this->fromSharedDirectory ).
				'/'.$thumbName;
			$done = false;

			// Migration from old directory structure
			if ( is_file( $oldThumbPath ) ) {
				if ( filemtime($oldThumbPath) >= filemtime($this->imagePath) ) {
					if ( file_exists( $thumbPath ) ) {
						if ( !is_dir( $thumbPath ) ) {
							// Old image in the way of rename
							unlink( $thumbPath );
						} else {
							// This should have been dealt with already
							throw new MWException( "Directory where image should be: $thumbPath" );
						}
					}
					// Rename the old image into the new location
					rename( $oldThumbPath, $thumbPath );
					$done = true;
				} else {
					unlink( $oldThumbPath );
				}
			}
			if ( !$done ) {
				$this->lastError = $this->reallyRenderThumb( $thumbPath, $width, $height );
				if ( $this->lastError === true ) {
					$done = true;
				} elseif( $GLOBALS['wgIgnoreImageErrors'] ) {
					// Log the error but output anyway.
					// With luck it's a transitory error...
					$done = true;
				}

				# Purge squid
				# This has to be done after the image is updated and present for all machines on NFS,
				# or else the old version might be stored into the squid again
				if ( $wgUseSquid ) {
					$urlArr = array( $url );
					wfPurgeSquidServers($urlArr);
				}
			}
		}

		if ( $done ) {
			$thumb = new ThumbnailImage( $url, $width, $height, $thumbPath );
		} else {
			$thumb = null;
		}
		wfProfileOut( __METHOD__ );
		return $thumb;
	} // END OF function renderThumb

	/**
	 * Really render a thumbnail
	 * Call this only for images for which canRender() returns true.
	 *
	 * @param string $thumbPath Path to thumbnail
	 * @param int $width Desired width in pixels
	 * @param int $height Desired height in pixels
	 * @return bool True on error, false or error string on failure.
	 * @private
	 */
	function reallyRenderThumb( $thumbPath, $width, $height ) {
		global $wgSVGConverters, $wgSVGConverter;
		global $wgUseImageMagick, $wgImageMagickConvertCommand;
		global $wgCustomConvertCommand;
		global $wgDjvuRenderer, $wgDjvuPostProcessor;

		$this->load();

		$err = false;
		$cmd = "";
		$retval = 0;

		if( $this->mime === "image/svg" ) {
			#Right now we have only SVG

			global $wgSVGConverters, $wgSVGConverter;
			if( isset( $wgSVGConverters[$wgSVGConverter] ) ) {
				global $wgSVGConverterPath;
				$cmd = str_replace(
					array( '$path/', '$width', '$height', '$input', '$output' ),
					array( $wgSVGConverterPath ? "$wgSVGConverterPath/" : "",
						   intval( $width ),
						   intval( $height ),
						   wfEscapeShellArg( $this->imagePath ),
						   wfEscapeShellArg( $thumbPath ) ),
					$wgSVGConverters[$wgSVGConverter] );
				wfProfileIn( 'rsvg' );
				wfDebug( "reallyRenderThumb SVG: $cmd\n" );
				$err = wfShellExec( $cmd, $retval );
				wfProfileOut( 'rsvg' );
			}
		} else {
			if ( $this->mime === "image/vnd.djvu" && $wgDjvuRenderer ) {
				// DJVU image
				// The file contains several images. First, extract the
				// page in hi-res, if it doesn't yet exist. Then, thumbnail
				// it.

				$cmd = "{$wgDjvuRenderer} -page={$this->page} -size=${width}x${height} " .
					wfEscapeShellArg( $this->imagePath ) . 
					" | {$wgDjvuPostProcessor} > " . wfEscapeShellArg($thumbPath);
				wfProfileIn( 'ddjvu' );
				wfDebug( "reallyRenderThumb DJVU: $cmd\n" );
				$err = wfShellExec( $cmd, $retval );
				wfProfileOut( 'ddjvu' );

			} elseif ( $wgUseImageMagick ) {
				# use ImageMagick

				if ( $this->mime == 'image/jpeg' ) {
					$quality = "-quality 80"; // 80%
				} elseif ( $this->mime == 'image/png' ) {
					$quality = "-quality 95"; // zlib 9, adaptive filtering
				} else {
					$quality = ''; // default
				}

				# Specify white background color, will be used for transparent images
				# in Internet Explorer/Windows instead of default black.

				# Note, we specify "-size {$width}" and NOT "-size {$width}x{$height}".
				# It seems that ImageMagick has a bug wherein it produces thumbnails of
				# the wrong size in the second case.

				$cmd  =  wfEscapeShellArg($wgImageMagickConvertCommand) .
					" {$quality} -background white -size {$width} ".
					wfEscapeShellArg($this->imagePath) .
					// Coalesce is needed to scale animated GIFs properly (bug 1017).
					' -coalesce ' .
					// For the -resize option a "!" is needed to force exact size,
					// or ImageMagick may decide your ratio is wrong and slice off
					// a pixel.
					" -thumbnail " . wfEscapeShellArg( "{$width}x{$height}!" ) .
					" -depth 8 " .
					wfEscapeShellArg($thumbPath) . " 2>&1";
				wfDebug("reallyRenderThumb: running ImageMagick: $cmd\n");
				wfProfileIn( 'convert' );
				$err = wfShellExec( $cmd, $retval );
				wfProfileOut( 'convert' );
			} elseif( $wgCustomConvertCommand ) {
				# Use a custom convert command
				# Variables: %s %d %w %h
				$src = wfEscapeShellArg( $this->imagePath );
				$dst = wfEscapeShellArg( $thumbPath );
				$cmd = $wgCustomConvertCommand;
				$cmd = str_replace( '%s', $src, str_replace( '%d', $dst, $cmd ) ); # Filenames
				$cmd = str_replace( '%h', $height, str_replace( '%w', $width, $cmd ) ); # Size
				wfDebug( "reallyRenderThumb: Running custom convert command $cmd\n" );
				wfProfileIn( 'convert' );
				$err = wfShellExec( $cmd, $retval );
				wfProfileOut( 'convert' );
			} else {
				# Use PHP's builtin GD library functions.
				#
				# First find out what kind of file this is, and select the correct
				# input routine for this.

				$typemap = array(
					'image/gif'          => array( 'imagecreatefromgif',  'palette',   'imagegif'  ),
					'image/jpeg'         => array( 'imagecreatefromjpeg', 'truecolor', array( &$this, 'imageJpegWrapper' ) ),
					'image/png'          => array( 'imagecreatefrompng',  'bits',      'imagepng'  ),
					'image/vnd.wap.wmbp' => array( 'imagecreatefromwbmp', 'palette',   'imagewbmp'  ),
					'image/xbm'          => array( 'imagecreatefromxbm',  'palette',   'imagexbm'  ),
				);
				if( !isset( $typemap[$this->mime] ) ) {
					$err = 'Image type not supported';
					wfDebug( "$err\n" );
					return $err;
				}
				list( $loader, $colorStyle, $saveType ) = $typemap[$this->mime];

				if( !function_exists( $loader ) ) {
					$err = "Incomplete GD library configuration: missing function $loader";
					wfDebug( "$err\n" );
					return $err;
				}
				if( $colorStyle == 'palette' ) {
					$truecolor = false;
				} elseif( $colorStyle == 'truecolor' ) {
					$truecolor = true;
				} elseif( $colorStyle == 'bits' ) {
					$truecolor = ( $this->bits > 8 );
				}

				$src_image = call_user_func( $loader, $this->imagePath );
				if ( $truecolor ) {
					$dst_image = imagecreatetruecolor( $width, $height );
				} else {
					$dst_image = imagecreate( $width, $height );
				}
				imagecopyresampled( $dst_image, $src_image,
							0,0,0,0,
							$width, $height, $this->width, $this->height );
				call_user_func( $saveType, $dst_image, $thumbPath );
				imagedestroy( $dst_image );
				imagedestroy( $src_image );
			}
		}

		#
		# Check for zero-sized thumbnails. Those can be generated when
		# no disk space is available or some other error occurs
		#
		if( file_exists( $thumbPath ) ) {
			$thumbstat = stat( $thumbPath );
			if( $thumbstat['size'] == 0 || $retval != 0 ) {
				wfDebugLog( 'thumbnail',
					sprintf( 'Removing bad %d-byte thumbnail "%s"',
						$thumbstat['size'], $thumbPath ) );
				unlink( $thumbPath );
			}
		}
		if ( $retval != 0 ) {
			wfDebugLog( 'thumbnail',
				sprintf( 'thumbnail failed on %s: error %d "%s" from "%s"',
					wfHostname(), $retval, trim($err), $cmd ) );
			return wfMsg( 'thumbnail_error', $err );
		} else {
			return true;
		}
	}

	function getLastError() {
		return $this->lastError;
	}

	function imageJpegWrapper( $dst_image, $thumbPath ) {
		imageinterlace( $dst_image );
		imagejpeg( $dst_image, $thumbPath, 95 );
	}

	/**
	 * Get all thumbnail names previously generated for this image
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
						if ( $file{0} != '.' ) {
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
	 */
	function purgeMetadataCache() {
		clearstatcache();
		$this->loadFromFile();
		$this->saveToCache();
	}

	/**
	 * Delete all previously generated thumbnails, refresh metadata in memcached and purge the squid
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
			$m = array();
			if ( preg_match( '/^(\d+)px/', $file, $m ) ) {
				list( /* $isScriptUrl */, $url ) = $this->thumbUrl( $m[1] );
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
	 */
	function purgeDescription() {
		$page = Title::makeTitle( NS_IMAGE, $this->name );
		$page->invalidateCache();
		$page->purgeSquid();
	}

	/**
	 * Purge metadata and all affected pages when the image is created,
	 * deleted, or majorly updated. A set of additional URLs may be
	 * passed to purge, such as specific image files which have changed.
	 * @param $urlArray array
	 */
	function purgeEverything( $urlArr=array() ) {
		// Delete thumbnails and refresh image metadata cache
		$this->purgeCache();
		$this->purgeDescription();

		// Purge cache of all pages using this image
		$update = new HTMLCacheUpdate( $this->getTitle(), 'imagelinks' );
		$update->doUpdate();
	}

	function checkDBSchema(&$db) {
		static $checkDone = false;
		global $wgCheckDBSchema;
		if (!$wgCheckDBSchema || $checkDone) {
			return;
		}
		# img_name must be unique
		if ( !$db->indexUnique( 'image', 'img_name' ) && !$db->indexExists('image','PRIMARY') ) {
			throw new MWException( 'Database schema not up to date, please run maintenance/archives/patch-image_name_unique.sql' );
		}
		$checkDone = true;

		# new fields must exist
		# 
		# Not really, there's hundreds of checks like this that we could do and they're all pointless, because 
		# if the fields are missing, the database will loudly report a query error, the first time you try to do 
		# something. The only reason I put the above schema check in was because the absence of that particular
		# index would lead to an annoying subtle bug. No error message, just some very odd behaviour on duplicate
		# uploads. -- TS
		/*
		if ( !$db->fieldExists( 'image', 'img_media_type' )
		  || !$db->fieldExists( 'image', 'img_metadata' )
		  || !$db->fieldExists( 'image', 'img_width' ) ) {

			throw new MWException( 'Database schema not up to date, please run maintenance/update.php' );
		 }
		 */
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
	 */
	function nextHistoryLine() {
		$dbr = wfGetDB( DB_SLAVE );

		$this->checkDBSchema($dbr);

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
	*
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
	 * @return bool
	 * @static
	 */
	public static function isHashed( $shared ) {
		global $wgHashedUploadDirectory, $wgHashedSharedUploadDirectory;
		return $shared ? $wgHashedSharedUploadDirectory : $wgHashedUploadDirectory;
	}

	/**
	 * Record an image upload in the upload log and the image table
	 */
	function recordUpload( $oldver, $desc, $license = '', $copyStatus = '', $source = '', $watch = false ) {
		global $wgUser, $wgUseCopyrightUpload;

		$dbw = wfGetDB( DB_MASTER );

		$this->checkDBSchema($dbw);

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
	 * Retrive Exif data from the file and prune unrecognized tags
	 * and/or tags with invalid contents
	 *
	 * @param $filename
	 * @return array
	 */
	private function retrieveExifData( $filename ) {
		global $wgShowEXIF;

		/*
		if ( $this->getMimeType() !== "image/jpeg" )
			return array();
		*/

		if( $wgShowEXIF && file_exists( $filename ) ) {
			$exif = new Exif( $filename );
			return $exif->getFilteredData();
		}

		return array();
	}

	function getExifData() {
		global $wgRequest;
		if ( $this->metadata === '0' || $this->mime == 'image/vnd.djvu' )
			return array();

		$purge = $wgRequest->getVal( 'action' ) == 'purge';
		$ret = unserialize( $this->metadata );

		$oldver = isset( $ret['MEDIAWIKI_EXIF_VERSION'] ) ? $ret['MEDIAWIKI_EXIF_VERSION'] : 0;
		$newver = Exif::version();

		if ( !count( $ret ) || $purge || $oldver != $newver ) {
			$this->purgeMetadataCache();
			$this->updateExifData( $newver );
		}
		if ( isset( $ret['MEDIAWIKI_EXIF_VERSION'] ) )
			unset( $ret['MEDIAWIKI_EXIF_VERSION'] );
		$format = new FormatExif( $ret );

		return $format->getFormattedData();
	}

	function updateExifData( $version ) {
		if ( $this->getImagePath() === false ) # Not a local image
			return;

		# Get EXIF data from image
		$exif = $this->retrieveExifData( $this->imagePath );
		if ( count( $exif ) ) {
			$exif['MEDIAWIKI_EXIF_VERSION'] = $version;
			$this->metadata = serialize( $exif );
		} else {
			$this->metadata = '0';
		}

		# Update EXIF data in database
		$dbw = wfGetDB( DB_MASTER );

		$this->checkDBSchema($dbw);

		$dbw->update( 'image',
			array( 'img_metadata' => $this->metadata ),
			array( 'img_name' => $this->name ),
			__METHOD__
		);
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
	 * @param $reason
	 * @return true on success, false on some kind of failure
	 */
	function delete( $reason ) {
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

				$transaction->add( $this->prepareDeleteOld( $oldName, $reason ) );

				// We'll need to purge this URL from caches...
				$urlArr[] = wfImageArchiveUrl( $oldName );
			}
			$dbw->freeResult( $result );

			// And the current version...
			$transaction->add( $this->prepareDeleteCurrent( $reason ) );

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
	 * @param $reason
	 * @throws MWException or FSException on database or filestore failure
	 * @return true on success, false on some kind of failure
	 */
	function deleteOld( $archiveName, $reason ) {
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
			$transaction->add( $this->prepareDeleteOld( $archiveName, $reason ) );
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
	 * @return true on success, false on failure
	 */
	private function prepareDeleteCurrent( $reason ) {
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
			__METHOD__ );
	}

	/**
	 * Delete a given older version of a file.
	 * May throw a database error.
	 * @return true on success, false on failure
	 */
	private function prepareDeleteOld( $archiveName, $reason ) {
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
	private function prepareDeleteVersion( $path, $reason, $table, $fieldMap, $where, $fname ) {
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

		$dbw = wfGetDB( DB_MASTER );
		$storageMap = array(
			'fa_storage_group' => $dbw->addQuotes( $group ),
			'fa_storage_key'   => $dbw->addQuotes( $key ),

			'fa_deleted_user'      => $dbw->addQuotes( $wgUser->getId() ),
			'fa_deleted_timestamp' => $dbw->timestamp(),
			'fa_deleted_reason'    => $dbw->addQuotes( $reason ) );
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
	function restore( $versions=array() ) {
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
						$metadata = serialize( $this->retrieveExifData( $tempFile ) );

						$magic = MimeMagic::singleton();
						$mime = $magic->guessMimeType( $tempFile, true );
						$media_type = $magic->getMediaType( $tempFile, $mime );
						list( $major_mime, $minor_mime ) = self::splitMime( $mime );
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
				/// @fixme this delete is not totally safe, potentially
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
	 * Select a page from a multipage document. Determines the page used for
	 * rendering thumbnails.
	 *
	 * @param $page Integer: page number, starting with 1
	 */
	function selectPage( $page ) {
		if( $this->initializeMultiPageXML() ) {
			wfDebug( __METHOD__." selecting page $page \n" );
			$this->page = $page;
			$o = $this->multiPageXML->BODY[0]->OBJECT[$page-1];
			$this->height = intval( $o['height'] );
			$this->width = intval( $o['width'] );
		} else {
			wfDebug( __METHOD__." selectPage($page) for bogus multipage xml on '$this->name'\n" );
			return;
		}
	}

	/**
	 * Lazy-initialize multipage XML metadata for DjVu files.
	 * @return bool true if $this->multiPageXML is set up and ready;
	 *              false if corrupt or otherwise failing
	 */
	function initializeMultiPageXML() {
		$this->load();
		if ( isset( $this->multiPageXML ) ) {
			return true;
		}

		#
		# Check for files uploaded prior to DJVU support activation,
		# or damaged.
		#
		if( empty( $this->metadata ) || $this->metadata == serialize( array() ) ) {
			$deja = new DjVuImage( $this->imagePath );
			$this->metadata = $deja->retrieveMetaData();
			$this->purgeMetadataCache();

			# Update metadata in the database
			$dbw = wfGetDB( DB_MASTER );
			$dbw->update( 'image',
				array( 'img_metadata' => $this->metadata ),
				array( 'img_name' => $this->name ),
				__METHOD__
			);
		}
		wfSuppressWarnings();
		try {
			$this->multiPageXML = new SimpleXMLElement( $this->metadata );
		} catch( Exception $e ) {
			wfDebug( "Bogus multipage XML metadata on '$this->name'\n" );
			$this->multiPageXML = null;
		}
		wfRestoreWarnings();
		return isset( $this->multiPageXML );
	}

	/**
	 * Returns 'true' if this image is a multipage document, e.g. a DJVU
	 * document.
	 *
	 * @return Bool
	 */
	function isMultipage() {
		return ( $this->mime == 'image/vnd.djvu' );
	}

	/**
	 * Returns the number of pages of a multipage document, or NULL for
	 * documents which aren't multipage documents
	 */
	function pageCount() {
		if ( ! $this->isMultipage() ) {
			return null;
		}
		if( $this->initializeMultiPageXML() ) {
			return count( $this->multiPageXML->xpath( '//OBJECT' ) );
		} else {
			wfDebug( "Requested pageCount() for bogus multi-page metadata for '$this->name'\n" );
			return null;
		}
	}

} //class

/**
 * Wrapper class for thumbnail images
 */
class ThumbnailImage {
	/**
	 * @param string $path Filesystem path to the thumb
	 * @param string $url URL path to the thumb
	 * @private
	 */
	function ThumbnailImage( $url, $width, $height, $path = false ) {
		$this->url = $url;
		$this->width = round( $width );
		$this->height = round( $height );
			# These should be integers when they get here.
			# If not, there's a bug somewhere.  But let's at
			# least produce valid HTML code regardless.
		$this->path = $path;
	}

	/**
	 * @return string The thumbnail URL
	 */
	function getUrl() {
		return $this->url;
	}

	/**
	 * Return HTML <img ... /> tag for the thumbnail, will include
	 * width and height attributes and a blank alt text (as required).
	 *
	 * You can set or override additional attributes by passing an
	 * associative array of name => data pairs. The data will be escaped
	 * for HTML output, so should be in plaintext.
	 *
	 * @param array $attribs
	 * @return string
	 * @public
	 */
	function toHtml( $attribs = array() ) {
		$attribs['src'] = $this->url;
		$attribs['width'] = $this->width;
		$attribs['height'] = $this->height;
		if( !isset( $attribs['alt'] ) ) $attribs['alt'] = '';

		$html = '<img ';
		foreach( $attribs as $name => $data ) {
			$html .= $name . '="' . htmlspecialchars( $data ) . '" ';
		}
		$html .= '/>';
		return $html;
	}

}

?>
