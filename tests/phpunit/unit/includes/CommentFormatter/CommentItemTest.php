<?php
/**
 * @license GPL-2.0-or-later
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
