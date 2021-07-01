<?php

namespace MediaWiki\CommentFormatter;

use CommentStore;
use Traversable;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * This is basically a CommentFormatter with a CommentStore dependency, allowing
 * it to retrieve comment texts directly from database result wrappers.
 *
 * @since 1.38
 */
class RowCommentFormatter extends CommentFormatter {
	/** @var CommentStore */
	private $commentStore;

	/**
	 * @internal Use MediaWikiServices::getRowCommentFormatter()
	 *
	 * @param CommentParserFactory $commentParserFactory
	 * @param CommentStore $commentStore
	 */
	public function __construct(
		CommentParserFactory $commentParserFactory,
		CommentStore $commentStore
	) {
		parent::__construct( $commentParserFactory );
		$this->commentStore = $commentStore;
	}

	/**
	 * Format DB rows using a fluent interface. Pass the return value of this
	 * function to CommentBatch::comments().
	 *
	 * Example:
	 *   $comments = $rowCommentFormatter->createBatch()
	 *       ->comments(
	 *           $rowCommentFormatter->rows( $rows )
	 *           ->commentField( 'img_comment' )
	 *       )
	 *       ->useBlock( true )
	 *       ->execute();
	 *
	 * @param Traversable|array $rows
	 * @return RowCommentIterator
	 */
	public function rows( $rows ) {
		return new RowCommentIterator( $this->commentStore, $rows );
	}

	/**
	 * Format DB rows using a parametric interface.
	 *
	 * @param iterable<\stdClass>|IResultWrapper $rows
	 * @param string $commentKey The comment key to pass through to CommentStore,
	 *   typically a legacy field name.
	 * @param string|null $namespaceField The namespace field for the self-link
	 *   target, or null to have no self-link target.
	 * @param string|null $titleField The title field for the self-link target,
	 *   or null to have no self-link target.
	 * @param string|null $indexField The field to use for array keys in the
	 *   result, or null to use the same keys as in the input $rows
	 * @param bool $useBlock Wrap the output in standard punctuation and
	 *   formatting if it's non-empty.
	 * @param bool $useParentheses Wrap the output with parentheses. Has no
	 *   effect if $useBlock is false.
	 * @return string[] The formatted comment. The key will be the value of the
	 *   index field if an index field was specified, or the key from the
	 *   corresponding element of $rows if no index field was specified.
	 */
	public function formatRows( $rows, $commentKey, $namespaceField = null, $titleField = null,
		$indexField = null, $useBlock = false, $useParentheses = true
	) {
		return $this->createBatch()
			->comments(
				$this->rows( $rows )
				->commentKey( $commentKey )
				->namespaceField( $namespaceField )
				->titleField( $titleField )
				->indexField( $indexField )
			)
			->useBlock( $useBlock )
			->useParentheses( $useParentheses )
			->execute();
	}
}
