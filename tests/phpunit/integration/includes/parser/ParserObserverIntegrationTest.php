<?php

use MediaWiki\Parser\ParserObserver;

/**
 * @covers \MediaWiki\Parser\ParserObserver
 * @group Database
 */
class ParserObserverIntegrationTest extends MediaWikiIntegrationTestCase {

	/**
	 * @param bool $duplicate
	 * @param int $count
	 *
	 * @dataProvider provideDuplicateParse
	 */
	public function testDuplicateParse( bool $duplicate, int $count ) {
		$logger = new TestLogger( true );
		$observer = new ParserObserver( $logger );
		$this->setService( '_ParserObserver', $observer );
		$contentRenderer = $this->getServiceContainer()->getContentRenderer();
		// Create a test page. Parse it twice if a duplicate is desired, or once otherwise.
		$page = $this->getExistingTestPage();
		$contentRenderer->getParserOutput( $page->getContent(), $page->getTitle() );
		if ( $duplicate ) {
			$contentRenderer->getParserOutput( $page->getContent(), $page->getTitle() );
		}

		$this->assertCount( $count, $logger->getBuffer() );
	}

	public static function provideDuplicateParse() {
		yield [ true, 1 ];
		yield [ false, 0 ];
	}
}
