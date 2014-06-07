<?php
/**
 * Response handler for Ajax requests.
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
 * @ingroup Ajax
 */

/**
 * Handle responses for Ajax requests (send headers, print
 * content, that sort of thing)
 *
 * @ingroup Ajax
 */
class AjaxResponse {
	/**
	 * Number of seconds to get the response cached by a proxy
	 * @var int $mCacheDuration
	 */
	private $mCacheDuration;

	/**
	 * HTTP header Content-Type
	 * @var string $mContentType
	 */
	private $mContentType;

	/**
	 * Disables output. Can be set by calling $AjaxResponse->disable()
	 * @var bool $mDisabled
	 */
	private $mDisabled;

	/**
	 * Date for the HTTP header Last-modified
	 * @var string|false $mLastModified
	 */
	private $mLastModified;

	/**
	 * HTTP response code
	 * @var string $mResponseCode
	 */
	private $mResponseCode;

	/**
	 * HTTP Vary header
	 * @var string $mVary
	 */
	private $mVary;

	/**
	 * Content of our HTTP response
	 * @var string $mText
	 */
	private $mText;

	/**
	 * @param $text string|null
	 */
	function __construct( $text = null ) {
		$this->mCacheDuration = null;
		$this->mVary = null;

		$this->mDisabled = false;
		$this->mText = '';
		$this->mResponseCode = '200 OK';
		$this->mLastModified = false;
		$this->mContentType = 'application/x-wiki';

		if ( $text ) {
			$this->addText( $text );
		}
	}

	/**
	 * Set the number of seconds to get the response cached by a proxy
	 * @param $duration int
	 */
	function setCacheDuration( $duration ) {
		$this->mCacheDuration = $duration;
	}

	/**
	 * Set the HTTP Vary header
	 * @param $vary string
	 */
	function setVary( $vary ) {
		$this->mVary = $vary;
	}

	/**
	 * Set the HTTP response code
	 * @param $code string
	 */
	function setResponseCode( $code ) {
		$this->mResponseCode = $code;
	}

	/**
	 * Set the HTTP header Content-Type
	 * @param $type string
	 */
	function setContentType( $type ) {
		$this->mContentType = $type;
	}

	/**
	 * Disable output.
	 */
	function disable() {
		$this->mDisabled = true;
	}

	/**
	 * Add content to the response
	 * @param $text string
	 */
	function addText( $text ) {
		if ( ! $this->mDisabled && $text ) {
			$this->mText .= $text;
		}
	}

	/**
	 * Output text
	 */
	function printText() {
		if ( ! $this->mDisabled ) {
			print $this->mText;
		}
	}

	/**
	 * Construct the header and output it
	 */
	function sendHeaders() {
		global $wgUseSquid, $wgUseESI;

		if ( $this->mResponseCode ) {
			$n = preg_replace( '/^ *(\d+)/', '\1', $this->mResponseCode );
			header( "Status: " . $this->mResponseCode, true, (int)$n );
		}

		header ( "Content-Type: " . $this->mContentType );

		if ( $this->mLastModified ) {
			header ( "Last-Modified: " . $this->mLastModified );
		} else {
			header ( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
		}

		if ( $this->mCacheDuration ) {
			# If squid caches are configured, tell them to cache the response,
			# and tell the client to always check with the squid. Otherwise,
			# tell the client to use a cached copy, without a way to purge it.

			if ( $wgUseSquid ) {
				# Expect explicit purge of the proxy cache, but require end user agents
				# to revalidate against the proxy on each visit.
				# Surrogate-Control controls our Squid, Cache-Control downstream caches

				if ( $wgUseESI ) {
					header( 'Surrogate-Control: max-age=' . $this->mCacheDuration . ', content="ESI/1.0"' );
					header( 'Cache-Control: s-maxage=0, must-revalidate, max-age=0' );
				} else {
					header( 'Cache-Control: s-maxage=' . $this->mCacheDuration . ', must-revalidate, max-age=0' );
				}

			} else {
				# Let the client do the caching. Cache is not purged.
				header ( "Expires: " . gmdate( "D, d M Y H:i:s", time() + $this->mCacheDuration ) . " GMT" );
				header ( "Cache-Control: s-maxage={$this->mCacheDuration}," .
					"public,max-age={$this->mCacheDuration}" );
			}

		} else {
			# always expired, always modified
			header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );    // Date in the past
			header ( "Cache-Control: no-cache, must-revalidate" );  // HTTP/1.1
			header ( "Pragma: no-cache" );                          // HTTP/1.0
		}

		if ( $this->mVary ) {
			header ( "Vary: " . $this->mVary );
		}
	}

