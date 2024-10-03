<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\Page\PageStoreRecord;
use MediaWiki\Parser\ParserCacheFilter;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Parser\ParserCacheFilter
 */
class ParserCacheFilterTest extends MediaWikiUnitTestCase {

	private function makeParserOutput( $profile ) {
		$map = [];

		foreach ( $profile as $clock => $duration ) {
			$map[] = [ $clock, $duration ];
		}

		$mock = $this->createNoOpMock( ParserOutput::class, [ 'getTimeProfile' ] );
		$mock->method( 'getTimeProfile' )->willReturnMap( $map );
		return $mock;
	}

	private function makePageRecord() {
		return new PageStoreRecord( (object)[
			'page_id' => 7,
			'page_namespace' => NS_MAIN,
			'page_title' => 'Testing',
			'page_is_redirect' => 0,
			'page_is_new' => 0,
			'page_latest' => 12345,
			'page_touched' => '20220101334455',
		], false );
	}

	private function makeParserOptions() {
		return $this->createNoOpMock( ParserOptions::class );
	}

	public static function provideShouldCache() {
		$profile = [
			'wall' => 1.0,
			'cpu' => 0.5
		];

		yield 'no config' => [ [], $profile ];
		yield 'use default' => [
			[ 'default' => [ 'minCpuTime' => 0.25 ] ],
			$profile
		];
		yield 'ignore default' => [
			[
				'default' => [ 'minCpuTime' => 2 ],
				NS_MAIN => [ 'minCpuTime' => 0.25 ],
			],
			$profile
		];
		yield 'no profile' => [
			[ 'default' => [ 'minCpuTime' => 2 ] ],
			[]
		];
	}

	/**
	 * @dataProvider provideShouldCache
	 */
	public function testShouldCache( array $config, array $profile ) {
		$po = $this->makeParserOutput( $profile );
		$filter = new ParserCacheFilter( $config );

		$page = $this->makePageRecord();
		$options = $this->makeParserOptions();

		$this->assertTrue( $filter->shouldCache( $po, $page, $options ) );
	}

	public static function provideShouldNotCache() {
		$profile = [
			'wall' => 1.0,
			'cpu' => 0.5
		];

		yield 'use default' => [
			[ 'default' => [ 'minCpuTime' => 0.7 ] ],
			$profile
		];
		yield 'ignore default' => [
			[
				'default' => [ 'minCpuTime' => 0.25 ],
				NS_MAIN => [ 'minCpuTime' => 2 ],
			],
			$profile
		];
	}

	/**
	 * @dataProvider provideShouldNotCache
	 */
	public function testShouldNotCache( array $config, array $profile ) {
		$po = $this->makeParserOutput( $profile );
		$filter = new ParserCacheFilter( $config );

		$page = $this->makePageRecord();
		$options = $this->makeParserOptions();

		$this->assertFalse( $filter->shouldCache( $po, $page, $options ) );
	}

}
