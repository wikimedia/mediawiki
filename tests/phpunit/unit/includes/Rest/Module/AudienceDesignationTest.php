<?php

namespace MediaWiki\Tests\Rest\Module;

use MediaWiki\Rest\Module\AudienceDesignation;

/**
 * @covers \MediaWiki\Rest\Module\AudienceDesignation
 */
class AudienceDesignationTest extends \MediaWikiUnitTestCase {
	/**
	 * @dataProvider provideFromModuleIdCases
	 */
	public function testFromModuleId( string $moduleId, ?AudienceDesignation $expected ): void {
		$this->assertSame( $expected, AudienceDesignation::fromModuleId( $moduleId ) );
	}

	public static function provideFromModuleIdCases() {
		return [
			'no designation' => [
				'mymodule/v1',
				AudienceDesignation::PUBLISHED,
			],
			'published' => [
				'mymodule/v1-published',
				AudienceDesignation::PUBLISHED,
			],
			'internal' => [
				'mymodule/v1-internal',
				AudienceDesignation::INTERNAL,
			],
			'beta' => [
				'mymodule/v1-beta',
				AudienceDesignation::BETA,
			],
			'invalid' => [
				'mymodule/v1-invalid',
				null,
			],
			'unrecognized' => [
				'mymodule/v1-unrecognized',
				null,
			],
			'malformed' => [
				'not-a-module-id',
				null,
			],
			'trailing-version-digits' => [
				'mymodule/v1-beta123',
				AudienceDesignation::BETA,
			],
		];
	}
}
