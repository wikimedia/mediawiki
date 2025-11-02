<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * Nolines image gallery. Like "traditional" but without borders and
 * less padding.
 */
class NolinesImageGallery extends TraditionalImageGallery {
	/** @inheritDoc */
	protected function getThumbPadding() {
		return 0;
	}

	/** @inheritDoc */
	protected function getGBBorders() {
		// This accounts for extra space between <li> elements.
		return 4;
	}

	/** @inheritDoc */
	protected function getVPad( $boxHeight, $thumbHeight ) {
		return 0;
	}
}
