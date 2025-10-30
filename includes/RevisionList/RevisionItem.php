<?php
/**
 * Holders of revision list for a single page
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RevisionList;

use MediaWiki\Context\RequestContext;
use MediaWiki\Linker\Linker;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;

/**
 * Item class for a live revision table row
 */
class RevisionItem extends RevisionItemBase {
	/** @var RevisionRecord */
	protected $revisionRecord;

	/** @var RequestContext */
	protected $context;

	/** @inheritDoc */
	public function __construct( RevisionListBase $list, $row ) {
		parent::__construct( $list, $row );
		$this->revisionRecord = MediaWikiServices::getInstance()
			->getRevisionFactory()
			->newRevisionFromRow( $row );
		$this->context = $list->getContext();
	}

	/**
	 * Get the RevisionRecord for the item
	 */
	protected function getRevisionRecord(): RevisionRecord {
		return $this->revisionRecord;
	}

	/** @inheritDoc */
	public function getIdField() {
		return 'rev_id';
	}

	/** @inheritDoc */
	public function getTimestampField() {
		return 'rev_timestamp';
	}

	/** @inheritDoc */
	public function getAuthorIdField() {
		return 'rev_user';
	}

	/** @inheritDoc */
	public function getAuthorNameField() {
		return 'rev_user_text';
	}

	/** @inheritDoc */
	public function canView() {
		return $this->getRevisionRecord()->userCan(
			RevisionRecord::DELETED_RESTRICTED,
			$this->context->getAuthority()
		);
	}

	/** @inheritDoc */
	public function canViewContent() {
		return $this->getRevisionRecord()->userCan(
			RevisionRecord::DELETED_TEXT,
			$this->context->getAuthority()
		);
	}

	/**
	 * @return bool
	 */
	public function isDeleted() {
		return $this->getRevisionRecord()->isDeleted( RevisionRecord::DELETED_TEXT );
	}

	/**
	 * Get the HTML link to the revision text.
	 * @todo Essentially a copy of RevDelRevisionItem::getRevisionLink. That class
	 * should inherit from this one, and implement an appropriate interface instead
	 * of extending RevDelItem
	 * @return string HTML
	 */
	protected function getRevisionLink() {
		$revRecord = $this->getRevisionRecord();
		$date = $this->list->getLanguage()->userTimeAndDate(
			$revRecord->getTimestamp(), $this->list->getUser() );

		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return htmlspecialchars( $date );
		}
		$linkRenderer = $this->getLinkRenderer();
		return $linkRenderer->makeKnownLink(
			$this->list->getPage(),
			$date,
			[],
			[
				'oldid' => $revRecord->getId(),
				'unhide' => 1
			]
		);
	}

	/**
	 * Get the HTML link to the diff.
	 * @todo Essentially a copy of RevDelRevisionItem::getDiffLink. That class
	 * should inherit from this one, and implement an appropriate interface instead
	 * of extending RevDelItem
	 * @return string HTML
	 */
	protected function getDiffLink() {
		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return $this->context->msg( 'diff' )->escaped();
		} else {
			$linkRenderer = $this->getLinkRenderer();
			return $linkRenderer->makeKnownLink(
				$this->list->getPage(),
				$this->list->msg( 'diff' )->text(),
				[],
				[
					'diff' => $this->getRevisionRecord()->getId(),
					'oldid' => 'prev',
					'unhide' => 1
				]
			);
		}
	}

	/**
	 * @todo Essentially a copy of RevDelRevisionItem::getHTML. That class
	 * should inherit from this one, and implement an appropriate interface instead
	 * of extending RevDelItem
	 * @return string HTML
	 */
	public function getHTML() {
		$difflink = $this->context->msg( 'parentheses' )
			->rawParams( $this->getDiffLink() )->escaped();
		$revlink = $this->getRevisionLink();
		$userlink = Linker::revUserLink( $this->getRevisionRecord() );
		$comment = MediaWikiServices::getInstance()->getCommentFormatter()
			->formatRevision( $this->getRevisionRecord(), $this->context->getAuthority() );
		if ( $this->isDeleted() ) {
			$class = Linker::getRevisionDeletedClass( $this->getRevisionRecord() );
			$revlink = "<span class=\"$class\">$revlink</span>";
		}
		return "<li>$difflink $revlink $userlink $comment</li>";
	}
}
/** @deprecated class alias since 1.43 */
class_alias( RevisionItem::class, 'RevisionItem' );
