<?php
/**
 * @package MediaWiki
 */

/**
 * NOTE FOR WINDOWS USERS:
 * To enable EXIF functions, add the folloing lines to the
 * "Windows extensions" section of php.ini:
 *
 * extension=extensions/php_mbstring.dll
 * extension=extensions/php_exif.dll
 */

if ($wgShowEXIF)
	require_once('Exif.php');

/**
 * Bump this number when serialized cache records may be incompatible.
 */
define( 'MW_IMAGE_VERSION', 1 );

/**
 * Class to represent an image
 *
 * Provides methods to retrieve paths (physical, logical, URL),
 * to generate thumbnails or for uploading.
 * @package MediaWiki
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
		$lastError;     # Error string associated with a thumbnail display error


	/**#@-*/

	/**
	 * Create an Image object from an image name
	 *
	 * @param string $name name of the image, used to create a title object using Title::makeTitleSafe
	 * @public
	 */
	function newFromName( $name ) {
		$title = Title::makeTitleSafe( NS_IMAGE, $name );
		if ( is_object( $title ) ) {
			return new Image( $title );
		} else {
			return NULL;
		}
	}

	/**
	 * Obsolete factory function, use constructor
	 */
	function newFromTitle( $title ) {
		return new Image( $title );
	}

	function Image( $title ) {
		if( !is_object( $title ) ) {
			wfDebugDieBacktrace( 'Image constructor given bogus title.' );
		}
		$this->title =& $title;
		$this->name = $title->getDBkey();
		$this->metadata = serialize ( array() ) ;

		$n = strrpos( $this->name, '.' );
		$this->extension = strtolower( $n ? substr( $this->name, $n + 1 ) : '' );
		$this->historyLine = 0;

		$this->dataLoaded = false;
	}

	/**
	 * Get the memcached keys
	 * Returns an array, first element is the local cache key, second is the shared cache key, if there is one
	 */
	function getCacheKeys( ) {
		global $wgDBname, $wgUseSharedUploads, $wgSharedUploadDBname, $wgCacheSharedUploads;

		$hashedName = md5($this->name);
		$keys = array( "$wgDBname:Image:$hashedName" );
		if ( $wgUseSharedUploads && $wgSharedUploadDBname && $wgCacheSharedUploads ) {
			$keys[] = "$wgSharedUploadDBname:Image:$hashedName";
		}
		return $keys;
	}

	/**
	 * Try to load image metadata from memcached. Returns true on success.
	 */
	function loadFromCache() {
		global $wgUseSharedUploads, $wgMemc;
		$fname = 'Image::loadFromMemcached';
		wfProfileIn( $fname );
		$this->dataLoaded = false;
		$keys = $this->getCacheKeys();
		$cachedValues = $wgMemc->get( $keys[0] );

		// Check if the key existed and belongs to this version of MediaWiki
		if (!empty($cachedValues) && is_array($cachedValues)
		  && isset($cachedValues['version']) && ( $cachedValues['version'] == MW_IMAGE_VERSION )
		  && $cachedValues['fileExists'] && isset( $cachedValues['mime'] ) && isset( $cachedValues['metadata'] ) )
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

		wfProfileOut( $fname );
		return $this->dataLoaded;
	}

	/**
	 * Save the image metadata to memcached
	 */
	function saveToCache() {
		global $wgMemc;
		$this->load();
		$keys = $this->getCacheKeys();
		if ( $this->fileExists ) {
			// We can't cache negative metadata for non-existent files,
			// because if the file later appears in commons, the local
			// keys won't be purged.
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
		global $wgUseSharedUploads, $wgSharedUploadDirectory, $wgContLang, $wgShowEXIF;
		$fname = 'Image::loadFromFile';
		wfProfileIn( $fname );
		$this->imagePath = $this->getFullPath();
		$this->fileExists = file_exists( $this->imagePath );
		$this->fromSharedDirectory = false;
		$gis = array();

		if (!$this->fileExists) wfDebug("$fname: ".$this->imagePath." not found locally!\n");

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
			$magic=& wfGetMimeMagic();

			$this->mime = $magic->guessMimeType($this->imagePath,true);
			$this->type = $magic->getMediaType($this->imagePath,$this->mime);

			# Get size in bytes
			$this->size = filesize( $this->imagePath );

			$magic=& wfGetMimeMagic();

			# Height and width
			if( $this->mime == 'image/svg' ) {
				wfSuppressWarnings();
				$gis = wfGetSVGsize( $this->imagePath );
				wfRestoreWarnings();
			}
			elseif ( !$magic->isPHPImageType( $this->mime ) ) {
				# Don't try to get the width and height of sound and video files, that's bad for performance
				$gis[0]= 0; //width
				$gis[1]= 0; //height
				$gis[2]= 0; //unknown
				$gis[3]= ""; //width height string
			}
			else {
				wfSuppressWarnings();
				$gis = getimagesize( $this->imagePath );
				wfRestoreWarnings();
			}

			wfDebug("$fname: ".$this->imagePath." loaded, ".$this->size." bytes, ".$this->mime.".\n");
		}
		else {
			$gis[0]= 0; //width
			$gis[1]= 0; //height
			$gis[2]= 0; //unknown
			$gis[3]= ""; //width height string

			$this->mime = NULL;
			$this->type = MEDIATYPE_UNKNOWN;
			wfDebug("$fname: ".$this->imagePath." NOT FOUND!\n");
		}

		$this->width = $gis[0];
		$this->height = $gis[1];

		#NOTE: $gis[2] contains a code for the image type. This is no longer used.

		#NOTE: we have to set this flag early to avoid load() to be called
		# be some of the functions below. This may lead to recursion or other bad things!
		# as ther's only one thread of execution, this should be safe anyway.
		$this->dataLoaded = true;


		if ($this->fileExists && $wgShowEXIF) $this->metadata = serialize ( $this->retrieveExifData() ) ;
		else $this->metadata = serialize ( array() ) ;

		if ( isset( $gis['bits'] ) )  $this->bits = $gis['bits'];
		else $this->bits = 0;

		wfProfileOut( $fname );
	}

	/**
	 * Load image metadata from the DB
	 */
	function loadFromDB() {
		global $wgUseSharedUploads, $wgSharedUploadDBname, $wgSharedUploadDBprefix, $wgContLang;
		$fname = 'Image::loadFromDB';
		wfProfileIn( $fname );

		$dbr =& wfGetDB( DB_SLAVE );

		$this->checkDBSchema($dbr);

		$row = $dbr->selectRow( 'image',
			array( 'img_size', 'img_width', 'img_height', 'img_bits',
			       'img_media_type', 'img_major_mime', 'img_minor_mime', 'img_metadata' ),
			array( 'img_name' => $this->name ), $fname );
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
			$dbc =& wfGetDB( DB_SLAVE, 'commons' );

			$row = $dbc->selectRow( "`$wgSharedUploadDBname`.{$wgSharedUploadDBprefix}image",
				array(
					'img_size', 'img_width', 'img_height', 'img_bits',
					'img_media_type', 'img_major_mime', 'img_minor_mime', 'img_metadata' ),
				array( 'img_name' => $name ), $fname );
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
		}

		# Unconditionally set loaded=true, we don't want the accessors constantly rechecking
		$this->dataLoaded = true;
		wfProfileOut( $fname );
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
				} elseif ( $this->fileExists ) {
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
		$fname = 'Image::upgradeRow';
		wfProfileIn( $fname );

		$this->loadFromFile();

		if ( $this->fromSharedDirectory ) {
			if ( !$wgSharedUploadDBname ) {
				wfProfileOut( $fname );
				return;
			}

			// Write to the other DB using selectDB, not database selectors
			// This avoids breaking replication in MySQL
			$dbw =& wfGetDB( DB_MASTER, 'commons' );
			$dbw->selectDB( $wgSharedUploadDBname );
		} else {
			$dbw =& wfGetDB( DB_MASTER );
		}

		$this->checkDBSchema($dbw);

		if (strpos($this->mime,'/')!==false) {
			list($major,$minor)= explode('/',$this->mime,2);
		}
		else {
			$major= $this->mime;
			$minor= "unknown";
		}

		wfDebug("$fname: upgrading ".$this->name." to 1.5 schema\n");

		$dbw->update( 'image',
			array(
				'img_width' => $this->width,
				'img_height' => $this->height,
				'img_bits' => $this->bits,
				'img_media_type' => $this->type,
				'img_major_mime' => $major,
				'img_minor_mime' => $minor,
				'img_metadata' => $this->metadata,
			), array( 'img_name' => $this->name ), $fname
		);
		if ( $this->fromSharedDirectory ) {
			$dbw->selectDB( $wgDBname );
		}
		wfProfileOut( $fname );
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
		global $wgUseImageMagick;

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
	function getEscapeLocalURL() {
		$this->getTitle();
		return $this->title->escapeLocalURL();
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
	 * @param integer $width	maximum width of the generated thumbnail
	 * @param integer $height	maximum height of the image (optional)
	 * @return ThumbnailImage
	 * @public
	 */
	function getThumbnail( $width, $height=-1 ) {
		if ( $height <= 0 ) {
			return $this->renderThumb( $width );
		}
		$this->load();

		if ($this->canRender()) {
			if ( $width > $this->width * $height / $this->height )
				$width = wfFitBoxWidth( $this->width, $this->height, $height );
			$thumb = $this->renderThumb( $width );
		}
		else $thumb= NULL; #not a bitmap or renderable image, don't try.

		if( is_null( $thumb ) ) {
			$thumb = $this->iconThumb();
		}
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
	 * Create a thumbnail of the image having the specified width.
	 * The thumbnail will not be created if the width is larger than the
	 * image's width. Let the browser do the scaling in this case.
	 * The thumbnail is stored on disk and is only computed if the thumbnail
	 * file does not exist OR if it is older than the image.
	 * Returns an object which can return the pathname, URL, and physical
	 * pixel size of the thumbnail -- or null on failure.
	 *
	 * @return ThumbnailImage
	 * @private
	 */
	function renderThumb( $width, $useScript = true ) {
		global $wgUseSquid, $wgInternalServer;
		global $wgSVGMaxSize, $wgMaxImageArea, $wgThumbnailEpoch;

		$fname = 'Image::renderThumb';
		wfProfileIn( $fname );

		$width = intval( $width );

		$this->load();
		if ( ! $this->exists() )
		{
			# If there is no image, there will be no thumbnail
			wfProfileOut( $fname );
			return null;
		}

		# Sanity check $width
		if( $width <= 0 || $this->width <= 0) {
			# BZZZT
			wfProfileOut( $fname );
			return null;
		}

		# Don't thumbnail an image so big that it will fill hard drives and send servers into swap
		# JPEG has the handy property of allowing thumbnailing without full decompression, so we make
		# an exception for it.
		if ( $this->getMediaType() == MEDIATYPE_BITMAP &&
			$this->getMimeType() !== 'image/jpeg' &&
			$this->width * $this->height > $wgMaxImageArea )
		{
			wfProfileOut( $fname );
			return null;
		}

		# Don't make an image bigger than the source, or wgMaxSVGSize for SVGs
		if ( $this->mustRender() ) {
			$width = min( $width, $wgSVGMaxSize );
		} elseif ( $width > $this->width - 1 ) {
			$thumb = new ThumbnailImage( $this->getURL(), $this->getWidth(), $this->getHeight() );
			wfProfileOut( $fname );
			return $thumb;
		}

		$height = round( $this->height * $width / $this->width );

		list( $isScriptUrl, $url ) = $this->thumbUrl( $width );
		if ( $isScriptUrl && $useScript ) {
			// Use thumb.php to render the image
			$thumb = new ThumbnailImage( $url, $width, $height );
			wfProfileOut( $fname );
			return $thumb;
		}

		$thumbName = $this->thumbName( $width, $this->fromSharedDirectory );
		$thumbPath = wfImageThumbDir( $this->name, $this->fromSharedDirectory ).'/'.$thumbName;

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
			filemtime( $thumbPath ) < wfTimestamp( TS_UNIX, $wgThumbnailEpoch ) ) {
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
							wfDebugDieBacktrace( "Directory where image should be: $thumbPath" );
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
				}

				# Purge squid
				# This has to be done after the image is updated and present for all machines on NFS,
				# or else the old version might be stored into the squid again
				if ( $wgUseSquid ) {
					if ( substr( $url, 0, 4 ) == 'http' ) {
						$urlArr = array( $url );
					} else {
						$urlArr = array( $wgInternalServer.$url );
					}
					wfPurgeSquidServers($urlArr);
				}
			}
		}

		if ( $done ) {
			$thumb = new ThumbnailImage( $url, $width, $height, $thumbPath );
		} else {
			$thumb = null;
		}
		wfProfileOut( $fname );
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

		$this->load();

		$err = false;
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
				$err = wfShellExec( $cmd );
				wfProfileOut( 'rsvg' );
			}
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
				// For the -resize option a "!" is needed to force exact size,
				// or ImageMagick may decide your ratio is wrong and slice off
				// a pixel.
				" -resize " . wfEscapeShellArg( "{$width}x{$height}!" ) .
				" -depth 8 " .
				wfEscapeShellArg($thumbPath) . " 2>&1";
			wfDebug("reallyRenderThumb: running ImageMagick: $cmd\n");
			wfProfileIn( 'convert' );
			$err = wfShellExec( $cmd );
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
			$err = wfShellExec( $cmd );
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

		#
		# Check for zero-sized thumbnails. Those can be generated when
		# no disk space is available or some other error occurs
		#
		if( file_exists( $thumbPath ) ) {
			$thumbstat = stat( $thumbPath );
			if( $thumbstat['size'] == 0 ) {
				unlink( $thumbPath );
			} else {
				// All good
				$err = true;
			}
		}
		if ( $err !== true ) {
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

			// This generates an error on failure, hence the @
			$handle = @opendir( $dir );

			if ( $handle ) {
				while ( false !== ( $file = readdir($handle) ) ) {
					if ( $file{0} != '.' ) {
						$files[] = $file;
					}
				}
				closedir( $handle );
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
		global $wgInternalServer, $wgUseSquid;

		// Refresh metadata cache
		$this->purgeMetadataCache();

		// Delete thumbnails
		$files = $this->getThumbnails( $shared );
		$dir = wfImageThumbDir( $this->name, $shared );
		$urls = array();
		foreach ( $files as $file ) {
			if ( preg_match( '/^(\d+)px/', $file, $m ) ) {
				$urls[] = $wgInternalServer . $this->thumbUrl( $m[1], $this->fromSharedDirectory );
				@unlink( "$dir/$file" );
			}
		}

		// Purge the squid
		if ( $wgUseSquid ) {
			$urls[] = $wgInternalServer . $this->getViewURL();
			foreach ( $archiveFiles as $file ) {
				$urls[] = $wgInternalServer . wfImageArchiveUrl( $file );
			}
			wfPurgeSquidServers( $urls );
		}
	}

	function checkDBSchema(&$db) {
		global $wgCheckDBSchema;
		if (!$wgCheckDBSchema) {
			return;
		}
		# img_name must be unique
		if ( !$db->indexUnique( 'image', 'img_name' ) && !$db->indexExists('image','PRIMARY') ) {
			wfDebugDieBacktrace( 'Database schema not up to date, please run maintenance/archives/patch-image_name_unique.sql' );
		}

		#new fields must exist
		if ( !$db->fieldExists( 'image', 'img_media_type' )
		  || !$db->fieldExists( 'image', 'img_metadata' )
		  || !$db->fieldExists( 'image', 'img_width' ) ) {

			wfDebugDieBacktrace( 'Database schema not up to date, please run maintenance/update.php' );
		}
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
		$fname = 'Image::nextHistoryLine()';
		$dbr =& wfGetDB( DB_SLAVE );

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
				$fname
			);
			if ( 0 == wfNumRows( $this->historyRes ) ) {
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
				$fname,
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
	function isHashed( $shared ) {
		global $wgHashedUploadDirectory, $wgHashedSharedUploadDirectory;
		return $shared ? $wgHashedSharedUploadDirectory : $wgHashedUploadDirectory;
	}

	/**
	 * Record an image upload in the upload log and the image table
	 */
	function recordUpload( $oldver, $desc, $license = '', $copyStatus = '', $source = '', $watch = false ) {
		global $wgUser, $wgUseCopyrightUpload, $wgUseSquid, $wgPostCommitUpdateList;

		$fname = 'Image::recordUpload';
		$dbw =& wfGetDB( DB_MASTER );

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
			$fname,
			'IGNORE'
		);
		$descTitle = $this->getTitle();
		$purgeURLs = array();

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
				), array( 'img_name' => $this->name ), $fname
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
				), $fname
			);
		} else {
			# This is a new image
			# Update the image count
			$site_stats = $dbw->tableName( 'site_stats' );
			$dbw->query( "UPDATE $site_stats SET ss_images=ss_images+1", $fname );
		}

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
			$purgeURLs[] = $descTitle->getInternalURL();
		} else {
			// New image; create the description page.
			$article->insertNewArticle( $textdesc, $desc, $minor, $watch, $suppressRC );
		}

		# Invalidate cache for all pages using this image
		$linksTo = $this->getLinksTo();

		if ( $wgUseSquid ) {
			$u = SquidUpdate::newFromTitles( $linksTo, $purgeURLs );
			array_push( $wgPostCommitUpdateList, $u );
		}
		Title::touchArray( $linksTo );

		$log = new LogPage( 'upload' );
		$log->addEntry( 'upload', $descTitle, $desc );

		return true;
	}

	/**
	 * Get an array of Title objects which are articles which use this image
	 * Also adds their IDs to the link cache
	 *
	 * This is mostly copied from Title::getLinksTo()
	 */
	function getLinksTo( $options = '' ) {
		$fname = 'Image::getLinksTo';
		wfProfileIn( $fname );

		if ( $options ) {
			$db =& wfGetDB( DB_MASTER );
		} else {
			$db =& wfGetDB( DB_SLAVE );
		}
		$linkCache =& LinkCache::singleton();

		extract( $db->tableNames( 'page', 'imagelinks' ) );
		$encName = $db->addQuotes( $this->name );
		$sql = "SELECT page_namespace,page_title,page_id FROM $page,$imagelinks WHERE page_id=il_from AND il_to=$encName $options";
		$res = $db->query( $sql, $fname );

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
		wfProfileOut( $fname );
		return $retVal;
	}
	/**
	 * Retrive Exif data from the database
	 *
	 * Retrive Exif data from the database and prune unrecognized tags
	 * and/or tags with invalid contents
	 *
	 * @return array
	 */
	function retrieveExifData() {
		if ( $this->getMimeType() !== "image/jpeg" )
			return array();

		$exif = new Exif( $this->imagePath );
		return $exif->getFilteredData();
	}

	function getExifData() {
		global $wgRequest;
		if ( $this->metadata === '0' )
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
		$fname = 'Image:updateExifData';

		if ( $this->getImagePath() === false ) # Not a local image
			return;

		# Get EXIF data from image
		$exif = $this->retrieveExifData();
		if ( count( $exif ) ) {
			$exif['MEDIAWIKI_EXIF_VERSION'] = $version;
			$this->metadata = serialize( $exif );
		} else {
			$this->metadata = '0';
		}

		# Update EXIF data in database
		$dbw =& wfGetDB( DB_MASTER );

		$this->checkDBSchema($dbw);

		$dbw->update( 'image',
			array( 'img_metadata' => $this->metadata ),
			array( 'img_name' => $this->name ),
			$fname
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
		$dbw =& wfGetDB( DB_MASTER );
		$del = $dbw->selectField( 'archive', 'COUNT(*) AS count', array( 'ar_namespace' => NS_IMAGE, 'ar_title' => $this->title->getDBkey() ), 'Image::wasDeleted' );
		return $del > 0;
	}

} //class


