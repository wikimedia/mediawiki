<?php

/**
 * Functions related to the output of file content.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Output;

use InvalidArgumentException;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Upload\UploadBase;
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
				'obResetFunc' => wfResetOutputBuffers( ... ),
				'streamMimeFunc' => self::contentTypeFromPath( ... ),
				'headerFunc' => static function ( string $header ): void {
					RequestContext::getMain()->getRequest()->response()->header( $header );
				},
			]
		);

		return $streamer->stream( $headers, $sendErrors, $optHeaders, $flags );
	}

	/**
	 * Determine the file type of a file based on the path
	 *
	 * @param string $filename Storage path or file system path
	 * @param bool $safe Whether to do retroactive upload prevention checks
	 * @return null|string
	 */
	public static function contentTypeFromPath( $filename, $safe = true ) {
		$services = MediaWikiServices::getInstance();
		// NOTE: TrivialMimeDetection is forced by ThumbnailEntryPoint. When this
		// code is moved to a non-static method in a service object, we can no
		// longer rely on that.
		$trivialMimeDetection = $services->getMainConfig()
			->get( MainConfigNames::TrivialMimeDetection );

		$ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );

		# trivial detection by file extension,
		# used for thumbnails (thumb.php)
		if ( $trivialMimeDetection ) {
			return match ( $ext ) {
				'gif' => 'image/gif',
				'png' => 'image/png',
				'jpg',
				'jpeg' => 'image/jpeg',
				'webp' => 'image/webp',
				default => self::UNKNOWN_CONTENT_TYPE,
			};
		}

		// Use the extension only, rather than magic numbers, to avoid opening
		// up vulnerabilities due to uploads of files with allowed extensions
		// but disallowed types.
		$type = $services->getMimeAnalyzer()->getMimeTypeFromExtensionOrNull( $ext );

		/**
		 * Double-check some security settings that were done on upload but might
		 * have changed since.
		 */
		if ( $safe ) {
			$mainConfig = $services->getMainConfig();
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
