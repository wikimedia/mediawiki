<?php

use MediaWiki\EditPage\SpamChecker;

/**
 * @covers \MediaWiki\EditPage\SpamChecker
 *
 * @author DannyS712
 */
class SpamCheckerTest extends MediaWikiUnitTestCase {

	public function testNoMatches() {
		$checker = new SpamChecker( [], [] );
		$this->assertFalse(
			$checker->checkContent( 'spam content goes here' )
		);
		$this->assertFalse(
			$checker->checkSummary( 'spam summary goes here' )
		);
	}

	public function testMatchContent() {
		$checker = new SpamChecker( [ '/spam\s*content/' ], [] );
		$this->assertSame(
			'spam content',
			$checker->checkContent( 'spam content goes here' )
		);
		$this->assertFalse(
			$checker->checkSummary( 'spam summary goes here' )
		);
	}

	public function testMatchSummary() {
		$checker = new SpamChecker( [], [ '/spam\s*summary/' ] );
		$this->assertFalse(
			$checker->checkContent( 'spam content goes here' )
		);
		$this->assertSame(
			'spam summary',
			$checker->checkSummary( 'spam summary goes here' )
		);
	}
}
