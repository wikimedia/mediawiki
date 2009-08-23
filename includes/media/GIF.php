<?php
/**
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
		if ( !isset($image->parsedGIFMetadata) ) {
			try {
				$image->parsedGIFMetadata = GIFMetadataExtractor::getMetadata( $filename );
			} catch( Exception $e ) {
				// Broken file?
				wfDebug( __METHOD__ . ': ' . $e->getMessage() . "\n" );
				return '0';
			}
		}

		return serialize($image->parsedGIFMetadata);

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
	
	function getMetadataType( $image ) {
		return 'parsed-gif';
	}
	
	function getLongDesc( $image ) {
		global $wgUser, $wgLang;
		$sk = $wgUser->getSkin();
		
		$metadata = @unserialize($image->getMetadata());
		
		if (!$metadata) return parent::getLongDesc( $image );
		
		$info = array();
		$info[] = $image->getMimeType();
		$info[] = $sk->formatSize( $image->getSize() );
		
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
