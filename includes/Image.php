<?php
# Class to represent an image
# Provides methods to retrieve paths (physical, logical, URL),
# to generate thumbnails or for uploading.

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
		$height,	#  --- returned by getimagesize, see http://de3.php.net/manual/en/function.getimagesize.php
		$type,		#  |
		$attr;		# /



	function Image( $name )
	{
		global $wgUploadDirectory;

		$this->name      = $name;
		$this->title     = Title::makeTitle( Namespace::getImage(), $this->name );
		//$this->imagePath = wfImagePath( $name );
		$hash 		 = md5( $this->title->getDBkey() );
		$this->imagePath = $wgUploadDirectory . "/" . $hash{0} . "/" .substr( $hash, 0, 2 ) . "/{$name}";

		$this->url       = $this->wfImageUrl( $name );

		if ( $this->fileExists = file_exists( $this->imagePath ) ) // Sic!, "=" is intended
		{
			list($this->width, $this->height, $this->type, $this->attr) = getimagesize( $this->imagePath );
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

	function thumbUrl( $width, $subdir="thumb" ) {
		global $wgUploadPath;

		$name = $this->thumbName( $width );
		$hash = md5( $name );
		$url = "{$wgUploadPath}/{$subdir}/" . $hash{0} . "/" . substr( $hash, 0, 2 ) . "/{$name}";

		return wfUrlencode($url);
	}

	function thumbName( $width ) {
		return $width."px-".$this->name;
	}

	//**********************************************************************
	// Create a thumbnail of the image having the specified width.
	// The thumbnail will not be created if the width is larger than the
	// image's width. Let the browser do the scaling in this case.
	// The thumbnail is stored on disk and is only computed if the thumbnail
	// file does not exist OR if it is older than the image.
	function createThumb( $width ) {
		global $wgUploadDirectory;
		global $wgImageMagickConvertCommand;
		global $wgUseImageMagick;
		global $wgUseSquid, $wgInternalServer;

		$width = IntVal( $width );

		$thumbName = $this->thumbName( $width );
		$thumbPath = wfImageThumbDir( $thumbName )."/".$thumbName;
		$thumbUrl  = $this->thumbUrl( $width );

		if ( ! $this->exists() )
		{
			# If there is no image, there will be no thumbnail
			return "";
		}
		
		# Sanity check $width
		if( $width <= 0 ) {
			# BZZZT
			return "";
		}

		if( $width > $this->width ) {
			# Don't make an image bigger than the source
			return $this->getURL();
		}

		if (    (! file_exists( $thumbPath ) )
		     || ( filemtime($thumbPath) < filemtime($this->imagePath) ) ) {
			# Squid purging
			if ( $wgUseSquid ) {
				$urlArr = Array(
					$wgInternalServer.$thumbUrl
				);
				wfPurgeSquidServers($urlArr);
			}

			if ( $wgUseImageMagick ) {
				# use ImageMagick
				$cmd  =  $wgImageMagickConvertCommand .
					" -quality 85 -geometry {$width} ".
					escapeshellarg($this->imagePath) . " " .
					escapeshellarg($thumbPath);
				$conv = shell_exec( $cmd );
			} else {
				# Use PHP's builtin GD library functions.
				#
				# First find out what kind of file this is, and select the correct
				# input routine for this.
				
				switch( $this->type ) {
					case 1: # GIF
						$src_image = imagecreatefromgif( $this->imagePath );
						break;
					case 2: # JPG
						$src_image = imagecreatefromjpeg( $this->imagePath );
						break;
					case 3: # PNG
						$src_image = imagecreatefrompng( $this->imagePath );
						break;
					case 15: # WBMP for WML
						$src_image = imagecreatefromwbmp( $this->imagePath );
						break;
					case 16: # XBM
						$src_image = imagecreatefromxbm( $this->imagePath );
						break;
					default:
						return "Image type not supported";
						break;
				}
				$height = floor( $this->height * ( $width/$this->width ) );
				$dst_image = imagecreatetruecolor( $width, $height );
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
			if( $thumbstat["size"] == 0 )
			{
				unlink( $thumbPath );
			}

		}
		return $thumbUrl;
	} // END OF function createThumb

	//**********************************************************************
	// Return the image history of this image, line by line.
	// start with current version, than old versions.
	// use $this->historyLine to check which line to return:
	//  0      return line for current version
	//  1      query for old versions, return first one
	//  2, ... return next old version from above query
	function nextHistoryLine()
	{
		$fname = "Image::nextHistoryLine()";
		
		if ( $this->historyLine == 0 ) // called for the first time, return line from cur
		{ 
			$sql = "SELECT img_size,img_description,img_user," .
			  "img_user_text,img_timestamp, '' AS oi_archive_name FROM image WHERE " .
			  "img_name='" . wfStrencode( $this->title->getDBkey() ) . "'";
			$this->historyRes = wfQuery( $sql, DB_READ, $fname );

			if ( 0 == wfNumRows( $this->historyRes ) ) { return FALSE; }

		} else if ( $this->historyLine == 1 )
		{
			$sql = "SELECT oi_size AS img_size, oi_description AS img_description," .
			  "oi_user AS img_user," .
			  "oi_user_text AS img_user_text, oi_timestamp AS img_timestamp , oi_archive_name FROM oldimage WHERE " .
			  "oi_name='" . wfStrencode( $this->title->getDBkey() ) . "' " .
			  "ORDER BY oi_timestamp DESC";
			$this->historyRes = wfQuery( $sql, DB_READ, $fname );
		}
		$this->historyLine ++;

		return wfFetchObject( $this->historyRes );
	}

	function resetHistory()
	{
		$this->historyLine = 0;
	}


} //class

