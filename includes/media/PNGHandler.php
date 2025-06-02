<?php
/**
 * Handler for PNG images.
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
use Wikimedia\RequestTimeout\TimeoutException;

/**
 * Handler for PNG images.
 *
 * @ingroup Media
 */
class PNGHandler extends BitmapHandler {
	private const BROKEN_FILE = '0';

	/**
	 * @param MediaHandlerState $state
	 * @param string $filename
	 * @return array
	 */
	public function getSizeAndMetadata( $state, $filename ) {
		try {
			$metadata = BitmapMetadataHandler::PNG( $filename );
		} catch ( TimeoutException $e ) {
			throw $e;
		} catch ( Exception $e ) {
			// Broken file?
			wfDebug( __METHOD__ . ': ' . $e->getMessage() );

			return [ 'metadata' => [ '_error' => self::BROKEN_FILE ] ];
		}

		return [
			'width' => $metadata['width'],
			'height' => $metadata['height'],
			'bits' => $metadata['bitDepth'],
			'metadata' => array_diff_key(
				$metadata,
				[ 'width' => true, 'height' => true, 'bits' => true ]
			)
		];
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

	/**
	 * Get a file type independent array of metadata.
	 *
	 * @param File $image
	 * @return array The metadata array
	 */
	public function getCommonMetaArray( File $image ) {
		$meta = $image->getMetadataArray();

		if ( !isset( $meta['metadata'] ) ) {
			return [];
		}
		unset( $meta['metadata']['_MW_PNG_VERSION'] );

		return $meta['metadata'];
	}

	/**
	 * @param File $image
	 * @return bool
	 */
	public function isAnimatedImage( $image ) {
		$metadata = $image->getMetadataArray();
		return isset( $metadata['frameCount'] ) && $metadata['frameCount'] > 1;
	}

	/**
	 * We do not support making APNG thumbnails, so always false
	 * @param File $image
	 * @return bool False
	 */
	public function canAnimateThumbnail( $image ) {
		return false;
	}

	public function getMetadataType( $image ) {
		return 'parsed-png';
	}

	public function isFileMetadataValid( $image ) {
		$data = $image->getMetadataArray();
		if ( $data === [ '_error' => self::BROKEN_FILE ] ) {
			// Do not repetitively regenerate metadata on broken file.
			return self::METADATA_GOOD;
		}

		if ( !$data || isset( $data['_error'] ) ) {
			wfDebug( __METHOD__ . " invalid png metadata" );

			return self::METADATA_BAD;
		}

		if ( !isset( $data['metadata']['_MW_PNG_VERSION'] )
			|| $data['metadata']['_MW_PNG_VERSION'] !== PNGMetadataExtractor::VERSION
		) {
			wfDebug( __METHOD__ . " old but compatible png metadata" );

			return self::METADATA_COMPATIBLE;
		}

		return self::METADATA_GOOD;
	}

	/**
	 * @param File $image
	 * @return string
	 */
	public function getLongDesc( $image ) {
		global $wgLang;
		$original = parent::getLongDesc( $image );

		$metadata = $image->getMetadataArray();

		if ( !$metadata || isset( $metadata['_error'] ) || $metadata['frameCount'] <= 0 ) {
			return $original;
		}

		$info = [];
		$info[] = $original;

		if ( $metadata['loopCount'] == 0 ) {
			$info[] = wfMessage( 'file-info-png-looped' )->parse();
		} elseif ( $metadata['loopCount'] > 1 ) {
			$info[] = wfMessage( 'file-info-png-repeat' )->numParams( $metadata['loopCount'] )->parse();
		}

		if ( $metadata['frameCount'] > 0 ) {
			$info[] = wfMessage( 'file-info-png-frames' )->numParams( $metadata['frameCount'] )->parse();
		}

		if ( $metadata['duration'] ) {
			$info[] = htmlspecialchars( $wgLang->formatTimePeriod( $metadata['duration'] ), ENT_QUOTES );
		}

		return $wgLang->commaList( $info );
	}

	/**
	 * Return the duration of an APNG file.
	 *
	 * Shown in the &query=imageinfo&iiprop=size api query.
	 *
	 * @param File $file
	 * @return float The duration of the file.
	 */
	public function getLength( $file ) {
		$metadata = $file->getMetadataArray();

		if ( !$metadata || !isset( $metadata['duration'] ) || !$metadata['duration'] ) {
			return 0.0;
		}

		return (float)$metadata['duration'];
	}

	// PNGs should be easy to support, but it will need some sharpening applied
	// and another user test to check if the perceived quality change is noticeable
	public function supportsBucketing() {
		return false;
	}
}
