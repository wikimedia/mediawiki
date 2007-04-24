<?php

/**
 * Media-handling base classes and generic functionality
 */

/**
 * Base media handler class
 *
 * @addtogroup Media
 */
abstract class MediaHandler {
	const TRANSFORM_LATER = 1;

	/**
	 * Instance cache
	 */
	static $handlers = array();

	/**
	 * Get a MediaHandler for a given MIME type from the instance cache
	 */
	static function getHandler( $type ) {
		global $wgMediaHandlers;
		if ( !isset( $wgMediaHandlers[$type] ) ) {
			wfDebug( __METHOD__ . ": no handler found for $type.\n");
			return false;
		}
		$class = $wgMediaHandlers[$type];
		if ( !isset( self::$handlers[$class] ) ) {
			self::$handlers[$class] = new $class;
			if ( !self::$handlers[$class]->isEnabled() ) {
				self::$handlers[$class] = false;
			}
		}
		return self::$handlers[$class];
	}

	/*
	 * Validate a thumbnail parameter at parse time. 
	 * Return true to accept the parameter, and false to reject it.
	 * If you return false, the parser will do something quiet and forgiving.
	 */
	abstract function validateParam( $name, $value );

	/**
	 * Merge a parameter array into a string appropriate for inclusion in filenames
	 */
	abstract function makeParamString( $params );

	/**
	 * Parse a param string made with makeParamString back into an array
	 */
	abstract function parseParamString( $str );

	/**
	 * Changes the parameter array as necessary, ready for transformation. 
	 * Should be idempotent.
	 * Returns false if the parameters are unacceptable and the transform should fail
	 */
	abstract function normaliseParams( $image, &$params );

	/**
	 * Get an image size array like that returned by getimagesize(), or false if it 
	 * can't be determined.
	 *
	 * @param Image $image The image object, or false if there isn't one
	 * @param string $fileName The filename
	 * @return array
	 */
	abstract function getImageSize( $image, $path );

	/**
	 * Get handler-specific metadata which will be saved in the img_metadata field.
	 *
	 * @param Image $image The image object, or false if there isn't one
	 * @param string $fileName The filename
	 * @return string
	 */
	function getMetadata( $image, $path ) { return ''; }

	/**
	 * Get a string describing the type of metadata, for display purposes.
	 */
	function getMetadataType( $image ) { return false; }

	/**
	 * Check if the metadata string is valid for this handler.
	 * If it returns false, Image will reload the metadata from the file and update the database
	 */
	function isMetadataValid( $image, $metadata ) { return true; }

	/**
	 * Get a MediaTransformOutput object representing the transformed output. Does not 
	 * actually do the transform.
	 *
	 * @param Image $image The image object
	 * @param string $dstPath Filesystem destination path
	 * @param string $dstUrl Destination URL to use in output HTML
	 * @param array $params Arbitrary set of parameters validated by $this->validateParam()
	 */
	function getTransform( $image, $dstPath, $dstUrl, $params ) {
		return $this->doTransform( $image, $dstPath, $dstUrl, $params, self::TRANSFORM_LATER );
	}

	/**
	 * Get a MediaTransformOutput object representing the transformed output. Does the 
	 * transform unless $flags contains self::TRANSFORM_LATER.
	 *
	 * @param Image $image The image object
	 * @param string $dstPath Filesystem destination path
	 * @param string $dstUrl Destination URL to use in output HTML
	 * @param array $params Arbitrary set of parameters validated by $this->validateParam()
	 * @param integer $flags A bitfield, may contain self::TRANSFORM_LATER
	 */
	abstract function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 );

	/**
	 * Get the thumbnail extension and MIME type for a given source MIME type
	 * @return array thumbnail extension and MIME type
	 */
	function getThumbType( $ext, $mime ) {
		return array( $ext, $mime );
	}	

	/**
	 * True if the handled types can be transformed
	 */
	function canRender() { return true; }
	/**
	 * True if handled types cannot be displayed directly in a browser 
	 * but can be rendered
	 */
	function mustRender() { return false; }
	/**
	 * True if the type has multi-page capabilities
	 */
	function isMultiPage() { return false; }
	/**
	 * Page count for a multi-page document, false if unsupported or unknown
	 */
	function pageCount() { return false; }
	/**
	 * False if the handler is disabled for all files
	 */
	function isEnabled() { return true; }

	/**
	 * Get an associative array of page dimensions
	 * Currently "width" and "height" are understood, but this might be 
	 * expanded in the future.
	 * Returns false if unknown or if the document is not multi-page.
	 */
	function getPageDimensions( $image, $page ) {
		$gis = $this->getImageSize( $image, $image->getImagePath() );
		return array(
			'width' => $gis[0],
			'height' => $gis[1]
		);
	}
}

