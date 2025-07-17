<?php

namespace MediaWiki\CommentFormatter;

use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\RevisionRecord;
use Traversable;

/**
 * This is the main service interface for converting single-line comments from
 * various DB comment fields into HTML.
 *
 * @since 1.38
 */
class CommentFormatter {
	/** @var CommentParserFactory */
	protected $parserFactory;

	/**
	 * @internal Use MediaWikiServices::getCommentFormatter()
	 *
	 * @param CommentParserFactory $parserFactory
	 */
	public function __construct( CommentParserFactory $parserFactory ) {
		$this->parserFactory = $parserFactory;
	}

	/**
	 * Format comments using a fluent interface.
	 *
	 * @return CommentBatch
	 */
	public function createBatch() {
		return new CommentBatch( $this );
	}

	/**
	 * Format a single comment. Similar to the old Linker::formatComment().
	 *
	 * @param string $comment
	 * @param LinkTarget|null $selfLinkTarget The title used for fragment-only
	 *   and section links, formerly $title.
	 * @param bool $samePage If true, self links are rendered with a fragment-
	 *   only URL. Formerly $local.
	 * @param string|false|null $wikiId ID of the wiki to link to (if not the local
	 *   wiki), as used by WikiMap.
	 * @return string
	 */
	public function format( string $comment, ?LinkTarget $selfLinkTarget = null,
		$samePage = false, $wikiId = false
	) {
		return $this->formatInternal( $comment, true, false, false,
			$selfLinkTarget, $samePage, $wikiId );
	}

	/**
	 * Wrap a comment in standard punctuation and formatting if
	 * it's non-empty, otherwise return an empty string.
	 *
	 * @param string $comment
	 * @param LinkTarget|null $selfLinkTarget The title used for fragment-only
	 *   and section links, formerly $title.
	 * @param bool $samePage If true, self links are rendered with a fragment-
	 *   only URL. Formerly $local.
	 * @param string|false|null $wikiId ID of the wiki to link to (if not the local
	 *   wiki), as used by WikiMap.
	 * @param bool $useParentheses
	 * @return string
	 */
	public function formatBlock( string $comment, ?LinkTarget $selfLinkTarget = null,
		$samePage = false, $wikiId = false, $useParentheses = true
	) {
		return $this->formatInternal( $comment, true, true, $useParentheses,
			$selfLinkTarget, $samePage, $wikiId );
	}

	/**
	 * Format a comment, passing through HTML in the input to the output.
	 * This is unsafe and exists only for backwards compatibility with
	 * Linker::formatLinksInComment().
	 *
	 * In new code, use formatLinks() or createBatch()->disableSectionLinks().
	 *
	 * @internal
	 *
	 * @param string $comment
	 * @param LinkTarget|null $selfLinkTarget The title used for fragment-only
	 *   and section links, formerly $title.
	 * @param bool $samePage If true, self links are rendered with a fragment-
	 *   only URL. Formerly $local.
	 * @param string|false|null $wikiId ID of the wiki to link to (if not the local
	 *   wiki), as used by WikiMap.
	 * @return string
	 */
	public function formatLinksUnsafe( string $comment, ?LinkTarget $selfLinkTarget = null,
		$samePage = false, $wikiId = false
	) {
		$parser = $this->parserFactory->create();
		$preprocessed = $parser->preprocessUnsafe( $comment, $selfLinkTarget,
			$samePage, $wikiId, false );
		return $parser->finalize( $preprocessed );
	}

	/**
	 * Format links in a comment, ignoring section links in C-style comments.
	 *
	 * @param string $comment
	 * @param LinkTarget|null $selfLinkTarget The title used for fragment-only
	 *   and section links, formerly $title.
	 * @param bool $samePage If true, self links are rendered with a fragment-
	 *   only URL. Formerly $local.
	 * @param string|false|null $wikiId ID of the wiki to link to (if not the local
	 *   wiki), as used by WikiMap.
	 * @return string
	 */
	public function formatLinks( string $comment, ?LinkTarget $selfLinkTarget = null,
		$samePage = false, $wikiId = false
	) {
		return $this->formatInternal( $comment, false, false, false,
			$selfLinkTarget, $samePage, $wikiId );
	}