	/**
	 * checkLastModified tells the client to use the client-cached response if
	 * possible. If successful, the AjaxResponse is disabled so that
	 * any future call to AjaxResponse::printText() have no effect.
	 *
	 * @param $timestamp string
	 * @return bool Returns true if the response code was set to 304 Not Modified.
	 */
	function checkLastModified( $timestamp ) {
		global $wgCachePages, $wgCacheEpoch, $wgUser;
		$fname = 'AjaxResponse::checkLastModified';

		if ( !$timestamp || $timestamp == '19700101000000' ) {
			wfDebug( "$fname: CACHE DISABLED, NO TIMESTAMP\n", 'log' );
			return false;
		}

		if ( !$wgCachePages ) {
			wfDebug( "$fname: CACHE DISABLED\n", 'log' );
			return false;
		}

		$timestamp = wfTimestamp( TS_MW, $timestamp );
		$lastmod = wfTimestamp( TS_RFC2822, max( $timestamp, $wgUser->getTouched(), $wgCacheEpoch ) );

		if ( !empty( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) {
			# IE sends sizes after the date like this:
			# Wed, 20 Aug 2003 06:51:19 GMT; length=5202
			# this breaks strtotime().
			$modsince = preg_replace( '/;.*$/', '', $_SERVER["HTTP_IF_MODIFIED_SINCE"] );
			$modsinceTime = strtotime( $modsince );
			$ismodsince = wfTimestamp( TS_MW, $modsinceTime ? $modsinceTime : 1 );
			wfDebug( "$fname: -- client send If-Modified-Since: " . $modsince . "\n", 'log' );
			wfDebug( "$fname: --  we might send Last-Modified : $lastmod\n", 'log' );

			if ( ( $ismodsince >= $timestamp )
				&& $wgUser->validateCache( $ismodsince ) &&
				$ismodsince >= $wgCacheEpoch
			) {
				ini_set( 'zlib.output_compression', 0 );
				$this->setResponseCode( "304 Not Modified" );
				$this->disable();
				$this->mLastModified = $lastmod;

				wfDebug( "$fname: CACHED client: $ismodsince ; user: {$wgUser->getTouched()} ; " .
					"page: $timestamp ; site $wgCacheEpoch\n", 'log' );

				return true;
			} else {
				wfDebug( "$fname: READY  client: $ismodsince ; user: {$wgUser->getTouched()} ; " .
					"page: $timestamp ; site $wgCacheEpoch\n", 'log' );
				$this->mLastModified = $lastmod;
			}
		} else {
			wfDebug( "$fname: client did not send If-Modified-Since header\n", 'log' );
			$this->mLastModified = $lastmod;
		}
		return false;
	}

	/**
	 * @param $mckey string
	 * @param $touched int
	 * @return bool
	 */
	function loadFromMemcached( $mckey, $touched ) {
		global $wgMemc;

		if ( !$touched ) {
			return false;
		}

		$mcvalue = $wgMemc->get( $mckey );
		if ( $mcvalue ) {
			# Check to see if the value has been invalidated
			if ( $touched <= $mcvalue['timestamp'] ) {
				wfDebug( "Got $mckey from cache\n" );
				$this->mText = $mcvalue['value'];

				return true;
			} else {
				wfDebug( "$mckey has expired\n" );
			}
		}

		return false;
	}

	/**
	 * @param $mckey string
	 * @param $expiry int
	 * @return bool
	 */
	function storeInMemcached( $mckey, $expiry = 86400 ) {
		global $wgMemc;

		$wgMemc->set( $mckey,
			array(
				'timestamp' => wfTimestampNow(),
				'value' => $this->mText
			), $expiry
		);

		return true;
	}
}
