<?php
/**
 * Handler for JPEG images.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Media
 */

/**
 * JPEG specific handler.
 * Inherits most stuff from BitmapHandler, just here to do the metadata handler differently.
 *
 * Metadata stuff common to Jpeg and built-in Tiff (not PagedTiffHandler) is
 * in ExifBitmapHandler.
 *
 * @ingroup Media
 */
class JpegHandler extends ExifBitmapHandler {
	function getMetadata( $image, $filename ) {
		try {
			$meta = BitmapMetadataHandler::Jpeg( $filename );
			if ( !is_array( $meta ) ) {
				// This should never happen, but doesn't hurt to be paranoid.
				throw new MWException( 'Metadata array is not an array' );
			}
			$meta['MEDIAWIKI_EXIF_VERSION'] = Exif::version();

			return serialize( $meta );
		} catch ( MWException $e ) {
			// BitmapMetadataHandler throws an exception in certain exceptional
			// cases like if file does not exist.
			wfDebug( __METHOD__ . ': ' . $e->getMessage() . "\n" );

			/* This used to use 0 (ExifBitmapHandler::OLD_BROKEN_FILE) for the cases
			 * 	* No metadata in the file
			 * 	* Something is broken in the file.
			 * However, if the metadata support gets expanded then you can't tell if the 0 is from
			 * a broken file, or just no props found. A broken file is likely to stay broken, but
			 * a file which had no props could have props once the metadata support is improved.
			 * Thus switch to using -1 to denote only a broken file, and use an array with only
			 * MEDIAWIKI_EXIF_VERSION to denote no props.
			 */

			return ExifBitmapHandler::BROKEN_FILE;
		}
	}

	/**
	 * @param File $file
	 * @param array $params Rotate parameters.
	 *    'rotation' clockwise rotation in degrees, allowed are multiples of 90
	 * @since 1.21
	 * @return bool
	 */
	public function rotate( $file, $params ) {
		global $wgJpegTran;

		$rotation = ( $params['rotation'] + $this->getRotation( $file ) ) % 360;

		if ( $wgJpegTran && is_file( $wgJpegTran ) ) {
			$cmd = wfEscapeShellArg( $wgJpegTran ) .
				" -rotate " . wfEscapeShellArg( $rotation ) .
				" -outfile " . wfEscapeShellArg( $params['dstPath'] ) .
				" " . wfEscapeShellArg( $params['srcPath'] );
			wfDebug( __METHOD__ . ": running jpgtran: $cmd\n" );
			wfProfileIn( 'jpegtran' );
			$retval = 0;
			$err = wfShellExecWithStderr( $cmd, $retval );
			wfProfileOut( 'jpegtran' );
			if ( $retval !== 0 ) {
				$this->logErrorForExternalProcess( $retval, $err, $cmd );

				return new MediaTransformError( 'thumbnail_error', 0, 0, $err );
			}

			return false;
		} else {
			return parent::rotate( $file, $params );
		}
	}
}
