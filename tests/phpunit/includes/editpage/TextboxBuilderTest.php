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

use Language;
use MediaWiki\EditPage\TextboxBuilder;
use MediaWikiTestCase;
use Title;
use User;

/**
 * @covers \MediaWiki\EditPage\TextboxBuilder
 */
class TextboxBuilderTest extends MediaWikiTestCase {

	public function provideAddNewLineAtEnd() {
		return [
			[ '', '' ],
			[ 'foo', "foo\n" ],
		];
	}

	/**
	 * @dataProvider provideAddNewLineAtEnd
	 */
	public function testAddNewLineAtEnd( $input, $expected ) {
		$builder = new TextboxBuilder();
		$this->assertSame( $expected, $builder->addNewLineAtEnd( $input ) );
	}

	public function testBuildTextboxAttribs() {
		$user = new User();
		$user->setOption( 'editfont', 'monospace' );

		$title = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->getMock();
		$title->expects( $this->any() )
			->method( 'getPageLanguage' )
			->will( $this->returnValue( Language::factory( 'en' ) ) );

		$builder = new TextboxBuilder();
		$attribs = $builder->buildTextboxAttribs(
			'mw-textbox1',
			[ 'class' => 'foo bar', 'data-foo' => '123', 'rows' => 30 ],
			$user,
			$title
		);

		$this->assertInternalType( 'array', $attribs );
		// custom attrib showed up
		$this->assertArrayHasKey( 'data-foo', $attribs );
		// classes merged properly (string)
		$this->assertSame( 'foo bar mw-editfont-monospace', $attribs['class'] );
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
		$this->assertSame( 'mw-editfont-monospace', $attribs3['class'] );
	}

	public function provideMergeClassesIntoAttributes() {
		return [
			[
				[],
				[],
				[],
			],
			[
				[ 'mw-new-classname' ],
				[],
				[ 'class' => 'mw-new-classname' ],
			],
			[
				[],
				[ 'title' => 'My Title' ],
				[ 'title' => 'My Title' ],
			],
			[
				[ 'mw-new-classname' ],
				[ 'title' => 'My Title' ],
				[ 'title' => 'My Title', 'class' => 'mw-new-classname' ],
			],
			[
				[ 'mw-new-classname' ],
				[ 'class' => 'mw-existing-classname' ],
				[ 'class' => 'mw-existing-classname mw-new-classname' ],
			],
			[
				[ 'mw-new-classname', 'mw-existing-classname' ],
				[ 'class' => 'mw-existing-classname' ],
				[ 'class' => 'mw-existing-classname mw-new-classname' ],
			],
		];
	}

	/**
	 * @dataProvider provideMergeClassesIntoAttributes
	 */
	public function testMergeClassesIntoAttributes( $inputClasses, $inputAttributes, $expected ) {
		$builder = new TextboxBuilder();
		$this->assertSame(
			$expected,
			$builder->mergeClassesIntoAttributes( $inputClasses, $inputAttributes )
		);
	}

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
			// set to trick MWNamespace::getRestrictionLevels
			'wgRestrictionLevels' => $restrictionLevels
		] );

		$builder = new TextboxBuilder();
		$this->assertSame( $expected, $builder->getTextboxProtectionCSSClasses(
			$this->mockProtectedTitle( $protectionModes )
		) );
	}

	/**
	 * @return Title
	 */
	private function mockProtectedTitle( $methodsToReturnTrue ) {
		$title = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->getMock();

		$title->expects( $this->any() )
			->method( 'getNamespace' )
			->will( $this->returnValue( 1 ) );

		foreach ( $methodsToReturnTrue as $method ) {
			$title->expects( $this->any() )
				->method( $method )
				->will( $this->returnValue( true ) );
		}

		return $title;
	}
}
