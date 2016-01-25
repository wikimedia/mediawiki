<?php
/**
 * Testing MediaWiki WebResponse.
 *
 * Copyright © 2016 Antoine Musso <hashar@free.fr>
 * Copyright © 2016 Wikimedia Foundation Inc.
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
 * @author Antoine "hashar" Musso <hashar@free.fr>
 */

class WebResponseTest extends MediaWikiTestCase {

	/**
	 * When WebResponseSetCookie returns false, set_cookie() is not invoked
	 *
	 * @covers WebResponse::setCookie
	 */
	function testHooksReturnsFalseSkip_set_cookie() {
		Hooks::clear( 'WebResponseSetCookie' );
		Hooks::register( 'WebResponseSetCookie', function() { return false; } );

		$observer = $this->getMockBuilder( 'WebResponseTest' )
			->getMock();

		# Assertion. MediaWiki does not set a cookie
		$observer->expects( $this->exactly( 0 ) )
			->method( 'set_cookie' );

		$r = new WebResponse();
		$r->setCookie(
			__METHOD__, 'set cookie test value', 0,
			array( 'function' => array( $observer, 'set_cookie') )
		);
	}

	/**
	 * @covers WebResponse::setCookie
	 *
	 * Implementation reason:
	 *
	 * The MediaWiki method Ultimately invokes PHP built-in set_cookie(). Since
	 * PHPUnit closes the stream, the headers are emitted and set_cookie() can
	 * not be reused in another test.
	 *
	 * We could have used PHPUnit process isolation but there are a couple
	 * issues with it:
	 * - it seralize() the class and writes it to a file. protected members are
	 * serialized as: \NULL*\NULLprotected, but PHP 5.3 bails out when a .php
	 * file contains \NULL resulting in garbage being emitted back to the
	 * parent process
	 * - with later PHP version, the constructed child used to run the test
	 * require_once maintenance/doMaintenance.php before $maintClass has been
	 * set. That prevents phpunit.php wrapper from running.
	 *
	 * Instead create a stub with built-in expectations and inject as the
	 * function to be used to set the cookie (option 'function' of
	 * WebResponse::setCookie() introduced with #together with that test suite.
	 *
	 * TODO should use a dataProvider for more scenarii
	 * TODO does not test cookie deletion / behavior when already deleted
	 * TODO does not test cookie already set
	 */
	function testSetACookie() {
		Hooks::clear( 'WebResponseSetCookie' );
		Hooks::register( 'WebResponseSetCookie', function() { return true; } );

		$observer = $this->getMockBuilder( 'WebResponseTest' )
			->setMethods( array( 'set_cookie' ) )
			->getMock();

		# Assertion
		$observer->expects( $this->exactly( 1 ) )
			->method( 'set_cookie' )
			->with( $this->anything() )
			->will( $this->returnValue( true ) );

		# Test parameters
		$prefix = 'DonutsWiki-';
		$cookieName = 'cookie_name';
		$cookieValue = 'cookie test value';
		$domain = 'example.org';
		$cookiePath = '~user/wiki';

		# Actual invocation which is going to be observed
		$r = new WebResponse();
		$r->setCookie(
			$cookieName, $cookieValue,
			null,  # expiration
			array(
				'prefix' => $prefix,
				'domain' => $domain,
				'path' => $cookiePath,
				'function' => array( $observer, 'set_cookie') )
		);

		# Verify internal state of WebResponse
		# Changes the visibility of WebResponse::$setCookies automagically.
		PHPUnit_Framework_Assert::assertAttributeEquals(
			array( "{$prefix}{$cookieName}\n{$domain}\n{$cookiePath}" => array(
				# The 'function' we have passed to setCookie()
				array( $observer, 'set_cookie' ),
				# Expected internal state
				array(
					'name' => "{$prefix}{$cookieName}",
					'value' => $cookieValue,
					'expire' => 0,
					'path' => $cookiePath,
					'domain' => $domain,
					'secure' => false,
					'httpOnly' => true,
				) )
			),
			# protected variable WebResponse::$setCookies
			'setCookies', $r
		);
	}

}
