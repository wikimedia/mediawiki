<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Context\IContextSource;
use MediaWiki\FileRepo\File\File;

/**
 * Packed image gallery. All images adjusted to be same height.
 */
class PackedImageGallery extends TraditionalImageGallery {
	/** @inheritDoc */
	public function __construct( $mode = 'traditional', ?IContextSource $context = null ) {
		parent::__construct( $mode, $context );
		// Does not support per row option.
		$this->mPerRow = 0;
	}

	/**
	 * We artificially have 1.5 the resolution necessary so that
	 * we can scale it up by that much on the client side, without
	 * worrying about requesting a new image.
	 */
	private const SCALE_FACTOR = 1.5;

	/** @inheritDoc */
	protected function getVPad( $boxHeight, $thumbHeight ) {
		return ( $this->getThumbPadding() + $boxHeight - $thumbHeight / self::SCALE_FACTOR ) / 2;
	}

	/** @inheritDoc */
	protected function getThumbPadding() {
		return 0;
	}

	/** @inheritDoc */
	protected function getGBPadding() {
		return 2;
	}

	/**
	 * @param File|false $img The file being transformed. May be false
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
		return [
			'width' => (int)floor( $width * self::SCALE_FACTOR ),
			'height' => (int)floor( $this->mHeights * self::SCALE_FACTOR ),
		];
	}

	/** @inheritDoc */
	protected function getThumbDivWidth( $thumbWidth ) {
		// Require at least 60px wide, so caption is wide enough to work.
		if ( $thumbWidth < 60 * self::SCALE_FACTOR ) {
			$thumbWidth = 60 * self::SCALE_FACTOR;
		}

		return $thumbWidth / self::SCALE_FACTOR + $this->getThumbPadding();
	}

	/**
	 * @param MediaTransformOutput|false $thumb The thumbnail, or false if no
	 *   thumb (which can happen)
	 * @return float
	 */
	protected function getGBWidth( $thumb ) {
		$thumbWidth = $thumb ? $thumb->getWidth() : $this->mWidths * self::SCALE_FACTOR;

		return $this->getThumbDivWidth( $thumbWidth ) + $this->getGBPadding();
	}

	/** @inheritDoc */
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
		return [ 'mediawiki.page.gallery' ];
	}

	/**
	 * Do not support per-row on packed. It really doesn't work
	 * since the images have varying widths.
	 * @param int $num
	 */
	public function setPerRow( $num ) {
	}
}
