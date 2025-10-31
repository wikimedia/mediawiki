<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\Parser\ParserObserver;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Tests\Mocks\Content\DummyContentForTesting;
use MediaWiki\Title\Title;
use MediaWikiUnitTestCase;
use TestLogger;

/**
 * @covers \MediaWiki\Parser\ParserObserver
 */
class ParserObserverTest extends MediaWikiUnitTestCase {
	/**
	 * @param string $hashOne
	 * @param string $hashTwo
	 * @param array $expects
	 *
	 * @dataProvider provideDuplicateParse
	 */
	public function testDuplicateParse( string $hashOne, string $hashTwo, array $expects ) {
		$logger = new TestLogger( true );

		// ::makeTitle allows us to create a title without needing any services
		$title = Title::makeTitle(
			NS_PROJECT,
			'Duplicate Parse Test'
		);

		$options = $this->createNoOpMock( ParserOptions::class, [ 'optionsHash' ] );
		$options->method( 'optionsHash' )->willReturnOnConsecutiveCalls( $hashOne, $hashTwo );

		$content = new DummyContentForTesting( "hello world" );

		$output = new ParserOutput();
		$observer = new ParserObserver( $logger );
		$observer->notifyParse( $title, null, $options, $content, $output );
		$observer->notifyParse( $title, null, $options, $content, $output );

		$this->assertArrayEquals( $expects, $logger->getBuffer() );
	}

	public static function provideDuplicateParse() {
		yield [
			'foo',
			'bar',
			[]
		];
		yield [
			'foo',
			'foo',
			[
				[
					'debug',
					'MediaWiki\Parser\ParserObserver::notifyParse: Possibly redundant parse!'
				]
			]
		];
	}
}
