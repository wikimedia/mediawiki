<?php

use MediaWiki\Block\Block;
use MediaWiki\Block\RangeBlockTarget;

/**
 * @covers \MediaWiki\Block\RangeBlockTarget
 */
class RangeBlockTargetTest extends MediaWikiUnitTestCase {
	private const DEFAULT_LIMITS = [ 'IPv4' => 16, 'IPv6' => 19 ];

	private function getTarget() {
		return new RangeBlockTarget(
			'1.2.3.0/24',
			self::DEFAULT_LIMITS,
			'enwiki'
		);
	}

	public function testToString() {
		$this->assertSame( '1.2.3.0/24', $this->getTarget()->toString() );
	}

	public function testGetType() {
		$this->assertSame( Block::TYPE_RANGE, $this->getTarget()->getType() );
	}

	public function testGetLogPage() {
		$page = $this->getTarget()->getLogPage();
		$this->assertSame( NS_USER, $page->getNamespace() );
		$this->assertSame( '1.2.3.0/24', $page->getDBkey() );
		$this->assertSame( 'enwiki', $page->getWikiId() );
	}

	public static function provideGetSpecificity() {
		return [
			[ '1.2.3.4/32', 2 ],
			[ '1.2.3.0/24', 2.25 ],
			[ '0.0.0.0/0', 3 ],
			[ '::1/64', 2.5 ],
			[ '::1/32', 2.75 ],
		];
	}

	/**
	 * @dataProvider provideGetSpecificity
	 */
	public function testGetSpecificity( $range, $expected ) {
		$target = new RangeBlockTarget( $range, self::DEFAULT_LIMITS, false );
		$this->assertSame( $expected, $target->getSpecificity() );
	}

	public static function provideValidateForCreation() {
		return [
			[ '1.2.3.4/16', false ],
			[ '1.2.3.4/15', 'ip_range_toolarge' ],
			[ '1.2.3.4/65', 'ip_range_invalid' ],
			[ '::1/19', false ],
			[ '::1/18', 'ip_range_toolarge' ],
			[ '::1/130', 'ip_range_invalid' ],
		];
	}

	/**
	 * @dataProvider provideValidateForCreation
	 * @param string $range
	 * @param string|false $error
	 */
	public function testValidateForCreation( $range, $error ) {
		$target = new RangeBlockTarget( $range, self::DEFAULT_LIMITS, false );
		$status = $target->validateForCreation();
		if ( $error === false ) {
			$this->assertStatusGood( $status );
		} else {
			$this->assertStatusError( $error, $status );
		}
	}

	public static function provideValidateForCreationDisabled() {
		return [
			[ '1.2.3.0/24' ],
			[ '::1/24' ],
		];
	}

	/**
	 * @dataProvider provideValidateForCreationDisabled
	 * @param string $range
	 */
	public function testValidateForCreationDisabled( $range ) {
		$target = new RangeBlockTarget(
			$range, [
				'IPv4' => 32,
				'IPv6' => 128
			],
			false
		);
		$status = $target->validateForCreation();
		$this->assertStatusError( 'range_block_disabled', $status );
	}

	public function testToHexRange() {
		$this->assertSame(
			[ '01020300', '010203FF' ],
			$this->getTarget()->toHexRange()
		);
	}

	public function testGetHexRangeStart() {
		$this->assertSame( '01020300', $this->getTarget()->getHexRangeStart() );
	}

	public function testGetHexRangeEnd() {
		$this->assertSame( '010203FF', $this->getTarget()->getHexRangeEnd() );
	}

	public function testGetLegacyTuple() {
		$this->assertSame(
			[ '1.2.3.0/24', Block::TYPE_RANGE ],
			$this->getTarget()->getLegacyTuple()
		);
	}

	public function testGetWikiId() {
		$this->assertSame( 'enwiki', $this->getTarget()->getWikiId() );
	}
}
