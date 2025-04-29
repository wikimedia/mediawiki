<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup RevisionDelete
 */

use MediaWiki\Api\ApiResult;
use MediaWiki\ChangeTags\ChangeTags;
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
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Item class for a logging table row
 */
class RevDelLogItem extends RevDelItem {

	/** @var CommentStore */
	private $commentStore;
	private IConnectionProvider $dbProvider;
	private LogFormatterFactory $logFormatterFactory;

	/**
	 * @param RevisionListBase $list
	 * @param stdClass $row DB result row
	 * @param CommentStore $commentStore
	 * @param IConnectionProvider $dbProvider
	 * @param LogFormatterFactory $logFormatterFactory
	 */
	public function __construct(
		RevisionListBase $list,
		$row,
		CommentStore $commentStore,
		IConnectionProvider $dbProvider,
		LogFormatterFactory $logFormatterFactory
	) {
		parent::__construct( $list, $row );
		$this->commentStore = $commentStore;
		$this->dbProvider = $dbProvider;
		$this->logFormatterFactory = $logFormatterFactory;
	}

	public function getIdField() {
		return 'log_id';
	}

	public function getTimestampField() {
		return 'log_timestamp';
	}

	public function getAuthorIdField() {
		return 'log_user';
	}

	public function getAuthorNameField() {
		return 'log_user_text';
	}

	public function getAuthorActorField() {
		return 'log_actor';
	}

	public function canView() {
		return LogEventsList::userCan(
			$this->row, LogPage::DELETED_RESTRICTED, $this->list->getAuthority()
		);
	}

	public function canViewContent() {
		return true; // none
	}

	public function getBits() {
		return (int)$this->row->log_deleted;
	}

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
			[ $tagSummary, $classes ] = ChangeTags::formatSummaryRow(
				$this->row->ts_tags,
				'revisiondelete',
				$this->list->getContext()
			);
			$content .= " $tagSummary";
			$attribs['class'] = $classes;
		}
		return Html::rawElement( 'li', $attribs, $content );
	}

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
