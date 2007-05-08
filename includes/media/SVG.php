<?php

/**
 * @addtogroup Media
 */
class SvgHandler extends ImageHandler {
	function isEnabled() {
		global $wgSVGConverters, $wgSVGConverter;
		if ( !isset( $wgSVGConverters[$wgSVGConverter] ) ) {
			wfDebug( "\$wgSVGConverter is invalid, disabling SVG rendering.\n" );
			return false;
		} else {
			return true;
		}
	}

	function mustRender() {
		return true;
	}

	function normaliseParams( $image, &$params ) {
		global $wgSVGMaxSize;
		if ( !parent::normaliseParams( $image, $params ) ) {
			return false;
		}

		# Don't make an image bigger than wgMaxSVGSize
		$params['physicalWidth'] = $params['width'];
		$params['physicalHeight'] = $params['height'];
		if ( $params['physicalWidth'] > $wgSVGMaxSize ) {
			$srcWidth = $image->getWidth( $params['page'] );
			$srcHeight = $image->getHeight( $params['page'] );
			$params['physicalWidth'] = $wgSVGMaxSize;
			$params['physicalHeight'] = Image::scaleHeight( $srcWidth, $srcHeight, $wgSVGMaxSize );
		}
		return true;
	}
	
	function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
		global $wgSVGConverters, $wgSVGConverter, $wgSVGConverterPath;
		
		if ( !$this->normaliseParams( $image, $params ) ) {
			return new TransformParameterError( $params );
		}
		$clientWidth = $params['width'];
		$clientHeight = $params['height'];
		$physicalWidth = $params['physicalWidth'];
		$physicalHeight = $params['physicalHeight'];
		$srcPath = $image->getImagePath();

		if ( $flags & self::TRANSFORM_LATER ) {
			return new ThumbnailImage( $dstUrl, $clientWidth, $clientHeight, $dstPath );
		}

		if ( !wfMkdirParents( dirname( $dstPath ) ) ) {
			return new MediaTransformError( 'thumbnail_error', $clientWidth, $clientHeight, 
				wfMsg( 'thumbnail_dest_directory' ) );
		}

		$err = false;
		if( isset( $wgSVGConverters[$wgSVGConverter] ) ) {
			$cmd = str_replace(
				array( '$path/', '$width', '$height', '$input', '$output' ),
				array( $wgSVGConverterPath ? wfEscapeShellArg( "$wgSVGConverterPath/" ) : "",
					   intval( $physicalWidth ),
					   intval( $physicalHeight ),
					   wfEscapeShellArg( $srcPath ),
					   wfEscapeShellArg( $dstPath ) ),
				$wgSVGConverters[$wgSVGConverter] ) . " 2>&1";
			wfProfileIn( 'rsvg' );
			wfDebug( __METHOD__.": $cmd\n" );
			$err = wfShellExec( $cmd, $retval );
			wfProfileOut( 'rsvg' );
		}

		$removed = $this->removeBadFile( $dstPath, $retval );
		if ( $retval != 0 || $removed ) {
			wfDebugLog( 'thumbnail',
				sprintf( 'thumbnail failed on %s: error %d "%s" from "%s"',
					wfHostname(), $retval, trim($err), $cmd ) );
			return new MediaTransformError( 'thumbnail_error', $clientWidth, $clientHeight, $err );
		} else {
			return new ThumbnailImage( $dstUrl, $clientWidth, $clientHeight, $dstPath );
		}
	}

	function getImageSize( $image, $path ) {
		return wfGetSVGsize( $path );
	}

	function getThumbType( $ext, $mime ) {
		return array( 'png', 'image/png' );
	}
}
?>
