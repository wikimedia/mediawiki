<?php
/**
 * @file
 * @ingroup Media
 */

/**
 * Handler for PNG images.
 *
 * @ingroup Media
 */
class PNGHandler extends BitmapHandler {
	
	function getMetadata( $image, $filename ) {
		if ( !isset($image->parsedPNGMetadata) ) {
			try {
				$image->parsedPNGMetadata = PNGMetadataExtractor::getMetadata( $filename );
			} catch( Exception $e ) {
				// Broken file?
				wfDebug( __METHOD__ . ': ' . $e->getMessage() . "\n" );
				return '0';
			}
		}

		return serialize($image->parsedPNGMetadata);

	}
	
	function formatMetadata( $image ) {
		return false;
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
		return 'parsed-png';
	}
	
	function isMetadataValid( $image, $metadata ) {
		wfSuppressWarnings();
		$data = unserialize( $metadata );
		wfRestoreWarnings();
		return (boolean) $data;
	}
	function getLongDesc( $image ) {
		global $wgUser, $wgLang;
		$sk = $wgUser->getSkin();
		$original = parent::getLongDesc( $image );

		wfSuppressWarnings();
		$metadata = unserialize($image->getMetadata());
		wfRestoreWarnings();

		if( !metadata || $metadata['frameCount'] == 0 )
			return $original;

		$info[] = substr( $original, 1, strlen( $original )-2 );
		
		if ($metadata['loopCount'] == 0)
			$info[] = wfMsgExt( 'file-info-png-looped', 'parseinline' );
		elseif ($metadata['loopCount'] > 1)
			$info[] = wfMsgExt( 'file-info-png-repeat', 'parseinline', $metadata['loopCount'] );;
		
		if ($metadata['frameCount'] > 0)
			$info[] = wfMsgExt( 'file-info-png-frames', 'parseinline', $metadata['frameCount'] );
		
		if ($metadata['duration'])
			$info[] = $wgLang->formatTimePeriod( $metadata['duration'] );
		
		$infoString = $wgLang->commaList( $info );
		
		return "($infoString)";
	}

}
