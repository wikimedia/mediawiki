<?php
/**
 * Handler for PNG images.
 *
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

	/**
	 * @param $image File
	 * @return bool
	 */
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

	/**
	 * @param $image File
	 * @return string
	 */
	function getLongDesc( $image ) {
		global $wgLang;
		$original = parent::getLongDesc( $image );

		wfSuppressWarnings();
		$metadata = unserialize($image->getMetadata());
		wfRestoreWarnings();

		if( !$metadata || $metadata['frameCount'] <= 0 )
			return $original;

		$info = array();
		$info[] = $original;
		
		if ($metadata['loopCount'] == 0)
			$info[] = wfMsgExt( 'file-info-png-looped', 'parseinline' );
		elseif ($metadata['loopCount'] > 1)
			$info[] = wfMsgExt( 'file-info-png-repeat', 'parseinline', $metadata['loopCount'] );
		
		if ($metadata['frameCount'] > 0)
			$info[] = wfMsgExt( 'file-info-png-frames', 'parseinline', $metadata['frameCount'] );
		
		if ($metadata['duration'])
			$info[] = $wgLang->formatTimePeriod( $metadata['duration'] );
		
		return $wgLang->commaList( $info );
	}

}
