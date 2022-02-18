<?php

namespace MediaWiki\CommentFormatter;

use MediaWiki\Linker\LinkTarget;
use Traversable;

/**
 * This class provides a fluent interface for formatting a batch of comments.
 *
 * @since 1.38
 */
class CommentBatch {
	/** @var CommentFormatter */
	private $formatter;
	/** @var iterable<CommentItem>|Traversable */
	private $comments;
	/** @var bool|null */
	private $useBlock;
	/** @var bool|null */
	private $useParentheses;
	/** @var LinkTarget|null */
	private $selfLinkTarget;
	/** @var bool|null */
	private $samePage;
	/** @var string|false|null */
	private $wikiId;
	/** @var bool|null */
	private $enableSectionLinks;

	/**
	 * @internal Use CommentFormatter::createBatch()
	 *
	 * @param CommentFormatter $formatter
	 */
	public function __construct( CommentFormatter $formatter ) {
		$this->formatter = $formatter;
	}

	/**
	 * Set the comments to be formatted. This can be an array of CommentItem
	 * objects, or it can be an iterator which generates CommentItem objects.
	 *
	 * Theoretically iterable should imply Traversable, but PHPStorm gives an
	 * error when RowCommentIterator is passed as iterable<CommentItem>.
	 *
	 * @param iterable<CommentItem>|Traversable $comments
	 * @return $this
	 */
	public function comments( $comments ) {
		$this->comments = $comments;
		return $this;
	}

	/**
	 * Specify the comments to be formatted as an array of strings. This is a
	 * simplified wrapper for comments() which does not allow you to set options
	 * on a per-comment basis.
	 *
	 * $strings must be an array -- use comments() if you want to use an iterator.
	 *
	 * @param string[] $strings
	 * @return $this
	 */
	public function strings( array $strings ) {
		$this->comments = new StringCommentIterator( $strings );
		return $this;
	}

	/**
	 * Wrap each comment in standard punctuation and formatting if it's
	 * non-empty. Empty comments remain empty. This causes the batch to work
	 * like the old Linker::commentBlock().
	 *
	 * If this function is not called, the option is false.
	 *
	 * @param bool $useBlock
	 * @return $this
	 */
	public function useBlock( $useBlock = true ) {
		$this->useBlock = $useBlock;
		return $this;
	}

	/**
	 * Wrap each comment with parentheses. This has no effect if the useBlock
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
	 * Set the title to be used for self links in the comments. If there is no
	 * title specified either here or in the item, fragment links are not
	 * expanded.
	 *
	 * @param LinkTarget $selfLinkTarget
	 * @return $this
	 */
	public function selfLinkTarget( LinkTarget $selfLinkTarget ) {
		$this->selfLinkTarget = $selfLinkTarget;
		return $this;
	}

	/**
	 * Set the option to enable/disable section links formatted as C-style
	 * comments, as used in revision comments to indicate the section which
	 * was edited.
	 *
	 * If the method is not called, the option is true. Setting this to false
	 * approximately emulates Linker::formatLinksInComment() except that HTML
	 * in the input is escaped.
	 *
	 * @param bool $enable
	 * @return $this
	 */
	public function enableSectionLinks( $enable ) {
		$this->enableSectionLinks = $enable;
		return $this;
	}

	/**
	 * Disable section links formatted as C-style comments, as used in revision
	 * comments to indicate the section which was edited. Calling this
	 * approximately emulates Linker::formatLinksInComment() except that HTML
	 * in the input is escaped.
	 *
	 * @return $this
	 */
	public function disableSectionLinks() {
		$this->enableSectionLinks = false;
		return $this;
	}

	/**
	 * Set the same-page option. If this is true, section links and fragment-
	 * only wikilinks are rendered with an href that is a fragment-only URL.
	 * If it is false (the default), such links go to the self link title.
	 *
	 * This can also be set per-item using CommentItem::samePage().
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
	 * ID of the wiki to link to (if not the local wiki), as used by WikiMap.
	 * This is used to render comments which are loaded from a foreign wiki.
	 * This only affects links which are syntactically internal -- it has no
	 * effect on interwiki links.
	 *
	 * This can also be set per-item using CommentItem::wikiId().
	 *
	 * @param string|false|null $wikiId
	 * @return $this
	 */
	public function wikiId( $wikiId ) {
		$this->wikiId = $wikiId;
		return $this;
	}

	/**
	 * Format the comments and produce an array of HTML fragments.
	 *
	 * @return string[]
	 */
	public function execute() {
		return $this->formatter->formatItemsInternal(
			$this->comments,
			$this->selfLinkTarget,
			$this->samePage,
			$this->wikiId,
			$this->enableSectionLinks,
			$this->useBlock,
			$this->useParentheses
		);
	}

}