/**
 * Returns the image directory of an image
 * If the directory does not exist, it is created.
 * The result is an absolute path.
 *
 * This function is called from thumb.php before Setup.php is included
 *
 * @param $fname String: file name of the image file.
 * @public
 */
function wfImageDir( $fname ) {
	global $wgUploadDirectory, $wgHashedUploadDirectory;

	if (!$wgHashedUploadDirectory) { return $wgUploadDirectory; }

	$hash = md5( $fname );
	$oldumask = umask(0);
	$dest = $wgUploadDirectory . '/' . $hash{0};
	if ( ! is_dir( $dest ) ) { mkdir( $dest, 0777 ); }
	$dest .= '/' . substr( $hash, 0, 2 );
	if ( ! is_dir( $dest ) ) { mkdir( $dest, 0777 ); }

	umask( $oldumask );
	return $dest;
}

/**
 * Returns the image directory of an image's thubnail
 * If the directory does not exist, it is created.
 * The result is an absolute path.
 *
 * This function is called from thumb.php before Setup.php is included
 *
 * @param $fname String: file name of the original image file
 * @param $shared Boolean: (optional) use the shared upload directory (default: 'false').
 * @public
 */
function wfImageThumbDir( $fname, $shared = false ) {
	$base = wfImageArchiveDir( $fname, 'thumb', $shared );
	if ( Image::isHashed( $shared ) ) {
		$dir =  "$base/$fname";

		if ( !is_dir( $base ) ) {
			$oldumask = umask(0);
			@mkdir( $base, 0777 );
			umask( $oldumask );
		}

		if ( ! is_dir( $dir ) ) {
			if ( is_file( $dir ) ) {
				// Old thumbnail in the way of directory creation, kill it
				unlink( $dir );
			}
			$oldumask = umask(0);
			@mkdir( $dir, 0777 );
			umask( $oldumask );
		}
	} else {
		$dir = $base;
	}

	return $dir;
}

