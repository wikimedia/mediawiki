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
	var	$name,		# name of the image
		$imagePath,	# Path of the image
		$url,		# Image URL
		$title,		# Title object for this image. Initialized when needed.
		$fileExists,	# does the image file exist on disk?
		$fromSharedDirectory, # load this image from $wgSharedUploadDirectory
		$historyLine,	# Number of line to return by nextHistoryLine()
		$historyRes,	# result of the query for the image's history
		$width,		# \
		$height,	#  |
		$bits,		#   --- returned by getimagesize, see http://de3.php.net/manual/en/function.getimagesize.php
		$type,		#  |
		$attr;		# /

	/**#@-*/


	/**
	 * Create an Image object from an image name
	 *
	 * @param string $name name of the image, used to create a title object using Title::makeTitleSafe
	 * @access public
	 */
	function Image( $name )
	{

		global $wgUseSharedUploads, $wgUseLatin1, $wgSharedLatin1, $wgLang;

		$this->name      = $name;
		$this->title     = Title::makeTitleSafe( NS_IMAGE, $this->name );
		$this->fromSharedDirectory = false;
		$this->imagePath = $this->getFullPath();
		$this->fileExists = file_exists( $this->imagePath);		
		
		# If the file is not found, and a shared upload directory 
		# like the Wikimedia Commons is used, look for it there.
		if (!$this->fileExists && $wgUseSharedUploads) {			
			
			# In case we're on a wgCapitalLinks=false wiki, we 
			# capitalize the first letter of the filename before 
			# looking it up in the shared repository.
			$this->name= $wgLang->ucfirst($name);
			
			# Encode the filename if we're on a Latin1 wiki and the
			# shared repository is UTF-8
			if($wgUseLatin1 && !$wgSharedLatin1) {
				$this->name  = utf8_encode($name);
			}
			
			$this->imagePath = $this->getFullPath(true);
			$this->fileExists = file_exists( $this->imagePath);
			$this->fromSharedDirectory = true;
			$name=$this->name;
			
		}
		if($this->fileExists) {			
			$this->url = $this->wfImageUrl( $this->name, $this->fromSharedDirectory );
		} else {
			$this->url='';
		}
		
		$n = strrpos( $name, '.' );
		$this->extension = strtolower( $n ? substr( $name, $n + 1 ) : '' );
				

		if ( $this->fileExists )
		{
			if( $this->extension == 'svg' ) {
				@$gis = getSVGsize( $this->imagePath );
			} else {
				@$gis = getimagesize( $this->imagePath );
			}
			if( $gis !== false ) {
				$this->width = $gis[0];
				$this->height = $gis[1];
				$this->type = $gis[2];
				$this->attr = $gis[3];
				if ( isset( $gis['bits'] ) )  {
					$this->bits = $gis['bits'];
				} else {
					$this->bits = 0;
				}
			}
		}
		$this->historyLine = 0;				
	}

	/**
	 * Factory function
	 *
	 * Create a new image object from a title object.
	 *
	 * @param Title $nt Title object. Must be from namespace "image"
	 * @access public
	 */
	function newFromTitle( $nt )
	{
		$img = new Image( $nt->getDBKey() );
		$img->title = $nt;
		return $img;
	}

	/**
	 * Return the name of this image
	 * @access public
	 */
	function getName()
	{
		return $this->name;
	}

	/**
	 * Return the associated title object
	 * @access public
	 */
	function getTitle()
	{
		return $this->title;
	}

	/**
	 * Return the URL of the image file
	 * @access public
	 */
	function getURL()
	{
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
	function getImagePath()
	{
		return $this->imagePath;
	}

	/**
	 * Return the width of the image
	 *
	 * Returns -1 if the file specified is not a known image type
	 * @access public
	 */
	function getWidth()
	{
		return $this->width;
	}

	/**
	 * Return the height of the image
	 *
	 * Returns -1 if the file specified is not a known image type
	 * @access public
	 */
	function getHeight()
	{
		return $this->height;
	}

	/**
	 * Return the size of the image file, in bytes
	 * @access public
	 */
	function getSize()
	{
		$st = stat( $this->getImagePath() );
		if( $st ) {
			return $st['size'];
		} else {
			return false;
		}
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
	function getType()
	{
		return $this->type;
	}

	/**
	 * Return the escapeLocalURL of this image
	 * @access public
	 */
	function getEscapeLocalURL()
	{
		return $this->title->escapeLocalURL();
	}

	/**
	 * Return the escapeFullURL of this image
	 * @access public
	 */
	function getEscapeFullURL()
	{
		return $this->title->escapeFullURL();
	}

	/**
	 * Return the URL of an image, provided its name.
	 *
	 * @param string $name	Name of the image, without the leading "Image:"
	 * @param boolean $fromSharedDirectory	Should this be in $wgSharedUploadPath?	 
	 * @access public
	 */
	function wfImageUrl( $name, $fromSharedDirectory = false )
	{
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
	 * Returns true iff the image file exists on disk.
	 *
	 * @access public
	 */
	function exists()
	{
		return $this->fileExists;
	}

	/**
	 *
	 * @access private
	 */
	function thumbUrl( $width, $subdir='thumb') {
		global $wgUploadPath, $wgUploadBaseUrl,
		       $wgSharedUploadPath,$wgSharedUploadDirectory,
		       $wgUseLatin1,$wgSharedLatin1;
		$name = $this->thumbName( $width );		
		if($this->fromSharedDirectory) {
			$base = '';
			$path = $wgSharedUploadPath;
			if($wgUseLatin1 && !$wgSharedLatin1) {
				$name=utf8_encode($name);
			}			
		} else {
			$base = $wgUploadBaseUrl;
			$path = $wgUploadPath;
		}
		$url = "{$base}{$path}/{$subdir}" . 
		wfGetHashPath($name, $this->fromSharedDirectory)
		. "{$name}";
		return wfUrlencode($url);
	}

	/**
	 * Return the file name of a thumbnail of the specified width
	 *
	 * @param integer $width	Width of the thumbnail image
	 * @param boolean $shared	Does the thumbnail come from the shared repository?
	 * @access private
	 */
	function thumbName( $width, $shared=false ) {
		global $wgUseLatin1,$wgSharedLatin1;
		$thumb = $width."px-".$this->name;
		if( $this->extension == 'svg' ) {
			# Rasterize SVG vector images to PNG
			$thumb .= '.png';
		}
		if( $shared && $wgUseLatin1 && !$wgSharedLatin1) { 
			$thumb=utf8_encode($thumb); 
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
				return new ThumbnailImage( $filepath, $wgStylePath . $path );
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
	function /* private */ renderThumb( $width ) {
		global $wgImageMagickConvertCommand;
		global $wgUseImageMagick;
		global $wgUseSquid, $wgInternalServer;

		$width = IntVal( $width );

		$thumbName = $this->thumbName( $width, $this->fromSharedDirectory );
		$thumbPath = wfImageThumbDir( $thumbName, 'thumb', $this->fromSharedDirectory ).'/'.$thumbName;
		$thumbUrl  = $this->thumbUrl( $width );
		#wfDebug ( "Render name: $thumbName path: $thumbPath url: $thumbUrl\n");
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
			return new ThumbnailImage( $this->getImagePath(), $this->getViewURL() );
		}

		if ( (! file_exists( $thumbPath ) ) || ( filemtime($thumbPath) < filemtime($this->imagePath) ) ) {
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
				$height = floor( $this->height * ( $width/$this->width ) );
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
						#$thumbUrl .= ".png";
						#$thumbPath .= ".png";
						imagepng( $dst_image, $thumbPath );
						break;
					case 2:  # JPEG
						#$thumbUrl .= ".jpg";
						#$thumbPath .= ".jpg";
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
			if( file_exists( $thumbstat ) ) {
				$thumbstat = stat( $thumbPath );
				if( $thumbstat['size'] == 0 ) {
					unlink( $thumbPath );
				}
			}

			# Purge squid
			# This has to be done after the image is updated and present for all machines on NFS, 
			# or else the old version might be stored into the squid again
			if ( $wgUseSquid ) {
				$urlArr = Array(
					$wgInternalServer.$thumbUrl
				);
				wfPurgeSquidServers($urlArr);
			}
		}
		return new ThumbnailImage( $thumbPath, $thumbUrl );
	} // END OF function createThumb

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
	function nextHistoryLine()
	{
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
	function resetHistory()
	{
		$this->historyLine = 0;
	}

	/**
	 * Return true if the file is of a type that can't be directly
	 * rendered by typical browsers and needs to be re-rasterized.
	 * @return bool
	 */
	function mustRender() {
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
	function getFullPath( $fromSharedRepository = false )
	{
		global $wgUploadDirectory, $wgSharedUploadDirectory;
		global $wgHashedUploadDirectory, $wgHashedSharedUploadDirectory;
		
		$dir      = $fromSharedRepository ? $wgSharedUploadDirectory :
		                                    $wgUploadDirectory;
		$ishashed = $fromSharedRepository ? $wgHashedSharedUploadDirectory : 
		                                    $wgHashedUploadDirectory;
		$name     = $this->name;							
		$fullpath = $dir . wfGetHashPath($name) . $name;		
		return $fullpath;
	}
	
	
} //class


/**
 * Returns the image directory of an image
 * If the directory does not exist, it is created.
 * The result is an absolute path.
 * 
 * @param string $fname		file name of the image file
 * @access public
 */
function wfImageDir( $fname )
{
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
 * @param string $fname		file name of the thumbnail file, including file size prefix
 * @param string $subdir	(optional) subdirectory of the image upload directory that should be used for storing the thumbnail. Default is 'thumb'
 * @param boolean $shared	(optional) use the shared upload directory
 * @access public
 */
function wfImageThumbDir( $fname , $subdir='thumb', $shared=false)
{
	return wfImageArchiveDir( $fname, $subdir, $shared );
}

/**
 * Returns the image directory of an image's old version
 * If the directory does not exist, it is created.
 * The result is an absolute path.
 * 
 * @param string $fname		file name of the thumbnail file, including file size prefix
 * @param string $subdir	(optional) subdirectory of the image upload directory that should be used for storing the old version. Default is 'archive'
 * @param boolean $shared	(optional) use the shared upload directory (only relevant for other functions which call this one)
 * @access public
 */
function wfImageArchiveDir( $fname , $subdir='archive', $shared=false )
{
	global $wgUploadDirectory, $wgHashedUploadDirectory,
	       $wgSharedUploadDirectory, $wgHashedSharedUploadDirectory;
	$dir = $shared ? $wgSharedUploadDirectory : $wgUploadDirectory;
	$hashdir = $shared ? $wgHashedSharedUploadDirectory : $wgHashedUploadDirectory;	
	if (!$hashdir) { return $dir.'/'.$subdir; }
	$hash = md5( $fname );
	$oldumask = umask(0);
	# Suppress warning messages here; if the file itself can't
	# be written we'll worry about it then.
	$archive = $dir.'/'.$subdir;
	if ( ! is_dir( $archive ) ) { @mkdir( $archive, 0777 ); }
	$archive .= '/' . $hash{0};
	if ( ! is_dir( $archive ) ) { @mkdir( $archive, 0777 ); }
	$archive .= '/' . substr( $hash, 0, 2 );
	if ( ! is_dir( $archive ) ) { @mkdir( $archive, 0777 ); }

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
	
	$ishashed = $fromSharedDirectory ? $wgHashedSharedUploadDirectory :
	                                   $wgSharedUploadDirectory;
	if($ishashed) {
		$hash = md5($dbkey);
		return '/' . $hash{0} . '/' . substr( $hash, 0, 2 ) . "/";
	} else {
		return '/';
	}
}


/**
 * Record an image upload in the upload log.
 */
function wfRecordUpload( $name, $oldver, $size, $desc, $copyStatus = "", $source = "" )
{
	global $wgUser, $wgLang, $wgTitle, $wgOut, $wgDeferredUpdateList;
	global $wgUseCopyrightUpload;

	$fname = 'wfRecordUpload';
	$dbw =& wfGetDB( DB_MASTER );

	# img_name must be unique
	if ( !$dbw->indexUnique( 'image', 'img_name' ) && !$dbw->indexExists('image','PRIMARY') ) {
		wfDebugDieBacktrace( 'Database schema not up to date, please run maintenance/archives/patch-image_name_unique.sql' );
	}


	$now = wfTimestampNow();
	$won = wfInvertTimestamp( $now );
	$size = IntVal( $size );

	if ( $wgUseCopyrightUpload )
	  {
		$textdesc = '== ' . wfMsg ( 'filedesc' ) . " ==\n" . $desc . "\n" .
		  '== ' . wfMsg ( 'filestatus' ) . " ==\n" . $copyStatus . "\n" .
		  '== ' . wfMsg ( 'filesource' ) . " ==\n" . $source ;
	  }
	else $textdesc = $desc ;

	$now = wfTimestampNow();
	$won = wfInvertTimestamp( $now );

	# Test to see if the row exists using INSERT IGNORE
	# This avoids race conditions by locking the row until the commit, and also
	# doesn't deadlock. SELECT FOR UPDATE causes a deadlock for every race condition.
	$dbw->insert( 'image',
		array(
			'img_name' => $name,
			'img_size'=> $size,
			'img_timestamp' => $dbw->timestamp($now),
			'img_description' => $desc,
			'img_user' => $wgUser->getID(),
			'img_user_text' => $wgUser->getName(),
		), $fname, 'IGNORE' 
	);
	$descTitle = Title::makeTitleSafe( NS_IMAGE, $name );

	if ( $dbw->affectedRows() ) {
		# Successfully inserted, this is a new image
		$id = $descTitle->getArticleID();

		if ( $id == 0 ) {
			$now = wfTimestampNow();
			$won = wfInvertTimestamp( $now );

			$text_old_id = $dbw->nextSequenceValue( 'text_old_id_seq' );
			$dbw->insert( 'text',
				array(
					'old_id' => $text_old_id,
					'old_text' => $textdesc,
					'old_flags' => '' ),
				$fname );
			if ( is_null( $text_old_id ) ) $text_old_id = $dbw->insertId();

			$page_page_id = $dbw->nextSequenceValue( 'page_page_id' );
			$dbw->insert( 'page',
				array(
					'page_id'	 => $page_page_id,
					'page_namespace' => NS_IMAGE,
					'page_title'	 => $name,
					'page_restrictions' => '',
					'page_counter'	 => 0,
					'page_is_redirect' => 0,
					'page_is_new'	 => 1,
					'page_random'	 => 0.5,
					'page_touched'	 => $now,
					'page_latest'	 => $text_old_id ),
				$fname );
			if ( is_null( $page_page_id ) ) $page_page_id = $dbw->insertId();

			$dbw->insert( 'revision',
				array(
					'rev_id'	=> $text_old_id,
					'rev_page'	=> $page_page_id,
					'rev_comment'	=> $desc,
					'rev_user'	=> $wgUser->getID(),
					'rev_user_text'	=> $wgUser->getName(),
					'rev_timestamp'	=> $now,
					'inverse_timestamp' => $won,
					'rev_minor_edit' => 0 ),
				$fname );

			RecentChange::notifyNew( $now, $descTitle, 0, $wgUser, $desc );

			$u = new SearchUpdate( $page_page_id, $name, $desc );
			$u->doUpdate();
		}
	} else {
		# Collision, this is an update of an image
		# Get current image row for update
		$s = $dbw->selectRow( 'image', array( 'img_name','img_size','img_timestamp','img_description',
		  'img_user','img_user_text' ), array( 'img_name' => $name ), $fname, 'FOR UPDATE' );

		# Insert it into oldimage
		$dbw->insert( 'oldimage',
			array(
				'oi_name' => $s->img_name,
				'oi_archive_name' => $oldver,
				'oi_size' => $s->img_size,
				'oi_timestamp' => $dbw->timestamp($s->img_timestamp),
				'oi_description' => $s->img_description,
				'oi_user' => $s->img_user,
				'oi_user_text' => $s->img_user_text
			), $fname
		);

		# Update the current image row
		$dbw->update( 'image',
			array( /* SET */
				'img_size' => $size,
				'img_timestamp' => $dbw->timestamp(),
				'img_user' => $wgUser->getID(),
				'img_user_text' => $wgUser->getName(),
				'img_description' => $desc,
			), array( /* WHERE */
				'img_name' => $name
			), $fname
		);

		# Invalidate the cache for the description page
		$descTitle->invalidateCache();
	}

	$log = new LogPage( 'upload' );
	$log->addEntry( 'upload', $descTitle, $desc );
}

/**
 * Returns the image URL of an image's old version
 * 
 * @param string $fname		file name of the image file
 * @param string $subdir	(optional) subdirectory of the image upload directory that is used by the old version. Default is 'archive'
 * @access public
 */
function wfImageArchiveUrl( $name, $subdir='archive' )
{
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
function scaleSVGUnit( $length ) {
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
function getSVGsize( $filename ) {
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
		$width = scaleSVGUnit( trim( substr( $matches[1], 1, -1 ) ) );
	}
	if( preg_match( '/\bheight\s*=\s*("[^"]+"|\'[^\']+\')/s', $tag, $matches ) ) {
		$height = scaleSVGUnit( trim( substr( $matches[1], 1, -1 ) ) );
	}
	
	return array( $width, $height, 'SVG',
		"width=\"$width\" height=\"$height\"" );
}


/**
 * Wrapper class for thumbnail images
 */
class ThumbnailImage {
	/**
	 * @param string $path Filesystem path to the thumb
	 * @param string $url URL path to the thumb
	 * @access private
	 */
	function ThumbnailImage( $path, $url ) {
		$this->url = $url;
		$this->path = $path;
		$size = @getimagesize( $this->path );
		if( $size ) {
			$this->width = $size[0];
			$this->height = $size[1];
		} else {
			$this->width = 0;
			$this->height = 0;
		}
	}
	
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
