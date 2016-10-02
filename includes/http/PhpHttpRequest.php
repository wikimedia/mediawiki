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

class PhpHttpRequest extends MWHttpRequest {

	private $fopenErrors = [];

	/**
	 * @param string $url
	 * @return string
	 */
	protected function urlToTcp( $url ) {
		$parsedUrl = parse_url( $url );

		return 'tcp://' . $parsedUrl['host'] . ':' . $parsedUrl['port'];
	}

	/**
	 * Returns an array with a 'capath' or 'cafile' key
	 * that is suitable to be merged into the 'ssl' sub-array of
	 * a stream context options array.
	 * Uses the 'caInfo' option of the class if it is provided, otherwise uses the system
	 * default CA bundle if PHP supports that, or searches a few standard locations.
	 * @return array
	 * @throws DomainException
	 */
	protected function getCertOptions() {
		$certOptions = [];
		$certLocations = [];
		if ( $this->caInfo ) {
			$certLocations = [ 'manual' => $this->caInfo ];
		} elseif ( version_compare( PHP_VERSION, '5.6.0', '<' ) ) {
			// @codingStandardsIgnoreStart Generic.Files.LineLength
			// Default locations, based on
			// https://www.happyassassin.net/2015/01/12/a-note-about-ssltls-trusted-certificate-stores-and-platforms/
			// PHP 5.5 and older doesn't have any defaults, so we try to guess ourselves.
			// PHP 5.6+ gets the CA location from OpenSSL as long as it is not set manually,
			// so we should leave capath/cafile empty there.
			// @codingStandardsIgnoreEnd
			$certLocations = array_filter( [
				getenv( 'SSL_CERT_DIR' ),
				getenv( 'SSL_CERT_PATH' ),
				'/etc/pki/tls/certs/ca-bundle.crt', # Fedora et al
				'/etc/ssl/certs',  # Debian et al
				'/etc/pki/tls/certs/ca-bundle.trust.crt',
				'/etc/pki/ca-trust/extracted/pem/tls-ca-bundle.pem',
				'/System/Library/OpenSSL', # OSX
			] );
		}

		foreach ( $certLocations as $key => $cert ) {
			if ( is_dir( $cert ) ) {
				$certOptions['capath'] = $cert;
				break;
			} elseif ( is_file( $cert ) ) {
				$certOptions['cafile'] = $cert;
				break;
			} elseif ( $key === 'manual' ) {
				// fail more loudly if a cert path was manually configured and it is not valid
				throw new DomainException( "Invalid CA info passed: $cert" );
			}
		}

		return $certOptions;
	}

	/**
	 * Custom error handler for dealing with fopen() errors.
	 * fopen() tends to fire multiple errors in succession, and the last one
	 * is completely useless (something like "fopen: failed to open stream")
	 * so normal methods of handling errors programmatically
	 * like get_last_error() don't work.
	 */
	public function errorHandler( $errno, $errstr ) {
		$n = count( $this->fopenErrors ) + 1;
		$this->fopenErrors += [ "errno$n" => $errno, "errstr$n" => $errstr ];
	}

