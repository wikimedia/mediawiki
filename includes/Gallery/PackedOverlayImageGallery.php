<?php
/**
 * @license GPL-2.0-or-later
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
	 * @param MediaTransformOutput|false $thumb The thumb this caption is for
	 *   or false for bad image.
	 * @return string
	 */
	protected function wrapGalleryText( $galleryText, $thumb ) {
		// If we have no text, do not output anything to avoid
		// ugly white overlay.
		if ( trim( $galleryText ) === '' ) {
			return '';
		}

		$thumbWidth = $this->getGBWidth( $thumb ) - $this->getThumbPadding() - $this->getGBPadding();
		$captionWidth = ceil( $thumbWidth - 20 );

		$outerWrapper = '<div class="gallerytextwrapper" style="width: ' . $captionWidth . 'px">';

		return "\n\t\t\t" .
			$outerWrapper . '<div class="gallerytext">' . $galleryText . "</div>"
			. "\n\t\t\t</div>";
	}
}
