<?php
/**
 * Handler for PNG images.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
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
	/**
	 * We do not support making APNG thumbnails, so always false
	 * @param $image File
	 * @return bool false
	 */
	function canAnimateThumbnail( $image ) {
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
			$info[] = wfMessage( 'file-info-png-looped' )->parse();
		} elseif ( $metadata['loopCount'] > 1 ) {
			$info[] = wfMessage( 'file-info-png-repeat' )->numParams( $metadata['loopCount'] )->parse();
		}
		
		if ( $metadata['frameCount'] > 0 ) {
			$info[] = wfMessage( 'file-info-png-frames' )->numParams( $metadata['frameCount'] )->parse();
		}
		
		if ( $metadata['duration'] ) {
			$info[] = $wgLang->formatTimePeriod( $metadata['duration'] );
		}
		
		return $wgLang->commaList( $info );
	}

}
