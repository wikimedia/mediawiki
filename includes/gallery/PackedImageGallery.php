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

	/**
	 * We divide by 1.5 because we artificially made the thumb be 1.5x resolution
	 */
	protected function getVPad( $boxHeight, $thumbHeight ) {
		return ( $this->getThumbPadding() + $boxHeight - $thumbHeight/1.5 ) / 2;
	}

	protected function getThumbPadding() {
		return 0;
	}

	protected function getGBPadding() {
		return 2;
	}

	/**
	 * @param File $img The file being transformed. May be false
	 */
	protected function getThumbParams( $img ) {
		if ( $img && $img->getMediaType() === MEDIATYPE_AUDIO ) {
			$width = $this->mWidths;
		} else {
			// We want the width not to be the constraining
			// factor, so use random big number.
			$width = $this->mHeights*10 + 100;
		}
		// *1.5 so the js has some room to manipulate sizes.
		return array(
			'width' => $width*1.5,
			'height' => $this->mHeights*1.5,
		);
	}

	protected function getThumbDivWidth( $thumbWidth ) {
		// Require at least 60px wide, so caption works ok.
		if ( $thumbWidth < 60*1.5 ) {
			$thumbWidth = 60*1.5;
		}
		return $thumbWidth/1.5 + $this->getThumbPadding();
	}

	/**
	 * @param MediaTransformOutput|bool $thumb the thumbnail, or false if no thumb (which can happen)
	 */
	protected function getGBWidth( $thumb ) {
		$thumbWidth = $thumb ? $thumb->getWidth()/1.5 : $this->mWidths;
		// Require at least 60px wide, so caption works ok.
		if ( $thumbWidth < 60 ) {
			$thumbWidth = 60;
		}
		return $thumbWidth + $this->getThumbPadding() + $this->getGBPadding();
	}

	protected function adjustImageParameters( $thumb, &$imageParameters ) {
		// Re-adjust back to normal size.
		$imageParameters['override-width'] = ceil( $thumb->getWidth() / 1.5 );
		$imageParameters['override-height'] = ceil( $thumb->getHeight() / 1.5 );
	}

	/**
	 * Add javascript which auto-justifies the rows by manipulating the image sizes.
	 * Also ensures that the hover version of this degrades gracefully.
	 */
	protected function getModules() {
		return array( 'mediawiki.page.gallery' );
	}
}