	/**
	 * Format a single comment with many ugly boolean parameters.
	 *
	 * @param string $comment
	 * @param bool $enableSectionLinks
	 * @param bool $useBlock
	 * @param bool $useParentheses
	 * @param LinkTarget|null $selfLinkTarget The title used for fragment-only
	 *   and section links, formerly $title.
	 * @param bool $samePage If true, self links are rendered with a fragment-
	 *   only URL. Formerly $local.
	 * @param string|false|null $wikiId ID of the wiki to link to (if not the local
	 *   wiki), as used by WikiMap.
	 * @return string|string[]
	 */
	private function formatInternal( $comment, $enableSectionLinks, $useBlock, $useParentheses,
		$selfLinkTarget = null, $samePage = false, $wikiId = false
	) {
		$parser = $this->parserFactory->create();
		$preprocessed = $parser->preprocess( $comment, $selfLinkTarget, $samePage, $wikiId,
			$enableSectionLinks );
		$output = $parser->finalize( $preprocessed );
		if ( $useBlock ) {
			$output = $this->wrapCommentWithBlock( $output, $useParentheses );
		}
		return $output;
	}

	/**
	 * Format comments which are provided as strings and all have the same
	 * self-link target and other options.
	 *
	 * If you need a different title for each comment, use createBatch().
	 *
	 * @param string[] $strings
	 * @param LinkTarget|null $selfLinkTarget The title used for fragment-only
	 *   and section links, formerly $title.
	 * @param bool $samePage If true, self links are rendered with a fragment-
	 *   only URL. Formerly $local.
	 * @param string|false|null $wikiId ID of the wiki to link to (if not the local
	 *   wiki), as used by WikiMap.
	 * @return string[]
	 */
	public function formatStrings( $strings, ?LinkTarget $selfLinkTarget = null,
		$samePage = false, $wikiId = false
	) {
		$parser = $this->parserFactory->create();
		$outputs = [];
		foreach ( $strings as $i => $comment ) {
			$outputs[$i] = $parser->preprocess( $comment, $selfLinkTarget, $samePage, $wikiId );
		}
		return $parser->finalize( $outputs );
	}

	/**
	 * Wrap and format the given revision's comment block, if the specified
	 * user is allowed to view it.
	 *
	 * This method produces HTML that requires CSS styles in mediawiki.interface.helpers.styles.
	 *
	 * NOTE: revision comments are special. This is not the same as getting a
	 * revision comment as a string and then formatting it with format().
	 *
	 * @param RevisionRecord $revision The revision to extract the comment and
	 *   title from. The title should always be populated, to avoid an additional
	 *   DB query.
	 * @param Authority $authority The user viewing the comment
	 * @param bool $samePage If true, self links are rendered with a fragment-
	 *   only URL. Formerly $local.
	 * @param bool $isPublic Show only if all users can see it
	 * @param bool $useParentheses Whether the comment is wrapped in parentheses
	 * @return string
	 */
	public function formatRevision(
		RevisionRecord $revision,
		Authority $authority,
		$samePage = false,
		$isPublic = false,
		$useParentheses = true
	) {
		$parser = $this->parserFactory->create();
		return $parser->finalize( $this->preprocessRevComment(
			$parser, $authority, $revision, $samePage, $isPublic, $useParentheses ) );
	}

	/**
	 * Format multiple revision comments.
	 *
	 * @see CommentFormatter::formatRevision()
	 *
	 * @param iterable<RevisionRecord> $revisions
	 * @param Authority $authority
	 * @param bool $samePage
	 * @param bool $isPublic
	 * @param bool $useParentheses
	 * @param bool $indexById
	 * @return string|string[]
	 */
	public function formatRevisions(
		$revisions,
		Authority $authority,
		$samePage = false,
		$isPublic = false,
		$useParentheses = true,
		$indexById = false
	) {
		$parser = $this->parserFactory->create();
		$outputs = [];
		foreach ( $revisions as $i => $rev ) {
			if ( $indexById ) {
				$key = $rev->getId();
			} else {
				$key = $i;
			}
			// @phan-suppress-next-line PhanTypeMismatchDimAssignment getId does not return null here
			$outputs[$key] = $this->preprocessRevComment(
				$parser, $authority, $rev, $samePage, $isPublic, $useParentheses );
		}
		return $parser->finalize( $outputs );
	}

	/**
	 * Format a batch of revision comments using a fluent interface.
	 *
	 * @return RevisionCommentBatch
	 */
	public function createRevisionBatch() {
		return new RevisionCommentBatch( $this );
	}