/**
 * Media handler abstract base class for images
 *
 * @addtogroup Media
 */
abstract class ImageHandler extends MediaHandler {
	function validateParam( $name, $value ) {
		if ( in_array( $name, array( 'width', 'height' ) ) ) {
			if ( $value <= 0 ) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	function makeParamString( $params ) {
		if ( isset( $params['physicalWidth'] ) ) {
			$width = $params['physicalWidth'];
		} else {
			$width = $params['width'];
		}
		$width = intval( $width );
		return "{$width}px";
	}

	function parseParamString( $str ) {
		$m = false;
		if ( preg_match( '/^(\d+)px$/', $str, $m ) ) {
			return array( 'width' => $m[1] );
		} else {
			return false;
		}
	}

	function getScriptParams( $params ) {
		return array( 'width' => $params['width'] );
	}

	function normaliseParams( $image, &$params ) {
		$mimeType = $image->getMimeType();

		if ( !isset( $params['width'] ) ) {
			return false;
		}
		if ( !isset( $params['page'] ) ) {
			$params['page'] = 1;
		}
		$srcWidth = $image->getWidth( $params['page'] );
		$srcHeight = $image->getHeight( $params['page'] );
		if ( isset( $params['height'] ) && $params['height'] != -1 ) {
			if ( $params['width'] * $srcHeight > $params['height'] * $srcWidth ) {
				$params['width'] = wfFitBoxWidth( $srcWidth, $srcHeight, $params['height'] );
			}
		}
		$params['height'] = Image::scaleHeight( $srcWidth, $srcHeight, $params['width'] );
		if ( !$this->validateThumbParams( $params['width'], $params['height'], $srcWidth, $srcHeight, $mimeType ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Get a transform output object without actually doing the transform
	 */
	function getTransform( $image, $dstPath, $dstUrl, $params ) {
		return $this->doTransform( $image, $dstPath, $dstUrl, $params, self::TRANSFORM_LATER );
	}
	
	/**
	 * Validate thumbnail parameters and fill in the correct height
	 *
	 * @param integer &$width Specified width (input/output)
	 * @param integer &$height Height (output only)
	 * @return false to indicate that an error should be returned to the user. 
	 */
	function validateThumbParams( &$width, &$height, $srcWidth, $srcHeight, $mimeType ) {
		$width = intval( $width );

		# Sanity check $width
		if( $width <= 0) {
			wfDebug( __METHOD__.": Invalid destination width: $width\n" );
			return false;
		}
		if ( $srcWidth <= 0 ) {
			wfDebug( __METHOD__.": Invalid source width: $srcWidth\n" );
			return false;
		}

		$height = Image::scaleHeight( $srcWidth, $srcHeight, $width );
		return true;
	}

	function getScriptedTransform( $image, $script, $params ) {
		if ( !$this->normaliseParams( $image, $params ) ) {
			return false;
		}
		$url = $script . '&' . wfArrayToCGI( $this->getScriptParams( $params ) );
		return new ThumbnailImage( $url, $params['width'], $params['height'] );
	}

	/**
	 * Check for zero-sized thumbnails. These can be generated when
	 * no disk space is available or some other error occurs
	 *
	 * @param $dstPath The location of the suspect file
	 * @param $retval Return value of some shell process, file will be deleted if this is non-zero
	 * @return true if removed, false otherwise
	 */
	function removeBadFile( $dstPath, $retval = 0 ) {
		$removed = false;
		if( file_exists( $dstPath ) ) {
			$thumbstat = stat( $dstPath );
			if( $thumbstat['size'] == 0 || $retval != 0 ) {
				wfDebugLog( 'thumbnail',
					sprintf( 'Removing bad %d-byte thumbnail "%s"',
						$thumbstat['size'], $dstPath ) );
				unlink( $dstPath );
				return true;
			}
		}
		return false;
	}

	function getImageSize( $image, $path ) {
		wfSuppressWarnings();
		$gis = getimagesize( $path );
		wfRestoreWarnings();
		return $gis;
	}
}

?>
