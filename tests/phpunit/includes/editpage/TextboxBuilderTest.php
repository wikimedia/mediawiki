<?php
/**
 * Copyright (C) 2017 Kunal Mehta <legoktm@member.fsf.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

namespace MediaWiki\Tests\EditPage;

use MediaWiki\EditPage\TextboxBuilder;
use MediaWikiIntegrationTestCase;
use Title;

/**
 * See also unit tests at \MediaWiki\Tests\Unit\EditPage\TextboxBuilderTest
 *
 * @covers \MediaWiki\EditPage\TextboxBuilder
 */
class TextboxBuilderTest extends MediaWikiIntegrationTestCase {

	public function provideGetTextboxProtectionCSSClasses() {
		return [
			[
				[ '' ],
				[ 'isProtected' ],
				[],
			],
			[
				true,
				[],
				[],
			],
			[
				true,
				[ 'isProtected' ],
				[ 'mw-textarea-protected' ]
			],
			[
				true,
				[ 'isProtected', 'isSemiProtected' ],
				[ 'mw-textarea-sprotected' ],
			],
			[
				true,
				[ 'isProtected', 'isCascadeProtected' ],
				[ 'mw-textarea-protected', 'mw-textarea-cprotected' ],
			],
			[
				true,
				[ 'isProtected', 'isCascadeProtected', 'isSemiProtected' ],
				[ 'mw-textarea-sprotected', 'mw-textarea-cprotected' ],
			],
		];
	}

	/**
	 * @dataProvider provideGetTextboxProtectionCSSClasses
	 */
	public function testGetTextboxProtectionCSSClasses(
		$restrictionLevels,
		$protectionModes,
		$expected
	) {
		$this->setMwGlobals( [
			// set to trick PermissionManager::getNamespaceRestrictionLevels
			'wgRestrictionLevels' => $restrictionLevels
		] );

		$title = $this->createMock( Title::class );
		$title->method( 'getNamespace' )->willReturn( 1 );

		foreach ( $protectionModes as $method ) {
			$title->method( $method )->willReturn( true );
		}

		$builder = new TextboxBuilder();
		$this->assertSame( $expected, $builder->getTextboxProtectionCSSClasses( $title ) );
	}
}
