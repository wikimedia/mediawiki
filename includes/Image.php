<?php
/**
 *
 */

/**
 * Class to represent an image
 * 
 * Provides methods to retrieve paths (physical, logical, URL),
 * to generate thumbnails or for uploading.
 */
class Image
{
	/* private */
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



	function Image( $name )
	{
		global $wgUploadDirectory;

		$this->name      = $name;
		$this->title     = Title::makeTitleSafe( NS_IMAGE, $this->name );
		//$this->imagePath = wfImagePath( $name );
		$hash 		 = md5( $this->title->getDBkey() );
		$this->imagePath = $wgUploadDirectory . '/' . $hash{0} . '/' .substr( $hash, 0, 2 ) . "/{$name}";

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

	function newFromTitle( $nt )
	{
		$img = new Image( $nt->getDBKey() );
		$img->title = $nt;
		return $img;
	}

	function getName()
	{
		return $this->name;
	}

	function getURL()
	{
		return $this->url;
	}

	function getImagePath()
	{
		return $this->imagePath;
	}

	function getWidth()
	{
		return $this->width;
	}

	function getHeight()
	{
		return $this->height;
	}

	function getType()
	{
		return $this->type;
	}

	function getEscapeLocalURL()
	{
		return $this->title->escapeLocalURL();
	}

	function wfImageUrl( $name )
	{
		global $wgUploadPath;
		$hash = md5( $name );

        	$url = "{$wgUploadPath}/" . $hash{0} . "/" .
              	substr( $hash, 0, 2 ) . "/{$name}";
        	return wfUrlencode( $url );
	}


	function exists()
	{
		return $this->fileExists;
	}

	function thumbUrl( $width, $subdir='thumb' ) {
		global $wgUploadPath;

		$name = $this->thumbName( $width );
		$hash = md5( $name );
		$url = "{$wgUploadPath}/{$subdir}/" . $hash{0} . "/" . substr( $hash, 0, 2 ) . "/{$name}";

		return wfUrlencode($url);
	}

	function thumbName( $width ) {
		return $width."px-".$this->name;
	}

	/**
	 * Create a thumbnail of the image having the specified width.
	 * The thumbnail will not be created if the width is larger than the
	 * image's width. Let the browser do the scaling in this case.
	 * The thumbnail is stored on disk and is only computed if the thumbnail
	 * file does not exist OR if it is older than the image.
	 * Returns the URL.
	 */
	function createThumb( $width ) {
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

	function resetHistory()
	{
		$this->historyLine = 0;
	}


} //class


function wfImageDir( $fname )
{
	global $wgUploadDirectory;

	$hash = md5( $fname );
	$oldumask = umask(0);
	$dest = $wgUploadDirectory . '/' . $hash{0};
	if ( ! is_dir( $dest ) ) { mkdir( $dest, 0777 ); }
	$dest .= '/' . substr( $hash, 0, 2 );
	if ( ! is_dir( $dest ) ) { mkdir( $dest, 0777 ); }

	umask( $oldumask );
	return $dest;
}

function wfImageThumbDir( $fname , $subdir='thumb')
{
	return wfImageArchiveDir( $fname, $subdir );
}

function wfImageArchiveDir( $fname , $subdir='archive')
{
	global $wgUploadDirectory;

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
	$dbw->insert( 'image',
		array(
			'img_name' => $name,
			'img_size'=> $size,
			'img_timestamp' => $now,
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
					'cur_timestamp' => $now,
					'cur_is_new' => 1,
					'cur_text' => $textdesc,
					'inverse_timestamp' => $won,
					'cur_touched' => $now
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
				'oi_timestamp' => $s->img_timestamp,
				'oi_description' => $s->img_description,
				'oi_user' => $s->img_user,
				'oi_user_text' => $s->img_user_text
			), $fname
		);

		# Update the current image row
		$dbw->updateArray( 'image',
			array( /* SET */
				'img_size' => $size,
				'img_timestamp' => wfTimestampNow(),
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

function wfImageArchiveUrl( $name )
{
	global $wgUploadPath;

	$hash = md5( substr( $name, 15) );
	$url = $wgUploadPath.'/archive/' . $hash{0} . '/' .
	  substr( $hash, 0, 2 ) . '/'.$name;
	return wfUrlencode($url);
}

?>
