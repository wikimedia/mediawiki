<?php
/**
 * Handler for GIF images.
 *
 * @file
 * @ingroup Media
 */

/**
 * Handler for GIF images.
 *
 * @ingroup Media
 */
class GIFHandler extends BitmapHandler {
	
	function getMetadata( $image, $filename ) {
		if ( !isset( $image->parsedGIFMetadata ) ) {
			try {
				$image->parsedGIFMetadata = GIFMetadataExtractor::getMetadata( $filename );
			} catch( Exception $e ) {
				// Broken file?
				wfDebug( __METHOD__ . ': ' . $e->getMessage() . "\n" );
				return '0';
			}
		}

		return serialize( $image->parsedGIFMetadata );

	}
	
	function formatMetadata( $image ) {
		return false;
	}
	
	function getImageArea( $image, $width, $height ) {
		$ser = $image->getMetadata();
		if ($ser) {
			$metadata = unserialize($ser);
			return $width * $height * $metadata['frameCount'];
		} else {
			return $width * $height;
		}
	}

	function isAnimatedImage( $image ) {
		$ser = $image->getMetadata();
		if ($ser) {
			$metadata = unserialize($ser);
			if( $metadata['frameCount'] > 1 ) return true;
		}
		return false;
	}
	
	function getMetadataType( $image ) {
		return 'parsed-gif';
	}
	
	function isMetadataValid( $image, $metadata ) {
		wfSuppressWarnings();
		$data = unserialize( $metadata );
		wfRestoreWarnings();
		return (boolean) $data;
	}

	function getLongDesc( $image ) {
		global $wgLang;

		$original = parent::getLongDesc( $image );

		wfSuppressWarnings();
		$metadata = unserialize($image->getMetadata());
		wfRestoreWarnings();
		
		if (!$metadata || $metadata['frameCount'] <=  1)
			return $original;
		
		$info = array();
		$info[] = substr( $original, 1, strlen( $original )-2 );
		
		if ($metadata['looped'])
			$info[] = wfMsgExt( 'file-info-gif-looped', 'parseinline' );
		
		if ($metadata['frameCount'] > 1)
			$info[] = wfMsgExt( 'file-info-gif-frames', 'parseinline', $metadata['frameCount'] );
		
		if ($metadata['duration'])
			$info[] = $wgLang->formatTimePeriod( $metadata['duration'] );
		
		$infoString = $wgLang->commaList( $info );
		
		return "($infoString)";
	}
}
