<?php
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
 * @since 1.42
 */

namespace MediaWiki\Tests\Unit\CommentFormatter;

use MediaWiki\CommentFormatter\CommentItem;
use MediaWiki\CommentFormatter\StringCommentIterator;
use MediaWikiUnitTestCase;

/**
 * @group CommentFormatter
 * @coversDefaultClass \MediaWiki\CommentFormatter\StringCommentIterator
 */
class StringCommentIteratorTest extends MediaWikiUnitTestCase {

	/**
	 * Test that the StringCommentIterator can be instantiated.
	 * @covers ::__construct
	 */
	public function testConstruct() {
		// Create an instance of StringCommentIterator with an array of strings
		$iterator = new StringCommentIterator( [ 'Some comment', 'Another comment' ] );

		// Verify that it is an instance of StringCommentIterator
		$this->assertInstanceOf( StringCommentIterator::class, $iterator );
	}

	/**
	 * Test the current method to ensure it returns CommentItem objects.
	 * @covers ::current
	 */
	public function testCurrent() {
		// Create an instance of StringCommentIterator with an array of strings
		$iterator = new StringCommentIterator( [ 'Some comment', 'Another comment' ] );

		// Verify that the current method returns a CommentItem object
		$this->assertInstanceOf( CommentItem::class, $iterator->current() );
	}
}
