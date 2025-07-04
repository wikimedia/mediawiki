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

use MediaWiki\Context\IContextSource;
use MediaWiki\FileRepo\File\File;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use Wikimedia\RequestTimeout\TimeoutException;

/**
 * Handler for GIF images.
 *
 * @ingroup Media
 */
class GIFHandler extends BitmapHandler {
	/**
	 * Value to store in img_metadata if there was error extracting metadata
	 */
	private const BROKEN_FILE = '0';

	/** @inheritDoc */
	public function getSizeAndMetadata( $state, $filename ) {
		try {
			$parsedGIFMetadata = BitmapMetadataHandler::GIF( $filename );
		} catch ( TimeoutException $e ) {
			throw $e;
		} catch ( Exception $e ) {
			// Broken file?
			wfDebug( __METHOD__ . ': ' . $e->getMessage() );

			return [ 'metadata' => [ '_error' => self::BROKEN_FILE ] ];
		}

		return [
			'width' => $parsedGIFMetadata['width'],
			'height' => $parsedGIFMetadata['height'],
			'bits' => $parsedGIFMetadata['bits'],
			'metadata' => array_diff_key(
				$parsedGIFMetadata,
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
	 * Return the standard metadata elements for #filemetadata parser func.
	 * @param File $image
	 * @return array
	 */
	public function getCommonMetaArray( File $image ) {
		$meta = $image->getMetadataArray();
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
	 * @return int
	 */
	public function getImageArea( $image ) {
		$metadata = $image->getMetadataArray();
		if ( isset( $metadata['frameCount'] ) && $metadata['frameCount'] > 0 ) {
			return $image->getWidth() * $image->getHeight() * $metadata['frameCount'];
		}
		return $image->getWidth() * $image->getHeight();
	}

	/**
	 * @param File $image
	 * @return bool
	 */
	public function isAnimatedImage( $image ) {
		$metadata = $image->getMetadataArray();
		if ( isset( $metadata['frameCount'] ) && $metadata['frameCount'] > 1 ) {
			return true;
		}

		return false;
	}

	/**
	 * We cannot animate thumbnails that are bigger than a particular size
	 * @param File $file
	 * @return bool
	 */
	public function canAnimateThumbnail( $file ) {
		$maxAnimatedGifArea = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::MaxAnimatedGifArea );

		return $this->getImageArea( $file ) <= $maxAnimatedGifArea;
	}

	/** @inheritDoc */
	public function getMetadataType( $image ) {
		return 'parsed-gif';
	}

	/** @inheritDoc */
	public function isFileMetadataValid( $image ) {
		$data = $image->getMetadataArray();
		if ( $data === [ '_error' => self::BROKEN_FILE ] ) {
			// Do not repetitively regenerate metadata on broken file.
			return self::METADATA_GOOD;
		}

		if ( !$data || isset( $data['_error'] ) ) {
			wfDebug( __METHOD__ . " invalid GIF metadata" );

			return self::METADATA_BAD;
		}

		if ( !isset( $data['metadata']['_MW_GIF_VERSION'] )
			|| $data['metadata']['_MW_GIF_VERSION'] !== GIFMetadataExtractor::VERSION
		) {
			wfDebug( __METHOD__ . " old but compatible GIF metadata" );

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
			$info[] = htmlspecialchars( $wgLang->formatTimePeriod( $metadata['duration'] ), ENT_QUOTES );
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
		$metadata = $file->getMetadataArray();

		if ( !$metadata || !isset( $metadata['duration'] ) || !$metadata['duration'] ) {
			return 0.0;
		}
		return (float)$metadata['duration'];
	}
}
