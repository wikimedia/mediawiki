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

/**
 * Handler for Tiff images.
 *
 * @ingroup Media
 */
class TiffHandler extends ExifBitmapHandler {
	const EXPENSIVE_SIZE_LIMIT = 10485760; // TIFF files over 10M are considered expensive to thumbnail

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
		global $wgTiffThumbnailType;

		return (bool)$wgTiffThumbnailType
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
		global $wgTiffThumbnailType;

		return $wgTiffThumbnailType;
	}

	/**
	 * @param File|FSFile $image
	 * @param string $filename
	 * @throws MWException
	 * @return string
	 */
	public function getMetadata( $image, $filename ) {
		global $wgShowEXIF;

		if ( $wgShowEXIF ) {
			try {
				$meta = BitmapMetadataHandler::Tiff( $filename );
				if ( !is_array( $meta ) ) {
					// This should never happen, but doesn't hurt to be paranoid.
					throw new MWException( 'Metadata array is not an array' );
				}
				$meta['MEDIAWIKI_EXIF_VERSION'] = Exif::version();

				return serialize( $meta );
			} catch ( Exception $e ) {
				// BitmapMetadataHandler throws an exception in certain exceptional
				// cases like if file does not exist.
				wfDebug( __METHOD__ . ': ' . $e->getMessage() . "\n" );

				return ExifBitmapHandler::BROKEN_FILE;
			}
		} else {
			return '';
		}
	}

	public function isExpensiveToThumbnail( $file ) {
		return $file->getSize() > static::EXPENSIVE_SIZE_LIMIT;
	}
}
