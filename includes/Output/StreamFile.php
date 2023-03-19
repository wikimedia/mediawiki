<?php

/**
 * Functions related to the output of file content.
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
 */

namespace MediaWiki\Output;

use InvalidArgumentException;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use UploadBase;
use Wikimedia\FileBackend\FileBackend;
use Wikimedia\FileBackend\HTTPFileStreamer;

/**
 * Functions related to the output of file content
 */
class StreamFile {

	private const UNKNOWN_CONTENT_TYPE = 'unknown/unknown';

	/**
	 * Stream a file to the browser, adding all the headings and fun stuff.
	 * Headers sent include: Content-type, Content-Length, Last-Modified,
	 * and Content-Disposition.
	 *
	 * @param string $fname Full name and path of the file to stream
	 * @param array $headers Any additional headers to send if the file exists
	 * @param bool $sendErrors Send error messages if errors occur (like 404)
	 * @param array $optHeaders HTTP request header map (e.g. "range") (use lowercase keys)
	 * @param int $flags Bitfield of STREAM_* constants
	 * @return bool Success
	 */
	public static function stream(
		$fname,
		$headers = [],
		$sendErrors = true,
		$optHeaders = [],
		$flags = 0
	) {
		if ( FileBackend::isStoragePath( $fname ) ) {
			throw new InvalidArgumentException( __FUNCTION__ . " given storage path '$fname'." );
		}

		$streamer = new HTTPFileStreamer(
			$fname,
			[
				'obResetFunc' => 'wfResetOutputBuffers',
				'streamMimeFunc' => [ self::class, 'contentTypeFromPath' ],
				'headerFunc' => [ self::class, 'setHeader' ],
			]
		);

		return $streamer->stream( $headers, $sendErrors, $optHeaders, $flags );
	}

	/**
	 * @param string $header
	 *
	 * @internal
	 */
	public static function setHeader( $header ) {
		RequestContext::getMain()->getRequest()->response()->header( $header );
	}

	/**
	 * Determine the file type of a file based on the path
	 *
	 * @param string $filename Storage path or file system path
	 * @param bool $safe Whether to do retroactive upload prevention checks
	 * @return null|string
	 */
	public static function contentTypeFromPath( $filename, $safe = true ) {
		// NOTE: TrivialMimeDetection is forced by ThumbnailEntryPoint. When this
		// code is moved to a non-static method in a service object, we can no
		// longer rely on that.
		$trivialMimeDetection = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::TrivialMimeDetection );

		$ext = strrchr( $filename, '.' );
		$ext = $ext ? strtolower( substr( $ext, 1 ) ) : '';

		# trivial detection by file extension,
		# used for thumbnails (thumb.php)
		if ( $trivialMimeDetection ) {
			switch ( $ext ) {
				case 'gif':
					return 'image/gif';
				case 'png':
					return 'image/png';
				case 'jpg':
				case 'jpeg':
					return 'image/jpeg';
				case 'webp':
					return 'image/webp';
			}

			return self::UNKNOWN_CONTENT_TYPE;
		}

		$magic = MediaWikiServices::getInstance()->getMimeAnalyzer();
		// Use the extension only, rather than magic numbers, to avoid opening
		// up vulnerabilities due to uploads of files with allowed extensions
		// but disallowed types.
		$type = $magic->getMimeTypeFromExtensionOrNull( $ext );

		/**
		 * Double-check some security settings that were done on upload but might
		 * have changed since.
		 */
		if ( $safe ) {
			$mainConfig = MediaWikiServices::getInstance()->getMainConfig();
			$prohibitedFileExtensions = $mainConfig->get( MainConfigNames::ProhibitedFileExtensions );
			$checkFileExtensions = $mainConfig->get( MainConfigNames::CheckFileExtensions );
			$strictFileExtensions = $mainConfig->get( MainConfigNames::StrictFileExtensions );
			$fileExtensions = $mainConfig->get( MainConfigNames::FileExtensions );
			$verifyMimeType = $mainConfig->get( MainConfigNames::VerifyMimeType );
			$mimeTypeExclusions = $mainConfig->get( MainConfigNames::MimeTypeExclusions );
			[ , $extList ] = UploadBase::splitExtensions( $filename );
			if ( UploadBase::checkFileExtensionList( $extList, $prohibitedFileExtensions ) ) {
				return self::UNKNOWN_CONTENT_TYPE;
			}
			if (
				$checkFileExtensions &&
				$strictFileExtensions &&
				!UploadBase::checkFileExtensionList( $extList, $fileExtensions )
			) {
				return self::UNKNOWN_CONTENT_TYPE;
			}
			if ( $verifyMimeType && $type !== null && in_array( strtolower( $type ), $mimeTypeExclusions ) ) {
				return self::UNKNOWN_CONTENT_TYPE;
			}
		}
		return $type;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( StreamFile::class, 'StreamFile' );