	/**
	 * Format an iterator over CommentItem objects
	 *
	 * A shortcut for createBatch()->comments()->execute() for when you
	 * need to pass no other options.
	 *
	 * @param iterable<CommentItem>|Traversable $items
	 * @return string[]
	 */
	public function formatItems( $items ) {
		return $this->formatItemsInternal( $items );
	}

	/**
	 * @internal For use by CommentBatch
	 *
	 * Format comments with nullable batch options.
	 *
	 * @param iterable<CommentItem> $items
	 * @param LinkTarget|null $selfLinkTarget
	 * @param bool|null $samePage
	 * @param string|false|null $wikiId
	 * @param bool|null $enableSectionLinks
	 * @param bool|null $useBlock
	 * @param bool|null $useParentheses
	 * @return string[]
	 */
	public function formatItemsInternal( $items, $selfLinkTarget = null,
		$samePage = null, $wikiId = null, $enableSectionLinks = null,
		$useBlock = null, $useParentheses = null
	) {
		$outputs = [];
		$parser = $this->parserFactory->create();
		foreach ( $items as $index => $item ) {
			$preprocessed = $parser->preprocess(
				$item->comment,
				$item->selfLinkTarget ?? $selfLinkTarget,
				$item->samePage ?? $samePage ?? false,
				$item->wikiId ?? $wikiId ?? false,
				$enableSectionLinks ?? true
			);
			if ( $useBlock ?? false ) {
				$preprocessed = $this->wrapCommentWithBlock(
					$preprocessed,
					$useParentheses ?? true
				);
			}
			$outputs[$index] = $preprocessed;
		}
		return $parser->finalize( $outputs );
	}

	/**
	 * Wrap a comment in standard punctuation and formatting if
	 * it's non-empty, otherwise return empty string.
	 *
	 * @param string $formatted
	 * @param bool $useParentheses Whether the comment is wrapped in parentheses
	 *
	 * @return string
	 */
	protected function wrapCommentWithBlock(
		$formatted, $useParentheses
	) {
		// '*' used to be the comment inserted by the software way back
		// in antiquity in case none was provided, here for backwards
		// compatibility, acc. to [brooke] -ævar
		if ( $formatted == '' || $formatted == '*' ) {
			return '';
		}
		if ( $useParentheses ) {
			$formatted = wfMessage( 'parentheses' )->rawParams( $formatted )->escaped();
			$classNames = 'comment';
		} else {
			$classNames = 'comment comment--without-parentheses';
		}
		return " <span class=\"$classNames\">$formatted</span>";
	}

	/**
	 * Preprocess and wrap a revision comment.
	 *
	 * @param CommentParser $parser
	 * @param Authority $authority
	 * @param RevisionRecord $revRecord
	 * @param bool $samePage Whether section links should refer to local page
	 * @param bool $isPublic Show only if all users can see it
	 * @param bool $useParentheses (optional) Wrap comments in parentheses where needed
	 * @return string HTML fragment with link markers
	 */
	private function preprocessRevComment(
		CommentParser $parser,
		Authority $authority,
		RevisionRecord $revRecord,
		$samePage = false,
		$isPublic = false,
		$useParentheses = true
	) {
		if ( $revRecord->getComment( RevisionRecord::RAW ) === null ) {
			return "";
		}
		if ( $revRecord->audienceCan(
			RevisionRecord::DELETED_COMMENT,
			$isPublic ? RevisionRecord::FOR_PUBLIC : RevisionRecord::FOR_THIS_USER,
			$authority )
		) {
			$comment = $revRecord->getComment( RevisionRecord::FOR_THIS_USER, $authority );
			$block = $parser->preprocess(
				$comment ? $comment->text : '',
				$revRecord->getPageAsLinkTarget(),
				$samePage,
				$revRecord->getWikiId(),
				true
			);
			$block = $this->wrapCommentWithBlock( $block, $useParentheses );
		} else {
			$block = " <span class=\"comment\">" . wfMessage( 'rev-deleted-comment' )->escaped() . "</span>";
		}
		if ( $revRecord->isDeleted( RevisionRecord::DELETED_COMMENT ) ) {
			$class = Linker::getRevisionDeletedClass( $revRecord );
			return " <span class=\"$class comment\">$block</span>";
		}
		return $block;
	}

}
