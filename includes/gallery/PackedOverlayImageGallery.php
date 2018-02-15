<?php
/**
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

/**
 * Packed overlay image gallery. All images adjusted to be same height and
 * image caption being placed over top of image.
 */
class PackedOverlayImageGallery extends PackedImageGallery {
	/**
	 * Add the wrapper html around the thumb's caption
	 *
	 * @param string $galleryText The caption
	 * @param MediaTransformOutput|bool $thumb The thumb this caption is for
	 *   or false for bad image.
	 * @return string
	 */
	protected function wrapGalleryText( $galleryText, $thumb ) {
		// If we have no text, do not output anything to avoid
		// ugly white overlay.
		if ( trim( $galleryText ) === '' ) {
			return '';
		}

		# ATTENTION: The newline after <div class="gallerytext"> is needed to
		# accommodate htmltidy which in version 4.8.6 generated crackpot HTML
		# in its absence, see: https://phabricator.wikimedia.org/T3765
		# -Ævar

		$thumbWidth = $this->getGBWidth( $thumb ) - $this->getThumbPadding() - $this->getGBPadding();
		$captionWidth = ceil( $thumbWidth - 20 );

		$outerWrapper = '<div class="gallerytextwrapper" style="width: ' . $captionWidth . 'px">';

		return "\n\t\t\t" . $outerWrapper . '<div class="gallerytext">' . "\n"
			. $galleryText
			. "\n\t\t\t</div></div>";
	}
}

/**
 * Same as Packed except different CSS is applied to make the
 * caption only show up on hover. If a touch screen is detected,
 * falls back to PackedHoverGallery. Degrades gracefully for
 * screen readers.
 */
class PackedHoverImageGallery extends PackedOverlayImageGallery {
}
