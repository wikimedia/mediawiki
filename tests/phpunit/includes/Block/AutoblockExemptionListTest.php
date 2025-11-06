<?php

namespace MediaWiki\Tests\Block;

use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;

/**
 * @group Database
 * @group Blocking
 * @covers \MediaWiki\Block\AutoblockExemptionList
 */
class AutoblockExemptionListTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		$this->overrideConfigValue(
			MainConfigNames::AutoblockExemptions,
			[ '2001:db8::1', '2001:db8:100::/64' ],
		);

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
			'IP is not exempt from autoblocks' => [ '1.2.3.5', false ],
			'IP is exempt from autoblocks via on-wiki message' => [ '1.2.3.4', true ],
			'IP is exempt from autoblocks based on IP range exemption from on-wiki message' => [ '7.8.9.40', true ],
			'IP is exempt from autoblocks via configuration' => [ '2001:db8::1', true ],
			'IP is exempt from autoblocks based on IP range exemption in configuration' => [ '2001:db8:100::1', true ],
			'IPv4 that is not a list entry is not exempt' => [ '192.0.2.1', false ],
			'IPv6 that is not a list entry is not exempt' => [ '2001:db8:200::1', false ],
		];
	}

	public function addDBDataOnce() {
		$this->editPage(
			Title::newFromText( 'block-autoblock-exemptionlist', NS_MEDIAWIKI ),
			'[[Test]]. This is a autoblocking exemption list description.' .
			"\n\n* 1.2.3.4\n** 1.2.3.6\n* 7.8.9.0/24\n192.0.2.1"
		);
	}
}
