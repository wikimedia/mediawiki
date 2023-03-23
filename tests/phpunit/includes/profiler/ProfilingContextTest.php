<?php

use MediaWiki\Profiler\ProfilingContext;

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
