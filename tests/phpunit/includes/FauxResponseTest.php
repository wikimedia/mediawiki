<?php
/**
 * Tests for the FauxResponse class
 *
 * Copyright @ 2011 Alexandre Emsenhuber
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

class FauxResponseTest extends MediaWikiTestCase {
	/** @var FauxResponse */
	protected $response;

	protected function setUp() {
		parent::setUp();
		$this->response = new FauxResponse;
	}

	/**
	 * @covers FauxResponse::getcookie
	 * @covers FauxResponse::setcookie
	 */
	public function testCookie() {
		$this->assertEquals( null, $this->response->getcookie( 'key' ), 'Non-existing cookie' );
		$this->response->setcookie( 'key', 'val' );
		$this->assertEquals( 'val', $this->response->getcookie( 'key' ), 'Existing cookie' );
	}

	/**
	 * @covers FauxResponse::getheader
	 * @covers FauxResponse::header
	 */
	public function testHeader() {
		$this->assertEquals( null, $this->response->getheader( 'Location' ), 'Non-existing header' );

		$this->response->header( 'Location: http://localhost/' );
		$this->assertEquals( 'http://localhost/', $this->response->getheader( 'Location' ), 'Set header' );

		$this->response->header( 'Location: http://127.0.0.1/' );
		$this->assertEquals( 'http://127.0.0.1/', $this->response->getheader( 'Location' ), 'Same header' );

		$this->response->header( 'Location: http://127.0.0.2/', false );
		$this->assertEquals( 'http://127.0.0.1/', $this->response->getheader( 'Location' ), 'Same header with override disabled' );

		$this->response->header( 'Location: http://localhost/' );
		$this->assertEquals( 'http://localhost/', $this->response->getheader( 'LOCATION' ), 'Get header case insensitive' );
	}

	/**
	 * @covers FauxResponse::getStatusCode
	 */
	public function testResponseCode() {
		$this->response->header( 'HTTP/1.1 200' );
		$this->assertEquals( 200, $this->response->getStatusCode(), 'Header with no message' );

		$this->response->header( 'HTTP/1.x 201' );
		$this->assertEquals( 201, $this->response->getStatusCode(), 'Header with no message and protocol 1.x' );

		$this->response->header( 'HTTP/1.1 202 OK' );
		$this->assertEquals( 202, $this->response->getStatusCode(), 'Normal header' );

		$this->response->header( 'HTTP/1.x 203 OK' );
		$this->assertEquals( 203, $this->response->getStatusCode(), 'Normal header with no message and protocol 1.x' );

		$this->response->header( 'HTTP/1.x 204 OK', false, 205 );
		$this->assertEquals( 205, $this->response->getStatusCode(), 'Third parameter overrides the HTTP/... header' );

		$this->response->header( 'Location: http://localhost/', false, 206 );
		$this->assertEquals( 206, $this->response->getStatusCode(), 'Third parameter with another header' );
	}
}
