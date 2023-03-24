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
