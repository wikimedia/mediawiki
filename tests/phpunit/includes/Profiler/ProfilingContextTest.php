<?php

use MediaWiki\Profiler\ProfilingContext;

/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * @covers \MediaWiki\Profiler\ProfilingContext
 */
class ProfilingContextTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public static function provideEntryPointNames() {
		return [
			[ 'index', 'edit', 'index_edit' ],
			[ 'index', 'Recentchanges', 'index_Recentchanges' ],
			[ 'api', 'upload', 'api_upload' ],
			[ 'rest', '/wikibase/v1/something/{complex}/id', 'rest__wikibase_v1_something_complex_id' ]
		];
	}

	/**
	 * @dataProvider provideEntryPointNames
	 */
	public function testSetEntryPointHandler( $entryPoint, $handler, $metricName ) {
		$profilerContext = new ProfilingContext();

		$this->assertFalse( $profilerContext->isInitialized() );
		$profilerContext->init( $entryPoint, $handler );

		$this->assertTrue( $profilerContext->isInitialized() );
		$this->assertSame( $entryPoint, $profilerContext->getEntryPoint() );
		$this->assertSame( $handler, $profilerContext->getHandler() );
		$this->assertSame( $metricName, $profilerContext->getHandlerMetricPrefix() );
	}
}
