<?php
use Wikimedia\ScopedCallback;

/**
 * Factory for handling the special page list and generating SpecialPage objects.
 *
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
 * @covers SpecialPageFactory
 * @group SpecialPage
 */
class SpecialPageFactoryTest extends MediaWikiTestCase {

	protected function tearDown() {
		parent::tearDown();

		SpecialPageFactory::resetList();
	}

	public function testResetList() {
		SpecialPageFactory::resetList();
		$this->assertContains( 'Specialpages', SpecialPageFactory::getNames() );
	}

	public function testHookNotCalledTwice() {
		$count = 0;
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'SpecialPage_initList' => [
				function () use ( &$count ) {
					$count++;
				}
		] ] );
		SpecialPageFactory::resetList();
		SpecialPageFactory::getNames();
		SpecialPageFactory::getNames();
		$this->assertEquals( 1, $count );
	}

	public function newSpecialAllPages() {
		return new SpecialAllPages();
	}

	public function specialPageProvider() {
		$specialPageTestHelper = new SpecialPageTestHelper();

		return [
			'class name' => [ 'SpecialAllPages', false ],
			'closure' => [ function () {
				return new SpecialAllPages();
			}, false ],
			'function' => [ [ $this, 'newSpecialAllPages' ], false ],
			'callback string' => [ 'SpecialPageTestHelper::newSpecialAllPages', false ],
			'callback with object' => [
				[ $specialPageTestHelper, 'newSpecialAllPages' ],
				false
			],
			'callback array' => [
				[ 'SpecialPageTestHelper', 'newSpecialAllPages' ],
				false
			]
		];
	}

	/**
	 * @covers SpecialPageFactory::getPage
	 * @dataProvider specialPageProvider
	 */
	public function testGetPage( $spec, $shouldReuseInstance ) {
		$this->mergeMwGlobalArrayValue( 'wgSpecialPages', [ 'testdummy' => $spec ] );
		SpecialPageFactory::resetList();

		$page = SpecialPageFactory::getPage( 'testdummy' );
		$this->assertInstanceOf( SpecialPage::class, $page );

		$page2 = SpecialPageFactory::getPage( 'testdummy' );
		$this->assertEquals( $shouldReuseInstance, $page2 === $page, "Should re-use instance:" );
	}

	/**
	 * @covers SpecialPageFactory::getNames
	 */
	public function testGetNames() {
		$this->mergeMwGlobalArrayValue( 'wgSpecialPages', [ 'testdummy' => SpecialAllPages::class ] );
		SpecialPageFactory::resetList();

		$names = SpecialPageFactory::getNames();
		$this->assertInternalType( 'array', $names );
		$this->assertContains( 'testdummy', $names );
	}

	/**
	 * @covers SpecialPageFactory::resolveAlias
	 */
	public function testResolveAlias() {
		$this->setMwGlobals( 'wgContLang', Language::factory( 'de' ) );
		SpecialPageFactory::resetList();

		list( $name, $param ) = SpecialPageFactory::resolveAlias( 'Spezialseiten/Foo' );
		$this->assertEquals( 'Specialpages', $name );
		$this->assertEquals( 'Foo', $param );
	}

	/**
	 * @covers SpecialPageFactory::getLocalNameFor
	 */
	public function testGetLocalNameFor() {
		$this->setMwGlobals( 'wgContLang', Language::factory( 'de' ) );
		SpecialPageFactory::resetList();

		$name = SpecialPageFactory::getLocalNameFor( 'Specialpages', 'Foo' );
		$this->assertEquals( 'Spezialseiten/Foo', $name );
	}

	/**
	 * @covers SpecialPageFactory::getTitleForAlias
	 */
	public function testGetTitleForAlias() {
		$this->setMwGlobals( 'wgContLang', Language::factory( 'de' ) );
		SpecialPageFactory::resetList();

		$title = SpecialPageFactory::getTitleForAlias( 'Specialpages/Foo' );
		$this->assertEquals( 'Spezialseiten/Foo', $title->getText() );
		$this->assertEquals( NS_SPECIAL, $title->getNamespace() );
	}

	/**
	 * @dataProvider provideTestConflictResolution
	 */
	public function testConflictResolution(
		$test, $aliasesList, $alias, $expectedName, $expectedAlias, $expectWarnings
	) {
		global $wgContLang;
		$lang = clone $wgContLang;
		$lang->mExtendedSpecialPageAliases = $aliasesList;
		$this->setMwGlobals( 'wgContLang', $lang );
		$this->setMwGlobals( 'wgSpecialPages',
			array_combine( array_keys( $aliasesList ), array_keys( $aliasesList ) )
		);
		SpecialPageFactory::resetList();

		// Catch the warnings we expect to be raised
		$warnings = [];
		$this->setMwGlobals( 'wgDevelopmentWarnings', true );
		set_error_handler( function ( $errno, $errstr ) use ( &$warnings ) {
			if ( preg_match( '/First alias \'[^\']*\' for .*/', $errstr ) ||
				preg_match( '/Did not find a usable alias for special page .*/', $errstr )
			) {
				$warnings[] = $errstr;
				return true;
			}
			return false;
		} );
		$reset = new ScopedCallback( 'restore_error_handler' );

		list( $name, /*...*/ ) = SpecialPageFactory::resolveAlias( $alias );
		$this->assertEquals( $expectedName, $name, "$test: Alias to name" );
		$result = SpecialPageFactory::getLocalNameFor( $name );
		$this->assertEquals( $expectedAlias, $result, "$test: Alias to name to alias" );

		$gotWarnings = count( $warnings );
		if ( $gotWarnings !== $expectWarnings ) {
			$this->fail( "Expected $expectWarnings warning(s), but got $gotWarnings:\n" .
				implode( "\n", $warnings )
			);
		}
	}

	/**
	 * @dataProvider provideTestConflictResolution
	 */
	public function testConflictResolutionReversed(
		$test, $aliasesList, $alias, $expectedName, $expectedAlias, $expectWarnings
	) {
		// Make sure order doesn't matter by reversing the list
		$aliasesList = array_reverse( $aliasesList );
		return $this->testConflictResolution(
			$test, $aliasesList, $alias, $expectedName, $expectedAlias, $expectWarnings
		);
	}

	public function provideTestConflictResolution() {
		return [
			[
				'Canonical name wins',
				[ 'Foo' => [ 'Foo', 'Bar' ], 'Baz' => [ 'Foo', 'BazPage', 'Baz2' ] ],
				'Foo',
				'Foo',
				'Foo',
				1,
			],

			[
				'Doesn\'t redirect to a different special page\'s canonical name',
				[ 'Foo' => [ 'Foo', 'Bar' ], 'Baz' => [ 'Foo', 'BazPage', 'Baz2' ] ],
				'Baz',
				'Baz',
				'BazPage',
				1,
			],

			[
				'Canonical name wins even if not aliased',
				[ 'Foo' => [ 'FooPage' ], 'Baz' => [ 'Foo', 'BazPage', 'Baz2' ] ],
				'Foo',
				'Foo',
				'FooPage',
				1,
			],

			[
				'Doesn\'t redirect to a different special page\'s canonical name even if not aliased',
				[ 'Foo' => [ 'FooPage' ], 'Baz' => [ 'Foo', 'BazPage', 'Baz2' ] ],
				'Baz',
				'Baz',
				'BazPage',
				1,
			],

			[
				'First local name beats non-first',
				[ 'First' => [ 'Foo' ], 'NonFirst' => [ 'Bar', 'Foo' ] ],
				'Foo',
				'First',
				'Foo',
				0,
			],

			[
				'Doesn\'t redirect to a different special page\'s first alias',
				[
					'Foo' => [ 'Foo' ],
					'First' => [ 'Bar' ],
					'Baz' => [ 'Foo', 'Bar', 'BazPage', 'Baz2' ]
				],
				'Baz',
				'Baz',
				'BazPage',
				1,
			],

			[
				'Doesn\'t redirect wrong even if all aliases conflict',
				[
					'Foo' => [ 'Foo' ],
					'First' => [ 'Bar' ],
					'Baz' => [ 'Foo', 'Bar' ]
				],
				'Baz',
				'Baz',
				'Baz',
				2,
			],

		];
	}

	public function testGetAliasListRecursion() {
		$called = false;
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'SpecialPage_initList' => [
				function () use ( &$called ) {
					SpecialPageFactory::getLocalNameFor( 'Specialpages' );
					$called = true;
				}
			],
		] );
		SpecialPageFactory::resetList();
		SpecialPageFactory::getLocalNameFor( 'Specialpages' );
		$this->assertTrue( $called, 'Recursive call succeeded' );
	}

}
