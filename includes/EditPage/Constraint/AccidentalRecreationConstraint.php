<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\EditPage\EditPageStatus;
use MediaWiki\Logging\DatabaseLogEntry;
use MediaWiki\Logging\LogFormatterFactory;
use MediaWiki\Logging\LogPage;
use MediaWiki\Message\Message;
use MediaWiki\Title\Title;
use Wikimedia\Message\MessageValue;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Make sure user doesn't accidentally recreate a page deleted after they started editing
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class AccidentalRecreationConstraint implements IEditConstraint {

	public function __construct(
		private readonly IConnectionProvider $connectionProvider,
		private readonly LogFormatterFactory $logFormatterFactory,
		private readonly Title $title,
		private readonly bool $allowRecreation,
		private readonly ?string $startTime,
		private readonly ?string $submitButtonLabel,
	) {
	}

	public function checkConstraint(): EditPageStatus {
		if ( $this->allowRecreation ) {
			return EditPageStatus::newGood();
		}
		$deletion = $this->getDeletionSinceLastEdit();
		if ( $deletion ) {
			if ( $this->submitButtonLabel ) {
				$logFormatter = $this->logFormatterFactory->newFromEntry( $deletion );
				$username = $deletion->isDeleted( LogPage::DELETED_USER )
					? MessageValue::new( 'rev-deleted-user' )
					: $deletion->getPerformerIdentity()->getName();
				$commentHtml = $logFormatter->getComment();

				return EditPageStatus::newGood( self::AS_ARTICLE_WAS_DELETED )
					->setOK( false )
					->warning(
						$commentHtml === ''
							? 'edit-constraint-confirmrecreate-noreason'
							: 'edit-constraint-confirmrecreate',
						$username,
						Message::rawParam( $commentHtml ),
						new MessageValue( $this->submitButtonLabel ),
					);
			} else {
				return EditPageStatus::newGood( self::AS_ARTICLE_WAS_DELETED )
					->setOK( false )
					->warning( 'deletedwhileediting' );
			}
		}
		return EditPageStatus::newGood();
	}

	/**
	 * Check if a page was deleted while the user was editing it.
	 * @return ?DatabaseLogEntry The log entry of the deletion, or null if the page wasn't deleted.
	 */
	private function getDeletionSinceLastEdit(): ?DatabaseLogEntry {
		if ( !$this->title->exists() && $this->title->hasDeletedEdits() ) {
			$lastDelete = $this->getLastDelete();
			if ( $lastDelete && $lastDelete->getTimestamp() > $this->startTime ) {
				return $lastDelete;
			}
		}
		return null;
	}

	/**
	 * Get the last log record of this page being deleted, if ever. This is
	 * used to detect whether a delete occurred during editing.
	 */
	private function getLastDelete(): ?DatabaseLogEntry {
		$dbr = $this->connectionProvider->getReplicaDatabase();
		$row = DatabaseLogEntry::newSelectQueryBuilder( $dbr )
			->where( [
				'log_namespace' => $this->title->getNamespace(),
				'log_title' => $this->title->getDBkey(),
				'log_type' => 'delete',
				'log_action' => 'delete',
			] )
			->orderBy( [ 'log_timestamp', 'log_id' ], SelectQueryBuilder::SORT_DESC )
			->caller( __METHOD__ )
			->fetchRow();

		return $row ? DatabaseLogEntry::newFromRow( $row ) : null;
	}

}
