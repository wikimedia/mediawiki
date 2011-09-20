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
class ExifBitmapHandler extends BitmapHandler {

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
		if ( $metadata === self::OLD_BROKEN_FILE ||
			$metadata === self::BROKEN_FILE ||
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

	/**
	 * Wrapper for base classes ImageHandler::getImageSize() that checks for
	 * rotation reported from metadata and swaps the sizes to match.
	 *
	 * @param File $image
	 * @param string $path
	 * @return array
	 */
	function getImageSize( $image, $path ) {
		$gis = parent::getImageSize( $image, $path );
		
		// Don't just call $image->getMetadata(); File::getPropsFromPath() calls us with a bogus object.
		// This may mean we read EXIF data twice on initial upload.
		$meta = $this->getMetadata( $image, $path );
		$rotation = $this->getRotationForExif( $meta );

		if ($rotation == 90 || $rotation == 270) {
			$width = $gis[0];
			$gis[0] = $gis[1];
			$gis[1] = $width;
		}
		return $gis;
	}

	/**
	 * On supporting image formats, try to read out the low-level orientation
	 * of the file and return the angle that the file needs to be rotated to
	 * be viewed.
	 *
	 * This information is only useful when manipulating the original file;
	 * the width and height we normally work with is logical, and will match
	 * any produced output views.
	 *
	 * @param $file File
	 * @return int 0, 90, 180 or 270
	 */
	public function getRotation( $file ) {
		$data = $file->getMetadata();
		return $this->getRotationForExif( $data );
	}

	/**
	 * Given a chunk of serialized Exif metadata, return the orientation as
	 * degrees of rotation.
	 *
	 * @param string $data
	 * @return int 0, 90, 180 or 270
	 * @fixme orientation can include flipping as well; see if this is an issue!
	 */
	protected function getRotationForExif( $data ) {
		if ( !$data ) {
			return 0;
		}
		wfSuppressWarnings();
		$data = unserialize( $data );
		wfRestoreWarnings();
		if ( isset( $data['Orientation'] ) ) {
			# See http://sylvana.net/jpegcrop/exif_orientation.html
			switch ( $data['Orientation'] ) {
				case 8:
					return 90;
				case 3:
					return 180;
				case 6:
					return 270;
				default:
					return 0;
			}
		}
		return 0;
	}
}

