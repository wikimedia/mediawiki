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
		$title;		# Title object for this image. Initialized when needed.


	function Image( $name )
	{
		$this->name      = $name;
		$this->imagePath = wfImagePath( $name );
		$this->url       = wfImageUrl( $name );
		$this->title     = NULL;
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

	function getEscapeLocalURL()
	{
		if ( $this->title == NULL )
		{
			$this->title = Title::makeTitle( Namespace::getImage(), $this->name );
		}
		return $this->title->escapeLocalURL();
	}

	function createThumb( $width ) {
		global $wgUploadDirectory;
		global $wgImageMagickConvertCommand;
		global $wgUseImageMagick;
		global $wgUseSquid, $wgInternalServer;
		$thumbName = $width."px-".$this->name;
		$thumbPath = wfImageThumbDir( $thumbName )."/".$thumbName;
		$thumbUrl  = wfImageThumbUrl( $thumbName );

		if ( ! file_exists( $this->imagePath ) )
		{
			# If there is no image, there will be no thumbnail
			return "";
		}

		if (     (! file_exists( $thumbPath ) )
		||  ( filemtime($thumbPath) < filemtime($this->imagePath) ) ) {
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
				list($src_width, $src_height, $src_type, $src_attr) = getimagesize( $this->imagePath );
				switch( $src_type ) {
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
				$height = floor( $src_height * ( $width/$src_width ) );
				$dst_image = imagecreatetruecolor( $width, $height );
				imagecopyresampled( $dst_image, $src_image, 
							0,0,0,0,
							$width, $height, $src_width, $src_height );
				switch( $src_type ) {
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
			$imgstat   = stat( $this->imagePath );
			if( $thumbstat["size"] == 0 )
			{
				unlink( $thumbPath );
			}

		}
		return $thumbUrl;
	} //function createThumb

} //class
