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

	const BROKEN_FILE = '0';

	/**
	 * @param File $image
	 * @param string $filename
	 * @return string
	 */
	function getMetadata( $image, $filename ) {
		try {
			$metadata = BitmapMetadataHandler::PNG( $filename );
		} catch( Exception $e ) {
			// Broken file?
			wfDebug( __METHOD__ . ': ' . $e->getMessage() . "\n" );
			return self::BROKEN_FILE;
		}

		return serialize($metadata);
	}

	/**
	 * @param $image File
	 * @return array|bool
	 */
	function formatMetadata( $image ) {
		$meta = $image->getMetadata();

		if ( !$meta ) {
			return false;
		}
		$meta = unserialize( $meta );
		if ( !isset( $meta['metadata'] ) || count( $meta['metadata'] ) <= 1 ) {
			return false;
		}

		if ( isset( $meta['metadata']['_MW_PNG_VERSION'] ) ) {
			unset( $meta['metadata']['_MW_PNG_VERSION'] );
		}
		return $this->formatMetadataHelper( $meta['metadata'] );
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

		if ( $metadata === self::BROKEN_FILE ) {
			// Do not repetitivly regenerate metadata on broken file.
			return self::METADATA_GOOD;
		}

		wfSuppressWarnings();
		$data = unserialize( $metadata );
		wfRestoreWarnings();

		if ( !$data || !is_array( $data ) ) {
			wfDebug(__METHOD__ . ' invalid png metadata' );
			return self::METADATA_BAD;
		}

		if ( !isset( $data['metadata']['_MW_PNG_VERSION'] )
			|| $data['metadata']['_MW_PNG_VERSION'] != PNGMetadataExtractor::VERSION ) {
			wfDebug(__METHOD__ . ' old but compatible png metadata' );
			return self::METADATA_COMPATIBLE;
		}
		return self::METADATA_GOOD;
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
		
		if ( $metadata['loopCount'] == 0 ) {
			$info[] = wfMsgExt( 'file-info-png-looped', 'parseinline' );
		} elseif ( $metadata['loopCount'] > 1 ) {
			$info[] = wfMsgExt( 'file-info-png-repeat', 'parseinline', $metadata['loopCount'] );
		}
		
		if ( $metadata['frameCount'] > 0 ) {
			$info[] = wfMsgExt( 'file-info-png-frames', 'parseinline', $metadata['frameCount'] );
		}
		
		if ( $metadata['duration'] ) {
			$info[] = $wgLang->formatTimePeriod( $metadata['duration'] );
		}
		
		return $wgLang->commaList( $info );
	}

}
