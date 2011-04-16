<?php
/**
 * @file
 * @ingroup Media
 */

/** JPEG specific handler.
 * Inherits most stuff from BitmapHandler, just here to do the metadata handler differently
 * @ingroup Media
 */
class JpegHandler extends BitmapHandler {

	const BROKEN_FILE = '-1'; // error extracting metadata
	const OLD_BROKEN_FILE = '0'; // outdated error extracting metadata.

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

			/* This used to use 0 (self::OLD_BROKEN_FILE) for the cases
			 * 	* No metadata in the file
			 * 	* Something is broken in the file.
			 * However, if the metadata support gets expanded then you can't tell if the 0 is from
			 * a broken file, or just no props found. A broken file is likely to stay broken, but
			 * a file which had no props could have props once the metadata support is improved.
			 * Thus switch to using -1 to denote only a broken file, and use an array with only
			 * MEDIAWIKI_EXIF_VERSION to denote no props.
			 */
			return self::BROKEN_FILE;
		}
	}

	function convertMetadataVersion( $metadata, $version = 1 ) {
		// basically flattens arrays.
		$version = explode(';', $version, 2);
		$version = intval($version[0]);
		if ( $version < 1 || $version >= 2 ) {
			return $metadata;
		}

		$avoidHtml = true;

		if ( !is_array( $metadata ) ) {
			$metadata = unserialize( $metadata );
		}
		if ( !isset( $metadata['MEDIAWIKI_EXIF_VERSION'] ) || $metadata['MEDIAWIKI_EXIF_VERSION'] != 2 ) {
			return $metadata;
		}

		// Treat Software as a special case because in can contain
		// an array of (SoftwareName, Version).
		if (isset( $metadata['Software'] ) 
			&& is_array( $metadata['Software'] ) 
			&& is_array( $metadata['Software'][0])
			&& isset( $metadata['Software'][0][0] )
			&& isset( $metadata['Software'][0][1])
		 ) {
			$metadata['Software'] = $metadata['Software'][0][0] . ' (Version '
				. $metadata['Software'][0][1] . ')';
		}

		// ContactInfo also has to be dealt with specially
		if ( isset( $metadata['Contact'] ) ) {
			$metadata['Contact'] =
				FormatMetadata::collapseContactInfo(
					$metadata['Contact'] );
		}

		foreach ( $metadata as &$val ) {
			if ( is_array( $val ) ) {
				$val = FormatMetadata::flattenArray( $val, 'ul', $avoidHtml );
			}
		}
		$metadata['MEDIAWIKI_EXIF_VERSION'] = 1;
		return $metadata;
	}

	function isMetadataValid( $image, $metadata ) {
		global $wgShowEXIF;
		if ( !$wgShowEXIF ) {
			# Metadata disabled and so an empty field is expected
			return self::METADATA_GOOD;
		}
		if ( $metadata === self::OLD_BROKEN_FILE ) {
			# Old special value indicating that there is no EXIF data in the file.
			# or that there was an error well extracting the metadata.
			wfDebug( __METHOD__ . ": back-compat version\n");
			return self::METADATA_COMPATIBLE;
		}
		if ( $metadata === self::BROKEN_FILE ) {
			return self::METADATA_GOOD;
		}
		wfSuppressWarnings();
		$exif = unserialize( $metadata );
		wfRestoreWarnings();
		if ( !isset( $exif['MEDIAWIKI_EXIF_VERSION'] ) ||
			$exif['MEDIAWIKI_EXIF_VERSION'] != Exif::version() )
		{
			if ( isset( $exif['MEDIAWIKI_EXIF_VERSION'] ) &&
				$exif['MEDIAWIKI_EXIF_VERSION'] == 1 )
			{
				//back-compatible but old
				wfDebug( __METHOD__.": back-compat version\n" );
				return self::METADATA_COMPATIBLE;
			}
			# Wrong (non-compatible) version
			wfDebug( __METHOD__.": wrong version\n" );
			return self::METADATA_BAD;
		}
		return self::METADATA_GOOD;
	}

	function formatMetadata( $image ) {
		$metadata = $image->getMetadata();
		if ( !$metadata || $metadata == self::BROKEN_FILE ) {
			return false;
		}
		$exif = unserialize( $metadata );
		if ( !$exif ) {
			return false;
		}
		unset( $exif['MEDIAWIKI_EXIF_VERSION'] );
		if ( count( $exif ) == 0 ) {
			return false;
		}
		return $this->formatMetadataHelper( $exif );
	}


}
