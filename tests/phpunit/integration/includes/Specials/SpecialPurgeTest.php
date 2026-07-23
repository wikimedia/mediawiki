<?php

namespace MediaWiki\Tests\Integration\Specials;

/**
 * @covers \MediaWiki\Specials\SpecialPurge
 * @covers \MediaWiki\SpecialPage\SpecialRedirectWithAction
 * @covers \MediaWiki\SpecialPage\RedirectSpecialPage
 * @group Database
 */
class SpecialPurgeTest extends SpecialRedirectWithActionTestBase {

	/** @inheritDoc */
	protected function getAction(): string {
		return 'purge';
	}

	/** @inheritDoc */
	protected function getMsgPrefix(): string {
		return 'purge';
	}

	public function testPrefixSearchSubpages(): void {
		$this->getExistingTestPage( 'Test' );

		$this->assertSame( [ 'Test' ], $this->newSpecialPage()->prefixSearchSubpages( 'T', 1, 0 ) );
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Purge' );
	}
}
