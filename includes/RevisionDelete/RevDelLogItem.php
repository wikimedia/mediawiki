<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup RevisionDelete
 */

namespace MediaWiki\RevisionDelete;

use MediaWiki\Api\ApiResult;
use MediaWiki\ChangeTags\ChangeTagsFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Html\Html;
use MediaWiki\Logging\DatabaseLogEntry;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogFormatter;
use MediaWiki\Logging\LogFormatterFactory;
use MediaWiki\Logging\LogPage;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\RevisionList\RevisionListBase;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Item class for a logging table row
 */
class RevDelLogItem extends RevDelItem {

	/**
	 * @param RevisionListBase $list
	 * @param stdClass $row DB result row
	 * @param CommentStore $commentStore
	 * @param IConnectionProvider $dbProvider
	 * @param LogFormatterFactory $logFormatterFactory
	 * @param ChangeTagsFormatter $changeTagsFormatter
	 */
	public function __construct(
		RevisionListBase $list,
		$row,
		private readonly CommentStore $commentStore,
		private readonly IConnectionProvider $dbProvider,
		private readonly LogFormatterFactory $logFormatterFactory,
		private readonly ChangeTagsFormatter $changeTagsFormatter,
	) {
		parent::__construct( $list, $row );
	}

	/** @inheritDoc */
	public function getIdField() {
		return 'log_id';
	}

	/** @inheritDoc */
	public function getTimestampField() {
		return 'log_timestamp';
	}

	/** @inheritDoc */
	public function getAuthorIdField() {
		return 'log_user';
	}

	/** @inheritDoc */
	public function getAuthorNameField() {
		return 'log_user_text';
	}

	/** @inheritDoc */
	public function getAuthorActorField() {
		return 'log_actor';
	}

	/** @inheritDoc */
	public function canView() {
		return LogEventsList::userCan(
			$this->row, LogPage::DELETED_RESTRICTED, $this->list->getAuthority()
		);
	}

	/** @inheritDoc */
	public function canViewContent() {
		return true; // none
	}

	/** @inheritDoc */
	public function getBits() {
		return (int)$this->row->log_deleted;
	}

	/** @inheritDoc */
	public function setBits( $bits ) {
		$dbw = $this->dbProvider->getPrimaryDatabase();

		$dbw->newUpdateQueryBuilder()
			->update( 'logging' )
			->set( [ 'log_deleted' => $bits ] )
			->where( [
				'log_id' => $this->row->log_id,
				'log_deleted' => $this->getBits() // cas
			] )
			->caller( __METHOD__ )->execute();

		if ( !$dbw->affectedRows() ) {
			// Concurrent fail!
			return false;
		}

		$dbw->newUpdateQueryBuilder()
			->update( 'recentchanges' )
			->set( [
				'rc_deleted' => $bits,
				'rc_patrolled' => RecentChange::PRC_AUTOPATROLLED
			] )
			->where( [
				'rc_logid' => $this->row->log_id,
				'rc_timestamp' => $this->row->log_timestamp // index
			] )
			->caller( __METHOD__ )->execute();

		return true;
	}

	/** @inheritDoc */
	public function getHTML() {
		$date = htmlspecialchars( $this->list->getLanguage()->userTimeAndDate(
			$this->row->log_timestamp, $this->list->getUser() ) );
		$title = Title::makeTitle( $this->row->log_namespace, $this->row->log_title );
		$formatter = $this->logFormatterFactory->newFromRow( $this->row );
		$formatter->setContext( $this->list->getContext() );
		$formatter->setAudience( LogFormatter::FOR_THIS_USER );

		// Log link for this page
		$loglink = $this->getLinkRenderer()->makeLink(
			SpecialPage::getTitleFor( 'Log' ),
			$this->list->msg( 'log' )->text(),
			[],
			[ 'page' => $title->getPrefixedText() ]
		);
		$loglink = $this->list->msg( 'parentheses' )->rawParams( $loglink )->escaped();
		// User links and action text
		$action = $formatter->getActionText();

		$dir = $this->list->getLanguage()->getDir();
		$comment = Html::rawElement( 'bdi', [ 'dir' => $dir ], $formatter->getComment() );

		$content = "$loglink $date $action $comment";
		$attribs = [];
		if ( $this->row->ts_tags ) {
			[ $tagSummary, $classes ] = $this->changeTagsFormatter->formatTagsAsSummaryList(
				$this->row->ts_tags,
				$this->list->getContext(),
				$this->list->getAuthority()
			);
			$content .= " $tagSummary";
			$attribs['class'] = $classes;
		}
		return Html::rawElement( 'li', $attribs, $content );
	}

	/** @inheritDoc */
	public function getApiData( ApiResult $result ) {
		$logEntry = DatabaseLogEntry::newFromRow( $this->row );
		$user = $this->list->getAuthority();
		$ret = [
			'id' => $logEntry->getId(),
			'type' => $logEntry->getType(),
			'action' => $logEntry->getSubtype(),
			'userhidden' => (bool)$logEntry->isDeleted( LogPage::DELETED_USER ),
			'commenthidden' => (bool)$logEntry->isDeleted( LogPage::DELETED_COMMENT ),
			'actionhidden' => (bool)$logEntry->isDeleted( LogPage::DELETED_ACTION ),
		];

		if ( LogEventsList::userCan( $this->row, LogPage::DELETED_ACTION, $user ) ) {
			$ret['params'] = $this->logFormatterFactory->newFromEntry( $logEntry )->formatParametersForApi();
		}
		if ( LogEventsList::userCan( $this->row, LogPage::DELETED_USER, $user ) ) {
			$ret += [
				'userid' => $this->row->log_user ?? 0,
				'user' => $this->row->log_user_text,
			];
		}
		if ( LogEventsList::userCan( $this->row, LogPage::DELETED_COMMENT, $user ) ) {
			$ret += [
				'comment' => $this->commentStore->getComment( 'log_comment', $this->row )->text,
			];
		}

		return $ret;
	}
}

/** @deprecated class alias since 1.46 */
class_alias( RevDelLogItem::class, 'RevDelLogItem' );
