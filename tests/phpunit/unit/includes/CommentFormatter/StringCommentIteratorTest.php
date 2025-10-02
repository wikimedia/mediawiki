<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\CommentFormatter;

use MediaWiki\CommentFormatter\CommentItem;
use MediaWiki\CommentFormatter\StringCommentIterator;
use MediaWikiUnitTestCase;

/**
 * @group CommentFormatter
 * @covers \MediaWiki\CommentFormatter\StringCommentIterator
 */
class StringCommentIteratorTest extends MediaWikiUnitTestCase {

	public function testConstruct() {
		$iterator = new StringCommentIterator( [ 'Some comment', 'Another comment' ] );
		$this->assertInstanceOf( StringCommentIterator::class, $iterator );
	}

	public function testCurrent() {
		$iterator = new StringCommentIterator( [ 'Some comment', 'Another comment' ] );
		$this->assertInstanceOf( CommentItem::class, $iterator->current() );
	}
}
