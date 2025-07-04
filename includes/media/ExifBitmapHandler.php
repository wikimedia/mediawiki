<?php

/**
 * Handler for bitmap images with exif metadata.
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

use MediaWiki\Context\IContextSource;
use MediaWiki\FileRepo\File\File;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Stuff specific to JPEG and (built-in) TIFF handler.
 * All metadata related, since both JPEG and TIFF support Exif.
 *
 * @stable to extend
 * @ingroup Media
 */
class ExifBitmapHandler extends BitmapHandler {
	/** Error extracting metadata */
	public const BROKEN_FILE = '-1';

	/** Outdated error extracting metadata */
	public const OLD_BROKEN_FILE = '0';

	/** @inheritDoc */
	public function convertMetadataVersion( $metadata, $version = 1 ) {
		// basically flattens arrays.
		$version = is_int( $version ) ? $version : (int)explode( ';', $version, 2 )[0];
		if ( $version < 1 || $version >= 2 ) {
			return $metadata;
		}

		if ( !isset( $metadata['MEDIAWIKI_EXIF_VERSION'] ) || $metadata['MEDIAWIKI_EXIF_VERSION'] !== 2 ) {
			return $metadata;
		}

		// Treat Software as a special case because in can contain
		// an array of (SoftwareName, Version).
		if ( isset( $metadata['Software'] )
			&& is_array( $metadata['Software'] )
			&& is_array( $metadata['Software'][0] )
			&& isset( $metadata['Software'][0][0] )
			&& isset( $metadata['Software'][0][1] )
		) {
			$metadata['Software'] = $metadata['Software'][0][0] . ' (Version '
				. $metadata['Software'][0][1] . ')';
		}

		$formatter = new FormatMetadata;

		// ContactInfo also has to be dealt with specially
		if ( isset( $metadata['Contact'] ) ) {
			$metadata['Contact'] = $formatter->collapseContactInfo(
				is_array( $metadata['Contact'] ) ? $metadata['Contact'] : [ $metadata['Contact'] ]
			);
		}

		// Ignore Location shown if it is not a simple string
		if ( isset( $metadata['LocationShown'] ) && !is_string( $metadata['LocationShown'] ) ) {
			unset( $metadata['LocationShown'] );
		}

		foreach ( $metadata as &$val ) {
			if ( is_array( $val ) ) {
				// @phan-suppress-next-line SecurityCheck-DoubleEscaped Ambiguous with the true for nohtml
				$val = $formatter->flattenArrayReal( $val, 'ul', true );
			}
		}
		unset( $val );
		$metadata['MEDIAWIKI_EXIF_VERSION'] = 1;

		return $metadata;
	}

	/**
	 * @param File $image
	 * @return bool|int
	 */
	public function isFileMetadataValid( $image ) {
		$showEXIF = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::ShowEXIF );
		if ( !$showEXIF ) {
			# Metadata disabled and so an empty field is expected
			return self::METADATA_GOOD;
		}
		$exif = $image->getMetadataArray();
		if ( !$exif ) {
			wfDebug( __METHOD__ . ': error unserializing?' );
			return self::METADATA_BAD;
		}
		if ( $exif === [ '_error' => self::OLD_BROKEN_FILE ] ) {
			# Old special value indicating that there is no Exif data in the file.
			# or that there was an error well extracting the metadata.
			wfDebug( __METHOD__ . ": back-compat version" );
			return self::METADATA_COMPATIBLE;
		}

		if ( $exif === [ '_error' => self::BROKEN_FILE ] ) {
			return self::METADATA_GOOD;
		}

		if ( !isset( $exif['MEDIAWIKI_EXIF_VERSION'] )
			|| $exif['MEDIAWIKI_EXIF_VERSION'] !== Exif::version()
		) {
			if ( isset( $exif['MEDIAWIKI_EXIF_VERSION'] )
				&& $exif['MEDIAWIKI_EXIF_VERSION'] === 1
			) {
				// back-compatible but old
				wfDebug( __METHOD__ . ": back-compat version" );

				return self::METADATA_COMPATIBLE;
			}
			# Wrong (non-compatible) version
			wfDebug( __METHOD__ . ": wrong version" );

			return self::METADATA_BAD;
		}

		return self::METADATA_GOOD;
	}

	/**
	 * @param File $image
	 * @param IContextSource|false $context
	 * @return array[]|false
	 */
	public function formatMetadata( $image, $context = false ) {
		$meta = $this->getCommonMetaArray( $image );
		if ( !$meta ) {
			return false;
		}

		return $this->formatMetadataHelper( $meta, $context );
	}

	/** @inheritDoc */
	public function getCommonMetaArray( File $file ) {
		$exif = $file->getMetadataArray();
		if ( !$exif ) {
			return [];
		}
		unset( $exif['MEDIAWIKI_EXIF_VERSION'] );

		return $exif;
	}

	/** @inheritDoc */
	public function getMetadataType( $image ) {
		return 'exif';
	}

	/**
	 * @param array $info
	 * @param array $metadata
	 * @return array
	 */
	protected function applyExifRotation( $info, $metadata ) {
		if ( $this->autoRotateEnabled() ) {
			$rotation = $this->getRotationForExifFromOrientation( $metadata['Orientation'] ?? null );
		} else {
			$rotation = 0;
		}

		if ( $rotation === 90 || $rotation === 270 ) {
			$width = $info['width'];
			$info['width'] = $info['height'];
			$info['height'] = $width;
		}
		return $info;
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
	 * @param File $file
	 * @return int 0, 90, 180 or 270
	 */
	public function getRotation( $file ) {
		if ( !$this->autoRotateEnabled() ) {
			return 0;
		}

		$orientation = $file->getMetadataItem( 'Orientation' );
		return $this->getRotationForExifFromOrientation( $orientation );
	}

	/**
	 * Given a chunk of serialized Exif metadata, return the orientation as
	 * degrees of rotation.
	 *
	 * @param int|null $orientation
	 * @return int 0, 90, 180 or 270
	 * @todo FIXME: Orientation can include flipping as well; see if this is an issue!
	 */
	protected function getRotationForExifFromOrientation( $orientation ) {
		if ( $orientation === null ) {
			return 0;
		}
		# See http://sylvana.net/jpegcrop/exif_orientation.html
		switch ( $orientation ) {
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
}
