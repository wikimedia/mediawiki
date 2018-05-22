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
namespace MediaWiki\Http;

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
	public function create( $url, array $options = [], $caller = __METHOD__ ) {
		if ( !Http::$httpEngine ) {
			Http::$httpEngine = function_exists( 'curl_init' ) ? 'curl' : 'php';
		} elseif ( Http::$httpEngine == 'curl' && !function_exists( 'curl_init' ) ) {
			throw new DomainException( __METHOD__ . ': curl (https://secure.php.net/curl) is not ' .
			   'installed, but Http::$httpEngine is set to "curl"' );
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
					   'https://secure.php.net/curl.'
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
