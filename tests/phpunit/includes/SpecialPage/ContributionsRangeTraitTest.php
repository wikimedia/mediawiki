<?php

namespace MediaWiki\Tests\SpecialPage;

use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\ContributionsRangeTrait;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\SpecialPage\ContributionsRangeTrait
 */
class ContributionsRangeTraitTest extends MediaWikiIntegrationTestCase {

	private function getConfig() {
		return new HashConfig( [
			MainConfigNames::RangeContributionsCIDRLimit => [
				'IPv4' => 16,
				'IPv6' => 32,
			]
		] );
	}

	private function getWrappedTrait() {
		return TestingAccessWrapper::newFromObject(
			$this->getMockForTrait( ContributionsRangeTrait::class )
		);
	}

	/**
	 * @dataProvider provideQueryableRanges
	 */
	public function testIsQueryableRange( $ipRange ) {
		$this->assertTrue(
			$this->getWrappedTrait()->isQueryableRange( $ipRange, $this->getConfig() ),
		);
	}

	public static function provideQueryableRanges() {
		return [
			[ '116.17.184.5/32' ],
			[ '0.17.184.5/16' ],
			[ '2000::/32' ],
			[ '2001:db8::/128' ],
		];
	}

	/**
	 * @dataProvider provideUnqueryableRanges
	 */
	public function testUnqueryableRanges( $ipRange ) {
		$this->assertFalse(
			$this->getWrappedTrait()->isQueryableRange( $ipRange, $this->getConfig() ),
		);
	}

	public static function provideUnqueryableRanges() {
		return [
			[ '116.17.184.5/33' ],
			[ '0.17.184.5/15' ],
			[ '2000::/31' ],
			[ '2001:db8::/9999' ],
			[ '2001:db8::' ],
			[ '0.17.184.5' ],
			[ 'test' ],
		];
	}

	/**
	 * @dataProvider provideValidIPOrQueryableRanges
	 */
	public function testIsValidIPOrQueryableRange( $ip ) {
		$this->assertTrue(
			$this->getWrappedTrait()->isValidIPOrQueryableRange( $ip, $this->getConfig() ),
		);
	}

	public static function provideValidIPOrQueryableRanges() {
		return [
			[ '0.17.184.5/16' ],
			[ '0.17.184.5' ],
			[ '2000::/32' ],
			[ '2000::' ],
		];
	}

	/**
	 * @dataProvider provideInvalidIPOrUnqueryableRanges
	 */
	public function testInvalidIPOrUnqueryableRanges( $ip ) {
		$this->assertFalse(
			$this->getWrappedTrait()->isValidIPOrQueryableRange( $ip, $this->getConfig() ),
		);
	}

	public static function provideInvalidIPOrUnqueryableRanges() {
		return [
			[ '0.17.184.5/15' ],
			[ '2000::/31' ],
			[ 'test' ],
		];
	}

	public function testGetQueryableRangeLimit() {
		$limit = [
			'IPv4' => 16,
			'IPv6' => 32,
		];

		$config = new HashConfig( [
			MainConfigNames::RangeContributionsCIDRLimit => $limit
		] );

		$this->assertSame( $limit, $this->getWrappedTrait()->getQueryableRangeLimit( $config ) );
	}

}
