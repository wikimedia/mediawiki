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
		$fromSharedDirectory, # load this image from $wgSharedUploadDirectory	
		$historyLine,	# Number of line to return by nextHistoryLine()
		$historyRes,	# result of the query for the image's history
		$width,		# \
		$height,	#  |
		$bits,		#   --- returned by getimagesize, see http://de3.php.net/manual/en/function.getimagesize.php
		$type,		#  |
		$attr;		# /



	function Image( $name )
	{
		global $wgUploadDirectory,$wgHashedUploadDirectory,
		       $wgUseSharedUploads, $wgSharedUploadDirectory, 
		       $wgHashedSharedUploadDirectory;

		$this->name      = $name;
		$this->title     = Title::makeTitle( Namespace::getImage(), $this->name );
		$this->fromSharedDirectory = false;
		if($wgHashedUploadDirectory) {
			$hash 		 = md5( $this->title->getDBkey() );
			$this->imagePath = $wgUploadDirectory . "/" . $hash{0} . "/" .substr( $hash, 0, 2 ) . "/{$name}";
		} else {
			$this->imagePath = $wgUploadDirectory . '/' . $name;
		}

		$this->url       = $this->wfImageUrl( $name );
		
		$this->fileExists = file_exists( $this->imagePath );
		
		# If the file is not found, and a shared upload directory 
		# like the Wikimedia Commons is used, look for it there.
		if (!$this->fileExists && $wgUseSharedUploads) {
			
			if($wgHashedSharedUploadDirectory) {				
				$hash = md5( $this->title->getDBkey() );
				$this->imagePath = $wgSharedUploadDirectory . '/' . $hash{0} . '/' .
					substr( $hash, 0, 2 ) . "/{$name}";
			} else {
				$this->imagePath = $wgSharedUploadDirectory . '/' . $name;
			}
			$this->fileExists = file_exists( $this->imagePath);
			$this->fromSharedDirectory = true;
			#wfDebug ("File from shared directory: ".$this->imagePath."\n");
		}		
		if($this->fileExists) {
			$this->url       = $this->wfImageUrl( $name, $this->fromSharedDirectory );
		} else {
			$this->url='';
		}
		

		if ( $this->fileExists ) 
		{
			@$gis = getimagesize( $this->imagePath );
			if( $gis !== false ) {
				$this->width = $gis[0];
				$this->height = $gis[1];
				$this->type = $gis[2];
				$this->attr = $gis[3];
				if ( isset( $gis["bits"] ) )  {
					$this->bits = $gis["bits"];
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

	function wfImageUrl( $name, $fromSharedDirectory = false )
	{
		global $wgUploadPath,$wgUploadBaseUrl,$wgHashedUploadDirectory,
		       $wgHashedSharedUploadDirectory,$wgSharedUploadBaseUrl,
		       $wgSharedUploadPath;
		if($fromSharedDirectory) {
			$hash = $wgHashedSharedUploadDirectory;
			$base = $wgSharedUploadBaseUrl;
			$path = $wgSharedUploadPath;
		} else {
			$hash = $wgHashedUploadDirectory;
			$base = $wgUploadBaseUrl;
			$path = $wgUploadPath;
		}			
		if ( $hash ) {
			$hash = md5( $name );
        		$url = "{$base}{$path}/" . $hash{0} . "/" .
              		substr( $hash, 0, 2 ) . "/{$name}";
		} else {
			$url = "{$base}{$path}/{$name}";
		}
        	return wfUrlencode( $url );
	}


	function exists()
	{
		return $this->fileExists;
	}

	function thumbUrl( $width, $subdir='thumb') {
		global $wgUploadPath,$wgHashedUploadDirectory, $wgUploadBaseUrl,
		       $wgSharedUploadPath,$wgSharedUploadDirectory,
		       $wgHashedSharedUploadDirectory, $wgSharedUploadBaseUrl;
		$name = $this->thumbName( $width );
		if($this->fromSharedDirectory) {
			$hashdir = $wgHashedSharedUploadDirectory;
			$base = $wgSharedUploadBaseUrl;
			$path = $wgSharedUploadPath;
		} else {
			$hashdir = $wgHashedUploadDirectory;
			$base = $wgUploadBaseUrl;
			$path = $wgUploadPath;
		}
		if ($hashdir) {
			$hash = md5( $name );
			$url = "{$base}{$path}/{$subdir}/" . $hash{0} . "/" . 
				substr( $hash, 0, 2 ) . "/{$name}";
		} else {
			$url = "{$base}{$path}/{$subdir}/{$name}";
		}

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
		global $wgImageMagickConvertCommand;
		global $wgUseImageMagick;
		global $wgUseSquid, $wgInternalServer;

		$width = IntVal( $width );

		$thumbName = $this->thumbName( $width );
 		$thumbPath = wfImageThumbDir( $thumbName, 'thumb', $this->fromSharedDirectory ).'/'.$thumbName;
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
						return "Image type not supported";
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

