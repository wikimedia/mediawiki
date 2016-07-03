<?php
/**
 * Handler for GIF images.
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
 * Handler for GIF images.
 *
 * @ingroup Media
 */
class GIFHandler extends BitmapHandler {
	const BROKEN_FILE = '0'; // value to store in img_metadata if error extracting metadata.

	function getMetadata( $image, $filename ) {
		try {
			$parsedGIFMetadata = BitmapMetadataHandler::GIF( $filename );
		} catch ( Exception $e ) {
			// Broken file?
			wfDebug( __METHOD__ . ': ' . $e->getMessage() . "\n" );

			return self::BROKEN_FILE;
		}

		return serialize( $parsedGIFMetadata );
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
	 * Return the standard metadata elements for #filemetadata parser func.
	 * @param File $image
	 * @return array|bool
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
		unset( $meta['metadata']['_MW_GIF_VERSION'] );

		return $meta['metadata'];
	}

	/**
	 * @todo Add unit tests
	 *
	 * @param File $image
	 * @return bool
	 */
	function getImageArea( $image ) {
		$ser = $image->getMetadata();
		if ( $ser ) {
			$metadata = unserialize( $ser );

			return $image->getWidth() * $image->getHeight() * $metadata['frameCount'];
		} else {
			return $image->getWidth() * $image->getHeight();
		}
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
	 * We cannot animate thumbnails that are bigger than a particular size
	 * @param File $file
	 * @return bool
	 */
	function canAnimateThumbnail( $file ) {
		global $wgMaxAnimatedGifArea;
		$answer = $this->getImageArea( $file ) <= $wgMaxAnimatedGifArea;

		return $answer;
	}

	function getMetadataType( $image ) {
		return 'parsed-gif';
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
			wfDebug( __METHOD__ . " invalid GIF metadata\n" );

			return self::METADATA_BAD;
		}

		if ( !isset( $data['metadata']['_MW_GIF_VERSION'] )
			|| $data['metadata']['_MW_GIF_VERSION'] != GIFMetadataExtractor::VERSION
		) {
			wfDebug( __METHOD__ . " old but compatible GIF metadata\n" );

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

		if ( !$metadata || $metadata['frameCount'] <= 1 ) {
			return $original;
		}

		/* Preserve original image info string, but strip the last char ')' so we can add even more */
		$info = [];
		$info[] = $original;

		if ( $metadata['looped'] ) {
			$info[] = wfMessage( 'file-info-gif-looped' )->parse();
		}

		if ( $metadata['frameCount'] > 1 ) {
			$info[] = wfMessage( 'file-info-gif-frames' )->numParams( $metadata['frameCount'] )->parse();
		}

		if ( $metadata['duration'] ) {
			$info[] = $wgLang->formatTimePeriod( $metadata['duration'] );
		}

		return $wgLang->commaList( $info );
	}

	/**
	 * Return the duration of the GIF file.
	 *
	 * Shown in the &query=imageinfo&iiprop=size api query.
	 *
	 * @param File $file
	 * @return float The duration of the file.
	 */
	public function getLength( $file ) {
		$serMeta = $file->getMetadata();
		MediaWiki\suppressWarnings();
		$metadata = unserialize( $serMeta );
		MediaWiki\restoreWarnings();

		if ( !$metadata || !isset( $metadata['duration'] ) || !$metadata['duration'] ) {
			return 0.0;
		} else {
			return (float)$metadata['duration'];
		}
	}
}
