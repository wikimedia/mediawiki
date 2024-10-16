<?php

namespace MediaWiki\Tests\Block;

use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;

/**
 * @group Database
 * @group Blocking
 * @covers \MediaWiki\Block\AutoblockExemptionList
 */
class AutoblockExemptionListTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		// Allow the MediaWiki override to take effect
		$this->getServiceContainer()->getMessageCache()->enable();
	}

	/** @dataProvider provideIsExempt */
	public function testIsExempt( $ip, $expectedReturnValue ) {
		$this->assertSame(
			$expectedReturnValue,
			$this->getServiceContainer()->getAutoblockExemptionList()->isExempt( $ip )
		);
	}

	public static function provideIsExempt() {
		return [
			'IP is exempt from autoblocks' => [ '1.2.3.4', true ],
			'IP is not exempt from autoblocks' => [ '1.2.3.5', false ],
			'IP is exempt from autoblocks based on IP range exemption' => [ '7.8.9.40', true ],
		];
	}

	public function addDBDataOnce() {
		$this->editPage(
			Title::newFromText( 'block-autoblock-exemptionlist', NS_MEDIAWIKI ),
			'[[Test]]. This is a autoblocking exemption list description.' .
			"\n\n* 1.2.3.4\n** 1.2.3.6\n* 7.8.9.0/24"
		);
	}
}
