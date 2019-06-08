<?php
/**
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
 * MWHttpRequest implemented using internal curl compiled into PHP
 */
class CurlHttpRequest extends MWHttpRequest {
	const SUPPORTS_FILE_POSTS = true;

	protected $curlOptions = [];
	protected $headerText = "";

	/**
	 * @param resource $fh
	 * @param string $content
	 * @return int
	 */
	protected function readHeader( $fh, $content ) {
		$this->headerText .= $content;
		return strlen( $content );
	}

	/**
	 * @see MWHttpRequest::execute
	 *
	 * @throws MWException
	 * @return Status
	 */
	public function execute() {
		$this->prepare();

		if ( !$this->status->isOK() ) {
			return Status::wrap( $this->status ); // TODO B/C; move this to callers
		}

		$this->curlOptions[CURLOPT_PROXY] = $this->proxy;
		$this->curlOptions[CURLOPT_TIMEOUT] = $this->timeout;
		$this->curlOptions[CURLOPT_CONNECTTIMEOUT_MS] = $this->connectTimeout * 1000;
		$this->curlOptions[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_0;
		$this->curlOptions[CURLOPT_WRITEFUNCTION] = $this->callback;
		$this->curlOptions[CURLOPT_HEADERFUNCTION] = [ $this, "readHeader" ];
		$this->curlOptions[CURLOPT_MAXREDIRS] = $this->maxRedirects;
		$this->curlOptions[CURLOPT_ENCODING] = ""; # Enable compression

		$this->curlOptions[CURLOPT_USERAGENT] = $this->reqHeaders['User-Agent'];

		$this->curlOptions[CURLOPT_SSL_VERIFYHOST] = $this->sslVerifyHost ? 2 : 0;
		$this->curlOptions[CURLOPT_SSL_VERIFYPEER] = $this->sslVerifyCert;

		if ( $this->caInfo ) {
			$this->curlOptions[CURLOPT_CAINFO] = $this->caInfo;
		}

		if ( $this->headersOnly ) {
			$this->curlOptions[CURLOPT_NOBODY] = true;
			$this->curlOptions[CURLOPT_HEADER] = true;
		} elseif ( $this->method == 'POST' ) {
			$this->curlOptions[CURLOPT_POST] = true;
			$postData = $this->postData;
			// Don't interpret POST parameters starting with '@' as file uploads, because this
			// makes it impossible to POST plain values starting with '@' (and causes security
			// issues potentially exposing the contents of local files).
			$this->curlOptions[CURLOPT_SAFE_UPLOAD] = true;
			$this->curlOptions[CURLOPT_POSTFIELDS] = $postData;

			// Suppress 'Expect: 100-continue' header, as some servers
			// will reject it with a 417 and Curl won't auto retry
			// with HTTP 1.0 fallback
			$this->reqHeaders['Expect'] = '';
		} else {
			$this->curlOptions[CURLOPT_CUSTOMREQUEST] = $this->method;
		}

		$this->curlOptions[CURLOPT_HTTPHEADER] = $this->getHeaderList();

		$curlHandle = curl_init( $this->url );

		if ( !curl_setopt_array( $curlHandle, $this->curlOptions ) ) {
			$this->status->fatal( 'http-internal-error' );
			throw new InvalidArgumentException( "Error setting curl options." );
		}

		if ( $this->followRedirects && $this->canFollowRedirects() ) {
			Wikimedia\suppressWarnings();
			if ( !curl_setopt( $curlHandle, CURLOPT_FOLLOWLOCATION, true ) ) {
				$this->logger->debug( __METHOD__ . ": Couldn't set CURLOPT_FOLLOWLOCATION. " .
					"Probably open_basedir is set.\n" );
				// Continue the processing. If it were in curl_setopt_array,
				// processing would have halted on its entry
			}
			Wikimedia\restoreWarnings();
		}

		if ( $this->profiler ) {
			$profileSection = $this->profiler->scopedProfileIn(
				__METHOD__ . '-' . $this->profileName
			);
		}

		$curlRes = curl_exec( $curlHandle );
		if ( curl_errno( $curlHandle ) == CURLE_OPERATION_TIMEOUTED ) {
			$this->status->fatal( 'http-timed-out', $this->url );
		} elseif ( $curlRes === false ) {
			$this->status->fatal( 'http-curl-error', curl_error( $curlHandle ) );
		} else {
			$this->headerList = explode( "\r\n", $this->headerText );
		}

		curl_close( $curlHandle );

		if ( $this->profiler ) {
			$this->profiler->scopedProfileOut( $profileSection );
		}

		$this->parseHeader();
		$this->setStatus();

		return Status::wrap( $this->status ); // TODO B/C; move this to callers
	}

	/**
	 * @return bool
	 */
	public function canFollowRedirects() {
		$curlVersionInfo = curl_version();
		if ( $curlVersionInfo['version_number'] < 0x071304 ) {
			$this->logger->debug( "Cannot follow redirects with libcurl < 7.19.4 due to CVE-2009-0037\n" );
			return false;
		}

		return true;
	}
}
