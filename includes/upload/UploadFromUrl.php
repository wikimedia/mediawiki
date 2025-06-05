<?php
/**
 * Backend for uploading files from a HTTP resource.
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
 * @ingroup Upload
 */

use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\Request\WebRequest;
use MediaWiki\Status\Status;

/**
 * Implements uploading from a HTTP resource.
 *
 * @ingroup Upload
 * @author Bryan Tong Minh
 * @author Michael Dale
 */
class UploadFromUrl extends UploadBase {
	/** @var string */
	protected $mUrl;

	/** @var resource|null|false */
	protected $mTmpHandle;

	/** @var array<string,bool> */
	protected static $allowedUrls = [];

	/**
	 * Checks if the user is allowed to use the upload-by-URL feature. If the
	 * user is not allowed, return the name of the user right as a string. If
	 * the user is allowed, have the parent do further permissions checking.
	 *
	 * @param Authority $performer
	 *
	 * @return bool|string
	 */
	public static function isAllowed( Authority $performer ) {
		if ( !$performer->isAllowed( 'upload_by_url' ) ) {
			return 'upload_by_url';
		}

		return parent::isAllowed( $performer );
	}

	/**
	 * Checks if the upload from URL feature is enabled
	 * @return bool
	 */
	public static function isEnabled() {
		$allowCopyUploads = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::AllowCopyUploads );

