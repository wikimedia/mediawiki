<?php
/**
 * Copyright (C) 2017 Kunal Mehta <legoktm@debian.org>
 *
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Tests\Unit\EditPage;

use MediaWiki\EditPage\TextboxBuilder;
use MediaWikiUnitTestCase;

/**
 * Split from \MediaWiki\Tests\EditPage\TextboxBuilderTest integration tests
 *
 * @covers \MediaWiki\EditPage\TextboxBuilder
 */
class TextboxBuilderTest extends MediaWikiUnitTestCase {

	public static function provideAddNewLineAtEnd() {
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

	public static function provideMergeClassesIntoAttributes() {
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

}
