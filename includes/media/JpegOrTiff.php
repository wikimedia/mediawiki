<?php
/**
 * @file
 * @ingroup Media
 */

/**
 * Stuff specific to JPEG and (built-in) TIFF handler.
 * All metadata related, since both JPEG and TIFF support Exif.
 *
 * @ingroup Media
 */
class JpegOrTiffHandler extends BitmapHandler {

	const BROKEN_FILE = '-1'; // error extracting metadata
	const OLD_BROKEN_FILE = '0'; // outdated error extracting metadata.

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

	/**
	 * @param $image File
	 * @return array|bool
	 */
	function formatMetadata( $image ) {
		$metadata = $image->getMetadata();
		if ( !$metadata ||
			$this->isMetadataValid( $image, $metadata ) === self::METADATA_BAD )
		{
			// So we don't try and display metadata from PagedTiffHandler
			// for example when using InstantCommons.
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

	function getMetadataType( $image ) {
		return 'exif';
	}
}

