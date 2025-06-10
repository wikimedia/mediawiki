<?php
/**
 * Copyright (C) 2017 Kunal Mehta <legoktm@debian.org>
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
use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\StaticUserOptionsLookup;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;

/**
 * See also unit tests at \MediaWiki\Tests\Unit\EditPage\TextboxBuilderTest
 *
 * @covers \MediaWiki\EditPage\TextboxBuilder
 */
class TextboxBuilderTest extends MediaWikiIntegrationTestCase {

	public static function provideGetTextboxProtectionCSSClasses() {
		return [
			[
				[ '' ],
				[ 'isProtected' ],
				[],
			],
			[
				[ '', 'something' ],
				[],
				[],
			],
			[
				[ '', 'something' ],
				[ 'isProtected' ],
				[ 'mw-textarea-protected' ]
			],
			[
				[ '', 'something' ],
				[ 'isProtected', 'isSemiProtected' ],
				[ 'mw-textarea-sprotected' ],
			],
			[
				[ '', 'something' ],
				[ 'isProtected', 'isCascadeProtected' ],
				[ 'mw-textarea-protected', 'mw-textarea-cprotected' ],
			],
			[
				[ '', 'something' ],
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
		$this->overrideConfigValue(
			// set to trick PermissionManager::getNamespaceRestrictionLevels
			MainConfigNames::RestrictionLevels, $restrictionLevels
		);

		$mockRestrictionStore = $this->createMock( RestrictionStore::class );
		$pageIdValue = PageIdentityValue::localIdentity( 1, NS_MAIN, 'test' );

		$mockRestrictionStore->method(
			$this->logicalOr( ...array_map( $this->identicalTo( ... ), $protectionModes ) )
		)->willReturn( true );

		$this->setService( 'RestrictionStore', $mockRestrictionStore );

		$builder = new TextboxBuilder();
		$this->assertSame( $expected, $builder->getTextboxProtectionCSSClasses( $pageIdValue ) );
	}

	public function testBuildTextboxAttribs() {
		$user = UserIdentityValue::newRegistered( 42, 'Test' );
		$mockUserOptionsLookup = new StaticUserOptionsLookup( [
			'Test' => [ 'editfont' => 'monospace' ],
		] );
		$this->setService( 'UserOptionsLookup', $mockUserOptionsLookup );

		$enLanguage = $this->createMock( Language::class );
		$enLanguage->method( 'getHtmlCode' )->willReturn( 'en' );
		$enLanguage->method( 'getDir' )->willReturn( 'ltr' );

		$title = $this->createMock( Title::class );
		$title->method( 'getPageLanguage' )->willReturn( $enLanguage );

		$builder = new TextboxBuilder();
		$attribs = $builder->buildTextboxAttribs(
			'mw-textbox1',
			[ 'class' => 'foo bar', 'data-foo' => '123', 'rows' => 30 ],
			$user,
			$title
		);

		$this->assertIsArray( $attribs );
		// custom attrib showed up
		$this->assertArrayHasKey( 'data-foo', $attribs );
		// classes merged properly (string)
		$this->assertSame( [ 'foo bar', 'mw-editfont-monospace' ], $attribs['class'] );
		// overrides in custom attrib worked
		$this->assertSame( 30, $attribs['rows'] );
		$this->assertSame( 'en', $attribs['lang'] );

		$attribs2 = $builder->buildTextboxAttribs(
			'mw-textbox2', [ 'class' => [ 'foo', 'bar' ] ], $user, $title
		);
		// classes merged properly (array)
		$this->assertSame( [ 'foo', 'bar', 'mw-editfont-monospace' ], $attribs2['class'] );

		$attribs3 = $builder->buildTextboxAttribs(
			'mw-textbox3', [], $user, $title
		);
		// classes ok when nothing to be merged
		$this->assertSame( [ 'mw-editfont-monospace' ], $attribs3['class'] );
	}
}