/**
 * Old thumbnail directory, kept for conversion
 */
function wfDeprecatedThumbDir( $thumbName , $subdir='thumb', $shared=false) {
	return wfImageArchiveDir( $thumbName, $subdir, $shared );
}

/**
 * Returns the image directory of an image's old version
 * If the directory does not exist, it is created.
 * The result is an absolute path.
 *
 * This function is called from thumb.php before Setup.php is included
 *
 * @param $fname String: file name of the thumbnail file, including file size prefix.
 * @param $subdir String: subdirectory of the image upload directory that should be used for storing the old version. Default is 'archive'.
 * @param $shared Boolean use the shared upload directory (only relevant for other functions which call this one). Default is 'false'.
 * @public
 */
function wfImageArchiveDir( $fname , $subdir='archive', $shared=false ) {
	global $wgUploadDirectory, $wgHashedUploadDirectory;
	global $wgSharedUploadDirectory, $wgHashedSharedUploadDirectory;
	$dir = $shared ? $wgSharedUploadDirectory : $wgUploadDirectory;
	$hashdir = $shared ? $wgHashedSharedUploadDirectory : $wgHashedUploadDirectory;
	if (!$hashdir) { return $dir.'/'.$subdir; }
	$hash = md5( $fname );
	$oldumask = umask(0);

	# Suppress warning messages here; if the file itself can't
	# be written we'll worry about it then.
	wfSuppressWarnings();

	$archive = $dir.'/'.$subdir;
	if ( ! is_dir( $archive ) ) { mkdir( $archive, 0777 ); }
	$archive .= '/' . $hash{0};
	if ( ! is_dir( $archive ) ) { mkdir( $archive, 0777 ); }
	$archive .= '/' . substr( $hash, 0, 2 );
	if ( ! is_dir( $archive ) ) { mkdir( $archive, 0777 ); }

	wfRestoreWarnings();
	umask( $oldumask );
	return $archive;
}


