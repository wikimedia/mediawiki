<?php

namespace MediaWiki\CommentFormatter;

use ArrayIterator;
use CommentStore;
use IteratorIterator;
use TitleValue;
use Traversable;

/**
 * An adaptor which converts a row iterator into a CommentItem iterator for
 * batch formatting.
 *
 * Fluent-style mutators are provided to configure how comment text is extracted
 * from rows.
 *
 * Using an iterator for this configuration, instead of putting the
 * options in CommentBatch, allows CommentBatch to be a simple single
 * class without a CommentStore dependency.
 *
 * @since 1.38
 */
class RowCommentIterator extends IteratorIterator {
	/** @var CommentStore */
	private $commentStore;
	/** @var string|null */
	private $commentKey;
	/** @var string|null */
	private $namespaceField;
	/** @var string|null */
	private $titleField;
	/** @var string|null */
	private $indexField;

	/**
	 * @internal Use RowCommentFormatter::rows()
	 * @param CommentStore $commentStore
	 * @param Traversable|array $rows
	 */
	public function __construct( CommentStore $commentStore, $rows ) {
		if ( is_array( $rows ) ) {
			parent::__construct( new ArrayIterator( $rows ) );
		} else {
			parent::__construct( $rows );
		}

		$this->commentStore = $commentStore;
	}

	/**
	 * Set what CommentStore calls the key -- typically a legacy field name
	 * which once held a comment. This must be called before attempting
	 * iteration.
	 *
	 * @param string $key
	 * @return $this
	 */
	public function commentKey( $key ) {
		$this->commentKey = $key;
		return $this;
	}

	/**
	 * Set the namespace field. If this is not called, the item will not have
	 * a self-link target, although it may be provided by the batch.
	 *
	 * @param string $field
	 * @return $this
	 */
	public function namespaceField( $field ) {
		$this->namespaceField = $field;
		return $this;
	}

	/**
	 * Set the title field. If this is not called, the item will not have
	 * a self-link target, although it may be provided by the batch.
	 *
	 * @param string $field
	 * @return $this
	 */
	public function titleField( $field ) {
		$this->titleField = $field;
		return $this;
	}

	/**
	 * Set the index field. Values from this field will appear as array keys
	 * in the final formatted comment array. If unset, the array will be
	 * numerically indexed.
	 *
	 * @param string $field
	 * @return $this
	 */
	public function indexField( $field ) {
		$this->indexField = $field;
		return $this;
	}

	public function key() {
		if ( $this->indexField ) {
			return parent::current()->{$this->indexField};
		} else {
			return parent::key();
		}
	}

	public function current() {
		if ( $this->commentKey === null ) {
			throw new \RuntimeException( __METHOD__ . ': commentKey must be specified' );
		}
		$row = parent::current();
		$comment = $this->commentStore->getComment( $this->commentKey, $row );
		$item = new CommentItem( (string)$comment->text );
		if ( $this->namespaceField && $this->titleField ) {
			$item->selfLinkTarget( new TitleValue(
				(int)$row->{$this->namespaceField},
				(string)$row->{$this->titleField}
			) );
		}
		return $item;
	}
}
