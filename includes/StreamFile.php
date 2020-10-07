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

/**
 * Functions related to the output of file content
 */
class StreamFile {
	// Do not send any HTTP headers unless requested by caller (e.g. body only)
	/** @deprecated since 1.34 */
	public const STREAM_HEADLESS = HTTPFileStreamer::STREAM_HEADLESS;
	// Do not try to tear down any PHP output buffers
	/** @deprecated since 1.34 */
	public const STREAM_ALLOW_OB = HTTPFileStreamer::STREAM_ALLOW_OB;

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
	 * @throws MWException
	 * @return bool Success
	 */
	public static function stream(
		$fname, $headers = [], $sendErrors = true, $optHeaders = [], $flags = 0
	) {
		if ( FileBackend::isStoragePath( $fname ) ) { // sanity
			throw new InvalidArgumentException( __FUNCTION__ . " given storage path '$fname'." );
		}

		$streamer = new HTTPFileStreamer(
			$fname,
			[
				'obResetFunc' => 'wfResetOutputBuffers',
				'streamMimeFunc' => [ __CLASS__, 'contentTypeFromPath' ]
			]
		);

		return $streamer->stream( $headers, $sendErrors, $optHeaders, $flags );
	}

	/**
	 * Send out a standard 404 message for a file
	 *
	 * @param string $fname Full name and path of the file to stream
	 * @param int $flags Bitfield of STREAM_* constants
	 * @since 1.24
	 * @deprecated since 1.34, use HTTPFileStreamer::send404Message() instead
	 */
	public static function send404Message( $fname, $flags = 0 ) {
		wfDeprecated( __METHOD__, '1.34' );
		HTTPFileStreamer::send404Message( $fname, $flags );
	}

	/**
	 * Convert a Range header value to an absolute (start, end) range tuple
	 *
	 * @param string $range Range header value
	 * @param int $size File size
	 * @return array|string Returns error string on failure (start, end, length)
	 * @since 1.24
	 * @deprecated since 1.34, use HTTPFileStreamer::parseRange() instead
	 */
	public static function parseRange( $range, $size ) {
		wfDeprecated( __METHOD__, '1.34' );
		return HTTPFileStreamer::parseRange( $range, $size );
	}

	/**
	 * Determine the file type of a file based on the path
	 *
	 * @param string $filename Storage path or file system path
	 * @param bool $safe Whether to do retroactive upload blacklist checks
	 * @return null|string
	 */
	public static function contentTypeFromPath( $filename, $safe = true ) {
		global $wgTrivialMimeDetection;

		$ext = strrchr( $filename, '.' );
		$ext = $ext ? strtolower( substr( $ext, 1 ) ) : '';

		# trivial detection by file extension,
		# used for thumbnails (thumb.php)
		if ( $wgTrivialMimeDetection ) {
			switch ( $ext ) {
				case 'gif':
					return 'image/gif';
				case 'png':
					return 'image/png';
				case 'jpg':
				case 'jpeg':
					return 'image/jpeg';
			}

			return 'unknown/unknown';
		}

		$magic = MediaWiki\MediaWikiServices::getInstance()->getMimeAnalyzer();
		// Use the extension only, rather than magic numbers, to avoid opening
		// up vulnerabilities due to uploads of files with allowed extensions
		// but disallowed types.
		$type = $magic->getMimeTypeFromExtensionOrNull( $ext );

		/**
		 * Double-check some security settings that were done on upload but might
		 * have changed since.
		 */
		if ( $safe ) {
			global $wgFileBlacklist, $wgCheckFileExtensions, $wgStrictFileExtensions,
				$wgFileExtensions, $wgVerifyMimeType, $wgMimeTypeBlacklist;
			list( , $extList ) = UploadBase::splitExtensions( $filename );
			if ( UploadBase::checkFileExtensionList( $extList, $wgFileBlacklist ) ) {
				return 'unknown/unknown';
			}
			if ( $wgCheckFileExtensions && $wgStrictFileExtensions
				&& !UploadBase::checkFileExtensionList( $extList, $wgFileExtensions )
			) {
				return 'unknown/unknown';
			}
			if ( $wgVerifyMimeType && in_array( strtolower( $type ), $wgMimeTypeBlacklist ) ) {
				return 'unknown/unknown';
			}
		}
		return $type;
	}
}