/*
 * Return the hash path component of an image path (URL or filesystem),
 * e.g. "/3/3c/", or just "/" if hashing is not used.
 *
 * @param $dbkey The filesystem / database name of the file
 * @param $fromSharedDirectory Use the shared file repository? It may
 *   use different hash settings from the local one.
 */
function wfGetHashPath ( $dbkey, $fromSharedDirectory = false ) {
	if( Image::isHashed( $fromSharedDirectory ) ) {
		$hash = md5($dbkey);
		return '/' . $hash{0} . '/' . substr( $hash, 0, 2 ) . '/';
	} else {
		return '/';
	}
}

/**
 * Returns the image URL of an image's old version
 *
 * @param $name String: file name of the image file
 * @param $subdir String: (optional) subdirectory of the image upload directory that is used by the old version. Default is 'archive'
 * @public
 */
function wfImageArchiveUrl( $name, $subdir='archive' ) {
	global $wgUploadPath, $wgHashedUploadDirectory;

	if ($wgHashedUploadDirectory) {
		$hash = md5( substr( $name, 15) );
		$url = $wgUploadPath.'/'.$subdir.'/' . $hash{0} . '/' .
		  substr( $hash, 0, 2 ) . '/'.$name;
	} else {
		$url = $wgUploadPath.'/'.$subdir.'/'.$name;
	}
	return wfUrlencode($url);
}