		return $allowCopyUploads && parent::isEnabled();
	}

	/**
	 * Checks whether the URL is for an allowed host
	 * The domains in the allowlist can include wildcard characters (*) in place
	 * of any of the domain levels, e.g. '*.flickr.com' or 'upload.*.gov.uk'.
	 *
	 * @param string $url
	 * @return bool
	 */
	public static function isAllowedHost( $url ) {
		$domains = self::getAllowedHosts();
		if ( !count( $domains ) ) {
			return true;
		}
		$parsedUrl = wfGetUrlUtils()->parse( $url );
		if ( !$parsedUrl ) {
			return false;
		}
		$valid = false;
		foreach ( $domains as $domain ) {
			// See if the domain for the upload matches this allowed domain
			$domainPieces = explode( '.', $domain );
			$uploadDomainPieces = explode( '.', $parsedUrl['host'] );
			if ( count( $domainPieces ) === count( $uploadDomainPieces ) ) {
				$valid = true;
				// See if all the pieces match or not (excluding wildcards)
				foreach ( $domainPieces as $index => $piece ) {
					if ( $piece !== '*' && $piece !== $uploadDomainPieces[$index] ) {
						$valid = false;
					}
				}
				if ( $valid ) {
					// We found a match, so quit comparing against the list
					break;
				}
			}
			/* Non-wildcard test
			if ( $parsedUrl['host'] === $domain ) {
				$valid = true;
				break;
			}
			*/
		}

		return $valid;
	}

	/**
	 * Provides a caching key for an upload from url set of parameters
	 * Used to set the status of an async job in UploadFromUrlJob
	 * and retreive it in frontend clients like ApiUpload. Will return the
	 * empty string if not all parameters are present.
	 *
	 * @param array $params
	 * @return string
	 */
	public static function getCacheKey( $params ) {
		if ( !isset( $params['filename'] ) || !isset( $params['url'] ) ) {
			return "";
		} else {
			// We use sha1 here to ensure we have a fixed-length string of printable
			// characters. There is no cryptography involved, so we just need a
			// relatively fast function.
			return sha1( sprintf( "%s|||%s", $params['filename'], $params['url'] ) );
		}
	}

	/**
	 * Get the caching key from a web request
	 * @param WebRequest &$request
	 *
	 * @return string
	 */
	public static function getCacheKeyFromRequest( &$request ) {
		$uploadCacheKey = $request->getText( 'wpCacheKey', $request->getText( 'key', '' ) );
		if ( $uploadCacheKey !== '' ) {
			return $uploadCacheKey;
		}
		$desiredDestName = $request->getText( 'wpDestFile' );
		if ( !$desiredDestName ) {
			$desiredDestName = $request->getText( 'wpUploadFileURL' );
		}
		return self::getCacheKey(
			[
				'filename' => $desiredDestName,
				'url' => trim( $request->getVal( 'wpUploadFileURL' ) )
			]
		);
	}

	/**
	 * @since 1.45 public
	 * @return string[]
	 */
	public static function getAllowedHosts(): array {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$domains = $config->get( MainConfigNames::CopyUploadsDomains );

		if ( $config->get( MainConfigNames::CopyUploadAllowOnWikiDomainConfig ) ) {
			$page = wfMessage( 'copyupload-allowed-domains' )->inContentLanguage()->plain();

			foreach ( explode( "\n", $page ) as $line ) {
				// Strip comments
				$line = preg_replace( "/^\\s*([^#]*)\\s*((.*)?)$/", "\\1", $line );
				// Trim whitespace
				$line = trim( $line );

				if ( $line !== '' ) {
					$domains[] = $line;
				}
			}
		}

		return $domains;
	}

	/**
	 * Checks whether the URL is not allowed.
	 *
	 * @param string $url
	 * @return bool
	 */
	public static function isAllowedUrl( $url ) {
		if ( !isset( self::$allowedUrls[$url] ) ) {
			$allowed = true;
			( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
				->onIsUploadAllowedFromUrl( $url, $allowed );
			self::$allowedUrls[$url] = $allowed;
		}

		return self::$allowedUrls[$url];
	}

	/**
	 * Get the URL of the file to be uploaded
	 * @return string
	 */
	public function getUrl() {
		return $this->mUrl;
	}

	/**
	 * Entry point for API upload
	 *
	 * @param string $name
	 * @param string $url
	 */
	public function initialize( $name, $url ) {
		$this->mUrl = $url;

		$tempPath = $this->makeTemporaryFile();
		# File size and removeTempFile will be filled in later
		$this->initializePathInfo( $name, $tempPath, 0, false );
	}

	/**
	 * Entry point for SpecialUpload
	 * @param WebRequest &$request
	 */
	public function initializeFromRequest( &$request ) {
		$desiredDestName = $request->getText( 'wpDestFile' );
		if ( !$desiredDestName ) {
			$desiredDestName = $request->getText( 'wpUploadFileURL' );
		}
		$this->initialize(
			$desiredDestName,
			trim( $request->getVal( 'wpUploadFileURL' ) )
		);
	}

	/**
	 * @param WebRequest $request
	 * @return bool
	 */
	public static function isValidRequest( $request ) {
		$user = RequestContext::getMain()->getUser();

		$url = $request->getVal( 'wpUploadFileURL' );

		return $url
			&& MediaWikiServices::getInstance()
				->getPermissionManager()
				->userHasRight( $user, 'upload_by_url' );
	}

	/**
	 * @return string
	 */
	public function getSourceType() {
		return 'url';
	}

	/**
	 * Download the file
	 *
	 * @param array $httpOptions Array of options for MWHttpRequest.
	 *   This could be used to override the timeout on the http request.
	 * @return Status
	 */
	public function fetchFile( $httpOptions = [] ) {
		$status = $this->canFetchFile();
		if ( !$status->isGood() ) {
			return $status;
		}
		return $this->reallyFetchFile( $httpOptions );
	}

	/**
	 * verify we can actually download the file
	 *
	 * @return Status
	 */
	public function canFetchFile() {
		if ( !MWHttpRequest::isValidURI( $this->mUrl ) ) {
			return Status::newFatal( 'http-invalid-url', $this->mUrl );
		}

		if ( !self::isAllowedHost( $this->mUrl ) ) {
			return Status::newFatal( 'upload-copy-upload-invalid-domain' );
		}
		if ( !self::isAllowedUrl( $this->mUrl ) ) {
			return Status::newFatal( 'upload-copy-upload-invalid-url' );
		}
		return Status::newGood();
	}

	/**
	 * Create a new temporary file in the URL subdirectory of wfTempDir().
	 *
	 * @return string Path to the file
	 */
	protected function makeTemporaryFile() {
		$tmpFile = MediaWikiServices::getInstance()->getTempFSFileFactory()
			->newTempFSFile( 'URL', 'urlupload_' );
		$tmpFile->bind( $this );

		return $tmpFile->getPath();
	}

	/**
	 * Callback: save a chunk of the result of a HTTP request to the temporary file
	 *
	 * @param mixed $req
	 * @param string $buffer
	 * @return int Number of bytes handled
	 */
	public function saveTempFileChunk( $req, $buffer ) {
		wfDebugLog( 'fileupload', 'Received chunk of ' . strlen( $buffer ) . ' bytes' );
		$nbytes = fwrite( $this->mTmpHandle, $buffer );

		if ( $nbytes == strlen( $buffer ) ) {
			$this->mFileSize += $nbytes;
		} else {
			// Well... that's not good!
			wfDebugLog(
				'fileupload',
				'Short write ' . $nbytes . '/' . strlen( $buffer ) .
					' bytes, aborting with ' . $this->mFileSize . ' uploaded so far'
			);
			fclose( $this->mTmpHandle );
			$this->mTmpHandle = false;
		}

		return $nbytes;
	}

	/**
	 * Download the file, save it to the temporary file and update the file
	 * size and set $mRemoveTempFile to true.
	 *
	 * @param array $httpOptions Array of options for MWHttpRequest
	 * @return Status
	 */
	protected function reallyFetchFile( $httpOptions = [] ) {
		$copyUploadProxy = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::CopyUploadProxy );
		$copyUploadTimeout = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::CopyUploadTimeout );

		// Note the temporary file should already be created by makeTemporaryFile()
		$this->mTmpHandle = fopen( $this->mTempPath, 'wb' );
		if ( !$this->mTmpHandle ) {
			return Status::newFatal( 'tmp-create-error' );
		}
		wfDebugLog( 'fileupload', 'Temporary file created "' . $this->mTempPath . '"' );

		$this->mRemoveTempFile = true;
		$this->mFileSize = 0;

		$options = $httpOptions + [ 'followRedirects' => false ];

		if ( $copyUploadProxy !== false ) {
			$options['proxy'] = $copyUploadProxy;
		}

		if ( $copyUploadTimeout && !isset( $options['timeout'] ) ) {
			$options['timeout'] = $copyUploadTimeout;
		}
		wfDebugLog(
			'fileupload',
			'Starting download from "' . $this->mUrl . '" ' .
				'<' . implode( ',', array_keys( array_filter( $options ) ) ) . '>'
		);

		// Manually follow any redirects up to the limit and reset the output file before each new request to prevent
		// capturing the redirect response as part of the file.
		$attemptsLeft = $options['maxRedirects'] ?? 5;
		$targetUrl = $this->mUrl;
		$requestFactory = MediaWikiServices::getInstance()->getHttpRequestFactory();
		while ( $attemptsLeft > 0 ) {
			$req = $requestFactory->create( $targetUrl, $options, __METHOD__ );
			$req->setCallback( [ $this, 'saveTempFileChunk' ] );
			$status = $req->execute();
			if ( !$req->isRedirect() ) {
				break;
			}
			$targetUrl = $req->getFinalUrl();
			// Remove redirect response content from file.
			ftruncate( $this->mTmpHandle, 0 );
			rewind( $this->mTmpHandle );
			$attemptsLeft--;
		}

		if ( $attemptsLeft == 0 ) {
			return Status::newFatal( 'upload-too-many-redirects' );
		}

		if ( $this->mTmpHandle ) {
			// File got written ok...
			fclose( $this->mTmpHandle );
			$this->mTmpHandle = null;
		} else {
			// We encountered a write error during the download...
			return Status::newFatal( 'tmp-write-error' );
		}

		// @phan-suppress-next-line PhanPossiblyUndeclaredVariable Always set after loop
		if ( $status->isOK() ) {
			wfDebugLog( 'fileupload', 'Download by URL completed successfully.' );
		} else {
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable Always set after loop
			wfDebugLog( 'fileupload', $status->getWikiText( false, false, 'en' ) );
			wfDebugLog(
				'fileupload',
				// @phan-suppress-next-line PhanPossiblyUndeclaredVariable Always set after loop
				'Download by URL completed with HTTP status ' . $req->getStatus()
			);
		}

		// @phan-suppress-next-line PhanTypeMismatchReturnNullable,PhanPossiblyUndeclaredVariable Always set after loop
		return $status;
	}
}
