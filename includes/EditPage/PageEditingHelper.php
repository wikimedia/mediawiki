<?php

namespace MediaWiki\EditPage;

use MediaWiki\Content\Content;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\UnknownContentModelException;
use MediaWiki\Content\UnsupportedContentFormatException;
use MediaWiki\Page\Article;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Provides helper methods used by EditPage and a future editing backend (T157658).
 * @internal
 * @since 1.46
 */
class PageEditingHelper {

	public function __construct(
		private readonly IContentHandlerFactory $contentHandlerFactory,
		private readonly ParserFactory $parserFactory,
		private readonly RevisionStore $revisionStore,
	) {
	}

	/**
	 * Turns section name wikitext into anchors for use in HTTP redirects.
	 */
	public function guessSectionName( string $text ): string {
		$parser = $this->parserFactory->getMainInstance();
		$name = $parser->guessSectionNameFromWikiText( $text );
		// Per T216029, fragments in HTTP redirects need to be urlencoded,
		// otherwise Chrome double-escapes the rest of the URL.
		return '#' . urlencode( mb_substr( $name, 1 ) );
	}

	/**
	 * Returns the result of a three-way merge when undoing changes.
	 *
	 * @param WikiPage $page
	 * @param RevisionRecord $undoRev Newest revision being undone. Corresponds to `undo`
	 *        URL parameter.
	 * @param RevisionRecord $oldRev Revision that is being restored. Corresponds to
	 *        `undoafter` URL parameter.
	 * @param ?string &$error If false is returned, this will be set to "norev"
	 *   if the revision failed to load, or "failure" if the content handler
	 *   failed to merge the required changes.
	 */
	public function getUndoContent(
		WikiPage $page,
		RevisionRecord $undoRev,
		RevisionRecord $oldRev,
		?string &$error
	): Content|false {
		$handler = $this->contentHandlerFactory
			->getContentHandler( $undoRev->getSlot(
				SlotRecord::MAIN,
				RevisionRecord::RAW
			)->getModel() );
		$currentContent = $page->getRevisionRecord()
			->getContent( SlotRecord::MAIN );
		$undoContent = $undoRev->getContent( SlotRecord::MAIN );
		$undoAfterContent = $oldRev->getContent( SlotRecord::MAIN );
		$undoIsLatest = $page->getRevisionRecord()->getId() === $undoRev->getId();
		if ( $currentContent === null
			|| $undoContent === null
			|| $undoAfterContent === null
		) {
			$error = 'norev';
			return false;
		}

		$content = $handler->getUndoContent(
			$currentContent,
			$undoContent,
			$undoAfterContent,
			$undoIsLatest
		);
		if ( $content === false ) {
			$error = 'failure';
		}
		return $content;
	}

	/**
	 * Gets an editable textual representation of $content.
	 * The textual representation can be turned by into a Content object by the
	 * EditPage::toEditContent() method.
	 *
	 * If $content is null or false or a string, $content is returned unchanged.
	 *
	 * If the given Content object is not of a type that can be edited using
	 * the text base EditPage, an exception will be raised. Set
	 * $enableApiEditOverride to true to allow editing of non-textual
	 * content.
	 *
	 * @return string|null The editable text form of the content, or null if
	 * $content is not an instance of TextContent and $enableApiEditOverride
	 * is not true.
	 * @throws UnsupportedContentFormatException
	 */
	public function toEditText(
		Content|null|false|string $content,
		?string $contentFormat,
		bool $enableApiEditOverride,
	): ?string {
		if ( $content === null || $content === false ) {
			return '';
		}
		if ( is_string( $content ) ) {
			return $content;
		}

		if ( !$this->isSupportedContentModel( $content->getModel(), $enableApiEditOverride ) ) {
			return null;
		}

		return $content->serialize( $contentFormat );
	}

	/**
	 * Returns if the given content model is editable.
	 *
	 * @param string $modelId The ID of the content model to test. Use CONTENT_MODEL_XXX constants.
	 * @param bool $enableApiEditOverride
	 *
	 * @throws UnknownContentModelException If $modelId has no known handler
	 */
	public function isSupportedContentModel(
		string $modelId,
		bool $enableApiEditOverride,
	): bool {
		return $enableApiEditOverride ||
			$this->contentHandlerFactory->getContentHandler( $modelId )->supportsDirectEditing();
	}

	/**
	 * Get the content of the wanted revision, without section extraction.
	 *
	 * The result of this function can be used to compare user's input with
	 * section replaced in its context (using WikiPage::replaceSectionAtRev())
	 * to the original text of the edit.
	 *
	 * This differs from Article::getContent() that when a missing revision is
	 * encountered the result will be null and not the
	 * 'missing-revision' message.
	 *
	 * @param Authority $performer to get the revision for
	 * @param Article $article
	 * @param string $contentModel
	 * @param string $section
	 */
	public function getOriginalContent(
		Authority $performer,
		Article $article,
		string $contentModel,
		string $section,
	): ?Content {
		if ( $section === 'new' ) {
			return $this->getCurrentContent( $contentModel, $article->getPage() );
		}
		$revRecord = $article->fetchRevisionRecord();
		if ( $revRecord === null ) {
			return $this->contentHandlerFactory
				->getContentHandler( $contentModel )
				->makeEmptyContent();
		}
		return $revRecord->getContent( SlotRecord::MAIN, RevisionRecord::FOR_THIS_USER, $performer );
	}

	/**
	 * Get the current content of the page. This is basically similar to
	 * WikiPage::getContent( RevisionRecord::RAW ) except that when the page doesn't
	 * exist an empty content object is returned instead of null.
	 */
	public function getCurrentContent(
		string $contentModel,
		WikiPage $page,
	): Content {
		$revRecord = $page->getRevisionRecord();
		$content = $revRecord?->getContent( SlotRecord::MAIN, RevisionRecord::RAW );

		if ( $content === null ) {
			return $this->contentHandlerFactory
				->getContentHandler( $contentModel )
				->makeEmptyContent();
		}

		return $content;
	}

	/**
	 * Returns the RevisionRecord corresponding to the revision that was current at the time
	 * editing was initiated on the client even if the edit was based on an old revision
	 *
	 * @return ?RevisionRecord Current revision when editing was initiated on the client
	 */
	public function getExpectedParentRevision(
		?int $editRevId,
		?string $editTime,
		WikiPage $page,
	): ?RevisionRecord {
		if ( $editRevId ) {
			return $this->revisionStore->getRevisionById(
				$editRevId,
				IDBAccessObject::READ_LATEST
			);
		} elseif ( $editTime ) {
			return $this->revisionStore->getRevisionByTimestamp(
				$page,
				$editTime,
				IDBAccessObject::READ_LATEST
			);
		}
		return null;
	}

}
