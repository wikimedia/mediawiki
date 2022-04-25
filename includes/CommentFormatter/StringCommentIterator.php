<?php

namespace MediaWiki\CommentFormatter;

use ArrayIterator;

/**
 * An adaptor which converts an array of strings to an iterator of CommentItem
 * objects.
 *
 * @since 1.38
 */
class StringCommentIterator extends ArrayIterator {
	/**
	 * @internal Use CommentBatch::strings()
	 * @param string[] $strings
	 */
	public function __construct( $strings ) {
		parent::__construct( $strings );
	}

	public function current(): CommentItem {
		return new CommentItem( parent::current() );
	}
}
