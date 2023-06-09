<?php

namespace MediaWiki\Skins\Vector\Tests\Unit\FeatureManagement\Requirements;

use MediaWiki\Skins\Vector\FeatureManagement\Requirements\UserPreferenceRequirement;
use MediaWiki\User\UserOptionsLookup;
use Title;
use User;

/**
 * @group Vector
 * @group FeatureManagement
 * @coversDefaultClass \MediaWiki\Skins\Vector\FeatureManagement\Requirements\UserPreferenceRequirement
 */
final class UserPreferenceRequirementTest extends \MediaWikiUnitTestCase {
	public static function providerTestIsMetRequirement() {
		return [
			[
				// Is option enabled?
				true,
				// Is title present?
				true,
				// Expected
				true,
				'If enabled, returns true'
			],
			[
				// Is option enabled?
				false,
				// Is title present?
				true,
				// Expected
				false,
				'If disabled, returns false'
			],
			[
				// Is option enabled?
				true,
				// Is title present?
				false,
				// Expected
				false,
				'If enabled but title null, returns false'
			],
		];
	}

	/**
	 * @covers ::isMet
	 * @dataProvider providerTestIsMetRequirement
	 * @param bool $isEnabled
	 * @param bool $isTitlePresent
	 * @param bool $expected
	 * @param string $msg
	 */
	public function testIsMetRequirement(
		$isEnabled,
		$isTitlePresent,
		$expected,
		$msg
	) {
		$user = $this->createMock( User::class );
		$title = $isTitlePresent ? $this->createMock( Title::class ) : null;

		$userOptionsLookup = $this->createMock( UserOptionsLookup::class );
		$userOptionsLookup->method( 'getOption' )->willReturn( $isEnabled );
		$userOptionsLookup->method( 'getBoolOption' )->willReturn( $isEnabled );

		$requirement = new UserPreferenceRequirement(
			$user,
			$userOptionsLookup,
			'userOption',
			'userRequirement',
			$title
		);

		$this->assertSame( $expected, $requirement->isMet(), $msg );
	}
}
