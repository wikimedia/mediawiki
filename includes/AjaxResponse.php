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
use MediaWiki\MediaWikiServices;

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
	 * @var string|bool $mLastModified
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
	 * @var Config
	 */
	private $mConfig;

	/**
	 * @param string|null $text
	 * @param Config|null $config
	 */
	function __construct( $text = null, Config $config = null ) {
		$this->mCacheDuration = null;
		$this->mVary = null;
		$this->mConfig = $config ?: MediaWikiServices::getInstance()->getMainConfig();

		$this->mDisabled = false;
		$this->mText = '';
		$this->mResponseCode = 200;
		$this->mLastModified = false;
		$this->mContentType = 'application/x-wiki';

		if ( $text ) {
			$this->addText( $text );
		}
	}

	/**
	 * Set the number of seconds to get the response cached by a proxy
	 * @param int $duration
	 */
	function setCacheDuration( $duration ) {
		$this->mCacheDuration = $duration;
	}

	/**
	 * Set the HTTP Vary header
	 * @param string $vary
	 */
	function setVary( $vary ) {
		$this->mVary = $vary;
	}

	/**
	 * Set the HTTP response code
	 * @param string $code
	 */
	function setResponseCode( $code ) {
		$this->mResponseCode = $code;
	}

	/**
	 * Set the HTTP header Content-Type
	 * @param string $type
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
	 * @param string $text
	 */
	function addText( $text ) {
		if ( !$this->mDisabled && $text ) {
			$this->mText .= $text;
		}
	}

	/**
	 * Output text
	 */
	function printText() {
		if ( !$this->mDisabled ) {
			print $this->mText;
		}
	}

	/**
	 * Construct the header and output it
	 */
	function sendHeaders() {
		if ( $this->mResponseCode ) {
			// For back-compat, it is supported that mResponseCode be a string like " 200 OK"
			// (with leading space and the status message after). Cast response code to an integer
			// to take advantage of PHP's conversion rules which will turn "  200 OK" into 200.
			// https://secure.php.net/manual/en/language.types.string.php#language.types.string.conversion
			$n = intval( trim( $this->mResponseCode ) );
			HttpStatus::header( $n );
		}

		header( "Content-Type: " . $this->mContentType );

		if ( $this->mLastModified ) {
			header( "Last-Modified: " . $this->mLastModified );
		} else {
			header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
		}

		if ( $this->mCacheDuration ) {
			# If CDN caches are configured, tell them to cache the response,
			# and tell the client to always check with the CDN. Otherwise,
			# tell the client to use a cached copy, without a way to purge it.

			if ( $this->mConfig->get( 'UseSquid' ) ) {
				# Expect explicit purge of the proxy cache, but require end user agents
				# to revalidate against the proxy on each visit.
				# Surrogate-Control controls our CDN, Cache-Control downstream caches

				if ( $this->mConfig->get( 'UseESI' ) ) {
					header( 'Surrogate-Control: max-age=' . $this->mCacheDuration . ', content="ESI/1.0"' );
					header( 'Cache-Control: s-maxage=0, must-revalidate, max-age=0' );
				} else {
					header( 'Cache-Control: s-maxage=' . $this->mCacheDuration . ', must-revalidate, max-age=0' );
				}

			} else {
				# Let the client do the caching. Cache is not purged.
				header( "Expires: " . gmdate( "D, d M Y H:i:s", time() + $this->mCacheDuration ) . " GMT" );
				header( "Cache-Control: s-maxage={$this->mCacheDuration}," .
					"public,max-age={$this->mCacheDuration}" );
			}

		} else {
			# always expired, always modified
			header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );    // Date in the past
			header( "Cache-Control: no-cache, must-revalidate" );  // HTTP/1.1
			header( "Pragma: no-cache" );                          // HTTP/1.0
		}

		if ( $this->mVary ) {
			header( "Vary: " . $this->mVary );
		}
	}

	/**
	 * checkLastModified tells the client to use the client-cached response if
	 * possible. If successful, the AjaxResponse is disabled so that
	 * any future call to AjaxResponse::printText() have no effect.
	 *
	 * @param string $timestamp
	 * @return bool Returns true if the response code was set to 304 Not Modified.
	 */
	function checkLastModified( $timestamp ) {
		global $wgCachePages, $wgCacheEpoch, $wgUser;
		$fname = 'AjaxResponse::checkLastModified';

		if ( !$timestamp || $timestamp == '19700101000000' ) {
			wfDebug( "$fname: CACHE DISABLED, NO TIMESTAMP", 'private' );
			return false;
		}

		if ( !$wgCachePages ) {
			wfDebug( "$fname: CACHE DISABLED", 'private' );
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
			$ismodsince = wfTimestamp( TS_MW, $modsinceTime ?: 1 );
			wfDebug( "$fname: -- client send If-Modified-Since: $modsince", 'private' );
			wfDebug( "$fname: --  we might send Last-Modified : $lastmod", 'private' );

			if ( ( $ismodsince >= $timestamp )
				&& $wgUser->validateCache( $ismodsince ) &&
				$ismodsince >= $wgCacheEpoch
			) {
				ini_set( 'zlib.output_compression', 0 );
				$this->setResponseCode( 304 );
				$this->disable();
				$this->mLastModified = $lastmod;

				wfDebug( "$fname: CACHED client: $ismodsince ; user: {$wgUser->getTouched()} ; " .
					"page: $timestamp ; site $wgCacheEpoch", 'private' );

				return true;
			} else {
				wfDebug( "$fname: READY  client: $ismodsince ; user: {$wgUser->getTouched()} ; " .
					"page: $timestamp ; site $wgCacheEpoch", 'private' );
				$this->mLastModified = $lastmod;
			}
		} else {
			wfDebug( "$fname: client did not send If-Modified-Since header", 'private' );
			$this->mLastModified = $lastmod;
		}
		return false;
	}

	/**
	 * @param string $mckey
	 * @param int $touched
	 * @return bool
	 */
	function loadFromMemcached( $mckey, $touched ) {
		if ( !$touched ) {
			return false;
		}

		$mcvalue = ObjectCache::getMainWANInstance()->get( $mckey );
		if ( $mcvalue ) {
			# Check to see if the value has been invalidated
			if ( $touched <= $mcvalue['timestamp'] ) {
				wfDebug( "Got $mckey from cache" );
				$this->mText = $mcvalue['value'];

				return true;
			} else {
				wfDebug( "$mckey has expired" );
			}
		}

		return false;
	}

	/**
	 * @param string $mckey
	 * @param int $expiry
	 * @return bool
	 */
	function storeInMemcached( $mckey, $expiry = 86400 ) {
		ObjectCache::getMainWANInstance()->set( $mckey,
			[
				'timestamp' => wfTimestampNow(),
				'value' => $this->mText
			], $expiry
		);

		return true;
	}
}
