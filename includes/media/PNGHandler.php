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

/**
 * Handler for PNG images.
 *
 * @ingroup Media
 */
class PNGHandler extends BitmapHandler {
	const BROKEN_FILE = '0';

	/**
	 * @param File|FSFile $image
	 * @param string $filename
	 * @return string
	 */
	public function getMetadata( $image, $filename ) {
		try {
			$metadata = BitmapMetadataHandler::PNG( $filename );
		} catch ( Exception $e ) {
			// Broken file?
			wfDebug( __METHOD__ . ': ' . $e->getMessage() . "\n" );

			return self::BROKEN_FILE;
		}

		return serialize( $metadata );
	}

	/**
	 * @param File $image
	 * @param bool|IContextSource $context Context to use (optional)
	 * @return array|bool
	 */
	public function formatMetadata( $image, $context = false ) {
		$meta = $this->getCommonMetaArray( $image );
		if ( count( $meta ) === 0 ) {
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
		$meta = $image->getMetadata();

		if ( !$meta ) {
			return [];
		}
		$meta = unserialize( $meta );
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
	function isAnimatedImage( $image ) {
		$ser = $image->getMetadata();
		if ( $ser ) {
			$metadata = unserialize( $ser );
			if ( $metadata['frameCount'] > 1 ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * We do not support making APNG thumbnails, so always false
	 * @param File $image
	 * @return bool False
	 */
	function canAnimateThumbnail( $image ) {
		return false;
	}

	function getMetadataType( $image ) {
		return 'parsed-png';
	}

	public function isMetadataValid( $image, $metadata ) {
		if ( $metadata === self::BROKEN_FILE ) {
			// Do not repetitivly regenerate metadata on broken file.
			return self::METADATA_GOOD;
		}

		Wikimedia\suppressWarnings();
		$data = unserialize( $metadata );
		Wikimedia\restoreWarnings();

		if ( !$data || !is_array( $data ) ) {
			wfDebug( __METHOD__ . " invalid png metadata\n" );

			return self::METADATA_BAD;
		}

		if ( !isset( $data['metadata']['_MW_PNG_VERSION'] )
			|| $data['metadata']['_MW_PNG_VERSION'] != PNGMetadataExtractor::VERSION
		) {
			wfDebug( __METHOD__ . " old but compatible png metadata\n" );

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

		Wikimedia\suppressWarnings();
		$metadata = unserialize( $image->getMetadata() );
		Wikimedia\restoreWarnings();

		if ( !$metadata || $metadata['frameCount'] <= 0 ) {
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
			$info[] = $wgLang->formatTimePeriod( $metadata['duration'] );
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
		$serMeta = $file->getMetadata();
		Wikimedia\suppressWarnings();
		$metadata = unserialize( $serMeta );
		Wikimedia\restoreWarnings();

		if ( !$metadata || !isset( $metadata['duration'] ) || !$metadata['duration'] ) {
			return 0.0;
		} else {
			return (float)$metadata['duration'];
		}
	}

	// PNGs should be easy to support, but it will need some sharpening applied
	// and another user test to check if the perceived quality change is noticeable
	public function supportsBucketing() {
		return false;
	}
}
