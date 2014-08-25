<?php
/**
 * Packed image gallery. All images adjusted to be same height.
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
 */

class PackedImageGallery extends TraditionalImageGallery {
	function __construct( $mode = 'traditional' ) {
		parent::__construct( $mode );
		// Does not support per row option.
		$this->mPerRow = 0;
	}

	/**
	 * We artificially have 1.5 the resolution neccessary so that
	 * we can scale it up by that much on the client side, without
	 * worrying about requesting a new image.
	 */
	const SCALE_FACTOR = 1.5;

	protected function getVPad( $boxHeight, $thumbHeight ) {
		return ( $this->getThumbPadding() + $boxHeight - $thumbHeight / self::SCALE_FACTOR ) / 2;
	}

	protected function getThumbPadding() {
		return 0;
	}

	protected function getGBPadding() {
		return 2;
	}

	/**
	 * @param File $img The file being transformed. May be false
	 * @return array
	 */
	protected function getThumbParams( $img ) {
		if ( $img && $img->getMediaType() === MEDIATYPE_AUDIO ) {
			$width = $this->mWidths;
		} else {
			// We want the width not to be the constraining
			// factor, so use random big number.
			$width = $this->mHeights * 10 + 100;
		}

		// self::SCALE_FACTOR so the js has some room to manipulate sizes.
		return array(
			'width' => $width * self::SCALE_FACTOR,
			'height' => $this->mHeights * self::SCALE_FACTOR,
		);
	}

	protected function getThumbDivWidth( $thumbWidth ) {
		// Require at least 60px wide, so caption is wide enough to work.
		if ( $thumbWidth < 60 * self::SCALE_FACTOR ) {
			$thumbWidth = 60 * self::SCALE_FACTOR;
		}

		return $thumbWidth / self::SCALE_FACTOR + $this->getThumbPadding();
	}

	/**
	 * @param MediaTransformOutput|bool $thumb The thumbnail, or false if no
	 *   thumb (which can happen)
	 * @return float
	 */
	protected function getGBWidth( $thumb ) {
		$thumbWidth = $thumb ? $thumb->getWidth() : $this->mWidths * self::SCALE_FACTOR;

		return $this->getThumbDivWidth( $thumbWidth ) + $this->getGBPadding();
	}

	protected function adjustImageParameters( $thumb, &$imageParameters ) {
		// Re-adjust back to normal size.
		$imageParameters['override-width'] = ceil( $thumb->getWidth() / self::SCALE_FACTOR );
		$imageParameters['override-height'] = ceil( $thumb->getHeight() / self::SCALE_FACTOR );
	}

	/**
	 * Add javascript which auto-justifies the rows by manipulating the image sizes.
	 * Also ensures that the hover version of this degrades gracefully.
	 * @return array
	 */
	protected function getModules() {
		return array( 'mediawiki.page.gallery' );
	}

	/**
	 * Do not support per-row on packed. It really doesn't work
	 * since the images have varying widths.
	 * @param int $num
	 */
	public function setPerRow( $num ) {
		return;
	}
}
