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
	 * @param File $image
	 * @param string $filename
	 * @return string
	 */
	function getMetadata( $image, $filename ) {
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
	function formatMetadata( $image, $context = false ) {
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
			return array();
		}
		$meta = unserialize( $meta );
		if ( !isset( $meta['metadata'] ) ) {
			return array();
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

	function isMetadataValid( $image, $metadata ) {

		if ( $metadata === self::BROKEN_FILE ) {
			// Do not repetitivly regenerate metadata on broken file.
			return self::METADATA_GOOD;
		}

		MediaWiki\suppressWarnings();
		$data = unserialize( $metadata );
		MediaWiki\restoreWarnings();

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
	function getLongDesc( $image ) {
		global $wgLang;
		$original = parent::getLongDesc( $image );

		MediaWiki\suppressWarnings();
		$metadata = unserialize( $image->getMetadata() );
		MediaWiki\restoreWarnings();

		if ( !$metadata || $metadata['frameCount'] <= 0 ) {
			return $original;
		}

		$info = array();
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

	// PNGs should be easy to support, but it will need some sharpening applied
	// and another user test to check if the perceived quality change is noticeable

	public function supportsBucketing() {
		return false;
	}

	/**
	 * Add extra args to the image magick invocation
	 *
	 * The returned array will be merged with the $animation_pre
	 * array in BitmapHandler::transformImageMagick, where they
	 * will be escaped for shell, and added directly after the
	 * input filename.
	 *
	 * @param $image File The image where are converting
	 * @param $params array Scalar parameters
	 * @return Array Extra args for image magick
	 */
	protected function getAnimationPreIMArgs( $image, $pre ) {
		// Work-around T106516 (Wrong default gamma on greyscale png images w/o gAMA chunk)
		$animation_pre = array();
		MediaWiki\suppressWarnings();
		$pngMeta = unserialize( $image->getMetadata() );
		MediaWiki\restoreWarnings();
		if ( $pngMeta && isset( $pngMeta['gamma'] )
			&& $pngMeta['gamma'] === false
			&& ( $pngMeta['colorType'] === 'greyscale-alpha' || $pngMeta['colorType'] === 'greyscale' )
			&& !isset( $pngMeta['metadata']['ColorSpace'] )
				&& version_compare( $this->getMagickVersion(), "6.9.0-1" ) < 0
		) {
			$animation_pre[] = '+gamma';
			$animation_pre[] = '.45455';
		}
		return $animation_pre;
	}
}
