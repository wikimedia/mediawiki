<?php
/**
 * Handler for JPEG images.
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
 * JPEG specific handler.
 * Inherits most stuff from BitmapHandler, just here to do the metadata handler differently.
 *
 * Metadata stuff common to Jpeg and built-in Tiff (not PagedTiffHandler) is
 * in ExifBitmapHandler.
 *
 * @ingroup Media
 */
class JpegHandler extends ExifBitmapHandler {

	function normaliseParams( $image, &$params ) {
		if ( !parent::normaliseParams( $image, $params ) ) {
			return false;
		}
		if ( isset( $params['quality'] ) && !self::validateQuality( $params['quality'] ) ) {
			return false;
		}
		return true;
	}

	public function validateParam( $name, $value ) {
		if ( $name === 'quality' ) {
			return self::validateQuality( $value );
		} else {
			return parent::validateParam( $name, $value );
		}
	}

	/** Validate and normalize quality value to be between 1 and 100 (inclusive).
	 * @param int $value Quality value, will be converted to integer or 0 if invalid
	 * @return bool True if the value is valid
	 */
	private static function validateQuality( $value ) {
		return $value === 'low';
	}

	public function makeParamString( $params ) {
		// Prepend quality as "qValue-". This has to match parseParamString() below
		$res = parent::makeParamString( $params );
		if ( $res && isset( $params['quality'] ) ) {
			$res = "q{$params['quality']}-$res";
		}
		return $res;
	}

	public function parseParamString( $str ) {
		// $str contains "qlow-200px" or "200px" strings because thumb.php would strip the filename
		// first - check if the string begins with "qlow-", and if so, treat it as quality.
		// Pass the first portion, or the whole string if "qlow-" not found, to the parent
		// The parsing must match the makeParamString() above
		$res = false;
		$m = false;
		if ( preg_match( '/q([^-]+)-(.*)$/', $str, $m ) ) {
			$v = $m[1];
			if ( self::validateQuality( $v ) ) {
				$res = parent::parseParamString( $m[2] );
				if ( $res ) {
					$res['quality'] = $v;
				}
			}
		} else {
			$res = parent::parseParamString( $str );
		}
		return $res;
	}

	function getScriptParams( $params ) {
		$res = parent::getScriptParams( $params );
		if ( isset( $params['quality'] ) ) {
			$res['quality'] = $params['quality'];
		}
		return $res;
	}

	function getMetadata( $image, $filename ) {
		try {
			$meta = BitmapMetadataHandler::Jpeg( $filename );
			if ( !is_array( $meta ) ) {
				// This should never happen, but doesn't hurt to be paranoid.
				throw new MWException( 'Metadata array is not an array' );
			}
			$meta['MEDIAWIKI_EXIF_VERSION'] = Exif::version();

			return serialize( $meta );
		} catch ( Exception $e ) {
			// BitmapMetadataHandler throws an exception in certain exceptional
			// cases like if file does not exist.
			wfDebug( __METHOD__ . ': ' . $e->getMessage() . "\n" );

			/* This used to use 0 (ExifBitmapHandler::OLD_BROKEN_FILE) for the cases
			 *   * No metadata in the file
			 *   * Something is broken in the file.
			 * However, if the metadata support gets expanded then you can't tell if the 0 is from
			 * a broken file, or just no props found. A broken file is likely to stay broken, but
			 * a file which had no props could have props once the metadata support is improved.
			 * Thus switch to using -1 to denote only a broken file, and use an array with only
			 * MEDIAWIKI_EXIF_VERSION to denote no props.
			 */

			return ExifBitmapHandler::BROKEN_FILE;
		}
	}

	/**
	 * @param File $file
	 * @param array $params Rotate parameters.
	 *    'rotation' clockwise rotation in degrees, allowed are multiples of 90
	 * @since 1.21
	 * @return bool|MediaTransformError
	 */
	public function rotate( $file, $params ) {
		global $wgJpegTran;

		$rotation = ( $params['rotation'] + $this->getRotation( $file ) ) % 360;

		if ( $wgJpegTran && is_executable( $wgJpegTran ) ) {
			$cmd = wfEscapeShellArg( $wgJpegTran ) .
				" -rotate " . wfEscapeShellArg( $rotation ) .
				" -outfile " . wfEscapeShellArg( $params['dstPath'] ) .
				" " . wfEscapeShellArg( $params['srcPath'] );
			wfDebug( __METHOD__ . ": running jpgtran: $cmd\n" );
			$retval = 0;
			$err = wfShellExecWithStderr( $cmd, $retval );
			if ( $retval !== 0 ) {
				$this->logErrorForExternalProcess( $retval, $err, $cmd );

				return new MediaTransformError( 'thumbnail_error', 0, 0, $err );
			}

			return false;
		} else {
			return parent::rotate( $file, $params );
		}
	}

	public function supportsBucketing() {
		return true;
	}

	public function sanitizeParamsForBucketing( $params ) {
		$params = parent::sanitizeParamsForBucketing( $params );

		// Quality needs to be cleared for bucketing. Buckets need to be default quality
		if ( isset( $params['quality'] ) ) {
			unset( $params['quality'] );
		}

		return $params;
	}
}