/**
 * Return a rounded pixel equivalent for a labeled CSS/SVG length.
 * http://www.w3.org/TR/SVG11/coords.html#UnitIdentifiers
 *
 * @param $length String: CSS/SVG length.
 * @return Integer: length in pixels
 */
function wfScaleSVGUnit( $length ) {
	static $unitLength = array(
		'px' => 1.0,
		'pt' => 1.25,
		'pc' => 15.0,
		'mm' => 3.543307,
		'cm' => 35.43307,
		'in' => 90.0,
		''   => 1.0, // "User units" pixels by default
		'%'  => 2.0, // Fake it!
		);
	if( preg_match( '/^(\d+(?:\.\d+)?)(em|ex|px|pt|pc|cm|mm|in|%|)$/', $length, $matches ) ) {
		$length = floatval( $matches[1] );
		$unit = $matches[2];
		return round( $length * $unitLength[$unit] );
	} else {
		// Assume pixels
		return round( floatval( $length ) );
	}
}

/**
 * Compatible with PHP getimagesize()
 * @todo support gzipped SVGZ
 * @todo check XML more carefully
 * @todo sensible defaults
 *
 * @param $filename String: full name of the file (passed to php fopen()).
 * @return array
 */
function wfGetSVGsize( $filename ) {
	$width = 256;
	$height = 256;

	// Read a chunk of the file
	$f = fopen( $filename, "rt" );
	if( !$f ) return false;
	$chunk = fread( $f, 4096 );
	fclose( $f );

	// Uber-crappy hack! Run through a real XML parser.
	if( !preg_match( '/<svg\s*([^>]*)\s*>/s', $chunk, $matches ) ) {
		return false;
	}
	$tag = $matches[1];
	if( preg_match( '/\bwidth\s*=\s*("[^"]+"|\'[^\']+\')/s', $tag, $matches ) ) {
		$width = wfScaleSVGUnit( trim( substr( $matches[1], 1, -1 ) ) );
	}
	if( preg_match( '/\bheight\s*=\s*("[^"]+"|\'[^\']+\')/s', $tag, $matches ) ) {
		$height = wfScaleSVGUnit( trim( substr( $matches[1], 1, -1 ) ) );
	}

	return array( $width, $height, 'SVG',
		"width=\"$width\" height=\"$height\"" );
}

