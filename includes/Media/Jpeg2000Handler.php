<?php
/**
 * Handler for JPEG2000 images.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Media
 */

namespace MediaWiki\Media;

use MediaWiki\FileRepo\File\File;

/**
 * Handler for JPEG2000 images.
 * image/jp2 and image/jpx (jpeg2000 part 2)
 *
 * @ingroup Media
 */
class Jpeg2000Handler extends BitmapHandler {

	/**
	 * Not all browsers support jpeg2000
	 *
	 * @param File $file
	 * @return bool
	 */
	public function mustRender( $file ) {
		return true;
	}

	/**
	 * Render files as PNG
	 *
	 * @param string $ext
	 * @param string $mime
	 * @param array|null $params
	 * @return array
	 */
	public function getThumbType( $ext, $mime, $params = null ) {
		return [ 'png', 'image/png' ];
	}

}

/** @deprecated class alias since 1.46 */
class_alias( Jpeg2000Handler::class, 'Jpeg2000Handler' );
