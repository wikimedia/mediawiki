<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Rest\HeaderContainer;

/**
 * @covers \MediaWiki\Rest\HeaderContainer
 */
class HeaderContainerTest extends \MediaWikiUnitTestCase {
	public static function provideSetHeader() {
		return [
			'simple' => [
				[
					[ 'Test', 'foo' ]
				],
				[ 'Test' => [ 'foo' ] ],
				[ 'Test' => 'foo' ]
			],
			'replace' => [
				[
					[ 'Test', 'foo' ],
					[ 'Test', 'bar' ],
				],
				[ 'Test' => [ 'bar' ] ],
				[ 'Test' => 'bar' ],
			],
			'array value' => [
				[
					[ 'Test', [ '1', '2' ] ],
					[ 'Test', [ '3', '4' ] ],
				],
				[ 'Test' => [ '3', '4' ] ],
				[ 'Test' => '3, 4' ]
			],
			'preserve most recent case' => [
				[
					[ 'test', 'foo' ],
					[ 'tesT', 'bar' ],
				],
				[ 'tesT' => [ 'bar' ] ],
				[ 'tesT' => 'bar' ]
			],
			'empty' => [ [], [], [] ],
		];
	}

	/** @dataProvider provideSetHeader */
	public function testSetHeader( $setOps, $headers, $lines ) {
		$hc = new HeaderContainer;
		foreach ( $setOps as list( $name, $value ) ) {
			$hc->setHeader( $name, $value );
		}
		$this->assertSame( $headers, $hc->getHeaders() );
		$this->assertSame( $lines, $hc->getHeaderLines() );
	}

	public static function provideAddHeader() {
		return [
			'simple' => [
				[
					[ 'Test', 'foo' ]
				],
				[ 'Test' => [ 'foo' ] ],
				[ 'Test' => 'foo' ]
			],
			'add' => [
				[
					[ 'Test', 'foo' ],
					[ 'Test', 'bar' ],
				],
				[ 'Test' => [ 'foo', 'bar' ] ],
				[ 'Test' => 'foo, bar' ],
			],
			'array value' => [
				[
					[ 'Test', [ '1', '2' ] ],
					[ 'Test', [ '3', '4' ] ],
				],
				[ 'Test' => [ '1', '2', '3', '4' ] ],
				[ 'Test' => '1, 2, 3, 4' ]
			],
			'preserve original case' => [
				[
					[ 'Test', 'foo' ],
					[ 'tesT', 'bar' ],
				],
				[ 'Test' => [ 'foo', 'bar' ] ],
				[ 'Test' => 'foo, bar' ]
			],
		];
	}

	/** @dataProvider provideAddHeader */
	public function testAddHeader( $addOps, $headers, $lines ) {
		$hc = new HeaderContainer;
		foreach ( $addOps as list( $name, $value ) ) {
			$hc->addHeader( $name, $value );
		}
		$this->assertSame( $headers, $hc->getHeaders() );
		$this->assertSame( $lines, $hc->getHeaderLines() );
	}

	public static function provideRemoveHeader() {
		return [
			'simple' => [
				[ [ 'Test', 'foo' ] ],
				[ 'Test' ],
				[],
				[]
			],
			'case mismatch' => [
				[ [ 'Test', 'foo' ] ],
				[ 'tesT' ],
				[],
				[]
			],
			'remove nonexistent' => [
				[ [ 'A', '1' ] ],
				[ 'B' ],
				[ 'A' => [ '1' ] ],
				[ 'A' => '1' ]
			],
		];
	}

	/** @dataProvider provideRemoveHeader */
	public function testRemoveHeader( $addOps, $removeOps, $headers, $lines ) {
		$hc = new HeaderContainer;
		foreach ( $addOps as list( $name, $value ) ) {
			$hc->addHeader( $name, $value );
		}
		foreach ( $removeOps as $name ) {
			$hc->removeHeader( $name );
		}
		$this->assertSame( $headers, $hc->getHeaders() );
		$this->assertSame( $lines, $hc->getHeaderLines() );
	}

	public function testHasHeader() {
		$hc = new HeaderContainer;
		$hc->addHeader( 'A', '1' );
		$hc->addHeader( 'B', '2' );
		$hc->addHeader( 'C', '3' );
		$hc->removeHeader( 'B' );
		$hc->removeHeader( 'c' );
		$this->assertTrue( $hc->hasHeader( 'A' ) );
		$this->assertTrue( $hc->hasHeader( 'a' ) );
		$this->assertFalse( $hc->hasHeader( 'B' ) );
		$this->assertFalse( $hc->hasHeader( 'c' ) );
		$this->assertFalse( $hc->hasHeader( 'C' ) );
	}

	public function testGetRawHeaderLines() {
		$hc = new HeaderContainer;
		$hc->addHeader( 'A', '1' );
		$hc->addHeader( 'a', '2' );
		$hc->addHeader( 'b', '3' );
		$hc->addHeader( 'Set-Cookie', 'x' );
		$hc->addHeader( 'SET-cookie', 'y' );
		$this->assertSame(
			[
				'A: 1, 2',
				'b: 3',
				'Set-Cookie: x',
				'Set-Cookie: y',
			],
			$hc->getRawHeaderLines()
		);
	}
}
