<?php
/**
 * @package MediaWiki
 * $Id$
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
		global $wgUploadDirectory,$wgHashedUploadDirectory;

		$this->name      = $name;
		$this->title     = Title::makeTitleSafe( NS_IMAGE, $this->name );
		//$this->imagePath = wfImagePath( $name );
		if ($wgHashedUploadDirectory) {
			$hash 		 = md5( $this->title->getDBkey() );
			$this->imagePath = $wgUploadDirectory . '/' . $hash{0} . '/' .
						substr( $hash, 0, 2 ) . "/{$name}";
		} else {
			$this->imagePath = $wgUploadDirectory . '/' . $name;
		}

		$this->url       = $this->wfImageUrl( $name );

		if ( $this->fileExists = file_exists( $this->imagePath ) ) // Sic!, "=" is intended
		{
			@$gis = getimagesize( $this->imagePath );
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
		return $st['size'];
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
	 * Return the URL of an image, provided its name.
	 *
	 * @param string $name	Name of the image, without the leading Image:
	 * @access public
	 */
	function wfImageUrl( $name )
	{
		global $wgUploadPath,$wgUploadBaseUrl,$wgHashedUploadDirectory;
		if ($wgHashedUploadDirectory) {
			$hash = md5( $name );
        		$url = "{$wgUploadBaseUrl}{$wgUploadPath}/" . $hash{0} . "/" .
              		substr( $hash, 0, 2 ) . "/{$name}";
		} else {
			$url = "{$wgUploadBaseUrl}{$wgUploadPath}/{$name}";
		}
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
	function thumbUrl( $width, $subdir='thumb' ) {
		global $wgUploadPath,$wgHashedUploadDirectory;
		$name = $this->thumbName( $width );
		if ($wgHashedUploadDirectory) {
			$hash = md5( $name );
			$url = "{$wgUploadPath}/{$subdir}/" . $hash{0} . "/" . 
				substr( $hash, 0, 2 ) . "/{$name}";
		} else {
			$url = "{$wgUploadPath}/{$subdir}/{$name}";
		}

		return wfUrlencode($url);
	}

	/**
	 * Return the file name of a thumbnail of the specified width
	 *
	 * @param integer $width	Width of the thumbnail image
	 * @access private
	 */
	function thumbName( $width ) {
		return $width."px-".$this->name;
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
		return $this->renderThumb( $thumbwidth );
	}
		
	/**
	 * Create a thumbnail of the image having the specified width.
	 * The thumbnail will not be created if the width is larger than the
	 * image's width. Let the browser do the scaling in this case.
	 * The thumbnail is stored on disk and is only computed if the thumbnail
	 * file does not exist OR if it is older than the image.
	 * Returns the URL.
	 *
	 * @access private
	 */
	function /* private */ renderThumb( $width ) {
		global $wgUploadDirectory;
		global $wgImageMagickConvertCommand;
		global $wgUseImageMagick;
		global $wgUseSquid, $wgInternalServer;

		$width = IntVal( $width );

		$thumbName = $this->thumbName( $width );
		$thumbPath = wfImageThumbDir( $thumbName ).'/'.$thumbName;
		$thumbUrl  = $this->thumbUrl( $width );

		if ( ! $this->exists() )
		{
			# If there is no image, there will be no thumbnail
			return '';
		}
		
		# Sanity check $width
		if( $width <= 0 ) {
			# BZZZT
			return '';
		}

		if( $width > $this->width ) {
			# Don't make an image bigger than the source
			return $this->getURL();
		}

		if ( (! file_exists( $thumbPath ) ) || ( filemtime($thumbPath) < filemtime($this->imagePath) ) ) {
			if ( $wgUseImageMagick ) {
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
			$thumbstat = stat( $thumbPath );
			if( $thumbstat['size'] == 0 )
			{
				unlink( $thumbPath );
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
		return $thumbUrl;
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
 * @access public
 */
function wfImageThumbDir( $fname , $subdir='thumb')
{
	return wfImageArchiveDir( $fname, $subdir );
}

/**
 * Returns the image directory of an image's old version
 * If the directory does not exist, it is created.
 * The result is an absolute path.
 * 
 * @param string $fname		file name of the thumbnail file, including file size prefix
 * @param string $subdir	(optional) subdirectory of the image upload directory that should be used for storing the old version. Default is 'archive'
 * @access public
 */
function wfImageArchiveDir( $fname , $subdir='archive')
{
	global $wgUploadDirectory, $wgHashedUploadDirectory;

	if (!$wgHashedUploadDirectory) { return $wgUploadDirectory.'/'.$subdir; }

	$hash = md5( $fname );
	$oldumask = umask(0);

	# Suppress warning messages here; if the file itself can't
	# be written we'll worry about it then.
	$archive = $wgUploadDirectory.'/'.$subdir;
	if ( ! is_dir( $archive ) ) { @mkdir( $archive, 0777 ); }
	$archive .= '/' . $hash{0};
	if ( ! is_dir( $archive ) ) { @mkdir( $archive, 0777 ); }
	$archive .= '/' . substr( $hash, 0, 2 );
	if ( ! is_dir( $archive ) ) { @mkdir( $archive, 0777 ); }

	umask( $oldumask );
	return $archive;
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
	if ( !$dbw->indexUnique( 'image', 'img_name' ) ) {
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
	$dbw->insertArray( 'image',
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
			$seqVal = $dbw->nextSequenceValue( 'cur_cur_id_seq' );
			$dbw->insertArray( 'cur',
				array(
					'cur_id' => $seqVal,
					'cur_namespace' => NS_IMAGE,
					'cur_title' => $name,
					'cur_comment' => $desc,
					'cur_user' => $wgUser->getID(),
					'cur_user_text' => $wgUser->getName(),
					'cur_timestamp' => $dbw->timestamp($now),
					'cur_is_new' => 1,
					'cur_text' => $textdesc,
					'inverse_timestamp' => $won,
					'cur_touched' => $dbw->timestamp($now)
				), $fname
			);
			$id = $dbw->insertId() or 0; # We should throw an error instead

			RecentChange::notifyNew( $now, $descTitle, 0, $wgUser, $desc );

			$u = new SearchUpdate( $id, $name, $desc );
			$u->doUpdate();
		}
	} else {
		# Collision, this is an update of an image
		# Get current image row for update
		$s = $dbw->getArray( 'image', array( 'img_name','img_size','img_timestamp','img_description',
		  'img_user','img_user_text' ), array( 'img_name' => $name ), $fname, 'FOR UPDATE' );

		# Insert it into oldimage
		$dbw->insertArray( 'oldimage',
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
		$dbw->updateArray( 'image',
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

?>
