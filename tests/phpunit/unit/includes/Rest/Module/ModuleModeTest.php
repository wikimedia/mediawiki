<?php

namespace MediaWiki\Tests\Rest\Module;

use MediaWiki\Rest\Module\AudienceDesignation;
use MediaWiki\Rest\Module\ModuleMode;

/**
 * @covers \MediaWiki\Rest\Module\ModuleMode
 */
class ModuleModeTest extends \MediaWikiUnitTestCase {
	/**
	 * @dataProvider provideGetModuleModeCases
	 */
	public function testGetModuleMode( ?AudienceDesignation $ad, ModuleMode $expected ): void {
		$is = ModuleMode::getModuleMode( $ad );

		// The default message is unhelpful in identifying which test case failed
		$msg = "Failure for audience designation " . ( $ad === null ? 'null' : $ad->name );
		$this->assertSame( $expected, $is, $msg );
	}

	public static function provideGetModuleModeCases() {
		yield from [
			[ AudienceDesignation::PUBLIC, ModuleMode::PUBLISHED ],
			[ AudienceDesignation::INTERNAL, ModuleMode::PUBLISHED ],
			[ AudienceDesignation::BETA, ModuleMode::PUBLISHED ],
			[ AudienceDesignation::NONE, ModuleMode::PUBLISHED ],
			[ null, ModuleMode::DISABLED ],
		];
	}
}
