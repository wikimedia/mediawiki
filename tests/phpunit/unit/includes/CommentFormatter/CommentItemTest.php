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
use MediaWiki\Linker\LinkTarget;
use MediaWikiUnitTestCase;

/**
 * @group CommentFormatter
 * @covers \MediaWiki\CommentFormatter\CommentItem
 */
class CommentItemTest extends MediaWikiUnitTestCase {

	/**
	 * Test the constructor of the CommentItem class.
	 */
	public function testConstruct() {
		// Create a new CommentItem object
		$comment = new CommentItem( 'foo' );
		// Verify the comment property is set to 'foo'
		$this->assertSame( 'foo', $comment->comment );
	}

	/**
	 * Test the selfLinkTarget method of the CommentItem class.
	 */
	public function testSelfLinkTarget() {
		// Create a new CommentItem object
		$comment = new CommentItem( 'foo' );
		// Create a mock LinkTarget object
		$target = $this->createMock( LinkTarget::class );
		// Set the selfLinkTarget to the mock LinkTarget object
		$comment->selfLinkTarget( $target );
		// Verify the selfLinkTarget property is set to the mock LinkTarget object
		$this->assertSame( $target, $comment->selfLinkTarget );
	}

	/**
	 * Test the samePage method of the CommentItem class.
	 */
	public function testSamePage() {
		// Create a new CommentItem object
		$comment = new CommentItem( 'foo' );
		// Verify the samePage property is true
		$this->assertTrue( $comment->samePage()->samePage );

		$this->assertInstanceOf( CommentItem::class, $comment->samePage() );

		$this->assertFalse( $comment->samePage( false )->samePage );

		$this->assertTrue( $comment->samePage( true )->samePage );
	}

	/**
	 * Test the wikiId method of the CommentItem class with various values.
	 */
	public function testWikiId() {
		// Create a new CommentItem object
		$comment = new CommentItem( 'foo' );
		// Set the wikiId to 'bar'
		$comment->wikiId( 'bar' );
		// Verify the wikiId property is set to 'bar'
		$this->assertSame( 'bar', $comment->wikiId );
		// Set the wikiId to false
		$comment->wikiId( false );
		// Verify the wikiId property is false
		$this->assertFalse( $comment->wikiId );
		// Set the wikiId to null
		$comment->wikiId( null );
		// Verify the wikiId property is null
		$this->assertNull( $comment->wikiId );
	}
}
