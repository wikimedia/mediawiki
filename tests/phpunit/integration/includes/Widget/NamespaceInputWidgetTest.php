<?php

namespace MediaWiki\Tests\Integration\Widget;

use MediaWiki\Widget\NamespaceInputWidget;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Widget\NamespaceInputWidget
 */
class NamespaceInputWidgetTest extends MediaWikiIntegrationTestCase {
	/** @dataProvider provideConstruct */
	public function testConstruct( $config, $expectedPropertyValues ) {
		$widget = new NamespaceInputWidget( $config );
		$widget = TestingAccessWrapper::newFromObject( $widget );
		foreach ( $expectedPropertyValues as $property => $expectedValue ) {
			$this->assertSame( $expectedValue, $widget->$property );
		}
	}

	public static function provideConstruct() {
		return [
			'Empty config provided gives defaults' => [
				[],
				[ 'include' => null, 'exclude' => [], 'userLang' => false, 'includeAllValue' => null ],
			],
			'Custom values for config' => [
				[ 'include' => [ 0 ], 'exclude' => [ 1 ], 'userLang' => 'en', 'includeAllValue' => 'include' ],
				[ 'include' => [ 0 ], 'exclude' => [ 1 ], 'userLang' => 'en', 'includeAllValue' => 'include' ],
			],
		];
	}
}
