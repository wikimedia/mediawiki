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
		$width,		# \
		$height,	#  --- returned by getimagesize, see http://de3.php.net/manual/en/function.getimagesize.php
		$type,		#  |
		$attr;		# /


	function Image( $name )
	{
		$this->name      = $name;
		$this->title     = Title::makeTitle( Namespace::getImage(), $this->name );
		$this->imagePath = wfImagePath( $name );
		$this->url       = $this->wfImageUrl( $name );
		
		if ( $this->fileExists = file_exists( $this->imagePath ) ) // Sic!, "=" is intended
		{
			list($this->width, $this->height, $this->type, $this->attr) = getimagesize( $this->imagePath );
		}
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


	function createThumb( $width ) {
		global $wgUploadDirectory;
		global $wgImageMagickConvertCommand;
		global $wgUseImageMagick;
		global $wgUseSquid, $wgInternalServer;
		$thumbName = $width."px-".$this->name;
		$thumbPath = wfImageThumbDir( $thumbName )."/".$thumbName;
		$thumbUrl  = wfImageThumbUrl( $thumbName );

		if ( ! $this->exists() )
		{
			# If there is no image, there will be no thumbnail
			return "";
		}
		
		# Sanity check $width
		$width = IntVal( $width );
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
	} //function createThumb

} //class
