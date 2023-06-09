<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Skins\Vector\Tests\Unit\FeatureManagement\Requirements;

use HashConfig;
use MediaWiki\Skins\Vector\Constants;
use MediaWiki\Skins\Vector\FeatureManagement\Requirements\OverridableConfigRequirement;
use User;
use WebRequest;

/**
 * @group Vector
 * @group FeatureManagement
 * @coversDefaultClass \MediaWiki\Skins\Vector\FeatureManagement\Requirements\OverridableConfigRequirement
 */
class OverridableConfigRequirementTest extends \MediaWikiUnitTestCase {

	public static function providerLanguageInHeaderTreatmentRequirement() {
		return [
			[
				// Is language enabled
				[
					'logged_in' => false,
					'logged_out' => false,
				],
				// note 0 = anon user
				0,
				// `languageinheader` query param
				null,
				false,
				'If nothing enabled, nobody gets new treatment'
			],
			[
				// Is language enabled
				[
					'logged_in' => false,
					'logged_out' => true,
				],
				// note 0 = anon user
				0,
				// `languageinheader` query param
				null,
				true,
				'Anon users should get new treatment if enabled'
			],
			[
				// Is language enabled
				[
					'logged_in' => true,
					'logged_out' => false,
				],
				1,
				// `languageinheader` query param
				"0",
				false,
				'Even logged in users get old treatment when query param set to "0"'
			],
			[
				// Is language enabled
				[
					'logged_in' => false,
					'logged_out' => false,
				],
				1,
				// `languageinheader` query param
				"1",
				true,
				'Users get new treatment when query param set to "1" regardless of state of config flags'
			],
			[
				// Is language enabled
				[
					'logged_in' => false,
					'logged_out' => false,
				],
				1,
				// `languageinheader` query param
				"0",
				false,
				'Users get old treatment when query param set to "0" regardless of state of config flags'
			],
		];
	}

	/**
	 * @covers ::isMet
	 * @dataProvider providerLanguageInHeaderTreatmentRequirement
	 * @param bool $configValue
	 * @param int $userId
	 * @param string|null $queryParam
	 * @param bool $expected
	 * @param string $msg
	 */
	public function testLanguageInHeaderTreatmentRequirement(
		$configValue,
		$userId,
		$queryParam,
		$expected,
		$msg
	) {
		$config = new HashConfig( [
			Constants::CONFIG_KEY_LANGUAGE_IN_HEADER => $configValue,
		] );

		$user = $this->createMock( User::class );
		$user->method( 'isRegistered' )->willReturn( $userId !== 0 );
		$user->method( 'getID' )->willReturn( $userId );

		$request = $this->createMock( WebRequest::class );
		$request->method( 'getCheck' )->willReturn( $queryParam !== null );
		$request->method( 'getBool' )->willReturn( (bool)$queryParam );

		$requirement = new OverridableConfigRequirement(
			$config,
			$user,
			$request,
			'VectorLanguageInHeader',
			'LanguageInHeader',
			'languageinheader'
		);

		$this->assertSame( $expected, $requirement->isMet(), $msg );
	}

}
