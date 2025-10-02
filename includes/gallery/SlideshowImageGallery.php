<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Context\IContextSource;

/**
 * A slideshow gallery shows one image at a time with controls to move around.
 */
class SlideshowImageGallery extends TraditionalImageGallery {
	/** @inheritDoc */
	public function __construct( $mode = 'traditional', ?IContextSource $context = null ) {
		parent::__construct( $mode, $context );
		// Does not support per row option.
		$this->mPerRow = 0;
	}

	/**
	 * Add javascript adds interface elements
	 * @return array
	 */
	protected function getModules() {
		return [ 'mediawiki.page.gallery.slideshow' ];
	}

	/** @inheritDoc */
	public function setAdditionalOptions( $params ) {
		$this->mAttribs['data-showthumbnails'] = isset( $params['showthumbnails'] );
	}
}
