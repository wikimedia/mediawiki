<?php

/**
 * @license GPL-2.0-or-later
 */

/**
 * Fake handler for DjVu images.
 *
 * @ingroup Media
 */
class MockDjVuHandler extends DjVuHandler {
	/** @inheritDoc */
	public function isEnabled() {
		return true;
	}

	/** @inheritDoc */
	public function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
		if ( !$this->normaliseParams( $image, $params ) ) {
			return new TransformParameterError( $params );
		}
		$width = $params['width'];
		$height = $params['height'];
		$page = $params['page'];
		if ( $page > $this->pageCount( $image ) ) {
			return new MediaTransformError(
				'thumbnail_error',
				$width,
				$height,
				wfMessage( 'djvu_page_error' )->text()
			);
		}

		$params = [
			'width' => $width,
			'height' => $height,
			'page' => $page
		];

		return new ThumbnailImage( $image, $dstUrl, $dstPath, $params );
	}
}
