<?php

namespace MediaWiki\Tests\Unit\Settings\Config;

use MediaWiki\Settings\Config\ConfigSink;
use MediaWiki\Settings\Config\GlobalConfigSink;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Config\GlobalConfigSink
 */
class GlobalConfigSinkTest extends TestCase {

	protected function getConfigBuilder(): ConfigSink {
		return new GlobalConfigSink( 'prefix_' );
	}

	public function testSet() {
		unset( $GLOBALS['prefix_foo'] );
		$this->getConfigBuilder()
			->set( 'foo', 'bar' );
		$this->assertEquals( 'bar', $GLOBALS['prefix_foo'] );
	}

	public function testSetIfNotDefined() {
		unset( $GLOBALS['prefix_baz'] );
		$GLOBALS['prefix_foo'] = 'bar';
		$this->getConfigBuilder()
			->set( 'baz', 'quux' )
			->setIfNotDefined( 'foo', 'baz' );

		$this->assertEquals( 'quux', $GLOBALS['prefix_baz'] );
		$this->assertEquals( 'bar', $GLOBALS['prefix_foo'] );
	}
}
