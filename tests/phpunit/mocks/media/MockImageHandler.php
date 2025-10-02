<?php
/**
 * Fake handler for images.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Media
 */

use MediaWiki\FileRepo\File\File;

/**
 * Mock handler for images.
 *
 * This is really intended for unit testing.
 *
 * @ingroup Media
 */
class MockImageHandler {

	/**
	 * Override BitmapHandler::doTransform() making sure we do not generate
	 * a thumbnail at all. That is merely returning a ThumbnailImage that
	 * will be consumed by the unit test.  There is no need to create a real
	 * thumbnail on the filesystem.
	 * @param ImageHandler $that
	 * @param File $image
	 * @param string $dstPath
	 * @param string $dstUrl
	 * @param array $params
	 * @param int $flags
	 * @return ThumbnailImage
	 */
	public static function doFakeTransform( $that, $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
		# Example of what we receive:
		# $image: LocalFile
		# $dstPath: /tmp/transform_7d0a7a2f1a09-1.jpg
		# $dstUrl : http://example.com/images/thumb/0/09/Bad.jpg/320px-Bad.jpg
		# $params:  width: 320,  descriptionUrl http://trunk.dev/wiki/File:Bad.jpg

		$that->normaliseParams( $image, $params );

		$scalerParams = [
			# The size to which the image will be resized
			'physicalWidth' => $params['physicalWidth'],
			'physicalHeight' => $params['physicalHeight'],
			'physicalDimensions' => "{$params['physicalWidth']}x{$params['physicalHeight']}",
			# The size of the image on the page
			'clientWidth' => $params['width'],
			'clientHeight' => $params['height'],
			# Comment as will be added to the EXIF of the thumbnail
			'comment' => isset( $params['descriptionUrl'] ) ?
					"File source: {$params['descriptionUrl']}" : '',
			# Properties of the original image
			'srcWidth' => $image->getWidth(),
			'srcHeight' => $image->getHeight(),
			'mimeType' => $image->getMimeType(),
			'dstPath' => $dstPath,
			'dstUrl' => $dstUrl,
		];

		# In some cases, we do not bother generating a thumbnail.
		if ( !$image->mustRender() &&
			$scalerParams['physicalWidth'] == $scalerParams['srcWidth']
			&& $scalerParams['physicalHeight'] == $scalerParams['srcHeight']
		) {
			wfDebug( __METHOD__ . ": returning unscaled image" );
			// getClientScalingThumbnailImage is protected
			return $that->doClientImage( $image, $scalerParams );
		}

		return new ThumbnailImage( $image, $dstUrl, false, $params );
	}
}
