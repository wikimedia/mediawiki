<?php
class MockMediaHandler extends BitmapHandler {

	function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
		# $image: LocalFile
		# $dstPath: /var/folders/wz/nbgqkfqx1_zcsqz5l9j5x_th0000gn/T//transform_7d0a7a2f1a09-1.jpg
		# $dstUrl : http://example.com/images/thumb/0/09/Bad.jpg/320px-Bad.jpg
		# $params:  width: 320,  descriptionUrl http://trunk.dev/wiki/File:Bad.jpg
		$thumbPath = $image->getThumbPath();
		$this->normaliseParams( $image, $params );

		$scalerParams = array(
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
		);

		if ( !$image->mustRender() &&
			$scalerParams['physicalWidth'] == $scalerParams['srcWidth']
			&& $scalerParams['physicalHeight'] == $scalerParams['srcHeight'] ) {
			wfDebug( __METHOD__ . ": returning unscaled image\n" );
			return $this->getClientScalingThumbnailImage( $image, $scalerParams );
		}

		wfDebug( __METHOD__ . " with params: " . serialize( $params ) . "\n" );
		return new ThumbnailImage( $image, $dstUrl, false, $params );
	}
}
