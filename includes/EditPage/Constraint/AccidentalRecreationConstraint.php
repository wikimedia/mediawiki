<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Context\IContextSource;
use MediaWiki\EditPage\EditPageStatus;
use MediaWiki\Logging\LogPage;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Timestamp\TimestampFormat;

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
		private readonly CommentStore $commentStore,
		private readonly IContextSource $context,
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
				$username = $deletion->actor_name;
				$comment = $this->commentStore->getComment( 'log_comment', $deletion )->text;

				return EditPageStatus::newGood( self::AS_ARTICLE_WAS_DELETED )
					->setOK( false )
					->warning(
						$comment === ''
							? 'edit-constraint-confirmrecreate-noreason'
							: 'edit-constraint-confirmrecreate',
						$username,
						new ScalarParam( ParamType::PLAINTEXT, $comment ),
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
	 * @return ?stdClass The deletion object, or null if the page wasn't deleted.
	 */
	private function getDeletionSinceLastEdit(): ?stdClass {
		if ( !$this->title->exists() && $this->title->hasDeletedEdits() ) {
			$lastDelete = $this->getLastDelete();
			if ( $lastDelete ) {
				$deleteTime = wfTimestamp( TimestampFormat::MW, $lastDelete->log_timestamp );
				if ( $deleteTime > $this->startTime ) {
					return $lastDelete;
				}
			}
		}
		return null;
	}

	/**
	 * Get the last log record of this page being deleted, if ever.  This is
	 * used to detect whether a delete occurred during editing.
	 * @return stdClass|null
	 */
	private function getLastDelete(): ?stdClass {
		$dbr = $this->connectionProvider->getReplicaDatabase();
		$commentQuery = $this->commentStore->getJoin( 'log_comment' );
		$data = $dbr->newSelectQueryBuilder()
			->select( [
				'log_type',
				'log_action',
				'log_timestamp',
				'log_namespace',
				'log_title',
				'log_params',
				'log_deleted',
				'actor_name'
			] )
			->from( 'logging' )
			->join( 'actor', null, 'actor_id=log_actor' )
			->where( [
				'log_namespace' => $this->title->getNamespace(),
				'log_title' => $this->title->getDBkey(),
				'log_type' => 'delete',
				'log_action' => 'delete',
			] )
			->orderBy( [ 'log_timestamp', 'log_id' ], SelectQueryBuilder::SORT_DESC )
			->queryInfo( $commentQuery )
			->caller( __METHOD__ )
			->fetchRow();

		// Quick paranoid permission checks...
		if ( $data !== false ) {
			if ( $data->log_deleted & LogPage::DELETED_USER ) {
				$data->actor_name = $this->context->msg( 'rev-deleted-user' )->escaped();
			}

			if ( $data->log_deleted & LogPage::DELETED_COMMENT ) {
				$data->log_comment_text = $this->context->msg( 'rev-deleted-comment' )->escaped();
				$data->log_comment_data = null;
			}
		}

		return $data ?: null;
	}

}
