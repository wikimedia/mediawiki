<?php
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
		$this->mergeMwGlobalArrayValue( 'wgHooks', array(
			'SpecialPage_initList' => array(
				function () use ( &$count ) {
					$count++;
				}
		) ) );
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

		return array(
			'class name' => array( 'SpecialAllPages', false ),
			'closure' => array( function () {
				return new SpecialAllPages();
			}, false ),
			'function' => array( array( $this, 'newSpecialAllPages' ), false ),
			'callback string' => array( 'SpecialPageTestHelper::newSpecialAllPages', false ),
			'callback with object' => array(
				array( $specialPageTestHelper, 'newSpecialAllPages' ),
				false
			),
			'callback array' => array(
				array( 'SpecialPageTestHelper', 'newSpecialAllPages' ),
				false
			)
		);
	}

	/**
	 * @covers SpecialPageFactory::getPage
	 * @dataProvider specialPageProvider
	 */
	public function testGetPage( $spec, $shouldReuseInstance ) {
		$this->mergeMwGlobalArrayValue( 'wgSpecialPages', array( 'testdummy' => $spec ) );
		SpecialPageFactory::resetList();

		$page = SpecialPageFactory::getPage( 'testdummy' );
		$this->assertInstanceOf( 'SpecialPage', $page );

		$page2 = SpecialPageFactory::getPage( 'testdummy' );
		$this->assertEquals( $shouldReuseInstance, $page2 === $page, "Should re-use instance:" );
	}

	/**
	 * @covers SpecialPageFactory::getNames
	 */
	public function testGetNames() {
		$this->mergeMwGlobalArrayValue( 'wgSpecialPages', array( 'testdummy' => 'SpecialAllPages' ) );
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
		$warnings = array();
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
				join( "\n", $warnings )
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
		return array(
			array(
				'Canonical name wins',
				array( 'Foo' => array( 'Foo', 'Bar' ), 'Baz' => array( 'Foo', 'BazPage', 'Baz2' ) ),
				'Foo',
				'Foo',
				'Foo',
				1,
			),

			array(
				'Doesn\'t redirect to a different special page\'s canonical name',
				array( 'Foo' => array( 'Foo', 'Bar' ), 'Baz' => array( 'Foo', 'BazPage', 'Baz2' ) ),
				'Baz',
				'Baz',
				'BazPage',
				1,
			),

			array(
				'Canonical name wins even if not aliased',
				array( 'Foo' => array( 'FooPage' ), 'Baz' => array( 'Foo', 'BazPage', 'Baz2' ) ),
				'Foo',
				'Foo',
				'FooPage',
				1,
			),

			array(
				'Doesn\'t redirect to a different special page\'s canonical name even if not aliased',
				array( 'Foo' => array( 'FooPage' ), 'Baz' => array( 'Foo', 'BazPage', 'Baz2' ) ),
				'Baz',
				'Baz',
				'BazPage',
				1,
			),

			array(
				'First local name beats non-first',
				array( 'First' => array( 'Foo' ), 'NonFirst' => array( 'Bar', 'Foo' ) ),
				'Foo',
				'First',
				'Foo',
				0,
			),

			array(
				'Doesn\'t redirect to a different special page\'s first alias',
				array(
					'Foo' => array( 'Foo' ),
					'First' => array( 'Bar' ),
					'Baz' => array( 'Foo', 'Bar', 'BazPage', 'Baz2' )
				),
				'Baz',
				'Baz',
				'BazPage',
				1,
			),

			array(
				'Doesn\'t redirect wrong even if all aliases conflict',
				array(
					'Foo' => array( 'Foo' ),
					'First' => array( 'Bar' ),
					'Baz' => array( 'Foo', 'Bar' )
				),
				'Baz',
				'Baz',
				'Baz',
				2,
			),

		);
	}

	public function testGetAliasListRecursion() {
		$called = false;
		$this->mergeMwGlobalArrayValue( 'wgHooks', array(
			'SpecialPage_initList' => array(
				function () use ( &$called ) {
					SpecialPageFactory::getLocalNameFor( 'Specialpages' );
					$called = true;
				}
			),
		) );
		SpecialPageFactory::resetList();
		SpecialPageFactory::getLocalNameFor( 'Specialpages' );
		$this->assertTrue( $called, 'Recursive call succeeded' );
	}

}
