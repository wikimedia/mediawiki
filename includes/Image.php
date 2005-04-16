<?php
/**
 * @package MediaWiki
 */

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
	 * @access private
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
		$type,          #  |
		$attr,          # /
		$size,          # Size in bytes (loadFromXxx)
		$dataLoaded;    # Whether or not all this has been loaded from the database (loadFromXxx)


	/**#@-*/

	/**
	 * Create an Image object from an image name
	 *
	 * @param string $name name of the image, used to create a title object using Title::makeTitleSafe
	 * @access public
	 */
	function newFromName( $name ) {
		$title = Title::makeTitleSafe( NS_IMAGE, $name );
		return new Image( $title );
	}

	/** 
	 * Obsolete factory function, use constructor
	 */
	function newFromTitle( $title ) {
		return new Image( $title );
	}
	
	function Image( $title ) {
		$this->title =& $title;
		$this->name = $title->getDBkey();

		$n = strrpos( $this->name, '.' );
		$this->extension = strtolower( $n ? substr( $this->name, $n + 1 ) : '' );
		$this->historyLine = 0;

		$this->dataLoaded = false;
	}

	/**
	 * Get the memcached keys
	 * Returns an array, first element is the local cache key, second is the shared cache key, if there is one
	 */
	function getCacheKeys( $shared = false ) {
		global $wgDBname, $wgUseSharedUploads, $wgSharedUploadDBname, $wgCacheSharedUploads;
		
		$foundCached = false;
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
		if (!empty($cachedValues) && is_array($cachedValues) && isset($cachedValues['width']) && $cachedValues['fileExists']) {
			if ( $wgUseSharedUploads && $cachedValues['fromShared']) {
				# if this is shared file, we need to check if image
				# in shared repository has not changed
				if ( isset( $keys[1] ) ) {
					$commonsCachedValues = $wgMemc->get( $keys[1] );
					if (!empty($commonsCachedValues) && is_array($commonsCachedValues) && isset($commonsCachedValues['width'])) {
						$this->name = $commonsCachedValues['name'];
						$this->imagePath = $commonsCachedValues['imagePath'];
						$this->fileExists = $commonsCachedValues['fileExists'];
						$this->width = $commonsCachedValues['width'];
						$this->height = $commonsCachedValues['height'];
						$this->bits = $commonsCachedValues['bits'];
						$this->type = $commonsCachedValues['type'];
						$this->size = $commonsCachedValues['size'];
						$this->fromSharedDirectory = true;
						$this->dataLoaded = true;
						$this->imagePath = $this->getFullPath(true);
					}
				}
			}
			else {
				$this->name = $cachedValues['name'];
				$this->imagePath = $cachedValues['imagePath'];
				$this->fileExists = $cachedValues['fileExists'];
				$this->width = $cachedValues['width'];
				$this->height = $cachedValues['height'];
				$this->bits = $cachedValues['bits'];
				$this->type = $cachedValues['type'];
				$this->size = $cachedValues['size'];
				$this->fromSharedDirectory = false;
				$this->dataLoaded = true;
				$this->imagePath = $this->getFullPath();
			}
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
		// We can't cache metadata for non-existent files, because if the file later appears 
		// in commons, the local keys won't be purged.
		if ( $this->fileExists ) {
			$keys = $this->getCacheKeys();
		
			$cachedValues = array('name' => $this->name,
								  'imagePath' => $this->imagePath,
								  'fileExists' => $this->fileExists,
								  'fromShared' => $this->fromSharedDirectory,
								  'width' => $this->width,
								  'height' => $this->height,
								  'bits' => $this->bits,
								  'type' => $this->type,
								  'size' => $this->size);

			$wgMemc->set( $keys[0], $cachedValues );
		}
	}
	
	/** 
	 * Load metadata from the file itself
	 */
	function loadFromFile() {
		global $wgUseSharedUploads, $wgSharedUploadDirectory, $wgLang;
		$fname = 'Image::loadFromFile';
		wfProfileIn( $fname );
		$this->imagePath = $this->getFullPath();
		$this->fileExists = file_exists( $this->imagePath );
		$this->fromSharedDirectory = false;
		$gis = false;

		# If the file is not found, and a shared upload directory is used, look for it there.
		if (!$this->fileExists && $wgUseSharedUploads && $wgSharedUploadDirectory) {			
			# In case we're on a wgCapitalLinks=false wiki, we 
			# capitalize the first letter of the filename before 
			# looking it up in the shared repository.
			$sharedImage = Image::newFromName( $wgLang->ucfirst($this->name) );
			$this->fileExists = file_exists( $sharedImage->getFullPath(true) );
			if ( $this->fileExists ) {
				$this->name = $sharedImage->name;
				$this->imagePath = $this->getFullPath(true);
				$this->fromSharedDirectory = true;
			}
		}

		if ( $this->fileExists ) {
			# Get size in bytes
			$s = stat( $this->imagePath );
			$this->size = $s['size'];

			# Height and width
			# Don't try to get the width and height of sound and video files, that's bad for performance
			if ( !Image::isKnownImageExtension( $this->extension ) ) {
				$gis = false;
			} elseif( $this->extension == 'svg' ) {
				wfSuppressWarnings();
				$gis = wfGetSVGsize( $this->imagePath );
				wfRestoreWarnings();
			} else {
				wfSuppressWarnings();
				$gis = getimagesize( $this->imagePath );
				wfRestoreWarnings();
			}
		}
		if( $gis === false ) {
			$this->width = 0;
			$this->height = 0;
			$this->bits = 0;
			$this->type = 0;
		} else {
			$this->width = $gis[0];
			$this->height = $gis[1];
			$this->type = $gis[2];
			if ( isset( $gis['bits'] ) )  {
				$this->bits = $gis['bits'];
			} else {
				$this->bits = 0;
			}
		}
		$this->dataLoaded = true;
		wfProfileOut( $fname );
	}

	/** 
	 * Load image metadata from the DB
	 */
	function loadFromDB() {
		global $wgUseSharedUploads, $wgSharedUploadDBname, $wgLang;
		$fname = 'Image::loadFromDB';
		wfProfileIn( $fname );
		
		$dbr =& wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'image', 
			array( 'img_size', 'img_width', 'img_height', 'img_bits', 'img_type' ),
			array( 'img_name' => $this->name ), $fname );
		if ( $row ) {
			$this->fromSharedDirectory = false;
			$this->fileExists = true;
			$this->loadFromRow( $row );
			$this->imagePath = $this->getFullPath();
			// Check for rows from a previous schema, quietly upgrade them
			if ( $this->type == -1 ) {
				$this->upgradeRow();
			}
		} elseif ( $wgUseSharedUploads && $wgSharedUploadDBname ) {
			# In case we're on a wgCapitalLinks=false wiki, we 
			# capitalize the first letter of the filename before 
			# looking it up in the shared repository.
			$name = $wgLang->ucfirst($this->name);

			$row = $dbr->selectRow( "`$wgSharedUploadDBname`.image", 
				array( 'img_size', 'img_width', 'img_height', 'img_bits', 'img_type' ),
				array( 'img_name' => $name ), $fname );
			if ( $row ) {
				$this->fromSharedDirectory = true;
				$this->fileExists = true;
				$this->imagePath = $this->getFullPath(true);
				$this->name = $name;
				$this->loadFromRow( $row );
				
				// Check for rows from a previous schema, quietly upgrade them
				if ( $this->type == -1 ) {
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
		}

		# Unconditionally set loaded=true, we don't want the accessors constantly rechecking
		$this->dataLoaded = true;
	}

	/*
	 * Load image metadata from a DB result row
	 */
	function loadFromRow( &$row ) {
		$this->size = $row->img_size;
		$this->width = $row->img_width;
		$this->height = $row->img_height;
		$this->bits = $row->img_bits;
		$this->type = $row->img_type;
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
		$this->loadFromFile();
		$dbw =& wfGetDB( DB_MASTER );

		if ( $this->fromSharedDirectory ) {
			if ( !$wgSharedUploadDBname ) {
				return;
			}

			// Write to the other DB using selectDB, not database selectors
			// This avoids breaking replication in MySQL
			$dbw->selectDB( $wgSharedUploadDBname );
		}
		$dbw->update( '`image`', 
			array( 
				'img_width' => $this->width,
				'img_height' => $this->height,
				'img_bits' => $this->bits,
				'img_type' => $this->type,
			), array( 'img_name' => $this->name ), $fname
		);
		if ( $this->fromSharedDirectory ) {
			$dbw->selectDB( $wgDBname );
		}
	}
				
	/**
	 * Return the name of this image
	 * @access public
	 */
	function getName() {
		return $this->name;
	}

	/**
	 * Return the associated title object
	 * @access public
	 */
	function getTitle() {
		return $this->title;
	}

	/**
	 * Return the URL of the image file
	 * @access public
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
		if( $this->mustRender() ) {
			return $this->createThumb( $this->getWidth() );
		} else {
			return $this->getURL();
		}
	}

	/**
	 * Return the image path of the image in the
	 * local file system as an absolute path
	 * @access public
	 */
	function getImagePath() {
		$this->load();
		return $this->imagePath;
	}

	/**
	 * Return the width of the image
	 *
	 * Returns -1 if the file specified is not a known image type
	 * @access public
	 */
	function getWidth() {
		$this->load();
		return $this->width;
	}

	/**
	 * Return the height of the image
	 *
	 * Returns -1 if the file specified is not a known image type
	 * @access public
	 */
	function getHeight() {
		$this->load();
		return $this->height;
	}

	/**
	 * Return the size of the image file, in bytes
	 * @access public
	 */
	function getSize() {
		$this->load();
		return $this->size;
	}

	/**
	 * Return the type of the image
	 *
	 * -  1 GIF
	 * -  2 JPG
	 * -  3 PNG
	 * - 15 WBMP
	 * - 16 XBM
	 */
	function getType() {
		$this->load();
		return $this->type;
	}

	/**
	 * Return the escapeLocalURL of this image
	 * @access public
	 */
	function getEscapeLocalURL() {
		$this->getTitle();
		return $this->title->escapeLocalURL();
	}

	/**
	 * Return the escapeFullURL of this image
	 * @access public
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
	 * @access public
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
	 *
	 * @access public
	 */
	function exists() {
		$this->load();
		return $this->fileExists;
	}

	/**
	 *
	 * @access private
	 */
	function thumbUrl( $width, $subdir='thumb') {
		global $wgUploadPath, $wgUploadBaseUrl,
		       $wgSharedUploadPath,$wgSharedUploadDirectory,
			   $wgSharedThumbnailScriptPath, $wgThumbnailScriptPath;

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
		} else {  
			$name = $this->thumbName( $width );		
			if($this->fromSharedDirectory) {
				$base = '';
				$path = $wgSharedUploadPath;
			} else {
				$base = $wgUploadBaseUrl;
				$path = $wgUploadPath;
			}
			$url = "{$base}{$path}/{$subdir}" . 
			wfGetHashPath($this->name, $this->fromSharedDirectory)
			. $this->name.'/'.$name;
			$url = wfUrlencode( $url );
		}
		return array( $script !== false, $url );
	}

	/**
	 * Return the file name of a thumbnail of the specified width
	 *
	 * @param integer $width	Width of the thumbnail image
	 * @param boolean $shared	Does the thumbnail come from the shared repository?
	 * @access private
	 */
	function thumbName( $width ) {
		$thumb = $width."px-".$this->name;
		if( $this->extension == 'svg' ) {
			# Rasterize SVG vector images to PNG
			$thumb .= '.png';
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
	 * @access public
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
	 * @access public
	 */
	function &getThumbnail( $width, $height=-1 ) {
		if ( $height == -1 ) {
			return $this->renderThumb( $width );
		}
		$this->load();
		if ( $width < $this->width ) {
			$thumbheight = $this->height * $width / $this->width;
			$thumbwidth = $width;
		} else {
			$thumbheight = $this->height;
			$thumbwidth = $this->width;
		}
		if ( $thumbheight > $height ) {
			$thumbwidth = $thumbwidth * $height / $thumbheight;
			$thumbheight = $height;
		}
		$thumb = $this->renderThumb( $thumbwidth );
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
			$path = '/common/images/' . $icon;
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
	 * @access private
	 */
	function /* private */ renderThumb( $width, $useScript = true ) {
		global $wgUseSquid, $wgInternalServer;
		global $wgThumbnailScriptPath, $wgSharedThumbnailScriptPath;
		
		$width = IntVal( $width );

		$this->load();
		if ( ! $this->exists() )
		{
			# If there is no image, there will be no thumbnail
			return null;
		}
		
		# Sanity check $width
		if( $width <= 0 ) {
			# BZZZT
			return null;
		}

		if( $width > $this->width && !$this->mustRender() ) {
			# Don't make an image bigger than the source
			return new ThumbnailImage( $this->getViewURL(), $this->getWidth(), $this->getHeight() );
		}
		
		$height = floor( $this->height * ( $width/$this->width ) );
		
		list( $isScriptUrl, $url ) = $this->thumbUrl( $width );
		if ( $isScriptUrl && $useScript ) {
			// Use thumb.php to render the image
			return new ThumbnailImage( $url, $width, $height );
		}

		$thumbName = $this->thumbName( $width, $this->fromSharedDirectory );
		$thumbPath = wfImageThumbDir( $this->name, $this->fromSharedDirectory ).'/'.$thumbName;

		if ( !file_exists( $thumbPath ) ) {
			$oldThumbPath = wfDeprecatedThumbDir( $thumbName, 'thumb', $this->fromSharedDirectory ).
				'/'.$thumbName;
			$done = false;
			if ( file_exists( $oldThumbPath ) ) {
				if ( filemtime($oldThumbPath) >= filemtime($this->imagePath) ) {
					rename( $oldThumbPath, $thumbPath );
					$done = true;
				} else {
					unlink( $oldThumbPath );
				}
			}
			if ( !$done ) {
				$this->reallyRenderThumb( $thumbPath, $width, $height );

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
		return new ThumbnailImage( $url, $width, $height, $thumbPath );
	} // END OF function renderThumb

	/**
	 * Really render a thumbnail
	 *
	 * @access private
	 */
	function /*private*/ reallyRenderThumb( $thumbPath, $width, $height ) {
		global $wgSVGConverters, $wgSVGConverter,
			$wgUseImageMagick, $wgImageMagickConvertCommand;
		
		$this->load();
		
		if( $this->extension == 'svg' ) {
			global $wgSVGConverters, $wgSVGConverter;
			if( isset( $wgSVGConverters[$wgSVGConverter] ) ) {
				global $wgSVGConverterPath;
				$cmd = str_replace(
					array( '$path/', '$width', '$input', '$output' ),
					array( $wgSVGConverterPath,
						   $width,
						   escapeshellarg( $this->imagePath ),
						   escapeshellarg( $thumbPath ) ),
					$wgSVGConverters[$wgSVGConverter] );
				$conv = shell_exec( $cmd );
			} else {
				$conv = false;
			}
		} elseif ( $wgUseImageMagick ) {
			# use ImageMagick
			# Specify white background color, will be used for transparent images
			# in Internet Explorer/Windows instead of default black.
			$cmd  =  $wgImageMagickConvertCommand .
				" -quality 85 -background white -geometry {$width} ".
				escapeshellarg($this->imagePath) . " " .
				escapeshellarg($thumbPath);				
			$conv = shell_exec( $cmd );
		} else {
			# Use PHP's builtin GD library functions.
			#
			# First find out what kind of file this is, and select the correct
			# input routine for this.

			$truecolor = false;
			
			switch( $this->type ) {
				case 1: # GIF
					$src_image = imagecreatefromgif( $this->imagePath );
					break;
				case 2: # JPG
					$src_image = imagecreatefromjpeg( $this->imagePath );
					$truecolor = true;
					break;
				case 3: # PNG
					$src_image = imagecreatefrompng( $this->imagePath );
					$truecolor = ( $this->bits > 8 );
					break;
				case 15: # WBMP for WML
					$src_image = imagecreatefromwbmp( $this->imagePath );
					break;
				case 16: # XBM
					$src_image = imagecreatefromxbm( $this->imagePath );
					break;
				default:
					return 'Image type not supported';
					break;
			}
			if ( $truecolor ) {
				$dst_image = imagecreatetruecolor( $width, $height );
			} else {
				$dst_image = imagecreate( $width, $height );
			}
			imagecopyresampled( $dst_image, $src_image, 
						0,0,0,0,
						$width, $height, $this->width, $this->height );
			switch( $this->type ) {
				case 1:  # GIF
				case 3:  # PNG
				case 15: # WBMP
				case 16: # XBM
					imagepng( $dst_image, $thumbPath );
					break;
				case 2:  # JPEG
					imageinterlace( $dst_image );
					imagejpeg( $dst_image, $thumbPath, 95 );
					break;
				default:
					break;
			}
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
			}
		}
	}

	/** 
	 * Get all thumbnail names previously generated for this image
	 */
	function getThumbnails( $shared = false ) {
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
		
		return $files;
	}

	/**
	 * Delete all previously generated thumbnails, refresh metadata in memcached and purge the squid
	 */
	function purgeCache( $archiveFiles = array(), $shared = false ) {
		global $wgInternalServer, $wgUseSquid;

		// Refresh metadata cache
		$this->loadFromFile();
		$this->saveToCache();

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

	/**
	 * Return the image history of this image, line by line.
	 * starts with current version, then old versions.
	 * uses $this->historyLine to check which line to return:
	 *  0      return line for current version
	 *  1      query for old versions, return first one
	 *  2, ... return next old version from above query
	 *
	 * @access public
	 */
	function nextHistoryLine() {
		$fname = 'Image::nextHistoryLine()';
		$dbr =& wfGetDB( DB_SLAVE );
		if ( $this->historyLine == 0 ) {// called for the first time, return line from cur 
			$this->historyRes = $dbr->select( 'image', 
				array( 'img_size','img_description','img_user','img_user_text','img_timestamp', "'' AS oi_archive_name" ), 
				array( 'img_name' => $this->title->getDBkey() ),
				$fname
			);
			if ( 0 == wfNumRows( $this->historyRes ) ) { 
				return FALSE; 
			}
		} else if ( $this->historyLine == 1 ) {
			$this->historyRes = $dbr->select( 'oldimage', 
				array( 'oi_size AS img_size', 'oi_description AS img_description', 'oi_user AS img_user',
					'oi_user_text AS img_user_text', 'oi_timestamp AS img_timestamp', 'oi_archive_name'
				), array( 'oi_name' => $this->title->getDBkey() ), $fname, array( 'ORDER BY' => 'oi_timestamp DESC' ) 
			);
		}
		$this->historyLine ++;

		return $dbr->fetchObject( $this->historyRes );
	}

	/**
	 * Reset the history pointer to the first element of the history
	 * @access public
	 */
	function resetHistory() {
		$this->historyLine = 0;
	}

	/**
	 * Return true if the file is of a type that can't be directly
	 * rendered by typical browsers and needs to be re-rasterized.
	 * @return bool
	 */
	function mustRender() {
		$this->load();
		return ( $this->extension == 'svg' );
	}
	
	/**
	* Return the full filesystem path to the file. Note that this does
	* not mean that a file actually exists under that location.
	*
	* This path depends on whether directory hashing is active or not,
	* i.e. whether the images are all found in the same directory,
	* or in hashed paths like /images/3/3c.
	*
	* @access public
	* @param boolean $fromSharedDirectory Return the path to the file
	*   in a shared repository (see $wgUseSharedRepository and related
	*   options in DefaultSettings.php) instead of a local one.
	* 
	*/
	function getFullPath( $fromSharedRepository = false ) {
		global $wgUploadDirectory, $wgSharedUploadDirectory;
		global $wgHashedUploadDirectory, $wgHashedSharedUploadDirectory;
		
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
	function isKnownImageExtension( $ext ) {
		static $extensions = array( 'svg', 'png', 'jpg', 'jpeg', 'gif', 'bmp', 'xbm' );
		return in_array( $ext, $extensions );
	}

	/**
	 * Record an image upload in the upload log and the image table
	 */
	function recordUpload( $oldver, $desc, $copyStatus = '', $source = '' ) {
		global $wgUser, $wgLang, $wgTitle, $wgOut, $wgDeferredUpdateList;
		global $wgUseCopyrightUpload;

		$fname = 'Image::recordUpload';
		$dbw =& wfGetDB( DB_MASTER );

		# img_name must be unique
		if ( !$dbw->indexUnique( 'image', 'img_name' ) && !$dbw->indexExists('image','PRIMARY') ) {
			wfDebugDieBacktrace( 'Database schema not up to date, please run maintenance/archives/patch-image_name_unique.sql' );
		}

		// Delete thumbnails and refresh the cache
		$this->purgeCache();

		// Fail now if the image isn't there
		if ( !$this->fileExists || $this->fromSharedDirectory ) {
			return false;
		}

		if ( $wgUseCopyrightUpload ) {
			$textdesc = '== ' . wfMsg ( 'filedesc' ) . " ==\n" . $desc . "\n" .
			  '== ' . wfMsg ( 'filestatus' ) . " ==\n" . $copyStatus . "\n" .
			  '== ' . wfMsg ( 'filesource' ) . " ==\n" . $source ;
		} else {
			$textdesc = $desc;
		}

		$now = wfTimestampNow();

		# Test to see if the row exists using INSERT IGNORE
		# This avoids race conditions by locking the row until the commit, and also
		# doesn't deadlock. SELECT FOR UPDATE causes a deadlock for every race condition.
		$dbw->insert( 'image',
			array(
				'img_name' => $this->name,
				'img_size'=> $this->size,
				'img_width' => $this->width,
				'img_height' => $this->height,
				'img_bits' => $this->bits,
				'img_type' => $this->type,
				'img_timestamp' => $dbw->timestamp($now),
				'img_description' => $desc,
				'img_user' => $wgUser->getID(),
				'img_user_text' => $wgUser->getName(),
			), $fname, 'IGNORE' 
		);
		$descTitle = $this->getTitle();

		if ( $dbw->affectedRows() ) {
			# Successfully inserted, this is a new image
			$id = $descTitle->getArticleID();

			if ( $id == 0 ) {
				$article = new Article( $descTitle );
				$article->insertNewArticle( $textdesc, $desc, false, false, true );
			}
		} else {
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
					'oi_type' => 'img_type',
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
					'img_width' => $this->width,
					'img_height' => $this->height,
					'img_bits' => $this->bits,
					'img_type' => $this->type,
					'img_timestamp' => $dbw->timestamp(),
					'img_user' => $wgUser->getID(),
					'img_user_text' => $wgUser->getName(),
					'img_description' => $desc,
				), array( /* WHERE */
					'img_name' => $this->name
				), $fname
			);
			
			# Invalidate the cache for the description page
			$descTitle->invalidateCache();
		}

		$log = new LogPage( 'upload' );
		$log->addEntry( 'upload', $descTitle, $desc );

		return true;
	}
	
} //class


/**
 * Returns the image directory of an image
 * If the directory does not exist, it is created.
 * The result is an absolute path.
 *
 * This function is called from thumb.php before Setup.php is included
 * 
 * @param string $fname		file name of the image file
 * @access public
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
 * @param string $fname		file name of the original image file
 * @param string $subdir	(optional) subdirectory of the image upload directory that should be used for storing the thumbnail. Default is 'thumb'
 * @param boolean $shared	(optional) use the shared upload directory
 * @access public
 */
function wfImageThumbDir( $fname, $shared = false ) {
	$base = wfImageArchiveDir( $fname, 'thumb', $shared );
	$dir =  "$base/$fname";

	if ( !is_dir( $base ) ) {
		$oldumask = umask(0);
		@mkdir( $base, 0777 ); 
		umask( $oldumask );
	}

	if ( ! is_dir( $dir ) ) { 
		$oldumask = umask(0);
		@mkdir( $dir, 0777 ); 
		umask( $oldumask );
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
 * @param string $fname		file name of the thumbnail file, including file size prefix
 * @param string $subdir	(optional) subdirectory of the image upload directory that should be used for storing the old version. Default is 'archive'
 * @param boolean $shared	(optional) use the shared upload directory (only relevant for other functions which call this one)
 * @access public
 */
function wfImageArchiveDir( $fname , $subdir='archive', $shared=false ) {
	global $wgUploadDirectory, $wgHashedUploadDirectory,
	       $wgSharedUploadDirectory, $wgHashedSharedUploadDirectory;
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
	global $wgHashedSharedUploadDirectory, $wgSharedUploadDirectory;
	global $wgHashedUploadDirectory;
	
	$ishashed = $fromSharedDirectory ? $wgHashedSharedUploadDirectory :
	                                   $wgHashedUploadDirectory;
	if($ishashed) {
		$hash = md5($dbkey);
		return '/' . $hash{0} . '/' . substr( $hash, 0, 2 ) . '/';
	} else {
		return '/';
	}
}

/**
 * Returns the image URL of an image's old version
 * 
 * @param string $fname		file name of the image file
 * @param string $subdir	(optional) subdirectory of the image upload directory that is used by the old version. Default is 'archive'
 * @access public
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
 * @param string $length
 * @return int Length in pixels
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
	if( preg_match( '/^(\d+)(em|ex|px|pt|pc|cm|mm|in|%|)$/', $length, $matches ) ) {
		$length = FloatVal( $matches[1] );
		$unit = $matches[2];
		return round( $length * $unitLength[$unit] );
	} else {
		// Assume pixels
		return round( FloatVal( $length ) );
	}
}

/**
 * Compatible with PHP getimagesize()
 * @todo support gzipped SVGZ
 * @todo check XML more carefully
 * @todo sensible defaults
 *
 * @param string $filename
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
 * Is an image on the bad image list?
 */
function wfIsBadImage( $name ) {
	global $wgLang;

	$lines = explode("\n", wfMsgForContent( 'bad_image_list' ));
	foreach ( $lines as $line ) {
		if ( preg_match( '/^\*\s*\[\[:(' . $wgLang->getNsText( NS_IMAGE ) . ':.*(?=]]))\]\]/', $line, $m ) ) {
			$t = Title::newFromText( $m[1] );
			if ( $t->getDBkey() == $name ) {
				return true;
			}
		}
	}
	return false;
}
	


/**
 * Wrapper class for thumbnail images
 * @package MediaWiki
 */
class ThumbnailImage {
	/**
	 * @param string $path Filesystem path to the thumb
	 * @param string $url URL path to the thumb
	 * @access private
	 */
	function ThumbnailImage( $url, $width, $height, $path = false ) {
		$this->url = $url;
		$this->width = $width;
		$this->height = $height;
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
	 * @access public
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
