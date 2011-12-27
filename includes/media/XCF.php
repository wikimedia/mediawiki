<?php
/**
 * Handler for the Gimp's native file format
 *
 * @file
 * @ingroup Media
 */

/**
 * Handler for the Gimp's native file format; getimagesize() doesn't
 * support these files
 *
 * @ingroup Media
 */
class XCFHandler extends BitmapHandler {

	/**
	 * Render files as PNG
	 *
	 * @param $ext
	 * @param $mime
	 * @param $params
	 * @return array
	 */
	function getThumbType( $ext, $mime, $params = null ) {
		return array( 'png', 'image/png' );
	}

	/**
	 * Get width and height from the bmp header.
	 *
	 * @param $image
	 * @param $filename
	 * @return array
	 */
	function getImageSize( $image, $filename ) {
		return self::getXCFMetaData( $filename );
	}

	static function getXCFMetaData( $filename ) {
		global $wgImageMagickIdentifyCommand;

		$md = false;
		$cmd = wfEscapeShellArg( $wgImageMagickIdentifyCommand ) . ' -verbose ' . wfEscapeShellArg( $filename );
		wfDebug( __METHOD__ . ": Running $cmd \n" );
		$retval = '';
		$return = wfShellExec( $cmd, $retval );

		if( $retval == 0 ) {
			$colorspace = preg_match_all( '/ *Colorspace: RGB/', $return, $match );
			$frameCount = preg_match_all( '/ *Geometry: ([0-9]+x[0-9]+)\+[+0-9]*/', $return, $match );
			wfDebug( __METHOD__ . ": Got $frameCount matches\n" );

			/* if( $frameCount == 1 ) { */
			/* 	preg_match( '/([0-9]+)x([0-9]+)/sm', $match[1][0], $m ); */
			/* 	$sizeX = $m[1]; */
			/* 	$sizeY = $m[2]; */
			/* } else { */
				$sizeX = 0;
				$sizeY = 0;

				foreach( $match[1] as $res ) {
					preg_match( '/([0-9]+)x([0-9]+)/sm', $res, $m );
					if( $m[1] > $sizeX ) {
						$sizeX = $m[1];
					}
					if( $m[2] > $sizeY ) {
						$sizeY = $m[2];
					}
				}
			/* } */

			wfDebug( __METHOD__ . ": Found $sizeX x $sizeY x $frameCount \n" );
			$md['frameCount'] = $frameCount;
			$md[0] = $sizeX;
			$md[1] = $sizeY;
			$md[2] = null;
			$md[3] = "height=\"$sizeY\" width=\"$sizeX\"";
			$md['mime'] = 'image/x-xcf';
			$md['channels'] = $colorspace == 1 ? 3 : 4;
		}
		return $md;
	}

	/**
	 * Must use "im" for XCF
	 *
	 * @return string
	 */
	protected static function getScalerType( $dstPath, $checkDstPath = true ) {
		return "im";
	}
}
