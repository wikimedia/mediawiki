<?php
/**
 * Contains a class for dealing with database log entries
 *
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
 * @author Niklas Laxström
 * @license GPL-2.0-or-later
 * @since 1.19
 */

namespace MediaWiki\Logging;

use InvalidArgumentException;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;
use stdClass;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * A value class to process existing log entries. In other words, this class caches a log
 * entry from the database and provides an immutable object-oriented representation of it.
 *
 * This class should only be used in context of the LogFormatter class.
 *
 * @since 1.19
 */
class DatabaseLogEntry extends LogEntryBase {

	/**
	 * Returns array of information that is needed for querying
	 * log entries. Array contains the following keys:
	 * tables, fields, conds, options and join_conds
	 *
	 * Since 1.34, log_user and log_user_text have not been present in the
	 * database, but they continue to be available in query results as
	 * aliases.
	 *
	 * @deprecated since 1.41 use ::newSelectQueryBuilder() instead
	 *
	 * @return array
	 */
	public static function getSelectQueryData() {
		$commentQuery = MediaWikiServices::getInstance()->getCommentStore()->getJoin( 'log_comment' );

		$tables = array_merge(
			[
				'logging',
				'logging_actor' => 'actor',
				'user'
			],
			$commentQuery['tables']
		);
		$fields = [
			'log_id', 'log_type', 'log_action', 'log_timestamp',
			'log_namespace', 'log_title', // unused log_page
			'log_params', 'log_deleted',
			'user_id',
			'user_name',
			'log_actor',
			'log_user' => 'logging_actor.actor_user',
			'log_user_text' => 'logging_actor.actor_name'
		] + $commentQuery['fields'];

		$joins = [
			'logging_actor' => [ 'JOIN', 'actor_id=log_actor' ],
			// IPs don't have an entry in user table
			'user' => [ 'LEFT JOIN', 'user_id=logging_actor.actor_user' ],
		] + $commentQuery['joins'];

		return [
			'tables' => $tables,
			'fields' => $fields,
			'conds' => [],
			'options' => [],
			'join_conds' => $joins,
		];
	}

	public static function newSelectQueryBuilder( IReadableDatabase $db ): LoggingSelectQueryBuilder {
		return new LoggingSelectQueryBuilder( $db );
	}

	/**
	 * Constructs new LogEntry from database result row.
	 * Supports rows from both logging and recentchanges table.
	 *
	 * @param stdClass|array $row
	 * @return DatabaseLogEntry
	 */
	public static function newFromRow( $row ) {
		$row = (object)$row;
		if ( isset( $row->rc_logid ) ) {
			return new RCDatabaseLogEntry( $row );
		}

		return new self( $row );
	}

	/**
	 * Loads a LogEntry with the given id from database.
	 *
	 * @param int $id
	 * @param IReadableDatabase $db
	 * @return DatabaseLogEntry|null
	 */
	public static function newFromId( $id, IReadableDatabase $db ) {
		$row = self::newSelectQueryBuilder( $db )
			->where( [ 'log_id' => $id ] )
			->caller( __METHOD__ )->fetchRow();
		if ( !$row ) {
			return null;
		}
		return self::newFromRow( $row );
	}

	/** @var stdClass Database result row. */
	protected $row;

	/** @var UserIdentity */
	protected $performer;

	/** @var array|null Parameters for log entry */
	protected $params;

	/** @var int A rev id associated to the log entry */
	protected $revId = null;

	/** @var bool Whether the parameters for this log entry are stored in new or old format. */
	protected $legacy;

	protected function __construct( $row ) {
		$this->row = $row;
	}

	/**
	 * Returns the unique database id.
	 *
	 * @return int
	 */
	public function getId() {
		return (int)( $this->row->log_id ?? 0 );
	}

	/**
	 * Returns whatever is stored in the database field (typically a serialized
	 * associative array but very old entries might have different formats).
	 *
	 * @return string
	 */
	protected function getRawParameters() {
		return $this->row->log_params;
	}

	/** @inheritDoc */
	public function isLegacy() {
		// This extracts the property
		$this->getParameters();
		return $this->legacy;
	}

	/** @inheritDoc */
	public function getType() {
		return $this->row->log_type;
	}

	/** @inheritDoc */
	public function getSubtype() {
		return $this->row->log_action;
	}

	/** @inheritDoc */
	public function getParameters() {
		if ( $this->params === null ) {
			$blob = $this->getRawParameters();
			AtEase::suppressWarnings();
			$params = LogEntryBase::extractParams( $blob );
			AtEase::restoreWarnings();
			if ( $params !== false ) {
				$this->params = $params;
				$this->legacy = false;
			} else {
				$this->params = LogPage::extractParams( $blob );
				$this->legacy = true;
			}

			if ( isset( $this->params['associated_rev_id'] ) ) {
				$this->revId = $this->params['associated_rev_id'];
				unset( $this->params['associated_rev_id'] );
			}
		}

		return $this->params;
	}

	/** @inheritDoc */
	public function getAssociatedRevId() {
		// This extracts the property
		$this->getParameters();
		return $this->revId;
	}

	/** @inheritDoc */
	public function getPerformerIdentity(): UserIdentity {
		if ( !$this->performer ) {
			$actorStore = MediaWikiServices::getInstance()->getActorStore();
			try {
				$this->performer = $actorStore->newActorFromRowFields(
					$this->row->user_id ?? 0,
					$this->row->log_user_text ?? null,
					$this->row->log_actor ?? null
				);
			} catch ( InvalidArgumentException $e ) {
				LoggerFactory::getInstance( 'logentry' )->warning(
					'Failed to instantiate log entry performer', [
						'exception' => $e,
						'log_id' => $this->getId()
					]
				);
				$this->performer = $actorStore->getUnknownActor();
			}
		}
		return $this->performer;
	}

	/** @inheritDoc */
	public function getTarget() {
		$namespace = $this->row->log_namespace;
		$page = $this->row->log_title;
		return MediaWikiServices::getInstance()->getTitleFactory()->makeTitle( $namespace, $page );
	}

	/** @inheritDoc */
	public function getTimestamp() {
		return wfTimestamp( TS_MW, $this->row->log_timestamp );
	}

	/** @inheritDoc */
	public function getComment() {
		return MediaWikiServices::getInstance()->getCommentStore()
			->getComment( 'log_comment', $this->row )->text;
	}

	/** @inheritDoc */
	public function getDeleted() {
		return $this->row->log_deleted;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( DatabaseLogEntry::class, 'DatabaseLogEntry' );
