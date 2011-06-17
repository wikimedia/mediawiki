<?php
/**
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

	function getMetadata ( $image, $filename ) {
		try {
			$meta = BitmapMetadataHandler::Jpeg( $filename );
			if ( !is_array( $meta ) ) {
				// This should never happen, but doesn't hurt to be paranoid.
				throw new MWException('Metadata array is not an array');
			}
			$meta['MEDIAWIKI_EXIF_VERSION'] = Exif::version();
			return serialize( $meta );
		}
		catch ( MWException $e ) {
			// BitmapMetadataHandler throws an exception in certain exceptional cases like if file does not exist.
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

}

