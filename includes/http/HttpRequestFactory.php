<?php
namespace Mediawiki\Http;

use CurlHttpRequest;
use DomainException;
use Http;
use MediaWiki\Logger\LoggerFactory;
use MWHttpRequest;
use PhpHttpRequest;
use Profiler;

/**
 * Factory creating MWHttpRequest objects.
 */
class HttpRequestFactory {

	/**
	 * Generate a new MWHttpRequest object
	 * @param string $url Url to use
	 * @param array $options (optional) extra params to pass (see Http::request())
	 * @param string $caller The method making this request, for profiling
	 * @throws DomainException
	 * @return MWHttpRequest
	 * @see MWHttpRequest::__construct
	 */
	public function create( $url, $options = null, $caller = __METHOD__ ) {
		if ( !Http::$httpEngine ) {
			Http::$httpEngine = function_exists( 'curl_init' ) ? 'curl' : 'php';
		} elseif ( Http::$httpEngine == 'curl' && !function_exists( 'curl_init' ) ) {
			throw new DomainException( __METHOD__ . ': curl (http://php.net/curl) is not installed, but' .
			                           ' Http::$httpEngine is set to "curl"' );
		}

		if ( !is_array( $options ) ) {
			$options = [];
		}

		if ( !isset( $options['logger'] ) ) {
			$options['logger'] = LoggerFactory::getInstance( 'http' );
		}

		switch ( Http::$httpEngine ) {
			case 'curl':
				return new CurlHttpRequest( $url, $options, $caller, Profiler::instance() );
			case 'php':
				if ( !wfIniGetBool( 'allow_url_fopen' ) ) {
					throw new DomainException( __METHOD__ . ': allow_url_fopen ' .
					                           'needs to be enabled for pure PHP http requests to ' .
					                           'work. If possible, curl should be used instead. See ' .
					                           'http://php.net/curl.'
					);
				}
				return new PhpHttpRequest( $url, $options, $caller, Profiler::instance() );
			default:
				throw new DomainException( __METHOD__ . ': The setting of Http::$httpEngine is not valid.' );
		}

	}

	/**
	 * Simple function to test if we can make any sort of requests at all, using
	 * cURL or fopen()
	 * @return bool
	 */
	public function canMakeRequests() {
		return function_exists( 'curl_init' ) || wfIniGetBool( 'allow_url_fopen' );
	}

}