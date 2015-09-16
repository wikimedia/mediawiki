<?php

class FauxRequestTest extends MediaWikiTestCase {
	/**
	 * @covers FauxRequest::setHeader
	 * @covers FauxRequest::getHeader
	 */
	public function testGetSetHeader() {
		$value = 'text/plain, text/html';

		$request = new FauxRequest();
		$request->setHeader( 'Accept', $value );

		$this->assertEquals( $request->getHeader( 'Nonexistent' ), false );
		$this->assertEquals( $request->getHeader( 'Accept' ), $value );
		$this->assertEquals( $request->getHeader( 'ACCEPT' ), $value );
		$this->assertEquals( $request->getHeader( 'accept' ), $value );
		$this->assertEquals(
			$request->getHeader( 'Accept', WebRequest::GETHEADER_LIST ),
			array( 'text/plain', 'text/html' )
		);
	}

	/**
	 * @covers FauxRequest::getAllHeaders
	 */
	public function testGetAllHeaders() {
		$_SERVER['HTTP_TEST'] = 'Example';

		$request = new FauxRequest();

		$this->assertEquals(
			array(),
			$request->getAllHeaders()
		);
	}

	/**
	 * @covers FauxRequest::getHeader
	 */
	public function testGetHeader() {
		$_SERVER['HTTP_TEST'] = 'Example';

		$request = new FauxRequest();

		$this->assertEquals(
			false,
			$request->getHeader( 'test' )
		);
	}
}
