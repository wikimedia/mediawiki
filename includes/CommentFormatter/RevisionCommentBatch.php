<?php

namespace MediaWiki\CommentFormatter;

use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\RevisionRecord;

/**
 * Fluent interface for revision comment batch inputs.
 *
 * @since 1.38
 */
class RevisionCommentBatch {
	/** @var CommentFormatter */
	private $formatter;
	/** @var Authority|null */
	private $authority;
	/** @var iterable<RevisionRecord> */
	private $revisions;
	/** @var bool */
	private $samePage = false;
	/** @var bool */
	private $isPublic = false;
	/** @var bool */
	private $useParentheses = false;
	/** @var bool */
	private $indexById = false;

	/**
	 * @param CommentFormatter $formatter
	 */
	public function __construct( CommentFormatter $formatter ) {
		$this->formatter = $formatter;
	}

	/**
	 * Set the authority to use for permission checks. This must be called
	 * prior to execute().
	 *
	 * @param Authority $authority
	 * @return $this
	 */
	public function authority( Authority $authority ) {
		$this->authority = $authority;
		return $this;
	}

	/**
	 * Set the revisions to extract comments from.
	 *
	 * @param iterable<RevisionRecord> $revisions
	 * @return $this
	 */
	public function revisions( $revisions ) {
		$this->revisions = $revisions;
		return $this;
	}

	/**
	 * Set the same-page option. If this is true, section links and fragment-
	 * only wikilinks are rendered with an href that is a fragment-only URL.
	 * If it is false (the default), such links go to the self link title.
	 *
	 * This is equivalent to $local in the old Linker methods.
	 *
	 * @param bool $samePage
	 * @return $this
	 */
	public function samePage( $samePage = true ) {
		$this->samePage = $samePage;
		return $this;
	}

	/**
	 * Wrap the comment with parentheses. This has no effect if the useBlock
	 * option is not enabled.
	 *
	 * Unlike the legacy Linker::commentBlock(), this option defaults to false
	 * if this method is not called, since that better preserves the fluent
	 * style.
	 *
	 * @param bool $useParentheses
	 * @return $this
	 */
	public function useParentheses( $useParentheses = true ) {
		$this->useParentheses = $useParentheses;
		return $this;
	}

	/**
	 * If this is true, show the comment only if all users can see it.
	 *
	 * We'll call it hideIfDeleted() since public is a keyword and isPublic()
	 * has an inappropriate verb.
	 *
	 * @param bool $isPublic
	 * @return $this
	 */
	public function hideIfDeleted( $isPublic = true ) {
		$this->isPublic = $isPublic;
		return $this;
	}

	/**
	 * If this is true, the array keys in the return value will be the revision
	 * IDs instead of the keys from the input array.
	 *
	 * @param bool $indexById
	 * @return $this
	 */
	public function indexById( $indexById = true ) {
		$this->indexById = $indexById;
		return $this;
	}

	/**
	 * Format the comments.
	 *
	 * @return string[] Formatted comments. The array key is either the field
	 *   value specified by indexField(), or if that was not called, it is the
	 *   key from the array passed to revisions().
	 */
	public function execute() {
		return $this->formatter->formatRevisions(
			$this->revisions,
			$this->authority,
			$this->samePage,
			$this->isPublic,
			$this->useParentheses,
			$this->indexById
		);
	}
}