/**
 * Determine if an image exists on the 'bad image list'.
 *
 * @param $name String: the image name to check
 * @return bool
 */
function wfIsBadImage( $name ) {
	static $titleList = false;
	
	if( !$titleList ) {
		# Build the list now
		$titleList = array();
		$lines = explode( "\n", wfMsgForContent( 'bad_image_list' ) );
		foreach( $lines as $line ) {
			if( preg_match( '/^\*\s*\[\[:?(.*?)\]\]/i', $line, $matches ) ) {
				$title = Title::newFromText( $matches[1] );
				if( is_object( $title ) && $title->getNamespace() == NS_IMAGE )
					$titleList[ $title->getDBkey() ] = true;
			}
		}
	}
	return array_key_exists( $name, $titleList );
}



/**
 * Wrapper class for thumbnail images
 * @package MediaWiki
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

/**
 * Calculate the largest thumbnail width for a given original file size
 * such that the thumbnail's height is at most $maxHeight.
 * @param $boxWidth Integer Width of the thumbnail box.
 * @param $boxHeight Integer Height of the thumbnail box.
 * @param $maxHeight Integer Maximum height expected for the thumbnail.
 * @return Integer.
 */
function wfFitBoxWidth( $boxWidth, $boxHeight, $maxHeight ) {
	$idealWidth = $boxWidth * $maxHeight / $boxHeight;
	$roundedUp = ceil( $idealWidth );
	if( round( $roundedUp * $boxHeight / $boxWidth ) > $maxHeight )
		return floor( $idealWidth );
	else
		return $roundedUp;
}

?>
