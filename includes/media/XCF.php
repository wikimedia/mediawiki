<?php
/**
 * Handler for the Gimp's native file format (XCF)
 *
 * Overview:
 *   http://en.wikipedia.org/wiki/XCF_(file_format)
 * Specification in Gnome repository:
 *   http://svn.gnome.org/viewvc/gimp/trunk/devel-docs/xcf.txt?view=markup
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
	 * Get width and height from the XCF header.
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

		$cmd = wfEscapeShellArg( $wgImageMagickIdentifyCommand ) . ' -verbose ' . wfEscapeShellArg( $filename );
		wfDebug( __METHOD__ . ": Running $cmd \n" );

		$retval = null;
		$return = wfShellExec( $cmd, $retval );
		if( $retval !== 0 ) {
			wfDebug( __METHOD__ . ": error encountered while running $cmd\n" );
			return false;
		}

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

			# Find out the largest width and height used in any frame
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

		# Forge a return array containing metadata information just like getimagesize()
		# See PHP documentation at: http://www.php.net/getimagesize
		$metadata = array();
		$metadata['frameCount'] = $frameCount;
		$metadata[0] = $sizeX;
		$metadata[1] = $sizeY;
		$metadata[2] = null;
		$metadata[3] = "height=\"$sizeY\" width=\"$sizeX\"";
		$metadata['mime'] = 'image/x-xcf';
		$metadata['channels'] = $colorspace == 1 ? 3 : 4;

		return $metadata;
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
