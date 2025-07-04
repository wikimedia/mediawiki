<?php
/**
 * Handler for Tiff images.
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

use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\ForeignAPIRepo;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Handler for Tiff images.
 *
 * @ingroup Media
 */
class TiffHandler extends ExifBitmapHandler {
	/**
	 * TIFF files over 10M are considered expensive to thumbnail
	 */
	private const EXPENSIVE_SIZE_LIMIT = 10_485_760;

	/**
	 * Conversion to PNG for inline display can be disabled here...
	 * Note scaling should work with ImageMagick, but may not with GD scaling.
	 *
	 * Files pulled from an another MediaWiki instance via ForeignAPIRepo /
	 * InstantCommons will have thumbnails managed from the remote instance,
	 * so we can skip this check.
	 *
	 * @param File $file
	 * @return bool
	 */
	public function canRender( $file ) {
		$tiffThumbnailType = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::TiffThumbnailType );

		return (bool)$tiffThumbnailType
			|| $file->getRepo() instanceof ForeignAPIRepo;
	}

	/**
	 * Browsers don't support TIFF inline generally...
	 * For inline display, we need to convert to PNG.
	 *
	 * @param File $file
	 * @return bool
	 */
	public function mustRender( $file ) {
		return true;
	}

	/**
	 * @param string $ext
	 * @param string $mime
	 * @param array|null $params
	 * @return array
	 */
	public function getThumbType( $ext, $mime, $params = null ) {
		$tiffThumbnailType = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::TiffThumbnailType );

		return $tiffThumbnailType;
	}

	/** @inheritDoc */
	public function getSizeAndMetadata( $state, $filename ) {
		$showEXIF = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::ShowEXIF );

		try {
			$meta = BitmapMetadataHandler::Tiff( $filename );
			if ( !is_array( $meta ) ) {
				// This should never happen, but doesn't hurt to be paranoid.
				throw new InvalidTiffException( 'Metadata array is not an array' );
			}
			$info = [
				'width' => $meta['ImageWidth'] ?? 0,
				'height' => $meta['ImageLength'] ?? 0,
			];
			$info = $this->applyExifRotation( $info, $meta );
			if ( $showEXIF ) {
				$meta['MEDIAWIKI_EXIF_VERSION'] = Exif::version();
				$info['metadata'] = $meta;
			}
			return $info;
		} catch ( InvalidTiffException $e ) {
			// BitmapMetadataHandler throws an exception in certain exceptional
			// cases like if file does not exist.
			wfDebug( __METHOD__ . ': ' . $e->getMessage() );

			return [ 'metadata' => [ '_error' => ExifBitmapHandler::BROKEN_FILE ] ];
		}
	}

	/** @inheritDoc */
	public function isExpensiveToThumbnail( $file ) {
		return $file->getSize() > static::EXPENSIVE_SIZE_LIMIT;
	}
}
