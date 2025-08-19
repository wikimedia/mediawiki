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

	public function testConstruct() {
		$comment = new CommentItem( 'foo' );
		$this->assertSame( 'foo', $comment->comment );
	}

	public function testSelfLinkTarget() {
		$comment = new CommentItem( 'foo' );
		$target = $this->createMock( LinkTarget::class );
		$comment->selfLinkTarget( $target );
		$this->assertSame( $target, $comment->selfLinkTarget );
	}

	public function testSamePage() {
		$comment = new CommentItem( 'foo' );
		$this->assertTrue( $comment->samePage()->samePage );
		$this->assertInstanceOf( CommentItem::class, $comment->samePage() );
		$this->assertFalse( $comment->samePage( false )->samePage );
		$this->assertTrue( $comment->samePage( true )->samePage );
	}

	public function testWikiId() {
		$comment = new CommentItem( 'foo' );
		$comment->wikiId( 'bar' );
		$this->assertSame( 'bar', $comment->wikiId );

		$comment->wikiId( false );
		$this->assertFalse( $comment->wikiId );

		$comment->wikiId( null );
		$this->assertNull( $comment->wikiId );
	}
}
