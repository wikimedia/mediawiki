<?php

namespace MediaWiki\HookContainer;

use MediaWikiUnitTestCase;
use Wikimedia\TestingAccessWrapper;

class DeprecatedHooksTest extends MediaWikiUnitTestCase {

	/**
	 * @covers       \MediaWiki\HookContainer\DeprecatedHooks::__construct
	 * @covers       \MediaWiki\HookContainer\DeprecatedHooks::isHookDeprecated
	 */
	public function testIsHookDeprecated() {
		$extDeprecatedHooks = [
			'FooBaz' => [ 'deprecatedVersion' => '1.35', 'component' => 'ComponentFooBaz' ]
		];
		$deprecatedHooks = new DeprecatedHooks( $extDeprecatedHooks );
		$this->assertTrue( $deprecatedHooks->isHookDeprecated( 'FooBaz' ) );
		$this->assertFalse( $deprecatedHooks->isHookDeprecated( 'FooBazBar' ) );
	}

	/**
	 * @covers       \MediaWiki\HookContainer\DeprecatedHooks::markDeprecated
	 */
	public function testMarkDeprecatedException() {
		$extDeprecatedHooks = [
			'FooBaz' => [ 'deprecatedVersion' => '1.35', 'component' => 'ComponentFooBaz' ]
		];
		$deprecatedHooks = new DeprecatedHooks( $extDeprecatedHooks );
		$this->expectExceptionMessage(
			"Cannot mark hook 'FooBaz' deprecated with version 1.31. " .
			"It is already marked deprecated with version 1.35"
		);
		$deprecatedHooks->markDeprecated( 'FooBaz', '1.31' );
	}

	/**
	 * @covers       \MediaWiki\HookContainer\DeprecatedHooks::markDeprecated
	 */
	public function testMarkDeprecated() {
		$deprecatedHooks = new DeprecatedHooks();
		$deprecatedHooks->markDeprecated( 'FooBaz', '1.31', 'ComponentFooBaz' );
		$allDeprecated = $deprecatedHooks->getDeprecationInfo();
		$this->assertArrayHasKey( 'FooBaz', $allDeprecated );
		$this->assertContains(
			[
				'deprecatedVersion' => '1.31',
				'component' => 'ComponentFooBaz',
				'silent' => false
			],
			$allDeprecated
		);
	}

	/**
	 * @covers       \MediaWiki\HookContainer\DeprecatedHooks::getDeprecationInfo
	 */
	public function testGetDeprecationInfo() {
		$extDeprecatedHooks = [
			'FooBar' => [ 'deprecatedVersion' => '1.21', 'component' => 'ComponentFooBar' ],
			'FooBarBaz' => [ 'deprecatedVersion' => '1.21' ],
			'SoftlyDeprecated' => [
				'deprecatedVersion' => '1.21',
				'component' => 'ComponentFooBar',
				'silent' => true
			]
		];
		$deprecatedHooksWrapper = TestingAccessWrapper::newFromObject( new DeprecatedHooks() );
		$preRegisteredDeprecated = $deprecatedHooksWrapper->deprecatedHooks;
		$deprecatedHooks = new DeprecatedHooks( $extDeprecatedHooks );
		$hookDeprecationInfo = $deprecatedHooks->getDeprecationInfo( 'FooBar' );

		$this->assertNull( $deprecatedHooks->getDeprecationInfo( 'FooBazBaz' ) );
		$this->assertEquals(
			$hookDeprecationInfo,
			[
				'deprecatedVersion' => '1.21',
				'component' => 'ComponentFooBar',
				'silent' => false,
			]
		);

		$this->assertEquals(
			$deprecatedHooks->getDeprecationInfo( 'SoftlyDeprecated' ),
			[
				'deprecatedVersion' => '1.21',
				'component' => 'ComponentFooBar',
				'silent' => true,
			]
		);

		$this->assertCount(
			count( $preRegisteredDeprecated ) + count( $extDeprecatedHooks ),
			$deprecatedHooks->getDeprecationInfo()
		);
	}
}