	public function execute() {

		parent::execute();

		if ( is_array( $this->postData ) ) {
			$this->postData = wfArrayToCgi( $this->postData );
		}

		if ( $this->parsedUrl['scheme'] != 'http'
			&& $this->parsedUrl['scheme'] != 'https' ) {
			$this->status->fatal( 'http-invalid-scheme', $this->parsedUrl['scheme'] );
		}

		$this->reqHeaders['Accept'] = "*/*";
		$this->reqHeaders['Connection'] = 'Close';
		if ( $this->method == 'POST' ) {
			// Required for HTTP 1.0 POSTs
			$this->reqHeaders['Content-Length'] = strlen( $this->postData );
			if ( !isset( $this->reqHeaders['Content-Type'] ) ) {
				$this->reqHeaders['Content-Type'] = "application/x-www-form-urlencoded";
			}
		}

		// Set up PHP stream context
		$options = [
			'http' => [
				'method' => $this->method,
				'header' => implode( "\r\n", $this->getHeaderList() ),
				'protocol_version' => '1.1',
				'max_redirects' => $this->followRedirects ? $this->maxRedirects : 0,
				'ignore_errors' => true,
				'timeout' => $this->timeout,
				// Curl options in case curlwrappers are installed
				'curl_verify_ssl_host' => $this->sslVerifyHost ? 2 : 0,
				'curl_verify_ssl_peer' => $this->sslVerifyCert,
			],
			'ssl' => [
				'verify_peer' => $this->sslVerifyCert,
				'SNI_enabled' => true,
				'ciphers' => 'HIGH:!SSLv2:!SSLv3:-ADH:-kDH:-kECDH:-DSS',
				'disable_compression' => true,
			],
		];

		if ( $this->proxy ) {
			$options['http']['proxy'] = $this->urlToTcp( $this->proxy );
			$options['http']['request_fulluri'] = true;
		}

		if ( $this->postData ) {
			$options['http']['content'] = $this->postData;
		}

		if ( $this->sslVerifyHost ) {
			// PHP 5.6.0 deprecates CN_match, in favour of peer_name which
			// actually checks SubjectAltName properly.
			if ( version_compare( PHP_VERSION, '5.6.0', '>=' ) ) {
				$options['ssl']['peer_name'] = $this->parsedUrl['host'];
			} else {
				$options['ssl']['CN_match'] = $this->parsedUrl['host'];
			}
		}

		$options['ssl'] += $this->getCertOptions();

		$context = stream_context_create( $options );

		$this->headerList = [];
		$reqCount = 0;
		$url = $this->url;

		$result = [];

		if ( $this->profiler ) {
			$profileSection = $this->profiler->scopedProfileIn(
				__METHOD__ . '-' . $this->profileName
			);
		}
		do {
			$reqCount++;
			$this->fopenErrors = [];
			set_error_handler( [ $this, 'errorHandler' ] );
			$fh = fopen( $url, "r", false, $context );
			restore_error_handler();

			if ( !$fh ) {
				// HACK for instant commons.
				// If we are contacting (commons|upload).wikimedia.org
				// try again with CN_match for en.wikipedia.org
				// as php does not handle SubjectAltName properly
				// prior to "peer_name" option in php 5.6
				if ( isset( $options['ssl']['CN_match'] )
					&& ( $options['ssl']['CN_match'] === 'commons.wikimedia.org'
						|| $options['ssl']['CN_match'] === 'upload.wikimedia.org' )
				) {
					$options['ssl']['CN_match'] = 'en.wikipedia.org';
					$context = stream_context_create( $options );
					continue;
				}
				break;
			}

			$result = stream_get_meta_data( $fh );
			$this->headerList = $result['wrapper_data'];
			$this->parseHeader();

			if ( !$this->followRedirects ) {
				break;
			}

			# Handle manual redirection
			if ( !$this->isRedirect() || $reqCount > $this->maxRedirects ) {
				break;
			}
			# Check security of URL
			$url = $this->getResponseHeader( "Location" );

			if ( !Http::isValidURI( $url ) ) {
				$this->logger->debug( __METHOD__ . ": insecure redirection\n" );
				break;
			}
		} while ( true );
		if ( $this->profiler ) {
			$this->profiler->scopedProfileOut( $profileSection );
		}

		$this->setStatus();

		if ( $fh === false ) {
			if ( $this->fopenErrors ) {
				$this->logger->warning( __CLASS__
					. ': error opening connection: {errstr1}', $this->fopenErrors );
			}
			$this->status->fatal( 'http-request-error' );
			return $this->status;
		}

		if ( $result['timed_out'] ) {
			$this->status->fatal( 'http-timed-out', $this->url );
			return $this->status;
		}

		// If everything went OK, or we received some error code
		// get the response body content.
		if ( $this->status->isOK() || (int)$this->respStatus >= 300 ) {
			while ( !feof( $fh ) ) {
				$buf = fread( $fh, 8192 );

				if ( $buf === false ) {
					$this->status->fatal( 'http-read-error' );
					break;
				}

				if ( strlen( $buf ) ) {
					call_user_func( $this->callback, $fh, $buf );
				}
			}
		}
		fclose( $fh );

		return $this->status;
	}
}
