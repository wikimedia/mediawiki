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

use MediaWiki\FileRepo\File\File;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Shell\Shell;

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
	private const SRGB_EXIF_COLOR_SPACE = 'sRGB';
	private const SRGB_ICC_PROFILE_DESCRIPTION = 'sRGB IEC61966-2.1';

	/** @inheritDoc */
	public function normaliseParams( $image, &$params ) {
		if ( !parent::normaliseParams( $image, $params ) ) {
			return false;
		}
		if ( isset( $params['quality'] ) && !self::validateQuality( $params['quality'] ) ) {
			return false;
		}
		return true;
	}

	/** @inheritDoc */
	public function validateParam( $name, $value ) {
		if ( $name === 'quality' ) {
			return self::validateQuality( $value );
		}
		return parent::validateParam( $name, $value );
	}

	/** Validate and normalize quality value to be between 1 and 100 (inclusive).
	 * @param string $value Quality value, will be converted to integer or 0 if invalid
	 * @return bool True if the value is valid
	 */
	private static function validateQuality( $value ) {
		return $value === 'low';
	}

	/** @inheritDoc */
	public function makeParamString( $params ) {
		// Prepend quality as "qValue-". This has to match parseParamString() below
		$res = parent::makeParamString( $params );
		if ( $res && isset( $params['quality'] ) ) {
			$res = "q{$params['quality']}-$res";
		}
		return $res;
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
	protected function getScriptParams( $params ) {
		$res = parent::getScriptParams( $params );
		if ( isset( $params['quality'] ) ) {
			$res['quality'] = $params['quality'];
		}
		return $res;
	}

	/** @inheritDoc */
	public function getSizeAndMetadata( $state, $filename ) {
		try {
			$meta = BitmapMetadataHandler::Jpeg( $filename );
			if ( !is_array( $meta ) ) {
				// This should never happen, but doesn't hurt to be paranoid.
				throw new InvalidJpegException( 'Metadata array is not an array' );
			}
			$meta['MEDIAWIKI_EXIF_VERSION'] = Exif::version();

			$info = [
				'width' => $meta['SOF']['width'] ?? 0,
				'height' => $meta['SOF']['height'] ?? 0,
			];
			if ( isset( $meta['SOF']['bits'] ) ) {
				$info['bits'] = $meta['SOF']['bits'];
			}
			$info = $this->applyExifRotation( $info, $meta );
			unset( $meta['SOF'] );
			$info['metadata'] = $meta;
			return $info;
		} catch ( InvalidJpegException $e ) {
			wfDebug( __METHOD__ . ': ' . $e->getMessage() );

			// This used to return an integer-like string from getMetadata(),
			// producing a value which could not be unserialized in
			// img_metadata. The "_error" array key matches the legacy
			// unserialization for such image rows.
			return [ 'metadata' => [ '_error' => ExifBitmapHandler::BROKEN_FILE ] ];
		}
	}

	/**
	 * @param File $file
	 * @param array $params Rotate parameters.
	 *    'rotation' clockwise rotation in degrees, allowed are multiples of 90
	 * @since 1.21
	 * @return MediaTransformError|false
	 */
	public function rotate( $file, $params ) {
		$jpegTran = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::JpegTran );

		$rotation = ( $params['rotation'] + $this->getRotation( $file ) ) % 360;

		if ( $jpegTran && is_executable( $jpegTran ) ) {
			$command = Shell::command( $jpegTran,
				'-rotate',
				(string)$rotation,
				'-outfile',
				$params['dstPath'],
				$params['srcPath']
			);
			$result = $command
				->includeStderr()
				->execute();
			if ( $result->getExitCode() !== 0 ) {
				$this->logErrorForExternalProcess( $result->getExitCode(),
					$result->getStdout(),
					$command
				);

				return new MediaTransformError( 'thumbnail_error', 0, 0, $result->getStdout() );
			}

			return false;
		}
		return parent::rotate( $file, $params );
	}

	/** @inheritDoc */
	public function supportsBucketing() {
		return true;
	}

	/** @inheritDoc */
	public function sanitizeParamsForBucketing( $params ) {
		$params = parent::sanitizeParamsForBucketing( $params );

		// Quality needs to be cleared for bucketing. Buckets need to be default quality
		unset( $params['quality'] );

		return $params;
	}

	/**
	 * @inheritDoc
	 */
	protected function transformImageMagick( $image, $params ) {
		$useTinyRGBForJPGThumbnails = MediaWikiServices::getInstance()
			->getMainConfig()->get( MainConfigNames::UseTinyRGBForJPGThumbnails );

		$ret = parent::transformImageMagick( $image, $params );

		if ( $ret ) {
			return $ret;
		}

		if ( $useTinyRGBForJPGThumbnails ) {
			// T100976 If the profile embedded in the JPG is sRGB, swap it for the smaller
			// (and free) TinyRGB

			/**
			 * We'll want to replace the color profile for JPGs:
			 * * in the sRGB color space, or with the sRGB profile
			 *   (other profiles will be left untouched)
			 * * without color space or profile, in which case browsers
			 *   should assume sRGB, but don't always do (e.g. on wide-gamut
			 *   monitors (unless it's meant for low bandwidth)
			 * @see https://phabricator.wikimedia.org/T134498
			 */
			$colorSpaces = [ self::SRGB_EXIF_COLOR_SPACE, '-' ];
			$profiles = [ self::SRGB_ICC_PROFILE_DESCRIPTION ];

			// we'll also add TinyRGB profile to images lacking a profile, but
			// only if they're not low quality (which are meant to save bandwidth
			// and we don't want to increase the filesize by adding a profile)
			if ( isset( $params['quality'] ) && $params['quality'] > 30 ) {
				$profiles[] = '-';
			}

			$this->swapICCProfile(
				$params['dstPath'],
				$colorSpaces,
				$profiles,
				realpath( __DIR__ ) . '/tinyrgb.icc'
			);
		}

		return false;
	}

	/**
	 * Swaps an embedded ICC profile for another, if found.
	 * Depends on exiftool, no-op if not installed.
	 * @param string $filepath File to be manipulated (will be overwritten)
	 * @param array $colorSpaces Only process files with this/these Color Space(s)
	 * @param array $oldProfileStrings Exact name(s) of color profile to look for
	 *  (the one that will be replaced)
	 * @param string $profileFilepath ICC profile file to apply to the file
	 * @since 1.26
	 * @return bool
	 */
	public function swapICCProfile( $filepath, array $colorSpaces,
		array $oldProfileStrings, $profileFilepath
	) {
		$exiftool = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::Exiftool );

		if ( !$exiftool || !is_executable( $exiftool ) ) {
			return false;
		}

		$result = Shell::command(
			$exiftool,
			'-EXIF:ColorSpace',
			'-ICC_Profile:ProfileDescription',
			'-S',
			'-T',
			$filepath
		)
			->includeStderr()
			->execute();

		// Explode EXIF data into an array with [0 => Color Space, 1 => Device Model Desc]
		$data = explode( "\t", trim( $result->getStdout() ), 3 );

		if ( $result->getExitCode() !== 0 ) {
			return false;
		}

		// Make a regex out of the source data to match it to an array of color
		// spaces in a case-insensitive way
		$colorSpaceRegex = '/' . preg_quote( $data[0], '/' ) . '/i';
		if ( !preg_grep( $colorSpaceRegex, $colorSpaces ) ) {
			// We can't establish that this file matches the color space, don't process it
			return false;
		}

		$profileRegex = '/' . preg_quote( $data[1], '/' ) . '/i';
		if ( !preg_grep( $profileRegex, $oldProfileStrings ) ) {
			// We can't establish that this file has the expected ICC profile, don't process it
			return false;
		}

		$command = Shell::command( $exiftool,
			'-overwrite_original',
			'-icc_profile<=' . $profileFilepath,
			$filepath
		);
		$result = $command
			->includeStderr()
			->execute();

		if ( $result->getExitCode() !== 0 ) {
			$this->logErrorForExternalProcess( $result->getExitCode(),
				$result->getStdout(),
				$command
			);

			return false;
		}

		return true;
	}
}
